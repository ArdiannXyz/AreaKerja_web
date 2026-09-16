@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
@php
    $role = $user->role;
    $img = null;
    $detail = null;

    if ($role === 'admin') {
        $detail = $user->admin;
        $img = $detail?->img_profile;
    } elseif ($role === 'finance') {
        $detail = $user->finance;
        $img = $detail?->img_profile;
    } elseif ($role === 'perusahaan') {
        $detail = $user->perusahaan;
        $img = $detail?->img_profile;
    } elseif ($role === 'pelamar') {
        $detail = $user->pelamar;
        $img = $detail?->img_profile;
    }

    $displayName = match($role) {
        'admin'      => $detail?->nama_lengkap ?? $user->nama_lengkap ?? $user->username,
        'finance'    => $detail?->nama_lengkap ?? $user->nama_lengkap ?? $user->username,
        'perusahaan' => $detail?->nama_perusahaan ?? $user->nama_lengkap ?? $user->username,
        'pelamar'    => $detail?->nama_pelamar ?? $user->nama_lengkap ?? $user->username,
        default      => $user->nama_lengkap ?? $user->username,
    };

    $roleBadgeConfig = match($role) {
        'admin' => [
            'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
            'hero' => 'bg-blue-500/25 text-blue-200 border-blue-400/40',
            'icon' => 'ph-shield-check',
            'label' => 'Admin'
        ],
        'finance' => [
            'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'hero' => 'bg-emerald-500/25 text-emerald-200 border-emerald-400/40',
            'icon' => 'ph-wallet',
            'label' => 'Finance'
        ],
        'perusahaan' => [
            'bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'hero' => 'bg-indigo-500/25 text-indigo-200 border-indigo-400/40',
            'icon' => 'ph-buildings',
            'label' => 'Perusahaan'
        ],
        'pelamar' => [
            'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
            'hero' => 'bg-amber-500/25 text-amber-200 border-amber-400/40',
            'icon' => 'ph-user',
            'label' => 'Pelamar'
        ],
        default => [
            'bg' => 'bg-purple-50 text-purple-700 border-purple-200',
            'hero' => 'bg-purple-500/25 text-purple-200 border-purple-400/40',
            'icon' => 'ph-crown',
            'label' => 'Super Admin'
        ],
    };
@endphp

