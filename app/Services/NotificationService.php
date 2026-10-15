<?php

namespace App\Services;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Kirim notifikasi ke user dengan data default dan error handling yang aman.
     *
     * @param int $userId ID penerima notifikasi
     * @param string $judul Judul notifikasi
     * @param string $pesan Isi pesan notifikasi
     * @param array $extra Opsi tambahan (perusahaan_id, lowongan_id, pelamar_lowongan_id, expired_at)
     * @return Notifikasi|null
     */
    public static function send(
        int $userId,
        string $judul,
        string $pesan,
        array $extra = []
    ): ?Notifikasi {
        try {
            return Notifikasi::create([
                'user_id'             => $userId,
                'perusahaan_id'       => $extra['perusahaan_id'] ?? null,
                'lowongan_id'         => $extra['lowongan_id'] ?? null,
                'pelamar_lowongan_id' => $extra['pelamar_lowongan_id'] ?? null,
                'judul'               => $judul,
                'pesan'               => $pesan,
                'is_read'             => false,
                'expired_at'          => $extra['expired_at'] ?? now()->addDays(7),
            ]);
        } catch (\Throwable $e) {
            Log::warning("Gagal membuat notifikasi sistem: {$e->getMessage()}", [
                'user_id' => $userId,
                'judul'   => $judul,
            ]);
            return null;
        }
    }

    /**
     * Tandai notifikasi sebagai terbaca
     */
    public static function markAsRead(int $notificationId, int $userId): bool
    {
        $notif = Notifikasi::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notif) {
            return $notif->update(['is_read' => true]);
        }

        return false;
    }
}

