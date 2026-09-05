<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <meta name="register-finance-url" content="{{ route('registerproses_finance') }}"> --}}
    <title>Finance</title>

    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="icon" sizes="512x512" type="image/png" href="{{ asset('images/logoarea.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>

    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 hover:text-white rounded-lg sm:hidden hover:bg-[#00509d] focus:outline-none focus:ring-2 focus:ring-[#00509d] dark:text-gray-500 dark:hover:bg-[#00509d] dark:focus:ring-[#00509d] transition duration-300">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-[#00509d]">
            <div class="px-4 py-2">
                <div class="inline-flex items-center -ml-2 gap-1 border-b-2 border-blue-300 pb-2">
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="logo" class="w-14 h-14">
                    <p class="text-xl text-white font-semibold">areakerja.com</p>
                </div>
            </div>

            <ul class="space-y-2 font-medium">
                <li>
                    <p class="flex items-center p-2 text-white rounded-lg dark:text-white">
                        <span class="ms-3 mt-7">Umum</span>
                    </p>
                </li>
                <li>
                    <div
                        class="{{ request()->is('finance/dashboard') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md">
                        <a href="{{ route('finance.dashboard') }}"
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
                </li>
                <li>
                    <p class="flex items-center p-2 text-white rounded-lg dark:text-white">
                        <span class="ms-3 mt-4">Finance</span>
                    </p>
                </li>
                <li>
                    <div
                        class="{{ request()->is('finance/paket/harga') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md">
                        <a href="{{ route('finance.paket-harga') }}"
                            class="flex font-semibold items-center gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 24 24"
                                fill="currentColor" class="-ml-1 transition-colors duration-300">
                                <path
                                    d="M19,7H18V6a3,3,0,0,0-3-3H5A3,3,0,0,0,2,6H2V18a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V10A3,3,0,0,0,19,7ZM5,5H15a1,1,0,0,1,1,1V7H5A1,1,0,0,1,5,5ZM20,15H19a1,1,0,0,1,0-2h1Zm0-4H19a3,3,0,0,0,0,6h1v1a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V8.83A3,3,0,0,0,5,9H19a1,1,0,0,1,1,1Z" />
                            </svg>
                            Paket Harga
                        </a>
                    </div>
                </li>
                <li>
                    <div
                        class="{{ request()->is('finance/omset') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md">
                        <a href="{{ route('finance.omset') }}"
                            class="flex  font-semibold items-center gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="currentColor" class="-ml-1"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.0217 0.909668C7.51245 0.909668 5.2259 1.70443 3.87345 2.35409C3.75155 2.41263 3.6373 2.46999 3.5311 2.52548C3.321 2.63531 3.1426 2.73781 3 2.82769L4.53885 5.00019L5.26315 5.27672C8.09445 6.64672 11.8913 6.64672 14.7226 5.27672L15.545 4.86751L17 2.82769C16.7868 2.69137 16.4919 2.52598 16.1291 2.35012C16.107 2.3394 16.0846 2.32864 16.062 2.31785C14.7154 1.67526 12.4844 0.909668 10.0217 0.909668ZM6.4426 3.36908C5.8888 3.2711 5.34465 3.13595 4.831 2.98089C6.0984 2.44118 7.98715 1.86868 10.0217 1.86868C11.4313 1.86868 12.7641 2.14356 13.8665 2.49161C12.5746 2.6665 11.196 2.96174 9.8827 3.32609C8.84935 3.61277 7.64145 3.58122 6.4426 3.36908Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.3093 6.06748L15.1731 6.13336C12.0584 7.64045 7.9273 7.64045 4.8127 6.13336L4.6832 6.07074C0.00453835 10.9943 -4.90379 20.2272 10.0217 20.0888C24.9369 19.9506 19.9517 10.8244 15.3093 6.06748ZM10.8557 9.54122H9.14435V10.3084C8.58825 10.3072 8.05355 10.5002 7.6534 10.8465C7.25335 11.1928 7.0194 11.6651 7.00115 12.1635C6.9829 12.6618 7.1818 13.147 7.55565 13.516C7.92955 13.8851 8.44905 14.1091 9.00405 14.1406L9.14435 14.1445H10.8557L10.9327 14.1506C11.0313 14.1666 11.1206 14.2132 11.1848 14.2822C11.2491 14.3511 11.2843 14.4382 11.2843 14.5281C11.2843 14.6179 11.2491 14.705 11.1848 14.774C11.1206 14.843 11.0313 14.8895 10.9327 14.9056L10.8557 14.9117H7.43305V16.4461H9.14435V17.2133H10.8557V16.4461C11.4118 16.4474 11.9465 16.2544 12.3466 15.908C12.7467 15.5618 12.9806 15.0894 12.9989 14.591C13.0171 14.0927 12.8182 13.6076 12.4444 13.2385C12.0705 12.8694 11.551 12.6454 10.996 12.6139L10.8557 12.6101H9.14435L9.06736 12.6039C8.9687 12.5879 8.87945 12.5413 8.8152 12.4723C8.75095 12.4034 8.7157 12.3163 8.7157 12.2265C8.7157 12.1366 8.75095 12.0495 8.8152 11.9806C8.87945 11.9116 8.9687 11.865 9.06736 11.849L9.14435 11.8428H12.567V10.3084H10.8557V9.54122Z"
                                    fill="currentColor" />
                            </svg>

                            Omset Perusahaan
                        </a>
                    </div>
                </li>
                <li>
                    <div
                        class="{{ request()->is('finance/laporan/transaksi') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md">
                        <a href="{{ route('finance.catatan') }}"
                            class="flex font-semibold items-center gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="currentColor" class="-ml-1"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4 7.3C4 5.0376 4 3.9056 4.65925 3.2032C5.31775 2.5 6.379 2.5 8.5 2.5H11.5C13.621 2.5 14.6823 2.5 15.3408 3.2032C16 3.9056 16 5.0376 16 7.3V13.7C16 15.9624 16 17.0944 15.3408 17.7968C14.6823 18.5 13.621 18.5 11.5 18.5H8.5C6.379 18.5 5.31775 18.5 4.65925 17.7968C4 17.0944 4 15.9624 4 13.7V7.3Z"
                                    stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path
                                    d="M15.9235 13.7002H6.9235C6.226 13.7002 5.87725 13.7002 5.59075 13.7818C5.20923 13.8909 4.86137 14.1053 4.58213 14.4032C4.3029 14.7012 4.10212 15.0724 4 15.4794"
                                    stroke="currentColor" stroke-width="1.5" fill="none" />
                                <path
                                    d="M7 6.5H13M7 9.3H10.75M10.75 13.7V16.524C10.75 16.7448 10.75 16.8552 10.6787 16.9C10.6075 16.9448 10.5107 16.8952 10.3157 16.796L9.38425 16.324C9.31825 16.292 9.28525 16.2744 9.25 16.2744C9.21475 16.2744 9.18175 16.2912 9.11575 16.3248L8.18425 16.7968C7.98925 16.8952 7.89175 16.9448 7.82125 16.9C7.75 16.8552 7.75 16.7448 7.75 16.524V14.06"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    fill="none" />
                            </svg>
                            Catatan Transaksi
                        </a>
                    </div>
                </li>
                <li>
                    <div
                        class="{{ request()->is('finance/laporan') ? 'bg-white text-[#00509d]' : 'text-white' }} rounded-md">
                        <a href="{{ route('finance.laporan') }}"
                            class="flex font-semibold items-center gap-2 hover:bg-white hover:text-[#00509d] rounded-md px-3 py-2 transition duration-300">
                            <svg width="20" height="23" viewBox="0 0 20 23" fill="none" class="-ml-1"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0 15.8822L0.779186 15.3096L4.2708 21.3348L18.7438 12.3646L19.523 13.3189L4.27084 22.6708L0 15.8822ZM0.293989 12.6151L0.887456 12.2334L4.37911 18.4495L18.8521 9.67008L19.4456 10.8152L4.37911 19.9763L0.293989 12.6151ZM0.10569 9.16111L15.5435 0L20 7.93376L4.56221 17.0949L0.10569 9.16111ZM10.1329 4.96223C8.28691 4.96223 6.79046 6.50034 6.79046 8.39765C6.79046 10.295 8.28691 11.8331 10.1329 11.8331C11.9788 11.8331 13.4752 10.295 13.4752 8.39765C13.4752 6.50034 11.9788 4.96223 10.1329 4.96223ZM11.6705 10.5671L11.1624 10.8686L10.8831 10.3715L10.3489 10.5856L9.90247 10.6534L9.70643 9.94617L10.1715 9.88894L10.6756 9.68537C10.8277 9.59404 10.9377 9.48975 11.0057 9.37249C11.0737 9.25523 11.0799 9.14283 11.0245 9.03541C10.9679 8.9355 10.8697 8.87861 10.7299 8.86472L10.1759 8.91757C9.8497 8.98748 9.56242 8.99452 9.31402 8.93883C9.06568 8.88309 8.86753 8.72832 8.71971 8.47451C8.58545 8.23289 8.54624 7.98429 8.60197 7.7287C8.65774 7.4731 8.81213 7.23299 9.06512 7.00835L8.78318 6.50646L9.28752 6.20718L9.54867 6.67208L10.0163 6.47382L10.3883 6.40627L10.575 7.09499L10.2312 7.14176L9.70526 7.3609C9.54271 7.46187 9.44528 7.56254 9.41291 7.66307C9.38054 7.76356 9.38758 7.85228 9.43402 7.92923C9.47896 8.01399 9.57123 8.06129 9.71089 8.07113L10.3295 8.00493C10.697 7.93347 10.9921 7.93839 11.2149 8.01968C11.4378 8.10102 11.6155 8.26318 11.7478 8.50619C11.8838 8.74838 11.9239 9.0033 11.868 9.2709C11.8121 9.53855 11.6453 9.79091 11.3677 10.0281L11.6705 10.5671Z"
                                    fill="currentColor" />
                            </svg>
                            Laporan Transaksi
                        </a>
                    </div>
                </li>
                <li>
                    <form id="logout" action="" method="POST">
                        @csrf
                        <button onclick="openModal()"
                            class="flex font-semibold text-white items-center mt-28 gap-2 rounded-md px-3 py-2 transition duration-300">
                            <svg width="18" height="19" viewBox="0 0 18 19" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14 5.1709L12.59 6.5809L14.17 8.1709H6V10.1709H14.17L12.59 11.7509L14 13.1709L18 9.1709L14 5.1709ZM2 2.1709H9V0.170898H2C0.9 0.170898 0 1.0709 0 2.1709V16.1709C0 17.2709 0.9 18.1709 2 18.1709H9V16.1709H2V2.1709Z"
                                    fill="currentColor" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>



        </div>
    </aside>

    @yield('sidebar')

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
                <form action="{{ route('logout_finance') }}" method="POST">
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


    @include('finance.sidebar.modal-logout')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

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
    <script>
        // Buka modal saat klik tombol "Keluar"
        document.querySelector('#logout button').addEventListener('click', function(e) {
            e.preventDefault(); // jangan langsung submit
            let modal = document.getElementById("successModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Tombol batal → tutup modal
        function closeModal() {
            let modal = document.getElementById("successModal");
            modal.classList.remove("flex");
            modal.classList.add("hidden");
        }

        // Tombol "Keluar" di modal → submit form logout
        document.getElementById("goLogin").addEventListener("click", function() {
            document.getElementById("logout").submit();
        });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
