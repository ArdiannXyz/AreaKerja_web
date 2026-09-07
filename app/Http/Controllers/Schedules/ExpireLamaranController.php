<?php

namespace App\Http\Controllers\Schedules;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Models\PelamarLowongan;

class ExpireLamaranController extends Controller
{
    /**
     * Meng-expiret lamaran pelamar,
     * menghapus notifikasi diterima,
     * dan membuat notifikasi expired
     */
    public function index()
    {
        $lams = PelamarLowongan::whereNotNull('expired_at')
            ->where('expired_at', '<=', Carbon::now())
            ->with(['lowongan_perusahaan.perusahaan', 'pelamar'])
            ->get();

        $created = 0;

        foreach ($lams as $lam) {
            $pid    = $lam->id;
            $userId = $lam->pelamar->user_id ?? null;

            // Update status lamaran menjadi 'expired' agar UI mencerminkan kondisi sebenarnya
            $lam->update(['status' => 'expired']);

            Notifikasi::where('pelamar_lowongan_id', $pid)
                ->whereRaw('LOWER(judul) LIKE ?', ['%diterima%'])
                ->delete();

            $exists = Notifikasi::where('pelamar_lowongan_id', $pid)
                ->whereRaw('LOWER(judul) = ?', ['lamaran expired'])
                ->exists();

            if (! $exists) {
                Notifikasi::create([
                    'user_id'             => $userId,
                    'pelamar_lowongan_id' => $pid,
                    'judul'               => 'Lamaran Expired',
                    'pesan'               => 'Lamaran Anda telah expired.',
                    'expired_at' => now()->addDays(7),
                    'is_read'             => 0,
                ]);
                $created++;
            }
        }

        return response()->json([
            'task'    => 'expire_lamaran',
            'created' => $created,
        ]);
    }
}
