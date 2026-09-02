@extends('layouts.index')
@section('content')

    @php
        $isPdf = $artikel->image && \Illuminate\Support\Str::endsWith(strtolower($artikel->image), ['.pdf']);
        $hasImage = $artikel->image && file_exists(public_path('storage/' . $artikel->image));
    @endphp

    <div class="bg-slate-50/60 min-h-screen text-slate-800 pt-28 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            <!-- Breadcrumb / Back button -->
            <div class="mb-6">
                <a href="{{ route('pelamar.tips-kerja') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-600 hover:text-[#00509d] transition">
                    <i class="ph ph-arrow-left font-bold"></i> Kembali ke Tips Kerja
                </a>
            </div>

            <!-- Main Article Card Container -->
            <article class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200/80 mb-12">

                <!-- Article Title -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 leading-snug mb-4 font-serif">
                    {{ $artikel->title }}
                </h1>

                <!-- Author & Date Meta -->
                <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-100 text-xs sm:text-sm text-slate-500 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#00509d] text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                            <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="AK" class="h-5 w-auto object-contain">
                        </div>
                        <div>
                            <p class="font-bold text-[#00509d] text-sm">{{ $artikel->penulis ?? 'Areakerja.com' }}</p>
                            <p class="text-xs text-slate-400">{{ $artikel->created_at ? $artikel->created_at->translatedFormat('l, d F Y H:i') . ' WIB' : 'Terbaru' }}</p>
                        </div>
                    </div>

                    <!-- Share Button -->
                    <div x-data="{ showMenu: false }" class="relative">
                        <button @click="showMenu = !showMenu"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition font-semibold text-xs flex items-center gap-2">
                            <i class="ph ph-share-network text-base text-[#00509d]"></i> Bagikan
                        </button>

                        <div x-show="showMenu" @click.outside="showMenu = false" x-transition x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 py-2 text-slate-800">
                            <a href="{{ route('tips.share', ['platform' => 'linkedin', 'tips' => $artikel->slug]) }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold hover:bg-blue-50 hover:text-[#00509d] transition">
                                <i class="ph ph-linkedin-logo text-blue-600 text-lg"></i> LinkedIn
                            </a>
                            <a href="{{ route('tips.share', ['platform' => 'email', 'tips' => $artikel->slug]) }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold hover:bg-blue-50 hover:text-[#00509d] transition">
                                <i class="ph ph-envelope text-red-500 text-lg"></i> Gmail
                            </a>
                            <a href="{{ route('tips.share', ['platform' => 'whatsapp', 'tips' => $artikel->slug]) }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold hover:bg-blue-50 hover:text-[#00509d] transition">
                                <i class="ph ph-whatsapp-logo text-green-600 text-lg"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Featured Image / PDF Header -->
                @if ($isPdf)
                    <div class="bg-[#00509d] p-6 sm:p-8 rounded-2xl text-white mb-8 shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl font-bold">
                                <i class="ph ph-file-pdf"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-base sm:text-lg">Lampiran Dokumen PDF</h4>
                                <p class="text-xs text-blue-100">Artikel ini dilengkapi dokumen pendukung PDF.</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $artikel->image) }}" target="_blank"
                            class="bg-white hover:bg-slate-100 text-[#00509d] font-bold text-xs sm:text-sm px-6 py-3 rounded-xl shadow-md transition flex items-center gap-2 whitespace-nowrap">
                            <i class="ph ph-download-simple font-bold"></i> Unduh / Buka PDF
                        </a>
                    </div>
                @else
                    <div class="mb-8 rounded-2xl overflow-hidden shadow-sm max-h-[440px] w-full border border-slate-100">
                        @if ($hasImage)
                            <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/tips_kerja_default.png') }}" alt="{{ $artikel->title }}" class="w-full h-full object-cover">
                        @endif
                    </div>
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
                        <span class="w-2.5 h-2.5 bg-[#00509d] rounded-full inline-block"></span>
                        Artikel Terkait Lainnya
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        @foreach ($related as $rel)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 border border-slate-200/80 p-4 flex flex-col justify-between group">
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 group-hover:text-[#00509d] line-clamp-2 mb-2 transition">
                                        <a href="{{ route('pelamar.tips-kerja.show', $rel->id) }}">{{ $rel->title }}</a>
                                    </h4>
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-3 leading-relaxed">{{ strip_tags($rel->intro ?? $rel->content) }}</p>
                                </div>
                                <a href="{{ route('pelamar.tips-kerja.show', $rel->id) }}" class="text-xs text-[#00509d] font-bold hover:underline flex items-center gap-1 mt-2">
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
        class="fixed bottom-8 right-8 bg-[#00509d] hover:bg-[#003d7a] text-white p-3.5 rounded-full shadow-2xl hover:scale-110 transition-all duration-300 z-50 flex items-center justify-center">
        <i class="ph ph-arrow-up text-lg font-bold"></i>
    </a>

    @include('layouts.footer')
@endsection
