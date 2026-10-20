
@extends('layouts.index')
@section('content')

    <div class="bg-slate-50/60 min-h-[calc(100vh-200px)] text-slate-800 pt-28 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 pb-4 border-b border-slate-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-[#00509d] text-white flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="ph-fill ph-bookmark-simple"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Lowongan Tersimpan</h1>
                        <p class="text-xs sm:text-sm text-slate-500">Kelola dan lamar lowongan pekerjaan favorit yang telah Anda simpan</p>
                    </div>
                </div>

                @auth
                    @if ($simpanlowongan && $simpanlowongan->count() > 0)
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-200">
                                <i class="ph ph-briefcase"></i>
                                {{ ($simpanlowongan instanceof \Illuminate\Contracts\Pagination\Paginator ? $simpanlowongan->total() : $simpanlowongan->count()) }} Lowongan Tersimpan
                            </span>
                            <a href="{{ route('beranda') }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-[#00509d] hover:bg-white border border-slate-200/80 shadow-2xs transition">
                                <i class="ph ph-magnifying-glass"></i> Cari Lowongan Lain
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            @guest
                {{-- TAMPILAN BELUM LOGIN (GUEST) --}}
                <div class="max-w-md mx-auto my-12 p-8 md:p-10 bg-white rounded-3xl border border-slate-200/80 shadow-sm text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 mb-5 rounded-2xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                        <i class="ph-fill ph-bookmark-simple text-4xl"></i>
                    </div>

                    <h3 class="text-slate-800 font-bold text-lg mb-2">Simpan Lowongan Favorit Anda</h3>
                    <p class="text-slate-500 text-xs md:text-sm leading-relaxed mb-8 max-w-xs">
                        Masuk atau buat akun AreaKerja untuk menyimpan lowongan dan melamarnya kapan saja dengan cepat.
                    </p>

                    <div class="flex items-center justify-center gap-3 w-full max-w-xs">
                        <a href="{{ route('login') }}"
                            class="flex-1 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-sm hover:shadow transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex-1 border-2 border-[#00509d] text-[#00509d] hover:bg-blue-50 font-bold py-2.5 px-6 rounded-xl text-center text-sm transition">
                            Daftar
                        </a>
                    </div>
                </div>
            @else
                {{-- TAMPILAN SUDAH LOGIN --}}
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

                        {{-- Kartu Pelengkap Jika Hanya 1 Item Agar Tampilan Seimbang --}}
                        @if ($simpanlowongan->count() === 1)
                            <a href="{{ route('beranda') }}"
                                class="rounded-2xl border-2 border-dashed border-slate-200 hover:border-[#00509d] bg-white/70 hover:bg-white p-6 md:p-8 flex flex-col items-center justify-center text-center group transition-all duration-200 min-h-[260px] shadow-2xs hover:shadow-sm">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#00509d] flex items-center justify-center text-2xl mb-3.5 group-hover:scale-110 transition-transform">
                                    <i class="ph ph-magnifying-glass-plus"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800 group-hover:text-[#00509d] transition-colors mb-1.5">
                                    Temukan Lowongan Lainnya
                                </h3>
                                <p class="text-xs text-slate-500 max-w-xs leading-relaxed mb-4">
                                    Jelajahi ratusan lowongan kerja terbaru dari perusahaan terpercaya di AreaKerja
                                </p>
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#00509d] group-hover:underline">
                                    Jelajahi Sekarang <i class="ph ph-arrow-right"></i>
                                </span>
                            </a>
                        @endif
                    </div>

                    @if ($simpanlowongan instanceof \Illuminate\Contracts\Pagination\Paginator && $simpanlowongan->hasPages())
                        <div class="mt-8 mb-12 flex justify-center">
                            {{ $simpanlowongan->links() }}
                        </div>
                    @endif
                @else
                    {{-- Kosong Setelah Login --}}
                    <div class="max-w-md mx-auto my-12 p-8 md:p-10 bg-white rounded-3xl border border-slate-200/80 shadow-sm text-center flex flex-col items-center justify-center">
                        <div class="w-20 h-20 mb-4 rounded-2xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                            <i class="ph ph-bookmark-simple text-4xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-base md:text-lg mb-1">Belum Ada Lowongan Tersimpan</h3>
                        <p class="text-slate-500 text-xs md:text-sm mb-6 max-w-xs leading-relaxed">
                            Simpan lowongan kerja yang menarik perhatian Anda untuk dilamar nanti dengan menekan tombol bookmark.
                        </p>
                        <a href="{{ route('beranda') }}"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-sm hover:shadow transition inline-flex items-center gap-2">
                            <i class="ph ph-magnifying-glass"></i> Cari Lowongan Sekarang
                        </a>
                    </div>
                @endif
            @endguest

        </div>
    </div>

    @include('layouts.footer')
@endsection
