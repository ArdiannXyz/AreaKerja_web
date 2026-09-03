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

                <!-- Tombol Otentikasi Sosial (Google, Facebook, LinkedIn) -->
                <div class="flex space-x-4 mb-4 justify-center">
                    <!-- Google -->
                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}" title="Masuk dengan Google"
                        class="w-11 h-11 flex items-center justify-center border border-slate-300 rounded-full hover:border-[#00509d] hover:bg-blue-50 transition shadow-xs text-slate-700 font-bold">
                        <span class="text-lg font-bold font-sans">G</span>
                    </a>

                    <!-- Facebook -->
                    <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" title="Masuk dengan Facebook"
                        class="w-11 h-11 flex items-center justify-center border border-slate-300 rounded-full hover:border-blue-600 hover:bg-blue-50 transition shadow-xs text-slate-700 font-bold">
                        <span class="text-lg font-bold font-sans">f</span>
                    </a>

                    <!-- LinkedIn -->
                    <a href="{{ route('social.redirect', ['provider' => 'linkedin']) }}" title="Masuk dengan LinkedIn"
                        class="w-11 h-11 flex items-center justify-center border border-slate-300 rounded-full hover:border-blue-700 hover:bg-blue-50 transition shadow-xs text-slate-700 font-bold">
                        <span class="text-base font-bold font-sans">in</span>
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

                                <!-- Perusahaan 1 -->
                                <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan1@areakerja.test', 'password123', 'PT AreaKerja Tech')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-buildings text-blue-600"></i> Perusahaan 1
                                        </div>
                                        <div class="text-[10px] text-blue-700 font-mono truncate">perusahaan1@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Pelamar 1 -->
                                <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar1@areakerja.test', 'password123', 'Pelamar 1')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-white hover:bg-teal-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-user text-teal-600"></i> Pelamar 1
                                        </div>
                                        <div class="text-[10px] text-teal-700 font-mono truncate">pelamar1@areakerja.test</div>
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold shrink-0">pwd123</span>
                                </button>

                                <!-- Kandidat 1 -->
                                <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat1@areakerja.test', 'password123', 'Kandidat 1')"
                                    class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 transition text-left group cursor-pointer">
                                    <div class="truncate mr-2">
                                        <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                            <i class="ph ph-star text-rose-600"></i> Kandidat 1
                                        </div>
                                        <div class="text-[10px] text-rose-700 font-mono truncate">kandidat1@areakerja.test</div>
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
