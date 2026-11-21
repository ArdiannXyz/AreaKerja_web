@extends(Auth::check() && Auth::user()->role === 'perusahaan' ? 'layouts.index-perusahaan' : 'layouts.index')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 pt-28 pb-16 min-h-[80vh]">

    {{-- Breadcrumb & Title --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ Auth::check() && Auth::user()->role === 'perusahaan' ? route('perusahaan.dashboard') : url('/') }}"
               class="text-xs text-slate-500 hover:text-[#00509d] inline-flex items-center gap-1 mb-2 font-medium transition">
                <i class="ph ph-arrow-left"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pusat Riwayat Tagihan</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau status tagihan, selesaikan pembayaran tertunda, atau batalkan pesanan.</p>
        </div>

        @if(Auth::check() && Auth::user()->role === 'perusahaan')
        <div>
            <button type="button" onclick="toggleModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow-xs transition">
                <i class="ph ph-plus-circle text-base font-bold"></i>
                <span>Top Up Koin Baru</span>
            </button>
        </div>
        @endif
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ph ph-check-circle text-lg text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="ph ph-x text-sm"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ph ph-warning-circle text-lg text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="ph ph-x text-sm"></i>
            </button>
        </div>
    @endif

    {{-- SISTEM TAB NAVIGASI 3 KATEGORI --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-1.5 mb-6 flex items-center gap-1 text-xs font-bold">
        {{-- Tab 1: Menunggu Bayar --}}
        <a href="{{ route('tenant.bills.index', ['tab' => 'pending']) }}"
           class="flex-1 py-3 px-3 rounded-xl flex items-center justify-center gap-2 transition {{ $tab === 'pending' ? 'bg-[#00509d] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="ph ph-clock text-base"></i>
            <span>Menunggu Bayar</span>
            @if($pendingCount > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $tab === 'pending' ? 'bg-white text-[#00509d]' : 'bg-rose-500 text-white animate-pulse' }}">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        {{-- Tab 2: Riwayat Lunas --}}
        <a href="{{ route('tenant.bills.index', ['tab' => 'lunas']) }}"
           class="flex-1 py-3 px-3 rounded-xl flex items-center justify-center gap-2 transition {{ $tab === 'lunas' ? 'bg-[#00509d] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="ph ph-check-circle text-base"></i>
            <span>Riwayat Lunas</span>
            @if($paidCount > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $tab === 'lunas' ? 'bg-white text-[#00509d]' : 'bg-slate-100 text-slate-700' }}">
                    {{ $paidCount }}
                </span>
            @endif
        </a>

        {{-- Tab 3: Dibatalkan --}}
        <a href="{{ route('tenant.bills.index', ['tab' => 'dibatalkan']) }}"
           class="flex-1 py-3 px-3 rounded-xl flex items-center justify-center gap-2 transition {{ $tab === 'dibatalkan' ? 'bg-[#00509d] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="ph ph-x-circle text-base"></i>
            <span>Dibatalkan</span>
            @if($cancelledCount > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $tab === 'dibatalkan' ? 'bg-white text-[#00509d]' : 'bg-slate-100 text-slate-700' }}">
                    {{ $cancelledCount }}
                </span>
            @endif
        </a>
    </div>

    {{-- DAFTAR TRANSAKSI TAGIHAN --}}
    <div class="space-y-4">
        @forelse($tagihans as $item)
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:shadow-md transition p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
                
                {{-- Info Kiri: No. Referensi & Detail Pesanan --}}
                <div class="space-y-2 flex-1">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                            #{{ $item->no_referensi ?: ('AK-' . $item->id) }}
                        </span>

                        {{-- Status Badge --}}
                        @if($item->isPending())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Menunggu Pembayaran</span>
                            </span>
                        @elseif($item->isPaid())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="ph ph-check-circle text-emerald-600 font-bold"></i>
                                <span>Lunas / Terverifikasi</span>
                            </span>
                        @elseif($item->isCancelled())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                <i class="ph ph-x-circle text-slate-500 font-bold"></i>
                                <span>Dibatalkan</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <i class="ph ph-clock text-rose-500 font-bold"></i>
                                <span>Kedaluwarsa</span>
                            </span>
                        @endif

                        <span class="text-[11px] text-slate-400">
                            {{ $item->created_at->translatedFormat('d F Y, H:i') }} WIB
                        </span>
                    </div>

                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">{{ $item->pesanan ?: 'Top Up Koin Area Kerja' }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Metode: <strong class="text-slate-700 font-semibold">{{ $item->sumberDana ?: 'QRIS & E-Wallet / VA' }}</strong>
                        </p>
                    </div>

                    @if($item->isPending() && $item->expired_at)
                        <div class="text-[11px] text-amber-700 font-medium flex items-center gap-1">
                            <i class="ph ph-clock font-bold"></i>
                            <span>Batas Bayar: <strong>{{ $item->expired_at->translatedFormat('d M Y H:i') }} WIB</strong></span>
                        </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Total Tagihan & Action Buttons --}}
                <div class="flex flex-col sm:flex-row md:flex-col items-start md:items-end justify-between sm:justify-start gap-4 pt-4 md:pt-0 border-t md:border-t-0 border-slate-100">
                    <div class="text-left md:text-right">
                        <span class="text-[11px] text-slate-400 block font-medium">Total Pembayaran</span>
                        <span class="text-xl font-black text-[#00509d] tracking-tight block">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        @if($item->isPending())
                            {{-- Tombol Batalkan --}}
                            <form action="{{ route('tenant.bills.cancel', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi tagihan ini?');">
                                @csrf
                                <button type="submit"
                                        class="px-3.5 py-2.5 rounded-xl border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-600 text-xs font-bold transition">
                                    Batalkan
                                </button>
                            </form>

                            {{-- Tombol Bayar Sekarang → --}}
                            <a href="{{ route('catatan_cash.show', $item->id) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-black rounded-xl shadow-xs transition flex-1 sm:flex-initial">
                                <span>Bayar Sekarang</span>
                                <i class="ph ph-arrow-right font-bold text-sm"></i>
                            </a>
                        @elseif($item->isPaid())
                            <a href="{{ route('catatan_cash.show', $item->id) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold rounded-xl border border-emerald-200 transition">
                                <i class="ph ph-receipt font-bold"></i>
                                <span>Bukti Transaksi</span>
                            </a>
                        @else
                            <a href="{{ route('catatan_cash.show', $item->id) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                <span>Detail Tagihan</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <i class="ph ph-receipt-x text-3xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tidak Ada Tagihan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    @if($tab === 'pending')
                        Hebat! Anda tidak memiliki tagihan pembayaran yang tertunda saat ini.
                    @elseif($tab === 'lunas')
                        Belum ada riwayat transaksi lunas yang tercatat pada akun Anda.
                    @else
                        Tidak ada riwayat tagihan yang dibatalkan.
                    @endif
                </p>
                @if(Auth::check() && Auth::user()->role === 'perusahaan')
                    <div class="mt-6">
                        <button type="button" onclick="toggleModal()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow-xs transition">
                            <i class="ph ph-coins"></i>
                            <span>Beli Paket Koin</span>
                        </button>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    @if($tagihans->hasPages())
        <div class="mt-8">
            {{ $tagihans->links() }}
        </div>
    @endif

</div>

@include('layouts.footer')
@endsection
