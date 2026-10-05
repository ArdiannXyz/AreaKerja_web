@props([
    'message' => 'Koin anda kurang silahkan Top Up terlebih dahulu.',
    'buttonText' => 'Top Up',
    'buttonLink' => '#',
    'open' => false,
])

<div x-data="{ open: @json($open) }" x-show="open"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-transition>
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full text-center relative">

        <!-- Judul -->
        <h2 class="text-2xl font-bold italic text-gray-800 mb-4">Upss!!</h2>

        <!-- Pesan -->
        <p class="text-gray-600 mb-8">{{ $message }}</p>

        <!-- Tombol -->
        <a href="{{ $buttonLink }}"
            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold px-8 py-3 rounded-full transition">
            {{ $buttonText }}
        </a>
    </div>
</div>

