@extends('layouts.index')
@section('content')
    <div class="min-h-screen bg-gray-50 py-6 md:py-10 mt-10">
        <div class="w-full max-w-xl md:max-w-2xl mx-auto bg-white rounded-xl shadow-md p-4 md:p-8">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-5">
                <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Edit Riwayat Pendidikan</h1>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-sm md:text-base">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('pendidikan.update', $DT->id) }}" method="POST" class="space-y-4 md:space-y-5">
                @csrf
                @method('PUT')

                <!-- Pendidikan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pendidikan</label>
                    <input type="text" name="pendidikan" value="{{ old('pendidikan', $DT->pendidikan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                </div>

                <!-- Jurusan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jurusan</label>
                    <input type="text" name="jurusan" value="{{ old('jurusan', $DT->jurusan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                </div>

                <!-- Asal Pendidikan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Asal Pendidikan</label>
                    <input type="text" name="asal_pendidikan" value="{{ old('asal_pendidikan', $DT->asal_pendidikan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                </div>

                <!-- Tahun Awal & Akhir -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Awal</label>
                        <input type="text" name="tahun_awal" value="{{ old('tahun_awal', $DT->tahun_awal) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Akhir</label>
                        <input type="text" name="tahun_akhir" value="{{ old('tahun_akhir', $DT->tahun_akhir) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>
                </div>

                <!-- Action -->
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-2.5 bg-[#00509d] text-white font-bold rounded-xl hover:bg-[#003d7a] shadow-sm text-sm md:text-base transition">
                        Simpan
                    </button>

                    <a href="{{ route('profile.index') }}"
                        class="w-full md:w-auto px-5 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 shadow text-sm md:text-base text-center">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>
@endsection
