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

                {{-- Search Bar Container --}}
                <div class="w-full flex justify-center mb-4">
                    <div class="w-full max-w-4xl">
                        <form action="{{ route('lowongan.search') }}" method="GET">
                            <div class="bg-white p-1.5 md:p-2 rounded-2xl shadow-xl flex flex-col md:flex-row items-center gap-1.5 md:gap-2">

                                {{-- Posisi / Kata Kunci --}}
                                <div class="flex items-center gap-2.5 px-3 py-1.5 w-full md:w-1/2">
                                    <i class="ph ph-magnifying-glass text-lg text-slate-400"></i>
                                    <input type="text" name="posisi" value="{{ request('posisi') }}"
                                        placeholder="Posisi lowongan, kata kunci, ..."
                                        class="w-full border-none focus:ring-0 text-xs md:text-sm text-slate-800 placeholder-slate-400 bg-transparent font-medium p-0">
                                </div>

                                {{-- Separator --}}
                                <div class="hidden md:block h-6 w-px bg-slate-200"></div>

                                {{-- Lokasi --}}
                                <div class="flex items-center gap-2.5 px-3 py-1.5 w-full md:w-1/2">
                                    <i class="ph ph-map-pin text-lg text-slate-400"></i>
                                    <input type="text" name="lokasi" value="{{ request('lokasi') }}"
                                        placeholder="Kota, provinsi, kode pos, eta..."
                                        class="w-full border-none focus:ring-0 text-xs md:text-sm text-slate-800 placeholder-slate-400 bg-transparent font-medium p-0">
                                </div>

                                <input type="hidden" name="kategori" id="kategoriInput">
                                <input type="hidden" name="jenis" id="jenisInput">

                                {{-- Button Search --}}
                                <button type="submit"
                                    class="w-full md:w-auto bg-[#004e98] hover:bg-[#003d7a] text-white font-bold text-xs md:text-sm px-6 py-2.5 md:py-3 rounded-xl shadow-sm transition-all duration-200 whitespace-nowrap flex items-center justify-center gap-2 cursor-pointer">
                                    <span>Cari Lowongan Kerja</span>
                                </button>

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

        <!-- Tabs Section -->
        <div x-data="{ tab: 'umpan' }" class="max-w-6xl mx-auto px-4 sm:px-6 pt-8 pb-16">

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
                        @php
                            $isSearchPage = request()->routeIs('lowongan.search') || !empty($adaPencarian);
                            $displayData = $isSearchPage ? $Data : $Data->take(6);
                        @endphp

                        @forelse ($displayData as $d)
                            @if ($d->published_at && (!$d->expired_at || $d->expired_at > now()) && $d->perusahaan)
                                <div class="h-full" onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $d->perusahaan->slug ?? 'perusahaan', 'lowongan' => $d->slug ?? $d->id]) }}'">
                                    @include('non-user.components.card', ['lowongan' => $d])
                                </div>
                            @endif
                        @empty
                            <div class="col-span-1 md:col-span-2 text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                                <i class="ph ph-briefcase text-5xl text-slate-300 mb-2 inline-block"></i>
                                <p class="text-slate-600 font-semibold">Tidak ada lowongan pekerjaan yang ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                @if (!$isSearchPage)
                    <!-- Tombol Muat Lebih Banyak di Beranda (Hanya muncul jika total lowongan > 6) -->
                    @if (count($Data) > 6)
                        <div class="flex justify-center mt-8">
                            <a href="{{ route('lowongan.search') }}"
                                class="bg-[#004e98] hover:bg-[#003d7a] text-white font-bold px-8 py-3 rounded-lg shadow-sm hover:shadow transition-all duration-200 text-xs sm:text-sm inline-flex items-center justify-center gap-2">
                                <span>Muat lebih banyak....</span>
                            </a>
                        </div>
                    @endif
                @else
                    <!-- Pagination di Halaman Search / Semua Lowongan -->
                    @if ($Data instanceof \Illuminate\Contracts\Pagination\Paginator && $Data->hasPages())
                        <div class="mt-8 flex justify-center">
                            {{ $Data->appends(request()->query())->links() }}
                        </div>
                    @endif
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

        document.addEventListener('DOMContentLoaded', function() {
            const kategori = "{{ request('kategori') }}";
            const jenis = "{{ request('jenis') }}";

            if (kategori) {
                document.querySelector(`[data-kategori="${kategori}"]`)?.click();
            }

            if (jenis) {
                document.querySelector(`[data-jenis="${jenis}"]`)?.click();
            }
        });
    </script>

    @include('layouts.footer')
@endsection
