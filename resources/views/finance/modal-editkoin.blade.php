<!-- Modal -->
<div id="koinModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
    <!-- Konten Modal -->
    <div class="relative bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-8 text-center animate-fadeIn">

        <!-- Tombol X -->
        {{-- <button onclick="tutupModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
            &times;
        </button> --}}
        {{-- 
        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-3 font-style: italic">HARGA COIN</h2> --}}

        <!-- Pesan -->
        {{-- header tabel --}}
        <div class="flex justify-between items-start mb-3">
            <h2 class="text-lg font-semibold">Paket Harga Koin</h2>
        </div>

        <div class="border border-gray-300 rounded-2xl overflow-hidden w[500px] mb-4">
            {{-- header --}}
            <div class="flex justify-between items-center bg-[#00509d] text-white px-4 py-2">
                <div class="flex-1 font-semibold">Nama</div>
                <div class="font-semibold">Harga</div>
            </div>

            {{-- isi tabel --}}
            <div class="divider-y divide-gray-300 bg-white">
                <div class="flex justify-between items-center px-4 py-3">
                    <div>Pasang Lowongan Bronze</div>
                    <div class="bg-gray-400">150 Koin</div>
                </div>

                <div class="divider-y divide-gray-300 bg-white">
                <div class="flex justify-between items-center px-4 py-3">
                    <div>Pasang Lowongan Silver</div>
                    <div class="bg-gray-400">150 Koin</div>
                </div>
            </div>
        </div>


        <!-- Tombol aksi -->
        <div class="flex justify-center gap-6">
            <button type="submit" onclick="tutupModal()" class="text-white bg-[#00509d] w-24 rounded-md font-medium">Selesai</button>
        </div>
    </div>
</div>

<script>
    function bukaModal() {
        const modal = document.getElementById('koinModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModal() {
        const modal = document.getElementById('koinModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>

