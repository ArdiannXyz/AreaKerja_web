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
                'status' => 'pending',
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

    public function tawaran()
    {
        $user = auth()->user();
        $pelamar = $user?->pelamar;

        if (!$pelamar) {
            $tawaran = collect();
        } else {
            $tawaran = PelamarLowongan::where('pelamar_id', $pelamar->id)
                ->with(['lowonganPerusahaan.perusahaan'])
                ->latest()
                ->get();
        }

        return view('kandidat.rekrut-saya', compact('tawaran'));
    }

    public function detailTawaran($perusahaan, $lowongan)
    {
        $user = auth()->user();
        $pelamar = $user?->pelamar;

        $perusahaanModel = Perusahaan::where('slug', $perusahaan)->orWhere('id', $perusahaan)->first();
        $lowonganModel = LowonganPerusahaan::where(function ($q) use ($lowongan) {
            $q->where('slug', $lowongan);
            if (is_numeric($lowongan)) {
                $q->orWhere('id', (int) $lowongan);
            }
        });

        if ($perusahaanModel) {
            $lowonganModel->where('perusahaan_id', $perusahaanModel->id);
        }

        $lowonganModel = $lowonganModel->with('perusahaan')->first();

        if (!$lowonganModel) {
            abort(404, 'Lowongan tidak ditemukan.');
        }

        $tawaran = null;
        if ($pelamar && $lowonganModel) {
            $tawaran = PelamarLowongan::where('pelamar_id', $pelamar->id)
                ->where('lowongan_id', $lowonganModel->id)
                ->with(['lowonganPerusahaan.perusahaan'])
                ->first();
        }

        if (!$tawaran) {
            $tawaran = (object)[
                'id' => 0,
                'status' => 'pending',
                'lowonganPerusahaan' => $lowonganModel
            ];
        }

        $perusahaanId = $tawaran->lowonganPerusahaan->perusahaan_id ?? null;
        $lowonganId = $tawaran->lowonganPerusahaan->id ?? null;

        $lowonganLain = LowonganPerusahaan::where('perusahaan_id', $perusahaanId)
            ->where('id', '!=', $lowonganId)
            ->with('perusahaan')
            ->take(5)
            ->get();

        return view('kandidat.detail_rekrut', compact('tawaran', 'lowonganLain'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $user = auth()->user();
        $pelamar = $user?->pelamar;

        $tawaran = null;

        if ($id && $id > 0) {
            $tawaran = PelamarLowongan::with(['lowonganPerusahaan.perusahaan', 'pelamar'])->find($id);
        }

        if (!$tawaran && $request->lowongan_id && $pelamar) {
            $tawaran = PelamarLowongan::where('pelamar_id', $pelamar->id)
                ->where('lowongan_id', $request->lowongan_id)
                ->with(['lowonganPerusahaan.perusahaan', 'pelamar'])
                ->first();

            if (!$tawaran) {
                $tawaran = PelamarLowongan::create([
                    'pelamar_id' => $pelamar->id,
                    'lowongan_id' => $request->lowongan_id,
                    'status' => 'pending',
                ]);
                $tawaran->load(['lowonganPerusahaan.perusahaan', 'pelamar']);
            }
        }

        if (!$tawaran && $pelamar) {
            $tawaran = PelamarLowongan::where('pelamar_id', $pelamar->id)
                ->with(['lowonganPerusahaan.perusahaan', 'pelamar'])
                ->latest()
                ->first();
        }

        if (!$tawaran) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tawaran tidak ditemukan.'
            ], 404);
        }

        $oldStatus = strtolower($tawaran->status ?? '');
        $newStatus = strtolower($request->status);

        $tawaran->status = $newStatus;
        if ($request->alasan_penolakan) {
            $tawaran->alasan_penolakan = $request->alasan_penolakan;
        }
        $tawaran->save();

        // JIKA KANDIDAT MENOLAK TAWARAN & STATUS SEBELUMNYA BUKAN DITOLAK -> REFUND KOIN 100 KE PERUSAHAAN
        if ($newStatus === 'ditolak' && $oldStatus !== 'ditolak') {
            $perusahaan = $tawaran->lowonganPerusahaan?->perusahaan;
            if ($perusahaan) {
                $jumlahRefund = 100;
                $perusahaan->increment('koin_perusahaan', $jumlahRefund);

                $namaPelamar = $tawaran->pelamar?->nama_pelamar ?? 'Kandidat';

                // Catat refund di CatatanKoin
                CatatanKoin::create([
                    'user_id'      => $perusahaan->user_id,
                    'no_referensi' => 'REFUND-' . strtoupper(Str::random(8)),
                    'pesanan'      => 'Pengembalian Koin (Penolakan Kandidat: ' . $namaPelamar . ')',
                    'dari'         => 'Sistem AreaKerja',
                    'sumber_dana'  => 'Refund Koin',
                    'total'        => '+' . $jumlahRefund,
                ]);

                // Notifikasi ke Perusahaan
                if ($perusahaan->user_id) {
                    $alasanTxt = $request->alasan_penolakan ? " Alasan: {$request->alasan_penolakan}." : "";
                    Notifikasi::create([
                        'user_id'       => $perusahaan->user_id,
                        'perusahaan_id' => $perusahaan->id,
                        'judul'         => 'Penolakan Tawaran Rekrutmen',
                        'pesan'         => "Kandidat {$namaPelamar} menolak tawaran Anda.{$alasanTxt} Saldo +100 koin telah dikembalikan ke akun Anda.",
                        'is_read'       => false,
                        'expired_at'    => now()->addDays(7),
                    ]);
                }
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => $newStatus === 'ditolak'
                ? 'Tawaran berhasil ditolak dan 100 koin telah dikembalikan ke perusahaan.'
                : 'Status tawaran berhasil diperbarui.',
        ]);
    }
}
