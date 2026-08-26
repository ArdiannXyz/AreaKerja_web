<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="register-pelamar-url" content="{{ route('registerproses') }}">
    <meta name="register-perusahaan-url" content="{{ route('registerproses_perusahaan') }}">

    <title>areakerja.com</title>
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="icon" sizes="512x512" type="image/png" href="{{ asset('images/logoarea.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Paksa semua teks pakai Poppins  --}}
    <style>
        [x-cloak] {
            display: none !important;
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
            background: rgba(0, 0, 0, 0.8);
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

    <script src="//unpkg.com/alpinejs" defer></script>

</head>

<body x-data="{ openNotif: false, openAllNotif: false, openMenu: false }">
    {{-- navbar --}}
    <header class="bg-white border-b shadow-md py-2 border-gray-300 fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            <!-- HAMBURGER UNTUK TABLET DAN MOBILE -->
            <button @click="openMenu = !openMenu" class="flex xl:hidden">
                <!-- ikon hamburger -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <!-- MENU TABLET DAN MOBILE -->
            <div x-show="openMenu" x-transition x-cloak
                class="flex flex-col absolute top-16 left-0 w-full bg-white border-t border-gray-200 py-4 shadow-lg z-40 xl:hidden">


                <div class="flex items-center gap-2 px-6 pb-3 pt-4">
                    <img src="{{ asset('images/logoarea.png') }}" class="h-9" alt="">
                    <span class="font-semibold text-orange-600">
                        <a href="{{ route('perusahaan.dashboard') }}">areakerja.com</a></span>
                </div>

                <a href="{{ route('perusahaan.dashboard') }}"
                    class="px-6 py-3 text-gray-700 hover:bg-gray-100 hover:text-orange-500  transition duration-300">
                    Beranda
                </a>
                <a href="{{ route('perusahaan.berlangganan') }}"
                    class="px-6 py-3 hover:bg-gray-100 hover:text-orange-500 transition duration-300 text-gray-700">
                    Berlangganan
                </a>
                <a href="{{ route('talent-hunter.index') }}"
                    class="px-6 py-3 hover:bg-gray-100 hover:text-orange-500 transition duration-300 text-gray-700">
                    Talent Hunter
                </a>
                <a href="{{ route('perusahaan.kandidat.ak') }}"
                    class="px-6 py-3 hover:bg-gray-100 hover:text-orange-500 transition duration-300 text-gray-700">
                    Kandidat
                </a>
                <a href="{{ route('paket.form') }}"
                    class="px-6 py-3 hover:bg-gray-100 hover:text-orange-500 transition duration-300 text-gray-700">
                    Pasang Lowongan
                </a>
                <a href="{{ route('perusahaan.event.index') }}"
                    class="px-6 py-3 hover:bg-gray-100 hover:text-orange-500 transition duration-300 text-gray-700">
                    Event
                </a>
            </div>


            {{-- logo --}}
            <div class="hidden xl:flex items-center gap-2">
                <a href="{{ route('perusahaan.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logoarea.png') }}" alt="Areakerja Logo" class="h-10">
                    <span
                        class="font-bold text-xl 
            {{ request()->routeIs('perusahaan.dashboard') ? 'text-orange-500' : 'text-orange-600' }}">
                        areakerja.com
                    </span>
                </a>
            </div>

            @if (auth()->user()->perusahaan?->verification_status !== 'approved')
            <div>
                <div id="alert"
                    class="fixed lg:ml-24 md:ml-72 ml-52 top-24 left-1/2 -translate-x-1/2  bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 rounded w-full max-w-2xl z-10 shadow-lg animate-slideDown">
                    <strong>Perhatian!</strong><br>
                    Akun perusahaan Anda sedang dalam proses verifikasi.
                    Harap tunggu hingga admin menyetujui data perusahaan Anda.
                </div>
            </div>
            @endif

            <style>
              
                @keyframes slideDown {
                    from {
                        opacity: 0;
                        transform: translate(-50%, -20px);
                    }

                    to {
                        opacity: 1;
                        transform: translate(-50%, 0);
                    }
                }

              
                @keyframes slideUp {
                    from {
                        opacity: 1;
                        transform: translate(-50%, 0);
                    }

                    to {
                        opacity: 0;
                        transform: translate(-50%, -20px);
                    }
                }

            
                .animate-slideDown {
                    animation: slideDown 0.6s ease-out forwards;
                }

                .animate-slideUp {
                    animation: slideUp 0.6s ease-in forwards;
                }
            </style>



            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    setTimeout(() => {
                        const alert = document.getElementById("alert");
                        if (!alert) return;

                        alert.classList.remove("animate-slideDown");
                        alert.classList.add("animate-slideUp");

                        setTimeout(() => {
                            alert.remove();
                        }, 600);
                    }, 10000);
                });
            </script>



            {{-- menu --}}
            <nav class="hidden xl:flex gap-8 font-mediu m text-gray-800">

                <a href="{{ route('perusahaan.dashboard') }}"
                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-400
                {{ request()->routeIs('perusahaan.dashboard') ? 'text-orange-500 font-bold' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('perusahaan.berlangganan') }}"
                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-400
                    {{ request()->routeIs('perusahaan.berlangganan') ? 'text-orange-500 font-bold' : '' }}">
                    Berlangganan
                </a>
                <a href="{{ route('talent-hunter.index') }}"
                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-400
                 {{ request()->routeIs('talent-hunter.index') ? 'text-orange-500 font-bold' : '' }}">
                    Talent Hunter
                </a>

                <a href="{{ route('perusahaan.kandidat.ak') }}"
                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-400
                    {{ request()->routeIs('perusahaan.kandidat.ak') ? 'text-orange-500 font-bold' : '' }}">
                    Kandidat
                </a>
                <a href="{{ route('paket.form') }}"
                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-400
                {{ request()->routeIs('paket.form') ? 'text-orange-500 font-bold' : '' }}">
                    Pasang Lowongan
                </a>
                <a href="{{ route('perusahaan.event.index') }}"
                    class="hover:text-orange-500 hover:font-bold hover:scale-105 transition-all duration-400
                {{ request()->routeIs('perusahaan.event.index') ? 'text-orange-500 font-bold' : '' }}">
                    Event
                </a>

            </nav>
            {{-- Aksi --}}
            <div class="flex items-center gap-5">
                {{-- Notifikasi --}}
                <button @click="openNotif = true" class="relative">
                    <!-- Icon Lonceng -->
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23.4955 17.1131C23.3918 17.006 23.29 16.8989 23.1901 16.7955C21.8162 15.3699 20.9851 14.5096 20.9851 10.474C20.9851 8.38475 20.4024 6.67047 19.254 5.38475C18.4072 4.43493 17.2626 3.7144 15.7539 3.1819C15.7344 3.17263 15.7171 3.16048 15.7027 3.146C15.16 1.58708 13.675 0.542969 12.0002 0.542969C10.3253 0.542969 8.84094 1.58708 8.29828 3.1444C8.28379 3.15834 8.2667 3.17011 8.24769 3.17922C4.72691 4.42261 3.01586 6.80815 3.01586 10.4724C3.01586 14.5096 2.18593 15.3699 0.810843 16.7939C0.710927 16.8973 0.609138 17.0023 0.505476 17.1115C0.237702 17.3886 0.0680456 17.7256 0.0165842 18.0828C-0.0348772 18.4399 0.0340108 18.8023 0.215096 19.1269C0.600396 19.8233 1.42158 20.2556 2.35891 20.2556H21.6483C22.5812 20.2556 23.3968 19.8239 23.7833 19.1306C23.9652 18.8059 24.0347 18.4433 23.9837 18.0857C23.9327 17.7282 23.7633 17.3906 23.4955 17.1131ZM12.0002 24.543C12.9025 24.5423 13.7879 24.3322 14.5623 23.9349C15.3368 23.5375 15.9714 22.9677 16.3989 22.286C16.4191 22.2533 16.429 22.2167 16.4278 22.1798C16.4266 22.1429 16.4143 22.1068 16.392 22.0752C16.3698 22.0435 16.3384 22.0173 16.3008 21.9992C16.2633 21.981 16.221 21.9715 16.1779 21.9715H7.82368C7.78054 21.9714 7.7381 21.9809 7.70049 21.999C7.66288 22.0171 7.63138 22.0433 7.60906 22.0749C7.58674 22.1066 7.57435 22.1427 7.57311 22.1797C7.57188 22.2167 7.58182 22.2533 7.60199 22.286C8.02946 22.9677 8.664 23.5374 9.43832 23.9347C10.2126 24.3321 11.0979 24.5422 12.0002 24.543Z"
                            fill="#FA6601" />
                    </svg>

                    <!-- Badge angka merah -->
                    @if ($global_notifikasi_unread > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- </button> --}}
                @guest
                    <a href="{{ route('login') }}"
                        class="px-11 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        Masuk
                    </a>
                @endguest

                {{-- Jika sudah login tampilkan dropdown --}}
                @auth
                    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                        <button id="ntap" type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0"
                            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                            data-dropdown-placement="bottom">
                            <span class="sr-only">Open user menu</span>
                            @if (Auth::user()->role == 'perusahaan')
                                <div
                                    class="px-6 py-2 bg-orange-500 rounded-lg text-white font-semibold text-center max-w-[130px] truncate">
                                    {{ Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username }}
                                </div>
                            @else
                                <div class="px-6 py-2 bg-orange-500 rounded-lg text-white font-semibold text-center">
                                    {{ Auth::user()->username }}
                                </div>
                            @endif
                        </button>

                        <!-- Dropdown menu -->
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-md border"
                            id="user-dropdown">
                            <div class="bg-white rounded-2xl shadow-lg w-80 overflow-hidden">
                                <!-- Header -->
                                <div class="flex items-center gap-3 px-5 py-4">
                                    @if (Auth::user()->role == 'perusahaan')
                                        @if (Auth::user()->perusahaan->img_profile)
                                            <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                                src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}"
                                                alt="Profile">
                                        @else
                                            <img id="pu" class="w-10 h-10 rounded-full"
                                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                                alt="">
                                        @endif
                                    @else
                                        <img class="w-10 h-10 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                            alt="">
                                    @endif
                                    <div>
                                        <span
                                            class="block text-sm text-gray-900 break-all">{{ Auth::user()->username }}</span>
                                        <span
                                            class="block text-sm text-gray-500 truncate">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                                <hr>

                                 <!-- Menu -->
                                <div class="flex flex-col mt-4">
                                    <a href="{{ route('profile.perusahaan') }}"
                                        class="flex items-center gap-3 px-5 py-3 hover:bg-orange-50 hover:text-orange-500 text-gray-700 font-medium"
                                        id="profile-lank">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11 1C5.477 1 1 5.477 1 11C1 16.523 5.477 21 11 21C16.523 21 21 16.523 21 11C21 5.477 16.523 1 11 1Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M3.27344 17.346C3.27344 17.346 5.50244 14.5 11.0024 14.5C16.5024 14.5 18.7324 17.346 18.7324 17.346M11.0024 11C11.7981 11 12.5611 10.6839 13.1238 10.1213C13.6864 9.55871 14.0024 8.79565 14.0024 8C14.0024 7.20435 13.6864 6.44129 13.1238 5.87868C12.5611 5.31607 11.7981 5 11.0024 5C10.2068 5 9.44373 5.31607 8.88112 5.87868C8.31851 6.44129 8.00244 7.20435 8.00244 8C8.00244 8.79565 8.31851 9.55871 8.88112 10.1213C9.44373 10.6839 10.2068 11 11.0024 11Z"
                                                fill="currentColor" />
                                        </svg>
                                        Pengaturan & Profil Perusahaan
                                    </a>

                                    @if ($perusahaan->is_berlangganan == 1)
                                        <a href="{{ url('/perusahaan/dashboard?show=dashboard') }}"
                                            class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-500">
                                            <svg width="20" height="19" viewBox="0 0 15 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M14.8064 13.7977C14.8064 14.272 14.6455 14.6779 14.3236 15.0154C14.0017 15.3529 13.6143 15.5219 13.1613 15.5225H1.64516C1.19274 15.5225 0.805581 15.3534 0.483677 15.0154C0.161774 14.6773 0.000548387 14.2714 0 13.7977L0 1.72439C0 1.25008 0.161226 0.843896 0.483677 0.505842C0.806129 0.167789 1.19329 -0.000948906 1.64516 -0.00037384H13.1613C13.6137 -0.00037384 14.0011 0.168365 14.3236 0.505842C14.646 0.843321 14.807 1.2495 14.8064 1.72439V13.7977ZM13.1613 9.4858H8.22581V13.7977H13.1613V9.4858ZM13.1613 7.76104V1.72439H8.22581V7.76104H13.1613ZM6.58064 13.7977L6.58064 1.72439H1.64516L1.64516 13.7977H6.58064Z"
                                                    fill="currentColor" />
                                            </svg>

                                            Dashboard
                                        </a>
                                    @else
                                    @endif

                                    <button onclick="toggleModal()"
                                        class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-500">
                                        <svg width="20" height="22" viewBox="0 0 20 19" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2 19C1.45 19 0.979333 18.8043 0.588 18.413C0.196667 18.0217 0.000666667 17.5507 0 17V6C0 5.45 0.196 4.97933 0.588 4.588C0.98 4.19667 1.45067 4.00067 2 4H6V2C6 1.45 6.196 0.979333 6.588 0.588C6.98 0.196667 7.45067 0.000666667 8 0H12C12.55 0 13.021 0.196 13.413 0.588C13.805 0.98 14.0007 1.45067 14 2V4H18C18.55 4 19.021 4.196 19.413 4.588C19.805 4.98 20.0007 5.45067 20 6V17C20 17.55 19.8043 18.021 19.413 18.413C19.0217 18.805 18.5507 19.0007 18 19H2ZM2 17H18V6H2V17ZM8 4H12V2H8V4Z"
                                                fill="currentColor" />
                                        </svg>

                                        Koin Area Kerja
                                    </button>

                                    <a href="{{ route('perusahaan.kandidat.saya') }}"
                                        class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-500">
                                        <svg width="20" height="19" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.3333 1H2.66667C2.22464 1 1.80072 1.17559 1.48816 1.48816C1.17559 1.80072 1 2.22464 1 2.66667V19.3333C1 19.7754 1.17559 20.1993 1.48816 20.5118C1.80072 20.8244 2.22464 21 2.66667 21H19.3333C19.7754 21 20.1993 20.8244 20.5118 20.5118C20.8244 20.1993 21 19.7754 21 19.3333V2.66667C21 2.22464 20.8244 1.80072 20.5118 1.48816C20.1993 1.17559 19.7754 1 19.3333 1Z"
                                                stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M9.3342 14.8889L12.112 17.1111L16.5564 11.5556M5.44531 6H16.5564M5.44531 10.4444H9.88976"
                                                stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        Kandidat Saya
                                    </a>
                                </div>
                                </div>

                                <!-- Logout Button -->
                                <div class="px-5 py-4">
                                    <form action="{{ route('logout_perusahaan') }}" method="POST"
                                        class="flex justify-center mt-2">
                                        @csrf
                                        <button type="submit"
                                            class="px-10 py-1 bg-orange-500 text-white rounded-lg shadow-md hover:bg-orange-600 transition">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>


                    @endauth
                </div>
            </div>
    </header>

    {{-- isi halaman --}}
    @yield('content')
    {{-- NOTIF --}}
    @include('perusahaan.notif.modal_notif')
    @include('perusahaan.notif.modal_semua')


    <!-- ================= MODAL STEP 1 ================= -->
    @include('perusahaan.modal-topup.step1')
    <!-- ================= MODAL STEP 2 ================= -->
    @include('perusahaan.modal-topup.step2')
    <!-- ================= MODAL STEP 3 ================= -->
    @include('perusahaan.modal-topup.step3')



    <script>
        document.getElementById('fileinputperusahaan').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('pu').setAttribute('src', event.target.result);
                    document.getElementById('pa').setAttribute('src', event.target.result);
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
    {{-- TRX176466817743382688 --}}

    {{-- TOP UP --}}
    <script>
        //redirect
        document.getElementById('btnKonfirmasi').addEventListener('click', function() {
            if (!selectedKoin || !selectedBank) {
                alert("Silakan pilih paket dan metode pembayaran dulu.");
                return;
            }

            fetch("{{ route('catatan_cash.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        harga_pembayaran_id: document.querySelector(".paketCoin:checked").value,
                        daftar_bank_id: document.querySelector(".metodePembayaran:checked").value,
                    })
                })
                .then(async res => {
                    let data = {};

                    // paksa baca JSON kalau ada
                    try {
                        data = await res.json();
                    } catch (e) {}

                    /* ===============================
                        SWITCH ALERT VERIFIKASI
                    =============================== */
                    if (res.status === 403 && data.type === 'verification') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Akun Belum Terverifikasi',
                            text: data.message,
                            confirmButtonText: 'Mengerti',
                        });
                        return null; // STOP TOTAL
                    }

                    if (!res.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }

                    return data;
                })
                .then(data => { 
                    if (!data) return;

                    if (data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.message || 'Terjadi kesalahan',
                    });
                });
        });



        let selectedKoin = null;
        let selectedHarga = null;
        let selectedBank = null;

        function toggleModal() {
            closeAllModal();
            document.getElementById('modalStep1').classList.remove('hidden');
            document.getElementById('modalStep1').classList.add('flex');
            updateButtons();
        }

        function closeAllModal() {
            document.querySelectorAll('[id^="modalStep"]').forEach(m => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            });
        }

        function goToStep(step) {
            // ✅ Validasi sebelum pindah step
            if (step === 2 && !selectedKoin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih paket koin terlebih dahulu!',
                    confirmButtonColor: '#f97316' // warna tombol orange
                });
                return;
            }
            if (step === 3 && !selectedBank) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih metode pembayaran terlebih dahulu!',
                    confirmButtonColor: '#f97316'
                });
                return;
            }

            closeAllModal();
            let modal = document.getElementById('modalStep' + step);
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            updateButtons();

            // Step 3: update detail pembayaran
            if (step === 3) {
                const biayaAdmin = 2000;
                const totalBayar = (selectedHarga ?? 0) + biayaAdmin;

                // // 🔑 Buat No Transaksi random unik
                // const randomPart = Math.floor(Math.random() * 1000000);
                // const noTransaksi = "TRX" + Date.now() + randomPart;

                // document.getElementById('detailTransaksi').innerText = noTransaksi;
                document.getElementById('detailPengirim').innerText = "{{ Auth::user()->perusahaan->nama_perusahaan }}";
                document.getElementById('detailBank').innerText = selectedBank ?? '-';
                document.getElementById('detailWaktu').innerText = new Date().toLocaleString('id-ID');
                document.getElementById('detailHarga').innerText = "Rp. " + (selectedHarga ?? 0).toLocaleString('id-ID');
                document.getElementById('detailTotal').innerText = "Rp. " + totalBayar.toLocaleString('id-ID');
            }
        }


        // 🔑 Update status tombol (disable/enable)
        function updateButtons() {
            // Step 1: tombol konfirmasi paket
            const btnStep1 = document.getElementById('btnConfirmStep1');
            if (btnStep1) {
                btnStep1.disabled = !selectedKoin;
                btnStep1.classList.toggle('opacity-50', !selectedKoin);
                btnStep1.classList.toggle('cursor-not-allowed', !selectedKoin);
            }

            // Step 2: tombol selanjutnya metode pembayaran
            const btnStep2 = document.getElementById('btnNextStep2');
            if (btnStep2) {
                btnStep2.disabled = !selectedBank;
                btnStep2.classList.toggle('opacity-50', !selectedBank);
                btnStep2.classList.toggle('cursor-not-allowed', !selectedBank);
            }
        }

        // Tutup modal jika klik di luar area konten (backdrop) atau tekan tombol Escape
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[id^="modalStep"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeAllModal();
                    }
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeAllModal();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Step 1: Pilih Paket Koin
            document.querySelectorAll('.paketCoin').forEach(el => {
                el.addEventListener('change', function() {
                    selectedKoin = this.dataset.jumlah;
                    selectedHarga = parseInt(this.dataset.harga);

                    // Highlight kartu terpilih
                    document.querySelectorAll('.paketCoinWrapper').forEach(w => {
                        w.classList.remove('ring-2', 'ring-orange-500');
                    });
                    this.closest('.paketCoinWrapper').classList.add('ring-2', 'ring-orange-500');

                    updateButtons();
                });
            });

            // Step 2: Pilih Metode Pembayaran
            document.querySelectorAll('.metodePembayaran').forEach(el => {
                el.addEventListener('change', function() {
                    selectedBank = this.dataset.bank;

                    // Highlight bank terpilih
                    document.querySelectorAll('.pembayaranWrapper').forEach(w => {
                        w.classList.remove('ring-2', 'ring-orange-500');
                    });
                    this.closest('.pembayaranWrapper').classList.add('ring-2', 'ring-orange-500');

                    updateButtons();
                });
            });
        });
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

</body>

</html>
