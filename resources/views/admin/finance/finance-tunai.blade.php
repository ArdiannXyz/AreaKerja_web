@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64" x-data="{
        showDetail: false,
        selected: null,
        openNotif: false,
        openAllNotif: false
    }">
        <!-- Header -->
        <header class="w-full flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">

            <!-- Judul -->
            <h1 class="text-2xl font-medium break-words">
                Data Transaksi Tunai
            </h1>

            <!-- Bagian kanan -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative flex-shrink-0 ml-32">
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

                <!-- Profil -->
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-shrink min-w-0">

                    <a href="#">
                        @if (Auth::user()->role == 'admin')
                            @if (Auth::user()->admin->img_profile)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full"
                                    src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
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

                    <div class="text-sm mr-6 sm:mr-14 truncate max-w-[140px] sm:max-w-none">
                        <span class="font-semibold break-words">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm break-words">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>



        <!-- Filter dan tombol -->
        <div class="mt-8">
            <div class="flex flex-wrap items-center gap-4 mb-4">

                <!-- Tombol Koin -->
                <a href="{{ url('/admin/finance') }}"
                    class="px-8 py-2 rounded-lg border-2 
            {{ request()->is('admin/finance') ? 'bg-white text-gray-500 border-gray-500' : 'bg-white text-gray-500 border-gray-300 hover:bg-gray-100' }}">
                    Koin
                </a>

                <!-- Tombol Tunai -->
                <a href="{{ url('/admin/finance/tunai') }}"
                    class="px-8 py-2 rounded-lg border-2 
            {{ request()->is('admin/finance/tunai') ? 'bg-gray-500 text-white border-gray-500' : 'bg-white text-gray-500 border-gray-500 hover:bg-gray-600 hover:text-white' }}">
                    Tunai
                </a>

                <!-- Filter No Referensi -->
                <form method="GET" class="flex flex-wrap items-center gap-3 ml-auto w-full sm:w-auto" x-cloak>

                    <div class="flex items-center border-2 overflow-hidden rounded-lg border-gray-400 w-full sm:w-auto">
                        <select name="no_referensi"
                            class="px-4 py-2 text-sm focus:outline-none w-full sm:w-auto break-words">
                            <option value="">Semua No. Referensi</option>
                            @foreach ($noReferensiList as $ref)
                                <option value="{{ $ref }}" {{ $selectedRef == $ref ? 'selected' : '' }}>
                                    {{ $ref }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="px-6 py-2 rounded-lg border border-gray-600 text-white bg-gray-500 hover:bg-gray-600 w-full sm:w-auto">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Tabel (scrollable mobile) -->
            <div id="table_cash" class="rounded-2xl border-2 border-gray-400 overflow-x-auto">

                <table class="w-full text-sm text-left min-w-[700px]">
                    <thead class="bg-white">
                        <tr class="text-center">
                            <th class="p-5 font-semibold">No</th>
                            <th class="p-5 font-semibold">No. Referensi</th>
                            <th class="p-5 font-semibold">Pesanan</th>
                            <th class="p-5 font-semibold">Dari</th>
                            <th class="p-5 font-semibold">Sumber Dana</th>
                            <th class="p-5 font-semibold">Total</th>
                            <th class="p-5 font-semibold">Status</th>
                            <th class="p-5 font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cash as $index => $item)
                            <tr class="border-b-[2px] border-gray-300 text-center">
                                <td class="py-2">{{ $index + 1 }}</td>

                                <!-- Wrap text panjang -->
                                <td class="py-2 break-words max-w-[150px]">{{ $item->no_referensi ?? '-' }}</td>
                                <td class="py-2 break-words max-w-[150px]">{{ $item->pesanan ?? '-' }}</td>
                                <td class="py-2 break-words max-w-[150px]">{{ $item->dari ?? '-' }}</td>
                                <td class="py-2 break-words max-w-[150px]">{{ $item->sumberDana ?? '-' }}</td>

                                <td class="py-2">{{ number_format($item->total, 0, ',', '.') }}</td>

                                <td
                                    class="py-2 font-medium
                            @if ($item->status === 'pending') text-yellow-500
                            @elseif($item->status === 'diterima') text-green-600
                            @elseif($item->status === 'ditolak') text-red-600
                            @else text-gray-600 @endif">
                                    {{ ucfirst($item->status) }}
                                </td>

                                <td class="py-2">
                                    <button @click="selected = {{ Js::from($item) }}; showDetail = true"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="ph ph-file-arrow-up text-2xl"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- ====================== MODAL RESPONSIF ====================== -->
            <div x-show="showDetail" x-cloak
                class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4" x-transition>

                <div
                    class="bg-white rounded-2xl shadow-lg w-full max-w-xs sm:max-w-md p-6 relative overflow-y-auto max-h-[90vh]">
                    <!-- Judul -->
                    <h2 class="text-xl font-semibold text-center mb-6"
                        x-text="
        selected?.status?.toLowerCase() === 'diterima'
            ? 'Top Up Berhasil'
            : selected?.status?.toLowerCase() === 'ditolak'
                ? 'Top Up Ditolak'
                : selected?.status?.toLowerCase() === 'expired'
                    ? 'Top Up Kedaluwarsa'
                    : 'Menunggu Verifikasi'
    ">
                    </h2>


                    <!-- DETAIL -->
                    <div class="text-sm space-y-2">

                        <div class="flex justify-between break-words">
                            <span class="font-semibold">No. Transaksi</span>
                            <span x-text="selected?.id"></span>
                        </div>

                        <div class="flex justify-between items-center break-words">
                            <span class="font-semibold">Status</span>
                            <span
                                :class="selected?.status?.toLowerCase() === 'diterima' ? 'bg-orange-500' :
                                    (selected?.status?.toLowerCase() === 'ditolak' ? 'bg-red-500' : 'bg-gray-500')"
                                class="text-white text-xs px-3 py-1 rounded-full" x-text="selected?.status">
                            </span>
                        </div>

                        <div class="flex justify-between break-words">
                            <span class="font-semibold">Nama Pengirim</span>
                            <span x-text="selected?.user?.username ?? '-'"></span>
                        </div>

                        <div class="flex justify-between break-words">
                            <span class="font-semibold">Metode Pembayaran</span>
                            <span x-text="selected?.sumberDana ?? '-'"></span>
                        </div>

                        <div class="flex justify-between break-words">
                            <span class="font-semibold">Tanggal</span>
                            <span x-text="new Date(selected?.created_at).toLocaleString('id-ID')"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="font-semibold">Nominal</span>
                            <span>Rp <span x-text="Number(selected?.total).toLocaleString('id-ID')"></span></span>
                        </div>

                        <div class="border-t border-dashed my-2"></div>

                        <div class="flex justify-between font-semibold">
                            <span>Total Pembayaran</span>
                            <span>Rp <span
                                    x-text="(Number(selected?.total ?? 0) + 2500).toLocaleString('id-ID')"></span></span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="showDetail = false" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </div>
@endsection
