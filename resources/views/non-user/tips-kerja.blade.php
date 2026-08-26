@extends('layouts.index')
@section('content')

    @php
        $headline = $head ?? ($others->first() ?? null);
        $isHeadlinePdf = $headline && $headline->image && \Illuminate\Support\Str::endsWith(strtolower($headline->image), ['.pdf']);
    @endphp

    <div class="bg-white min-h-screen text-slate-800 pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <!-- Hero Featured Article Banner -->
            @if ($headline)
                <div class="space-y-6 mb-12">
                    <!-- Featured Image -->
                    <div class="w-full h-72 sm:h-96 md:h-[450px] overflow-hidden rounded-2xl md:rounded-3xl shadow-sm border border-slate-100">
                        @if ($headline->image && !$isHeadlinePdf)
                            <img src="{{ asset('storage/' . $headline->image) }}" alt="{{ $headline->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop" alt="Hero Banner" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <!-- Title & Action Buttons -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight font-serif flex-1">
                            <a href="{{ route('pelamar.tips-kerja.show', $headline->id) }}" class="hover:text-orange-600 transition">
                                {{ $headline->title }}
                            </a>
                        </h1>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('pelamar.tips-kerja.show', $headline->id) }}"
                                class="w-9 h-9 bg-orange-500 text-white rounded-full flex items-center justify-center shadow-sm hover:bg-orange-600 transition">
                                <i class="ph ph-arrow-up-right font-bold text-lg"></i>
                            </a>

                            <div x-data="{ showMenu: false }" class="relative">
                                <button @click="showMenu = !showMenu"
                                    class="w-9 h-9 border border-slate-200 text-slate-600 hover:text-slate-900 rounded-full flex items-center justify-center transition">
                                    <i class="ph ph-share-network text-lg"></i>
                                </button>
                                <div x-show="showMenu" @click.outside="showMenu = false" x-transition x-cloak
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 py-2 text-slate-800">
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

                    <!-- Meta info line -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-6 text-xs sm:text-sm">
                        <span class="font-extrabold text-orange-600">
                            {{ $headline->penulis ?? 'Areakerja.com' }}
                        </span>
                        <span class="text-slate-400 font-medium">
                            {{ $headline->created_at ? $headline->created_at->translatedFormat('l, d F Y H:i') . ' WIB' : 'Kamis, 27 Oktober 13:00 WIB' }}
                        </span>
                    </div>
                </div>
            @endif

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap items-center gap-2 mb-8 border-b border-slate-100 pb-4">
                @php
                    $categories = [
                        'Semua'            => 'Semua Artikel',
                        'Tips Kerja'       => 'Tips Kerja',
                        'Interview & Gaji' => 'Interview & Gaji',
                        'CV & Lamaran'     => 'CV & Lamaran',
                        'Top News'         => 'Top News'
                    ];
                    $currentCat = request('kategori', 'Semua');
                @endphp

                @foreach ($categories as $key => $label)
                    <a href="{{ route('pelamar.tips-kerja', $key === 'Semua' ? [] : ['kategori' => $key]) }}"
                        class="px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition {{ $currentCat === $key ? 'bg-orange-500 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Tips Kerja Grid Section -->
            <div class="pt-2 mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        {{ $currentCat === 'Semua' ? 'Artikel Terbaru' : $currentCat }}
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse ($others->when($headline, fn($q) => $q->where('id', '!=', $headline->id)) as $artikel)
                        @php
                            $isPdf = $artikel->image && \Illuminate\Support\Str::endsWith(strtolower($artikel->image), ['.pdf']);
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                            <div>
                                <!-- Image -->
                                <div class="w-full h-44 bg-slate-100 rounded-xl overflow-hidden mb-3 relative">
                                    @if ($artikel->image && !$isPdf)
                                        <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=400&auto=format&fit=crop" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300">
                                    @endif
                                </div>

                                <!-- Metadata -->
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-medium mb-1.5">
                                    <img src="{{ asset('images/logoarea.png') }}" alt="AK" class="h-3.5 object-contain">
                                    <span class="font-bold text-slate-700">Areakerja.com</span>
                                    <span>•</span>
                                    <span>{{ $artikel->created_at ? $artikel->created_at->translatedFormat('d F Y') : '-' }}</span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug mb-1.5">
                                    <a href="{{ route('pelamar.tips-kerja.show', $artikel->id) }}">
                                        {{ $artikel->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                @if ($artikel->intro)
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-3">
                                        {{ strip_tags($artikel->intro) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Footer Tag -->
                            <div class="pt-2 border-t border-slate-100 flex items-center gap-1 text-[11px] font-bold text-orange-600">
                                <span>{{ $artikel->kategori ?? 'Tips Kerja' }}</span>
                                <span>•</span>
                                <span>5 min baca</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                            <p class="text-sm text-slate-500 font-medium">Belum ada artikel tips kerja lainnya.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Floating Back to Top Button --}}
    <a href="#top"
        class="fixed bottom-8 right-8 bg-orange-500 hover:bg-orange-600 text-white p-3.5 rounded-full shadow-lg hover:scale-110 transition-all duration-300 z-40 flex items-center justify-center">
        <i class="ph ph-caret-up text-xl font-bold"></i>
    </a>

    @include('layouts.footer')
@endsection
