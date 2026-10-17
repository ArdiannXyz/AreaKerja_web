<?php

namespace Tests\Feature;

use App\Models\Perusahaan;
use App\Models\TalentHunter;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TalentHunterSubmissionTest extends TestCase
{
    use DatabaseTransactions;

    private function createPerusahaanUser(): array
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'perusahaan_' . $unique,
            'nama_lengkap' => 'PT Sukses Mandiri',
            'email'        => 'perusahaan_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'perusahaan',
            'status'       => 0,
            'verified'     => true,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'             => $user->id,
            'nama_perusahaan'     => 'PT Sukses Mandiri',
            'koin'                => 10, // Koin sedikit untuk membuktikan koin TIDAK dipotong
            'verification_status' => 'approved',
        ]);

        return [$user, $perusahaan];
    }

    public function test_perusahaan_can_submit_talent_hunter_with_valid_offered_salary(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser();

        $initialCoins = $perusahaan->koin_perusahaan ?? 0;

        $response = $this->actingAs($user)->postJson(route('talent-hunter.store'), [
            'alamat'           => 'Jakarta Selatan',
            'posisi'           => 'Fullstack Web Developer',
            'pengalaman_kerja' => 'Minimal 2 tahun',
            'gender'           => 'Semua Gender',
            'gaji_ditawarkan'  => 5000000,
            'deskripsi'        => 'Kandidat menguasai Laravel dan Vue.js',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('talent_hunters', [
            'perusahaan_id'   => $perusahaan->id,
            'posisi'          => 'Fullstack Web Developer',
            'gaji_ditawarkan' => 5000000,
        ]);

        $this->assertDatabaseHas('catatan_cashs', [
            'user_id' => $user->id,
            'total'   => 5000000,
            'pesanan' => 'Talent Hunter: Fullstack Web Developer',
            'status'  => 'pending',
        ]);

        $json = $response->json();
        $this->assertNotEmpty($json['redirect_url']);
        $this->assertTrue(
            str_contains($json['redirect_url'], 'xendit') ||
            str_contains($json['redirect_url'], 'checkout') ||
            str_contains($json['redirect_url'], '/perusahaan/topup/') ||
            str_contains($json['redirect_url'], '/perusahaan/transaksi-koin/')
        );

        // Pastikan koin perusahaan TIDAK terpotong
        $perusahaan->refresh();
        $this->assertEquals($initialCoins, $perusahaan->koin_perusahaan ?? 0);
    }

    public function test_submission_fails_if_offered_salary_is_below_minimum_3_5_million(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser();

        $response = $this->actingAs($user)->postJson(route('talent-hunter.store'), [
            'alamat'           => 'Jakarta Selatan',
            'posisi'           => 'Staff Admin',
            'pengalaman_kerja' => 'Fresh Graduate',
            'gender'           => 'Semua Gender',
            'gaji_ditawarkan'  => 2500000, // Di bawah batas minimal 3.500.000
            'deskripsi'        => 'Testing gaji kurang dari 3.5jt',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gaji_ditawarkan']);
    }

    public function test_superadmin_can_update_talent_hunter_offered_salary(): void
    {
        $unique = uniqid();
        $adminUser = User::create([
            'username'     => 'superadmin_' . $unique,
            'nama_lengkap' => 'Super Admin',
            'email'        => 'admin_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'super_admin',
            'status'       => 0,
            'verified'     => true,
        ]);

        [$user, $perusahaan] = $this->createPerusahaanUser();

        $th = TalentHunter::create([
            'perusahaan_id'   => $perusahaan->id,
            'alamat'          => 'Surabaya',
            'posisi'          => 'UI/UX Designer',
            'pengalaman_kerja' => '1 tahun',
            'gender'          => 'Perempuan',
            'gaji_ditawarkan' => 4000000,
        ]);

        $response = $this->actingAs($adminUser)->put(route('superadmin.talent-hunter.update', $th->id), [
            'alamat'           => 'Surabaya Barat',
            'posisi'           => 'Senior UI/UX Designer',
            'pengalaman_kerja' => '3 tahun',
            'gender'           => 'Perempuan',
            'gaji_ditawarkan'  => 6000000,
        ]);

        $response->assertRedirect();

        $th->refresh();
        $this->assertEquals(6000000, $th->gaji_ditawarkan);
        $this->assertEquals('Senior UI/UX Designer', $th->posisi);
    }

    public function test_perusahaan_can_view_talent_hunter_page_with_proper_elements(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser();

        $response = $this->actingAs($user)->get(route('talent-hunter.index'));

        $response->assertStatus(200);
        $response->assertSee('btnDaftarTH');
        $response->assertSee('modalFormTH');
        $response->assertSee('modalBeli');
        $response->assertSee('Gaji yang Ditawarkan (1 Bulan)');
        $response->assertSee('Min. Rp 3.500.000');
    }

    public function test_unverified_perusahaan_cannot_submit_talent_hunter(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser();
        $perusahaan->update(['verification_status' => 'pending']);

        $response = $this->actingAs($user)->postJson(route('talent-hunter.store'), [
            'alamat'           => 'Jakarta Pusat',
            'posisi'           => 'Backend Developer',
            'pengalaman_kerja' => '1 tahun',
            'gender'           => 'Laki-laki',
            'gaji_ditawarkan'  => 5000000,
        ]);

        $response->assertStatus(403);
    }
}
