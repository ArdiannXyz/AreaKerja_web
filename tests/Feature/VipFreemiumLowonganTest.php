<?php

namespace Tests\Feature;

use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\PengalamanKerja;
use App\Models\Perusahaan;
use App\Models\RiwayatPendidikan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VipFreemiumLowonganTest extends TestCase
{
    use DatabaseTransactions;

    private function createPelamar(): array
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'pelamar_' . $unique,
            'nama_lengkap' => 'Pelamar ' . $unique,
            'email'        => 'pelamar_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'pelamar',
            'status'       => 0,
            'verified'     => true,
        ]);

        $pelamar = Pelamar::create([
            'user_id'      => $user->id,
            'nama_pelamar' => 'Pelamar ' . $unique,
            'skills'       => ['PHP', 'Laravel'],
            'kategori'     => 'pelamar',
        ]);

        RiwayatPendidikan::create([
            'pelamar_id'      => $pelamar->id,
            'pendidikan'      => 'S1',
            'jurusan'         => 'Teknik Informatika',
            'asal_pendidikan' => 'Universitas Indonesia',
            'tahun_awal'      => 2018,
            'tahun_akhir'     => 2022,
        ]);

        PengalamanKerja::create([
            'pelamar_id'      => $pelamar->id,
            'posisi_pekerjaan'=> 'Junior Programmer',
            'nama_perusahaan' => 'PT Tech Indonesia',
            'tahun_awal'      => 2022,
            'tahun_akhir'     => 2024,
        ]);

        return [$user, $pelamar];
    }

    private function createPerusahaan(bool $isVip = false): array
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'pt_' . $unique,
            'nama_lengkap' => 'PT Mitra ' . $unique,
            'email'        => 'pt_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'perusahaan',
            'status'       => 0,
            'verified'     => true,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'             => $user->id,
            'nama_perusahaan'     => 'PT Mitra ' . $unique,
            'slug'                => 'pt-mitra-' . $unique,
            'koin_perusahaan'     => 0,
            'is_berlangganan'     => $isVip ? 1 : 0,
            'tanggal_expired'     => $isVip ? Carbon::now()->addMonths(6) : null,
            'verification_status' => 'approved',
        ]);

        return [$user, $perusahaan];
    }

    public function test_non_vip_company_can_post_up_to_2_jobs_with_7_days_expiry(): void
    {
        [$user, $perusahaan] = $this->createPerusahaan(isVip: false);

        $payload = [
            'nama'             => 'Web Developer',
            'alamat'           => 'Surabaya, Jawa Timur',
            'jenis'            => 'Fulltime',
            'kategori'         => 'IT & Software',
            'gaji_awal'        => 5000000,
            'gaji_akhir'       => 8000000,
            'deskripsi'        => 'Deskripsi pekerjaan web developer',
            'syarat_pekerjaan' => 'Minimal lulusan D3/S1 Informatika',
        ];

        // 1st Job
        $res1 = $this->actingAs($user)->post(route('lowongan.saya.store'), $payload);
        $res1->assertRedirect(route('lowongan.saya.perusahaan'));

        $job1 = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)->first();
        $this->assertNotNull($job1);
        $this->assertEquals(7, Carbon::parse($job1->published_at)->diffInDays($job1->expired_at));
        $this->assertNull($job1->boosted_until);

        // 2nd Job
        $payload['nama'] = 'Frontend Engineer';
        $res2 = $this->actingAs($user)->post(route('lowongan.saya.store'), $payload);
        $res2->assertRedirect(route('lowongan.saya.perusahaan'));

        // 3rd Job should be blocked because limit for free is 2
        $payload['nama'] = 'Backend Engineer';
        $res3 = $this->actingAs($user)->post(route('lowongan.saya.store'), $payload);
        $res3->assertSessionHasErrors(['kuota']);
        $this->assertEquals(2, $perusahaan->activeLowonganCount());
    }

    public function test_vip_company_can_post_with_30_days_expiry_and_auto_job_booster(): void
    {
        [$user, $perusahaan] = $this->createPerusahaan(isVip: true);

        $payload = [
            'nama'             => 'Senior Tech Lead',
            'alamat'           => 'Jakarta Pusat, DKI Jakarta',
            'jenis'            => 'Fulltime',
            'kategori'         => 'IT & Software',
            'gaji_awal'        => 15000000,
            'gaji_akhir'       => 25000000,
            'deskripsi'        => 'Memimpin tim engineering AreaKerja',
            'syarat_pekerjaan' => 'Pengalaman min 5 tahun arsitektur sistem',
        ];

        $res = $this->actingAs($user)->post(route('lowongan.saya.store'), $payload);
        $res->assertRedirect(route('lowongan.saya.perusahaan'));

        $job = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)->first();
        $this->assertNotNull($job);
        $this->assertEquals(30, Carbon::parse($job->published_at)->diffInDays($job->expired_at));
        $this->assertNotNull($job->boosted_until);
        $this->assertTrue(Carbon::parse($job->boosted_until)->isFuture());
    }

    public function test_non_vip_job_is_locked_after_5_applicants_on_the_same_day(): void
    {
        [$userComp, $perusahaan] = $this->createPerusahaan(isVip: false);

        $lowongan = LowonganPerusahaan::create([
            'perusahaan_id'    => $perusahaan->id,
            'nama'             => 'Marketing Specialist',
            'slug'             => 'marketing-specialist-' . uniqid(),
            'status'           => 'buka',
            'published_at'     => now(),
            'expired_at'       => now()->addDays(7),
            'batas_lamaran'    => now()->addDays(7),
            'gaji_awal'        => 4000000,
            'gaji_akhir'       => 6000000,
            'deskripsi'        => 'Staff Marketing',
            'syarat_pekerjaan' => 'Komunikatif',
            'alamat'           => 'Yogyakarta',
            'jenis'            => 'Fulltime',
            'kategori'         => 'Marketing',
        ]);

        // Submit 5 applicants today
        for ($i = 0; $i < 5; $i++) {
            [$userPelamar, $pelamar] = $this->createPelamar();
            $res = $this->actingAs($userPelamar)->postJson(route('lamar.cepat', $lowongan->id));
            $res->assertStatus(200)->assertJson(['success' => true]);
        }

        $this->assertEquals(5, $lowongan->today_applicants_count);
        $this->assertTrue($lowongan->isDailyLocked());

        // 6th applicant on the same day must be rejected with 429
        [$userPelamar6, $pelamar6] = $this->createPelamar();
        $res6 = $this->actingAs($userPelamar6)->postJson(route('lamar.cepat', $lowongan->id));
        $res6->assertStatus(429)->assertJson([
            'success'      => false,
            'daily_locked' => true,
        ]);
    }

    public function test_vip_job_allows_more_than_5_applicants_without_locking(): void
    {
        [$userComp, $perusahaan] = $this->createPerusahaan(isVip: true);

        $lowongan = LowonganPerusahaan::create([
            'perusahaan_id'    => $perusahaan->id,
            'nama'             => 'VIP Product Designer',
            'slug'             => 'vip-product-designer-' . uniqid(),
            'status'           => 'buka',
            'published_at'     => now(),
            'expired_at'       => now()->addDays(30),
            'batas_lamaran'    => now()->addDays(30),
            'gaji_awal'        => 8000000,
            'gaji_akhir'       => 12000000,
            'deskripsi'        => 'UI/UX Lead',
            'syarat_pekerjaan' => 'Figma Expert',
            'alamat'           => 'Jakarta Selatan',
            'jenis'            => 'Fulltime',
            'kategori'         => 'Desain & Kreatif',
        ]);

        // Submit 5 applicants
        for ($i = 0; $i < 5; $i++) {
            [$userPelamar, $pelamar] = $this->createPelamar();
            $res = $this->actingAs($userPelamar)->postJson(route('lamar.cepat', $lowongan->id));
            $res->assertStatus(200);
        }

        $this->assertFalse($lowongan->isDailyLocked());

        // 6th applicant should also succeed
        [$userPelamar6, $pelamar6] = $this->createPelamar();
        $res6 = $this->actingAs($userPelamar6)->postJson(route('lamar.cepat', $lowongan->id));
        $res6->assertStatus(200)->assertJson(['success' => true]);
    }
}

