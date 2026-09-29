@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- ================= TOP NAVBAR & HEADER ================= -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div>
                    <h1 class="text-base sm:text-xl font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="ph ph-chart-line-up text-[#00509d] text-lg sm:text-2xl"></i> Omset Perusahaan
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Pantau pertumbuhan pendapatan, tren omset per periode, dan ekspor data laporan keuangan.</p>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- Card 1: Total Omset -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Omset</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                        Rp {{ number_format($totalOmset, 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium">Periode: {{ $periodeDipilih == 'current' ? 'Bulan Ini' : $periodeDipilih . ' Bulan Terakhir' }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                    <i class="ph-fill ph-chart-line-up"></i>
                </div>
            </div>

            <!-- Card 2: Rata-Rata Omset -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-Rata Bulanan</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                        Rp {{ number_format($rataRata, 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium">Rata-rata pendapatan per bulan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                    <i class="ph-fill ph-currency-circle-dollar"></i>
                </div>
            </div>

            <!-- Card 3: Jumlah Periode Bulan -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between sm:col-span-2 lg:col-span-1">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bulan Aktif</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                        {{ $omsetPerBulan->count() }} Bulan
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1 font-medium">Data rekapan tercatat</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                    <i class="ph-fill ph-calendar-check"></i>
                </div>
            </div>
        </div>

        <!-- ================= FILTER & ACTION TOOLBAR ================= -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Filter Periode Omset</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pilih rentang waktu untuk memfilter rincian pendapatan perusahaan</p>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <form method="GET" action="{{ route('finance.omset') }}" class="flex items-center gap-2 m-0 p-0 grow sm:grow-0">
                    <div class="relative grow sm:grow-0">
                        <select name="periode" onchange="this.form.submit()"
                            class="h-10 w-full sm:w-auto appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] cursor-pointer shadow-2xs">
                            <option value="current" {{ $periodeDipilih == 'current' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="1" {{ $periodeDipilih == '1' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                            <option value="3" {{ $periodeDipilih == '3' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                            <option value="5" {{ $periodeDipilih == '5' ? 'selected' : '' }}>5 Bulan Terakhir</option>
                            <option value="7" {{ $periodeDipilih == '7' ? 'selected' : '' }}>7 Bulan Terakhir</option>
                            <option value="9" {{ $periodeDipilih == '9' ? 'selected' : '' }}>9 Bulan Terakhir</option>
                            <option value="12" {{ $periodeDipilih == '12' ? 'selected' : '' }}>12 Bulan Terakhir (1 Tahun)</option>
                            <option value="24" {{ $periodeDipilih == '24' ? 'selected' : '' }}>24 Bulan Terakhir (2 Tahun)</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs font-bold"></i>
                    </div>
                </form>

                <a href="{{ route('finance.omset.unduh', ['periode' => $periodeDipilih]) }}"
                    class="h-10 inline-flex items-center justify-center gap-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-2xs shrink-0 whitespace-nowrap">
                    <i class="ph-bold ph-download-simple text-base"></i>
                    <span>Unduh PDF</span>
                </a>
            </div>
        </div>

        <!-- ================= VISUAL CHART CARD ================= -->
        @if ($omsetPerBulan->count() > 0)
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-lg shrink-0 font-bold">
                            <i class="ph-fill ph-chart-bar"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Grafik Tren Omset Bulanan</h2>
                            <p class="text-xs text-slate-500">Visualisasi pendapatan per bulan dalam periode yang dipilih</p>
                        </div>
                    </div>
                </div>

                <div id="omsetBarChart" class="w-full"></div>
            </div>
        @endif

        <!-- ================= TABLE DAFTAR OMSET PERUSAHAAN ================= -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-lg shrink-0 font-bold">
                        <i class="ph-fill ph-list-numbers"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Rincian Omset Per Bulan</h2>
                        <p class="text-xs text-slate-500">Rekap total pembayaran diterima per periode</p>
                    </div>
                </div>

                <span class="px-3 py-1 bg-blue-50 border border-blue-100 text-[#00509d] text-xs font-black rounded-xl w-fit">
                    Total: Rp {{ number_format($totalOmset, 0, ',', '.') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Periode Bulan</th>
                            <th class="px-6 py-4 text-center">Status Rekap</th>
                            <th class="px-6 py-4 text-right">Total Pendapatan (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($omsetPerBulan as $item)
                            <tr class="hover:bg-blue-50/30 transition group">
                                <td class="px-6 py-4 font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-extrabold group-hover:bg-[#00509d] group-hover:text-white transition">
                                        <i class="ph ph-calendar"></i>
                                    </div>
                                    <span>{{ $item['nama_bulan'] }} {{ $item['tahun'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Tercatat</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm sm:text-base font-black text-slate-900">
                                        Rp {{ number_format($item['total'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                    <i class="ph ph-chart-line text-4xl mx-auto mb-2 text-slate-300"></i>
                                    <p class="font-bold text-sm text-slate-600">Belum ada data omset pada periode ini.</p>
                                    <p class="text-xs text-slate-400 mt-1">Transaksi yang sudah berstatus 'Diterima' akan tercatat di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($omsetPerBulan->count() > 0)
                        <tfoot class="bg-slate-50/80 border-t border-slate-200 font-extrabold text-xs sm:text-sm">
                            <tr>
                                <td class="px-6 py-4 text-slate-700">Total Akumulasi</td>
                                <td class="px-6 py-4 text-center text-slate-500">{{ $omsetPerBulan->count() }} Periode</td>
                                <td class="px-6 py-4 text-right text-[#00509d] text-base font-black">
                                    Rp {{ number_format($totalOmset, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>

    @if ($omsetPerBulan->count() > 0)
        @php
            $chartCategories = $omsetPerBulan->pluck('nama_bulan')->reverse()->values()->toJson();
            $chartSeries = $omsetPerBulan->pluck('total')->reverse()->values()->toJson();
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const categories = {!! $chartCategories !!};
                const dataSeries = {!! $chartSeries !!};

                // Dynamic column width calculation based on category count
                const catCount = categories.length;
                let calculatedWidth = '45%';
                if (catCount === 1) {
                    calculatedWidth = '14%';
                } else if (catCount === 2) {
                    calculatedWidth = '22%';
                } else if (catCount <= 4) {
                    calculatedWidth = '32%';
                }

                const options = {
                    series: [{
                        name: 'Omset Perusahaan',
                        data: dataSeries
                    }],
                    chart: {
                        type: 'bar',
                        height: 240,
                        toolbar: { show: false },
                        fontFamily: 'Poppins, sans-serif'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            borderRadiusApplication: 'end',
                            columnWidth: calculatedWidth,
                            distributed: false,
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.15,
                            gradientToColors: ['#003366'],
                            inverseColors: false,
                            opacityFrom: 0.95,
                            opacityTo: 0.85,
                            stops: [0, 100]
                        }
                    },
                    colors: ['#00509d'],
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    },
                    xaxis: {
                        categories: categories,
                        labels: {
                            style: {
                                colors: '#64748b',
                                fontSize: '12px',
                                fontWeight: 600
                            }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                if (val >= 1000000) {
                                    return 'Rp ' + (val / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' jt';
                                }
                                return 'Rp ' + (val / 1000).toLocaleString('id-ID') + 'k';
                            },
                            style: {
                                colors: '#64748b',
                                fontSize: '11px',
                                fontWeight: 500
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4,
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return 'Rp ' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#omsetBarChart"), options);
                chart.render();
            });
        </script>
    @endif
@endsection

