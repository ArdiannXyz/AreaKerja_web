<?php

namespace App\Console\Commands;

use App\Models\LowonganPerusahaan;
use Illuminate\Console\Command;

class CleanInactiveLowongan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lowongan:clean-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus lowongan yang tidak ada interaksi selama 6 bulan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil total sebelum delete
        $count = LowonganPerusahaan::where(function ($q) {
            $q->whereNull('last_activity')
                ->orWhere('last_activity', '<', now()->subMonths(6));
        })
            ->whereDoesntHave('pelamar')
            ->delete();

        $this->info("{$count} lowongan tidak aktif berhasil dihapus.");

        return Command::SUCCESS;
    }
}
