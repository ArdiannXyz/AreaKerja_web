<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\Controller;

use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifyExpiredLowonganController extends Controller
{
    /**
     * Mengirim notifikasi ke perusahaan
     * jika lowongan sudah kadaluarsa
     */
    public function index()
    {
        $sent = 0;

        $lowongans = LowonganPerusahaan::whereNotNull('published_at')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->with('perusahaan')
            ->get();

        foreach ($lowongans as $l) {

            // Lewati jika perusahaan tidak ditemukan (data corrupt)
            if (!$l->perusahaan) {
                continue;
            }

            $notifExpiredAt = now()->addDays(3);

            $notif = Notifikasi::firstOrCreate(
                [
                    'lowongan_id' => $l->id,
                    'judul'       => 'Lowongan Kadaluarsa',
                ],
                [
                    'user_id'       => $l->perusahaan->user_id,
                    'perusahaan_id' => $l->perusahaan_id,
                    'pesan'         => "Lowongan \"{$l->nama}\" telah kadaluarsa.",
                    'expired_at'    => $notifExpiredAt,
                    'is_read'       => 0,
                ]
            );

            // hanya hitung kalau benar-benar BARU dibuat
            if ($notif->wasRecentlyCreated) {
                $sent++;
            }
        }

        return response()->json([
            'task' => 'notify_expired_lowongan_per_lowongan',
            'sent' => $sent,
        ]);
    }
}   
