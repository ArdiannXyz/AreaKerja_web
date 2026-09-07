<!-- Modal -->
<div id="logoutModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
    <!-- Konten Modal -->
    <div class="relative bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-8 text-center animate-fadeIn">

        <!-- Tombol X -->
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
            &times;
        </button>

        <!-- Gambar ilustrasi -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/orang.png') }}" alt="Ilustrasi" class="w-30 h-28">
        </div>

        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-3">Konfirmasi Keluar</h2>

        <!-- Pesan -->
        <p class="text-gray-700 mb-8">
            Apakah anda yakin untuk keluar dari <span class="font-bold">Jason Statham</span>?
        </p>

        <!-- Tombol aksi -->
        <div class="flex justify-center gap-6">
            <button onclick="logoutLogout()"
                class="px-6 py-2 bg-[#00509d] text-white rounded-full font-medium shadow hover:bg-[#003d7a]">
                Keluar
            </button>
            <button onclick="closeModal()"
                class="px-6 py-2 bg-gray-400 text-white rounded-full font-medium shadow hover:bg-gray-500">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    function openModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function logoutLogout() {
        alert('Anda telah keluar!');
        closeModal();
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

