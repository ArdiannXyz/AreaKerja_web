@props([
    'transaksi',
    'backUrl' => null,
])

@php
    $backUrl = $backUrl ?? route('tenant.bills.index');
    $amount = (float)($transaksi->total ?? 0);
    $formattedTotal = 'Rp ' . number_format($amount, 0, ',', '.');
    $referenceNo = $transaksi->no_referensi ?: ('AK-' . $transaksi->id);
    $orderName = $transaksi->pesanan ?: 'Top Up Koin Area Kerja';
    
    // Hitung sisa detik expired
    $now = now();
    $expiredAt = $transaksi->expired_at ?: now()->addMinutes(15);
    $remainingSeconds = max(0, $now->diffInSeconds($expiredAt, false));
    if ($remainingSeconds <= 0 && $transaksi->status === 'pending') {
        $remainingSeconds = 900; // default 15 menit jika belum expired di DB
    }

    // QRIS SVG dari lokal QrisService
    $qrisSvg = $transaksi->qris_svg;

    // Ambil setting manual transfer dari service
    $manualBanks = \App\Services\PaymentSettingService::getManualBanks();
    $manualInstructions = \App\Services\PaymentSettingService::getManualInstructions();
    $manualQrUrl = \App\Services\PaymentSettingService::getManualQrImageUrl();
    $isXenditEnabled = \App\Services\PaymentSettingService::isXenditEnabled();
    $isManualEnabled = \App\Services\PaymentSettingService::isManualEnabled();

    // Virtual Account generator mock numbers (BCA 80777..., Mandiri 88708..., BRI 12890..., BNI 988...)
    $numericSuffix = preg_replace('/\D/', '', $referenceNo) ?: (string)$transaksi->id;
    $bcaVaNumber = '80777' . str_pad(substr($numericSuffix, -8), 8, '0', STR_PAD_LEFT);
    $mandiriVaNumber = '88708' . str_pad(substr($numericSuffix, -8), 8, '0', STR_PAD_LEFT);
    $briVaNumber = '12890' . str_pad(substr($numericSuffix, -8), 8, '0', STR_PAD_LEFT);
    $bniVaNumber = '988' . str_pad(substr($numericSuffix, -8), 8, '0', STR_PAD_LEFT);
@endphp

