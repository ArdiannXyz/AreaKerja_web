<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\KegiatanEvent;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    private function ensureTableAndData()
    {
        if (!Schema::hasTable('events')) {
            try {
                Schema::create('events', function ($table) {
                    $table->id();
                    $table->string('status')->default('buka');
                    $table->string('title');
                    $table->integer('kuota')->nullable();
                    $table->string('image')->nullable();
                    $table->mediumText('content')->nullable();
                    $table->date('tgl_mulai');
                    $table->string('jam_mulai', 10)->default('09:00');
                    $table->date('tgl_akhir');
                    $table->string('jam_akhir', 10)->default('17:00');
                    $table->text('lokasi')->nullable();
                    $table->string('link_form')->nullable();
                    $table->date('penutupan_pendaftaran')->nullable();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {
                Log::error('Event table create error: ' . $e->getMessage());
            }
        }

        if (!Schema::hasTable('kegiatan_events')) {
            try {
                Schema::create('kegiatan_events', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('event_id')->index();
                    $table->string('waktu')->nullable();
                    $table->string('kegiatan')->nullable();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {
                Log::error('KegiatanEvent table create error: ' . $e->getMessage());
            }
        }

        // Seed demo events if empty
        if (Event::count() === 0) {
            $e1 = Event::create([
                'status' => 'buka',
                'title' => 'National Virtual Job Fair & Career Expo 2026',
                'kuota' => 500,
                'image' => null,
                'content' => 'AreaKerja mempersembahkan National Virtual Job Fair 2026 yang mempertemukan lebih dari 50+ perusahaan teknologi, BUMN, perbankan, dan agensi terkemuka di Indonesia. Siapkan CV terbaik Anda dan dapatkan kesempatan wawancara langsung (walk-in online interview)!',
                'tgl_mulai' => now()->addDays(5)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'tgl_akhir' => now()->addDays(7)->format('Y-m-d'),
                'jam_akhir' => '16:00',
                'lokasi' => 'Online (Zoom & Platform AreaKerja)',
                'link_form' => 'https://forms.gle/demo-jobfair-areakerja',
                'penutupan_pendaftaran' => now()->addDays(4)->format('Y-m-d'),
            ]);

            KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '09:00 - 10:00', 'kegiatan' => 'Opening Ceremony & Keynote Speaker dari HR Leaders']);
            KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '10:00 - 12:30', 'kegiatan' => 'Company Presentation & Direct Job Pitching']);
            KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '13:30 - 16:00', 'kegiatan' => 'Breakout Rooms Online Interview & CV Review']);

            $e2 = Event::create([
                'status' => 'buka',
                'title' => 'Masterclass: Rahasia Lolos Interview & Negosiasi Gaji',
                'kuota' => 200,
                'image' => null,
                'content' => 'Pelajari teknik praktis menyusun resume ATS-friendly, menjawab pertanyaan behavioral interview dengan metode STAR, serta strategi negosiasi penawaran gaji bersama para Talent Acquisition Senior.',
                'tgl_mulai' => now()->addDays(10)->format('Y-m-d'),
                'jam_mulai' => '13:30',
                'tgl_akhir' => now()->addDays(10)->format('Y-m-d'),
                'jam_akhir' => '16:30',
                'lokasi' => 'Live Streaming Webinar AreaKerja',
                'link_form' => 'https://forms.gle/demo-masterclass-areakerja',
                'penutupan_pendaftaran' => now()->addDays(9)->format('Y-m-d'),
            ]);

            KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '13:30 - 14:30', 'kegiatan' => 'Bedah CV & Portofolio Standar HR 2026']);
            KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '14:30 - 15:30', 'kegiatan' => 'Simulasi Mock Interview Live Session']);
            KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '15:30 - 16:30', 'kegiatan' => 'Tanya Jawab & Tips Menghadapi User Interview']);
        }
    }

    // ==========================================
    // PUBLIC / PELAMAR / NON-USER METHODS
    // ==========================================
    public function publicEventList(Request $request)
    {
        $this->ensureTableAndData();

        $search = $request->query('q');
        $status = $request->query('status');

        $events = Event::with('kegiatan')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            }, function ($q) {
                $q->where('status', '!=', 'draft');
            })
            ->orderBy('tgl_mulai', 'asc')
            ->paginate(9);

        return view('non-user.event.index', compact('events', 'search', 'status'));
    }

    public function publicEventShow($id)
    {
        $this->ensureTableAndData();

        $event = Event::with('kegiatan')->findOrFail($id);
        $otherEvents = Event::where('id', '!=', $id)
            ->where('status', 'buka')
            ->latest('tgl_mulai')
            ->take(3)
            ->get();

        return view('non-user.event.show', compact('event', 'otherEvents'));
    }

    // ==========================================
    // ADMIN / SUPER ADMIN METHODS
    // ==========================================
    public function index(Request $request)
    {
        $this->ensureTableAndData();
        $events = Event::with('kegiatan')->latest()->paginate(10);

        if (view()->exists('super_admin.event.home')) {
            return view('super_admin.event.home', compact('events'));
        }
        return view('admin.event.home', compact('events'));
    }

    public function createForm()
    {
        if (view()->exists('super_admin.event.buat')) {
            return view('super_admin.event.buat');
        }
        return view('admin.event.buat-event');
    }

    public function store_event(Request $request)
    {
        $this->ensureTableAndData();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date',
        ]);

        Event::create($request->all());

        return redirect()->route('superadmin.eventform')->with('success', 'Event berhasil disimpan.');
    }

    public function edit_event($id)
    {
        $this->ensureTableAndData();
        $event = Event::with('kegiatan')->findOrFail($id);

        if (view()->exists('super_admin.event.edit')) {
            return view('super_admin.event.edit', compact('event'));
        }
        return view('admin.event.edit', compact('event'));
    }

    public function update_event(Request $request, $id)
    {
        $this->ensureTableAndData();
        $event = Event::findOrFail($id);
        $event->update($request->all());

        return redirect()->route('superadmin.eventform')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy_event($id)
    {
        $this->ensureTableAndData();
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('superadmin.eventform')->with('success', 'Event berhasil dihapus.');
    }

    public function detail_event($id)
    {
        $this->ensureTableAndData();
        $event = Event::with('kegiatan')->findOrFail($id);

        if (view()->exists('super_admin.event.detail')) {
            return view('super_admin.event.detail', compact('event'));
        }
        return view('admin.event.detail-event', compact('event'));
    }
}
