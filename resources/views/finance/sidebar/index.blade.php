<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#00509d">

    <title>Finance - AreaKerja</title>

    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_favicon.png') }}?v=7">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=7">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        html, body {
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
            background-color: #f8fafc;
        }

        /* === MOBILE (< 640px) === */
        @media (max-width: 639px) {
            #logo-sidebar {
                display: none !important;
            }
            #bottom-nav {
                display: flex !important;
            }
            body {
                padding-top: 0 !important;
                padding-bottom: 72px !important;
                padding-bottom: calc(72px + env(safe-area-inset-bottom, 0px)) !important;
            }
        }

        /* === DESKTOP (≥ 640px) === */
        @media (min-width: 640px) {
            #logo-sidebar {
                display: block !important;
                pointer-events: auto !important;
                touch-action: auto !important;
            }
            #bottom-nav,
            #mobile-menu-sheet {
                display: none !important;
            }
            body {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
        }

        #logo-sidebar::-webkit-scrollbar { width: 4px; }
        #logo-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.25);
            border-radius: 99px;
        }

        #bottom-nav {
            z-index: 9999 !important;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        a, button {
            -webkit-tap-highlight-color: transparent;
        }

        .tap-effect {
            position: relative;
            overflow: hidden;
        }
        .tap-effect::after {
            content: '';
            position: absolute;
            inset: 0;
            background: currentColor;
            opacity: 0;
            border-radius: inherit;
            transition: opacity 0.15s;
        }
        .tap-effect:active::after {
            opacity: 0.08;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper: sidebar fixed, main content block with sm:ml-64 -->
    <div class="min-h-screen bg-slate-50/70">
        
        <!-- Sidebar Desktop -->
        <aside id="logo-sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 overflow-y-auto overflow-x-hidden"
            aria-label="Sidebar">
            <div class="min-h-screen w-64 bg-[#00509d] text-white flex flex-col justify-between pb-6">
                <div>
                    <!-- Logo -->
                    <div class="px-4 py-4 border-b border-blue-400/30">
                        <a href="{{ route('finance.dashboard') }}" class="inline-flex items-center gap-2.5">
                            <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="logo" class="w-8 h-8 object-contain">
                            <span class="text-base font-semibold tracking-tight text-white leading-none self-center">areakerja.com</span>
                        </a>
                    </div>

                    <!-- Menu -->
                    <nav class="flex-1 px-4 text-sm mt-3">
                        <div class="font-bold mb-3 ml-3 text-blue-200/90 text-xs uppercase tracking-wider">Umum</div>
                        <div class="{{ request()->is('finance/dashboard*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-4">
                            <a href="{{ route('finance.dashboard') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('finance/dashboard*') ? 'ph-fill ph-squares-four' : 'ph ph-squares-four' }} text-lg"></i>
                                Dashboard
                            </a>
                        </div>

                        <div class="font-bold ml-3 mb-3 text-blue-200/90 text-xs uppercase tracking-wider">Menu Keuangan</div>
                        <div class="{{ request()->is('finance/paket*') || request()->is('finance/paketharga*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ route('finance.paket-harga') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('finance/paket*') || request()->is('finance/paketharga*') ? 'ph-fill ph-tag' : 'ph ph-tag' }} text-lg"></i>
                                Paket Harga
                            </a>
                        </div>

                        <div class="{{ request()->is('finance/omset*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ route('finance.omset') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('finance/omset*') ? 'ph-fill ph-chart-line-up' : 'ph ph-chart-line-up' }} text-lg"></i>
                                Omset Perusahaan
                            </a>
                        </div>

                        <div class="{{ request()->is('finance/laporan/transaksi*') || request()->is('finance/detail*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ route('finance.catatan') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('finance/laporan/transaksi*') || request()->is('finance/detail*') ? 'ph-fill ph-receipt' : 'ph ph-receipt' }} text-lg"></i>
                                Catatan Transaksi
                            </a>
                        </div>

                        <div class="{{ request()->is('finance/laporan') || request()->is('finance/laporan/detail*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ route('finance.laporan') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('finance/laporan') || request()->is('finance/laporan/detail*') ? 'ph-fill ph-file-text' : 'ph ph-file-text' }} text-lg"></i>
                                Laporan Transaksi
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </aside>

        @yield('sidebar')
    </div>

    <!-- Bottom Navigation Bar — hanya tampil di mobile (< 640px) -->
    <nav id="bottom-nav" class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-1 flex items-stretch justify-around shadow-[0_-2px_12px_rgba(0,0,0,0.10)]" style="z-index:9999; height:64px;">

        {{-- 1. Dashboard --}}
        @php $isDashboard = request()->is('finance/dashboard*'); @endphp
        <a href="{{ route('finance.dashboard') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isDashboard ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isDashboard ? 'ph-fill ph-squares-four' : 'ph-squares-four' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isDashboard ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Beranda</span>
            @if($isDashboard)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
        </a>

        {{-- 2. Paket Harga --}}
        @php $isPaket = request()->is('finance/paket*') || request()->is('finance/paketharga*'); @endphp
        <a href="{{ route('finance.paket-harga') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isPaket ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isPaket ? 'ph-fill ph-tag' : 'ph-tag' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isPaket ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Paket</span>
            @if($isPaket)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
        </a>

        {{-- 3. Omset --}}
        @php $isOmset = request()->is('finance/omset*'); @endphp
        <a href="{{ route('finance.omset') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isOmset ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isOmset ? 'ph-fill ph-chart-line-up' : 'ph-chart-line-up' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isOmset ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Omset</span>
            @if($isOmset)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
        </a>

        {{-- 4. Catatan Transaksi --}}
        @php $isCatatan = request()->is('finance/laporan/transaksi*') || request()->is('finance/detail*'); @endphp
        <a href="{{ route('finance.catatan') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isCatatan ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isCatatan ? 'ph-fill ph-receipt' : 'ph-receipt' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isCatatan ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Catatan</span>
            @if($isCatatan)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
        </a>

        {{-- 5. Menu --}}
        <button onclick="openMobileMenuSheet()"
                type="button"
                class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl text-slate-400 hover:text-[#00509d] focus:outline-none">
            <i class="ph ph-list text-[22px] leading-none"></i>
            <span class="text-[10px] font-medium leading-none tracking-tight">Menu</span>
        </button>
    </nav>

    <!-- Mobile Menu Bottom Sheet (Muncul dari BAWAH) -->
    <div id="mobile-menu-sheet" class="hidden fixed inset-0 z-[10000] flex flex-col justify-end" aria-modal="true" role="dialog">
        <!-- Backdrop -->
        <div id="mobile-menu-backdrop" onclick="closeMobileMenuSheet()" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity duration-300 opacity-0"></div>

        <!-- Sheet Panel -->
        <div id="mobile-menu-panel" class="relative w-full max-h-[85vh] bg-white rounded-t-3xl shadow-2xl flex flex-col transform translate-y-full transition-transform duration-300 ease-out z-10 overflow-hidden">
            <!-- Drag Indicator -->
            <div class="pt-3 pb-1 flex justify-center cursor-pointer" onclick="closeMobileMenuSheet()">
                <div class="w-12 h-1.5 bg-slate-300 rounded-full"></div>
            </div>

            <!-- Header Panel: Info Finance & Tombol Tutup -->
            <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if (Auth::user()?->avatar)
                        <img class="w-10 h-10 rounded-full object-cover border border-slate-200"
                            src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
                    @else
                        <img class="w-10 h-10 rounded-full border border-slate-200"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'FN') }}&background=00509d&color=fff&size=64"
                            alt="Avatar">
                    @endif
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->username }}</p>
                        <p class="text-[11px] text-[#00509d] font-semibold">Finance Panel &bull; {{ Auth::user()->email }}</p>
                    </div>
                </div>
                <button onclick="closeMobileMenuSheet()" type="button" class="p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition active:scale-95" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Menu Grid List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 overscroll-contain">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-1">Menu Keuangan</p>
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <a href="{{ route('finance.dashboard') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('finance/dashboard*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 text-[#00509d] flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-squares-four"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Dashboard</span>
                        </a>
                        <a href="{{ route('finance.paket-harga') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('finance/paket*') || request()->is('finance/paketharga*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-tag"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Paket Harga</span>
                        </a>
                        <a href="{{ route('finance.omset') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('finance/omset*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-chart-line-up"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Omset</span>
                        </a>
                        <a href="{{ route('finance.catatan') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('finance/laporan/transaksi*') || request()->is('finance/detail*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-receipt"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Catatan</span>
                        </a>
                        <a href="{{ route('finance.laporan') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('finance/laporan') || request()->is('finance/laporan/detail*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-file-text"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Laporan</span>
                        </a>
                        <a href="{{ route('finance.profile') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('finance/profile*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-user-circle"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Profil</span>
                        </a>
                    </div>
                </div>

                <!-- Tombol Logout -->
                <div class="pt-2">
                    <button onclick="closeMobileMenuSheet(); openLogoutModal();" type="button"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-red-50 text-red-600 font-semibold text-xs hover:bg-red-100 transition active:scale-98">
                        <i class="ph ph-sign-out text-base"></i>
                        <span>Keluar dari Akun</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Logout -->
    <div id="logoutModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="relative bg-white rounded-xl shadow-lg w-[90%] max-w-sm p-6 text-center">
            <h2 class="text-lg font-bold mb-3">Konfirmasi Keluar</h2>
            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin keluar dari akun Finance?</p>
            <div class="flex justify-center gap-4">
                <form action="{{ route('logout_finance') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-md font-medium">
                        Keluar
                    </button>
                </form>
                <button type="button" onclick="closeLogoutModal()" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md font-medium">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <script>
        function openLogoutModal() {
            const modal = document.getElementById("logoutModal");
            if (modal) {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            }
        }

        function closeLogoutModal() {
            const modal = document.getElementById("logoutModal");
            if (modal) {
                modal.classList.remove("flex");
                modal.classList.add("hidden");
            }
        }

        // Mobile Menu Bottom Sheet Handlers
        function openMobileMenuSheet() {
            const sheet = document.getElementById('mobile-menu-sheet');
            const backdrop = document.getElementById('mobile-menu-backdrop');
            const panel = document.getElementById('mobile-menu-panel');
            if (!sheet || !backdrop || !panel) return;

            sheet.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                panel.classList.remove('translate-y-full');
                panel.classList.add('translate-y-0');
            });
        }

        function closeMobileMenuSheet() {
            const sheet = document.getElementById('mobile-menu-sheet');
            const backdrop = document.getElementById('mobile-menu-backdrop');
            const panel = document.getElementById('mobile-menu-panel');
            if (!sheet || !backdrop || !panel) return;

            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            panel.classList.remove('translate-y-0');
            panel.classList.add('translate-y-full');

            setTimeout(() => {
                sheet.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenuSheet();
                closeLogoutModal();
            }
        });
    </script>
</body>

</html>
