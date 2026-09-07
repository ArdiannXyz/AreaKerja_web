@extends('layouts.index')
@section('content')

    <div class="bg-slate-50 min-h-screen text-slate-800 pt-24 sm:pt-28 md:pt-32 pb-20">

        {{-- Top Title Header Container (Rounded) --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 mb-8">
            <div class="bg-white border border-slate-200/80 rounded-2xl md:rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-bold text-[#00509d] text-xl md:text-2xl">
                        Lowongan Tersimpan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Kelola dan tinjau kembali semua lowongan pekerjaan favorit yang telah Anda simpan.
                    </p>
                </div>
                @auth
                    <a href="{{ route('beranda') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#00509d] hover:text-[#003d7a] bg-sky-50 hover:bg-sky-100 px-4 py-2.5 rounded-xl transition shadow-xs shrink-0">
                        <i class="ph ph-plus-circle text-base"></i>
                        <span>Cari Lowongan Baru</span>
                    </a>
                @endauth
            </div>
        </div>

        @guest
            {{-- TAMPILAN BELUM LOGIN (GUEST) --}}
            <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                <div class="w-24 h-24 mb-6 flex items-center justify-center rounded-3xl bg-[#00509d]/10 text-[#00509d]">
                    <svg class="w-14 h-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        <polyline points="9 10 11 12 15 8"></polyline>
                    </svg>
                </div>

                <h2 class="text-slate-800 font-bold text-lg md:text-xl mb-2">Simpan Lowongan Favorit Anda</h2>
                <p class="text-slate-500 text-sm leading-relaxed mb-8 max-w-xs">
                    Masuk ke akun AreaKerja Anda untuk menyimpan lowongan pekerjaan dan melamarnya kapan saja.
                </p>

                <div class="flex items-center justify-center gap-4 w-full max-w-xs">
                    <a href="{{ route('login') }}"
                        class="flex-1 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-md transition">
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
                @if ($simpanlowongan && $simpanlowongan->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch mb-10">
                        @foreach ($simpanlowongan as $item)
                            @php
                                $lowongan = $item->lowongan;
                                $perusahaan = $lowongan?->perusahaan;
                            @endphp
                            @if ($lowongan)
                                <div x-data="{ saved: true, loading: false }"
                                    x-show="saved"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between relative group">
                                    
                                    <div>
                                        {{-- Header Badges & Bookmark Icon --}}
                                        <div class="flex items-start justify-between gap-3 mb-3 pb-2 border-b border-slate-100">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1 bg-[#d7ebfc] text-[#00509d] text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-2xs">
                                                    <i class="ph-fill ph-bookmark-simple text-xs"></i>
                                                    Tersimpan
                                                </span>

                                                @if (!is_null($lowongan->boosted_until))
                                                    <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-2xs">
                                                        Boosted
                                                    </span>
                                                @endif

                                                @if ($lowongan->rekomendasi !== null)
                                                    <span class="bg-sky-50 text-sky-700 border border-sky-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-2xs">
                                                        Rekomendasi
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Bookmark Action Button --}}
                                            <button type="button"
                                                @click.prevent.stop="
                                                    if (loading) return;
                                                    loading = true;
                                                    fetch('/pelamar/simpan-lowongan/{{ $lowongan->id }}', {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'Accept': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                                        }
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        loading = false;
                                                        if (data.success) {
                                                            saved = false;
                                                            Swal.fire({
                                                                toast: true,
                                                                position: 'top-end',
                                                                icon: 'success',
                                                                title: 'Dihapus dari simpanan',
                                                                showConfirmButton: false,
                                                                timer: 1500
                                                            });
                                                        } else {
                                                            Swal.fire({
                                                                toast: true,
                                                                position: 'top-end',
                                                                icon: 'error',
                                                                title: data.message || 'Gagal menghapus',
                                                                showConfirmButton: false,
                                                                timer: 1500
                                                            });
                                                        }
                                                    })
                                                    .catch(err => {
                                                        loading = false;
                                                        console.error(err);
                                                    });
                                                "
                                                class="p-1.5 text-[#00509d] hover:text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer shrink-0"
                                                title="Hapus dari Simpanan">
                                                <i class="ph-fill ph-bookmark-simple text-2xl"></i>
                                            </button>
                                        </div>

                                        {{-- Job Title & Company Info --}}
                                        <div class="flex items-start gap-3.5 mb-3.5">
                                            <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-slate-100 bg-slate-50 flex items-center justify-center shadow-xs">
                                                @if (!empty($perusahaan?->img_profile))
                                                    <img src="{{ asset('storage/' . $perusahaan->img_profile) }}" alt="Logo" class="w-full h-full object-contain">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($perusahaan?->nama_perusahaan ?? 'Perusahaan') }}&background=00509d&color=fff&size=80"
                                                        alt="Logo" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route('detail.lowongan.non.user', ['perusahaan' => $perusahaan?->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) }}"
                                                    class="font-bold text-slate-800 hover:text-[#00509d] text-base leading-snug line-clamp-1 transition">
                                                    {{ $lowongan->nama }}
                                                </a>
                                                <p class="text-xs font-semibold text-slate-600 truncate mt-0.5">
                                                    {{ $perusahaan?->nama_perusahaan ?? 'Perusahaan' }}
                                                </p>
                                                <p class="text-[11px] text-slate-400 truncate flex items-center gap-1 mt-0.5">
                                                    <i class="ph ph-map-pin text-slate-400"></i>
                                                    {{ $lowongan->alamat ?? $perusahaan?->alamat ?? 'Lokasi tidak tertera' }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Gaji Bar --}}
                                        @if ($lowongan->gaji_awal)
                                            <div class="mb-3.5">
                                                <span class="inline-block bg-[#00509d]/10 text-[#00509d] font-bold px-3 py-1 rounded-lg text-xs">
                                                    Rp. {{ number_format($lowongan->gaji_awal, 0, ',', '.') }} per bulan
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Deskripsi Ringkas --}}
                                        @if (!empty($lowongan->deskripsi))
                                            <div class="text-xs text-slate-600 leading-relaxed space-y-1 mb-3 line-clamp-2">
                                                <span class="inline-block mr-1 font-bold text-slate-700">•</span>{!! Str::limit(strip_tags($lowongan->deskripsi), 120) !!}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Card Footer Actions --}}
                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 mt-2">
                                        <span class="text-[11px] text-slate-400 font-medium">
                                            {{ $lowongan->published_at ? 'Aktif ' . $lowongan->published_at->diffForHumans() : 'Lowongan Aktif' }}
                                        </span>

                                        <a href="{{ route('detail.lowongan.non.user', ['perusahaan' => $perusahaan?->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00509d] hover:text-[#003d7a] bg-sky-50 hover:bg-sky-100 px-3.5 py-2 rounded-xl transition">
                                            <span>Lihat Lowongan</span>
                                            <i class="ph ph-arrow-right text-xs"></i>
                                        </a>
                                    </div>

                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    {{-- Kosong Setelah Login --}}
                    <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                        <div class="w-20 h-20 mb-4 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <i class="ph ph-bookmark-simple text-4xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-base mb-1">Belum Ada Lowongan Tersimpan</h3>
                        <p class="text-slate-500 text-xs md:text-sm mb-6">Simpan lowongan kerja yang menarik perhatian Anda untuk dilamar nanti.</p>
                        <a href="{{ route('beranda') }}"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-md transition">
                            Cari Lowongan Sekarang
                        </a>
                    </div>
                @endif
            </div>
        @endguest

    </div>

    @include('layouts.footer')
@endsection
