<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Masuk | AreaKerja</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_biru.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.sweetalert-theme')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- ================= PANEL KIRI (BANNER DESKTOP) ================= -->
        <section class="relative lg:w-1/2 hidden lg:flex flex-col justify-between overflow-hidden bg-slate-900 text-white p-12">
            <!-- Background Image -->
            <img src="{{ asset('images/auth_team.png') }}" alt="Team Background"
                class="absolute inset-0 w-full h-full object-cover">

            <!-- Subtle Overlay -->
            <div class="absolute inset-0 bg-black/20"></div>



            <!-- Center Content -->
            <div class="relative z-10 text-center max-w-md mx-auto my-auto py-12">
                <h1 class="text-3xl sm:text-4xl font-bold mb-4 tracking-tight leading-tight">Hallo, Pekerja</h1>
                <p class="text-sm text-white/90 mb-8 leading-relaxed">
                    untuk tetap terhubung dengan kami, silakan masuk dengan informasi pribadi Anda
                </p>
                <a href="{{ url('/register') }}"
                    class="inline-block px-14 py-3 border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-slate-900 transition duration-300 text-sm tracking-wider uppercase shadow-md">
                    DAFTAR
                </a>
            </div>

            <!-- Bottom Spacer -->
            <div class="relative z-10 text-xs text-white/50 text-center">
                © {{ date('Y') }} AreaKerja. All rights reserved.
            </div>
        </section>

        <!-- ================= PANEL KANAN (FORM LOGIN) ================= -->
        <div class="flex w-full lg:w-1/2 bg-white items-center justify-center min-h-screen py-10 px-6 sm:px-12">

            <div class="w-full max-w-md flex flex-col justify-center">

                <!-- Header Navigasi -->
                <div class="flex items-center justify-start mb-6">
                    <a href="{{ route('beranda') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#00509d] px-2.5 py-1.5 rounded-lg hover:bg-blue-50/60 transition"
                        title="Kembali ke Halaman Utama">
                        <i class="ph ph-arrow-left font-bold text-sm"></i>
                        <span>Beranda</span>
                    </a>
                </div>

                <!-- Judul -->
                <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#00509d] mb-4">Masuk</h2>

                <!-- Tombol Otentikasi Sosial (Google, Facebook) -->
                <div class="flex items-center justify-center gap-3 mb-5">
                    <!-- Google -->
                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}" title="Masuk dengan Google"
                        class="w-11 h-11 flex items-center justify-center border border-slate-200 bg-white rounded-full hover:border-[#00509d] hover:bg-slate-50 transition shadow-sm hover:scale-105 duration-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" title="Masuk dengan Facebook"
                        class="w-11 h-11 flex items-center justify-center border border-slate-200 bg-white rounded-full hover:border-blue-600 hover:bg-slate-50 transition shadow-sm hover:scale-105 duration-200">
                        <svg class="w-5 h-5 text-[#1877F2]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>

                <p class="text-center text-slate-500 mb-6 text-xs sm:text-sm">
                    gunakan email Anda untuk pendaftaran
                </p>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs text-center font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs text-center font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs text-center font-medium">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('loginproses') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition" />
                    </div>

                    <div x-data="{ showPass: false }">
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                        <div class="relative flex items-center">
                            <input :type="showPass ? 'text' : 'password'" id="password" name="password" required placeholder="Kata Sandi"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition pr-11" />
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer">
                                <i :class="showPass ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-xl leading-none"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-xs pt-1">
                        <label class="flex items-center text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="mr-2 rounded text-[#00509d] focus:ring-[#00509d]"> Ingat saya
                        </label>
                        <a href="{{ route('verifikasi_pelamar') }}" class="text-[#00509d] hover:underline font-semibold">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-3.5 rounded-xl shadow-md transition duration-200 text-sm tracking-wider uppercase">
                            MASUK
                        </button>
                    </div>

                    <p class="text-center text-xs text-slate-600 pt-2">
                        Tidak Memiliki Akun?
                        <a href="{{ url('/register') }}" class="text-[#00509d] font-bold hover:underline">
                            Daftar Sekarang
                        </a>
                    </p>

                    <!-- TOGGLE TRIGGER LIST AKUN DEMO -->
                    <div class="text-center pt-3" x-data="{ showDemo: false, tab: 'all' }">
                        <button type="button" @click="showDemo = !showDemo"
                            class="text-xs font-semibold text-slate-400 hover:text-[#00509d] inline-flex items-center gap-1.5 transition cursor-pointer px-3 py-1 rounded-full hover:bg-slate-100">
                            <i class="ph ph-lightning text-amber-500"></i>
                            <span>List Akun Demo</span>
                            <i :class="showDemo ? 'ph ph-caret-up' : 'ph ph-caret-down'" class="text-xs"></i>
                        </button>

                        <!-- COLLAPSIBLE DEMO ACCOUNTS HELPER BOX -->
                        <div x-show="showDemo" x-transition x-cloak class="mt-4 p-4 rounded-2xl border border-slate-200 bg-slate-50/90 text-left shadow-md">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800 uppercase tracking-wider">
                                    <i class="ph ph-lightning text-amber-500 text-base"></i> Akun Demo Cepat
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium">Klik untuk auto-fill</span>
                            </div>

                            <!-- Filter Tabs -->
                            <div class="flex items-center gap-1 overflow-x-auto pb-2 mb-3 text-[11px] font-bold no-scrollbar">
                                <button type="button" @click="tab = 'all'" :class="tab === 'all' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Semua</button>
                                <button type="button" @click="tab = 'admin'" :class="tab === 'admin' ? 'bg-purple-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Admin (3)</button>
                                <button type="button" @click="tab = 'perusahaan'" :class="tab === 'perusahaan' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Perusahaan (6)</button>
                                <button type="button" @click="tab = 'pelamar'" :class="tab === 'pelamar' ? 'bg-teal-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Pelamar (6)</button>
                                <button type="button" @click="tab = 'kandidat'" :class="tab === 'kandidat' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Kandidat (6)</button>
                            </div>

                            <!-- List Cards (Scrollable) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs max-h-52 overflow-y-auto pr-1">

                                <!-- Super Admin -->
                                <button type="button" x-show="tab === 'all' || tab === 'admin'" onclick="fillDemo('superadmin@gmail.com', '123', 'Super Admin')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-purple-200 bg-white hover:bg-purple-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-purple-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-crown text-purple-600"></i> Super Admin
                                        </div>
                                        <div class="text-[10px] text-purple-700 font-mono truncate">superadmin@gmail.com</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-purple-100 text-purple-800 font-bold shrink-0">123</span>
                                </button>

                                <!-- Admin -->
                                <button type="button" x-show="tab === 'all' || tab === 'admin'" onclick="fillDemo('admin@gmail.com', '123', 'Admin Operasional')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-shield-check text-blue-600"></i> Admin
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">admin@gmail.com</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">123</span>
                                </button>

                                <!-- Finance -->
                                <button type="button" x-show="tab === 'all' || tab === 'admin'" onclick="fillDemo('finance@gmail.com', '123', 'Finance Manager')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-emerald-200 bg-white hover:bg-emerald-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-emerald-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-wallet text-emerald-600"></i> Finance
                                        </div>
                                        <div class="text-[10px] text-emerald-700 font-mono truncate">finance@gmail.com</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold shrink-0">123</span>
                                </button>

                                <!-- ================= PERUSAHAAN (1-6) ================= -->
                                <!-- Perusahaan 1 -->
                                <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan1@areakerja.test', 'password123', 'PT AreaKerja Tech')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-buildings text-blue-600"></i> Perusahaan 1 (Tech)
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">perusahaan1@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Perusahaan 2 -->
                                <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan2@areakerja.test', 'password123', 'PT Nusantara Digital')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-buildings text-blue-600"></i> Perusahaan 2 (Agency)
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">perusahaan2@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Perusahaan 3 -->
                                <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan3@areakerja.test', 'password123', 'PT Inovasi Karya Media')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-buildings text-blue-600"></i> Perusahaan 3 (Desain)
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">perusahaan3@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Perusahaan 4 -->
                                <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan4@areakerja.test', 'password123', 'PT Mitra Sejahtera Abadi')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-buildings text-blue-600"></i> Perusahaan 4 (Finance)
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">perusahaan4@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Perusahaan 5 -->
                                <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan5@areakerja.test', 'password123', 'PT Techindo Cloud')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-buildings text-blue-600"></i> Perusahaan 5 (DevOps)
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">perusahaan5@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Perusahaan 6 -->
                                <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan6@areakerja.test', 'password123', 'PT Sukses Gemilang')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-buildings text-blue-600"></i> Perusahaan 6 (Logistik)
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">perusahaan6@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- ================= PELAMAR (1-6) ================= -->
                                <!-- Pelamar 1 -->
                                <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar1@areakerja.test', 'password123', 'Budi Santoso (Backend)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-white hover:bg-teal-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-user text-teal-600"></i> Pelamar 1 (Budi)
                                        </div>
                                        <div class="text-[10px] text-teal-700 font-mono truncate">pelamar1@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Pelamar 2 -->
                                <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar2@areakerja.test', 'password123', 'Ahmad Rizky (Frontend)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-white hover:bg-teal-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-user text-teal-600"></i> Pelamar 2 (Ahmad)
                                        </div>
                                        <div class="text-[10px] text-teal-700 font-mono truncate">pelamar2@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Pelamar 3 -->
                                <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar3@areakerja.test', 'password123', 'Dewi Lestari (UI/UX)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-white hover:bg-teal-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-user text-teal-600"></i> Pelamar 3 (Dewi)
                                        </div>
                                        <div class="text-[10px] text-teal-700 font-mono truncate">pelamar3@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Pelamar 4 -->
                                <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar4@areakerja.test', 'password123', 'Fajar Pratama (Data)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-white hover:bg-teal-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-user text-teal-600"></i> Pelamar 4 (Fajar)
                                        </div>
                                        <div class="text-[10px] text-teal-700 font-mono truncate">pelamar4@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Pelamar 5 -->
                                <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar5@areakerja.test', 'password123', 'Rina Indah (Marketing)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-white hover:bg-teal-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-user text-teal-600"></i> Pelamar 5 (Rina)
                                        </div>
                                        <div class="text-[10px] text-teal-700 font-mono truncate">pelamar5@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Pelamar 6 -->
                                <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar6@areakerja.test', 'password123', 'Eko Prasetyo (Finance)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-white hover:bg-teal-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-user text-teal-600"></i> Pelamar 6 (Eko)
                                        </div>
                                        <div class="text-[10px] text-teal-700 font-mono truncate">pelamar6@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- ================= KANDIDAT (1-6) ================= -->
                                <!-- Kandidat 1 -->
                                <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat1@areakerja.test', 'password123', 'Siti Rahayu (Flutter/Vue)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-star text-rose-600"></i> Kandidat 1 (Siti)
                                        </div>
                                        <div class="text-[10px] text-rose-700 font-mono truncate">kandidat1@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Kandidat 2 -->
                                <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat2@areakerja.test', 'password123', 'Doni Kurniawan (Java/Docker)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-star text-rose-600"></i> Kandidat 2 (Doni)
                                        </div>
                                        <div class="text-[10px] text-rose-700 font-mono truncate">kandidat2@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Kandidat 3 -->
                                <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat3@areakerja.test', 'password123', 'Maya Kartika (HR/Psikologi)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-star text-rose-600"></i> Kandidat 3 (Maya)
                                        </div>
                                        <div class="text-[10px] text-rose-700 font-mono truncate">kandidat3@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Kandidat 4 -->
                                <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat4@areakerja.test', 'password123', 'Agus Wijaya (Mobile/Flutter)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-star text-rose-600"></i> Kandidat 4 (Agus)
                                        </div>
                                        <div class="text-[10px] text-rose-700 font-mono truncate">kandidat4@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Kandidat 5 -->
                                <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat5@areakerja.test', 'password123', 'Nabila Putri (Social Media)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-star text-rose-600"></i> Kandidat 5 (Nabila)
                                        </div>
                                        <div class="text-[10px] text-rose-700 font-mono truncate">kandidat5@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Kandidat 6 -->
                                <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat6@areakerja.test', 'password123', 'Hendra Saputra (Network)')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-star text-rose-600"></i> Kandidat 6 (Hendra)
                                        </div>
                                        <div class="text-[10px] text-rose-700 font-mono truncate">kandidat6@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold shrink-0">pwd123</span>
                                </button>

                            </div>
                        </div>
                    </div>

            </div>

        </div>

    </div>

    <script>
        function fillDemo(email, password, label) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = password;
                emailInput.classList.add('ring-2', 'ring-[#00509d]');
                setTimeout(() => emailInput.classList.remove('ring-2', 'ring-[#00509d]'), 600);
            }
        }
    </script>
</body>

</html>
