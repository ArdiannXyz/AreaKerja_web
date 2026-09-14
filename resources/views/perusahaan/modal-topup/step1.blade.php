<!-- ================= MODAL STEP 1: PILIH PAKET KOIN ================= -->
<div id="modalStep1" class="fixed inset-0 hidden bg-slate-900/60 backdrop-blur-xs z-50 items-center justify-center p-4 transition-all">
    <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-100 relative overflow-hidden flex flex-col max-h-[90vh] animate-fadeIn">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-500 flex items-center justify-center shrink-0 shadow-2xs">
                    <i class="ph-fill ph-coins text-2xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-extrabold text-slate-900">Top Up Koin AreaKerja</h2>
                        <span class="px-2 py-0.5 bg-blue-100 text-[#00509d] text-[10px] font-extrabold rounded-full">Langkah 1/3</span>
                    </div>
                    <p class="text-xs text-slate-500">Pilih paket koin sesuai kebutuhan perusahaan Anda</p>
                </div>
            </div>
            <button type="button" onclick="closeAllModal()"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
                <i class="ph ph-x font-bold text-sm"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($hargaPembayarans as $paket)
                    <div onclick="selectCoinPackage(this, '{{ $paket->id }}', '{{ $paket->jumlah_koin }}', '{{ $paket->harga }}')"
                        class="paketCoinWrapper relative cursor-pointer border-2 border-slate-200 hover:border-[#00509d]/50 bg-white hover:bg-blue-50/20 rounded-2xl p-4 flex flex-col items-center justify-between text-center transition-all duration-200 group shadow-2xs hover:shadow-md select-none">

                        <!-- Input radio -->
                        <input type="radio" name="paket" value="{{ $paket->id }}"
                            data-jumlah="{{ $paket->jumlah_koin }}" data-harga="{{ $paket->harga }}"
                            class="paketCoin sr-only">

                        <!-- Isi kartu -->
                        <div class="flex flex-col items-center flex-1 py-2">
                            <div class="w-16 h-16 rounded-2xl bg-amber-50/60 border border-amber-100/80 flex items-center justify-center mb-3 group-hover:scale-105 transition-transform pointer-events-none">
                                <img src="{{ asset('icon/' . ($paket->icon ?? 'default-icon.png')) }}"
                                    alt="{{ $paket->nama }}" class="w-12 h-12 object-contain drop-shadow-xs pointer-events-none">
                            </div>
                            <span class="text-2xl font-black text-slate-900 tracking-tight pointer-events-none">
                                {{ number_format($paket->jumlah_koin, 0, ',', '.') }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider pointer-events-none">Koin</span>
                        </div>

                        <!-- Badge Harga -->
                        <div class="w-full mt-3 bg-[#00509d] text-white text-center py-2 px-1 rounded-xl font-bold text-xs shadow-2xs group-hover:bg-[#003d7a] transition-colors pointer-events-none">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3 shrink-0">
            <button type="button" onclick="closeAllModal()"
                class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition">
                Batal
            </button>
            <button type="button" id="btnConfirmStep1" onclick="goToStep(2)"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold text-xs rounded-xl shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span>Pilih Metode Pembayaran</span>
                <i class="ph ph-arrow-right font-bold text-sm"></i>
            </button>
        </div>
    </div>
</div>

