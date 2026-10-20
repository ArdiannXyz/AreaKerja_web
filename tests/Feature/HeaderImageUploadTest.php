<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeaderImageUploadTest extends TestCase
{
    use DatabaseTransactions;

    private function createSuperAdmin(): User
    {
        $unique = uniqid();
        $user = User::create([
            'username' => 'superadmin_' . $unique,
            'nama_lengkap' => 'Super Admin',
            'email' => 'superadmin_' . $unique . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'status' => 0,
            'verified' => true,
        ]);

        SuperAdmin::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Super Admin',
        ]);

        return $user;
    }

    public function test_superadmin_can_view_social_and_header_page(): void
    {
        $superadmin = $this->createSuperAdmin();

        $response = $this->actingAs($superadmin)->get(route('superadmin.social.index'));

        $response->assertStatus(200);
        $response->assertSee('Banner Image Header');
        $response->assertSee('Pasang Lowongan');
        $response->assertSee('Talent Hunter');
        $response->assertSee('Daftar Kandidat');
    }

    public function test_superadmin_can_upload_and_change_header_banner(): void
    {
        Storage::fake('public');

        $superadmin = $this->createSuperAdmin();
        $file = UploadedFile::fake()->image('custom_header.jpg', 1200, 400);

        $response = $this->actingAs($superadmin)->put(route('superadmin.header.update', 'header_pasang_lowongan'), [
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Banner image header berhasil diperbarui!');

        $savedPath = SiteSetting::get('header_pasang_lowongan');
        $this->assertNotNull($savedPath);
        Storage::disk('public')->assertExists($savedPath);

        // Verify the public page displays the new header image
        $pageResponse = $this->get(route('pelamar.pasangLowongan'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee(asset('storage/' . $savedPath));
    }

    public function test_uploading_invalid_header_name_returns_error(): void
    {
        Storage::fake('public');

        $superadmin = $this->createSuperAdmin();
        $file = UploadedFile::fake()->image('invalid.jpg');

        $response = $this->actingAs($superadmin)->put(route('superadmin.header.update', 'header_tidak_valid'), [
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['error']);
    }

    public function test_superadmin_can_update_social_links(): void
    {
        $superadmin = $this->createSuperAdmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.social.update'), [
            'links' => [
                1 => 'https://facebook.com/areakerja_official',
                3 => 'https://instagram.com/areakerja_official',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Tautan media sosial berhasil disimpan.');

        $this->assertEquals('https://facebook.com/areakerja_official', SiteSetting::get('social_facebook'));
        $this->assertEquals('https://instagram.com/areakerja_official', SiteSetting::get('social_instagram'));
    }
}
