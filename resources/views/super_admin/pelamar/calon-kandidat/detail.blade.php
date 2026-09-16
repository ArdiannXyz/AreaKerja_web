@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-[#f8fafc] min-h-screen overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">

        {{-- TOP NAVIGATION & ADMIN USER BAR --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 pb-4 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.pelamar') }}"
                    class="p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 transition shadow-sm flex items-center justify-center"
                    title="Kembali ke Data Pelamar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight whitespace-nowrap">Detail Calon Kandidat</h1>
                    <p class="text-xs text-slate-500">Kelola masa pelatihan dan status kelulusan calon kandidat</p>
                </div>
            </div>
            <div class="flex items-center gap-3 self-end sm:self-auto">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
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

        {{-- CANDIDATE HERO CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                {{-- Left: Avatar + Identity --}}
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        @if ($pelamar->img_profile)
                            <img class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-2xl border-2 border-slate-100 shadow-sm"
                                src="{{ asset('storage/' . $pelamar->img_profile) }}" alt="{{ $pelamar->nama_pelamar }}">
                        @else
                            <img class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-2xl border-2 border-slate-100 shadow-sm"
                                src="https://ui-avatars.com/api/?name={{ urlencode($pelamar->nama_pelamar ?? 'Calon') }}&background=d97706&color=fff&size=128"
                                alt="{{ $pelamar->nama_pelamar }}">
                        @endif
                        <div class="absolute -bottom-1 -right-1 bg-amber-500 text-white p-1 rounded-full border-2 border-white shadow-sm" title="Calon Kandidat">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">
                                {{ $pelamar->nama_pelamar ?: ($pelamar->user->username ?? 'Calon Kandidat') }}
                            </h2>
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Calon Kandidat
                            </span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs text-slate-500 mt-1 flex-wrap">
                            <span class="font-medium text-slate-600">{{ '@' . ($pelamar->user->username ?? 'user') }}</span>
                            @if (!empty($pelamar->divisi))
                                <span>•</span>
                                <span class="text-blue-600 font-semibold">
                                    {{ is_array($pelamar->divisi) ? implode(', ', $pelamar->divisi) : $pelamar->divisi }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Action Buttons --}}
                <div class="flex items-center gap-2.5 flex-wrap pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                    {{-- Lulus --}}
                    <form action="{{ route('superadmin.calon.lulus', $pelamar->id) }}" method="POST" class="inline"
                        onsubmit="return confirm('Tandai kandidat {{ $pelamar->nama_pelamar }} sebagai LULUS? Data akan dipindah ke Kandidat Aktif.');">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition shadow-sm shadow-emerald-600/20 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Lulus</span>
                        </button>
                    </form>

                    {{-- Gugur --}}
                    <form action="{{ route('superadmin.calon.gugur', $pelamar->id) }}" method="POST" class="inline"
                        onsubmit="return confirm('Tandai kandidat {{ $pelamar->nama_pelamar }} sebagai GUGUR? Status akan diubah.');">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-600 text-xs font-semibold transition shadow-sm whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Gugur</span>
                        </button>
                    </form>

                    {{-- Preview CV --}}
                    <a href="{{ route('cv.preview', $pelamar->id) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition shadow-sm whitespace-nowrap">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Preview CV</span>
                    </a>

                    {{-- Unduh CV --}}
                    <a href="{{ route('cv.download', $pelamar->id) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition shadow-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh CV</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Periode Pelatihan Card (Form) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="p-2 rounded-xl bg-amber-50 text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Masa Pelatihan</h3>
                            <p class="text-xs text-slate-500">Atur jadwal periode pelatihan kandidat</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('superadmin.calon.update', $pelamar->id) }}" class="space-y-4">
                        @csrf

                        {{-- Divisi (Display only) --}}
                        @if (!empty($pelamar->divisi))
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Divisi / Posisi</label>
                                <div class="px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800">
                                    {{ is_array($pelamar->divisi) ? implode(', ', $pelamar->divisi) : $pelamar->divisi }}
                                </div>
                            </div>
                        @endif

                        {{-- Tanggal Mulai --}}
                        <div>
                            <label for="mulai_pelatihan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Tanggal Mulai Pelatihan
                            </label>
                            <input type="date" name="mulai_pelatihan" id="mulai_pelatihan"
                                value="{{ $pelamar->mulai_pelatihan ? \Carbon\Carbon::parse($pelamar->mulai_pelatihan)->format('Y-m-d') : '' }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 transition">
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <label for="selesai_pelatihan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Tanggal Selesai Pelatihan
                            </label>
                            <input type="date" name="selesai_pelatihan" id="selesai_pelatihan"
                                value="{{ $pelamar->selesai_pelatihan ? \Carbon\Carbon::parse($pelamar->selesai_pelatihan)->format('Y-m-d') : '' }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 transition">
                        </div>

                        {{-- Durasi Info --}}
                        @if ($pelamar->mulai_pelatihan && $pelamar->selesai_pelatihan)
                            @php
                                $mulai = \Carbon\Carbon::parse($pelamar->mulai_pelatihan);
                                $selesai = \Carbon\Carbon::parse($pelamar->selesai_pelatihan);
                                $durasi = $mulai->diffInDays($selesai);
                            @endphp
                            <div class="flex items-center gap-2.5 px-3.5 py-2.5 bg-amber-50 rounded-xl border border-amber-100 text-xs">
                                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-amber-800 font-medium">Durasi: <strong>{{ $durasi }} hari</strong> pelatihan</span>
                            </div>
                        @endif

                        <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition shadow-sm shadow-amber-500/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Simpan Jadwal Pelatihan
                        </button>
                    </form>
                </div>

                {{-- Status Decision Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="p-2 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Keputusan Status</h3>
                            <p class="text-xs text-slate-500">Tetapkan status akhir calon kandidat</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        {{-- Lulus --}}
                        <form action="{{ route('superadmin.calon.lulus', $pelamar->id) }}" method="POST"
                            onsubmit="return confirm('Tandai kandidat sebagai LULUS? Kandidat akan dipindah ke Kandidat Aktif.');">
                            @csrf
                            <button type="submit"
                                class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition shadow-sm shadow-emerald-600/20 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Nyatakan LULUS — Pindah ke Kandidat Aktif
                            </button>
                        </form>

                        {{-- Gugur --}}
                        <form action="{{ route('superadmin.calon.gugur', $pelamar->id) }}" method="POST"
                            onsubmit="return confirm('Tandai kandidat sebagai GUGUR? Status akan diubah ke Pelamar.');">
                            @csrf
                            <button type="submit"
                                class="w-full py-3 rounded-xl border-2 border-rose-200 hover:border-rose-300 hover:bg-rose-50 text-rose-600 text-sm font-semibold transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Nyatakan GUGUR — Kembalikan ke Pelamar
                            </button>
                        </form>

                        <p class="text-[11px] text-slate-400 text-center mt-2">Keputusan ini akan mempengaruhi status kandidat secara permanen</p>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: Full CV Profile --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Informasi Kontak --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                        <div class="p-2 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Informasi Kandidat</h3>
                            <p class="text-xs text-slate-500">Data diri dan kontak</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        @php $alamat = $pelamar->alamat_pelamar->first(); @endphp

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block mb-1">Email</span>
                            <span class="font-semibold text-slate-800">{{ $pelamar->user->email ?? '-' }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block mb-1">No. Telepon</span>
                            <span class="font-semibold text-slate-800">{{ $pelamar->telepon_pelamar ?: '-' }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block mb-1">Gender</span>
                            <span class="font-semibold text-slate-800">{{ $pelamar->gender ?: '-' }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 font-medium block mb-1">Usia</span>
                            <span class="font-semibold text-slate-800">{{ $pelamar->umur ? $pelamar->umur . ' Tahun' : '-' }}</span>
                        </div>
                        @if ($alamat)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 col-span-2">
                                <span class="text-slate-400 font-medium block mb-1">Domisili</span>
                                <span class="font-semibold text-slate-800">
                                    {{ $alamat->kota ? $alamat->kota . ', ' : '' }}{{ $alamat->provinsi ?? '-' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pendidikan --}}
                @if ($pelamar->riwayat_pendidikan->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                            <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Riwayat Pendidikan</h3>
                        </div>
                        <div class="space-y-3">
                            @foreach ($pelamar->riwayat_pendidikan as $pend)
                                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 flex-shrink-0 mt-0.5">{{ $pend->pendidikan }}</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $pend->asal_pendidikan }}</p>
                                        @if ($pend->jurusan)
                                            <p class="text-xs text-slate-500">{{ $pend->jurusan }}</p>
                                        @endif
                                        <p class="text-xs text-slate-400">{{ $pend->tahun_awal }} - {{ $pend->tahun_akhir ?: 'Sekarang' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Keahlian --}}
                @if ($pelamar->skill->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                            <div class="p-2 rounded-xl bg-amber-50 text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Keahlian & Kemampuan</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($pelamar->skill as $s)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50/70 border border-amber-200/70 text-amber-800 text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    {{ $s->skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- CV Banner --}}
                <div class="bg-gradient-to-r from-amber-700 via-orange-700 to-slate-800 rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center flex-shrink-0 border border-white/20">
                            <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold">Curriculum Vitae (CV)</h4>
                            <p class="text-xs text-white/70 mt-0.5">Tinjau atau unduh dokumen CV resmi calon kandidat</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 flex-shrink-0">
                        <a href="{{ route('cv.preview', $pelamar->id) }}" target="_blank"
                            class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold transition backdrop-blur-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>Preview</span>
                        </a>
                        <a href="{{ route('cv.download', $pelamar->id) }}"
                            class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold transition shadow-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Unduh PDF</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modals Notifikasi --}}
        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')

    </main>

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
                        if (count > 1) badge.textContent = count - 1;
                        else badge.remove();
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
                    let res = await fetch(url, { method: "DELETE", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" } });
                    let data = await res.json();
                    if (data.success) document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                },
                async hapusSemua() {
                    if (!confirm("Hapus semua notifikasi?")) return;
                    let res = await fetch("{{ route('notifikasi.hapusSemua') }}", { method: "DELETE", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" } });
                    let data = await res.json();
                    if (data.success) document.querySelectorAll('.notif-item').forEach(e => e.remove());
                },
                async hapusSemuaBaca() {
                    if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;
                    let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", { method: "DELETE", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" } });
                    let data = await res.json();
                    if (data.success) document.querySelectorAll('.notif-item.bg-gray-200').forEach(e => e.remove());
                }
            }));
        });
    </script>
@endsection
