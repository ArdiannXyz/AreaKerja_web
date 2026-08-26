<?php

namespace App\Http\Controllers;

use App\Models\AlamatPelamar;
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

    public function edit()
    {
        $user = auth()->user();

        $pelamar = Pelamar::where('user_id', $user->id)->first();

        return view('non-user.profile.edit', compact('pelamar'));
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
                $telepon = preg_replace('/[^0-9]/', '', $request->telepon_pelamar);
                $telepon = preg_replace('/^62/', '0', $telepon);
                $validated['telepon_pelamar'] = $telepon;
            }

            if ($request->has('gaji_minimal')) {
                $validated['gaji_minimal'] = $request->gaji_minimal !== null ? preg_replace('/[^0-9]/', '', (string)$request->gaji_minimal) : null;
            }
            if ($request->has('gaji_maksimal')) {
                $validated['gaji_maksimal'] = $request->gaji_maksimal !== null ? preg_replace('/[^0-9]/', '', (string)$request->gaji_maksimal) : null;
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

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        $alamatCount = is_countable($pelamar?->alamat_pelamar) ? count($pelamar->alamat_pelamar) : 0;

        return view('non-user.alamat.index', [
            'pelamar'     => $pelamar,
            'alamatCount' => $alamatCount
        ]);
    }

    public function form_alamat()
    {
        $user = auth()->user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        $provinsis = collect();
        if (file_exists(database_path('data/provinces.json'))) {
            $content = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/provinces.json')));
            $json = json_decode(trim($content), true) ?? [];
            $provinsis = collect($json)->map(function ($item) {
                return (object)[
                    'id'   => (string)$item['id'],
                    'nama' => ucwords(strtolower($item['name'])),
                ];
            })->sortBy('nama')->values();
        }

        $allKotas = collect();
        if (file_exists(database_path('data/regencies.json'))) {
            $content = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/regencies.json')));
            $json = json_decode(trim($content), true) ?? [];
            $allKotas = collect($json)->map(function ($item) {
                return [
                    'id'          => (string)$item['id'],
                    'provinsi_id' => (string)$item['province_id'],
                    'nama'        => ucwords(strtolower($item['name'])),
                ];
            })->sortBy('nama')->values();
        }

        $allKecamatans = collect();
        if (file_exists(database_path('data/districts.json'))) {
            $content = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/districts.json')));
            $content = trim($content);
            if (function_exists('mb_convert_encoding')) {
                $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
            }
            $json = json_decode($content, true) ?? [];
            $allKecamatans = collect($json)->map(function ($item) {
                return [
                    'id'      => (string)$item['id'],
                    'kota_id' => (string)$item['regency_id'],
                    'nama'    => ucwords(strtolower($item['name'])),
                ];
            })->sortBy('nama')->values();
        }

        return view('non-user.alamat.create-alamat', [
            'pelamar'       => $pelamar,
            'provinsis'     => $provinsis,
            'allKotas'      => $allKotas,
            'allKecamatans' => $allKecamatans,
        ]);
    }

    public function store_alamat(Request $request)
    {
        $user = auth()->user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        $count = AlamatPelamar::where('pelamar_id', $pelamar?->id)->count();
        if ($count >= 3) {
            return redirect()->route('alamat')->with('error', 'Batas maksimal alamat (3 alamat) telah tercapai.');
        }

        $validated = $request->validate([
            'label'     => 'nullable|string',
            'provinsi'  => 'nullable|string',
            'kota'      => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'desa'      => 'nullable|string',
            'detail'    => 'nullable|string',
            'kode_pos'  => 'nullable|string',
        ]);

        $validated['pelamar_id'] = $pelamar?->id;
        $alamat = AlamatPelamar::create($validated);

        if ($pelamar) {
            $pelamar->update([
                'provinsi' => $validated['provinsi'] ?? $pelamar->provinsi,
                'kota'     => $validated['kota'] ?? $pelamar->kota,
                'alamat'   => $validated['desa'] ?? $pelamar->alamat,
            ]);
        }

        return redirect()->route('alamat')->with('success', 'Alamat berhasil disimpan');
    }

    public function edit_alamat($alamatpelamar)
    {
        session(['profile_popup_closed' => true]);
        session()->forget('show_first_login_popup');

        $user = auth()->user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (is_numeric($alamatpelamar) || is_string($alamatpelamar)) {
            $data = AlamatPelamar::find($alamatpelamar);
            if (!$data) {
                $data = (object)[
                    'id'        => 1,
                    'label'     => 'Alamat Utama',
                    'provinsi'  => $pelamar->provinsi ?? '',
                    'kota'      => $pelamar->kota ?? '',
                    'kecamatan' => '',
                    'desa'      => $pelamar->alamat ?? '',
                    'kode_pos'  => '60111',
                    'detail'    => $pelamar->alamat ?? '',
                ];
            }
        } else {
            $data = $alamatpelamar;
        }

        $provinsis = collect();
        if (file_exists(database_path('data/provinces.json'))) {
            $json = json_decode(file_get_contents(database_path('data/provinces.json')), true);
            $provinsis = collect($json)->map(function ($item) {
                return (object)[
                    'id'   => (string)$item['id'],
                    'nama' => ucwords(strtolower($item['name'])),
                ];
            })->sortBy('nama')->values();
        }

        $kotas = collect();
        $kecamatans = collect();

        $selectedProvName = trim((string)($data->provinsi ?? ''));
        $selectedKotaName = trim((string)($data->kota ?? ''));

        if ($selectedProvName && file_exists(database_path('data/provinces.json'))) {
            $content = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/provinces.json')));
            $provinces = json_decode(trim($content), true) ?? [];
            $foundProv = collect($provinces)->first(function ($p) use ($selectedProvName) {
                return strcasecmp($p['name'], $selectedProvName) === 0 
                    || (string)$p['id'] === (string)$selectedProvName 
                    || str_contains(strtolower($p['name']), strtolower($selectedProvName));
            });

            if ($foundProv && file_exists(database_path('data/regencies.json'))) {
                $cReg = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/regencies.json')));
                $regencies = json_decode(trim($cReg), true) ?? [];
                $kotas = collect($regencies)
                    ->filter(function($item) use ($foundProv) {
                        return (string)$item['province_id'] === (string)$foundProv['id']
                            || (int)$item['province_id'] === (int)$foundProv['id'];
                    })
                    ->values()
                    ->map(function ($item) {
                        return (object)[
                            'id'   => (string)$item['id'],
                            'nama' => ucwords(strtolower($item['name'])),
                        ];
                    })->sortBy('nama')->values();
            }
        }

        if ($selectedKotaName && file_exists(database_path('data/regencies.json'))) {
            $cReg = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/regencies.json')));
            $regencies = json_decode(trim($cReg), true) ?? [];
            $foundKota = collect($regencies)->first(function ($r) use ($selectedKotaName) {
                return strcasecmp($r['name'], $selectedKotaName) === 0 
                    || (string)$r['id'] === (string)$selectedKotaName 
                    || str_contains(strtolower($r['name']), strtolower($selectedKotaName));
            });

            if ($foundKota && file_exists(database_path('data/districts.json'))) {
                $cDis = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/districts.json')));
                $cDis = trim($cDis);
                $districts = json_decode($cDis, true);
                if ($districts === null) {
                    $cDis = iconv('UTF-8', 'UTF-8//IGNORE', $cDis);
                    $districts = json_decode($cDis, true) ?? [];
                }
                $kecamatans = collect($districts)
                    ->filter(function($item) use ($foundKota) {
                        return (string)$item['regency_id'] === (string)$foundKota['id']
                            || (int)$item['regency_id'] === (int)$foundKota['id'];
                    })
                    ->values()
                    ->map(function ($item) {
                        return (object)[
                            'id'   => (string)$item['id'],
                            'nama' => ucwords(strtolower($item['name'])),
                        ];
                    })->sortBy('nama')->values();
            }
        }

        $allKotas = collect();
        if (file_exists(database_path('data/regencies.json'))) {
            $content = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/regencies.json')));
            $json = json_decode(trim($content), true) ?? [];
            $allKotas = collect($json)->map(function ($item) {
                return [
                    'id'          => (string)$item['id'],
                    'provinsi_id' => (string)$item['province_id'],
                    'nama'        => ucwords(strtolower($item['name'])),
                ];
            })->sortBy('nama')->values();
        }

        $allKecamatans = collect();
        if (file_exists(database_path('data/districts.json'))) {
            $content = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents(database_path('data/districts.json')));
            $content = trim($content);
            if (function_exists('mb_convert_encoding')) {
                $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
            }
            $json = json_decode($content, true) ?? [];
            $allKecamatans = collect($json)->map(function ($item) {
                return [
                    'id'      => (string)$item['id'],
                    'kota_id' => (string)$item['regency_id'],
                    'nama'    => ucwords(strtolower($item['name'])),
                ];
            })->sortBy('nama')->values();
        }

        return view('non-user.alamat.edit', [
            'data'          => $data,
            'pelamar'       => $pelamar,
            'provinsis'     => $provinsis,
            'kotas'         => $kotas,
            'kecamatans'    => $kecamatans,
            'allKotas'      => $allKotas,
            'allKecamatans' => $allKecamatans,
        ]);
    }

    public function update_alamat(Request $request, $alamatpelamar)
    {
        $user = auth()->user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'label'     => 'nullable|string',
            'provinsi'  => 'nullable|string',
            'kota'      => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'desa'      => 'nullable|string',
            'detail'    => 'nullable|string',
            'kode_pos'  => 'nullable|string',
        ]);

        if (is_numeric($alamatpelamar) || is_string($alamatpelamar)) {
            $record = AlamatPelamar::find($alamatpelamar);
            if ($record) {
                $record->update($validated);
            } else {
                $validated['pelamar_id'] = $pelamar?->id;
                AlamatPelamar::create($validated);
            }
        } elseif ($alamatpelamar instanceof AlamatPelamar) {
            $alamatpelamar->update($validated);
        }

        if ($pelamar) {
            $pelamar->update([
                'provinsi' => $validated['provinsi'] ?? $pelamar->provinsi,
                'kota'     => $validated['kota'] ?? $pelamar->kota,
                'alamat'   => $validated['desa'] ?? $pelamar->alamat,
            ]);
        }

        return redirect()->route('alamat')->with('success', 'Alamat berhasil diupdate');
    }

    public function destroy_alamat($alamatpelamar)
    {
        if (is_numeric($alamatpelamar) || is_string($alamatpelamar)) {
            $record = AlamatPelamar::find($alamatpelamar);
            if ($record) {
                $record->delete();
            }
        } elseif ($alamatpelamar instanceof AlamatPelamar) {
            $alamatpelamar->delete();
        }

        return redirect()->route('alamat')->with('success', 'Alamat berhasil dihapus');
    }

    //SUPER ADMIN
    public function store_alamatSuper(Request $request)
    {
        $validated = $request->validate([
            'label'     => 'nullable',
            'desa'      => 'nullable',
            'kecamatan' => 'nullable',
            'kota'      => 'nullable',
            'provinsi'  => 'nullable',
            'kode_pos'  => 'nullable',
            'detail'    => 'nullable'
        ]);

        $pelamar_id = session('pelamar_terakhir_id');

        if (!$pelamar_id) {
            return back()->with('error', 'Pelamar belum dibuat. Harap buat pelamar terlebih dahulu.');
        }

        $pelamar = Pelamar::find($pelamar_id);
        if ($pelamar) {
            $pelamar->update([
                'provinsi' => $validated['provinsi'] ?? $pelamar->provinsi,
                'kota'     => $validated['kota'] ?? $pelamar->kota,
                'alamat'   => $validated['desa'] ?? $pelamar->alamat,
            ]);

            if ($pelamar->user) {
                $pelamar->user->update([
                    'provinsi_id'   => $validated['provinsi'] ?? $pelamar->user->provinsi_id,
                    'kota_id'       => $validated['kota'] ?? $pelamar->user->kota_id,
                    'kecamatan_id'  => $validated['kecamatan'] ?? $pelamar->user->kecamatan_id,
                    'desa'          => $validated['desa'] ?? $pelamar->user->desa,
                    'kode_pos'      => $validated['kode_pos'] ?? $pelamar->user->kode_pos,
                    'detail_alamat' => $validated['detail'] ?? $pelamar->user->detail_alamat,
                ]);
            }
        }

        $mapKategori = [
            'pelamar'           => 'non_kandidat',
            'calon kandidat'    => 'calon_kandidat',
            'kandidat aktif'    => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori ?? '')] ?? 'non_kandidat';

        return redirect()->route('superadmin.pelamar.create', ['kategori' => $kategori])
            ->with('success', 'Alamat berhasil disimpan');
    }

    public function edit_alamatSuper($id)
    {
        $pelamar = Pelamar::find($id);
        $data = (object)[
            'id'       => $id,
            'provinsi' => $pelamar->provinsi ?? '',
            'kota'     => $pelamar->kota ?? '',
            'desa'     => $pelamar->alamat ?? '',
        ];
        return view('super_admin.pelamar.modal.edit.edit_alamat', ["data" => $data]);
    }

    public function update_alamatSuper(Request $request, $id = null)
    {
        $validated = $request->validate([
            'pelamar_id' => 'required|exists:pelamars,id',
            'label'      => 'nullable',
            'desa'       => 'nullable',
            'kecamatan'  => 'nullable',
            'kota'       => 'nullable',
            'provinsi'   => 'nullable',
            'kode_pos'   => 'nullable',
            'detail'     => 'nullable'
        ]);

        $pelamar_id = $validated['pelamar_id'];
        $pelamar = Pelamar::find($pelamar_id);
        if ($pelamar) {
            $pelamar->update([
                'provinsi' => $validated['provinsi'] ?? $pelamar->provinsi,
                'kota'     => $validated['kota'] ?? $pelamar->kota,
                'alamat'   => $validated['desa'] ?? $pelamar->alamat,
            ]);

            if ($pelamar->user) {
                $pelamar->user->update([
                    'provinsi_id'   => $validated['provinsi'] ?? $pelamar->user->provinsi_id,
                    'kota_id'       => $validated['kota'] ?? $pelamar->user->kota_id,
                    'kecamatan_id'  => $validated['kecamatan'] ?? $pelamar->user->kecamatan_id,
                    'desa'          => $validated['desa'] ?? $pelamar->user->desa,
                    'kode_pos'      => $validated['kode_pos'] ?? $pelamar->user->kode_pos,
                    'detail_alamat' => $validated['detail'] ?? $pelamar->user->detail_alamat,
                ]);
            }
        }

        $mapKategori = [
            'pelamar'           => 'non_kandidat',
            'calon kandidat'    => 'calon_kandidat',
            'kandidat aktif'    => 'kandidat',
            'kandidat nonaktif' => 'kandidat_nonaktif',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori ?? '')] ?? 'non_kandidat';

        return redirect()->route('superadmin.pelamar.edit', [
            'kategori' => $kategori,
            'id'       => $pelamar_id
        ])->with('success', 'Data alamat berhasil disimpan.');
    }

    public function destroy_alamatSuper($id = null)
    {
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
