@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 flex-1">
                <a href="{{ route('superadmin.paket-harga') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Finance / <span class="text-slate-500 font-medium">Edit Harga Koin</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Paket Harga Koin</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <form action="{{ route('superadmin.paket-harga.update-koin') }}" method="post">
                @csrf @method('PUT')

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-5 py-3.5">Nama Paket</th>
                                <th class="px-5 py-3.5 text-right">Jumlah Koin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($koin as $k)
                                <tr class="hover:bg-slate-50/60 transition duration-150">
                                    <td class="px-5 py-4 font-medium text-slate-800">{{ $k->nama }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="inline-flex items-center bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 gap-2">
                                            <input type="hidden" name="id[]" value="{{ $k->id }}">
                                            <input type="number" name="harga[]"
                                                   class="bg-transparent w-20 text-center outline-none text-slate-800 font-semibold text-sm"
                                                   value="{{ $k->harga }}">
                                            <span class="text-xs text-slate-500 font-medium">Koin</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('superadmin.paket-harga') }}"
                       class="inline-flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition shadow-sm">
                        <i class="ph ph-floppy-disk text-base"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
