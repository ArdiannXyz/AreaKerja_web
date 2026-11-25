<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use Illuminate\Console\Command;

class HapusNotifikasiKadaluarsa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifikasi:hapus-expired {--months= : Batas usia notifikasi dalam bulan untuk dihapus (default: 3 bulan)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus notifikasi yang lewat expired_at dan notifikasi lama yang sudah melewati batas bulan (default: 3 bulan)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $monthsOption = $this->option('months');
        $months = $monthsOption !== null ? (int) $monthsOption : (int) config('app.notification_retention_months', 3);
        if ($months <= 0) {
            $months = 3;
        }

        $limitTanggal = now()->subMonths($months);

        // 1. Hapus notifikasi yang sudah melewati tanggal expired_at
        $deletedExpired = Notifikasi::whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->delete();

        // 2. Hapus notifikasi lama yang sudah melewati batas retensi bulan (1 atau 3 bulan)
        $deletedOld = Notifikasi::where('created_at', '<', $limitTanggal)
            ->delete();

        $total = $deletedExpired + $deletedOld;

        $this->info("Pembersihan notifikasi selesai: {$deletedExpired} lewat expired_at, {$deletedOld} berusia > {$months} bulan. Total: {$total} notifikasi dihapus.");

        return Command::SUCCESS;
    }
}
