@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-[#f8fafc] min-h-screen overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        
        @php
            $data = $pelamar ?? ($data ?? null);
            $mapKategori = [
                'pelamar' => 'non_kandidat',
                'calon kandidat' => 'calon_kandidat',
                'kandidat aktif' => 'kandidat',
            ];
            $kategoriKey = strtolower($data->kategori ?? 'pelamar');

            $formatUrl = function($url, $type) {
                if (!$url) return null;
                if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) return $url;
                $clean = ltrim($url, '@');
                return match($type) {
                    'instagram' => 'https://instagram.com/' . $clean,
                    'linkedin' => (str_contains($clean, 'linkedin.com') ? 'https://' . $clean : 'https://linkedin.com/in/' . $clean),
                    'twitter' => 'https://x.com/' . $clean,
                    'website' => 'https://' . $clean,
                    default => 'https://' . $clean,
                };
            };

            $primaryAlamat = $data->alamat_pelamar ? $data->alamat_pelamar->first() : null;
        @endphp

        {{-- TOP NAVIGATION & ADMIN USER BAR --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 pb-4 border-b border-slate-200">
            {{-- Title & Back Button --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.non-kandidat') }}"
                    class="p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 transition shadow-sm flex items-center justify-center"
                    title="Kembali ke Data Pelamar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight whitespace-nowrap">Detail Pelamar</h1>
                    <p class="text-xs text-slate-500">Informasi profil lengkap dan kurikulum vitae pelamar umum</p>
                </div>
            </div>

            {{-- Admin Notif & User Badge Dropdown --}}
            <div class="flex items-center gap-3 self-end sm:self-auto">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </div>

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 text-sm px-4 py-3 rounded-xl shadow-sm">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- CANDIDATE HERO & ACTION BAR CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                
                {{-- Left: Avatar + Identity + Status Badge --}}
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        @if ($data->img_profile)
                            <img class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-2xl border-2 border-slate-100 shadow-sm"
                                src="{{ asset('storage/' . $data->img_profile) }}" alt="{{ $data->nama_pelamar }}">
                        @else
                            <img class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-2xl border-2 border-slate-100 shadow-sm"
                                src="https://ui-avatars.com/api/?name={{ urlencode($data->nama_pelamar ?? 'Pelamar') }}&background=64748b&color=fff&size=128"
                                alt="{{ $data->nama_pelamar }}">
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">
                                {{ $data->nama_pelamar ?: ($data->user->username ?? 'Pelamar') }}
                            </h2>
                            
                            {{-- Status Badge --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 whitespace-nowrap">
                                Pelamar Umum
                            </span>
                        </div>

                        <div class="flex items-center gap-2.5 text-xs text-slate-500 mt-1 flex-wrap">
                            <span class="font-medium text-slate-600">{{ '@' . ($data->user->username ?? 'user') }}</span>
                            <span>•</span>
                            <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-600 border border-slate-200/60">ID: #{{ $data->user->id ?? $data->id }}</span>
                            @if (!empty($data->divisi))
                                <span>•</span>
                                <span class="text-[#00509d] font-semibold">{{ is_array($data->divisi) ? implode(', ', $data->divisi) : $data->divisi }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Candidate Action Buttons --}}
                <div class="flex items-center gap-2.5 flex-wrap pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                    {{-- Preview CV --}}
                    <a href="{{ route('cv.preview', $data->id) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 border border-blue-200 text-[#00509d] hover:bg-blue-100 text-xs font-semibold transition shadow-sm whitespace-nowrap">
                        <svg class="w-4 h-4 text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Preview CV</span>
                    </a>

                    {{-- Unduh CV --}}
                    <a href="{{ route('cv.download', $data->id) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition shadow-sm whitespace-nowrap shadow-emerald-600/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh CV</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- MAIN TWO-COLUMN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ================= LEFT COLUMN: CANDIDATE PROFILE INFO ================= --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Demographic Summary Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Informasi Personal</span>
                    </h3>

                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                            <span class="block text-[11px] text-slate-400 font-medium">Gender</span>
                            <span class="text-xs font-semibold text-slate-700 mt-0.5 block truncate">
                                {{ $data->gender ?: '-' }}
                            </span>
                        </div>
                        <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                            <span class="block text-[11px] text-slate-400 font-medium">Usia</span>
                            <span class="text-xs font-semibold text-slate-700 mt-0.5 block">
                                {{ $data->umur ? $data->umur . ' Thn' : '-' }}
                            </span>
                        </div>
                        <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100">
                            <span class="block text-[11px] text-slate-400 font-medium">Kategori</span>
                            <span class="text-xs font-semibold text-slate-700 mt-0.5 block truncate capitalize">
                                {{ $data->kategori ?: 'Pelamar' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Contact & Address Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Kontak & Domisili</span>
                    </h3>

                    <div class="space-y-3.5 text-xs">
                        {{-- Email --}}
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 border border-slate-100">
                            <div class="p-2 rounded-lg bg-white border border-slate-200/70 text-slate-500">
                                <svg class="w-4 h-4 text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[11px] font-medium text-slate-400 block">Email Akun</span>
                                <a href="mailto:{{ $data->user->email ?? '' }}" class="text-xs font-semibold text-slate-800 hover:text-[#00509d] truncate block transition" title="{{ $data->user->email ?? '' }}">
                                    {{ $data->user->email ?? '-' }}
                                </a>
                            </div>
                        </div>

                        {{-- Telepon --}}
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 border border-slate-100">
                            <div class="p-2 rounded-lg bg-white border border-slate-200/70 text-slate-500">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[11px] font-medium text-slate-400 block">No. Telepon / WhatsApp</span>
                                @if ($data->telepon_pelamar)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->telepon_pelamar) }}" target="_blank"
                                        class="text-xs font-semibold text-slate-800 hover:text-emerald-600 truncate block transition">
                                        {{ $data->telepon_pelamar }}
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Belum diisi</span>
                                @endif
                            </div>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 border border-slate-100">
                            <div class="p-2 rounded-lg bg-white border border-slate-200/70 text-slate-500">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[11px] font-medium text-slate-400 block">Tanggal Lahir</span>
                                <span class="text-xs font-semibold text-slate-800 block">
                                    {{ $data->tanggal_lahir ? (\Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y')) : 'Belum diisi' }}
                                </span>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 border border-slate-100">
                            <div class="p-2 rounded-lg bg-white border border-slate-200/70 text-slate-500">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[11px] font-medium text-slate-400 block">Alamat Domisili</span>
                                @if ($primaryAlamat)
                                    <p class="text-xs font-semibold text-slate-800 leading-relaxed mt-0.5">
                                        {{ $primaryAlamat->detail ?: ($primaryAlamat->desa ?: '') }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $primaryAlamat->kecamatan ? $primaryAlamat->kecamatan . ', ' : '' }}
                                        {{ $primaryAlamat->kota ? $primaryAlamat->kota . ', ' : '' }}
                                        {{ $primaryAlamat->provinsi ? $primaryAlamat->provinsi : '' }}
                                        {{ $primaryAlamat->kode_pos ? ' (' . $primaryAlamat->kode_pos . ')' : '' }}
                                    </p>
                                @else
                                    <span class="text-slate-400 italic">Belum ada data alamat</span>
                                @endif
                            </div>
                        </div>

                        {{-- Ekspektasi Gaji --}}
                        @if ($data->gaji_minimal || $data->gaji_maksimal)
                            <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/60 border border-slate-100">
                                <div class="p-2 rounded-lg bg-white border border-slate-200/70 text-slate-500">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-[11px] font-medium text-slate-400 block">Ekspektasi Gaji</span>
                                    <span class="text-xs font-semibold text-slate-800 block">
                                        Rp {{ number_format($data->gaji_minimal ?? 0, 0, ',', '.') }} - Rp {{ number_format($data->gaji_maksimal ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Social Media & Portofolio Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <span>Media Sosial & Tautan</span>
                    </h3>

                    <div class="space-y-2.5 text-xs">
                        {{-- Instagram --}}
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/60 border border-slate-100 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-xs">
                                    <i class="ph ph-instagram-logo text-base"></i>
                                </div>
                                <span class="font-medium text-slate-700">Instagram</span>
                            </div>
                            @if (!empty($data->sosmed?->instagram))
                                <a href="{{ $formatUrl($data->sosmed->instagram, 'instagram') }}" target="_blank"
                                    class="text-[#00509d] hover:text-blue-700 font-semibold truncate max-w-[140px] text-right flex items-center gap-1">
                                    <span>{{ $data->sosmed->instagram }}</span>
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Belum diisi</span>
                            @endif
                        </div>

                        {{-- LinkedIn --}}
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/60 border border-slate-100 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                    <i class="ph ph-linkedin-logo text-base"></i>
                                </div>
                                <span class="font-medium text-slate-700">LinkedIn</span>
                            </div>
                            @if (!empty($data->sosmed?->linkedin))
                                <a href="{{ $formatUrl($data->sosmed->linkedin, 'linkedin') }}" target="_blank"
                                    class="text-[#00509d] hover:text-blue-700 font-semibold truncate max-w-[140px] text-right flex items-center gap-1">
                                    <span>{{ $data->sosmed->linkedin }}</span>
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Belum diisi</span>
                            @endif
                        </div>

                        {{-- Twitter / X --}}
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/60 border border-slate-100 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-slate-200 text-slate-800 flex items-center justify-center font-bold text-xs">
                                    <i class="ph ph-x-logo text-base"></i>
                                </div>
                                <span class="font-medium text-slate-700">Twitter (X)</span>
                            </div>
                            @if (!empty($data->sosmed?->twitter))
                                <a href="{{ $formatUrl($data->sosmed->twitter, 'twitter') }}" target="_blank"
                                    class="text-[#00509d] hover:text-blue-700 font-semibold truncate max-w-[140px] text-right flex items-center gap-1">
                                    <span>{{ $data->sosmed->twitter }}</span>
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Belum diisi</span>
                            @endif
                        </div>

                        {{-- Website / Portfolio --}}
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/60 border border-slate-100 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                    <i class="ph ph-globe text-base"></i>
                                </div>
                                <span class="font-medium text-slate-700">Website / Portfolio</span>
                            </div>
                            @if (!empty($data->sosmed?->website))
                                <a href="{{ $formatUrl($data->sosmed->website, 'website') }}" target="_blank"
                                    class="text-[#00509d] hover:text-blue-700 font-semibold truncate max-w-[140px] text-right flex items-center gap-1">
                                    <span>{{ $data->sosmed->website }}</span>
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Belum diisi</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- ================= RIGHT COLUMN: RESUME & CURRICULUM VITAE ================= --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- 1. Ringkasan Diri / Tentang Saya --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                        <div class="p-2 rounded-xl bg-blue-50 text-[#00509d]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Tentang Pelamar</h3>
                            <p class="text-xs text-slate-500">Ringkasan profil dan latar belakang profesional</p>
                        </div>
                    </div>

                    <div class="text-sm text-slate-600 leading-relaxed bg-slate-50/50 rounded-xl p-4 border border-slate-100">
                        @if ($data->deskripsi_diri)
                            <p class="whitespace-pre-line">{{ $data->deskripsi_diri }}</p>
                        @else
                            <p class="text-slate-400 italic text-xs">Pelamar belum mengisi deskripsi diri.</p>
                        @endif
                    </div>
                </div>

                {{-- 2. Keahlian & Keterampilan (Skills) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-amber-50 text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Keahlian & Kemampuan</h3>
                                <p class="text-xs text-slate-500">Keahlian teknis dan kompetensi yang dikuasai</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                            {{ $data->skill ? count($data->skill) : 0 }} Keahlian
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if ($data->skill && $data->skill->isNotEmpty())
                            @foreach ($data->skill as $s)
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50/70 border border-blue-200/70 text-blue-800 text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#00509d]"></span>
                                    <span>{{ $s->skill }}</span>
                                    @if (!empty($s->experience_level))
                                        <span class="text-[10px] font-normal px-1.5 py-0.5 rounded bg-blue-200/60 text-[#003d7a]">
                                            {{ $s->experience_level }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-xs text-slate-400 italic">Belum ada data keahlian yang ditambahkan.</p>
                        @endif
                    </div>
                </div>

                {{-- 3. Pengalaman Kerja (Work Experience) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Pengalaman Kerja</h3>
                                <p class="text-xs text-slate-500">Riwayat karir dan pengalaman profesional</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                            {{ $data->pengalaman_kerja ? $data->pengalaman_kerja->count() : 0 }} Riwayat
                        </span>
                    </div>

                    @if ($data->pengalaman_kerja && $data->pengalaman_kerja->isNotEmpty())
                        <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                            @foreach ($data->pengalaman_kerja as $kerja)
                                <div class="relative group">
                                    {{-- Timeline bullet --}}
                                    <div class="absolute -left-[29px] top-1 w-3.5 h-3.5 rounded-full bg-white border-2 border-indigo-600 group-hover:scale-125 transition"></div>

                                    <div class="bg-slate-50/70 hover:bg-slate-50 rounded-xl p-4 border border-slate-200/70 transition">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1.5">
                                            <h4 class="text-sm font-bold text-slate-900">
                                                {{ $kerja->posisi_pekerjaan ?: ($kerja->jabatan_pekerjaan ?: 'Posisi Kerja') }}
                                            </h4>
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md self-start sm:self-auto">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $kerja->tahun_awal }} - {{ $kerja->tahun_akhir ?: 'Sekarang' }}
                                            </span>
                                        </div>

                                        <p class="text-xs font-semibold text-slate-600 mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            {{ $kerja->nama_perusahaan ?: 'Perusahaan' }}
                                        </p>

                                        @if ($kerja->deskripsi)
                                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line border-t border-slate-200/60 pt-2 mt-2">
                                                {{ $kerja->deskripsi }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-xs text-slate-400 italic">Belum ada riwayat pengalaman kerja.</p>
                        </div>
                    @endif
                </div>

                {{-- 4. Riwayat Pendidikan (Education History) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Riwayat Pendidikan</h3>
                                <p class="text-xs text-slate-500">Latar belakang pendidikan formal dan akademik</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                            {{ $data->riwayat_pendidikan ? $data->riwayat_pendidikan->count() : 0 }} Pendidikan
                        </span>
                    </div>

                    @if ($data->riwayat_pendidikan && $data->riwayat_pendidikan->isNotEmpty())
                        <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                            @foreach ($data->riwayat_pendidikan as $pend)
                                <div class="relative group">
                                    {{-- Timeline bullet --}}
                                    <div class="absolute -left-[29px] top-1 w-3.5 h-3.5 rounded-full bg-white border-2 border-emerald-600 group-hover:scale-125 transition"></div>

                                    <div class="bg-slate-50/70 hover:bg-slate-50 rounded-xl p-4 border border-slate-200/70 transition">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1.5">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                    {{ $pend->pendidikan ?: 'Pendidikan' }}
                                                </span>
                                                <h4 class="text-sm font-bold text-slate-900">
                                                    {{ $pend->asal_pendidikan }}
                                                </h4>
                                            </div>
                                            <span class="text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md self-start sm:self-auto">
                                                {{ $pend->tahun_awal }} - {{ $pend->tahun_akhir ?: 'Sekarang' }}
                                            </span>
                                        </div>

                                        @if ($pend->jurusan)
                                            <p class="text-xs text-slate-600 mt-1">
                                                Jurusan / Program Studi: <span class="font-medium text-slate-800">{{ $pend->jurusan }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-xs text-slate-400 italic">Belum ada data riwayat pendidikan.</p>
                        </div>
                    @endif
                </div>

                {{-- 5. Pengalaman Organisasi (Organization Experience) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-purple-50 text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Pengalaman Organisasi</h3>
                                <p class="text-xs text-slate-500">Aktivitas organisasi, kepemimpinan, dan kepanitiaan</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                            {{ $data->pengalaman_organisasi ? $data->pengalaman_organisasi->count() : 0 }} Organisasi
                        </span>
                    </div>

                    @if ($data->pengalaman_organisasi && $data->pengalaman_organisasi->isNotEmpty())
                        <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                            @foreach ($data->pengalaman_organisasi as $org)
                                <div class="relative group">
                                    {{-- Timeline bullet --}}
                                    <div class="absolute -left-[29px] top-1 w-3.5 h-3.5 rounded-full bg-white border-2 border-purple-600 group-hover:scale-125 transition"></div>

                                    <div class="bg-slate-50/70 hover:bg-slate-50 rounded-xl p-4 border border-slate-200/70 transition">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1.5">
                                            <h4 class="text-sm font-bold text-slate-900">
                                                {{ $org->jabatan }} &mdash; <span class="text-purple-700 font-semibold">{{ $org->nama_organisasi }}</span>
                                            </h4>
                                            <span class="text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md self-start sm:self-auto">
                                                {{ $org->tahun_awal }} - {{ $org->tahun_akhir ?: 'Sekarang' }}
                                            </span>
                                        </div>

                                        @if ($org->deskripsi)
                                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line border-t border-slate-200/60 pt-2 mt-2">
                                                {{ $org->deskripsi }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-xs text-slate-400 italic">Belum ada riwayat pengalaman organisasi.</p>
                        </div>
                    @endif
                </div>

                {{-- 6. CV & Lampiran Berkas Banner --}}
                <div class="bg-gradient-to-r from-[#003d7a] via-[#00509d] to-slate-900 rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4 text-center sm:text-left">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center flex-shrink-0 border border-white/20">
                            <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold">Curriculum Vitae (CV) Pelamar</h4>
                            <p class="text-xs text-slate-300 mt-0.5">Tinjau atau unduh dokumen CV resmi format AreaKerja</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 flex-shrink-0">
                        <a href="{{ route('cv.preview', $data->id) }}" target="_blank"
                            class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold transition backdrop-blur-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Preview</span>
                        </a>
                        <a href="{{ route('cv.download', $data->id) }}"
                            class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold transition shadow-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh PDF</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modals Notifikasi --}}
        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')

    </main>
@endsection
