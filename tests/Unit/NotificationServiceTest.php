<?php

namespace Tests\Unit;

use App\Models\Notifikasi;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_create_notification_safely(): void
    {
        $user = User::create([
            'username'     => 'test_notif_' . time(),
            'nama_lengkap' => 'Test User',
            'email'        => 'notif_' . time() . '@test.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'pelamar',
            'verified'     => true,
            'status'       => 1,
        ]);

        $notif = NotificationService::send(
            $user->id,
            'Profil Diperbarui',
            'Data profil berhasil disimpan.'
        );

        $this->assertNotNull($notif);
        $this->assertEquals($user->id, $notif->user_id);
        $this->assertEquals('Profil Diperbarui', $notif->judul);
        $this->assertFalse((bool)$notif->is_read);
        $this->assertNotNull($notif->expired_at);
    }
}
