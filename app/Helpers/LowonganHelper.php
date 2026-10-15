<?php

use App\Models\LowonganPerusahaan;

if (!function_exists('route_lowongan')) {
    function route_lowongan(LowonganPerusahaan $lowongan)
    {
        return route('detail.lowongan.non.user', [
            'perusahaan' => $lowongan->perusahaan->slug,
            'lowongan'   => $lowongan->slug,
        ]);
    }
}

if (!function_exists('url_lowongan')) {
    function url_lowongan(LowonganPerusahaan $lowongan)
    {
        return url(route_lowongan($lowongan));
    }
}

if (!function_exists('header_image')) {
    function header_image(string $key, string $defaultPath = 'images/logoarea.png'): string
    {
        return \App\Services\SiteSettingService::getHeaderUrl($key, $defaultPath);
    }
}
