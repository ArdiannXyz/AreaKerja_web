@extends('finance.sidebar.index')
@section('sidebar')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen">
        <!-- Top Header & Breadcrumb -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('finance.laporan') }}"
                       class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0"
                       title="Kembali">
                        <i class="ph ph-arrow-left text-base"></i>
                    </a>
                    <div>
                        <h1 class="text-base sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Rincian Laporan Transaksi Harian</h1>
                        <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Rincian {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                    <a href="{{ route('finance.laporan.unduh', ['tanggal' => $tanggal]) }}"
                        class="inline-flex items-center p-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-xs"
                        title="Unduh PDF">
                        <i class="ph ph-download-simple text-base"></i>
                    </a>
                    @include('finance.components.notif_button')
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('finance.laporan.unduh', ['tanggal' => $tanggal]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                    <i class="ph ph-download-simple text-base"></i>
                    <span>Unduh PDF</span>
                </a>
                @include('finance.components.notif_button')
                @include('finance.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Company & Meta Header Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-start gap-4">
                <img src="{{ asset('images/logoarea.png') }}" class="w-12 h-12 object-contain" alt="AreaKerja">
                <div>
                    <h2 class="text-lg font-black text-slate-900">areakerja.com</h2>
                    <p class="text-xs text-slate-500 max-w-md mt-0.5 leading-relaxed">
                        Jl. Laksda Adisucipto No.80, Ambarrukmo, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
                    </p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-1 text-xs w-full md:w-auto">
                <div class="flex items-center justify-between gap-4">
                    <span class="text-slate-400 font-medium">Tanggal Laporan:</span>
                    <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <span class="text-slate-400 font-medium">Finance Officer:</span>
                    <span class="font-bold text-slate-800">{{ Auth::user()->username ?? 'Finance' }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <span class="text-slate-400 font-medium">Email Petugas:</span>
                    <span class="font-semibold text-slate-600">{{ Auth::user()->email ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Table of Transactions -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-lg shrink-0 font-bold">
                        <i class="ph-fill ph-receipt"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Daftar Transaksi Tanggal Ini</h2>
                        <p class="text-xs text-slate-500">Seluruh penerimaan cash dan aktivitas mutasi koin</p>
                    </div>
                </div>

                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">
                    {{ $transaksi->count() }} Transaksi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm min-w-[750px]">
                    <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5 text-center w-12">No</th>
                            <th class="px-5 py-3.5">No. Referensi</th>
                            <th class="px-5 py-3.5">Pelanggan / Akun</th>
                            <th class="px-5 py-3.5">Jenis Transaksi</th>
                            <th class="px-5 py-3.5">Sumber Dana</th>
                            <th class="px-5 py-3.5 text-right">Nominal IDR</th>
                            <th class="px-5 py-3.5 text-right">Mutasi Koin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transaksi as $t)
                            <tr class="hover:bg-blue-50/20 transition">
                                <td class="px-5 py-3.5 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 font-mono tracking-tight text-xs">
                                    {{ $t->no_referensi ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    {{ $t->dari ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-700">
                                    {{ $t->pesanan ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 font-bold text-[11px] rounded-lg">
                                        {{ $t->sumber_dana ?? ($t->sumberDana ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right font-black text-slate-900">
                                    @if ($t->tipe == 'cash')
                                        <span class="text-emerald-700">Rp {{ number_format($t->total, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-black">
                                    @if ($t->tipe == 'koin')
                                        <span class="px-2 py-0.5 bg-rose-50 border border-rose-100 text-rose-700 rounded-md text-xs font-black">
                                            -{{ $t->total_koin }} Koin
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400 font-bold">
                                    Tidak ada transaksi pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Summary -->
            <div class="p-5 sm:p-6 bg-slate-50/80 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-6 text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-500 font-medium block">Total Pembayaran Tunai:</span>
                        <span class="text-base font-black text-slate-900">Rp {{ number_format($totalCash, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-l border-slate-200 pl-6">
                        <span class="text-slate-500 font-medium block">Total Volume Koin:</span>
                        <span class="text-base font-black text-amber-600">{{ $totalKoin }} Koin</span>
                    </div>
                </div>

                <a href="{{ route('finance.laporan') }}"
                    class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-bold rounded-xl transition text-center">
                    Kembali ke Daftar Laporan
                </a>
            </div>
        </div>

    </main>
@endsection

