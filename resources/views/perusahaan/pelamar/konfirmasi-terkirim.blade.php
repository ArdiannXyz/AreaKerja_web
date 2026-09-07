@extends('layouts.index-perusahaan')

@section('content')
    <!-- Judul di luar border -->


    <!-- Box utama dengan border -->
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4 mt-28 md:ml-10 sm:ml-4 sm:text-base">
            Konfirmasi Terima Lamaran
        </h1>

        <div class="border border-gray-300 rounded-xl p-6 bg-white shadow-sm mb-10">

            <!-- Header: Logo dan Informasi -->
            <div class="flex items-start gap-6 mb-4 sm:flex-col sm:items-center sm:text-center">
                <!-- Logo -->
                <div class="w-30 sm:w-24 sm:h-24">
                    <img src="{{ asset('storage/' . $data->lowongan_perusahaan->perusahaan->img_profile) }}" alt="Seven Logo"
                        class="w-52 h-52 sm:w-24 sm:h-24 object-contain" />
                </div>

                <!-- Teks -->
                <div class="sm:mt-3 sm:text-sm">
                    <h2 class="text-xl font-semibold sm:text-lg">Selamat Kepada</h2><br>
                    <!-- Nama pelamar -->
                    <p class="mb-4 mt-1 font-medium">{{ $data->pelamar->nama_pelamar ?? '-' }}</p>
                    <span class="font-semibold">Kategori :</span>
                    <span class="font-normal">{{ $data->pelamar->kategori ?? 'Belum Kerja' }}</span>

                    <p class="mt-4">
                        Lamaran yang anda ajukan ke lowongan kami pada Divisi <br>
                        {{ $data->lowongan_perusahaan->nama ?? '-' }} telah kami
                        <span class="text-green-600 font-semibold">Terima</span>.
                    </p>

                    <p class="mt-4">
                        Oleh karena itu, kami mengharapkan kehadiran anda pada :
                    </p>

                    <div class="mt-4 space-y-1 sm:text-sm">
                        <p><span class="font-semibold">Tanggal</span> :
                            {{ \Carbon\Carbon::parse($konfirmasi['tanggal'])->translatedFormat('d F Y') }}</p>
                        <p><span class="font-semibold">Pukul</span> : {{ $konfirmasi['waktu'] }}</p>
                        <p><span class="font-semibold">Tempat</span> : {{ $konfirmasi['tempat'] }}</p>
                        @if ($data->gmaps_url)
                            <a href="{{ $data->gmaps_url }}" target="_blank"
                                class="inline-flex items-center text-blue-500 gap-2 px-4 py-2">
                                 Buka Lokasi di Google Maps
                            </a>
                        @endif
                        <p><span class="font-semibold">Keperluan</span> : Wawancara Kerja</p>
                    </div>

                    <p class="mt-8">
                        <span class="font-semibold">Catatan</span> :
                        {{ $konfirmasi['catatan'] ?? '-' }}
                    </p>

                    <p class="mt-4 font-semibold">Hormat
                        kami,<br>{{ $data->lowongan_perusahaan->perusahaan->nama_perusahaan ?? 'Perusahaan' }}</p>
                </div>
            </div>

            <!-- Footer Logo -->
            <div class="flex flex-col items-center mt-10">
                <img src="{{ asset('images/logoarea.png') }}" alt="logoarea" class="w-16 mb-1" />
                <p class="text-xs text-gray-500">CopyrightÂ©2024 areakerja.com</p>
            </div>

            <!-- Tombol -->
            <div class="flex justify-center gap-2 mt-6 sm:flex-col sm:w-full sm:gap-3 sm:items-center">
                <a href="{{ route('pelamar.konfirmasi', $data->id) }}"
                    class="px-7 py-1 bg-[#00509d] text-white rounded-lg hover:bg-[#003d7a]">Kembali</a>
                <form action="{{ route('pelamar.konfirmasi.kirim', $data->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-7 py-1 bg-[#00509d] text-white rounded-lg hover:bg-[#003d7a]">
                        Kirim
                    </button>
                </form>
            </div>

        </div>
    </div>

    @include('layouts.footer')
@endsection

