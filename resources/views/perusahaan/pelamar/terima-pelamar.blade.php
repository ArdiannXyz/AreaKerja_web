@extends('layouts.index-perusahaan')
@section('content')
    <div class="flex justify-center items-center mt-24 mb-10">
        <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-md border mt-6">
            <!-- Judul -->
            <h2 class="text-xl font-semibold mb-6 text-center">Konfirmasi Terima Lamaran</h2>
            <p class="mb-6 text-center text-gray-600">Silahkan input jadwal wawancara untuk calon kandidat</p>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>â€¢ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('pelamar.konfirmasi.simpan', $data->id) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Tanggal -->
                <div>
                    <label class="block mb-1 font-medium">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal ?? '') }}"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00509d]">
                    @error('tanggal')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu -->
                <div>
                    <label class="block mb-1 font-medium">
                        Waktu <span class="text-red-500">*</span>
                    </label>

                    <div class="flex gap-2">
                        <select name="jam"
                            class="w-20 border rounded-md px-2 py-2 focus:outline-none focus:ring-2 focus:ring-[#00509d]">
                            @for ($i = 0; $i < 24; $i++)
                                <option value="{{ $i }}"
                                    {{ (int) old('jam', isset($jadwal) ? substr($jadwal->waktu, 0, 2) : -1) === $i ? 'selected' : '' }}>
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>

                        <span class="flex items-center">:</span>

                        <select name="menit"
                            class="w-20 border rounded-md px-2 py-2 focus:outline-none focus:ring-2 focus:ring-[#00509d]">
                            @for ($i = 0; $i < 60; $i++)
                                <option value="{{ $i }}"
                                    {{ (int) old('menit', isset($jadwal) ? substr($jadwal->waktu, 3, 2) : -1) === $i ? 'selected' : '' }}>
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>


                <!-- Tempat -->
                <div>
                    <label class="block mb-1 font-medium">Tempat <span class="text-red-500">*</span></label>
                    <textarea rows="2" name="tempat"
                        class="w-full flex items-start h-24 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00509d]">{{ old('tempat', $jadwal->tempat ?? '') }}</textarea>
                    @error('tempat')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <label class="block font-semibold mb-1">
                        Lokasi (Google Maps)
                    </label>

                    <input type="url" name="gmaps_url" class="w-full border rounded-lg p-2"
                        placeholder="https://maps.google.com/?q=-6.200000,106.816666">

                    <p class="text-sm text-gray-500 mt-1">
                        Buka Google Maps â†’ Pilih lokasi â†’ Share â†’ Salin link
                    </p>

                    @error('gmaps_url')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Catatan -->
                <div>
                    <label class="block mb-1 font-medium">Catatan</label>
                    <textarea rows="2" name="catatan"
                        class="w-full flex items-start h-24 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00509d]">{{ old('catatan', $jadwal->catatan ?? '') }}</textarea>
                </div>


                <!-- Tombol -->
                <div class="flex justify-center pt-3 gap-4">
                    <button type="submit"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-md font-medium transition duration-300">
                        Selanjutnya
                    </button>
                </div>
            </form>
        </div>
    </div>

    


    @include('layouts.footer')
@endsection

