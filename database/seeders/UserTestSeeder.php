<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Finance;
use App\Models\Hargakoin;
use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    /**
     * Seeder untuk testing semua role pengguna AreaKerja.
     *
     * Akun yang dibuat:
     *  - super_admin : superadmin / password123
     *  - admin       : admin      / password123
     *  - finance     : finance    / password123
     *  - perusahaan  : perusahaan / password123  (sudah verified, punya 500 koin)
     *  - pelamar     : pelamar    / password123  (profil lengkap, kategori pelamar)
     *  - kandidat    : kandidat   / password123  (profil lengkap, kategori kandidat aktif)
     */
    public function run(): void
    {
        DB::transaction(function () {

            // =====================================================
            // 1. SUPER ADMIN
            // =====================================================
            $userSuperAdmin = User::updateOrCreate(
                ['username' => 'superadmin_test'],
                [
                    'email'              => 'superadmin@areakerja.test',
                    'password'           => Hash::make('password123'),
                    'role'               => 'super_admin',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            SuperAdmin::updateOrCreate(
                ['user_id' => $userSuperAdmin->id],
                [
                    'nama_lengkap' => 'Super Admin Test',
                    'provinsi'     => 'Jawa Timur',
                ]
            );


            // =====================================================
            // 2. ADMIN
            // =====================================================
            $userAdmin = User::updateOrCreate(
                ['username' => 'admin_test'],
                [
                    'email'              => 'admin@areakerja.test',
                    'password'           => Hash::make('password123'),
                    'role'               => 'admin',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            Admin::updateOrCreate(
                ['user_id' => $userAdmin->id],
                [
                    'nama_lengkap' => 'Admin Test',
                    'provinsi_id'  => \App\Models\Provinsi::inRandomOrder()->first()?->id,
                    'kota_id'      => \App\Models\Kota::inRandomOrder()->first()?->id,
                    'kecamatan_id' => \App\Models\Kecamatan::inRandomOrder()->first()?->id,
                ]
            );


            // =====================================================
            // 3. FINANCE
            // =====================================================
            $userFinance = User::updateOrCreate(
                ['username' => 'finance_test'],
                [
                    'email'              => 'finance@areakerja.test',
                    'password'           => Hash::make('password123'),
                    'role'               => 'finance',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            Finance::updateOrCreate(
                ['user_id' => $userFinance->id],
                [
                    'nama_lengkap' => 'Finance Test',
                    'provinsi_id'  => \App\Models\Provinsi::inRandomOrder()->first()?->id,
                    'kota_id'      => \App\Models\Kota::inRandomOrder()->first()?->id,
                    'kecamatan_id' => \App\Models\Kecamatan::inRandomOrder()->first()?->id,
                ]
            );


            // =====================================================
            // 4. PERUSAHAAN (sudah terverifikasi, punya 500 koin)
            // =====================================================
            $userPerusahaan = User::updateOrCreate(
                ['username' => 'perusahaan_test'],
                [
                    'email'              => 'perusahaan@areakerja.test',
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
                    'jenis_perusahaan'   => 'Teknologi Informasi',
                    'website_perusahaan' => 'https://areakerja.test',
                    'telepon_perusahaan' => '0812345678',
                    'whatsapp'           => '0812345678',
                    'legalitas'          => 'PT',
                    'deskripsi'          => 'Perusahaan teknologi yang bergerak di bidang pengembangan platform rekrutmen digital.',
                    'visi'               => 'Menjadi platform rekrutmen terpercaya di Indonesia.',
                    'misi'               => 'Menghubungkan perusahaan dengan kandidat terbaik secara efisien.',
                    'img_profile'        => null,
                    // Field sistem — diset langsung bukan lewat fillable
                    'verification_status' => 'approved',
                    'verified_at'         => now(),
                    'koin_perusahaan'     => 500,
                    'is_berlangganan'     => 0,
                ]
            );

            // Buat 2 lowongan untuk perusahaan test
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
                    'deskripsi'        => '<p>Kami mencari Backend Developer berpengalaman untuk bergabung bersama tim kami.</p>',
                    'alamat'           => 'Surabaya, Jawa Timur',
                    'kategori'         => 'IT & Software',
                    'batas_lamaran'    => now()->addDays(30),
                    'syarat_pekerjaan' => 'Minimal S1 Teknik Informatika, pengalaman 2 tahun Laravel, menguasai MySQL.',
                    'tanggung_jawab'   => 'Mengembangkan REST API, optimasi database, code review.',
                    'benefit'          => 'BPJS, THR, Remote Work, Laptop',
                    'paket_id'         => \App\Models\PaketLowongan::where('nama', 'Gold')->first()?->id,
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
                    'syarat_pekerjaan' => 'Minimal D3/S1, menguasai Vue.js atau React, pengalaman 1 tahun.',
                    'tanggung_jawab'   => 'Membangun UI responsif, integrasi API, testing.',
                    'benefit'          => 'BPJS, THR, Flexible Hour',
                    'paket_id'         => \App\Models\PaketLowongan::where('nama', 'Silver')->first()?->id,
                    'published_at'     => now(),
                    'expired_at'       => now()->addDays(30),
                ]
            );


            // =====================================================
            // 5. PELAMAR BIASA (profil lengkap, belum jadi kandidat)
            // =====================================================
            $userPelamar = User::updateOrCreate(
                ['username' => 'pelamar_test'],
                [
                    'email'              => 'pelamar@areakerja.test',
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
                    'deskripsi_diri'  => 'Saya adalah seorang developer dengan pengalaman 3 tahun di bidang web development. Saya senang belajar teknologi baru dan bekerja dalam tim.',
                    'img_profile'     => null,
                    'gaji_minimal'    => '5000000',
                    'gaji_maksimal'   => '10000000',
                    'divisi'          => json_encode(['Backend', 'Full Stack']),
                    // 'kategori' diset langsung
                    'kategori'        => 'pelamar',
                ]
            );

            // Buat riwayat pendidikan pelamar
            \App\Models\RiwayatPendidikan::updateOrCreate(
                ['pelamar_id' => $pelamar->id, 'asal_pendidikan' => 'Universitas Brawijaya'],
                [
                    'pendidikan'     => 'S1',
                    'jurusan'        => 'Teknik Informatika',
                    'asal_pendidikan'=> 'Universitas Brawijaya',
                    'tahun_awal'     => '2016',
                    'tahun_akhir'    => '2020',
                ]
            );

            // Buat skill pelamar
            \App\Models\Skill::updateOrCreate(
                ['pelamar_id' => $pelamar->id, 'skill' => 'Laravel'],
                ['experience_level' => 'Advanced']
            );
            \App\Models\Skill::updateOrCreate(
                ['pelamar_id' => $pelamar->id, 'skill' => 'MySQL'],
                ['experience_level' => 'Intermediate']
            );

            // Buat pengalaman kerja
            \App\Models\PengalamanKerja::updateOrCreate(
                ['pelamar_id' => $pelamar->id, 'nama_perusahaan' => 'PT Maju Bersama'],
                [
                    'posisi_pekerjaan' => 'Junior Backend Developer',
                    'jabatan_pekerjaan' => 'Staff',
                    'tahun_awal'       => '2020',
                    'tahun_akhir'      => '2023',
                    'deskripsi'        => 'Mengembangkan REST API menggunakan Laravel dan PostgreSQL.',
                ]
            );


            // =====================================================
            // 6. KANDIDAT AKTIF (profil lengkap, sudah bayar, diverifikasi)
            // =====================================================
            $userKandidat = User::updateOrCreate(
                ['username' => 'kandidat_test'],
                [
                    'email'              => 'kandidat@areakerja.test',
                    'password'           => Hash::make('password123'),
                    'role'               => 'pelamar',  // role tetap pelamar, beda di kolom kategori
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            $kandidat = Pelamar::updateOrCreate(
                ['user_id' => $userKandidat->id],
                [
                    'nama_pelamar'    => 'Siti Rahayu',
                    'telepon_pelamar' => '081312345678',
                    'gender'          => 'perempuan',
                    'tanggal_lahir'   => '2000-08-20',
                    'deskripsi_diri'  => 'Frontend developer dengan keahlian Vue.js dan React. Memiliki passion dalam UI/UX dan desain produk digital.',
                    'img_profile'     => null,
                    'gaji_minimal'    => '4000000',
                    'gaji_maksimal'   => '8000000',
                    'divisi'          => json_encode(['Frontend', 'UI/UX']),
                    // Sudah jadi kandidat aktif
                    'kategori'        => 'kandidat aktif',
                    'mulai_pelatihan' => now()->subDays(10),
                    'selesai_pelatihan' => now()->addDays(80),
                ]
            );

            // Riwayat pendidikan kandidat
            \App\Models\RiwayatPendidikan::updateOrCreate(
                ['pelamar_id' => $kandidat->id, 'asal_pendidikan' => 'Institut Teknologi Sepuluh Nopember'],
                [
                    'pendidikan'      => 'S1',
                    'jurusan'         => 'Sistem Informasi',
                    'asal_pendidikan' => 'Institut Teknologi Sepuluh Nopember',
                    'tahun_awal'      => '2018',
                    'tahun_akhir'     => '2022',
                ]
            );

            // Skill kandidat
            \App\Models\Skill::updateOrCreate(
                ['pelamar_id' => $kandidat->id, 'skill' => 'Vue.js'],
                ['experience_level' => 'Advanced']
            );
            \App\Models\Skill::updateOrCreate(
                ['pelamar_id' => $kandidat->id, 'skill' => 'Flutter'],
                ['experience_level' => 'Intermediate']
            );
            \App\Models\Skill::updateOrCreate(
                ['pelamar_id' => $kandidat->id, 'skill' => 'Figma'],
                ['experience_level' => 'Advanced']
            );

            // Tambah harga Beli Kandidat jika belum ada (perbaikan Bug #4)
            Hargakoin::updateOrCreate(
                ['nama' => 'Beli Kandidat'],
                ['harga' => 100]
            );

        });
    }
}
