<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        \App\Console\Commands\TestExpireLamaran::class,
        \App\Console\Commands\TestDeleteExpiredNotif::class,
        \App\Console\Commands\NotifyExpiredLowongans::class,
        \App\Console\Commands\SendWeeklyNewsletter::class,
    ];


    protected function schedule(Schedule $schedule): void

    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('transaksi:cek-expired')->hourly();
        // $schedule->job(new \App\Jobs\ResetExpiredLowonganJob)->dailyAt('00:00');
        // $schedule->command('pelamar:delete-expired')->dailyAt('00:00');
        // $schedule->job(new \App\Jobs\ExpireLamaranJob)->dailyAt('00:00');
        // $schedule->job(new \App\Jobs\DeleteExpiredNotifJob)->dailyAt('01:00');
        // $schedule->command('langganan:cek-expired')->dailyAt('00:00');
        // Hapus notifikasi yang kadaluarsa tiap hari
        // $schedule->command('notifikasi:hapus-expired')->everyMinute();
        // $schedule->command('notify:expired-lowongans')->hourly();
        // $schedule->command('lowongan:clean-inactive')->daily();

        $base = url('/cron');

        $headers = [
            'X-CRON-TOKEN' => config('app.cron_token'),
        ];

        $call = fn(string $uri) =>
        Http::withHeaders($headers)
            ->timeout(30)
            ->get("{$base}{$uri}");

        // 1. Transaksi cash expired
        $schedule->call(fn() => $call('/transaksi/expire'))
            ->hourly(); 

        // 2. Reset lowongan expired
        $schedule->call(fn() => $call('/lowongan/reset-expired'))
            ->dailyAt('00:00');

        // 3. Hapus pelamar expired
        $schedule->call(fn() => $call('/pelamar/delete-expired'))
            ->dailyAt('00:00');

        // 4. Expire lamaran + notifikasi
        $schedule->call(fn() => $call('/lamaran/expire'))
            ->dailyAt('00:00');

        // 5. Hapus notifikasi lamaran expired > 3 hari
        $schedule->call(fn() => $call('/notifikasi/delete-lamaran-expired'))
            ->dailyAt('00:00');

        // 6. Cek langganan perusahaan
        $schedule->call(fn() => $call('/langganan/expire'))
            ->dailyAt('00:00');

        // 7. Hapus notifikasi expired global
        $schedule->call(fn() => $call('/notifikasi/delete-expired'))
            ->everyMinute();

        // 8. Notifikasi lowongan expired
        $schedule->call(fn() => $call('/lowongan/notify-expired'))
            ->hourly();

        // 9. Hapus lowongan tidak aktif 6 bulan
        $schedule->call(fn() => $call('/lowongan/clean-inactive'))
            ->dailyAt('00:00');

        // 10. Kirim Newsletter Mingguan ke Subscriber (Setiap Senin jam 08:00 WIB)
        $schedule->command('newsletter:send-weekly')
            ->weeklyOn(1, '08:00')
            ->withoutOverlapping()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
