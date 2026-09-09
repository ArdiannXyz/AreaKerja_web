    @extends('layouts.index-perusahaan')
    @section('content')
        <div class="w-full mx-auto bg-white p-6 pb-8 mt-20">
            <!-- Header -->
            <h2 class="text-lg text-[#00509d] font-semibold">Dashboard</h2>
            <h1 class="text-2xl font-semibold mt-1 mb-4">Selamat Datang di Area Kerja <br>
                <span class="text-[#00509d] font-bold">{{ $perusahaan->nama_perusahaan }}</span>
            </h1>

            <!-- Grid utama -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">

                <!-- === Lowongan Saya === -->
                <div class="bg-[#00509d] text-white p-7 rounded-xl shadow lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold">Lowongan Saya</h3>
                        <a href="{{ route('lowongan.saya.perusahaan') }}"
                            class="border-2 border-white bg-[#00509d] text-white px-4 py-1 rounded-md text-lg font-semibold hover:bg-white/20 transition duration-300">
                            Kelola Lowongan
                        </a>

                    </div>

                    @php
                        $publish = $lowongans->filter(fn($l) => !is_null($l->published_at));
                        $draft = $lowongans->filter(fn($l) => is_null($l->published_at));
                    @endphp

                    @if ($lowongans->isEmpty())
                        <!-- Jika BELUM ADA lowongan -->
                        <div class="bg-white rounded-lg flex justify-between items-center px-4 py-3">

                            <span class="text-black font-semibold">Lowongan Belum Terpasang</span>
                            <a href="{{ route('lowongan.saya.perusahaan') }}"
                                class="border border-[#00509d] text-[#00509d] px-3 py-1 rounded-md text-sm font-medium hover:bg-blue-50 transition">
                                Tambah Lowongan
                            </a>
                        </div>

                        <!-- Tombol Top Up Koin -->
                        <div class="bg-white rounded-xl mt-4 px-4 py-2 text-green-700 inline-block">
                            <div class="max-w-2xl mx-auto flex justify-end">
                                <div class="flex items-center gap-6 bg-white px-2 py-1">
                                    <!-- Coin + jumlah + teks -->
                                    <div class="flex flex-col items-center">
                                        <span class="flex items-center">
                                            <p class="text-yellow-500 font-semibold text-4xl">
                                                {{ $perusahaan->koin_perusahaan ?? 0 }}
                                            </p>
                                            <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-8 h-8 ml-4">
                                        </span>
                                        <button onclick="toggleModal()"
                                            class="flex items-center text-green-600 text-sm font-medium">
                                            <p class="mr-2">Top Up Koin</p>
                                            <!-- icon + -->
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <mask id="mask0_614_15612" style="mask-type:alpha"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="22">
                                                    <rect x="0.53125" y="0.722656" width="20.4918" height="20.4918"
                                                        fill="url(#pattern0_614_15612)" />
                                                </mask>
                                                <g mask="url(#mask0_614_15612)">
                                                    <rect x="0.773438" y="0.96875" width="20" height="20"
                                                        fill="#42BB72" />
                                                </g>
                                                <defs>
                                                    <pattern id="pattern0_614_15612" patternContentUnits="objectBoundingBox"
                                                        width="1" height="1">
                                                        <use xlink:href="#image0_614_15612" transform="scale(0.0104167)" />
                                                    </pattern>
                                                    <image id="image0_614_15612" width="96" height="96"
                                                        preserveAspectRatio="none"
                                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAAEhUlEQVR4nO2dz48URRTHP47ouCPIL1kJRBc9AAdNIF6JhkVMjEwg/uJEOHhBvUDgjyASJRIve9UbBxU5GvGCePRHlMCGRUPWFQPyQxDwsnCoISyrs/W6q6pf9fT7JN/Mpabn9fdNdXV3Vb8GwzAMwzAMwzCaxgPaAXgYBtYDa2ZoGHgUWNz7BPgHuNz7/BMYB04Bp4HvgQuVRl2A3BLQATYDo8BG4FnCY7wN/Awc6+kr4GbgNgeKFrABGAOu4gxLqRvAYaALPFjB/mXLI8C7wFnSm95PE8A7vVgaQwfYB0yhZ/xsTQF7e7ENNF3gV/QN76dJ4M1ke6/IKuAo+gZLdQQYSeKEAtuAS+ibWlRXge0J/KiMNvAR+kaGaqy3L7ViKfAd+ubF0rfAkqgOJWQF8BP6psXWSeCpiD4lYS1wDn2zUulcbx+zZCXwG/ompdYkGZ4hLcV1UW1zqtI47qZgFrQZrAFXqhNkcnb0MfpmaOlQBP+CeAN9E7T1erCLJVkFXBEEOOi6jNKg/GWJYAdVRwK9LMy2SIEPkrpBjhagg+75vg+tuM4CQ4L47qNV9AvAe2R4IZIBTwO7Uv9IG/gd3a7uQzO2PyjYC4r2gLdxN9uM/2c5sDPVxlvoTqDXoQfcxk30i5fSFOkBL+KOc8bcPAO8IG1cJAE7isfSWMReSbvKEG6AWVgqnLj4YpYcplLzN2488K7Ak/aAl8nD/LrwGLBJ0lCagNHysTSWjZJG0gSINmbch+hPKxkDhoHzwrZVUIcxAGAaeAK4OFcjSQ9YTz7m14kWsE7SyMea8Fgai9c7S0BaoiRgdYRAmoo3AfMEG1keIZCZpB5PYjzSFAuvd5IesCBCIE3F650lIC1REjA/QiBNJUoCjIRIEnA9eRSDyzVfA0kCvBsx+mIJUCZKAs5HCGQmuc8Jx8TrnSQB4xECaSqnfQ0kCfBuxOiLJUAZr3eS+ybLcDV4cpkTqNOEzDDw11yNJD3gAq7ejlGMH/GYD/Ir4WNhsTQSkWeWgHR8I2lkC7PSEH1h1k3gs5CIGsZhhHXpitwN/bRcLI0kiVct3NJr7eXfPrTjO0Oi5enTwAcF2jeVAyQch+wRpbk1RcEKjEVnxP4FPiz4nSbxPnAr9Y900K186EMrrglKPKZallcq2KG6aUuQoyWwUgX39Hmgl6UYwRWq0N55bV1CsZZcF3d6qm2ClqaB14JdDOQQ+kZo6WAE/4Jp4+pqaptRtY4DD0fwLwoLcRMQ2qZUpV/IsJBrk8pWZlvAtQmFW7N/YmgFg3k4Ogk8GdGnpCzB1dXUNi2WjpPhMd/HQ8B+6n+dMEZGZztl2Ep9X+DwVgI/VBihXveOviDjM50QuuRRdaufJoBXk+19JnSAPejPrM3UJLCbCu/n50Ab9xI1zYn+CVypySyqoGvyPO6FPxdJb/oV4BPgJTJYcKwewCyGcJWmRnt6jvAnOadx77S5+zLPr6lg3lZKbgmYzeO4cjmrcbc57r7OdgGwiHvPMF/H/bOv8d/X2f6Ap2aPYRiGYRiGYRhGldwBFK9RwjpRCLwAAAAASUVORK5CYII=" />
                                                </defs>
                                            </svg>

                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    @elseif ($publish->isEmpty() && $draft->isNotEmpty())
                        <!-- Jika SUDAH ADA lowongan tapi BELUM publish -->
                        <div class="bg-white rounded-lg flex justify-between items-center px-4 py-3">
                            <span class="text-black font-semibold">Lowongan masih draft / belum publish</span>
                            <a href="{{ route('lowongan.saya.perusahaan') }}"
                                class="border border-[#00509d] text-[#00509d] px-3 py-1 rounded-md text-sm font-medium hover:bg-blue-50 transition">
                                Kelola Lowongan
                            </a>
                        </div>

                        <div class="bg-white rounded-xl mt-4 px-4 py-2 text-green-700 inline-block">
                            <div class="max-w-2xl mx-auto flex justify-end">
                                <div class="flex items-center gap-6 bg-white px-2 py-1">
                                    <!-- Coin + jumlah + teks -->
                                    <div class="flex flex-col items-center">
                                        <span class="flex items-center">
                                            <p class="text-yellow-500 font-semibold text-4xl">
                                                {{ $perusahaan->koin_perusahaan ?? 0 }}
                                            </p>
                                            <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-8 h-8 ml-4">
                                        </span>
                                        <button onclick="toggleModal()"
                                            class="flex items-center text-green-600 text-sm font-medium">
                                            <p class="mr-2">Top Up Koin</p>
                                            <!-- icon + -->
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <mask id="mask0_614_15612" style="mask-type:alpha"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="22">
                                                    <rect x="0.53125" y="0.722656" width="20.4918" height="20.4918"
                                                        fill="url(#pattern0_614_15612)" />
                                                </mask>
                                                <g mask="url(#mask0_614_15612)">
                                                    <rect x="0.773438" y="0.96875" width="20" height="20"
                                                        fill="#42BB72" />
                                                </g>
                                                <defs>
                                                    <pattern id="pattern0_614_15612" patternContentUnits="objectBoundingBox"
                                                        width="1" height="1">
                                                        <use xlink:href="#image0_614_15612" transform="scale(0.0104167)" />
                                                    </pattern>
                                                    <image id="image0_614_15612" width="96" height="96"
                                                        preserveAspectRatio="none"
                                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAAEhUlEQVR4nO2dz48URRTHP47ouCPIL1kJRBc9AAdNIF6JhkVMjEwg/uJEOHhBvUDgjyASJRIve9UbBxU5GvGCePRHlMCGRUPWFQPyQxDwsnCoISyrs/W6q6pf9fT7JN/Mpabn9fdNdXV3Vb8GwzAMwzAMwzCaxgPaAXgYBtYDa2ZoGHgUWNz7BPgHuNz7/BMYB04Bp4HvgQuVRl2A3BLQATYDo8BG4FnCY7wN/Awc6+kr4GbgNgeKFrABGAOu4gxLqRvAYaALPFjB/mXLI8C7wFnSm95PE8A7vVgaQwfYB0yhZ/xsTQF7e7ENNF3gV/QN76dJ4M1ke6/IKuAo+gZLdQQYSeKEAtuAS+ibWlRXge0J/KiMNvAR+kaGaqy3L7ViKfAd+ubF0rfAkqgOJWQF8BP6psXWSeCpiD4lYS1wDn2zUulcbx+zZCXwG/ompdYkGZ4hLcV1UW1zqtI47qZgFrQZrAFXqhNkcnb0MfpmaOlQBP+CeAN9E7T1erCLJVkFXBEEOOi6jNKg/GWJYAdVRwK9LMy2SIEPkrpBjhagg+75vg+tuM4CQ4L47qNV9AvAe2R4IZIBTwO7Uv9IG/gd3a7uQzO2PyjYC4r2gLdxN9uM/2c5sDPVxlvoTqDXoQfcxk30i5fSFOkBL+KOc8bcPAO8IG1cJAE7isfSWMReSbvKEG6AWVgqnLj4YpYcplLzN2488K7Ak/aAl8nD/LrwGLBJ0lCagNHysTSWjZJG0gSINmbch+hPKxkDhoHzwrZVUIcxAGAaeAK4OFcjSQ9YTz7m14kWsE7SyMea8Fgai9c7S0BaoiRgdYRAmoo3AfMEG1keIZCZpB5PYjzSFAuvd5IesCBCIE3F650lIC1REjA/QiBNJUoCjIRIEnA9eRSDyzVfA0kCvBsx+mIJUCZKAs5HCGQmuc8Jx8TrnSQB4xECaSqnfQ0kCfBuxOiLJUAZr3eS+ybLcDV4cpkTqNOEzDDw11yNJD3gAq7ejlGMH/GYD/Ir4WNhsTQSkWeWgHR8I2lkC7PSEH1h1k3gs5CIGsZhhHXpitwN/bRcLI0kiVct3NJr7eXfPrTjO0Oi5enTwAcF2jeVAyQch+wRpbk1RcEKjEVnxP4FPiz4nSbxPnAr9Y900K186EMrrglKPKZallcq2KG6aUuQoyWwUgX39Hmgl6UYwRWq0N55bV1CsZZcF3d6qm2ClqaB14JdDOQQ+kZo6WAE/4Jp4+pqaptRtY4DD0fwLwoLcRMQ2qZUpV/IsJBrk8pWZlvAtQmFW7N/YmgFg3k4Ogk8GdGnpCzB1dXUNi2WjpPhMd/HQ8B+6n+dMEZGZztl2Ep9X+DwVgI/VBihXveOviDjM50QuuRRdaufJoBXk+19JnSAPejPrM3UJLCbCu/n50Ab9xI1zYn+CVypySyqoGvyPO6FPxdJb/oV4BPgJTJYcKwewCyGcJWmRnt6jvAnOadx77S5+zLPr6lg3lZKbgmYzeO4cjmrcbc57r7OdgGwiHvPMF/H/bOv8d/X2f6Ap2aPYRiGYRiGYRhGldwBFK9RwjpRCLwAAAAASUVORK5CYII=" />
                                                </defs>
                                            </svg>

                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    @else
                        <!-- Jika SUDAH ADA yang DIPUBLISH -->
                        <div class="space-y-4">
                            @foreach ($lowongans as $lowongan)
                                @if ($lowongan->published_at)
                                    <div
                                        class="bg-white rounded-lg p-5 flex flex-col lg:flex-row lg:items-center justify-between shadow-sm gap-4">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ asset('storage/' . $lowongan->perusahaan->img_profile) }}"
                                                alt="logo" class="w-12 h-12 rounded-full">
                                            <div>

                                                <h4 class="font-semibold text-gray-500">{{ $perusahaan->nama_perusahaan }}
                                                </h4>
                                                <p class="text-black font-medium">{{ $lowongan->nama }} -
                                                    {{ $lowongan->jenis }}</p>
                                                <p class="text-gray-500 text-sm mb-2">{{ $lowongan->alamat }}</p>
                                                <p
                                                    class="text-gray-700 text-sm bg-gray-300 px-2 py-1 inline-block rounded">
                                                    Rp. {{ number_format($lowongan->gaji_awal, 0, ',', '.') }} â€“
                                                    Rp. {{ number_format($lowongan->gaji_akhir, 0, ',', '.') }} per bulan
                                                </p>
                                                <p class="text-xs text-gray-400 mt-2">
                                                    Aktif {{ $lowongan->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 mt-3 md:mt-0">
                                            @php
                                                $paket = strtolower($lowongan->paket->nama ?? '');

                                                $paketStyle = match ($paket) {
                                                    'gold' => 'border-yellow-500 bg-yellow-100 text-yellow-600',
                                                    'silver' => 'border-gray-400 bg-gray-100 text-gray-500',
                                                    'bronze' => 'border-amber-600 bg-amber-100 text-amber-700',
                                                    default => 'border-gray-300 text-gray-500',
                                                };
                                            @endphp

                                            <span class="px-3 py-1.5 border-2 rounded-md text-sm {{ $paketStyle }}">
                                                {{ ucfirst($lowongan->paket->nama ?? '-') }}
                                            </span>

                                            <a href="{{ route('perusahaan.pelamar', $lowongan->slug) }}"
                                                class="bg-[#00509d] hover:bg-[#003d7a] text-white px-4 py-2 rounded-md text-sm font-medium transition">
                                                Lihat Pelamar
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- <!-- Tombol cari kandidat -->
                        <div class="text-center mt-6">
                            <a href="#"
                                class="bg-white text-[#00509d] border border-[#00509d] px-5 py-2 rounded-md font-medium hover:bg-blue-50 transition">
                                Cari Kandidat
                            </a>
                        </div> --}}
                    @endif

                </div>

                <!-- === Kandidat Saya === -->
                <div class="bg-[#00509d] rounded-2xl p-8 flex flex-col w-full">

                    <div>
                        @if ($publish->isNotEmpty())
                            <h2 class="text-xl font-semibold text-white mb-6">Koin Saya</h2>
                            <!-- Tampilkan saldo koin -->
                            <div class="mb-6">
                                <div class="flex flex-col items-center">
                                    <span class="flex items-center">
                                        <p class="text-yellow-300  font-semibold text-4xl">
                                            {{ $perusahaan->koin_perusahaan ?? 0 }}
                                        </p>
                                        <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-8 h-8 ml-3">
                                    </span>
                                    <button onclick="toggleModal()"
                                        class="flex items-center mt-2 text-white font-medium hover:text-yellow-200">
                                        <p class="mr-2">Top Up Koin</p>
                                        <svg width="20" height="20" viewBox="0 0 22 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="0.5" y="0.5" width="20" height="20" rx="10"
                                                fill="#42BB72" />
                                            <path d="M11 6V16M6 11H16" stroke="white" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-[#00509d] my-2"></div>

                    <div class="flex flex-col">
                        <h2 class="text-xl font-semibold text-white mb-4 mt-5">Kandidat Saya</h2>
                        <!-- Tombol Lihat Kandidat -->
                        <a href="{{ route('perusahaan.kandidat.saya') }}"
                            class="w-48 mx-auto py-2 mb-4 border border-white text-white font-semibold rounded-lg hover:bg-white/10 transition">
                            <span class="ml-[40px]">Lihat Kandidat</span>
                        </a>

                        <!-- Tombol Cari Kandidat -->
                        <a href="{{ route('perusahaan.kandidat.ak') }}"
                            class="w-48 mx-auto py-2 bg-white text-black font-semibold rounded-lg hover:bg-gray-100 transition">
                            <span class="ml-[40px]">Cari Kandidat</span>
                        </a>
                    </div>
                </div>


            </div>

            <h1 class="text-center text-3xl text-[#00509d] font-bold mt-8 mb-4">Tentang Area Kerja</h1>
            <!-- === Bagian Bawah === -->
            <div class="grid md:grid-cols-2 gap-8 mb-4 items-center">
                <!-- Gambar -->
                <div class="flex justify-center">
                    <img src="{{ asset('images/nari.jpg') }}" alt="Illustrasi" class="w-full max-w-md object-contain">
                </div>
                <!-- 3 Card kecil -->
                <div class="grid lg:grid-cols-2 gap-6 max-w-5xl mx-auto items-center">
                    <!-- Card 1 -->
                    <div class="bg-[#00509d] text-white p-6 rounded-lg flex flex-col justify-center shadow">
                        <div class="flex items-center space-x-3 mb-3">
                            <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="logo" class="w-7 h-7 object-contain">
                            <div>
                                <p class="font-bold text-lg">01</p>
                                <p class="text-sm">Mencari Lowongan</p>
                            </div>
                        </div>
                        <p class="text-sm leading-relaxed">
                            Area Kerja membantu pencari kerja menemukan posisi sesuai keahlian dan minat mereka.
                        </p>
                    </div>

                    <!-- Card 2 & 3 -->
                    <div class="flex flex-col gap-6">
                        <div class="border-2 border-[#00509d] rounded-lg p-6 text-[#00509d] shadow-sm">
                            <div class="flex items-center space-x-3 mb-3">
                                <img src="{{ asset('images/logo_area_kerja_biru.png') }}" alt="logo" class="w-7 h-7 object-contain">
                                <div>
                                    <p class="font-bold text-lg">02</p>
                                    <p class="text-sm">Lowongan Terbaru</p>
                                </div>
                            </div>
                            <p class="text-sm leading-relaxed">
                                Temukan berbagai lowongan terbaru yang selalu diperbarui setiap hari.
                            </p>
                        </div>

                        <div class="border-2 border-[#00509d] rounded-lg p-6 text-[#00509d] shadow-sm">
                            <div class="flex items-center space-x-3 mb-3">
                                <img src="{{ asset('images/logo_area_kerja_biru.png') }}" alt="logo" class="w-7 h-7 object-contain">
                                <div>
                                    <p class="font-bold text-lg">03</p>
                                    <p class="text-sm">Pasti Cocok</p>
                                </div>
                            </div>
                            <p class="text-sm leading-relaxed">
                                Kandidat yang mendaftar sudah siap kerja secara mental maupun skill.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('layouts.footer')
    @endsection
