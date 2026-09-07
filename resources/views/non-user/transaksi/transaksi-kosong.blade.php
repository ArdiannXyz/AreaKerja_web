@extends('layouts.index')
@section('content')

    <div class="bg-slate-50 min-h-screen text-slate-800 pt-24 sm:pt-28 md:pt-32 pb-20"
        x-data="{
            searchQuery: '',
            statusFilter: 'all',
            matches(t) {
                const matchStatus = this.statusFilter === 'all' || 
                    (this.statusFilter === 'menunggu' && (t.status === 'menunggu_verifikasi' || t.status === 'pending')) ||
                    (this.statusFilter === 'sukses' && t.status === 'diterima') ||
                    (this.statusFilter === 'gagal' && (t.status === 'ditolak' || t.status === 'expired'));
                
                const q = this.searchQuery.toLowerCase().trim();
                const matchQuery = q === '' || 
                    t.ref.toLowerCase().includes(q) || 
                    t.pesanan.toLowerCase().includes(q) ||
                    t.bank.toLowerCase().includes(q);

                return matchStatus && matchQuery;
            }
        }">

        {{-- Top Title Header Container (Rounded) --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 mb-8">
            <div class="bg-white border border-slate-200/80 rounded-2xl md:rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-bold text-[#00509d] text-xl md:text-2xl">
                        Riwayat Transaksi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Pantau status pembayaran, tagihan pendaftaran kandidat, dan riwayat transfer Anda.
                    </p>
                </div>
                <a href="{{ route('pelamar.daftar-kandidat') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#00509d] hover:text-[#003d7a] bg-sky-50 hover:bg-sky-100 px-4 py-2.5 rounded-xl transition shadow-xs shrink-0">
                    <i class="ph ph-star text-base"></i>
                    <span>Daftar Kandidat Baru</span>
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            @if (isset($transaksi) && $transaksi->count() > 0)
                {{-- Filter Bar --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">
                    
                    {{-- Status Filter Tabs --}}
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar text-xs font-semibold">
                        <button type="button" @click="statusFilter = 'all'"
                            :class="statusFilter === 'all' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                            class="px-3.5 py-2 rounded-xl transition cursor-pointer shrink-0">
                            Semua ({{ $transaksi->count() }})
                        </button>
                        <button type="button" @click="statusFilter = 'menunggu'"
                            :class="statusFilter === 'menunggu' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                            class="px-3.5 py-2 rounded-xl transition cursor-pointer shrink-0">
                            Menunggu ({{ $transaksi->filter(fn($t) => in_array($t->status, ['menunggu_verifikasi', 'pending']))->count() }})
                        </button>
                        <button type="button" @click="statusFilter = 'sukses'"
                            :class="statusFilter === 'sukses' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                            class="px-3.5 py-2 rounded-xl transition cursor-pointer shrink-0">
                            Berhasil ({{ $transaksi->where('status', 'diterima')->count() }})
                        </button>
                        <button type="button" @click="statusFilter = 'gagal'"
                            :class="statusFilter === 'gagal' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                            class="px-3.5 py-2 rounded-xl transition cursor-pointer shrink-0">
                            Ditolak / Expired ({{ $transaksi->filter(fn($t) => in_array($t->status, ['ditolak', 'expired']))->count() }})
                        </button>
                    </div>

                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-64">
                        <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text"
                            x-model="searchQuery"
                            placeholder="Cari no. referensi..."
                            class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                    </div>
                </div>

                {{-- Table Container Card (Rounded) --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl md:rounded-3xl shadow-sm overflow-hidden mb-12">
                    
                    {{-- Desktop Table View --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-5">No. Referensi</th>
                                    <th class="py-3.5 px-4">Metode Bayar</th>
                                    <th class="py-3.5 px-4">Pesanan</th>
                                    <th class="py-3.5 px-4">Total</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4">Tanggal</th>
                                    <th class="py-3.5 px-5 text-right">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                                @foreach ($transaksi as $t)
                                    @php
                                        $status = strtolower($t->status);
                                        $bankName = $t->bank->nama_bank ?? 'QRIS';
                                    @endphp
                                    <tr x-show="matches({
                                            status: '{{ $status }}',
                                            ref: '{{ $t->no_referensi }}',
                                            pesanan: '{{ addslashes($t->pesanan ?? '') }}',
                                            bank: '{{ addslashes($bankName) }}'
                                        })"
                                        class="hover:bg-blue-50/40 transition group cursor-pointer"
                                        onclick="window.location='{{ route('kandidat.transaksi', $t->id) }}'">
                                        
                                        {{-- No Referensi --}}
                                        <td class="py-4 px-5">
                                            <a href="{{ route('kandidat.transaksi', $t->id) }}"
                                                class="font-mono font-bold text-[#00509d] hover:underline group-hover:text-[#003d7a] inline-flex items-center gap-1.5">
                                                <i class="ph ph-receipt text-sm"></i>
                                                <span>{{ $t->no_referensi }}</span>
                                            </a>
                                        </td>

                                        {{-- Bank / Metode --}}
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-semibold text-[11px] border border-slate-200">
                                                <i class="ph-bold ph-credit-card text-xs text-slate-500"></i>
                                                {{ $bankName }}
                                            </span>
                                        </td>

                                        {{-- Pesanan --}}
                                        <td class="py-4 px-4 font-medium text-slate-800">
                                            {{ $t->pesanan ?? 'Pendaftaran Kandidat' }}
                                        </td>

                                        {{-- Total --}}
                                        <td class="py-4 px-4 font-bold text-slate-900">
                                            Rp {{ number_format($t->total, 0, ',', '.') }}
                                        </td>

                                        {{-- Status Badge --}}
                                        <td class="py-4 px-4">
                                            @if ($status === 'diterima')
                                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold px-2.5 py-1 rounded-full shadow-xs">
                                                    <i class="ph-fill ph-check-circle text-emerald-600 text-xs"></i>
                                                    Berhasil
                                                </span>
                                            @elseif ($status === 'menunggu_verifikasi')
                                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 border border-sky-200 text-[11px] font-bold px-2.5 py-1 rounded-full shadow-xs">
                                                    <i class="ph-bold ph-hourglass-medium text-sky-600 text-xs"></i>
                                                    Menunggu Verifikasi
                                                </span>
                                            @elseif ($status === 'pending')
                                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold px-2.5 py-1 rounded-full shadow-xs">
                                                    <i class="ph-fill ph-clock text-amber-500 text-xs animate-pulse"></i>
                                                    Menunggu Pembayaran
                                                </span>
                                            @elseif ($status === 'expired')
                                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 border border-slate-200 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                                    <i class="ph-fill ph-clock-countdown text-slate-400 text-xs"></i>
                                                    Kedaluwarsa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                                    <i class="ph-fill ph-x-circle text-rose-500 text-xs"></i>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Tanggal --}}
                                        <td class="py-4 px-4 text-slate-500 text-[11px]">
                                            <div class="font-medium text-slate-700">{{ $t->created_at ? $t->created_at->format('d M Y') : '-' }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $t->created_at ? $t->created_at->format('H:i') . ' WIB' : '' }}</div>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="py-4 px-5 text-right" onclick="event.stopPropagation()">
                                            <a href="{{ route('kandidat.transaksi', $t->id) }}"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition">
                                                <i class="ph ph-eye text-xs"></i>
                                                <span>Detail</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card List View --}}
                    <div class="block md:hidden divide-y divide-slate-100">
                        @foreach ($transaksi as $t)
                            @php
                                $status = strtolower($t->status);
                                $bankName = $t->bank->nama_bank ?? 'QRIS';
                            @endphp
                            <div x-show="matches({
                                    status: '{{ $status }}',
                                    ref: '{{ $t->no_referensi }}',
                                    pesanan: '{{ addslashes($t->pesanan ?? '') }}',
                                    bank: '{{ addslashes($bankName) }}'
                                })"
                                class="p-4 space-y-3 hover:bg-slate-50 transition"
                                onclick="window.location='{{ route('kandidat.transaksi', $t->id) }}'">
                                
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-mono font-bold text-xs text-[#00509d]">{{ $t->no_referensi }}</span>
                                    <div>
                                        @if ($status === 'diterima')
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                <i class="ph-fill ph-check-circle text-xs"></i> Berhasil
                                            </span>
                                        @elseif ($status === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 border border-sky-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                <i class="ph-bold ph-hourglass-medium text-xs"></i> Menunggu Verifikasi
                                            </span>
                                        @elseif ($status === 'pending')
                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                <i class="ph-fill ph-clock text-xs"></i> Menunggu Bayar
                                            </span>
                                        @elseif ($status === 'expired')
                                            <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 border border-slate-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                <i class="ph-fill ph-clock-countdown text-xs"></i> Expired
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                <i class="ph-fill ph-x-circle text-xs"></i> Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $t->pesanan ?? 'Pendaftaran Kandidat' }}</p>
                                        <p class="text-slate-500 text-[11px] mt-0.5">{{ $bankName }} &bull; {{ $t->created_at ? $t->created_at->format('d M Y H:i') : '' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-slate-900 text-sm">Rp {{ number_format($t->total, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex justify-end" onclick="event.stopPropagation()">
                                    <a href="{{ route('kandidat.transaksi', $t->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#00509d] text-white text-xs font-bold rounded-xl">
                                        <i class="ph ph-eye"></i>
                                        <span>Lihat Detail</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

            @else
                {{-- Empty State (No Transactions at all) --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl md:rounded-3xl p-10 md:p-16 text-center max-w-lg mx-auto mb-12 shadow-sm">
                    <div class="w-20 h-20 rounded-3xl bg-blue-50 text-[#00509d] flex items-center justify-center mx-auto mb-5 shadow-xs">
                        <i class="ph-fill ph-receipt text-4xl"></i>
                    </div>

                    <h2 class="text-lg md:text-xl font-bold text-slate-800 mb-2">Belum Ada Transaksi</h2>
                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed mb-6 max-w-sm mx-auto">
                        Anda belum memiliki riwayat transaksi pendaftaran kandidat atau pembayaran lainnya di AreaKerja.
                    </p>

                    <a href="{{ route('pelamar.daftar-kandidat') }}"
                        class="inline-flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold text-xs sm:text-sm px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition">
                        <i class="ph-bold ph-star text-base"></i>
                        <span>Daftar Kandidat AreaKerja</span>
                    </a>
                </div>
            @endif

        </div>

    </div>

    @include('layouts.footer')

@endsection

