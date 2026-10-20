<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SiteSettingService
{
    protected static string $settingsPath = 'settings/site_settings.json';

    /**
     * Header default configurations for all active hero banners across the project.
     */
    public static function getDefaultHeaders(): array
    {
        return [
            'header_pasang_lowongan' => [
                'id'        => 1,
                'nama'      => 'header_pasang_lowongan',
                'title'     => 'Pasang Lowongan',
                'link'      => null,
                'default'   => 'images/tangan.png',
                'deskripsi' => 'Banner hero halaman Pasang Lowongan publik dan perusahaan',
            ],
            'header_talent_hunter' => [
                'id'        => 2,
                'nama'      => 'header_talent_hunter',
                'title'     => 'Talent Hunter',
                'link'      => null,
                'default'   => 'images/woi.jpg',
                'deskripsi' => 'Banner hero halaman Talent Hunter publik dan perusahaan',
            ],
            'header_daftar_kandidat' => [
                'id'        => 3,
                'nama'      => 'header_daftar_kandidat',
                'title'     => 'Daftar Kandidat',
                'link'      => null,
                'default'   => 'images/ntap.png',
                'deskripsi' => 'Banner hero halaman Daftar Kandidat dan Kandidat AreaKerja',
            ],
            'header_pelamar_perusahaan' => [
                'id'        => 4,
                'nama'      => 'header_pelamar_perusahaan',
                'title'     => 'Pelamar Perusahaan',
                'link'      => null,
                'default'   => 'images/tangan.png',
                'deskripsi' => 'Banner hero halaman riwayat pelamar masuk di dashboard perusahaan',
            ],
            'header_berlangganan' => [
                'id'        => 5,
                'nama'      => 'header_berlangganan',
                'title'     => 'Halaman Berlangganan',
                'link'      => null,
                'default'   => 'images/ntap.png',
                'deskripsi' => 'Banner hero halaman paket langganan perusahaan',
            ],
            'header_request_data' => [
                'id'        => 6,
                'nama'      => 'header_request_data',
                'title'     => 'Request Data Pekerja',
                'link'      => null,
                'default'   => 'images/ntap.png',
                'deskripsi' => 'Banner hero halaman request data pekerja bagi perusahaan langganan',
            ],
            'header_rekrut_pelamar' => [
                'id'        => 7,
                'nama'      => 'header_rekrut_pelamar',
                'title'     => 'Rekrut Saya (Kandidat)',
                'link'      => null,
                'default'   => 'images/ter.jpg',
                'deskripsi' => 'Banner hero halaman tawaran rekrutmen kandidat',
            ],
            'header_syarat_ketentuan' => [
                'id'        => 8,
                'nama'      => 'header_syarat_ketentuan',
                'title'     => 'Syarat & Ketentuan',
                'link'      => null,
                'default'   => 'images/syarat-ketentuan.png',
                'deskripsi' => 'Banner hero halaman Syarat dan Ketentuan layanan',
            ],
            'header_tips_kerja' => [
                'id'        => 9,
                'nama'      => 'header_tips_kerja',
                'title'     => 'Tips Kerja',
                'link'      => null,
                'default'   => 'images/tips_kerja_default.png',
                'deskripsi' => 'Banner hero utama halaman Tips Kerja publik',
            ],
        ];
    }

    /**
     * Social media links default configurations.
     * Each entry includes 'icon' — a Phosphor icon class (ph-fill ph-xxx).
     */
    public static function getDefaultSocials(): array
    {
        return [
            1 => ['id' => 1, 'nama' => 'Facebook',  'icon' => 'ph-fill ph-facebook-logo',  'link' => 'https://facebook.com/areakerja'],
            2 => ['id' => 2, 'nama' => 'Youtube',   'icon' => 'ph-fill ph-youtube-logo',   'link' => 'https://youtube.com/@areakerja'],
            3 => ['id' => 3, 'nama' => 'Instagram', 'icon' => 'ph-fill ph-instagram-logo', 'link' => 'https://instagram.com/areakerjacom'],
            4 => ['id' => 4, 'nama' => 'Linkedin',  'icon' => 'ph-fill ph-linkedin-logo',  'link' => 'https://linkedin.com/company/areakerja'],
            5 => ['id' => 5, 'nama' => 'Twitter',   'icon' => 'ph-fill ph-twitter-logo',   'link' => 'https://twitter.com/areakerja'],
        ];
    }

    /**
     * Available Phosphor social / brand icons for the admin icon picker.
     */
    public static function getAvailableIcons(): array
    {
        return [
            'ph-fill ph-facebook-logo'  => 'Facebook',
            'ph-fill ph-instagram-logo' => 'Instagram',
            'ph-fill ph-twitter-logo'   => 'Twitter / X',
            'ph-fill ph-linkedin-logo'  => 'LinkedIn',
            'ph-fill ph-youtube-logo'   => 'YouTube',
            'ph-fill ph-tiktok-logo'    => 'TikTok',
            'ph-fill ph-whatsapp-logo'  => 'WhatsApp',
            'ph-fill ph-telegram-logo'  => 'Telegram',
            'ph-fill ph-github-logo'    => 'GitHub',
            'ph-fill ph-globe'          => 'Website / Lainnya',
        ];
    }

    /**
     * Get all raw settings from JSON storage.
     */
    public static function getSettings(): array
    {
        if (Storage::disk('local')->exists(self::$settingsPath)) {
            $content = Storage::disk('local')->get(self::$settingsPath);
            $data = json_decode($content, true);
            if (is_array($data)) {
                return $data;
            }
        }

        return [
            'headers' => [],
            'socials' => [],
        ];
    }

    /**
     * Save raw settings to JSON storage.
     */
    public static function saveSettings(array $settings): void
    {
        Storage::disk('local')->put(
            self::$settingsPath,
            json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Get headers collection merged with stored custom links.
     */
    public static function getHeaders(): Collection
    {
        $settings = self::getSettings();
        $storedHeaders = $settings['headers'] ?? [];
        $defaults = self::getDefaultHeaders();

        $result = collect();

        foreach ($defaults as $key => $defaultItem) {
            $item = $defaultItem;
            if (isset($storedHeaders[$key]) && !empty($storedHeaders[$key])) {
                $customPath = $storedHeaders[$key];
                // Check if file physically exists in public storage
                if (Storage::disk('public')->exists($customPath)) {
                    $item['link'] = $customPath;
                } else {
                    $item['link'] = null;
                }
            }
            $result->push((object) $item);
        }

        return $result;
    }

    /**
     * Get direct URL for a specific header banner.
     */
    public static function getHeaderUrl(string $nama, ?string $fallback = null): string
    {
        $settings = self::getSettings();
        $storedHeaders = $settings['headers'] ?? [];
        $headerPath = $storedHeaders[$nama] ?? (class_exists(\App\Models\SiteSetting::class) ? \App\Models\SiteSetting::get($nama) : null);

        if (!empty($headerPath) && Storage::disk('public')->exists($headerPath)) {
            $fullPath = Storage::disk('public')->path($headerPath);
            $version = file_exists($fullPath) ? filemtime($fullPath) : time();
            return asset('storage/' . $headerPath) . '?v=' . $version;
        }

        $defaults = self::getDefaultHeaders();
        $defaultImage = $fallback ?? ($defaults[$nama]['default'] ?? 'images/logoarea.png');

        return asset($defaultImage);
    }

    /**
     * Update/upload a header banner image.
     */
    public static function updateHeader(string $nama, UploadedFile $file): string
    {
        $settings = self::getSettings();
        $storedHeaders = $settings['headers'] ?? [];

        // Delete old file if exists
        if (!empty($storedHeaders[$nama]) && Storage::disk('public')->exists($storedHeaders[$nama])) {
            Storage::disk('public')->delete($storedHeaders[$nama]);
        }

        // Store new file in 'headers' folder on public disk
        $path = $file->store('headers', 'public');

        $storedHeaders[$nama] = $path;
        $settings['headers'] = $storedHeaders;
        self::saveSettings($settings);

        if (class_exists(\App\Models\SiteSetting::class)) {
            \App\Models\SiteSetting::set($nama, $path);
        }

        return $path;
    }

    /**
     * Delete/reset a header banner back to default.
     */
    public static function deleteHeader(string $nama): bool
    {
        $settings = self::getSettings();
        $storedHeaders = $settings['headers'] ?? [];

        if (!empty($storedHeaders[$nama])) {
            if (Storage::disk('public')->exists($storedHeaders[$nama])) {
                Storage::disk('public')->delete($storedHeaders[$nama]);
            }
            unset($storedHeaders[$nama]);
            $settings['headers'] = $storedHeaders;
            self::saveSettings($settings);
        }

        if (class_exists(\App\Models\SiteSetting::class)) {
            \App\Models\SiteSetting::set($nama, null);
        }

        return true;
    }

    /**
     * Get all social links (defaults merged with stored overrides + custom entries).
     * Returns a Collection of objects with: id, nama, icon, link.
     */
    public static function getSocials(): Collection
    {
        $settings    = self::getSettings();
        $storedSocials = $settings['socials_config'] ?? [];  // full config override
        $storedLinks = $settings['socials'] ?? [];            // legacy link-only overrides
        $defaults    = self::getDefaultSocials();

        // Legacy SiteSetting DB map (still respected for backward compat)
        $socialMap = [
            1 => 'social_facebook',
            2 => 'social_youtube',
            3 => 'social_instagram',
            4 => 'social_linkedin',
            5 => 'social_twitter',
        ];

        $result = collect();

        // If full config is stored, use it directly (preserves order, icon, nama)
        if (!empty($storedSocials)) {
            foreach ($storedSocials as $item) {
                $result->push((object) $item);
            }
            return $result;
        }

        // Fallback: merge defaults with legacy link overrides
        foreach ($defaults as $id => $item) {
            if (isset($storedLinks[$id])) {
                $item['link'] = $storedLinks[$id];
            } elseif (isset($socialMap[$id]) && class_exists(\App\Models\SiteSetting::class)) {
                $dbVal = \App\Models\SiteSetting::get($socialMap[$id]);
                if ($dbVal) {
                    $item['link'] = $dbVal;
                }
            }
            $result->push((object) $item);
        }

        return $result;
    }

    /**
     * Update social links (link-only, legacy support).
     */
    public static function updateSocials(array $links): void
    {
        $settings = self::getSettings();
        $storedSocials = $settings['socials'] ?? [];

        $socialMap = [
            1 => 'social_facebook',
            2 => 'social_youtube',
            3 => 'social_instagram',
            4 => 'social_linkedin',
            5 => 'social_twitter',
        ];

        foreach ($links as $id => $link) {
            $storedSocials[$id] = $link;
            if (isset($socialMap[$id]) && class_exists(\App\Models\SiteSetting::class)) {
                \App\Models\SiteSetting::set($socialMap[$id], $link);
            }
        }

        $settings['socials'] = $storedSocials;
        self::saveSettings($settings);
    }

    /**
     * Replace the full social config (nama, icon, link for each entry).
     * Used by the admin social management UI.
     *
     * @param array $socials  Array of ['nama'=>..., 'icon'=>..., 'link'=>...]
     */
    public static function updateSocialsConfig(array $socials): void
    {
        $settings = self::getSettings();

        $config = [];
        foreach (array_values($socials) as $idx => $item) {
            $config[] = [
                'id'   => $idx + 1,
                'nama' => trim($item['nama'] ?? ''),
                'icon' => trim($item['icon'] ?? 'ph-fill ph-globe'),
                'link' => trim($item['link'] ?? ''),
            ];
        }

        $settings['socials_config'] = $config;
        self::saveSettings($settings);
    }

    /**
     * Reset social config back to defaults.
     */
    public static function resetSocialsConfig(): void
    {
        $settings = self::getSettings();
        unset($settings['socials_config']);
        self::saveSettings($settings);
    }
}

