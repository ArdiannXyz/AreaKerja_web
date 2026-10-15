<?php

namespace App\Http\Controllers;

use App\Services\SiteSettingService;
use Illuminate\Http\Request;

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

        return back()->with('success', 'Berhasil memperbarui tautan media sosial.');
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

        try {
            SiteSettingService::updateHeader($nama, $request->file('image'));

            return back()->with('success', 'Banner Image Header berhasil diperbarui!');
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

            return back()->with('success', 'Banner Image Header berhasil dikembalikan ke default.');
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
