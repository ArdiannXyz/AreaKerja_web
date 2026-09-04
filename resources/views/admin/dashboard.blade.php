@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:p-6 sm:ml-64 bg-slate-50 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-squares-four text-[#00509d] text-2xl"></i> Dashboard Overview
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Selamat datang kembali, <span class="text-[#003d7a] font-bold">{{ Auth::user()->username }}</span>! Berikut ringkasan aktivitas sistem hari ini.</p>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto justify-end">
                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative p-2.5 bg-slate-100 hover:bg-blue-50 hover:text-[#003d7a] rounded-xl text-slate-600 transition shadow-xs">
                    <i class="ph ph-bell text-xl"></i>
                    @if (isset($global_notifikasi_unread) && $global_notifikasi_unread > 0)
                        <span id="notif-badge" class="absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] font-extrabold px-1.5 py-0.5 rounded-full animate-pulse border-2 border-white">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profil Admin Pill --}}
                <div class="flex items-center gap-3 bg-slate-100/80 px-3.5 py-2 rounded-2xl border border-slate-200">
                    @if (Auth::user()?->avatar)
                        <img id="pu" class="w-9 h-9 object-cover rounded-xl profile-img border border-slate-200"
                            src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
                    @else
                        <img id="pu" class="w-9 h-9 rounded-xl border border-slate-200"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'Admin') }}&background=f97316&color=fff&size=128">
                    @endif

                    <div class="text-left">
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-slate-800 text-xs leading-tight">{{ Auth::user()->username }}</span>
                            <span class="bg-blue-100 text-[#003d7a] text-[10px] font-extrabold px-1.5 py-0.2 rounded-md">Admin</span>
                        </div>
                        <p class="text-slate-500 text-[11px] leading-tight mt-0.5">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- CARDS STATISTIK KONSISTEN & BERSIH -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            <!-- PERUSAHAAN -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perusahaan</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalPerusahaan }}</span>
                    <i class="ph ph-buildings text-2xl text-slate-400"></i>
                </div>
            </div>

            <!-- KANDIDAT -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kandidat Aktif</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalKandidat }}</span>
                    <i class="ph ph-user-check text-2xl text-slate-400"></i>
                </div>
            </div>

            <!-- NON KANDIDAT -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pelamar / Public</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalNonKandidat }}</span>
                    <i class="ph ph-users text-2xl text-slate-400"></i>
                </div>
            </div>

            <!-- LOWONGAN -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-xs hover:border-slate-300 transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lowongan Kerja</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalLowongan }}</span>
                    <i class="ph ph-briefcase text-2xl text-slate-400"></i>
                </div>
            </div>

        </div>



        <!-- DUA KOLOM AKTIVITAS TERBARU -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <!-- KOLOM KIRI (2 SPAN): LOWONGAN TERBARU -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Lowongan Terbaru Terbit</h3>
                        <p class="text-xs text-slate-500 font-medium">Data lowongan kerja yang baru saja terpasang</p>
                    </div>
                    <a href="{{ route('admin.perusahaan') }}" class="text-xs font-bold text-[#003d7a] hover:text-[#003d7a] flex items-center gap-1">
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
                                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 font-bold rounded-md text-[11px]">
                                            {{ $lowongan->jenis ?? 'Full Time' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if (($lowongan->status ?? 'buka') === 'tutup')
                                            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-700 font-bold rounded-full text-[10px]">
                                                ðŸ”’ Ditutup
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 font-bold rounded-full text-[10px]">
                                                ðŸŸ¢ Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400 font-medium">
                                        Belum ada data lowongan terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KOLOM KANAN (1 SPAN): PERUSAHAAN BARU TERDAFTAR -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Perusahaan Baru</h3>
                        <p class="text-xs text-slate-500 font-medium">Mitra perusahaan yang baru mendaftar</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($latestPerusahaans ?? [] as $perusahaan)
                        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200">
                            @if ($perusahaan->img_profile)
                                <img src="{{ asset('storage/' . $perusahaan->img_profile) }}" class="w-10 h-10 object-cover rounded-xl border border-slate-200" alt="Logo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($perusahaan->nama_perusahaan ?? 'P') }}&background=f97316&color=fff&size=128" class="w-10 h-10 object-cover rounded-xl border border-slate-200" alt="Logo">
                            @endif

                            <div class="overflow-hidden">
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

    </div>
@endsection

