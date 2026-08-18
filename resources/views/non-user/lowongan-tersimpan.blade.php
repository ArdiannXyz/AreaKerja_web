@extends('layouts.index')
@section('content')
    <!-- Hero Section -->
    <div class="relative">
        <img src="{{ asset('images/tersimpan.jpg') }}"
            alt="Header Image" class="w-full h-[400px] sm:h-[500px] md:h-[600px] object-cover">

        <div class="absolute inset-0 bg-black bg-opacity-40"></div>

         <div class="absolute left-5 sm:left-10 md:left-20 bottom-20 sm:bottom-32 md:bottom-52 text-white max-w-xs sm:max-w-md md:max-w-2xl">
            <h1 class="text-xl md:text-3xl lg:text-4xl font-semibold mt-3 max-w-xs md:max-w-2xl">Lowongan Tersimpan</h1>
            <p class="text-white text-sm md:text-lg mt-2">
                Lowongan anda yang sudah tersimpan <br class="hidden md:block"> disistem areakerja.com
            </p>
        </div>
    </div>

    <!-- Card List -->
    <div class="max-w-6xl mx-auto px-4 mt-6 md:mt-10 space-y-4 mb-10">
        @forelse($simpanlowongan as $item)
            @php $lowongan = $item->lowongan; @endphp

            <a
                href="{{ route('detail.lowongan.non.user', [
                    'perusahaan' => $lowongan->perusahaan->slug,
                    'lowongan' => $lowongan->slug,
                ]) }}">

                <div
                    class="bg-white shadow rounded-md p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $lowongan->perusahaan->img_profile) }}" alt="logo"
                            class="w-14 h-14 md:w-20 md:h-20 rounded-full object-cover">

                        <div>
                            <h5 class="text-gray-500 text-xs md:text-sm">
                                {{ $lowongan->perusahaan->nama_perusahaan ?? 'Perusahaan' }}
                            </h5>

                            <h2 class="font-semibold text-gray-800 text-sm md:text-base">
                                {{ $lowongan->nama }}
                            </h2>

                            <p class="text-gray-500 text-xs md:text-sm">
                                {{ $lowongan->alamat }}
                            </p>

                            <p class="text-gray-700 text-xs md:text-sm bg-gray-100 px-2 py-1 inline-block rounded mt-1">
                                Rp. {{ number_format($lowongan->gaji_awal, 0, ',', '.') }} –
                                Rp. {{ number_format($lowongan->gaji_akhir, 0, ',', '.') }} per bulan
                            </p>
                        </div>
                    </div>

                    <p class="text-gray-400 text-xs md:text-sm md:text-right">
                        Aktif {{ $lowongan->created_at->diffForHumans() }}
                    </p>

                </div>

            </a>

        @empty
            <p class="text-center text-gray-500">Belum ada lowongan yang tersimpan.</p>
        @endforelse
    </div>


    <!-- Button -->
    @if ($simpanlowongan->count() > 5)
        <div class="flex justify-center mb-10">
            <button class="bg-orange-500 text-white px-8 py-2 rounded-md hover:bg-orange-600 transition">
                Memuat
            </button>
        </div>
    @endif

    @include('layouts.footer')
@endsection
