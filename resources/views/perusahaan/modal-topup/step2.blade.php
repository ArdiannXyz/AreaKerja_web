<!-- ================= MODAL STEP 2: METODE PEMBAYARAN ================= -->
<div id="modalStep2" class="fixed inset-0 hidden bg-slate-900/60 backdrop-blur-xs z-50 items-center justify-center p-4 transition-all">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 relative overflow-hidden flex flex-col max-h-[90vh] animate-fadeIn">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-200 text-[#00509d] flex items-center justify-center shrink-0 shadow-2xs">
                    <i class="ph-fill ph-credit-card text-2xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-extrabold text-slate-900">Metode Pembayaran</h2>
                        <span class="px-2 py-0.5 bg-blue-100 text-[#00509d] text-[10px] font-extrabold rounded-full">Langkah 2/3</span>
                    </div>
                    <p class="text-xs text-slate-500">Pilih rekening transfer atau QRIS</p>
                </div>
            </div>
            <button type="button" onclick="closeAllModal()"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
                <i class="ph ph-x font-bold text-sm"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-4">
            <!-- Ringkasan Paket Terpilih -->
            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider">Paket Terpilih</span>
                    <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-1.5 mt-0.5">
                        <i class="ph-fill ph-coins text-amber-500"></i>
                        <span id="step2KoinSummary">0</span> Koin
                    </h4>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Nominal</span>
                    <p class="text-sm font-extrabold text-[#00509d] mt-0.5" id="step2HargaSummary">Rp 0</p>
                </div>
            </div>

            <!-- Transfer Bank -->
            <div class="space-y-2.5">
                <span class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">Transfer Bank</span>
                <div class="space-y-2">
                    @foreach ($daftarBank as $bank)
                        @if (strtolower($bank->nama_bank) !== 'qris')
                            <div onclick="selectPaymentMethod(this, '{{ $bank->id }}', '{{ $bank->nama_bank }}')"
                                class="pembayaranWrapper flex justify-between items-center px-4 py-3.5 border-2 border-slate-200 hover:border-[#00509d]/40 rounded-2xl cursor-pointer bg-white hover:bg-blue-50/30 transition-all select-none">
                                <div class="flex items-center gap-3.5 pointer-events-none">
                                    <div class="w-10 h-8 bg-slate-50 border border-slate-100 rounded-lg p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset($bank->logo_image ?? 'default-bank.png') }}" alt="{{ $bank->nama_bank }}" class="max-h-full max-w-full object-contain pointer-events-none">
                                    </div>
                                    <span class="font-extrabold text-sm text-slate-800 pointer-events-none">{{ $bank->nama_bank }}</span>
                                </div>
                                <input type="radio" name="bank" value="{{ $bank->id }}"
                                    data-bank="{{ $bank->nama_bank }}" class="metodePembayaran sr-only">
                                <div class="radioCheckIcon w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center transition pointer-events-none">
                                    <i class="ph ph-check text-white text-xs font-bold hidden pointer-events-none"></i>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- QRIS -->
            @foreach ($daftarBank as $bank)
                @if (strtolower($bank->nama_bank) === 'qris')
                    <div class="pt-2">
                        <span class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">E-Wallet & QRIS</span>
                        <div onclick="selectPaymentMethod(this, '{{ $bank->id }}', '{{ $bank->nama_bank }}')"
                            class="pembayaranWrapper flex justify-between items-center px-4 py-3.5 border-2 border-slate-200 hover:border-[#00509d]/40 rounded-2xl cursor-pointer bg-white hover:bg-blue-50/30 transition-all select-none">
                            <div class="flex items-center gap-3.5 pointer-events-none">
                                <div class="w-10 h-8 bg-slate-50 border border-slate-100 rounded-lg p-1 flex items-center justify-center shrink-0">
                                    <img src="{{ asset($bank->logo_image ?? 'default-bank.png') }}" alt="{{ $bank->nama_bank }}" class="max-h-full max-w-full object-contain pointer-events-none">
                                </div>
                                <div>
                                    <span class="font-extrabold text-sm text-slate-800 pointer-events-none">{{ $bank->nama_bank }}</span>
                                    <p class="text-[11px] text-slate-500 pointer-events-none">Scan QRIS instan via GoPay, OVO, Dana, BCA, dll</p>
                                </div>
                            </div>
                            <input type="radio" name="bank" value="{{ $bank->id }}" data-bank="{{ $bank->nama_bank }}"
                                class="metodePembayaran sr-only">
                            <div class="radioCheckIcon w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center transition pointer-events-none">
                                <i class="ph ph-check text-white text-xs font-bold hidden pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3 shrink-0">
            <button type="button" onclick="goToStep(1)"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition">
                <i class="ph ph-arrow-left font-bold text-sm"></i>
                <span>Kembali</span>
            </button>
            <button type="button" id="btnNextStep2" onclick="goToStep(3)"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold text-xs rounded-xl shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span>Lanjut Rincian</span>
                <i class="ph ph-arrow-right font-bold text-sm"></i>
            </button>
        </div>
    </div>
</div>

