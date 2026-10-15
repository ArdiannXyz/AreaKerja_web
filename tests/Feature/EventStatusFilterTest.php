<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\User;

class EventStatusFilterTest extends TestCase
{
    public function test_upcoming_filter_only_shows_truly_upcoming_events()
    {
        // Past event with status buka (simulating the bug where past event is not yet closed)
        $pastEvent = Event::create([
            'status' => 'buka',
            'title' => 'Expired Past Event',
            'tgl_mulai' => now()->subDays(10)->toDateString(),
            'jam_mulai' => '08:00',
            'tgl_akhir' => now()->subDays(8)->toDateString(),
            'jam_akhir' => '17:00',
        ]);

        // Future upcoming event
        $futureEvent = Event::create([
            'status' => 'buka',
            'title' => 'Genuine Future Event',
            'tgl_mulai' => now()->addDays(5)->toDateString(),
            'jam_mulai' => '08:00',
            'tgl_akhir' => now()->addDays(7)->toDateString(),
            'jam_akhir' => '17:00',
        ]);

        // Access public / pelamar event list with ?status=buka
        $response = $this->get(route('pelamar.event.index', ['status' => 'buka']));
        $response->assertStatus(200);

        // Future event should be visible
        $response->assertSee('Genuine Future Event');
        // Past event should NOT appear in upcoming filter
        $response->assertDontSee('Expired Past Event');

        // Past event should have been auto-closed
        $pastEvent->refresh();
        $this->assertEquals('tutup', $pastEvent->status);
        $this->assertTrue($pastEvent->is_ended);

        // Access with ?status=tutup
        $endedResponse = $this->get(route('pelamar.event.index', ['status' => 'tutup']));
        $endedResponse->assertStatus(200);
        $endedResponse->assertSee('Expired Past Event');
        $endedResponse->assertDontSee('Genuine Future Event');

        // Clean up created records
        $pastEvent->delete();
        $futureEvent->delete();
    }

    public function test_perusahaan_event_page_filters_correctly()
    {
        $pastEvent = Event::create([
            'status' => 'buka',
            'title' => 'Perusahaan Past Event',
            'tgl_mulai' => now()->subDays(5)->toDateString(),
            'jam_mulai' => '08:00',
            'tgl_akhir' => now()->subDays(3)->toDateString(),
            'jam_akhir' => '17:00',
        ]);

        $futureEvent = Event::create([
            'status' => 'buka',
            'title' => 'Perusahaan Future Event',
            'tgl_mulai' => now()->addDays(10)->toDateString(),
            'jam_mulai' => '08:00',
            'tgl_akhir' => now()->addDays(12)->toDateString(),
            'jam_akhir' => '17:00',
        ]);

        $user = User::create([
            'username'     => 'perusahaan_test_' . time(),
            'nama_lengkap' => 'Perusahaan Test',
            'email'        => 'perusahaan_' . time() . '@test.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'perusahaan',
            'verified'     => true,
            'status'       => 0,
        ]);

        \App\Models\Perusahaan::create([
            'user_id' => $user->id,
            'nama_perusahaan' => 'PT Test Perusahaan',
            'verification_status' => 'approved',
        ]);

        $response = $this->actingAs($user)->get(route('perusahaan.event.index', ['status' => 'buka']));
        $response->assertStatus(200);
        $response->assertSee('Perusahaan Future Event');
        $response->assertDontSee('Perusahaan Past Event');

        $pastEvent->delete();
        $futureEvent->delete();
        $user->delete();
    }
}

