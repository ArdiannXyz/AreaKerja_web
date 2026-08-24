@extends('finance.sidebar.index')
@section('sidebar')
    <div class="p-4 sm:ml-64">
        <!-- Header -->
        <header
            class="w-full flex flex-col md:flex-row md:items-center md:justify-between px-3 md:px-6 py-2 md:py-3 gap-2 md:gap-0">

            <!-- Judul -->
            <p class="font-medium text-lg md:text-2xl">Paket Harga</p>

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

        <div class="p-4 md:p-10">

            {{-- Paket Harga Koin --}}
            <div class="mb-10">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-base md:text-lg font-medium">Paket Harga Koin</h2>
                    <a href="{{ route('finance.paket-harga.edit-koin') }}"
                        class="bg-orange-500 text-white text-xs md:text-sm px-4 md:px-5 py-1 rounded-full">Edit</a>
                </div>

                <div class="border-2 border-gray-400 shadow-md rounded-2xl overflow-x-auto">
                    <table class="min-w-full text-left border-collapse text-sm md:text-md">
                        <thead>
                            <tr class="bg-orange-500 text-white">
                                <th class="px-3 md:px-4 py-2 font-semibold">Nama</th>
                                <th class="px-3 md:px-4 py-2 font-semibold text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($koin as $k)
                                <tr class="border-b border-gray-400">
                                    <td class="px-3 md:px-4 py-3">{{ $k->nama }}</td>
                                    <td class="px-3 md:px-4 py-3 text-right">{{ $k->harga }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paket Harga Pembayaran --}}
            <div class="mb-10">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-base md:text-lg font-medium">Paket Harga Pembayaran</h2>
                    <a href="{{ route('finance.paket-harga.edit-pembayaran') }}"
                        class="bg-orange-500 text-white text-xs md:text-sm px-4 md:px-5 py-1 rounded-full">Edit</a>
                </div>

                <div class="border-2 border-gray-400 shadow-md rounded-2xl overflow-x-auto">
                    <table class="min-w-full text-left border-collapse text-sm md:text-md">
                        <thead>
                            <tr class="bg-orange-500 text-white">
                                <th class="px-3 md:px-4 py-2 font-semibold">Nama</th>
                                <th class="px-3 md:px-4 py-2 font-semibold text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembayaran as $p)
                                <tr class="border-b border-gray-400">
                                    <td class="px-3 md:px-4 py-3">{{ $p->nama }}</td>
                                    <td class="px-3 md:px-4 py-3 text-right">Rp.
                                        {{ number_format($p->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @include('finance.modal-editkoin')
        </div>

    </div>
@endsection
