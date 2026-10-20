<?php

namespace App\Http\Controllers;

use App\Models\BantuanChat;
use App\Models\BantuanMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BantuanChatController extends Controller
{
    /**
     * Tampilkan halaman chat bantuan untuk user (pelamar / perusahaan)
     */
    public function userIndex(Request $request)
    {
        $user = Auth::user();

        // Ambil atau buat percakapan bantuan yang persisten untuk user ini
        $chat = BantuanChat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'subjek' => 'Bantuan & Keluhan Pengguna',
                'status' => 'menunggu',
                'last_message_at' => now(),
            ]
        );

        // Tandai pesan dari admin sebagai terbaca oleh user
        BantuanMessage::where('bantuan_chat_id', $chat->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()->with('sender')->get();

        return view('bantuan.chat', compact('chat', 'messages', 'user'));
    }

    /**
     * Kirim pesan dari user ke admin
     */
    public function userSend(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string|max:5000',
        ]);

        $user = Auth::user();

        $chat = BantuanChat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'subjek' => 'Bantuan & Keluhan Pengguna',
                'status' => 'menunggu',
                'last_message_at' => now(),
            ]
        );

        $message = BantuanMessage::create([
            'bantuan_chat_id' => $chat->id,
            'sender_id'       => $user->id,
            'sender_type'     => 'user',
            'pesan'           => trim($request->pesan),
            'is_read'         => false,
        ]);

        $chat->update([
            'status'          => 'menunggu',
            'last_message_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id'               => $message->id,
                    'pesan'            => nl2br(e($message->pesan)),
                    'time'             => $message->created_at->format('H:i'),
                    'sender_type'      => 'user',
                ],
            ]);
        }

        return redirect()->route('bantuan.chat')->with('success', 'Pesan Anda berhasil dikirim ke Admin.');
    }

    /**
     * Halaman index daftar bantuan untuk Admin & Super Admin
     */
    public function adminIndex(Request $request)
    {
        $isSuperAdmin = $request->routeIs('superadmin.*') || Auth::user()?->role === 'super_admin';
        $status = $request->get('status');
        $search = $request->get('search');

        $query = BantuanChat::with(['user.pelamar', 'user.perusahaan', 'latestMessage'])
            ->withCount('unreadMessagesForAdmin');

        if ($status && in_array($status, ['menunggu', 'dibalas', 'selesai'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('username', 'like', "%{$search}%")
                        ->orWhere('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('messages', function ($mq) use ($search) {
                    $mq->where('pesan', 'like', "%{$search}%");
                });
            });
        }

        // Urutkan: yang ada pesan belum dibaca lebih dulu, lalu last_message_at terbaru
        $chats = $query->orderByDesc('unread_messages_for_admin_count')
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        // Hitung total status untuk badge
        $counts = [
            'semua'    => BantuanChat::count(),
            'menunggu' => BantuanChat::where('status', 'menunggu')->count(),
            'dibalas'  => BantuanChat::where('status', 'dibalas')->count(),
            'selesai'  => BantuanChat::where('status', 'selesai')->count(),
            'unread'   => BantuanMessage::where('sender_type', 'user')->where('is_read', false)->count(),
        ];

        $viewName = $isSuperAdmin ? 'super_admin.bantuan.index' : 'admin.bantuan.index';
        return view($viewName, compact('chats', 'counts', 'status', 'search', 'isSuperAdmin'));
    }

    /**
     * Tampilkan detail thread percakapan untuk Admin / Super Admin
     */
    public function adminShow(Request $request, $id)
    {
        $isSuperAdmin = $request->routeIs('superadmin.*') || Auth::user()?->role === 'super_admin';

        $chat = BantuanChat::with(['user.pelamar', 'user.perusahaan'])->findOrFail($id);

        // Tandai pesan user sebagai sudah dibaca
        BantuanMessage::where('bantuan_chat_id', $chat->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()->with('sender')->get();

        $viewName = $isSuperAdmin ? 'super_admin.bantuan.show' : 'admin.bantuan.show';
        return view($viewName, compact('chat', 'messages', 'isSuperAdmin'));
    }

    /**
     * Kirim balasan Admin / Super Admin ke user
     */
    public function adminReply(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required|string|max:5000',
        ]);

        $chat = BantuanChat::findOrFail($id);

        $message = BantuanMessage::create([
            'bantuan_chat_id' => $chat->id,
            'sender_id'       => Auth::id(),
            'sender_type'     => 'admin',
            'pesan'           => trim($request->pesan),
            'is_read'         => false,
        ]);

        $chat->update([
            'status'          => 'dibalas',
            'last_message_at' => now(),
        ]);

        $redirectRoute = $request->routeIs('superadmin.*') || Auth::user()?->role === 'super_admin'
            ? 'superadmin.bantuan.show'
            : 'admin.bantuan.show';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id'          => $message->id,
                    'pesan'       => nl2br(e($message->pesan)),
                    'time'        => $message->created_at->format('H:i'),
                    'sender_type' => 'admin',
                ],
            ]);
        }

        return redirect()->route($redirectRoute, $chat->id)->with('success', 'Balasan Anda berhasil dikirim ke pengguna.');
    }

    /**
     * Ubah status chat bantuan (misal Tandai Selesai atau Buka Kembali)
     */
    public function adminToggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dibalas,selesai',
        ]);

        $chat = BantuanChat::findOrFail($id);
        $chat->update(['status' => $request->status]);

        $redirectRoute = $request->routeIs('superadmin.*') || Auth::user()?->role === 'super_admin'
            ? 'superadmin.bantuan.show'
            : 'admin.bantuan.show';

        return redirect()->route($redirectRoute, $chat->id)->with('success', 'Status bantuan berhasil diubah menjadi: ' . ucfirst($request->status));
    }
}