<div x-data="paymentCheckout({
        referenceId: '{{ $referenceNo }}',
        transaksiId: {{ $transaksi->id }},
        totalAmount: {{ $amount }},
        remainingSeconds: {{ $remainingSeconds }},
        statusUrl: '{{ url('/payments/status/' . $referenceNo) }}',
        successUrl: '{{ route('catatan_cash.show', $transaksi->id) }}',
        status: '{{ $transaksi->status }}'
    })"
    x-init="initPayment()"
    class="w-full max-w-md mx-auto bg-slate-50 min-h-screen sm:min-h-0 sm:py-6 sm:px-2 antialiased text-slate-800">

    {{-- CARD UTAMA CHECKOUT --}}
    <div class="bg-white sm:rounded-3xl shadow-xl sm:border border-slate-100 overflow-hidden flex flex-col transition-all">

        {{-- ================= HEADER CARD ================= --}}
        <div class="px-5 pt-5 pb-4 bg-white border-b border-slate-100 sticky top-0 z-20">
            <div class="flex items-center justify-between gap-3">
                {{-- Tombol Kembali < --}}
                <div class="flex items-center gap-3">
                    <template x-if="viewMode === 'select'">
                        <a href="{{ $backUrl }}"
                           class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 flex items-center justify-center transition">
                            <i class="ph ph-caret-left text-lg font-bold"></i>
                        </a>
                    </template>
                    <template x-if="viewMode !== 'select'">
                        <button type="button" @click="viewMode = 'select'"
                                class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 flex items-center justify-center transition">
                            <i class="ph ph-caret-left text-lg font-bold"></i>
                        </button>
                    </template>

                    <h1 class="text-base font-bold text-slate-900 tracking-tight">Pilih Metode Pembayaran</h1>
                </div>

                {{-- Countdown Timer Pembayaran Aktif --}}
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-colors"
                     :class="secondsLeft > 180 ? 'bg-amber-50 text-amber-700 border border-amber-200/80' : 'bg-rose-50 text-rose-700 border border-rose-200 animate-pulse'">
                    <i class="ph ph-clock text-sm"></i>
                    <span x-text="formattedTimer">15:00</span>
                </div>
            </div>

            {{-- Ringkasan Total Tagihan --}}
            <div class="mt-4 p-4 rounded-2xl bg-gradient-to-br from-blue-50/70 to-slate-50 border border-blue-100/80">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Tagihan</span>
                        <div class="text-2xl font-black text-[#00509d] tracking-tight mt-0.5">
                            {{ $formattedTotal }}
                        </div>
                    </div>
                    <button type="button" @click="copyText('{{ $amount }}', 'Nominal')"
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-[#00509d] bg-white rounded-lg border border-blue-200 hover:bg-blue-50 transition shadow-2xs">
                        <i class="ph ph-copy text-xs"></i>
                        <span x-text="copiedNominal ? 'Tersalin!' : 'Salin'">Salin</span>
                    </button>
                </div>

                <div class="mt-3 pt-3 border-t border-blue-100/70 flex items-center justify-between text-xs text-slate-600">
                    <div class="truncate max-w-[200px]">
                        <span class="text-slate-400 block text-[10px]">Pesanan</span>
                        <span class="font-bold text-slate-800 truncate block">{{ $orderName }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-400 block text-[10px]">No. Referensi</span>
                        <span class="font-mono font-bold text-slate-700 text-[11px]">{{ $referenceNo }}</span>
                    </div>
                </div>
            </div>

            {{-- Tab Navigasi Segmented Control (Hanya tampil di viewMode select) --}}
            <template x-if="viewMode === 'select'">
                <div class="mt-4 p-1 bg-slate-100/90 rounded-2xl flex items-center text-xs font-bold text-slate-600 gap-1">
                    <button type="button" @click="activeTab = 'qris'"
                            :class="activeTab === 'qris' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-2 px-2 rounded-xl flex items-center justify-center gap-1.5 transition-all duration-150">
                        <i class="ph ph-qr-code text-base text-emerald-600"></i>
                        <span>QRIS & E-Wallet</span>
                    </button>

                    <button type="button" @click="activeTab = 'va'"
                            :class="activeTab === 'va' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-2 px-2 rounded-xl flex items-center justify-center gap-1.5 transition-all duration-150">
                        <i class="ph ph-credit-card text-base text-blue-600"></i>
                        <span>Virtual Account</span>
                    </button>

                    @if($isManualEnabled)
                    <button type="button" @click="activeTab = 'manual'"
                            :class="activeTab === 'manual' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-2 px-2 rounded-xl flex items-center justify-center gap-1.5 transition-all duration-150">
                        <i class="ph ph-bank text-base text-amber-600"></i>
                        <span>Manual</span>
                    </button>
                    @endif
                </div>
            </template>
        </div>

        {{-- ================= BODY CONTENT ================= --}}
        <div class="p-5 flex-1 overflow-y-auto">

            {{-- ================= MODE 1: PILIH METODE (SELECT) ================= --}}
            <div x-show="viewMode === 'select'" class="space-y-4">

                {{-- TAB 1: QRIS & E-WALLET --}}
                <div x-show="activeTab === 'qris'" class="space-y-3">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-1">
                        Saluran Pembayaran Instan
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs divide-y divide-slate-100 overflow-hidden">
                        {{-- QRIS Standar Nasional --}}
                        <div @click="openQrisDetail('qris')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-slate-200 bg-white flex items-center justify-center shrink-0 shadow-2xs">
                                    {{-- Logo Standar QRIS --}}
                                    <span class="font-black text-slate-900 tracking-tighter text-sm flex items-center">
                                        <span class="text-rose-600 font-black">Q</span>RIS
                                    </span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-sm text-slate-900">QRIS Standar Nasional</span>
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/80">Populer</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">BCA, Mandiri, BRImo, BNI, GoPay, DANA, OVO, ShopeePay</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>

                        {{-- GoPay --}}
                        <div @click="openQrisDetail('gopay')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-slate-200 bg-white flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-extrabold text-[#00A5CF] text-xs tracking-tight flex items-center gap-1">
                                        <span class="w-3.5 h-3.5 rounded-full bg-[#00A5CF] text-white flex items-center justify-center text-[8px] font-bold">●</span>
                                        gopay
                                    </span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">GoPay</span>
                                    <p class="text-[11px] text-slate-500">Scan QRIS instan via GoPay App</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>

                        {{-- DANA --}}
                        <div @click="openQrisDetail('dana')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-slate-200 bg-white flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-extrabold text-[#118EEA] text-xs tracking-wider">DANA</span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">DANA</span>
                                    <p class="text-[11px] text-slate-500">Scan QRIS instan via DANA Wallet</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>

                        {{-- OVO --}}
                        <div @click="openQrisDetail('ovo')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-slate-200 bg-white flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-black text-[#4C3494] text-xs tracking-tight">ovo</span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">OVO</span>
                                    <p class="text-[11px] text-slate-500">Scan QRIS instan via OVO Points & Cash</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>

                        {{-- ShopeePay --}}
                        <div @click="openQrisDetail('shopeepay')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-slate-200 bg-white flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-extrabold text-[#EE4D2D] text-[11px] tracking-tight flex items-center gap-0.5">
                                        <i class="ph ph-shopping-bag"></i> Pay
                                    </span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">ShopeePay</span>
                                    <p class="text-[11px] text-slate-500">Scan QRIS instan via ShopeePay</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: VIRTUAL ACCOUNT --}}
                <div x-show="activeTab === 'va'" class="space-y-3">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-1">
                        Saluran Virtual Account (Otomatis 24 Jam)
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs divide-y divide-slate-100 overflow-hidden">
                        {{-- BCA VA --}}
                        <div @click="openVaDetail('bca', 'BCA Virtual Account', '{{ $bcaVaNumber }}')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-blue-200 bg-blue-50/60 flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-black text-[#0060AF] text-xs">BCA</span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">BCA Virtual Account</span>
                                    <p class="text-[11px] text-slate-500">Verifikasi otomatis via myBCA & KlikBCA</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>

                        {{-- Mandiri VA --}}
                        <div @click="openVaDetail('mandiri', 'Mandiri Virtual Account', '{{ $mandiriVaNumber }}')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-amber-200 bg-amber-50/60 flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-black text-[#003876] text-xs">Mandiri</span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">Mandiri Virtual Account</span>
                                    <p class="text-[11px] text-slate-500">Verifikasi otomatis via Livin' by Mandiri</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>

                        {{-- BRI BRIVA --}}
                        <div @click="openVaDetail('bri', 'BRI BRIVA', '{{ $briVaNumber }}')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-blue-200 bg-blue-50/60 flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-black text-[#00529C] text-xs">BRIVA</span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">BRI BRIVA</span>
                                    <p class="text-[11px] text-slate-500">Verifikasi otomatis via BRImo & ATM BRI</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>

                        {{-- BNI VA --}}
                        <div @click="openVaDetail('bni', 'BNI Virtual Account', '{{ $bniVaNumber }}')"
                             class="p-4 flex items-center justify-between hover:bg-slate-50/80 active:bg-slate-100 cursor-pointer transition group">
                            <div class="flex items-center gap-3.5">
                                <div class="w-16 h-10 px-2 rounded-xl border border-teal-200 bg-teal-50/60 flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="font-black text-[#005E6A] text-xs">BNI</span>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-900">BNI Virtual Account</span>
                                    <p class="text-[11px] text-slate-500">Verifikasi otomatis via BNI Mobile Banking</p>
                                </div>
                            </div>
                            <i class="ph ph-caret-right text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition font-bold"></i>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: TRANSFER MANUAL --}}
                @if($isManualEnabled)
                <div x-show="activeTab === 'manual'" class="space-y-4">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-1">
                        Rekening Resmi Transfer Manual
                    </div>

                    <div class="space-y-3">
                        @foreach($manualBanks as $mb)
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-black text-slate-800 text-xs shrink-0">
                                    {{ $mb['bank_name'] }}
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Bank {{ $mb['bank_name'] }}</span>
                                    <span class="font-mono text-sm font-black text-[#00509d] block tracking-wide">{{ $mb['account_number'] }}</span>
                                    <span class="text-[10px] text-slate-500 block">a/n {{ $mb['account_holder'] }}</span>
                                </div>
                            </div>
                            <button type="button" @click="copyText('{{ $mb['account_number'] }}', 'No. Rekening {{ $mb['bank_name'] }}')"
                                    class="px-3 py-1.5 text-xs font-bold text-[#00509d] bg-blue-50 hover:bg-blue-100 rounded-xl transition border border-blue-200">
                                Salin
                            </button>
                        </div>
                        @endforeach
                    </div>

                    {{-- Petunjuk Manual --}}
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-600 space-y-2">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            <i class="ph ph-info text-blue-600"></i> Panduan Transfer Manual
                        </span>
                        <div class="text-[11px] whitespace-pre-line leading-relaxed text-slate-600">
                            {{ $manualInstructions }}
                        </div>
                    </div>

                    {{-- Form Upload Struk Transfer --}}
                    <div class="p-4 bg-white rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                        <span class="font-bold text-xs text-slate-800 block">Unggah Bukti Struk Transfer</span>
                        <form action="{{ route('catatan_cash.upload_bukti', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="file" name="bukti" required accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#00509d] file:text-white hover:file:bg-[#003d7a] file:cursor-pointer border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                            
                            <button type="submit"
                                    class="w-full py-2.5 px-4 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                                <i class="ph ph-upload-simple font-bold"></i>
                                <span>Kirim Bukti Pembayaran</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endif

            </div>

            {{-- ================= MODE 2: TAMPILAN DETAIL QRIS STANDEE ================= --}}
            <div x-show="viewMode === 'qris'" x-cloak class="space-y-4">
                
                {{-- Tombol Ganti Metode Pembayaran --}}
                <button type="button" @click="viewMode = 'select'"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00509d] hover:text-[#003d7a] transition py-1">
                    <i class="ph ph-arrow-left font-bold"></i>
                    <span>← Ganti Metode Pembayaran</span>
                </button>

                {{-- KARTU STANDEE STANDAR QRIS NASIONAL --}}
                <div id="qrisStandeeCard" class="bg-white rounded-3xl border-2 border-slate-200/90 shadow-xl overflow-hidden text-center max-w-[340px] mx-auto p-5 relative">
                    
                    {{-- Top Header Standee: QRIS Logo & ASPI / BI --}}
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-lg tracking-tighter text-slate-900">
                                <span class="text-rose-600">Q</span>RIS
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-black uppercase text-slate-700 tracking-wider block">QR INDONESIA STANDAR</span>
                            <span class="text-[8px] text-slate-400 block font-semibold">ASPI / BANK INDONESIA</span>
                        </div>
                    </div>

                    {{-- Merchant Name & NMID --}}
                    <div class="my-3 text-center">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">AREA KERJA INDONESIA</h3>
                        <p class="text-[10px] font-mono text-slate-500 mt-0.5">NMID: ID1020038920192</p>
                    </div>

                    {{-- KODE QRIS SVG MURNI LOKAL --}}
                    <div class="p-3 bg-white rounded-2xl border border-slate-200/90 shadow-inner my-2 flex items-center justify-center min-h-[240px]">
                        <div id="svgContainer" class="w-full h-full flex items-center justify-center">
                            {!! $qrisSvg !!}
                        </div>
                    </div>

                    {{-- Nominal Tagihan & Salin --}}
                    <div class="mt-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <div class="text-left">
                            <span class="text-[10px] text-slate-400 block font-medium">Nominal Pas</span>
                            <span class="font-black text-sm text-[#00509d]">{{ $formattedTotal }}</span>
                        </div>
                        <button type="button" @click="copyText('{{ $amount }}', 'Nominal')"
                                class="px-3 py-1 bg-white hover:bg-blue-50 text-[#00509d] font-bold text-xs rounded-lg border border-blue-200 transition">
                            <span x-text="copiedNominal ? 'Tersalin!' : 'Salin'">Salin</span>
                        </button>
                    </div>

                    {{-- Tombol Unduh QR (.svg) --}}
                    <div class="mt-3">
                        <button type="button" @click="downloadSvg()"
                                class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 active:scale-98 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                            <i class="ph ph-download-simple text-sm font-bold"></i>
                            <span>Unduh QR (.svg)</span>
                        </button>
                    </div>
                </div>

                {{-- INSTRUKSI 4 LANGKAH PEMBAYARAN M-BANKING / E-WALLET --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
                    <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                        <i class="ph ph-list-numbers text-blue-600 font-bold text-sm"></i>
                        <span>Cara Pembayaran QRIS</span>
                    </h4>
                    <ol class="space-y-2 text-[11px] text-slate-600 leading-relaxed list-decimal list-inside pl-1">
                        <li>Buka aplikasi m-Banking (BCA, Livin', BRImo, BNI) atau E-Wallet (GoPay, DANA, OVO, ShopeePay).</li>
                        <li>Pilih menu <strong>Scan / Bayar QRIS</strong> di aplikasi Anda.</li>
                        <li>Arahkan kamera ke kode QR di atas (atau unggah file SVG dari galeri).</li>
                        <li>Periksa nama merchant <strong>AREA KERJA INDONESIA</strong> dan nominal, lalu masukkan PIN Anda.</li>
                    </ol>
                </div>
            </div>

            {{-- ================= MODE 3: DETAIL VIRTUAL ACCOUNT ================= --}}
            <div x-show="viewMode === 'va_detail'" x-cloak class="space-y-4">
                <button type="button" @click="viewMode = 'select'"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00509d] hover:text-[#003d7a] transition py-1">
                    <i class="ph ph-arrow-left font-bold"></i>
                    <span>← Ganti Metode Pembayaran</span>
                </button>

                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-slate-500 block">Bank Pembayaran</span>
                            <h3 class="text-base font-extrabold text-slate-900" x-text="vaTitle">BCA Virtual Account</h3>
                        </div>
                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">Otomatis</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block mb-1">Nomor Virtual Account</span>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="font-mono text-base font-black text-[#00509d] tracking-wider" x-text="vaNumber">80777000123</span>
                            <button type="button" @click="copyText(vaNumber, 'Nomor VA')"
                                    class="px-3 py-1 bg-white hover:bg-blue-50 text-[#00509d] font-bold text-xs rounded-lg border border-blue-200 transition">
                                <span x-text="copiedVa ? 'Tersalin!' : 'Salin'">Salin</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl text-xs text-amber-800 space-y-1">
                        <div class="font-bold flex items-center gap-1">
                            <i class="ph ph-warning-circle text-amber-600"></i> Penting:
                        </div>
                        <p class="text-[11px] leading-relaxed">
                            Transfer tepat sesuai nominal tagihan <strong>{{ $formattedTotal }}</strong>. Pembayaran melalui Virtual Account akan diverifikasi otomatis oleh sistem dalam 1-2 menit setelah transfer berhasil.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= FOOTER / STATUS POLLING INDIKATOR ================= --}}
        <div class="px-5 py-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[11px] font-semibold text-slate-600">Verifikasi Otomatis Aktif</span>
            </div>
            <span class="text-[10px] text-slate-400 font-mono">Auto-polling 5s</span>
        </div>

    </div>
</div>

<script>
function paymentCheckout(config) {
    return {
        viewMode: 'select', // 'select' | 'qris' | 'va_detail'
        activeTab: 'qris',   // 'qris' | 'va' | 'manual'
        vaTitle: '',
        vaNumber: '',
        secondsLeft: config.remainingSeconds || 900,
        formattedTimer: '15:00',
        copiedNominal: false,
        copiedVa: false,
        timerInterval: null,
        pollingInterval: null,

        initPayment() {
            this.updateFormattedTimer();

            // Countdown timer interval
            this.timerInterval = setInterval(() => {
                if (this.secondsLeft > 0) {
                    this.secondsLeft--;
                    this.updateFormattedTimer();
                } else {
                    clearInterval(this.timerInterval);
                    this.formattedTimer = '00:00';
                }
            }, 1000);

            // Auto-polling status pembayaran setiap 5 detik
            this.pollingInterval = setInterval(() => {
                this.checkStatus();
            }, 5000);
        },

        updateFormattedTimer() {
            const m = Math.floor(this.secondsLeft / 60);
            const s = this.secondsLeft % 60;
            this.formattedTimer = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        },

        openQrisDetail(channel) {
            this.viewMode = 'qris';
        },

        openVaDetail(bankCode, title, vaNumber) {
            this.vaTitle = title;
            this.vaNumber = vaNumber;
            this.viewMode = 'va_detail';
        },

        copyText(text, label = 'Teks') {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text);
            } else {
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }

            if (label.toLowerCase().includes('nominal')) {
                this.copiedNominal = true;
                setTimeout(() => { this.copiedNominal = false; }, 2000);
            } else {
                this.copiedVa = true;
                setTimeout(() => { this.copiedVa = false; }, 2000);
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `${label} berhasil disalin`,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        },

        downloadSvg() {
            const container = document.getElementById('svgContainer');
            if (!container) return;
            const svgEl = container.querySelector('svg');
            if (!svgEl) return;

            const serializer = new XMLSerializer();
            const source = serializer.serializeToString(svgEl);
            const blob = new Blob([source], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = `qris-areakerja-${config.referenceId}.svg`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        async checkStatus() {
            try {
                const res = await fetch(config.statusUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;

                const data = await res.json();
                if (data.is_paid || ['diterima', 'lunas', 'selesai', 'paid', 'settled'].includes(data.status)) {
                    clearInterval(this.pollingInterval);
                    clearInterval(this.timerInterval);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Diterima!',
                            text: 'Pembayaran tagihan Anda telah terverifikasi secara otomatis.',
                            confirmButtonText: 'Lihat Bukti Transaksi',
                            confirmButtonColor: '#00509d'
                        }).then(() => {
                            window.location.href = config.successUrl;
                        });
                    } else {
                        window.location.href = config.successUrl;
                    }
                }
            } catch (e) {
                console.error('Polling payment error:', e);
            }
        }
    };
}
</script>
