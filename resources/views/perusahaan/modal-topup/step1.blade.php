 <!-- ================= MODAL STEP 1 ================= -->
    <div id="modalStep1" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl relative p-6 max-h-[80vh] overflow-y-auto">
            <button onclick="closeAllModal()" class="absolute top-3 right-3 text-gray-400 hover:text-black">✕</button>
            <h2 class="text-lg font-semibold mb-4">Top Up Koin</h2>
            <div class="grid grid-cols-3 gap-4">
                @foreach ($hargaPembayarans as $paket)
                    <label
                        class="paketCoinWrapper cursor-pointer border rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col items-center">

                        <!-- Input radio -->
                        <input type="radio" name="paket" value="{{ $paket->id }}"
                            data-jumlah="{{ $paket->jumlah_koin }}" data-harga="{{ $paket->harga }}"
                            class="hidden paketCoin">

                        <!-- Isi kartu -->
                        <div class="flex flex-col items-center flex-1 p-4">
                            <img src="{{ asset('icon/' . ($paket->icon ?? 'default-icon.png')) }}"
                                alt="{{ $paket->nama }}" class="w-14 h-14 mb-3">
                            <span class="text-lg font-bold text-gray-800">
                                {{ number_format($paket->jumlah_koin, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Bagian harga -->
                        <div class="w-full bg-orange-500 text-white text-center py-2 font-semibold">
                            Rp. {{ number_format($paket->harga, 0, ',', '.') }}
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="flex justify-center mt-6">
                <button id="btnConfirmStep1" onclick="goToStep(2)" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md transition">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
