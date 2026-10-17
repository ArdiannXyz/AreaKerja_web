<?php

namespace Tests\Feature;

use App\Models\LowonganPerusahaan;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LowonganFilterSearchTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test filter pencarian lowongan berdasarkan jenis, pengalaman, dan pendidikan.
     */
    public function test_search_lowongan_with_filters()
    {
        // 1. Request halaman search tanpa parameter
        $response = $this->get(route('lowongan.search'));
        $response->assertStatus(200);
        $response->assertViewIs('non-user.home');

        // 2. Request filter jenis = Magang
        $responseMagang = $this->get(route('lowongan.search', ['jenis' => 'Magang']));
        $responseMagang->assertStatus(200);

        // 3. Request filter pengalaman = Fresh Graduate
        $responsePengalaman = $this->get(route('lowongan.search', ['pengalaman' => 'Fresh Graduate']));
        $responsePengalaman->assertStatus(200);

        // 4. Request filter pendidikan = S1
        $responsePendidikan = $this->get(route('lowongan.search', ['pendidikan' => 'S1']));
        $responsePendidikan->assertStatus(200);

        // 5. Request kombinasi jenis=Freelance, pengalaman=1-3 Tahun, pendidikan=D3, provinsi dan kota
        $responseCombo = $this->get(route('lowongan.search', [
            'jenis'      => 'Freelance',
            'pengalaman' => '1-3 Tahun',
            'pendidikan' => 'D3',
            'provinsi'   => '32',
            'kota'       => 'Kota Bandung',
        ]));
        $responseCombo->assertStatus(200);

        // 6. Test public get-kota API
        $responseKotaApi = $this->get('/get-kota/32');
        $responseKotaApi->assertStatus(200);
        $responseKotaApi->assertJsonStructure([
            '*' => ['id', 'provinsi_id', 'nama']
        ]);
    }

    /**
     * Test form alamat perusahaan dapat diakses tanpa Undefined variable $districts
     */
    public function test_perusahaan_create_alamat_view_accessible()
    {
        $user = User::factory()->create(['role' => 'perusahaan']);
        $perusahaan = Perusahaan::create([
            'user_id' => $user->id,
            'nama_perusahaan' => 'PT Test Alamat',
        ]);

        $response = $this->actingAs($user)->get('/perusahaan/create/alamat');
        $response->assertStatus(200);
    }

    /**
     * Test halaman tips-kerja pelamar dapat diakses tanpa class not found error
     */
    public function test_pelamar_tips_kerja_accessible()
    {
        $response = $this->get('/pelamar/tips-kerja');
        $response->assertStatus(200);
    }
}

