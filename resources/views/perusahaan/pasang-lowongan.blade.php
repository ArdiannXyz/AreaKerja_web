@extends('layouts.index-perusahaan')
@section('content')
    <!-- Hero Section -->
    <section class="relative mt-20 w-full overflow-hidden min-h-[340px] md:min-h-[400px] flex items-center bg-gray-900 shadow-md">
        <!-- Background Image with Dark Overlay -->
        <img src="{{ asset('images/tangan.png') }}" alt="Pasang Lowongan"
            class="absolute inset-0 w-full h-full object-cover object-center opacity-70">
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/60 to-black/30"></div>

        <!-- Hero Content -->
        <div class="relative z-10 px-6 sm:px-12 md:px-20 lg:px-28 max-w-4xl text-white py-12">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight mb-3">
                Pasang Lowongan
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-gray-200 mb-6 font-normal max-w-2xl leading-relaxed">
                Dapatkan karyawan berkualitas untuk perusahaan anda
            </p>
            <a href="{{ route('lowongan.create.form') }}"
                class="inline-flex items-center justify-center bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-sm sm:text-base px-8 py-2.5 rounded-xl shadow-md transition-all duration-200 hover:scale-105 active:scale-95">
                Tambah
            </a>
        </div>
    </section>

    <!-- Pricing / Paket Lowongan Cards Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            @php
                $warnaHeader = [
                    'Gold'   => 'bg-[#F59E0B]',
                    'Silver' => 'bg-[#8A929A]',
                    'Bronze' => 'bg-[#5F554B]',
                ];
                $warnaBtn = [
                    'Gold'   => 'bg-[#F59E0B] hover:bg-amber-600',
                    'Silver' => 'bg-[#8A929A] hover:bg-slate-600',
                    'Bronze' => 'bg-[#5F554B] hover:bg-stone-800',
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch justify-center max-w-5xl mx-auto">
                @foreach ($pakets as $paket)
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-xl overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-1">
                        
                        <!-- Header Paket -->
                        <div class="py-3.5 text-center {{ $warnaHeader[$paket->nama] ?? 'bg-[#00509d]' }}">
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider">
                                {{ $paket->nama }}
                            </h3>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="text-base font-bold text-gray-900 mb-1 text-center">
                                    Lebih Banyak Benefit
                                </h4>
                                <p class="text-xs text-gray-500 mb-4 text-center font-medium leading-relaxed">
                                    {{ $paket->deskripsi }}
                                </p>

                                <hr class="my-4 border-gray-200">

                                <ul class="text-xs text-gray-700 space-y-3 mb-6">
                                    @if (!empty($paket->benefit))
                                        @foreach (explode("\n", $paket->benefit) as $item)
                                            @if(trim($item))
                                                <li class="flex items-center gap-2.5">
                                                    <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                                    <span class="font-medium text-slate-800">{{ trim($item) }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    @else
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">Website & Aplikasi</span>
                                        </li>
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">Instagram Post & Story</span>
                                        </li>
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">Highlight Story Favorit</span>
                                        </li>
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">Google Jobs & Bisnis</span>
                                        </li>
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">Facebook Post & Story</span>
                                        </li>
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">Twitter</span>
                                        </li>
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">LinkedIn</span>
                                        </li>
                                        <li class="flex items-center gap-2.5">
                                            <i class="ph ph-check text-slate-800 text-sm font-bold shrink-0"></i>
                                            <span class="font-medium text-slate-800">Telegram</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <!-- Button Pasang Lowongan -->
                            <button type="button"
                                onclick="openModal({{ $paket->id }}, '{{ $paket->nama }}', {{ $paket->harga }})"
                                class="{{ $warnaBtn[$paket->nama] ?? 'bg-[#00509d] hover:bg-[#003d7a]' }} text-white font-semibold py-2.5 rounded-lg w-full transition duration-200 shadow-sm text-sm">
                                Pasang Lowongan
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Steps Section (Langkah - Langkah) -->
    <section class="py-14 bg-white">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#00509d]">Langkah - Langkah</h2>
            <div class="w-32 h-1 bg-[#00509d] mx-auto mt-2 mb-9 rounded-full"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 text-left overflow-hidden rounded-xl shadow-sm">
                <!-- Step 1 -->
                <div class="bg-[#E65100] p-6 text-white flex flex-col justify-start min-h-[140px]">
                    <h3 class="text-2xl font-black mb-2">01</h3>
                    <p class="text-xs sm:text-sm font-normal leading-relaxed text-white/95">
                        Pilih paket pemasangan lowongan sesuai yang anda inginkan
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-[#F57C00] p-6 text-white flex flex-col justify-start min-h-[140px]">
                    <h3 class="text-2xl font-black mb-2">02</h3>
                    <p class="text-xs sm:text-sm font-normal leading-relaxed text-white/95">
                        Kirim materi lowongan via formulir website atau whatsapp kami
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-[#FB8C00] p-6 text-white flex flex-col justify-start min-h-[140px]">
                    <h3 class="text-2xl font-black mb-2">03</h3>
                    <p class="text-xs sm:text-sm font-normal leading-relaxed text-white/95">
                        Anda akan diberi instruksi pembayaran
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="bg-[#FFA726] p-6 text-white flex flex-col justify-start min-h-[140px]">
                    <h3 class="text-2xl font-black mb-2">04</h3>
                    <p class="text-xs sm:text-sm font-normal leading-relaxed text-white/95">
                        Lowongan anda siap di publish!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us (Kenapa Harus Area Kerja ?) -->
    <section class="py-14 bg-white max-w-6xl mx-auto px-4 sm:px-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-[#00509d] text-center mb-2">
            Kenapa Harus Area Kerja ?
        </h2>
        <div class="w-32 h-1 bg-[#00509d] mx-auto mb-10 rounded-full"></div>

        <div class="grid md:grid-cols-2 gap-10 items-center max-w-5xl mx-auto">
            <!-- Left Image -->
            <div class="flex justify-center">
                <img src="{{ asset('images/wongwong.png') }}" alt="Area Kerja" class="max-w-full md:max-w-sm h-auto object-contain">
            </div>

            <!-- Right Benefit Points -->
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/2.png') }}" alt="Website Icon" class="w-16 h-16 shrink-0 object-contain">
                    <p class="text-xs sm:text-sm font-medium text-[#00509d] leading-relaxed">
                        Website kami menjangkau ratusan perusahaan yang siap menerima ribuan pencari kerja.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/3.png') }}" alt="Social Media Icon" class="w-16 h-16 shrink-0 object-contain">
                    <p class="text-xs sm:text-sm font-medium text-[#00509d] leading-relaxed">
                        Akun media social kami diikuti ratusan ribu pencari kerja serta memiliki jaringan social media yang lengkap
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/1.png') }}" alt="Harga Icon" class="w-16 h-16 shrink-0 object-contain">
                    <p class="text-xs sm:text-sm font-medium text-[#00509d] leading-relaxed">
                        Harga yang ramah bagi para pencari kerja tetapi dengan keuntungan peluang yang besar
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Back to Top Button -->
    <a href="#top"
        class="fixed bottom-6 right-6 bg-[#00509d] text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-[#003d7a] transition hover:scale-110 active:scale-95 z-30">
        <i class="ph ph-caret-up text-2xl font-bold"></i>
    </a>

    <!-- Modal Pembelian / Konfirmasi Paket -->
    <div id="paketModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <h2 class="text-lg font-bold text-gray-800">Konfirmasi Pembelian Paket</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition text-lg font-bold">
                    âœ•
                </button>
            </div>

            <form action="{{ route('paket.beli') }}" method="POST">
                @csrf
                <input type="hidden" name="paket_id" id="modal_paket_id">

                <!-- Detail Paket Info -->
                <div class="space-y-2 bg-blue-50/50 p-4 rounded-xl border border-blue-100 mb-4">
                    <p class="text-xs text-gray-600 flex justify-between">
                        <span>Paket Dipilih:</span>
                        <span id="modal_paket_name" class="font-bold text-gray-900"></span>
                    </p>
                    <p class="text-xs text-gray-600 flex justify-between">
                        <span>Biaya:</span>
                        <span class="font-bold text-[#00509d]"><span id="modal_paket_price"></span> Koin</span>
                    </p>
                    <p class="text-xs text-gray-600 flex justify-between">
                        <span>Saldo Koin Anda:</span>
                        <span class="font-bold text-emerald-600">{{ $perusahaan->koin_perusahaan ?? 0 }} Koin</span>
                    </p>
                </div>

                <!-- Dropdown Lowongan -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-gray-700">Pilih Lowongan yang Dipasang</label>
                        <a href="{{ route('lowongan.create.form') }}"
                            class="text-xs text-[#00509d] hover:underline font-semibold">
                            + Buat Baru
                        </a>
                    </div>
                    <select name="lowongan_id" id="modal_lowongan_select" required
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] transition">
                        <option value="">-- Pilih Draft Lowongan --</option>
                        @if (!empty($perusahaan->pasanglowongan))
                            @foreach ($perusahaan->pasanglowongan as $lowongan)
                                <option value="{{ $lowongan->id }}">{{ $lowongan->nama }}</option>
                            @endforeach
                        @endif
                        <option value="__create_new__" class="text-[#003d7a] font-semibold bg-blue-50">
                            âž• [Buat Lowongan Baru]
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white rounded-xl text-xs font-bold shadow-xs transition">
                        Konfirmasi Pembelian
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TIDAK CUKUP KOIN --}}
    <div x-data="{ open: {{ session('koin_kurang') ? 'true' : 'false' }} }" x-show="open" x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
        <div x-transition class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl max-w-sm w-full text-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-[#00509d] flex items-center justify-center mx-auto mb-3">
                <i class="ph ph-warning-circle text-2xl font-bold"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mb-2">Koin Tidak Mencukupi</h2>
            <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                Jumlah koin Anda saat ini tidak cukup untuk memasang paket ini. Silakan lakukan Top Up terlebih dahulu.
            </p>
            <div class="flex gap-3 justify-center">
                <button @click="open = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">
                    Tutup
                </button>
                <a href="{{ route('perusahaan.dashboard') }}"
                    class="px-5 py-2 bg-[#00509d] text-white rounded-xl text-xs font-bold hover:bg-[#003d7a] transition shadow-xs">
                    Top Up Sekarang
                </a>
            </div>
        </div>
    </div>

    <script>
        function openModal(paketId, paketName, paketPrice) {
            document.getElementById('modal_paket_id').value = paketId;
            document.getElementById('modal_paket_name').textContent = paketName;
            document.getElementById('modal_paket_price').textContent = Number(paketPrice).toLocaleString();
            document.getElementById('paketModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('paketModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('modal_lowongan_select');
            if (select) {
                select.addEventListener('change', function() {
                    if (this.value === '__create_new__') {
                        window.location.href = "{{ route('lowongan.create.form') }}";
                    }
                });
            }
        });
    </script>
    @include('layouts.footer')
@endsection



