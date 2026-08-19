<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = collect();
        return view('super_admin.event.home', compact('events'));
    }

    public function createForm()
    {
        return view('super_admin.event.buat');
    }

    public function store_event(Request $request)
    {
        return redirect()->route('superadmin.event')->with('success', 'Event berhasil disimpan.');
    }

    public function edit_event($id = null)
    {
        $event = (object)[
            'id'                   => 1,
            'title'                => 'Event Area Kerja',
            'status'               => 'aktif',
            'content'              => 'Deskripsi event',
            'tgl_mulai'            => now(),
            'jam_mulai'            => '09:00',
            'tgl_akhir'            => now()->addDays(2),
            'jam_akhir'            => '17:00',
            'kuota'                => 100,
            'lokasi'               => 'Online',
            'link_form'            => 'https://areakerja.com',
            'penutupan_pendaftaran'=> now()->addDay(),
            'kegiatan'             => collect(),
        ];

        return view('super_admin.event.edit', compact('event'));
    }

    public function update_event(Request $request, $id = null)
    {
        return redirect()->route('superadmin.event')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy_event($id = null)
    {
        return redirect()->route('superadmin.event')->with('success', 'Event berhasil dihapus.');
    }

    public function detail_event($id = null)
    {
        return redirect()->route('superadmin.event');
    }
}
