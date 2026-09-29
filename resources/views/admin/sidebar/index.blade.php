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

    <title>Admin - AreaKerja</title>

    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_favicon.png') }}?v=6">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=6">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

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

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
        }
        select::-ms-expand {
            display: none;
        }

        .tinymce-content {
            font-family: Inter, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.7;
        }
        .tinymce-content p {
            margin-bottom: 1rem;
        }
        .tinymce-content ul,
        .tinymce-content ul li {
            list-style-type: disc !important;
            list-style-position: outside !important;
            margin-left: 1.5rem !important;
            padding-left: 0.5rem !important;
        }
        .tinymce-content ol,
        .tinymce-content ol li {
            list-style-type: decimal !important;
            list-style-position: outside !important;
            margin-left: 1.5rem !important;
            padding-left: 0.5rem !important;
        }
        .tinymce-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 1rem auto;
            border-radius: 6px;
        }
        .tinymce-content blockquote {
            border-left: 4px solid #ccc;
            padding-left: 1rem;
            margin: 1rem 0;
            font-style: italic;
            color: #555;
        }
        .tinymce-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .tinymce-content table,
        .tinymce-content th,
        .tinymce-content td {
            border: 1px solid #ddd;
            padding: 8px;
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
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2.5">
                            <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="logo" class="w-8 h-8 object-contain">
                            <span class="text-base font-semibold tracking-tight text-white leading-none self-center">areakerja.com</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="flex-1 px-4 text-sm mt-3">
                        <div class="font-bold mb-3 ml-3 text-blue-200/90 text-xs uppercase tracking-wider">Umum</div>
                        <div class="{{ request()->is('admin/dashboard*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-4">
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('admin/dashboard*') ? 'ph-fill ph-squares-four' : 'ph ph-squares-four' }} text-lg"></i>
                                <span>Dashboard</span>
                            </a>
                        </div>

                        <div class="font-bold ml-3 mb-3 text-blue-200/90 text-xs uppercase tracking-wider">Admin</div>
                        
                        <div class="{{ request()->is('admin/pelamar*') || request()->is('admin/non/kandidat*') || request()->is('admin/kandidat*') || request()->is('admin/calon/kandidat*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ route('admin.calon-kandidat') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('admin/pelamar*') || request()->is('admin/non/kandidat*') || request()->is('admin/kandidat*') || request()->is('admin/calon/kandidat*') ? 'ph-fill ph-users' : 'ph ph-users' }} text-lg"></i>
                                <span>Data Pelamar</span>
                            </a>
                        </div>

                        <div class="{{ request()->is('admin/perusahaan*') || request()->is('admin/recruitment*') || request()->is('admin/talenthunter*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ route('admin.perusahaan') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('admin/perusahaan*') || request()->is('admin/recruitment*') || request()->is('admin/talenthunter*') ? 'ph-fill ph-buildings' : 'ph ph-buildings' }} text-lg"></i>
                                <span>Data Perusahaan</span>
                            </a>
                        </div>

                        <div class="{{ request()->is('admin/finance*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ url('/admin/finance') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('admin/finance*') ? 'ph-fill ph-wallet' : 'ph ph-wallet' }} text-lg"></i>
                                <span>Finance</span>
                            </a>
                        </div>

                        <div class="{{ request()->is('admin/tips/kerja*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ url('/admin/tips/kerja') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('admin/tips/kerja*') ? 'ph-fill ph-lightbulb' : 'ph ph-lightbulb' }} text-lg"></i>
                                <span>Tips Kerja</span>
                            </a>
                        </div>

                        <div class="{{ request()->is('admin/event*') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md mb-1.5">
                            <a href="{{ route('admin.eventform') }}"
                                class="flex font-semibold items-center gap-2.5 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-200">
                                <i class="{{ request()->is('admin/event*') ? 'ph-fill ph-calendar-star' : 'ph ph-calendar-star' }} text-lg"></i>
                                <span>Event</span>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </aside>

        @yield('sidebaradmin')
    </div>

    <!-- Bottom Navigation Bar — hanya tampil di mobile (< 640px) -->
    <nav id="bottom-nav" class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-1 flex items-stretch justify-around shadow-[0_-2px_12px_rgba(0,0,0,0.10)]" style="z-index:9999; height:64px;">

        {{-- 1. Dashboard --}}
        @php $isDashboard = request()->is('admin/dashboard*'); @endphp
        <a href="{{ route('admin.dashboard') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isDashboard ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isDashboard ? 'ph-fill ph-squares-four' : 'ph-squares-four' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isDashboard ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Beranda</span>
            @if($isDashboard)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
        </a>

        {{-- 2. Pelamar --}}
        @php $isPelamar = request()->is('admin/pelamar*') || request()->is('admin/non/kandidat*') || request()->is('admin/kandidat*') || request()->is('admin/calon/kandidat*'); @endphp
        <a href="{{ route('admin.calon-kandidat') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isPelamar ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isPelamar ? 'ph-fill ph-users' : 'ph-users' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isPelamar ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Pelamar</span>
            @if($isPelamar)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
        </a>

        {{-- 3. Perusahaan --}}
        @php $isPerusahaan = request()->is('admin/perusahaan*') || request()->is('admin/recruitment*') || request()->is('admin/talenthunter*'); @endphp
        <a href="{{ route('admin.perusahaan') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isPerusahaan ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isPerusahaan ? 'ph-fill ph-buildings' : 'ph-buildings' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isPerusahaan ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Perusahaan</span>
            @if($isPerusahaan)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
        </a>

        {{-- 4. Event --}}
        @php $isEvent = request()->is('admin/event*'); @endphp
        <a href="{{ route('admin.eventform') }}"
           class="tap-effect flex flex-col items-center justify-center gap-0.5 flex-1 py-2 rounded-xl {{ $isEvent ? 'text-[#00509d]' : 'text-slate-400' }}">
            <i class="ph {{ $isEvent ? 'ph-fill ph-calendar-star' : 'ph-calendar-star' }} text-[22px] leading-none"></i>
            <span class="text-[10px] {{ $isEvent ? 'font-bold' : 'font-medium' }} leading-none tracking-tight">Event</span>
            @if($isEvent)<span class="w-1 h-1 rounded-full bg-[#00509d]"></span>@endif
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

            <!-- Header Panel: Info Admin & Tombol Tutup -->
            <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if (Auth::user()?->admin?->img_profile)
                        <img class="w-10 h-10 rounded-full object-cover border border-slate-200"
                            src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
                    @elseif (Auth::user()?->avatar)
                        <img class="w-10 h-10 rounded-full object-cover border border-slate-200"
                            src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
                    @else
                        <img class="w-10 h-10 rounded-full border border-slate-200"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'AD') }}&background=00509d&color=fff&size=64"
                            alt="Avatar">
                    @endif
                    <div>
                        <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()?->admin?->nama_lengkap ?: Auth::user()->username }}</p>
                        <p class="text-[11px] text-[#00509d] font-semibold">Admin Panel &bull; {{ Auth::user()->email }}</p>
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
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-1">Navigasi Utama</p>
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/dashboard*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 text-[#00509d] flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-squares-four"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.calon-kandidat') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/pelamar*') || request()->is('admin/non/kandidat*') || request()->is('admin/kandidat*') || request()->is('admin/calon/kandidat*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-users"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Pelamar</span>
                        </a>
                        <a href="{{ route('admin.perusahaan') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/perusahaan*') || request()->is('admin/recruitment*') || request()->is('admin/talenthunter*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-buildings"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Perusahaan</span>
                        </a>
                        <a href="{{ url('/admin/finance') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/finance*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-wallet"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Finance</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-1">Fitur & Konten</p>
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <a href="{{ url('/admin/tips/kerja') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/tips/kerja*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-lightbulb"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Tips Kerja</span>
                        </a>
                        <a href="{{ route('admin.eventform') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/event*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-calendar-star"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Event</span>
                        </a>
                        <a href="{{ route('admin.profile') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/profile*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-user"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Profil</span>
                        </a>
                        <a href="{{ route('admin.edit.profile') }}" class="flex flex-col items-center gap-1.5 p-2 rounded-xl hover:bg-blue-50 transition active:scale-95 {{ request()->is('admin/edit/profile*') ? 'bg-blue-50 text-[#00509d] font-bold' : 'text-slate-700' }}">
                            <div class="w-11 h-11 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph ph-note-pencil"></i>
                            </div>
                            <span class="text-[11px] leading-tight">Edit Profil</span>
                        </a>
                    </div>
                </div>

                <!-- Tombol Logout -->
                <div class="pt-2">
                    <button onclick="closeMobileMenuSheet(); openModal();" type="button"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-red-50 text-red-600 font-semibold text-xs hover:bg-red-100 transition active:scale-98">
                        <i class="ph ph-sign-out text-base"></i>
                        <span>Keluar dari Akun</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Logout -->
    <div id="successModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="relative bg-white rounded-xl shadow-lg w-[90%] max-w-sm p-6 text-center">
            <h2 class="text-lg font-bold mb-3">Konfirmasi Keluar</h2>
            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin keluar?</p>
            <div class="flex justify-center gap-4">
                <form id="logout_admin" action="{{ route('logout_admin') }}" method="POST">
                    @csrf
                    <button id="goLogin" type="submit"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-md font-medium">
                        Keluar
                    </button>
                </form>
                <button type="button" onclick="closeModal()"
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md font-medium">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <script>
        window.routes = {
            hapusNotif: "{{ route('notifikasi.hapus', ':id') }}",
            hapusSemua: "{{ route('notifikasi.hapusSemua') }}",
            hapusSemuaBaca: "{{ route('notifikasi.hapusSemuaBaca') }}"
        };
        window.csrf = "{{ csrf_token() }}";

        function openModal() {
            let modal = document.getElementById("successModal");
            if (modal) {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            }
        }

        function closeModal() {
            let modal = document.getElementById("successModal");
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
                closeModal();
            }
        });
    </script>
</body>

</html>
