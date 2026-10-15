<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socials = collect([
            (object)['id' => 1, 'nama' => 'Facebook', 'link' => SiteSetting::get('social_facebook', 'https://facebook.com/areakerja')],
            (object)['id' => 2, 'nama' => 'Youtube', 'link' => SiteSetting::get('social_youtube', 'https://youtube.com/@areakerja')],
            (object)['id' => 3, 'nama' => 'Instagram', 'link' => SiteSetting::get('social_instagram', 'https://instagram.com/areakerjacom')],
            (object)['id' => 4, 'nama' => 'Linkedin', 'link' => SiteSetting::get('social_linkedin', 'https://linkedin.com/company/areakerja')],
            (object)['id' => 5, 'nama' => 'Twitter', 'link' => SiteSetting::get('social_twitter', 'https://twitter.com/areakerja')],
        ]);

        $headers = collect([
            (object)[
                'id' => 1,
                'nama' => 'header_pasang_lowongan',
                'link' => SiteSetting::get('header_pasang_lowongan'),
                'default' => 'images/tangan.png'
            ],
            (object)[
                'id' => 2,
                'nama' => 'header_talent_hunter',
                'link' => SiteSetting::get('header_talent_hunter'),
                'default' => 'images/woi.jpg'
            ],
            (object)[
                'id' => 3,
                'nama' => 'header_daftar_kandidat',
                'link' => SiteSetting::get('header_daftar_kandidat'),
                'default' => 'images/ntap.png'
            ],
        ]);

        return view('super_admin.social.banner', [
            'socials' => $socials,
            'headers' => $headers
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'links' => 'nullable|array',
            'links.*' => 'nullable|url',
        ]);

        $socialMap = [
            1 => 'social_facebook',
            2 => 'social_youtube',
            3 => 'social_instagram',
            4 => 'social_linkedin',
            5 => 'social_twitter',
        ];

        foreach ($request->input('links', []) as $id => $link) {
            if (isset($socialMap[$id])) {
                SiteSetting::set($socialMap[$id], $link);
            }
        }

        return back()->with('success', 'Tautan media sosial berhasil disimpan.');
    }

    public function index_footer()
    {
        $socialLinks = collect([
            (object)['nama' => 'facebook', 'link' => SiteSetting::get('social_facebook', 'https://facebook.com/areakerja')],
            (object)['nama' => 'youtube', 'link' => SiteSetting::get('social_youtube', 'https://youtube.com/@areakerja')],
            (object)['nama' => 'instagram', 'link' => SiteSetting::get('social_instagram', 'https://instagram.com/areakerjacom')],
            (object)['nama' => 'linkedin', 'link' => SiteSetting::get('social_linkedin', 'https://linkedin.com/company/areakerja')],
        ]);

        return view('layouts.footer', [
            'socialLinks' => $socialLinks
        ]);
    }

    public function headerImageUpdate(Request $request, $nama)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'image.required' => 'Silakan pilih berkas gambar terlebih dahulu.',
            'image.image' => 'Berkas harus berupa gambar valid (jpeg, png, jpg, webp, gif).',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $validNames = [
            'header_pasang_lowongan',
            'header_talent_hunter',
            'header_daftar_kandidat'
        ];

        if (!in_array($nama, $validNames)) {
            return back()->withErrors(['error' => 'Jenis header tidak valid.']);
        }

        $oldPath = SiteSetting::get($nama);
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $path = $request->file('image')->store('headers', 'public');
        SiteSetting::set($nama, $path);

        return back()->with('success', 'Banner image header berhasil diperbarui!');
    }

    public function headerImageDestroy($nama)
    {
        $oldPath = SiteSetting::get($nama);
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        SiteSetting::set($nama, null);

        return back()->with('success', 'Banner image header berhasil direset ke default.');
    }
}
