@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">

        <div class="flex justify-between items-center mb-6 flex-wrap gap-4">

            <h1 class="text-2xl font-medium ml-[40px] whitespace-nowrap">
                Finance
            </h1>

            <div class="flex items-center gap-3 flex-wrap">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative">
                    <!-- Icon Lonceng -->
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>

                    {{-- Badge jumlah notif --}}
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profil --}}
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-wrap">

                    <a href="{{ route('superadmin.profile') }}">
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

                    <div class="text-sm max-w-[180px] break-words">
                        <span class="font-semibold">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm break-words">{{ Auth::user()->email }}</p>
                    </div>

                </div>

            </div>
        </div>


        {{-- Konten scrollable --}}
        <main class="flex-1 overflow-hidden p-8 bg-white" x-cloak>
            {{-- Dropdown Menu --}}
            <div class="flex items-center gap-3 mb-8 flex-wrap">

                <select id="menu_select" name="menu_select"
                    class="w-48 sm:w-48 w-full bg-orange-500 text-white font-medium px-4 py-2 border border-orange-500 rounded-lg 
        focus:outline-none break-words truncate">
                    <option value="paket_harga" class="break-words">Paket Harga</option>
                    <option value="riwayat" class="break-words">Riwayat</option>
                    <option value="laporan" class="break-words">Laporan</option>
                </select>

            </div>


            {{-- Paket Harga Koin --}}
            <div id="paket_harga">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <h2 class="text-base font-semibold">Paket Harga Koin</h2>
                    <a href="{{ route('superadmin.paket-harga.edit-koin') }}"
                        class="bg-orange-500 text-white text-sm font-medium px-7 py-1 rounded-lg hover:bg-orange-600 whitespace-nowrap">
                        Edit
                    </a>
                </div>

                <div class="mb-10 bg-white">
                    <div class="overflow-x-auto rounded-lg shadow-md">
                        <table class="min-w-full text-sm border-2 border-gray-400 table-auto">
                            <thead>
                                <tr>
                                    <th
                                        class="bg-orange-500 text-white text-left px-4 py-3 font-semibold whitespace-nowrap">
                                        Nama
                                    </th>
                                    <th
                                        class="bg-orange-500 text-white text-right px-4 py-3 font-semibold whitespace-nowrap">
                                        Koin
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400">
                                @foreach ($koin as $k)
                                    <tr>
                                        <td class="px-4 py-3 break-words">{{ $k->nama }}</td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            {{ number_format($k->harga, 0, ',', '.') }} koin
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Paket Harga Pembayaran --}}
                <div>
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <h2 class="text-base font-semibold">Paket Harga Pembayaran</h2>
                        <a href="{{ route('superadmin.paket-harga.edit-pembayaran') }}"
                            class="bg-orange-500 text-white text-sm font-medium px-7 py-1 rounded-lg hover:bg-orange-600 whitespace-nowrap">
                            Edit
                        </a>
                    </div>

                    <div class="mb-10 bg-white">
                        <div class="overflow-x-auto rounded-lg shadow-md">
                            <table class="min-w-full text-sm border-2 border-gray-400 table-auto">
                                <thead>
                                    <tr>
                                        <th
                                            class="bg-orange-500 text-white text-left px-4 py-3 font-semibold whitespace-nowrap">
                                            Nama
                                        </th>
                                        <th
                                            class="bg-orange-500 text-white text-right px-4 py-3 font-semibold whitespace-nowrap">
                                            Harga
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-400">
                                    @foreach ($pembayaran as $p)
                                        <tr>
                                            <td class="px-4 py-3 break-words">{{ $p->nama }}</td>
                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                Rp. {{ number_format($p->harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Selesai paket harga --}}

            {{-- RIWAYAT --}}
            {{-- riwayat transaksi --}}
            <div id="riwayat" class="hidden">
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-2">Riwayat Tunai</h2>
                    <div x-data="{ openDetail: false, selected: {} }" class="rounded-2xl overflow-hidden border">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[700px] text-sm">
                                <thead>
                                    <tr class="bg-orange-500 text-white">
                                        <th class="px-4 py-2 text-left whitespace-nowrap">No</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">No. Refrensi</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Jenis Pesanan</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Dari</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Sumber Dana</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Nominal</th>
                                        <th class="px-4 py-2 text-center whitespace-nowrap">Detail</th>
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cashTerbaru as $index => $cash)
                                        <tr class="border-t">
                                            <td class="px-4 py-3 break-words">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 break-words">{{ $cash->no_referensi ?? '-' }}</td>
                                            <td class="px-4 py-3 break-words">{{ $cash->pesanan ?? '-' }}</td>
                                            <td class="px-4 py-3 break-words">{{ $cash->dari ?? '-' }}</td>
                                            <td class="px-4 py-3 break-words">{{ $cash->sumberDana ?? '-' }}</td>
                                            <td class="px-4 py-3 break-words">Rp
                                                {{ number_format($cash->total, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-orange-500 flex items-center justify-center">
                                                <button
                                                    @click="selected = {
                                    no_referensi: '{{ $cash->no_referensi }}',
                                    status: '{{ ucfirst($cash->status) }}',
                                    jenis: '{{ $cash->pesanan }}',
                                    pengirim: '{{ $cash->dari }}',
                                    penerima: 'Area Kerja',
                                    metode: '{{ $cash->sumberDana }}',
                                    waktu: '{{ $cash->created_at->format('d M Y H:i') }} WIB',
                                    nominal: '{{ number_format($cash->total, 0, ',', '.') }}'
                                }; openDetail = true">
                                                    <svg width="19" height="24" viewBox="0 0 19 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0.4375 0.680664V23.091H18.3658V11.8858H8.12105V0.680664H0.4375ZM10.6822 0.680664V9.08455H18.3658L10.6822 0.680664ZM2.99868 6.28325H5.55987V9.08455H2.99868V6.28325ZM2.99868 11.8858H5.55987V14.6871H2.99868V11.8858ZM2.99868 17.4884H13.2434V20.2897H2.99868V17.4884Z"
                                                            fill="#FA6601" />
                                                    </svg>
                                                </button>
                                            </td>
                                            <td class="px-4 py-2 font-semibold">
                                                <span
                                                    class="{{ $cash->status == 'diterima' ? 'text-green-500' : 'text-red-500' }}">
                                                    {{ ucfirst($cash->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-gray-500">Belum ada transaksi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ==================== MODAL DETAIL ==================== --}}
                        <div x-show="openDetail"
                            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4" x-cloak>
                            <div
                                class="bg-white rounded-2xl shadow-lg w-full sm:w-[400px] max-w-full p-6 relative overflow-y-auto max-h-[90vh]">
                                <button @click="openDetail = false"
                                    class="absolute top-3 right-4 text-gray-500 hover:text-black text-lg">✕</button>

                                <!-- Judul & Icon -->
                                <div class="text-center mb-6">
                                    <template x-if="selected.status == 'Diterima'">
                                        <div
                                            class="w-16 h-16 mx-auto rounded-full bg-orange-100 flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="selected.status == 'Ditolak'">
                                        <div
                                            class="w-16 h-16 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="selected.status == 'Pending'">
                                        <div
                                            class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-gray-500 animate-spin" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="selected.status == 'menunggu_verifikasi'">
                                        <div
                                            class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-gray-500 animate-spin" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </div>
                                    </template>

                                    <template x-if="selected.status == 'expired'">
                                        <div
                                            class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-gray-500 animate-spin" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </div>
                                    </template>

                                    <h2 class="text-xl font-semibold"
                                        x-text="
        selected.status == 'Diterima'
            ? 'Top Up Berhasil'
            : (selected.status == 'Ditolak'
                ? 'Top Up Ditolak'
                : (selected.status == 'Expired'
                    ? 'Top Up Kedaluwarsa'
                    : 'Menunggu Verifikasi'))
    ">
                                    </h2>

                                </div>

                                <!-- Detail -->
                                <div class="text-sm space-y-2 break-words">
                                    <div class="flex justify-between"><span class="font-semibold">No.Transaksi</span><span
                                            x-text="selected.no_referensi"></span></div>
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold">Status</span>
                                        <span class="text-xs px-3 py-1 rounded-full text-white"
                                            :class="selected.status == 'Diterima' ? 'bg-orange-500' :
                                                (selected.status == 'Ditolak' ? 'bg-red-500' : 'bg-gray-500')"
                                            x-text="selected.status"></span>
                                    </div>
                                    <div class="flex justify-between"><span class="font-semibold">Jenis
                                            Transaksi</span><span x-text="selected.jenis"></span></div>
                                    <div class="flex justify-between"><span class="font-semibold">Nama
                                            Pengirim</span><span x-text="selected.pengirim"></span></div>
                                    <div class="flex justify-between"><span class="font-semibold">Nama
                                            Penerima</span><span x-text="selected.penerima"></span></div>
                                    <div class="flex justify-between"><span class="font-semibold">Metode
                                            Pembayaran</span><span x-text="selected.metode"></span></div>
                                    <div class="flex justify-between"><span class="font-semibold">Tgl/Waktu</span><span
                                            x-text="selected.waktu"></span></div>
                                    <div class="flex justify-between"><span class="font-semibold">Nominal</span><span
                                            x-text=" 'Rp. ' + selected.nominal "></span></div>
                                    <div class="flex justify-between"><span class="font-semibold">Biaya
                                            Admin</span><span>Rp 2.000</span></div>

                                    <div class="border-t border-dashed my-2"></div>

                                    <div class="flex justify-between font-semibold">
                                        <span>Total Pembayaran</span>
                                        <span
                                            x-text="'Rp ' + (Number(selected.nominal.replaceAll('.', '')) + 2000).toLocaleString('id-ID')">
                                        </span>
                                    </div>
                                </div>

                                <div class="flex justify-center mt-6">
                                    <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="w-16">
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button @click="openDetail = false"
                                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">Tutup</button>
                                </div>
                            </div>
                        </div>
                        {{-- ================ END MODAL ================= --}}
                    </div>



                    {{-- riwayat transaksi koin --}}
                    <div class="w-full">
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold mb-2">Riwayat Koin</h2>
                            <div x-data="{ openDetailKoin: false, selectedKoin: {} }" class="rounded-2xl overflow-hidden border">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[700px] text-sm">
                                        <thead>
                                            <tr class="bg-orange-500 text-white">
                                                <th class="px-4 py-2 text-left whitespace-nowrap">No</th>
                                                <th class="px-4 py-2 text-left whitespace-nowrap">No. Referensi</th>
                                                <th class="px-4 py-2 text-left whitespace-nowrap">Jenis Pesanan</th>
                                                <th class="px-4 py-2 text-left whitespace-nowrap">Dari</th>
                                                <th class="px-4 py-2 text-left whitespace-nowrap">Sumber Dana</th>
                                                <th class="px-4 py-2 text-left whitespace-nowrap">Total Koin</th>
                                                <th class="px-4 py-2 text-center whitespace-nowrap">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($koinTerbaru as $index => $koin)
                                                <tr class="border-t">
                                                    <td class="px-4 py-2 break-words">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2 break-words">{{ $koin->no_referensi ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-2 break-words">{{ $koin->pesanan ?? '-' }}</td>
                                                    <td class="px-4 py-2 break-words">{{ $koin->dari ?? '-' }}</td>
                                                    <td class="px-4 py-2 break-words">{{ $koin->sumber_dana ?? '-' }}</td>
                                                    <td class="px-4 py-2 break-words">{{ $koin->total }} Koin</td>
                                                    <td class="px-4 py-2 text-orange-500 flex items-center justify-center">
                                                        <button
                                                            @click="selectedKoin = {
                                        no_referensi: '{{ $koin->no_referensi }}',
                                        jenis: '{{ $koin->pesanan }}',
                                        dari: '{{ $koin->dari }}',
                                        sumber_dana: '{{ $koin->sumber_dana }}',
                                        nominal: '{{ number_format((int) str_replace('-', '', $koin->total), 0, ',', '.') }}',
                                        waktu: '{{ $koin->created_at->format('d M Y H:i') }} WIB'
                                    }; openDetailKoin = true">
                                                            <svg width="19" height="24" viewBox="0 0 19 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0.4375 0.680664V23.091H18.3658V11.8858H8.12105V0.680664H0.4375ZM10.6822 0.680664V9.08455H18.3658L10.6822 0.680664ZM2.99868 6.28325H5.55987V9.08455H2.99868V6.28325ZM2.99868 11.8858H5.55987V14.6871H2.99868V11.8858ZM2.99868 17.4884H13.2434V20.2897H2.99868V17.4884Z"
                                                                    fill="#FA6601" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-gray-500">Belum ada
                                                        transaksi</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Modal Detail Riwayat Koin -->
                                <div x-show="openDetailKoin" x-cloak
                                    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4">
                                    <div
                                        class="bg-white rounded-2xl shadow-xl w-full sm:w-[360px] max-w-full max-h-[90vh] overflow-y-auto relative p-4">
                                        <button @click="openDetailKoin = false"
                                            class="absolute top-3 right-3 text-gray-500 hover:text-black text-lg">✕</button>

                                        <div class="text-center py-6">
                                            <div
                                                class="bg-orange-100 w-14 h-14 mx-auto rounded-full flex items-center justify-center">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                                                    <path d="M9 12l2 2 4-4" stroke="#FA6601" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <h2 class="text-lg font-semibold mt-4">Detail Transaksi Koin</h2>
                                        </div>

                                        <div class="px-2 sm:px-6 pb-6 text-sm text-gray-700 space-y-1 break-words">
                                            <div class="flex justify-between"><span>No. Referensi</span><span
                                                    x-text="selectedKoin.no_referensi"></span></div>
                                            <div class="flex justify-between"><span>Jenis Pesanan</span><span
                                                    x-text="selectedKoin.jenis"></span></div>
                                            <div class="flex justify-between"><span>Dari</span><span
                                                    x-text="selectedKoin.dari"></span></div>
                                            <div class="flex justify-between"><span>Sumber Dana</span><span
                                                    x-text="selectedKoin.sumber_dana"></span></div>
                                            <div class="flex justify-between"><span>Tgl/Waktu</span><span
                                                    x-text="selectedKoin.waktu"></span></div>
                                            <hr class="my-2 border-dashed border-gray-300">
                                            <div class="flex justify-between font-semibold"><span>Total</span>
                                                <span x-text="selectedKoin.nominal + ' Koin'"></span>
                                            </div>
                                        </div>

                                        <div class="text-center pb-4">
                                            <img src="{{ asset('images/logoarea.png') }}" alt="Logo"
                                                class="mx-auto w-20 opacity-70">
                                            <p class="text-xs text-gray-400 mt-1">Copyright ©2024 areakerja</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- SELESAI RIWAYAT --}}

            {{-- -------------------- LAPORAN --------------------------- --}}
            <div id="laporan" class="hidden">
                <!-- Description -->
                <h3 class="font-semibold text-lg">Catatan Transaksi Penghasilan</h3>
                <p class="text-gray-600 mb-6 text-sm">
                    Hanya catatan transaksi dalam 12 bulan terakhir akan dipertahankan. Silahkan download salinan PDF anda.
                </p>

                <!-- Riwayat Koin Box -->
                <div class="bg-orange-500 rounded-lg p-4 sm:p-9">
                    <h2 class="text-white font-semibold text-lg mb-4">Riwayat Koin</h2>

                    <!-- Filters -->
                    <div class="flex flex-col sm:flex-row gap-3 mb-4">
                        <form id="formFilterLaporan" method="GET" action="{{ route('superadmin.paket-harga') }}"
                            class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            @php
                                // Buat array 12 bulan terakhir (format [bulan, tahun])
                                $bulanSekarang = now();
                                $listBulan = collect();
                                for ($i = 0; $i < 12; $i++) {
                                    $listBulan->push([
                                        'bulan' => $bulanSekarang->copy()->subMonths($i)->format('m'),
                                        'tahun' => $bulanSekarang->copy()->subMonths($i)->format('Y'),
                                        'label' => $bulanSekarang->copy()->subMonths($i)->translatedFormat('F Y'),
                                    ]);
                                }
                            @endphp

                            <select name="bulan"
                                class="w-full sm:w-40 bg-white text-orange-500 text-sm font-medium px-3 py-2 border rounded-lg focus:outline-none">
                                @foreach ($listBulan as $item)
                                    <option value="{{ $item['bulan'] }}" data-tahun="{{ $item['tahun'] }}"
                                        {{ $item['bulan'] == sprintf('%02d', $bulan) && $item['tahun'] == $tahun ? 'selected' : '' }}>
                                        {{ $item['label'] }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="tahun" id="tahun_input" value="{{ $tahun }}">

                            <button type="submit"
                                class="w-full sm:w-auto bg-white text-orange-500 text-sm font-medium px-3 py-2 rounded">
                                Filter
                            </button>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="bg-white rounded-lg shadow overflow-x-auto">
                        <table class="min-w-[700px] w-full text-sm text-left">
                            <thead class="bg-white text-orange-500">
                                <tr>
                                    <th class="px-4 py-3 text-center whitespace-nowrap">Catatan Transaksi</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Pendapatan</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Koin</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y border-4">
                                @forelse($laporan as $item)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-center break-words">{{ $item['catatan'] }}
                                        </td>
                                        <td class="px-4 py-3 font-semibold break-words">
                                            {{ number_format($item['pendapatan'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 font-semibold break-words">
                                            {{ number_format($item['koin'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 font-semibold break-words">{{ $item['tanggal'] }}</td>
                                        <td class="px-4 py-3 text-orange-500 whitespace-nowrap">
                                            <a
                                                href="{{ route('superadmin.laporan.detail', ['tanggal' => $item['tanggal']]) }}">
                                                <svg width="19" height="24" viewBox="0 0 19 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M0.4375 0.680664V23.091H18.3658V11.8858H8.12105V0.680664H0.4375ZM10.6822 0.680664V9.08455H18.3658L10.6822 0.680664ZM2.99868 6.28325H5.55987V9.08455H2.99868V6.28325ZM2.99868 11.8858H5.55987V14.6871H2.99868V11.8858ZM2.99868 17.4884H13.2434V20.2897H2.99868V17.4884Z"
                                                        fill="#FA6601" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-3 text-center text-gray-500">Tidak ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('super_admin.notif.modal_notif')
            @include('super_admin.notif.modal_semua')
        </main>
        </div>

    </main>
    <script>
        // Tandai dibaca
        async function markAsRead(url, el) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {

                    // Ubah warna bg
                    el.classList.remove("bg-white");
                    el.classList.add("bg-gray-200");

                    // Kurangi badge
                    const badge = document.getElementById("notif-badge");
                    if (badge) {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove();
                        }
                    }
                }

            } catch (error) {
                console.error("markAsRead error:", error);
            }
        }

        // AlpineJS init
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifHandler', () => ({

                // Hapus satu notifikasi
                async hapus(id) {
                    if (!confirm("Hapus notifikasi ini?")) return;

                    let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);

                    let res = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                    }
                },

                // Hapus semua
                async hapusSemua() {
                    if (!confirm("Hapus semua notifikasi?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item').forEach(e => e.remove());
                    }
                },

                // Hapus semua yang sudah dibaca
                async hapusSemuaBaca() {
                    if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item.bg-gray-200')
                            .forEach(e => e.remove());
                    }
                }

            }));
        });
    </script>

    <script>
        document.querySelector('form[target="hiddenFrame"]').addEventListener('submit', () => {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.classList.remove('bg-white');
                item.classList.add('bg-gray-200');
            });
            const badge = document.querySelector('.absolute .bg-red-500');
            if (badge) badge.remove();
        });
    </script>
@endsection
