@extends('layouts.index')
@section('content')
    <div class="min-h-screen bg-gray-50 py-6 md:py-10 mt-10">
        <div class="max-w-full md:max-w-2xl mx-auto bg-white rounded-xl shadow-md p-4 md:p-8">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-3 md:pb-4 mb-5 md:mb-6">
                <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Edit Pengalaman Kerja</h1>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('kerja.update', $DK->id) }}" method="POST" class="space-y-4 md:space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama Organisasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $DK->nama_perusahaan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                </div>

                <!-- Posisi_pekerjaan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1">Posisi Pekerjaan</label>
                    <input type="text" name="posisi_pekerjaan"
                        value="{{ old('posisi_pekerjaan', $DK->posisi_pekerjaan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                </div>

                <!-- Tahun -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1">Tahun Awal</label>
                        <input type="text" name="tahun_awal" value="{{ old('tahun_awal', $DK->tahun_awal) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1">Tahun Akhir</label>
                        <input type="text" name="tahun_akhir" value="{{ old('tahun_akhir', $DK->tahun_akhir) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">{{ $DK->deskripsi }}</textarea>
                </div>

                <!-- Action -->
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-2.5 bg-[#00509d] text-white font-bold rounded-xl hover:bg-[#003d7a] shadow-sm text-sm transition">
                        Simpan
                    </button>
                    <a href="{{ route('profile.index') }}"
                        class="w-full md:w-auto px-6 py-2.5 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 shadow-sm text-sm text-center transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
@endsection
