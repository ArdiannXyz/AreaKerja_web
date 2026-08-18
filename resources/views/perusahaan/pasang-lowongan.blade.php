@extends('layouts.index-perusahaan')
@section('content')
    <!-- Hero Section -->
    <div class="pt-24 px-4 md:px-0">
        <section class="relative overflow-hidden rounded-2xl max-w-6xl mx-auto min-h-[260px] md:min-h-[300px] flex items-center bg-gradient-to-r from-gray-900 via-gray-800 to-orange-900 shadow-xl">
            <!-- Background Image with Overlay -->
            <img src="{{ asset('images/tangan.png') }}"
                alt="Header Image" class="absolute inset-0 w-full h-full object-cover object-center opacity-30 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>

            <!-- Content -->
            <div class="relative z-10 px-8 md:px-14 py-8 text-white max-w-2xl">
                <div class="inline-flex items-center gap-2 bg-orange-500/20 border border-orange-500/40 text-orange-300 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                    <i class="ph ph-briefcase"></i> Paket Publikasi Lowongan
                </div>
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white mb-2">
                    Pasang Lowongan
                </h1>
                <p class="text-sm md:text-base text-gray-200 mb-6 font-normal leading-relaxed">
                    Dapatkan kandidat dan karyawan berkualitas terbaik untuk kemajuan perusahaan Anda.
                </p>
                <a href="{{ route('lowongan.create.form') }}"
                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-lg hover:shadow-orange-500/30 hover:scale-105 active:scale-95 transition-all duration-200">
                    <i class="ph ph-plus-circle text-lg"></i>
                    Tambah Lowongan
                </a>
            </div>
        </section>
    </div>
    <section class="py-16 space-y-6">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-center flex-wrap md:mt-16 ">
                @php
                    function formatDurasi($hari)
                    {
                        $bulan = intdiv($hari, 30);
                        $sisaHari = $hari % 30;

                        $minggu = intdiv($sisaHari, 7);
                        $hariAkhir = $sisaHari % 7;

                        $output = [];

                        if ($bulan > 0) {
                            $output[] = $bulan . ' Bulan';
                        }

                        if ($minggu > 0) {
                            $output[] = $minggu . ' Minggu';
                        }

                        if ($hariAkhir > 0) {
                            $output[] = $hariAkhir . ' Hari';
                        }

                        // Jika semua 0 (misal batas_listing kosong)
                        if (empty($output)) {
                            return '0 Hari';
                        }

                        return implode(' ', $output);
                    }

                    // Warna header tetap
                    $warnaHeader = [
                        'Gold' => 'bg-yellow-500',
                        'Silver' => 'bg-gray-500',
                        'Bronze' => 'bg-amber-700',
                    ];
                @endphp


                @foreach ($pakets as $paket)
                    <div class="w-72 mx-auto mt-6">
                        <div
                            class="bg-white border border-gray-300 rounded-xl shadow-sm hover:shadow-lg overflow-hidden flex flex-col transition-all duration-500 hover:scale-105">

                            <!-- Header Paket -->
                            <div class="py-3 text-center {{ $warnaHeader[$paket->nama] ?? 'bg-orange-500' }}">
                                <h3 class="text-xl font-bold text-white uppercase tracking-wide">
                                    {{ $paket->nama }}
                                </h3>
                            </div>

                            <!-- Isi Card -->
                            <div class="p-6 flex-1 flex flex-col">

                                <!-- Deskripsi -->
                                <h4 class="text-base font-semibold mb-1 text-center">Lebih Banyak Benefit</h4>
                                <p class="text-sm text-gray-700 mb-3 text-center">
                                    {{ $paket->deskripsi }}
                                </p>

                                <!-- Durasi Publish -->
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 mb-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                            <i class="ph ph-calendar-dots text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 font-medium leading-tight">Masa Publikasi</p>
                                            <p class="text-lg font-bold text-gray-900">
                                                {{ formatDurasi($paket->batas_listing) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>



                                <hr class="my-3 border-gray-300">

                                <ul class="text-sm text-gray-700 space-y-2 mb-6 flex-1">
                                    @foreach (explode("\n", $paket->benefit) as $item)
                                        <li class="flex items-start">
                                            <span class="mr-2">✔</span>
                                            {{ trim($item) }}
                                        </li>
                                    @endforeach
                                </ul>

                                <!-- Tombol -->
                                <button type="button"
                                    onclick="openModal({{ $paket->id }}, '{{ $paket->nama }}', {{ $paket->harga }})"
                                    class="{{ $warnaHeader[$paket->nama] ?? 'bg-orange-500' }} 
                           text-white font-semibold py-2 rounded-md hover:opacity-90 w-full transition">
                                    Pasang Lowongan
                                </button>

                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>


    <!-- Steps Section -->
    <!-- Steps Section -->
    <section class="py-12 bg-white">
        <div class="max-w-5xl mx-auto text-center">

            <!-- Judul -->
            <h2 class="text-2xl font-bold text-orange-600">Langkah - Langkah</h2>
            <div class="w-32 h-1 bg-orange-500 mx-auto mt-5 mb-7 rounded"></div>

            <!-- Steps Box -->
            <div class="grid md:grid-cols-4 grid-cols-1 text-left font-semibold overflow-hidden rounded-lg">

                <!-- Step 1 -->
                <div class="bg-orange-600 p-6 text-white">
                    <h3 class="text-xl font-bold">01</h3>
                    <p class="text-sm mt-2 font-normal text-white">
                        Pilih paket pemasangan lowongan sesuai yang anda inginkan
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-orange-400 p-6 text-white">
                    <h3 class="text-xl font-bold">02</h3>
                    <p class="text-sm mt-2 font-normal text-white">
                        Kirim materi lowongan via formulir website atau whatsapp kami
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-orange-500 p-6 text-white">
                    <h3 class="text-xl font-bold">03</h3>
                    <p class="text-sm mt-2 font-normal text-white">
                        Anda akan diberi instruksi pembayaran
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="bg-yellow-500 p-6 text-white">
                    <h3 class="text-xl font-bold">04</h3>
                    <p class="text-sm mt-2 font-normal text-white">
                        Lowongan anda siap di publish!
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- Why Choose Us -->
    <section class="max-w-6xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold text-orange-600 text-center mb-5">
            Kenapa Harus Area Kerja ?
        </h2>
        <div class="w-32 h-1 bg-orange-500 mx-auto mt-5 mb-7 rounded"></div>
        <div class="grid md:grid-cols-2 gap-8 items-center">

            <!-- Image -->
            <div class="flex justify-center">
                <img src="{{ asset('images/wongwong.png') }}" alt="Team" class="rounded-lg ">
            </div>

            <!-- Text -->
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <img src="{{ asset('images/2.png') }}" alt="www" class="w-20 h-20">
                    <p class="text-sm text-orange-600">Website kami menjangkau ratusan perusahaan yang siap menerima ribuan
                        pencari
                        kerja</p>
                </div>
                <div class="flex items-start gap-3">
                    <img src="{{ asset('images/3.png') }}" alt="obrol" class="w-20 h-20">
                    <p class="text-sm text-orange-600">Akun media sosial kami didedikasikan untuk membagikan info pekerjaan
                        setiap hari
                    </p>
                </div>
                <div class="flex items-start gap-3">
                    <img src="{{ asset('images/1.png') }}" alt="rp" class="w-20 h-20">
                    <p class="text-sm text-orange-600">Harga yang ramah bagi para pencari kerja dengan keuntungan peluang
                        kerja yang
                        besar</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Floating Button -->



    </div>
    </section>

    <!-- Floating Button -->
    <a href="#top"
        class="fixed bottom-6 right-6 bg-orange-500 text-white px-3 py-3 rounded-full shadow-lg hover:bg-orange-600 transition">
        <svg width="24" height="23" viewBox="0 0 31 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_231_4417)">
                <path
                    d="M26.6695 18.25L15.532 7.31684L4.3945 18.25L0.973172 14.8841L15.532 0.561196L30.0908 14.8841L26.6695 18.25Z"
                    fill="white" />
            </g>
            <defs>
                <clipPath id="clip0_231_4417">
                    <rect width="29.1176" height="26.9608" fill="white"
                        transform="translate(30.0586 27.2148) rotate(-180)" />
                </clipPath>
            </defs>
        </svg>

    </a>


    <!-- Modal -->
    <div id="paketModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 scale-[0.85] md:scale-100">

            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Konfirmasi Pembelian Paket</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 transition">
                    ✕
                </button>
            </div>

            <form action="{{ route('paket.beli') }}" method="POST">
                @csrf
                <input type="hidden" name="paket_id" id="modal_paket_id">

                <!-- Detail Paket -->
                <div class="space-y-2 bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                    <p class="text-sm">
                        Paket:
                        <span id="modal_paket_name" class="font-semibold text-gray-800"></span>
                    </p>
                    <p class="text-sm">
                        Harga:
                        <span id="modal_paket_price" class="font-semibold text-orange-600"></span> koin
                    </p>
                    <p class="text-sm">
                        Koin Anda:
                        <span class="font-semibold text-green-600">{{ $perusahaan->koin_perusahaan ?? 0 }}</span>
                    </p>
                </div>

                <!-- Dropdown Lowongan -->
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700">Pilih Lowongan</label>
                    <a href="{{ route('lowongan.create.form') }}"
                        class="text-xs text-orange-500 hover:text-orange-600 font-semibold flex items-center gap-1 hover:underline">
                        <i class="ph ph-plus-circle"></i> + Tambah Lowongan Baru
                    </a>
                </div>
                <select name="lowongan_id" id="modal_lowongan_select" required
                    class="w-full border-2 border-gray-300 rounded-xl px-3 py-2 mb-2 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition">
                    <option value=""> Pilih Lowongan </option>
                    @foreach ($perusahaan->pasanglowongan as $lowongan)
                        <option value="{{ $lowongan->id }}">{{ $lowongan->nama }}</option>
                    @endforeach
                    <option value="__create_new__" class="text-orange-600 font-semibold bg-orange-50">
                        ➕ [Buat Lowongan Baru]
                    </option>
                </select>
                <div class="mb-4">
                    <p class="text-xs text-gray-500">
                        Belum punya draft lowongan? 
                        <a href="{{ route('lowongan.create.form') }}" class="text-orange-600 font-semibold hover:underline">
                            Klik di sini untuk membuat lowongan baru
                        </a>
                    </p>
                </div>

                <!-- Button -->
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-xl font-medium transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-semibold shadow-md transition">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Animation -->
    <style>
        @keyframes scaleIn {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-scaleIn {
            animation: scaleIn 0.25s ease-out;
        }
    </style>


    {{-- MODAL TIDAK CUKUP KOIN --}}
    <div x-data="{ open: {{ session('koin_kurang') ? 'true' : 'false' }} }" x-show="open" x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div x-transition class="bg-white p-8 rounded-2xl shadow-lg w-[400px] text-center">

            <h2 class="text-xl font-semibold mb-4 italic">Upss!!</h2>

            <p class="mb-6 text-gray-700">
                Koin anda kurang silahkan Top Up terlebih dahulu.
            </p>

            <a href="{{ route('perusahaan.dashboard') }}"
                class="px-6 py-2 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition">
                Top Up
            </a>
        </div>
    </div>




    <script>
        function openModal(paketId, paketName, paketPrice) {
            document.getElementById('modal_paket_id').value = paketId;
            document.getElementById('modal_paket_name').textContent = paketName;
            document.getElementById('modal_paket_price').textContent = paketPrice.toLocaleString();
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
