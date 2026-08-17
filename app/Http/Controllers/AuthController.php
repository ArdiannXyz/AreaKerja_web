<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use App\Models\Category;
use App\Models\DaftarBank;
use App\Models\Event;
use App\Models\Finance;
use App\Models\HargaPembayaran;
use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Pembayaran;
use App\Models\Perusahaan;
use App\Models\Provinsi;
use App\Models\SocialLink;
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
            "username" => "required",
            "password" => "required"
        ]);
        
        if (Auth::attempt($valid)) {

            $user = Auth::user();
            $role = $user->role;

            // // === Popup untuk PELAMAR ===
            // if ($role === 'pelamar') {
            //     // set popup ON (hanya saat login)
            //     session(['show_first_login_popup' => true]);
            //     session()->forget('profile_popup_closed');
            // }

            return match ($role) {
                'super_admin' => redirect()->route('superadmin.dashboard'),
                'admin'       => redirect()->route('admin.dashboard'),
                'pelamar'     => redirect()->route('beranda'),
                'perusahaan'  => redirect()->route('perusahaan.dashboard'),
                'finance'     => redirect('/dashboard/finance'),
                default       => back(),
            };
        }

        return back();
    }




    //pelamar
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

        $KategoriList = Category::pluck('nama');

        $Data = LowonganPerusahaan::with('perusahaan')
            ->whereNotNull('published_at')

            ->when($kategori, function ($q) use ($KategoriList, $kategori) {
                if ($KategoriList->contains($kategori)) {
                    $q->where('kategori', $kategori);
                }
            })

            /* ===============================
     | PRIORITAS GRUP
     =============================== */
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NOT NULL THEN 0
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NULL THEN 1
            WHEN boosted_until IS NULL AND rekomendasi IS NOT NULL THEN 2
            ELSE 3
        END
    ")

            /* ===============================
     | URUTAN DALAM GRUP
     =============================== */

            // -- Boost + Rekomendasi → BOOST TERBARU DI ATAS
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NOT NULL
            THEN boosted_until
        END DESC
    ")

            // -- Boost saja → BOOST TERBARU DI ATAS
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NOT NULL AND rekomendasi IS NULL
            THEN boosted_until
        END DESC
    ")

            // -- Rekomendasi saja → PALING LAMA DI ATAS
            ->orderByRaw("
        CASE
            WHEN boosted_until IS NULL AND rekomendasi IS NOT NULL
            THEN rekomendasi
        END ASC
    ")

            // -- Biasa → TERBARU DI ATAS
            ->orderBy('created_at', 'DESC')

            ->get();


        $jenisList = LowonganPerusahaan::query()
            ->whereNotNull('jenis')
            ->where('jenis', '!=', '')
            ->distinct()
            ->pluck('jenis');

        return view('non-user.home', [
            "Data" => $Data,
            "KategoriList" => $KategoriList,
            "kategori" => $kategori,
            "jenisList" => $jenisList,
        ]);
    }


    public function loginproses(Request $request)
    {
        $valid = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($valid)) {

            $user = Auth::user();

            if ($user->status == 0) {

                // ============================
                //  POPUP KHUSUS PELAMAR
                // ============================
                if ($user->role === 'pelamar') {

                    $pelamar = Pelamar::where('user_id', $user->id)->first();

                    // Kalau profil belum lengkap → tampilkan popup
                    if ($pelamar && !$pelamar->isProfileComplete()) {
                        if (!session()->has('profile_popup_closed')) {
                            session(['show_first_login_popup' => true]);
                        }
                    }

                    return redirect()->route('beranda');
                }


                // ============================
                // Role lainnya tanpa popup
                // ============================
                if ($user->role == 'super_admin') {
                    return redirect()->route('superadmin.dashboard');
                } elseif ($user->role == 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role == 'finance') {
                    return redirect()->route('finance.dashboard');
                } elseif ($user->role == 'perusahaan') {
                    return redirect()->route('perusahaan.dashboard');
                }
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Akun anda telah di nonaktifkan',
                ]);
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }



    public function regis_proses(Request $request)
    {
        try {
            $valid = $request->validate([
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required',
                'telepon_pelamar' => ['required', 'regex:/^(?:628|08)[0-9]+$/'],
                'agree_pelamar' => 'accepted'
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'role.required' => 'Role wajib diisi.',
                'telepon_pelamar.required' => 'Nomor telepon wajib diisi.',
                'telepon_pelamar.numeric' => 'Nomor telepon harus berupa angka.',
                'agree_pelamar.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
                'telepon_pelamar.regex' => 'Nomor telepon harus diawali dengan 628, atau 08.'
            ]);

            // Normalisasi nomor telepon
            $telepon = preg_replace('/[^0-9\+]/', '', $request->telepon_pelamar);
            $telepon = preg_replace('/^62/', '0', $telepon);

            $valid['telepon_pelamar'] = $telepon;

            $valid['password'] = Hash::make($request->password);
            $user = User::create($valid);

            $user->pelamar()->create([
                'telepon_pelamar' => $telepon
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


    //LOGIN PERUSAHAAN
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

        if (session('event_popup')) {
            $events = Event::whereIn('id', session('event_popup'))->orderBy('created_at')->get();
        }

        // Jika sedang berlangganan DAN user tidak meminta dashboard
        if (
            $perusahaan->is_berlangganan == 1 &&
            \Carbon\Carbon::now()->lt($perusahaan->tanggal_expired) &&
            $request->query('show') !== 'dashboard'
        ) {
            return view('perusahaan.langganan.dah_langganan', [
                'perusahaan' => $perusahaan
            ]);
        }

        // Jika tidak berlangganan ATAU user minta dashboard
        return view('perusahaan.dashboard', [
            'hargaPembayarans' => HargaPembayaran::where('jumlah_koin', '>', 0)->get(),
            'daftarBank' => DaftarBank::all(),
            'lowongans' => $lowongans,
            'perusahaan' => $perusahaan,
            'events' => $events
        ]);
    }


    public function loginproses_perusahaan(Request $request)
    {
        $val = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($val)) {
            $request->session()->regenerate();

            // Hanya set sekali waktu login pertama
            if (!$request->session()->has('already_logged')) {
                $request->session()->put('first_login', true);
                $request->session()->put('already_logged', true);
            }

            return redirect()->route('perusahaan.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }


    public function regis_proses_perusahaan(Request $request)
    {
        try {
            $valid = $request->validate([
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required',
                'telepon_perusahaan' =>  ['required', 'regex:/^(?:628|08)[0-9]+$/'],
                'agree_perusahaan' => 'accepted'
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 3 karakter.',
                'role.required' => 'Role wajib diisi.',
                'telepon_perusahaan.required' => 'Nomor telepon perusahaan wajib diisi.',
                'telepon_perusahaan.numeric' => 'Nomor telepon perusahaan harus berupa angka.',
                'telepon_perusahaan.regex' => 'Nomor telepon harus diawali dengan 628, atau 08.',
                'agree_perusahaan.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
            ]);

            // Normalisasi nomor telepon
            $telepon = preg_replace('/[^0-9\+]/', '', $request->telepon_perusahaan);
            $telepon = preg_replace('/^62/', '0', $telepon);

            $valid['telepon_perusahaan'] = $telepon;

            $valid['password'] = Hash::make($request->password);
            $user = User::create($valid);

            $user->perusahaan()->create([
                'telepon_perusahaan' => $telepon,
                'nama_perusahaan' => $request->nama_perusahaan,
            ]);

            // $valid_dataperusahaan['nama_perusahaan'] = $request->username;

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
        return redirect()->route('perusahaan.dashboard');
    }



    //LOGIN FINANCE
    public function beranda_finance()
    {
        // Ambil semua transaksi cash yang sudah diterima
        $cash = CatatanCash::where('status', 'diterima')->get();

        // Total Omset = total uang masuk nyata dari transaksi cash diterima
        $totalOmset = $cash->sum('total');

        // Ambil semua transaksi koin
        $koin = CatatanKoin::all();

        // Total Transaksi Koin = jumlah absolut dari semua pergerakan koin
        $totalTransaksiKoin = $koin->sum(fn($item) => abs($item->total));

        // Ambil 5 transaksi terbaru
        $cashTerbaru = CatatanCash::orderBy('created_at', 'desc')->take(5)->get();
        $koinTerbaru = CatatanKoin::orderBy('created_at', 'desc')->take(5)->get();

        // 🔸 Ambil data notifikasi untuk transaksi cash menunggu verifikasi
        $notifikasiCash = CatatanCash::where('status', 'menunggu_verifikasi')->get();

        // 🔸 Hitung jumlah notifikasi
        $notifCount = $notifikasiCash->count();

        // Kirim semua data ke view
        return view('finance.dashboard', [
            'totalOmset' => $totalOmset,
            'totalTransaksiKoin' => $totalTransaksiKoin,
            'cash' => $cash,
            'koin' => $koin,
            'cashTerbaru' => $cashTerbaru,
            'koinTerbaru' => $koinTerbaru,
            'notifikasiCash' => $notifikasiCash,
            'notifCount' => $notifCount, // ← tambahkan ini
        ]);
    }



    public function loginproses_finance(Request $request)
    {
        $val = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($val)) {
            $request->session()->regenerate();

            // Hanya set sekali waktu login pertama
            if (!$request->session()->has('already_logged')) {
                $request->session()->put('first_login', true);
                $request->session()->put('already_logged', true);
            }

            return redirect()->route('finance.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }


    public function regis_proses_finance(Request $request)
    {
        $valid = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        $valid['password'] = Hash::make($request->password);
        $user = User::create($valid);

        $valid_datafinance = $request->validate([
            'nama_lengkap' => 'nullable',
        ]);

        $user->finance()->create($valid_datafinance);

        return response()->json([
            'success' => true,
        ]);
    }


    public function logout_finance(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }



    //LOGIN ADMIN
    public function login_admin()
    {
        return view('admin.auth.login');
    }
    public function beranda_admin()
    {
        $now = Carbon::now();

        // ===========================
        // RANGE WAKTU
        // ===========================

        // Awal bulan ini
        $startThisMonth = $now->copy()->startOfMonth();

        // Awal 3 bulan sebelumnya
        $startThreeMonthsAgo = $now->copy()->subMonths(3)->startOfMonth();

        // Akhir bulan lalu (sebelum bulan ini)
        $endLastMonth = $startThisMonth->copy()->subSecond();


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
        // KANDIDAT AKTIF
        // ===========================
        $currentKandidat = Pelamar::where('kategori', 'kandidat aktif')
            ->whereBetween('created_at', [$startThisMonth, $now])
            ->count();

        $lastKandidat = Pelamar::where('kategori', 'kandidat aktif')
            ->whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])
            ->count();

        $growthKandidat = $this->calcGrowth($lastKandidat, $currentKandidat);


        // ===========================
        // NON KANDIDAT
        // ===========================
        $currentNonKandidat = Pelamar::where('kategori', 'pelamar')
            ->whereBetween('created_at', [$startThisMonth, $now])
            ->count();

        $lastNonKandidat = Pelamar::where('kategori', 'pelamar')
            ->whereBetween('created_at', [$startThreeMonthsAgo, $endLastMonth])
            ->count();

        $growthNonKandidat = $this->calcGrowth($lastNonKandidat, $currentNonKandidat);


        // ===========================
        // LOWONGAN
        // ===========================
        $currentLowongan = LowonganPerusahaan::whereBetween('created_at', [
            $startThisMonth,
            $now
        ])->count();

        $lastLowongan = LowonganPerusahaan::whereBetween('created_at', [
            $startThreeMonthsAgo,
            $endLastMonth
        ])->count();

        $growthLowongan = $this->calcGrowth($lastLowongan, $currentLowongan);


        return view('admin.dashboard', [
            'totalPerusahaan'     => $currentPerusahaan,
            'growthPerusahaan'    => $growthPerusahaan,

            'totalKandidat'       => $currentKandidat,
            'growthKandidat'      => $growthKandidat,

            'totalNonKandidat'    => $currentNonKandidat,
            'growthNonKandidat'   => $growthNonKandidat,

            'totalLowongan'       => $currentLowongan,
            'growthLowongan'      => $growthLowongan,
        ]);
    }



    /**
     * Hitung pertumbuhan dalam persen (%)
     * @param int $last jumlah bulan lalu
     * @param int $current jumlah sekarang
     * @return float
     */
    private function calcGrowth($last, $current)
    {
        if ($last == 0 && $current > 0) return 100; // dari 0 ke ada = +100%
        if ($last == 0 && $current == 0) return 0;  // tetap 0
        return round((($current - $last) / $last) * 100, 1);
    }



    public function loginproses_admin(Request $request)
    {
        $val = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($val)) {
            $request->session()->regenerate();

            // Hanya set sekali waktu login pertama
            if (!$request->session()->has('already_logged')) {
                $request->session()->put('first_login', true);
                $request->session()->put('already_logged', true);
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function regis_admin()
    {
        return view('admin.auth.register');
    }

    public function regis_proses_admin(Request $request)
    {
        $valid = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        $valid['password'] = Hash::make($request->password);
        $user = User::create($valid);

        $valid_dataadmin = $request->validate([
            'nama_lengkap' => 'nullable',
        ]);

        $user->admin()->create($valid_dataadmin);

        return response()->json([
            'success' => true,
        ]);
    }


    public function logout_admin(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }




    //LOGIN SUPER ADMIN
    public function login_superadmin()
    {
        return view('super_admin.auth.login');
    }
    public function loginproses_superadmin(Request $request)
    {
        $val = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($val)) {
            if (Auth::user()->role == "super_admin") {
                return redirect()->route('superadmin.dashboard');
            }
        } else {
            return back();
        }
    }

    public function regis_super_admin()
    {
        return view('super_admin.auth.register');
    }

    public function regis_proses_superadmin(Request $request)
    {
        $valid = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        $valid['password'] = Hash::make($request->password);
        $user = User::create($valid);

        $valid_datasuperadmin = $request->validate([
            'nama_lengkap' => 'nullable',
        ]);

        $user->superadmin()->create($valid_datasuperadmin);

        return response()->json([
            'success' => true,
        ]);
    }

    public function logout_superadmin(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    //  NON USER
    public function login_non_user()
    {
           $socialLinks = SocialLink::all();
        return view('non-user.auth.login', compact('socialLinks'));
    }

    public function regis_non_user()
    {
        $socialLinks = SocialLink::all();
        return view('non-user.auth.register', compact('socialLinks'));
    }






    //FINANCE
    public function login_finance()
    {
        return view('finance.auth.login');
    }

    public function regis_finance()
    {
        return view('finance.auth.register');
    }

    public function verif_finance()
    {
        return view('finance.auth.verifikasi');
    }

    public function verifotp_finance()
    {
        return view('finance.auth.verif-codepw');
    }

    public function veriflupapw_finance()
    {
        return view('finance.auth.verif-lupa-sandi');
    }







    //ADMIN


    public function verif_admin()
    {
        return view('admin.auth.verif');
    }

    public function verifotp_admin()
    {
        return view('admin.auth.verif-otp');
    }

    public function veriflupapw_admin()
    {
        return view('admin.auth.verif-lupapw');
    }







    //SUPER ADMIN 




    public function verif_super_admin()
    {
        return view('super_admin.auth.verif');
    }

    public function verifotp_super_admin()
    {
        return view('super_admin.auth.verif-otp');
    }

    public function veriflupapw_super_admin()
    {
        return view('super_admin.auth.verif-lupapw');
    }





    //PERUSAHAAN
    public function login_perusahaan()
    {
        return view('perusahaan.auth.login');
    }
    public function regis_perusahaan()
    {
        return view('perusahaan.auth.register');
    }

    public function verif_perusahaan()
    {
        return view('perusahaan.auth.verif');
    }

    public function verifotp_perusahaan()
    {
        return view('perusahaan.auth.verif-otp');
    }

    public function veriflupapw_perusahaan()
    {
        return view('perusahaan.auth.verif-lupapw');
    }
}
