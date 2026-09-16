@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        @php
            $kategori = $kategori ?? 'kandidat';
        @endphp

        {{-- TOP NAVIGATION & ADMIN BAR --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.pelamar') }}"
                    class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0"
                    title="Kembali ke Data Pelamar">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Pelamar / <span class="text-slate-500 font-medium">Edit Data</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">
                        @if ($kategori === 'non_kandidat')
                            Edit Pelamar
                        @elseif ($kategori === 'calon_kandidat')
                            Edit Calon Kandidat
                        @elseif ($kategori === 'kandidat')
                            Edit Kandidat
                        @else
                            Edit Data Pelamar
                        @endif
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        {{-- FORM CONTAINER --}}
        <div class="max-w-5xl mx-auto space-y-6">

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show"
                    class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-center justify-between text-xs sm:text-sm shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button class="text-rose-400 hover:text-rose-700 text-lg font-bold" @click="show=false">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs sm:text-sm shadow-sm space-y-1">
                    <span class="font-bold block text-rose-900 mb-1">Terdapat kesalahan input:</span>
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-1.5 text-rose-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- MAIN FORM --}}
            <form action="{{ route('superadmin.pelamar.update', $pelamar->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- CARD 1: FOTO & IDENTITAS AKUN --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                        <div class="p-2 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Informasi Akun & Data Diri</h2>
                            <p class="text-xs text-slate-500">Identitas dan kredensial akun kandidat</p>
                        </div>
                    </div>

                    {{-- Upload Foto Profil --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 p-4 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <div class="relative flex-shrink-0">
                            <img id="pp" class="w-24 h-24 object-cover rounded-2xl border-2 border-white shadow-sm bg-white"
                                src="{{ $pelamar && $pelamar->img_profile
                                    ? asset('storage/' . $pelamar->img_profile)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($pelamar->nama_pelamar ?? 'Kandidat') . '&background=00509d&color=fff&size=128' }}"
                                alt="Preview Foto">
                        </div>

                        <div class="space-y-2 text-center sm:text-left">
                            <span class="block text-xs font-bold text-slate-800">Foto Profil Kandidat</span>
                            <p class="text-[11px] text-slate-500">Format: JPG, PNG, atau WebP. Maksimal 2MB.</p>
                            
                            <div class="flex items-center justify-center sm:justify-start gap-2.5 pt-1">
                                <label class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold cursor-pointer shadow-sm transition">
                                    <input type="file" name="img_profile" id="fileinput" accept="image/*" class="hidden">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span>Ganti Foto</span>
                                </label>

                                <button type="button" id="removeButton"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Form Fields Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                        {{-- Username (Readonly) --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Username <span class="text-slate-400 font-normal">(Tidak dapat diubah)</span>
                            </label>
                            <input type="text" name="username" value="{{ $pelamar?->user?->username }}" readonly
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-500 bg-slate-50 cursor-not-allowed focus:outline-none" />
                        </div>

                        {{-- Email (Readonly) --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Email <span class="text-slate-400 font-normal">(Tidak dapat diubah)</span>
                            </label>
                            <input type="email" name="email" value="{{ $pelamar?->user?->email }}" readonly
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-500 bg-slate-50 cursor-not-allowed focus:outline-none" />
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_pelamar" value="{{ old('nama_pelamar', $pelamar->nama_pelamar ?? '') }}"
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                placeholder="Nama Lengkap" required />
                        </div>

                        {{-- No. Telepon --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="telepon_pelamar" value="{{ old('telepon_pelamar', $pelamar->telepon_pelamar ?? '') }}"
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                placeholder="08xxxxxxxxxx" required />
                        </div>

                        {{-- Gender --}}
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition">
                                    <input type="radio" name="gender" value="laki-laki"
                                        class="accent-blue-600 text-blue-600 focus:ring-blue-500"
                                        {{ old('gender', $pelamar->gender ?? '') == 'laki-laki' ? 'checked' : '' }} required>
                                    <span class="font-medium text-slate-800 text-xs">Laki-Laki</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition">
                                    <input type="radio" name="gender" value="perempuan"
                                        class="accent-blue-600 text-blue-600 focus:ring-blue-500"
                                        {{ old('gender', $pelamar->gender ?? '') == 'perempuan' ? 'checked' : '' }}>
                                    <span class="font-medium text-slate-800 text-xs">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        {{-- Divisi --}}
                        @php
                            $selectedDivisi = is_array($pelamar?->divisi)
                                ? $pelamar?->divisi
                                : json_decode($pelamar?->divisi, true);
                            $selectedDivisi = old('divisi', $selectedDivisi ?? []);
                        @endphp

                        <div id="divisi-wrapper" class="md:col-span-2 {{ in_array($kategori, ['calon_kandidat', 'kandidat']) ? '' : 'hidden' }}">
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Bidang / Divisi yang Diminati <span class="text-rose-500">*</span>
                            </label>
                            <select id="divisi" name="divisi[]" multiple class="w-full">
                                @foreach ($divisis as $divisi)
                                    <option value="{{ $divisi->divisi }}"
                                        {{ in_array($divisi->divisi, $selectedDivisi) ? 'selected' : '' }}>
                                        {{ $divisi->divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: DATA PELENGKAP (Alamat, Pendidikan, Organisasi, Pengalaman, Skill) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                        <div class="p-2 rounded-xl bg-purple-50 text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Data Pelengkap & Riwayat</h2>
                            <p class="text-xs text-slate-500">Kelola riwayat pendidikan, pengalaman, dan keahlian</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- 1. Alamat --}}
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 flex-shrink-0">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Alamat Domisili</h4>
                                    @if ($pelamar && $pelamar->alamat_pelamar->count() > 0)
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $pelamar->alamat_pelamar->first()->detail ?? $pelamar->alamat_pelamar->first()->desa }},
                                            {{ $pelamar->alamat_pelamar->first()->kota }}
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 italic mt-0.5">Belum ada data alamat</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                @if ($pelamar && $pelamar->alamat_pelamar->count() > 0)
                                    <button data-modal-target="show-alamat" data-modal-toggle="show-alamat" type="button"
                                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition shadow-sm">
                                        Lihat / Edit
                                    </button>
                                @endif
                                <button type="button" data-modal-target="create_alamatmodal" data-modal-toggle="create_alamatmodal"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>

                        {{-- 2. Pendidikan --}}
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Riwayat Pendidikan</h4>
                                    @if ($pelamar && $pelamar->riwayat_pendidikan->count() > 0)
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $pelamar->riwayat_pendidikan->count() }} data pendidikan terdaftar
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 italic mt-0.5">Belum ada riwayat pendidikan</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                @if ($pelamar && $pelamar->riwayat_pendidikan->count() > 0)
                                    <button data-modal-target="show-pendidikan" data-modal-toggle="show-pendidikan" type="button"
                                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition shadow-sm">
                                        Lihat / Edit
                                    </button>
                                @endif
                                <button type="button" data-modal-target="create_pendidikanmodal" data-modal-toggle="create_pendidikanmodal"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>

                        {{-- 3. Pengalaman Kerja --}}
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Pengalaman Kerja</h4>
                                    @if ($pelamar && $pelamar->pengalaman_kerja->count() > 0)
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $pelamar->pengalaman_kerja->count() }} riwayat karir terdaftar
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 italic mt-0.5">Belum ada pengalaman kerja</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                @if ($pelamar && $pelamar->pengalaman_kerja->count() > 0)
                                    <button data-modal-target="show-kerja" data-modal-toggle="show-kerja" type="button"
                                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition shadow-sm">
                                        Lihat / Edit
                                    </button>
                                @endif
                                <button type="button" data-modal-target="create_kerjamodal" data-modal-toggle="create_kerjamodal"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>

                        {{-- 4. Pengalaman Organisasi --}}
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Pengalaman Organisasi</h4>
                                    @if ($pelamar && $pelamar->pengalaman_organisasi->count() > 0)
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $pelamar->pengalaman_organisasi->count() }} aktivitas organisasi terdaftar
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 italic mt-0.5">Belum ada pengalaman organisasi</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                @if ($pelamar && $pelamar->pengalaman_organisasi->count() > 0)
                                    <button data-modal-target="show-org" data-modal-toggle="show-org" type="button"
                                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition shadow-sm">
                                        Lihat / Edit
                                    </button>
                                @endif
                                <button type="button" data-modal-target="create_organisasimodal" data-modal-toggle="create_organisasimodal"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>

                        {{-- 5. Keahlian (Skills) --}}
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 flex-shrink-0">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Keahlian & Kompetensi</h4>
                                    @if ($pelamar && $pelamar->skill->count() > 0)
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $pelamar->skill->count() }} keahlian ditambahkan
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 italic mt-0.5">Belum ada data keahlian</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                @if ($pelamar && $pelamar->skill->count() > 0)
                                    <button data-modal-target="show-skill" data-modal-toggle="show-skill" type="button"
                                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition shadow-sm">
                                        Lihat / Edit
                                    </button>
                                @endif
                                <button type="button" data-modal-target="create_skillmodal" data-modal-toggle="create_skillmodal"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: MEDIA SOSIAL --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                        <div class="p-2 rounded-xl bg-pink-50 text-pink-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Media Sosial & Portofolio</h2>
                            <p class="text-xs text-slate-500">Tautan akun jejaring sosial dan website profil kandidat</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        {{-- Instagram --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <i class="ph ph-instagram-logo text-pink-600 text-sm"></i>
                                <span>Instagram</span>
                            </label>
                            <input type="text" name="social_media[instagram]"
                                value="{{ old('social_media.instagram', $pelamar?->sosmed?->instagram) }}"
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                placeholder="Username atau link Instagram" />
                        </div>

                        {{-- LinkedIn --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <i class="ph ph-linkedin-logo text-blue-700 text-sm"></i>
                                <span>LinkedIn</span>
                            </label>
                            <input type="text" name="social_media[linkedin]"
                                value="{{ old('social_media.linkedin', $pelamar?->sosmed?->linkedin) }}"
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                placeholder="Link profil LinkedIn" />
                        </div>

                        {{-- Website --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <i class="ph ph-globe text-emerald-600 text-sm"></i>
                                <span>Website / Portofolio</span>
                            </label>
                            <input type="text" name="social_media[website]"
                                value="{{ old('social_media.website', $pelamar?->sosmed?->website) }}"
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                placeholder="https://portofolio-anda.com" />
                        </div>

                        {{-- Twitter / X --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5 flex items-center gap-1.5">
                                <i class="ph ph-x-logo text-slate-800 text-sm"></i>
                                <span>Twitter (X)</span>
                            </label>
                            <input type="text" name="social_media[twitter]"
                                value="{{ old('social_media.twitter', $pelamar?->sosmed?->twitter) }}"
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                placeholder="Username atau link Twitter/X" />
                        </div>
                    </div>
                </div>

                {{-- SUBMIT & ACTION BUTTONS --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('superadmin.pelamar') }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition shadow-sm shadow-blue-500/25">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </form>
        </div>

        {{-- Modals Notifikasi --}}
        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')

        {{-- Modals Data Pelengkap --}}
        @include('super_admin.pelamar.modal.alamat2')
        @include('super_admin.pelamar.modal.pendidikan2')
        @include('super_admin.pelamar.modal.organisasi2')
        @include('super_admin.pelamar.modal.pengalaman2')
        @include('super_admin.pelamar.modal.skill2')

        {{-- Detail Modals --}}
        @include('super_admin.pelamar.modal.detail_pendidikan2')
        @include('super_admin.pelamar.modal.detail_organisasi2')
        @include('super_admin.pelamar.modal.detail_pengalaman2')
        @include('super_admin.pelamar.modal.detail_skill2')
        @include('super_admin.pelamar.modal.detail_alamat')

        {{-- Tom Select CSS & JS --}}
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        {{-- Script Upload Foto & TomSelect --}}
        <script>
            document.getElementById('fileinput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('pp').setAttribute('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('removeButton').addEventListener('click', function() {
                const img = document.getElementById('pp');
                const fileInput = document.getElementById('fileinput');
                img.setAttribute('src', 'https://ui-avatars.com/api/?name=Kandidat&background=00509d&color=fff&size=128');
                fileInput.value = '';
            });

            document.addEventListener('DOMContentLoaded', function() {
                const divisiEl = document.getElementById('divisi');
                if (divisiEl) {
                    const divisiSelect = new TomSelect("#divisi", {
                        plugins: ['remove_button'],
                        persist: false,
                        create: false,
                        hideSelected: true,
                        maxItems: 5,
                        placeholder: "Pilih divisi yang diminati...",
                        render: {
                            item: function(data, escape) {
                                return `<div class="py-1 px-2.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold mr-1 mb-1">${escape(data.text)}</div>`;
                            }
                        }
                    });

                    const kategori = "{{ $kategori }}";
                    if (kategori !== 'calon_kandidat' && kategori !== 'kandidat') {
                        document.getElementById('divisi-wrapper')?.classList.add('hidden');
                        divisiSelect.clear();
                    }
                }
            });
        </script>

        {{-- Notif Script --}}
        <script>
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
                        el.classList.add("bg-gray-200");
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
                            document.querySelectorAll('.notif-item.bg-gray-200').forEach(e => e.remove());
                        }
                    }
                }));
            });
        </script>
    </main>
@endsection
