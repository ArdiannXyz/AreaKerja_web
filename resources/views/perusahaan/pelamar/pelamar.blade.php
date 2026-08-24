@extends('layouts.index-perusahaan')
@section('content')
    <section class="relative">
        @php
            $header = null;
        @endphp

        <section class="relative w-full">

            <!-- Gambar Header -->
            <img src="{{ $header && $header->link ? asset('storage/' . $header->link) : asset('images/tangan.png') }}"
                alt="Header Image" class="w-full h-52 md:h-96 object-cover">

            <!-- Overlay Hitam -->
            <div class="absolute inset-0 bg-black bg-opacity-10"></div>

            <!-- Text Overlay -->
            <div class="absolute bottom-6 md:bottom-20 left-4 md:left-20 text-white break-words pr-4">

                <h1 class="text-2xl md:text-5xl font-semibold mt-3 max-w-md md:max-w-2xl mb-2">
                    Pelamar
                </h1>

                <h2 class="text-sm md:text-xl">Lihat riwayat lamar yang masuk</h2>
                <h2 class="text-sm md:text-xl">Ke lowongan anda</h2>
            </div>

        </section>


        <div class="p-4 md:p-6 bg-white min-h-screen flex justify-center mt-24">
            <div class="w-full max-w-5xl">

                <!-- Header Info Lowongan -->
                <div
                    class="bg-white rounded-lg shadow-md p-4 md:p-5 flex flex-col md:flex-row items-start md:items-center justify-between mb-6 border gap-4">

                    <!-- Kiri -->
                    <div class="flex items-center gap-4 w-full break-words">
                        <img src="{{ asset('storage/' . $data->perusahaan->img_profile) }}" alt="logo"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover">

                        <div class="w-full">
                            <h5 class="text-gray-500 text-sm">{{ $data->perusahaan->nama_perusahaan }}</h5>
                            <h2 class="font-semibold text-gray-800 break-words">
                                {{ $data->nama }} - {{ $data->jenis }}
                            </h2>
                            <p class="text-sm text-gray-500 break-words">
                                {{ $data->alamat }}
                            </p>

                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="bg-gray-300 border rounded px-3 py-1 text-xs">
                                    Rp. {{ $data->gaji_awal }} - Rp. {{ $data->gaji_akhir }} / bulan
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan (tanggal) -->
                    <span class="text-sm text-gray-500 whitespace-nowrap">
                        {{ $data->published_at?->diffForHumans() }}
                    </span>
                </div>


                <!-- TABEL PELAMAR — dibungkus agar responsif -->
                <div class="bg-white rounded-xl shadow overflow-hidden border w-full">

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-sm border-collapse min-w-[700px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-center font-semibold">Tanggal</th>
                                    <th class="px-6 py-4 text-center font-semibold">Nama</th>
                                    <th class="px-6 py-4 text-center font-semibold">CV</th>
                                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold">Waktu</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">
                                @foreach ($woi as $p)
                                    @if ($p->lowongan_id === $data->id)
                                        <tr class="text-center">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                {{ $p->created_at?->format('d M Y') }}
                                            </td>

                                            <td class="px-4 py-4 break-words">
                                                {{ $p->pelamar->nama_pelamar }}
                                            </td>

                                            <td class="px-4 py-4">
                                                <div class="flex justify-center items-center">
                                                    <button onclick="openConfirmModal({{ $p->pelamar->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-5 h-5 text-orange-500" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path d="M12 3v12l4-4h-3V3h-2v8H8l4 4zM4 19h16v2H4z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if ($p->status === 'diterima' || $p->status === 'ditolak')
                                                    <button
                                                        class="bg-gray-400 text-white px-4 py-1 rounded cursor-not-allowed"
                                                        disabled>
                                                        Terima
                                                    </button>
                                                    <button
                                                        class="bg-gray-400 text-white px-4 py-1 rounded cursor-not-allowed ml-1"
                                                        disabled>
                                                        Tolak
                                                    </button>
                                                @else
                                                    <a href="{{ route('pelamar.konfirmasi', $p->id) }}"
                                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">
                                                        Terima
                                                    </a>

                                                    <button onclick="openTolakModal({{ $p->id }})"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded ml-1">
                                                        Tolak
                                                    </button>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if ($p->expired_at)
                                                    @php
                                                        $sisaHari = now()->diffInDays($p->expired_at, false);
                                                    @endphp

                                                    @if ($sisaHari > 0)
                                                        {{ $sisaHari }} hari
                                                    @else
                                                        <span class="text-red-600 font-semibold">Expired</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-800">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="flex items-start gap-2 text-red-500 text-sm mt-4 break-words">
                    <svg class="w-[33px] h-[33px] text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Zm-1 7a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                            clip-rule="evenodd" />
                    </svg>

                    <p class="break-words">
                        Informasi pelamar akan hilang dalam waktu 30 hari setelah<br>
                        anda konfirmasi <span class="font-semibold">Terima</span>.
                    </p>
                </div>

            </div>
        </div>

        {{-- MODAL CV --}}
        <div id="confirmModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 px-4 z-[9999]">

            <div class="bg-white p-5 md:p-6 rounded-lg text-center w-full max-w-sm break-words">

                <!-- Icon -->
                <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-orange-500 mb-4" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M12 16l4-5h-3V4h-2v7H8l4 5zM4 20h16v2H4z" />
                    </svg>
                </div>

                <!-- Teks -->
                <p class="mb-4 font-medium break-words">
                    Yakin akan mengunduh CV pelamar?
                </p>

                <!-- Tombol -->
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <button onclick="downloadCV()" class="px-4 py-2 bg-orange-500 text-white rounded w-full sm:w-auto">
                        Unduh
                    </button>

                    <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-black rounded w-full sm:w-auto">
                        Batal
                    </button>
                </div>

            </div>
        </div>


        <!-- Modal 2: Sukses -->
        <div id="successModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 px-4">

            <div class="bg-white p-5 md:p-6 rounded-lg text-center w-full max-w-sm break-words">

                <div class="flex justify-center">
                    <div class="bg-orange-100 p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-orange-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 font-semibold break-words">
                    CV Berhasil diunduh
                </p>

                <div class="mt-4">
                    <button onclick="closeSuccessModal()"
                        class="px-4 py-2 bg-orange-500 text-white rounded w-full sm:w-auto">
                        Tutup
                    </button>
                </div>

            </div>
        </div>


        <!-- Modal Tolak -->
        <div id="modalTolak" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">

            <div class="bg-white p-5 md:p-6 rounded-lg text-center shadow-lg w-full max-w-md break-words">

                <h2 class="text-lg md:text-xl font-bold mb-3 text-center break-words">
                    Konfirmasi Tolak Lamaran
                </h2>

                <p class="mb-4 break-words text-center">
                    Apakah anda yakin ingin menolak lamaran ini?
                </p>

                <form id="tolakForm" method="POST" action="" class="text-center">
                    @csrf

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded w-full sm:w-auto transition duration-200">
                            Tolak
                        </button>

                        <button type="button" onclick="closeTolakModal()"
                            class="bg-gray-400 hover:bg-gray-500 px-5 py-2 rounded w-full sm:w-auto transition duration-200">
                            Batal
                        </button>
                    </div>
                </form>

            </div>
        </div>



        <script>
            function openTolakModal(id) {
                let form = document.getElementById('tolakForm');
                let url = "{{ route('pelamar.tolak', ':id') }}";
                form.action = url.replace(':id', id);
                document.getElementById('modalTolak').classList.remove('hidden');
            }

            function closeTolakModal() {
                document.getElementById('modalTolak').classList.add('hidden');
            }
        </script>


        <script>
            let selectedId = null;

            function openConfirmModal(id) {
                selectedId = id;
                document.getElementById('confirmModal').classList.remove('hidden');
            }

            function closeConfirmModal() {
                document.getElementById('confirmModal').classList.add('hidden');
            }

            function downloadCV() {
                if (!selectedId) return;
                closeConfirmModal();
                document.getElementById('successModal').classList.remove('hidden');
                setTimeout(() => {
                    let url = "{{ route('cv.download', ':id') }}";
                    url = url.replace(':id', selectedId);
                    window.location.href = url;
                }, 500);
            }

            function closeSuccessModal() {
                document.getElementById('successModal').classList.add('hidden');
            }
        </script>
        @include('layouts.footer')
    @endsection
