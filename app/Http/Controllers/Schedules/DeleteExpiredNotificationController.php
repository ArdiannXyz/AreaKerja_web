<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class DeleteExpiredNotificationController extends Controller
{
    /**
     * Menghapus semua notifikasi:
     * 1. Yang expired_at sudah lewat
     * 2. Yang berusia lebih dari batas bulan (default 3 bulan, konfigurasi via env/parameter)
     */
    public function index(Request $request)
    {
        $months = (int) ($request->input('months') ?? config('app.notification_retention_months', 3));
        if ($months <= 0) {
            $months = 3;
        }

        $deletedExpired = Notifikasi::whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->delete();

        $deletedOld = Notifikasi::where('created_at', '<', now()->subMonths($months))
            ->delete();

        return response()->json([
            'task' => 'delete_expired_notification',
            'retention_months' => $months,
            'deleted_expired' => $deletedExpired,
            'deleted_old' => $deletedOld,
            'total_deleted' => $deletedExpired + $deletedOld,
        ]);
    }
}
