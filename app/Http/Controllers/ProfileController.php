<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $pelamar = Pelamar::where('user_id', $user->id)->first();

        return view('non-user.profile.profile', compact('pelamar'));
    }


    public function update_profile(Request $request, Pelamar $pelamar)
    {
        try {

            $validated = $request->validate([
                // "username"        => "nullable",
                "nama_pelamar"    => "nullable",
                "img_profile"     => "nullable|file|image",
                "gender"          => "nullable",
                "tanggal_lahir"   => "nullable",
                "deskripsi_diri"  => "nullable",
                "gaji_minimal"    => "nullable",
                "gaji_maksimal"   => "nullable",
 
                // VALIDASI TELEPON PELAMAR
                "telepon_pelamar" => [
                    "nullable",
                    "regex:/^(?:628|08)[0-9]+$/"
                ],
            ], [
                "telepon_pelamar.regex" => "Nomor telepon harus diawali dengan 08, atau 628."
            ]);

            /* ==========================
            NOMOR TELEPON
        =========================== */
            if (!empty($request->telepon_pelamar)) {

                // Hapus karakter non angka kecuali +
                $telepon = preg_replace('/[^0-9]/', '', $request->telepon_pelamar);

                // 62 → 0
                $telepon = preg_replace('/^62/', '0', $telepon);

                $validated['telepon_pelamar'] = $telepon;
            }

            /* ==========================
            HANDLE GAMBAR PROFILE
        =========================== */
            if ($request->hasFile('img_profile')) {
                if ($pelamar->img_profile && Storage::exists('public/' . $pelamar->img_profile)) {
                    Storage::delete('public/' . $pelamar->img_profile);
                }

                $validated['img_profile'] = $request
                    ->file('img_profile')
                    ->store('images', 'public');
            }

            $validated['user_id'] = Auth::id();

            if ($request->filled('username')) {
                Auth::user()->update([
                    'username' => $request->username
                ]);
            }

            $sosmed = $request->only(['instagram', 'linkedin', 'website', 'twitter']);
            $validated['social_links'] = $sosmed;

            $pelamar->update($validated);

            /* ==========================
         NOTIFIKASI BERHASIL
        =========================== */
            Notifikasi::create([
                'user_id'   => Auth::id(),
                'judul'     => 'Profil Berhasil Diperbarui',
                'pesan'     => 'Data profil Anda telah berhasil disimpan.',
                'is_read'   => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->route('profile.index')
                ->with('success', 'Profile berhasil diperbarui');
        } catch (\Exception $e) {

            /* ==========================
         NOTIFIKASI GAGAL
        =========================== */
            Notifikasi::create([
                'user_id'   => Auth::id(),
                'judul'     => 'Gagal Memperbarui Profil',
                'pesan'     => 'Terjadi kesalahan saat menyimpan data profil Anda.',
                'is_read'   => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Profile gagal diperbarui! Error: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function destroy_profile(Pelamar $pelamar)
    {

        if ($pelamar->img_profile && Storage::exists('public/' . $pelamar->img_profile)) {
            Storage::delete('public/' . $pelamar->img_profile);
        }

        $pelamar->img_profile = null;
        $pelamar->save();
        return redirect()->route('profile.index')->with('success', 'Profile berhasil dihapus');
    }



    public function alamat()
    {

        $user = auth()->user();

        $pelamar = Pelamar::where('user_id', $user->id)
            ->with('pengalaman_organisasi')
            ->first();
        $alamatCount = $pelamar->alamat_pelamar()->count();

        return view('non-user.alamat.index', [
            'pelamar' => $pelamar,
            'alamatCount' => $alamatCount
        ]);
    }

    public function form_alamat()
    {
        $user = auth()->user();

        $pelamar = Pelamar::where('user_id', $user->id)
            ->with('pengalaman_organisasi')
            ->first();
        // $alamatCount = $pelamar->alamat_pelamar()->count();

        return view('non-user.alamat.create-alamat', [
            'pelamar' => $pelamar,
            // 'alamatCount' => $alamatCount
        ]);
    }

    public function store_alamat(Request $request)
    {
        $user = Auth::user();

        try {
            // Hitung alamat yang sudah ada
            $jumlahAlamat = $user->pelamar->alamat_pelamar()->count();

            // Jika sudah 4 → stop + buat notifikasi
            if ($jumlahAlamat >= 4) {

                Notifikasi::create([
                    'user_id'   => $user->id,
                    'judul'     => 'Batas Maksimal Alamat',
                    'pesan'     => 'Anda hanya dapat menyimpan maksimal 4 alamat.',
                    'is_read'   => 0,
                    'expired_at' => now()->addDays(7),
                ]);

                return redirect()->route('alamat')
                    ->with('error', 'Maksimal 4 alamat diperbolehkan.');
            }

            // Validasi
            $validated = $request->validate([
                'label'      => 'nullable',
                'desa'       => 'nullable',
                'kecamatan'  => 'nullable',
                'kota'       => 'nullable',
                'provinsi'   => 'nullable',
                'kode_pos'   => 'nullable',
                'detail'     => 'nullable'
            ]);

            $validated['pelamar_id'] = $user->pelamar->id;

            AlamatPelamar::create($validated);

            /* ============================
           NOTIFIKASI BERHASIL
        ============================ */
            Notifikasi::create([
                'user_id'   => $user->id,
                'judul'     => 'Alamat Berhasil Ditambahkan',
                'pesan'     => 'Alamat baru Anda telah berhasil disimpan.',
                'is_read'   => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->route('alamat')
                ->with('success', 'Alamat berhasil disimpan.');
        } catch (\Exception $e) {

            /* ============================
           NOTIFIKASI GAGAL
        ============================ */
            Notifikasi::create([
                'user_id'   => $user->id,
                'judul'     => 'Gagal Menyimpan Alamat',
                'pesan'     => 'Terjadi kesalahan saat menyimpan alamat Anda.',
                'is_read'   => 0,
                'expired_at' => now()->addDays(7),
            ]);

            return redirect()->route('alamat')
                ->with('error', 'Terjadi kesalahan! Alamat gagal disimpan.');
        }
    }



    public function edit_alamat(AlamatPelamar $alamatpelamar)
    {
        session(['profile_popup_closed' => true]);
        session()->forget('show_first_login_popup');

        return view('non-user.alamat.edit', ["data" => $alamatpelamar]);
    }
    public function update_alamat(Request $request, AlamatPelamar $alamatpelamar)
    {
        $validated = $request->validate([
            'label'  => 'nullable',
            'desa'   => 'nullable',
            'kecamatan' => 'nullable',
            'kota'  =>  'nullable',
            'provinsi' => 'nullable',
            'kode_pos' => 'nullable',
            'detail' =>   'nullable'
        ]);

        $validated['pelamar_id'] = Auth::user()->pelamar->id;
        $alamatpelamar->update($validated);
        return redirect()->route('alamat')->with('success', 'Alamat berhasil diupdate');
    }

    public function destroy_alamat(AlamatPelamar $alamatpelamar)
    {

        $alamatpelamar->delete();
        return redirect()->route('alamat')->with('success', 'Alamat berhasil dihapus');
    }



    //SUPER ADMIN
    public function store_alamatSuper(Request $request)
    {
        $validated = $request->validate([
            'label'  => 'nullable',
            'desa'   => 'nullable',
            'kecamatan' => 'nullable',
            'kota'  =>  'nullable',
            'provinsi' => 'nullable',
            'kode_pos' => 'nullable',
            'detail' =>   'nullable'
        ]);

        $pelamar_id = session('pelamar_terakhir_id');

        if (!$pelamar_id) {
            return back()->with('error', 'Pelamar belum dibuat. Harap buat pelamar terlebih dahulu sebelum menambahkan pendidikan.');
        }

        $validated['pelamar_id'] = $pelamar_id;
        AlamatPelamar::create($validated);
        $pelamar = Pelamar::find($pelamar_id);


        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';

        return redirect()->route('superadmin.pelamar.create', ['kategori' => $kategori])
            ->with('success', 'Organisasi berhasil disimpan');
    }

    public function edit_alamatSuper(AlamatPelamar $alamatpelamar)
    {
        return view('super_admin.pelamar.modal.edit.edit_alamat', ["data" => $alamatpelamar]);
    }
    public function update_alamatSuper(Request $request, ?AlamatPelamar $alamatpelamar = null)
    {
        // dd($request->all());
        $validated = $request->validate([
            'pelamar_id' => 'required|exists:pelamars,id',
            'label'  => 'nullable',
            'desa'   => 'nullable',
            'kecamatan' => 'nullable',
            'kota'  =>  'nullable',
            'provinsi' => 'nullable',
            'kode_pos' => 'nullable',
            'detail' =>   'nullable'
        ]);

        $pelamar_id = $validated['pelamar_id'];

        if ($alamatpelamar && $alamatpelamar->exists) {
            $alamatpelamar->update($validated);
        } else {
            $alamatpelamar = AlamatPelamar::create($validated);
        }

        $pelamar = Pelamar::find($pelamar_id);

        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';
        // $alamatpelamar->update($validated);
        return redirect()->route('superadmin.pelamar.edit', [
            'kategori' => $kategori,
            'id' => $pelamar_id
        ])->with('success', 'Data organisasi berhasil disimpan.');
    }
    public function destroy_alamatSuper(AlamatPelamar $alamatpelamar)
    {

        $alamatpelamar->delete();
        return redirect()->back()->with('success', 'Alamat berhasil dihapus');
    }




    //update status kandidat
    public function updateKategori(Request $request, $id)
    {
        $pelamar = Pelamar::findOrFail($id);

        $pelamar->kategori = $request->kategori; // kandidat nonaktif
        $pelamar->save();

        return response()->json(['success' => true]);
    }
}
