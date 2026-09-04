<!-- Modal -->
<div id="talentModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
    <!-- Konten Modal -->
    <div class="relative bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-8 text-center animate-fadeIn">

        <!-- Tombol X -->
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
            &times;
        </button>

        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-3 font-style: italic">Upss!!</h2>

        <!-- Pesan -->
        <p class="text-gray-700 mb-8">
            Koin anda kurang silahkan Top Up <br>terlebih dahulu
        </p>

        <!-- Tombol aksi -->
        <div class="flex justify-center gap-6">
            <a href="{{ url('/modal-topup') }}" onclick="toggleModal()"
                class="text-[#00509d] font-medium">Top Up</a>
        </div>
    </div>
</div>

<script>
    function openModal() {
        const modal = document.getElementById('talentModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('talentModal');
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

