@extends('layouts.index')
@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-28 mb-20 min-h-[calc(100vh-280px)]">

        {{-- Card Container --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] border-collapse">
                    <thead>
                        <tr class="bg-[#00509d] text-white text-xs sm:text-sm font-semibold tracking-wide">
                            <th class="py-4 px-4 text-center">No Referensi</th>
                            <th class="py-4 px-4 text-center">Bank / Metode</th>
                            <th class="py-4 px-4 text-center">Pesanan</th>
                            <th class="py-4 px-4 text-center">Total</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-4 text-center">Tanggal</th>
                            <th class="py-4 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse ($transaksi as $index => $t)
                            <tr class="hover:bg-blue-50/40 transition-colors duration-150 cursor-pointer {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/40' }}"
                                onclick="window.location='{{ route('kandidat.transaksi', $t->id) }}'">

                                {{-- No Referensi --}}
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('kandidat.transaksi', $t->id) }}"
                                        class="font-semibold text-[#00509d] hover:text-[#003870] hover:underline transition-colors">
                                        {{ $t->no_referensi }}
                                    </a>
                                </td>

                                {{-- Bank --}}
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium">
                                        <i class="ph ph-bank text-sm text-[#00509d]"></i>
                                        {{ $t->bank->nama_bank ?? '-' }}
                                    </span>
                                </td>

                                {{-- Pesanan --}}
                                <td class="py-4 px-4 text-center font-medium text-slate-800">
                                    {{ $t->pesanan ?? '-' }}
                                </td>

                                {{-- Total --}}
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold text-slate-900">
                                        Rp {{ number_format($t->total, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Status Badge --}}
                                <td class="py-4 px-4 text-center" onclick="event.stopPropagation()">
                                    @php
                                        $status = strtolower($t->status ?? '');
                                    @endphp
                                    @if (in_array($status, ['diterima', 'sukses', 'success']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Diterima
                                        </span>
                                    @elseif (in_array($status, ['ditolak', 'gagal', 'failed', 'expired']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            {{ ucfirst($t->status) }}
                                        </span>
                                    @elseif (in_array($status, ['menunggu_verifikasi', 'menunggu verifikasi']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00509d]"></span>
                                            {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Tanggal --}}
                                <td class="py-4 px-4 text-center">
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="font-medium text-slate-700">{{ $t->created_at->format('d M Y') }}</span>
                                        <span class="text-slate-400 text-[11px]">{{ $t->created_at->format('H:i') }} WIB</span>
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="py-4 px-4 text-center" onclick="event.stopPropagation()">
                                    <a href="{{ route('kandidat.transaksi', $t->id) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#00509d] hover:bg-[#003870] text-white text-xs font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-150 active:scale-95">
                                        <i class="ph ph-eye text-sm"></i> Detail
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="py-20 md:py-24 text-center px-4">
                                        <div class="flex justify-center mb-4">
                                            <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                                                <i class="ph ph-receipt text-3xl"></i>
                                            </div>
                                        </div>
                                        <h3 class="text-base sm:text-lg font-semibold text-slate-700 mb-1">Belum Ada Transaksi</h3>
                                        <p class="text-xs sm:text-sm text-slate-400 max-w-sm mx-auto">Anda belum memiliki riwayat transaksi pendaftaran kandidat saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection
