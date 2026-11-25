<?php

namespace Tests\Feature;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HapusNotifikasiKadaluarsaTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'username' => 'test_notif_user',
            'email' => 'test_notif@areakerja.com',
            'password' => Hash::make('password123'),
            'role' => 'pelamar',
            'verified' => 1,
            'status' => 0,
        ]);
    }

    public function test_it_deletes_expired_and_old_notifications_using_default_3_months(): void
    {
        // 1. Notifikasi yang expired_at sudah lewat (harus terhapus)
        $expiredNotif = Notifikasi::create([
            'user_id' => $this->user->id,
            'judul' => 'Notif Expired',
            'pesan' => 'Pesan expired',
            'expired_at' => now()->subDay(),
        ]);

        // 2. Notifikasi lama > 3 bulan tanpa expired_at (harus terhapus)
        $oldNotif = Notifikasi::create([
            'user_id' => $this->user->id,
            'judul' => 'Notif Lama 4 Bulan',
            'pesan' => 'Pesan lama',
            'expired_at' => null,
        ]);
        $oldNotif->created_at = now()->subMonths(4);
        $oldNotif->saveQuietly();

        // 3. Notifikasi baru (kurang dari 3 bulan, expired_at masih di masa depan - TIDAK BOLEH TERHAPUS)
        $validNotif = Notifikasi::create([
            'user_id' => $this->user->id,
            'judul' => 'Notif Aktif Baru',
            'pesan' => 'Pesan aktif',
            'expired_at' => now()->addDays(7),
        ]);

        // 4. Notifikasi tanpa expired_at yang masih baru (10 hari lalu - TIDAK BOLEH TERHAPUS)
        $recentNotif = Notifikasi::create([
            'user_id' => $this->user->id,
            'judul' => 'Notif Baru Tanpa Expired',
            'pesan' => 'Pesan baru',
            'expired_at' => null,
        ]);

        // Jalankan artisan command
        $this->artisan('notifikasi:hapus-expired')
            ->expectsOutputToContain('Pembersihan notifikasi selesai')
            ->assertExitCode(0);

        // Verifikasi database
        $this->assertDatabaseMissing('notifikasis', ['id' => $expiredNotif->id]);
        $this->assertDatabaseMissing('notifikasis', ['id' => $oldNotif->id]);
        $this->assertDatabaseHas('notifikasis', ['id' => $validNotif->id]);
        $this->assertDatabaseHas('notifikasis', ['id' => $recentNotif->id]);
    }

    public function test_it_supports_custom_months_option_like_1_month(): void
    {
        // Notifikasi berusia 40 hari (> 1 bulan)
        $notifOverOneMonth = Notifikasi::create([
            'user_id' => $this->user->id,
            'judul' => 'Notif 40 Hari',
            'pesan' => 'Pesan 40 hari lalu',
            'expired_at' => null,
        ]);
        $notifOverOneMonth->created_at = now()->subDays(40);
        $notifOverOneMonth->saveQuietly();

        // Notifikasi berusia 15 hari (< 1 bulan)
        $notifUnderOneMonth = Notifikasi::create([
            'user_id' => $this->user->id,
            'judul' => 'Notif 15 Hari',
            'pesan' => 'Pesan 15 hari lalu',
            'expired_at' => null,
        ]);
        $notifUnderOneMonth->created_at = now()->subDays(15);
        $notifUnderOneMonth->saveQuietly();

        // Jalankan dengan opsi --months=1
        $this->artisan('notifikasi:hapus-expired', ['--months' => 1])
            ->assertExitCode(0);

        $this->assertDatabaseMissing('notifikasis', ['id' => $notifOverOneMonth->id]);
        $this->assertDatabaseHas('notifikasis', ['id' => $notifUnderOneMonth->id]);
    }
}
