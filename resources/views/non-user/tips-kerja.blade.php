@extends('layouts.index')
@section('content')

    @php
        $headline = $head ?? ($others->first() ?? null);
        $isHeadlinePdf = $headline && $headline->image && \Illuminate\Support\Str::endsWith(strtolower($headline->image), ['.pdf']);
    @endphp

    <div class="bg-white min-h-screen text-slate-800 pt-28 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <!-- Hero Featured Article Banner -->
            @if ($headline)
                <div class="space-y-6 mb-16">
                    <!-- Featured Image -->
                    <div class="w-full h-72 sm:h-96 md:h-[480px] overflow-hidden rounded-2xl md:rounded-3xl shadow-sm border border-slate-100">
                        @if ($headline->image && !$isHeadlinePdf && file_exists(public_path('storage/' . $headline->image)))
                            <img src="{{ asset('storage/' . $headline->image) }}" alt="{{ $headline->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/tips_kerja_default.png') }}" alt="Hero Banner" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <!-- Title & Action Buttons -->
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 leading-snug tracking-tight font-serif flex-1">
                            <a href="{{ route('pelamar.tips-kerja.show', $headline->id) }}" class="hover:text-[#00509d] transition">
                                {{ $headline->title }}
                            </a>
                        </h1>

                        <div class="flex items-center gap-2.5 shrink-0 mt-1">
                            <a href="{{ route('pelamar.tips-kerja.show', $headline->id) }}"
                                class="w-10 h-10 bg-[#00509d] text-white rounded-full flex items-center justify-center shadow-sm hover:bg-[#003d7a] transition">
                                <i class="ph ph-arrow-up-right font-bold text-lg"></i>
                            </a>

                            <div x-data="{ showMenu: false }" class="relative">
                                <button @click="showMenu = !showMenu"
                                    class="w-10 h-10 border border-slate-200 text-slate-600 hover:text-slate-900 rounded-full flex items-center justify-center transition">
                                    <i class="ph ph-share-network text-lg"></i>
                                </button>
                                <div x-show="showMenu" @click.outside="showMenu = false" x-transition x-cloak
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 py-2 text-slate-800">
                                    <a href="{{ route('tips.share', ['platform' => 'linkedin', 'tips' => $headline->slug]) }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold hover:bg-blue-50 hover:text-[#00509d] transition">
                                        <i class="ph ph-linkedin-logo text-blue-600 text-lg"></i> LinkedIn
                                    </a>
                                    <a href="{{ route('tips.share', ['platform' => 'email', 'tips' => $headline->slug]) }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold hover:bg-blue-50 hover:text-[#00509d] transition">
                                        <i class="ph ph-envelope text-red-500 text-lg"></i> Gmail
                                    </a>
                                    <a href="{{ route('tips.share', ['platform' => 'whatsapp', 'tips' => $headline->slug]) }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold hover:bg-blue-50 hover:text-[#00509d] transition">
                                        <i class="ph ph-whatsapp-logo text-green-600 text-lg"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta info line -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-6 text-xs sm:text-sm">
                        <span class="font-bold text-[#00509d]">
                            {{ $headline->penulis ?? 'Areakerja.com' }}
                        </span>
                        <span class="text-slate-400 font-medium">
                            {{ $headline->created_at ? $headline->created_at->translatedFormat('l, d F Y H:i') . ' WIB' : 'Kamis, 27 Oktober 13:00 WIB' }}
                        </span>
                    </div>
                </div>
            @endif

            <!-- Section: Tips Kerja Grid -->
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Tips Kerja</h2>
                    <a href="{{ url('/pelamar/tips-kerja') }}" class="text-sm font-bold text-[#00509d] hover:underline flex items-center gap-1">
                        <span>See all</span>
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                    @forelse($others as $item)
                        @php
                            $isPdf = $item->image && \Illuminate\Support\Str::endsWith(strtolower($item->image), ['.pdf']);
                        @endphp
                        <article class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition duration-300 overflow-hidden flex flex-col justify-between group">
                            <div>
                                <!-- Image -->
                                <div class="w-full h-44 overflow-hidden rounded-xl bg-slate-100 mb-3">
                                    <a href="{{ route('pelamar.tips-kerja.show', $item->id) }}">
                                        @if ($item->image && !$isPdf && file_exists(public_path('storage/' . $item->image)))
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @else
                                            <img src="{{ asset('images/tips_kerja_default.png') }}" alt="Article"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @endif
                                    </a>
                                </div>

                                <!-- Brand & Date -->
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mb-2 px-1">
                                    <img src="{{ asset('images/logo_area_kerja_biru.png') }}" alt="AK" class="h-3.5 object-contain">
                                    <span class="font-semibold text-slate-700">Areakerja.com</span>
                                    <span>•</span>
                                    <span>{{ $item->created_at ? $item->created_at->translatedFormat('d F Y') : '14 Oktober 2024' }}</span>
                                </div>

                                <!-- Title -->
                                <h3 class="font-bold text-sm text-slate-900 leading-snug line-clamp-2 px-1 group-hover:text-[#00509d] transition">
                                    <a href="{{ route('pelamar.tips-kerja.show', $item->id) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>

                                <!-- Content snippet -->
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1.5 px-1 leading-relaxed">
                                    {!! strip_tags($item->content) !!}
                                </p>
                            </div>

                            <!-- Footer Category & Read Time -->
                            <div class="mt-4 pt-2 border-t border-slate-50 flex items-center justify-between text-[11px] text-slate-400 px-1">
                                <span class="font-semibold text-[#00509d]">Tips</span>
                                <span>20 menit</span>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full text-center py-16 text-slate-400">
                            Belum ada artikel tips kerja.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Back to top button -->
        <a href="#top"
            class="fixed bottom-6 right-6 bg-[#00509d] text-white p-3.5 rounded-full shadow-xl hover:bg-[#003d7a] transition z-40 flex items-center justify-center"
            title="Kembali ke Atas">
            <i class="ph ph-arrow-up font-bold text-lg"></i>
        </a>
    </div>

    @include('layouts.footer')
@endsection
