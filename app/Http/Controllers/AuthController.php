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
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
                'finance'     => redirect()->route('finance.dashboard'),
                default       => back(),
            };
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
    }

    // Pelamar / Home Publik
    public function beranda(Request $request)
    {
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
            ->whereNotNull('published_at')
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
            ->get();

        $jenisList = LowonganPerusahaan::query()
            ->whereNotNull('jenis')
            ->where('jenis', '!=', '')
            ->distinct()
            ->pluck('jenis');

        return view('non-user.home', [
            "Data"         => $Data,
            "KategoriList" => $KategoriList,
            "kategori"     => $kategori,
            "jenisList"    => $jenisList,
        ]);
    }

    public function loginproses(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

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
                } elseif ($user->role == 'finance') {
                    return redirect()->route('finance.dashboard');
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

    public function regis_proses(Request $request)
    {
        try {
            $valid = $request->validate([
                'username'         => 'required|unique:users,username',
                'email'            => 'required|email|unique:users,email',
                'password'         => 'required|min:8',
                'role'             => 'required',
                'telepon_pelamar'  => ['required', 'regex:/^(?:628|08)[0-9]+$/'],
                'agree_pelamar'    => 'accepted'
            ], [
                'username.required'        => 'Username wajib diisi.',
                'username.unique'          => 'Username sudah digunakan.',
                'email.required'           => 'Email wajib diisi.',
                'email.email'              => 'Format email tidak valid.',
                'email.unique'             => 'Email sudah terdaftar.',
                'password.required'        => 'Password wajib diisi.',
                'password.min'             => 'Password minimal 8 karakter.',
                'role.required'            => 'Role wajib diisi.',
                'telepon_pelamar.required' => 'Nomor telepon wajib diisi.',
                'agree_pelamar.accepted'   => 'Anda harus menyetujui syarat dan ketentuan.',
                'telepon_pelamar.regex'    => 'Nomor telepon harus diawali dengan 628, atau 08.'
            ]);

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
            ->with('paket')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhereDate('expired_at', '>=', now());
            })
            ->latest()
            ->get();

        if (
            $perusahaan->is_berlangganan == 1 &&
            $perusahaan->tanggal_expired &&
            Carbon::now()->lt($perusahaan->tanggal_expired) &&
            $request->query('show') !== 'dashboard'
        ) {
            return view('perusahaan.langganan.dah_langganan', [
                'perusahaan' => $perusahaan
            ]);
        }

        // Paket topup default
        $hargaPembayarans = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000, 'icon' => 'bitcoin.png'],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000, 'icon' => 'bit2.png'],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000, 'icon' => 'bit3.png'],
        ]);

        return view('perusahaan.dashboard', [
            'hargaPembayarans' => $hargaPembayarans,
            'daftarBank'       => DaftarBank::all(),
            'lowongans'        => $lowongans,
            'perusahaan'       => $perusahaan,
            'events'           => $events
        ]);
    }

    public function loginproses_perusahaan(Request $request)
    {
        return $this->loginproses($request);
    }

    public function regis_proses_perusahaan(Request $request)
    {
        try {
            $valid = $request->validate([
                'username'           => 'required|unique:users,username',
                'email'              => 'required|email|unique:users,email',
                'password'           => 'required|min:8',
                'role'               => 'required',
                'nama_perusahaan'    => 'required|string|max:255',
                'telepon_perusahaan' => ['required', 'regex:/^(?:628|08)[0-9]+$/'],
                'agree_perusahaan'   => 'accepted'
            ], [
                'username.required'           => 'Username wajib diisi.',
                'username.unique'             => 'Username sudah digunakan.',
                'email.required'              => 'Email wajib diisi.',
                'email.email'                 => 'Format email tidak valid.',
                'email.unique'                => 'Email sudah terdaftar.',
                'password.required'           => 'Password wajib diisi.',
                'password.min'                => 'Password minimal 8 karakter.',
                'role.required'               => 'Role wajib diisi.',
                'telepon_perusahaan.required' => 'Nomor telepon perusahaan wajib diisi.',
                'agree_perusahaan.accepted'   => 'Anda harus menyetujui syarat dan ketentuan.',
                'telepon_perusahaan.regex'    => 'Nomor telepon harus diawali dengan 628, atau 08.',
            ]);

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
                'nama_perusahaan'    => $request->nama_perusahaan,
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

    // FINANCE
    public function beranda_finance()
    {
        $cash = CatatanCash::where('status', 'diterima')->orWhere('status', 'sukses')->get();
        $totalOmset = $cash->sum('total');

        $koin = CatatanKoin::all();
        $totalTransaksiKoin = $koin->sum(fn($item) => abs($item->total));

        $cashTerbaru = CatatanCash::orderBy('created_at', 'desc')->take(5)->get();
        $koinTerbaru = CatatanKoin::orderBy('created_at', 'desc')->take(5)->get();

        $notifikasiCash = CatatanCash::where('status', 'menunggu_verifikasi')->orWhere('status', 'pending')->get();
        $notifCount = $notifikasiCash->count();

        return view('finance.dashboard', [
            'totalOmset'         => $totalOmset,
            'totalTransaksiKoin' => $totalTransaksiKoin,
            'cash'               => $cash,
            'koin'               => $koin,
            'cashTerbaru'        => $cashTerbaru,
            'koinTerbaru'        => $koinTerbaru,
            'notifikasiCash'     => $notifikasiCash,
            'notifCount'         => $notifCount,
        ]);
    }

    public function loginproses_finance(Request $request)
    {
        return $this->loginproses($request);
    }

    public function regis_proses_finance(Request $request)
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
            'role'         => 'finance',
            'verified'     => 1,
            'status'       => 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function logout_finance(Request $request)
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
        $startThreeMonthsAgo = $now->copy()->subMonths(3)->startOfMonth();
        $endLastMonth = $startThisMonth->copy()->subSecond();

        $currentPerusahaan = Perusahaan::whereBetween('created_at', [$startThisMonth, $now])->count();
        $lastPerusahaan = Perusahaan::whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])->count();
        $growthPerusahaan = $this->calcGrowth($lastPerusahaan, $currentPerusahaan);

        $currentKandidat = Pelamar::where('kategori', 'kandidat aktif')
            ->whereBetween('created_at', [$startThisMonth, $now])
            ->count();
        $lastKandidat = Pelamar::where('kategori', 'kandidat aktif')
            ->whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])
            ->count();
        $growthKandidat = $this->calcGrowth($lastKandidat, $currentKandidat);

        $currentNonKandidat = Pelamar::where('kategori', 'pelamar')
            ->whereBetween('created_at', [$startThisMonth, $now])
            ->count();
        $lastNonKandidat = Pelamar::where('kategori', 'pelamar')
            ->whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])
            ->count();
        $growthNonKandidat = $this->calcGrowth($lastNonKandidat, $currentNonKandidat);

        $currentLowongan = LowonganPerusahaan::whereBetween('created_at', [$startThisMonth, $now])->count();
        $lastLowongan = LowonganPerusahaan::whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])->count();
        $growthLowongan = $this->calcGrowth($lastLowongan, $currentLowongan);

        return view('admin.dashboard', [
            'totalPerusahaan'   => $currentPerusahaan,
            'growthPerusahaan'  => $growthPerusahaan,
            'totalKandidat'     => $currentKandidat,
            'growthKandidat'    => $growthKandidat,
            'totalNonKandidat'  => $currentNonKandidat,
            'growthNonKandidat' => $growthNonKandidat,
            'totalLowongan'     => $currentLowongan,
            'growthLowongan'    => $growthLowongan,
        ]);
    }

    private function calcGrowth($last, $current)
    {
        if ($last == 0 && $current > 0) return 100;
        if ($last == 0 && $current == 0) return 0;
        return round((($current - $last) / $last) * 100, 1);
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
    public function login_finance() { return view('finance.auth.login'); }
    public function regis_finance() { return view('finance.auth.register'); }
    public function verif_finance() { return view('finance.auth.verifikasi'); }
    public function verifotp_finance() { return view('finance.auth.verif-codepw'); }
    public function veriflupapw_finance() { return view('finance.auth.verif-lupa-sandi'); }

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
