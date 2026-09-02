@extends('layouts.index')
@section('content')

    <div class="bg-white min-h-screen text-slate-800 pt-20 sm:pt-22 md:pt-24 pb-20">

        <!-- Hero Section (Full bleed edge-to-edge, no margin gap, no round) -->
        <section class="relative w-full overflow-hidden shadow-sm">
            <img src="{{ asset('images/woi.jpg') }}"
                alt="Header Image" class="w-full h-[360px] sm:h-[450px] md:h-[520px] object-cover">

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute left-6 sm:left-12 md:left-20 bottom-12 sm:bottom-20 text-white max-w-md md:max-w-xl">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight">
                    Talent Hunter
                </h1>
                <p class="text-sm sm:text-base mt-3 text-white/90">
                    Daftarkan perusahaan anda dan biar kami yang mencarikan kandidat yang cocok untuk anda
                </p>
                <div class="mt-6">
                    <a href="{{ route('register') }}"
                        class="inline-block bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-8 py-2.5 rounded-lg text-sm shadow-md transition">
                        Daftar
                    </a>
                </div>
            </div>
        </section>

        <!-- Steps Section (Langkah - Langkah) -->
        <section class="w-full bg-[#0066cc] text-white py-16 mb-16">
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center px-6">

                <!-- Left Man Image -->
                <div class="flex justify-center">
                    <img src="{{ asset('images/ntip.png') }}" alt="Talent Hunter"
                        class="h-64 sm:h-80 md:h-[420px] object-contain drop-shadow-xl">
                </div>

                <!-- Right Steps List -->
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold mb-8 leading-snug">
                        Langkah - Langkah Daftar <br> Talent Hunter
                    </h2>

                    <div class="relative flex">
                        <!-- Vertical line with dots -->
                        <div class="flex flex-col items-center mr-5 sm:mr-6 mt-1.5">
                            <div class="w-3.5 h-3.5 bg-white rounded-full"></div>
                            <div class="w-0.5 h-16 bg-white/70"></div>
                            <div class="w-3.5 h-3.5 bg-white rounded-full"></div>
                            <div class="w-0.5 h-16 bg-white/70"></div>
                            <div class="w-3.5 h-3.5 bg-white rounded-full"></div>
                            <div class="w-0.5 h-16 bg-white/70"></div>
                            <div class="w-3.5 h-3.5 bg-white rounded-full"></div>
                        </div>

                        <!-- Step texts -->
                        <div class="flex flex-col justify-between space-y-6">
                            <div>
                                <p class="text-sm sm:text-base font-semibold leading-relaxed">
                                    Klik tombol daftar untuk mendaftarkan perusahaan anda
                                </p>
                            </div>
                            <div>
                                <p class="text-sm sm:text-base font-semibold leading-relaxed">
                                    Mengisi formulir pendaftaran dan kirim formulir pendaftaran
                                </p>
                            </div>
                            <div>
                                <p class="text-sm sm:text-base font-semibold leading-relaxed">
                                    Tunggu pemberitahuan selanjutnya setelah pendaftaran
                                </p>
                            </div>
                            <div>
                                <p class="text-sm sm:text-base font-semibold leading-relaxed">
                                    Perusahaan berhasil didaftarkan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Benefit Talent Hunter Section -->
        <section class="py-16 max-w-5xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-[#00509d]">Benefit Talent Hunter</h2>
                <div class="w-32 h-1 bg-[#00509d] mx-auto mt-3 rounded-full"></div>
            </div>

            <!-- 2x2 Dark Blue Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <!-- Card 1: Kandidat -->
                <div class="bg-[#004e98] text-white rounded-2xl p-8 text-center flex flex-col items-center justify-center shadow-md hover:-translate-y-1 transition duration-200">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-4 text-white">
                        <i class="ph ph-clock-counter-clockwise"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Kandidat</h3>
                    <p class="text-xs sm:text-sm text-white/90 leading-relaxed">
                        Mendapatkan kandidat sesuai kebutuhan perusahaan dan posisi yang ditujukan.
                    </p>
                </div>

                <!-- Card 2: Siap Kerja -->
                <div class="bg-[#004e98] text-white rounded-2xl p-8 text-center flex flex-col items-center justify-center shadow-md hover:-translate-y-1 transition duration-200">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-4 text-white">
                        <i class="ph ph-desktop"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Siap Kerja</h3>
                    <p class="text-xs sm:text-sm text-white/90 leading-relaxed">
                        Kandidat yang didapatkan dipastikan siap kerja dengan perusahaan yang direkomendasikan
                    </p>
                </div>

                <!-- Card 3: Memudahkan -->
                <div class="bg-[#004e98] text-white rounded-2xl p-8 text-center flex flex-col items-center justify-center shadow-md hover:-translate-y-1 transition duration-200">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-4 text-white">
                        <i class="ph ph-rocket-launch"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Memudahkan</h3>
                    <p class="text-xs sm:text-sm text-white/90 leading-relaxed">
                        Mempermudah perusahaan dalam penyaringan kandidat.
                    </p>
                </div>

                <!-- Card 4: Jaminan -->
                <div class="bg-[#004e98] text-white rounded-2xl p-8 text-center flex flex-col items-center justify-center shadow-md hover:-translate-y-1 transition duration-200">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-4 text-white">
                        <i class="ph ph-shield-check"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Jaminan</h3>
                    <p class="text-xs sm:text-sm text-white/90 leading-relaxed">
                        Jaminan ganti kandidat yang baru jika tidak cocok dengan spesifikasi perusahaan.
                    </p>
                </div>
            </div>
        </section>

        <!-- Back to top button -->
        <a href="#top"
            class="fixed bottom-6 right-6 bg-[#00509d] text-white p-3.5 rounded-full shadow-xl hover:bg-[#003d7a] transition z-40 flex items-center justify-center"
            title="Kembali ke Atas">
            <i class="ph ph-arrow-up font-bold text-lg"></i>
        </a>

    </div>

    @include('layouts.footer')
@endsection
