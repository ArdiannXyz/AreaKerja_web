<?php

namespace App\Http\Controllers;

use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\PembeliKandidat;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class PembeliKandidatController extends Controller
{
    public function beli(Request $request)
    {
        $request->validate([
            'pelamar_id' => 'required|exists:pelamars,id',
            'lowongan_perusahaan_id' => 'required|exists:lowongan_perusahaans,id'
        ]);

        $user = auth()->user();
        $perusahaan = $user->perusahaan;

        if (!$perusahaan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perusahaan tidak ditemukan'
            ], 403);
        }

        $pelamar = Pelamar::findOrFail($request->pelamar_id);
        $lowongan = LowonganPerusahaan::findOrFail($request->lowongan_perusahaan_id);

        if ($lowongan->perusahaan_id != $perusahaan->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lowongan tidak ditemukan'
            ], 403);
        }

        $hargaObj = \App\Models\Hargakoin::where('nama', 'Beli Kandidat')->firstOrFail();
        $harga = $hargaObj->harga;

        // Hapus riwayat lama dengan status ditolak
        PembeliKandidat::where('pelamar_id', $pelamar->id)
            ->where('lowongan_perusahaan_id', $lowongan->id)
            ->where('status', 'ditolak')
            ->delete();

        $exists = PembeliKandidat::where('pelamar_id', $pelamar->id)
            ->where('lowongan_perusahaan_id', $lowongan->id)
            ->whereIn('status', ['pending', 'diterima'])
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kandidat ini sudah dibeli untuk lowongan ' . $lowongan->nama,
            ]);
        }

        if ($perusahaan->koin_perusahaan < $harga) {
            return response()->json([
                'status' => 'error',
                'message' => 'Koin tidak cukup'
            ]);
        }

        // Kurangi koin
        $perusahaan->decrement('koin_perusahaan', $harga);

        $noReferensi = 'TRX-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

        $pembelian = $pelamar->pembelianKandidat()->create([
            'no_referensi' => $noReferensi,
            'lowongan_perusahaan_id' => $lowongan->id,
            'status' => 'pending',
        ]);

        // Catatan koin
        $user->catatanKoins()->create([
            'no_referensi' => $noReferensi,
            'pesanan' => 'Pembelian Kandidat: ' . ($pelamar->nama_pelamar ?? 'kandidat'),
            'dari' => $perusahaan->nama_perusahaan,
            'sumber_dana' => 'Saldo Koin Perusahaan',
            'total' => '-' . $harga,
        ]);

        // NOTIFIKASI UNTUK PELAMAR
        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'perusahaan_id' => $perusahaan->id,
            'judul'   => 'Kamu Telah Dibeli Perusahaan',
            'pesan'   => 'Selamat Anda Telah Direkrut Oleh Perusahaan ' . $perusahaan->nama_perusahaan .
                ' Dan Akan Ditempatkan Di Bagian ' . $lowongan->nama .
                '. Harap Memeriksa Status Tawaranmu.',
            'is_read' => false,
            'expired_at' => now()->addDays(7),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pembelian berhasil',
            'pembelian' => $pembelian
        ]);
    }


    public function tawaran()
    {
        $pelamar = auth()->user()->pelamar ?? null;
        if (!$pelamar) abort(403);

        $tawaran = PembeliKandidat::with(['lowonganPerusahaan', 'catatanKoin', 'pelamar'])
            ->where('pelamar_id', $pelamar->id)
            ->where('status', 'pending')
            ->get();

        return view('kandidat.rekrut-saya', [
            'tawaran' => $tawaran
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->merge(['status' => strtolower($request->status ?? '')]);
        $request->validate([
            'status' => 'required|in:diterima,ditolak'
        ]);

        $pembelian = PembeliKandidat::FindOrFail($id);

        $pelamar = auth()->user()->pelamar ?? null;
        if (!$pelamar || $pembelian->pelamar_id != $pelamar->id) {
            abort(403);
        }

        $pembelian->update([
            'status' => $request->status,
            'alasan_penolakan' => $request->status == 'ditolak' ? $request->alasan_penolakan : null
        ]);

        $perusahaanUserId = $pembelian->lowonganPerusahaan->perusahaan->user_id ?? null;

        if ($perusahaanUserId) {
            $judul = $request->status === 'diterima'  ? 'Kandidat Menerima Tawaran'
                : 'Kandidat Menolak Tawaran';

            $pesan = $request->status === 'diterima'  ? "Kandidat {$pelamar->nama_pelamar} menerima tawaran pada lowongan {$pembelian->lowonganPerusahaan->nama}."
                : "Kandidat {$pelamar->nama_pelamar} menolak tawaran pada lowongan {$pembelian->lowonganPerusahaan->nama}. "
                . "Alasan: " . ($request->alasan_penolakan ?? '-');

            Notifikasi::create([
                'user_id' => $perusahaanUserId,
                'judul' => $judul,
                'pesan' => $pesan,
                'expired_at' => now()->addDays(7),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status berhasil diperbarui'
            ]);
        }

        return redirect()->back()->with('success', 'Status berhasil diperbarui');
    }

    public function detailTawaran(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        $pelamar = auth()->user()->pelamar ?? null;
        if (!$pelamar) abort(403);

        // Ambil tawaran berdasarkan pelamar & lowongan
        $tawaran = PembeliKandidat::with(['lowonganPerusahaan.perusahaan'])
            ->where('pelamar_id', $pelamar->id)
            ->where('lowongan_perusahaan_id', $lowongan->id)
            ->firstOrFail();

        // Lowongan lain yg pernah dibeli perusahaan itu
        $lowonganLain = PembeliKandidat::with('lowonganPerusahaan')
            ->where('pelamar_id', $pelamar->id)
            ->where('id', '!=', $tawaran->id)
            ->latest()
            ->take(3)
            ->get();

        // Tambahan keamanan → pastikan lowongan ini milik perusahaan yang ada di URL
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }

        return view('kandidat.detail_rekrut', [
            'tawaran'      => $tawaran,
            'lowonganLain' => $lowonganLain,
        ]);
    }
}
