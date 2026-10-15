<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Areakerja</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_biru.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">


    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">

        {{-- Background dengan overlay  --}}
        <section class="relative h-screen w-2/4">
            <img src="{{ asset('images/gambar2.jpg') }}" alt="Background"
                class="absolute inset-0 w-full h-full object-cover">

            <!-- Overlay hitam transparan -->
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>

            <!-- Konten -->
            <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-6">

                <!-- Logo -->
                <div class="absolute top-6 left-6 flex items-center">
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="Logo" class="h-12 w-12">
                    <span class="font-semibold mb-1">areakerja.com</span>
                </div>

                <!-- Text -->
                <h1 class="text-3xl font-bold mt-[-45%] mb-10">Hallo, Pekerja</h1>
                <p class="text-sm mb-10">untuk tetap terhubung dengan kami, silakan<br> masuk dengan informasi pribadi
                    Anda</p>

                <!-- Button -->
                <a href="{{ url('/register') }}"
                    class="px-20 py-4 border border-white rounded-full hover:bg-white hover:text-black transition">
                    DAFTAR
                </a>
            </div>
        </section>



        <!-- Kanan -->
        <div class="flex w-full md:w-4/5 bg-white items-center justify-center">
            <div class="w-full max-w-md p-8">
                <h2 class="text-2xl font-bold text-center text-[#003d7a] mb-6">Masuk</h2>

                <!-- Login Sosial -->
                <div class="flex items-center justify-center gap-3 mb-6">
                    <a href="{{ route('social.redirect', ['provider' => 'google', 'role' => 'perusahaan']) }}" title="Masuk dengan Google"
                        class="w-10 h-10 flex items-center justify-center border border-gray-200 bg-white rounded-full hover:bg-gray-50 transition shadow-sm hover:scale-105 duration-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                    </a>

                    <a href="{{ route('social.redirect', ['provider' => 'facebook', 'role' => 'perusahaan']) }}" title="Masuk dengan Facebook"
                        class="w-10 h-10 flex items-center justify-center border border-gray-200 bg-white rounded-full hover:bg-gray-50 transition shadow-sm hover:scale-105 duration-200">
                        <svg class="w-5 h-5 text-[#1877F2]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>

                <p class="text-center text-gray-500 mb-6 mt-6 text-sm">gunakan email Anda untuk pendaftaran</p>

                <!-- Form Login -->
                <!-- Alert -->
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm text-center">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm text-center">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="{{ route('loginproses') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Perusahaan</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="perusahaan@contoh.com"
                            class="mt-2 block w-full border border-gray-700 rounded-lg p-2.5 focus:ring-[#00509d] focus:border-[#00509d]" />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                        <input type="password" id="password" name="password" required placeholder="Kata Sandi"
                            class="mt-2 block w-full border border-gray-700 rounded-lg p-2.5 focus:ring-[#00509d] focus:border-[#00509d]" />
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2 border rounded-sm"> Ingat saya
                        </label>
                        <a href="{{ route('verifikasi_perusahaan') }}" class="text-[#00509d] hover:underline">Lupa kata sandi?</a>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit"
                            class="w-52 h-14 bg-[#00509d] text-white py-2.5 rounded-full font-small text-sm hover:bg-[#003d7a] transition">
                            MASUK
                        </button>
                    </div>
                    <p class="text-center text-sm mt-4">Tidak Memiliki Akun? <a href="#"
                            class="text-[#00509d] font-medium"> Daftar
                            Sekarang</a></p>
                </form>

                <!-- DEMO ACCOUNTS HELPER BOX -->
                <div class="mt-8 pt-6 border-t-2 border-dashed border-slate-200" x-data="{ tab: 'all' }">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800 uppercase tracking-wider">
                            <i class="ph ph-lightning text-amber-500 text-base"></i> Akun Demo Cepat
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Klik untuk auto-fill form</span>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="flex items-center gap-1 overflow-x-auto pb-2 mb-3 text-[11px] font-bold no-scrollbar">
                        <button type="button" @click="tab = 'all'" :class="tab === 'all' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Semua</button>
                        <button type="button" @click="tab = 'admin'" :class="tab === 'admin' ? 'bg-purple-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Admin/Staff (3)</button>
                        <button type="button" @click="tab = 'perusahaan'" :class="tab === 'perusahaan' ? 'bg-[#003d7a] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Perusahaan (6)</button>
                        <button type="button" @click="tab = 'pelamar'" :class="tab === 'pelamar' ? 'bg-teal-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Pelamar (6)</button>
                        <button type="button" @click="tab = 'kandidat'" :class="tab === 'kandidat' ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-lg transition shrink-0">Kandidat (6)</button>
                    </div>

                    <!-- List Cards (Scrollable) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs max-h-72 overflow-y-auto pr-1">

                        <!-- ================= ADMIN / STAFF ================= -->
                        <!-- Super Admin -->
                        <button type="button" x-show="tab === 'all' || tab === 'admin'" onclick="fillDemo('superadmin@gmail.com', '123', 'Super Admin')"
                            class="flex items-center justify-between p-2 rounded-xl border border-purple-200 bg-purple-50/50 hover:bg-purple-100/80 hover:border-purple-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-purple-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-crown text-purple-600"></i> Super Admin
                                </div>
                                <div class="text-[10px] text-purple-700 font-mono truncate">superadmin@gmail.com</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-purple-200 text-purple-800 font-bold group-hover:scale-105 transition shrink-0">123</span>
                        </button>

                        <!-- Admin -->
                        <button type="button" x-show="tab === 'all' || tab === 'admin'" onclick="fillDemo('admin@gmail.com', '123', 'Admin Operasional')"
                            class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-100/80 hover:border-blue-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-blue-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-shield-check text-blue-600"></i> Admin
                                </div>
                                <div class="text-[10px] text-blue-700 font-mono truncate">admin@gmail.com</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-200 text-blue-800 font-bold group-hover:scale-105 transition shrink-0">123</span>
                        </button>

                        <!-- Finance -->
                        <button type="button" x-show="tab === 'all' || tab === 'admin'" onclick="fillDemo('finance@gmail.com', '123', 'Finance Manager')"
                            class="flex items-center justify-between p-2 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-100/80 hover:border-emerald-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-emerald-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-wallet text-emerald-600"></i> Finance
                                </div>
                                <div class="text-[10px] text-emerald-700 font-mono truncate">finance@gmail.com</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-200 text-emerald-800 font-bold group-hover:scale-105 transition shrink-0">123</span>
                        </button>

                        <!-- ================= PERUSAHAAN (1-6) ================= -->
                        <!-- Perusahaan 1 -->
                        <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan1@areakerja.test', 'password123', 'PT AreaKerja Tech')"
                            class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-100/80 hover:border-blue-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-orange-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-buildings text-[#003d7a]"></i> Perusahaan 1 (Tech)
                                </div>
                                <div class="text-[10px] text-[#003d7a] font-mono truncate">perusahaan1@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-200 text-orange-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Perusahaan 2 -->
                        <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan2@areakerja.test', 'password123', 'PT Nusantara Digital')"
                            class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-100/80 hover:border-blue-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-orange-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-buildings text-[#003d7a]"></i> Perusahaan 2 (Agency)
                                </div>
                                <div class="text-[10px] text-[#003d7a] font-mono truncate">perusahaan2@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-200 text-orange-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Perusahaan 3 -->
                        <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan3@areakerja.test', 'password123', 'PT Inovasi Karya Media')"
                            class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-100/80 hover:border-blue-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-orange-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-buildings text-[#003d7a]"></i> Perusahaan 3 (Desain)
                                </div>
                                <div class="text-[10px] text-[#003d7a] font-mono truncate">perusahaan3@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-200 text-orange-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Perusahaan 4 -->
                        <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan4@areakerja.test', 'password123', 'PT Mitra Sejahtera Abadi')"
                            class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-100/80 hover:border-blue-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-orange-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-buildings text-[#003d7a]"></i> Perusahaan 4 (Finance)
                                </div>
                                <div class="text-[10px] text-[#003d7a] font-mono truncate">perusahaan4@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-200 text-orange-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Perusahaan 5 -->
                        <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan5@areakerja.test', 'password123', 'PT Techindo Cloud')"
                            class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-100/80 hover:border-blue-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-orange-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-buildings text-[#003d7a]"></i> Perusahaan 5 (DevOps)
                                </div>
                                <div class="text-[10px] text-[#003d7a] font-mono truncate">perusahaan5@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-200 text-orange-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Perusahaan 6 -->
                        <button type="button" x-show="tab === 'all' || tab === 'perusahaan'" onclick="fillDemo('perusahaan6@areakerja.test', 'password123', 'PT Sukses Gemilang')"
                            class="flex items-center justify-between p-2 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-100/80 hover:border-blue-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-orange-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-buildings text-[#003d7a]"></i> Perusahaan 6 (Logistik)
                                </div>
                                <div class="text-[10px] text-[#003d7a] font-mono truncate">perusahaan6@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-200 text-orange-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- ================= PELAMAR (1-6) ================= -->
                        <!-- Pelamar 1 -->
                        <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar1@areakerja.test', 'password123', 'Budi Santoso (Backend)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-teal-50/50 hover:bg-teal-100/80 hover:border-teal-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-user text-teal-600"></i> Pelamar 1 (Budi)
                                </div>
                                <div class="text-[10px] text-teal-700 font-mono truncate">pelamar1@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-200 text-teal-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Pelamar 2 -->
                        <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar2@areakerja.test', 'password123', 'Ahmad Rizky (Frontend)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-teal-50/50 hover:bg-teal-100/80 hover:border-teal-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-user text-teal-600"></i> Pelamar 2 (Ahmad)
                                </div>
                                <div class="text-[10px] text-teal-700 font-mono truncate">pelamar2@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-200 text-teal-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Pelamar 3 -->
                        <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar3@areakerja.test', 'password123', 'Dewi Lestari (UI/UX)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-teal-50/50 hover:bg-teal-100/80 hover:border-teal-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-user text-teal-600"></i> Pelamar 3 (Dewi)
                                </div>
                                <div class="text-[10px] text-teal-700 font-mono truncate">pelamar3@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-200 text-teal-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Pelamar 4 -->
                        <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar4@areakerja.test', 'password123', 'Fajar Pratama (Data)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-teal-50/50 hover:bg-teal-100/80 hover:border-teal-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-user text-teal-600"></i> Pelamar 4 (Fajar)
                                </div>
                                <div class="text-[10px] text-teal-700 font-mono truncate">pelamar4@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-200 text-teal-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Pelamar 5 -->
                        <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar5@areakerja.test', 'password123', 'Rina Indah (Marketing)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-teal-50/50 hover:bg-teal-100/80 hover:border-teal-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-user text-teal-600"></i> Pelamar 5 (Rina)
                                </div>
                                <div class="text-[10px] text-teal-700 font-mono truncate">pelamar5@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-200 text-teal-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Pelamar 6 -->
                        <button type="button" x-show="tab === 'all' || tab === 'pelamar'" onclick="fillDemo('pelamar6@areakerja.test', 'password123', 'Eko Prasetyo (Finance)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-teal-200 bg-teal-50/50 hover:bg-teal-100/80 hover:border-teal-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-teal-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-user text-teal-600"></i> Pelamar 6 (Eko)
                                </div>
                                <div class="text-[10px] text-teal-700 font-mono truncate">pelamar6@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-teal-200 text-teal-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- ================= KANDIDAT (1-6) ================= -->
                        <!-- Kandidat 1 -->
                        <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat1@areakerja.test', 'password123', 'Siti Rahayu (Flutter/Vue)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/80 hover:border-rose-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-star text-rose-600"></i> Kandidat 1 (Siti)
                                </div>
                                <div class="text-[10px] text-rose-700 font-mono truncate">kandidat1@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-200 text-rose-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Kandidat 2 -->
                        <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat2@areakerja.test', 'password123', 'Doni Kurniawan (Java/Docker)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/80 hover:border-rose-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-star text-rose-600"></i> Kandidat 2 (Doni)
                                </div>
                                <div class="text-[10px] text-rose-700 font-mono truncate">kandidat2@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-200 text-rose-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Kandidat 3 -->
                        <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat3@areakerja.test', 'password123', 'Maya Kartika (HR/Psikologi)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/80 hover:border-rose-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-star text-rose-600"></i> Kandidat 3 (Maya)
                                </div>
                                <div class="text-[10px] text-rose-700 font-mono truncate">kandidat3@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-200 text-rose-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Kandidat 4 -->
                        <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat4@areakerja.test', 'password123', 'Agus Wijaya (Mobile/Flutter)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/80 hover:border-rose-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-star text-rose-600"></i> Kandidat 4 (Agus)
                                </div>
                                <div class="text-[10px] text-rose-700 font-mono truncate">kandidat4@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-200 text-rose-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Kandidat 5 -->
                        <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat5@areakerja.test', 'password123', 'Nabila Putri (Social Media)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/80 hover:border-rose-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-star text-rose-600"></i> Kandidat 5 (Nabila)
                                </div>
                                <div class="text-[10px] text-rose-700 font-mono truncate">kandidat5@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-200 text-rose-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>

                        <!-- Kandidat 6 -->
                        <button type="button" x-show="tab === 'all' || tab === 'kandidat'" onclick="fillDemo('kandidat6@areakerja.test', 'password123', 'Hendra Saputra (Network)')"
                            class="flex items-center justify-between p-2 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-100/80 hover:border-rose-300 transition text-left group cursor-pointer shadow-2xs">
                            <div class="truncate mr-2">
                                <div class="font-bold text-rose-900 flex items-center gap-1 text-xs">
                                    <i class="ph ph-star text-rose-600"></i> Kandidat 6 (Hendra)
                                </div>
                                <div class="text-[10px] text-rose-700 font-mono truncate">kandidat6@areakerja.test</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-rose-200 text-rose-800 font-bold group-hover:scale-105 transition shrink-0">pwd123</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js & Phosphor Icons -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        function fillDemo(email, password, roleName) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;

                // Visual flash effect
                emailInput.classList.add('ring-2', 'ring-[#00509d]', 'bg-blue-50');
                passwordInput.classList.add('ring-2', 'ring-[#00509d]', 'bg-blue-50');

                setTimeout(() => {
                    emailInput.classList.remove('ring-2', 'ring-[#00509d]', 'bg-blue-50');
                    passwordInput.classList.remove('ring-2', 'ring-[#00509d]', 'bg-blue-50');
                }, 800);
            }
        }
    </script>
</body>

</html>

