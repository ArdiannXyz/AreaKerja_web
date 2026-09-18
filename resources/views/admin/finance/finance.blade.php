@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{
        openNotif: false,
        openAllNotif: false,
        openKoinModal: false,
        detailKoin: {
            id: '',
            referensi: '',
            user: '',
            dari: '',
            sumber: '',
            total: '',
            tanggal: ''
        }
    }" x-cloak>

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-coins text-[#00509d] text-2xl"></i> Data Transaksi Koin
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Kelola dan pantau riwayat transaksi koin pengguna</p>
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
                    class="bg-[#00509d] text-white border-[#00509d] px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap shadow-xs">
                    <i class="ph ph-coins mr-1"></i> Transaksi Koin
                </a>
                <a href="{{ url('/admin/finance/tunai') }}"
                    class="bg-white text-slate-600 border-slate-200 hover:bg-slate-50 px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
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
                            <th class="px-5 py-4">Jenis Transaksi</th>
                            <th class="px-5 py-4">Dari</th>
                            <th class="px-5 py-4">Sumber Dana</th>
                            <th class="px-5 py-4 text-right">Total Koin</th>
                            <th class="px-5 py-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse ($koin as $index => $item)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-500">{{ $index + 1 }}</td>

                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-slate-800">
                                    {{ $item->no_referensi ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                        {{ $item->pesanan ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-3.5 text-slate-700 font-semibold">
                                    {{ $item->dari ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $item->sumber_dana ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-right font-extrabold text-amber-600">
                                    {{ number_format($item->total, 0, ',', '.') }} <span class="text-xs font-medium text-slate-400">Koin</span>
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    <button type="button"
                                        class="inline-flex items-center justify-center bg-[#00509d] hover:bg-[#003d7a] text-white p-2 rounded-lg transition"
                                        title="Lihat Detail Transaksi"
                                        @click="
                                            detailKoin = {
                                                id: '{{ $item->id }}',
                                                referensi: '{{ $item->no_referensi ?? '-' }}',
                                                user: '{{ $item->user->username ?? '-' }}',
                                                dari: '{{ $item->dari ?? '-' }}',
                                                sumber: '{{ $item->sumber_dana ?? '-' }}',
                                                total: '{{ number_format($item->total ?? 0, 0, ',', '.') }}',
                                                tanggal: '{{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}'
                                            };
                                            openKoinModal = true;
                                        ">
                                        <i class="ph ph-receipt text-base"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <i class="ph ph-coins text-4xl mb-2 block"></i>
                                    Belum ada data transaksi koin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL DETAIL KOIN --}}
        <div x-show="openKoinModal" x-cloak
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center z-50 p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <!-- Container Modal -->
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative border border-slate-100"
                @click.outside="openKoinModal = false">

                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="ph ph-receipt text-[#00509d] text-xl"></i> Detail Transaksi Koin
                    </h2>
                    <button @click="openKoinModal = false" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <div class="text-xs space-y-3">
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">ID Transaksi:</span>
                        <span x-text="detailKoin.id" class="font-mono font-bold text-slate-800"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">No. Referensi:</span>
                        <span x-text="detailKoin.referensi" class="font-mono font-bold text-[#00509d]"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Nama Pengguna:</span>
                        <span x-text="detailKoin.user" class="font-semibold text-slate-800"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Dari:</span>
                        <span x-text="detailKoin.dari" class="font-semibold text-slate-800"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Sumber Dana:</span>
                        <span x-text="detailKoin.sumber" class="font-semibold text-slate-800"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-amber-50/70 border border-amber-200/60">
                        <span class="text-amber-800 font-semibold">Total Koin:</span>
                        <span x-text="detailKoin.total + ' Koin'" class="font-extrabold text-amber-700 text-sm"></span>
                    </div>

                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium">Tanggal:</span>
                        <span x-text="detailKoin.tanggal" class="font-semibold text-slate-600"></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="openKoinModal = false"
                        class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </main>
@endsection
