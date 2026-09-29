@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- ================= TOP NAVBAR & HEADER ================= -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div>
                    <h1 class="text-base sm:text-xl font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="ph ph-tag text-[#00509d] text-lg sm:text-2xl"></i> Paket & Tarif Harga
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Kelola tarif penggunaan koin untuk pasang lowongan serta paket nominal top up koin AreaKerja.</p>
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
