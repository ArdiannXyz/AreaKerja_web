@extends('layouts.index-perusahaan')
@section('content')
    <div class="w-full bg-slate-50/50 min-h-screen pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- ================= HEADER SECTION ================= -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
                <div class="space-y-2 relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-[#00509d] text-xs font-black uppercase tracking-wide">
                        <i class="ph-fill ph-megaphone-simple text-sm"></i>
                        <span>Pusat Promosi & Iklan</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Manajemen Iklan Homepage
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium max-w-2xl">
                        Promosikan profil perusahaan, lowongan kerja prioritas, atau program rekrutmen Anda langsung di banner utama beranda pelamar AreaKerja.
                    </p>
                </div>

                <div class="flex items-center gap-3 relative z-10 w-full sm:w-auto">
                    <a href="{{ route('perusahaan.iklan.create') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs sm:text-sm font-extrabold rounded-2xl shadow-sm hover:shadow-md transition active:scale-98">
                        <i class="ph-bold ph-plus-circle text-base"></i>
                        <span>Pasang Iklan Baru</span>
                    </a>
                </div>
            </div>

            <!-- ================= STATS / KPI CARDS ================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Total Iklan -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Iklan</span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">{{ $totalIklan }}</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Semua riwayat pengajuan</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-2xl shrink-0">
                        <i class="ph-fill ph-files"></i>
                    </div>
                </div>

                <!-- Iklan Aktif -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Sedang Tayang</span>
                        <h3 class="text-xl sm:text-2xl font-black text-emerald-600 mt-1">{{ $iklanAktif }}</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Aktif di homepage sekarang</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-2xl shrink-0">
                        <i class="ph-fill ph-broadcast"></i>
                    </div>
                </div>

                <!-- Total Views -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Impresi</span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">{{ number_format($totalViews, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Dilihat oleh pelamar</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-2xl shrink-0">
                        <i class="ph-fill ph-eye"></i>
                    </div>
                </div>

                <!-- Total Clicks -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Klik</span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">{{ number_format($totalClicks, 0, ',', '.') }}</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Pengunjung yang berinteraksi</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-2xl shrink-0">
                        <i class="ph-fill ph-cursor-click"></i>
                    </div>
                </div>
            </div>

            <!-- ================= ALERT SUCCESS ================= -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-2xs">
                    <i class="ph-fill ph-check-circle text-2xl text-emerald-600 shrink-0"></i>
                    <p class="text-xs sm:text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- ================= TABEL RIWAYAT IKLAN ================= -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-xl font-bold">
                            <i class="ph-fill ph-list-dashes"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Daftar Iklan Perusahaan</h2>
                            <p class="text-xs text-slate-500 font-medium">Status pengajuan, durasi, dan performa penayangan</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500">Saldo Koin Saat Ini:</span>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 border border-blue-100 rounded-xl">
                            <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-4 h-4 object-contain">
                            <span class="text-xs font-black text-[#00509d]">{{ number_format($perusahaan->koin_perusahaan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Materi Banner & Judul</th>
                                <th class="px-6 py-4">Paket & Durasi</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Periode Tayang</th>
                                <th class="px-6 py-4 text-center">Performa</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($iklans as $item)
                                <tr class="hover:bg-blue-50/20 transition group">
                                    <!-- Banner & Judul -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-24 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 relative group/img cursor-pointer"
                                                onclick="showBannerModal('{{ $item->banner_url }}', '{{ addslashes($item->judul_iklan) }}')">
                                                <img src="{{ $item->banner_url }}" alt="Banner" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition flex items-center justify-center text-white text-xs">
                                                    <i class="ph ph-magnifying-glass-plus text-base"></i>
                                                </div>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-extrabold text-slate-900 group-hover:text-[#00509d] transition truncate max-w-xs sm:max-w-sm">
                                                    {{ $item->judul_iklan }}
                                                </h4>
                                                <a href="{{ $item->url_tujuan }}" target="_blank"
                                                    class="inline-flex items-center gap-1 text-[11px] text-[#00509d] hover:underline font-semibold mt-0.5 truncate max-w-xs">
                                                    <span>{{ Str::limit($item->url_tujuan, 35) }}</span>
                                                    <i class="ph ph-arrow-square-out text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Paket & Durasi -->
                                    <td class="px-6 py-4">
                                        <p class="font-extrabold text-slate-800">{{ $item->paket_nama }}</p>
                                        <div class="flex items-center gap-1.5 mt-1 text-slate-500 text-xs font-semibold">
                                            <i class="ph ph-clock text-xs"></i>
                                            <span>{{ $item->durasi_hari }} Hari</span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 text-center">
                                        @if ($item->status === 'menunggu')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-[#00509d] border border-blue-200 text-xs font-bold rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#00509d] animate-pulse"></span>
                                                <span>Menunggu Review</span>
                                            </span>
                                        @elseif ($item->status === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Sedang Tayang</span>
                                            </span>
                                        @elseif ($item->status === 'ditolak')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full"
                                                title="{{ $item->alasan_penolakan }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span>Ditolak</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold rounded-full">
                                                <span>Selesai</span>
                                            </span>
                                        @endif

                                        @if($item->status === 'ditolak' && $item->alasan_penolakan)
                                            <p class="text-[10px] text-rose-600 font-medium mt-1 max-w-[150px] mx-auto truncate" title="{{ $item->alasan_penolakan }}">
                                                "{{ $item->alasan_penolakan }}"
                                            </p>
                                        @endif
                                    </td>

                                    <!-- Periode Tayang -->
                                    <td class="px-6 py-4 text-center text-xs">
                                        @if ($item->tanggal_mulai && $item->tanggal_selesai)
                                            <p class="font-extrabold text-slate-800">
                                                {{ $item->tanggal_mulai->translatedFormat('d M') }} - {{ $item->tanggal_selesai->translatedFormat('d M Y') }}
                                            </p>
                                            @if ($item->status === 'aktif')
                                                <span class="inline-block mt-0.5 text-[11px] font-bold text-emerald-600">
                                                    Sisa {{ $item->sisa_hari }} hari
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 font-medium">Belum tayang</span>
                                        @endif
                                    </td>

                                    <!-- Performa (Views & Clicks) -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex flex-col items-center">
                                            <div class="flex items-center gap-3 text-xs font-bold">
                                                <span class="text-[#00509d] flex items-center gap-1" title="Impresi / Dilihat">
                                                    <i class="ph ph-eye"></i> {{ number_format($item->total_views, 0, ',', '.') }}
                                                </span>
                                                <span class="text-slate-600 flex items-center gap-1" title="Klik">
                                                    <i class="ph ph-cursor-click"></i> {{ number_format($item->total_clicks, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            @php
                                                $ctr = $item->total_views > 0 ? round(($item->total_clicks / $item->total_views) * 100, 1) : 0;
                                            @endphp
                                            <span class="text-[10px] text-slate-400 font-medium mt-0.5">CTR: {{ $ctr }}%</span>
                                        </div>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 text-right">
                                        <button onclick="showBannerModal('{{ $item->banner_url }}', '{{ addslashes($item->judul_iklan) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white rounded-xl text-xs font-bold transition shadow-2xs">
                                            <i class="ph ph-eye text-sm"></i>
                                            <span>Lihat</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#00509d] flex items-center justify-center text-3xl mx-auto mb-3">
                                            <i class="ph ph-megaphone"></i>
                                        </div>
                                        <p class="font-extrabold text-sm text-slate-700">Belum ada iklan yang diajukan.</p>
                                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                            Tingkatkan pelamar potensial dengan mempromosikan banner perusahaan Anda di halaman beranda AreaKerja.
                                        </p>
                                        <a href="{{ route('perusahaan.iklan.create') }}"
                                            class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-extrabold rounded-xl transition shadow-xs">
                                            <i class="ph-bold ph-plus"></i>
                                            <span>Pasang Iklan Pertama</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($iklans->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $iklans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- ================= MODAL PREVIEW BANNER ================= -->
    <div id="bannerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-6 relative border border-slate-100">
            <button onclick="closeBannerModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="ph ph-x font-bold"></i>
            </button>

            <h3 id="modalTitle" class="text-base font-extrabold text-slate-900 mb-4 pr-8 truncate"></h3>

            <div class="w-full rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
                <img id="modalBannerImg" src="" alt="Banner Preview" class="w-full h-auto object-contain max-h-[350px]">
            </div>

            <div class="mt-5 flex justify-end">
                <button type="button" onclick="closeBannerModal()"
                    class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                    Tutup Preview
                </button>
            </div>
        </div>
    </div>

    <script>
        function showBannerModal(url, title) {
            document.getElementById('modalBannerImg').src = url;
            document.getElementById('modalTitle').innerText = title;
            const m = document.getElementById('bannerModal');
            if (m) m.style.display = 'flex';
        }

        function closeBannerModal() {
            const m = document.getElementById('bannerModal');
            if (m) m.style.display = 'none';
        }
    </script>

    @include('layouts.footer')
@endsection
