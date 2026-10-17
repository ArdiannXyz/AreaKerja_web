<?php

namespace App\Http\Controllers;

use App\Models\CatatanCash;
use App\Models\DaftarBank;
use App\Models\Perusahaan;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadBuktiTransferRequest;

class CatatanCashController extends Controller
{
    // simpan transaksi baru & buat invoice Xendit
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
        if (!$bank) {
            $bank = DaftarBank::first();
        }

        $hargaPembayarans = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000],
        ]);

        $paketId = $request->harga_pembayaran_id ?? $request->paket_id ?? $request->paket;
        $paket = $hargaPembayarans->firstWhere('id', (int) $paketId);

        if ($paket) {
            $nominal = $paket->harga;
            $pesanan = $paket->nama;
        } else {
            $nominal = $request->total ?? $request->harga ?? 100000;
            $pesanan = $request->pesanan ?? "Top Up Koin Area Kerja";
        }

        $sumberDana = ($bank && strtolower($bank->nama_bank) === 'qris')
            ? 'Qris'
            : ($bank ? 'Transfer ' . $bank->nama_bank : 'Xendit Payment Gateway');

        $transaksi = CatatanCash::create([
            'user_id'        => $user->id,
            'daftar_bank_id' => $bank?->id,
            'no_referensi'   => 'AK-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'pesanan'        => $pesanan,
            'dari'           => $user->perusahaan?->nama_perusahaan ?? $user->username,
            'sumberDana'     => $sumberDana,
            'total'          => $nominal,
            'status'         => 'pending',
            'expired_at'     => now()->addHours(24),
        ]);

        // Buat Invoice di Xendit Payment Gateway
        $xenditService = app(XenditService::class);
        $invoiceResult = $xenditService->createInvoice(
            $transaksi,
            route('catatan_cash.show', $transaksi->id),
            route('catatan_cash.show', $transaksi->id)
        );

        $transaksi->refresh();
        $targetUrl = route('catatan_cash.show', $transaksi->id);

        return response()->json([
            'success'      => true,
            'message'      => 'Tagihan berhasil dibuat. Silakan selesaikan pembayaran.',
            'redirect_url' => $targetUrl,
            'checkout_url' => $transaksi->checkout_url,
            'detail_url'   => $targetUrl,
            'transaksi_id' => $transaksi->id,
        ]);
    }

    // Cek status transaksi real-time (AJAX polling)
    public function checkStatus($id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        if ($transaksi->status === 'pending' && $transaksi->xendit_id) {
            $transaksi = app(XenditService::class)->syncInvoiceStatus($transaksi);
        }

        return response()->json([
            'status'     => $transaksi->status,
            'is_paid'    => in_array($transaksi->status, ['diterima', 'lunas', 'selesai']),
            'is_expired' => $transaksi->status === 'expired',
            'paid_at'    => $transaksi->paid_at ? $transaksi->paid_at->translatedFormat('d F Y H:i:s') : null,
        ]);
    }

    // halaman detail transaksi
    public function show($id)
    {
        $transaksi = CatatanCash::with('bank')->findOrFail($id);

        // Jika transaksi pending dan punya invoice xendit, cek sinkronisasi status terbaru
        if ($transaksi->status === 'pending' && $transaksi->xendit_id) {
            $transaksi = app(XenditService::class)->syncInvoiceStatus($transaksi);
        }

        if (
            $transaksi->status === 'pending' &&
            $transaksi->expired_at &&
            $transaksi->expired_at <= now()
        ) {
            $transaksi->update([
                'status' => 'expired'
            ]);
            $transaksi->refresh();
        }

        return view('perusahaan.transaksi-koin', compact('transaksi'));
    }

    // Redirect langsung ke URL pembayaran Xendit
    public function payNow($id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        if (!$transaksi->checkout_url || $transaksi->status === 'expired') {
            app(XenditService::class)->createInvoice($transaksi);
            $transaksi->refresh();
        }

        if ($transaksi->checkout_url) {
            return redirect()->away($transaksi->checkout_url);
        }

        return redirect()->route('catatan_cash.show', $transaksi->id)
            ->with('error', 'Gagal memuat halaman pembayaran Xendit.');
    }


    // upload bukti transaksi
    public function uploadBukti(UploadBuktiTransferRequest $request, $id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        $path = $request->file('bukti')->store('bukti-transfer', 'public');

        $transaksi->update([
            'bukti'  => $path,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('catatan_cash.show', $transaksi->id)
            ->with('success', 'Bukti transfer berhasil diupload.');
    }

    // update status transaksi (dipakai dari panel Finance)
    public function updateStatus(Request $request, $id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,menunggu_verifikasi,diterima,ditolak,expired',
        ]);

        $transaksi->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
