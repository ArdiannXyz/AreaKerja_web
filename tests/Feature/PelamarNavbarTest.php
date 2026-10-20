<?php

namespace Tests\Feature;

use App\Models\Pelamar;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PelamarNavbarTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function guest_can_see_talent_hunter_and_pasang_lowongan_in_navbar()
    {
        $response = $this->get(route('beranda'));
        $response->assertStatus(200);

        preg_match('/<header.*?<\/header>/s', $response->getContent(), $matches);
        $headerHtml = $matches[0] ?? '';

        $this->assertStringContainsString('Talent Hunter', $headerHtml);
        $this->assertStringContainsString('Pasang Lowongan', $headerHtml);
        $this->assertStringContainsString('Beranda', $headerHtml);
        $this->assertStringContainsString('Event', $headerHtml);
    }

    /** @test */
    public function pelamar_user_does_not_see_talent_hunter_and_pasang_lowongan_in_navbar()
    {
        $user = User::create([
            'username'     => 'pelamar_test_' . time(),
            'nama_lengkap' => 'Pelamar Test',
            'email'        => 'pelamar_' . time() . '@test.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'pelamar',
            'verified'     => true,
            'status'       => 0,
        ]);

        Pelamar::create([
            'user_id'      => $user->id,
            'nama_lengkap' => 'Pelamar Test',
            'email'        => $user->email,
            'verified'     => 1,
            'status'       => '0',
        ]);

        $response = $this->actingAs($user)->get(route('beranda'));
        $response->assertStatus(200);

        preg_match('/<header.*?<\/header>/s', $response->getContent(), $matches);
        $headerHtml = $matches[0] ?? '';

        // Menu yang wajib ada di header
        $this->assertStringContainsString('Beranda', $headerHtml);
        $this->assertStringContainsString('Event', $headerHtml);
        $this->assertStringContainsString('Tips Kerja', $headerHtml);
        $this->assertStringContainsString('Daftar Kandidat', $headerHtml);

        // Menu yang disembunyikan untuk pelamar di header navbar
        $this->assertStringNotContainsString('Talent Hunter', $headerHtml);
        $this->assertStringNotContainsString('Pasang Lowongan', $headerHtml);
    }
}
