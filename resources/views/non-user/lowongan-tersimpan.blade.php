@extends('layouts.index')
@section('content')

    <div class="bg-slate-100 min-h-screen text-slate-800">
        <!-- Hero Section -->
        <div class="relative">
            <img src="{{ asset('images/tersimpan.jpg') }}"
                alt="Header Image" class="w-full h-[300px] sm:h-[380px] md:h-[450px] object-cover">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-xs"></div>

            <div class="absolute left-6 sm:left-12 md:left-20 bottom-12 sm:bottom-16 md:bottom-20 text-white max-w-xs sm:max-w-md md:max-w-2xl">
                <h1 class="text-2xl md:text-4xl font-extrabold text-white drop-shadow-md">Lowongan Tersimpan</h1>
                <p class="text-white/90 text-sm md:text-base mt-2 font-medium leading-relaxed">
                    Daftar lowongan kerja favorit yang telah Anda simpan di areakerja.com
                </p>
            </div>
        </div>

        <!-- Grid Container (Matching Beranda Grid) -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                @forelse($simpanlowongan as $item)
                    @php $lowongan = $item->lowongan; @endphp
                    @if ($lowongan)
                        <div class="h-full cursor-pointer"
                            onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $lowongan->perusahaan->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) }}'">
                            @include('non-user.components.card', ['lowongan' => $lowongan])
                        </div>
                    @endif
                @empty
                    <div class="col-span-1 md:col-span-2 text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 shadow-sm">
                        <i class="ph ph-bookmark-simple text-5xl text-slate-300 mb-3 inline-block"></i>
                        <h3 class="text-slate-700 font-bold text-lg mb-1">Belum Ada Lowongan Tersimpan</h3>
                        <p class="text-slate-500 text-xs md:text-sm font-medium mb-4">Simpan lowongan kerja yang menarik perhatian Anda untuk dilamar nanti.</p>
                        <a href="{{ route('pelamar.beranda') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs md:text-sm px-5 py-2.5 rounded-xl shadow-sm transition">
                            Cari Lowongan Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection
