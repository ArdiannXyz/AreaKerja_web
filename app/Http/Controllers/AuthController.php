<?php

namespace App\Http\Controllers;

use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use App\Models\DaftarBank;
use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterPelamarRequest;
use App\Http\Requests\RegisterPerusahaanRequest;

class AuthController extends Controller
{
    /**
     * @deprecated Method ini tidak dipakai oleh route manapun.
     * Route login yang aktif adalah POST /loginproses → loginproses().
     * Method ini tidak memiliki session regenerate, status check, maupun remember-me.
     * Tidak dihapus untuk menghindari breaking change jika ada view lama yang masih merujuk ke sini.
     */
    public function masuk(Request $request)
    {
        $valid = $request->validate([
            "email"    => "required|email",
            "password" => "required"
        ]);
        
        if (Auth::attempt($valid)) {
            $user = Auth::user();
            $role = $user->role;

            return match ($role) {
                'super_admin' => redirect()->route('superadmin.dashboard'),
                'admin'       => redirect()->route('admin.dashboard'),
                'pelamar'     => redirect()->route('beranda'),
                'perusahaan'  => redirect()->route('perusahaan.dashboard'),
                default       => back(),
            };
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
    }

    // Pelamar / Home Publik
    public function beranda(Request $request)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'super_admin') {
                return redirect()->route('superadmin.dashboard');
            }
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($role === 'perusahaan') {
                return redirect()->route('perusahaan.dashboard');
            }
        }


        // Ambil kategori dari query string
        $kategori = $request->query('kategori');

        // Jika kategori ada → simpan, filter, lalu redirect ke URL bersih
        if ($kategori) {
            return redirect()
                ->to('/pelamar/home')
                ->with('kategori_filter', $kategori);
        }

        // Ambil kategori dari session ONLY untuk 1x
        $kategori = session()->pull('kategori_filter');

        $KategoriList = LowonganPerusahaan::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        if ($KategoriList->isEmpty()) {
            $KategoriList = collect(['IT & Software', 'Marketing', 'Finance', 'Desain & Kreatif', 'Operasional', 'Sales']);
        }

        $Data = LowonganPerusahaan::with('perusahaan')
            ->whereHas('perusahaan')
            ->publik()
            ->whereNotNull('published_at')
            ->where('status', '!=', 'tutup')
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->when($kategori, function ($q) use ($KategoriList, $kategori) {
                if ($KategoriList->contains($kategori)) {
                    $q->where('kategori', $kategori);
                }
            })
            ->orderByRaw("
                CASE
                    WHEN boosted_until IS NOT NULL AND boosted_until > NOW() THEN 0
                    WHEN rekomendasi IS NOT NULL THEN 1
                    ELSE 2
                END
            ")
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $jenisList = LowonganPerusahaan::query()
            ->whereNotNull('jenis')
            ->where('jenis', '!=', '')
            ->distinct()
            ->pluck('jenis');

        $activeAds = \App\Models\Iklan::active()->with('perusahaan')->latest()->get();
        if ($activeAds->isNotEmpty()) {
            \App\Models\Iklan::whereIn('id', $activeAds->pluck('id'))->increment('total_views');
        }

        return view('non-user.home', [
            "Data"         => $Data,
            "KategoriList" => $KategoriList,
            "kategori"     => $kategori,
            "jenisList"    => $jenisList,
            "activeAds"    => $activeAds,
            "posisi"       => null,
            "lokasi"       => null,
            "provinsi"     => null,
            "kota"         => null,
            "provinsis"    => \App\Services\WilayahService::getProvinces(),
            "jenis"        => null,
            "pengalaman"   => null,
            "pendidikan"   => null,
            "adaPencarian" => false,
            "riwayat"      => session()->get('riwayat_full', []),
        ]);
    }

    public function loginproses(LoginRequest $request)
    {
        $credentials = $request->validated();

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status == 0) {
                if ($user->role === 'pelamar') {
                    $pelamar = Pelamar::where('user_id', $user->id)->first();
                    if ($pelamar && !$pelamar->isProfileComplete()) {
                        if (!session()->has('profile_popup_closed')) {
                            session(['show_first_login_popup' => true]);
                        }
                    }
                    return redirect()->route('beranda');
                }

                if ($user->role == 'super_admin') {
                    return redirect()->route('superadmin.dashboard');
                } elseif ($user->role == 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role == 'perusahaan') {

                    if (!$request->session()->has('already_logged')) {
                        $request->session()->put('first_login', true);
                        $request->session()->put('already_logged', true);
                    }
                    return redirect()->route('perusahaan.dashboard');
                }
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan.',
                ]);
            }
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ]);
    }

    public function regis_proses(RegisterPelamarRequest $request)
    {
        try {
            $valid = $request->validated();

            $telepon = preg_replace('/[^0-9\+]/', '', $request->telepon_pelamar);
            $telepon = preg_replace('/^62/', '0', $telepon);

            $user = User::create([
                'username'     => $valid['username'],
                'email'        => $valid['email'],
                'telepon'      => $telepon,
                'password'     => Hash::make($request->password),
                'role'         => 'pelamar',
                'verified'     => 1,
                'status'       => 0,
            ]);

            $user->pelamar()->create([
                'telepon_pelamar' => $telepon,
                'nama_pelamar'    => $valid['username'],
                'kategori'        => 'pelamar',
            ]);

            return response()->json(['success' => true]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function logout_pelamar(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // LOGIN & DASHBOARD PERUSAHAAN
    public function beranda_perusahaan(Request $request)
    {
        $events = collect();
        $perusahaan = auth()->user()->perusahaan;

        $lowongans = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhereDate('expired_at', '>=', now());
            })
            ->latest()
            ->get();

        // Paket topup default
        $hargaPembayarans = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000, 'icon' => 'bitcoin.png'],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000, 'icon' => 'bit2.png'],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000, 'icon' => 'bit3.png'],
        ]);

        // IDs semua lowongan milik perusahaan ini
        $allLowonganIds = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)->pluck('id');

        // Total pelamar yang masuk
        $totalPelamar = PelamarLowongan::whereIn('lowongan_id', $allLowonganIds)->count();

        // Total lowongan aktif (published & belum expired)
        $totalLowonganAktif = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->whereNotNull('published_at')
            ->whereDate('expired_at', '>=', now())
            ->count();

        // Total iklan aktif
        $totalIklanAktif = \App\Models\Iklan::where('perusahaan_id', $perusahaan->id)
            ->where('status', 'aktif')
            ->count();

        // Data grafik pelamar per bulan (6 bulan terakhir)
        $chartLabels = [];
        $chartData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M Y');
            $chartData[] = PelamarLowongan::whereIn('lowongan_id', $allLowonganIds)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return view('perusahaan.dashboard', [
            'hargaPembayarans'  => $hargaPembayarans,
            'daftarBank'        => DaftarBank::all(),
            'lowongans'         => $lowongans,
            'perusahaan'        => $perusahaan,
            'events'            => $events,
            'totalPelamar'      => $totalPelamar,
            'totalLowonganAktif'=> $totalLowonganAktif,
            'totalIklanAktif'   => $totalIklanAktif,
            'chartLabels'       => $chartLabels,
            'chartData'         => $chartData,
        ]);
    }

    public function loginproses_perusahaan(Request $request)
    {
        return $this->loginproses($request);
    }

    public function regis_proses_perusahaan(RegisterPerusahaanRequest $request)
    {
        try {
            $valid = $request->validated();

            $telepon = preg_replace('/[^0-9\+]/', '', $request->telepon_perusahaan);
            $telepon = preg_replace('/^62/', '0', $telepon);

            $user = User::create([
                'username'     => $valid['username'],
                'nama_lengkap' => $valid['nama_perusahaan'],
                'email'        => $valid['email'],
                'telepon'      => $telepon,
                'password'     => Hash::make($request->password),
                'role'         => 'perusahaan',
                'verified'     => 1,
                'status'       => 0,
            ]);

            $user->perusahaan()->create([
                'telepon_perusahaan' => $telepon,
                'nama_perusahaan'    => $valid['nama_perusahaan'],
                'koin_perusahaan'    => 0,
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function logout_perusahaan(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ADMIN
    public function login_admin()

    {
        return view('admin.auth.login');
    }

    public function beranda_admin()
    {
        $now = Carbon::now();
        $startThisMonth = $now->copy()->startOfMonth();
        $startLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endLastMonth = $startThisMonth->copy()->subSecond();

        // 1. PERUSAHAAN (Total & Pertumbuhan)
        $totalPerusahaan = Perusahaan::count();
        $newPerusahaanThisMonth = Perusahaan::where('created_at', '>=', $startThisMonth)->count();
        $newPerusahaanLastMonth = Perusahaan::whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $growthPerusahaan = $this->calcGrowth($newPerusahaanLastMonth, $newPerusahaanThisMonth);

        // 2. KANDIDAT AKTIF (Total & Pertumbuhan)
        $totalKandidat = Pelamar::where('kategori', 'kandidat aktif')->count();
        $newKandidatThisMonth = Pelamar::where('kategori', 'kandidat aktif')->where('created_at', '>=', $startThisMonth)->count();
        $newKandidatLastMonth = Pelamar::where('kategori', 'kandidat aktif')->whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $growthKandidat = $this->calcGrowth($newKandidatLastMonth, $newKandidatThisMonth);

        // 3. NON KANDIDAT / PELAMAR BIASA (Total & Pertumbuhan)
        $totalNonKandidat = Pelamar::where(function ($q) {
            $q->whereNull('kategori')->orWhere('kategori', 'pelamar')->orWhere('kategori', '');
        })->count();
        $newNonKandidatThisMonth = Pelamar::where(function ($q) {
            $q->whereNull('kategori')->orWhere('kategori', 'pelamar')->orWhere('kategori', '');
        })->where('created_at', '>=', $startThisMonth)->count();
        $newNonKandidatLastMonth = Pelamar::where(function ($q) {
            $q->whereNull('kategori')->orWhere('kategori', 'pelamar')->orWhere('kategori', '');
        })->whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $growthNonKandidat = $this->calcGrowth($newNonKandidatLastMonth, $newNonKandidatThisMonth);

        // 4. LOWONGAN KERJA (Total & Pertumbuhan)
        $totalLowongan = LowonganPerusahaan::count();
        $newLowonganThisMonth = LowonganPerusahaan::where('created_at', '>=', $startThisMonth)->count();
        $newLowonganLastMonth = LowonganPerusahaan::whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $growthLowongan = $this->calcGrowth($newLowonganLastMonth, $newLowonganThisMonth);

        $latestLowongans = LowonganPerusahaan::with('perusahaan')->latest()->take(5)->get();
        $latestPerusahaans = Perusahaan::latest()->take(5)->get();

        return view('admin.dashboard', [
            'totalPerusahaan'    => $totalPerusahaan,
            'growthPerusahaan'   => $growthPerusahaan,
            'totalKandidat'      => $totalKandidat,
            'growthKandidat'     => $growthKandidat,
            'totalNonKandidat'   => $totalNonKandidat,
            'growthNonKandidat'  => $growthNonKandidat,
            'totalLowongan'      => $totalLowongan,
            'growthLowongan'     => $growthLowongan,
            'latestLowongans'    => $latestLowongans,
            'latestPerusahaans'  => $latestPerusahaans,
        ]);
    }

    private function calcGrowth($last, $current)
    {
        if ($last == 0 && $current > 0) return 100;
        if ($last == 0 && $current == 0) return 0;
        if ($last > 0 && $current == 0) return 0;
        return round((($current - $last) / max($last, 1)) * 100, 1);
    }

    public function loginproses_admin(Request $request)
    {
        return $this->loginproses($request);
    }

    public function regis_admin()
    {
        return view('admin.auth.register');
    }

    public function regis_proses_admin(Request $request)
    {
        $valid = $request->validate([
            'username'     => 'required|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8',
            'nama_lengkap' => 'nullable|string',
        ]);

        User::create([
            'username'     => $valid['username'],
            'email'        => $valid['email'],
            'nama_lengkap' => $valid['nama_lengkap'] ?? $valid['username'],
            'password'     => Hash::make($request->password),
            'role'         => 'admin',
            'verified'     => 1,
            'status'       => 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function logout_admin(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // SUPER ADMIN
    public function login_superadmin()
    {
        return view('super_admin.auth.login');
    }

    public function loginproses_superadmin(Request $request)
    {
        return $this->loginproses($request);
    }

    public function regis_super_admin()
    {
        return view('super_admin.auth.register');
    }

    public function regis_proses_superadmin(Request $request)
    {
        $valid = $request->validate([
            'username'     => 'required|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8',
            'nama_lengkap' => 'nullable|string',
        ]);

        User::create([
            'username'     => $valid['username'],
            'email'        => $valid['email'],
            'nama_lengkap' => $valid['nama_lengkap'] ?? $valid['username'],
            'password'     => Hash::make($request->password),
            'role'         => 'super_admin',
            'verified'     => 1,
            'status'       => 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function logout_superadmin(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // NON USER AUTH
    public function login_non_user()
    {
        $socialLinks = collect();
        return view('non-user.auth.login', compact('socialLinks'));
    }

    public function regis_non_user()
    {
        $socialLinks = collect();
        return view('non-user.auth.register', compact('socialLinks'));
    }

    // Views placeholders for forgotten password
    public function verif_admin() { return view('admin.auth.verif'); }

    public function verifotp_admin() { return view('admin.auth.verif-otp'); }
    public function veriflupapw_admin() { return view('admin.auth.verif-lupapw'); }

    public function verif_super_admin() { return view('super_admin.auth.verif'); }
    public function verifotp_super_admin() { return view('super_admin.auth.verif-otp'); }
    public function veriflupapw_super_admin() { return view('super_admin.auth.verif-lupapw'); }

    public function login_perusahaan() { return view('perusahaan.auth.login'); }
    public function regis_perusahaan() { return view('perusahaan.auth.register'); }
    public function verif_perusahaan() { return view('perusahaan.auth.verif'); }
    public function verifotp_perusahaan() { return view('perusahaan.auth.verif-otp'); }
    public function veriflupapw_perusahaan() { return view('perusahaan.auth.verif-lupapw'); }
}
