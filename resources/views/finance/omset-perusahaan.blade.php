@extends('finance.sidebar.index')
@section('sidebar')
    <div class="p-4 sm:ml-64">
        <!-- Header -->
        <header
            class="w-full flex flex-col md:flex-row md:items-center md:justify-between px-3 md:px-6 py-2 md:py-3 gap-2 md:gap-0">

            <!-- Judul -->
            <p class="font-medium text-lg md:text-2xl">Omset Perusahaan</p>

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




        <div class="p-5 md:p-10">
            <div class="mb-8 md:mb-10">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 mb-3">

                    <h2 class="text-base md:text-lg font-medium">Tampilkan Omset Perusahaan</h2>

                    <form method="GET" action="{{ route('finance.omset') }}"
                        class="flex flex-col sm:flex-row items-start sm:items-center gap-3">

                        <div class="relative inline-block text-left w-full sm:w-auto">
                            <select name="periode"
                                class="appearance-none border-2 border-[#00509d] text-[#00509d] rounded-lg px-3 py-2 pr-10 
                        text-sm md:text-base bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d] cursor-pointer w-full sm:w-auto">
                                <option value="current" {{ $periodeDipilih == 'current' ? 'selected' : '' }}>Bulan ini
                                </option>
                                <option value="1" {{ $periodeDipilih == '1' ? 'selected' : '' }}>1 Bulan Terakhir
                                </option>
                                <option value="3" {{ $periodeDipilih == '3' ? 'selected' : '' }}>3 Bulan Terakhir
                                </option>
                                <option value="5" {{ $periodeDipilih == '5' ? 'selected' : '' }}>5 Bulan Terakhir
                                </option>
                                <option value="7" {{ $periodeDipilih == '7' ? 'selected' : '' }}>7 Bulan Terakhir
                                </option>
                                <option value="9" {{ $periodeDipilih == '9' ? 'selected' : '' }}>9 Bulan Terakhir
                                </option>
                                <option value="12" {{ $periodeDipilih == '12' ? 'selected' : '' }}>12 Bulan Terakhir
                                </option>
                                <option value="24" {{ $periodeDipilih == '24' ? 'selected' : '' }}>2 Tahun Terakhir
                                </option>
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#00509d]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <button
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white text-sm md:text-base px-6 py-2 font-medium rounded-lg transition w-full sm:w-auto">
                            Cari
                        </button>
                    </form>
                </div>

                <div class="border border-gray-400 rounded-2xl overflow-hidden w-full shadow-md">
                    <div class="flex justify-center items-center bg-[#00509d] text-white px-4 py-3">
                        <div class="font-medium text-base md:text-lg">Daftar Omset Perusahaan</div>
                    </div>

                    <div class="relative bg-white text-sm">
                        <div class="absolute left-1/2 top-0 bottom-0 w-px bg-gray-300 hidden sm:block"></div>

                        @forelse ($omsetPerBulan as $item)
                            <div class="flex px-3 md:px-4 py-3 items-center">
                                <div class="w-1/2 text-sm md:text-lg font-medium">
                                    {{ $item['nama_bulan'] }} {{ $item['tahun'] }}
                                </div>
                                <div class="w-1/2 text-right text-sm md:text-lg font-medium">
                                    Rp. {{ number_format($item['total'], 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">Belum ada data omset</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <ul class="text-sm md:text-md font-medium">
                <li class="py-2 text-base md:text-lg">
                    Total Omset
                    <span class="pl-3 md:pl-4 text-base md:text-lg">:
                        Rp. {{ number_format($totalOmset, 0, ',', '.') }}</span>
                </li>

                <li class="py-2 text-base md:text-lg">
                    Rata-Rata
                    <span class="pl-5 md:pl-8 text-base md:text-lg">:
                        Rp. {{ number_format($rataRata, 0, ',', '.') }}</span>
                </li>
            </ul>

            <div class="border border-[#00509d] mt-2"></div>

            <div class="mt-5 flex justify-end">
                <a href="{{ route('finance.omset.unduh', ['periode' => $periodeDipilih]) }}"
                    class="bg-[#00509d] hover:bg-[#003d7a] text-white text-sm md:text-base px-8 py-2 font-medium rounded-full">
                    Unduh
                </a>
            </div>

        </div>
    @endsection

