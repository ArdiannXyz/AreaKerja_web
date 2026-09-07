<!-- ================= MODAL STEP 2 ================= -->
<div id="modalStep2" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full sm:max-w-md rounded-2xl shadow-xl relative p-6 max-h-[90vh] overflow-y-auto">
        <button onclick="closeAllModal()" class="absolute top-4 right-4 text-slate-400 hover:text-black text-xl">✕</button>

        <h2 class="text-xl font-bold text-slate-800 mb-2">Metode Pembayaran</h2>
        <div class="h-1 w-24 bg-[#00509d] mb-4 rounded-full"></div>

        <!-- Dropdown Transfer Bank -->
        <details class="border border-slate-200 rounded-xl overflow-hidden mb-3" open>
            <summary class="flex items-center justify-between px-4 py-3 cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                <span class="flex items-center gap-2 font-bold text-sm text-slate-800">
                    <i class="ph ph-bank font-bold text-lg text-[#00509d]"></i>
                    Transfer Bank
                </span>
                <i class="ph ph-caret-down font-bold text-slate-400"></i>
            </summary>
            <div class="divide-y divide-slate-100 bg-white">
                @foreach ($daftarBank as $bank)
                    @if (strtolower($bank->nama_bank) !== 'qris')
                        <label
                            class="pembayaranWrapper flex justify-between items-center px-4 py-3 cursor-pointer hover:bg-blue-50/40 transition">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($bank->logo_image ?? 'default-bank.png') }}" class="w-8 h-8 object-contain">
                                <span class="font-bold text-sm text-slate-700">{{ $bank->nama_bank }}</span>
                            </div>
                            <input type="radio" name="bank" value="{{ $bank->id }}"
                                data-bank="{{ $bank->nama_bank }}" class="hidden peer metodePembayaran">
                            <span
                                class="w-5 h-5 border-2 border-[#00509d] rounded-full flex items-center justify-center peer-checked:bg-[#00509d] transition">
                                <span class="hidden peer-checked:block w-2 h-2 bg-white rounded-full"></span>
                            </span>
                        </label>
                    @endif
                @endforeach
            </div>
        </details>

        <!-- QRIS (pisah dari dropdown) -->
        @foreach ($daftarBank as $bank)
            @if (strtolower($bank->nama_bank) === 'qris')
                <label
                    class="pembayaranWrapper flex justify-between items-center px-4 py-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/40 transition mb-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset($bank->logo_image ?? 'default-bank.png') }}" class="w-8 h-8 object-contain">
                        <span class="font-bold text-sm text-slate-700">{{ $bank->nama_bank }}</span>
                    </div>
                    <input type="radio" name="bank" value="{{ $bank->id }}" data-bank="{{ $bank->nama_bank }}"
                        class="hidden peer metodePembayaran">
                    <span
                        class="w-5 h-5 border-2 border-[#00509d] rounded-full flex items-center justify-center peer-checked:bg-[#00509d] transition">
                        <span class="hidden peer-checked:block w-2 h-2 bg-white rounded-full"></span>
                    </span>
                </label>
            @endif
        @endforeach

        <!-- Tombol navigasi -->
        <div class="flex justify-between items-center mt-6 gap-3">
            <button onclick="goToStep(1)" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Kembali</button>
            <button onclick="goToStep(3)" class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-6 py-2 rounded-xl transition text-sm">Selanjutnya</button>
        </div>
    </div>
</div>
