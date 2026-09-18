@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- ================= TOP NAVBAR & HEADER ================= -->
        @php
            use App\Models\CatatanCash;
            $notifCount = CatatanCash::where('status', 'menunggu_verifikasi')->count();
            $notifikasiCash = CatatanCash::where('status', 'menunggu_verifikasi')->latest()->take(5)->get();
            $totalPenghasilanBulan = $laporan->sum('total_penghasilan');
            $totalKoinBulan = $laporan->sum('total_koin');
            $totalTransaksiBulan = $laporan->sum('total_transaksi');
        @endphp

        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
            <!-- Title & Greeting -->
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Laporan Transaksi</h1>
                    <span class="px-2.5 py-0.5 bg-blue-100 text-[#00509d] text-xs font-extrabold rounded-full">
                        Rekapitulasi
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    Laporan penghasilan harian dan rekapan aktivitas transaksi finansial dalam 12 bulan terakhir.
                </p>
            </div>

            <!-- Right Controls: Date, Notif & Profile Navbar -->
            <div class="flex items-center gap-3">
                
                <!-- Date Pill -->
                <div class="hidden lg:flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200/80 rounded-xl text-xs font-bold text-slate-600 shadow-2xs">
                    <i class="ph ph-calendar-blank text-base text-[#00509d]"></i>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <!-- Notifikasi Dropdown -->
                <div x-data="{ notifOpen: false }" class="relative">
                    <button @click="notifOpen = !notifOpen"
                        class="relative w-10 h-10 rounded-xl bg-white border border-slate-200/80 hover:border-[#00509d]/50 hover:bg-blue-50/40 text-slate-700 flex items-center justify-center transition shadow-2xs focus:outline-none">
                        <i class="ph ph-bell text-xl"></i>

                        @if ($notifCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-600 text-[10px] font-black text-white shadow-xs animate-bounce">
                                {{ $notifCount > 9 ? '9+' : $notifCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifikasi Menu -->
                    <div x-show="notifOpen" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        @click.outside="notifOpen = false" x-cloak
                        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white shadow-2xl rounded-2xl border border-slate-100 overflow-hidden z-50">
                        
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="ph-fill ph-bell-ringing text-[#00509d] text-base"></i>
                                <span class="font-extrabold text-xs text-slate-800">Menunggu Verifikasi</span>
                            </div>
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 font-extrabold text-[10px] rounded-full">
                                {{ $notifCount }} Pending
                            </span>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 text-xs">
                            @forelse ($notifikasiCash as $notif)
                                <a href="{{ route('finance.catatan') }}" class="p-3 flex items-start gap-3 hover:bg-blue-50/40 transition group">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0 font-bold">
                                        <i class="ph ph-receipt text-base"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-900 group-hover:text-[#00509d] truncate">
                                            {{ $notif->pesanan ?? 'Top Up Koin' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 truncate">
                                            Dari: <span class="font-semibold text-slate-700">{{ $notif->dari ?? 'Pelanggan' }}</span>
                                        </p>
                                        <span class="inline-block font-extrabold text-[#00509d] mt-1 text-[11px]">
                                            Rp {{ number_format($notif->total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="py-8 text-center text-slate-400">
                                    <i class="ph ph-check-circle text-3xl text-emerald-500 mx-auto mb-1"></i>
                                    <p class="text-xs font-semibold">Semua transaksi sudah diverifikasi.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="p-2.5 border-t border-slate-100 bg-slate-50 text-center">
                            <a href="{{ route('finance.catatan') }}"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00509d] hover:text-[#003d7a] transition">
                                <span>Buka Halaman Catatan Transaksi</span>
                                <i class="ph ph-arrow-right font-bold"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Navbar Dropdown -->
                <div x-data="{ profileOpen: false }" class="relative">
                    <button @click="profileOpen = !profileOpen"
                        class="flex items-center gap-2.5 px-3 py-1.5 bg-white hover:bg-slate-50 border border-slate-200/80 rounded-2xl transition shadow-2xs group focus:outline-none">
                        
                        <div class="w-8 h-8 rounded-xl overflow-hidden bg-[#00509d] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                            @if (Auth::user()?->avatar)
                                <img class="w-full h-full object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
                            @else
                                <img class="w-full h-full object-cover"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'Finance') }}&background=00509d&color=fff&size=128" alt="Avatar">
                            @endif
                        </div>

                        <div class="text-left hidden sm:block leading-tight">
                            <span class="block text-xs font-black text-slate-800 group-hover:text-[#00509d] transition">
                                {{ Auth::user()->username }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400">Finance Officer</span>
                        </div>

                        <i class="ph ph-caret-down font-bold text-xs text-slate-400 group-hover:text-slate-600 transition"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        @click.outside="profileOpen = false" x-cloak
                        class="absolute right-0 mt-2 w-56 bg-white shadow-2xl rounded-2xl border border-slate-100 overflow-hidden z-50 py-1.5 divide-y divide-slate-100 text-xs">
                        
                        <!-- Header Box -->
                        <div class="px-4 py-3 bg-slate-50/70">
                            <p class="font-extrabold text-slate-900 truncate">{{ Auth::user()->username }}</p>
                            <p class="text-[11px] text-slate-500 font-medium truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 bg-blue-100 text-[#00509d] text-[10px] font-extrabold rounded-md">
                                Petugas Keuangan
                            </span>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('finance.dashboard') }}"
                                class="flex items-center gap-2.5 px-4 py-2 font-semibold text-slate-700 hover:bg-blue-50/60 hover:text-[#00509d] transition">
                                <i class="ph ph-squares-four text-base text-[#00509d]"></i>
                                <span>Dashboard Finance</span>
                            </a>
                            <a href="{{ route('finance.paket-harga') }}"
                                class="flex items-center gap-2.5 px-4 py-2 font-semibold text-slate-700 hover:bg-blue-50/60 hover:text-[#00509d] transition">
                                <i class="ph ph-tag text-base text-amber-600"></i>
                                <span>Paket Harga</span>
                            </a>
                            <a href="{{ route('finance.omset') }}"
                                class="flex items-center gap-2.5 px-4 py-2 font-semibold text-slate-700 hover:bg-blue-50/60 hover:text-[#00509d] transition">
                                <i class="ph ph-chart-line-up text-base text-[#00509d]"></i>
                                <span>Omset Perusahaan</span>
                            </a>
                            <a href="{{ route('finance.catatan') }}"
                                class="flex items-center gap-2.5 px-4 py-2 font-semibold text-slate-700 hover:bg-blue-50/60 hover:text-[#00509d] transition">
                                <i class="ph ph-receipt text-base text-emerald-600"></i>
                                <span>Catatan Transaksi</span>
                            </a>
                        </div>

                        <!-- Logout -->
                        <div class="pt-1">
                            <button type="button" @click="profileOpen = false; openLogoutModal();"
                                class="w-full text-left flex items-center gap-2.5 px-4 py-2 font-bold text-rose-600 hover:bg-rose-50 transition">
                                <i class="ph ph-sign-out text-base"></i>
                                <span>Keluar Akun</span>
                            </button>
                        </div>
                    </div>
                </div>

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

