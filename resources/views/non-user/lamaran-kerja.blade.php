@extends('layouts.index')
@section('content')

    <div class="bg-white min-h-screen text-slate-800 pt-20 pb-20">

        {{-- Top Title Bar --}}
        <div class="border-b-2 border-[#00509d] bg-white py-4 mb-8">
            <h1 class="text-center font-bold text-[#00509d] text-lg md:text-xl">
                Lamaran Kerja
            </h1>
        </div>

        @guest
            {{-- TAMPILAN BELUM LOGIN SESUAI FIGMA (Lowongan Tersimpan - Belum Login.png) --}}
            <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                {{-- Briefcase Icon in Blue --}}
                <div class="w-24 h-24 mb-6 flex items-center justify-center text-[#00509d]">
                    <svg class="w-20 h-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>

                {{-- Message --}}
                <p class="text-[#00509d] font-bold text-base md:text-lg leading-relaxed mb-8 max-w-xs">
                    Lacak semua status lamaran anda dengan mudah
                </p>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-center gap-4 w-full max-w-xs">
                    <a href="{{ route('login') }}"
                        class="flex-1 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-sm transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex-1 border-2 border-[#00509d] text-[#00509d] hover:bg-[#00509d] hover:text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm transition">
                        Daftar
                    </a>
                </div>
            </div>
        @else
            {{-- TAMPILAN SUDAH LOGIN --}}
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                @if (isset($lamaranList) && $lamaranList->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch mb-10">
                        @foreach ($lamaranList as $item)
                            @php $lowongan = $item->lowongan_perusahaan; @endphp
                            @if ($lowongan)
                                <div class="h-full cursor-pointer"
                                    onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $lowongan->perusahaan->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) }}'">
                                    @include('non-user.components.card', ['lowongan' => $lowongan])
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                        <div class="w-20 h-20 mb-4 flex items-center justify-center text-slate-300">
                            <i class="ph ph-briefcase text-6xl"></i>
                        </div>
                        <h3 class="text-slate-700 font-bold text-base mb-1">Belum Ada Lamaran Kerja</h3>
                        <p class="text-slate-500 text-xs md:text-sm mb-6">Anda belum mengajukan lamaran ke lowongan pekerjaan apapun.</p>
                        <a href="{{ route('beranda') }}"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-sm transition">
                            Cari Lowongan Sekarang
                        </a>
                    </div>
                @endif
            </div>
        @endguest

    </div>

    @include('layouts.footer')
@endsection
