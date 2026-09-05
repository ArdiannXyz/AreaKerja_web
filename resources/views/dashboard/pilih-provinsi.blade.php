@extends('admin.sidebar.index')

@section('sidebaradmin')
<div class="p-4 sm:p-6 sm:ml-64 bg-slate-50 min-h-screen">

    {{-- HEADER --}}
    <header class="w-full flex items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="ph ph-map-pin text-[#00509d] text-2xl"></i> Filter Provinsi
            </h1>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                Pilih provinsi untuk memfilter data yang ditampilkan di dashboard.
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </header>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium px-4 py-3 rounded-xl">
            <i class="ph ph-check-circle text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM PILIH PROVINSI --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 max-w-xl">

        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="ph ph-funnel text-[#00509d]"></i> Pilih Provinsi
        </h2>

        {{-- Provinsi aktif saat ini --}}
        @if ($selected)
            <div class="mb-4 flex items-center gap-2 text-sm text-blue-700 bg-blue-50 border border-blue-200 px-4 py-2.5 rounded-xl">
                <i class="ph ph-info text-base"></i>
                Provinsi aktif sekarang:
                <span class="font-bold">
                    {{ $provinsis->firstWhere('id', $selected)?->nama ?? 'Tidak Diketahui' }}
                </span>
            </div>
        @endif

        <form action="{{ route('dashboard.set-provinsi') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="provinsi_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Provinsi <span class="text-rose-500">*</span>
                </label>
                <select id="provinsi_id" name="provinsi_id"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/30 focus:border-[#00509d] transition @error('provinsi_id') border-rose-400 @enderror">
                    <option value="">-- Semua Provinsi --</option>
                    @forelse ($provinsis as $provinsi)
                        <option value="{{ $provinsi->id }}"
                            {{ $selected == $provinsi->id ? 'selected' : '' }}>
                            {{ $provinsi->nama }}
                        </option>
                    @empty
                        <option value="" disabled>Data provinsi belum tersedia</option>
                    @endforelse
                </select>
                @error('provinsi_id')
                    <p class="mt-1 text-xs text-rose-600 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold rounded-xl transition">
                    <i class="ph ph-floppy-disk mr-1"></i> Simpan Pilihan
                </button>
                <a href="{{ route('admin.dashboard') }}"
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
