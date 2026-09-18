@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6">

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
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Paket & Tarif Harga</h1>
                    <span class="px-2.5 py-0.5 bg-blue-100 text-[#00509d] text-xs font-extrabold rounded-full">
                        Keuangan
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    Kelola tarif penggunaan koin untuk pasang lowongan serta paket nominal top up koin AreaKerja.
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

        @if (session('info'))
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-2xl flex items-center gap-3 text-blue-800 text-sm font-semibold shadow-xs">
                <i class="ph-fill ph-info text-[#00509d] text-xl shrink-0"></i>
                <p>{{ session('info') }}</p>
            </div>
        @endif

        <!-- Banner Info Card -->
        <div style="background: linear-gradient(135deg, #00509d 0%, #002d5a 100%);"
            class="bg-[#00509d] text-white p-5 sm:p-6 rounded-3xl shadow-lg relative overflow-hidden border border-[#00509d]/30">
            <div class="absolute -right-6 -bottom-10 opacity-15 pointer-events-none text-white">
                <i class="ph-fill ph-tag text-9xl"></i>
            </div>
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 rounded-xl text-xs font-bold text-blue-50 mb-2.5 backdrop-blur-md">
                    <i class="ph-fill ph-sparkle text-amber-300 text-sm"></i>
                    <span>Pengaturan Tarif Layanan</span>
                </div>
                <h2 class="text-lg sm:text-xl font-black text-white tracking-tight">Manajemen Paket & Tarif Koin AreaKerja</h2>
                <p class="text-xs sm:text-sm text-blue-100/90 mt-1 font-normal leading-relaxed">
                    Atur nilai koin yang diperlukan untuk setiap tingkatan paket lowongan perusahaan serta harga beli paket koin (top up) untuk pelanggan.
                </p>
            </div>
        </div>

        <!-- Content Grid: 2 Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Card 1: Paket Harga Koin (Lowongan) -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-xl shrink-0 shadow-2xs">
                                <i class="ph-fill ph-coins"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900">Paket Pasang Lowongan</h2>
                                <p class="text-xs text-slate-500">Tarif koin berdasarkan kategori paket lowongan</p>
                            </div>
                        </div>

                        <a href="{{ route('finance.paket-harga.edit-koin') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-xs">
                            <i class="ph ph-pencil-simple text-sm"></i>
                            <span>Edit</span>
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-100">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3.5">Nama Paket Lowongan</th>
                                    <th class="px-4 py-3.5 text-right">Tarif Koin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($koin as $k)
                                    <tr class="hover:bg-blue-50/30 transition group">
                                        <td class="px-4 py-3.5 font-bold text-slate-800 flex items-center gap-2">
                                            @if (str_contains(strtolower($k->nama), 'gold') || str_contains(strtolower($k->nama), 'vip'))
                                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                            @elseif (str_contains(strtolower($k->nama), 'silver'))
                                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-amber-700"></span>
                                            @endif
                                            <span>{{ $k->nama }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-black text-[#00509d]">
                                            <span class="px-3 py-1 bg-blue-50 border border-blue-100 text-[#00509d] font-black rounded-xl text-xs sm:text-sm">
                                                {{ number_format($k->harga, 0, ',', '.') }} Koin
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-400 font-semibold text-xs">
                                            Belum ada paket lowongan tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                    <span>* Perubahan tarif akan langsung berlaku pada sistem posting lowongan.</span>
                </div>
            </div>

            <!-- Card 2: Paket Harga Pembayaran (Top Up) -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-xl shrink-0 shadow-2xs">
                                <i class="ph-fill ph-credit-card"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900">Paket Top Up Koin</h2>
                                <p class="text-xs text-slate-500">Harga nominal pembelian koin AreaKerja</p>
                            </div>
                        </div>

                        <a href="{{ route('finance.paket-harga.edit-pembayaran') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-xs">
                            <i class="ph ph-pencil-simple text-sm"></i>
                            <span>Edit</span>
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-100">
                        <table class="w-full text-left text-xs sm:text-sm">
                            <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3.5">Nama Paket Top Up</th>
                                    <th class="px-4 py-3.5 text-right">Harga (IDR)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($pembayaran as $p)
                                    <tr class="hover:bg-emerald-50/30 transition group">
                                        <td class="px-4 py-3.5 font-bold text-slate-800 flex items-center gap-2">
                                            <i class="ph ph-check-circle text-emerald-500"></i>
                                            <span>{{ $p->nama }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-black text-emerald-700">
                                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 font-black rounded-xl text-xs sm:text-sm">
                                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-400 font-semibold text-xs">
                                            Belum ada paket top up pembayaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                    <span>* Pelanggan dapat melakukan transfer sesuai nominal paket yang tertera.</span>
                </div>
            </div>

        </div>

    </div>
@endsection
