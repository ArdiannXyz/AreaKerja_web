@extends('finance.sidebar.index')
@section('sidebar')
    <div class="p-4 sm:ml-64">
        <!-- Header -->
        <header
            class="w-full flex flex-col md:flex-row md:items-center md:justify-between px-3 md:px-6 py-2 md:py-3 gap-2 md:gap-0">

            <!-- Judul -->
            <p class="font-medium text-lg md:text-2xl">Dashboard</p>

            <!-- Right Section -->
            <div class="flex items-center gap-2 md:gap-3">

                {{-- Notifikasi --}}
                @php
                    use App\Models\CatatanCash;
                    $notifCount = CatatanCash::where('status', 'menunggu_verifikasi')->count();
                    $notifikasiCash = CatatanCash::where('status', 'menunggu_verifikasi')->latest()->take(5)->get();
                @endphp

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="relative group focus:outline-none">

                        <svg width="28" height="28" class="md:w-[31px] md:h-[32px] " fill="none"
                            xmlns="http://www.w3.org/2000/svg <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431Z"
                                fill="black" />
                            </g> <!-- ... icon tetap sama ... -->
                        </svg>

                        @if ($notifCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] md:text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-pulse">
                                {{ $notifCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" x-transition.opacity.duration.200ms @click.outside="open = false"
                        class="absolute left-2 mt-2 w-52 md:w-72 bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden z-[9999]">

                        <div class="p-2 md:p-3 border-b bg-orange-50">
                            <p class="font-semibold text-gray-700 text-xs md:text-sm">Notifikasi Finance</p>
                        </div>

                        <div class="max-h-48 md:max-h-56 overflow-y-auto">
                            @forelse ($notifikasiCash as $notif)
                                <div class="p-2 md:p-3 border-b hover:bg-gray-50">
                                    <p class="text-xs md:text-sm text-gray-800 font-medium">
                                        Transaksi dari <span class="text-orange-600">
                                            {{ $notif->dari ?? 'Tidak diketahui' }}
                                        </span>
                                    </p>
                                    <p class="text-[10px] md:text-xs text-gray-500">
                                        Menunggu verifikasi (Rp {{ number_format($notif->total, 0, ',', '.') }})
                                    </p>
                                </div>
                            @empty
                                <div class="p-2 md:p-3 text-xs md:text-sm text-gray-500 text-center">
                                    Tidak ada notifikasi.
                                </div>
                            @endforelse
                        </div>

                        <div class="p-2 text-center bg-gray-100">
                            <a href="{{ route('finance.catatan') }}"
                                class="text-orange-600 text-xs md:text-sm hover:underline font-semibold">
                                Lihat Lebih Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Box -->
                <div
                    class="flex items-center md:w-60 sm:w-auto justify-between bg-white border border-orange-500 shadow-md rounded-xl px-3 py-2">
                    <div class="flex items-center gap-2">
                        <a href="#">
                            @if (Auth::user()?->avatar)
                                <img class="w-9 h-9 md:w-10 md:h-10 object-cover rounded-full"
                                    src="{{ asset('storage/' . Auth::user()->avatar) }}">
                            @else
                                <img class="w-9 h-9 md:w-10 md:h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'Finance') }}&background=random&color=fff&size=128">
                            @endif
                        </a>

                        <div class="text-xs md:text-sm leading-tight">
                            <span class="font-semibold">{{ Auth::user()->username }}</span>
                            <p class="text-gray-500 text-xs md:text-sm">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

            </div>

        </header>



        <!-- Content -->
        <main class="px-4 md:px-6 py-6 space-y-10">

            <!-- Cards -->
            <div class="flex flex-col md:flex-row justify-center items-center gap-4 md:gap-6">
                <div
                    class="w-full md:w-80 bg-orange-500 text-white rounded-md p-4 md:p-5 flex justify-between items-center">
                    <div>
                        <p class="text-sm">Total Omset</p>
                        <p class="text-base md:text-lg font-bold">
                            Rp {{ number_format($totalOmset, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-3xl md:text-4xl">
                        <!-- ICON -->
                        <svg width="45" height="45" class="md:w-[52px] md:h-[53px]" viewBox="0 0 52 53"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M51.6876 26.6983C51.6876 40.904 40.1716 52.42 25.9659 52.42C11.7601 52.42 0.244141 40.904 0.244141 26.6983C0.244141 12.4926 11.7601 0.976562 25.9659 0.976562C40.1716 0.976562 51.6876 12.4926 51.6876 26.6983Z"
                                fill="white" />
                            <path
                                d="M22.3527 20.8449C22.6948 20.6176 23.1164 20.4123 23.6042 20.2556V24.0035C23.1621 23.865 22.7411 23.6667 22.3527 23.4142C21.5514 22.88 21.3969 22.3812 21.3969 22.1296C21.3969 21.8779 21.5514 21.3791 22.3527 20.8449ZM28.0187 32.8326V29.0847C28.5043 29.2414 28.9281 29.4466 29.2702 29.674C30.0737 30.2104 30.226 30.707 30.226 30.9586C30.226 31.2103 30.0715 31.7091 29.2702 32.2433C28.8818 32.4958 28.4608 32.694 28.0187 32.8326Z"
                                fill="#FA6601" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M25.8115 44.1993C30.4947 44.1993 34.9861 42.3389 38.2976 39.0273C41.6092 35.7158 43.4696 31.2244 43.4696 26.5411C43.4696 21.8579 41.6092 17.3665 38.2976 14.0549C34.9861 10.7434 30.4947 8.883 25.8115 8.883C21.1282 8.883 16.6368 10.7434 13.3253 14.0549C10.0137 17.3665 8.15333 21.8579 8.15333 26.5411C8.15333 31.2244 10.0137 35.7158 13.3253 39.0273C16.6368 42.3389 21.1282 44.1993 25.8115 44.1993ZM28.0187 15.5048C28.0187 14.9194 27.7862 14.358 27.3722 13.944C26.9583 13.5301 26.3969 13.2975 25.8115 13.2975C25.226 13.2975 24.6646 13.5301 24.2507 13.944C23.8367 14.358 23.6042 14.9194 23.6042 15.5048V15.7079C22.2857 15.9426 21.0278 16.4395 19.9048 17.1691C18.3112 18.2286 16.9824 19.9392 16.9824 22.1266C16.9824 24.3118 18.3112 26.0224 19.9048 27.0841C20.9643 27.7904 22.2313 28.2871 23.6042 28.5453V32.8296C22.7411 32.5493 22.1032 32.1299 21.7435 31.7172C21.5567 31.488 21.3259 31.2987 21.0646 31.1605C20.8033 31.0223 20.517 30.9379 20.2225 30.9125C19.928 30.887 19.6314 30.921 19.3502 31.0123C19.0691 31.1037 18.8092 31.2506 18.5859 31.4443C18.3627 31.638 18.1806 31.8745 18.0505 32.1399C17.9204 32.4054 17.8449 32.6942 17.8285 32.9893C17.8121 33.2845 17.8552 33.5799 17.9552 33.8581C18.0552 34.1362 18.21 34.3915 18.4105 34.6087C19.651 36.0412 21.5294 36.9837 23.6042 37.3744V37.5775C23.6042 38.1629 23.8367 38.7243 24.2507 39.1382C24.6646 39.5522 25.226 39.7847 25.8115 39.7847C26.3969 39.7847 26.9583 39.5522 27.3722 39.1382C27.7862 38.7243 28.0187 38.1629 28.0187 37.5775V37.3744C29.3372 37.1397 30.5951 36.6428 31.7181 35.9132C33.3117 34.8537 34.6405 33.1431 34.6405 30.9557C34.6405 28.7705 33.3117 27.0598 31.7181 25.9981C30.5951 25.2685 29.3372 24.7716 28.0187 24.5369V20.2526C28.8818 20.533 29.5197 20.9523 29.8794 21.3651C30.0679 21.5887 30.299 21.7727 30.5591 21.9062C30.8193 22.0398 31.1034 22.1203 31.395 22.1431C31.6865 22.166 31.9797 22.1306 32.2575 22.0392C32.5353 21.9477 32.7921 21.8019 33.0131 21.6104C33.2341 21.4188 33.4148 21.1852 33.5447 20.9232C33.6746 20.6612 33.7512 20.376 33.7699 20.0841C33.7887 19.7923 33.7492 19.4996 33.6539 19.2231C33.5586 18.9466 33.4093 18.6918 33.2146 18.4736C31.9719 17.0411 30.0958 16.0986 28.0187 15.7079V15.5048Z"
                                fill="#FA6601" />
                        </svg>
                    </div>
                </div>

                <div
                    class="w-full md:w-80 bg-orange-500 text-white rounded-md p-4 md:p-5 flex justify-between items-center">
                    <div>
                        <p class="text-sm">Total Transaksi Koin</p>
                        <p class="text-base md:text-lg font-bold">
                            {{ number_format($totalTransaksiKoin, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-3xl md:text-4xl">
                        <!-- ICON -->
                        <svg width="45" height="45" class="md:w-[52px] md:h-[53px]" viewBox="0 0 52 53"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M51.5899 26.6993C51.5899 40.905 40.0739 52.421 25.8682 52.421C11.6625 52.421 0.146484 40.905 0.146484 26.6993C0.146484 12.4935 11.6625 0.977539 25.8682 0.977539C40.0739 0.977539 51.5899 12.4935 51.5899 26.6993Z"
                                fill="white" />
                            <path
                                d="M13.3261 33.0642V34.8343C13.3261 36.2977 17.5094 37.4874 22.6655 37.4874C27.8216 37.4874 32.0049 36.2977 32.0049 34.8343V33.0642C29.9959 34.2705 26.3234 34.8343 22.6655 34.8343C19.0076 34.8343 15.3351 34.2705 13.3261 33.0642ZM28.8917 21.5689C34.0478 21.5689 38.2311 20.3791 38.2311 18.9158C38.2311 17.4524 34.0478 16.2627 28.8917 16.2627C23.7356 16.2627 19.5524 17.4524 19.5524 18.9158C19.5524 20.3791 23.7356 21.5689 28.8917 21.5689ZM13.3261 28.7156V30.8547C13.3261 32.318 17.5094 33.5078 22.6655 33.5078C27.8216 33.5078 32.0049 32.318 32.0049 30.8547V28.7156C29.9959 30.1251 26.3185 30.8547 22.6655 30.8547C19.0124 30.8547 15.3351 30.1251 13.3261 28.7156ZM33.5614 29.1716C36.3486 28.7115 38.2311 27.8575 38.2311 26.875V25.1049C37.1026 25.7848 35.4439 26.2491 33.5614 26.5351V29.1716ZM22.6655 22.8954C17.5094 22.8954 13.3261 24.3795 13.3261 26.2118C13.3261 28.0441 17.5094 29.5281 22.6655 29.5281C27.8216 29.5281 32.0049 28.0441 32.0049 26.2118C32.0049 24.3795 27.8216 22.8954 22.6655 22.8954ZM33.3328 25.2293C36.2513 24.7816 38.2311 23.9028 38.2311 22.8954V21.1253C36.5043 22.1658 33.5371 22.7254 30.4142 22.8581C31.8492 23.4509 32.9047 24.2468 33.3328 25.2293Z"
                                fill="#FA6601" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Table 1 -->
            <section>
                <h2 class="text-base md:text-lg font-medium mb-2">Riwayat Transaksi Cash Terbaru</h2>
                <div class="overflow-x-auto border shadow-md rounded-2xl">
                    <table class="w-full border-collapse text-xs md:text-sm">
                        <thead>
                            <tr class="bg-orange-500 text-white">
                                <th class="font-medium px-2 py-2 md:px-3">No</th>
                                <th class="font-medium px-2 py-2 md:px-3">No. Refrensi</th>
                                <th class="font-medium px-2 py-2 md:px-3">Pesanan</th>
                                <th class="font-medium px-2 py-2 md:px-3">Dari</th>
                                <th class="font-medium px-2 py-2 md:px-3">Sumber Dana</th>
                                <th class="font-medium px-2 py-2 md:px-3">Tanggal</th>
                                <th class="font-medium px-2 py-2 md:px-3">Nominal</th>
                                <th class="font-medium px-2 py-2 md:px-3">Status</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700">
                            @forelse ($cashTerbaru as $index => $cash)
                                <tr class="border-b border-gray-300 text-center">
                                    <td class="py-2 px-2">{{ $index + 1 }}</td>
                                    <td class="py-2 px-2">{{ $cash->no_referensi ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $cash->pesanan ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $cash->dari ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $cash->sumberDana ?? '-' }}</td>
                                    <td class="py-2 px-2">
                                        {{ \Carbon\Carbon::parse($cash->created_at)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="py-2 px-2">
                                        Rp {{ number_format($cash->total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 px-2">
                                        <span
                                            class="{{ $cash->status == 'diterima' ? 'text-green-500' : 'text-red-500' }}">
                                            {{ ucfirst($cash->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Table 2 -->
            <section>
                <h2 class="text-base md:text-lg font-medium mb-2">Riwayat Transaksi Koin Terbaru</h2>
                <div class="overflow-x-auto border shadow-md rounded-2xl">
                    <table class="w-full border-collapse text-xs md:text-sm">
                        <thead>
                            <tr class="bg-orange-500 text-white">
                                <th class="font-medium px-2 py-2 md:px-3">No</th>
                                <th class="font-medium px-2 py-2 md:px-3">No. Refrensi</th>
                                <th class="font-medium px-2 py-2 md:px-3">Pesanan</th>
                                <th class="font-medium px-2 py-2 md:px-3">Dari</th>
                                <th class="font-medium px-2 py-2 md:px-3">Sumber Dana</th>
                                <th class="font-medium px-2 py-2 md:px-3">Tanggal</th>
                                <th class="font-medium px-2 py-2 md:px-3">Koin</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700">
                            @forelse ($koinTerbaru as $index => $koin)
                                <tr class="border-b border-gray-300 text-center">
                                    <td class="py-2 px-2">{{ $index + 1 }}</td>
                                    <td class="py-2 px-2">{{ $koin->no_referensi ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $koin->pesanan ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $koin->dari ?? '-' }}</td>
                                    <td class="py-2 px-2">{{ $koin->sumber_dana ?? '-' }}</td>
                                    <td class="py-2 px-2">
                                        {{ \Carbon\Carbon::parse($koin->created_at)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="py-2 px-2">{{ $koin->total }} Koin</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </main>

    </div>
@endsection
