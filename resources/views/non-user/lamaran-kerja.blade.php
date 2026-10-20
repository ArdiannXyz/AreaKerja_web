@extends('layouts.index')
@section('content')

    <div class="bg-slate-50/60 min-h-[calc(100vh-200px)] text-slate-800 pt-28 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 pb-4 border-b border-slate-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-[#00509d] text-white flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Lamaran Kerja</h1>
                        <p class="text-xs sm:text-sm text-slate-500">Pantau dan kelola seluruh riwayat lamaran kerja yang telah Anda kirimkan</p>
                    </div>
                </div>

                @auth
                    @if (isset($lamaranList) && $lamaranList->count() > 0)
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-200">
                                <i class="ph ph-briefcase"></i>
                                {{ ($lamaranList instanceof \Illuminate\Contracts\Pagination\Paginator ? $lamaranList->total() : $lamaranList->count()) }} Lamaran Terkirim
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
                {{-- TAMPILAN BELUM LOGIN --}}
                <div class="max-w-md mx-auto my-12 p-8 md:p-10 bg-white rounded-3xl border border-slate-200/80 shadow-sm text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 mb-5 rounded-2xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                        <i class="ph-fill ph-briefcase text-4xl"></i>
                    </div>

                    <h3 class="text-slate-800 font-bold text-lg mb-2">Lacak Lamaran Kerja Anda</h3>
                    <p class="text-slate-500 text-xs md:text-sm leading-relaxed mb-8 max-w-xs">
                        Masuk ke akun AreaKerja untuk melihat dan memantau status lamaran pekerjaan yang telah Anda kirimkan.
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

                    @if ($lamaranList instanceof \Illuminate\Contracts\Pagination\Paginator && $lamaranList->hasPages())
                        <div class="mt-8 mb-12 flex justify-center">
                            {{ $lamaranList->links() }}
                        </div>
                    @endif
                @else
                    {{-- Kosong Setelah Login --}}
                    <div class="max-w-md mx-auto my-12 p-8 md:p-10 bg-white rounded-3xl border border-slate-200/80 shadow-sm text-center flex flex-col items-center justify-center">
                        <div class="w-20 h-20 mb-4 rounded-2xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                            <i class="ph ph-briefcase text-4xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-base md:text-lg mb-1">Belum Ada Lamaran Kerja</h3>
                        <p class="text-slate-500 text-xs md:text-sm mb-6 max-w-xs leading-relaxed">
                            Anda belum mengajukan lamaran ke lowongan pekerjaan apapun. Cari lowongan yang cocok dan kirimkan lamaran Anda sekarang!
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
