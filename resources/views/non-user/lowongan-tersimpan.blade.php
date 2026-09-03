@extends('layouts.index')
@section('content')

    <div class="bg-white min-h-screen text-slate-800 pt-20 pb-20">

        {{-- Top Title Bar --}}
        <div class="border-b-2 border-[#00509d] bg-white py-4 mb-8">
            <h1 class="text-center font-bold text-[#00509d] text-lg md:text-xl">
                Lowongan Tersimpan
            </h1>
        </div>

        @guest
            {{-- TAMPILAN BELUM LOGIN (GUEST) SESUAI FIGMA --}}
            <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                {{-- Icon --}}
                <div class="w-24 h-24 mb-6 flex items-center justify-center text-[#00509d]">
                    <svg class="w-20 h-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        <polyline points="9 10 11 12 15 8"></polyline>
                    </svg>
                </div>

                {{-- Message --}}
                <p class="text-[#00509d] font-bold text-base md:text-lg leading-relaxed mb-8 max-w-xs">
                    Simpan lowongan pekerjaan favorit anda untuk dilamar nanti
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
            {{-- TAMPILAN SUDAH LOGIN SESUAI FIGMA --}}
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                @if ($simpanlowongan && $simpanlowongan->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch mb-10">
                        @foreach ($simpanlowongan as $item)
                            @php $lowongan = $item->lowongan; @endphp
                            @if ($lowongan)
                                <div class="h-full cursor-pointer"
                                    onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $lowongan->perusahaan->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) }}'">
                                    @include('non-user.components.card', ['lowongan' => $lowongan])
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if ($simpanlowongan->count() > 6)
                        <div class="flex justify-center mt-8 mb-12">
                            <button class="bg-[#004e98] hover:bg-[#003d7a] text-white font-bold px-8 py-3 rounded-lg shadow-sm hover:shadow transition text-sm">
                                Muat lebih banyak....
                            </button>
                        </div>
                    @endif
                @else
                    {{-- Kosong Setelah Login --}}
                    <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                        <div class="w-20 h-20 mb-4 flex items-center justify-center text-slate-300">
                            <i class="ph ph-bookmark-simple text-6xl"></i>
                        </div>
                        <h3 class="text-slate-700 font-bold text-base mb-1">Belum Ada Lowongan Tersimpan</h3>
                        <p class="text-slate-500 text-xs md:text-sm mb-6">Simpan lowongan kerja yang menarik perhatian Anda untuk dilamar nanti.</p>
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
