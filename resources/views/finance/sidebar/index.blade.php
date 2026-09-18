<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finance AreaKerja</title>

    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_biru.png') }}?v=7">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=7">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <!-- Mobile Top Navigation Header -->
    <div class="sm:hidden flex items-center justify-between p-3.5 bg-[#00509d] text-white shadow-md sticky top-0 z-30">
        <div class="flex items-center gap-2.5">
            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                type="button"
                class="inline-flex items-center p-2 text-white hover:bg-[#003d7a] rounded-xl focus:outline-none transition">
                <span class="sr-only">Open sidebar</span>
                <i class="ph ph-list font-bold text-2xl"></i>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="AreaKerja" class="w-7 h-7 object-contain">
                <span class="font-bold text-base tracking-tight">areakerja.com</span>
            </div>
        </div>
        <span class="px-2.5 py-1 bg-white/20 text-white font-extrabold text-[11px] rounded-lg tracking-wider uppercase">
            Finance
        </span>
    </div>

    <!-- Sidebar -->
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="flex flex-col justify-between h-full bg-[#00509d] text-white p-4 shadow-xl select-none overflow-y-auto">
            
            <!-- Top Section -->
            <div class="space-y-6">
                <!-- Brand / Logo -->
                <div class="flex items-center justify-between px-2 pt-1 pb-4 border-b border-blue-400/30">
                    <a href="{{ route('finance.dashboard') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="logo" class="w-8 h-8 object-contain transition-transform group-hover:scale-105">
                        <div>
                            <p class="text-lg text-white font-extrabold tracking-tight leading-tight">areakerja.com</p>
                            <span class="text-[10px] font-semibold text-blue-200 tracking-wider uppercase">Finance Panel</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="space-y-5">
                    
                    <!-- UMUM GROUP -->
                    <div>
                        <span class="block px-3 mb-2 text-[10px] font-extrabold text-blue-200/80 uppercase tracking-wider">
                            Umum
                        </span>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('finance.dashboard') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition duration-200 {{ request()->is('finance/dashboard') ? 'bg-white text-[#00509d] shadow-md ring-1 ring-black/5' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                                    <i class="{{ request()->is('finance/dashboard') ? 'ph-fill ph-squares-four' : 'ph ph-squares-four' }} text-lg"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- FINANCE GROUP -->
                    <div>
                        <span class="block px-3 mb-2 text-[10px] font-extrabold text-blue-200/80 uppercase tracking-wider">
                            Menu Keuangan
                        </span>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('finance.paket-harga') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition duration-200 {{ request()->is('finance/paket*') || request()->is('finance/paketharga*') ? 'bg-white text-[#00509d] shadow-md ring-1 ring-black/5' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                                    <i class="{{ request()->is('finance/paket*') || request()->is('finance/paketharga*') ? 'ph-fill ph-tag' : 'ph ph-tag' }} text-lg"></i>
                                    <span>Paket Harga</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('finance.omset') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition duration-200 {{ request()->is('finance/omset*') ? 'bg-white text-[#00509d] shadow-md ring-1 ring-black/5' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                                    <i class="{{ request()->is('finance/omset*') ? 'ph-fill ph-chart-line-up' : 'ph ph-chart-line-up' }} text-lg"></i>
                                    <span>Omset Perusahaan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('finance.catatan') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition duration-200 {{ request()->is('finance/laporan/transaksi*') || request()->is('finance/detail*') ? 'bg-white text-[#00509d] shadow-md ring-1 ring-black/5' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                                    <i class="{{ request()->is('finance/laporan/transaksi*') || request()->is('finance/detail*') ? 'ph-fill ph-receipt' : 'ph ph-receipt' }} text-lg"></i>
                                    <span>Catatan Transaksi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('finance.laporan') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition duration-200 {{ request()->is('finance/laporan') || request()->is('finance/laporan/detail*') ? 'bg-white text-[#00509d] shadow-md ring-1 ring-black/5' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                                    <i class="{{ request()->is('finance/laporan') || request()->is('finance/laporan/detail*') ? 'ph-fill ph-file-text' : 'ph ph-file-text' }} text-lg"></i>
                                    <span>Laporan Transaksi</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Bottom Section / Logout -->
            <div class="pt-4 border-t border-blue-400/30">
                <button type="button" onclick="openLogoutModal()"
                    class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl font-bold text-xs text-rose-100 hover:text-white hover:bg-rose-600/80 transition duration-200 group">
                    <i class="ph ph-sign-out text-lg text-rose-300 group-hover:text-white"></i>
                    <span>Keluar</span>
                </button>
            </div>

        </div>
    </aside>

    <!-- Main Content Dynamic Container -->
    <div class="min-h-screen">
        @yield('sidebar')
    </div>

    <!-- Modal Logout -->
    <div id="logoutModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 transition-all">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 text-center border border-slate-100 animate-fadeIn">
            
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-2xs">
                <i class="ph-fill ph-sign-out text-2xl"></i>
            </div>

            <h2 class="text-base font-extrabold text-slate-900 mb-1.5">Konfirmasi Keluar</h2>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                Apakah Anda yakin ingin keluar dari akun Finance AreaKerja?
            </p>

            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="closeLogoutModal()"
                    class="w-1/2 px-4 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition">
                    Batal
                </button>
                <form action="{{ route('logout_finance') }}" method="POST" class="w-1/2">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-xs transition">
                        Ya, Keluar
                    </button>
                </form>
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

        // Close modal on backdrop click or ESC
        document.getElementById("logoutModal")?.addEventListener("click", function(e) {
            if (e.target === this) closeLogoutModal();
        });

        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") closeLogoutModal();
        });
    </script>
</body>

</html>
