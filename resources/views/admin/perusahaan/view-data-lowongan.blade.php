@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 flex-1">
                <a href="{{ route('admin.perusahaan.detail', $lowongan->perusahaan_id) }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Perusahaan / Detail Perusahaan /
                        <span class="text-slate-500 font-medium">{{ $lowongan->nama }}</span>
                    </p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Detail Lowongan</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Lowongan Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- Company + Title Header -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 p-6 border-b border-slate-100">
                @if($lowongan->perusahaan->img_profile)
                    <img src="{{ asset('storage/' . $lowongan->perusahaan->img_profile) }}"
                         alt="Logo Perusahaan"
                         class="w-20 h-20 object-cover rounded-2xl border border-slate-100 flex-shrink-0">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[#00509d] to-[#0077b6] flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                        {{ strtoupper(substr($lowongan->perusahaan->nama_perusahaan ?? 'P', 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 text-center sm:text-left">
                    <h2 class="text-xl font-bold text-slate-800">{{ $lowongan->nama }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $lowongan->perusahaan->nama_perusahaan ?? '-' }}</p>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-[#00509d]">
                            <i class="ph ph-briefcase text-sm"></i> {{ $lowongan->jenis }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                            <i class="ph ph-money text-sm"></i> Rp{{ number_format($lowongan->gaji_awal, 0, ',', '.') }} – Rp{{ number_format($lowongan->gaji_akhir, 0, ',', '.') }}
                        </span>
                        @if($lowongan->rekomendasi !== null)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">
                                <i class="ph ph-star text-sm"></i> Rekomendasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Content -->
            <div class="p-6 space-y-6">

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1.5">Gaji</p>
                        <p class="text-sm font-semibold text-slate-800">
                            Rp{{ number_format($lowongan->gaji_awal, 0, ',', '.') }} – Rp{{ number_format($lowongan->gaji_akhir, 0, ',', '.') }}
                            <span class="text-xs text-slate-400 font-normal">/ bulan</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1.5">Jenis Lowongan</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $lowongan->jenis }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1.5">Dipasang</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $lowongan->published_at ? \Carbon\Carbon::parse($lowongan->published_at)->format('d M Y') : '-' }}</p>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-2">Deskripsi Pekerjaan</p>
                    <div class="text-sm text-slate-700 leading-relaxed bg-slate-50 rounded-xl p-4 border border-slate-100">
                        {{ $lowongan->deskripsi }}
                    </div>
                </div>

                <!-- Syarat -->
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-2">Syarat Pekerjaan</p>
                    <div class="text-sm text-slate-700 leading-relaxed bg-slate-50 rounded-xl p-4 border border-slate-100">
                        {{ $lowongan->syarat_pekerjaan }}
                    </div>
                </div>

                <!-- Tanggung Jawab -->
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-2">Tanggung Jawab</p>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-700">
                            @foreach (preg_split("/\r\n|\n|\r/", $lowongan->tanggung_jawab ?? '') as $res)
                                @php
                                    $trim = trim($res);
                                    $isNumbered = preg_match('/^\d+[\.\-\)]\s*/', $trim);
                                @endphp
                                @if ($trim !== '')
                                    @if ($isNumbered)
                                        <li style="list-style-type: none;">{{ $trim }}</li>
                                    @else
                                        <li>{{ $trim }}</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="px-6 pb-6 flex flex-col sm:flex-row gap-3 max-w-lg">
                <form action="{{ route('admin.lowongan.toggleRekomendasi', $lowongan->id) }}" method="POST">
                    @csrf
                    @if ($lowongan->rekomendasi !== null)
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 border border-amber-400 text-amber-700 hover:bg-amber-500 hover:text-white hover:border-amber-500 text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            <i class="ph ph-star-slash text-base"></i>
                            Hapus dari Rekomendasi
                        </button>
                    @else
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-amber-400 hover:bg-amber-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            <i class="ph ph-star text-base"></i>
                            Jadikan Rekomendasi
                        </button>
                    @endif
                </form>
            </div>
        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>
@endsection
