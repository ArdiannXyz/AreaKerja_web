<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\TipsKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TipsKerjaController extends Controller
{
    public function index()
    {
        return view('admin.tips-kerja.tips-kerja', [
            "title"     =>   "Tips Kerja",
            "all"       =>    TipsKerja::count(),
            "terbit"    =>    TipsKerja::where('status', 'terbit')->count(),
            "noterbit"  =>    TipsKerja::where('status', 'belum terbit')->count(),
            "sudah_terbit"  =>    TipsKerja::where('status', 'terbit')->get(),
            "belum_terbit"  =>    TipsKerja::where('status', 'belum terbit')->get(),
            "semua"     => TipsKerja::orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function tips_kerja_tips1()
    {
        return view('non-user.tips-kerja1');
    }

    public function tips_kerja_buat_post()
    {
        return view('admin.tips-kerja.buat-post');
    }

    public function store_tips_kerja(Request $request)
    {
        try {
            $d = $request->validate([
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'penulis' => 'nullable|string',
                'image'   => 'nullable|file|mimes:png,jpg,jpeg,webp,pdf|max:10240',
                'status'  => 'nullable|in:terbit,belum terbit',
                'intro'   => 'nullable|string',
                'section' => 'nullable|json',
            ], [
                'title.required'   => 'Judul artikel wajib diisi.',
                'content.required' => 'Isi artikel wajib diisi.',
                'image.mimes'      => 'Format file cover harus berupa PNG, JPG, JPEG, WEBP, atau PDF.',
                'image.max'        => 'Ukuran file cover maksimal 10MB.',
            ]);

            // Slug
            if (!empty($request->title)) {
                $baseSlug = Str::slug($request->title);
                $slug = $baseSlug;

                $counter = 1;
                while (TipsKerja::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
            } else {
                $slug = 'tips-' . time();
            }
            $d['slug'] = $slug;

            $d['penulis'] = Auth::user()->username;
            $d['status'] = $request->input('status', 'belum terbit');

            if (empty($request->intro) && !empty($request->content)) {
                $d['intro'] = Str::limit(strip_tags($request->content), 150);
            } else {
                $d['intro'] = $request->intro;
            }

            if (empty($request->section) && !empty($request->content)) {
                $paragraphs = preg_split('/\r\n|\r|\n/', strip_tags($request->content));

                $sections = [];
                foreach ($paragraphs as $index => $pgr) {
                    if (trim($pgr) !== '') {
                        $sections[] = [
                            "judul" => "Bagian " . ($index + 1),
                            "isi"   => $pgr,
                        ];
                    }
                }

                $d['section'] = json_encode($sections);
            } else {
                $d['section'] = $request->section;
            }

            if ($request->hasFile('image')) {
                $d['image'] = $request->file('image')->store('images', 'public');
            }

            TipsKerja::create($d);

            /* NOTIFIKASI BERHASIL */
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Tips Kerja Berhasil Ditambahkan',
                'pesan' => 'Tips kerja dengan judul <b>' . ($request->title ?? 'Tanpa Judul') . '</b> berhasil disimpan.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7)
            ]);

            return redirect()->route('admin.tips-kerja')->with('success', 'Data berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {

            /* NOTIFIKASI GAGAL */
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Gagal Menyimpan Tips Kerja',
                'pesan' => 'Terjadi kesalahan saat menyimpan data tips kerja.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7)
            ]);

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $tips = TipsKerja::findOrFail($id);
        $newStatus = ($tips->status === 'terbit') ? 'belum terbit' : 'terbit';
        $tips->update(['status' => $newStatus]);

        $statusText = ($newStatus === 'terbit') ? 'diterbitkan' : 'diubah menjadi draf';
        return redirect()->back()->with('success', "Artikel '{$tips->title}' berhasil {$statusText}.");
    }



    public function update_status(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {

            Notifikasi::create([
                'user_id' => Auth::id(),
                'judul' => 'Gagal Mengubah Status Tips Kerja',
                'pesan' => 'Tidak ada data yang dipilih untuk diubah statusnya.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->back()->with('error', 'Data yang ingin diubah tidak ditemukan.');
        }

        TipsKerja::whereIn('id', $ids)->update([
            'status' => $request->status
        ]);

        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Status Tips Kerja Diperbarui',
            'pesan' => count($ids) . ' data tips kerja berhasil diubah statusnya menjadi <b>' . $request->status . '</b>.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
        ]);

        return redirect()->route('admin.tips-kerja')->with('success', 'Data berhasil diubah.');
    }


    public function destroy(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {

            Notifikasi::create([
                'user_id' => Auth::id(),
                'judul' => 'Gagal Menghapus Tips Kerja',
                'pesan' => 'Tidak ada data yang dipilih untuk dihapus.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->back()->with('error', 'Data yang ingin dihapus tidak ditemukan.');
        }

        TipsKerja::whereIn('id', $ids)->delete();

        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Tips Kerja Berhasil Dihapus',
            'pesan' => count($ids) . ' data tips kerja berhasil dihapus.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
        ]);

        return redirect()->route('admin.tips-kerja')->with('success', 'Data berhasil dihapus.');
    }

    public function edit($id)
    {
        $tips = TipsKerja::findOrFail($id);
        return view('admin.tips-kerja.edit', compact('tips'));
    }

    public function update(Request $request, $id)
    {
        $tips = TipsKerja::findOrFail($id);
        $d = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'image'    => 'nullable|file|mimes:png,jpg,jpeg,webp,pdf|max:10240',
            'status'   => 'nullable|in:terbit,belum terbit',
            'kategori' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $d['image'] = $request->file('image')->store('images', 'public');
        }

        $tips->update($d);

        return redirect()->route('admin.tips-kerja')->with('success', 'Tips kerja berhasil diperbarui.');
    }

    public function destroy_single($id)
    {
        $tips = TipsKerja::findOrFail($id);
        $tips->delete();

        return redirect()->route('admin.tips-kerja')->with('success', 'Tips kerja berhasil dihapus.');
    }






    //TIPS KERJA SUPERADMIN
    public function index_superadmin()
    {
        return view('super_admin.tips-kerja.tips-kerja', [
            "title"     =>   "Tips Kerja",
            "all"       =>    TipsKerja::count(),
            "terbit"    =>    TipsKerja::where('status', 'terbit')->count(),
            "noterbit"  =>    TipsKerja::where('status', 'belum terbit')->count(),
            "sudah_terbit"  =>    TipsKerja::where('status', 'terbit')->get(),
            "belum_terbit"  =>    TipsKerja::where('status', 'belum terbit')->get(),
            "semua"     => TipsKerja::orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function tips_kerja_buat_post_superadmin()
    {
        return view('super_admin.tips-kerja.buat-tips');
    }

    public function store_tips_kerja_superadmin(Request $request)
    {
        try {
            $d = $request->validate([
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'penulis' => 'nullable|string',
                'image'   => 'nullable|file|mimes:png,jpg,jpeg,webp,pdf|max:10240',
                'status'  => 'nullable',
                'intro'   => 'nullable|string',
                'section' => 'nullable|json',
            ], [
                'title.required'   => 'Judul artikel wajib diisi.',
                'content.required' => 'Isi artikel wajib diisi.',
                'image.mimes'      => 'Format file cover harus berupa PNG, JPG, JPEG, WEBP, atau PDF.',
                'image.max'        => 'Ukuran file cover maksimal 10MB.',
            ]);

            // SLUG
            if (!empty($request->title)) {
                $baseSlug = Str::slug($request->title);
                $slug = $baseSlug;

                $counter = 1;
                while (TipsKerja::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
            } else {
                $slug = 'tips-' . time();
            }

            $d['slug'] = $slug;

            // Set default
            $d['penulis'] = Auth::user()->username;
            $d['status'] = 'belum terbit';

            if (empty($request->intro) && !empty($request->content)) {
                $d['intro'] = Str::limit(strip_tags($request->content), 150);
            } else {
                $d['intro'] = $request->intro;
            }

            if (empty($request->section) && !empty($request->content)) {
                $paragraphs = preg_split('/\r\n|\r|\n/', strip_tags($request->content));

                $sections = [];
                foreach ($paragraphs as $index => $pgr) {
                    if (trim($pgr) !== '') {
                        $sections[] = [
                            "judul" => "Bagian " . ($index + 1),
                            "isi"   => $pgr,
                        ];
                    }
                }

                $d['section'] = json_encode($sections);
            } else {
                $d['section'] = $request->section;
            }

            if ($request->hasFile('image')) {
                $d['image'] = $request->file('image')->store('images', 'public');
            }

            TipsKerja::create($d);

            /* ========= NOTIFIKASI BERHASIL =========== */
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Tips Kerja Berhasil Ditambahkan (Superadmin)',
                'pesan' => 'Tips kerja dengan judul <b>' . ($request->title ?? 'Tanpa Judul') . '</b> berhasil disimpan.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->route('superadmin.tips-kerja')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {

            /* ========= NOTIFIKASI GAGAL =========== */
            Notifikasi::create([
                'user_id' => Auth::id(),
                'perusahaan_id' => null,
                'judul' => 'Gagal Menambah Tips Kerja (Superadmin)',
                'pesan' => 'Terjadi kesalahan saat menyimpan tips kerja.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        }
    }



    public function update_status_superadmin(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {

            Notifikasi::create([
                'user_id' => Auth::id(),
                'judul' => 'Gagal Mengubah Status Tips Kerja (Superadmin)',
                'pesan' => 'Tidak ada data yang dipilih untuk diubah statusnya.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        TipsKerja::whereIn('id', $ids)->update([
            'status' => $request->status
        ]);

        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Status Tips Kerja Diperbarui (Superadmin)',
            'pesan' => count($ids) . ' tips kerja berhasil diperbarui menjadi <b>' . $request->status . '</b>.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
        ]);

        return redirect()->route('superadmin.tips-kerja')->with('success', 'Data berhasil diubah.');
    }


    public function destroy_superadmin(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {

            Notifikasi::create([
                'user_id' => Auth::id(),
                'judul' => 'Gagal Menghapus Tips Kerja (Superadmin)',
                'pesan' => 'Tidak ada data yang dipilih untuk dihapus.',
                'is_read' => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        TipsKerja::whereIn('id', $ids)->delete();

        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Tips Kerja Berhasil Dihapus (Superadmin)',
            'pesan' => count($ids) . ' tips kerja berhasil dihapus.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
        ]);

        return redirect()->route('superadmin.tips-kerja')->with('success', 'Data berhasil dihapus.');
    }
}
