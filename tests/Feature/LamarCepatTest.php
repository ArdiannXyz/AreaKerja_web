<?php

namespace Tests\Feature;

use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\PengalamanKerja;
use App\Models\Perusahaan;
use App\Models\RiwayatPendidikan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LamarCepatTest extends TestCase
{
    use DatabaseTransactions;

    private function createCompletePelamar(): array
    {
        $unique = uniqid();
        $user = User::create([
            'username' => 'pelamar_' . $unique,
            'nama_lengkap' => 'Test Pelamar',
            'email' => 'pelamar_' . $unique . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelamar',
            'status' => 0,
            'verified' => true,
        ]);

        $pelamar = Pelamar::create([
            'user_id' => $user->id,
            'nama_pelamar' => 'Test Pelamar',
            'skills' => ['PHP', 'Laravel'],
            'kategori' => 'pelamar',
        ]);

        RiwayatPendidikan::create([
            'pelamar_id' => $pelamar->id,
            'pendidikan' => 'S1',
            'jurusan' => 'Informatika',
            'asal_pendidikan' => 'Universitas Test',
            'tahun_awal' => 2018,
            'tahun_akhir' => 2022,
        ]);

        PengalamanKerja::create([
            'pelamar_id' => $pelamar->id,
            'posisi_pekerjaan' => 'Developer',
            'nama_perusahaan' => 'PT Test',
            'tahun_awal' => 2022,
            'tahun_akhir' => 2024,
        ]);

        return [$user, $pelamar];
    }

    private function createLowongan(array $attrs = []): LowonganPerusahaan
    {
        $unique = uniqid();
        $userPerusahaan = User::create([
            'username' => 'perusahaan_' . $unique,
            'nama_lengkap' => 'PT Sejahtera',
            'email' => 'perusahaan_' . $unique . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'perusahaan',
            'status' => 0,
            'verified' => true,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id' => $userPerusahaan->id,
            'nama_perusahaan' => 'PT Sejahtera ' . $unique,
            'slug' => 'pt-sejahtera-' . $unique,
            'koin' => 100,
        ]);

        return LowonganPerusahaan::create(array_merge([
            'perusahaan_id' => $perusahaan->id,
            'nama' => 'Software Engineer',
            'slug' => 'software-engineer-' . $unique,
            'status' => 'buka',
            'published_at' => now(),
            'batas_lamaran' => now()->addDays(14),
            'expired_at' => now()->addDays(30),
            'gaji_awal' => 5000000,
            'gaji_akhir' => 8000000,
        ], $attrs));
    }

    public function test_pelamar_with_complete_profile_can_apply_successfully(): void
    {
        [$user, $pelamar] = $this->createCompletePelamar();
        $lowongan = $this->createLowongan();

        $response = $this->actingAs($user)->postJson(route('lamar.cepat', $lowongan->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Lamaran berhasil dikirim!',
            ]);

        $this->assertDatabaseHas('pelamar_lowongans', [
            'pelamar_id' => $pelamar->id,
            'lowongan_id' => $lowongan->id,
            'status' => 'pending',
        ]);
    }

    public function test_pelamar_with_incomplete_profile_is_rejected(): void
    {
        $unique = uniqid();
        $user = User::create([
            'username' => 'incomplete_' . $unique,
            'nama_lengkap' => 'Incomplete Pelamar',
            'email' => 'incomplete_' . $unique . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'pelamar',
            'status' => 0,
            'verified' => true,
        ]);

        Pelamar::create([
            'user_id' => $user->id,
            'nama_pelamar' => 'Test Incomplete',
            'skills' => null,
        ]);

        $lowongan = $this->createLowongan();

        $response = $this->actingAs($user)->postJson(route('lamar.cepat', $lowongan->id));

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Harap lengkapi Profile Anda terlebih dahulu sebelum melamar.',
            ]);
    }

    public function test_cannot_apply_to_expired_vacancy(): void
    {
        [$user, $pelamar] = $this->createCompletePelamar();
        $lowongan = $this->createLowongan([
            'batas_lamaran' => now()->subDay(),
        ]);

        $response = $this->actingAs($user)->postJson(route('lamar.cepat', $lowongan->id));

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'expired' => true,
            ]);
    }

    public function test_cannot_apply_twice_if_previous_application_is_pending(): void
    {
        [$user, $pelamar] = $this->createCompletePelamar();
        $lowongan = $this->createLowongan();

        PelamarLowongan::create([
            'pelamar_id' => $pelamar->id,
            'lowongan_id' => $lowongan->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->postJson(route('lamar.cepat', $lowongan->id));

        $response->assertJson([
            'success' => false,
        ]);
    }
}

