@extends('layouts.index')
@section('content')
    <div class="mt-28 min-h-[calc(100vh-200px)]">

        <!-- Wrapper scroll untuk mobile -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white border-collapse mb-16 min-w-[700px] md:min-w-0">
                <thead>
                    <tr class="bg-gray-100 text-xs md:text-sm">
                        <th class="p-2 md:p-3 text-center">No Referensi</th>
                        <th class="p-2 md:p-3 text-center">Bank</th>
                        <th class="p-2 md:p-3 text-center">Pesanan</th>
                        <th class="p-2 md:p-3 text-center">Total</th>
                        <th class="p-2 md:p-3 text-center">Status</th>
                        <th class="p-2 md:p-3 text-center">Tanggal</th>
                        <th class="p-2 md:p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksi as $t)
                        <tr class="border-b text-xs md:text-sm hover:bg-orange-50/50 transition cursor-pointer" onclick="window.location='{{ route('kandidat.transaksi', $t->id) }}'">
                            <td class="p-2 md:p-3 text-center font-bold text-orange-600 hover:underline">
                                <a href="{{ route('kandidat.transaksi', $t->id) }}">{{ $t->no_referensi }}</a>
                            </td>
                            <td class="p-2 md:p-3 text-center">{{ $t->bank->nama_bank ?? '-' }}</td>
                            <td class="p-2 md:p-3 text-center font-medium">{{ $t->pesanan ?? '-' }}</td>
                            <td class="p-2 md:p-3 text-center font-semibold">
                                Rp {{ number_format($t->total, 0, ',', '.') }}
                            </td>

                            <td
                                class="p-2 md:p-3 text-center font-semibold 
                            @if ($t->status == 'diterima') text-green-600
                            @elseif($t->status == 'ditolak') text-red-600
                            @elseif($t->status == 'menunggu_verifikasi') text-yellow-600
                            @else text-orange-600 @endif">
                                {{ ucfirst($t->status) }}
                            </td>

                            <td class="p-2 md:p-3 text-center text-gray-500">
                                {{ $t->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="p-2 md:p-3 text-center" onclick="event.stopPropagation()">
                                <a href="{{ route('kandidat.transaksi', $t->id) }}"
                                    class="px-4 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                                    <i class="ph ph-eye text-sm"></i> Detail
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7">

                                <div
                                    class="border-gray-200 py-20 md:py-32 max-w-md md:max-w-2xl mx-auto border rounded-3xl mt-6 mb-8 shadow text-center px-4">

                                    <p class="text-lg md:text-xl text-gray-400 font-medium">
                                        Anda belum memiliki transaksi apapun
                                    </p>

                                    <div class="flex items-center justify-center mt-4">
                                        <svg width="70" height="60" class="md:w-20 md:h-20" viewBox="0 0 100 98"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M99.4339 26.316C99.1459 25.9068 98.7638 25.5729 98.3197 25.3423C97.8756 25.1117 97.3827 24.9911 96.8823 24.9908H30.2266L25.181 7.59115C23.2023 0.739642 18.5006 0 16.5722 0H3.11884C1.39448 0 0 1.39605 0 3.11875C0 4.84144 1.39605 6.23739 3.11875 6.23739H16.5705C16.9961 6.23739 18.2948 6.23739 19.1805 9.29803L36.5378 73.0878C36.9147 74.4336 38.1411 75.3632 39.5402 75.3632H81.9369C83.253 75.3632 84.4275 74.5387 84.8721 73.2997L99.8157 29.1646C100.16 28.2083 100.017 27.1436 99.4341 26.316H99.4339ZM79.7422 69.1275H41.9075L31.9796 31.2299H92.4481L79.7422 69.1275ZM73.5566 81.6841C69.2192 81.6841 65.7048 85.1986 65.7048 89.5359C65.7048 93.8733 69.2192 97.3878 73.5566 97.3878C77.8939 97.3878 81.4084 93.8733 81.4084 89.5359C81.4084 85.1986 77.8939 81.6841 73.5566 81.6841ZM45.29 81.6841C40.9527 81.6841 37.4382 85.1986 37.4382 89.5359C37.4382 93.8733 40.9527 97.3878 45.29 97.3878C49.6274 97.3878 53.1418 93.8733 53.1418 89.5359C53.1418 85.1986 49.6274 81.6841 45.29 81.6841Z"
                                                fill="#606060" fill-opacity="0.8" />
                                        </svg>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    @include('layouts.footer')
@endsection
