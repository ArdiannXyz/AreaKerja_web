@extends('layouts.index')

@section('content')
    <div class="min-h-screen bg-slate-50/50 pt-28 pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            <!-- Filter Status Bar -->
            <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Event & Job Fair</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Temukan berbagai acara seminar karir, workshop, dan bursa kerja menarik.</p>
                </div>

                <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-slate-200 shadow-xs text-xs font-semibold">
                    <a href="{{ route('pelamar.event.index') }}"
                        class="px-4 py-2 rounded-xl transition {{ empty($status) ? 'bg-[#00509d] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                        Semua
                    </a>
                    <a href="{{ route('pelamar.event.index', ['status' => 'buka']) }}"
                        class="px-4 py-2 rounded-xl transition {{ ($status ?? '') === 'buka' ? 'bg-[#00509d] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                        Upcoming
                    </a>
                    <a href="{{ route('pelamar.event.index', ['status' => 'tutup']) }}"
                        class="px-4 py-2 rounded-xl transition {{ ($status ?? '') === 'tutup' ? 'bg-[#00509d] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                        Ended
                    </a>
                </div>
            </div>

            <!-- List Events -->
            @if ($events->count() > 0)
                <div class="space-y-8">
                    @foreach ($events as $event)
                        @php
                            $isEnded = ($event->status === 'tutup' || now()->toDateString() > $event->tgl_akhir);
                            $defaultImages = [
                                'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&auto=format&fit=crop&q=80',
                                'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1200&auto=format&fit=crop&q=80',
                                'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=1200&auto=format&fit=crop&q=80'
                            ];
                            $imgUrl = $event->image ? asset('storage/' . $event->image) : $defaultImages[$loop->index % count($defaultImages)];
                        @endphp

                        <div>
                            <!-- Tanggal Event -->
                            <p class="text-xs sm:text-sm font-semibold text-slate-500 mb-2">
                                {{ \Carbon\Carbon::parse($event->tgl_mulai)->translatedFormat('d F Y') }}
                            </p>

                            <!-- Banner Card Matching Figma -->
                            <div class="relative rounded-3xl overflow-hidden shadow-md group min-h-[220px] sm:min-h-[260px] md:min-h-[290px] flex flex-col justify-end p-6 sm:p-8 md:p-10 border border-slate-100">
                                <!-- Background Image -->
                                <img src="{{ $imgUrl }}" alt="{{ $event->title }}"
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                <!-- Dark Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/50 to-black/30"></div>

                                <!-- Status Badge (Top Right) -->
                                <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-10">
                                    @if ($isEnded)
                                        <span class="px-3.5 py-1 bg-slate-700/90 backdrop-blur-md text-white font-bold text-xs rounded-full shadow-sm">
                                            Ended
                                        </span>
                                    @else
                                        <span class="px-3.5 py-1 bg-emerald-600/90 backdrop-blur-md text-white font-bold text-xs rounded-full shadow-sm flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> Upcoming
                                        </span>
                                    @endif
                                </div>

                                <!-- Content Inside Banner -->
                                <div class="relative z-10 max-w-2xl">
                                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-2 tracking-tight leading-snug">
                                        {{ $event->title }}
                                    </h2>
                                    <p class="text-xs sm:text-sm text-white/90 line-clamp-2 mb-5 leading-relaxed font-normal">
                                        {{ strip_tags($event->content) }}
                                    </p>
                                    <a href="{{ route('pelamar.event.show', $event->id) }}"
                                        class="inline-block bg-[#00509d] hover:bg-[#003d7a] text-white font-bold text-xs sm:text-sm px-8 py-2.5 rounded-lg shadow-md transition duration-200">
                                        Bergabung
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    @if ($events instanceof \Illuminate\Contracts\Pagination\Paginator && $events->hasPages())
                        <div class="pt-6 flex justify-center">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State Matching Figma -->
                <div class="text-center py-24 px-4 flex flex-col items-center justify-center">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 mb-6 border border-slate-200 shadow-inner">
                        <i class="ph ph-envelope-open text-5xl sm:text-6xl text-slate-400"></i>
                    </div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-500 max-w-md mx-auto leading-relaxed">
                        Tidak Ada Event Yang Tersedia untuk Saat Ini
                    </h3>
                </div>
            @endif

        </div>
    </div>

    @include('layouts.footer')
@endsection
