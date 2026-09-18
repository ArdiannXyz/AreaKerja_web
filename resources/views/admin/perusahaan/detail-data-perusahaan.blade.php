@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header Topbar -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.perusahaan') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0"
                   title="Kembali ke Data Perusahaan">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Perusahaan / <span class="text-slate-500 font-medium">Detail</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Detail Perusahaan</h1>
                </div>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end flex-wrap">
                <div class="h-5 w-px bg-slate-200 hidden sm:block mx-1"></div>

                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <div class="space-y-6">

            <!-- Company Profile Banner Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border border-slate-100 p-2 bg-slate-50 flex items-center justify-center flex-shrink-0 overflow-hidden shadow-xs">
                        <img src="{{ $perusahaan->img_profile ? asset('storage/' . $perusahaan->img_profile) : asset('images/seven.png') }}"
                             alt="Logo {{ $perusahaan->nama_perusahaan }}"
                             class="w-full h-full object-contain">
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                                {{ $perusahaan->nama_perusahaan }}
                            </h2>
                            @if ($perusahaan->legalitas)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 self-center sm:self-auto">
                                    {{ $perusahaan->legalitas }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 mb-4">Username: <span class="font-medium text-slate-600">{{ $perusahaan->user->username ?? '-' }}</span> &bull; User ID: <span class="font-medium text-slate-600">#{{ $perusahaan->user->id }}</span></p>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-slate-600">
                            <div class="flex items-center gap-1.5">
                                <i class="ph ph-envelope-simple text-sm text-[#00509d]"></i>
                                <span>{{ $perusahaan->user->email ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="ph ph-phone text-sm text-[#00509d]"></i>
                                <span>{{ $perusahaan->telepon_perusahaan ?? '-' }}</span>
                            </div>
                            @if ($perusahaan->whatsapp)
                                <div class="flex items-center gap-1.5">
                                    <i class="ph ph-whatsapp-logo text-sm text-emerald-600"></i>
                                    <span>{{ $perusahaan->whatsapp }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Information Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Col 1 & 2: Deskripsi, Visi & Misi -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Deskripsi Card -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="ph ph-article text-base text-[#00509d]"></i>
                            Deskripsi Perusahaan
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            {{ $perusahaan->deskripsi ?? 'Belum ada deskripsi yang ditambahkan.' }}
                        </p>
                    </div>

                    <!-- Visi & Misi Card -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="ph ph-eye text-base text-[#00509d]"></i>
                                Visi
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                {{ $perusahaan->visi ?? 'Belum ada visi yang ditambahkan.' }}
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="ph ph-target text-base text-[#00509d]"></i>
                                Misi
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                {{ $perusahaan->misi ?? 'Belum ada misi yang ditambahkan.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Lowongan Perusahaan -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <i class="ph ph-briefcase text-base text-[#00509d]"></i>
                                Lowongan Kerja ({{ $perusahaan->lowonganPerusahaans ? $perusahaan->lowonganPerusahaans->count() : 0 }})
                            </h3>
                        </div>

                        @if ($perusahaan->lowonganPerusahaans && $perusahaan->lowonganPerusahaans->count())
                            <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                                @foreach ($perusahaan->lowonganPerusahaans as $l)
                                    <div class="p-4 hover:bg-slate-50/70 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <a href="{{ route('admin.lowongan.detail', [
                                                'perusahaan' => $perusahaan->slug ?: $perusahaan->id,
                                                'lowongan' => $l->slug ?: $l->id,
                                            ]) }}"
                                               class="text-sm font-bold text-[#00509d] hover:underline">
                                                {{ $l->nama }}
                                            </a>
                                            <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                                <i class="ph ph-map-pin text-xs"></i> {{ $l->alamat ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3 self-end sm:self-auto">
                                            <span class="text-[11px] text-slate-400">
                                                {{ $l->published_at ? \Carbon\Carbon::parse($l->published_at)->format('d M Y') : ($l->created_at ? $l->created_at->format('d M Y') : '-') }}
                                            </span>
                                            <a href="{{ route('admin.lowongan.detail', [
                                                'perusahaan' => $perusahaan->slug ?: $perusahaan->id,
                                                'lowongan' => $l->slug ?: $l->id,
                                            ]) }}"
                                               class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white flex items-center justify-center transition">
                                                <i class="ph ph-arrow-right text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 border border-dashed border-slate-200 rounded-xl">
                                <i class="ph ph-briefcase text-3xl text-slate-300 mb-2 block"></i>
                                <p class="text-xs text-slate-400">Perusahaan ini belum memiliki lowongan aktif.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Col 3: Data Akun & Kontak Ringkas -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-user-circle text-base text-[#00509d]"></i>
                            Kredensial Akun
                        </h3>

                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400">User ID</span>
                                <span class="font-semibold text-slate-800">#{{ $perusahaan->user->id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400">Username</span>
                                <span class="font-semibold text-slate-800">{{ $perusahaan->user->username }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400">Email</span>
                                <span class="font-semibold text-slate-800 break-all">{{ $perusahaan->user->email }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400">Kata Sandi</span>
                                <span class="font-mono text-slate-500">••••••••</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-400">Legalitas</span>
                                <span class="font-semibold text-slate-800">{{ $perusahaan->legalitas ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-phone-call text-base text-[#00509d]"></i>
                            Informasi Kontak
                        </h3>

                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400">Telepon</span>
                                <span class="font-semibold text-slate-800">{{ $perusahaan->telepon_perusahaan ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-400">WhatsApp</span>
                                <span class="font-semibold text-slate-800">{{ $perusahaan->whatsapp ?? '-' }}</span>
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
