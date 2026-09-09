@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-gray-50/50 min-h-screen" 
        x-data="{ 
            openNotif: false, 
            openAllNotif: false,
            currentTab: '{{ request('tab', request('bulan') ? 'laporan' : 'paket_harga') }}'
        }">

        {{-- Topbar Header --}}
        <div class="flex justify-between items-center mb-6 flex-col sm:flex-row gap-4 sm:gap-0 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Finance & Keuangan
                </h1>
                <p class="text-sm text-gray-500 mt-1">Kelola paket harga, riwayat transaksi, dan laporan pendapatan</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        {{-- Navigasi Tab Modern --}}
        <div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
            <div class="inline-flex p-1.5 bg-gray-100 rounded-xl space-x-1">
                <button type="button" @click="currentTab = 'paket_harga'"
                    :class="currentTab === 'paket_harga' ? 'bg-[#00509d] text-white shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                    class="px-5 py-2 text-sm rounded-lg transition-all duration-200">
                    Paket Harga
                </button>
                <button type="button" @click="currentTab = 'riwayat'"
                    :class="currentTab === 'riwayat' ? 'bg-[#00509d] text-white shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                    class="px-5 py-2 text-sm rounded-lg transition-all duration-200">
                    Riwayat Transaksi
                </button>
                <button type="button" @click="currentTab = 'laporan'"
                    :class="currentTab === 'laporan' ? 'bg-[#00509d] text-white shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                    class="px-5 py-2 text-sm rounded-lg transition-all duration-200">
                    Laporan Keuangan
                </button>
            </div>
        </div>

        {{-- ================= TAB 1: PAKET HARGA ================= --}}
        <div x-show="currentTab === 'paket_harga'" x-cloak class="space-y-8">
            {{-- Paket Harga Koin --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Paket Harga Koin</h2>
                        <p class="text-xs text-gray-500">Daftar tarif penggunaan koin untuk fitur perusahaan</p>
                    </div>
                    <a href="{{ route('superadmin.paket-harga.edit-koin') }}"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Edit Harga Koin
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                            <tr>
                                <th class="text-left px-5 py-3">Nama Layanan</th>
                                <th class="text-right px-5 py-3">Tarif Koin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($koin as $k)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3 text-gray-800 font-medium">{{ $k->nama }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-[#00509d]">
                                        {{ number_format($k->harga, 0, ',', '.') }} Koin
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-gray-500">Belum ada paket koin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paket Harga Pembayaran --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Paket Top Up & Pembayaran</h2>
                        <p class="text-xs text-gray-500">Daftar harga nominal top up koin Area Kerja</p>
                    </div>
                    <a href="{{ route('superadmin.paket-harga.edit-pembayaran') }}"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-5 py-2 rounded-xl transition duration-150 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Edit Harga Pembayaran
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                            <tr>
                                <th class="text-left px-5 py-3">Nama Paket</th>
                                <th class="text-right px-5 py-3">Harga (IDR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($pembayaran as $p)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3 text-gray-800 font-medium">{{ $p->nama }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-emerald-600">
                                        Rp {{ number_format($p->harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-gray-500">Belum ada paket pembayaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ================= TAB 2: RIWAYAT TRANSAKSI ================= --}}
        <div x-show="currentTab === 'riwayat'" x-cloak class="space-y-8">
            {{-- Riwayat Tunai --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="mb-4 pb-3 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-800">Riwayat Transaksi Tunai (Cash)</h2>
                    <p class="text-xs text-gray-500">Semua catatan transaksi pembayaran uang masuk</p>
                </div>

                <div x-data="{ openDetail: false, selected: {} }" class="rounded-xl overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-sm">
                            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left">No</th>
                                    <th class="px-4 py-3 text-left">No. Referensi</th>
                                    <th class="px-4 py-3 text-left">Jenis Pesanan</th>
                                    <th class="px-4 py-3 text-left">Dari</th>
                                    <th class="px-4 py-3 text-left">Sumber Dana</th>
                                    <th class="px-4 py-3 text-right">Nominal</th>
                                    <th class="px-4 py-3 text-center">Detail</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($cashTerbaru as $index => $c)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $c->no_referensi ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $c->pesanan ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $c->dari ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $c->sumberDana ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                            Rp {{ number_format($c->total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button"
                                                @click="selected = {
                                                    no_referensi: '{{ $c->no_referensi }}',
                                                    status: '{{ ucfirst($c->status) }}',
                                                    jenis: '{{ $c->pesanan }}',
                                                    pengirim: '{{ $c->dari }}',
                                                    penerima: 'Area Kerja',
                                                    metode: '{{ $c->sumberDana }}',
                                                    waktu: '{{ $c->created_at ? $c->created_at->format('d M Y H:i') : '-' }} WIB',
                                                    nominal: '{{ number_format($c->total, 0, ',', '.') }}'
                                                }; openDetail = true"
                                                class="p-1.5 rounded-lg text-[#00509d] hover:bg-blue-50 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($c->status == 'diterima')
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Diterima</span>
                                            @elseif($c->status == 'ditolak')
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">Ditolak</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">{{ ucfirst($c->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-6 text-gray-500">Belum ada transaksi tunai.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Modal Detail Transaksi Tunai --}}
                    <div x-show="openDetail" x-cloak
                        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-2xl shadow-xl w-full sm:w-[420px] max-w-full p-6 relative overflow-y-auto max-h-[90vh]">
                            <button @click="openDetail = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold">✕</button>

                            <div class="text-center mb-5">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-[#00509d] flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Rincian Transaksi</h3>
                                <p class="text-xs text-gray-500" x-text="selected.no_referensi"></p>
                            </div>

                            <div class="text-xs space-y-2.5 bg-gray-50 p-4 rounded-xl mb-5">
                                <div class="flex justify-between"><span class="text-gray-500">Status</span><span class="font-bold text-[#00509d]" x-text="selected.status"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Jenis Pesanan</span><span class="font-medium text-gray-800" x-text="selected.jenis"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Pengirim</span><span class="font-medium text-gray-800" x-text="selected.pengirim"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Penerima</span><span class="font-medium text-gray-800" x-text="selected.penerima"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Metode</span><span class="font-medium text-gray-800" x-text="selected.metode"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Waktu</span><span class="font-medium text-gray-800" x-text="selected.waktu"></span></div>
                                <div class="border-t border-dashed border-gray-300 pt-2 flex justify-between font-bold text-sm text-gray-900">
                                    <span>Total Nominal</span>
                                    <span class="text-[#00509d]" x-text="'Rp ' + selected.nominal"></span>
                                </div>
                            </div>

                            <div class="text-center pb-2">
                                <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="mx-auto h-8 opacity-70">
                                <p class="text-[10px] text-gray-400 mt-1">Area Kerja Super Admin Finance</p>
                            </div>

                            <button @click="openDetail = false"
                                class="w-full mt-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Koin --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="mb-4 pb-3 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-800">Riwayat Mutasi Koin</h2>
                    <p class="text-xs text-gray-500">Catatan perputaran dan pemakaian koin oleh pengguna</p>
                </div>

                <div x-data="{ openDetailKoin: false, selectedKoin: {} }" class="rounded-xl overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-sm">
                            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left">No</th>
                                    <th class="px-4 py-3 text-left">No. Referensi</th>
                                    <th class="px-4 py-3 text-left">Jenis Pesanan</th>
                                    <th class="px-4 py-3 text-left">Dari</th>
                                    <th class="px-4 py-3 text-left">Sumber Dana</th>
                                    <th class="px-4 py-3 text-right">Total Koin</th>
                                    <th class="px-4 py-3 text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($koinTerbaru as $index => $koinItem)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $koinItem->no_referensi ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $koinItem->pesanan ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $koinItem->dari ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $koinItem->sumber_dana ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-[#00509d]">{{ $koinItem->total }} Koin</td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button"
                                                @click="selectedKoin = {
                                                    no_referensi: '{{ $koinItem->no_referensi }}',
                                                    jenis: '{{ $koinItem->pesanan }}',
                                                    dari: '{{ $koinItem->dari }}',
                                                    sumber_dana: '{{ $koinItem->sumber_dana }}',
                                                    nominal: '{{ number_format((int) str_replace('-', '', $koinItem->total), 0, ',', '.') }}',
                                                    waktu: '{{ $koinItem->created_at ? $koinItem->created_at->format('d M Y H:i') : '-' }} WIB'
                                                }; openDetailKoin = true"
                                                class="p-1.5 rounded-lg text-[#00509d] hover:bg-blue-50 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-6 text-gray-500">Belum ada mutasi koin.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Modal Detail Koin --}}
                    <div x-show="openDetailKoin" x-cloak
                        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-2xl shadow-xl w-full sm:w-[400px] max-w-full p-6 relative overflow-y-auto max-h-[90vh]">
                            <button @click="openDetailKoin = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold">✕</button>

                            <div class="text-center mb-5">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-[#00509d] flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Detail Transaksi Koin</h3>
                                <p class="text-xs text-gray-500" x-text="selectedKoin.no_referensi"></p>
                            </div>

                            <div class="text-xs space-y-2.5 bg-gray-50 p-4 rounded-xl mb-5">
                                <div class="flex justify-between"><span class="text-gray-500">Jenis Pesanan</span><span class="font-medium text-gray-800" x-text="selectedKoin.jenis"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Dari</span><span class="font-medium text-gray-800" x-text="selectedKoin.dari"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Sumber Dana</span><span class="font-medium text-gray-800" x-text="selectedKoin.sumber_dana"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Waktu</span><span class="font-medium text-gray-800" x-text="selectedKoin.waktu"></span></div>
                                <div class="border-t border-dashed border-gray-300 pt-2 flex justify-between font-bold text-sm text-gray-900">
                                    <span>Total Koin</span>
                                    <span class="text-[#00509d]" x-text="selectedKoin.nominal + ' Koin'"></span>
                                </div>
                            </div>

                            <div class="text-center pb-2">
                                <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="mx-auto h-8 opacity-70">
                                <p class="text-[10px] text-gray-400 mt-1">Area Kerja Super Admin Finance</p>
                            </div>

                            <button @click="openDetailKoin = false"
                                class="w-full mt-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= TAB 3: LAPORAN KEUANGAN ================= --}}
        <div x-show="currentTab === 'laporan'" x-cloak class="space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100 mb-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Catatan Transaksi Penghasilan</h3>
                        <p class="text-gray-500 text-xs mt-1">
                            Data transaksi rekapitulasi 12 bulan terakhir. Silakan pilih filter bulan dan tahun.
                        </p>
                    </div>

                    <!-- Filter Form -->
                    <form id="formFilterLaporan" method="GET" action="{{ route('superadmin.paket-harga') }}" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="laporan">
                        @php
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

                        <select name="bulan" onchange="document.getElementById('tahun_input').value = this.options[this.selectedIndex].getAttribute('data-tahun'); this.form.submit();"
                            class="bg-gray-50 text-gray-700 text-xs font-semibold px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00509d]">
                            @foreach ($listBulan as $item)
                                <option value="{{ $item['bulan'] }}" data-tahun="{{ $item['tahun'] }}"
                                    {{ $item['bulan'] == sprintf('%02d', $bulan) && $item['tahun'] == $tahun ? 'selected' : '' }}>
                                    {{ $item['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="tahun" id="tahun_input" value="{{ $tahun }}">
                    </form>
                </div>

                <!-- Table Laporan -->
                <div class="rounded-xl overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-[700px] w-full text-sm">
                            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                                <tr>
                                    <th class="px-5 py-3 text-left">Catatan Transaksi</th>
                                    <th class="px-5 py-3 text-right">Pendapatan Tunai</th>
                                    <th class="px-5 py-3 text-right">Total Koin</th>
                                    <th class="px-5 py-3 text-center">Tanggal</th>
                                    <th class="px-5 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($laporan as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-3 font-medium text-gray-800">{{ $item['catatan'] }}</td>
                                        <td class="px-5 py-3 text-right font-semibold text-emerald-600">
                                            Rp {{ number_format($item['pendapatan'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3 text-right font-semibold text-[#00509d]">
                                            {{ number_format($item['koin'], 0, ',', '.') }} Koin
                                        </td>
                                        <td class="px-5 py-3 text-center text-xs text-gray-500 font-medium">{{ $item['tanggal'] }}</td>
                                        <td class="px-5 py-3 text-center">
                                            <a href="{{ route('superadmin.laporan.detail', ['tanggal' => $item['tanggal']]) }}"
                                                title="Lihat Rincian Laporan"
                                                class="inline-flex p-1.5 rounded-lg text-[#00509d] hover:bg-blue-50 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
