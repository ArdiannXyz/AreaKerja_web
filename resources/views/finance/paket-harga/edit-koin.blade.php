@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Top Header & Breadcrumb -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-200/80">
            <div class="flex items-center gap-3 flex-1">
                <a href="{{ route('finance.paket-harga') }}"
                   class="w-10 h-10 rounded-2xl bg-white border border-slate-200/80 hover:bg-blue-50/50 hover:border-[#00509d]/40 flex items-center justify-center text-slate-600 transition shadow-2xs">
                    <i class="ph ph-arrow-left text-lg font-bold"></i>
                </a>
                <div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                        <a href="{{ route('finance.dashboard') }}" class="hover:text-[#00509d]">Finance</a>
                        <span>/</span>
                        <a href="{{ route('finance.paket-harga') }}" class="hover:text-[#00509d]">Paket Harga</a>
                        <span>/</span>
                        <span class="text-slate-600 font-semibold">Edit Tarif Koin</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">Edit Tarif Pasang Lowongan</h1>
                </div>
            </div>
        </header>

        <!-- Form Card -->
        <div class="max-w-3xl bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-lg shrink-0 font-bold">
                        <i class="ph-fill ph-coins"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Tarif Koin Lowongan</h2>
                        <p class="text-xs text-slate-500">Sesuaikan jumlah koin yang dibutuhkan untuk masing-masing tier</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('finance.paket-harga.update-koin') }}" method="post">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Nama Paket Lowongan</th>
                                <th class="px-6 py-3.5 text-right">Tarif Koin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($koin as $k)
                                <tr class="hover:bg-blue-50/20 transition">
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        <div class="flex items-center gap-2.5">
                                            @if (str_contains(strtolower($k->nama), 'gold') || str_contains(strtolower($k->nama), 'vip'))
                                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                            @elseif (str_contains(strtolower($k->nama), 'silver'))
                                                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                                            @else
                                                <span class="w-2.5 h-2.5 rounded-full bg-amber-700"></span>
                                            @endif
                                            <span>{{ $k->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200 gap-2 focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-blue-100 transition">
                                            <input type="hidden" name="id[]" value="{{ $k->id }}">
                                            <input type="number" name="harga[]" min="0"
                                                class="bg-transparent w-24 text-right outline-none text-slate-900 font-extrabold text-sm sm:text-base"
                                                value="{{ $k->harga }}">
                                            <span class="text-xs text-slate-500 font-bold">Koin</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50/30 flex items-center justify-end gap-3">
                    <a href="{{ route('finance.paket-harga') }}"
                        class="px-5 py-2.5 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs sm:text-sm font-bold rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-xs">
                        <i class="ph ph-floppy-disk text-base"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
