@extends('layouts.index-perusahaan')
@section('content')
    <!-- Hero Section -->
    <section class="relative">
        <img src="{{ asset('images/ntap.png') }}"
            alt="Header Image" class="w-full h-[600px] object-cover">


        {{-- <img src="{{ asset('images/ntap.png') }}" alt="hero" class="w-full h-[350px] object-cover"> --}}
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="absolute bottom-52 left-20 text-white">
            <h1 class="text-3xl md:text-4xl font-semibold max-w-2xl mb-3">
                Kandidat Area Kerja
            </h1>
            <p class="text-sm mb-16">Rekrut karyawan terakreditasi <br> di Area Kerja</p>
        </div>
    </section>

    <!-- Filter -->
    <form action="{{ route('perusahaan.kandidat.ak') }}" method="get">
        <div class="bg-white border-2 shadow-md p-6 -mt-12 relative z-10 max-w-4xl mx-auto rounded-lg">
            <div class="overflow-x-auto">
                <div class="flex items-center gap-4 min-w-max">
                    <!-- Skill -->
                    <div class="flex items-center gap-2 text-center border-r pr-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="24" height="24" fill="url(#pattern0_661_9288)" fill-opacity="0.3" />
                            <defs>
                                <pattern id="pattern0_661_9288" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_661_9288" transform="scale(0.0104167)" />
                                </pattern>
                                <image id="image0_661_9288" width="96" height="96" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAACXBIWXMAAAsTAAALEwEAmpwYAAAEIklEQVR4nO2cTYhWVRjHf6OjhGOJaS5Ey8LsQ9H8wIIWgbqRwChcBLVwYQoaooIIMooQiA4agbkoqCgF3RkpIZIuitCCQNMxA1skIgouzCbx+5EDJ5RBnefM3Pee59xzfvDfvTz3PP/nvOeej3svFAqFQqFQKBQKhYI9ngTeBNYC3yh+L8aVBFOBTcBvwO3ABGIbnGwBOoBlwMkBJhDb4OQK0AFsAC5VlEBsg5MpQBuwGDhfcQKxDU6iABOAH1qUQGyDzRdgIXClhQmIcUVjELAFuNPiBMS4ojAY+KqmBDYr47iO8EEFuc0Driuv2UUEhgDf1tiD2oDPlbFu+SGxv7wG9Civ9bVvW620VdjzJeAv7Ia7Pcp4rvfO70duLwdMnfcD7USgqwVj6LvKaw8FDihj9vjerGU8cFYZ+2dgGBF4q4IbrjxAN/zekAaX+I/KuJeBVxQxRwGnlDG7/V5W7UwE/mmB+eL1H/C6si0jgePKuG5R+Fwfq/Yjylh/A+OIQNsAFlkSINdjZyjb9BRwWhn3L2DsQyYT3ytjuHvDS0RiSQ3mi9cF4Hllu54Fzinjnug1dLhOtTPgfvIqkRgOXKyxAOJvhk8r2zcpoH2/+Hwc2wLuT/2ZUVXGxprNl/tudqOVbZwN/KuMexDoVP7WTTjeIyKP+3FZIulX3wYNc4FrFV9/JZFZEdF88ToMPKZs7wLgZkXXdad30fnTQAEE2Buw6lxUwVrlixhbDL2ZZcB4uU87/XaEhg8HcJ19sbYYerPFgOnSS9sD2v9RP+If8QszE/R1kB5L6wNy2BEQ93e/wjbB6Bbt+UhFWqXMw43jXyrXHW4zzgxvGzBZHqHbAfPzIYpVrlvMmWKDAZNFcfDyjjKfvjbYzLHLgMGi0FXgjaYVYLCh+b8o5FbqjSrAagOmSqAaU4BnAja1LKkxBdhnwEzJtQDvGzBSci3AqAiHLlIKcA/t0ZxVJf0PmGN820GaXAD3jM0ZAwZKrgXYasA8ybUA0yo8vout5ArQ7t9WjG2c5FqApQZMkxoLYI7vDJgmORfgmAHTJOcCpL7wktQL8II/zIhtnORaAMeUgBcdrCtppgOfBbyYZlGNYIR/D+APA4ZKjgX4n0EGDJWa5DYh12AQyUTdAU9e14pkoBvATIwiGWgdhpGG66iVR9AfhjRYN5Uvb0dFGqwuEkAaqsuxPjMQijRUnSSCNFDXgTEkgjRQu0kIaaAWkBApPiEtfUw9nyAhug2YJhXqJxLjEwOmSYVy+SR3WnbLgHFSkZaTICEvOVvXPBLEfaHwkAHzpAK9SKK4InzagOFoHIkzGfjYf3ctxSnqSDJDjMl9riArxJiyQ4wpO8SYskOMKTvEmLJDjCk7xJiyQ4wpO8SYskOMKTvEmAqFQqFQKBQKhUKBVnIXvexR+hsD3XMAAAAASUVORK5CYII=" />
                            </defs>
                        </svg>

                        <select name="skill"
                            class="appearance-none px-8 py-2 bg-transparent boder-none text-gray-600 text-sm focus:outline-none">
                            <option value="">Skill</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill }}" {{ request('skill') == $skill ? 'selected' : '' }}>
                                    {{ $skill }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Umur -->
                    <div class="flex items-center gap-2 text-center border-r pr-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect opacity="0.3" width="24" height="24" fill="url(#pattern0_661_9289)" />
                            <defs>
                                <pattern id="pattern0_661_9289" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_661_9289" transform="scale(0.0104167)" />
                                </pattern>
                                <image id="image0_661_9289" width="96" height="96" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAACXBIWXMAAAsTAAALEwEAmpwYAAACjklEQVR4nO2dS04cMRCG/83kLHkckCVeIiUQEKdJcoyQgwTYTaRCI3UkQJNMM9j1Fa76pFr7f7jdI43UloqiKIpiHHZgaKLrm96gBdc3vUELrm96gxZc3/QGLbi+6Q1acH3TG7Tg+qY3aMH1HeSTpK+SbiTdrzD01ud+8Xoh6SMZ/DtJ15L+BAjFoNl5v5K0IcL/ESAACzLfvUu4DmDags2l55mf+dixf8xW0gePAi4DmLWgc+5RwK8ARi3o/PQo4C6AUQs6tx4F0CYt+FQBqgLwXWj1BPBBWB1BfBgGTL0DVAXgu9DqCeCDsDqC+DAMmHoHqArAd6HVE8AHYVmPoNkx2j8uAAb3jwuAwf3jAmBw/7gAGNw/LgAG948LgMH94wJgcP+4gEc0SWfyBfePC3gU/t81PUvA/eMC9DR87xJw/7SA9p+1zxL4RwW0FeuPLiFtAW3F2h4lpCygvSD80SWkK6AdEf7IElIV0F4R/qgS0hTQOoQ/ooQUBbSO4fcuYfoC2oDwe5YwfQHRwf3jAmBw/7gAGNw/LgAG948LgMH94wJgcP+4ABjcPy4ABvc/WsDJijWOndM34D+EgJOg4acpoHcJvcJPVUCvEnqGn66A15bQO/yUBRxbwojw0xbw0hJGhZ+6gLUljAxf2Qs4VMLo8CP45wVofwke4YfwjwvYU4JX+CH84wKe/X+8G09w/7gAGNw/LgAG948LgMH94wJgcP+3K0Rknd8eBdwEMGqZP118EcCoBZ3PHgXsru2oz9dr7+fr38uJqwC7zYLN7mRwY7Nc20GbtiDzjbhHZrNc5rANEIBBs112vnv4z98J58svgAx3C9wtXr94XVlSFEVRFEVRFEVRFEVRaAoeAGng/CsPyJlSAAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>

                        <select name="umur"
                            class="appearance-none px-10 py-2 bg-transparent border-none text-gray-600 text-sm focus:outline-none">
                            <option value="">Umur</option>
                            @foreach ($umurRange as $umur)
                                <option value="{{ $umur }}" {{ request('umur') == $umur ? 'selected' : '' }}>
                                    {{ $umur }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <!-- Gender -->
                    <div class="flex items-center gap-2 text-center pr-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect opacity="0.3" width="24" height="24" fill="url(#pattern0_661_9289)" />
                            <defs>
                                <pattern id="pattern0_661_9289" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_661_9289" transform="scale(0.0104167)" />
                                </pattern>
                                <image id="image0_661_9289" width="96" height="96" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAACXBIWXMAAAsTAAALEwEAmpwYAAACjklEQVR4nO2dS04cMRCG/83kLHkckCVeIiUQEKdJcoyQgwTYTaRCI3UkQJNMM9j1Fa76pFr7f7jdI43UloqiKIpiHHZgaKLrm96gBdc3vUELrm96gxZc3/QGLbi+6Q1acH3TG7Tg+qY3aMH1HeSTpK+SbiTdrzD01ud+8Xoh6SMZ/DtJ15L+BAjFoNl5v5K0IcL/ESAACzLfvUu4DmDags2l55mf+dixf8xW0gePAi4DmLWgc+5RwK8ARi3o/PQo4C6AUQs6tx4F0CYt+FQBqgLwXWj1BPBBWB1BfBgGTL0DVAXgu9DqCeCDsDqC+DAMmHoHqArAd6HVE8AHYVmPoNkx2j8uAAb3jwuAwf3jAmBw/7gAGNw/LgAG948LgMH94wJgcP+4gEc0SWfyBfePC3gU/t81PUvA/eMC9DR87xJw/7SA9p+1zxL4RwW0FeuPLiFtAW3F2h4lpCygvSD80SWkK6AdEf7IElIV0F4R/qgS0hTQOoQ/ooQUBbSO4fcuYfoC2oDwe5YwfQHRwf3jAmBw/7gAGNw/LgAG948LgMH94wJgcP+4ABjcPy4ABvc/WsDJijWOndM34D+EgJOg4acpoHcJvcJPVUCvEnqGn66A15bQO/yUBRxbwojw0xbw0hJGhZ+6gLUljAxf2Qs4VMLo8CP45wVofwke4YfwjwvYU4JX+CH84wKe/X+8G09w/7gAGNw/LgAG948LgMH94wJgcP+3K0Rknd8eBdwEMGqZP118EcCoBZ3PHgXsru2oz9dr7+fr38uJqwC7zYLN7mRwY7Nc20GbtiDzjbhHZrNc5rANEIBBs112vnv4z98J58svgAx3C9wtXr94XVlSFEVRFEVRFEVRFEVRaAoeAGng/CsPyJlSAAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>

                        <select name="gender"
                            class="appearance-none px-8 py-2 bg-transparent border-none text-gray-600 text-sm focus:outline-none">
                            <option value="">Gender</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>

                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="ml-auto bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-md text-sm font-medium">
                        Cari Kandidat
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Koin -->
    <div class="max-w-6xl mx-auto mt-4 flex justify-end">
        <div class="flex items-center gap-6 bg-white  px-6 py-4 rounded-lg">
            <!-- Coin + jumlah + teks -->
            <div class="flex flex-col items-center">
                <span class="flex items-center">
                    <p class="text-yellow-500 font-semibold text-4xl">{{ $perusahaan->koin_perusahaan ?? 0 }}</p>
                    <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-10 h-10 ml-2">
                </span>
                <button onclick="toggleModal()" class="flex items-center text-green-600 text-sm font-medium">
                    <p class="mr-2">Top Up Koin</p>
                    <!-- icon + -->
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <mask id="mask0_614_15612" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                            width="22" height="22">
                            <rect x="0.53125" y="0.722656" width="20.4918" height="20.4918"
                                fill="url(#pattern0_614_15612)" />
                        </mask>
                        <g mask="url(#mask0_614_15612)">
                            <rect x="0.773438" y="0.96875" width="20" height="20" fill="#42BB72" />
                        </g>
                        <defs>
                            <pattern id="pattern0_614_15612" patternContentUnits="objectBoundingBox" width="1"
                                height="1">
                                <use xlink:href="#image0_614_15612" transform="scale(0.0104167)" />
                            </pattern>
                            <image id="image0_614_15612" width="96" height="96" preserveAspectRatio="none"
                                xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAAEhUlEQVR4nO2dz48URRTHP47ouCPIL1kJRBc9AAdNIF6JhkVMjEwg/uJEOHhBvUDgjyASJRIve9UbBxU5GvGCePRHlMCGRUPWFQPyQxDwsnCoISyrs/W6q6pf9fT7JN/Mpabn9fdNdXV3Vb8GwzAMwzAMwzCaxgPaAXgYBtYDa2ZoGHgUWNz7BPgHuNz7/BMYB04Bp4HvgQuVRl2A3BLQATYDo8BG4FnCY7wN/Awc6+kr4GbgNgeKFrABGAOu4gxLqRvAYaALPFjB/mXLI8C7wFnSm95PE8A7vVgaQwfYB0yhZ/xsTQF7e7ENNF3gV/QN76dJ4M1ke6/IKuAo+gZLdQQYSeKEAtuAS+ibWlRXge0J/KiMNvAR+kaGaqy3L7ViKfAd+ubF0rfAkqgOJWQF8BP6psXWSeCpiD4lYS1wDn2zUulcbx+zZCXwG/ompdYkGZ4hLcV1UW1zqtI47qZgFrQZrAFXqhNkcnb0MfpmaOlQBP+CeAN9E7T1erCLJVkFXBEEOOi6jNKg/GWJYAdVRwK9LMy2SIEPkrpBjhagg+75vg+tuM4CQ4L47qNV9AvAe2R4IZIBTwO7Uv9IG/gd3a7uQzO2PyjYC4r2gLdxN9uM/2c5sDPVxlvoTqDXoQfcxk30i5fSFOkBL+KOc8bcPAO8IG1cJAE7isfSWMReSbvKEG6AWVgqnLj4YpYcplLzN2488K7Ak/aAl8nD/LrwGLBJ0lCagNHysTSWjZJG0gSINmbch+hPKxkDhoHzwrZVUIcxAGAaeAK4OFcjSQ9YTz7m14kWsE7SyMea8Fgai9c7S0BaoiRgdYRAmoo3AfMEG1keIZCZpB5PYjzSFAuvd5IesCBCIE3F650lIC1REjA/QiBNJUoCjIRIEnA9eRSDyzVfA0kCvBsx+mIJUCZKAs5HCGQmuc8Jx8TrnSQB4xECaSqnfQ0kCfBuxOiLJUAZr3eS+ybLcDV4cpkTqNOEzDDw11yNJD3gAq7ejlGMH/GYD/Ir4WNhsTQSkWeWgHR8I2lkC7PSEH1h1k3gs5CIGsZhhHXpitwN/bRcLI0kiVct3NJr7eXfPrTjO0Oi5enTwAcF2jeVAyQch+wRpbk1RcEKjEVnxP4FPiz4nSbxPnAr9Y900K186EMrrglKPKZallcq2KG6aUuQoyWwUgX39Hmgl6UYwRWq0N55bV1CsZZcF3d6qm2ClqaB14JdDOQQ+kZo6WAE/4Jp4+pqaptRtY4DD0fwLwoLcRMQ2qZUpV/IsJBrk8pWZlvAtQmFW7N/YmgFg3k4Ogk8GdGnpCzB1dXUNi2WjpPhMd/HQ8B+6n+dMEZGZztl2Ep9X+DwVgI/VBihXveOviDjM50QuuRRdaufJoBXk+19JnSAPejPrM3UJLCbCu/n50Ab9xI1zYn+CVypySyqoGvyPO6FPxdJb/oV4BPgJTJYcKwewCyGcJWmRnt6jvAnOadx77S5+zLPr6lg3lZKbgmYzeO4cjmrcbc57r7OdgGwiHvPMF/H/bOv8d/X2f6Ap2aPYRiGYRiGYRhGldwBFK9RwjpRCLwAAAAASUVORK5CYII=" />
                        </defs>
                    </svg>

                </button>
            </div>
        </div>
    </div>


    <!-- Tabel Kandidat -->
    <div class="max-w-6xl mx-auto mt-6 mb-10 overflow-x-auto rounded-lg border shadow-md">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-[#00509d] text-white text-center">
                    <th class="py-3 px-4 font-medium">Nama</th>
                    <th class="py-3 px-4 font-medium">Skill</th>
                    <th class="py-3 px-4 font-medium">Umur</th>
                    <th class="py-3 px-4 font-medium">Pengalaman</th>
                    <th class="py-3 px-4 font-medium">Gender</th>
                    <th class="py-3 px-4 font-medium">CV</th>
                    <th class="py-3 px-4 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pelamars as $p)
                    <tr class="hover:bg-gray-50 text-center">
                        <td class="py-3 px-4">{{ $p->nama_pelamar }}</td>
                        <td class="py-3 px-4">
                            @if ($p->skill->isNotEmpty())
                                {{ $p->skill->pluck('skill')->implode(', ') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $p->umur ?? '-' }} Tahun</td>
                        <td class="py-3 px-4">Expert</td>
                        <td class="py-3 px-4">{{ $p->gender_singkat ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <button onclick="openConfirmModal({{ $p->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#00509d]"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 3v12l4-4h-3V3h-2v8H8l4 4zM4 19h16v2H4z" />
                                </svg>
                            </button>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $sudahPernahDibeli = \App\Models\PelamarLowongan::where('pelamar_id', $p->id)
                                    ->whereHas('lowongan_perusahaan', function ($q) use ($perusahaan) {
                                        $q->where('perusahaan_id', $perusahaan->id);
                                    })
                                    ->exists();
                            @endphp
                            <button
                                class="btn-beli {{ $sudahPernahDibeli ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-500 hover:bg-green-600' }} text-white px-10 py-2 rounded-md text-xs font-medium"
                                data-id="{{ $p->id }}">
                                {{ $sudahPernahDibeli ? 'Beli Lagi' : 'Beli' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-3 px-4 text-center">Tidak ada kandidat yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL CV --}}
    <div id="confirmModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white p-6 rounded-lg text-center max-w-sm w-full scale-[0.85] md:scale-100">
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#00509d] mb-4" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M12 16l4-5h-3V4h-2v7H8l4 5zM4 20h16v2H4z" />
                </svg>
            </div>
            <p class="mb-4 font-medium">Yakin akan mengunduh CV pelamar?</p>
            <div class="flex justify-center gap-4">
                <button onclick="downloadCV()" class="px-4 py-2 bg-[#00509d] text-white rounded">Unduh</button>
                <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-black rounded">Batal</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Sukses -->
    <div id="successModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white p-6 rounded-lg text-center max-w-sm w-full scale-[0.85] md:scale-100">
            <div class="flex justify-center">
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#00509d]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 font-semibold">CV Berhasil diunduh</p>
            <button onclick="closeSuccessModal()" class="mt-4 px-4 py-2 bg-[#00509d] text-white rounded">Tutup</button>
        </div>
    </div>

    <!-- Modal Pilih Lowongan -->
    <div id="modalPilihLowongan"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 transition-all">
        <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md text-center animate-fade-in">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Pilih Lowongan</h3>
            <select id="selectLowongan"
                class="w-full border-2 border-gray-400 rounded-lg px-4 py-2 text-sm  focus:outline-none">
                @foreach (auth()->user()->perusahaan->lowonganPerusahaans as $low)
                    <option value="{{ $low->id }}">{{ $low->nama }}</option>
                @endforeach
            </select>
            <div class="flex justify-center gap-4 mt-6">
                <button id="btnLanjut"
                    class="bg-[#00509d] hover:bg-[#003d7a] text-white px-5 py-2 rounded-md transition-all">Lanjutkan</button>
                <button id="btnCancel1"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-5 py-2 rounded-md transition-all">Batal</button>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Lowongan -->
    <div id="modalPilihLowongan"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 transition-all">
        <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md text-center animate-fade-in">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Pilih Lowongan</h3>
            <select id="selectLowongan"
                class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#00509d] focus:outline-none">
                <option value="">-- Pilih Lowongan --</option>
                @foreach (auth()->user()->perusahaan->lowonganPerusahaans as $low)
                    <option value="{{ $low->id }}">{{ $low->nama }}</option>
                @endforeach
            </select>
            <div class="flex justify-center gap-4 mt-6">
                <button id="btnLanjut"
                    class="bg-[#00509d] hover:bg-[#003d7a] text-white px-5 py-2 rounded-md transition-all">
                    Lanjutkan
                </button>
                <button id="btnCancel1"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-5 py-2 rounded-md transition-all">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="modalKonfirmasi"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 transition-all">
        <div
            class="bg-white p-10 rounded-2xl shadow-xl w-[360px] text-center animate-fade-in flex flex-col items-center justify-center space-y-5">

            <!-- Nominal Koin -->
            <div class="flex items-center justify-center space-x-2">
                <span class="text-4xl font-bold text-yellow-500">100</span>
                <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-8 h-8">
            </div>

            <!-- Teks -->
            <p class="text-gray-800 font-medium">Beli kandidat area kerja</p>

            <!-- Tombol -->
            <div class="flex items-center justify-center space-x-4">
                <button id="btnKonfirmasiBeli"
                    class="bg-green-500 hover:bg-green-600 text-white font-medium px-8 py-2 rounded-full transition-all">
                    Beli
                </button>
                <button id="btnKonfirmasiBatal"
                    class="bg-red-500 hover:bg-red-600 text-white font-medium px-8 py-2 rounded-full transition-all">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- âœ… Modal Top Up -->
    <div id="modalTopUp"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 transition-all">
        <div
            class="bg-white p-10 rounded-2xl shadow-xl w-[360px] text-center animate-fade-in flex flex-col items-center justify-center space-y-5">
            <h2 class="text-2xl font-semibold italic text-gray-800">Upss!!</h2>
            <p class="text-gray-600 leading-relaxed text-sm">
                Koin anda kurang silahkan <br> Top Up terlebih dahulu.
            </p>
            <button onclick="toggleModal()"
                class="bg-[#00509d] hover:bg-[#003d7a] text-white font-medium px-8 py-2 rounded-full transition-all text-sm">
                Top Up
            </button>
        </div>
    </div>

    {{-- @include('perusahaan.modal-topup') --}}

    <script>
        let selectedId = null;

        function openConfirmModal(id) {
            selectedId = id;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function downloadCV() {
            if (!selectedId) return;
            closeConfirmModal();
            document.getElementById('successModal').classList.remove('hidden');
            setTimeout(() => {
                let url = "{{ route('cv.download', ':id') }}";
                url = url.replace(':id', selectedId);
                window.location.href = url;
            }, 500);
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
        }
    </script>

    <script>
        const harga = 100;
        let selectedPelamarId = null;
        let selectedLowonganId = null;

        const modalPilih = document.getElementById('modalPilihLowongan');
        const modalKonfirmasi = document.getElementById('modalKonfirmasi');
        const modalTopUp = document.getElementById('modalTopUp');
        const selectLowongan = document.getElementById('selectLowongan');

        // ðŸ”¸ Buka modal pilih lowongan
        document.querySelectorAll('.btn-beli').forEach(btn => {
            btn.addEventListener('click', function() {
                selectedPelamarId = this.dataset.id;
                modalPilih.classList.remove('hidden');
            });
        });

        // ðŸ”¸ Tombol lanjut dari modal pilih lowongan
        document.getElementById('btnLanjut').addEventListener('click', () => {
            selectedLowonganId = selectLowongan.value;
            if (!selectedLowonganId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Lowongan',
                    text: 'âš ï¸ Pilih lowongan terlebih dahulu!',
                    confirmButtonColor: '#3085d6',
                });
                return;
            }
            modalPilih.classList.add('hidden');
            modalKonfirmasi.classList.remove('hidden');
            document.getElementById('hargaKoin').textContent = harga;
        });

        // ðŸ”¸ Tombol batal di modal pilih lowongan
        document.getElementById('btnCancel1').addEventListener('click', () => {
            modalPilih.classList.add('hidden');
        });

        // ðŸ”¸ Tombol konfirmasi beli
        document.getElementById('btnKonfirmasiBeli').addEventListener('click', async () => {
            modalKonfirmasi.classList.add('hidden');

            try {
                const res = await fetch("{{ route('kandidat.beli') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pelamar_id: selectedPelamarId,
                        lowongan_perusahaan_id: selectedLowonganId
                    })
                });

                let data = {};
                try {
                    data = await res.json();
                } catch (jsonErr) {
                    console.warn('Respon bukan JSON:', jsonErr);
                }

                //Jika akun belum verifikasi Tampilkan alert dan stop semua proses
                if (!data.success && data.type === 'verification') {
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Akun Belum Terverifikasi',
                        text: data.message,
                        confirmButtonText: 'Mengerti'
                    });

                    return; //STOP SEMUA PROSES
                }


                if (!res.ok) {
                    throw new Error(data.message || `HTTP Error ${res.status}`);
                }

                // --- jika sukses ---
                if (data.status === 'success' || data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembelian Berhasil!',
                        text: 'Data kandidat berhasil dibeli.',
                        confirmButtonColor: '#22c55e',
                    });

                    const btn = document.querySelector(`.btn-beli[data-id="${selectedPelamarId}"]`);
                    if (btn) {
                        btn.textContent = 'Beli Lagi';
                        btn.classList.remove('bg-green-500', 'hover:bg-green-600');
                        btn.classList.add('bg-gray-600', 'hover:bg-gray-700');
                        //  jangan pakai btn.disabled = true
                    }

                    return;
                }

                // --- jika gagal karena koin ---
                if (data.message && data.message.toLowerCase().includes('koin')) {
                    // langsung buka modal top up
                    modalTopUp.classList.remove('hidden');
                    return;
                }


                // --- fallback error lainnya ---
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan.',
                    confirmButtonColor: '#d33'
                });

            } catch (err) {
                console.error('Fetch Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Bermasalah',
                    text: 'âš ï¸ Terjadi kesalahan koneksi, coba lagi nanti.',
                    confirmButtonColor: '#d33'
                });
            }
        });


        // ðŸ”¸ Tombol batal di modal konfirmasi
        document.getElementById('btnKonfirmasiBatal').addEventListener('click', () => {
            modalKonfirmasi.classList.add('hidden');
        });

        // ðŸ”¸ Tutup modal Top Up jika klik di luar
        modalTopUp.addEventListener('click', e => {
            if (e.target === modalTopUp) {
                modalTopUp.classList.add('hidden');
            }
        });
    </script>


    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.25s ease-out;
        }
    </style>



    @include('layouts.footer')
@endsection

