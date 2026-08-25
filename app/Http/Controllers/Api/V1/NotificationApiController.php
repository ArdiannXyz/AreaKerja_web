<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    /**
     * Get list of notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Notifikasi::where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->perusahaan) {
                $q->orWhere('perusahaan_id', $user->perusahaan->id);
            }
        })
        ->with(['pelamarLowongan.lowongan'])
        ->latest();

        $notifications = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $notifications,
        ]);
    }

    /**
     * Get count of unread notifications.
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $count = Notifikasi::where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->perusahaan) {
                $q->orWhere('perusahaan_id', $user->perusahaan->id);
            }
        })
        ->where('is_read', 0)
        ->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'unread_count' => $count,
            ],
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        $notification = Notifikasi::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->perusahaan) {
                    $q->orWhere('perusahaan_id', $user->perusahaan->id);
                }
            })
            ->firstOrFail();

        $notification->is_read = 1;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai telah dibaca.',
            'data'    => $notification,
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        Notifikasi::where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->perusahaan) {
                $q->orWhere('perusahaan_id', $user->perusahaan->id);
            }
        })
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai telah dibaca.',
        ]);
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $notification = Notifikasi::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->perusahaan) {
                    $q->orWhere('perusahaan_id', $user->perusahaan->id);
                }
            })
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }

    /**
     * Delete all notifications for current user.
     */
    public function destroyAll(Request $request)
    {
        $user = $request->user();

        Notifikasi::where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->perusahaan) {
                $q->orWhere('perusahaan_id', $user->perusahaan->id);
            }
        })->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil dibersihkan.',
        ]);
    }

    /**
     * Register / Update Push Notification Device Token (Firebase FCM).
     */
    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json([
            'success' => true,
            'message' => 'Device FCM token berhasil diperbarui.',
        ]);
    }
}
