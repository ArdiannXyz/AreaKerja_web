<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="register-pelamar-url" content="{{ route('registerproses') }}">
    <meta name="register-perusahaan-url" content="{{ route('registerproses_perusahaan') }}">
    {{-- <meta name="notif-mark-all-url" content="{{ route('notifikasi.bacaSemua') }}"> --}}


    
    <title>areakerja.com</title>
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- TinyMCE Content Styles -->
    <link rel="stylesheet"
        href="https://cdn.tiny.cloud/1/oqx873eo8a4800gwchmdyn357lbg0rvj9bxkryttzmw9uf7q/tinymce/8/skins/content/default/content.min.css">
    <link rel="stylesheet"
        href="https://cdn.tiny.cloud/1/oqx873eo8a4800gwchmdyn357lbg0rvj9bxkryttzmw9uf7q/tinymce/8/skins/ui/oxide/content.min.css">

    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/logoarea.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>





    <!-- Kalau mau CSS langsung (style regular) -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web/src/regular/style.css"> -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        div[modal-backdrop],
        div[backdrop-edge],
        .bg-gray-900\/50,
        .bg-black\/50 {
            background-color: rgba(15, 23, 42, 0.25) !important;
        }
    </style>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .notif-profil {
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 12px !important;
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); */
            background: transparent !important;
        }

        .notif-profil .introjs-skipbutton {
            display: none !important;
        }

        .notif-profil .introjs-arrow {
            display: none !important;
        }

        .notif-profil.introjs-tooltip {
            transform: translateY(-25px) !important;
        }

        .introjs-overlay {
            pointer-events: none !important;
            background: rgba(0, 0, 0, 0.3) !important;
        }

        .introjs-helperLayer,
        .introjs-overlay {
            pointer-events: none !important;
        }

        .introjs-tooltip {
            pointer-events: auto !important;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            cursor: pointer;
            object-fit: cover;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            /* background: rgba(0, 0, 0, 0.8); */
            justify-content: center;
            align-items: center;
        }

        .modal img {
            max-width: 90%;
            max-height: 90%;
        }

        .introjs-tooltip,
        .introjs-tooltip .introjs-tooltiptext,
        .introjs-tooltip .introjs-nextbutton,
        .introjs-tooltip .introjs-prevbutton,
        .introjs-tooltip .introjs-skipbutton,
        .introjs-tooltip .introjs-donebutton,
        .notif-profil.introjs-tooltip,
        .notif-profil.introjs-tooltip * {
            box-shadow: none !important;
            -webkit-box-shadow: none !important;
            filter: none !important;
        }

        .introjs-tooltip:before,
        .introjs-tooltip:after,
        .notif-profil.introjs-tooltip:before,
        .notif-profil.introjs-tooltip:after {
            box-shadow: none !important;
            -webkit-box-shadow: none !important;
            background: transparent !important;
        }


        .introjs-tooltip {
            z-index: 100000 !important;
            pointer-events: auto !important;
            background-clip: padding-box;
        }

        .notif-profil {
            box-shadow: none !important;
            background: transparent !important;
            border: 0 !important;
        }

        /* TinyMCE Content Styles */


        .tinymce-content {
            font-family: Inter, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.7;
        }

        /* Paragraph spacing */
        .tinymce-content p {
            margin-bottom: 1rem;
        }

        /* LIST — supaya BULLET hitam muncul */
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

        /* Gambar responsif */
        .tinymce-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 1rem auto;
            border-radius: 6px;
        }

        /* Blockquote */
        .tinymce-content blockquote {
            border-left: 4px solid #ccc;
            padding-left: 1rem;
            margin: 1rem 0;
            font-style: italic;
            color: #555;
        }

        /* Tabel */
        .tinymce-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        .tinymce-content table,
        .tinymce-content th,
        .tinymce-content td {
            border: 1px solid #ddd;
        }

        .tinymce-content th,
        .tinymce-content td {
            padding: 8px;
        }
    </style>
</head>


