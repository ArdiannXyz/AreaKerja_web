@extends('layouts.index')
@section('content')

    <div class="bg-white min-h-screen text-slate-800">

        {{-- Hero Header Section --}}
        <section class="bg-[#0054a6] md:bg-gradient-to-b md:from-[#0054a6] md:to-[#005eb8] text-white pt-24 md:pt-28 pb-14 md:pb-16 px-4 sm:px-6 relative overflow-hidden">
            <div class="max-w-5xl mx-auto relative z-10 text-center">

                {{-- Title & Subtitle --}}
                <div class="mb-6 md:mb-8">
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white leading-tight drop-shadow-sm">
                        Temukan Karir Impian Anda<br class="hidden sm:inline"> di areakerja.com
                    </h1>
                    <p class="text-white/90 text-xs sm:text-sm md:text-base mt-3 font-normal max-w-2xl mx-auto leading-relaxed">
                        Ribuan lowongan kerja terbaru dari perusahaan terverifikasi di indonesia<br class="hidden sm:inline"> siap anda lamar hari ini
                    </p>
                </div>

                {{-- Search Bar Container dengan Filter Terintegrasi --}}
                <div class="w-full flex justify-center mb-4">
                    <div class="w-full max-w-5xl">
                        <form action="{{ route('lowongan.search') }}" method="GET">
                            <div class="bg-white rounded-2xl shadow-2xl p-3 md:p-3.5 border border-white/30 text-left">
                                
                                {{-- Baris Utama Pencarian: Posisi & Button Cari --}}
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                    
                                    {{-- Posisi / Kata Kunci --}}
                                    <div class="flex-1 flex items-center gap-2.5 px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/80 rounded-xl border border-slate-200 transition focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/10 focus-within:bg-white">
                                        <i class="ph ph-magnifying-glass text-lg text-slate-400"></i>
                                        <input type="text" name="posisi" value="{{ request('posisi', $posisi ?? '') }}"
                                            placeholder="Posisi lowongan, keahlian, atau kata kunci..."
                                            class="w-full border-none focus:ring-0 text-xs sm:text-sm text-slate-800 placeholder-slate-400 bg-transparent font-medium p-0 focus:outline-none">
                                    </div>

                                    <input type="hidden" name="kategori" id="kategoriInput" value="{{ request('kategori', $kategori ?? '') }}">
                                    @if(request('lokasi'))
                                        <input type="hidden" name="lokasi" value="{{ request('lokasi') }}">
                                    @endif

                                    {{-- Button Search --}}
                                    <button type="submit"
                                        class="w-full sm:w-auto bg-[#00509d] hover:bg-[#003d7a] text-white font-bold text-xs sm:text-sm px-7 py-2.5 sm:py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 whitespace-nowrap flex items-center justify-center gap-2 cursor-pointer">
                                        <i class="ph ph-magnifying-glass font-bold text-base"></i>
                                        <span>Cari Lowongan</span>
                                    </button>

                                </div>

                                {{-- Baris Filter Sejajar: Provinsi, Kota, Jenis, Pengalaman, Pendidikan --}}
                                <div class="mt-2.5 pt-2.5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                                        <span class="inline-flex items-center gap-1 font-bold text-slate-500 uppercase tracking-wider text-[11px] mr-1">
                                            <i class="ph ph-sliders-horizontal text-slate-400 text-sm"></i> Filter:
                                        </span>

                                        {{-- Filter Provinsi (API Wilayah) --}}
                                        <div class="relative flex items-center bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition focus-within:border-[#00509d]">
                                            <i class="ph ph-map-pin text-sm text-slate-400 ml-2.5 pointer-events-none shrink-0"></i>
                                            <select name="provinsi" id="filterProvinsi"
                                                style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none !important;"
                                                class="border-0 border-none ring-0 focus:ring-0 focus:outline-none bg-transparent text-slate-700 font-semibold pl-1.5 pr-7 py-1.5 cursor-pointer text-xs max-w-[150px] truncate">
                                                <option value="">Semua Provinsi</option>
                                                @if(isset($provinsis))
                                                    @foreach ($provinsis as $p)
                                                        <option value="{{ $p->id }}" data-nama="{{ $p->nama }}"
                                                            {{ (string)request('provinsi', $provinsi ?? '') === (string)$p->id || strcasecmp(request('provinsi', $provinsi ?? ''), $p->nama) === 0 ? 'selected' : '' }}>
                                                            {{ $p->nama }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>

                                        {{-- Filter Kota (API Wilayah Dinamis) --}}
                                        <div class="relative flex items-center bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition focus-within:border-[#00509d]">
                                            <i class="ph ph-buildings text-sm text-slate-400 ml-2.5 pointer-events-none shrink-0"></i>
                                            <select name="kota" id="filterKota"
                                                style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none !important;"
                                                class="border-0 border-none ring-0 focus:ring-0 focus:outline-none bg-transparent text-slate-700 font-semibold pl-1.5 pr-7 py-1.5 cursor-pointer text-xs max-w-[150px] truncate">
                                                <option value="">Semua Kota</option>
                                                @if (!empty(request('kota', $kota ?? '')))
                                                    <option value="{{ request('kota', $kota ?? '') }}" selected>
                                                        {{ request('kota', $kota ?? '') }}
                                                    </option>
                                                @endif
                                            </select>
                                            <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>

                                        {{-- Filter Jenis Lowongan --}}
                                        <div class="relative flex items-center bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition focus-within:border-[#00509d]">
                                            <select name="jenis"
                                                style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none !important;"
                                                class="border-0 border-none ring-0 focus:ring-0 focus:outline-none bg-transparent text-slate-700 font-semibold pl-2.5 pr-7 py-1.5 cursor-pointer text-xs">
                                                <option value="" {{ empty(request('jenis', $jenis ?? '')) ? 'selected' : '' }}>💼 Semua Jenis</option>
                                                <option value="Magang" {{ request('jenis', $jenis ?? '') == 'Magang' ? 'selected' : '' }}>🎓 Magang</option>
                                                <option value="Freelance" {{ request('jenis', $jenis ?? '') == 'Freelance' ? 'selected' : '' }}>💻 Freelance</option>
                                                <option value="Kontrak" {{ in_array(request('jenis', $jenis ?? ''), ['Kontrak', 'Contract']) ? 'selected' : '' }}>📄 Kontrak</option>
                                                <option value="Full Time" {{ in_array(request('jenis', $jenis ?? ''), ['Full Time', 'Fulltime']) ? 'selected' : '' }}>🏢 Full Time</option>
                                            </select>
                                            <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>

                                        {{-- Filter Pengalaman --}}
                                        <div class="relative flex items-center bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition focus-within:border-[#00509d]">
                                            <select name="pengalaman"
                                                style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none !important;"
                                                class="border-0 border-none ring-0 focus:ring-0 focus:outline-none bg-transparent text-slate-700 font-semibold pl-2.5 pr-7 py-1.5 cursor-pointer text-xs">
                                                <option value="" {{ empty(request('pengalaman', $pengalaman ?? '')) ? 'selected' : '' }}>⏱️ Pengalaman</option>
                                                <option value="Fresh Graduate" {{ request('pengalaman', $pengalaman ?? '') == 'Fresh Graduate' ? 'selected' : '' }}>Fresh Graduate / < 1 Thn</option>
                                                <option value="1-3 Tahun" {{ request('pengalaman', $pengalaman ?? '') == '1-3 Tahun' ? 'selected' : '' }}>1 - 3 Tahun</option>
                                                <option value="3-5 Tahun" {{ request('pengalaman', $pengalaman ?? '') == '3-5 Tahun' ? 'selected' : '' }}>3 - 5 Tahun</option>
                                                <option value="> 5 Tahun" {{ request('pengalaman', $pengalaman ?? '') == '> 5 Tahun' ? 'selected' : '' }}>&gt; 5 Tahun</option>
                                            </select>
                                            <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>

                                        {{-- Filter Pendidikan --}}
                                        <div class="relative flex items-center bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition focus-within:border-[#00509d]">
                                            <select name="pendidikan"
                                                style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none !important;"
                                                class="border-0 border-none ring-0 focus:ring-0 focus:outline-none bg-transparent text-slate-700 font-semibold pl-2.5 pr-7 py-1.5 cursor-pointer text-xs">
                                                <option value="" {{ empty(request('pendidikan', $pendidikan ?? '')) ? 'selected' : '' }}>🎓 Pendidikan</option>
                                                <option value="SMA/SMK" {{ request('pendidikan', $pendidikan ?? '') == 'SMA/SMK' ? 'selected' : '' }}>SMA / SMK</option>
                                                <option value="D3" {{ request('pendidikan', $pendidikan ?? '') == 'D3' ? 'selected' : '' }}>D3 (Diploma)</option>
                                                <option value="S1" {{ request('pendidikan', $pendidikan ?? '') == 'S1' ? 'selected' : '' }}>S1 (Sarjana)</option>
                                                <option value="S2" {{ request('pendidikan', $pendidikan ?? '') == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                            </select>
                                            <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>
                                    </div>

                                    {{-- Reset Filter Button --}}
                                    @if(request('jenis') || request('pengalaman') || request('pendidikan') || request('provinsi') || request('kota') || request('posisi') || request('lokasi') || request('kategori'))
                                        <a href="{{ route('lowongan.search') }}"
                                            class="inline-flex items-center gap-1 text-rose-600 hover:text-rose-700 hover:bg-rose-50 font-semibold px-2.5 py-1.5 rounded-lg transition border border-rose-200 text-xs">
                                            <i class="ph ph-arrow-counter-clockwise"></i> Reset Filter
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tagline under search bar --}}
                <p class="text-[11px] md:text-xs text-white/90 font-normal">
                    Lamar Pekerjaan Kamu - Dengan waktu dan langkah yang cepat
                </p>

            </div>
        </section>

        {{-- ADVERTISEMENT CAROUSEL / BANNER --}}
        @if(isset($activeAds) && $activeAds->count() > 0)
            <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-6" 
                 x-data="{
                    activeSlide: 0,
                    totalSlides: {{ $activeAds->count() }},
                    autoplayTimer: null,
                    startAutoplay() {
                        if (this.totalSlides > 1) {
                            this.autoplayTimer = setInterval(() => {
                                this.nextSlide();
                            }, 5000);
                        }
                    },
                    stopAutoplay() {
                        if (this.autoplayTimer) clearInterval(this.autoplayTimer);
                    },
                    nextSlide() {
                        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                    },
                    prevSlide() {
                        this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                    }
                 }"
                 x-init="startAutoplay()"
                 @mouseenter="stopAutoplay()"
                 @mouseleave="startAutoplay()">
                
                <div class="relative w-full overflow-hidden rounded-2xl shadow-sm border border-slate-200/80 bg-slate-900 group">
                    <div class="relative w-full h-44 sm:h-56 md:h-72 lg:h-80">
                        @foreach($activeAds as $index => $ad)
                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-500 transform"
                                 x-transition:enter-start="opacity-0 translate-x-12 scale-98"
                                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                                 x-transition:leave="transition ease-in duration-300 transform absolute inset-0"
                                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-x-12 scale-98"
                                 class="w-full h-full">
                                
                                @if($ad->url_tujuan)
                                    <a href="{{ route('iklan.click', $ad->id) }}" target="_blank" rel="noopener noreferrer" class="block w-full h-full relative cursor-pointer group/item">
                                @else
                                    <div class="block w-full h-full relative cursor-default select-none">
                                @endif
                                        <img src="{{ $ad->banner_url }}" 
                                             alt="{{ $ad->judul_iklan }}" 
                                             class="w-full h-full object-cover object-center {{ $ad->url_tujuan ? 'group-hover/item:scale-[1.02]' : '' }} transition-transform duration-700 ease-out">
                                        
                                        {{-- Badge Official / Perusahaan di pojok kiri atas --}}
                                        <div class="absolute top-3 left-3 sm:top-4 sm:left-4 flex items-center gap-2 z-10 pointer-events-none">
                                            @if($ad->perusahaan)
                                                <span class="inline-flex items-center gap-1.5 bg-black/60 backdrop-blur-md text-white/95 border border-white/20 text-[11px] sm:text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                                    @if($ad->perusahaan->img_profile)
                                                        <img src="{{ asset('storage/' . $ad->perusahaan->img_profile) }}" alt="" class="w-4 h-4 rounded-full object-cover">
                                                    @else
                                                        <i class="ph-fill ph-buildings text-sky-300"></i>
                                                    @endif
                                                    <span>{{ $ad->perusahaan->nama_perusahaan }}</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-[#00509d]/90 backdrop-blur-md text-white border border-white/25 text-[11px] sm:text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                                    <i class="ph-fill ph-seal-check text-sky-300 text-sm"></i>
                                                    <span>Official AreaKerja</span>
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Overlay Gradien & Judul Iklan di bagian bawah --}}
                                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent pt-12 pb-3.5 sm:pb-4 px-4 sm:px-6 pointer-events-none flex items-end justify-between gap-4 z-10">
                                            <div class="max-w-xl">
                                                <h3 class="text-white font-extrabold text-sm sm:text-base md:text-lg lg:text-xl drop-shadow-md line-clamp-1 tracking-tight">
                                                    {{ $ad->judul_iklan }}
                                                </h3>
                                                @if($ad->perusahaan)
                                                    <p class="text-white/80 text-[10px] sm:text-xs font-medium flex items-center gap-1 mt-0.5 drop-shadow-xs">
                                                        <i class="ph ph-buildings"></i> {{ $ad->perusahaan->nama_perusahaan }}
                                                    </p>
                                                @else
                                                    <p class="text-sky-200 text-[10px] sm:text-xs font-medium flex items-center gap-1 mt-0.5 drop-shadow-xs">
                                                        <i class="ph-fill ph-seal-check"></i> Official Partner AreaKerja
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Tombol Kunjungi jika ada tautan tujuan --}}
                                            @if($ad->url_tujuan)
                                                <div class="pointer-events-auto shrink-0">
                                                    <span class="inline-flex items-center gap-1.5 bg-white/95 hover:bg-white text-slate-900 font-bold text-xs sm:text-sm px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl shadow-lg transition backdrop-blur-xs group-hover/item:scale-105">
                                                        <span>Kunjungi</span>
                                                        <i class="ph ph-arrow-square-out text-sm"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                @if($ad->url_tujuan)
                                    </a>
                                @else
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Navigation Arrows (if > 1 slide) --}}
                    @if($activeAds->count() > 1)
                        <button @click="prevSlide()" 
                                aria-label="Previous Slide"
                                class="absolute left-2.5 sm:left-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/40 hover:bg-black/70 backdrop-blur-sm text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-105 cursor-pointer z-10">
                            <i class="ph ph-caret-left text-lg sm:text-xl"></i>
                        </button>
                        <button @click="nextSlide()" 
                                aria-label="Next Slide"
                                class="absolute right-2.5 sm:right-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/40 hover:bg-black/70 backdrop-blur-sm text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-105 cursor-pointer z-10">
                            <i class="ph ph-caret-right text-lg sm:text-xl"></i>
                        </button>

                        {{-- Pagination Dots --}}
                        <div class="absolute bottom-2.5 sm:bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10">
                            @foreach($activeAds as $index => $ad)
                                <button @click="activeSlide = {{ $index }}"
                                        aria-label="Go to slide {{ $index + 1 }}"
                                        :class="activeSlide === {{ $index }} ? 'w-5 sm:w-6 bg-white' : 'w-1.5 sm:w-2 bg-white/50 hover:bg-white/80'"
                                        class="h-1.5 sm:h-2 rounded-full transition-all duration-300 cursor-pointer"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Tabs Section -->
        <div x-data="{ tab: 'umpan' }" class="max-w-6xl mx-auto px-4 sm:px-6 pt-8 pb-16">

            {{-- Active Filter Bar --}}
            @if (!empty($adaPencarian) || request('jenis') || request('pengalaman') || request('pendidikan') || request('provinsi') || request('kota'))
                <div class="mb-6 p-3.5 bg-blue-50/80 border border-blue-200/80 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-2xs">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="ph ph-funnel text-[#00509d] text-sm"></i> Filter aktif:
                        </span>
                        @if(request('posisi', $posisi ?? null))
                            <span class="inline-flex items-center gap-1 bg-white text-[#00509d] border border-blue-200 text-xs font-semibold px-2.5 py-1 rounded-lg shadow-2xs">
                                Posisi: "{{ request('posisi', $posisi) }}"
                            </span>
                        @endif
                        @if(request('lokasi', $lokasi ?? null))
                            <span class="inline-flex items-center gap-1 bg-white text-[#00509d] border border-blue-200 text-xs font-semibold px-2.5 py-1 rounded-lg shadow-2xs">
                                Lokasi: "{{ request('lokasi', $lokasi) }}"
                            </span>
                        @endif
                        @if(request('provinsi', $provinsi ?? null))
                            @php
                                $provVal = request('provinsi', $provinsi);
                                $provLabel = is_numeric($provVal) ? (\App\Services\WilayahService::findProvince($provVal)?->nama ?? $provVal) : $provVal;
                            @endphp
                            <span class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-2xs">
                                📍 Provinsi: {{ $provLabel }}
                            </span>
                        @endif
                        @if(request('kota', $kota ?? null))
                            <span class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-2xs">
                                🏙️ Kota: {{ request('kota', $kota) }}
                            </span>
                        @endif
                        @if(request('jenis', $jenis ?? null))
                            <span class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-2xs">
                                Jenis: {{ request('jenis', $jenis) }}
                            </span>
                        @endif
                        @if(request('pengalaman', $pengalaman ?? null))
                            <span class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-2xs">
                                Pengalaman: {{ request('pengalaman', $pengalaman) }}
                            </span>
                        @endif
                        @if(request('pendidikan', $pendidikan ?? null))
                            <span class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-2xs">
                                Pendidikan: {{ request('pendidikan', $pendidikan) }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('lowongan.search') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                        <i class="ph ph-x-circle text-base"></i> Reset Semua Filter
                    </a>
                </div>
            @endif

            {{-- Tab Headers --}}
            <div class="border-b border-slate-200 mb-8">
                <div class="flex gap-8">
                    <!-- TAB LOWONGAN KERJA -->
                    <button @click="tab = 'umpan'"
                        :class="tab === 'umpan'
                            ? 'pb-3 border-b-4 border-[#00509d] text-[#00509d] font-bold text-base md:text-lg'
                            : 'pb-3 text-slate-400 hover:text-slate-600 font-bold text-base md:text-lg transition'">
                        Lowongan Kerja
                    </button>

                    <!-- TAB PALING DICARI -->
                    <button @click="tab = 'riwayat'"
                        :class="tab === 'riwayat'
                            ? 'pb-3 border-b-4 border-[#00509d] text-[#00509d] font-bold text-base md:text-lg'
                            : 'pb-3 text-slate-400 hover:text-slate-600 font-bold text-base md:text-lg transition'">
                        Paling Dicari
                    </button>
                </div>
            </div>

            <!-- TAB CONTENT: PALING DICARI (RIWAYAT / POPULER) -->
            <div x-show="tab === 'riwayat'" x-cloak x-transition>
                <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-600 flex items-center gap-2">
                        <i class="ph ph-clock-counter-clockwise text-[#00509d] text-lg"></i>
                        Riwayat pencarian Anda
                    </h3>

                    <form action="{{ route('pelamar.resetRiwayat') }}" method="POST">
                        @csrf
                        <button class="text-red-600 text-xs font-semibold hover:underline flex items-center gap-1">
                            <i class="ph ph-trash"></i> Hapus riwayat
                        </button>
                    </form>
                </div>

                <div>
                    @if (!empty($riwayat) && count($riwayat) > 0)
                        @foreach ($riwayat as $r)
                            <h4 class="font-bold text-base text-slate-800 mb-3 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 bg-[#00509d] rounded-full"></span>
                                {{ $r['posisi'] ?: 'Nama Lowongan' }} <span class="text-slate-400 font-normal">•</span> {{ $r['lokasi'] ?: 'Lokasi apapun' }}
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                @foreach ($r['lowongan_ids'] as $id)
                                    @php
                                        $d = \App\Models\LowonganPerusahaan::find($id);
                                    @endphp

                                    @if ($d)
                                        <div onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $d->perusahaan->slug, 'lowongan' => $d->slug]) }}'">
                                            @include('non-user.components.card', ['lowongan' => $d])
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <hr class="my-6 border-slate-200">
                        @endforeach
                    @else
                        <div class="text-center py-14 bg-white rounded-2xl border border-dashed border-slate-300 shadow-sm">
                            <i class="ph ph-clock-counter-clockwise text-5xl text-slate-300 mb-2 inline-block"></i>
                            <p class="text-slate-500 text-sm font-medium">Belum ada riwayat pencarian.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- TAB CONTENT: LOWONGAN KERJA -->
            <div x-show="tab === 'umpan'" x-transition>
                <section class="mb-10">
                    <div id="section-umpan-lowongan" class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                        @forelse ($Data as $d)
                            <div class="h-full" onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $d->perusahaan->slug ?? 'perusahaan', 'lowongan' => $d->slug ?? $d->id]) }}'">
                                @include('non-user.components.card', ['lowongan' => $d])
                            </div>
                        @empty
                            <div class="col-span-1 md:col-span-2 text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                                <i class="ph ph-briefcase text-5xl text-slate-300 mb-2 inline-block"></i>
                                <p class="text-slate-600 font-semibold">Tidak ada lowongan pekerjaan yang ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Pagination Lowongan (12 per halaman jika melebihi) -->
                @if ($Data instanceof \Illuminate\Contracts\Pagination\Paginator && $Data->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $Data->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- AlpineJS & Filters JS --}}
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        function resetKategoriUI() {
            document.querySelectorAll('.kategori-btn').forEach(el => {
                el.classList.remove('bg-[#00509d]', 'border-[#00509d]', 'text-white', 'shadow-md');
                el.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-700');
            });
        }

        function resetJenisUI() {
            document.querySelectorAll('.jenis-btn').forEach(el => {
                el.classList.remove('bg-[#00509d]', 'border-[#00509d]', 'text-white', 'shadow-md');
                el.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-700');
            });
        }

        function pilihKategori(btn) {
            const value = btn.dataset.kategori;
            const input = document.getElementById('kategoriInput');

            if (input.value === value) {
                input.value = '';
                resetKategoriUI();
                return;
            }

            resetKategoriUI();
            btn.classList.remove('bg-slate-50', 'border-slate-200', 'text-slate-700');
            btn.classList.add('bg-[#00509d]', 'border-[#00509d]', 'text-white', 'shadow-md');
            input.value = value;
        }

        function pilihJenis(btn) {
            const value = btn.dataset.jenis;
            const input = document.getElementById('jenisInput');

            if (input.value === value) {
                input.value = '';
                resetJenisUI();
                return;
            }

            resetJenisUI();
            btn.classList.remove('bg-slate-50', 'border-slate-200', 'text-slate-700');
            btn.classList.add('bg-[#00509d]', 'border-[#00509d]', 'text-white', 'shadow-md');
            input.value = value;
        }

        async function loadKotas(provinsiId, selectedKota = '') {
            const kotaSelect = document.getElementById('filterKota');
            if (!kotaSelect) return;

            if (!provinsiId) {
                kotaSelect.innerHTML = '<option value="">Semua Kota</option>';
                return;
            }

            try {
                const res = await fetch(`/get-kota/${encodeURIComponent(provinsiId)}`);
                if (!res.ok) return;
                const data = await res.json();

                let html = '<option value="">Semua Kota</option>';
                data.forEach(item => {
                    const isSelected = selectedKota && (
                        selectedKota.toLowerCase() === item.nama.toLowerCase() ||
                        selectedKota.toLowerCase() === item.id.toLowerCase()
                    );
                    html += `<option value="${item.nama}" ${isSelected ? 'selected' : ''}>${item.nama}</option>`;
                });
                kotaSelect.innerHTML = html;
            } catch (err) {
                console.error('Gagal memuat daftar kota:', err);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const kategori = "{{ request('kategori') }}";
            const jenis = "{{ request('jenis') }}";

            if (kategori) {
                document.querySelector(`[data-kategori="${kategori}"]`)?.click();
            }

            if (jenis) {
                document.querySelector(`[data-jenis="${jenis}"]`)?.click();
            }

            const filterProvinsi = document.getElementById('filterProvinsi');
            const filterKota = document.getElementById('filterKota');
            const selectedKota = "{{ request('kota', $kota ?? '') }}";

            if (filterProvinsi) {
                if (filterProvinsi.value) {
                    loadKotas(filterProvinsi.value, selectedKota);
                }

                filterProvinsi.addEventListener('change', function() {
                    if (filterKota) {
                        filterKota.value = '';
                        filterKota.innerHTML = '<option value="">Memuat kota...</option>';
                    }
                    loadKotas(this.value, '');
                });
            }
        });
    </script>

    @include('layouts.footer')
@endsection
