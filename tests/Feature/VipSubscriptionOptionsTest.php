<?php

namespace Tests\Feature;

use App\Models\CatatanKoin;
use App\Models\Perusahaan;
use App\Models\SiteSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VipSubscriptionOptionsTest extends TestCase
{
    use DatabaseTransactions;

    private function createSuperAdmin(): User
    {
        $unique = uniqid();
        return User::create([
            'username'     => 'superadmin_' . $unique,
            'nama_lengkap' => 'Super Admin ' . $unique,
            'email'        => 'superadmin_' . $unique . '@example.com',
            'password'     => bcrypt('password'),
            'role'         => 'super_admin',
            'verified'     => true,
            'status'       => 0,
        ]);
    }

    private function createPerusahaanUser(int $koin = 2000, bool $isVip = false, ?Carbon $expiredAt = null): array
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'pt_' . $unique,
            'nama_lengkap' => 'PT Sukses ' . $unique,
            'email'        => 'pt_' . $unique . '@example.com',
            'password'     => bcrypt('password'),
            'role'         => 'perusahaan',
            'verified'     => true,
            'status'       => 0,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'             => $user->id,
            'nama_perusahaan'     => 'PT Sukses ' . $unique,
            'slug'                => 'pt-sukses-' . $unique,
            'koin_perusahaan'     => $koin,
            'is_berlangganan'     => $isVip ? 1 : 0,
            'tanggal_expired'     => $expiredAt,
            'verification_status' => 'approved',
        ]);

        return [$user, $perusahaan];
    }

    public function test_superadmin_can_view_all_4_vip_subscription_options_in_paket_harga(): void
    {
        $superadmin = $this->createSuperAdmin();

        $response = $this->actingAs($superadmin)->get(route('superadmin.paket-harga'));
        $response->assertStatus(200);

        $response->assertSee('Berlangganan VIP (1 Bulan)');
        $response->assertSee('Berlangganan VIP (3 Bulan)');
        $response->assertSee('Berlangganan VIP (6 Bulan)');
        $response->assertSee('Berlangganan VIP (12 Bulan / 1 Tahun)');
    }

    public function test_superadmin_can_view_and_update_prices_for_all_4_vip_subscription_tiers(): void
    {
        $superadmin = $this->createSuperAdmin();

        // Visit edit page
        $editResponse = $this->actingAs($superadmin)->get(route('superadmin.paket-harga.edit-koin'));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Berlangganan VIP (1 Bulan)');
        $editResponse->assertSee('Berlangganan VIP (3 Bulan)');
        $editResponse->assertSee('Berlangganan VIP (6 Bulan)');
        $editResponse->assertSee('Berlangganan VIP (12 Bulan / 1 Tahun)');

        // Update prices via POST
        $updateResponse = $this->actingAs($superadmin)->put(route('superadmin.paket-harga.update-koin'), [
            'keys' => [
                'harga_koin_berlangganan_1_bulan',
                'harga_koin_berlangganan_3_bulan',
                'harga_koin_berlangganan_6_bulan',
                'harga_koin_berlangganan_12_bulan',
            ],
            'hargas' => [
                175,
                400,
                700,
                1100,
            ],
        ]);

        $updateResponse->assertRedirect(route('superadmin.paket-harga'));

        // Verify SiteSetting updated
        $this->assertEquals(175, (int) SiteSetting::get('harga_koin_berlangganan_1_bulan'));
        $this->assertEquals(400, (int) SiteSetting::get('harga_koin_berlangganan_3_bulan'));
        $this->assertEquals(700, (int) SiteSetting::get('harga_koin_berlangganan_6_bulan'));
        $this->assertEquals(1100, (int) SiteSetting::get('harga_koin_berlangganan_12_bulan'));
        $this->assertEquals(1100, (int) SiteSetting::get('harga_koin_berlangganan')); // synced

        // Verify model reflects the new settings
        $paketList = Perusahaan::getPaketLanggananList();
        $this->assertEquals(175, $paketList['1_bulan']['harga']);
        $this->assertEquals(400, $paketList['3_bulan']['harga']);
        $this->assertEquals(700, $paketList['6_bulan']['harga']);
        $this->assertEquals(1100, $paketList['12_bulan']['harga']);
    }

    public function test_perusahaan_can_view_all_4_vip_subscription_options_on_berlangganan_page(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser();

        $response = $this->actingAs($user)->get(route('perusahaan.berlangganan'));
        $response->assertStatus(200);

        $response->assertSee('1 Bulan');
        $response->assertSee('3 Bulan');
        $response->assertSee('6 Bulan');
        $response->assertSee('12 Bulan (1 Tahun)');
    }

    public function test_perusahaan_can_subscribe_to_1_month_vip(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser(koin: 500);
        $harga1Bulan = Perusahaan::getPaketLangganan('1_bulan')['harga'];

        $response = $this->actingAs($user)->postJson(route('berlangganan.store'), [
            'paket' => '1_bulan',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $perusahaan->refresh();
        $this->assertEquals(1, $perusahaan->is_berlangganan);
        $this->assertEquals(500 - $harga1Bulan, $perusahaan->koin_perusahaan);
        $this->assertTrue(Carbon::now()->addDays(28)->lte($perusahaan->tanggal_expired));
        $this->assertTrue(Carbon::now()->addDays(32)->gte($perusahaan->tanggal_expired));

        $this->assertDatabaseHas('catatan_koins', [
            'user_id' => $user->id,
            'total'   => '-' . $harga1Bulan,
        ]);
    }

    public function test_perusahaan_can_subscribe_to_3_months_vip(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser(koin: 1000);
        $harga3Bulan = Perusahaan::getPaketLangganan('3_bulan')['harga'];

        $response = $this->actingAs($user)->postJson(route('berlangganan.store'), [
            'paket' => '3_bulan',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $perusahaan->refresh();
        $this->assertEquals(1, $perusahaan->is_berlangganan);
        $this->assertEquals(1000 - $harga3Bulan, $perusahaan->koin_perusahaan);
        $this->assertTrue(Carbon::now()->addDays(88)->lte($perusahaan->tanggal_expired));
    }

    public function test_perusahaan_can_subscribe_to_6_months_vip(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser(koin: 1000);
        $harga6Bulan = Perusahaan::getPaketLangganan('6_bulan')['harga'];

        $response = $this->actingAs($user)->postJson(route('berlangganan.store'), [
            'paket' => '6_bulan',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $perusahaan->refresh();
        $this->assertEquals(1, $perusahaan->is_berlangganan);
        $this->assertEquals(1000 - $harga6Bulan, $perusahaan->koin_perusahaan);
        $this->assertTrue(Carbon::now()->addDays(175)->lte($perusahaan->tanggal_expired));
    }

    public function test_perusahaan_can_subscribe_to_12_months_vip(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser(koin: 1500);
        $harga12Bulan = Perusahaan::getPaketLangganan('12_bulan')['harga'];

        $response = $this->actingAs($user)->postJson(route('berlangganan.store'), [
            'paket' => '12_bulan',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $perusahaan->refresh();
        $this->assertEquals(1, $perusahaan->is_berlangganan);
        $this->assertEquals(1500 - $harga12Bulan, $perusahaan->koin_perusahaan);
        $this->assertTrue(Carbon::now()->addDays(360)->lte($perusahaan->tanggal_expired));
    }

    public function test_perusahaan_extending_active_vip_extends_from_current_expiry(): void
    {
        $existingExpiry = Carbon::now()->addDays(20);
        [$user, $perusahaan] = $this->createPerusahaanUser(
            koin: 1000,
            isVip: true,
            expiredAt: $existingExpiry
        );

        $response = $this->actingAs($user)->postJson(route('berlangganan.store'), [
            'paket' => '1_bulan',
        ]);

        $response->assertStatus(200);

        $perusahaan->refresh();
        // Should be approximately existingExpiry + 1 month (~50 days from now)
        $this->assertTrue(Carbon::now()->addDays(48)->lte($perusahaan->tanggal_expired));
    }

    public function test_perusahaan_cannot_subscribe_if_coins_insufficient(): void
    {
        [$user, $perusahaan] = $this->createPerusahaanUser(koin: 50);

        $response = $this->actingAs($user)->postJson(route('berlangganan.store'), [
            'paket' => '1_bulan',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'error'   => 'koin_kurang',
        ]);

        $perusahaan->refresh();
        $this->assertEquals(50, $perusahaan->koin_perusahaan);
        $this->assertEquals(0, $perusahaan->is_berlangganan);
    }
}

