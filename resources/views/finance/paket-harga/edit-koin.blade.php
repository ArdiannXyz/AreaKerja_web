@extends('finance.sidebar.index')
@section('sidebar')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen">
        <!-- Top Header & Breadcrumb -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-[#00509d] text-white p-3.5 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('finance.paket-harga') }}"
                       class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition flex-shrink-0"
                       title="Kembali">
                        <i class="ph ph-arrow-left text-base"></i>
                    </a>
                    <div>
                        <h1 class="text-base sm:text-xl font-semibold text-white tracking-tight leading-tight">Edit Tarif Koin Layanan</h1>
                        <p class="text-[11px] sm:text-xs text-blue-100 mt-0.5">Finance / Paket Harga / Edit Tarif Koin</p>
                    </div>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                    @include('finance.components.notif_button')
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('finance.components.notif_button')
                @include('finance.components.user_badge_dropdown')
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
                        <h2 class="text-base font-extrabold text-slate-900">Tarif Koin Layanan Perusahaan</h2>
                        <p class="text-xs text-slate-500">Sesuaikan jumlah koin yang dibutuhkan untuk membuka CV, moderasi iklan, booster, dan VIP</p>
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
                                <th class="px-6 py-3.5">Nama Layanan</th>
                                <th class="px-6 py-3.5 text-right">Tarif Koin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($koin as $k)
                                <tr class="hover:bg-blue-50/20 transition">
                                    <td class="px-6 py-4 text-slate-800">
                                        <div class="flex items-center gap-2.5">
                                            @if (str_contains(strtolower($k->nama), 'cv'))
                                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                            @elseif (str_contains(strtolower($k->nama), 'iklan'))
                                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                            @elseif (str_contains(strtolower($k->nama), 'vip') || str_contains(strtolower($k->nama), 'berlangganan'))
                                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                            @else
                                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                            @endif
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $k->nama }}</p>
                                                @if(!empty($k->deskripsi))
                                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $k->deskripsi }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200 gap-2 focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-blue-100 focus-within:bg-white transition">
                                            <input type="hidden" name="keys[]" value="{{ $k->key ?? '' }}">
                                            <input type="hidden" name="id[]" value="{{ $k->id }}">
                                            <input type="number" name="hargas[]" min="0" required
                                                class="bg-transparent w-24 text-right text-slate-900 font-extrabold text-sm sm:text-base border-0 border-none ring-0 focus:ring-0 focus:outline-none p-0 shadow-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                style="border: none !important; box-shadow: none !important; outline: none !important; padding: 0 !important; background: transparent !important;"
                                                value="{{ $k->harga }}">
                                            <span class="text-xs text-slate-400 font-bold select-none">Koin</span>
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

    </main>
@endsection
