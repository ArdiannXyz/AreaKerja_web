<?php

namespace App\Http\Controllers;

use App\Helpers\BrowserPath;
use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use App\Models\TalentHunter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Browsershot\Browsershot;

class SuperAdminController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ===========================
        // RANGE WAKTU
        // ===========================

        // Awal bulan ini
        $startThisMonth = $now->copy()->startOfMonth();

        // Awal 3 bulan sebelumnya
        $startThreeMonthsAgo = $now->copy()->subMonths(3)->startOfMonth();

        // Akhir bulan lalu
        $endLastMonth = $startThisMonth->copy()->subSecond();


        // ===========================
        // PELAMAR
        // ===========================
        $currentPelamar = Pelamar::whereBetween('created_at', [
            $startThisMonth,
            $now
        ])->count();

        $lastPelamar = Pelamar::whereBetween('created_at', [
            $startThreeMonthsAgo,
            $endLastMonth
        ])->count();

        $growthPelamar = $this->calcGrowth($lastPelamar, $currentPelamar);


        // ===========================
        // PERUSAHAAN
        // ===========================
        $currentPerusahaan = Perusahaan::whereBetween('created_at', [
            $startThisMonth,
            $now
        ])->count();

        $lastPerusahaan = Perusahaan::whereBetween('created_at', [
            $startThreeMonthsAgo,
            $endLastMonth
        ])->count();

        $growthPerusahaan = $this->calcGrowth($lastPerusahaan, $currentPerusahaan);


        // ===========================
        // ADMIN
        // ===========================
        $currentAdmin = User::where('role', 'admin')
            ->whereBetween('created_at', [$startThisMonth, $now])
            ->count();

        $lastAdmin = User::where('role', 'admin')
            ->whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])
            ->count();

        $growthAdmin = $this->calcGrowth($lastAdmin, $currentAdmin);


        // ===========================
        // SUPER ADMIN
        // ===========================
        $currentSuperAdmin = User::where('role', 'super_admin')
            ->whereBetween('created_at', [$startThisMonth, $now])
            ->count();

        $lastSuperAdmin = User::where('role', 'super_admin')
            ->whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])
            ->count();

        $growthSuperAdmin = $this->calcGrowth($lastSuperAdmin, $currentSuperAdmin);


        return view('super_admin.dashboard', [
            "title" => "Dashboard",

            'totalPelamar'       => $currentPelamar,
            'growthPelamar'      => $growthPelamar,

            'totalPerusahaan'    => $currentPerusahaan,
            'growthPerusahaan'   => $growthPerusahaan,

            'totalAdmin'         => $currentAdmin,
            'growthAdmin'        => $growthAdmin,

            'totalSuperAdmin'    => $currentSuperAdmin,
            'growthSuperAdmin'   => $growthSuperAdmin,
        ]);
    }

    /**
     * Hitung pertumbuhan dalam persen (%)
     */
    private function calcGrowth($last, $current)
    {
        if ($last == 0 && $current > 0) return 100;
        if ($last == 0 && $current == 0) return 0;

        return round((($current - $last) / $last) * 100, 1);
    }


    //pengaturan
    public function pengaturan()
    {
        return view('super_admin.pengaturan');
    }



    //PROFILE
    public function profile_superadmin()
    {
        return view('super_admin.profile.profile-superadmin');
    }
    public function edit_profile(SuperAdmin $superadmin)
    {
        return view(
            'super_admin.profile.edit-profile-superadmin',
            [
                "data" => $superadmin
            ]
        );
    }
    public function update_profile_superadmin(Request $request, SuperAdmin $superadmin)
    {

        $validated = $request->validate([
            'username'     => "nullable|string",
            'email'    => "nullable|email",

        ]);

        $valid = $request->validate([
            "nama_lengkap"  => 'nullable|string',
            "img_profile"   => 'nullable|file|image|mimes:png,jpg,jpeg',
            "provinsi"      => 'nullable|string',
            "kota"          => 'nullable|string',
            "kecamatan"     => 'nullable|string',
            "desa"          => 'nullable|string',
            "kode_pos"      => 'nullable',
            "detail_alamat" => 'nullable|string'
        ]);

        $user = User::where('id', Auth::user()->id);
        if ($user) {
            $user->update($validated);
        }

        $superadmin = SuperAdmin::where('id', Auth::user()->id)->first();

        if ($request->hasFile('img_profile')) {
            // Hapus foto lama jika ada
            if ($superadmin->img_profile && Storage::exists('public/' . $superadmin->img_profile)) {
                Storage::delete('public/' . $superadmin->img_profile);
            }

            // Simpan foto baru ke storage/app/public/images
            $valid['img_profile'] = $request->file('img_profile')->store('images', 'public');
        }

        if ($superadmin) {
            $superadmin->update($valid);
        }

        return redirect()->route('superadmin.profile')->with('success', 'Profile updated successfully.');
    }


    public function destroy_profile(SuperAdmin $superadmin)
    {
        if ($superadmin->img_profile && Storage::exists('public/' . $superadmin->img_profile)) {
            Storage::delete('public/' . $superadmin->img_profile);
        }

        $superadmin->img_profile = null;
        $superadmin->save();
        return redirect()->route('superadmin.edit.profile')->with('success', 'Profile berhasil dihapus');
    }


    //AKUN FREEZE
    public function freezeForm(Request $request)
    {
        $search = $request->input('search');

        $data = User::when($search, function ($query, $search) {
            $query->where('username', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        })->get();

        return view('super_admin.freeze.freeze', [
            'data' => $data,
            'search' => $search
        ]);
    }


    public function ban(Request $request, User $user)
    {
        // dd($request->all());
        $data = $request->validate([
            'status' => 'required|boolean'
        ]);
        $user->update($data);
        return redirect()->route('superadmin.freeze')->with('success', 'Akun berhasil di freeze');
    }

    public function unban(Request $request, User $user)
    {
        $data = $request->validate([
            'status' => 'required|boolean'
        ]);
        $user->update($data);
        return redirect()->route('superadmin.freeze')->with('success', 'Akun berhasil di unfreeze');
    }

    public function delete_akun(User $user)
    {
        $user->delete($user->id);
        return redirect()->route('superadmin.freeze')->with('success', 'Akun berhasil dihapus');
    }

    public function detail_freeze(User $user)
    {
        return view('super_admin.freeze.detail-freeze', [
            "data" => $user
        ]);
    }




    public function pelamarhal(Request $request)
    {
        $search = $request->input('search');

        $kandidatQuery = Pelamar::where('kategori', 'kandidat aktif');
        $nonKandidatQuery = Pelamar::where('kategori', 'pelamar');
        $calonKandidatQuery = Pelamar::where('kategori', 'calon kandidat');

        if ($search) {
            $kandidatQuery->where(function ($q) use ($search) {
                $q->where('nama_pelamar', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', "%{$search}%");
                    });
            });

            $nonKandidatQuery->where(function ($q) use ($search) {
                $q->where('nama_pelamar', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', "%{$search}%");
                    });
            });

            $calonKandidatQuery->where(function ($q) use ($search) {
                $q->where('nama_pelamar', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', "%{$search}%");
                    });
            });
        }

        $kandidat = $kandidatQuery->get();
        $nonKandidat = $nonKandidatQuery->get();
        $calonKandidat = $calonKandidatQuery->get();

        session()->forget(['pelamar_terakhir_id', 'kategori_terakhir']);

        return view('super_admin.pelamar.data-pelamar', [
            'kandidat' => $kandidat,
            'nonKandidat' => $nonKandidat,
            'calonKandidat' => $calonKandidat,
        ]);
    }


    //CRUD KANDIDAT CALON KANDIDAT NON KANDIDAT
    public function createKategori($kategori)
    {

        // Validasi slug kategori yang diizinkan
        $allowedKategori = ['non_kandidat', 'calon_kandidat', 'kandidat'];

        if (!in_array($kategori, $allowedKategori)) {
            abort(404);
        }

        // Ambil data divisi hanya jika kategori = calon_kandidat atau kandidat
        $divisis = collect();
        if (in_array($kategori, ['calon_kandidat', 'kandidat'])) {
            $divisis = Divisi::all();
        }

        // dd($kategori, $divisis);


        $pelamar = null;

        if (session('pelamar_terakhir_id')) {
            $pelamarSession = Pelamar::find(session('pelamar_terakhir_id'));

            // Pastikan hanya pakai data dari session kalau kategorinya sama
            if ($pelamarSession) {
                $mapKategori = [
                    'pelamar' => 'non_kandidat',
                    'calon kandidat' => 'calon_kandidat',
                    'kandidat aktif' => 'kandidat',
                ];

                $kategoriPelamar = $mapKategori[strtolower($pelamarSession->kategori)] ?? null;

                if ($kategoriPelamar === $kategori) {
                    $pelamar = $pelamarSession;
                }
            }
        }

        // Kirim data ke view
        return view('super_admin.pelamar.tambah-kandidat-superadmin', [
            'kategori' => $kategori, // tetap kirim slug (agar cocok dengan Blade)
            'divisis'  => $divisis,
            'pelamar'  => $pelamar
        ]);
    }



    public function storeUser(Request $request)
    {
        //  CARI USER YANG SUDAH ADA
        $existingUser = null;

        if (session('pelamar_terakhir_id')) {
            $existingPelamar = Pelamar::find(session('pelamar_terakhir_id'));
            $existingUser = $existingPelamar ? $existingPelamar->user : null;
        }

        if (!$existingUser && $request->email) {
            $existingUser = User::where('email', $request->email)->first();
        }

        $userId = $existingUser?->id;

        //  VALIDASI UNIQUE
        $request->validate([
            'email' => $userId
                ? 'nullable|email|unique:users,email,' . $userId
                : 'nullable|email|unique:users,email',

            'username' => $userId
                ? 'nullable|string|unique:users,username,' . $userId
                : 'nullable|string|unique:users,username',

            'telepon_pelamar' => [
                'required',
                'regex:/^(?:628|08)[0-9]+$/'
            ],

        ]);

        // ========== USER ==========

        if (!$existingUser) {
            // User baru, password WAJIB ada
            if (
                empty($request->username) &&
                empty($request->email) &&
                empty($request->password)
            ) {
                return back()->with('error', 'Minimal isi email, username, atau password.');
            }

            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => $request->password ? bcrypt($request->password) : null,
                'role'     => 'pelamar',
                'status'   => 0,
            ]);
        } else {
            // User lama → jangan ubah password kalau kosong
            $existingUser->update([
                'username' => $request->username ?? $existingUser->username,
                'email'    => $request->email ?? $existingUser->email,
            ]);

            if ($request->password) {
                $existingUser->update([
                    'password' => bcrypt($request->password)
                ]);
            }

            $user = $existingUser;
        }

        //  SIMPAN FOTO
        $path = null;
        if ($request->hasFile('img_profile')) {
            $path = $request->file('img_profile')->store('images', 'public');
        }

        // ========== PELAMAR ==========

        $pelamar = Pelamar::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_pelamar'    => $request->nama_pelamar,
                'deskripsi_diri'  => $request->deskripsi_diri,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'gender'          => $request->gender,
                'telepon_pelamar' => $request->telepon_pelamar,
                'divisi'          => json_encode($request->divisi),
                'kategori'        => $request->kategori,
                'img_profile'     => $path,
            ]
        );

        // BERSIHKAN NOMOR TELEPON MENGGUNAKAN REGEX
        $telepon = $pelamar->telepon_pelamar;

        if ($request->filled('telepon_pelamar')) {
            $telp = preg_replace('/[^0-9]/', '', $request->telepon_pelamar); // hapus karakter selain angka 
            $telp = preg_replace('/^62/', '0', $telp);  // 62 → 0
            $telepon = $telp;
        }

        // UPDATE DATA LANJUTAN
        $pelamar->update([
            'nama_pelamar'    => $request->nama_pelamar ?? $pelamar->nama_pelamar,
            'tanggal_lahir'   => $request->tanggal_lahir ?? $pelamar->tanggal_lahir,
            'gender'          => $request->gender ?? $pelamar->gender,
            'telepon_pelamar' => $telepon,
            'divisi'          => $request->divisi ? json_encode($request->divisi) : $pelamar->divisi,
            'kategori'        => $request->kategori ?? $pelamar->kategori,
            'img_profile'     => $path ?? $pelamar->img_profile,
        ]);


        // SIMPAN SESSION
        session([
            'pelamar_terakhir_id' => $pelamar->id,
            'kategori_terakhir' => $pelamar->kategori
        ]);

        // ================= RELASI =================
        if ($request->alamat) {
            foreach ($request->alamat as $data) {
                $pelamar->alamat_pelamar()->create($data);
            }
        }

        if ($request->pendidikan) {
            foreach ($request->pendidikan as $data) {
                $pelamar->riwayat_pendidikan()->create($data);
            }
        }

        if ($request->organisasi) {
            foreach ($request->organisasi as $data) {
                $pelamar->pengalaman_organisasi()->create($data);
            }
        }

        if ($request->pengalaman_kerja) {
            foreach ($request->pengalaman_kerja as $data) {
                $pelamar->pengalaman_kerja()->create($data);
            }
        }

        if ($request->skill) {
            foreach ($request->skill as $data) {
                $pelamar->skill()->create($data);
            }
        }

        if ($request->social_media) {
            $pelamar->sosmed()->updateOrCreate([], $request->social_media);
        }

        // ================= REDIRECT =================
        $isComplete =
            $pelamar->alamat_pelamar()->exists() &&
            $pelamar->riwayat_pendidikan()->exists() &&
            $pelamar->pengalaman_organisasi()->exists() &&
            $pelamar->pengalaman_kerja()->exists() &&
            $pelamar->skill()->exists();

        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';

        if ($isComplete) {
            return redirect()->route('superadmin.pelamar')
                ->with('success', 'Data pelamar berhasil disimpan dan semua data sudah lengkap.');
        }

        return redirect()->route('superadmin.pelamar.create', ['kategori' => $kategori])
            ->with('success', 'Data pendidikan berhasil disimpan.');
    }


    public function editUser($kategori, $id)
    {

        $pelamar = Pelamar::with([
            'user',
            'alamat_pelamar',
            'riwayat_pendidikan',
            'pengalaman_organisasi',
            'pengalaman_kerja',
            'skill',
            'sosmed',
        ])->find($id);

        if (!$pelamar) {
            abort(404, "Pelamar ID kandidat tidak ditemukan");
        }

        $divisis = Divisi::all();

        $mapKategori = [
            'pelamar' => 'non_kandidat',
            'calon kandidat' => 'calon_kandidat',
            'kandidat aktif' => 'kandidat',
        ];

        $kategori = $mapKategori[strtolower($pelamar->kategori)] ?? 'non_kandidat';

        return view('super_admin.pelamar.edit-kandidat-superadmin', compact('pelamar', 'divisis', 'kategori'));
    }


    public function updateUser(Request $request, $id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $user = $pelamar->user ?? null;

        $request->validate([
            'nama_pelamar' => 'required|string|max:255',
            'email'        => 'required|email',
            'username'     => 'required|string|max:255',
            'password'     => 'nullable|min:3',
            'img_profile'  => 'nullable|image',
            'telepon_pelamar' => [
                'required',
                'regex:/^(?:628|08)[0-9]+$/'
            ],
        ]);

        // ================== FOTO PROFIL ==================
        $path = $pelamar->img_profile;
        if ($request->hasFile('img_profile')) {
            if ($pelamar->img_profile && Storage::exists('public/' . $pelamar->img_profile)) {
                Storage::delete('public/' . $pelamar->img_profile);
            }
            $path = $request->file('img_profile')->store('images', 'public');
        }

        // ================== TELEPON (Regex Normalisasi) ==================
        $telepon = $pelamar->telepon_pelamar;
        if ($request->filled('telepon_pelamar')) {

            // Hapus semua karakter selain angka dan "+"
            $telepon = preg_replace('/[^0-9]/', '', $request->telepon_pelamar);

            // Ubah format internasional ke format lokal // +62xxxxx -> 0xxxx
            $telepon = preg_replace('/^62/', '0', $telepon);   // 62xxxxx  -> 0xxxx
        }

        // ================== UPDATE DATA UTAMA ==================
        $pelamar->update([
            'nama_pelamar'    => $request->nama_pelamar,
            'deskripsi_diri'  => $request->deskripsi_diri,
            'tanggal_lahir'   => $request->tanggal_lahir,
            'gender'          => $request->gender,
            'telepon_pelamar' => $telepon,
            'divisi'          => $request->divisi,
            'kategori'        => $request->kategori,
            'gaji_minimal'    => $request->gaji_minimal,
            'gaji_maksimal'   => $request->gaji_maksimal,
            'img_profile'     => $path,
        ]);

        // ================== UPDATE USER ==================
        if ($user) {
            $user->update([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => $request->filled('password')
                    ? bcrypt($request->password)
                    : $user->password,
            ]);
        }

        // ================== RELASI ==================
        if ($request->filled('alamat')) {
            $pelamar->alamat_pelamar()->delete();
            foreach ($request->alamat as $data) {
                $pelamar->alamat_pelamar()->create($data);
            }
        }

        if ($request->filled('pendidikan')) {
            $pelamar->riwayat_pendidikan()->delete();
            foreach ($request->pendidikan as $data) {
                $pelamar->riwayat_pendidikan()->create($data);
            }
        }

        if ($request->filled('organisasi')) {
            $pelamar->pengalaman_organisasi()->delete();
            foreach ($request->organisasi as $data) {
                $pelamar->pengalaman_organisasi()->create($data);
            }
        }

        if ($request->filled('pengalaman_kerja')) {
            $pelamar->pengalaman_kerja()->delete();
            foreach ($request->pengalaman_kerja as $data) {
                $pelamar->pengalaman_kerja()->create($data);
            }
        }

        if ($request->filled('skill')) {
            $pelamar->skill()->delete();
            foreach ($request->skill as $data) {
                $pelamar->skill()->create($data);
            }
        }

        if ($request->has('social_media') && is_array($request->social_media)) {
            $pelamar->sosmed()->updateOrCreate([], $request->social_media);
        }

        // ================== REDIRECT ==================
        return redirect()->route('superadmin.pelamar')
            ->with('success', 'Data pelamar berhasil diperbarui.');
    }


    public function destroyUser($id)
    {
        try {

            $pelamar = Pelamar::findOrFail($id);


            $pelamar->alamat_pelamar()->delete();
            $pelamar->riwayat_pendidikan()->delete();
            $pelamar->pengalaman_organisasi()->delete();
            $pelamar->pengalaman_kerja()->delete();
            $pelamar->skill()->delete();
            $pelamar->sosmed()->delete();


            if ($pelamar->img_profile && Storage::exists('public/' . $pelamar->img_profile)) {
                Storage::delete('public/' . $pelamar->img_profile);
            }

            $user = $pelamar->user;
            $pelamar->delete();

            if ($user && !Pelamar::where('user_id', $user->id)->exists()) {
                $user->delete();
            }

            return redirect()->route('superadmin.pelamar')->with('success', 'Data pelamar berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }





    //KANDIDAT
    public function detail_kandidat(Pelamar $pelamar)
    {
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        return view('super_admin.pelamar.kandidat.detail', [
            "data" => $pelamar,
            "logoBase64" => $logoBase64,
            "sosmed" => $pelamar->sosmed,
        ]);
    }

    //NON KANDIDAT
    public function detail_non_kandidat(Pelamar $pelamar)
    {
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        return view('super_admin.pelamar.non-kandidat.detail', [
            "data" => $pelamar,
            "logoBase64" => $logoBase64,
            "sosmed" => $pelamar->sosmed,
        ]);
    }

    public function edit_non_kandidat(Pelamar $pelamar)
    {
        return view('super_admin.pelamar.non-kandidat.edit', [
            "data" => $pelamar
        ]);
    }

    //CALON KANDIDAT
    public function detailCalonKandidat($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        return view('super_admin.pelamar.calon-kandidat.detail', [
            'pelamar' => $pelamar
        ]);
    }

    public function updateTraining(Request $request, $id)
    {
        $request->validate([
            'mulai_pelatihan' => 'required|date',
            'selesai_pelatihan' => 'required|date|after:mulai_pelatihan',
        ]);

        $pelamar = Pelamar::findOrFail($id);
        $pelamar->mulai_pelatihan = $request->mulai_pelatihan;
        $pelamar->selesai_pelatihan = $request->selesai_pelatihan;
        $pelamar->save();

        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'perusahaan_id' => null,
            'judul' => 'Jadwal Pelatihan Diperbarui',
            'pesan' => 'Silahkan Untuk Mengikuti Pelatihan Pada Tanggal <b>' . $request->mulai_pelatihan . '</b> sampai <b>' . $request->selesai_pelatihan . '</b> untuk <b>' . $pelamar->nama_pelamar . '</b>.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
            'pelamar_lowongan_id' => null,
        ]);

        return back()->with('success', '');
    }

    public function lulus($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $pelamar->kategori = 'kandidat aktif';
        $pelamar->save();

        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'perusahaan_id' => null,
            'judul' => 'Selamat! Kamu Lulus Seleksi',
            'pesan' => 'Selamat! <b>' . $pelamar->nama_pelamar . '</b> telah lulus pelatihan dan menjadi kandidat.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
            'pelamar_lowongan_id' => null,
        ]);

        return redirect()->route('superadmin.pelamar')->with('success', 'Kandidat berhasil diluluskan.');
    }

    public function gugur($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $pelamar->kategori = 'pelamar';
        $pelamar->save();

        Notifikasi::create([
            'user_id' => $pelamar->user_id,
            'perusahaan_id' => null,
            'judul' => 'Status Kandidat Diperbarui',
            'pesan' => '<b>' . $pelamar->nama_pelamar . '</b> dinyatakan <span style="color:red;">gugur</span> dari proses seleksi.',
            'is_read' => 0,
            'expired_at' => now()->addDays(7),
            'pelamar_lowongan_id' => null,
        ]);

        return redirect()->route('superadmin.pelamar')->with('success', 'Kandidat dinyatakan gugur.');
    }




    //CV
    public function cv(Pelamar $pelamar)
    {
        return view('super_admin.pelamar.cv', [
            "data" => $pelamar
        ]);
    }




    //  CRUD ROLE
    public function role()
    {
        $usersAdminFinance = User::whereIn('role', ['admin', 'finance'])
            ->with(['admin', 'finance'])
            ->get();

        $usersPerusahaanPelamar = User::whereIn('role', ['perusahaan', 'pelamar'])
            ->with(['perusahaan', 'pelamar'])
            ->get();

        return view('super_admin.add.add-user', [
            'usersAdminFinance' => $usersAdminFinance,
            'usersPerusahaanPelamar' => $usersPerusahaanPelamar
        ]);
    }

    public function createForm(SuperAdmin $superadmin)
    {
        $provinsis = Provinsi::all();
        // $superadmin->load(['kota', 'kecamatan', 'provinsi']);
        return view('super_admin.add.create', [
            'provinsis' => $provinsis
        ]);
    }

    public function getKota($provinsi_id)
    {
        return response()->json(Kota::where('provinsi_id', $provinsi_id)->get());
    }

    public function getKecamatan($kota_id)
    {
        return response()->json(Kecamatan::where('kota_id', $kota_id)->get());
    }

    public function store(Request $request)
    {
        try {
            // RULES VALIDASI
            $rules = [
                'email'        => 'required|email|unique:users',
                'username'     => 'required|unique:users',
                'role'         => 'required|in:admin,finance,perusahaan,pelamar',
                'password'     => 'required|min:3',
                'img_profile'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];

            // Tambahan rules untuk admin / finance
            if (in_array($request->role, ['admin', 'finance'])) {
                $rules['nama_lengkap'] = 'required';
                $rules['provinsi_id']  = 'nullable|exists:provinsis,id';
                $rules['kota_id']      = 'nullable|exists:kotas,id';
                $rules['kecamatan_id'] = 'nullable|exists:kecamatans,id';
                $rules['desa']         = 'nullable|string';
                $rules['kode_pos']     = 'nullable|string';
                $rules['detail_alamat'] = 'nullable|string';
            }

            // Tambahan rules untuk perusahaan
            if ($request->role === 'perusahaan') {
                $rules['telepon_perusahaan'] = [
                    "nullable",
                    "regex:/^(?:628|08)[0-9]{8,15}$/"
                ];
                $rules['whatsapp'] = [
                    "nullable",
                    "regex:/^(?:628|08)[0-9]{8,15}$/"
                ];
                // $rules['koin_perusahaan'] = [
                //     "nullable",
                //     "integer",
                //     "min:0"
                // ];
            }

            // Tambahan rules untuk pelamar (DITAMBAHKAN)
            if ($request->role === 'pelamar') {
                $rules['telepon_pelamar'] = [
                    "nullable",
                    "regex:/^(?:628|08)[0-9]{8,15}$/"
                ];
            }

            // Pesan error
            $messages = [
                'email.required' => 'Email wajib diisi.',
                'email.email'    => 'Format email tidak valid.',
                'email.unique'   => 'Email sudah terdaftar.',

                'username.required' => 'Username wajib diisi.',
                'username.unique'   => 'Username sudah digunakan.',

                'role.required' => 'Role wajib diisi.',
                'role.in'       => 'Role tidak valid.',

                'password.required' => 'Password wajib diisi.',
                'password.min'      => 'Password minimal 3 karakter.',

                'img_profile.image' => 'File harus berupa gambar.',
                'img_profile.mimes' => 'Gambar harus bertipe jpg, jpeg, atau png.',
                'img_profile.max' => 'Ukuran gambar maksimal 2MB.',

                // nomor
                'telepon_perusahaan.regex' => "Nomor telepon perusahaan tidak valid.",
                'whatsapp.regex'           => "Nomor WhatsApp tidak valid.",
                'telepon_pelamar.regex'    => "Nomor telepon pelamar tidak valid.",
            ];

            $request->validate($rules, $messages);

            // CREATE USER
            $user = User::create([
                'email'    => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            // HANDLE GAMBAR
            $imgPath = null;
            if ($request->hasFile('img_profile')) {
                $imgPath = $request->file('img_profile')->store('images', 'public');
            }

            // UTIL UNTUK NORMALISASI NOMOR TELEPON  "0xxxxxxxx"
            $normalizePhone = function ($number) {
                if (!$number) return null;
                $num = preg_replace('/[^0-9]/', '', $number);
                $num = preg_replace('/^62/', '0', $num);
                return $num;
            };

            // SIMPAN SESUAI ROLE
            switch ($request->role) {

                case 'admin':
                    Admin::create([
                        'user_id'       => $user->id,
                        'nama_lengkap'  => $request->nama_lengkap,
                        'provinsi_id'   => $request->provinsi_id,
                        'kota_id'       => $request->kota_id,
                        'kecamatan_id'  => $request->kecamatan_id,
                        'kode_pos'      => $request->kode_pos,
                        'detail_alamat' => $request->detail_alamat,
                        'img_profile'   => $imgPath,
                    ]);
                    break;

                case 'finance':
                    Finance::create([
                        'user_id'       => $user->id,
                        'nama_lengkap'  => $request->nama_lengkap,
                        'provinsi_id'   => $request->provinsi_id,
                        'kota_id'       => $request->kota_id,
                        'kecamatan_id'  => $request->kecamatan_id,
                        'kode_pos'      => $request->kode_pos,
                        'detail_alamat' => $request->detail_alamat,
                        'img_profile'   => $imgPath,
                    ]);
                    break;

                case 'perusahaan':
                    Perusahaan::create([
                        'user_id'             => $user->id,
                        'nama_perusahaan'     => $request->nama_perusahaan,
                        'jenis_perusahaan'    => $request->jenis_perusahaan,
                        'website_perusahaan'  => $request->website_perusahaan,
                        'telepon_perusahaan'  => $normalizePhone($request->telepon_perusahaan),
                        'whatsapp'            => $normalizePhone($request->whatsapp),
                        'legalitas'           => $request->legalitas,
                        'koin_perusahaan'     => $request->koin_perusahaan,
                        'deskripsi'           => $request->deskripsi,
                        'visi'                => $request->visi,
                        'misi'                => $request->misi,
                        'img_profile'         => $imgPath,
                    ]);
                    break;

                case 'pelamar':
                    Pelamar::create([
                        'user_id'         => $user->id,
                        'nama_pelamar'    => $request->nama_pelamar,
                        'telepon_pelamar' => $normalizePhone($request->telepon_pelamar),
                        'img_profile'     => $imgPath,
                        'deskripsi_diri'  => $request->deskripsi_diri,
                        'kategori'        => $request->kategori,
                        'gender'          => $request->gender,
                        'gaji_minimal'    => $request->gaji_minimal,
                        'gaji_maksimal'   => $request->gaji_maksimal,
                        'tanggal_lahir'   => $request->tanggal_lahir,
                    ]);
                    break;
            }
            $me = Auth::user();
            // ==============================
            //           NOTIFIKASI BERHASIL
            // ================================
            Notifikasi::create([
                'user_id'              => $me->id, // notif ke superadmin
                // 'perusahaan_id'        => null,
                'judul'                => 'Akun Berhasil Dibuat',
                'pesan'                => 'Akun Anda telah berhasil dibuat oleh Superadmin.',
                'is_read'              => 0,
                'expired_at'           => now()->addDays(7),
                'pelamar_lowongan_id'  => null,
            ]);

            return redirect()->route('superadmin.add.user')
                ->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {

            $me = Auth::user();
            // ==============================
            //          NOTIFIKASI GAGAL
            // ================================
            Notifikasi::create([
                'user_id'              => $me->id, // notif ke superadmin
                // 'perusahaan_id'        => null,
                'judul'                => 'Gagal Menambahkan User',
                'pesan'                => 'Terjadi kesalahan: ' . $e->getMessage(),
                'is_read'              => 0,
                'expired_at'           => now()->addDays(3),
                'pelamar_lowongan_id'  => null,
            ]);

            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }





    public function edit($id)
    {
        $user = User::with([
            'admin.provinsi',
            'admin.kota',
            'admin.kecamatan',
            'finance.provinsi',
            'finance.kota',
            'finance.kecamatan'
        ])->findOrFail($id);
        $provinsis = Provinsi::all();
        return view('super_admin.add.edit-addprofile', [
            "user" => $user,
            'provinsis' => $provinsis
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Sanitasi nomor dulu agar regex tidak gagal
            $request->merge([
                'username' => trim(strtolower($request->username)),

                'email'    => trim(strtolower($request->email)),

                'telepon_perusahaan' => $request->telepon_perusahaan
                    ? preg_replace('/\D+/', '', $request->telepon_perusahaan)
                    : null,

                'whatsapp' => $request->whatsapp
                    ? preg_replace('/\D+/', '', $request->whatsapp)
                    : null,

                'telepon_pelamar' => $request->telepon_pelamar
                    ? preg_replace('/\D+/', '', $request->telepon_pelamar)
                    : null,
            ]);

            // FIX VALIDASI
            $rules = [
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'username' => [
                    'required',
                    Rule::unique('users', 'username')->ignore($user->id),
                ],
                'role'        => 'required|in:admin,finance,perusahaan,pelamar',
                'img_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];


            if (in_array($request->role, ['admin', 'finance'])) {
                $rules['nama_lengkap'] = 'required';
                $rules['provinsi_id']   = 'nullable|exists:provinsis,id';
                $rules['kota_id']       = 'nullable|exists:kotas,id';
                $rules['kecamatan_id']  = 'nullable|exists:kecamatans,id';
                $rules['desa']          = 'nullable|string';
                $rules['kode_pos']      = 'nullable|string';
                $rules['detail_alamat'] = 'nullable|string';
            }

            // Validasi role perusahaan
            if ($request->role === 'perusahaan') {

                $rules['koin_perusahaan'] = [
                    'nullable',
                    'integer',
                    'min:0'
                ];
                $rules['telepon_perusahaan'] = [
                    'nullable',
                    'regex:/^(?:628|08)[0-9]{8,15}$/'
                ];

                $rules['whatsapp'] = [
                    'nullable',
                    'regex:/^(?:628|08)[0-9]{8,15}$/'
                ];
            }

            // Validasi role pelamar (FIX TELEPON)
            if ($request->role === 'pelamar') {
                $rules['telepon_pelamar'] = [
                    'nullable',
                    'regex:/^(?:628|08)[0-9]{8,15}$/'
                ];
            }

            // Pesan error
            $request->validate($rules, [
                'telepon_perusahaan.regex' => 'Nomor telepon perusahaan harus diawali 08 atau 628.',
                'whatsapp.regex' => 'Nomor Whatsapp harus diawali 08 atau 628.',
                'telepon_pelamar.regex' => 'Nomor telepon pelamar harus diawali 08 atau 628.',
                'email.unique' => 'Email sudah terdaftar pada akun lain.',
                'username.unique' => 'Username sudah digunakan oleh akun lain.',
            ]);

            // Simpan role lama dan role baru
            $oldRole = $user->role;
            $newRole = $request->role;

            // Update data utama user
            $dataUser = [
                'email'    => $request->email,
                'username' => $request->username,
                'role'     => $newRole,
            ];

            // password hanya diubah jika diisi
            if ($request->filled('password')) {
                $dataUser['password'] = Hash::make($request->password);
            }

            $user->update($dataUser);


            // Hapus relasi lama jika role berubah
            if ($oldRole !== $newRole) {
                switch ($oldRole) {
                    case 'admin':
                        if ($user->admin && $user->admin->img_profile) {
                            Storage::delete('public/' . $user->admin->img_profile);
                        }
                        $user->admin()?->delete();
                        break;

                    case 'finance':
                        if ($user->finance && $user->finance->img_profile) {
                            Storage::delete('public/' . $user->finance->img_profile);
                        }
                        $user->finance()?->delete();
                        break;

                    case 'perusahaan':
                        if ($user->perusahaan && $user->perusahaan->img_profile) {
                            Storage::delete('public/' . $user->perusahaan->img_profile);
                        }
                        $user->perusahaan()?->delete();
                        break;

                    case 'pelamar':
                        if ($user->pelamar && $user->pelamar->img_profile) {
                            Storage::delete('public/' . $user->pelamar->img_profile);
                        }
                        $user->pelamar()?->delete();
                        break;
                }
            }

            // Ambil foto lama
            $imgPath = match ($newRole) {
                'admin' => $user->admin->img_profile ?? null,
                'finance' => $user->finance->img_profile ?? null,
                'perusahaan' => $user->perusahaan->img_profile ?? null,
                'pelamar' => $user->pelamar->img_profile ?? null,
                default => null
            };

            // Upload foto baru
            if ($request->hasFile('img_profile')) {
                if ($imgPath) Storage::delete('public/' . $imgPath);
                $imgPath = $request->file('img_profile')->store('images', 'public');
            }

            // FORMAT NOMOR (PERUSAHAAN, WA, PELAMAR)
            foreach (['telepon_perusahaan', 'whatsapp', 'telepon_pelamar'] as $field) {
                if ($request->$field) {
                    $num = $request->$field;
                    $num = preg_replace('/^62/', '0', $num);

                    $request->merge([$field => $num]);
                }
            }

            // Update perusahaan
            if ($newRole === 'perusahaan') {
                $user->perusahaan()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_perusahaan'   => $request->nama_perusahaan,
                        'jenis_perusahaan'  => $request->jenis_perusahaan,
                        'website_perusahaan' => $request->website_perusahaan,
                        'telepon_perusahaan' => $request->telepon_perusahaan,
                        'whatsapp'          => $request->whatsapp,
                        'koin_perusahaan'     => $request->koin_perusahaan,
                        'legalitas'         => $request->legalitas,
                        'deskripsi'         => $request->deskripsi,
                        'visi'              => $request->visi,
                        'misi'              => $request->misi,
                        'img_profile'       => $imgPath,
                    ]
                );
            }

            // Update pelamar
            if ($newRole === 'pelamar') {
                $user->pelamar()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_pelamar'      => $request->nama_pelamar,
                        'telepon_pelamar'   => $request->telepon_pelamar,
                        'deskripsi_diri'    => $request->deskripsi_diri,
                        'tanggal_lahir'     => $request->tanggal_lahir,
                        'gender'            => $request->gender,
                        'kategori'          => $request->kategori ?? 'pelamar',
                        'gaji_minimal'      => $request->gaji_minimal,
                        'gaji_maksimal'     => $request->gaji_maksimal,
                        'img_profile'       => $imgPath,
                    ]
                );
            }

            $me = Auth::user();
            // ===========================================
            //  SIMPAN NOTIFIKASI BERHASIL
            // ===========================================
            Notifikasi::create([
                'user_id'   => $me->id,
                'perusahaan_id' => $me->perusahaan->id ?? null,
                'judul'     => 'Pembaharuan Berhasil',
                'pesan'     => 'Data akun Anda berhasil diperbarui.',
                'is_read'   => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return redirect()->route('superadmin.add.user')->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {

            $me = Auth::user();
            // ===========================================
            //  SIMPAN NOTIFIKASI GAGAL
            // ===========================================
            Notifikasi::create([
                'user_id'   => $me->id,
                'perusahaan_id' => null,
                'judul'     => 'Gagal Memperbarui Data',
                'pesan'     => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage(),
                'is_read'   => 0,
                'expired_at' => now()->addDays(7),
                'pelamar_lowongan_id' => null,
            ]);

            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }




    public function detail($id)
    {
        $user = User::with(['admin', 'finance', 'perusahaan', 'pelamar'])->findOrFail($id);

        return view('super_admin.add.detail', [
            'user' => $user
        ]);
    }

    public function hapus($id)
    {
        $user = User::with(['admin', 'finance', 'perusahaan', 'pelamar'])->findOrFail($id);

        //  Hapus gambar profil sesuai role
        if ($user->role === 'admin' && $user->admin?->img_profile) {
            Storage::delete('public/' . $user->admin->img_profile);
        } elseif ($user->role === 'finance' && $user->finance?->img_profile) {
            Storage::delete('public/' . $user->finance->img_profile);
        } elseif ($user->role === 'perusahaan' && $user->perusahaan?->img_profile) {
            Storage::delete('public/' . $user->perusahaan->img_profile);
        } elseif ($user->role === 'pelamar' && $user->pelamar?->img_profile) {
            Storage::delete('public/' . $user->pelamar->img_profile);
        }

        //  Hapus data terkait sesuai role
        if ($user->role === 'admin' && $user->admin) {
            $user->admin->delete();
        } elseif ($user->role === 'finance' && $user->finance) {
            $user->finance->delete();
        } elseif ($user->role === 'perusahaan' && $user->perusahaan) {
            $user->perusahaan->delete();
        } elseif ($user->role === 'pelamar' && $user->pelamar) {
            $user->pelamar->delete();
        }

        // 🔹 Terakhir, hapus user utamanya
        $user->delete();

        return redirect()->back()->with('success', 'Data User Berhasil Dihapus');
    }


    //PENGATURAN 
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:3',
        ]);

        $user = $request->user();

        //cek pw lama
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah');
    }


    public function halPerusahaan(Request $request)
    {
        $search = $request->input('search');
        $perusahaan = Perusahaan::with('user')
            ->when($search, function ($query, $search) {
                $query->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%");
                    });
            })
            ->get();
        return view('super_admin.perusahaan.data-perusahaan', [
            'perusahaan' => $perusahaan
        ]);
    }

    public function detailPerusahaan($id)
    {
        $perusahaan = Perusahaan::with(['user', 'lowonganPerusahaans'])->findOrFail($id);
        return view('super_admin.perusahaan.detail-perusahaan', [
            'perusahaan' => $perusahaan
        ]);
    }

    public function detailLowongan(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        // Pastikan lowongan milik perusahaan tersebut
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }

        return view('super_admin.perusahaan.detail-lowongan', [
            'lowongan'   => $lowongan,
            'perusahaan' => $perusahaan,
        ]);
    }




    //FINANCE
    public function halFinance(Request $request)
    {
        $cash = CatatanCash::where('status', 'diterima')->get();
        $koin1 = CatatanKoin::all();
        $cashTerbaru = CatatanCash::orderBy('created_at', 'desc')->get();
        $koinTerbaru = CatatanKoin::orderBy('created_at', 'desc')->get();

        // Ambil bulan & tahun dari filter atau default saat ini
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Ambil catatan cash yang diterima (semua user)
        $cash1 = DB::table('catatan_cashs')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as pendapatan')
            )
            ->where('status', 'diterima')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Ambil catatan koin (semua user)
        $koin = DB::table('catatan_koins')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total_koin')
            )
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Gabungkan data berdasarkan tanggal
        $laporan = collect();
        $tanggalUnik = $cash1->pluck('tanggal')->merge($koin->pluck('tanggal'))->unique()->sort();

        foreach ($tanggalUnik as $tgl) {
            $laporan->push([
                'tanggal'     => Carbon::parse($tgl)->translatedFormat('d F Y'),
                'pendapatan'  => $cash1->firstWhere('tanggal', $tgl)->pendapatan ?? 0,
                'koin'        => $koin->firstWhere('tanggal', $tgl)->total_koin ?? 0,
                'catatan'     => 'Catatan_Transaksi_' . Carbon::parse($tgl)->translatedFormat('F'),
            ]);
        }

        return view('super_admin.finance.paket-harga', [
            'title'        => 'Paket Harga',
            'koin'         => Hargakoin::all(),
            'pembayaran'   => HargaPembayaran::all(),
            'cashTerbaru'  => $cashTerbaru,
            'koinTerbaru'  => $koinTerbaru,
            'cash'         => $cash,
            'koin1'        => $koin1,
            'laporan'      => $laporan,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
        ]);
    }


    //Detail Laporan Finance
    public function detail_laporan($tanggal)
    {
        // Ubah format tanggal dari "11 November 2025" ke "2025-11-11"
        $tanggal = Carbon::createFromFormat('d F Y', $tanggal)->format('Y-m-d');

        // Ambil data dari catatan_cashs
        $cashs = CatatanCash::select(
            'id',
            'no_referensi',
            'dari',
            'pesanan',
            'sumberDana as sumber_dana',
            'total',
            DB::raw('NULL as total_koin'),
            DB::raw('"cash" as tipe')
        )
            ->whereDate('created_at', $tanggal)
            ->where('status', 'diterima');

        // Ambil data dari catatan_koins
        $koins = CatatanKoin::select(
            'id',
            'no_referensi',
            'dari',
            'pesanan',
            'sumber_dana',
            DB::raw('NULL as total'),
            DB::raw('ABS(total) as total_koin'),
            DB::raw('"koin" as tipe')
        )
            ->whereDate('created_at', $tanggal);

        // Gabungkan
        $query = $cashs->unionAll($koins);
        $transaksi = DB::query()->fromSub($query, 't')->get();

        // Hitung total
        $totalCash = $transaksi->where('tipe', 'cash')->sum('total');
        $totalKoin = $transaksi->where('tipe', 'koin')->sum('total_koin');

        return view('super_admin.finance.detail_laporan', [
            'transaksi' => $transaksi,
            'totalCash' => $totalCash,
            'totalKoin' => $totalKoin,
            'tanggal' => $tanggal
        ]);
    }

    //Laporan to pdf
    public function unduh_laporan_harian($tanggal)
    {
        // Ambil data transaksi cash & koin
        $cashs = CatatanCash::whereDate('created_at', $tanggal)
            ->where('status', 'diterima')
            ->get();

        $koins = CatatanKoin::whereDate('created_at', $tanggal)->get();

        // Gabungkan dua jenis transaksi jadi satu tabel
        $transaksi = collect();

        foreach ($cashs as $c) {
            $transaksi->push((object)[
                'no_referensi' => $c->no_referensi ?? '-',
                'dari' => $c->dari ?? '-',
                'pesanan' => $c->pesanan ?? '-',
                'sumber_dana' => $c->sumberDana ?? 'BCA',
                'nominal' => $c->total,
                'koin' => '-',
            ]);
        }

        foreach ($koins as $k) {
            $transaksi->push((object)[
                'no_referensi' => $k->no_referensi ?? '-',
                'dari' => $k->dari ?? '-',
                'pesanan' => $k->pesanan ?? '-',
                'sumber_dana' => $k->sumber_dana ?? 'Koin',
                'nominal' => '-',
                'koin' => $k->total,
            ]);
        }

        $totalTunai = $cashs->sum('total');
        $totalKoin = $koins->sum('total');

        // Konversi logo jadi base64
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        // Data untuk view
        $data = [
            'tanggal' => Carbon::parse($tanggal)->translatedFormat('d F Y'),
            'transaksi' => $transaksi,
            'totalTunai' => $totalTunai,
            'totalKoin' => $totalKoin,
            'logoBase64' => $logoBase64,
            'tanggalCetak' => Carbon::now()->translatedFormat('F d, Y, H:i a'),
        ];

        // Render view
        $html = View::make('finance.page-unduh-laporan-harian', $data)->render();

        // Tambahkan HTML wrapper + Tailwind
        $htmlWithCss = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Transaksi Harian</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: "Inter", sans-serif; }
        </style>
    </head>
    <body class="text-[12px] text-black font-sans mx-8 my-6">
        ' . $html . '
    </body>
    </html>
    ';

        // Generate PDF pakai Browsershot
        $browserPath = BrowserPath::detect();
        if (!$browserPath) {
            return response()->json([
                "error" => "Browser Chrome/Edge tidak ditemukan. Pastikan sudah terinstall."
            ], 500);
        }

        $pdf = Browsershot::html($htmlWithCss)
            ->setOption('executablePath', $browserPath)
            ->noSandbox()
            ->showBackground()
            ->format('A4')
            ->margins(10, 15, 10, 15)
            ->pdf();

        // Kembalikan file PDF
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Transaksi_Harian_' . $tanggal . '.pdf"');
    }

    //HARGA KOIN
    public function edit_koin()
    {
        return view('super_admin.finance.edit-koin', [
            'title' => 'Edit Harga Koin',
            'koin' => Hargakoin::all(),
        ]);
    }
    public function update_koin(Request $request)
    {
        foreach ($request->id as $i => $id) {
            $koin = Hargakoin::find($id);
            if ($koin) {
                $koin->harga = $request->harga[$i];
                $koin->save();
            }
        }

        return redirect()->route('superadmin.paket-harga');
    }


    //HARGA PEMBAYARAN
    public function edit_pembayaran()
    {
        return view('super_admin.finance.edit-harga', [
            'title' => 'Edit Harga Pembayaran',
            'pembayaran' => HargaPembayaran::all(),
        ]);
    }
    public function update_pembayaran(Request $request)
    {
        foreach ($request->id as $i => $id) {
            $pembayaran = HargaPembayaran::find($id);
            if ($pembayaran) {
                $pembayaran->harga = $request->harga[$i];
                $pembayaran->save();
            }
        }

        return redirect()->route('superadmin.paket-harga');
    }


    public function panggilan(Request $request)
    {
        $search = $request->input('search');

        $perusahaans = Perusahaan::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_perusahaan', 'like', '%' . $search . '%');
            })
            ->with([
                'pasanglowongan.pelamar' => function ($query) {
                    $query->where('pelamar_lowongans.status', 'diterima');
                },
                'alamat_perusahaan' => function ($query) {
                    $query->where('utama', 1)
                        ->with(['provinsi', 'kota', 'kecamatan']);
                }
            ])
            ->get();

        return view('super_admin.panggilan.data-panggilan', [
            'perusahaans' => $perusahaans,
            'search' => $search
        ]);
    }



    public function listPekerja(Request $request, $perusahaan_id)
    {
        $search = $request->input('search');

        $perusahaan = Perusahaan::with([
            'pasanglowongan',
        ])->findOrFail($perusahaan_id);

        // =============================
        // Pelamar dari tabel pelamar_lowongans
        // =============================
        $pelamarDiterima = PelamarLowongan::with(['pelamar.user', 'lowongan_perusahaan'])
            ->where('status', 'diterima')
            ->whereHas('lowongan_perusahaan', function ($q) use ($perusahaan_id) {
                $q->where('perusahaan_id', $perusahaan_id);
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pelamar', function ($sub) use ($search) {
                    $sub->where('nama_pelamar', 'like', "%$search%");
                });
            })
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->pelamar->nama_pelamar,
                    'email' => $item->pelamar->user->email,
                    'lowongan' => $item->lowongan_perusahaan->nama,
                    'tanggal_diterima' => $item->updated_at->format('d M Y'),
                    'jenis' => 'pelamar_melamar'
                ];
            });

        // =============================
        // Nomor WhatsApp admin
        // =============================
        $waAdmin = '6287874732189'; // nomor WA admin
        $waUrl = "https://wa.me/{$waAdmin}";

        return view('super_admin.panggilan.list-nama-pekerja', [
            'perusahaan' => $perusahaan,
            'pelamarDiterima' => $pelamarDiterima,
            'search' => $search,
            'waUrl' => $waUrl,
        ]);
    }





    //Talent Hunter
    public function talentHunterForm(Request $request)
    {
        $keyword = $request->input('search');

        $talentHunter = TalentHunter::with(['perusahaan.user'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('posisi', 'like', "%{$keyword}%")
                    ->orWhereHas('perusahaan', function ($q2) use ($keyword) {
                        $q2->where('nama_perusahaan', 'like', "%{$keyword}%")
                            ->orWhereHas('user', function ($q3) use ($keyword) {
                                $q3->where('username', 'like', "%{$keyword}%");
                            });
                    });
            })
            ->get();

        return view('super_admin.talent-hunter.data-talent-hunter', [
            'talentHunter' => $talentHunter,
            'search' => $keyword,
        ]);
    }

    public function detailDataTalentHunter($id)
    {
        $talentHunter = TalentHunter::with('perusahaan')->findOrFail($id);
        return view('super_admin.talent-hunter.detail-data-talent-hunter', [
            'talentHunter' => $talentHunter
        ]);
    }



    //RECRUITMENT
    public function recruitment(Request $request, $id)
    {
        $search = $request->search;

        $perusahaan = Perusahaan::findOrFail($id);

        $recruitments = PembeliKandidat::where('status', 'diterima')
            ->whereHas('lowonganPerusahaan', function ($q) use ($id) {
                $q->where('perusahaan_id', $id);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->whereHas('pelamar', function ($pel) use ($search) {
                        $pel->where('nama_pelamar', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lowonganPerusahaan', function ($low) use ($search) {
                            $low->where('nama', 'like', '%' . $search . '%');
                        });
                });
            })
            ->with(['pelamar', 'lowonganPerusahaan'])
            ->get();


        return view('super_admin.recruitment.data-recruitment', [
            'perusahaan' => $perusahaan,
            'recruitments' => $recruitments,
            'search' => $search,
        ]);
    }


    public function recruitmentPerusahaan(Request $request)
    {
        $search = $request->input('search');
        $perusahaan = Perusahaan::with('user')
            ->when($search, function ($query, $search) {
                $query->where('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%");
                    });
            })
            ->get();

        return view('super_admin.recruitment.perusahaan', [
            'perusahaan' => $perusahaan
        ]);
    }


    public function detailRecruitment($id)
    {
        $recruitment = PembeliKandidat::with([
            'pelamar.user',
            'pelamar.sosmed',
            'pelamar.pengalaman_organisasi',
            'pelamar.pengalaman_kerja',
            'pelamar.riwayat_pendidikan',
            'pelamar.alamat_pelamar',
            'pelamar.skill',
            'lowonganPerusahaan.perusahaan.alamatUtama',
            'lowonganPerusahaan.perusahaan'
        ])->findOrFail($id);

        return view('super_admin.recruitment.detail-recruitment', [
            'recruitment' => $recruitment
        ]);
    }


    public function destroyRecruitment($id)
    {
        //Ambil data pembelian kandidat 
        $pembelian = PembeliKandidat::with([
            'pelamar.user',
            'lowonganPerusahaan.perusahaan'
        ])->findOrFail($id);

        $user = $pembelian->pelamar->user;
        $perusahaan = $pembelian->lowonganPerusahaan->perusahaan ?? null;
        $perusahaanUser = $perusahaan->user ?? null;

        //hapus pembelian kandidat
        $pembelian->delete();

        //kirim notifikasi ke pelamar
        Notifikasi::create([
            'user_id' => $user->id,
            'perusahaan_id' => $perusahaan->id,
            'judul' => 'Status Recruitment Dibatalkan',
            'pesan' => 'Status Recruitment Anda telah dibatalkan oleh Admin.',
            'expired_at' => now()->addDays(7),
        ]);

        if ($perusahaanUser) {
            //kirim notifikasi ke perusahaan
            Notifikasi::create([
                'user_id' => $perusahaan->user->id,
                // 'perusahaan_id' => $perusahaan->id,
                'judul' => 'Status Recruitment Dibatalkan',
                'pesan' => 'Kandidat' . $pembelian->pelamar->nama_pelamar .  'telah dihapus dari daftar recruitment oleh Admin.',
                'expired_at' => now()->addDays(7),
            ]);
        }

        return redirect()->route('superadmin.recruitment', $perusahaan->id)->with('success', 'Recruitment berhasil dihapus & pelamar kembali menjadi kandidat biasa.');
    }


    //EDIT RECRUITMENT
    // public function editRecruitment($kategori, $id)
    // {
    //     $pembelian = PembeliKandidat::with([
    //         'pelamar.user',
    //         'pelamar.alamat_pelamar',
    //         'pelamar.riwayat_pendidikan',
    //         'pelamar.pengalaman_organisasi',
    //         'pelamar.pengalaman_kerja',
    //         'pelamar.skill',
    //         'pelamar.sosmed',
    //         'lowonganPerusahaan.perusahaan',
    //     ])->find($id);

    //     return view('super_admin.pelamar.edit-kandidat-superadmin', [
    //         'pembelian' => $pembelian,
    //         'pelamar'   => $pembelian->pelamar,
    //         'lowongan'  => $pembelian->lowonganPerusahaan,

    //     ]);
    // }


    // public function updateRecruitment(Request $request, $id)
    // {
    //     $pembelian = PembeliKandidat::findOrFail($id);

    //     $pelamar = $pembelian->pelamar;

    //     $request->validate([
    //         'nama_pelamar' => 'required|string|max:255',
    //         'img_profile'  => 'nullable|image',
    //     ]);

    //     // Update foto profil
    //     $path = $pelamar->img_profile;
    //     if ($request->hasFile('img_profile')) {
    //         if ($pelamar->img_profile && Storage::exists('public/' . $pelamar->img_profile)) {
    //             Storage::delete('public/' . $pelamar->img_profile);
    //         }
    //         $path = $request->file('img_profile')->store('images', 'public');
    //     }

    //     // Update data utama
    //     $pelamar->update([
    //         'nama_pelamar'    => $request->nama_pelamar,
    //         'deskripsi_diri'  => $request->deskripsi_diri,
    //         'tanggal_lahir'   => $request->tanggal_lahir,
    //         'gender'          => $request->gender,
    //         'telepon_pelamar' => $request->telepon_pelamar,
    //         'divisi'          => $request->divisi,
    //         'gaji_minimal'    => $request->gaji_minimal,
    //         'gaji_maksimal'   => $request->gaji_maksimal,
    //         'img_profile'     => $path,
    //     ]);

    //     // Tambahkan data baru relasi
    //     if ($request->filled('alamat')) {
    //         $pelamar->alamat_pelamar()->delete();
    //         foreach ($request->alamat as $data) {
    //             $pelamar->alamat_pelamar()->create($data);
    //         }
    //     }

    //     if ($request->filled('pendidikan')) {
    //         $pelamar->riwayat_pendidikan()->delete();
    //         foreach ($request->pendidikan as $data) {
    //             $pelamar->riwayat_pendidikan()->create($data);
    //         }
    //     }

    //     if ($request->filled('organisasi')) {
    //         $pelamar->pengalaman_organisasi()->delete();
    //         foreach ($request->organisasi as $data) {
    //             $pelamar->pengalaman_organisasi()->create($data);
    //         }
    //     }

    //     if ($request->filled('pengalaman_kerja')) {
    //         $pelamar->pengalaman_kerja()->delete();
    //         foreach ($request->pengalaman_kerja as $data) {
    //             $pelamar->pengalaman_kerja()->create($data);
    //         }
    //     }

    //     if ($request->filled('skill')) {
    //         $pelamar->skill()->delete();
    //         foreach ($request->skill as $data) {
    //             $pelamar->skill()->create($data);
    //         }
    //     }

    //     // Sosial media
    //     if ($request->has('social_media') && is_array($request->social_media)) {
    //         $pelamar->sosmed()->updateOrCreate([], $request->social_media);
    //     }

    //     return back()->with('success', 'Data recruitment berhasil diperbarui.');
    // }
}
