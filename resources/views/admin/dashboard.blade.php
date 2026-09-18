@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-squares-four text-[#00509d] text-2xl"></i> Dashboard Overview
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Selamat datang kembali, <span class="text-[#003d7a] font-bold">{{ Auth::user()->username }}</span>! Berikut ringkasan aktivitas sistem hari ini.</p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- CARDS STATISTIK KONSISTEN & BERSIH -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            <!-- PERUSAHAAN -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perusahaan</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center">
                        <i class="ph ph-buildings text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalPerusahaan }}</span>
                    <span class="text-xs font-medium text-slate-400">Terdaftar</span>
                </div>
            </div>

            <!-- KANDIDAT -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kandidat Aktif</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph ph-user-check text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalKandidat }}</span>
                    <span class="text-xs font-medium text-slate-400">Siap Kerja</span>
                </div>
            </div>

            <!-- NON KANDIDAT -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pelamar / Public</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph ph-users text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalNonKandidat }}</span>
                    <span class="text-xs font-medium text-slate-400">Pengguna</span>
                </div>
            </div>

            <!-- LOWONGAN -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lowongan Kerja</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="ph ph-briefcase text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalLowongan }}</span>
                    <span class="text-xs font-medium text-slate-400">Tersedia</span>
                </div>
            </div>

        </div>

        <!-- DUA KOLOM AKTIVITAS TERBARU -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <!-- KOLOM KIRI (2 SPAN): LOWONGAN TERBARU -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Lowongan Terbaru Terbit</h3>
                        <p class="text-xs text-slate-500 font-medium">Data lowongan kerja yang baru saja dipublikasikan</p>
                    </div>
                    <a href="{{ route('admin.perusahaan') }}" class="text-xs font-bold text-[#00509d] hover:text-[#003d7a] flex items-center gap-1 transition">
                        Lihat Semua <i class="ph ph-arrow-right"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Lowongan</th>
                                <th class="px-4 py-3">Perusahaan</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($latestLowongans ?? [] as $lowongan)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-extrabold text-slate-800">
                                        {{ $lowongan->nama }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 font-medium">
                                        {{ $lowongan->perusahaan->nama_perusahaan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 font-semibold rounded-lg text-xs">
                                            {{ $lowongan->jenis ?? 'Full Time' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if (($lowongan->status ?? 'buka') === 'tutup')
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200/60 font-semibold rounded-full text-xs">
                                                <i class="ph ph-lock-key mr-1"></i> Ditutup
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200/60 font-semibold rounded-full text-xs">
                                                <i class="ph ph-check-circle mr-1"></i> Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-400 font-medium">
                                        <i class="ph ph-briefcase text-3xl mb-1 block"></i>
                                        Belum ada data lowongan terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KOLOM KANAN (1 SPAN): PERUSAHAAN BARU TERDAFTAR -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Perusahaan Baru</h3>
                        <p class="text-xs text-slate-500 font-medium">Mitra perusahaan yang baru mendaftar</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse ($latestPerusahaans ?? [] as $perusahaan)
                        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200">
                            @if ($perusahaan->img_profile)
                                <img src="{{ asset('storage/' . $perusahaan->img_profile) }}" class="w-10 h-10 object-contain rounded-xl border border-slate-200 p-0.5 bg-white flex-shrink-0" alt="Logo">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00509d] to-[#0077b6] text-white font-bold flex items-center justify-center text-sm flex-shrink-0 shadow-xs">
                                    {{ strtoupper(substr($perusahaan->nama_perusahaan ?? 'P', 0, 1)) }}
                                </div>
                            @endif

                            <div class="overflow-hidden flex-1">
                                <h4 class="text-xs font-extrabold text-slate-800 truncate">{{ $perusahaan->nama_perusahaan }}</h4>
                                <p class="text-[11px] text-slate-500 font-medium truncate">{{ $perusahaan->jenis_perusahaan ?? 'Industri Umum' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-6">Belum ada data perusahaan terbaru.</p>
                    @endforelse
                </div>
            </div>

        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')

    </main>
@endsection
