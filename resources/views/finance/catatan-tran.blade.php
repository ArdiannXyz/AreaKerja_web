@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6" 
         x-data="{ 
            openBukti: false, 
            openDetailCash: false,
            openDetailKoin: false,
            detailBukti: {},
            selectedCash: {},
            selectedKoin: {}
         }" 
         x-cloak>

        <!-- ================= TOP NAVBAR & HEADER ================= -->
        @php
            use App\Models\CatatanCash;
            $notifCount = CatatanCash::where('status', 'menunggu_verifikasi')->count();
            $notifikasiCash = CatatanCash::where('status', 'menunggu_verifikasi')->latest()->take(5)->get();
        @endphp

        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
            <!-- Title & Greeting -->
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Catatan Transaksi</h1>
                    <span class="px-2.5 py-0.5 bg-blue-100 text-[#00509d] text-xs font-extrabold rounded-full">
                        Riwayat
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    Kelola dan verifikasi catatan transaksi uang masuk (Cash) serta mutasi penggunaan Koin AreaKerja.
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
                            <a href="{{ route('finance.laporan') }}"
                                class="flex items-center gap-2.5 px-4 py-2 font-semibold text-slate-700 hover:bg-blue-50/60 hover:text-[#00509d] transition">
                                <i class="ph ph-file-text text-base text-amber-600"></i>
                                <span>Laporan Transaksi</span>
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

        <!-- Alerts -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm font-semibold shadow-xs">
                <i class="ph-fill ph-check-circle text-emerald-600 text-xl shrink-0"></i>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- ======================= SECTION 1: RIWAYAT TUNAI ======================= -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg shrink-0 font-bold">
                        <i class="ph-fill ph-receipt"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Riwayat Pembayaran Tunai (Cash)</h2>
                        <p class="text-xs text-slate-500">Daftar transaksi top up koin masuk yang perlu diverifikasi & dicatat</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">
                        {{ $catatanCash->count() }} Transaksi
                    </span>
                    <a href="{{ route('finance.detail.catatan.koin') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-2xs">
                        <i class="ph ph-list-magnifying-glass text-sm"></i>
                        <span>Lihat Semua Detail</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm min-w-[900px]">
                    <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">No. Referensi</th>
                            <th class="px-4 py-3.5">Jenis Pesanan</th>
                            <th class="px-4 py-3.5">Pelanggan</th>
                            <th class="px-4 py-3.5">Sumber Pembayaran</th>
                            <th class="px-4 py-3.5 text-right">Nominal / Koin</th>
                            <th class="px-4 py-3.5 text-center">Bukti</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-4 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($catatanCash as $item)
                            <tr class="hover:bg-blue-50/20 transition group">
                                <td class="px-4 py-3.5 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5 font-bold text-slate-900 font-mono tracking-tight text-xs">
                                    {{ $item->no_referensi }}
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-slate-800">
                                    {{ $item->pesanan ?? 'Top Up Koin' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-bold text-slate-800 block truncate max-w-[150px]">
                                        {{ $item->user->pelamar->nama_pelamar ?? ($item->user->username ?? $item->dari) }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $item->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 font-bold text-[11px] rounded-lg">
                                        <i class="ph ph-bank text-xs"></i>
                                        <span>{{ $item->sumberDana ?? ($item->bank->nama_bank ?? 'Transfer Bank') }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right font-black text-slate-900">
                                    @if ($item->hargaPembayaran && $item->hargaPembayaran->jumlah_koin > 0)
                                        <div class="text-[#00509d]">{{ $item->hargaPembayaran->jumlah_koin }} Koin</div>
                                    @endif
                                    <div class="text-xs text-slate-600 font-extrabold">
                                        Rp {{ number_format($item->total ?? ($item->hargaPembayaran->harga ?? 0), 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if ($item->bukti)
                                        <button type="button" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#00509d] font-bold text-xs rounded-xl border border-blue-200/80 transition"
                                            @click="
                                                detailBukti = { 
                                                    bukti: '{{ asset('storage/' . $item->bukti) }}', 
                                                    id: {{ $item->id }},
                                                    referensi: '{{ $item->no_referensi }}',
                                                    user: '{{ $item->user->username ?? $item->dari }}',
                                                    nominal: 'Rp {{ number_format($item->total ?? ($item->hargaPembayaran->harga ?? 0), 0, ',', '.') }}',
                                                    status: '{{ $item->status }}'
                                                }; 
                                                openBukti = true;
                                            ">
                                            <i class="ph ph-image text-sm font-bold"></i>
                                            <span>Lihat</span>
                                        </button>
                                    @else
                                        <span class="text-slate-300 font-bold text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if ($item->status == 'diterima')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold text-[11px] rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Diterima</span>
                                        </span>
                                    @elseif ($item->status == 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 border border-rose-200 text-rose-700 font-extrabold text-[11px] rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Ditolak</span>
                                        </span>
                                    @elseif ($item->status == 'menunggu_verifikasi' || $item->status == 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 font-extrabold text-[11px] rounded-lg animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Menunggu</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-600 font-bold text-[11px] rounded-lg">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <button type="button" 
                                        class="p-2 rounded-xl bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white transition"
                                        title="Rincian Transaksi"
                                        @click="
                                            selectedCash = {
                                                id: {{ $item->id }},
                                                referensi: '{{ $item->no_referensi }}',
                                                user: '{{ $item->user->username ?? '-' }}',
                                                email: '{{ $item->user->email ?? '-' }}',
                                                pesanan: '{{ $item->pesanan ?? '-' }}',
                                                bank: '{{ $item->bank->nama_bank ?? '-' }}',
                                                rekening: '{{ $item->bank->no_rek ?? '-' }}',
                                                sumber: '{{ $item->sumberDana ?? '-' }}',
                                                harga: 'Rp {{ number_format($item->total ?? ($item->hargaPembayaran->harga ?? 0), 0, ',', '.') }}',
                                                koin: '{{ $item->hargaPembayaran->jumlah_koin ?? 0 }} Koin',
                                                status: '{{ ucfirst($item->status) }}',
                                                tanggal: '{{ $item->created_at->format('d M Y H:i') }}',
                                                bukti: '{{ $item->bukti ? asset('storage/' . $item->bukti) : '' }}'
                                            };
                                            openDetailCash = true;
                                        ">
                                        <i class="ph ph-eye text-base font-bold"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                    <i class="ph ph-receipt text-4xl mx-auto mb-2 text-slate-300"></i>
                                    <p class="font-bold text-sm text-slate-600">Belum ada catatan transaksi tunai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- ======================= SECTION 2: RIWAYAT KOIN ======================= -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-lg shrink-0 font-bold">
                        <i class="ph-fill ph-coins"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Riwayat Transaksi Koin</h2>
                        <p class="text-xs text-slate-500">Catatan pemakaian & mutasi saldo koin perusahaan di AreaKerja</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">
                        {{ $catatanKoin->count() }} Mutasi Terakhir
                    </span>
                    <a href="{{ route('finance.detail.catatan.koin') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-2xs">
                        <i class="ph ph-list-magnifying-glass text-sm"></i>
                        <span>Lihat Semua Detail</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm min-w-[850px]">
                    <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">No. Referensi</th>
                            <th class="px-4 py-3.5">Jenis Transaksi / Pesanan</th>
                            <th class="px-4 py-3.5">Nama Akun</th>
                            <th class="px-4 py-3.5">Sumber Dana</th>
                            <th class="px-4 py-3.5 text-right">Jumlah Koin</th>
                            <th class="px-4 py-3.5">Tanggal</th>
                            <th class="px-4 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($catatanKoin as $item)
                            <tr class="hover:bg-blue-50/20 transition group">
                                <td class="px-4 py-3.5 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5 font-bold text-slate-900 font-mono tracking-tight text-xs">
                                    {{ $item->no_referensi ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-slate-800">
                                    {{ $item->pesanan ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5 font-bold text-slate-800">
                                    {{ $item->user->pelamar->nama_pelamar ?? ($item->user->username ?? $item->dari) }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 font-bold text-[11px] rounded-lg">
                                        <i class="ph ph-wallet text-xs"></i>
                                        <span>{{ $item->sumber_dana ?? 'Koin Perusahaan' }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right font-black text-rose-600">
                                    <span class="px-2.5 py-1 bg-rose-50 border border-rose-100 rounded-lg text-xs font-black">
                                        -{{ $item->total ?? 0 }} Koin
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-slate-500 font-medium text-xs">
                                    {{ $item->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <button type="button" 
                                        class="p-2 rounded-xl bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white transition"
                                        title="Rincian Mutasi"
                                        @click="
                                            selectedKoin = {
                                                id: {{ $item->id }},
                                                referensi: '{{ $item->no_referensi ?? '-' }}',
                                                user: '{{ $item->user->username ?? '-' }}',
                                                pesanan: '{{ $item->pesanan ?? '-' }}',
                                                dari: '{{ $item->dari ?? '-' }}',
                                                sumber: '{{ $item->sumber_dana ?? '-' }}',
                                                total: '{{ $item->total ?? 0 }}',
                                                tanggal: '{{ $item->created_at->format('d M Y H:i') }}'
                                            };
                                            openDetailKoin = true;
                                        ">
                                        <i class="ph ph-eye text-base font-bold"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                    <i class="ph ph-coins text-4xl mx-auto mb-2 text-slate-300"></i>
                                    <p class="font-bold text-sm text-slate-600">Belum ada catatan transaksi koin.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- ======================= MODAL 1: BUKTI PEMBAYARAN & VERIFIKASI ======================= -->
        <div x-show="openBukti" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            
            <div @click.outside="openBukti = false"
                 class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 animate-fadeIn">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Bukti Pembayaran</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Ref: <span class="font-mono font-bold text-[#00509d]" x-text="detailBukti.referensi"></span></p>
                    </div>
                    <button @click="openBukti = false" class="w-8 h-8 rounded-full bg-white hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition border border-slate-200">
                        <i class="ph ph-x font-bold"></i>
                    </button>
                </div>

                <!-- Modal Body: Image Preview -->
                <div class="p-5 bg-slate-100/60 flex items-center justify-center max-h-[420px] overflow-auto">
                    <template x-if="/\.pdf($|\?)/i.test(detailBukti.bukti)">
                        <embed :src="detailBukti.bukti" type="application/pdf" class="w-full h-80 rounded-2xl shadow-sm border border-slate-200" />
                    </template>
                    <template x-if="!(/\.pdf($|\?)/i.test(detailBukti.bukti))">
                        <img :src="detailBukti.bukti" alt="Bukti Transfer" class="max-h-72 object-contain rounded-2xl shadow-sm border border-slate-200 bg-white" />
                    </template>
                </div>

                <!-- Info Bar -->
                <div class="px-5 py-3 bg-white border-t border-slate-100 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Pelanggan</span>
                        <span class="font-extrabold text-slate-800" x-text="detailBukti.user"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-400 block font-medium">Nominal Transfer</span>
                        <span class="font-black text-emerald-600 text-sm" x-text="detailBukti.nominal"></span>
                    </div>
                </div>

                <!-- Modal Footer: Action Buttons -->
                <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                    <form :action="`{{ url('finance/verifikasi') }}/${detailBukti.id}`" method="POST" class="flex items-center gap-2">
                        @csrf
                        <button type="submit" name="action" value="terima"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                            <i class="ph-bold ph-check"></i>
                            <span>Terima Transaksi</span>
                        </button>
                        <button type="submit" name="action" value="tolak"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                            <i class="ph-bold ph-x"></i>
                            <span>Tolak</span>
                        </button>
                    </form>

                    <button type="button" @click="openBukti = false" 
                        class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>


        <!-- ======================= MODAL 2: RINCIAN TRANSAKSI TUNAI ======================= -->
        <div x-show="openDetailCash" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            
            <div @click.outside="openDetailCash = false"
                 class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 animate-fadeIn">
                
                <div class="p-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-bold text-sm">
                            <i class="ph-fill ph-receipt"></i>
                        </div>
                        <h3 class="text-base font-black text-slate-900">Rincian Transaksi Tunai</h3>
                    </div>
                    <button @click="openDetailCash = false" class="w-8 h-8 rounded-full bg-white hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition border border-slate-200">
                        <i class="ph ph-x font-bold"></i>
                    </button>
                </div>

                <div class="p-5 space-y-3 text-xs">
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">No. Referensi</span>
                        <span class="font-mono font-black text-slate-900" x-text="selectedCash.referensi"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Nama Akun</span>
                        <span class="font-bold text-slate-800" x-text="selectedCash.user"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Email</span>
                        <span class="font-semibold text-slate-700" x-text="selectedCash.email"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Jenis Pesanan</span>
                        <span class="font-bold text-slate-800" x-text="selectedCash.pesanan"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Sumber Dana / Bank</span>
                        <span class="font-bold text-slate-800" x-text="selectedCash.bank + ' (' + selectedCash.rekening + ')'"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Total Nominal</span>
                        <span class="font-black text-emerald-600 text-sm" x-text="selectedCash.harga"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Perolehan Koin</span>
                        <span class="font-extrabold text-[#00509d]" x-text="selectedCash.koin"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Tanggal Transaksi</span>
                        <span class="font-semibold text-slate-700" x-text="selectedCash.tanggal"></span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-500 font-medium">Status Verifikasi</span>
                        <span class="font-extrabold text-slate-900" x-text="selectedCash.status"></span>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <button type="button" @click="openDetailCash = false" 
                        class="px-5 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-2xs">
                        Tutup
                    </button>
                </div>
            </div>
        </div>


        <!-- ======================= MODAL 3: RINCIAN MUTASI KOIN ======================= -->
        <div x-show="openDetailKoin" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            
            <div @click.outside="openDetailKoin = false"
                 class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 animate-fadeIn">
                
                <div class="p-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-bold text-sm">
                            <i class="ph-fill ph-coins"></i>
                        </div>
                        <h3 class="text-base font-black text-slate-900">Rincian Pemakaian Koin</h3>
                    </div>
                    <button @click="openDetailKoin = false" class="w-8 h-8 rounded-full bg-white hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition border border-slate-200">
                        <i class="ph ph-x font-bold"></i>
                    </button>
                </div>

                <div class="p-5 space-y-3 text-xs">
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">No. Referensi</span>
                        <span class="font-mono font-black text-slate-900" x-text="selectedKoin.referensi"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Nama Akun</span>
                        <span class="font-bold text-slate-800" x-text="selectedKoin.user"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Jenis Layanan</span>
                        <span class="font-bold text-slate-800" x-text="selectedKoin.pesanan"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Sumber Pemotongan</span>
                        <span class="font-bold text-slate-800" x-text="selectedKoin.sumber"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Jumlah Koin Terpotong</span>
                        <span class="font-black text-rose-600 text-sm" x-text="'-' + selectedKoin.total + ' Koin'"></span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-500 font-medium">Tanggal Transaksi</span>
                        <span class="font-semibold text-slate-700" x-text="selectedKoin.tanggal"></span>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <button type="button" @click="openDetailKoin = false" 
                        class="px-5 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-2xs">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection

