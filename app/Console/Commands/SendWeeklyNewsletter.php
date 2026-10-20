<?php

namespace App\Console\Commands;

use App\Mail\WeeklyNewsletterMail;
use App\Models\EmailSubscriber;
use App\Models\LowonganPerusahaan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWeeklyNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send-weekly 
                            {--test= : Kirim hanya ke satu email untuk pengetesan} 
                            {--limit=6 : Jumlah lowongan terbaru yang dimuat}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email newsletter mingguan berisi rekomendasi lowongan terbaru ke semua subscriber aktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) ($this->option('limit') ?: 6);
        $testEmail = $this->option('test');

        $this->info("Mengambil {$limit} lowongan terbaru yang aktif...");

        // Ambil lowongan aktif, belum expired, dan perusahaan valid
        $lowongans = LowonganPerusahaan::publik()->where('status', '!=', 'tutup')
            ->whereHas('perusahaan')
            ->where('expired_at', '>', now())
            ->with(['perusahaan'])
            ->latest()
            ->take($limit)
            ->get();

        if ($lowongans->isEmpty()) {
            $this->warn('Tidak ada lowongan aktif yang ditemukan. Pengiriman newsletter dibatalkan.');
            Log::info('Newsletter weekly skipped: Tidak ada lowongan aktif.');
            return 0;
        }

        $this->info("Ditemukan {$lowongans->count()} lowongan aktif.");

        // Mode Pengetesan Tunggal (--test=email@example.com)
        if ($testEmail) {
            $this->info("Mengirim email uji coba ke: {$testEmail}");

            $subscriber = EmailSubscriber::firstOrNew(
                ['email' => strtolower(trim($testEmail))],
                ['is_active' => true]
            );

            try {
                Mail::to($testEmail)->send(new WeeklyNewsletterMail($lowongans, $subscriber));
                $this->info("✓ Berhasil mengirim email uji coba ke {$testEmail}!");
            } catch (\Throwable $e) {
                $this->error("Gagal mengirim ke {$testEmail}: " . $e->getMessage());
                Log::error("Newsletter test send failed to {$testEmail}: " . $e->getMessage());
                return 1;
            }

            return 0;
        }

        // Mode Broadcast ke seluruh subscriber aktif
        $query = EmailSubscriber::where('is_active', true);
        $totalSubscribers = $query->count();

        if ($totalSubscribers === 0) {
            $this->info('Tidak ada subscriber aktif yang terdaftar.');
            return 0;
        }

        $this->info("Memulai pengiriman newsletter ke {$totalSubscribers} subscriber aktif...");

        $successCount = 0;
        $failCount = 0;

        // Chunking untuk efisiensi memori dan keandalan antrian/server
        $query->chunk(50, function ($subscribers) use ($lowongans, &$successCount, &$failCount) {
            foreach ($subscribers as $subscriber) {
                try {
                    Mail::to($subscriber->email)->send(new WeeklyNewsletterMail($lowongans, $subscriber));
                    $successCount++;
                } catch (\Throwable $e) {
                    $failCount++;
                    Log::error("Gagal mengirim newsletter ke {$subscriber->email}: " . $e->getMessage());
                }
            }
        });

        $this->info("Pengiriman selesai! Berhasil: {$successCount}, Gagal: {$failCount}.");
        Log::info("Newsletter weekly broadcast completed. Success: {$successCount}, Failed: {$failCount}");

        return 0;
    }
}

