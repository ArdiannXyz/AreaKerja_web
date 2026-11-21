@extends(Auth::check() && Auth::user()->role === 'perusahaan' ? 'layouts.index-perusahaan' : 'layouts.index')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-24 sm:pt-28 pb-16">
        
        {{-- Breadcrumb Navigation --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('tenant.bills.index') }}"
               class="text-xs text-slate-500 hover:text-[#00509d] inline-flex items-center gap-1.5 font-bold transition">
                <i class="ph ph-arrow-left font-bold text-sm"></i>
                <span>Kembali ke Pusat Tagihan</span>
            </a>
            <span class="text-xs font-mono text-slate-400 font-semibold">
                {{ $transaksi->no_referensi }}
            </span>
        </div>

        @if ($transaksi->status === 'diterima' || $transaksi->status === 'lunas')
            {{-- SUCCESS STATE CARD --}}
            <div class="bg-white rounded-3xl border border-emerald-200 shadow-xl p-8 sm:p-10 text-center max-w-xl mx-auto">
                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-5 shadow-xs">
                    <i class="ph ph-check-circle text-5xl"></i>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 inline-block mb-3">
                    Lunas & Terverifikasi
                </span>
                <h2 class="text-2xl font-black text-slate-800">Pembayaran Berhasil!</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-md mx-auto leading-relaxed">
                    Transaksi pembayaran tagihan <strong>{{ $transaksi->pesanan }}</strong> telah diverifikasi dan lunas. Terima kasih atas kepercayaan Anda.
                </p>

                <div class="mt-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-600 space-y-2 text-left">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">No. Referensi:</span>
                        <span class="font-mono font-bold text-slate-800">{{ $transaksi->no_referensi }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Item / Layanan:</span>
                        <span class="font-bold text-slate-800">{{ $transaksi->pesanan }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                        <span class="text-slate-500 font-bold">Total Lunas:</span>
                        <span class="font-black text-base text-emerald-600">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                    </div>
                    @if ($transaksi->paid_at)
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Waktu Lunas:</span>
                            <span class="font-bold text-slate-700">{{ $transaksi->paid_at->translatedFormat('d F Y H:i') }} WIB</span>
                        </div>
                    @endif
                </div>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('tenant.bills.index') }}"
                       class="w-full sm:w-auto px-6 py-3 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl transition">
                        Lihat Semua Tagihan
                    </a>
                    <a href="{{ Auth::check() && Auth::user()->role === 'perusahaan' ? route('perusahaan.dashboard') : url('/') }}"
                       class="w-full sm:w-auto px-6 py-3 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Ke Dashboard
                    </a>
                </div>
            </div>

        @elseif ($transaksi->status === 'cancelled' || $transaksi->status === 'dibatalkan')
            {{-- CANCELLED STATE CARD --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 sm:p-10 text-center max-w-xl mx-auto">
                <div class="w-16 h-16 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph ph-x-circle text-4xl"></i>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 inline-block mb-3">
                    Transaksi Dibatalkan
                </span>
                <h2 class="text-xl font-bold text-slate-800">Tagihan Ini Telah Dibatalkan</h2>
                <p class="text-xs text-slate-500 mt-2 max-w-sm mx-auto">
                    Tagihan #{{ $transaksi->no_referensi }} telah dibatalkan atas permintaan Anda atau sistem.
                </p>
                <div class="mt-6 flex justify-center gap-3">
                    <a href="{{ route('tenant.bills.index') }}"
                       class="px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Kembali ke Pusat Tagihan
                    </a>
                </div>
            </div>

        @elseif ($transaksi->status === 'expired')
            {{-- EXPIRED STATE CARD --}}
            <div class="bg-white rounded-3xl border border-rose-200 shadow-xl p-8 sm:p-10 text-center max-w-xl mx-auto">
                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph ph-clock text-4xl"></i>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-block mb-3">
                    Batas Waktu Habis
                </span>
                <h2 class="text-xl font-bold text-slate-800">Tagihan Kedaluwarsa (Expired)</h2>
                <p class="text-xs text-slate-500 mt-2 max-w-sm mx-auto">
                    Batas waktu pembayaran tagihan telah berakhir. Silakan buat pesanan baru.
                </p>
                <div class="mt-6 flex justify-center gap-3">
                    <a href="{{ route('tenant.bills.index') }}"
                       class="px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Kembali ke Pusat Tagihan
                    </a>
                </div>
            </div>

        @else
            {{-- IN-APP PAYMENT CHECKOUT COMPONENT --}}
            <x-payment-gateway-checkout :transaksi="$transaksi" :backUrl="route('tenant.bills.index')" />
        @endif

    </div>

    @include('layouts.footer')
@endsection
