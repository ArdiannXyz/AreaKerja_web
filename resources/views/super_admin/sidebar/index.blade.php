<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="icon" sizes="512x512" type="image/png" href="{{ asset('images/logoarea.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
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

</head>

<body>
    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 hover:text-white rounded-lg md:hidden hover:bg-[#00509d] focus:outline-none focus:ring-2 focus:ring-[#00509d] dark:text-gray-400 dark:hover:bg-[#00509d] dark:focus:ring-[#00509d] transition duration-300">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <div class="flex">
        <!-- Sidebar -->
        <aside id="logo-sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 overflow-y-auto"
            aria-label="Sidebar">
            <div class="min-h-screen w-64 bg-[#00509d] text-white overflow-y-auto">
                <!-- Logo -->
                <div class="px-4 py-2">
                    <div class="inline-flex items-center gap-1 border-b-2 border-blue-300">
                        <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="logo" class="w-14 h-14">
                        <p class="text-xl font-semibold">areakerja.com</p>
                    </div>
                </div>

                <!-- Menu -->
                <nav class="flex-1 px-4 text-sm">
                    <div class="font-bold mb-3 ml-3">Umum</div>
                    <div
                        class="{{ request()->is('super_admin/dashboard') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.dashboard') }}"
                            class="flex font-semibold items-center mb-4 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="15" height="16" viewBox="0 0 15 16" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.8064 13.7987C14.8064 14.273 14.6455 14.6789 14.3236 15.0164C14.0017 15.3538 13.6143 15.5229 13.1613 15.5234H1.64516C1.19274 15.5234 0.805581 15.3544 0.483677 15.0164C0.161774 14.6783 0.000548387 14.2724 0 13.7987L0 1.72536C0 1.25105 0.161226 0.844872 0.483677 0.506819C0.806129 0.168766 1.19329 2.76566e-05 1.64516 0.000602722H13.1613C13.6137 0.000602722 14.0011 0.169341 14.3236 0.506819C14.646 0.844297 14.807 1.25048 14.8064 1.72536V13.7987ZM13.1613 9.48678H8.22581V13.7987H13.1613V9.48678ZM13.1613 7.76202V1.72536H8.22581V7.76202H13.1613ZM6.58064 13.7987L6.58064 1.72536H1.64516L1.64516 13.7987H6.58064Z"
                                    fill="currentColor" />
                            </svg>
                            Dashboard
                        </a>
                    </div>

                    <div class="font-bold ml-3 mb-3">Super Admin</div>
                    <div
                        class="{{ request()->is('super_admin/pelamar') || request()->is('super_admin/pelamar/tambah/kandidat') || request()->is('super_admin/kandidat/1') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.pelamar') }}"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.9974 2.1665C5.3974 2.1665 1.66406 5.89984 1.66406 10.4998C1.66406 15.0998 5.3974 18.8332 9.9974 18.8332C14.5974 18.8332 18.3307 15.0998 18.3307 10.4998C18.3307 5.89984 14.5974 2.1665 9.9974 2.1665ZM6.1224 15.9165C7.21406 15.1332 8.5474 14.6665 9.9974 14.6665C11.4474 14.6665 12.7807 15.1332 13.8724 15.9165C12.7807 16.6998 11.4474 17.1665 9.9974 17.1665C8.5474 17.1665 7.21406 16.6998 6.1224 15.9165ZM15.1141 14.7665C13.6544 13.6213 11.8527 12.9988 9.9974 12.9988C8.14208 12.9988 6.3404 13.6213 4.88073 14.7665C3.87816 13.5709 3.32931 12.0601 3.33073 10.4998C3.33073 6.8165 6.31406 3.83317 9.9974 3.83317C13.6807 3.83317 16.6641 6.8165 16.6641 10.4998C16.6641 12.1248 16.0807 13.6082 15.1141 14.7665Z"
                                    fill="currentColor" />
                                <path
                                    d="M10.0026 5.5C8.39427 5.5 7.08594 6.80833 7.08594 8.41667C7.08594 10.025 8.39427 11.3333 10.0026 11.3333C11.6109 11.3333 12.9193 10.025 12.9193 8.41667C12.9193 6.80833 11.6109 5.5 10.0026 5.5ZM10.0026 9.66667C9.31094 9.66667 8.7526 9.10833 8.7526 8.41667C8.7526 7.725 9.31094 7.16667 10.0026 7.16667C10.6943 7.16667 11.2526 7.725 11.2526 8.41667C11.2526 9.10833 10.6943 9.66667 10.0026 9.66667Z"
                                    fill="currentColor" />
                            </svg>
                            Data Pelamar
                        </a>
                    </div>
                    <div
                        class="{{ request()->is('super_admin/perusahaan') || request()->is('super_admin/tambah/perusahaan') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href={{ route('superadmin.perusahaan') }}
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4 7.3C4 5.0376 4 3.9056 4.65925 3.2032C5.31775 2.5 6.379 2.5 8.5 2.5H11.5C13.621 2.5 14.6823 2.5 15.3408 3.2032C16 3.9056 16 5.0376 16 7.3V13.7C16 15.9624 16 17.0944 15.3408 17.7968C14.6823 18.5 13.621 18.5 11.5 18.5H8.5C6.379 18.5 5.31775 18.5 4.65925 17.7968C4 17.0944 4 15.9624 4 13.7V7.3Z"
                                    stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path
                                    d="M15.9235 13.7002H6.9235C6.226 13.7002 5.87725 13.7002 5.59075 13.7818C5.20923 13.8909 4.86137 14.1053 4.58213 14.4032C4.3029 14.7012 4.10212 15.0724 4 15.4794"
                                    stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path
                                    d="M7 6.5H13M7 9.3H10.75M10.75 13.7V16.524C10.75 16.7448 10.75 16.8552 10.6787 16.9C10.6075 16.9448 10.5107 16.8952 10.3157 16.796L9.38425 16.324C9.31825 16.292 9.28525 16.2744 9.25 16.2744C9.21475 16.2744 9.18175 16.2912 9.11575 16.3248L8.18425 16.7968C7.98925 16.8952 7.89175 16.9448 7.82125 16.9C7.75 16.8552 7.75 16.7448 7.75 16.524V14.06"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none" />
                            </svg>
                            Data Perusahaan
                        </a>
                    </div>
                    <div
                        class="{{ request()->is('super_admin/paket/harga') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.paket-harga') }}"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" fill="none"
                                viewBox="0 0 20 21" stroke="currentColor" stroke-width="2" class="text-inherit">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 1.5H5.5C5.1 1.5 4.72 1.687 4.439 2.021C4.158 2.354 4 2.806 4 3.278V15.722C4 16.194 4.158 16.646 4.439 16.979C4.721 17.313 5.102 17.5 5.5 17.5H14.5C14.898 17.5 15.279 17.313 15.561 16.979C15.842 16.646 16 16.194 16 15.722V3.278C16 2.806 15.842 2.354 15.561 2.021C15.279 1.687 14.898 1.5 14.5 1.5H11.5M7 1.5V9.5L9.25 6.833 11.5 9.5V1.5H7Z" />
                            </svg>
                            Finance
                        </a>
                    </div>
                    <div
                        class="{{ request()->is('super_admin/freeze') || request()->is('super_admin/detail/freeze') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.freeze') }}"
                            class="flex font-semibold items-center mb-1 gap-3 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="14" height="17" viewBox="0 0 14 17" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.91667 0.166504C3.35869 0.166504 3.78262 0.342099 4.09518 0.654659C4.40774 0.96722 4.58333 1.39114 4.58333 1.83317C4.58333 2.2752 4.40774 2.69912 4.09518 3.01168C3.78262 3.32424 3.35869 3.49984 2.91667 3.49984C2.47464 3.49984 2.05072 3.32424 1.73816 3.01168C1.42559 2.69912 1.25 2.2752 1.25 1.83317C1.25 1.39114 1.42559 0.96722 1.73816 0.654659C2.05072 0.342099 2.47464 0.166504 2.91667 0.166504ZM1.66667 4.33317H4.16667C4.60869 4.33317 5.03262 4.50877 5.34518 4.82133C5.65774 5.13389 5.83333 5.55781 5.83333 5.99984V10.5832H4.58333V16.8332H1.25V10.5832H0V5.99984C0 5.55781 0.175595 5.13389 0.488155 4.82133C0.800716 4.50877 1.22464 4.33317 1.66667 4.33317ZM10.4167 0.166504C10.8587 0.166504 11.2826 0.342099 11.5952 0.654659C11.9077 0.96722 12.0833 1.39114 12.0833 1.83317C12.0833 2.2752 11.9077 2.69912 11.5952 3.01168C11.2826 3.32424 10.8587 3.49984 10.4167 3.49984C9.97464 3.49984 9.55072 3.32424 9.23816 3.01168C8.9256 2.69912 8.75 2.2752 8.75 1.83317C8.75 1.39114 8.9256 0.96722 9.23816 0.654659C9.55072 0.342099 9.97464 0.166504 10.4167 0.166504ZM9.16667 4.33317H11.6667C12.1087 4.33317 12.5326 4.50877 12.8452 4.82133C13.1577 5.13389 13.3333 5.55781 13.3333 5.99984V10.5832H12.0833V16.8332H8.75V10.5832H7.5V5.99984C7.5 5.55781 7.67559 5.13389 7.98816 4.82133C8.30072 4.50877 8.72464 4.33317 9.16667 4.33317Z"
                                    fill="currentColor" />
                            </svg>
                            Akun Freeze
                        </a>
                    </div>
                    <div
                        class="{{ request()->is('super_admin/tips/kerja') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="/super_admin/tips/kerja"
                            class="flex font-semibold items-center mb-1 gap-3 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.0725 0.499911L9.17833 0.60081L11.6783 3.18795H13.8333C14.2538 3.18781 14.6588 3.35215 14.9671 3.64803C15.2754 3.9439 15.4643 4.34944 15.4958 4.78335L15.5 4.91271V13.5365C15.4999 13.9715 15.341 14.3904 15.0551 14.7093C14.7692 15.0282 14.3775 15.2235 13.9583 15.2561L13.8333 15.2613H2.16667C1.74619 15.2614 1.34119 15.0971 1.03288 14.8012C0.724559 14.5053 0.535703 14.0998 0.504167 13.6659L0.5 13.5365V4.91271C0.499867 4.47757 0.658672 4.05846 0.944581 3.7394C1.23049 3.42034 1.62237 3.2249 2.04167 3.19226L2.16667 3.18795H4.32167L6.82167 0.60081C7.09945 0.313377 7.46641 0.136718 7.8578 0.102005C8.24918 0.067293 8.63984 0.176759 8.96083 0.411086L9.0725 0.499911ZM13.8333 4.91271H2.16667V13.5365H13.8333V4.91271ZM6.33333 10.087C6.55435 10.087 6.76631 10.1778 6.92259 10.3396C7.07887 10.5013 7.16667 10.7207 7.16667 10.9494C7.16667 11.1781 7.07887 11.3974 6.92259 11.5592C6.76631 11.7209 6.55435 11.8117 6.33333 11.8117H4.66667C4.44565 11.8117 4.23369 11.7209 4.07741 11.5592C3.92113 11.3974 3.83333 11.1781 3.83333 10.9494C3.83333 10.7207 3.92113 10.5013 4.07741 10.3396C4.23369 10.1778 4.44565 10.087 4.66667 10.087H6.33333ZM9.66667 6.63747C9.87907 6.63771 10.0834 6.72188 10.2378 6.87277C10.3923 7.02366 10.4852 7.22988 10.4976 7.44931C10.5101 7.66873 10.4411 7.8848 10.3048 8.05335C10.1685 8.22191 9.97508 8.33023 9.76417 8.35619L9.66667 8.36223H4.66667C4.45427 8.36198 4.24997 8.27782 4.09553 8.12693C3.94108 7.97604 3.84814 7.76981 3.83569 7.55039C3.82324 7.33096 3.89223 7.1149 4.02855 6.94635C4.16488 6.77779 4.35825 6.66947 4.56917 6.64351L4.66667 6.63747H9.66667ZM8 1.82021L6.67833 3.18795H9.32167L8 1.82021Z"
                                    fill="currentColor" />
                            </svg>
                            Tips Kerja
                        </a>
                    </div>
                    <div
                        class="{{ request()->is('super_admin/event') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="/super_admin/event"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.6129 9.30003H3.22581V14.1H2.25806C2.0014 14.1 1.75525 14.2264 1.57377 14.4515C1.39228 14.6765 1.29032 14.9818 1.29032 15.3C1.29032 15.6183 1.39228 15.9235 1.57377 16.1485C1.75525 16.3736 2.0014 16.5 2.25806 16.5H17.7419C17.9986 16.5 18.2447 16.3736 18.4262 16.1485C18.6077 15.9235 18.7097 15.6183 18.7097 15.3C18.7097 14.9818 18.6077 14.6765 18.4262 14.4515C18.2447 14.2264 17.9986 14.1 17.7419 14.1H16.7742V9.30003H18.3871C18.5978 9.30006 18.8028 9.21479 18.9709 9.0572C19.1389 8.89961 19.2609 8.6783 19.3183 8.42689C19.3757 8.17548 19.3654 7.90771 19.2889 7.66426C19.2123 7.42081 19.0738 7.21498 18.8944 7.07804L10.5073 0.678061C10.3547 0.561641 10.1791 0.5 10 0.5C9.82088 0.5 9.64528 0.561641 9.49274 0.678061L1.10565 7.07804C0.926184 7.21498 0.787671 7.42081 0.711149 7.66426C0.634626 7.90771 0.624276 8.17548 0.68167 8.42689C0.739065 8.6783 0.861067 8.89961 1.02914 9.0572C1.19722 9.21479 1.40219 9.30006 1.6129 9.30003ZM5.16129 9.30003H7.09677V14.1H5.16129V9.30003ZM10.9677 9.30003V14.1H9.03226V9.30003H10.9677ZM14.8387 14.1H12.9032V9.30003H14.8387V14.1ZM10 3.10905L14.9677 6.90004H5.03226L10 3.10905ZM20 19.3C20 19.6183 19.898 19.9235 19.7166 20.1485C19.5351 20.3736 19.2889 20.5 19.0323 20.5H0.967742C0.711081 20.5 0.464932 20.3736 0.283445 20.1485C0.101958 19.9235 0 19.6183 0 19.3C0 18.9817 0.101958 18.6765 0.283445 18.4515C0.464932 18.2264 0.711081 18.1 0.967742 18.1H19.0323C19.2889 18.1 19.5351 18.2264 19.7166 18.4515C19.898 18.6765 20 18.9817 20 19.3Z"
                                    fill="currentColor" />
                            </svg>
                            Event
                        </a>
                    </div>
                    <div class="font-bold mb-4 ml-3 mt-5">Manajemen Lowongan</div>
                    <div
                        class="{{ request()->is('super_admin/manajemen/lowongan/gold') ||  request()->is('super_admin/manajemen/lowongan/silver') ||  request()->is('super_admin/manajemen/lowongan/bronze') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.manajemen.lowongan.gold') }}"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-1 transition duration-300">
                            <i class="ph ph-wrench text-2xl"></i>
                            Setting Lowongan
                        </a>
                    </div>
                    <div class="font-bold mb-4 ml-3 mt-5">Manajemen Akun</div>
                    <div
                        class="{{ request()->is('super_admin/add/user') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.add.user') }}"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.9094 9.28404C14.9423 9.02804 14.967 8.77204 14.967 8.50004C14.967 8.22804 14.9423 7.97204 14.9094 7.71604L16.6448 6.39604C16.8011 6.27604 16.8422 6.06004 16.7435 5.88404L15.0986 3.11604C15.0501 3.0335 14.973 2.97045 14.8811 2.9382C14.7891 2.90595 14.6884 2.9066 14.5969 2.94004L12.549 3.74004C12.1214 3.42004 11.6608 3.15604 11.1591 2.95604L10.8466 0.836037C10.833 0.741911 10.7845 0.655846 10.7103 0.593986C10.6361 0.532127 10.5413 0.498722 10.4436 0.500037H7.15381C6.9482 0.500037 6.77548 0.644038 6.75081 0.836037L6.43828 2.95604C5.93659 3.15604 5.47602 3.42804 5.04835 3.74004L3.00047 2.94004C2.95278 2.9243 2.90279 2.9162 2.85243 2.91604C2.71262 2.91604 2.5728 2.98804 2.49878 3.11604L0.853896 5.88404C0.746979 6.06004 0.796325 6.27604 0.952589 6.39604L2.68794 7.71604C2.65505 7.97204 2.63037 8.23604 2.63037 8.50004C2.63037 8.76404 2.65505 9.02804 2.68794 9.28404L0.952589 10.604C0.796325 10.724 0.755203 10.94 0.853896 11.116L2.49878 13.884C2.54727 13.9666 2.62438 14.0296 2.71632 14.0619C2.80825 14.0941 2.90899 14.0935 3.00047 14.06L5.04835 13.26C5.47602 13.58 5.93659 13.844 6.43828 14.044L6.75081 16.164C6.77548 16.356 6.9482 16.5 7.15381 16.5H10.4436C10.6492 16.5 10.8219 16.356 10.8466 16.164L11.1591 14.044C11.6608 13.844 12.1214 13.572 12.549 13.26L14.5969 14.06C14.6463 14.076 14.6956 14.084 14.745 14.084C14.8848 14.084 15.0246 14.012 15.0986 13.884L16.7435 11.116C16.8422 10.94 16.8011 10.724 16.6448 10.604L14.9094 9.28404ZM13.281 7.91604C13.3139 8.16404 13.3221 8.33204 13.3221 8.50004C13.3221 8.66804 13.3057 8.84404 13.281 9.08404L13.1659 9.98804L13.8978 10.548L14.7861 11.22L14.2104 12.188L13.1659 11.78L12.3105 11.444L11.5703 11.988C11.2167 12.244 10.8795 12.436 10.5423 12.572L9.67048 12.916L9.53889 13.82L9.3744 14.9H8.22298L8.06672 13.82L7.93513 12.916L7.06334 12.572C6.70969 12.428 6.38071 12.244 6.05173 12.004L5.30331 11.444L4.43152 11.788L3.38702 12.196L2.81131 11.228L3.69955 10.556L4.43152 9.99604L4.31638 9.09204C4.29171 8.84404 4.27526 8.66004 4.27526 8.50004C4.27526 8.34004 4.29171 8.15604 4.31638 7.91604L4.43152 7.01204L3.69955 6.45204L2.81131 5.78004L3.38702 4.81204L4.43152 5.22004L5.28686 5.55604L6.02706 5.01204C6.38071 4.75604 6.71791 4.56404 7.05511 4.42804L7.9269 4.08404L8.05849 3.18004L8.22298 2.10004H9.36618L9.52244 3.18004L9.65403 4.08404L10.5258 4.42804C10.8795 4.57204 11.2084 4.75604 11.5374 4.99604L12.2858 5.55604L13.1576 5.21204L14.2021 4.80404L14.7778 5.77204L13.8978 6.45204L13.1659 7.01204L13.281 7.91604ZM8.79869 5.30004C6.98109 5.30004 5.50892 6.73204 5.50892 8.50004C5.50892 10.268 6.98109 11.7 8.79869 11.7C10.6163 11.7 12.0885 10.268 12.0885 8.50004C12.0885 6.73204 10.6163 5.30004 8.79869 5.30004ZM8.79869 10.1C7.894 10.1 7.15381 9.38004 7.15381 8.50004C7.15381 7.62004 7.894 6.90004 8.79869 6.90004C9.70338 6.90004 10.4436 7.62004 10.4436 8.50004C10.4436 9.38004 9.70338 10.1 8.79869 10.1Z"
                                    fill="currentColor" />
                            </svg>
                            Akun
                        </a>
                    </div>
                    <div
                        class="{{ request()->is('super_admin/email-subs') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.email-subs.index') }}"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <i class="ph ph-envelope"></i>
                            Email
                        </a>
                    </div>
                    <div
                        class=" {{ request()->is('super_admin/social-links') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="{{ route('superadmin.social.index') }}"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.9094 9.28404C14.9423 9.02804 14.967 8.77204 14.967 8.50004C14.967 8.22804 14.9423 7.97204 14.9094 7.71604L16.6448 6.39604C16.8011 6.27604 16.8422 6.06004 16.7435 5.88404L15.0986 3.11604C15.0501 3.0335 14.973 2.97045 14.8811 2.9382C14.7891 2.90595 14.6884 2.9066 14.5969 2.94004L12.549 3.74004C12.1214 3.42004 11.6608 3.15604 11.1591 2.95604L10.8466 0.836037C10.833 0.741911 10.7845 0.655846 10.7103 0.593986C10.6361 0.532127 10.5413 0.498722 10.4436 0.500037H7.15381C6.9482 0.500037 6.77548 0.644038 6.75081 0.836037L6.43828 2.95604C5.93659 3.15604 5.47602 3.42804 5.04835 3.74004L3.00047 2.94004C2.95278 2.9243 2.90279 2.9162 2.85243 2.91604C2.71262 2.91604 2.5728 2.98804 2.49878 3.11604L0.853896 5.88404C0.746979 6.06004 0.796325 6.27604 0.952589 6.39604L2.68794 7.71604C2.65505 7.97204 2.63037 8.23604 2.63037 8.50004C2.63037 8.76404 2.65505 9.02804 2.68794 9.28404L0.952589 10.604C0.796325 10.724 0.755203 10.94 0.853896 11.116L2.49878 13.884C2.54727 13.9666 2.62438 14.0296 2.71632 14.0619C2.80825 14.0941 2.90899 14.0935 3.00047 14.06L5.04835 13.26C5.47602 13.58 5.93659 13.844 6.43828 14.044L6.75081 16.164C6.77548 16.356 6.9482 16.5 7.15381 16.5H10.4436C10.6492 16.5 10.8219 16.356 10.8466 16.164L11.1591 14.044C11.6608 13.844 12.1214 13.572 12.549 13.26L14.5969 14.06C14.6463 14.076 14.6956 14.084 14.745 14.084C14.8848 14.084 15.0246 14.012 15.0986 13.884L16.7435 11.116C16.8422 10.94 16.8011 10.724 16.6448 10.604L14.9094 9.28404ZM13.281 7.91604C13.3139 8.16404 13.3221 8.33204 13.3221 8.50004C13.3221 8.66804 13.3057 8.84404 13.281 9.08404L13.1659 9.98804L13.8978 10.548L14.7861 11.22L14.2104 12.188L13.1659 11.78L12.3105 11.444L11.5703 11.988C11.2167 12.244 10.8795 12.436 10.5423 12.572L9.67048 12.916L9.53889 13.82L9.3744 14.9H8.22298L8.06672 13.82L7.93513 12.916L7.06334 12.572C6.70969 12.428 6.38071 12.244 6.05173 12.004L5.30331 11.444L4.43152 11.788L3.38702 12.196L2.81131 11.228L3.69955 10.556L4.43152 9.99604L4.31638 9.09204C4.29171 8.84404 4.27526 8.66004 4.27526 8.50004C4.27526 8.34004 4.29171 8.15604 4.31638 7.91604L4.43152 7.01204L3.69955 6.45204L2.81131 5.78004L3.38702 4.81204L4.43152 5.22004L5.28686 5.55604L6.02706 5.01204C6.38071 4.75604 6.71791 4.56404 7.05511 4.42804L7.9269 4.08404L8.05849 3.18004L8.22298 2.10004H9.36618L9.52244 3.18004L9.65403 4.08404L10.5258 4.42804C10.8795 4.57204 11.2084 4.75604 11.5374 4.99604L12.2858 5.55604L13.1576 5.21204L14.2021 4.80404L14.7778 5.77204L13.8978 6.45204L13.1659 7.01204L13.281 7.91604ZM8.79869 5.30004C6.98109 5.30004 5.50892 6.73204 5.50892 8.50004C5.50892 10.268 6.98109 11.7 8.79869 11.7C10.6163 11.7 12.0885 10.268 12.0885 8.50004C12.0885 6.73204 10.6163 5.30004 8.79869 5.30004ZM8.79869 10.1C7.894 10.1 7.15381 9.38004 7.15381 8.50004C7.15381 7.62004 7.894 6.90004 8.79869 6.90004C9.70338 6.90004 10.4436 7.62004 10.4436 8.50004C10.4436 9.38004 9.70338 10.1 8.79869 10.1Z"
                                    fill="currentColor" />
                            </svg>
                            Link & Header
                        </a>
                    </div>
                    <div
                        class="{{ request()->is('super_admin/pengaturan') ? 'bg-white text-[#00509d]' : '' }} rounded-md">
                        <a href="/super_admin/pengaturan"
                            class="flex font-semibold items-center mb-1 gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.9094 9.28404C14.9423 9.02804 14.967 8.77204 14.967 8.50004C14.967 8.22804 14.9423 7.97204 14.9094 7.71604L16.6448 6.39604C16.8011 6.27604 16.8422 6.06004 16.7435 5.88404L15.0986 3.11604C15.0501 3.0335 14.973 2.97045 14.8811 2.9382C14.7891 2.90595 14.6884 2.9066 14.5969 2.94004L12.549 3.74004C12.1214 3.42004 11.6608 3.15604 11.1591 2.95604L10.8466 0.836037C10.833 0.741911 10.7845 0.655846 10.7103 0.593986C10.6361 0.532127 10.5413 0.498722 10.4436 0.500037H7.15381C6.9482 0.500037 6.77548 0.644038 6.75081 0.836037L6.43828 2.95604C5.93659 3.15604 5.47602 3.42804 5.04835 3.74004L3.00047 2.94004C2.95278 2.9243 2.90279 2.9162 2.85243 2.91604C2.71262 2.91604 2.5728 2.98804 2.49878 3.11604L0.853896 5.88404C0.746979 6.06004 0.796325 6.27604 0.952589 6.39604L2.68794 7.71604C2.65505 7.97204 2.63037 8.23604 2.63037 8.50004C2.63037 8.76404 2.65505 9.02804 2.68794 9.28404L0.952589 10.604C0.796325 10.724 0.755203 10.94 0.853896 11.116L2.49878 13.884C2.54727 13.9666 2.62438 14.0296 2.71632 14.0619C2.80825 14.0941 2.90899 14.0935 3.00047 14.06L5.04835 13.26C5.47602 13.58 5.93659 13.844 6.43828 14.044L6.75081 16.164C6.77548 16.356 6.9482 16.5 7.15381 16.5H10.4436C10.6492 16.5 10.8219 16.356 10.8466 16.164L11.1591 14.044C11.6608 13.844 12.1214 13.572 12.549 13.26L14.5969 14.06C14.6463 14.076 14.6956 14.084 14.745 14.084C14.8848 14.084 15.0246 14.012 15.0986 13.884L16.7435 11.116C16.8422 10.94 16.8011 10.724 16.6448 10.604L14.9094 9.28404ZM13.281 7.91604C13.3139 8.16404 13.3221 8.33204 13.3221 8.50004C13.3221 8.66804 13.3057 8.84404 13.281 9.08404L13.1659 9.98804L13.8978 10.548L14.7861 11.22L14.2104 12.188L13.1659 11.78L12.3105 11.444L11.5703 11.988C11.2167 12.244 10.8795 12.436 10.5423 12.572L9.67048 12.916L9.53889 13.82L9.3744 14.9H8.22298L8.06672 13.82L7.93513 12.916L7.06334 12.572C6.70969 12.428 6.38071 12.244 6.05173 12.004L5.30331 11.444L4.43152 11.788L3.38702 12.196L2.81131 11.228L3.69955 10.556L4.43152 9.99604L4.31638 9.09204C4.29171 8.84404 4.27526 8.66004 4.27526 8.50004C4.27526 8.34004 4.29171 8.15604 4.31638 7.91604L4.43152 7.01204L3.69955 6.45204L2.81131 5.78004L3.38702 4.81204L4.43152 5.22004L5.28686 5.55604L6.02706 5.01204C6.38071 4.75604 6.71791 4.56404 7.05511 4.42804L7.9269 4.08404L8.05849 3.18004L8.22298 2.10004H9.36618L9.52244 3.18004L9.65403 4.08404L10.5258 4.42804C10.8795 4.57204 11.2084 4.75604 11.5374 4.99604L12.2858 5.55604L13.1576 5.21204L14.2021 4.80404L14.7778 5.77204L13.8978 6.45204L13.1659 7.01204L13.281 7.91604ZM8.79869 5.30004C6.98109 5.30004 5.50892 6.73204 5.50892 8.50004C5.50892 10.268 6.98109 11.7 8.79869 11.7C10.6163 11.7 12.0885 10.268 12.0885 8.50004C12.0885 6.73204 10.6163 5.30004 8.79869 5.30004ZM8.79869 10.1C7.894 10.1 7.15381 9.38004 7.15381 8.50004C7.15381 7.62004 7.894 6.90004 8.79869 6.90004C9.70338 6.90004 10.4436 7.62004 10.4436 8.50004C10.4436 9.38004 9.70338 10.1 8.79869 10.1Z"
                                    fill="currentColor" />
                            </svg>
                            Pengaturan
                        </a>
                    </div>
                    <div>
                        <form id="logout" action="" method="POST">
                            @csrf
                            <button onclick="openModal()"
                                class="flex font-semibold text-white items-center gap-2 mb-4 rounded-md px-3 py-2 transition duration-300">
                                <svg width="18" height="19" viewBox="0 0 18 19" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 5.1709L12.59 6.5809L14.17 8.1709H6V10.1709H14.17L12.59 11.7509L14 13.1709L18 9.1709L14 5.1709ZM2 2.1709H9V0.170898H2C0.9 0.170898 0 1.0709 0 2.1709V16.1709C0 17.2709 0.9 18.1709 2 18.1709H9V16.1709H2V2.1709Z"
                                        fill="currentColor" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>
        @yield('sidebarsuperadmin')

        <!-- Modal overlay -->
        <div id="successModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
            <!-- Konten Modal -->
            <div class="relative bg-white rounded-xl shadow-lg w-[90%] max-w-sm p-6 text-center">

                <!-- Judul -->
                <h2 class="text-lg font-bold mb-3">Konfimasi Keluar</h2>

                <!-- Pesan -->
                <p class="text-gray-700 mb-6">
                    Apakah anda yakin ingin keluar
                </p>

                <!-- Tombol aksi -->
                <div class="flex justify-center gap-4">
                    <form action="{{ route('logout_superadmin') }}" method="POST">
                        @csrf
                        <button id="goLogin"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-md font-medium">
                            Keluar
                        </button>
                    </form>
                    <button onclick="closeModal()"
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md font-medium">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <script>
            window.routes = {
                hapusNotif: "{{ route('notifikasi.hapus', ':id') }}",
                hapusSemua: "{{ route('notifikasi.hapusSemua') }}",
                hapusSemuaBaca: "{{ route('notifikasi.hapusSemuaBaca') }}"
            };

            window.csrf = "{{ csrf_token() }}";
        </script>
        <script src="{{ asset('js/super_admin.js') }}"></script>
        @include('finance.sidebar.modal-logout')
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        {{-- Preview gambar --}}
        <script>
            document.getElementById('fileinputsuperadmin').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        // update foto besar (pasti ada)
                        document.getElementById('pa').setAttribute('src', event.target.result);

                        // update foto kecil (navbar) kalau memang ada
                        const puImg = document.getElementById('pu');
                        if (puImg) {
                            puImg.setAttribute('src', event.target.result);
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>

        <script>
            // Buka modal saat klik tombol "Keluar"
            document.querySelector('#logout button').addEventListener('click', function(e) {
                e.preventDefault(); // jangan langsung submit
                let modal = document.getElementById("successModal");
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });

            // Tombol batal â†’ tutup modal
            function closeModal() {
                let modal = document.getElementById("successModal");
                modal.classList.remove("flex");
                modal.classList.add("hidden");
            }

            // Tombol "Keluar" di modal â†’ submit form logout
            document.getElementById("goLogin").addEventListener("click", function() {
                document.getElementById("logout_admin").submit();
            });
        </script>

        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        @stack('scripts')
</body>

</html>

