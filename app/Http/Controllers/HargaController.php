<?php

namespace App\Http\Controllers;

use App\Models\PaketLowongan;
use Illuminate\Http\Request;

class HargaController extends Controller
{
    public function index()
    {
        $koin = SuperAdminController::getFinanceKoinList();

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
        $koin = SuperAdminController::getFinanceKoinList();

        return view('finance.paket-harga.edit-koin', [
            'title' => 'Edit Harga Koin',
            'koin'  => $koin,
        ]);
    }

    public function update_koin(Request $request)
    {
        if ($request->has('keys') && $request->has('hargas')) {
            $keys = $request->input('keys');
            $hargas = $request->input('hargas');

            foreach ($keys as $index => $key) {
                if (isset($hargas[$index])) {
                    \App\Models\SiteSetting::set($key, (string) max(0, (int) $hargas[$index]));
                }
            }
        } elseif ($request->has('id') && $request->has('harga')) {
            $defaultKeys = [
                1 => 'harga_koin_buka_cv',
                2 => 'harga_koin_iklan_3_hari',
                3 => 'harga_koin_iklan_7_hari',
                4 => 'harga_koin_iklan_14_hari',
                5 => 'harga_koin_iklan_30_hari',
                6 => 'harga_koin_boost_lowongan',
                7 => 'harga_koin_berlangganan',
            ];
            foreach ($request->id as $index => $id) {
                if (isset($defaultKeys[$id]) && isset($request->harga[$index])) {
                    \App\Models\SiteSetting::set($defaultKeys[$id], (string) max(0, (int) $request->harga[$index]));
                }
            }
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
