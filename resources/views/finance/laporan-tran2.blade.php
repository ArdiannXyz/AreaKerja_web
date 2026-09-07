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

                        <div class="p-2 md:p-3 border-b bg-blue-50">
                            <p class="font-semibold text-gray-700 text-xs md:text-sm">Notifikasi Finance</p>
                        </div>

                        <div class="max-h-48 md:max-h-56 overflow-y-auto">
                            @forelse ($notifikasiCash as $notif)
                                <div class="p-2 md:p-3 border-b hover:bg-gray-50">
                                    <p class="text-xs md:text-sm text-gray-800 font-medium">
                                        Transaksi dari <span class="text-[#003d7a]">
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
                                class="text-[#003d7a] text-xs md:text-sm hover:underline font-semibold">
                                Lihat Lebih Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Box -->
                <div
                    class="flex items-center md:w-60 sm:w-auto justify-between bg-white border border-[#00509d] shadow-md rounded-xl px-3 py-2">
                    <div class="flex items-center gap-2">
                        <a href="#">
                            @if (Auth::user()?->avatar)
                                <img class="w-9 h-9 md:w-10 md:h-10 object-cover rounded-full"
                                    src="{{ asset('storage/' . Auth::user()->avatar) }}">
                            @else
                                <img class="w-9 h-9 md:w-10 md:h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'Finance') }}&background=00509d&color=fff&size=128">
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


        <div class="shadow-md rounded-xl p-4 sm:p-6 border mt-6">

            {{-- header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-start gap-4">

                {{-- logo alamat --}}
                <div class="font-semibold w-full sm:w-auto">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('images/logoarea.png') }}" class="w-10 h-10 sm:w-12 sm:h-12" alt="">
                        <span class="text-[#00509d] font-bold text-lg sm:text-xl">areakerja.com</span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 leading-snug">
                        Jl. Laksda Adisucipto No.80, Ambarrukmo, Caturtunggal, Kec.<br>
                        Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
                    </p>
                </div>

                {{-- info user --}}
                <div class="text-xs sm:text-sm text-gray-700 text-right font-semibold w-full sm:w-auto">

                    {{-- download icon --}}
                    <div class="flex justify-end space-x-2 sm:space-x-3 mt-2 text-[#00509d] text-lg">
                        <a href="{{ route('finance.laporan.unduh', ['tanggal' => $tanggal]) }}">
                            <span><svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <mask id="mask0_680_18811" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="28" height="28">
                                        <rect x="0.367188" y="0.15625" width="27.3438" height="27.3438"
                                            fill="url(#pattern0_680_18811)" />
                                    </mask>
                                    <g mask="url(#mask0_680_18811)">
                                        <rect x="-6.92188" y="-4.0957" width="39.4965" height="34.0278" fill="#00509d" />
                                    </g>
                                    <defs>
                                        <pattern id="pattern0_680_18811" patternContentUnits="objectBoundingBox"
                                            width="1" height="1">
                                            <use xlink:href="#image0_680_18811" transform="scale(0.0078125)" />
                                        </pattern>
                                        <image id="image0_680_18811" width="128" height="128"
                                            preserveAspectRatio="none"
                                            xlink:href="data:image/png;base64,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" />
                                    </defs>
                                </svg> </span>
                        </a>
                    </div>

                    <div class="text-xs sm:text-sm leading-relaxed space-y-1 mt-2">
                        <p>
                            <span class="font-semibold">Username :</span>
                            <span class="font-medium">{{ Auth::user()->username ?? '-' }}</span>
                        </p>
                        <p>
                            <span class="font-semibold">Email :</span>
                            <span>{{ Auth::user()->email ?? '-' }}</span>
                        </p>
                        <p>
                            <span class="font-semibold">No. Telp :</span>
                            <span>0816-3428-25322</span>
                        </p>
                    </div>

                </div>
            </div>


            {{-- riwayat transaksi --}}
            <div class="mt-8 sm:mt-10">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-semibold text-base sm:text-lg">Laporan Transaksi Penghasilan</h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="w-full text-xs sm:text-sm border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-[#00509d] text-white text-left">
                                <th class="py-2 px-2 sm:px-3">Transaksi</th>
                                <th class="py-2 px-2 sm:px-3">Dari</th>
                                <th class="py-2 px-2 sm:px-3">Jenis Transaksi</th>
                                <th class="py-2 px-2 sm:px-3">Sumber Dana</th>
                                <th class="py-2 px-2 sm:px-3">Nominal IDR</th>
                                <th class="py-2 px-2 sm:px-3">Transaksi Koin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                @forelse ($transaksi as $t)
                                    <td class="px-2 sm:px-3 py-2">{{ $t->no_referensi ?? '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2">{{ $t->dari ?? '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2">{{ $t->pesanan ?? '-' }}</td>
                                    <td class="px-2 sm:px-3 py-2">{{ $t->sumber_dana ?? ($t->sumberDana ?? '-') }}</td>
                                    <td class="px-2 sm:px-3 py-2">
                                        @if ($t->tipe == 'cash')
                                            Rp{{ number_format($t->total, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 sm:px-3 py-2 text-center">
                                        @if ($t->tipe == 'koin')
                                            {{ $t->total_koin }} Koin
                                        @else
                                            -
                                        @endif
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">
                                    Tidak ada transaksi pada tanggal ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- footer --}}
            <div class="mt-4 text-xs sm:text-sm text-gray-800 font-semibold space-y-1">
                <p>Total Tunai <span class="ml-8 sm:ml-14">: Rp{{ number_format($totalCash, 0, ',', '.') }}</span></p>
                <p>Total Koin <span class="ml-10 sm:ml-[65px]">: {{ $totalKoin }} Koin</span></p>
            </div>
        </div>

    </div>
@endsection

