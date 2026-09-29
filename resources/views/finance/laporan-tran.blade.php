@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- ================= TOP NAVBAR & HEADER ================= -->
        @php
            $totalPenghasilanBulan = $laporan->sum('total_penghasilan');
            $totalKoinBulan = $laporan->sum('total_koin');
            $totalTransaksiBulan = $laporan->sum('total_transaksi');
        @endphp

        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div>
                    <h1 class="text-base sm:text-xl font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="ph ph-file-text text-[#00509d] text-lg sm:text-2xl"></i> Laporan Transaksi
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Laporan penghasilan harian dan rekapan aktivitas transaksi finansial dalam 12 bulan terakhir.</p>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                    @include('finance.components.notif_button')
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('finance.components.notif_button')
                @include('finance.components.user_badge_dropdown')
            </div>
        </header>

        <!-- ================= STATS SUMMARY CARDS ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Card 1: Total Penghasilan Bulan -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Penghasilan ({{ $bulanList[$bulan] ?? 'Bulan' }})</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                        Rp {{ number_format($totalPenghasilanBulan, 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium">Total uang masuk cash</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                    <i class="ph-fill ph-money"></i>
                </div>
            </div>

            <!-- Card 2: Total Koin Digunakan -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Volume Koin</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                        {{ number_format($totalKoinBulan, 0, ',', '.') }} Koin
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium">Mutasi koin terpakai</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                    <i class="ph-fill ph-coins"></i>
                </div>
            </div>

            <!-- Card 3: Total Frekuensi Transaksi -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Frekuensi Transaksi</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                        {{ $totalTransaksiBulan }} Transaksi
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium">Akumulasi kejadian transaksi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                    <i class="ph-fill ph-receipt"></i>
                </div>
            </div>
        </div>

        <!-- ================= FILTER & ACTION TOOLBAR ================= -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Laporan Penghasilan Harian</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pilih periode bulan untuk melihat rincian transaksi per tanggal</p>
            </div>

            <form method="GET" action="{{ route('finance.laporan') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <select name="bulan" onchange="this.form.submit()"
                        class="w-full sm:w-auto appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl px-4 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] cursor-pointer shadow-2xs">
                        @foreach ($bulanList as $key => $nama)
                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                Bulan {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                    <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs font-bold"></i>
                </div>
            </form>
        </div>

        <!-- ================= TABEL LAPORAN TRANSAKSI ================= -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-lg shrink-0 font-bold">
                        <i class="ph-fill ph-calendar"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Rekap Harian - {{ $bulanList[$bulan] ?? 'Bulan' }}</h2>
                        <p class="text-xs text-slate-500">Klik tombol rincian untuk melihat daftar transaksi harian dan cetak PDF</p>
                    </div>
                </div>

                <span class="px-3 py-1 bg-blue-50 border border-blue-100 text-[#00509d] text-xs font-black rounded-xl">
                    {{ $laporan->count() }} Hari Tercatat
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm min-w-[700px]">
                    <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Tanggal Transaksi</th>
                            <th class="px-6 py-4 text-right">Penghasilan (IDR)</th>
                            <th class="px-6 py-4 text-center">Volume Koin</th>
                            <th class="px-6 py-4 text-center">Jumlah Transaksi</th>
                            <th class="px-6 py-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($laporan as $l)
                            <tr class="hover:bg-blue-50/20 transition group">
                                <td class="px-6 py-4 font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-extrabold group-hover:bg-[#00509d] group-hover:text-white transition">
                                        <i class="ph ph-calendar-check"></i>
                                    </div>
                                    <span>{{ \Carbon\Carbon::parse($l->tanggal)->translatedFormat('d F Y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-slate-900 text-sm">
                                    Rp {{ number_format($l->total_penghasilan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-amber-600">
                                    <span class="px-2.5 py-1 bg-amber-50 border border-amber-100 rounded-lg text-xs font-black">
                                        {{ $l->total_koin }} Koin
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 font-bold text-xs rounded-lg">
                                        {{ $l->total_transaksi }} Transaksi
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('finance.laporan.detail', ['tanggal' => $l->tanggal]) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold text-xs rounded-xl transition shadow-2xs">
                                        <i class="ph ph-arrow-square-out text-sm"></i>
                                        <span>Rincian</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <i class="ph ph-file-dashed text-4xl mx-auto mb-2 text-slate-300"></i>
                                    <p class="font-bold text-sm text-slate-600">Tidak ada data transaksi pada bulan {{ $bulanList[$bulan] ?? '' }}.</p>
                                    <p class="text-xs text-slate-400 mt-1">Pilih periode bulan lain pada dropdown di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($laporan->count() > 0)
                        <tfoot class="bg-slate-50/80 border-t border-slate-200 font-extrabold text-xs sm:text-sm">
                            <tr>
                                <td class="px-6 py-4 text-slate-700">Total Akumulasi</td>
                                <td class="px-6 py-4 text-right text-[#00509d] text-base font-black">
                                    Rp {{ number_format($totalPenghasilanBulan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center text-amber-600 font-black">
                                    {{ number_format($totalKoinBulan, 0, ',', '.') }} Koin
                                </td>
                                <td class="px-6 py-4 text-center text-slate-700">
                                    {{ $totalTransaksiBulan }} Transaksi
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>
@endsection

