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
                @include('super_admin.components.notif_button')
                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')

            </div>
        </div>

        <div class="relative inline-block w-full sm:w-48">
            <!-- Select utama -->
            <button id="dropdownButton"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 border border-blue-700 rounded-md flex justify-between items-center focus:outline-none truncate">
                <span id="dropdownText" class="truncate">Pilih Lowongan</span>
                <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="dropdownMenu" class="absolute hidden mt-2 w-full bg-white rounded-md shadow-lg overflow-hidden z-10">
                <ul class="text-blue-700">
                    <li>
                        <a href="{{ route('superadmin.manajemen.lowongan.gold') }}"
                            class="block px-4 py-2 hover:bg-blue-700 hover:text-white transition truncate">Lowongan
                            Gold</a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.manajemen.lowongan.silver') }}"
                            class="block px-4 py-2 hover:bg-blue-700 hover:text-white transition truncate">Lowongan
                            Silver</a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.manajemen.lowongan.bronze') }}"
                            class="block px-4 py-2 hover:bg-blue-700 hover:text-white transition truncate">Lowongan
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

            <form method="POST" action="{{ route('superadmin.manajemen.lowongan.gold.update') }}">
                @csrf

                <!-- Batas Listing -->
                <div class="mb-4">
                    <label for="batas_listing" class="text-sm font-semibold text-gray-700 mb-1 block">
                        Batas Durasi Lowongan
                    </label>

                    <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                        <input id="batas_listing" name="batas_listing" type="number" value="{{ $paket->batas_listing }}"
                            class="w-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <span class="px-3 py-2 bg-gray-100 text-gray-700 text-sm font-semibold border-l border-gray-300">
                            hari
                        </span>
                    </div>
                </div>

                <!-- Benefit -->
                <label for="benefit" class="block text-sm font-medium text-gray-700 mb-1">Benefit</label>
                <textarea id="benefit" name="benefit" placeholder="Contoh: BPJS, work from home, fleksibel"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mb-6 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    style="overflow:hidden; min-height: 120px;">{{ $paket->benefit }}</textarea>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                    <button
                        class="w-full sm:w-auto px-4 py-2 rounded-md bg-blue-700 text-white text-sm font-medium shadow-sm hover:bg-blue-800">Simpan</button>
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
