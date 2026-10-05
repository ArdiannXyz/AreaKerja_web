@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6 min-h-screen bg-slate-50/70">

        <!-- ================= TOP NAVBAR & HEADER ================= -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div>
                    <h1 class="text-base sm:text-xl font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="ph ph-squares-four text-[#00509d] text-lg sm:text-2xl"></i> Dashboard Finance
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Selamat datang, <span class="text-[#00509d] font-semibold">{{ Auth::user()->username }}</span>! Ringkasan performa finansial AreaKerja.</p>
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

        <!-- ================= STATS / KPI CARDS ================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
            
            <!-- Card 1: Total Omset Cash -->
            <div class="bg-[#00509d] text-white rounded-2xl p-5 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:shadow-md transition"
                style="background: linear-gradient(135deg, #00509d 0%, #003366 100%);">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xs pointer-events-none"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-200">Total Omset Masuk</span>
                        <h3 class="text-xl sm:text-2xl font-black mt-1 tracking-tight">
                            Rp {{ number_format($totalOmset, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-white/20 text-white flex items-center justify-center shrink-0">
                        <i class="ph-fill ph-wallet text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-xs text-blue-100">
                    <span>Transaksi Sukses</span>
                    <span class="font-extrabold text-white">{{ $countDiterima }} Trx</span>
                </div>
            </div>

            <!-- Card 2: Total Transaksi Koin -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs relative flex flex-col justify-between hover:border-amber-300/80 transition">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Volume Transaksi Koin</span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1 tracking-tight">
                            {{ number_format($totalTransaksiKoin, 0, ',', '.') }} <span class="text-xs font-bold text-amber-500">Koin</span>
                        </h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-200 text-amber-500 flex items-center justify-center shrink-0">
                        <i class="ph-fill ph-coins text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                    <span>Penggunaan & Top Up</span>
                    <span class="font-extrabold text-slate-800">{{ $koin->count() }} Aktivitas</span>
                </div>
            </div>

            <!-- Card 3: Menunggu Verifikasi -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs relative flex flex-col justify-between hover:border-rose-300/80 transition">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Menunggu Verifikasi</span>
                        <h3 class="text-xl sm:text-2xl font-black text-rose-600 mt-1 tracking-tight">
                            {{ $countMenunggu }} <span class="text-xs font-bold text-slate-500">Transaksi</span>
                        </h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-50 border border-rose-200 text-rose-500 flex items-center justify-center shrink-0">
                        <i class="ph-fill ph-clock-countdown text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                    <span>Nominal Tertunda</span>
                    <span class="font-extrabold text-rose-600">Rp {{ number_format($totalNominalMenunggu, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>

        <!-- ================= VISUAL CHARTS SECTION (APEXCHARTS) ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Chart 1: Tren Omset & Transaksi Koin 6 Bulan Terakhir (2 Col Span) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="ph-fill ph-chart-line-up text-[#00509d] text-xl"></i>
                            <span>Tren Omset & Transaksi 6 Bulan Terakhir</span>
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">Pergerakan pendapatan tunai (Rp) dan volume koin perusahaan</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-bold">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#00509d]"></span>
                            <span class="text-slate-600">Omset Cash</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#f59e0b]"></span>
                            <span class="text-slate-600">Volume Koin</span>
                        </div>
                    </div>
                </div>

                <!-- ApexChart Container -->
                <div id="financeTrendChart" class="w-full h-72"></div>
            </div>

            <!-- Chart 2: Distribusi Metode Pembayaran (1 Col Span) -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="mb-4 pb-4 border-b border-slate-100">
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="ph-fill ph-pie-chart text-[#00509d] text-xl"></i>
                            <span>Metode Pembayaran</span>
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">Distribusi kanal transaksi yang digunakan</p>
                    </div>

                    <!-- Donut Chart Container -->
                    <div id="paymentMethodDonutChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2">
                    <a href="{{ route('finance.catatan') }}"
                        class="px-2 py-2 bg-blue-50 hover:bg-blue-100 text-[#00509d] font-bold text-[10px] sm:text-[11px] rounded-xl text-center transition">
                        Verifikasi Trx
                    </a>
                    <a href="{{ route('finance.omset') }}"
                        class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[10px] sm:text-[11px] rounded-xl text-center transition">
                        Rekap Omset
                    </a>
                    <a href="{{ route('finance.bank.index') }}"
                        class="px-2 py-2 bg-teal-50 hover:bg-teal-100 text-teal-700 font-bold text-[10px] sm:text-[11px] rounded-xl text-center transition">
                        Kelola Bank
                    </a>
                </div>
            </div>

        </div>

        <!-- ================= RECENT TRANSACTIONS PREVIEW ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Box 1: Transaksi Cash Terbaru -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-bold">
                            <i class="ph-fill ph-receipt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Transaksi Cash Terbaru</h3>
                            <p class="text-[11px] text-slate-400">5 riwayat deposit / cash terakhir</p>
                        </div>
                    </div>
                    <a href="{{ route('finance.catatan') }}" class="text-xs font-bold text-[#00509d] hover:text-[#003d7a] flex items-center gap-1 transition">
                        <span>Lihat Semua</span>
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($cashTerbaru as $item)
                        <div class="py-3 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <p class="font-extrabold text-slate-900 truncate">
                                    {{ $item->pesanan ?? 'Top Up Koin' }}
                                </p>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $item->no_referensi }} &bull; {{ $item->dari ?? 'Perusahaan' }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-black text-slate-900">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </p>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-extrabold rounded-full mt-1
                                    @if ($item->status == 'diterima') bg-emerald-100 text-emerald-700
                                    @elseif ($item->status == 'menunggu_verifikasi') bg-amber-100 text-amber-700
                                    @elseif ($item->status == 'pending') bg-blue-100 text-blue-700
                                    @else bg-rose-100 text-rose-700 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-xs">Belum ada transaksi cash.</div>
                    @endforelse
                </div>
            </div>

            <!-- Box 2: Transaksi Koin Terbaru -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <i class="ph-fill ph-coins text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Transaksi Koin Terbaru</h3>
                            <p class="text-[11px] text-slate-400">5 aktivitas perputaran koin</p>
                        </div>
                    </div>
                    <a href="{{ route('finance.catatan') }}" class="text-xs font-bold text-[#00509d] hover:text-[#003d7a] flex items-center gap-1 transition">
                        <span>Lihat Semua</span>
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($koinTerbaru as $item)
                        <div class="py-3 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <p class="font-extrabold text-slate-900 truncate">
                                    {{ $item->pesanan ?? 'Penggunaan Koin' }}
                                </p>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $item->no_referensi ?? '-' }} &bull; {{ $item->dari ?? 'Perusahaan' }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-black text-amber-600 flex items-center justify-end gap-1">
                                    <i class="ph-fill ph-coins text-xs"></i>
                                    <span>{{ $item->total }} Koin</span>
                                </p>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-xs">Belum ada transaksi koin.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- ================= APEXCHARTS SCRIPT ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data dari backend
            const chartMonths = {!! json_encode($chartMonths) !!};
            const chartOmset = {!! json_encode($chartOmset) !!};
            const chartKoin = {!! json_encode($chartKoin) !!};

            const paymentLabels = {!! json_encode($paymentLabels) !!};
            const paymentCounts = {!! json_encode($paymentCounts) !!};

            // 1. Chart Tren Omset & Koin
            const trendChartOptions = {
                series: [
                    {
                        name: 'Omset Cash (Rp)',
                        data: chartOmset
                    },
                    {
                        name: 'Volume Koin',
                        data: chartKoin
                    }
                ],
                chart: {
                    type: 'area',
                    height: 290,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: { show: false }
                },
                colors: ['#00509d', '#f59e0b'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: [3, 3]
                },
                xaxis: {
                    categories: chartMonths,
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '11px',
                            fontWeight: 600
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: [
                    {
                        labels: {
                            style: { colors: '#64748b', fontSize: '11px' },
                            formatter: function (val) {
                                if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + ' jt';
                                if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + ' rb';
                                return 'Rp ' + val;
                            }
                        }
                    },
                    {
                        opposite: true,
                        labels: {
                            style: { colors: '#f59e0b', fontSize: '11px' },
                            formatter: function (val) {
                                return val + ' Koin';
                            }
                        }
                    }
                ],
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light',
                    y: [
                        {
                            formatter: function (val) {
                                return 'Rp ' + Number(val).toLocaleString('id-ID');
                            }
                        },
                        {
                            formatter: function (val) {
                                return Number(val).toLocaleString('id-ID') + ' Koin';
                            }
                        }
                    ]
                }
            };

            const trendChart = new ApexCharts(document.querySelector("#financeTrendChart"), trendChartOptions);
            trendChart.render();

            // 2. Chart Distribusi Metode Pembayaran (Donut)
            const paymentDonutOptions = {
                series: paymentCounts.length > 0 ? paymentCounts : [1],
                labels: paymentLabels.length > 0 ? paymentLabels : ['Belum ada data'],
                chart: {
                    type: 'donut',
                    height: 230,
                    fontFamily: 'Poppins, sans-serif'
                },
                colors: ['#00509d', '#0284c7', '#0ea5e9', '#f59e0b', '#10b981', '#6366f1'],
                dataLabels: { enabled: false },
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    fontWeight: 600,
                    labels: { colors: '#475569' }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Trx',
                                    fontSize: '12px',
                                    fontWeight: 700,
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function (val) {
                            return val + ' Transaksi';
                        }
                    }
                }
            };

            const paymentDonutChart = new ApexCharts(document.querySelector("#paymentMethodDonutChart"), paymentDonutOptions);
            paymentDonutChart.render();
        });
    </script>
@endsection

