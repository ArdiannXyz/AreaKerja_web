@extends('layouts.index')
@section('content')

    @php
        $headline = $head ?? ($others->first() ?? null);
        $isHeadlinePdf = $headline && $headline->image && \Illuminate\Support\Str::endsWith(strtolower($headline->image), ['.pdf']);
    @endphp

    <div class="bg-slate-100 min-h-screen text-slate-800 pt-28 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <!-- Hero Featured Article Banner -->
            @if ($headline)
                <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-orange-950 text-white rounded-3xl p-6 sm:p-10 shadow-2xl mb-12 border border-slate-700/50 relative overflow-hidden group">
                    {{-- Decorative Blur Circles --}}
                    <div class="absolute -top-10 -right-10 w-72 h-72 bg-orange-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-amber-500/15 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">

                        <!-- Left Info -->
                        <div class="lg:col-span-7 space-y-4">
                            <!-- Badges -->
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="bg-white/10 backdrop-blur-md text-amber-300 text-xs font-semibold px-3 py-1 rounded-full border border-white/15">
                                    Tips Kerja
                                </span>
                                <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                                    <i class="ph ph-fire"></i> Top News
                                </span>
                            </div>

                            <!-- Title -->
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight hover:text-amber-300 transition-colors">
                                <a href="{{ route('pelamar.tips-kerja.show', $headline->id) }}">
                                    {{ $headline->title }}
                                </a>
                            </h1>

                            <!-- Meta info -->
                            <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-slate-300 pt-1">
                                <span class="flex items-center gap-1.5 text-orange-400 font-semibold">
                                    <i class="ph ph-user-circle text-base"></i> {{ $headline->penulis ?? 'Areakerja.com' }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1.5">
                                    <i class="ph ph-calendar-blank text-base"></i> {{ $headline->created_at ? $headline->created_at->translatedFormat('l, d F Y') : 'Terbaru' }}
                                </span>
                            </div>

                            <!-- Excerpt -->
                            @if ($headline->intro)
                                <p class="text-slate-300 text-sm md:text-base line-clamp-3 leading-relaxed">
                                    {{ Str::limit(strip_tags($headline->intro), 200) }}
                                </p>
                            @endif

                            <!-- CTA Button -->
                            <div class="pt-2 flex flex-wrap items-center gap-4">
                                <a href="{{ route('pelamar.tips-kerja.show', $headline->id) }}"
                                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-orange-500/30 transition-all duration-300 hover:scale-105">
                                    Baca Selengkapnya
                                    <i class="ph ph-arrow-right font-bold"></i>
                                </a>

                                <!-- Share Button -->
                                <div x-data="{ showMenu: false }" class="relative">
                                    <button @click="showMenu = !showMenu"
                                        class="p-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition backdrop-blur-md flex items-center justify-center">
                                        <i class="ph ph-share-network text-xl"></i>
                                    </button>

                                    <!-- Popup Share -->
                                    <div x-show="showMenu" @click.outside="showMenu = false" x-transition x-cloak
                                        class="absolute left-0 lg:left-auto lg:right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 py-2 text-slate-800">
                                        <a href="{{ route('tips.share', ['platform' => 'linkedin', 'tips' => $headline->slug]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold hover:bg-orange-50 hover:text-orange-600 transition">
                                            <i class="ph ph-linkedin-logo text-blue-600 text-lg"></i> LinkedIn
                                        </a>
                                        <a href="{{ route('tips.share', ['platform' => 'email', 'tips' => $headline->slug]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold hover:bg-orange-50 hover:text-orange-600 transition">
                                            <i class="ph ph-envelope text-red-500 text-lg"></i> Gmail
                                        </a>
                                        <a href="{{ route('tips.share', ['platform' => 'whatsapp', 'tips' => $headline->slug]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold hover:bg-orange-50 hover:text-orange-600 transition">
                                            <i class="ph ph-whatsapp-logo text-green-600 text-lg"></i> WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Image Display / Banner -->
                        <div class="lg:col-span-5">
                            @if ($headline->image && !$isHeadlinePdf)
                                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/10 group-hover:scale-[1.02] transition-all duration-500 max-h-80">
                                    <img src="{{ asset('storage/' . $headline->image) }}" alt="{{ $headline->title }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                                </div>
                            @else
                                <div class="bg-gradient-to-br from-orange-500 via-amber-500 to-orange-600 p-8 rounded-2xl flex flex-col justify-between text-white shadow-2xl min-h-[260px] border border-amber-300/30">
                                    <div class="flex items-center justify-between">
                                        <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                            Artikel & Panduan
                                        </span>
                                        <i class="ph ph-article text-4xl text-amber-100 opacity-80"></i>
                                    </div>
                                    <div class="my-4">
                                        <h3 class="font-bold text-xl line-clamp-2 text-white drop-shadow-sm">{{ $headline->title }}</h3>
                                        <p class="text-xs text-amber-100 mt-2 font-medium">Dokumen & Tips Pengembangan Karir</p>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs font-semibold text-white/90 border-t border-white/20 pt-3">
                                        <i class="ph ph-file-pdf text-lg"></i> PDF Dokumen Panduan Karir
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endif

            <!-- Section Grid Artikel lainnya -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-3 h-3 bg-orange-500 rounded-full inline-block"></span>
                        Tips Kerja & Artikel Terbaru
                    </h2>
                    <span class="text-xs font-medium text-slate-500">Berbagi wawasan seputar karir</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @forelse ($others->when($headline, fn($q) => $q->where('id', '!=', $headline->id)) as $artikel)
                        @php
                            $isPdf = $artikel->image && \Illuminate\Support\Str::endsWith(strtolower($artikel->image), ['.pdf']);
                        @endphp
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200/80 flex flex-col justify-between group">

                            <div>
                                <!-- Thumbnail Image -->
                                <div class="w-full h-48 bg-slate-100 overflow-hidden relative">
                                    @if ($artikel->image && !$isPdf)
                                        <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-slate-800 to-orange-900 p-6 flex flex-col justify-between text-white">
                                            <div class="flex justify-between items-center">
                                                <span class="bg-orange-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full">PDF Tips</span>
                                                <i class="ph ph-file-text text-2xl text-orange-300"></i>
                                            </div>
                                            <h4 class="font-bold text-sm text-white line-clamp-2">{{ $artikel->title }}</h4>
                                        </div>
                                    @endif

                                    <div class="absolute top-3 left-3 flex gap-1">
                                        <span class="bg-slate-900/80 backdrop-blur-md text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                            Tips
                                        </span>
                                    </div>
                                </div>

                                <!-- Body Content -->
                                <div class="p-5">
                                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-2 font-medium">
                                        <i class="ph ph-calendar-blank text-orange-500"></i>
                                        <span>{{ $artikel->created_at ? $artikel->created_at->translatedFormat('d F Y') : 'Terbaru' }}</span>
                                    </div>

                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug mb-2">
                                        <a href="{{ route('pelamar.tips-kerja.show', $artikel->id) }}">
                                            {{ $artikel->title }}
                                        </a>
                                    </h3>

                                    @if ($artikel->intro)
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                                            {{ strip_tags($artikel->intro) }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer Card -->
                            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-400">
                                <span class="flex items-center gap-1 font-medium text-slate-500">
                                    <i class="ph ph-eye text-orange-500 text-sm"></i> {{ $artikel->views ?? 0 }} views
                                </span>

                                <a href="{{ route('pelamar.tips-kerja.show', $artikel->id) }}" class="text-orange-600 hover:text-orange-700 font-bold text-xs flex items-center gap-1">
                                    Baca <i class="ph ph-caret-right font-bold"></i>
                                </a>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                            <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-300 shadow-sm">
                                <div class="w-16 h-16 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                                    <i class="ph ph-article"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Artikel Tips Kerja</h3>
                                <p class="text-slate-500 text-sm max-w-sm mx-auto">Artikel tips kerja terbaru akan ditampilkan di sini. Silakan kembali lagi nanti!</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Floating Back to Top Button --}}
    <a href="#top"
        class="fixed bottom-8 right-8 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white p-3.5 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 z-50 flex items-center justify-center">
        <i class="ph ph-caret-up text-xl font-bold"></i>
    </a>

    @include('layouts.footer')
@endsection
