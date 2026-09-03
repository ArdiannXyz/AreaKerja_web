<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socials = collect([
            (object)['id' => 1, 'nama' => 'Facebook', 'link' => 'https://facebook.com/areakerja'],
            (object)['id' => 2, 'nama' => 'Youtube', 'link' => 'https://youtube.com/@areakerja'],
            (object)['id' => 3, 'nama' => 'Instagram', 'link' => 'https://instagram.com/areakerjacom'],
            (object)['id' => 4, 'nama' => 'Linkedin', 'link' => 'https://linkedin.com/company/areakerja'],
            (object)['id' => 5, 'nama' => 'Twitter', 'link' => 'https://twitter.com/areakerja'],
        ]);

        $headers = collect([
            (object)['id' => 1, 'nama' => 'header_pasang_lowongan', 'link' => null, 'default' => 'images/woi.jpg'],
            (object)['id' => 2, 'nama' => 'header_talent_hunter', 'link' => null, 'default' => 'images/woi.jpg'],
            (object)['id' => 3, 'nama' => 'header_daftar_kandidat', 'link' => null, 'default' => 'images/ntap.png'],
        ]);

        return view('super_admin.social.banner', [
            'socials' => $socials,
            'headers' => $headers
        ]);
    }

    public function update(Request $request)
    {
        return back()->with('success', 'Berhasil Mengupdate Social Link');
    }

    public function index_footer()
    {
        $socialLinks = collect([
            (object)['nama' => 'facebook', 'link' => 'https://facebook.com/areakerja'],
            (object)['nama' => 'youtube', 'link' => 'https://youtube.com/@areakerja'],
            (object)['nama' => 'instagram', 'link' => 'https://instagram.com/areakerjacom'],
            (object)['nama' => 'linkedin', 'link' => 'https://linkedin.com/company/areakerja'],
        ]);

        return view('layouts.footer', [
            'socialLinks' => $socialLinks
        ]);
    }

    public function headerImageUpdate(Request $request, $nama)
    {
        return back()->with('success', 'Header image berhasil diupdate');
    }

    public function headerImageDestroy($nama)
    {
        return back()->with('success', 'Header image berhasil dihapus');
    }
}
