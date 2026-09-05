@extends('finance.sidebar.index')
@section('sidebar')
    <div class="p-4 sm:ml-64" x-data="{ openCashModal: false, openKoinModal: false, detailCash: {}, detailKoin: {} }" x-cloak>

        <header class="w-full flex items-center justify-between px-6 py-3">
            <p class="font-semibold text-2xl">Catatan Transaksi</p>
        </header>

        <div class="p-4">

            {{-- ====================== TABEL CATATAN KOIN ====================== --}}
            <div class="mb-12">
                <h2 class="text-lg font-semibold mb-2">Riwayat Koin</h2>

                <div class="rounded-2xl overflow-hidden border overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm min-w-[800px]">
                        <thead>
                            <tr class="bg-[#00509d] text-white">
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">No. Referensi</th>
                                <th class="px-4 py-2 text-left">User</th>
                                <th class="px-4 py-2 text-left">Pesanan</th>
                                <th class="px-4 py-2 text-left">Dari</th>
                                <th class="px-4 py-2 text-left">Sumber Dana</th>
                                <th class="px-4 py-2 text-left">Total</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-center">Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($catatanKoins as $item)
                                <tr class="border-b border-gray-300">
                                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2">{{ $item->no_referensi ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->user->username ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->pesanan ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->dari ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->sumber_dana ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->total }} Koin</td>
                                    <td class="px-4 py-2">{{ $item->created_at->format('d M Y H:i') }}</td>

                                    <td class="px-4 py-2 text-center">
                                        <button class="text-blue-600 hover:underline"
                                            @click="
                                            detailKoin = {
                                                id: '{{ $item->id }}',
                                                referensi: '{{ $item->no_referensi ?? '-' }}',
                                                user: '{{ $item->user->username ?? '-' }}',
                                                dari: '{{ $item->dari ?? '-' }}',
                                                sumber: '{{ $item->sumber_dana ?? '-' }}',
                                                total: '{{ $item->total ?? 0 }}',
                                                tanggal: '{{ $item->created_at->format('d M Y H:i') }}'
                                            };
                                            openKoinModal = true;
                                    ">
                                            <i class="ph ph-file-arrow-up text-3xl sm:text-4xl"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- ====================== TABEL CATATAN CASH ====================== --}}
            <div>
                <h2 class="text-lg font-semibold mb-2">Riwayat Tunai</h2>

                <div class="rounded-2xl overflow-hidden border overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm min-w-[900px]">
                        <thead>
                            <tr class="bg-[#00509d] text-white">
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">No. Referensi</th>
                                <th class="px-4 py-2 text-left">User</th>
                                <th class="px-4 py-2 text-left">Jenis</th>
                                <th class="px-4 py-2 text-left">Bank</th>
                                <th class="px-4 py-2 text-left">Rekening</th>
                                <th class="px-4 py-2 text-left">Harga</th>
                                <th class="px-4 py-2 text-left">Jumlah Koin</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-2 py-2 text-center w-36 pr-6">Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($catatanCashs as $item)
                                <tr class="border-b border-gray-300">
                                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2">{{ $item->no_referensi }}</td>
                                    <td class="px-4 py-2">{{ $item->user->username ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->pesanan ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->bank->nama_bank ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->bank->no_rek ?? '-' }}</td>
                                    <td class="px-4 py-2">Rp
                                        {{ number_format($item->hargaPembayaran->harga ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $item->hargaPembayaran->jumlah_koin ?? 0 }} Koin</td>

                                    <td
                                        class="px-4 py-2 font-semibold {{ $item->status == 'diterima' ? 'text-green-600' : 'text-red-500' }}">
                                        {{ ucfirst($item->status) }}
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <button class="text-blue-600 hover:underline"
                                            @click="
                                            detailCash = {
                                                id: {{ $item->id }},
                                                user: '{{ $item->user->username ?? '-' }}',
                                                email: '{{ $item->user->email ?? '-' }}',
                                                bank: '{{ $item->bank->nama_bank ?? '-' }}',
                                                rekening: '{{ $item->bank->no_rek ?? '-' }}',
                                                harga: '{{ number_format($item->hargaPembayaran->harga ?? 0, 0, ',', '.') }}',
                                                koin: '{{ $item->hargaPembayaran->jumlah_koin ?? 0 }}',
                                                status: '{{ ucfirst($item->status) }}',
                                                tanggal: '{{ $item->created_at->format('d M Y H:i') }}'
                                            };
                                            openCashModal = true;
                                    ">
                                            <i class="ph ph-file-arrow-up text-3xl sm:text-4xl"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>



            {{-- ====================== MODAL DETAIL KOIN ====================== --}}
            <div x-show="openKoinModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
                x-transition>
                <div class="bg-white rounded-2xl shadow-lg w-[90%] max-w-[400px] p-4 sm:p-6 relative">
                    <h2 class="text-xl font-semibold text-center mb-6">Transaksi Koin</h2>

                    <div class="flex justify-center mb-6">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#00509d]" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <div class="text-sm space-y-2">
                        <div class="flex justify-between"><span class="font-semibold">No. Transaksi</span><span
                                x-text="detailKoin.id"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">No. Referensi</span><span
                                x-text="detailKoin.referensi"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">Nama Pengguna</span><span
                                x-text="detailKoin.user"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">Dari</span><span
                                x-text="detailKoin.dari"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">Sumber Dana</span><span
                                x-text="detailKoin.sumber"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">Total Koin</span><span
                                x-text="detailKoin.total + ' Koin'"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">Tanggal</span><span
                                x-text="detailKoin.tanggal"></span></div>
                    </div>

                    <div class="flex justify-center mt-8">
                        <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="w-14 sm:w-16">
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="openKoinModal = false" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>



            {{-- ====================== MODAL DETAIL CASH ====================== --}}
            <div x-show="openCashModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
                x-transition>
                <div class="bg-white rounded-2xl shadow-lg w-[90%] max-w-[400px] p-4 sm:p-6 relative">

                    <h2 class="text-xl font-semibold text-center mb-6"
                        x-text="
        detailCash.status == 'Diterima'
            ? 'Top Up Berhasil'
            : detailCash.status == 'Ditolak'
                ? 'Top Up Ditolak'
                : detailCash.status == 'Expired'
                    ? 'Top Up Kedaluwarsa'
                    : 'Menunggu Verifikasi'
    ">
                    </h2>


                    <div class="flex justify-center mb-6">
                        <template x-if="detailCash.status == 'Diterima'">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#00509d]" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </template>

                        <template x-if="detailCash.status == 'Ditolak'">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-red-500" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </template>

                        <template x-if="detailCash.status == 'Pending'">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-500 animate-spin" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                    </path>
                                </svg>
                            </div>
                        </template>

                        <template x-if="detailCash.status == 'Expired'">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-red-100 flex items-center justify-center">

                                <!-- Icon Expired -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 sm:w-8 sm:h-8 text-red-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                </svg>
                            </div>
                        </template>

                    </div>

                    <div class="text-sm space-y-2">
                        <div class="flex justify-between">
                            <span class="font-semibold">No. Transaksi</span>
                            <span x-text="detailCash.id"></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Status</span>
                            <span
                                :class="detailCash.status == 'Diterima' ? 'bg-[#00509d]' :
                                    (detailCash.status == 'Ditolak' ? 'bg-red-500' : 'bg-gray-500')"
                                class="text-white text-xs px-3 py-1 rounded-full" x-text="detailCash.status"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="font-semibold">Nama Pengirim</span>
                            <span x-text="detailCash.user"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="font-semibold">Nama Penerima</span>
                            <span>Area Kerja</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="font-semibold">Metode Pembayaran</span>
                            <span x-text="detailCash.bank"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="font-semibold">Tgl/Waktu</span>
                            <span x-text="detailCash.tanggal"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="font-semibold">Nominal</span>
                            <span>Rp <span x-text="detailCash.harga"></span></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="font-semibold">Biaya Admin</span>
                            <span>Rp. 2.500</span>
                        </div>

                        <div class="border-t border-dashed my-2"></div>

                        <div class="flex justify-between font-semibold">
                            <span>Total Pembayaran</span>
                            <span>Rp. <span x-text="parseInt(detailCash.harga.replaceAll('.', '')) + 2500"></span></span>
                        </div>
                    </div>

                    <div class="flex justify-center mt-8">
                        <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="w-14 sm:w-16">
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="openCashModal = false" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
@endsection

