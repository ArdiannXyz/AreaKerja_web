<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    footer {
        font-family: 'Poppins', sans-serif;
    }
</style>

<!-- Footer -->
<footer class="bg-[#004c94] text-white px-6 md:px-16 py-10 md:py-12">
    <div class="max-w-7xl mx-auto">
        {{-- Top Section (Desktop 3 Columns, Mobile Stacked) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">

            <!-- Logo + Description -->
            <div class="space-y-3">
                <div>
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="Logo" class="h-6 sm:h-7 md:h-8 w-auto object-contain">
                </div>
                <div class="text-xs md:text-sm leading-relaxed text-white/90">
                    <p>Lamar Pekerjaan Kamu - <br class="hidden md:inline">Dengan waktu dan <br class="hidden md:inline">langkah yang cepat</p>
                </div>
            </div>

            <!-- Kategori -->
            <div class="order-3 md:order-2">
                <h3 class="mb-3 md:mb-4 text-base md:text-lg font-bold">Kategori</h3>
                @auth
                    @if (Auth::user()->role == 'pelamar')
                        <ul class="grid grid-cols-2 gap-y-2.5 gap-x-4 text-xs md:text-sm text-white/90">
                            <li><a href="{{ route('beranda') }}" class="hover:text-white transition">Beranda</a></li>
                            <li><a href="{{ url('/pelamar/tips-kerja') }}" class="hover:text-white transition">Tips Kerja</a></li>
                            <li><a href="{{ route('transaksi.pendaftaran') }}" class="hover:text-white transition">Transaksi</a></li>
                            <li><a href="{{ url('/bantuan') }}" class="hover:text-white transition">Bantuan</a></li>
                        </ul>
                    @elseif (Auth::user()->role == 'perusahaan')
                        <ul class="grid grid-cols-2 gap-y-2.5 gap-x-4 text-xs md:text-sm text-white/90">
                            <li><a href="{{ route('perusahaan.dashboard') }}" class="hover:text-white transition">Beranda</a></li>
                            <li><a href="{{ route('perusahaan.kandidat.ak') }}" class="hover:text-white transition">Kandidat</a></li>
                            <li><a href="{{ route('talent-hunter.index') }}" class="hover:text-white transition">Talent Hunter</a></li>
                            <li><a href="{{ route('paket.form') }}" class="hover:text-white transition">Pasang Lowongan</a></li>
                        </ul>
                    @else
                        <ul class="grid grid-cols-2 gap-y-2.5 gap-x-4 text-xs md:text-sm text-white/90">
                            <li><a href="/bantuan" class="hover:text-white transition">Bantuan</a></li>
                        </ul>
                    @endif
                @endauth

                @guest
                    <ul class="grid grid-cols-2 gap-y-2.5 gap-x-4 text-xs md:text-sm text-white/90">
                        <li><a href="{{ route('beranda') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ url('/pelamar/tips-kerja') }}" class="hover:text-white transition">Tips Kerja</a></li>
                        <li><a href="#" class="hover:text-white transition">Provinsi Lainnya</a></li>
                        <li><a href="{{ url('/lowongan') }}" class="hover:text-white transition">Pasang Lowongan</a></li>
                    </ul>
                @endguest
            </div>

            <!-- Kontak Kami -->
            <div class="order-2 md:order-3">
                <h3 class="mb-3 md:mb-4 text-base md:text-lg font-bold">Kontak Kami</h3>

                <form action="{{ route('subscribe.email') }}" method="POST">
                    @csrf
                    <div class="flex items-center bg-white rounded-lg p-1 shadow-md max-w-sm">
                        <input type="email" name="email" placeholder="Email address"
                            class="w-full bg-transparent px-3 py-1.5 text-slate-800 focus:outline-none border-none text-xs italic font-normal">
                        <button type="submit"
                            class="bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-xs px-5 py-2 rounded-md transition duration-200 shrink-0">
                            Submit
                        </button>
                    </div>
                </form>

                @error('email')
                    <p class="text-red-200 mt-2 text-xs">{{ $message }}</p>
                @enderror

                @if (session('success'))
                    <p class="text-green-200 mt-2 text-xs">{{ session('success') }}</p>
                @endif
            </div>

        </div>

        {{-- Mobile Section: Berinteraksi Dengan Kami --}}
        <div class="block md:hidden mt-8">
            <h3 class="mb-4 text-base font-bold">Berinteraksi Dengan Kami</h3>
            <div class="flex gap-3">
                <a href="#" class="w-10 h-10 flex items-center justify-center border border-white/40 rounded-lg hover:bg-white/10 transition">
                    <i class="ph-fill ph-facebook-logo text-xl"></i>
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center border border-white/40 rounded-lg hover:bg-white/10 transition">
                    <i class="ph-fill ph-instagram-logo text-xl"></i>
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center border border-white/40 rounded-lg hover:bg-white/10 transition">
                    <i class="ph-fill ph-twitter-logo text-xl"></i>
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center border border-white/40 rounded-lg hover:bg-white/10 transition">
                    <i class="ph-fill ph-linkedin-logo text-xl"></i>
                </a>
            </div>
            <div class="mt-8 text-white/70 text-xs flex items-center gap-6">
                <span>Copyright</span>
                <span>2026 areakerja.com</span>
            </div>
        </div>

        {{-- Desktop Bottom Section --}}
        <div class="hidden md:block">
            <div class="border-t border-white/10 my-8"></div>
            <div class="flex items-center justify-between gap-4 text-xs md:text-sm text-white/70">
                <!-- Left text -->
                <p class="leading-relaxed">Get ease in applying for <br> your dream job</p>

                <!-- Social Icons -->
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 flex items-center justify-center border border-white/30 rounded-lg hover:bg-white/10 transition text-white">
                        <i class="ph-fill ph-facebook-logo text-lg"></i>
                    </a>
                    <a href="#" class="w-9 h-9 flex items-center justify-center border border-white/30 rounded-lg hover:bg-white/10 transition text-white">
                        <i class="ph-fill ph-instagram-logo text-lg"></i>
                    </a>
                    <a href="#" class="w-9 h-9 flex items-center justify-center border border-white/30 rounded-lg hover:bg-white/10 transition text-white">
                        <i class="ph-fill ph-twitter-logo text-lg"></i>
                    </a>
                    <a href="#" class="w-9 h-9 flex items-center justify-center border border-white/30 rounded-lg hover:bg-white/10 transition text-white">
                        <i class="ph-fill ph-linkedin-logo text-lg"></i>
                    </a>
                </div>

                <!-- Right copyright -->
                <p>Copyright © 2026 areakerja.com</p>
            </div>
        </div>
    </div>
</footer>
