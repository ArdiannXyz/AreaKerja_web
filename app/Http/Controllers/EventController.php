<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\KegiatanEvent;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreEventRequest;

class EventController extends Controller
{
    // ==========================================
    // PUBLIC / PELAMAR / NON-USER METHODS
    // ==========================================
    public function publicEventList(Request $request)
    {
        Event::autoCloseExpired();

        $search = $request->query('q');
        $status = $request->query('status');
        $today = now()->toDateString();

        $events = Event::with('kegiatan')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($status === 'buka', function ($q) {
                $q->upcoming();
            })
            ->when($status === 'tutup', function ($q) {
                $q->ended();
            })
            ->when(empty($status), function ($q) {
                $q->where('status', '!=', 'draft');
            })
            ->when($status === 'tutup', function ($q) {
                $q->orderBy('tgl_mulai', 'desc');
            }, function ($q) use ($today) {
                $q->orderByRaw("CASE WHEN status = 'tutup' OR (tgl_akhir IS NOT NULL AND tgl_akhir < ?) THEN 1 ELSE 0 END ASC", [$today])
                  ->orderBy('tgl_mulai', 'asc');
            })
            ->paginate(9)
            ->withQueryString();

        return view('non-user.event.index', compact('events', 'search', 'status'));
    }

    public function publicEventShow($id)
    {
        $event = Event::with('kegiatan')->findOrFail($id);
        $otherEvents = Event::where('id', '!=', $id)
            ->upcoming()
            ->latest('tgl_mulai')
            ->take(3)
            ->get();

        // Ambil hanya perusahaan yang tergabung/mendaftar di event ini
        $joinedUserIds = \Illuminate\Support\Facades\DB::table('event_participants')
            ->where('event_id', $event->id)
            ->pluck('user_id');

        $perusahaanList = \App\Models\Perusahaan::whereIn('user_id', $joinedUserIds)
            ->whereNotNull('nama_perusahaan')
            ->get();

        $userId = auth()->id();
        $isRegistered = false;
        if ($userId) {
            $isRegistered = \Illuminate\Support\Facades\DB::table('event_participants')
                ->where('event_id', $event->id)
                ->where('user_id', $userId)
                ->exists();
        }
        $isEnded = ($event->status === 'tutup' || now()->toDateString() > $event->tgl_akhir);

        return view('non-user.event.show', compact('event', 'otherEvents', 'perusahaanList', 'isRegistered', 'isEnded'));
    }

    public function daftarEvent($id, Request $request)
    {

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'unauthenticated' => true,
                'redirect' => route('login'),
                'message' => 'Silakan masuk terlebih dahulu untuk mendaftar event.'
            ], 401);
        }

        $event = Event::findOrFail($id);

        if ($event->status === 'tutup' || now()->toDateString() > $event->tgl_akhir) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran untuk event ini telah berakhir.'
            ], 422);
        }

        $userId = auth()->id();

        // Cek jika sudah terdaftar di database
        $alreadyRegistered = \Illuminate\Support\Facades\DB::table('event_participants')
            ->where('event_id', $event->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'success' => false,
                'already_registered' => true,
                'message' => 'Anda sudah terdaftar pada event ini.'
            ], 200);
        }

        // Simpan ke database
        \Illuminate\Support\Facades\DB::table('event_participants')->insert([
            'event_id' => $event->id,
            'user_id' => $userId,
            'status' => 'terdaftar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Selamat! Anda telah terdaftar pada acara ini. Silahkan cek email Anda untuk konfirmasi.'
        ]);
    }

    // ==========================================
    // ADMIN / SUPER ADMIN METHODS
    // ==========================================
    private function isSuperAdmin(Request $request = null): bool
    {
        if (Auth::check() && in_array(Auth::user()->role, ['super_admin', 'superadmin'])) {
            return true;
        }
        if ($request && $request->is('super_admin*')) {
            return true;
        }
        return request()->is('super_admin*');
    }

    public function index(Request $request)
    {
        Event::autoCloseExpired();

        $query = Event::with('kegiatan')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('status', 'like', "%{$q}%")
                  ->orWhere('lokasi', 'like', "%{$q}%");
            });
        }

        $events = $query->paginate(10)->withQueryString();

        $isSuperAdmin = $this->isSuperAdmin($request);

        if ($isSuperAdmin && view()->exists('super_admin.event.home')) {
            return view('super_admin.event.home', compact('events'));
        }
        return view('admin.event.home', compact('events'));
    }

    public function createForm()
    {
        $isSuperAdmin = $this->isSuperAdmin();

        if ($isSuperAdmin && view()->exists('super_admin.event.buat')) {
            return view('super_admin.event.buat');
        }
        return view('admin.event.buat-event');
    }

    public function store_event(StoreEventRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $kegiatanWaktu = $request->input('kegiatan_waktu', []);
        $kegiatanNama = $request->input('kegiatan_nama', []);
        unset($validated['kegiatan_waktu'], $validated['kegiatan_nama']);

        $event = Event::create($validated);

        if (!empty($kegiatanWaktu) && is_array($kegiatanWaktu)) {
            foreach ($kegiatanWaktu as $i => $waktu) {
                if (!empty($waktu) && !empty($kegiatanNama[$i])) {
                    $event->kegiatan()->create([
                        'waktu' => $waktu,
                        'kegiatan' => $kegiatanNama[$i],
                    ]);
                }
            }
        }

        $route = $this->isSuperAdmin($request) ? 'superadmin.eventform' : 'admin.eventform';
        return redirect()->route($route)->with('success', 'Event berhasil disimpan.');
    }

    public function edit_event($id)
    {
        $event = Event::with('kegiatan')->findOrFail($id);

        $isSuperAdmin = $this->isSuperAdmin();

        if ($isSuperAdmin && view()->exists('super_admin.event.edit')) {
            return view('super_admin.event.edit', compact('event'));
        }
        return view('admin.event.edit', compact('event'));
    }

    public function update_event(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'status'                 => 'required|string|in:buka,tutup,draft',
            'tgl_mulai'              => 'required|date',
            'tgl_akhir'              => 'required|date|after_or_equal:tgl_mulai',
            'kuota'                  => 'nullable|integer|min:1',
            'image'                  => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'content'                => 'nullable|string',
            'jam_mulai'              => 'nullable|string|max:10',
            'jam_akhir'              => 'nullable|string|max:10',
            'lokasi'                 => 'nullable|string',
            'link_form'              => 'nullable|url',
            'penutupan_pendaftaran'  => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        $route = $this->isSuperAdmin($request) ? 'superadmin.eventform' : 'admin.eventform';
        return redirect()->route($route)->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy_event($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        $route = $this->isSuperAdmin() ? 'superadmin.eventform' : 'admin.eventform';
        return redirect()->route($route)->with('success', 'Event berhasil dihapus.');
    }

    public function detail_event($id)
    {
        $event = Event::with('kegiatan')->findOrFail($id);

        $isSuperAdmin = $this->isSuperAdmin();

        if ($isSuperAdmin) {
            if (view()->exists('super_admin.event.view')) {
                return view('super_admin.event.view', compact('event'));
            }
            if (view()->exists('super_admin.event.detail')) {
                return view('super_admin.event.detail', compact('event'));
            }
        }
        return view('admin.event.detail-event', compact('event'));
    }

    public function updateStatus(Request $request, Event $event)
    {
        $validated = $request->validate([
            'status' => 'required|in:buka,tutup,draft',
        ]);

        $event->update($validated);

        return back()->with('success', 'Status event berhasil diperbarui.');
    }
}
