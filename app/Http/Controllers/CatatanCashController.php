<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadBuktiTransferRequest;
use App\Models\CatatanCash;
use App\Models\DaftarBank;
use App\Services\PaymentSettingService;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatatanCashController extends Controller
{
    /**
     * Simpan transaksi baru & inisiasi invoice jika gateway aktif.
     */
    public function store(Request $request)
    {
        $request->validate([
            'daftar_bank_id' => 'nullable|exists:daftar_bank,id',
        ]);

        $user = auth()->user();
        $bank = null;
        if ($request->filled('daftar_bank_id')) {
            $bank = DaftarBank::find($request->daftar_bank_id);
        }
        if (! $bank) {
            $bank = DaftarBank::first();
        }

        $hargaPembayarans = collect([
            (object) ['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000],
            (object) ['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000],
            (object) ['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000],
        ]);

        $paketId = $request->harga_pembayaran_id ?? $request->paket_id ?? $request->paket;
        $paket = $hargaPembayarans->firstWhere('id', (int) $paketId);

        if ($paket) {
            $nominal = $paket->harga;
            $pesanan = $paket->nama;
        } else {
            $nominal = $request->total ?? $request->harga ?? 100000;
            $pesanan = $request->pesanan ?? 'Top Up Koin Area Kerja';
        }

        $sumberDana = ($bank && strtolower($bank->nama_bank) === 'qris')
            ? 'Qris'
            : ($bank ? 'Transfer '.$bank->nama_bank : 'QRIS & Virtual Account');

        $transaksi = CatatanCash::create([
            'user_id' => $user->id,
            'daftar_bank_id' => $bank?->id,
            'no_referensi' => 'AK-'.date('Ymd').'-'.strtoupper(Str::random(6)),
            'pesanan' => $pesanan,
            'dari' => $user->perusahaan?->nama_perusahaan ?? $user->username,
            'sumberDana' => $sumberDana,
            'total' => $nominal,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        // Inisiasi Invoice di Xendit Payment Gateway jika gateway otomatis aktif
        if (PaymentSettingService::isXenditEnabled()) {
            try {
                $xenditService = app(XenditService::class);
                $xenditService->createInvoice(
                    $transaksi,
                    route('catatan_cash.show', $transaksi->id),
                    route('catatan_cash.show', $transaksi->id)
                );
            } catch (\Throwable $e) {
                // Jangan gagalkan transaksi jika gateway eksternal sedang mengalami limitasi/offline
            }
        }

        $transaksi->refresh();
        $targetUrl = route('catatan_cash.show', $transaksi->id);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dibuat. Silakan selesaikan pembayaran.',
            'redirect_url' => $targetUrl,
            'checkout_url' => $transaksi->checkout_url,
            'detail_url' => $targetUrl,
            'transaksi_id' => $transaksi->id,
        ]);
    }

    /**
     * Halaman Terpusat Riwayat Tagihan User (/tagihan).
     */
    public function billsIndex(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'pending');

        $baseQuery = CatatanCash::where('user_id', $user->id)->orderBy('created_at', 'desc');

        $pendingCount = (clone $baseQuery)->whereIn('status', ['pending', 'menunggu_verifikasi'])->count();
        $paidCount = (clone $baseQuery)->whereIn('status', ['diterima', 'lunas', 'selesai', 'paid', 'settled'])->count();
        $cancelledCount = (clone $baseQuery)->whereIn('status', ['cancelled', 'dibatalkan', 'batal', 'expired', 'ditolak', 'failed'])->count();

        $query = clone $baseQuery;
        if ($tab === 'lunas') {
            $query->whereIn('status', ['diterima', 'lunas', 'selesai', 'paid', 'settled']);
        } elseif ($tab === 'dibatalkan') {
            $query->whereIn('status', ['cancelled', 'dibatalkan', 'batal', 'expired', 'ditolak', 'failed']);
        } else {
            $tab = 'pending';
            $query->whereIn('status', ['pending', 'menunggu_verifikasi']);
        }

        $tagihans = $query->paginate(10)->withQueryString();

        return view('tagihan.index', compact('tagihans', 'tab', 'pendingCount', 'paidCount', 'cancelledCount'));
    }

    /**
     * Batalkan tagihan yang masih berstatus pending.
     */
    public function cancelBill(Request $request, $id)
    {
        $user = auth()->user();
        $transaksi = CatatanCash::where('user_id', $user->id)->findOrFail($id);

        if (! in_array(strtolower($transaksi->status), ['pending', 'menunggu_verifikasi'])) {
            return redirect()->back()->with('error', 'Tagihan ini tidak dapat dibatalkan.');
        }

        $transaksi->update([
            'status' => 'cancelled',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tagihan #'.$transaksi->no_referensi.' berhasil dibatalkan.',
            ]);
        }

        return redirect()->route('tenant.bills.index', ['tab' => 'dibatalkan'])
            ->with('success', 'Tagihan #'.$transaksi->no_referensi.' berhasil dibatalkan.');
    }

    /**
     * Cek status transaksi real-time (AJAX polling by reference_id / id).
     */
    public function statusByReference($referenceId)
    {
        $transaksi = CatatanCash::where('no_referensi', $referenceId)
            ->orWhere('id', $referenceId)
            ->first();

        if (! $transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        if ($transaksi->status === 'pending' && $transaksi->xendit_id && PaymentSettingService::isXenditEnabled()) {
            try {
                $transaksi = app(XenditService::class)->syncInvoiceStatus($transaksi);
            } catch (\Throwable $e) {
            }
        }

        $isPaid = in_array(strtolower($transaksi->status), ['diterima', 'lunas', 'selesai', 'paid', 'settled']);
        $isExpired = $transaksi->status === 'expired' || ($transaksi->expired_at && $transaksi->expired_at->isPast());
        $isCancelled = in_array(strtolower($transaksi->status), ['cancelled', 'dibatalkan', 'batal', 'failed']);

        return response()->json([
            'success' => true,
            'reference_id' => $transaksi->no_referensi,
            'status' => $transaksi->status,
            'is_paid' => $isPaid,
            'is_expired' => $isExpired,
            'is_cancelled' => $isCancelled,
            'paid_at' => $transaksi->paid_at ? $transaksi->paid_at->translatedFormat('d F Y H:i:s') : null,
            'redirect_url' => route('catatan_cash.show', $transaksi->id),
        ]);
    }

    /**
     * Cek status transaksi real-time (AJAX polling by ID).
     */
    public function checkStatus($id)
    {
        return $this->statusByReference($id);
    }

    /**
     * Halaman detail transaksi / Checkout In-App UI.
     */
    public function show($id)
    {
        $transaksi = CatatanCash::with('bank')->findOrFail($id);

        if ($transaksi->status === 'pending' && $transaksi->xendit_id && PaymentSettingService::isXenditEnabled()) {
            try {
                $transaksi = app(XenditService::class)->syncInvoiceStatus($transaksi);
            } catch (\Throwable $e) {
            }
        }

        if (
            $transaksi->status === 'pending' &&
            $transaksi->expired_at &&
            $transaksi->expired_at <= now()
        ) {
            $transaksi->update([
                'status' => 'expired',
            ]);
            $transaksi->refresh();
        }

        return view('perusahaan.transaksi-koin', compact('transaksi'));
    }

    /**
     * Bayar sekarang: arahkan langsung ke halaman checkout in-app.
     */
    public function payNow($id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        return redirect()->route('catatan_cash.show', $transaksi->id);
    }

    /**
     * Upload bukti transfer manual.
     */
    public function uploadBukti(UploadBuktiTransferRequest $request, $id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        $path = $request->file('bukti')->store('bukti-transfer', 'public');

        $transaksi->update([
            'bukti' => $path,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('catatan_cash.show', $transaksi->id)
            ->with('success', 'Bukti transfer berhasil diunggah. Admin akan segera memverifikasi pembayaran Anda.');
    }

    /**
     * Update status transaksi (dipakai dari panel Finance).
     */
    public function updateStatus(Request $request, $id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,menunggu_verifikasi,diterima,ditolak,expired,cancelled',
        ]);

        $transaksi->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
