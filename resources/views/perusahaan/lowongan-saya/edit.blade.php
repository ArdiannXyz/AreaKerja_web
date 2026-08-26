@extends('layouts.index-perusahaan')
@section('content')
    <div class="max-w-4xl mx-auto px-6 py-10 mt-28 bg-white shadow-md border rounded-lg mb-8">

        <div class="flex items-center justify-between p-4 mb-6">
            <div class="flex items-center space-x-4">
                @if (Auth::user()->role == 'perusahaan')
                    @if (Auth::user()->perusahaan->img_profile)
                        <img id="pu" class="w-24 h-24 object-cover rounded-full profile-img"
                            src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                    @else
                        <img id="pu" class="w-24 h-24 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                            alt="">
                    @endif
                @else
                    <img class="w-10 h-10 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                        alt="">
                @endif
                <div>
                    <h2 class="font-bold text-xl">{{ Auth::user()->perusahaan->nama_perusahaan }}</h2>
                    <p class="text-gray-600 text-sm">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->kecamatan->nama ?? '-' }}</p>
                </div>
            </div>

            <!-- <button class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 flex items-center shadow">
                    <span class="text-lg font-bold mr-2">+</span> Tambah
                 </button> -->
        </div>

        <h2 class="text-2xl font-semibold mb-6 border-b-2 border-orange-400 pb-2">Edit Lowongan</h2>

        <form action="{{ route('lowongan.update', $data->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium mb-1">Judul</label>
                    <input type="text" name="nama" value="{{ $data->nama }}"
                        class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                </div>
                <div>
                    <label class="block font-medium mb-1">Alamat</label>
                    <input name="alamat" value="{{ $data->alamat }}"
                        class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium mb-1">Jenis Lowongan</label>
                    <select name="jenis"
                        class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                        <option value="" disabled {{ old('jenis', $data->jenis) == '' ? 'selected' : '' }}>
                            Pilih Jenis Lowongan
                        </option>
                        <option value="fulltime" {{ old('jenis', $data->jenis) == 'fulltime' ? 'selected' : '' }}>
                            Full Time
                        </option>
                        <option value="middle" {{ old('jenis', $data->jenis) == 'middle' ? 'selected' : '' }}>
                            Middle
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="kategori"
                        class="form-select border rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 w-50">
                        <option value=""> Pilih Kategori </option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->nama }}"
                                {{ old('kategori', $data->kategori ?? '') == $cat->nama ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-span-2">
                    <label class="block font-medium mb-1">Gaji</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" name="gaji_awal" value="{{ $data->gaji_awal }}"
                            class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                        <span class="text-gray-500">–</span>
                        <input type="text" name="gaji_akhir" value="{{ $data->gaji_akhir }}"
                            class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                    </div>
                </div>
                {{-- <div>
                    <label class="block font-medium mb-1">Periode</label>
                    <select name="batas_lamaran" class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                        <option>Bulan</option>
                    </select>
                </div> --}}
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Benefit -->
                <div>
                    <label class="block font-medium mb-1">Benefit</label>
                    <input type="text" name="benefit" value="{{ $data->benefit }}"
                        class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                </div>

                <!-- Label Gaji -->
                <div>
                    <label class="block font-medium mb-1">Label Gaji</label>
                    <input type="text" name="label_gaji" value="{{ $data->label_gaji }}"
                        class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                </div>

            </div>

            <label class="block font-medium">Deskripsi Gaji</label>
            <textarea class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400" name="deskripsi"
                cols="30" rows="10">{!! old('deskripsi', $data->deskripsi ?? '') !!}</textarea>

            <label class="block font-medium">Tanggung Jawab</label>
            <textarea class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400"
                name="tanggung_jawab" cols="30" rows="10">{!! old('tanggung_jawab', $data->tanggung_jawab ?? '') !!}</textarea>


            <div class="space-y-6 border-t-2 pt-6">
                <div>
                    <label class="block font-medium mb-1">Syarat Pekerjaan <span class="text-red-500">*</span></label>
                    <textarea class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400"
                        name="syarat_pekerjaan" rows="4" required
                        placeholder="Contoh: Minimal D3/S1, menguasai Vue.js atau React, pengalaman minimal 1 tahun...">{{ old('syarat_pekerjaan', $data->syarat_pekerjaan) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium mb-1">Batas Waktu Lamaran (Deadline)</label>
                        <input type="date" name="batas_lamaran" value="{{ old('batas_lamaran', $data->batas_lamaran ? \Carbon\Carbon::parse($data->batas_lamaran)->format('Y-m-d') : '') }}"
                            class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Status Pendaftaran</label>
                        <select name="status" class="w-full border-2 rounded-md px-3 py-2 focus:ring-orange-400 focus:border-orange-400">
                            <option value="buka" {{ old('status', $data->status ?? 'buka') == 'buka' ? 'selected' : '' }}>
                                🟢 Buka Pendaftaran (Aktif)
                            </option>
                            <option value="tutup" {{ old('status', $data->status ?? 'buka') == 'tutup' ? 'selected' : '' }}>
                                🔒 Tutup Pendaftaran (Kuota Full)
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-center space-x-4 pt-6">
                <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">
                    Simpan
                </button>
                <a href="{{ route('lowongan.saya.perusahaan') }}"
                    class="px-6 py-2 border-2 border-orange-500 rounded-md text-orange-500 hover:bg-orange-50">
                    Batal
                </a>

            </div>`
        </form>
    </div>
    @include('layouts.footer')
@endsection
