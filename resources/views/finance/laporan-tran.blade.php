@extends('finance.sidebar.index')
@section('sidebar')
    <div class="p-4 sm:ml-64">

        <!-- Header -->
        <header class="w-full flex items-center justify-between px-4 sm:px-6 py-3">
            <p class="font-semibold text-xl sm:text-2xl">Catatan Transaksi</p>
        </header>

        <!-- Deskripsi -->
        <div class="ml-2 sm:ml-[24px] mb-6">
            <h4 class="font-semibold text-lg sm:text-xl">Laporan Transaksi Penghasilan</h4>
            <p class="text-sm mt-3 leading-relaxed sm:w-3/4">
                Hanya catatan transaksi dalam 12 bulan terakhir akan dipertahankan. Silahkan download
                salinan PDF anda
            </p>
        </div>

        {{-- Filter Periode --}}
        <div class="p-2 sm:p-4">
            <div class="flex justify-end mb-3 pr-2 sm:pr-0">
                <div x-data="{ open: false }" class="relative inline-block text-left">
                    <button @click="open = !open"
                        class="bg-[#00509d] text-white font-semibold px-3 py-1.5 rounded-t-md w-36 sm:w-40 flex items-center justify-between text-sm sm:text-base">
                        <span>{{ $bulanList[$bulan] ?? 'Periode' }}</span>
                        <span>
                            <template x-if="!open">â–¼</template>
                            <template x-if="open">â–²</template>
                        </span>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-cloak @click.outside="open = false"
                        class="absolute left-0 mt-1 w-32 sm:w-36 bg-white shadow-lg rounded-b-md overflow-hidden z-10 text-xs sm:text-sm">

                        @foreach ($bulanList as $key => $nama)
                            <a href="{{ route('finance.laporan', ['bulan' => $key]) }}"
                                class="block px-3 py-1.5 sm:px-4 sm:py-2 text-gray-900 hover:bg-[#00509d] hover:text-white
            {{ $bulan == $key ? 'bg-[#00509d] text-white' : '' }}">
                                {{ $nama }}
                            </a>
                        @endforeach

                    </div>

                </div>
            </div>

            {{-- Tabel --}}
            <div class="rounded-2xl overflow-hidden border bg-white">

                <!-- Scroll pada mobile -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm min-w-[600px]">
                        <thead>
                            <tr class="bg-[#00509d] text-white">
                                <th class="px-4 py-2 text-center font-semibold">Tanggal</th>
                                <th class="px-4 py-2 text-center font-semibold">Penghasilan</th>
                                <th class="px-4 py-2 text-center font-semibold">Koin</th>
                                <th class="px-4 py-2 text-center font-semibold">Total Transaksi</th>
                                <th class="px-4 py-2 text-center font-semibold">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporan as $l)
                                <tr class="text-center border-b font-medium">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($l->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        Rp{{ number_format($l->total_penghasilan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $l->total_koin }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $l->total_transaksi }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('finance.laporan.detail', ['tanggal' => $l->tanggal]) }}"
                                            class="text-[#00509d] hover:underline">
                                            <i class="ph ph-file text-2xl sm:text-3xl"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">
                                        Tidak ada transaksi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

