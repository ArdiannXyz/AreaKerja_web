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

                    </form>
            </div>

        </div>

    </div>
</body>


</html>
