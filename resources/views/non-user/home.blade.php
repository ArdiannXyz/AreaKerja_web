@extends('layouts.index')
@section('content')

    <div class="bg-slate-100 min-h-screen text-slate-800">

        {{-- Hero Header Section --}}
        <section class="bg-[#ff7a00] text-white pt-28 pb-16 px-4 sm:px-6 relative overflow-hidden">
            <div class="max-w-5xl mx-auto relative z-10 text-center">

                {{-- Title & Subtitle --}}
                <div class="mb-8">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white leading-tight drop-shadow-sm">
                        Temukan Karir Impian Anda di areakerja.com
                    </h1>
                    <p class="text-white/90 text-sm md:text-base mt-3 font-medium max-w-2xl mx-auto leading-relaxed">
                        Ribuan lowongan kerja terbaru dari perusahaan terverifikasi di indonesia siap anda lamar hari ini
                    </p>
                </div>

                {{-- Search Bar Container --}}
                <div class="w-full flex justify-center mb-6">
                    <div class="w-full max-w-4xl">
                        <form action="{{ route('lowongan.search') }}" method="GET">
                            <div class="bg-white p-2 md:p-2.5 rounded-2xl shadow-2xl flex flex-col md:flex-row items-center gap-2">

                                {{-- Posisi / Kata Kunci --}}
                                <div class="flex items-center gap-3 px-4 py-2 w-full md:w-1/2">
                                    <i class="ph ph-magnifying-glass text-xl text-slate-400"></i>
                                    <input type="text" name="posisi" value="{{ request('posisi') }}"
                                        placeholder="Posisi lowongan, kata kunci, ..."
                                        class="w-full border-none focus:ring-0 text-xs md:text-sm text-slate-800 placeholder-slate-400 bg-transparent font-medium">
                                </div>

                                {{-- Separator --}}
                                <div class="hidden md:block h-6 w-px bg-slate-200"></div>

                                {{-- Lokasi --}}
                                <div class="flex items-center gap-3 px-4 py-2 w-full md:w-1/2">
                                    <i class="ph ph-map-pin text-xl text-slate-400"></i>
                                    <input type="text" name="lokasi" value="{{ request('lokasi') }}"
                                        placeholder="Kota, provinsi kode pos, eta..."
                                        class="w-full border-none focus:ring-0 text-xs md:text-sm text-slate-800 placeholder-slate-400 bg-transparent font-medium">
                                </div>

                                <input type="hidden" name="kategori" id="kategoriInput">
                                <input type="hidden" name="jenis" id="jenisInput">

                                {{-- Button Search --}}
                                <button type="submit"
                                    class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs md:text-sm px-6 py-3 rounded-xl shadow-md transition-all duration-200 whitespace-nowrap flex items-center justify-center gap-2 cursor-pointer border border-orange-600">
                                    <span>Cari Lowongan Kerja</span>
                                </button>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tagline under search bar --}}
                <p class="text-xs text-white/90 font-medium mb-6">
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
                            ? 'pb-3 border-b-4 border-orange-500 text-orange-600 font-extrabold text-base md:text-lg'
                            : 'pb-3 text-slate-400 hover:text-slate-600 font-bold text-base md:text-lg transition'">
                        Lowongan Kerja
                    </button>

                    <!-- TAB PALING DICARI -->
                    <button @click="tab = 'riwayat'"
                        :class="tab === 'riwayat'
                            ? 'pb-3 border-b-4 border-orange-500 text-orange-600 font-extrabold text-base md:text-lg'
                            : 'pb-3 text-slate-400 hover:text-slate-600 font-bold text-base md:text-lg transition'">
                        Paling Dicari
                    </button>
                </div>
            </div>

            <!-- TAB CONTENT: PALING DICARI (RIWAYAT / POPULER) -->
            <div x-show="tab === 'riwayat'" x-cloak x-transition>
                <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-600 flex items-center gap-2">
                        <i class="ph ph-clock-counter-clockwise text-orange-500 text-lg"></i>
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
                                <span class="w-2.5 h-2.5 bg-orange-500 rounded-full"></span>
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
                        @foreach ($Data as $d)
                            <div class="h-full" onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $d->perusahaan->slug, 'lowongan' => $d->slug]) }}'">
                                @include('non-user.components.card', ['lowongan' => $d])
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Tombol Muat Lebih Banyak (Hanya muncul jika lowongan > 6) -->
                @if (count($Data) > 6)
                    <div class="flex justify-center mt-8">
                        <button type="button"
                            class="bg-orange-500 hover:bg-orange-600 text-white font-extrabold px-8 py-3 rounded-xl shadow-md transition text-xs sm:text-sm">
                            Muat lebih banyak...
                        </button>
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
                el.classList.remove('bg-orange-500', 'border-orange-500', 'text-white', 'shadow-md');
                el.classList.add('bg-slate-50', 'border-slate-200', 'text-slate-700');
            });
        }

        function resetJenisUI() {
            document.querySelectorAll('.jenis-btn').forEach(el => {
                el.classList.remove('bg-orange-500', 'border-orange-500', 'text-white', 'shadow-md');
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
            btn.classList.add('bg-orange-500', 'border-orange-500', 'text-white', 'shadow-md');
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
            btn.classList.add('bg-orange-500', 'border-orange-500', 'text-white', 'shadow-md');
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
