@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header Top Bar -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">
                    Dashboard
                </h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    Selamat datang kembali, <span class="text-[#00509d] font-semibold">{{ Auth::user()->username ?? 'Super Admin' }}</span> &mdash; {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Welcome Hero Banner -->
        @php
            $hour = \Carbon\Carbon::now()->hour;
            $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
            $adminName = Auth::user()->nama_lengkap ?? (Auth::user()->username ?? 'Super Admin');
        @endphp
        <div class="relative overflow-hidden rounded-2xl bg-[#00509d] text-white p-6 sm:p-8 mb-6">
            <!-- Decorative subtle circles -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
            <div class="absolute right-48 bottom-0 w-40 h-40 bg-black/10 rounded-full translate-y-1/2 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="max-w-xl">
                    <p class="text-white/60 text-xs font-medium mb-1.5 uppercase tracking-widest">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h2 class="text-xl sm:text-2xl font-semibold text-white leading-snug">
                        {{ $greeting }}, <span class="font-bold">{{ $adminName }}</span> 👋
                    </h2>
                    <p class="text-white/65 text-xs sm:text-sm mt-2 leading-relaxed max-w-lg">
                        Pantau dan kelola seluruh ekosistem talenta, mitra perusahaan, serta aktivitas sistem dari satu tempat.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-2 mt-5">
                        <a href="{{ route('superadmin.add.user') }}"
                           class="inline-flex items-center gap-1.5 bg-white text-[#00509d] hover:bg-blue-50 font-semibold text-xs px-4 py-2.5 rounded-lg transition duration-200">
                            <i class="ph ph-user-plus text-sm"></i>
                            Tambah Akun
                        </a>
                        <a href="{{ route('superadmin.pelamar') }}"
                           class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 text-white font-medium text-xs px-4 py-2.5 rounded-lg border border-white/20 transition duration-200">
                            <i class="ph ph-users text-sm"></i>
                            Data Pelamar
                        </a>
                        <a href="{{ route('superadmin.perusahaan') }}"
                           class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 text-white font-medium text-xs px-4 py-2.5 rounded-lg border border-white/20 transition duration-200">
                            <i class="ph ph-buildings text-sm"></i>
                            Data Perusahaan
                        </a>
                    </div>
                </div>

                <!-- Quick Snapshot Card -->
                <div class="hidden xl:flex flex-col gap-2 bg-white/10 p-4 rounded-xl border border-white/15 min-w-[220px]">
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-white/50 mb-1">Ringkasan Hari Ini</span>
                    <div class="flex items-center justify-between py-1.5 border-b border-white/10 text-xs">
                        <span class="text-white/70">Lowongan Terbuka</span>
                        <span class="font-semibold text-white">{{ $lowonganBuka }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-white/10 text-xs">
                        <span class="text-white/70">Kandidat Aktif</span>
                        <span class="font-semibold text-white">{{ $totalKandidatAktif }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 text-xs">
                        <span class="text-white/70">Mitra Disetujui</span>
                        <span class="font-semibold text-white">{{ $perusahaanApproved }} / {{ $totalPerusahaan }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5 Executive Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

            <!-- Card 1: Pelamar -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pelamar Terdaftar</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center">
                        <i class="ph ph-users text-xl font-bold"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalPelamar) }}</span>
                    @if($growthPelamar >= 0)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                            <i class="ph ph-trend-up"></i> +{{ $growthPelamar }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700">
                            <i class="ph ph-trend-down"></i> {{ $growthPelamar }}%
                        </span>
                    @endif
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Kandidat: <strong class="text-slate-800">{{ $totalKandidatAktif }}</strong></span>
                    <span>Umum: <strong class="text-slate-800">{{ $totalPelamarReguler }}</strong></span>
                </div>
            </div>

            <!-- Card 2: Perusahaan -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mitra Perusahaan</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="ph ph-buildings text-xl font-bold"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalPerusahaan) }}</span>
                    @if($growthPerusahaan >= 0)
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                            <i class="ph ph-trend-up"></i> +{{ $growthPerusahaan }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700">
                            <i class="ph ph-trend-down"></i> {{ $growthPerusahaan }}%
                        </span>
                    @endif
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Disetujui: <strong class="text-emerald-600">{{ $perusahaanApproved }}</strong></span>
                    <span>Pending: <strong class="text-amber-600">{{ $perusahaanPending }}</strong></span>
                </div>
            </div>

            <!-- Card 3: Lowongan Kerja -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lowongan Kerja</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph ph-briefcase text-xl font-bold"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalLowongan) }}</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                        {{ $lowonganBuka }} Buka
                    </span>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Aktif: <strong class="text-emerald-600">{{ $lowonganBuka }}</strong></span>
                    <span>Ditutup: <strong class="text-slate-600">{{ $lowonganTutup }}</strong></span>
                </div>
            </div>

            <!-- Card 4: Staff & Admin -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Manajemen Staff</span>
                    <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                        <i class="ph ph-shield-check text-xl font-bold"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalAdmin + $totalSuperAdmin) }}</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-violet-50 text-violet-700">
                        Admin Team
                    </span>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Super Admin: <strong class="text-slate-800">{{ $totalSuperAdmin }}</strong></span>
                    <span>Admin: <strong class="text-slate-800">{{ $totalAdmin }}</strong></span>
                </div>
            </div>

            <!-- Card 5: Akun Freeze & Keamanan -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Keamanan</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="ph ph-lock-key text-xl font-bold"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalFreeze) }}</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full {{ $totalFreeze > 0 ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-600' }}">
                        Akun Freeze
                    </span>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-500">Total User: <strong class="text-slate-800">{{ $totalUsers }}</strong></span>
                    <a href="{{ route('superadmin.freeze') }}" class="text-[#00509d] hover:underline font-bold flex items-center gap-0.5">
                        Kelola <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- 
        <!-- Quick Shortcuts Navigation Hub (8 Modul Cepat) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs mb-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="ph ph-lightning text-amber-500 text-lg"></i> Akses Pintas Fitur Super Admin
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Navigasi langsung ke modul pengelolaan sistem utama</p>
                </div>
                <span class="text-[11px] font-bold text-slate-400">8 Modul Cepat</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                <!-- 1. Data Pelamar -->
                <a href="{{ route('superadmin.pelamar') }}" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-blue-300 hover:bg-blue-50/50 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-blue-100/80 text-[#00509d] group-hover:bg-[#00509d] group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-user-focus text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-[#00509d] leading-tight">Data Pelamar</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $totalPelamar }} Akun</span>
                </a>

                <!-- 2. Data Perusahaan -->
                <a href="{{ route('superadmin.perusahaan') }}" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-indigo-300 hover:bg-indigo-50/50 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100/80 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-buildings text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 leading-tight">Perusahaan</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $totalPerusahaan }} Mitra</span>
                </a>

                <!-- 3. Paket & Finance -->
                <a href="{{ route('superadmin.paket-harga') }}" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-emerald-300 hover:bg-emerald-50/50 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100/80 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-coins text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-600 leading-tight">Paket & Fin</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Tarif Koin</span>
                </a>

                <!-- 4. Setting Lowongan -->
                <a href="{{ route('superadmin.manajemen.lowongan.gold') }}" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-amber-300 hover:bg-amber-50/50 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-amber-100/80 text-amber-600 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-sliders-horizontal text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-amber-600 leading-tight">Lowongan</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Gold/Silver</span>
                </a>

                <!-- 5. Akun Freeze -->
                <a href="{{ route('superadmin.freeze') }}" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-rose-300 hover:bg-rose-50/50 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-rose-100/80 text-rose-600 group-hover:bg-rose-600 group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-lock-key text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-rose-600 leading-tight">Akun Freeze</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $totalFreeze }} Kasus</span>
                </a>

                <!-- 6. Tips Kerja -->
                <a href="/super_admin/tips/kerja" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-cyan-300 hover:bg-cyan-50/50 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-cyan-100/80 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-article-medium text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-cyan-600 leading-tight">Tips Kerja</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Artikel</span>
                </a>

                <!-- 7. Event Webinar -->
                <a href="/super_admin/event" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-purple-300 hover:bg-purple-50/50 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-purple-100/80 text-purple-600 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-calendar-star text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-purple-600 leading-tight">Event</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Kegiatan</span>
                </a>

                <!-- 8. Manajemen Akun -->
                <a href="{{ route('superadmin.add.user') }}" 
                   class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 hover:border-slate-400 hover:bg-slate-100/60 hover:-translate-y-0.5 transition duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-slate-200/80 text-slate-700 group-hover:bg-slate-800 group-hover:text-white flex items-center justify-center transition duration-200 mb-2">
                        <i class="ph ph-user-gear text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-slate-900 leading-tight">Manajemen Akun</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Staff & Role</span>
                </a>
            </div>
        </div>
        --}}

        <!-- Interactive Visual Analytics Charts (ApexCharts) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <!-- Chart 1: Tren Pendaftaran 6 Bulan Terakhir (2 Span) -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="ph ph-chart-line-up text-[#00509d] text-xl"></i> Tren Pendaftaran 6 Bulan Terakhir
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">Perbandingan pertumbuhan pendaftaran Pelamar Baru vs Mitra Perusahaan</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#00509d]"></span>
                            <span class="text-slate-600">Pelamar</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#f97316]"></span>
                            <span class="text-slate-600">Perusahaan</span>
                        </div>
                    </div>
                </div>

                <!-- ApexChart Container -->
                <div id="registrationTrendChart" class="w-full h-72"></div>
            </div>

            <!-- Chart 2: Komposisi Kategori Pelamar (1 Span) -->
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="mb-4 pb-3 border-b border-slate-100">
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="ph ph-pie-chart text-violet-600 text-xl"></i> Komposisi Talenta
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">Distribusi kategori kandidat dan pelamar</p>
                    </div>

                    <!-- Donut Chart Container -->
                    <div id="talentDonutChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>

                <!-- Quick Legend / Breakdown -->
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-slate-600 font-medium">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#00509d]"></span> Kandidat Aktif
                        </span>
                        <span class="font-extrabold text-slate-900">{{ $totalKandidatAktif }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-slate-600 font-medium">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#0ea5e9]"></span> Calon Kandidat
                        </span>
                        <span class="font-extrabold text-slate-900">{{ $totalCalonKandidat }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-slate-600 font-medium">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#94a3b8]"></span> Pelamar Reguler
                        </span>
                        <span class="font-extrabold text-slate-900">{{ $totalPelamarReguler }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Realtime Data Feeds (Two-Column Layout) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <!-- Kolom Kiri (2 Span): Lowongan Kerja Terbaru -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="ph ph-briefcase text-emerald-600 text-xl"></i> Lowongan Kerja Terbaru
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">Peluang karir yang baru dipublikasikan oleh mitra</p>
                    </div>
                    <a href="{{ route('superadmin.manajemen.lowongan.gold') }}" class="text-xs font-bold text-[#00509d] hover:underline flex items-center gap-1">
                        Lihat Semua <i class="ph ph-arrow-right"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Posisi Lowongan</th>
                                <th class="px-4 py-3">Perusahaan</th>
                                <th class="px-4 py-3">Tipe & Kategori</th>
                                <th class="px-4 py-3">Gaji</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right rounded-r-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($latestLowongans as $lowongan)
                                <tr class="hover:bg-slate-50/80 transition duration-150">
                                    <td class="px-4 py-3">
                                        <div class="font-extrabold text-slate-900 text-xs">{{ $lowongan->nama }}</div>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $lowongan->created_at ? $lowongan->created_at->diffForHumans() : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-700 truncate max-w-[150px]">
                                            {{ $lowongan->perusahaan->nama_perusahaan ?? '-' }}
                                        </div>
                                        <span class="text-[10px] text-slate-400 truncate block">
                                            {{ $lowongan->perusahaan->kota ?? 'Indonesia' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2 py-0.5 bg-blue-50 text-[#00509d] font-bold rounded-md text-[10px]">
                                            {{ $lowongan->jenis ?? 'Full Time' }}
                                        </span>
                                        <span class="block text-[10px] text-slate-400 mt-0.5">
                                            {{ $lowongan->kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-700">
                                        {{ $lowongan->label_gaji ?? ( 'Rp ' . number_format((float)($lowongan->gaji_awal ?? 0), 0, ',', '.') ) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($lowongan->status ?? 'buka') === 'buka')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Buka
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Tutup
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('superadmin.lowongan.detail', ['perusahaan' => $lowongan->perusahaan_id ?? 1, 'lowongan' => $lowongan->id]) }}" 
                                           class="inline-flex items-center justify-center p-1.5 bg-slate-100 hover:bg-[#00509d] hover:text-white text-slate-600 rounded-lg transition duration-150"
                                           title="Lihat Detail Lowongan">
                                            <i class="ph ph-eye text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                        <i class="ph ph-briefcase text-3xl mb-1 block opacity-50"></i>
                                        Belum ada lowongan kerja yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kolom Kanan (1 Span): Perusahaan Baru & Pelamar Terkini -->
            <div class="space-y-6">

                <!-- Perusahaan Baru -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="ph ph-buildings text-indigo-600 text-lg"></i> Perusahaan Baru
                            </h3>
                            <p class="text-[11px] text-slate-500 font-medium">Mitra baru yang mendaftar di sistem</p>
                        </div>
                        <a href="{{ route('superadmin.perusahaan') }}" class="text-[11px] font-bold text-[#00509d] hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($latestPerusahaans as $perusahaan)
                            <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition duration-150 border border-transparent hover:border-slate-100">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if ($perusahaan->img_profile)
                                        <img src="{{ asset('storage/' . $perusahaan->img_profile) }}" 
                                             class="w-9 h-9 object-cover rounded-xl border border-slate-200 flex-shrink-0" alt="Logo">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($perusahaan->nama_perusahaan ?? 'P') }}&background=00509d&color=fff&size=100" 
                                             class="w-9 h-9 object-cover rounded-xl border border-slate-200 flex-shrink-0" alt="Logo">
                                    @endif
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-900 truncate">{{ $perusahaan->nama_perusahaan }}</h4>
                                        <p class="text-[10px] text-slate-500 truncate">{{ $perusahaan->jenis_perusahaan ?? ($perusahaan->kota ?? 'Perusahaan Mitra') }}</p>
                                    </div>
                                </div>

                                <div class="flex-shrink-0 ml-2">
                                    @if($perusahaan->verification_status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700">
                                            ✓ Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-4">Belum ada perusahaan baru.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pelamar Baru Terdaftar -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="ph ph-user-check text-[#00509d] text-lg"></i> Pelamar Terkini
                            </h3>
                            <p class="text-[11px] text-slate-500 font-medium">Talenta pencari kerja yang baru bergabung</p>
                        </div>
                        <a href="{{ route('superadmin.pelamar') }}" class="text-[11px] font-bold text-[#00509d] hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($latestPelamars as $pelamar)
                            <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition duration-150 border border-transparent hover:border-slate-100">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if ($pelamar->img_profile)
                                        <img src="{{ asset('storage/' . $pelamar->img_profile) }}" 
                                             class="w-9 h-9 object-cover rounded-full border border-slate-200 flex-shrink-0" alt="Avatar">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pelamar->nama_pelamar ?? 'User') }}&background=0284c7&color=fff&size=100" 
                                             class="w-9 h-9 object-cover rounded-full border border-slate-200 flex-shrink-0" alt="Avatar">
                                    @endif
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-900 truncate">{{ $pelamar->nama_pelamar }}</h4>
                                        <p class="text-[10px] text-slate-500 truncate">
                                            {{ is_array($pelamar->divisi) ? implode(', ', $pelamar->divisi) : ($pelamar->kota ?? 'Pelamar') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex-shrink-0 ml-2">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold capitalize
                                        {{ $pelamar->kategori === 'kandidat aktif' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $pelamar->kategori ?? 'pelamar' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-4">Belum ada pelamar baru.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

        <!-- Modal Notifikasi (Preserved 100%) -->
        <div x-data="notifHandler()" x-cloak x-show="openNotif"
            class="fixed inset-0 z-50 flex items-start justify-end p-2 sm:p-4" @click.self="openNotif = false">

            <div class="bg-white w-[80%] sm:w-[360px] rounded-xl shadow-lg overflow-hidden border border-slate-200">

                <!-- Header -->
                <div class="flex items-center justify-between px-3 sm:px-4 py-3 border-b bg-slate-50">
                    <h2 class="font-semibold text-sm sm:text-base text-slate-800">Notifikasi</h2>
                    <button @click="openNotif=false; openAllNotif=true" class="text-xs text-[#00509d] font-bold hover:underline">
                        Lihat semua
                    </button>
                </div>

                <!-- List Notifikasi -->
                <div class="max-h-[280px] sm:max-h-[400px] overflow-y-auto">
                    @forelse($global_notifikasis ?? [] as $notif)
                        <div data-id="{{ $notif->id }}"
                            onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                            class="notif-item cursor-pointer flex items-start gap-2 p-3 border-b 
                    {{ $notif->is_read ? 'bg-gray-100' : 'bg-white' }} hover:bg-slate-50 transition">

                            <!-- Logo perusahaan -->
                            @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                                <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                        class="w-full h-full object-cover rounded-md">
                                </div>
                            @endif

                            <!-- Pesan -->
                            <div class="flex-1 min-w-0">
                                <p class="text-xs break-words leading-snug text-slate-800">{!! $notif->pesan !!}</p>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    {{ $notif->created_at->diffForHumans() }}
                                </p>

                                <button @click.stop="hapus({{ $notif->id }})"
                                    class="text-red-500 text-[10px] font-medium hover:underline mt-1">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="p-4 text-gray-400 text-xs text-center">Tidak ada notifikasi baru</p>
                    @endforelse
                </div>

                <!-- Footer -->
                <iframe name="hiddenFrame" style="display:none;"></iframe>
                <div class="p-3 border-t bg-slate-50 flex justify-between items-center text-xs">
                    <button @click="hapusSemua()" class="text-red-600 font-semibold hover:underline">
                        Hapus Semua
                    </button>

                    <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrame">
                        @csrf
                        <button type="submit" class="text-blue-600 font-semibold hover:underline">
                            Tandai Baca
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Semua Notifikasi (Preserved 100%) -->
        <div x-data="notifHandler()" x-cloak x-show="openAllNotif"
            class="fixed inset-0 z-50 flex items-start justify-center p-2 sm:p-4 bg-black/40 backdrop-blur-xs"
            @click.self="openAllNotif = false">

            <div class="bg-white w-[85%] sm:w-full sm:max-w-lg rounded-2xl shadow-xl overflow-hidden border border-slate-200">

                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b bg-slate-50">
                    <h2 class="font-bold text-base text-slate-800">Semua Notifikasi</h2>
                    <button @click="openAllNotif=false" class="text-xs text-slate-500 hover:text-slate-800">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <!-- Semua Notifikasi -->
                <div class="max-h-[350px] sm:max-h-[500px] overflow-y-auto">
                    @foreach (\App\Models\Notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $notif)
                        <div data-id="{{ $notif->id }}"
                            onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                            class="notif-item cursor-pointer flex items-start gap-3 p-3.5 border-b 
                            {{ $notif->is_read ? 'bg-gray-100' : 'bg-white' }} hover:bg-slate-50 transition">

                            @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                                <div class="w-9 h-9 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                        class="w-full h-full object-contain rounded-lg border border-slate-200">
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="text-xs break-words leading-snug text-slate-800">{!! $notif->pesan !!}</p>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    {{ $notif->created_at->diffForHumans() }}
                                </p>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="p-3 border-t bg-slate-50 flex justify-between items-center text-xs">
                    <button @click="hapusSemuaBaca()" class="text-[#003d7a] font-semibold hover:underline">
                        Hapus Semua Dibaca
                    </button>

                    <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrameAll">
                        @csrf
                        <button type="submit" class="text-blue-600 font-semibold hover:underline">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                </div>

                <iframe name="hiddenFrameAll" style="display:none;"></iframe>
            </div>
        </div>

    </main>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Chart Configuration Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Trend Registration Chart (Area Chart)
            const trendMonths = {!! json_encode($chartMonths) !!};
            const pelamarData = {!! json_encode($chartPelamar) !!};
            const perusahaanData = {!! json_encode($chartPerusahaan) !!};

            const trendOptions = {
                series: [
                    {
                        name: 'Pelamar Baru',
                        data: pelamarData
                    },
                    {
                        name: 'Perusahaan Baru',
                        data: perusahaanData
                    }
                ],
                chart: {
                    type: 'area',
                    height: 280,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: { show: false },
                    sparkline: { enabled: false },
                    zoom: { enabled: false }
                },
                colors: ['#00509d', '#f97316'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 95, 100]
                    }
                },
                xaxis: {
                    categories: trendMonths,
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return Math.floor(val);
                        },
                        style: {
                            colors: '#64748b',
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    },
                    min: 0,
                    forceNiceScale: true
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                legend: { show: false },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function (val) {
                            return val + ' akun';
                        }
                    }
                }
            };

            const trendChartEl = document.querySelector("#registrationTrendChart");
            if (trendChartEl) {
                const trendChart = new ApexCharts(trendChartEl, trendOptions);
                trendChart.render();
            }

            // Donut Chart - Komposisi Talenta
            const kandidatAktif = {{ $totalKandidatAktif }};
            const calonKandidat = {{ $totalCalonKandidat }};
            const pelamarReguler = {{ $totalPelamarReguler }};

            const donutSeries = [kandidatAktif, calonKandidat, pelamarReguler];
            const hasData = donutSeries.some(val => val > 0);

            const donutOptions = {
                series: hasData ? donutSeries : [1, 1, 1],
                labels: ['Kandidat Aktif', 'Calon Kandidat', 'Pelamar Reguler'],
                chart: {
                    type: 'donut',
                    height: 220,
                    fontFamily: 'Poppins, sans-serif'
                },
                colors: ['#00509d', '#0ea5e9', '#94a3b8'],
                stroke: { width: 2, colors: ['#ffffff'] },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '12px', fontWeight: 600, color: '#64748b' },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 800,
                                    color: '#0f172a',
                                    formatter: function (val) {
                                        return hasData ? val : 0;
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '11px',
                                    fontWeight: 700,
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return hasData ? w.globals.seriesTotals.reduce((a, b) => a + b, 0) : 0;
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: { show: false },
                tooltip: {
                    enabled: hasData,
                    y: {
                        formatter: function(val) {
                            return val + ' orang';
                        }
                    }
                }
            };

            const donutChartEl = document.querySelector("#talentDonutChart");
            if (donutChartEl) {
                const donutChart = new ApexCharts(donutChartEl, donutOptions);
                donutChart.render();
            }
        });
    </script>

    <!-- Notification Scripts (Preserved 100%) -->
    <script>
        // Tandai dibaca
        async function markAsRead(url, el) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {
                    el.classList.remove("bg-white");
                    el.classList.add("bg-gray-100");

                    const badge = document.getElementById("notif-badge");
                    if (badge) {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove();
                        }
                    }
                }
            } catch (error) {
                console.error("markAsRead error:", error);
            }
        }

        // AlpineJS init
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifHandler', () => ({
                async hapus(id) {
                    if (!confirm("Hapus notifikasi ini?")) return;

                    let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);

                    let res = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                    }
                },

                async hapusSemua() {
                    if (!confirm("Hapus semua notifikasi?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item').forEach(e => e.remove());
                    }
                },

                async hapusSemuaBaca() {
                    if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item.bg-gray-100')
                            .forEach(e => e.remove());
                    }
                }
            }));
        });
    </script>

    <script>
        document.querySelector('form[target="hiddenFrame"]')?.addEventListener('submit', () => {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.classList.remove('bg-white');
                item.classList.add('bg-gray-100');
            });
            const badge = document.querySelector('.absolute .bg-red-500');
            if (badge) badge.remove();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
