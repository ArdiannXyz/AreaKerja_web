@extends('layouts.index')

@section('content')
    <div class="min-h-screen bg-slate-50">
        {{-- Hero Header --}}
        <div class="relative bg-gradient-to-r from-orange-600 via-orange-500 to-amber-500 text-white pt-28 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="ph ph-calendar-star text-base"></i> Agenda & Bursa Karir
                </span>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-3">
                    Event & Job Fair AreaKerja
                </h1>
                <p class="text-white/90 text-xs sm:text-sm md:text-base max-w-2xl mx-auto">
                    Ikuti Job Fair virtual, webinar persiapan karir, workshop interview, dan temu HR langsung dari puluhan perusahaan ternama.
                </p>

                {{-- Search Box --}}
                <div class="mt-8 max-w-xl mx-auto">
                    <form action="{{ route('pelamar.event.index') }}" method="GET" class="flex items-center gap-2 bg-white p-1.5 rounded-2xl shadow-lg">
                        <div class="flex items-center gap-2 pl-3 flex-1">
                            <i class="ph ph-magnifying-glass text-slate-400 text-lg"></i>
                            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama event, webinar, atau lokasi..."
                                class="w-full text-xs sm:text-sm text-slate-700 bg-transparent focus:outline-none border-none ring-0 placeholder:text-slate-400 py-2">
                        </div>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-5 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-sm cursor-pointer shrink-0">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Events Container --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {{-- Filter Pills --}}
            <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="ph ph-sparkle text-orange-500"></i> Event Sedang Berlangsung & Akan Datang
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Jangan lewatkan kesempatan berharga untuk mengembangkan karir impian Anda.</p>
                </div>

                <div class="flex items-center gap-1.5 bg-white p-1 rounded-xl border border-slate-200 shadow-xs text-xs font-semibold">
                    <a href="{{ route('pelamar.event.index') }}" class="px-3 py-1.5 rounded-lg transition {{ empty($status) ? 'bg-orange-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                        Semua
                    </a>
                    <a href="{{ route('pelamar.event.index', ['status' => 'buka']) }}" class="px-3 py-1.5 rounded-lg transition {{ ($status ?? '') === 'buka' ? 'bg-orange-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                        Pendaftaran Buka
                    </a>
                </div>
            </div>

            {{-- Grid of Events --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($events as $event)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col group">
                        {{-- Poster Image --}}
                        <div class="relative h-44 bg-slate-100 overflow-hidden">
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 flex items-center justify-center p-6 text-center text-white relative">
                                    <div class="absolute inset-0 bg-black/10"></div>
                                    <div class="relative z-10">
                                        <i class="ph ph-calendar-check text-4xl mb-1 opacity-90"></i>
                                        <div class="font-bold text-sm line-clamp-2">{{ $event->title }}</div>
                                    </div>
                                </div>
                            @endif

                            {{-- Status Badge --}}
                            <div class="absolute top-3 left-3 z-20">
                                @if ($event->status === 'buka')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white shadow-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Pendaftaran Buka
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-600 text-white shadow-xs">
                                        Selesai
                                    </span>
                                @endif
                            </div>

                            {{-- Date Badge --}}
                            <div class="absolute bottom-3 right-3 z-20 bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-lg text-[11px] font-bold text-slate-800 shadow-xs flex items-center gap-1">
                                <i class="ph ph-calendar text-orange-500"></i>
                                {{ \Carbon\Carbon::parse($event->tgl_mulai)->translatedFormat('d M Y') }}
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 text-base mb-2 line-clamp-2 group-hover:text-orange-600 transition">
                                    {{ $event->title }}
                                </h3>

                                <p class="text-slate-500 text-xs line-clamp-2 mb-4 leading-relaxed">
                                    {{ strip_tags($event->content) }}
                                </p>

                                {{-- Meta Badges --}}
                                <div class="space-y-1.5 text-xs text-slate-600 mb-5">
                                    <div class="flex items-center gap-2">
                                        <i class="ph ph-map-pin text-orange-500 text-sm shrink-0"></i>
                                        <span class="truncate">{{ $event->lokasi ?: 'Online Webinar' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="ph ph-clock text-orange-500 text-sm shrink-0"></i>
                                        <span>{{ $event->jam_mulai }} - {{ $event->jam_akhir }} WIB</span>
                                    </div>
                                    @if ($event->kuota)
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-users text-orange-500 text-sm shrink-0"></i>
                                            <span>Kuota: <strong>{{ $event->kuota }} Peserta</strong></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            <a href="{{ route('pelamar.event.show', $event->id) }}"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-4 rounded-xl text-center text-xs transition shadow-xs flex items-center justify-center gap-1.5 group-hover:shadow">
                                <span>Lihat Detail & Daftar</span>
                                <i class="ph ph-arrow-right font-bold text-xs"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-2xl border border-dashed border-slate-300 shadow-xs">
                        <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl">
                            <i class="ph ph-calendar-x"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-700 mb-1">Belum Ada Event Ditemukan</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Saat ini belum ada event atau job fair yang sesuai dengan pencarian Anda. Silakan cek kembali dalam beberapa waktu ke depan.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($events->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('layouts.footer')
@endsection