<body x-data="{ openNotif: false, openAllNotif: false, openMenu: false }">
    @php
        $isBeranda = Route::is('beranda') || request()->is('/') || request()->is('pelamar/home');
    @endphp

    {{-- Navbar --}}
    <header class="{{ $isBeranda ? 'bg-[#ff7a00] text-white' : 'bg-white text-slate-800 border-b border-slate-100 shadow-sm' }} fixed top-0 left-0 w-full z-50 transition-all duration-200">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10 h-20 flex items-center justify-between">

            <!-- Logo & Hamburger (Kiri) -->
            <div class="flex items-center gap-3">
                <!-- Hamburger Button (HANYA MUNCUL DI HP < 768px) -->
                <button @click="openMenu = !openMenu" type="button" class="flex md:hidden p-1.5 rounded-lg {{ $isBeranda ? 'text-white hover:bg-white/10' : 'text-[#ff7a00] hover:bg-orange-50' }} focus:outline-none focus:ring-2 focus:ring-orange-400" aria-label="Toggle Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('beranda') }}" class="flex items-center gap-2.5 sm:gap-3">
                    @if ($isBeranda)
                        <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="Areakerja Logo" class="h-8 sm:h-9 lg:h-10 object-contain">
                        <span class="font-bold text-lg sm:text-2xl lg:text-[26px] text-white tracking-tight">areakerja.com</span>
                    @else
                        <img src="{{ asset('images/logoarea.png') }}" alt="Areakerja Logo" class="h-8 sm:h-9 lg:h-10 object-contain">
                        <span class="font-bold text-lg sm:text-2xl lg:text-[26px] text-[#ff7a00] tracking-tight">areakerja.com</span>
                    @endif
                </a>
            </div>

            <!-- Menu Desktop & Laptop (Tengah) - Tampil di layar >= 768px -->
            <nav class="hidden md:flex items-center font-semibold text-xs sm:text-sm lg:text-base {{ $isBeranda ? 'text-white' : 'text-[#ff7a00]' }} gap-4 sm:gap-6 lg:gap-9 xl:gap-12">
                <a href="{{ route('beranda') }}" class="{{ $isBeranda ? 'hover:text-white/80' : 'hover:text-orange-600' }} transition-colors whitespace-nowrap">
                    Beranda
                </a>
                <a href="{{ url('/talent-hunter') }}" class="{{ $isBeranda ? 'hover:text-white/80' : 'hover:text-orange-600' }} transition-colors whitespace-nowrap">
                    Talent Hunter
                </a>
                <a href="{{ url('/pelamar/tips-kerja') }}" class="{{ $isBeranda ? 'hover:text-white/80' : 'hover:text-orange-600' }} transition-colors whitespace-nowrap">
                    Tips Kerja
                </a>
                <a href="{{ route('pelamar.daftar-kandidat') }}" class="{{ $isBeranda ? 'hover:text-white/80' : 'hover:text-orange-600' }} transition-colors whitespace-nowrap">
                    Daftar Kandidat
                </a>
                <a href="{{ url('/lowongan') }}" class="{{ $isBeranda ? 'hover:text-white/80' : 'hover:text-orange-600' }} transition-colors whitespace-nowrap">
                    Pasang Lowongan
                </a>
            </nav>

            <!-- Aksi (Kanan) -->
            <div class="flex items-center gap-3 sm:gap-4">
                @auth
                    {{-- Notifikasi (Hanya jika Login) --}}
                    <button @click="openNotif = true" class="relative p-2 rounded-full {{ $isBeranda ? 'hover:bg-white/10 text-white' : 'hover:bg-orange-50 text-[#ff7a00]' }} transition">
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M23.4955 17.1131C23.3918 17.006 23.29 16.8989 23.1901 16.7955C21.8162 15.3699 20.9851 14.5096 20.9851 10.474C20.9851 8.38475 20.4024 6.67047 19.254 5.38475C18.4072 4.43493 17.2626 3.7144 15.7539 3.1819C15.7344 3.17263 15.7171 3.16048 15.7027 3.146C15.16 1.58708 13.675 0.542969 12.0002 0.542969C10.3253 0.542969 8.84094 1.58708 8.29828 3.1444C8.28379 3.15834 8.2667 3.17011 8.24769 3.17922C4.72691 4.42261 3.01586 6.80815 3.01586 10.4724C3.01586 14.5096 2.18593 15.3699 0.810843 16.7939C0.710927 16.8973 0.609138 17.0023 0.505476 17.1115C0.237702 17.3886 0.0680456 17.7256 0.0165842 18.0828C-0.0348772 18.4399 0.0340108 18.8023 0.215096 19.1269C0.600396 19.8233 1.42158 20.2556 2.35891 20.2556H21.6483C22.5812 20.2556 23.3968 19.8239 23.7833 19.1306C23.9652 18.8059 24.0347 18.4433 23.9837 18.0857C23.9327 17.7282 23.7633 17.3906 23.4955 17.1131ZM12.0002 24.543C12.9025 24.5423 13.7879 24.3322 14.5623 23.9349C15.3368 23.5375 15.9714 22.9677 16.3989 22.286C16.4191 22.2533 16.429 22.2167 16.4278 22.1798C16.4266 22.1429 16.4143 22.1068 16.392 22.0752C16.3698 22.0435 16.3384 22.0173 16.3008 21.9992C16.2633 21.981 16.221 21.9715 16.1779 21.9715H7.82368C7.78054 21.9714 7.7381 21.9809 7.70049 21.999C7.66288 22.0171 7.63138 22.0433 7.60906 22.0749C7.58674 22.1066 7.57435 22.1427 7.57311 22.1797C7.57188 22.2167 7.58182 22.2533 7.60199 22.286C8.02946 22.9677 8.664 23.5374 9.43832 23.9347C10.2126 24.3321 11.0979 24.5422 12.0002 24.543Z" fill="{{ $isBeranda ? '#FFFFFF' : '#FF7A00' }}" />
                        </svg>
                        @if ($global_notifikasi_unread > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">
                                {{ $global_notifikasi_unread }}
                            </span>
                        @endif
                    </button>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                        class="{{ $isBeranda ? 'bg-white text-[#ff7a00] hover:bg-orange-50' : 'bg-[#ff7a00] text-white hover:bg-orange-600' }} font-semibold px-6 sm:px-8 lg:px-10 py-2 sm:py-2.5 lg:py-3 rounded-2xl transition-all text-sm sm:text-base text-center shadow-sm hover:shadow whitespace-nowrap">
                        Masuk
                    </a>
                @endguest

                {{-- Menu Mobile Dropdown (Hanya HP < 768px) --}}
                <div x-show="openMenu" x-transition x-cloak @click.outside="openMenu = false"
                    class="flex flex-col absolute top-full left-0 w-full {{ $isBeranda ? 'bg-orange-600 text-white border-orange-700' : 'bg-white text-slate-800 border-slate-100' }} border-t py-4 shadow-xl z-40 md:hidden">

                    <a href="{{ route('beranda') }}"
                        class="px-6 py-3 {{ $isBeranda ? 'text-white hover:bg-orange-700' : 'text-slate-700 hover:bg-orange-50 hover:text-orange-600' }} transition duration-300 font-medium">
                        Beranda
                    </a>
                    <a href="{{ url('/talent-hunter') }}"
                        class="px-6 py-3 {{ $isBeranda ? 'text-white hover:bg-orange-700' : 'text-slate-700 hover:bg-orange-50 hover:text-orange-600' }} transition duration-300 font-medium">
                        Talent Hunter
                    </a>
                    <a href="{{ url('/pelamar/tips-kerja') }}"
                        class="px-6 py-3 {{ $isBeranda ? 'text-white hover:bg-orange-700' : 'text-slate-700 hover:bg-orange-50 hover:text-orange-600' }} transition duration-300 font-medium">
                        Tips Kerja
                    </a>
                    <a href="{{ route('pelamar.daftar-kandidat') }}"
                        class="px-6 py-3 {{ $isBeranda ? 'text-white hover:bg-orange-700' : 'text-slate-700 hover:bg-orange-50 hover:text-orange-600' }} transition duration-300 font-medium">
                        Daftar Kandidat
                    </a>
                    <a href="{{ url('/lowongan') }}"
                        class="px-6 py-3 {{ $isBeranda ? 'text-white hover:bg-orange-700' : 'text-slate-700 hover:bg-orange-50 hover:text-orange-600' }} transition duration-300 font-medium">
                        Pasang Lowongan
                    </a>

                    @guest
                        <div class="px-6 pt-3">
                            <a href="{{ route('login') }}"
                                class="block w-full text-center {{ $isBeranda ? 'bg-white text-orange-600 hover:bg-orange-50' : 'bg-[#ff7a00] text-white hover:bg-orange-600' }} py-3 rounded-2xl font-semibold transition">
                                Masuk
                            </a>
                        </div>
                    @endguest
                </div>

                {{-- Jika sudah login tampilkan dropdown --}}
                @php
                    $user = Auth::user();
                    $role = $user->role ?? null;

                    $dashboardRoute = match ($role) {
                        'super_admin' => route('superadmin.dashboard'),
                        'admin' => route('admin.dashboard'),
                        'finance' => route('finance.dashboard'),
                        'perusahaan' => route('perusahaan.dashboard'),
                        default => null,
                    };
                @endphp

                @auth
                    <div class="flex items-center space-x-3">
                        {{-- Foto Profil --}}
                        <button type="button" id="user-menu-button"
                            class="flex text-sm rounded-full focus:ring-4 focus:ring-gray-300"
                            data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                            <span class="sr-only">Open user menu</span>
                            @if (Auth::user()->role == 'pelamar')
                                @if (Auth::user()->pelamar->img_profile)
                                    <img id="pi" class="w-10 h-10  object-cover rounded-full profile-img"
                                        src="{{ asset('storage/' . Auth::user()->pelamar->img_profile) }}"
                                        alt="Profile">
                                @else
                                    <img id="pi" class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                        alt="">
                                @endif
                            @else
                                <img class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                    alt="">
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        {{-- Dropdown --}}
                        <div class="z-50 min-w-[220px] max-w-[300px] hidden my-4 text-base bg-white text-gray-800 divide-y divide-gray-100 rounded-xl shadow-2xl border border-slate-100"
                            id="user-dropdown">
                            <div class="px-4 py-3">
                                <span class="block text-sm font-bold text-gray-900 break-all">{{ Auth::user()->username }}</span>
                                <span class="block text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</span>
                            </div>
                            <ul class="py-2 text-gray-700 text-sm font-medium" aria-labelledby="user-menu-button">
                                @php
                                    $user = Auth::user();
                                @endphp

                                {{-- JIKA ROLE ADMIN / SUPER / FINANCE / PERUSAHAAN --}}
                                @if ($dashboardRoute)
                                    <li>
                                        <a href="{{ $dashboardRoute }}"
                                            class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                            <i class="ph ph-squares-four mr-2 text-orange-500 text-lg"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                @else
                                    {{-- JIKA PELAMAR --}}
                                    @php
                                        $kategori = $user?->pelamar?->kategori;
                                    @endphp

                                    <li>
                                        <a href="{{ route('profile.index') }}"
                                            class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition"
                                            id="profile-link">
                                            @if ($kategori === 'kandidat aktif')
                                                <i class="ph ph-users mr-2 text-orange-500 text-lg"></i>
                                                <span>Kandidat</span>
                                            @elseif ($kategori === 'calon kandidat')
                                                <i class="ph ph-users mr-2 text-orange-500 text-lg"></i>
                                                <span>Calon Kandidat</span>
                                            @else
                                                <i class="ph ph-user mr-2 text-orange-500 text-lg"></i>
                                                <span>Profil Saya</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <a href="{{ $dashboardRoute ?? route('lowongan.tersimpan') }}"
                                        class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                        <i class="ph ph-bookmark-simple mr-2 text-orange-500 text-lg"></i>
                                        Lowongan Tersimpan
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ $dashboardRoute ?? route('transaksi.pendaftaran') }}"
                                        class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                        <i class="ph ph-receipt mr-2 text-orange-500 text-lg"></i>
                                        Transaksi
                                    </a>
                                </li>

                                <li>
                                    <a href="/bantuan"
                                        class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                        <i class="ph ph-question mr-2 text-orange-500 text-lg"></i>
                                        Bantuan
                                    </a>
                                </li>
                                <li class="px-4 pt-2 pb-1">
                                    <form action="{{ route('logout_pelamar') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-2 bg-orange-500 text-white font-bold rounded-lg shadow-sm hover:bg-orange-600 transition text-sm">
                                            Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endauth
                {{-- POPUP LOGIN PERTAMA --}}
                {{-- @if (session('show_first_login_popup') && !session('profile_popup_closed'))
                    <div id="firstLoginPopup" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                        <div class="bg-white p-6 rounded-xl shadow-xl text-center w-[350px]">
                            <h2 class="text-lg font-bold mb-2">Lengkapi Profil Anda</h2>

                            <p class="text-gray-600 mb-4">
                                Lengkapi informasi profil Anda untuk mendapatkan rekomendasi lowongan terbaik.
                            </p>

                            <a href="{{ route('profile.index') }}"
                                class="px-5 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                                Pergi ke Profil
                            </a>
                        </div>

                    </div>
                @endif --}}

                {{-- MENU MOBILE (Hamburger) --}}

                <!-- <div x-show="openMenu" x-transition
                    class="md:hidden absolute top-16 left-0 w-full bg-white shadow-lg z-50">

                    <nav class="flex flex-col font-medium text-sm text-orange-500 gap-4 p-4">

                        <a href="{{ route('beranda') }}"
                            class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                   {{ Route::is('beranda') ? 'font-bold text-orange-500 scale-105' : '' }}">
                            Beranda
                        </a>

                        <a href="{{ url('/talent-hunter') }}"
                            class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                    {{ request()->is('talent-hunter') ? 'font-bold text-orange-500 scale-105' : '' }}">
                            Talent Hunter
                        </a>

                        <a href="{{ url('/pelamar/tips-kerja') }}"
                            class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                   {{ Route::is('pelamar.tips-kerja') ? 'font-bold text-orange-500 scale-105' : '' }}">
                            Tips Kerja
                        </a>

                       
                        @if (Auth::check() && Auth::user()->pelamar)
