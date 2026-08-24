@extends('layouts.index')
@section('content')

    <div class="bg-slate-100 min-h-screen text-slate-800">

        {{-- Hero Header Section with Ultra-Modern Deep Gradient --}}
        <section class="bg-gradient-to-br from-slate-950 via-slate-900 to-orange-950 text-white pt-28 pb-24 px-4 sm:px-6 relative overflow-hidden shadow-xl">
            {{-- Decorative Blur Glows --}}
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-orange-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-6xl mx-auto relative z-10">

                {{-- Title & Subtitle --}}
                <div class="text-center mb-8">
                    <span class="inline-flex items-center gap-2 bg-orange-500/10 text-orange-400 border border-orange-500/20 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm">
                        <i class="ph ph-sparkle text-sm"></i> Platform Karir & Rekrutmen Terpercaya #1
                    </span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight text-white leading-tight">
                        Temukan Karir Impian Anda di <span class="bg-gradient-to-r from-orange-400 via-amber-300 to-orange-500 bg-clip-text text-transparent">areakerja.com</span>
                    </h1>
                    <p class="text-slate-300 text-sm md:text-lg mt-3 font-normal max-w-2xl mx-auto leading-relaxed">
                        Ribuan lowongan kerja terbaru dari perusahaan terverifikasi di Indonesia siap Anda lamar hari ini
                    </p>
                </div>

                {{-- Floating Search Bar Glassmorphism Container --}}
                <div class="w-full flex justify-center mb-8">
                    <div class="w-full max-w-4xl">
                        <form action="{{ route('lowongan.search') }}" method="GET">
                            <div class="bg-white/95 backdrop-blur-md p-3 md:p-4 rounded-2xl shadow-2xl border border-white/40 flex flex-col md:flex-row items-center gap-3">

                                {{-- Posisi / Kata Kunci --}}
                                <div class="flex items-center gap-3 px-3 py-2 w-full md:w-1/2">
                                    <i class="ph ph-magnifying-glass text-2xl text-orange-500"></i>
                                    <input type="text" name="posisi" value="{{ request('posisi') }}"
                                        placeholder="Posisi lowongan, kata kunci, keahlian..."
                                        class="w-full border-none focus:ring-0 text-sm md:text-base text-slate-800 placeholder-slate-400 bg-transparent font-medium">
                                </div>

                                {{-- Separator --}}
                                <div class="hidden md:block h-8 w-px bg-slate-200"></div>

                                {{-- Lokasi --}}
                                <div class="flex items-center gap-3 px-3 py-2 w-full md:w-1/2">
                                    <i class="ph ph-map-pin text-2xl text-orange-500"></i>
                                    <input type="text" name="lokasi" value="{{ request('lokasi') }}"
                                        placeholder="Kota, provinsi, kode pos..."
                                        class="w-full border-none focus:ring-0 text-sm md:text-base text-slate-800 placeholder-slate-400 bg-transparent font-medium">
                                </div>

                                <input type="hidden" name="kategori" id="kategoriInput">
                                <input type="hidden" name="jenis" id="jenisInput">

                                {{-- Button Search --}}
                                <button type="submit"
                                    style="background-color: #f97316;"
                                    class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-sm md:text-base px-8 py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 whitespace-nowrap flex items-center justify-center gap-2 cursor-pointer border border-orange-600">
                                    <i class="ph ph-magnifying-glass font-bold text-lg text-white"></i>
                                    <span class="text-white font-extrabold">Cari Lowongan</span>
                                </button>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- Popular Keywords Tagline --}}
                <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-slate-300">
                    <span class="text-slate-400 font-medium">Trending:</span>
                    <a href="{{ route('lowongan.search', ['posisi' => 'Programmer']) }}" class="bg-white/10 hover:bg-orange-500/30 text-slate-200 px-3 py-1 rounded-full transition">Programmer</a>
                    <a href="{{ route('lowongan.search', ['posisi' => 'Graphic Designer']) }}" class="bg-white/10 hover:bg-orange-500/30 text-slate-200 px-3 py-1 rounded-full transition">Graphic Designer</a>
                    <a href="{{ route('lowongan.search', ['posisi' => 'Admin']) }}" class="bg-white/10 hover:bg-orange-500/30 text-slate-200 px-3 py-1 rounded-full transition">Admin</a>
                    <a href="{{ route('lowongan.search', ['posisi' => 'Marketing']) }}" class="bg-white/10 hover:bg-orange-500/30 text-slate-200 px-3 py-1 rounded-full transition">Marketing</a>
                    <a href="{{ route('lowongan.search', ['posisi' => 'Customer Service']) }}" class="bg-white/10 hover:bg-orange-500/30 text-slate-200 px-3 py-1 rounded-full transition">Customer Service</a>
                </div>

                {{-- Stat Counters Strip --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12 pt-8 border-t border-white/10 text-center">
                    <div class="p-3">
                        <p class="text-2xl md:text-3xl font-black text-amber-300">2.500+</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Lowongan Aktif</p>
                    </div>
                    <div class="p-3">
                        <p class="text-2xl md:text-3xl font-black text-amber-300">1.200+</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Perusahaan Terverifikasi</p>
                    </div>
                    <div class="p-3">
                        <p class="text-2xl md:text-3xl font-black text-amber-300">15.000+</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Pelamar Sukses</p>
                    </div>
                    <div class="p-3">
                        <p class="text-2xl md:text-3xl font-black text-amber-300">1-Klik</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Proses Lamaran Cepat</p>
                    </div>
                </div>

            </div>
        </section>

        <!-- Kategori Pekerjaan Populer (Floating Card Section) -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl border border-slate-200/80 -mt-10 relative z-20">
                <h2 class="text-sm md:text-base font-bold text-slate-800 uppercase tracking-wider mb-6 flex items-center justify-between border-b border-slate-100 pb-3">
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-orange-500 rounded-full inline-block"></span>
                        Kategori Pekerjaan Populer
                    </span>
                    <span class="text-xs font-normal text-slate-400">Pilih kategori untuk memfilter</span>
                </h2>

                <div class="flex flex-col lg:flex-row gap-6 items-start">

                    <!-- Kategori Badges Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 flex-1" id="kategori-wrapper">
                        @foreach ($KategoriList as $namaKategori)
                            <button type="button" data-kategori="{{ $namaKategori }}" onclick="pilihKategori(this)"
                                class="kategori-btn px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:border-orange-500 hover:bg-orange-500 hover:text-white hover:shadow-md transition-all duration-200 text-center flex items-center justify-center gap-2">
                                <i class="ph ph-briefcase text-base"></i>
                                {{ $namaKategori }}
                            </button>
                        @endforeach
                    </div>

                    <div class="hidden lg:block w-px bg-slate-200 self-stretch my-1"></div>

                    <!-- Jenis Pekerjaan Side Badges -->
                    <div class="flex flex-wrap lg:flex-col gap-3 w-full lg:w-56" id="jenis-wrapper">
                        @foreach ($jenisList as $jenis)
                            <button type="button" data-jenis="{{ $jenis }}" onclick="pilihJenis(this)"
                                class="jenis-btn px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:border-orange-500 hover:bg-orange-500 hover:text-white hover:shadow-md transition-all duration-200 text-center flex-1 lg:flex-none flex items-center justify-center gap-2">
                                <i class="ph ph-clock text-base"></i>
                                {{ $jenis }}
                            </button>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>

        <!-- Keunggulan AreaKerja (Why Choose Us Section) -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl shrink-0">
                        <i class="ph ph-paper-plane-tilt font-bold"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base mb-1">Lamaran Cepat 1-Klik</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Kirim CV Anda secara langsung ke perusahaan tanpa proses pengisian ulang yang rumit.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shrink-0">
                        <i class="ph ph-shield-check font-bold"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base mb-1">Lowongan Terverifikasi</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Semua perusahaan dan lowongan kerja telah melalui prosedur verifikasi ketat.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shrink-0">
                        <i class="ph ph-bell-simple font-bold"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base mb-1">Notifikasi Real-time</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Dapatkan update status lamaran dan panggilah wawancara langsung ke akun Anda.</p>
                    </div>
                </div>

            </div>
        </section>

        <!-- Tabs Umpan & Riwayat Lowongan -->
        <div x-data="{ tab: 'umpan' }" class="max-w-6xl mx-auto px-4 sm:px-6 pb-16">

            {{-- Tab Headers --}}
            <div class="border-b border-slate-200 mb-8">
                <div class="flex gap-4">
                    <!-- TAB UMPAN -->
                    <button @click="tab = 'umpan'"
                        :class="tab === 'umpan'
                            ? 'pb-3 px-6 border-b-4 border-orange-500 text-orange-600 font-bold text-sm md:text-base bg-white rounded-t-xl shadow-sm'
                            : 'pb-3 px-6 text-slate-600 hover:text-slate-900 font-semibold text-sm md:text-base transition'">
                        UMPAN LOWONGAN
                    </button>

                    <!-- TAB RIWAYAT -->
                    <button @click="tab = 'riwayat'"
                        :class="tab === 'riwayat'
                            ? 'pb-3 px-6 border-b-4 border-orange-500 text-orange-600 font-bold text-sm md:text-base bg-white rounded-t-xl shadow-sm'
                            : 'pb-3 px-6 text-slate-600 hover:text-slate-900 font-semibold text-sm md:text-base transition'">
                        PENCARIAN BARU-BARU INI
                    </button>
                </div>
            </div>

            <!-- TAB CONTENT: PENCARIAN TERAKHIR -->
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

            <!-- TAB CONTENT: UMPAN LOWONGAN -->
            <div x-show="tab === 'umpan'" x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="ph ph-sparkle text-orange-500 text-lg"></i>
                        Lowongan Berdasarkan Aktivitas Anda di AreaKerja
                    </h3>
                    <span class="text-xs text-slate-400 font-medium">Menampilkan {{ count($Data) }} lowongan terbaru</span>
                </div>

                <section class="mb-12">
                    <div id="section-umpan-lowongan" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($Data as $d)
                            <div onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $d->perusahaan->slug, 'lowongan' => $d->slug]) }}'">
                                @include('non-user.components.card', ['lowongan' => $d])
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

        </div>

        <!-- Employer Banner CTA Section ("Ingin Pasang Lowongan Pekerjaan?") -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-20">
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-orange-950 text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-700/50 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-3 max-w-xl">
                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        Khusus Perusahaan / Employer
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight">
                        Ingin Rekrut Karyawan Terbaik untuk Perusahaan Anda?
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Pasang lowongan kerja Anda di AreaKerja dan jangkau puluhan ribu talenta berbakat di seluruh Indonesia secara cepat dan efisien.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold px-8 py-4 rounded-xl shadow-xl shadow-orange-500/20 hover:scale-105 transition-all text-sm sm:text-base whitespace-nowrap">
                        <i class="ph ph-plus-circle text-lg"></i>
                        Pasang Lowongan Sekarang
                    </a>
                </div>
            </div>
        </section>

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
