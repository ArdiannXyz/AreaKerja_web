@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 flex-1">
                <a href="{{ route('superadmin.paket-harga') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Finance / <span class="text-slate-500 font-medium">Detail Laporan</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Laporan Transaksi</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Report Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- Report Header: Logo + Download + User Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start gap-5 p-6 border-b border-slate-100">

                {{-- Logo & Alamat --}}
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <img src="{{ asset('images/logoarea.png') }}" class="w-10 h-10" alt="Logo AreaKerja">
                        <span class="text-[#00509d] font-bold text-lg">areakerja.com</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-xs">
                        Jl. Laksda Adisucipto No.80, Ambarrukmo, Caturtunggal, Kec.<br>
                        Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
                    </p>
                </div>

                {{-- Download + User Info --}}
                <div class="text-right">
                    <a href="{{ route('superadmin.laporan.unduh', ['tanggal' => $tanggal]) }}"
                       title="Unduh PDF"
                       class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-4 py-2 rounded-xl transition shadow-sm mb-3">
                        <i class="ph ph-download-simple text-base"></i>
                        Unduh PDF
                    </a>
                    <div class="text-xs text-slate-600 space-y-1 text-right">
                        <p><span class="font-semibold text-slate-500">Username :</span> {{ Auth::user()->username ?? '-' }}</p>
                        <p><span class="font-semibold text-slate-500">Email :</span> {{ Auth::user()->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="p-6">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Laporan Transaksi Penghasilan</h2>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4">Transaksi</th>
                                <th class="py-3 px-4">Dari</th>
                                <th class="py-3 px-4">Jenis</th>
                                <th class="py-3 px-4">Sumber Dana</th>
                                <th class="py-3 px-4">Nominal (IDR)</th>
                                <th class="py-3 px-4 text-center">Transaksi Koin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transaksi as $t)
                                <tr class="hover:bg-slate-50/60 transition duration-150">
                                    <td class="px-4 py-3 text-xs font-mono text-slate-600 break-all">{{ $t->no_referensi ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 text-xs">{{ $t->dari ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-700 text-xs">{{ $t->pesanan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-600 text-xs">{{ $t->sumber_dana ?? ($t->sumberDana ?? '-') }}</td>
                                    <td class="px-4 py-3 text-slate-800 font-medium text-xs">
                                        @if ($t->tipe == 'cash')
                                            Rp{{ number_format($t->total, 0, ',', '.') }}
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-700 text-xs">
                                        @if ($t->tipe == 'koin')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">
                                                {{ $t->total_koin }} Koin
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-slate-400 text-xs">
                                        Tidak ada transaksi pada tanggal ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary Footer -->
                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-3">
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 flex-1">
                        <i class="ph ph-currency-circle-dollar text-emerald-600 text-xl"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Total Tunai</p>
                            <p class="text-sm font-bold text-emerald-700">Rp{{ number_format($totalCash, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 flex-1">
                        <i class="ph ph-coins text-amber-600 text-xl"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Total Koin</p>
                            <p class="text-sm font-bold text-amber-700">{{ $totalKoin }} Koin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
