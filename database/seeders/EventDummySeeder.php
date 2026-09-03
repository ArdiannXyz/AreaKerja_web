<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\KegiatanEvent;

class EventDummySeeder extends Seeder
{
    public function run()
    {
        KegiatanEvent::truncate();
        Event::truncate();

        $e1 = Event::create([
            'status' => 'buka',
            'title' => 'Job Fair & Career Expo Yogyakarta 2026',
            'kuota' => 500,
            'image' => null,
            'content' => "Job Fair akbar yang mempertemukan 70+ perusahaan BUMN, perbankan, IT startup, manufaktur, dan instansi terkemuka di Indonesia.\n\nBawa berkas CV terbaik Anda untuk walk-in interview langsung di booth perusahaan atau kirim lamaran digital secara instan melalui sistem AreaKerja!",
            'tgl_mulai' => '2026-09-15',
            'jam_mulai' => '08:30',
            'tgl_akhir' => '2026-09-17',
            'jam_akhir' => '16:30',
            'lokasi' => 'Jogja Expo Center (JEC)',
            'link_form' => 'https://areakerja.com/event/jobfair-2026',
            'penutupan_pendaftaran' => '2026-09-14',
        ]);

        KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '08:30 - 09:30', 'kegiatan' => 'Registrasi Peserta & Sambutan Pembukaan']);
        KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '09:30 - 12:00', 'kegiatan' => 'Sesi 1: Company Presentation & Job Pitching']);
        KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '12:00 - 13:00', 'kegiatan' => 'Istirahat & Networking Session']);
        KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '13:00 - 16:00', 'kegiatan' => 'Sesi 2: Walk-in Interview & On-the-spot Test']);
        KegiatanEvent::create(['event_id' => $e1->id, 'waktu' => '16:00 - 16:30', 'kegiatan' => 'Closing Day & Pengumuman Tahap Lanjut']);

        $e2 = Event::create([
            'status' => 'buka',
            'title' => 'Seminar Kerja: Akselerasi Karir & Bedah Gaji Profesional',
            'kuota' => 250,
            'image' => null,
            'content' => "Seminar interaktif bersama para praktisi HRD Senior dan Head of Talent Acquisition.\n\nDapatkan wawasan mendalam tentang tren kompetensi industri 2026, optimasi resume standar ATS, strategi interview metode STAR, dan teknik negosiasi offering letter yang profesional.",
            'tgl_mulai' => '2026-09-22',
            'jam_mulai' => '09:00',
            'tgl_akhir' => '2026-09-22',
            'jam_akhir' => '15:30',
            'lokasi' => 'Auditorium Magister Manajemen UGM & Zoom Live',
            'link_form' => 'https://areakerja.com/event/seminar-karir-2026',
            'penutupan_pendaftaran' => '2026-09-21',
        ]);

        KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '09:00 - 09:30', 'kegiatan' => 'Registrasi & Welcome Coffee']);
        KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '09:30 - 11:30', 'kegiatan' => 'Sesi 1: Tren Rekrutmen & Standar Portofolio 2026']);
        KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '11:30 - 13:00', 'kegiatan' => 'Ishoma']);
        KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '13:00 - 15:00', 'kegiatan' => 'Sesi 2: Simulasi Live Interview & Negosiasi Gaji']);
        KegiatanEvent::create(['event_id' => $e2->id, 'waktu' => '15:00 - 15:30', 'kegiatan' => 'Sesi Tanya Jawab & Foto Bersama']);

        $e3 = Event::create([
            'status' => 'tutup',
            'title' => 'Tech Career Day & Talent Pitching 2026',
            'kuota' => 150,
            'image' => null,
            'content' => "Acara eksklusif bagi talenta Software Engineering, UI/UX Designer, Data Analyst, dan Product Manager untuk pitching langsung ke para CTO dan HR Tech Company ternama di Indonesia.",
            'tgl_mulai' => '2026-08-20',
            'jam_mulai' => '08:00',
            'tgl_akhir' => '2026-08-20',
            'jam_akhir' => '16:00',
            'lokasi' => 'Grand Ballroom Hyatt Regency Yogyakarta',
            'link_form' => 'https://areakerja.com/event/tech-career-day',
            'penutupan_pendaftaran' => '2026-08-19',
        ]);

        KegiatanEvent::create(['event_id' => $e3->id, 'waktu' => '08:00 - 09:00', 'kegiatan' => 'Registrasi & Morning Briefing']);
        KegiatanEvent::create(['event_id' => $e3->id, 'waktu' => '09:00 - 12:00', 'kegiatan' => 'Tech Showcase & Speed Interview']);
        KegiatanEvent::create(['event_id' => $e3->id, 'waktu' => '13:00 - 16:00', 'kegiatan' => 'Direct Hiring Assessment & Awarding']);
    }
}
