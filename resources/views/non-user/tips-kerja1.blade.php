@extends('layouts.index')
@section('content')

    @php
        $isPdf = $artikel->image && \Illuminate\Support\Str::endsWith(strtolower($artikel->image), ['.pdf']);
    @endphp

    <div class="bg-slate-100 min-h-screen text-slate-800 pt-28 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            <!-- Breadcrumb / Back button -->
            <div class="mb-6">
                <a href="{{ route('pelamar.tips-kerja') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-600 hover:text-orange-600 transition">
                    <i class="ph ph-arrow-left font-bold"></i> Kembali ke Tips Kerja
                </a>
            </div>

            <!-- Main Article Card Container -->
            <article class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-200/80 mb-12">

                <!-- Header Meta & Badges -->
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">
                        Tips Kerja
                    </span>
                    <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-3 py-1 rounded-full">
                        Top News
                    </span>
                </div>

                <!-- Article Title -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                    {{ $artikel->title }}
                </h1>

                <!-- Author & Date Meta -->
                <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-100 text-xs sm:text-sm text-slate-500 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                            {{ strtoupper(substr($artikel->penulis ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">{{ $artikel->penulis ?? 'Areakerja.com' }}</p>
                            <p class="text-xs text-slate-400">{{ $artikel->created_at ? $artikel->created_at->translatedFormat('l, d F Y H:i') : 'Terbaru' }}</p>
                        </div>
                    </div>

                    <!-- Share Button -->
                    <div x-data="{ showMenu: false }" class="relative">
                        <button @click="showMenu = !showMenu"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition font-semibold text-xs flex items-center gap-2">
                            <i class="ph ph-share-network text-base text-orange-500"></i> Bagikan
                        </button>

                        <div x-show="showMenu" @click.outside="showMenu = false" x-transition x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 py-2 text-slate-800">
                            <a href="{{ route('tips.share', ['platform' => 'linkedin', 'tips' => $artikel->slug]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold hover:bg-orange-50 hover:text-orange-600 transition">
                                <i class="ph ph-linkedin-logo text-blue-600 text-lg"></i> LinkedIn
                            </a>
                            <a href="{{ route('tips.share', ['platform' => 'email', 'tips' => $artikel->slug]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold hover:bg-orange-50 hover:text-orange-600 transition">
                                <i class="ph ph-envelope text-red-500 text-lg"></i> Gmail
                            </a>
                            <a href="{{ route('tips.share', ['platform' => 'whatsapp', 'tips' => $artikel->slug]) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold hover:bg-orange-50 hover:text-orange-600 transition">
                                <i class="ph ph-whatsapp-logo text-green-600 text-lg"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Featured Image / PDF Header -->
                @if ($artikel->image)
                    @if ($isPdf)
                        <div class="bg-gradient-to-r from-orange-500 to-amber-500 p-6 sm:p-8 rounded-2xl text-white mb-8 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl font-bold">
                                    <i class="ph ph-file-pdf"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-base sm:text-lg">Lampiran Dokumen PDF</h4>
                                    <p class="text-xs text-amber-100">Artikel ini dilengkapi dokumen pendukung PDF.</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $artikel->image) }}" target="_blank"
                                class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm px-6 py-3 rounded-xl shadow-md transition flex items-center gap-2 whitespace-nowrap">
                                <i class="ph ph-download-simple font-bold"></i> Unduh / Buka PDF
                            </a>
                        </div>
                    @else
                        <div class="mb-8 rounded-2xl overflow-hidden shadow-lg max-h-96 w-full">
                            <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                @endif

                <!-- Rich Text Article Content -->
                <div class="tinymce-content text-slate-700 text-base leading-relaxed space-y-4">
                    {!! $artikel->content !!}
                </div>

            </article>

            <!-- Related Articles Section -->
            @if (isset($related) && count($related) > 0)
                <div class="mt-12">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="w-3 h-3 bg-orange-500 rounded-full inline-block"></span>
                        Artikel Terkait Lainnya
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        @foreach ($related as $rel)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-200/80 p-4 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 hover:text-orange-600 line-clamp-2 mb-2">
                                        <a href="{{ route('pelamar.tips-kerja.show', $rel->id) }}">{{ $rel->title }}</a>
                                    </h4>
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ strip_tags($rel->intro ?? $rel->content) }}</p>
                                </div>
                                <a href="{{ route('pelamar.tips-kerja.show', $rel->id) }}" class="text-xs text-orange-600 font-bold hover:underline flex items-center gap-1">
                                    Baca Artikel <i class="ph ph-arrow-right font-bold"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Floating Back to Top Button -->
    <a href="#top"
        class="fixed bottom-8 right-8 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white p-3.5 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 z-50 flex items-center justify-center">
        <i class="ph ph-caret-up text-xl font-bold"></i>
    </a>

    @include('layouts.footer')
@endsection
