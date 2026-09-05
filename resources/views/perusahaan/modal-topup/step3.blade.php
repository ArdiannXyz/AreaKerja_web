<!-- ================= MODAL STEP 3 ================= -->
<div id="modalStep3" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl relative p-8">
        <button onclick="closeAllModal()" class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl">âœ•</button>

        <h2 class="text-xl font-bold">Detail Pembayaran</h2>
        <div class="h-1 w-32 bg-[#00509d] mb-6"></div>

        <div class="border border-[#00509d] rounded-lg p-6 space-y-3 text-sm">
            {{-- <div class="flex justify-between"> --}}
                {{-- <span>No. Transaksi</span>
                    <span id="detailTransaksi">-</span> --}}
            {{-- </div> --}}
            <div class="flex justify-between">
                <span>Nama Pengirim</span>
                <span id="detailPengirim">-</span>
            </div>
            <div class="flex justify-between">
                <span>Nama Penerima</span>
                <span id="detailPenerima">Area Kerja</span>
            </div>
            <div class="flex justify-between">
                <span>Metode Pembayaran</span>
                <span class="bg-[#00509d] text-white text-xs font-medium px-3 py-1 rounded-full"
                    id="detailBank">-</span>
            </div>
            <div class="flex justify-between">
                <span>Tgl/Waktu</span>
                <span id="detailWaktu">-</span>
            </div>
            <div class="flex justify-between">
                <span>Jumlah Deposit</span>
                <span id="detailHarga">-</span>
            </div>
            <div class="flex justify-between">
                <span>Biaya Admin</span>
                <span id="detailAdmin">Rp. 2.000</span>
            </div>
            <div class="border-t border-dashed my-3"></div>
            <div class="flex justify-between font-semibold">
                <span>Total Pembayaran</span>
                <span id="detailTotal">-</span>
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <button type="button" id="btnKonfirmasi"
                class="w-full py-3 bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold rounded-full">
                Konfirmasi
            </button>
        </div>
    </div>
</div>

