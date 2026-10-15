<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialLinkController extends Controller
{
    /**
     * Display social links and banner header management.
     */
    public function index()
    {
        $socials = SiteSettingService::getSocials();
        $headers = SiteSettingService::getHeaders();

        return view('super_admin.social.banner', [
            'socials' => $socials,
            'headers' => $headers,
        ]);
    }

    /**
     * Alias for headerImageIndex to match named routes.
     */
    public function headerImageIndex()
    {
        return $this->index();
    }

    /**
     * Update social media links.
     */
    public function update(Request $request)
    {
        $request->validate([
            'links'   => 'nullable|array',
            'links.*' => 'nullable|url',
        ], [
            'links.*.url' => 'Format URL tautan media sosial tidak valid.',
        ]);

        $links = $request->input('links', []);
        SiteSettingService::updateSocials($links);

        $socialMap = [
            1 => 'social_facebook',
            2 => 'social_youtube',
            3 => 'social_instagram',
            4 => 'social_linkedin',
            5 => 'social_twitter',
        ];

        foreach ($links as $id => $link) {
            if (isset($socialMap[$id])) {
                SiteSetting::set($socialMap[$id], $link);
            }
        }

        return back()->with('success', 'Tautan media sosial berhasil disimpan.');
    }

    /**
     * Update/upload header banner image.
     */
    public function headerImageUpdate(Request $request, $nama)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'image.required' => 'Silakan pilih berkas gambar terlebih dahulu.',
            'image.image'    => 'Berkas yang diunggah harus berupa gambar.',
            'image.mimes'    => 'Format gambar yang didukung: jpeg, png, jpg, webp, gif.',
            'image.max'      => 'Ukuran gambar maksimal adalah 5MB.',
        ]);

        $validNames = array_keys(SiteSettingService::getDefaultHeaders());

        if (!in_array($nama, $validNames)) {
            return back()->withErrors(['error' => 'Jenis header tidak valid.']);
        }

        try {
            $path = SiteSettingService::updateHeader($nama, $request->file('image'));
            SiteSetting::set($nama, $path);

            return back()->with('success', 'Banner image header berhasil diperbarui!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengunggah banner: ' . $e->getMessage());
        }
    }

    /**
     * Delete/reset header banner back to default.
     */
    public function headerImageDestroy($nama)
    {
        try {
            SiteSettingService::deleteHeader($nama);
            SiteSetting::set($nama, null);

            return back()->with('success', 'Banner image header berhasil direset ke default.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mereset banner: ' . $e->getMessage());
        }
    }

    /**
     * Footer social links view helper.
     */
    public function index_footer()
    {
        return view('layouts.footer', [
            'socialLinks' => SiteSettingService::getSocials(),
        ]);
    }
}
