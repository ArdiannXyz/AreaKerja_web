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
        html {
            height: 100%;
            background-color: #00509d;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #00509d;
            display: flex;
            flex-direction: column;
        }

        .page-content-wrapper {
            flex: 1 0 auto;
            background-color: #ffffff;
            width: 100%;
            display: flex;
            flex-direction: column;
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

        /* LIST â€” supaya BULLET hitam muncul */
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

    <script>
        // Global Notification Functions
        window.markAsRead = async function(url, el) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });
                let data = await res.json();
                if (data.success && el) {
                    el.classList.remove("bg-white", "font-medium", "text-gray-900");
                    el.classList.add("bg-gray-50/80", "text-gray-600");
                    const badges = document.querySelectorAll('#notif-badge, .notif-badge');
                    badges.forEach(badge => {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove();
                        }
                    });
                }
            } catch (error) {
                console.error("markAsRead error:", error);
            }
        };

        window.hapusNotif = async function(id, btnEl) {
            const item = btnEl ? btnEl.closest('.notif-item') : document.querySelector(`.notif-item[data-id="${id}"]`);
            if (item) {
                item.style.transition = 'all 0.25s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateX(20px)';
                setTimeout(() => item.remove(), 250);
            }

            try {
                let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);
                let res = await fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });
                let data = await res.json();
                if (!data.success && item) {
                    item.style.opacity = '1';
                    item.style.transform = 'none';
                }
            } catch (err) {
                console.error(err);
                if (item) {
                    item.style.opacity = '1';
                    item.style.transform = 'none';
                }
            }
        };

        window.bacaSemuaNotif = async function() {
            const hasItems = document.querySelectorAll('.notif-item').length > 0;
            const hasBadge = document.querySelector('#notif-badge, .notif-badge') !== null;
            const hasUnread = document.querySelectorAll('.notif-item.bg-white, .notif-item.font-medium, .notif-item:not(.bg-gray-50\\/80):not(.bg-gray-200)').length > 0;

            if (!hasItems || (!hasBadge && !hasUnread)) {
                Swal.fire({
                    title: '<span class="text-xs font-semibold text-gray-700">Tidak ada notifikasi baru</span>',
                    icon: 'info',
                    iconColor: '#00509d',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'w-[280px] p-4 rounded-2xl shadow-xl',
                        icon: 'scale-75 my-1'
                    }
                });
                return;
            }

            try {
                let res = await fetch("{{ route('notifikasi.bacaSemua') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });
                let data = await res.json();
                if (data.success) {
                    document.querySelectorAll('.notif-item').forEach(item => {
                        item.classList.remove('bg-white', 'font-medium', 'text-gray-900');
                        item.classList.add('bg-gray-50/80', 'text-gray-600');
                    });
                    document.querySelectorAll('#notif-badge, .notif-badge').forEach(b => b.remove());
                    Swal.fire({
                        title: '<span class="text-xs font-bold text-gray-800">Semua Ditandai Dibaca</span>',
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'w-[260px] p-3 rounded-2xl shadow-lg',
                            icon: 'scale-75 my-1'
                        }
                    });
                }
            } catch (err) {
                console.error("bacaSemua error:", err);
            }
        };

        window.hapusSemuaNotif = function() {
            const items = document.querySelectorAll('.notif-item');
            if (items.length === 0) {
                Swal.fire({
                    title: '<span class="text-xs font-semibold text-gray-700">Tidak ada notifikasi untuk dihapus</span>',
                    icon: 'info',
                    iconColor: '#00509d',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'w-[280px] p-4 rounded-2xl shadow-lg',
                        icon: 'scale-75 my-1'
                    }
                });
                return;
            }

            Swal.fire({
                title: '<span class="text-sm font-bold text-gray-800">Hapus Semua Notifikasi?</span>',
                text: 'Semua notifikasi Anda akan dibersihkan.',
                icon: 'warning',
                iconColor: '#ef4444',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'w-[300px] p-4 rounded-2xl shadow-xl',
                    htmlContainer: 'text-xs text-gray-500 my-2',
                    confirmButton: 'text-xs px-3.5 py-1.5 rounded-lg font-medium',
                    cancelButton: 'text-xs px-3.5 py-1.5 rounded-lg font-medium',
                    icon: 'scale-75 my-1'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Accept": "application/json"
                            }
                        });
                        let data = await res.json();
                        if (data.success) {
                            document.querySelectorAll('.notif-item').forEach(e => e.remove());
                            document.querySelectorAll('#notif-badge, .notif-badge').forEach(b => b.remove());
                            Swal.fire({
                                title: '<span class="text-xs font-bold text-gray-800">Berhasil Dihapus</span>',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'w-[260px] p-3 rounded-2xl shadow-lg',
                                    icon: 'scale-75 my-1'
                                }
                            });
                        }
                    } catch (err) {
                        console.error(err);
                    }
                }
            });
        };

        window.hapusSemuaBacaNotif = function() {
            const readItems = document.querySelectorAll('.notif-item.bg-gray-100, .notif-item.bg-gray-200, .notif-item.bg-gray-50\\/80, .notif-item.bg-gray-50\\/70');
            if (readItems.length === 0) {
                Swal.fire({
                    title: '<span class="text-xs font-semibold text-gray-700">Tidak ada notifikasi yang sudah dibaca</span>',
                    icon: 'info',
                    iconColor: '#00509d',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'w-[280px] p-4 rounded-2xl shadow-lg',
                        icon: 'scale-75 my-1'
                    }
                });
                return;
            }

            Swal.fire({
                title: '<span class="text-sm font-bold text-gray-800">Hapus Notifikasi Dibaca?</span>',
                text: 'Notifikasi yang sudah dibaca akan dibersihkan.',
                icon: 'warning',
                iconColor: '#ef4444',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'w-[300px] p-4 rounded-2xl shadow-xl',
                    htmlContainer: 'text-xs text-gray-500 my-2',
                    confirmButton: 'text-xs px-3.5 py-1.5 rounded-lg font-medium',
                    cancelButton: 'text-xs px-3.5 py-1.5 rounded-lg font-medium',
                    icon: 'scale-75 my-1'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Accept": "application/json"
                            }
                        });
                        let data = await res.json();
                        if (data.success) {
                            document.querySelectorAll('.notif-item.bg-gray-100, .notif-item.bg-gray-200, .notif-item.bg-gray-50\\/80, .notif-item.bg-gray-50\\/70')
                                .forEach(e => e.remove());
                            Swal.fire({
                                title: '<span class="text-xs font-bold text-gray-800">Berhasil Dihapus</span>',
                                icon: 'success',
                                timer: 1200,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'w-[260px] p-3 rounded-2xl shadow-lg',
                                    icon: 'scale-75 my-1'
                                }
                            });
                        }
                    } catch (err) {
                        console.error(err);
                    }
                }
            });
        };

        function notifHandler() {
            return {
                hapus(id, btnEl) { return window.hapusNotif(id, btnEl); },
                hapusSemua() { return window.hapusSemuaNotif(); },
                bacaSemua() { return window.bacaSemuaNotif(); },
                hapusSemuaBaca() { return window.hapusSemuaBacaNotif(); }
            };
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('notifHandler', notifHandler);
        });
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>

