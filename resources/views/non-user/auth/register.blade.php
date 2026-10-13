<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | AreaKerja</title>
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

        <!-- ================= PANEL KIRI (FORM REGISTER) ================= -->
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
                <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#00509d] mb-4">Buat Akun</h2>

                <!-- Tombol Otentikasi Sosial (Google, Facebook, GitHub) -->
                <div class="flex items-center justify-center gap-3 mb-5">
                    <!-- Google -->
                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}?role=pelamar" title="Daftar dengan Google"
                        class="social-auth-btn w-11 h-11 flex items-center justify-center border border-slate-200 bg-white rounded-full hover:border-[#00509d] hover:bg-slate-50 transition shadow-sm hover:scale-105 duration-200" data-provider="google">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}?role=pelamar" title="Daftar dengan Facebook"
                        class="social-auth-btn w-11 h-11 flex items-center justify-center border border-slate-200 bg-white rounded-full hover:border-blue-600 hover:bg-slate-50 transition shadow-sm hover:scale-105 duration-200" data-provider="facebook">
                        <svg class="w-5 h-5 text-[#1877F2]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>

                    <!-- GitHub -->
                    <a href="{{ route('social.redirect', ['provider' => 'github']) }}?role=pelamar" title="Daftar dengan GitHub"
                        class="social-auth-btn w-11 h-11 flex items-center justify-center border border-slate-200 bg-white rounded-full hover:border-slate-800 hover:bg-slate-50 transition shadow-sm hover:scale-105 duration-200 text-slate-800" data-provider="github">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                        </svg>
                    </a>
                </div>

                <!-- Role Switcher Pill -->
                <div class="flex justify-center mb-6">
                    <div class="bg-slate-100 rounded-full p-1 flex space-x-1 border border-slate-200">
                        <button type="button" id="btn_pelamar"
                            class="bg-[#00509d] text-white px-7 py-2 rounded-full text-xs font-bold transition shadow-xs cursor-pointer">
                            Pelamar
                        </button>
                        <button type="button" id="btn_perusahaan"
                            class="bg-transparent text-slate-600 px-7 py-2 rounded-full text-xs font-bold transition hover:text-slate-900 cursor-pointer">
                            Perusahaan
                        </button>
                    </div>
                </div>

                @if (session('error'))
                    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs text-center font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- ====================== FORM PELAMAR ====================== --}}
                <div id="regis_pelamar">
                    <form id="registerForm" action="{{ route('registerproses') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Pengguna</label>
                            <input type="text" name="username" placeholder="Nama Pengguna" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="username"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" placeholder="email@contoh.com" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="email"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">No. Tlp / WhatsApp</label>
                            <input type="text" name="telepon_pelamar" placeholder="08xxxxxxxxxx" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="telepon_pelamar"></p>
                        </div>

                        <div x-data="{ showPass: false }">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                            <div class="relative flex items-center">
                                <input :type="showPass ? 'text' : 'password'" name="password" placeholder="Kata Sandi" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition pr-11">
                                <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer">
                                    <i :class="showPass ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-xl leading-none"></i>
                                </button>
                            </div>
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="password"></p>
                        </div>

                        <label class="flex items-start text-xs font-medium gap-2 cursor-pointer pt-1">
                            <input type="checkbox" id="agree_pelamar" name="agree_pelamar" required class="mt-0.5 rounded text-[#00509d] focus:ring-[#00509d]">
                            <span class="text-slate-600">Saya menyetujui <button type="button" onclick="openTermsModal('pelamar')" class="text-[#00509d] font-bold hover:underline cursor-pointer">Syarat dan Ketentuan</button> yang berlaku</span>
                        </label>
                        <p class="error-message text-rose-500 text-xs mt-0.5" data-field="agree_pelamar"></p>

                        <input type="hidden" name="role" value="pelamar">

                        <button type="submit"
                            class="w-full py-3.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl text-sm uppercase tracking-wider transition shadow-md cursor-pointer mt-4">
                            DAFTAR
                        </button>
                    </form>
                </div>

                {{-- ====================== FORM PERUSAHAAN ====================== --}}
                <div id="regis_perusahaan" class="hidden">
                    <form id="register_perusahaanForm" action="{{ route('registerproses_perusahaan') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" placeholder="Nama Perusahaan" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="nama_perusahaan"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Perusahaan</label>
                            <input type="email" name="email" placeholder="hrd@perusahaan.com" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="email"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">No. Tlp Perusahaan</label>
                            <input type="text" name="telepon_perusahaan" placeholder="08xxxxxxxxxx" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="telepon_perusahaan"></p>
                        </div>

                        <div x-data="{ showPass: false }">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                            <div class="relative flex items-center">
                                <input :type="showPass ? 'text' : 'password'" name="password" placeholder="Kata Sandi" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition pr-11">
                                <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer">
                                    <i :class="showPass ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-xl leading-none"></i>
                                </button>
                            </div>
                            <p class="text-rose-500 text-xs mt-1 error-message" data-field="password"></p>
                        </div>

                        <label class="flex items-start text-xs font-medium gap-2 cursor-pointer pt-1">
                            <input type="checkbox" id="agree_perusahaan" name="agree_perusahaan" required class="mt-0.5 rounded text-[#00509d] focus:ring-[#00509d]">
                            <span class="text-slate-600">Saya menyetujui <button type="button" onclick="openTermsModal('perusahaan')" class="text-[#00509d] font-bold hover:underline cursor-pointer">Syarat dan Ketentuan</button> yang berlaku</span>
                        </label>
                        <p class="error-message text-rose-500 text-xs mt-0.5" data-field="agree_perusahaan"></p>

                        <input type="hidden" name="role" value="perusahaan">

                        <button type="submit"
                            class="w-full py-3.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl text-sm uppercase tracking-wider transition shadow-md cursor-pointer mt-4">
                            DAFTAR
                        </button>
                    </form>
                </div>

                <p class="text-center text-xs text-slate-600 pt-4">
                    Sudah Memiliki Akun?
                    <a href="{{ url('/login') }}" class="text-[#00509d] font-bold hover:underline">
                        Masuk Sekarang
                    </a>
                </p>

            </div>
        </div>

        <!-- ================= PANEL KANAN (BANNER DESKTOP) ================= -->
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
                <a href="{{ url('/login') }}"
                    class="inline-block px-14 py-3 border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-slate-900 transition duration-300 text-sm tracking-wider uppercase shadow-md">
                    MASUK
                </a>
            </div>

            <!-- Bottom Spacer -->
            <div class="relative z-10 text-xs text-white/50 text-center">
                © {{ date('Y') }} AreaKerja. All rights reserved.
            </div>
        </section>

    </div>

    {{-- =============== MODAL SYARAT DAN KETENTUAN =============== --}}
    <div id="termsModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden animate-fadeIn">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <i class="ph ph-file-text text-[#00509d] text-xl font-bold"></i>
                    <h3 class="font-bold text-slate-800 text-base sm:text-lg">Syarat &amp; Ketentuan Penggunaan</h3>
                </div>
                <button type="button" onclick="closeTermsModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none cursor-pointer">&times;</button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed text-justify">
                <p>
                    Dokumen Syarat dan Ketentuan Penggunaan ("S&amp;K") ini menyatakan hak, kewajiban, dan ketentuan yang perlu Anda setujui untuk dapat menggunakan layanan, fitur, dan platform <strong>AreaKerja</strong>. Harap baca dengan seksama sebelum melakukan registrasi.
                </p>

                <h4 class="font-bold text-slate-900 text-sm">1. Pihak yang Terlibat</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>AreaKerja</strong>, sebagai penyedia platform bursa kerja, manajemen talenta, dan perantara rekrutmen.</li>
                    <li><strong>Pencari Kerja (Pelamar/Kandidat)</strong>, yaitu individu yang mencari informasi dan melamar lowongan kerja.</li>
                    <li><strong>Perusahaan (Mitra Employer)</strong>, yaitu entitas/organisasi yang mempublikasikan lowongan kerja dan merekrut talenta.</li>
                </ul>

                <h4 class="font-bold text-slate-900 text-sm">2. Ketentuan Akun &amp; Keamanan</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Pengguna wajib berusia minimal 18 tahun atau memiliki izin yang sah sesuai hukum yang berlaku.</li>
                    <li>Data yang dimasukkan saat pendaftaran harus valid, akurat, dan dapat dipertanggungjawabkan.</li>
                    <li>Pengguna bertanggung jawab penuh atas kerahasiaan kata sandi dan seluruh aktivitas akun.</li>
                    <li>Dilarang membuat akun palsu atau mengatasnamakan entitas lain tanpa wewenang legal.</li>
                </ul>

                <h4 class="font-bold text-slate-900 text-sm">3. Etika &amp; Larangan Penggunaan</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Dilarang menyalahgunakan data pelamar atau perusahaan untuk penipuan, pungutan liar, atau tindakan melanggar hukum.</li>
                    <li>AreaKerja berhak membatasi, membekukan, atau menghapus akun yang melanggar ketentuan tanpa pemberitahuan sebelumnya.</li>
                </ul>

                <h4 class="font-bold text-slate-900 text-sm">4. Privasi &amp; Data Pribadi</h4>
                <p>
                    Data pribadi Anda akan diproses sesuai Kebijakan Privasi AreaKerja dan hanya digunakan untuk keperluan proses rekrutmen, penelusuran lowongan, serta notifikasi resmi platform.
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" onclick="closeTermsModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-100 transition cursor-pointer">
                    Tutup
                </button>
                <button type="button" onclick="acceptTermsModal()" class="px-5 py-2.5 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white font-bold text-xs sm:text-sm transition shadow-sm cursor-pointer flex items-center gap-1.5">
                    <i class="ph ph-check-circle text-base"></i>
                    <span>Saya Paham &amp; Setuju</span>
                </button>
            </div>
        </div>
    </div>

    {{-- =============== MODAL PELAMAR =============== --}}
    <div id="successModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center">
            <button onclick="closeModal()" class="absolute top-3 right-3 text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            <div class="w-16 h-16 bg-blue-50 text-[#00509d] rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl font-bold">
                <i class="ph ph-check-circle"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-1">Selamat!</h2>
            <p class="text-sm font-semibold text-[#00509d] mb-2">Akun anda berhasil dibuat</p>
            <p class="text-xs text-slate-500 mb-6">Silakan masuk untuk melanjutkan ke AreaKerja.</p>
            <button id="goLogin"
                class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-3 rounded-xl transition text-sm shadow-md">
                Masuk
            </button>
        </div>
    </div>

    {{-- =============== MODAL PERUSAHAAN =============== --}}
    <div id="successModal_perusahaan" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center">
            <button onclick="closeModal()" class="absolute top-3 right-3 text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            <div class="w-16 h-16 bg-blue-50 text-[#00509d] rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl font-bold">
                <i class="ph ph-buildings"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-1">Selamat!</h2>
            <p class="text-sm font-semibold text-[#00509d] mb-2">Akun perusahaan berhasil dibuat</p>
            <p class="text-xs text-slate-500 mb-6">Silakan masuk untuk melanjutkan pasang lowongan.</p>
            <button id="gooLogin"
                class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-3 rounded-xl transition text-sm shadow-md">
                Masuk
            </button>
        </div>
    </div>

    {{-- SCRIPT TOGGLE FORM --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btnPelamar = document.getElementById("btn_pelamar");
            const btnPerusahaan = document.getElementById("btn_perusahaan");
            const regisPelamar = document.getElementById("regis_pelamar");
            const regisPerusahaan = document.getElementById("regis_perusahaan");

            function updateSocialLinksRole(role) {
                document.querySelectorAll('.social-auth-btn').forEach(btn => {
                    let provider = btn.getAttribute('data-provider');
                    btn.href = `/auth/${provider}/redirect?role=${role}`;
                });
            }

            btnPelamar.addEventListener("click", () => {
                regisPelamar.classList.remove("hidden");
                regisPerusahaan.classList.add("hidden");
                btnPelamar.className = "bg-[#00509d] text-white px-7 py-2 rounded-full text-xs font-bold transition shadow-xs cursor-pointer";
                btnPerusahaan.className = "bg-transparent text-slate-600 px-7 py-2 rounded-full text-xs font-bold transition hover:text-slate-900 cursor-pointer";
                updateSocialLinksRole('pelamar');
            });

            btnPerusahaan.addEventListener("click", () => {
                regisPerusahaan.classList.remove("hidden");
                regisPelamar.classList.add("hidden");
                btnPerusahaan.className = "bg-[#00509d] text-white px-7 py-2 rounded-full text-xs font-bold transition shadow-xs cursor-pointer";
                btnPelamar.className = "bg-transparent text-slate-600 px-7 py-2 rounded-full text-xs font-bold transition hover:text-slate-900 cursor-pointer";
                updateSocialLinksRole('perusahaan');
            });
        });

        // FETCH REGISTER PERUSAHAAN
        document.getElementById("register_perusahaanForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            document.querySelectorAll("#register_perusahaanForm .error-message").forEach(el => el.textContent = "");

            if (!document.getElementById("agree_perusahaan").checked) {
                document.querySelector(`#register_perusahaanForm .error-message[data-field="agree_perusahaan"]`)
                    .textContent = "Anda harus menyetujui syarat dan ketentuan.";
                return;
            }

            let formData = new FormData(this);

            try {
                let response = await fetch(this.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": this.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        document.getElementById("successModal_perusahaan").classList.remove("hidden");
                        document.getElementById("successModal_perusahaan").classList.add("flex");
                    }
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    Object.keys(errorData.errors).forEach(field => {
                        const el = document.querySelector(
                            `#register_perusahaanForm .error-message[data-field="${field}"]`
                        );
                        if (el) el.textContent = errorData.errors[field][0];
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: 'Terjadi kesalahan server.', confirmButtonText: 'Mengerti' });
                }

            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Gagal menghubungi server. Coba lagi.', confirmButtonText: 'Mengerti' });
            }
        });

        // FETCH REGISTER PELAMAR
        document.getElementById("registerForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            document.querySelectorAll("#registerForm .error-message").forEach(el => el.textContent = "");

            if (!document.getElementById("agree_pelamar").checked) {
                document.querySelector(`#registerForm .error-message[data-field="agree_pelamar"]`)
                    .textContent = "Anda harus menyetujui syarat dan ketentuan.";
                return;
            }

            let formData = new FormData(this);

            try {
                let response = await fetch(this.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": this.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        document.getElementById("successModal").classList.remove("hidden");
                        document.getElementById("successModal").classList.add("flex");
                    }
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    Object.keys(errorData.errors).forEach(field => {
                        const el = document.querySelector(
                            `#registerForm .error-message[data-field="${field}"]`
                        );
                        if (el) el.textContent = errorData.errors[field][0];
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: 'Terjadi kesalahan server.', confirmButtonText: 'Mengerti' });
                }

            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Koneksi Gagal', text: 'Gagal menghubungi server. Coba lagi.', confirmButtonText: 'Mengerti' });
            }
        });

        document.getElementById("goLogin")?.addEventListener("click", function() {
            window.location.href = "{{ url('/login') }}";
        });

        document.getElementById("gooLogin")?.addEventListener("click", function() {
            window.location.href = "{{ url('/login') }}";
        });

        function closeModal() {
            document.getElementById("successModal").classList.add("hidden");
            document.getElementById("successModal").classList.remove("flex");
            document.getElementById("successModal_perusahaan").classList.add("hidden");
            document.getElementById("successModal_perusahaan").classList.remove("flex");
        }

        let currentTermsTarget = 'agree_pelamar';

        function openTermsModal(target) {
            currentTermsTarget = target === 'perusahaan' ? 'agree_perusahaan' : 'agree_pelamar';
            const modal = document.getElementById('termsModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeTermsModal() {
            const modal = document.getElementById('termsModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function acceptTermsModal() {
            const cb = document.getElementById(currentTermsTarget) || document.getElementById('agree_pelamar');
            if (cb) {
                cb.checked = true;
            }
            const err = document.querySelector(`.error-message[data-field="${currentTermsTarget}"]`);
            if (err) err.textContent = '';
            closeTermsModal();
        }
    </script>
</body>

</html>
