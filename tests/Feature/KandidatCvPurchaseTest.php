<?php

namespace Tests\Feature;

use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\PembelianCv;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KandidatCvPurchaseTest extends TestCase
{
    use DatabaseTransactions;

    private function createPerusahaanWithCoins(int $coins = 50): array
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'perusahaan_' . $unique,
            'nama_lengkap' => 'PT Teknologi Indonesia',
            'email'        => 'perusahaan_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'perusahaan',
            'status'       => 0,
            'verified'     => true,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'             => $user->id,
            'nama_perusahaan'     => 'PT Teknologi Indonesia',
            'koin_perusahaan'     => $coins,
            'verification_status' => 'approved',
        ]);

        return [$user, $perusahaan];
    }

    private function createKandidat(): Pelamar
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'pelamar_' . $unique,
            'nama_lengkap' => 'Bambang Kurnia',
            'email'        => 'pelamar_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'pelamar',
            'status'       => 0,
            'verified'     => true,
        ]);

        return Pelamar::create([
            'user_id'       => $user->id,
            'nama_pelamar'  => 'Bambang Kurnia',
            'gender'        => 'Laki-laki',
            'tanggal_lahir' => '2000-01-01',
            'kategori'      => 'kandidat aktif',
            'skills'        => ['UI/UX', 'Laravel'],
        ]);
    }

    public function test_perusahaan_can_view_kandidat_page_with_symmetrical_ui_elements(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(80);
        $kandidat = $this->createKandidat();

        $response = $this->actingAs($user)->get(route('perusahaan.kandidat.ak'));

        $response->assertStatus(200);
        $response->assertSee('Kandidat Area Kerja');
        $response->assertSee('Cari Kandidat');
        $response->assertSee('Top Up Koin');
        $response->assertSee('Bambang Kurnia');
        $response->assertSee('btn-beli-cv');
        $response->assertSee('Rekrut');
    }

    public function test_perusahaan_can_buy_cv_with_5_coins(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(50);
        $kandidat = $this->createKandidat();

        $response = $this->actingAs($user)->postJson(route('perusahaan.kandidat.beli-cv', $kandidat->id));

        $response->assertStatus(200)
            ->assertJson([
                'status'    => 'success',
                'sisa_koin' => 45,
            ]);

        $this->assertDatabaseHas('pembelian_cvs', [
            'perusahaan_id' => $perusahaan->id,
            'pelamar_id'    => $kandidat->id,
            'koin'          => 5,
        ]);

        $perusahaan->refresh();
        $this->assertEquals(45, $perusahaan->koin_perusahaan);
    }

    public function test_buying_cv_fails_if_coins_insufficient(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(3); // Kurang dari 5 koin
        $kandidat = $this->createKandidat();

        $response = $this->actingAs($user)->postJson(route('perusahaan.kandidat.beli-cv', $kandidat->id));

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'type'   => 'coin_insufficient',
            ]);

        $perusahaan->refresh();
        $this->assertEquals(3, $perusahaan->koin_perusahaan);
    }

    public function test_perusahaan_cannot_be_charged_twice_for_same_cv(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(50);
        $kandidat = $this->createKandidat();

        // Pembelian pertama
        $this->actingAs($user)->postJson(route('perusahaan.kandidat.beli-cv', $kandidat->id));

        $perusahaan->refresh();
        $this->assertEquals(45, $perusahaan->koin_perusahaan);

        // Pembelian kedua untuk pelamar yang sama
        $response = $this->actingAs($user)->postJson(route('perusahaan.kandidat.beli-cv', $kandidat->id));

        $response->assertStatus(200)
            ->assertJson([
                'status'  => 'success',
                'message' => 'CV sudah pernah dibuka.',
            ]);

        // Saldo tidak berkurang lagi
        $perusahaan->refresh();
        $this->assertEquals(45, $perusahaan->koin_perusahaan);
    }

    public function test_kandidat_page_shows_download_button_for_purchased_cv(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(50);
        $kandidat = $this->createKandidat();

        PembelianCv::create([
            'perusahaan_id' => $perusahaan->id,
            'pelamar_id'    => $kandidat->id,
            'koin'          => 5,
        ]);

        $response = $this->actingAs($user)->get(route('perusahaan.kandidat.ak'));

        $response->assertStatus(200);
        $response->assertSee('Download');
    }

    public function test_cannot_recruit_kandidat_without_downloading_cv_first(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(150);
        $kandidat = $this->createKandidat();
        $lowongan = LowonganPerusahaan::create([
            'perusahaan_id' => $perusahaan->id,
            'nama'          => 'Frontend Developer',
            'slug'          => 'frontend-dev-' . uniqid(),
            'jenis'         => 'Full-time',
            'status'        => 'aktif',
        ]);

        $response = $this->actingAs($user)->postJson(route('kandidat.beli'), [
            'pelamar_id'             => $kandidat->id,
            'lowongan_perusahaan_id' => $lowongan->id,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('pelamar_lowongans', [
            'pelamar_id'  => $kandidat->id,
            'lowongan_id' => $lowongan->id,
        ]);
        $perusahaan->refresh();
        $this->assertEquals(150, $perusahaan->koin_perusahaan);
    }

    public function test_can_recruit_kandidat_after_downloading_cv(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(150);
        $kandidat = $this->createKandidat();
        $lowongan = LowonganPerusahaan::create([
            'perusahaan_id' => $perusahaan->id,
            'nama'          => 'Backend Developer',
            'slug'          => 'backend-dev-' . uniqid(),
            'jenis'         => 'Full-time',
            'status'        => 'aktif',
        ]);

        // 1. Unduh / beli CV terlebih dahulu (5 koin)
        $this->actingAs($user)->postJson(route('perusahaan.kandidat.beli-cv', $kandidat->id));
        $perusahaan->refresh();
        $this->assertEquals(145, $perusahaan->koin_perusahaan);

        // 2. Rekrut kandidat (100 koin)
        $response = $this->actingAs($user)->postJson(route('kandidat.beli'), [
            'pelamar_id'             => $kandidat->id,
            'lowongan_perusahaan_id' => $lowongan->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status'  => 'success',
                'message' => 'Kandidat berhasil direkrut!',
            ]);

        $this->assertDatabaseHas('pelamar_lowongans', [
            'pelamar_id'  => $kandidat->id,
            'lowongan_id' => $lowongan->id,
            'status'      => 'pending',
        ]);

        $perusahaan->refresh();
        $this->assertEquals(45, $perusahaan->koin_perusahaan);
    }

    public function test_cannot_recruit_same_kandidat_twice(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(250);
        $kandidat = $this->createKandidat();
        $lowongan = LowonganPerusahaan::create([
            'perusahaan_id' => $perusahaan->id,
            'nama'          => 'Fullstack Developer',
            'slug'          => 'fullstack-dev-' . uniqid(),
            'jenis'         => 'Full-time',
            'status'        => 'aktif',
        ]);

        // Buka CV kandidat
        PembelianCv::create([
            'perusahaan_id' => $perusahaan->id,
            'pelamar_id'    => $kandidat->id,
            'koin'          => 5,
        ]);

        // Rekrut kandidat pertama kali
        $response1 = $this->actingAs($user)->postJson(route('kandidat.beli'), [
            'pelamar_id'             => $kandidat->id,
            'lowongan_perusahaan_id' => $lowongan->id,
        ]);
        $response1->assertStatus(200);

        $perusahaan->refresh();
        $this->assertEquals(150, $perusahaan->koin_perusahaan);

        // Mencoba merekrut kandidat yang sama untuk kedua kalinya
        $response2 = $this->actingAs($user)->postJson(route('kandidat.beli'), [
            'pelamar_id'             => $kandidat->id,
            'lowongan_perusahaan_id' => $lowongan->id,
        ]);

        $response2->assertStatus(422);

        // Koin tidak berkurang lagi
        $perusahaan->refresh();
        $this->assertEquals(150, $perusahaan->koin_perusahaan);
    }

    public function test_kandidat_page_shows_sudah_direkrut_for_recruited_kandidat(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanWithCoins(150);
        $kandidat = $this->createKandidat();
        $lowongan = LowonganPerusahaan::create([
            'perusahaan_id' => $perusahaan->id,
            'nama'          => 'Mobile Developer',
            'slug'          => 'mobile-dev-' . uniqid(),
            'jenis'         => 'Full-time',
            'status'        => 'aktif',
        ]);

        // Tandai sudah direkrut
        PelamarLowongan::create([
            'pelamar_id'  => $kandidat->id,
            'lowongan_id' => $lowongan->id,
            'status'      => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('perusahaan.kandidat.ak'));

        $response->assertStatus(200);
        $response->assertSee('Sudah Direkrut');
    }
}

