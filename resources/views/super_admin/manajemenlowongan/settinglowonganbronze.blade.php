@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')

    <!-- Main Content -->
    <main class="flex-1 p-6 sm:ml-64" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6 flex-col sm:flex-row gap-4 sm:gap-0">

            <h1 class="text-2xl font-medium w-full sm:w-auto text-center sm:text-left">
                Upload Lowongan
            </h1>

            <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative flex-shrink-0">
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>

                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profile --}}
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl w-full sm:w-auto break-words">

                    <a href="{{ route('superadmin.profile') }}" class="flex-shrink-0">
                        @if (Auth::user()->role == 'super_admin')
                            @if (Auth::user()->superadmin?->img_profile)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                    src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                            @else
                                <img id="pu" class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="">
                            @endif
                        @else
                            <img class="w-10 h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    </a>

                    <div class="text-sm break-words max-w-[150px] sm:max-w-none">
                        <span class="font-semibold break-words">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm break-words">{{ Auth::user()->email }}</p>
                    </div>

                </div>

            </div>
        </div>

        <div class="relative inline-block w-full sm:w-48">
            <!-- Select utama -->
            <button id="dropdownButton"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium px-4 py-2 border border-orange-500 rounded-md flex justify-between items-center focus:outline-none truncate">
                <span id="dropdownText" class="truncate">Pilih Lowongan</span>
                <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="dropdownMenu" class="absolute hidden mt-2 w-full bg-white rounded-md shadow-lg overflow-hidden z-10">
                <ul class="text-orange-500">
                    <li>
                        <a href="{{ route('superadmin.manajemen.lowongan.gold') }}"
                            class="block px-4 py-2 hover:bg-orange-500 hover:text-white transition truncate">Lowongan
                            Gold</a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.manajemen.lowongan.silver') }}"
                            class="block px-4 py-2 hover:bg-orange-500 hover:text-white transition truncate">Lowongan
                            Silver</a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.manajemen.lowongan.bronze') }}"
                            class="block px-4 py-2 hover:bg-orange-500 hover:text-white transition truncate">Lowongan
                            Bronze</a>
                    </li>
                </ul>
            </div>
        </div>



        <div class="bg-white w-full border mx-auto p-6 mt-4 rounded-lg shadow-md sm:p-8 md:p-10 lg:p-12">
            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <h2 class="text-xl font-semibold mb-4 text-center md:text-left">Paket Lowongan {{ $paket->nama }}</h2>

            <form method="POST" action="{{ route('superadmin.manajemen.lowongan.bronze.update') }}">
                @csrf

                <!-- Batas Listing -->
                <div class="mb-4">
                    <label for="batas_listing" class="text-sm font-semibold text-gray-700 mb-1 block">
                        Batas Durasi Lowongan
                    </label>

                    <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                        <input id="batas_listing" name="batas_listing" type="number" value="{{ $paket->batas_listing }}"
                            class="w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">

                        <span class="px-3 py-2 bg-gray-100 text-gray-700 text-sm font-semibold border-l border-gray-300">
                            hari
                        </span>
                    </div>
                </div>

                <!-- Benefit -->
                <label for="benefit" class="block text-sm font-medium text-gray-700 mb-1">Benefit</label>
                <textarea id="benefit" name="benefit" placeholder="Contoh: BPJS, work from home, fleksibel"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mb-6 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"
                    style="overflow:hidden; min-height: 120px;">{{ $paket->benefit }}</textarea>


                <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                    <button
                        class="w-full sm:w-auto px-4 py-2 rounded-md bg-orange-500 text-white text-sm font-medium shadow-sm hover:bg-orange-600">Simpan</button>
                </div>
            </form>
        </div>
        
            @include('super_admin.notif.modal_notif')
            @include('super_admin.notif.modal_semua')
    </main>

    <script>
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownText = document.getElementById('dropdownText');

        // Toggle dropdown
        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Ganti teks tombol saat klik opsi
        dropdownMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', (e) => {
                dropdownText.textContent = link.textContent; // ubah teks tombol
                dropdownMenu.classList.add('hidden'); // tutup dropdown
                // Navigasi tetap terjadi karena tag <a> ada href-nya
            });
        });

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', (e) => {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>

    <script>
        const textarea = document.getElementById("benefit");

        function autoResize(el) {
            el.style.height = "auto"; // reset height
            el.style.height = el.scrollHeight + "px"; // sesuaikan dengan isi
        }

        // saat user mengetik
        textarea.addEventListener("input", function() {
            autoResize(this);
        });

        // sesuaikan tinggi saat halaman pertama kali dimuat (untuk data existing)
        window.addEventListener("load", function() {
            autoResize(textarea);
        });
    </script>

@endsection
