<?php

namespace App\Console\Commands;

use App\Models\LowonganPerusahaan;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate public/sitemap.xml untuk halaman statis dan lowongan kerja aktif';

    private const MAX_URLS_PER_SITEMAP = 50000;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $baseUrl = $this->resolveBaseUrl();
        $entries = [];

        foreach ($this->staticPages($baseUrl) as $url) {
            $entries[] = $url;
        }

        LowonganPerusahaan::query()
            ->with('perusahaan:id,slug')
            ->select(['id', 'perusahaan_id', 'slug', 'published_at', 'updated_at'])
            ->publik()
            ->whereNotNull('published_at')
            ->whereNotNull('slug')
            ->where('status', '!=', 'tutup')
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('batas_lamaran')
                    ->orWhere('batas_lamaran', '>=', now()->toDateString());
            })
            ->whereHas('perusahaan', function ($q) {
                $q->whereNotNull('slug');
            })
            ->chunkById(500, function ($lowongans) use (&$entries, $baseUrl) {
                foreach ($lowongans as $lowongan) {
                    $companySlug = $lowongan->perusahaan->slug ?? null;
                    if (! $companySlug || ! $lowongan->slug) {
                        continue;
                    }

                    $lastMod = $lowongan->updated_at ?? $lowongan->published_at ?? now();

                    $entries[] = Url::create("{$baseUrl}/pelamar/detail-lowongan/{$companySlug}/{$lowongan->slug}")
                        ->setLastModificationDate($lastMod)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8);
                }
            });

        $totalUrls = count($entries);

        if ($totalUrls <= self::MAX_URLS_PER_SITEMAP) {
            $sitemap = Sitemap::create();
            foreach ($entries as $entry) {
                $sitemap->add($entry);
            }
            $sitemap->writeToFile(public_path('sitemap.xml'));
        } else {
            $index = SitemapIndex::create();
            foreach (array_chunk($entries, self::MAX_URLS_PER_SITEMAP) as $idx => $chunk) {
                $partNumber = $idx + 1;
                $partFilename = "sitemap-{$partNumber}.xml";

                $partSitemap = Sitemap::create();
                foreach ($chunk as $entry) {
                    $partSitemap->add($entry);
                }
                $partSitemap->writeToFile(public_path($partFilename));

                $index->add("{$baseUrl}/{$partFilename}");
            }
            $index->writeToFile(public_path('sitemap.xml'));
        }

        $this->info("Sitemap berhasil dibuat ({$totalUrls} URL) di public/sitemap.xml.");

        return Command::SUCCESS;
    }

    private function resolveBaseUrl(): string
    {
        $appUrl = rtrim((string) config('app.url'), '/');

        if (str_starts_with($appUrl, 'https://areakerja.com')) {
            return $appUrl;
        }

        return 'https://areakerja.com';
    }

    /**
     * @return array<int, Url>
     */
    private function staticPages(string $baseUrl): array
    {
        $paths = [
            ['/', 1.0, Url::CHANGE_FREQUENCY_DAILY],
            ['/search', 0.9, Url::CHANGE_FREQUENCY_DAILY],
            ['/pelamar/tips-kerja', 0.7, Url::CHANGE_FREQUENCY_WEEKLY],
            ['/event', 0.7, Url::CHANGE_FREQUENCY_WEEKLY],
            ['/lowongan', 0.6, Url::CHANGE_FREQUENCY_MONTHLY],
            ['/talent-hunter', 0.6, Url::CHANGE_FREQUENCY_MONTHLY],
            ['/faq', 0.5, Url::CHANGE_FREQUENCY_MONTHLY],
            ['/syarat-dan-ketentuan', 0.3, Url::CHANGE_FREQUENCY_YEARLY],
        ];

        $urls = [];
        foreach ($paths as [$path, $priority, $freq]) {
            $loc = $path === '/' ? "{$baseUrl}/" : "{$baseUrl}{$path}";
            $urls[] = Url::create($loc)
                ->setLastModificationDate(now())
                ->setChangeFrequency($freq)
                ->setPriority($priority);
        }

        return $urls;
    }
}
