<!-- ================= MODAL STEP 3: DETAIL PEMBAYARAN ================= -->
<div id="modalStep3" class="fixed inset-0 hidden bg-slate-900/60 backdrop-blur-xs z-50 items-center justify-center p-4 transition-all">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 relative overflow-hidden flex flex-col max-h-[90vh] animate-fadeIn">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs">
                    <i class="ph-fill ph-receipt text-2xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-extrabold text-slate-900">Rincian Pembayaran</h2>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-extrabold rounded-full">Langkah 3/3</span>
                    </div>
                    <p class="text-xs text-slate-500">Konfirmasi transaksi sebelum diproses</p>
                </div>
            </div>
            <button type="button" onclick="closeAllModal()"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
                <i class="ph ph-x font-bold text-sm"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-4">
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3.5 text-xs">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-500">Nama Perusahaan</span>
                    <span id="detailPengirim" class="font-extrabold text-slate-900 text-right">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-500">Penerima</span>
                    <span id="detailPenerima" class="font-extrabold text-slate-900">PT. Area Kerja</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-500">Metode Pembayaran</span>
                    <span class="bg-[#00509d] text-white text-[11px] font-extrabold px-3 py-1 rounded-lg shadow-2xs" id="detailBank">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-500">Tanggal / Waktu</span>
                    <span id="detailWaktu" class="font-semibold text-slate-700">-</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                    <span class="font-bold text-slate-500">Nominal Deposit</span>
                    <span id="detailHarga" class="font-extrabold text-slate-800 text-sm">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-500">Biaya Administrasi</span>
                    <span id="detailAdmin" class="font-semibold text-slate-600">Rp 2.000</span>
                </div>
                <div class="border-t border-dashed border-slate-300 my-2"></div>
                <div class="flex justify-between items-center">
                    <span class="font-extrabold text-slate-900 text-sm">Total Tagihan</span>
                    <span id="detailTotal" class="font-black text-[#00509d] text-base">-</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3 shrink-0">
            <button type="button" onclick="goToStep(2)"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition">
                <i class="ph ph-arrow-left font-bold text-sm"></i>
                <span>Kembali</span>
            </button>
            <button type="button" id="btnKonfirmasi"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold text-xs rounded-xl shadow-sm transition">
                <i class="ph ph-check-circle font-bold text-base"></i>
                <span>Selesaikan Pembayaran</span>
            </button>
        </div>
    </div>
</div>