<main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

    {{-- Top Navigation & Header Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1.5">
                <a href="{{ route('superadmin.add.user') }}" class="hover:text-[#00509d] transition flex items-center gap-1 font-medium">
                    <i class="ph ph-users"></i> Kelola Akun
                </a>
                <i class="ph ph-caret-right text-[10px]"></i>
                <span class="text-slate-600 font-semibold">Detail User</span>
            </nav>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                Detail Profil User
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full border {{ $roleBadgeConfig['bg'] }}">
                    <i class="ph {{ $roleBadgeConfig['icon'] }} mr-1"></i>{{ $roleBadgeConfig['label'] }}
                </span>
            </h1>
        </div>

        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-end">
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.add.user') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition duration-150">
                    <i class="ph ph-arrow-left text-sm"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('superadmin.edit.user', $user->id) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-[#00509d] hover:bg-[#003f7a] rounded-xl shadow-sm hover:shadow transition duration-150">
                    <i class="ph ph-pencil-simple text-sm"></i>
                    <span>Edit Profil</span>
                </a>
            </div>

            <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>
    </div>

    {{-- Main Profile Hero Banner --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        {{-- Cover Gradient Banner --}}
        <div class="relative bg-gradient-to-r from-[#003060] via-[#00509d] to-[#0a74da] px-6 pt-8 pb-16 sm:pb-14 text-white overflow-hidden">
            {{-- Background decorative circles --}}
            <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-white/5 pointer-events-none blur-xl"></div>
            <div class="absolute right-32 -top-10 w-32 h-32 rounded-full bg-sky-300/10 pointer-events-none blur-lg"></div>

            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full border {{ $roleBadgeConfig['hero'] }}">
                            <i class="ph {{ $roleBadgeConfig['icon'] }}"></i> {{ $roleBadgeConfig['label'] }}
                        </span>
                        <span class="text-[11px] font-medium px-2.5 py-0.5 rounded-full bg-white/10 text-sky-100 border border-white/15">
                            ID: #{{ $user->id }}
                        </span>
                        @if ($user->status == 1)
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-200 border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Akun Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-200 border border-rose-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                Dinonaktifkan
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-sky-200 flex items-center gap-1">
                        <i class="ph ph-calendar-blank"></i> Terdaftar sejak {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Profile Bar (Overlapping) --}}
        <div class="px-6 pb-6 pt-0 relative">
            <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between gap-4 -mt-12 sm:-mt-10 mb-4">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 text-center sm:text-left">
                    {{-- Avatar --}}
                    <div class="relative">
                        @if ($img)
                            <img src="{{ asset('storage/' . $img) }}"
                                 alt="{{ $displayName }}"
                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-white shadow-md bg-white">
                        @else
                            <div style="background: linear-gradient(135deg, #00509d 0%, #002855 100%);"
                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl text-white flex items-center justify-center font-bold text-2xl sm:text-3xl ring-4 ring-white shadow-md uppercase tracking-wider select-none">
                                {{ strtoupper(substr($displayName, 0, 2)) }}
                            </div>
                        @endif
                        @if ($user->status == 1)
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center" title="Online / Aktif">
                                <i class="ph ph-check text-[10px] text-white font-bold"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Name & Subtitle --}}
                    <div class="sm:mb-1">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">{{ $displayName }}</h2>
                        <div class="flex items-center justify-center sm:justify-start gap-2 text-xs text-slate-500 mt-1 flex-wrap">
                            <span class="font-medium text-slate-700">@<span>{{ $user->username }}</span></span>
                            <span class="text-slate-300">•</span>
                            <span class="flex items-center gap-1"><i class="ph ph-envelope-simple text-slate-400"></i> {{ $user->email }}</span>
                            @if ($user->telepon)
                                <span class="text-slate-300">•</span>
                                <span class="flex items-center gap-1"><i class="ph ph-phone text-slate-400"></i> {{ $user->telepon }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('superadmin.edit.user', $user->id) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#00509d] hover:bg-[#003f7a] rounded-xl shadow-xs transition">
                        <i class="ph ph-note-pencil text-sm"></i> Edit Data
                    </a>
                </div>
            </div>

            {{-- Summary Stats Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100">
                <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Role Sistem</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-800 capitalize flex items-center gap-1.5">
                        <i class="ph {{ $roleBadgeConfig['icon'] }} text-[#00509d]"></i> {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Status Akun</span>
                    <span class="text-xs sm:text-sm font-bold {{ $user->status == 1 ? 'text-emerald-600' : 'text-rose-600' }} flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->status == 1 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        {{ $user->status == 1 ? 'Aktif Normal' : 'Dibekukan' }}
                    </span>
                </div>
                <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Verifikasi Email</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-800 flex items-center gap-1.5">
                        @if ($user->verified)
                            <i class="ph ph-check-circle text-emerald-600 text-sm"></i>
                            <span class="text-emerald-700">Terverifikasi</span>
                        @else
                            <i class="ph ph-warning-circle text-amber-500 text-sm"></i>
                            <span class="text-amber-700">Belum Verifikasi</span>
                        @endif
                    </span>
                </div>
                <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Terdaftar Sejak</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-800">
                        {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Grid: 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Card 1: Informasi Akun (Autentikasi & Sistem) --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#00509d] flex items-center justify-center">
                        <i class="ph ph-shield-check text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Informasi Akun</h3>
                        <p class="text-[11px] text-slate-400">Data login dan identitas autentikasi</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded bg-slate-100 text-slate-600">ID: {{ $user->id }}</span>
            </div>

            <div class="space-y-3.5">
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                        <i class="ph ph-user text-slate-400 text-sm"></i> Username
                    </span>
                    <span class="text-xs font-semibold text-slate-800">{{ $user->username }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                        <i class="ph ph-envelope-simple text-slate-400 text-sm"></i> Email
                    </span>
                    <span class="text-xs font-semibold text-slate-800 break-all text-right">{{ $user->email }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                        <i class="ph ph-phone text-slate-400 text-sm"></i> Nomor Telepon
                    </span>
                    <span class="text-xs font-semibold text-slate-800">{{ $user->telepon ?? 'Belum Ditambahkan' }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                        <i class="ph ph-identification-badge text-slate-400 text-sm"></i> Role
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full border {{ $roleBadgeConfig['bg'] }}">
                        {{ $roleBadgeConfig['label'] }}
                    </span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                        <i class="ph ph-toggle-right text-slate-400 text-sm"></i> Status Akun
                    </span>
                    <span class="text-xs font-semibold {{ $user->status == 1 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-rose-700 bg-rose-50 border-rose-200' }} px-2.5 py-0.5 rounded-full border">
                        {{ $user->status == 1 ? 'Aktif' : 'Nonaktif / Dibekukan' }}
                    </span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                        <i class="ph ph-calendar-plus text-slate-400 text-sm"></i> Waktu Pendaftaran
                    </span>
                    <span class="text-xs font-semibold text-slate-800">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Detail Profil Berdasarkan Role --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph {{ $roleBadgeConfig['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Profil {{ $roleBadgeConfig['label'] }}</h3>
                        <p class="text-[11px] text-slate-400">Biodata dan informasi spesifik peran</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest">{{ $role }}</span>
            </div>

            <div class="space-y-3.5">
                {{-- Nama Lengkap (Umum) --}}
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                    <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                        <i class="ph ph-user-circle text-slate-400 text-sm"></i> Nama Lengkap
                    </span>
                    <span class="text-xs font-semibold text-slate-800 text-right">{{ $displayName }}</span>
                </div>

                {{-- ==================== ROLE: ADMIN / FINANCE ==================== --}}
                @if (in_array($role, ['admin', 'finance']))
                    @php
                        $provNama = $detail?->provinsi->nama ?? (is_object($detail?->provinsi ?? null) ? $detail->provinsi->nama ?? null : (is_string($detail?->provinsi ?? null) ? $detail->provinsi : null));
                        $kotaNama = $detail?->kota->nama ?? (is_object($detail?->kota ?? null) ? $detail->kota->nama ?? null : (is_string($detail?->kota ?? null) ? $detail->kota : null));
                        $kecNama  = $detail?->kecamatan->nama ?? (is_object($detail?->kecamatan ?? null) ? $detail->kecamatan->nama ?? null : (is_string($detail?->kecamatan ?? null) ? $detail->kecamatan : null));
                    @endphp

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-map-pin text-slate-400 text-sm"></i> Provinsi
                        </span>
                        <span class="text-xs font-semibold {{ $provNama ? 'text-slate-800' : 'text-slate-400 italic' }}">
                            {{ $provNama ?? 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-buildings text-slate-400 text-sm"></i> Kota / Kabupaten
                        </span>
                        <span class="text-xs font-semibold {{ $kotaNama ? 'text-slate-800' : 'text-slate-400 italic' }}">
                            {{ $kotaNama ?? 'Belum Diisi' }}
                        </span>
                    </div>

                    @if ($kecNama || ($detail?->desa ?? null) || ($detail?->kode_pos ?? null))
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                                <i class="ph ph-map-trifold text-slate-400 text-sm"></i> Kecamatan / Desa
                            </span>
                            <span class="text-xs font-semibold text-slate-800 text-right">
                                {{ $kecNama ?? '-' }} / {{ $detail?->desa ?? '-' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                                <i class="ph ph-mailbox text-slate-400 text-sm"></i> Kode Pos
                            </span>
                            <span class="text-xs font-semibold text-slate-800">{{ $detail?->kode_pos ?? '-' }}</span>
                        </div>
                    @endif

                    @if ($detail?->detail_alamat ?? null)
                        <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 flex items-center gap-2 mb-1.5">
                                <i class="ph ph-house-line text-slate-400 text-sm"></i> Detail Alamat
                            </span>
                            <p class="text-xs text-slate-700 leading-relaxed">{{ $detail->detail_alamat }}</p>
                        </div>
                    @endif

                {{-- ==================== ROLE: PERUSAHAAN ==================== --}}
                @elseif ($role === 'perusahaan')
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-certificate text-slate-400 text-sm"></i> Legalitas
                        </span>
                        <span class="text-xs font-semibold text-slate-800">{{ $user->perusahaan?->legalitas ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-tag text-slate-400 text-sm"></i> Jenis Industri
                        </span>
                        <span class="text-xs font-semibold text-slate-800">{{ $user->perusahaan?->jenis_perusahaan ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-globe text-slate-400 text-sm"></i> Website
                        </span>
                        @if ($user->perusahaan?->website_perusahaan)
                            <a href="{{ $user->perusahaan->website_perusahaan }}" target="_blank" class="text-xs font-semibold text-[#00509d] hover:underline flex items-center gap-1">
                                {{ $user->perusahaan->website_perusahaan }} <i class="ph ph-arrow-square-out text-xs"></i>
                            </a>
                        @else
                            <span class="text-xs text-slate-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-coins text-amber-500 text-sm"></i> Saldo Koin
                        </span>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">
                            🪙 {{ number_format($user->perusahaan?->koin ?? 0, 0, ',', '.') }} Koin
                        </span>
                    </div>

                {{-- ==================== ROLE: PELAMAR ==================== --}}
                @elseif ($role === 'pelamar')
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-calendar text-slate-400 text-sm"></i> Tanggal Lahir
                        </span>
                        <span class="text-xs font-semibold text-slate-800">
                            @if ($user->pelamar?->tanggal_lahir)
                                {{ \Carbon\Carbon::parse($user->pelamar->tanggal_lahir)->translatedFormat('d F Y') }}
                                <span class="text-slate-400 text-[11px]">({{ \Carbon\Carbon::parse($user->pelamar->tanggal_lahir)->age }} tahun)</span>
                            @else
                                <span class="text-slate-400 italic">Belum Diisi</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <i class="ph ph-whatsapp-logo text-slate-400 text-sm"></i> Telepon Pelamar
                        </span>
                        <span class="text-xs font-semibold text-slate-800">{{ $user->pelamar?->telepon_pelamar ?? '-' }}</span>
                    </div>

                    @if ($user->pelamar?->deskripsi_diri)
                        <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 flex items-center gap-2 mb-1.5">
                                <i class="ph ph-article text-slate-400 text-sm"></i> Deskripsi Diri
                            </span>
                            <p class="text-xs text-slate-700 leading-relaxed">{{ $user->pelamar->deskripsi_diri }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Bottom Action Footer Bar --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <i class="ph ph-info text-[#00509d] text-base"></i>
            <span>Perubahan data user hanya dapat dilakukan oleh Super Admin yang memiliki hak akses.</span>
        </div>

        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
            <a href="{{ route('superadmin.add.user') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition duration-150">
                <i class="ph ph-arrow-left text-sm"></i>
                <span>Kembali ke Daftar</span>
            </a>
            <a href="{{ route('superadmin.edit.user', $user->id) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 text-xs font-semibold text-white bg-[#00509d] hover:bg-[#003f7a] rounded-xl shadow-sm hover:shadow transition duration-150">
                <i class="ph ph-pencil-simple text-sm"></i>
                <span>Edit User Ini</span>
            </a>
        </div>
    </div>

</main>
@endsection

