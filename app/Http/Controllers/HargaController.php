<?php

namespace App\Http\Controllers;

use App\Models\PaketLowongan;
use Illuminate\Http\Request;

class HargaController extends Controller
{
    public function index()
    {
        $koin = collect([
            (object)['id' => 1, 'nama' => 'Pasang Lowongan Bronze', 'harga' => 100],
            (object)['id' => 2, 'nama' => 'Pasang Lowongan Silver', 'harga' => 200],
            (object)['id' => 3, 'nama' => 'Pasang Lowongan Gold', 'harga' => 300],
            (object)['id' => 4, 'nama' => 'Boost Lowongan', 'harga' => 300],
            (object)['id' => 5, 'nama' => 'Berlangganan', 'harga' => 1000],
        ]);

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
        $koin = collect([
            (object)['id' => 1, 'nama' => 'Pasang Lowongan Bronze', 'harga' => 100],
            (object)['id' => 2, 'nama' => 'Pasang Lowongan Silver', 'harga' => 200],
            (object)['id' => 3, 'nama' => 'Pasang Lowongan Gold', 'harga' => 300],
            (object)['id' => 4, 'nama' => 'Boost Lowongan', 'harga' => 300],
            (object)['id' => 5, 'nama' => 'Berlangganan', 'harga' => 1000],
        ]);

        return view('finance.paket-harga.edit-koin', [
            'title' => 'Edit Harga Koin',
            'koin'  => $koin,
        ]);
    }

    public function update_koin(Request $request)
    {
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
        return redirect()->route('finance.paket-harga')->with('success', 'Harga pembayaran berhasil diperbarui.');
    }
}
