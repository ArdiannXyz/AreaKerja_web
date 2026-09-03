<?php

namespace Database\Seeders;

use App\Models\AlamatPelamar;
use App\Models\DaftarBank;
use App\Models\LowonganPerusahaan;
use App\Models\PaketLowongan;
use App\Models\Pelamar;
use App\Models\PengalamanKerja;
use App\Models\PengalamanOrganisasi;
use App\Models\Perusahaan;
use App\Models\RiwayatPendidikan;
use App\Models\TipsKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTestSeeder extends Seeder
{
    /**
     * Seeder untuk 12 Tabel Inti & Testing Akun Semua Role.
     * Termasuk: 1 Super Admin, 1 Admin, 1 Finance, 6 Perusahaan, 6 Pelamar, 6 Kandidat, & 5 Artikel Tips Kerja.
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
            // 1. SUPER ADMIN (1 User)
            // =====================================================
            User::updateOrCreate(
                ['email' => 'superadmin@gmail.com'],
                [
                    'username'           => 'superadmin',
                    'nama_lengkap'       => 'Super Admin AreaKerja',
                    'telepon'            => '081122334455',
                    'password'           => Hash::make('123'),
                    'role'               => 'super_admin',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            // =====================================================
            // 2. ADMIN (1 User)
            // =====================================================
            User::updateOrCreate(
                ['email' => 'admin@gmail.com'],
                [
                    'username'           => 'admin',
                    'nama_lengkap'       => 'Admin Operasional',
                    'telepon'            => '081122334466',
                    'password'           => Hash::make('123'),
                    'role'               => 'admin',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            // =====================================================
            // 3. FINANCE (1 User)
            // =====================================================
            User::updateOrCreate(
                ['email' => 'finance@gmail.com'],
                [
                    'username'           => 'finance',
                    'nama_lengkap'       => 'Finance Manager',
                    'telepon'            => '081122334477',
                    'password'           => Hash::make('123'),
                    'role'               => 'finance',
                    'verified'           => 1,
                    'status'             => 0,
                    'alasan_freeze_akun' => null,
                ]
            );

            // =====================================================
            // 4. PERUSAHAAN (6 Companies)
            // =====================================================
            $perusahaansData = [
                [
                    'email'    => 'perusahaan1@areakerja.test',
                    'username' => 'areakerja_tech',
                    'nama'     => 'PT. AreaKerja Teknologi',
                    'jenis'    => 'Teknologi Informasi',
                    'kota'     => 'Surabaya',
                    'provinsi' => 'Jawa Timur',
                    'lowongans' => [
                        [
                            'nama' => 'Backend Developer',
                            'jenis' => 'Full Time',
                            'gaji_awal' => '5000000',
                            'gaji_akhir' => '10000000',
                            'kategori' => 'IT & Software',
                            'paket' => $gold,
                        ],
                        [
                            'nama' => 'Frontend Developer',
                            'jenis' => 'Full Time',
                            'gaji_awal' => '4000000',
                            'gaji_akhir' => '8000000',
                            'kategori' => 'IT & Software',
                            'paket' => $silver,
                        ],
                    ]
                ],
                [
                    'email'    => 'perusahaan2@areakerja.test',
                    'username' => 'nusantara_digital',
                    'nama'     => 'PT Nusantara Digital Solutions',
                    'jenis'    => 'Digital Marketing & Agency',
                    'kota'     => 'Jakarta Selatan',
                    'provinsi' => 'DKI Jakarta',
                    'lowongans' => [
                        [
                            'nama' => 'Digital Marketing Specialist',
                            'jenis' => 'Full Time',
                            'gaji_awal' => '4500000',
                            'gaji_akhir' => '7500000',
                            'kategori' => 'Marketing',
                            'paket' => $gold,
                        ],
                        [
                            'nama' => 'Content Creator & Copywriter',
                            'jenis' => 'Contract',
                            'gaji_awal' => '3500000',
                            'gaji_akhir' => '5500000',
                            'kategori' => 'Media & Komunikasi',
                            'paket' => $bronze,
                        ],
                    ]
                ],
                [
                    'email'    => 'perusahaan3@areakerja.test',
                    'username' => 'inovasi_karya',
                    'nama'     => 'PT Inovasi Karya Media',
                    'jenis'    => 'Desain & Desain Grafis',
                    'kota'     => 'Bandung',
                    'provinsi' => 'Jawa Barat',
                    'lowongans' => [
                        [
                            'nama' => 'UI/UX Designer Senior',
                            'jenis' => 'Full Time',
                            'gaji_awal' => '6000000',
                            'gaji_akhir' => '11000000',
                            'kategori' => 'Desain & Kreatif',
                            'paket' => $silver,
                        ],
                    ]
                ],
                [
                    'email'    => 'perusahaan4@areakerja.test',
                    'username' => 'mitra_sejahtera',
                    'nama'     => 'PT Mitra Sejahtera Abadi',
                    'jenis'    => 'Keuangan & Perbankan',
                    'kota'     => 'Semarang',
                    'provinsi' => 'Jawa Tengah',
                    'lowongans' => [
                        [
                            'nama' => 'Staff Akuntansi & Finance',
                            'jenis' => 'Full Time',
                            'gaji_awal' => '4000000',
                            'gaji_akhir' => '6500000',
                            'kategori' => 'Keuangan',
                            'paket' => $bronze,
                        ],
                    ]
                ],
                [
                    'email'    => 'perusahaan5@areakerja.test',
                    'username' => 'techindo_cloud',
                    'nama'     => 'PT Techindo Cloud Indonesia',
                    'jenis'    => 'Cloud Infrastructure & DevOps',
                    'kota'     => 'Yogyakarta',
                    'provinsi' => 'DI Yogyakarta',
                    'lowongans' => [
                        [
                            'nama' => 'DevOps Engineer',
                            'jenis' => 'Full Time',
                            'gaji_awal' => '7000000',
                            'gaji_akhir' => '13000000',
                            'kategori' => 'IT & Software',
                            'paket' => $gold,
                        ],
                    ]
                ],
                [
                    'email'    => 'perusahaan6@areakerja.test',
                    'username' => 'sukses_gemilang',
                    'nama'     => 'PT Sukses Gemilang Utama',
                    'jenis'    => 'Manufaktur & Logistik',
                    'kota'     => 'Sidoarjo',
                    'provinsi' => 'Jawa Timur',
                    'lowongans' => [
                        [
                            'nama' => 'Supervisor Logistik',
                            'jenis' => 'Full Time',
                            'gaji_awal' => '5000000',
                            'gaji_akhir' => '8500000',
                            'kategori' => 'Logistik & Gudang',
                            'paket' => $silver,
                        ],
                    ]
                ],
            ];

            foreach ($perusahaansData as $index => $pData) {
                $uPerusahaan = User::updateOrCreate(
                    ['email' => $pData['email']],
                    [
                        'username'           => $pData['username'],
                        'nama_lengkap'       => 'HRD ' . $pData['nama'],
                        'telepon'            => '081234567' . ($index + 1),
                        'password'           => Hash::make('password123'),
                        'role'               => 'perusahaan',
                        'verified'           => 1,
                        'status'             => 0,
                        'alasan_freeze_akun' => null,
                    ]
                );

                $pModel = Perusahaan::updateOrCreate(
                    ['user_id' => $uPerusahaan->id],
                    [
                        'nama_perusahaan'     => $pData['nama'],
                        'slug'                => Str::slug($pData['nama']),
                        'jenis_perusahaan'    => $pData['jenis'],
                        'website_perusahaan'  => 'https://' . Str::slug($pData['nama']) . '.com',
                        'telepon_perusahaan'  => '081234567' . ($index + 1),
                        'whatsapp'            => '081234567' . ($index + 1),
                        'legalitas'           => 'PT',
                        'deskripsi'           => 'Perusahaan terkemuka di bidang ' . $pData['jenis'] . ' dengan fokus inovasi dan pelayanan terbaik.',
                        'visi'                => 'Menjadi perusahaan terbaik dan terdepan di Indonesia.',
                        'misi'                => 'Memberikan produk dan layanan berkualitas bagi masyarakat.',
                        'alamat'              => 'Jl. Industri Utama No. ' . (($index + 1) * 10),
                        'kota'                => $pData['kota'],
                        'provinsi'            => $pData['provinsi'],
                        'img_profile'         => null,
                        'verification_status' => 'approved',
                        'verified_at'          => now(),
                        'koin_perusahaan'      => 500,
                        'is_berlangganan'      => 0,
                    ]
                );

                foreach ($pData['lowongans'] as $lData) {
                    LowonganPerusahaan::updateOrCreate(
                        ['slug' => Str::slug($lData['nama'] . '-' . $pModel->id)],
                        [
                            'perusahaan_id'    => $pModel->id,
                            'nama'             => $lData['nama'],
                            'slug'             => Str::slug($lData['nama'] . '-' . $pModel->id),
                            'jenis'            => $lData['jenis'],
                            'gaji_awal'        => $lData['gaji_awal'],
                            'gaji_akhir'       => $lData['gaji_akhir'],
                            'label_gaji'       => 'Rp ' . number_format($lData['gaji_awal'], 0, ',', '.') . ' - ' . number_format($lData['gaji_akhir'], 0, ',', '.'),
                            'deskripsi'        => '<p>Kami membuka kesempatan karir sebagai ' . $lData['nama'] . ' di ' . $pModel->nama_perusahaan . '.</p>',
                            'alamat'           => $pModel->kota . ', ' . $pModel->provinsi,
                            'kategori'         => $lData['kategori'],
                            'batas_lamaran'    => now()->addDays(30),
                            'syarat_pekerjaan' => 'Minimal D3/S1 bidang relevan, pengalaman minimal 1 tahun.',
                            'tanggung_jawab'   => 'Menjalankan tugas operasional dan pengembangan tim sesuai divisi.',
                            'benefit'          => 'BPJS Kesehatan & Ketenagakerjaan, Bonus Kinerja, THR',
                            'paket_id'         => $lData['paket']->id,
                            'published_at'     => now(),
                            'expired_at'       => now()->addDays($lData['paket']->batas_listing),
                        ]
                    );
                }
            }

            // =====================================================
            // 5. PELAMAR REGULER (6 Applicants)
            // =====================================================
            $pelamarsData = [
                [
                    'username' => 'budi_santoso',
                    'email'    => 'pelamar1@areakerja.test',
                    'nama'     => 'Budi Santoso',
                    'gender'   => 'laki-laki',
                    'lahir'    => '1998-05-15',
                    'skills'   => ['Laravel', 'PHP', 'MySQL', 'Git', 'REST API'],
                    'univ'     => 'Universitas Brawijaya',
                    'jurusan'  => 'Teknik Informatika',
                    'pt'       => 'PT Maju Bersama',
                    'posisi'   => 'Junior Backend Developer',
                    'org'      => 'Himpunan Mahasiswa Informatika',
                    'jabatan'  => 'Ketua Divisi Humas',
                ],
                [
                    'username' => 'ahmad_rizky',
                    'email'    => 'pelamar2@areakerja.test',
                    'nama'     => 'Ahmad Rizky Pratama',
                    'gender'   => 'laki-laki',
                    'lahir'    => '1999-08-20',
                    'skills'   => ['React.js', 'Node.js', 'TailwindCSS', 'JavaScript'],
                    'univ'     => 'Universitas Gadjah Mada',
                    'jurusan'  => 'Ilmu Komputer',
                    'pt'       => 'PT Solusi Tekno',
                    'posisi'   => 'Frontend Engineer',
                    'org'      => 'Unit Kegiatan Mahasiswa Komputer',
                    'jabatan'  => 'Wakil Ketua',
                ],
                [
                    'username' => 'dewi_lestari',
                    'email'    => 'pelamar3@areakerja.test',
                    'nama'     => 'Dewi Lestari',
                    'gender'   => 'perempuan',
                    'lahir'    => '2001-02-10',
                    'skills'   => ['Figma', 'UI/UX Design', 'Wireframing', 'Prototyping'],
                    'univ'     => 'Institut Teknologi Bandung',
                    'jurusan'  => 'Desain Komunikasi Visual',
                    'pt'       => 'PT Creative House',
                    'posisi'   => 'UI/UX Intern',
                    'org'      => 'Komunitas Desainer Muda',
                    'jabatan'  => 'Anggota Divisi Konten',
                ],
                [
                    'username' => 'fajar_pratama',
                    'email'    => 'pelamar4@areakerja.test',
                    'nama'     => 'Fajar Pratama',
                    'gender'   => 'laki-laki',
                    'lahir'    => '1997-11-03',
                    'skills'   => ['Python', 'Data Analysis', 'SQL', 'Tableau'],
                    'univ'     => 'Universitas Indonesia',
                    'jurusan'  => 'Statistika',
                    'pt'       => 'PT Data Analitika',
                    'posisi'   => 'Data Analyst Staff',
                    'org'      => 'Himpunan Mahasiswa Statistika',
                    'jabatan'  => 'Sekretaris',
                ],
                [
                    'username' => 'rina_indah',
                    'email'    => 'pelamar5@areakerja.test',
                    'nama'     => 'Rina Indah Permata',
                    'gender'   => 'perempuan',
                    'lahir'    => '2000-04-12',
                    'skills'   => ['Digital Marketing', 'SEO', 'Google Ads', 'Copywriting'],
                    'univ'     => 'Universitas Airlangga',
                    'jurusan'  => 'Ilmu Komunikasi',
                    'pt'       => 'PT Media Nusantara',
                    'posisi'   => 'Content Marketer',
                    'org'      => 'Pers Mahasiswa Kampus',
                    'jabatan'  => 'Pemimpin Redaksi',
                ],
                [
                    'username' => 'eko_prasetyo',
                    'email'    => 'pelamar6@areakerja.test',
                    'nama'     => 'Eko Prasetyo',
                    'gender'   => 'laki-laki',
                    'lahir'    => '1996-09-25',
                    'skills'   => ['Akuntansi', 'MYOB', 'Taxation', 'Excel Advanced'],
                    'univ'     => 'Universitas Diponegoro',
                    'jurusan'  => 'Akuntansi',
                    'pt'       => 'KANTOR AKUNTAN PUBLIK',
                    'posisi'   => 'Junior Auditor',
                    'org'      => 'Koperasi Mahasiswa',
                    'jabatan'  => 'Bendahara',
                ],
            ];

            foreach ($pelamarsData as $pData) {
                $uPelamar = User::updateOrCreate(
                    ['email' => $pData['email']],
                    [
                        'username'           => $pData['username'],
                        'nama_lengkap'       => $pData['nama'],
                        'telepon'            => '0812987' . rand(10000, 99999),
                        'password'           => Hash::make('password123'),
                        'role'               => 'pelamar',
                        'verified'           => 1,
                        'status'             => 0,
                        'alasan_freeze_akun' => null,
                    ]
                );

                $pelamarModel = Pelamar::updateOrCreate(
                    ['user_id' => $uPelamar->id],
                    [
                        'nama_pelamar'    => $pData['nama'],
                        'telepon_pelamar' => $uPelamar->telepon,
                        'gender'          => $pData['gender'],
                        'tanggal_lahir'   => $pData['lahir'],
                        'deskripsi_diri'  => 'Saya adalah profesional muda yang berdedikasi tinggi di bidang ' . $pData['jurusan'] . '.',
                        'alamat'          => 'Jl. Pemuda No. ' . rand(1, 100),
                        'kota'            => 'Surabaya',
                        'provinsi'        => 'Jawa Timur',
                        'skills'          => $pData['skills'],
                        'social_links'    => ['linkedin' => 'https://linkedin.com/in/' . $pData['username']],
                        'img_profile'     => null,
                        'gaji_minimal'    => '4000000',
                        'gaji_maksimal'   => '8000000',
                        'divisi'          => [$pData['posisi']],
                        'kategori'        => 'pelamar',
                    ]
                );

                RiwayatPendidikan::updateOrCreate(
                    ['pelamar_id' => $pelamarModel->id, 'asal_pendidikan' => $pData['univ']],
                    [
                        'pendidikan'     => 'S1',
                        'jurusan'        => $pData['jurusan'],
                        'asal_pendidikan'=> $pData['univ'],
                        'tahun_awal'     => '2016',
                        'tahun_akhir'    => '2020',
                    ]
                );

                PengalamanKerja::updateOrCreate(
                    ['pelamar_id' => $pelamarModel->id, 'nama_perusahaan' => $pData['pt']],
                    [
                        'posisi_pekerjaan'  => $pData['posisi'],
                        'jabatan_pekerjaan' => 'Staff',
                        'tahun_awal'        => '2020',
                        'tahun_akhir'       => '2023',
                        'deskripsi'         => 'Bertanggung jawab atas pekerjaan divisi ' . $pData['posisi'] . '.',
                    ]
                );

                PengalamanOrganisasi::updateOrCreate(
                    ['pelamar_id' => $pelamarModel->id, 'nama_organisasi' => $pData['org']],
                    [
                        'jabatan'         => $pData['jabatan'],
                        'tahun_awal'      => '2018',
                        'tahun_akhir'     => '2020',
                        'deskripsi'       => 'Mengatur dan menyelenggarakan berbagai event organisasi kampus.',
                    ]
                );

                AlamatPelamar::updateOrCreate(
                    ['pelamar_id' => $pelamarModel->id, 'label' => 'Alamat Rumah'],
                    [
                        'desa'      => 'Gubeng',
                        'kecamatan' => 'Gubeng',
                        'kota'      => 'Surabaya',
                        'provinsi'  => 'Jawa Timur',
                        'kode_pos'  => '60281',
                        'detail'    => 'Jl. Pemuda No. ' . rand(1, 100),
                        'is_primary'=> 1,
                    ]
                );
            }

            // =====================================================
            // 6. KANDIDAT AKTIF & CALON KANDIDAT (6 Candidates)
            // =====================================================
            $kandidatsData = [
                [
                    'username' => 'siti_rahayu',
                    'email'    => 'kandidat1@areakerja.test',
                    'nama'     => 'Siti Rahayu',
                    'gender'   => 'perempuan',
                    'kategori' => 'kandidat aktif',
                    'skills'   => ['Vue.js', 'Flutter', 'Figma', 'CSS3'],
                    'univ'     => 'Institut Teknologi Sepuluh Nopember',
                    'jurusan'  => 'Sistem Informasi',
                ],
                [
                    'username' => 'doni_kurniawan',
                    'email'    => 'kandidat2@areakerja.test',
                    'nama'     => 'Doni Kurniawan',
                    'gender'   => 'laki-laki',
                    'kategori' => 'kandidat aktif',
                    'skills'   => ['Java', 'Spring Boot', 'PostgreSQL', 'Docker'],
                    'univ'     => 'Universitas Brawijaya',
                    'jurusan'  => 'Teknik Komputer',
                ],
                [
                    'username' => 'maya_kartika',
                    'email'    => 'kandidat3@areakerja.test',
                    'nama'     => 'Maya Kartika',
                    'gender'   => 'perempuan',
                    'kategori' => 'calon kandidat',
                    'skills'   => ['Human Resources', 'Recruitment', 'Psychotest'],
                    'univ'     => 'Universitas Padjadjaran',
                    'jurusan'  => 'Psikologi',
                ],
                [
                    'username' => 'agus_wijaya',
                    'email'    => 'kandidat4@areakerja.test',
                    'nama'     => 'Agus Wijaya',
                    'gender'   => 'laki-laki',
                    'kategori' => 'kandidat aktif',
                    'skills'   => ['Flutter', 'Dart', 'Android Studio', 'Firebase'],
                    'univ'     => 'Universitas Sebelas Maret',
                    'jurusan'  => 'Informatika',
                ],
                [
                    'username' => 'nabila_putri',
                    'email'    => 'kandidat5@areakerja.test',
                    'nama'     => 'Nabila Putri',
                    'gender'   => 'perempuan',
                    'kategori' => 'calon kandidat',
                    'skills'   => ['Social Media Specialist', 'TikTok Marketing', 'Canva'],
                    'univ'     => 'Universitas Negeri Yogyakarta',
                    'jurusan'  => 'Ilmu Komunikasi',
                ],
                [
                    'username' => 'hendra_saputra',
                    'email'    => 'kandidat6@areakerja.test',
                    'nama'     => 'Hendra Saputra',
                    'gender'   => 'laki-laki',
                    'kategori' => 'kandidat aktif',
                    'skills'   => ['Network Engineer', 'Cisco CCNA', 'Mikrotik', 'Linux'],
                    'univ'     => 'Universitas Telkom',
                    'jurusan'  => 'Teknik Telekomunikasi',
                ],
            ];

            foreach ($kandidatsData as $kData) {
                $uKandidat = User::updateOrCreate(
                    ['email' => $kData['email']],
                    [
                        'username'           => $kData['username'],
                        'nama_lengkap'       => $kData['nama'],
                        'telepon'            => '0813123' . rand(10000, 99999),
                        'password'           => Hash::make('password123'),
                        'role'               => 'pelamar',
                        'verified'           => 1,
                        'status'             => 0,
                        'alasan_freeze_akun' => null,
                    ]
                );

                $kandidatModel = Pelamar::updateOrCreate(
                    ['user_id' => $uKandidat->id],
                    [
                        'nama_pelamar'      => $kData['nama'],
                        'telepon_pelamar'   => $uKandidat->telepon,
                        'gender'            => $kData['gender'],
                        'tanggal_lahir'     => '1999-10-10',
                        'deskripsi_diri'    => 'Kandidat profesional terverifikasi Areakerja dengan keahlian utama di bidang ' . $kData['jurusan'] . '.',
                        'alamat'            => 'Jl. Raya Darmo No. ' . rand(1, 100),
                        'kota'              => 'Surabaya',
                        'provinsi'          => 'Jawa Timur',
                        'skills'            => $kData['skills'],
                        'social_links'      => ['linkedin' => 'https://linkedin.com/in/' . $kData['username']],
                        'img_profile'       => null,
                        'gaji_minimal'      => '5000000',
                        'gaji_maksimal'     => '10000000',
                        'divisi'            => ['Kandidat Spesialis'],
                        'kategori'          => $kData['kategori'],
                        'mulai_pelatihan'   => now()->subDays(15),
                        'selesai_pelatihan' => now()->addDays(75),
                    ]
                );

                RiwayatPendidikan::updateOrCreate(
                    ['pelamar_id' => $kandidatModel->id, 'asal_pendidikan' => $kData['univ']],
                    [
                        'pendidikan'      => 'S1',
                        'jurusan'         => $kData['jurusan'],
                        'asal_pendidikan' => $kData['univ'],
                        'tahun_awal'      => '2017',
                        'tahun_akhir'     => '2021',
                    ]
                );

                PengalamanKerja::updateOrCreate(
                    ['pelamar_id' => $kandidatModel->id, 'nama_perusahaan' => 'PT Kandidat Nusantara'],
                    [
                        'posisi_pekerjaan'  => 'Spesialis ' . $kData['jurusan'],
                        'jabatan_pekerjaan' => 'Senior Staff',
                        'tahun_awal'        => '2021',
                        'tahun_akhir'       => '2023',
                        'deskripsi'         => 'Mengelola proyek teknis dan operasional utama.',
                    ]
                );

                PengalamanOrganisasi::updateOrCreate(
                    ['pelamar_id' => $kandidatModel->id, 'nama_organisasi' => 'Ikatan Alumni ' . $kData['univ']],
                    [
                        'jabatan'         => 'Koordinator Wilayah',
                        'tahun_awal'      => '2021',
                        'tahun_akhir'     => '2023',
                        'deskripsi'       => 'Menghubungkan alumni dan mahasiswa untuk kesempatan karir.',
                    ]
                );

                AlamatPelamar::updateOrCreate(
                    ['pelamar_id' => $kandidatModel->id, 'label' => 'Alamat Rumah'],
                    [
                        'desa'      => 'Darmo',
                        'kecamatan' => 'Wonokromo',
                        'kota'      => 'Surabaya',
                        'provinsi'  => 'Jawa Timur',
                        'kode_pos'  => '60241',
                        'detail'    => 'Jl. Raya Darmo No. ' . rand(1, 100),
                        'is_primary'=> 1,
                    ]
                );
            }

            // =====================================================
            // 7. ARTIKEL TIPS KERJA (5 Articles)
            // =====================================================
            $tipsData = [
                [
                    'title'   => 'Cara Membuat CV Professional yang Dilirik HRD Perusahaan Besar',
                    'intro'   => 'CV merupakan pintu gerbang pertama dalam proses rekrutmen. Ketahui rahasia menyusun CV ATS-friendly yang efektif memikat perhatian recruiter.',
                    'content' => '<p>Curriculum Vitae (CV) adalah dokumen kunci dalam melamar pekerjaan. Pastikan Anda menuliskan informasi kontak yang jelas, ringkasan profil singkat yang menjual, serta daftar pengalaman kerja yang relevan dengan metode STAR (Situation, Task, Action, Result).</p><p>Hindari penggunaan grafik berlebihan jika Anda melamar ke perusahaan korporasi, dan fokuslah pada pencapaian konkret dalam bentuk angka atau persentase.</p>',
                    'penulis' => 'Tim HR AreaKerja',
                    'status'  => 'terbit',
                    'kategori'=> 'Tips Karir',
                ],
                [
                    'title'   => 'Tips Menjawab Pertanyaan Interview Kerja dengan Metode STAR',
                    'intro'   => 'Sering bingung menjawab pertanyaan wawancara berbasis perilaku? Gunakan teknik STAR untuk menyusun jawaban yang terstruktur dan persuasif.',
                    'content' => '<p>Metode STAR (Situation, Task, Action, Result) adalah teknik paling efektif dalam menjawab pertanyaan wawancara kerja berbasis pengalaman nyata.</p><p>1. Situation: Jelaskan latar belakang masalah.<br>2. Task: Sebutkan tugas atau tantangan yang harus diselesaikan.<br>3. Action: Uraikan langkah spesifik yang Anda ambil.<br>4. Result: Tunjukkan hasil positif dari tindakan Anda.</p>',
                    'penulis' => 'Senior Career Coach',
                    'status'  => 'terbit',
                    'kategori'=> 'Interview',
                ],
                [
                    'title'   => 'Strategi Negosiasi Gaji untuk Fresh Graduate Agar Tidak Dirugikan',
                    'intro'   => 'Negosiasi gaji seringkali menakutkan bagi lulusan baru. Simak panduan riset standar industri dan cara menyampaikan ekspektasi gaji secara sopan.',
                    'content' => '<p>Sebagai lulusan baru, jangan ragu untuk melakukan riset standar gaji industri di kota tempat Anda melamar. Gunakan platform seperti Areakerja untuk membandingkan ekspektasi gaji.</p><p>Ketika ditanya ekspektasi gaji, berikan rentang angka dan tegaskan bahwa angka tersebut fleksibel tergantung benefit yang diberikan perusahaan.</p>',
                    'penulis' => 'Redaksi AreaKerja',
                    'status'  => 'terbit',
                    'kategori'=> 'Gaji & Benefit',
                ],
                [
                    'title'   => 'Pentingnya Soft Skills dalam Dunia Kerja Modern di Era AI',
                    'intro'   => 'Kemampuan teknis saja tidak lagi cukup. Soft skills seperti komunikasi, adaptabilitas, dan pemecahan masalah kini menjadi pembeda utama di tempat kerja.',
                    'content' => '<p>Di tengah pesatnya perkembangan kecerdasan buatan (AI), kemampuan interpersonal menjadi aset terbaik seorang pekerja. Kemampuan seperti kecerdasan emosional (EQ), kepemimpinan, dan negosiasi tidak dapat digantikan oleh mesin.</p><p>Tingkatkan terus soft skill Anda melalui kerja tim dan pengalaman organisasi.</p>',
                    'penulis' => 'Tim HR AreaKerja',
                    'status'  => 'terbit',
                    'kategori'=> 'Pengembangan Diri',
                ],
                [
                    'title'   => 'Persiapan Menghadapi Test Coding & Technical Assessment Developer',
                    'intro'   => 'Persiapkan diri Anda menghadapi uji kompetensi teknis programmer dengan latihan studi kasus, algoritma dasar, dan live coding.',
                    'content' => '<p>Tes teknis adalah tahap krusial bagi calon developer. Pelajari struktur data dasar, algoritma umum, dan pastikan Anda memahami arsitektur framework yang Anda gunakan.</p><p>Saat live coding, komunikasikan jalan pikiran Anda secara terbuka kepada interviewer.</p>',
                    'penulis' => 'Lead Tech Recruiter',
                    'status'  => 'terbit',
                    'kategori'=> 'Teknikal',
                ],
            ];

            foreach ($tipsData as $tData) {
                TipsKerja::updateOrCreate(
                    ['title' => $tData['title']],
                    [
                        'slug'     => Str::slug($tData['title']),
                        'intro'    => $tData['intro'],
                        'content'  => $tData['content'],
                        'penulis'  => $tData['penulis'],
                        'status'   => $tData['status'],
                        'kategori' => $tData['kategori'],
                        'image'    => null,
                    ]
                );
            }
        });
    }
}
