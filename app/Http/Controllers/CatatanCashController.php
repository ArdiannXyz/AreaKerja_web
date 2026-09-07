<?php

namespace App\Http\Controllers;

use App\Models\CatatanCash;
use App\Models\DaftarBank;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CatatanCashController extends Controller
{
    // simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'daftar_bank_id' => 'required|exists:daftar_bank,id',
        ]);

        $user = auth()->user();
        $bank = DaftarBank::findOrFail($request->daftar_bank_id);

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

        $sumberDana = (strtolower($bank->nama_bank) === 'qris')
            ? 'Qris'
            : 'Transfer Bank';

        $transaksi = CatatanCash::create([
            'user_id'        => $user->id,
            'daftar_bank_id' => $bank->id,
            'no_referensi'   => strtoupper(Str::random(10)),
            'pesanan'        => $pesanan,
            'dari'           => $user->username,
            'sumberDana'     => $sumberDana,
            'total'          => $nominal,
            'status'         => 'pending',
            'expired_at'     => now()->addHours(24),
        ]);

        return response()->json([
            'success'      => true,
            'redirect_url' => route('catatan_cash.show', $transaksi->id)
        ]);
    }

    // halaman detail transaksi
    public function show($id)
    {
        $transaksi = CatatanCash::with('bank')->findOrFail($id);
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

    // upload bukti transaksi
    public function uploadBukti(Request $request, $id)
    {
        $transaksi = CatatanCash::findOrFail($id);

        $request->validate([
            'bukti' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

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
