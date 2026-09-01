@extends('layouts.index')

@section('content')
    <div class="min-h-screen bg-slate-50 pt-28 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6">
                <a href="{{ route('beranda') }}" class="hover:text-orange-600 transition">Beranda</a>
                <i class="ph ph-caret-right text-[10px]"></i>
                <a href="{{ route('pelamar.event.index') }}" class="hover:text-orange-600 transition">Event & Job Fair</a>
                <i class="ph ph-caret-right text-[10px]"></i>
                <span class="text-slate-800 truncate max-w-xs">{{ $event->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- Left Content: Main Event Detail --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Event Card Container --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">

                        {{-- Event Poster Banner --}}
                        <div class="relative rounded-2xl overflow-hidden mb-6 bg-slate-100 max-h-[360px]">
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-64 bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 flex flex-col items-center justify-center p-8 text-center text-white relative">
                                    <div class="absolute inset-0 bg-black/15"></div>
                                    <div class="relative z-10">
                                        <i class="ph ph-calendar-star text-5xl mb-2"></i>
                                        <h2 class="text-xl sm:text-2xl font-extrabold">{{ $event->title }}</h2>
                                    </div>
                                </div>
                            @endif

                            <div class="absolute top-4 left-4 z-10">
                                @if ($event->status === 'buka')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> Pendaftaran Dibuka
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-700 text-white shadow-sm">
                                        Pendaftaran Ditutup
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Title & Meta Header --}}
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-4 leading-tight">
                            {{ $event->title }}
                        </h1>

                        {{-- Quick Info Badges --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 mb-6 text-xs text-slate-700">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-base shrink-0">
                                    <i class="ph ph-calendar"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">Tanggal Pelaksanaan</div>
                                    <div class="font-bold text-slate-800">
                                        {{ \Carbon\Carbon::parse($event->tgl_mulai)->translatedFormat('d M Y') }}
                                        @if ($event->tgl_akhir && $event->tgl_akhir != $event->tgl_mulai)
                                            - {{ \Carbon\Carbon::parse($event->tgl_akhir)->translatedFormat('d M Y') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-base shrink-0">
                                    <i class="ph ph-clock"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">Waktu Kegiatan</div>
                                    <div class="font-bold text-slate-800">{{ $event->jam_mulai }} - {{ $event->jam_akhir }} WIB</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-base shrink-0">
                                    <i class="ph ph-map-pin"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">Lokasi / Platform</div>
                                    <div class="font-bold text-slate-800">{{ $event->lokasi ?: 'Online Webinar' }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-base shrink-0">
                                    <i class="ph ph-users"></i>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">Kuota Peserta</div>
                                    <div class="font-bold text-slate-800">{{ $event->kuota ? $event->kuota . ' Peserta' : 'Terbuka Umum' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Event Description --}}
                        <div class="mb-8">
                            <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <i class="ph ph-article text-orange-500 text-lg"></i> Deskripsi & Informasi Acara
                            </h2>
                            <div class="text-sm text-slate-700 leading-relaxed space-y-3 prose prose-orange max-w-none">
                                {!! nl2br(e($event->content)) !!}
                            </div>
                        </div>

                        {{-- Rundown / Kegiatan Schedule --}}
                        @if ($event->kegiatan && $event->kegiatan->count() > 0)
                            <div class="mb-8 pt-6 border-t border-slate-200">
                                <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                                    <i class="ph ph-list-numbers text-orange-500 text-lg"></i> Susunan Acara & Rundown
                                </h2>
                                <div class="space-y-3">
                                    @foreach ($event->kegiatan as $index => $k)
                                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                            <div class="px-2.5 py-1 rounded-lg bg-orange-500 text-white font-bold shrink-0">
                                                {{ $k->waktu ?: 'Sesi ' . ($index + 1) }}
                                            </div>
                                            <div class="font-medium text-slate-800 pt-0.5 leading-relaxed">
                                                {{ $k->kegiatan }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

                {{-- Right Sidebar: Registration CTA Card --}}
                <div class="space-y-6 lg:sticky lg:top-28">

                    {{-- Card Registration --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                        <h3 class="text-base font-bold text-slate-900 mb-1">Daftar Event</h3>
                        <p class="text-xs text-slate-500 mb-4">Pastikan Anda telah mengisi data registrasi sebelum batas waktu berakhir.</p>

                        @if ($event->penutupan_pendaftaran)
                            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 mb-5 text-xs text-amber-900 flex items-center gap-2">
                                <i class="ph ph-hourglass-high text-base text-amber-600 shrink-0"></i>
                                <span>Batas Pendaftaran: <strong>{{ \Carbon\Carbon::parse($event->penutupan_pendaftaran)->translatedFormat('d F Y') }}</strong></span>
                            </div>
                        @endif

                        @if ($event->status === 'buka')
                            @if ($event->link_form)
                                <a href="{{ $event->link_form }}" target="_blank"
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 px-4 rounded-xl text-center text-sm transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <span>Isi Formulir Pendaftaran</span>
                                    <i class="ph ph-arrow-square-out font-bold"></i>
                                </a>
                            @else
                                <a href="mailto:support@areakerja.com?subject=Pendaftaran Event {{ urlencode($event->title) }}"
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 px-4 rounded-xl text-center text-sm transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <span>Hubungi Admin untuk Pendaftaran</span>
                                    <i class="ph ph-envelope-simple font-bold"></i>
                                </a>
                            @endif
                        @else
                            <button disabled class="w-full bg-slate-300 text-slate-600 font-bold py-3.5 px-4 rounded-xl text-center text-sm cursor-not-allowed">
                                Pendaftaran Telah Ditutup
                            </button>
                        @endif

                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-center gap-2 text-xs text-slate-500">
                            <i class="ph ph-shield-check text-emerald-500 text-base"></i>
                            <span>Diselenggarakan resmi oleh AreaKerja</span>
                        </div>
                    </div>

                    {{-- Upcoming Other Events --}}
                    @if ($otherEvents && $otherEvents->count() > 0)
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                                <i class="ph ph-lightning text-amber-500"></i> Event Lainnya
                            </h3>
                            <div class="space-y-3">
                                @foreach ($otherEvents as $oe)
                                    <a href="{{ route('pelamar.event.show', $oe->id) }}" class="block p-3 rounded-xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50/40 transition group">
                                        <div class="text-xs font-bold text-slate-800 line-clamp-1 group-hover:text-orange-600 transition mb-1">
                                            {{ $oe->title }}
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                            <span class="flex items-center gap-1"><i class="ph ph-calendar text-orange-500"></i> {{ \Carbon\Carbon::parse($oe->tgl_mulai)->translatedFormat('d M Y') }}</span>
                                            <span>•</span>
                                            <span>{{ $oe->lokasi ?: 'Online' }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>

    @include('layouts.footer')
@endsection
