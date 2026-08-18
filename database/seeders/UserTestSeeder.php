<?php

namespace Database\Seeders;

use App\Models\DaftarBank;
use App\Models\LowonganPerusahaan;
use App\Models\PaketLowongan;
use App\Models\Pelamar;
use App\Models\PengalamanKerja;
use App\Models\Perusahaan;
use App\Models\RiwayatPendidikan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    /**
     * Seeder untuk 12 Tabel Inti Bersih & Testing Akun Semua Role.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // =====================================================
            // 0. MASTER PAKET LOWONGAN & BANK
            // =====================================================
            $gold = PaketLowongan::updateOrCreate(
                ['nama' => 'Gold'],
                [
                    'harga_koin'    => 200,
                    'batas_listing' => 180,
                    'publikasi'     => 1,
                    'benefit'       => "Prioritas utama di pencarian\nPosting di media sosial\nBadge Gold Verified\nBroadcast info kerja",
                ]
            );

            $silver = PaketLowongan::updateOrCreate(
                ['nama' => 'Silver'],
                [
                    'harga_koin'    => 150,
                    'batas_listing' => 30,
                    'publikasi'     => 1,
                    'benefit'       => "Tampil di halaman utama\nPosting di media sosial\nBadge Silver",
                ]
            );

            $bronze = PaketLowongan::updateOrCreate(
                ['nama' => 'Bronze'],
                [
                    'harga_koin'    => 100,
                    'batas_listing' => 7,
                    'publikasi'     => 1,
                    'benefit'       => "Tampil di pencarian lowongan\nBadge Bronze",
                ]
            );

            // Master Bank
            DaftarBank::updateOrCreate(
                ['nama_bank' => 'BCA'],
                ['no_rek' => '1234567890', 'owner' => 'PT Area Kerja Global', 'logo_image' => 'images/bca.png']
            );
            DaftarBank::updateOrCreate(
                ['nama_bank' => 'Mandiri'],
                ['no_rek' => '0987654321', 'owner' => 'PT Area Kerja Global', 'logo_image' => 'images/bca.png']
            );
            DaftarBank::updateOrCreate(
                ['nama_bank' => 'QRIS'],
                ['no_rek' => 'NMID-0012398471', 'owner' => 'AreaKerja Pay', 'logo_image' => 'images/qrrrr-removebg-preview.png']
            );

            // =====================================================
            // 1. SUPER ADMIN
            // =====================================================
            User::updateOrCreate(
                ['email' => 'superadmin@areakerja.test'],
                [
                    'username'           => 'superadmin_test',
                    'nama_lengkap'       => 'Super Admin AreaKerja',
                    'telepon'            => '081122334455',
                    'password'           => Hash::make('password123'),
                    'role'               => 'super_admin',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            // =====================================================
            // 2. ADMIN
            // =====================================================
            User::updateOrCreate(
                ['email' => 'admin@areakerja.test'],
                [
                    'username'           => 'admin_test',
                    'nama_lengkap'       => 'Admin Operasional',
                    'telepon'            => '081122334466',
                    'password'           => Hash::make('password123'),
                    'role'               => 'admin',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            // =====================================================
            // 3. FINANCE
            // =====================================================
            User::updateOrCreate(
                ['email' => 'finance@areakerja.test'],
                [
                    'username'           => 'finance_test',
                    'nama_lengkap'       => 'Finance Manager',
                    'telepon'            => '081122334477',
                    'password'           => Hash::make('password123'),
                    'role'               => 'finance',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            // =====================================================
            // 4. PERUSAHAAN (Verified, 500 koin, 3 lowongan)
            // =====================================================
            $userPerusahaan = User::updateOrCreate(
                ['email' => 'perusahaan@areakerja.test'],
                [
                    'username'           => 'perusahaan_test',
                    'nama_lengkap'       => 'HRD PT AreaKerja',
                    'telepon'            => '0812345678',
                    'password'           => Hash::make('password123'),
                    'role'               => 'perusahaan',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            $perusahaan = Perusahaan::updateOrCreate(
                ['user_id' => $userPerusahaan->id],
                [
                    'nama_perusahaan'    => 'PT. AreaKerja Teknologi',
                    'slug'               => 'pt-areakerja-teknologi',
                    'jenis_perusahaan'   => 'Teknologi Informasi',
                    'website_perusahaan' => 'https://areakerja.test',
                    'telepon_perusahaan' => '0812345678',
                    'whatsapp'           => '0812345678',
                    'legalitas'          => 'PT',
                    'deskripsi'          => 'Perusahaan teknologi yang bergerak di bidang pengembangan platform rekrutmen digital & mobile.',
                    'visi'               => 'Menjadi platform rekrutmen terpercaya di Indonesia.',
                    'misi'               => 'Menghubungkan perusahaan dengan kandidat terbaik secara efisien.',
                    'alamat'             => 'Jl. Teknologi No. 123, Sukamaju',
                    'kota'               => 'Surabaya',
                    'provinsi'           => 'Jawa Timur',
                    'img_profile'        => null,
                    'verification_status' => 'approved',
                    'verified_at'         => now(),
                    'koin_perusahaan'     => 500,
                    'is_berlangganan'     => 0,
                ]
            );

            // 3 Lowongan Perusahaan
            LowonganPerusahaan::updateOrCreate(
                ['slug' => 'backend-developer-test'],
                [
                    'perusahaan_id'    => $perusahaan->id,
                    'nama'             => 'Backend Developer',
                    'slug'             => 'backend-developer-test',
                    'jenis'            => 'Full Time',
                    'gaji_awal'        => '5000000',
                    'gaji_akhir'       => '10000000',
                    'label_gaji'       => 'Rp 5jt - 10jt',
                    'deskripsi'        => '<p>Kami mencari Backend Developer berpengalaman untuk merancang REST API scalable.</p>',
                    'alamat'           => 'Surabaya, Jawa Timur',
                    'kategori'         => 'IT & Software',
                    'batas_lamaran'    => now()->addDays(30),
                    'syarat_pekerjaan' => 'Minimal S1 Teknik Informatika, pengalaman 2 tahun Laravel & MySQL.',
                    'tanggung_jawab'   => 'Mengembangkan REST API, optimasi database, code review.',
                    'benefit'          => 'BPJS, THR, Remote Work, Laptop',
                    'paket_id'         => $gold->id,
                    'published_at'     => now(),
                    'expired_at'       => now()->addDays(180),
                ]
            );

            LowonganPerusahaan::updateOrCreate(
                ['slug' => 'frontend-developer-test'],
                [
                    'perusahaan_id'    => $perusahaan->id,
                    'nama'             => 'Frontend Developer',
                    'slug'             => 'frontend-developer-test',
                    'jenis'            => 'Full Time',
                    'gaji_awal'        => '4000000',
                    'gaji_akhir'       => '8000000',
                    'label_gaji'       => 'Rp 4jt - 8jt',
                    'deskripsi'        => '<p>Kami mencari Frontend Developer yang menguasai Flutter & Vue.js.</p>',
                    'alamat'           => 'Surabaya, Jawa Timur',
                    'kategori'         => 'IT & Software',
                    'batas_lamaran'    => now()->addDays(30),
                    'syarat_pekerjaan' => 'Minimal D3/S1, menguasai Vue.js atau Flutter, pengalaman 1 tahun.',
                    'tanggung_jawab'   => 'Membangun UI responsif, integrasi API, testing.',
                    'benefit'          => 'BPJS, THR, Flexible Hour',
                    'paket_id'         => $silver->id,
                    'published_at'     => now(),
                    'expired_at'       => now()->addDays(30),
                ]
            );

            LowonganPerusahaan::updateOrCreate(
                ['slug' => 'qa-tester-test'],
                [
                    'perusahaan_id'    => $perusahaan->id,
                    'nama'             => 'QA Tester',
                    'slug'             => 'qa-tester-test',
                    'jenis'            => 'Full Time',
                    'gaji_awal'        => '3500000',
                    'gaji_akhir'       => '6000000',
                    'label_gaji'       => 'Rp 3.5jt - 6jt',
                    'deskripsi'        => '<p>Melakukan manual & automated testing untuk aplikasi web dan mobile.</p>',
                    'alamat'           => 'Surabaya, Jawa Timur',
                    'kategori'         => 'IT & Software',
                    'batas_lamaran'    => now()->addDays(30),
                    'syarat_pekerjaan' => 'Memahami SDLC, API testing dengan Postman, bug report terstruktur.',
                    'tanggung_jawab'   => 'Membuat test plan, test cases, dan validasi fungsional.',
                    'benefit'          => 'BPJS, THR, Bonus Kinerja',
                    'paket_id'         => $bronze->id,
                    'published_at'     => now(),
                    'expired_at'       => now()->addDays(7),
                ]
            );

            // =====================================================
            // 5. PELAMAR REGULER
            // =====================================================
            $userPelamar = User::updateOrCreate(
                ['email' => 'pelamar@areakerja.test'],
                [
                    'username'           => 'pelamar_test',
                    'nama_lengkap'       => 'Budi Santoso',
                    'telepon'            => '081298765432',
                    'password'           => Hash::make('password123'),
                    'role'               => 'pelamar',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            $pelamar = Pelamar::updateOrCreate(
                ['user_id' => $userPelamar->id],
                [
                    'nama_pelamar'    => 'Budi Santoso',
                    'telepon_pelamar' => '081298765432',
                    'gender'          => 'laki-laki',
                    'tanggal_lahir'   => '1998-05-15',
                    'deskripsi_diri'  => 'Saya adalah seorang developer dengan pengalaman 3 tahun di bidang web & backend development.',
                    'alamat'          => 'Jl. Sukolilo No. 45',
                    'kota'            => 'Surabaya',
                    'provinsi'        => 'Jawa Timur',
                    'skills'          => ['Laravel', 'MySQL', 'PHP', 'REST API', 'Git'],
                    'social_links'    => ['linkedin' => 'https://linkedin.com/in/budi-santoso', 'github' => 'https://github.com/budisantoso'],
                    'img_profile'     => null,
                    'gaji_minimal'    => '5000000',
                    'gaji_maksimal'   => '10000000',
                    'divisi'          => ['Backend', 'Full Stack'],
                    'kategori'        => 'pelamar',
                ]
            );

            RiwayatPendidikan::updateOrCreate(
                ['pelamar_id' => $pelamar->id, 'asal_pendidikan' => 'Universitas Brawijaya'],
                [
                    'pendidikan'     => 'S1',
                    'jurusan'        => 'Teknik Informatika',
                    'asal_pendidikan'=> 'Universitas Brawijaya',
                    'tahun_awal'     => '2016',
                    'tahun_akhir'    => '2020',
                ]
            );

            PengalamanKerja::updateOrCreate(
                ['pelamar_id' => $pelamar->id, 'nama_perusahaan' => 'PT Maju Bersama'],
                [
                    'posisi_pekerjaan'  => 'Junior Backend Developer',
                    'jabatan_pekerjaan' => 'Staff',
                    'tahun_awal'        => '2020',
                    'tahun_akhir'       => '2023',
                    'deskripsi'         => 'Mengembangkan REST API menggunakan Laravel dan MySQL.',
                ]
            );

            // =====================================================
            // 6. KANDIDAT AKTIF
            // =====================================================
            $userKandidat = User::updateOrCreate(
                ['email' => 'kandidat@areakerja.test'],
                [
                    'username'           => 'kandidat_test',
                    'nama_lengkap'       => 'Siti Rahayu',
                    'telepon'            => '081312345678',
                    'password'           => Hash::make('password123'),
                    'role'               => 'pelamar',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            $kandidat = Pelamar::updateOrCreate(
                ['user_id' => $userKandidat->id],
                [
                    'nama_pelamar'      => 'Siti Rahayu',
                    'telepon_pelamar'   => '081312345678',
                    'gender'            => 'perempuan',
                    'tanggal_lahir'     => '2000-08-20',
                    'deskripsi_diri'    => 'Frontend developer dengan keahlian Vue.js dan Flutter. Memiliki sertifikasi UI/UX.',
                    'alamat'            => 'Jl. Dharmawangsa No. 12',
                    'kota'              => 'Surabaya',
                    'provinsi'          => 'Jawa Timur',
                    'skills'            => ['Vue.js', 'Flutter', 'Figma', 'JavaScript', 'CSS3'],
                    'social_links'      => ['linkedin' => 'https://linkedin.com/in/siti-rahayu', 'github' => 'https://github.com/sitirahayu'],
                    'img_profile'       => null,
                    'gaji_minimal'      => '4000000',
                    'gaji_maksimal'     => '8000000',
                    'divisi'            => ['Frontend', 'UI/UX'],
                    'kategori'          => 'kandidat aktif',
                    'mulai_pelatihan'   => now()->subDays(10),
                    'selesai_pelatihan' => now()->addDays(80),
                ]
            );

            RiwayatPendidikan::updateOrCreate(
                ['pelamar_id' => $kandidat->id, 'asal_pendidikan' => 'Institut Teknologi Sepuluh Nopember'],
                [
                    'pendidikan'      => 'S1',
                    'jurusan'         => 'Sistem Informasi',
                    'asal_pendidikan' => 'Institut Teknologi Sepuluh Nopember',
                    'tahun_awal'      => '2018',
                    'tahun_akhir'     => '2022',
                ]
            );
        });
    }
}
