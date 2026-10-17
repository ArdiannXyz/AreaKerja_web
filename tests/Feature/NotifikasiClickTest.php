<?php

namespace Tests\Feature;

use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NotifikasiClickTest extends TestCase
{
    use DatabaseTransactions;

    public function test_recruitment_notification_generates_correct_url()
    {
        $perusahaanUser = User::factory()->create(['role' => 'perusahaan']);
        $perusahaan = Perusahaan::create([
            'user_id' => $perusahaanUser->id,
            'nama_perusahaan' => 'PT Testing Solusi',
            'slug' => 'pt-testing-solusi',
        ]);

        $lowongan = LowonganPerusahaan::create([
            'perusahaan_id' => $perusahaan->id,
            'nama' => 'Backend Engineer',
            'slug' => 'backend-engineer',
            'deskripsi' => 'Deskripsi pekerjaan backend engineer',
            'jenis' => 'Full-time',
            'alamat' => 'Jakarta',
            'gaji_awal' => 5000000,
            'gaji_akhir' => 8000000,
            'batas_lamaran' => now()->addDays(30),
            'status' => 'aktif',
        ]);

        $pelamarUser = User::factory()->create(['role' => 'pelamar']);
        $pelamar = Pelamar::create([
            'user_id' => $pelamarUser->id,
            'nama_pelamar' => 'Kandidat Hebat',
        ]);

        $pelamarLowongan = PelamarLowongan::create([
            'pelamar_id' => $pelamar->id,
            'lowongan_id' => $lowongan->id,
            'status' => 'pending',
        ]);

        $notif = Notifikasi::create([
            'user_id' => $pelamarUser->id,
            'perusahaan_id' => $perusahaan->id,
            'lowongan_id' => $lowongan->id,
            'pelamar_lowongan_id' => $pelamarLowongan->id,
            'judul' => 'Penawaran Rekrutmen Baru',
            'pesan' => "{$perusahaan->nama_perusahaan} telah merekrut Anda untuk lowongan {$lowongan->nama}.",
            'is_read' => 0,
        ]);

        $expectedUrl = route('kandidat.detailTawaran', [
            'perusahaan' => $perusahaan->slug,
            'lowongan' => $lowongan->slug,
        ]);

        $this->assertEquals($expectedUrl, $notif->url);

        // Test mark as read via POST endpoint
        $response = $this->actingAs($pelamarUser)->postJson(route('notifikasi.baca', $notif->id));
        $response->assertOk();
        $this->assertDatabaseHas('notifikasis', [
            'id' => $notif->id,
            'is_read' => 1,
        ]);

        // Test accessing detailTawaran page
        $path = parse_url($notif->url, PHP_URL_PATH);
        $pageResponse = $this->actingAs($pelamarUser)->get($path);
        $pageResponse->assertOk();
        $pageResponse->assertSee($perusahaan->nama_perusahaan);
        $pageResponse->assertSee($lowongan->nama);
    }
}
