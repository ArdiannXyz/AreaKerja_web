<?php

namespace App\Http\Controllers;

use App\Models\CatatanKoin;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PembeliKandidatController extends Controller
{
    public function beli(Request $request)
    {
        $request->validate([
            'pelamar_id'             => 'required|exists:pelamars,id',
            'lowongan_perusahaan_id' => 'required|exists:lowongan_perusahaans,id'
        ]);

        $user = auth()->user();
        $perusahaan = $user->perusahaan;

        if (!$perusahaan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Perusahaan tidak ditemukan'
            ], 403);
        }

        $pelamar = Pelamar::findOrFail($request->pelamar_id);
        $lowongan = LowonganPerusahaan::findOrFail($request->lowongan_perusahaan_id);

        if ($lowongan->perusahaan_id != $perusahaan->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lowongan tidak ditemukan'
            ], 403);
        }

        $harga = 100; // 100 koin per pembelian kandidat

        if ($perusahaan->koin_perusahaan < $harga) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Koin perusahaan tidak mencukupi'
            ], 400);
        }

        $perusahaan->decrement('koin_perusahaan', $harga);

        PelamarLowongan::updateOrCreate(
            [
                'pelamar_id'  => $pelamar->id,
                'lowongan_id' => $lowongan->id,
            ],
            [
                'status' => 'diterima',
            ]
        );

        CatatanKoin::create([
            'user_id'      => $user->id,
            'no_referensi' => 'KANDIDAT-' . strtoupper(Str::random(8)),
            'pesanan'      => 'Pembelian Kandidat: ' . $pelamar->nama_pelamar,
            'dari'         => $perusahaan->nama_perusahaan,
            'sumber_dana'  => 'Saldo Koin Perusahaan',
            'total'        => '-' . $harga,
        ]);

        if ($pelamar->user_id) {
            Notifikasi::create([
                'user_id'       => $pelamar->user_id,
                'perusahaan_id' => $perusahaan->id,
                'judul'         => 'Penawaran Rekrutmen Baru',
                'pesan'         => $perusahaan->nama_perusahaan . ' telah merekrut Anda untuk lowongan ' . $lowongan->nama,
                'is_read'       => false,
                'expired_at'    => now()->addDays(7),
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Kandidat berhasil direkrut!'
        ]);
    }

    public function terima($id)
    {
        $pl = PelamarLowongan::findOrFail($id);
        $pl->update(['status' => 'diterima']);
        return back()->with('success', 'Kandidat berhasil diterima.');
    }

    public function tolak($id)
    {
        $pl = PelamarLowongan::findOrFail($id);
        $pl->update(['status' => 'ditolak']);
        return back()->with('success', 'Kandidat ditolak.');
    }
}
