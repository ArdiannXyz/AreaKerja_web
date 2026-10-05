<?php

namespace Database\Seeders;

use App\Models\Iklan;
use App\Models\Perusahaan;
use Illuminate\Database\Seeder;

class IklanDummySeeder extends Seeder
{
    public function run(): void
    {
        $perusahaan = Perusahaan::first();

        if (!$perusahaan) {
            $this->command->warn('Tidak ada data perusahaan. Seed dibatalkan.');
            return;
        }

        $dummyAds = [
            [
                'judul_iklan'    => 'Karir Impianmu Dimulai di Sini!',
                'url_tujuan'     => 'https://areakerja.com',
                'paket_nama'     => 'Gold',
                'durasi_hari'    => 14,
                'koin_terpotong' => 550,
            ],
            [
                'judul_iklan'    => 'Rekrut Talenta Terbaik Bersama ' . $perusahaan->nama_perusahaan,
                'url_tujuan'     => 'https://areakerja.com',
                'paket_nama'     => 'Silver',
                'durasi_hari'    => 7,
                'koin_terpotong' => 300,
            ],
        ];

        foreach ($dummyAds as $ad) {
            Iklan::create([
                'perusahaan_id'  => $perusahaan->id,
                'judul_iklan'    => $ad['judul_iklan'],
                'gambar_banner'  => 'iklan/banner_sample.png',
                'url_tujuan'     => $ad['url_tujuan'],
                'paket_nama'     => $ad['paket_nama'],
                'durasi_hari'    => $ad['durasi_hari'],
                'koin_terpotong' => $ad['koin_terpotong'],
                'posisi'         => 'home_hero',
                'status'         => 'aktif',
                'tanggal_mulai'  => now(),
                'tanggal_selesai'=> now()->addDays($ad['durasi_hari']),
                'total_views'    => 0,
                'total_clicks'   => 0,
            ]);
        }

        $this->command->info('✅ ' . count($dummyAds) . ' iklan dummy berhasil dibuat untuk: ' . $perusahaan->nama_perusahaan);
    }
}
