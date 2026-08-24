<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EventController extends Controller
{
    private function dummyEvent()
    {
        return (object)[
            'id'                    => 1,
            'title'                 => 'Event Area Kerja',
            'status'                => 'aktif',
            'content'               => 'Deskripsi event',
            'tgl_mulai'             => now(),
            'jam_mulai'             => '09:00',
            'tgl_akhir'             => now()->addDays(2),
            'jam_akhir'             => '17:00',
            'kuota'                 => 100,
            'lokasi'                => 'Online',
            'link_form'             => 'https://areakerja.com',
            'penutupan_pendaftaran' => now()->addDay(),
            'kegiatan'              => collect(),
        ];
    }

    private function getPaginator()
    {
        $items = collect([$this->dummyEvent()]);
        return new LengthAwarePaginator($items, $items->count(), 10, 1, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }

    // Super Admin & General
    public function index(Request $request)
    {
        $events = $this->getPaginator();
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
        return redirect()->route('superadmin.eventform')->with('success', 'Event berhasil disimpan.');
    }

    public function edit_event($id = null)
    {
        $event = $this->dummyEvent();
        if (view()->exists('super_admin.event.edit')) {
            return view('super_admin.event.edit', compact('event'));
        }
        return view('admin.event.edit', compact('event'));
    }

    public function update_event(Request $request, $id = null)
    {
        return redirect()->route('superadmin.eventform')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy_event($id = null)
    {
        return redirect()->route('superadmin.eventform')->with('success', 'Event berhasil dihapus.');
    }

    public function detail_event($id = null)
    {
        $event = $this->dummyEvent();
        if (view()->exists('super_admin.event.detail')) {
            return view('super_admin.event.detail', compact('event'));
        }
        return view('admin.event.detail-event', compact('event'));
    }

    // Admin methods
    public function index_admin(Request $request)
    {
        $events = $this->getPaginator();
        return view('admin.event.home', compact('events'));
    }

    public function createForm_admin()
    {
        return view('admin.event.buat-event');
    }

    public function store_event_admin(Request $request)
    {
        return redirect()->route('admin.eventform')->with('success', 'Event berhasil disimpan.');
    }

    public function edit_admin($id = null)
    {
        $event = $this->dummyEvent();
        return view('admin.event.edit', compact('event'));
    }

    public function update_event_admin(Request $request, $id = null)
    {
        return redirect()->route('admin.eventform')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy_admin($id = null)
    {
        return redirect()->route('admin.eventform')->with('success', 'Event berhasil dihapus.');
    }

    public function detail_admin($id = null)
    {
        $event = $this->dummyEvent();
        return view('admin.event.detail-event', compact('event'));
    }

    public function updateStatus(Request $request, $id = null)
    {
        return redirect()->back()->with('success', 'Status event berhasil diperbarui.');
    }

    // Perusahaan method
    public function event(Request $request)
    {
        $events = $this->getPaginator();
        return view('perusahaan.event.event', compact('events'));
    }

    public function detail($id = null)
    {
        $event = $this->dummyEvent();
        return view('perusahaan.event.gabung-event', compact('event'));
    }
}
