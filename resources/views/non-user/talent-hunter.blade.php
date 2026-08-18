@extends('layouts.index')
@section('content')
    <!-- Hero Section -->
    <section class="relative">
        <img src="{{ asset('images/woi.jpg') }}"
            alt="Header Image" class="w-full h-[400px] sm:h-[500px] md:h-[600px] object-cover">

        <div class="absolute inset-0 bg-black bg-opacity-40"></div>

        <div class="absolute left-5 sm:left-10 md:left-20 bottom-20 sm:bottom-32 md:bottom-52 text-white max-w-xs sm:max-w-md md:max-w-2xl">
           <h1 class="text-xl md:text-3xl lg:text-4xl font-semibold mt-3 max-w-xs md:max-w-2xl">
                Talent Hunter
            </h1>
            <p class="text-xs sm:text-sm mt-2">Daftarkan perusahaan anda dan biar kami</p>
            <p class="text-xs sm:text-sm">yang mencarikan kandidat yang cocok untuk anda</p><br>
            <button>
                <span class="bg-gray-700 hover:bg-gray-800 text-xs sm:text-sm px-6 sm:px-8 py-2 rounded-lg">Daftar</span>
            </button>
        </div>
    </section>


    <section class="text-white py-12 md:py-20 rounded-b-[50px]"
        style="background: linear-gradient(to right, orange, #ff7b00)">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center px-4 sm:px-6">

            <!-- Gambar -->
            <div class="flex justify-center">
                <img src="{{ asset('images/ntip.png') }}" alt="Talent Hunter"
                    class="h-48 w-48 sm:h-72 sm:w-72 md:h-96 md:w-96">
            </div>

            <!-- Konten steps -->
            <div>
                <h2 class="text-xl sm:text-2xl font-semibold mb-6 leading-snug">
                    Langkah - Langkah Daftar <br> Talent Hunter
                </h2>

                <div class="relative flex max-w-full sm:max-w-xl">
                    <!-- Garis vertikal -->
                    <div class="flex flex-col items-center mr-4 sm:mr-6 mt-4">
                        <svg width="12" class="sm:w-16" height="220" sm:h-[280px]" viewBox="0 0 16 310" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 8V302.001" stroke="white" stroke-width="3" stroke-linecap="round" />
                            <circle cx="8" cy="8" r="8" fill="white" />
                            <circle cx="8" cy="106" r="8" fill="white" />
                            <circle cx="8" cy="204" r="8" fill="white" />
                            <circle cx="8" cy="302" r="8" fill="white" />
                        </svg>
                    </div>

                    <!-- Konten step -->
                    <div class="flex flex-col">
                        <!-- Step 1 -->
                        <div class="mb-6 sm:mb-8">
                            <p class="text-sm sm:text-lg leading-relaxed">Klik tombol daftar untuk mendaftarkan perusahaan
                                anda</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="mb-6 sm:mb-8">
                            <p class="text-sm sm:text-lg leading-relaxed">Mengisi formulir pendaftaran dan kirim formulir
                                pendaftaran</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="mb-6 sm:mb-8">
                            <p class="text-sm sm:text-lg leading-relaxed">Tunggu pemberitahuan selanjutnya setelah
                                pendaftaran</p>
                        </div>

                        <!-- Step 4 -->
                        <div>
                            <p class="text-sm sm:text-lg leading-relaxed">Perusahaan berhasil didaftarkan</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    

    <!-- Benefit Talent Hunter -->
    <section class="bg-white py-12">
        <div class="text-center py-10">
            <!-- Judul -->
            <h2 class="text-2xl font-semibold text-orange-600">Benefit Talent Hunter</h2>
            <div class="w-48 h-1 bg-orange-500 mx-auto mt-4"></div>

            <!-- Atas: 2 item -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10 max-w-3xl mx-auto">
                <!-- Kandidat -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/jam.png') }}" class="w-12 h-12 mb-3" />
                    <h3 class="font-bold text-orange-500">Kandidat</h3>
                    <p class="text-sm text-orange-500">Mendapatkan kandidat sesuai kebutuhan perusahaan dan posisi yang
                        ditujukan.</p>
                </div>

                <!-- Siap Kerja -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/komputer.jpg') }}" class="w-12 h-12 mb-2" />
                    <h3 class="font-bold text-orange-500">Siap Kerja</h3>
                    <p class="text-sm text-orange-500">Kandidat yang didapatkan dipastikan siap kerja dengan perusahaan yang
                        direkomendasikan.</p>
                </div>
            </div>

            <!-- Bawah: 2 item -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10 max-w-3xl mx-auto">
                <!-- Memudahkan -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/roket.png') }}" class="w-12 h-12 mb-3" />
                    <h3 class="font-bold text-orange-500">Memudahkan</h3>
                    <p class="text-sm text-orange-500">Mempermudah perusahaan dalam penyaringan kandidat.</p>
                </div>

                <!-- Jaminan -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/roket.png') }}" class="w-12 h-12 mb-3" />
                    <h3 class="font-bold text-orange-500">Jaminan</h3>
                    <p class="text-sm text-orange-500">Jaminan ganti kandidat baru jika tidak cocok dengan spesifikasi
                        perusahaan.</p>
                </div>
            </div>
        </div>

    </section>

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
    @include('layouts.footer')
@endsection
