<?php

namespace Tests\Feature;

use App\Http\Controllers\IklanPerusahaanController;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SuperAdminPaketHargaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_superadmin_finance_paket_harga_removes_bronze_silver_gold_and_shows_cv_and_iklan(): void
    {
        $timestamp = time() . '_sa';
        $superadmin = User::create([
            'username'     => 'superadmin_' . $timestamp,
            'nama_lengkap' => 'Super Admin Finance',
            'email'        => 'superadmin_' . $timestamp . '@example.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'super_admin',
            'verified'     => true,
            'status'       => 0,
        ]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.paket-harga'));
        $response->assertStatus(200);

        // Lowongan bronze/silver/gold must not be present
        $response->assertDontSee('Pasang Lowongan Bronze');
        $response->assertDontSee('Pasang Lowongan Silver');
        $response->assertDontSee('Pasang Lowongan Gold');

        // Buka CV and Moderasi Iklan must be present
        $response->assertSee('Buka CV Pelamar');
        $response->assertSee('Moderasi Iklan');
        $response->assertSee('Boost Lowongan');
        $response->assertSee('Berlangganan VIP');
    }

    public function test_superadmin_can_edit_and_update_koin_prices_for_cv_and_iklan(): void
    {
        $timestamp = time() . '_sa2';
        $superadmin = User::create([
            'username'     => 'superadmin_' . $timestamp,
            'nama_lengkap' => 'Super Admin Finance',
            'email'        => 'superadmin_' . $timestamp . '@example.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'super_admin',
            'verified'     => true,
            'status'       => 0,
        ]);

        $editResponse = $this->actingAs($superadmin)->get(route('superadmin.paket-harga.edit-koin'));
        $editResponse->assertStatus(200);
        $editResponse->assertDontSee('Pasang Lowongan Bronze');
        $editResponse->assertSee('Buka CV Pelamar');
        $editResponse->assertSee('Moderasi Iklan');

        // Update prices: set Buka CV to 12 koin, Iklan 3 Hari to 220 koin
        $updateResponse = $this->actingAs($superadmin)->put(route('superadmin.paket-harga.update-koin'), [
            'keys' => [
                'harga_koin_buka_cv',
                'harga_koin_iklan_3_hari',
                'harga_koin_iklan_7_hari',
                'harga_koin_iklan_14_hari',
                'harga_koin_iklan_30_hari',
                'harga_koin_boost_lowongan',
                'harga_koin_berlangganan',
            ],
            'hargas' => [
                12,
                220,
                350,
                600,
                1200,
                300,
                1000,
            ],
        ]);

        $updateResponse->assertRedirect(route('superadmin.paket-harga'));

        $this->assertEquals(12, (int) SiteSetting::get('harga_koin_buka_cv'));
        $this->assertEquals(220, (int) SiteSetting::get('harga_koin_iklan_3_hari'));

        // Check dynamic price reflection in IklanPerusahaanController
        $iklanPakets = IklanPerusahaanController::getPaketList();
        $this->assertEquals(220, $iklanPakets[1]['koin']);
    }
}

