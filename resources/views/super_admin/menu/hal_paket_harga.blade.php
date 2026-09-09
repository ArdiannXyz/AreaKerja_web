<div class="p-4 sm:ml-64 border rounded-lg shadow">
    {{-- paket harga koin --}}
    <div class="mb-10">
        <div class="flex justify-between items-center mb-2">
            <h2 class="flex justify-between items-start text-lg font-semibold mb-2">Paket Harga Koin</h2>
            <button onclick="bukaModal()"
                class="bg-blue-700 text-white text-xs px-5 py-1 rounded-full ml-auto">Edit</button>
        </div>
        <div class="border-2 border-gray-400 rounded-2xl overflow-hidden w[500px]">

            {{-- header --}}
            <div class="flex justify-between items-center border-2 border-gray-400 bg-blue-700 text-white px-4 py-2">
                <div class="flex-1 font-semibold">Nama</div>
                <div class="font-semibold">Harga</div>
            </div>

            {{-- isi tabel --}}
            <div class="divider-y border-2 border-gray-400 bg-white">
                <div class="flex justify-between items-center px-4 py-3">
                    <div>Pasang Lowongan Bronze</div>
                    <div>150 Koin</div>
                </div>
            </div>
        </div>
    </div>



    {{-- Paket Harga Pembayaran --}}
    <div class="mb-10">
        <div class="flex justify-between items-center mb-2">
            <h2 class="flex justify-between items-start text-lg font-semibold mb-2">Paket Harga Pembayaran</h2>
            <button class="bg-blue-700 text-white text-xs px-5 py-1 rounded-full ml-auto">Edit</button>
        </div>
        <div class="border border-gray-300 rounded-2xl overflow-hidden w[500px]">

            {{-- header --}}
            <div class="flex justify-between items-center bg-blue-700 text-white px-4 py-2">
                <div class="flex-1 font-semibold">Nama</div>
                <div class="font-semibold">Harga</div>
            </div>

            {{-- isi tabel --}}
            <div class="divider-y divide-gray-300 bg-white">
                <div class="flex justify-between items-center px-4 py-3">
                    <div>Pendaftaran kandidat</div>
                    <div>Rp. 10.000.000</div>
                </div>
            </div>
        </div>
    </div>

    @include('finance.modal-editkoin')
</div>
</div>
