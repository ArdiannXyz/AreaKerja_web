@extends('layouts.index')
@section('content')

    <div class="bg-white min-h-screen text-slate-800 pt-20 sm:pt-22 md:pt-24 pb-20">

        <!-- Hero Section (Full bleed edge-to-edge, no margin gap, no round) -->
        <section class="relative w-full overflow-hidden shadow-sm mb-16">
            <img src="{{ asset('images/tangan.png') }}"
                alt="Header Image" class="w-full h-[360px] sm:h-[450px] md:h-[520px] object-cover">

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute left-6 sm:left-12 md:left-20 bottom-12 sm:bottom-20 text-white max-w-md md:max-w-xl">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight">
                    Pasang Lowongan
                </h1>
                <p class="text-sm sm:text-base mt-3 text-white/90">
                    Dapatkan karyawan berkualitas untuk perusahaan anda
                </p>
                <div class="mt-6">
                    <a href="{{ route('register') }}"
                        class="inline-block bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-8 py-2.5 rounded-lg text-sm shadow-md transition">
                        Daftar
                    </a>
                </div>
            </div>
        </section>

        <!-- Pricing Tier Cards Section (Matching Figma preview_tier_cards.png) -->
        <section class="py-12 max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-center items-stretch">

                <!-- GOLD TIER CARD -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xl overflow-hidden flex flex-col transition duration-300 hover:-translate-y-1">
                    <div class="py-4 text-center bg-[#fca311]">
                        <h3 class="text-2xl font-bold text-white tracking-wide">GOLD</h3>
                    </div>
                    <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-lg font-bold text-slate-900 text-center mb-2">Lebih Banyak Benefit</p>
                            <p class="text-xs sm:text-sm text-slate-600 text-center mb-6">5 Kali Publikasi di semua jaringan Areakerja.com</p>
                            <div class="border-b border-slate-300 mb-6"></div>
                            <ul class="text-sm font-medium text-slate-800 space-y-3.5 mb-8">
                                @foreach (['Website & Aplikasi', 'Instagram Post & Story', 'Highlight Story Favorit', 'Google Jobs & Bisnis', 'Facebook Post & Story', 'Twitter', 'LinkedIn', 'Telegram'] as $item)
                                    <li class="flex items-center gap-3">
                                        <i class="ph-bold ph-check text-slate-900 text-base"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{ route('login') }}"
                            class="text-white font-bold py-3 px-6 rounded-xl block text-center shadow-sm hover:opacity-90 transition bg-[#fca311] text-sm">
                            Pasang Lowongan
                        </a>
                    </div>
                </div>

                <!-- SILVER TIER CARD -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xl overflow-hidden flex flex-col transition duration-300 hover:-translate-y-1">
                    <div class="py-4 text-center bg-[#949ca4]">
                        <h3 class="text-2xl font-bold text-white tracking-wide">Silver</h3>
                    </div>
                    <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-lg font-bold text-slate-900 text-center mb-2">Lebih Banyak Benefit</p>
                            <p class="text-xs sm:text-sm text-slate-600 text-center mb-6">3 Kali Publikasi di semua jaringan Areakerja.com</p>
                            <div class="border-b border-slate-300 mb-6"></div>
                            <ul class="text-sm font-medium text-slate-800 space-y-3.5 mb-8">
                                @foreach (['Website & Aplikasi', 'Instagram Post & Story', 'Highlight Story Favorit', 'Google Jobs & Bisnis', 'Facebook Post & Story', 'Twitter', 'LinkedIn', 'Telegram'] as $item)
                                    <li class="flex items-center gap-3">
                                        <i class="ph-bold ph-check text-slate-900 text-base"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{ route('login') }}"
                            class="text-white font-bold py-3 px-6 rounded-xl block text-center shadow-sm hover:opacity-90 transition bg-[#727d88] text-sm">
                            Pasang Lowongan
                        </a>
                    </div>
                </div>

                <!-- BRONZE TIER CARD -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xl overflow-hidden flex flex-col transition duration-300 hover:-translate-y-1">
                    <div class="py-4 text-center bg-[#6c584c]">
                        <h3 class="text-2xl font-bold text-white tracking-wide">Bronze</h3>
                    </div>
                    <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-lg font-bold text-slate-900 text-center mb-2">Lebih Banyak Benefit</p>
                            <p class="text-xs sm:text-sm text-slate-600 text-center mb-6">1 Kali Publikasi di semua jaringan Areakerja.com</p>
                            <div class="border-b border-slate-300 mb-6"></div>
                            <ul class="text-sm font-medium text-slate-800 space-y-3.5 mb-8">
                                @foreach (['Website & Aplikasi', 'Instagram Post & Story', 'Highlight Story Favorit', 'Google Jobs & Bisnis', 'Facebook Post & Story', 'Twitter', 'LinkedIn', 'Telegram'] as $item)
                                    <li class="flex items-center gap-3">
                                        <i class="ph-bold ph-check text-slate-900 text-base"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{ route('login') }}"
                            class="text-white font-bold py-3 px-6 rounded-xl block text-center shadow-sm hover:opacity-90 transition bg-[#58483e] text-sm">
                            Pasang Lowongan
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <!-- Langkah - Langkah Section -->
        <section class="py-16 max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-[#00509d]">Langkah - Langkah</h2>
                <div class="w-32 h-1 bg-[#00509d] mx-auto mt-3 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 rounded-2xl overflow-hidden shadow-md">
                <!-- 01 -->
                <div class="bg-[#004080] text-white p-6 flex flex-col justify-between min-h-[160px]">
                    <span class="text-2xl font-bold text-white/90">01</span>
                    <p class="text-xs sm:text-sm font-medium leading-relaxed mt-4">
                        Pilih paket pemasangan lowongan sesuai yang anda inginkan
                    </p>
                </div>

                <!-- 02 -->
                <div class="bg-[#0066cc] text-white p-6 flex flex-col justify-between min-h-[160px]">
                    <span class="text-2xl font-bold text-white/90">02</span>
                    <p class="text-xs sm:text-sm font-medium leading-relaxed mt-4">
                        Kirim materi lowongan via formulir website atau whatsapp kami
                    </p>
                </div>

                <!-- 03 -->
                <div class="bg-[#004d99] text-white p-6 flex flex-col justify-between min-h-[160px]">
                    <span class="text-2xl font-bold text-white/90">03</span>
                    <p class="text-xs sm:text-sm font-medium leading-relaxed mt-4">
                        Anda akan diberi instruksi pembayaran
                    </p>
                </div>

                <!-- 04 -->
                <div class="bg-[#3399ff] text-white p-6 flex flex-col justify-between min-h-[160px]">
                    <span class="text-2xl font-bold text-white/90">04</span>
                    <p class="text-xs sm:text-sm font-medium leading-relaxed mt-4">
                        Lowongan anda siap di publish!
                    </p>
                </div>
            </div>
        </section>

        <!-- Kenapa Harus Area Kerja ? Section -->
        <section class="py-16 max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-[#00509d]">Kenapa Harus Area Kerja ?</h2>
                <div class="w-36 h-1 bg-[#00509d] mx-auto mt-3 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-10">
                <!-- Left Woman Image -->
                <div class="flex justify-center">
                    <img src="{{ asset('images/kenapa.png') }}" alt="Kenapa Area Kerja" class="max-h-[440px] object-contain">
                </div>

                <!-- Right Feature Cards -->
                <div class="space-y-5">
                    <!-- Feature 1 -->
                    <div class="bg-[#004e98] text-white rounded-2xl p-6 flex items-center gap-5 shadow-sm hover:translate-x-1 transition duration-200">
                        <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center shrink-0 text-3xl">
                            <i class="ph ph-globe"></i>
                        </div>
                        <p class="text-xs sm:text-sm leading-relaxed font-medium">
                            Website kami menjangkau ratusan perusahaan yang siap menerima ribuan pencari kerja
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-[#004e98] text-white rounded-2xl p-6 flex items-center gap-5 shadow-sm hover:translate-x-1 transition duration-200">
                        <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center shrink-0 text-3xl">
                            <i class="ph ph-chat-circle-dots"></i>
                        </div>
                        <p class="text-xs sm:text-sm leading-relaxed font-medium">
                            Akun media social kami diikuti ratusan ribu pencari kerja serta memiliki jaringan social media yang lengkap
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-[#004e98] text-white rounded-2xl p-6 flex items-center gap-5 shadow-sm hover:translate-x-1 transition duration-200">
                        <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center shrink-0 text-3xl">
                            <i class="ph ph-thumbs-up"></i>
                        </div>
                        <p class="text-xs sm:text-sm leading-relaxed font-medium">
                            Harga yang ramah bagi para pencari kerja tetapi dengan keuntungan peluang yang besar
                        </p>
                    </div>
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
