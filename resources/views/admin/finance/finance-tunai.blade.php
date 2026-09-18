@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{
        openNotif: false,
        openAllNotif: false,
        showDetail: false,
        selected: null
    }" x-cloak>

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-money text-[#00509d] text-2xl"></i> Data Transaksi Tunai
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Kelola dan pantau riwayat transaksi tunai pengguna</p>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')

        <!-- Tabs & Filter Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

            <!-- Toggle Buttons -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ url('/admin/finance') }}"
                    class="bg-white text-slate-600 border-slate-200 hover:bg-slate-50 px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
                    <i class="ph ph-coins mr-1"></i> Transaksi Koin
                </a>
                <a href="{{ url('/admin/finance/tunai') }}"
                    class="bg-[#00509d] text-white border-[#00509d] px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap shadow-xs">
                    <i class="ph ph-money mr-1"></i> Transaksi Tunai
                </a>
            </div>

            <!-- Filter No Referensi -->
            <div class="w-full md:w-auto">
                <form method="GET" class="flex items-center gap-2">
                    <div class="relative w-full md:w-64">
                        <select name="no_referensi"
                            class="w-full pl-4 pr-10 h-10 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition shadow-xs appearance-none cursor-pointer text-slate-700">
                            <option value="">Semua No. Referensi</option>
                            @foreach ($noReferensiList as $ref)
                                <option value="{{ $ref }}" {{ ($selectedRef ?? '') == $ref ? 'selected' : '' }}>
                                    {{ $ref }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="ph ph-caret-down text-base leading-none"></i>
                        </div>
                    </div>

                    <button type="submit"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold px-5 h-10 rounded-xl transition shadow-xs flex items-center justify-center flex-shrink-0">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-4 text-center w-16">No</th>
                            <th class="px-5 py-4">No. Referensi</th>
                            <th class="px-5 py-4">Pesanan</th>
                            <th class="px-5 py-4">Dari</th>
                            <th class="px-5 py-4">Sumber Dana</th>
                            <th class="px-5 py-4 text-right">Total</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse ($cash as $index => $item)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-500">{{ $index + 1 }}</td>

                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-800">
                                    {{ $item->no_referensi ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-200/60">
                                        {{ $item->pesanan ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-3.5 text-slate-700 font-semibold">
                                    {{ $item->dari ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $item->sumberDana ?? ($item->sumber_dana ?? '-') }}
                                </td>

                                <td class="px-5 py-3.5 text-right font-extrabold text-emerald-600">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $st = strtolower($item->status ?? '');
                                    @endphp
                                    @if ($st === 'diterima' || $st === 'success' || $st === 'berhasil')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            Diterima
                                        </span>
                                    @elseif ($st === 'ditolak' || $st === 'failed' || $st === 'gagal')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Ditolak
                                        </span>
                                    @elseif ($st === 'expired' || $st === 'kadaluarsa')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                            {{ ucfirst($item->status ?? 'Pending') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    <button type="button"
                                        @click="selected = {{ Js::from($item) }}; showDetail = true"
                                        class="inline-flex items-center justify-center bg-[#00509d] hover:bg-[#003d7a] text-white p-2 rounded-lg transition"
                                        title="Lihat Detail Transaksi">
                                        <i class="ph ph-receipt text-base"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <i class="ph ph-money text-4xl mb-2 block"></i>
                                    Belum ada data transaksi tunai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ====================== MODAL DETAIL TRANSAKSI TUNAI ====================== -->
        <div x-show="showDetail" x-cloak
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center z-50 p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative border border-slate-100 overflow-y-auto max-h-[90vh]"
                @click.outside="showDetail = false">

                <!-- Header Modal -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="ph ph-receipt text-[#00509d] text-xl"></i>
                        <span x-text="
                            selected?.status?.toLowerCase() === 'diterima'
                                ? 'Detail Top Up (Berhasil)'
                                : selected?.status?.toLowerCase() === 'ditolak'
                                    ? 'Detail Top Up (Ditolak)'
                                    : selected?.status?.toLowerCase() === 'expired'
                                        ? 'Detail Top Up (Kedaluwarsa)'
                                        : 'Detail Top Up (Menunggu Verifikasi)'
                        "></span>
                    </h2>
                    <button @click="showDetail = false" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <!-- Detail List -->
                <div class="text-xs space-y-3">
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">No. Transaksi / ID:</span>
                        <span x-text="selected?.id" class="font-mono font-bold text-slate-800"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">No. Referensi:</span>
                        <span x-text="selected?.no_referensi ?? '-'" class="font-mono font-bold text-[#00509d]"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Status:</span>
                        <span
                            :class="
                                selected?.status?.toLowerCase() === 'diterima' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60' :
                                (selected?.status?.toLowerCase() === 'ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200/60' :
                                (selected?.status?.toLowerCase() === 'expired' ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-amber-50 text-amber-700 border-amber-200/60'))
                            "
                            class="px-2.5 py-1 rounded-full font-bold border text-xs capitalize"
                            x-text="selected?.status">
                        </span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Nama Pengirim:</span>
                        <span x-text="selected?.user?.username ?? selected?.dari ?? '-'" class="font-semibold text-slate-800"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Metode Pembayaran:</span>
                        <span x-text="selected?.sumberDana ?? selected?.sumber_dana ?? '-'" class="font-semibold text-slate-800"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Tanggal:</span>
                        <span x-text="selected?.created_at ? new Date(selected?.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : '-'" class="font-semibold text-slate-600"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Nominal:</span>
                        <span class="font-bold text-slate-800">Rp <span x-text="Number(selected?.total ?? 0).toLocaleString('id-ID')"></span></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/60">
                        <span class="text-emerald-800 font-semibold">Total Pembayaran (+ Admin):</span>
                        <span class="font-extrabold text-emerald-700 text-sm">Rp <span x-text="(Number(selected?.total ?? 0) + 2500).toLocaleString('id-ID')"></span></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="showDetail = false"
                        class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </main>
@endsection
