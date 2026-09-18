@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header Topbar -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.talent-hunter') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0"
                   title="Kembali ke Talent Hunter">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Talent Hunter / <span class="text-slate-500 font-medium">Detail</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Detail Talent Hunter</h1>
                </div>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end flex-wrap">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <div class="space-y-6 max-w-5xl mx-auto">

            <!-- Hero Banner Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border border-slate-100 p-2 bg-slate-50 flex items-center justify-center flex-shrink-0 overflow-hidden shadow-xs">
                        <img src="{{ $talentHunter->perusahaan->img_profile ? asset('storage/' . $talentHunter->perusahaan->img_profile) : asset('images/seven.png') }}"
                             alt="Logo {{ $talentHunter->perusahaan->nama_perusahaan }}"
                             class="w-full h-full object-contain">
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                                {{ $talentHunter->perusahaan->nama_perusahaan ?? '-' }}
                            </h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-200 self-center sm:self-auto">
                                {{ $talentHunter->posisi ?? 'Talent Hunter' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mb-4">
                            ID Request: <span class="font-medium text-slate-600">#{{ $talentHunter->id }}</span> &bull; 
                            Email: <span class="font-medium text-slate-600">{{ $talentHunter->perusahaan->user->email ?? '-' }}</span>
                        </p>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-slate-600">
                            <div class="flex items-center gap-1.5">
                                <i class="ph ph-phone text-sm text-[#00509d]"></i>
                                <span>{{ $talentHunter->perusahaan->telepon_perusahaan ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="ph ph-map-pin text-sm text-rose-500"></i>
                                <span>{{ $talentHunter->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Deskripsi Card -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="ph ph-article text-base text-[#00509d]"></i>
                            Deskripsi Permintaan
                        </h3>
                        @if (empty($talentHunter->deskripsi))
                            <p class="text-xs sm:text-sm text-slate-400 italic">Perusahaan belum menambahkan deskripsi untuk permintaan ini.</p>
                        @else
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $talentHunter->deskripsi }}</p>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="ph ph-map-pin text-base text-[#00509d]"></i>
                            Alamat Perusahaan
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $talentHunter->alamat ?? '-' }}</p>
                    </div>
                </div>

                <!-- Kriteria Kandidat Card -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-user-focus text-base text-[#00509d]"></i>
                            Kriteria Kandidat
                        </h3>

                        <div class="space-y-3.5 text-xs">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Posisi Dibutuhkan</span>
                                <span class="font-bold text-slate-800 text-sm">{{ $talentHunter->posisi ?? '-' }}</span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Jenis Kelamin</span>
                                <span class="font-semibold text-slate-800">{{ $talentHunter->gender ?? '-' }}</span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Kisaran Gaji</span>
                                <span class="font-semibold text-emerald-700">
                                    {{ $talentHunter->gaji_awal ? 'Rp' . number_format($talentHunter->gaji_awal, 0, ',', '.') : '-' }} - 
                                    {{ $talentHunter->gaji_akhir ? 'Rp' . number_format($talentHunter->gaji_akhir, 0, ',', '.') : '-' }}
                                </span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Pengalaman Kerja</span>
                                <span class="font-semibold text-slate-800">{{ $talentHunter->pengalaman_kerja ?? '-' }}</span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Kontak Perusahaan</span>
                                <span class="font-semibold text-slate-800">{{ $talentHunter->perusahaan->telepon_perusahaan ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>
@endsection