@if (Auth::user()->pelamar->kategori === 'calon kandidat')
<a href="{{ route('pelamar.calon-kandidat.pelatihan') }}"
                                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                    {{ Route::is('pelamar.calon-kandidat.pelatihan') ? 'font-bold text-orange-500 scale-105' : '' }}">
                                    Rekrut Saya
                                </a>
@elseif (Auth::user()->pelamar->kategori === 'kandidat aktif')
<a href="{{ route('pelamar.tawaran') }}"
                                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                    {{ Route::is('pelamar.tawaran') ? 'font-bold text-orange-500 scale-105' : '' }}">
                                    Rekrut Saya
                                </a>
@else
<a href="{{ route('pelamar.daftar-kandidat') }}"
                                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                    {{ Route::is('pelamar.daftar-kandidat') ? 'font-bold text-orange-500 scale-105' : '' }}">
                                    Daftar Kandidat
                                </a>
@endif
@else
<a href="{{ route('pelamar.daftar-kandidat') }}"
                                class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                  {{ Route::is('pelamar.daftar-kandidat') ? 'font-bold text-orange-500 scale-105' : '' }}">
                                Daftar Kandidat
                            </a>
@endif

                        <a href="{{ url('/lowongan') }}"
                            class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-300
                   {{ request()->is('lowongan') ? 'font-bold text-orange-500 scale-105' : '' }}">
                            Pasang Lowongan
                        </a>

                    </nav>
                </div> -->

            </div>
        </div>
    </header>
    {{-- Isi Halaman --}}
    @yield('content')

    {{-- NOTIF --}}
    @include('non-user.notif.modal_notif')
    @include('non-user.notif.modal_semua')


    {{-- Onboarding Tooltip
    <div id="onboarding" class="hidden">
        <div class="fixed inset-0 bg-black bg-opacity-70 z-40"></div>
        <div class="absolute top-20 right-6 bg-white p-4 rounded-lg shadow-lg z-50 max-w-xs">
            <p class="text-sm">Silahkan lengkapi <span class="font-semibold">Profil</span> anda terlebih dahulu.</p>
            <div class="mt-3 text-right">
                <button onclick="closeOnboarding()"
                    class="px-3 py-1 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition">
                    OK
                </button>
            </div>
            <div class="absolute top-3 -left-2 w-0 h-0 border-y-8 border-y-transparent border-r-8 border-r-white">
            </div>
        </div>
    </div> --}}
    {{-- @if (session('show_first_login_popup'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('popupModal').classList.remove('hidden');
            });
        </script>
    @endif --}}

    {{-- Script --}}
    <script>
        function showOnboarding() {
            document.getElementById('onboarding').classList.remove('hidden');
        }

        function closeOnboarding() {
            document.getElementById('onboarding').classList.add('hidden');
        }
        window.onload = function() {
            let firstLogin = "{{ session('first_login') }}";
            if (firstLogin) {
                showOnboarding();
            }
        };
    </script>

    {{-- Liat Gambar --}}
    <script>
        document.getElementById('fileinput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('pp').setAttribute('src', event.target.result);
                    document.getElementById('pi').setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    {{-- NOTIF --}}
    <script>
        // Tandai dibaca
        async function markAsRead(url, el) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {

                    // Ubah warna bg
                    el.classList.remove("bg-white");
                    el.classList.add("bg-gray-200");

                    // Kurangi badge
                    const badge = document.getElementById("notif-badge");
                    if (badge) {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove();
                        }
                    }
                }

            } catch (error) {
                console.error("markAsRead error:", error);
            }
        }

        // AlpineJS init
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifHandler', () => ({

                // Lihat Detail Notifikasi
                viewDetail(id, judul, pesan, createdAt, readUrl, el) {
                    if (readUrl && el) {
                        markAsRead(readUrl, el);
                    }

                    Swal.fire({
                        title: `<div class="text-base font-bold text-gray-800">${judul || 'Detail Notifikasi'}</div>`,
                        html: `
                            <div class="text-left text-sm text-gray-700 leading-relaxed bg-orange-50/50 p-4 rounded-xl border border-orange-100 mt-2 mb-3">
                                ${pesan}
                            </div>
                            <div class="text-xs text-gray-400 text-left flex items-center gap-1">
                                ⏱️ ${createdAt}
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#f97316',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Tutup',
                        cancelButtonText: 'Hapus Notifikasi Ini',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl'
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            this.hapus(id);
                        }
                    });
                },

                // Hapus satu notifikasi dengan SweetAlert
                hapus(id) {
                    Swal.fire({
                        title: 'Hapus Notifikasi?',
                        text: 'Notifikasi ini akan dihapus secara permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl'
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);

                            try {
                                let res = await fetch(url, {
                                    method: "DELETE",
                                    headers: {
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                        "Accept": "application/json"
                                    }
                                });

                                let data = await res.json();

                                if (data.success) {
                                    document.querySelectorAll(`.notif-item[data-id="${id}"]`).forEach(e => e.remove());
                                    Swal.fire({
                                        title: 'Terhapus!',
                                        text: 'Notifikasi berhasil dihapus.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            } catch (err) {
                                console.error(err);
                            }
                        }
                    });
                },

                // Hapus semua dengan SweetAlert
                hapusSemua() {
                    Swal.fire({
                        title: 'Hapus Semua Notifikasi?',
                        text: 'Semua notifikasi Anda akan dihapus secara permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus Semua!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl'
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                                    method: "DELETE",
                                    headers: {
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                        "Accept": "application/json"
                                    }
                                });

                                let data = await res.json();

                                if (data.success) {
                                    document.querySelectorAll('.notif-item').forEach(e => e.remove());
                                    Swal.fire({
                                        title: 'Terhapus!',
                                        text: 'Semua notifikasi berhasil dihapus.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            } catch (err) {
                                console.error(err);
                            }
                        }
                    });
                },

                // Hapus semua yang sudah dibaca dengan SweetAlert
                hapusSemuaBaca() {
                    Swal.fire({
                        title: 'Hapus Notifikasi Dibaca?',
                        text: 'Semua notifikasi yang sudah dibaca akan dihapus.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl'
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                                    method: "DELETE",
                                    headers: {
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                        "Accept": "application/json"
                                    }
                                });

                                let data = await res.json();

                                if (data.success) {
                                    document.querySelectorAll('.notif-item.bg-gray-100, .notif-item.bg-gray-200, .notif-item.bg-gray-50\\/70')
                                        .forEach(e => e.remove());
                                    Swal.fire({
                                        title: 'Terhapus!',
                                        text: 'Notifikasi yang sudah dibaca berhasil dihapus.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            } catch (err) {
                                console.error(err);
                            }
                        }
                    });
                }

            }));
        });
    </script>


    <script>
        document.querySelector('form[target="hiddenFrame"]').addEventListener('submit', () => {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.classList.remove('bg-white');
                item.classList.add('bg-gray-200');
            });
            const badge = document.querySelector('.absolute .bg-red-500');
            if (badge) badge.remove();
        });
    </script>



    @include('layouts.modal-logout')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="{{ asset('js/non_user.js') }}"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
</body>

</html>
