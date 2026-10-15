<?php

namespace Tests\Feature;

use App\Models\Iklan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminIklanUploadTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminUser;
    protected User $superAdminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->adminUser = User::create([
            'username'     => 'admin_iklan_' . time(),
            'nama_lengkap' => 'Admin Iklan',
            'email'        => 'admin_iklan_' . time() . '@test.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'admin',
            'verified'     => true,
            'status'       => 0,
        ]);

        $this->superAdminUser = User::create([
            'username'     => 'superadmin_iklan_' . time(),
            'nama_lengkap' => 'Super Admin Iklan',
            'email'        => 'superadmin_iklan_' . time() . '@test.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'super_admin',
            'verified'     => true,
            'status'       => 0,
        ]);
    }

    /** @test */
    public function admin_can_view_create_iklan_page()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.iklan.create'));
        $response->assertStatus(200);
        $response->assertSee('Pasang Banner Iklan');
    }

    /** @test */
    public function super_admin_can_view_create_iklan_page()
    {
        $response = $this->actingAs($this->superAdminUser)->get(route('superadmin.iklan.create'));
        $response->assertStatus(200);
        $response->assertSee('Pasang Banner Iklan');
    }

    /** @test */
    public function admin_can_upload_banner_iklan_without_fee_and_immediately_active()
    {
        $bannerFile = UploadedFile::fake()->image('banner_promo.jpg', 1200, 400);

        $response = $this->actingAs($this->adminUser)->post(route('admin.iklan.store'), [
            'judul_iklan'   => 'Promo Event Karir Admin 2026',
            'url_tujuan'    => 'https://areakerja.com/event-karir',
            'durasi_hari'   => 14,
            'gambar_banner' => $bannerFile,
            'perusahaan_id' => null,
        ]);

        $response->assertRedirect(route('admin.iklan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('iklans', [
            'judul_iklan'    => 'Promo Event Karir Admin 2026',
            'url_tujuan'     => 'https://areakerja.com/event-karir',
            'durasi_hari'    => 14,
            'koin_terpotong' => 0,
            'status'         => 'aktif',
            'user_id'        => $this->adminUser->id,
            'perusahaan_id'  => null,
        ]);

        $iklan = Iklan::where('judul_iklan', 'Promo Event Karir Admin 2026')->first();
        $this->assertNotNull($iklan);
        $this->assertNotNull($iklan->tanggal_mulai);
        $this->assertNotNull($iklan->tanggal_selesai);
        $this->assertTrue($iklan->isActive());
        Storage::disk('public')->assertExists($iklan->gambar_banner);
    }

    /** @test */
    public function super_admin_can_upload_banner_iklan_without_fee_and_immediately_active()
    {
        $bannerFile = UploadedFile::fake()->image('banner_super.png', 1200, 400);

        $response = $this->actingAs($this->superAdminUser)->post(route('superadmin.iklan.store'), [
            'judul_iklan'   => 'Kampanye Nasional Super Admin',
            'url_tujuan'    => 'https://areakerja.com/kampanye-nasional',
            'durasi_hari'   => 30,
            'gambar_banner' => $bannerFile,
            'perusahaan_id' => null,
        ]);

        $response->assertRedirect(route('superadmin.iklan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('iklans', [
            'judul_iklan'    => 'Kampanye Nasional Super Admin',
            'url_tujuan'     => 'https://areakerja.com/kampanye-nasional',
            'durasi_hari'    => 30,
            'koin_terpotong' => 0,
            'status'         => 'aktif',
            'user_id'        => $this->superAdminUser->id,
            'perusahaan_id'  => null,
        ]);

        $iklan = Iklan::where('judul_iklan', 'Kampanye Nasional Super Admin')->first();
        $this->assertNotNull($iklan);
        $this->assertTrue($iklan->isActive());
        Storage::disk('public')->assertExists($iklan->gambar_banner);
    }

    /** @test */
    public function admin_can_delete_iklan_banner()
    {
        $bannerFile = UploadedFile::fake()->image('banner_delete.jpg');
        $path = $bannerFile->store('iklan-banners', 'public');

        $iklan = Iklan::create([
            'judul_iklan'     => 'Iklan Mau Dihapus',
            'url_tujuan'      => 'https://areakerja.com',
            'gambar_banner'   => $path,
            'paket_nama'      => 'Official Admin (Gratis)',
            'durasi_hari'     => 7,
            'koin_terpotong'  => 0,
            'posisi'          => 'home_hero',
            'status'          => 'aktif',
            'tanggal_mulai'   => now(),
            'tanggal_selesai' => now()->addDays(7),
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.iklan.destroy', $iklan->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('iklans', ['id' => $iklan->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
