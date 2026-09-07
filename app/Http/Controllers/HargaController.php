<?php

namespace App\Http\Controllers;

use App\Models\PaketLowongan;
use Illuminate\Http\Request;

class HargaController extends Controller
{
    public function index()
    {
        // Ambil data harga koin dari paket lowongan di database
        $pakets = PaketLowongan::orderBy('harga_koin')->get();

        $koin = $pakets->map(fn($p) => (object)[
            'id'    => $p->id,
            'nama'  => $p->nama,
            'harga' => $p->harga_koin,
        ]);

        // Harga pembayaran (top-up) — jika ada tabel harga_pembayarans bisa diganti
        $pembayaran = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000],
        ]);

        return view('finance.paket-harga.paket-harga', [
            'title'      => 'Paket Harga',
            'koin'       => $koin,
            'pembayaran' => $pembayaran,
        ]);
    }

    public function edit_koin()
    {
        $pakets = PaketLowongan::orderBy('harga_koin')->get();

        $koin = $pakets->map(fn($p) => (object)[
            'id'    => $p->id,
            'nama'  => $p->nama,
            'harga' => $p->harga_koin,
        ]);

        return view('finance.paket-harga.edit-koin', [
            'title' => 'Edit Harga Koin',
            'koin'  => $koin,
        ]);
    }

    public function update_koin(Request $request)
    {
        $request->validate([
            'pakets'         => 'required|array',
            'pakets.*.id'    => 'required|exists:paket_lowongans,id',
            'pakets.*.harga' => 'required|integer|min:0',
        ]);

        foreach ($request->pakets as $item) {
            PaketLowongan::where('id', $item['id'])->update([
                'harga_koin' => $item['harga'],
            ]);
        }

        return redirect()->route('finance.paket-harga')->with('success', 'Harga koin berhasil diperbarui.');
    }

    public function edit_pembayaran()
    {
        $pembayaran = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000],
        ]);

        return view('finance.paket-harga.edit-harga', [
            'title'      => 'Edit Harga Pembayaran',
            'pembayaran' => $pembayaran,
        ]);
    }

    public function update_pembayaran(Request $request)
    {
        // Harga pembayaran (top-up) saat ini hardcoded karena tabel harga_pembayarans
        // sudah di-drop oleh migration consolidate. Jika tabel sudah dibuat ulang,
        // ganti dengan logika update ke database.
        return redirect()->route('finance.paket-harga')->with('info', 'Fitur update harga pembayaran memerlukan tabel harga_pembayarans. Silakan hubungi developer.');
    }
}