</head>

<body x-data="{ openNotif: false, openAllNotif: false, openMenu: false }">
    {{-- navbar --}}
    <header class="bg-white text-slate-800 border-b border-slate-100 shadow-sm fixed top-0 left-0 w-full z-50 transition-all duration-200">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10 h-20 flex items-center justify-between">

            <!-- Logo & Hamburger (Kiri) -->
            <div class="flex items-center gap-3 shrink-0">
                <!-- Hamburger Button (HANYA HP < 768px) -->
                <button @click="openMenu = !openMenu" type="button" class="flex md:hidden p-1.5 rounded-lg text-[#00509d] hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-[#00509d]" aria-label="Toggle Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('perusahaan.dashboard') }}" class="flex items-center gap-2 sm:gap-2.5">
                    <img src="{{ asset('images/logo_area_kerja_biru.png') }}" alt="Areakerja Logo" class="h-7 sm:h-8 lg:h-9 object-contain">
                    <span class="font-bold text-base sm:text-lg lg:text-[21px] text-[#00509d] tracking-tight">areakerja.com</span>
                </a>
            </div>

            <!-- Menu Desktop & Laptop (Tengah) - Tampil di layar >= 768px -->
            <nav class="hidden md:flex items-center font-semibold text-xs sm:text-sm lg:text-[15px] text-[#00509d] gap-4 sm:gap-6 lg:gap-7 xl:gap-10 ml-6 sm:ml-8 lg:ml-12 xl:ml-16 mr-auto">
                <a href="{{ route('perusahaan.dashboard') }}"
                    class="hover:text-[#003d7a] transition-colors whitespace-nowrap {{ request()->routeIs('perusahaan.dashboard') ? 'font-bold underline underline-offset-8 decoration-2' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('perusahaan.berlangganan') }}"
                    class="hover:text-[#003d7a] transition-colors whitespace-nowrap {{ request()->routeIs('perusahaan.berlangganan*') ? 'font-bold underline underline-offset-8 decoration-2' : '' }}">
                    Berlangganan
                </a>
                <a href="{{ route('talent-hunter.index') }}"
                    class="hover:text-[#003d7a] transition-colors whitespace-nowrap {{ request()->routeIs('talent-hunter.index*') ? 'font-bold underline underline-offset-8 decoration-2' : '' }}">
                    Talent Hunter
                </a>
                <a href="{{ route('perusahaan.kandidat.ak') }}"
                    class="hover:text-[#003d7a] transition-colors whitespace-nowrap {{ request()->routeIs('perusahaan.kandidat.ak*') ? 'font-bold underline underline-offset-8 decoration-2' : '' }}">
                    Kandidat
                </a>
                <a href="{{ route('paket.form') }}"
                    class="hover:text-[#003d7a] transition-colors whitespace-nowrap {{ request()->routeIs('paket.form*') ? 'font-bold underline underline-offset-8 decoration-2' : '' }}">
                    Pasang Lowongan
                </a>
                <a href="{{ route('perusahaan.event.index') }}"
                    class="hover:text-[#003d7a] transition-colors whitespace-nowrap {{ request()->routeIs('perusahaan.event.index*') ? 'font-bold underline underline-offset-8 decoration-2' : '' }}">
                    Event
                </a>
            </nav>

            <!-- Aksi (Kanan) -->
            <div class="flex items-center gap-3 sm:gap-4">
                {{-- Notifikasi --}}
                <button @click="openNotif = true" class="relative p-2 rounded-full hover:bg-blue-50 transition text-[#00509d]">
                    <!-- Icon Lonceng -->
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23.4955 17.1131C23.3918 17.006 23.29 16.8989 23.1901 16.7955C21.8162 15.3699 20.9851 14.5096 20.9851 10.474C20.9851 8.38475 20.4024 6.67047 19.254 5.38475C18.4072 4.43493 17.2626 3.7144 15.7539 3.1819C15.7344 3.17263 15.7171 3.16048 15.7027 3.146C15.16 1.58708 13.675 0.542969 12.0002 0.542969C10.3253 0.542969 8.84094 1.58708 8.29828 3.1444C8.28379 3.15834 8.2667 3.17011 8.24769 3.17922C4.72691 4.42261 3.01586 6.80815 3.01586 10.4724C3.01586 14.5096 2.18593 15.3699 0.810843 16.7939C0.710927 16.8973 0.609138 17.0023 0.505476 17.1115C0.237702 17.3886 0.0680456 17.7256 0.0165842 18.0828C-0.0348772 18.4399 0.0340108 18.8023 0.215096 19.1269C0.600396 19.8233 1.42158 20.2556 2.35891 20.2556H21.6483C22.5812 20.2556 23.3968 19.8239 23.7833 19.1306C23.9652 18.8059 24.0347 18.4433 23.9837 18.0857C23.9327 17.7282 23.7633 17.3906 23.4955 17.1131ZM12.0002 24.543C12.9025 24.5423 13.7879 24.3322 14.5623 23.9349C15.3368 23.5375 15.9714 22.9677 16.3989 22.286C16.4191 22.2533 16.429 22.2167 16.4278 22.1798C16.4266 22.1429 16.4143 22.1068 16.392 22.0752C16.3698 22.0435 16.3384 22.0173 16.3008 21.9992C16.2633 21.981 16.221 21.9715 16.1779 21.9715H7.82368C7.78054 21.9714 7.7381 21.9809 7.70049 21.999C7.66288 22.0171 7.63138 22.0433 7.60906 22.0749C7.58674 22.1066 7.57435 22.1427 7.57311 22.1797C7.57188 22.2167 7.58182 22.2533 7.60199 22.286C8.02946 22.9677 8.664 23.5374 9.43832 23.9347C10.2126 24.3321 11.0979 24.5422 12.0002 24.543Z"
                            fill="#00509d" />
                    </svg>

                    <!-- Badge angka merah -->
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="notif-badge absolute -top-0.5 -right-0.5 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                @guest
                    <a href="{{ route('login') }}"
                        class="bg-[#00509d] text-white hover:bg-[#003d7a] font-semibold px-6 sm:px-8 lg:px-10 py-2 sm:py-2.5 lg:py-3 rounded-2xl transition-all text-sm sm:text-base text-center shadow-sm hover:shadow whitespace-nowrap">
                        Masuk
                    </a>
                @endguest

                {{-- Jika sudah login tampilkan dropdown (Foto Profil) --}}
                @auth
                    <div class="flex items-center space-x-3">
                        <button id="ntap" type="button" class="flex text-sm rounded-full focus:ring-4 focus:ring-gray-300"
                            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                            data-dropdown-placement="bottom">
                            <span class="sr-only">Open user menu</span>
                            @if (Auth::user()->role == 'perusahaan' && Auth::user()->perusahaan?->img_profile)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                    src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}"
                                    alt="{{ Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username }}">
                            @else
                                <img id="pu" class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->role == 'perusahaan' ? (Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username) : Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="{{ Auth::user()->username }}">
                            @endif
                        </button>

                        <!-- Dropdown menu -->
                        <div class="z-50 min-w-[220px] max-w-[300px] hidden my-4 text-base bg-white text-gray-800 divide-y divide-gray-100 rounded-xl shadow-2xl border border-slate-100"
                            id="user-dropdown">
                            <!-- Header Info -->
                            <div class="px-4 py-3">
                                <span class="block text-sm font-bold text-gray-900 break-all">{{ Auth::user()->username }}</span>
                                <span class="block text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</span>
                            </div>

                            <!-- Menu List -->
                            <ul class="py-2 text-gray-700 text-sm font-medium" aria-labelledby="user-menu-button">
                                <li>
                                    <a href="{{ route('profile.perusahaan') }}"
                                        class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition"
                                        id="profile-lank">
                                        <i class="ph ph-user mr-2 text-[#00509d] text-lg"></i>
                                        Pengaturan & Profil Perusahaan
                                    </a>
                                </li>

                                @if ($perusahaan->is_berlangganan == 1)
                                    <li>
                                        <a href="{{ url('/perusahaan/dashboard?show=dashboard') }}"
                                            class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition">
                                            <i class="ph ph-squares-four mr-2 text-[#00509d] text-lg"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <button type="button" onclick="toggleModal()"
                                        class="w-full flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition text-left">
                                        <i class="ph ph-coins mr-2 text-[#00509d] text-lg"></i>
                                        Koin Area Kerja
                                    </button>
                                </li>

                                <li>
                                    <a href="{{ route('perusahaan.kandidat.saya') }}"
                                        class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition">
                                        <i class="ph ph-users mr-2 text-[#00509d] text-lg"></i>
                                        Kandidat Saya
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('syarat.ketentuan') }}"
                                        class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition">
                                        <i class="ph ph-file-text mr-2 text-[#00509d] text-lg"></i>
                                        Syarat dan Ketentuan
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('verifikasi_pelamar') }}"
                                        class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition">
                                        <i class="ph ph-lock-key mr-2 text-[#00509d] text-lg"></i>
                                        Ganti Password
                                    </a>
                                </li>

                                <li class="px-4 pt-2 pb-1">
                                    <form action="{{ route('logout_perusahaan') }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-2 bg-[#00509d] text-white font-bold rounded-lg shadow-sm hover:bg-[#003d7a] transition text-sm text-center">
                                            Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endauth

                {{-- Menu Mobile Dropdown (Hanya HP < 768px) --}}
                <div x-show="openMenu" x-transition x-cloak @click.outside="openMenu = false"
                    class="flex flex-col absolute top-full left-0 w-full bg-white text-slate-800 border-t border-slate-100 py-4 shadow-xl z-40 md:hidden">

                    <a href="{{ route('perusahaan.dashboard') }}"
                        class="px-6 py-3 text-slate-700 hover:bg-blue-50 hover:text-[#003d7a] transition duration-300 font-medium {{ request()->routeIs('perusahaan.dashboard') ? 'bg-blue-50 text-[#00509d] font-bold' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('perusahaan.berlangganan') }}"
                        class="px-6 py-3 text-slate-700 hover:bg-blue-50 hover:text-[#003d7a] transition duration-300 font-medium {{ request()->routeIs('perusahaan.berlangganan*') ? 'bg-blue-50 text-[#00509d] font-bold' : '' }}">
                        Berlangganan
                    </a>
                    <a href="{{ route('talent-hunter.index') }}"
                        class="px-6 py-3 text-slate-700 hover:bg-blue-50 hover:text-[#003d7a] transition duration-300 font-medium {{ request()->routeIs('talent-hunter.index*') ? 'bg-blue-50 text-[#00509d] font-bold' : '' }}">
                        Talent Hunter
                    </a>
                    <a href="{{ route('perusahaan.kandidat.ak') }}"
                        class="px-6 py-3 text-slate-700 hover:bg-blue-50 hover:text-[#003d7a] transition duration-300 font-medium {{ request()->routeIs('perusahaan.kandidat.ak*') ? 'bg-blue-50 text-[#00509d] font-bold' : '' }}">
                        Kandidat
                    </a>
                    <a href="{{ route('paket.form') }}"
                        class="px-6 py-3 text-slate-700 hover:bg-blue-50 hover:text-[#003d7a] transition duration-300 font-medium {{ request()->routeIs('paket.form*') ? 'bg-blue-50 text-[#00509d] font-bold' : '' }}">
                        Pasang Lowongan
                    </a>
                    <a href="{{ route('perusahaan.event.index') }}"
                        class="px-6 py-3 text-slate-700 hover:bg-blue-50 hover:text-[#003d7a] transition duration-300 font-medium {{ request()->routeIs('perusahaan.event.index*') ? 'bg-blue-50 text-[#00509d] font-bold' : '' }}">
                        Event
                    </a>

                    @guest
                        <div class="px-6 pt-3">
                            <a href="{{ route('login') }}"
                                class="block w-full text-center bg-[#00509d] text-white hover:bg-[#003d7a] py-3 rounded-2xl font-semibold transition">
                                Masuk
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    @if (auth()->check() && auth()->user()->perusahaan?->verification_status !== 'approved')
        <div class="fixed top-24 left-0 right-0 z-40 flex justify-center px-4 pointer-events-none">
            <div id="unverified-alert" x-data="{ show: true }" x-show="show" x-transition
                class="pointer-events-auto bg-amber-50 border border-amber-300 text-amber-900 px-5 py-3.5 rounded-2xl w-full max-w-2xl shadow-lg flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="ph ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-amber-950">Perhatian! Akun Dalam Proses Verifikasi</div>
                        <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">
                            Akun perusahaan Anda sedang dalam proses verifikasi. Harap tunggu hingga admin menyetujui data legalitas perusahaan Anda.
                        </p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-amber-500 hover:text-amber-700 p-1 rounded-lg hover:bg-amber-100 transition shrink-0 cursor-pointer" title="Tutup">
                    <i class="ph ph-x text-base font-bold"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- isi halaman --}}
    <main class="page-content-wrapper">
        @yield('content')
    </main>
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
            // âœ… Validasi sebelum pindah step
            if (step === 2 && !selectedKoin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih paket koin terlebih dahulu!',
                    confirmButtonColor: '#00509d' // warna tombol orange
                });
                return;
            }
            if (step === 3 && !selectedBank) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih metode pembayaran terlebih dahulu!',
                    confirmButtonColor: '#00509d'
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

                // // ðŸ”‘ Buat No Transaksi random unik
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


        // ðŸ”‘ Update status tombol (disable/enable)
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
                        w.classList.remove('ring-2', 'ring-[#00509d]');
                    });
                    this.closest('.paketCoinWrapper').classList.add('ring-2', 'ring-[#00509d]');

                    updateButtons();
                });
            });

            // Step 2: Pilih Metode Pembayaran
            document.querySelectorAll('.metodePembayaran').forEach(el => {
                el.addEventListener('change', function() {
                    selectedBank = this.dataset.bank;

                    // Highlight bank terpilih
                    document.querySelectorAll('.pembayaranWrapper').forEach(w => {
                        w.classList.remove('ring-2', 'ring-[#00509d]');
                    });
                    this.closest('.pembayaranWrapper').classList.add('ring-2', 'ring-[#00509d]');

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

