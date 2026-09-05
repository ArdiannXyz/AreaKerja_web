<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use App\Models\DaftarBank;
use App\Models\PaketLowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentApiController extends Controller
{
    /**
     * Get list of Coin Top-up Packages.
     */
    public function koinPackages()
    {
        $packages = [
            [
                'id'          => 1,
                'name'        => 'Paket Pemula',
                'coins'       => 10,
                'bonus_coins' => 0,
                'price'       => 10000,
                'description' => 'Cocok untuk mencoba pasang lowongan kilat',
            ],
            [
                'id'          => 2,
                'name'        => 'Paket Standar',
                'coins'       => 50,
                'bonus_coins' => 5,
                'price'       => 50000,
                'description' => 'Paket hemat untuk UMKM & Perusahaan berkembang',
            ],
            [
                'id'          => 3,
                'name'        => 'Paket Populer',
                'coins'       => 100,
                'bonus_coins' => 15,
                'price'       => 100000,
                'description' => 'Pilihan favorit untuk rekrutmen aktif bulanan',
            ],
            [
                'id'          => 4,
                'name'        => 'Paket Korporat',
                'coins'       => 500,
                'bonus_coins' => 100,
                'price'       => 500000,
                'description' => 'Paket terlengkap untuk rekrutmen massal & Talent Hunter',
            ],
        ];

        return response()->json([
            'success' => true,
            'data'    => $packages,
        ]);
    }

    /**
     * Get list of Company Subscription / Job Listing Packages.
     */
    public function subscriptionPackages()
    {
        $packages = PaketLowongan::all();

        if ($packages->isEmpty()) {
            $packages = [
                [
                    'id'            => 1,
                    'nama'          => 'Paket Basic (30 Hari)',
                    'harga_koin'    => 10,
                    'batas_listing' => 1,
                    'deskripsi'     => '1 Lowongan aktif selama 30 hari',
                    'benefit'       => 'Dukungan web & mobile feed, Filter pelamar',
                ],
                [
                    'id'            => 2,
                    'nama'          => 'Paket Premium Booster (30 Hari)',
                    'harga_koin'    => 50,
                    'batas_listing' => 3,
                    'deskripsi'     => '3 Lowongan aktif + Pin to top rekomendasi',
                    'benefit'       => 'Prioritas di halaman depan, Notifikasi pelamar, Akses kontak pelamar',
                ],
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $packages,
        ]);
    }

    /**
     * Get list of available payment methods / banks.
     */
    public function paymentChannels()
    {
        $banks = DaftarBank::all();

        if ($banks->isEmpty()) {
            $banks = [
                [
                    'id'            => 1,
                    'nama_bank'     => 'BCA Virtual Account',
                    'no_rekening'   => '1234567890',
                    'atas_nama'     => 'PT Area Kerja Indonesia',
                    'kode_bank'     => '014',
                ],
                [
                    'id'            => 2,
                    'nama_bank'     => 'Bank Mandiri',
                    'no_rekening'   => '1400012345678',
                    'atas_nama'     => 'PT Area Kerja Indonesia',
                    'kode_bank'     => '008',
                ],
                [
                    'id'            => 3,
                    'nama_bank'     => 'BRI Virtual Account',
                    'no_rekening'   => '00123456789012',
                    'atas_nama'     => 'PT Area Kerja Indonesia',
                    'kode_bank'     => '002',
                ],
                [
                    'id'            => 4,
                    'nama_bank'     => 'QRIS / E-Wallet (GoPay, OVO, Dana)',
                    'no_rekening'   => 'QRIS-AREAKERJA-01',
                    'atas_nama'     => 'AreaKerja Digital',
                    'kode_bank'     => 'QRIS',
                ],
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $banks,
        ]);
    }

    /**
     * Create Checkout Transaction (Top Up Koin or Subscription).
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'pesanan'        => 'required|string', // e.g. "Top Up 100 Koin"
            'total'          => 'required|numeric|min:1000',
            'daftar_bank_id' => 'nullable|integer',
            'sumberDana'     => 'nullable|string',
        ]);

        $user = $request->user();
        $refNumber = 'AK-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $transaction = CatatanCash::create([
            'user_id'        => $user->id,
            'no_referensi'   => $refNumber,
            'daftar_bank_id' => $request->daftar_bank_id ?? 1,
            'pesanan'        => $request->pesanan,
            'dari'           => $user->username,
            'sumberDana'     => $request->sumberDana ?? 'Transfer Bank / VA',
            'total'          => $request->total,
            'status'         => 'pending',
            'expired_at'     => now()->addHours(24),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat. Silakan lakukan pembayaran sebelum waktu kedaluwarsa.',
            'data'    => [
                'transaction'          => $transaction,
                'payment_instructions' => [
                    'no_referensi' => $refNumber,
                    'total'        => (int)$request->total,
                    'status'       => 'pending',
                    'expired_at'   => $transaction->expired_at->toIso8601String(),
                ],
            ],
        ], 201);
    }

    /**
     * Get Transaction / Payment History.
     */
    public function history(Request $request)
    {
        $user = $request->user();

        $cashHistory = CatatanCash::where('user_id', $user->id)
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $cashHistory,
        ]);
    }

    /**
     * Check single payment status.
     */
    public function showPayment(Request $request, $id)
    {
        $user = $request->user();

        $transaction = CatatanCash::where('user_id', $user->id)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('no_referensi', $id);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $transaction,
        ]);
    }

    /**
     * Webhook Callback from Payment Gateway (Public/No Auth).
     */
    public function paymentCallback(Request $request)
    {
        $refNumber = $request->input('no_referensi') ?? $request->input('order_id');
        $status = strtolower($request->input('status') ?? $request->input('transaction_status') ?? 'settlement');

        if (!$refNumber) {
            return response()->json(['success' => false, 'message' => 'Order ID tidak ditemukan.'], 400);
        }

        $transaction = CatatanCash::where('no_referensi', $refNumber)->first();
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Data transaksi tidak ditemukan.'], 404);
        }

        if (in_array($status, ['success', 'settlement', 'paid'])) {
            $transaction->update(['status' => 'diterima']);

            // Auto-credit coins if topup
            $coinsToAdd = $transaction->harga_pembayaran->jumlah_koin ?? 0;
            if ($coinsToAdd > 0 && $transaction->user && $transaction->user->perusahaan) {
                $perusahaan = $transaction->user->perusahaan;
                $perusahaan->increment('koin_perusahaan', $coinsToAdd);

                CatatanKoin::create([
                    'user_id'     => $transaction->user_id,
                    'jumlah_koin' => $coinsToAdd,
                    'tipe'        => 'masuk',
                    'keterangan'  => 'Top Up ' . $coinsToAdd . ' Koin (' . $transaction->no_referensi . ')',
                ]);
            }
        } elseif (in_array($status, ['expired', 'expire', 'failed', 'cancel'])) {
            $transaction->update(['status' => 'Gagal']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Callback diproses.',
            'status'  => $transaction->status,
        ]);
    }
}
