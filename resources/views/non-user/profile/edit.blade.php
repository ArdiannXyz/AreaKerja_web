@extends('layouts.index')
@section('content')

    <form action="{{ route('profile.update', Auth::user()->pelamar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h2 class="text-xl font-semibold mb-6 mt-28 ml-12">Edit Profil Akun</h2>
        <div class="bg-white mx-12">
            <!-- Header: Avatar + Tombol Upload/Remove & Simpan -->
            <div class="border-2 border-orange-500 rounded-lg p-4 md:p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center md:justify-between gap-6">

                    <!-- Kiri: Foto + Upload/Remove -->
                    <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8 w-full md:w-auto">
                        <div class="flex flex-col items-center w-full md:w-auto">
                            <div class="relative inline-flex items-center gap-3">
                                <div x-data="{ zoom: false }" class="cursor-pointer inline-block" @click="zoom = !zoom">
                                    <img id="pp"
                                        class="w-40 h-40 object-cover rounded-full border-2 border-orange-500 transition-transform duration-300"
                                        :class="zoom ? 'scale-[2] z-50 relative' : 'scale-100'"
                                        src="{{ Auth::user()->pelamar->img_profile
                                            ? asset('storage/' . Auth::user()->pelamar->img_profile)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->username) . '&background=00509d&color=fff&size=128' }}"
                                        alt="Profile">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Upload & Remove -->
                        <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                            <label
                                class="flex items-center gap-1 border border-orange-500 text-orange-500 px-3 py-2 rounded-md text-sm font-medium hover:bg-orange-50 w-full md:w-auto justify-center cursor-pointer">
                                <input type="file" name="img_profile" id="fileinput" accept="image/*" class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                Upload
                            </label>

                            <button type="button"
                                onclick="event.preventDefault(); document.getElementById('removeForm').submit();"
                                class="px-3 py-2 flex items-center gap-1 border border-gray-400 rounded text-sm text-gray-600 hover:bg-gray-100 w-full md:w-auto justify-center">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.7946 2.44649H9.4233V1.97744C9.4233 1.60425 9.27341 1.24634 9.00659 0.982451C8.73977 0.718563 8.37788 0.570313 8.00054 0.570312H5.15501C4.77767 0.570313 4.41579 0.718563 4.14896 0.982451C3.88214 1.24634 3.73225 1.60425 3.73225 1.97744V2.44649H1.36097C1.23519 2.44649 1.11456 2.4959 1.02562 2.58386C0.936685 2.67183 0.886719 2.79113 0.886719 2.91553C0.886719 3.03993 0.936685 3.15923 1.02562 3.24719C1.11456 3.33515 1.23519 3.38457 1.36097 3.38457H1.83523V11.8273C1.83523 12.0761 1.93516 12.3147 2.11304 12.4907C2.29092 12.6666 2.53218 12.7654 2.78374 12.7654H10.3718C10.6234 12.7654 10.8646 12.6666 11.0425 12.4907C11.2204 12.3147 11.3203 12.0761 11.3203 11.8273V3.38457H11.7946C11.9204 3.38457 12.041 3.33515 12.1299 3.24719C12.2189 3.15923 12.2688 3.03993 12.2688 2.91553C12.2688 2.79113 12.2189 2.67183 12.1299 2.58386C12.041 2.4959 11.9204 2.44649 11.7946 2.44649Z" fill="#606060" />
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>

                    <!-- Bagian Kanan (Tombol Batal & Simpan) -->
                    <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('profile.index') }}"
                            class="bg-gray-500 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-gray-600 w-full md:w-auto text-center">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-green-700 w-full md:w-auto text-center">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid: Dua Kolom Form Edit -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kolom Kiri -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-lg font-bold text-gray-800 border-b-2 border-orange-500 pb-1 mb-2">Data Diri</h3>

                    <div>
                        <label class="text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" placeholder="Nama Lengkap" name="nama_pelamar"
                            value="{{ old('nama_pelamar', Auth::user()->pelamar->nama_pelamar ?? '') }}"
                            class="w-full mt-1 border rounded-md px-3 py-2 text-sm focus:border-orange-500 outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Gender <span class="text-red-500">*</span></label>
                        <div class="flex gap-6 mt-2 text-sm">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="laki-laki"
                                    class="w-4 h-4 text-orange-500 border-2 border-orange-500"
                                    {{ (Auth::user()->pelamar->gender ?? '') === 'laki-laki' ? 'checked' : '' }}>
                                Laki - Laki
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="perempuan"
                                    class="w-4 h-4 text-orange-500 border-2 border-orange-500"
                                    {{ (Auth::user()->pelamar->gender ?? '') === 'perempuan' ? 'checked' : '' }}>
                                Perempuan
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir"
                            class="w-full mt-1 border rounded-md px-3 py-2 text-sm text-gray-700 outline-none"
                            value="{{ old('tanggal_lahir', optional(Auth::user()->pelamar->tanggal_lahir)->format('Y-m-d') ?? '') }}">
                    </div>

                    <div>
                        <label class="text-sm font-medium">No. Tlp <span class="text-red-500">*</span></label>
                        <input type="text" placeholder="08xxxxxxxx" name="telepon_pelamar"
                            class="w-full mt-1 border rounded-md px-3 py-2 text-sm outline-none"
                            value="{{ old('telepon_pelamar', Auth::user()->pelamar->telepon_pelamar ?? '') }}">
                        @error('telepon_pelamar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Deskripsi Diri <span class="text-red-500">*</span></label>
                        <textarea placeholder="Deskripsikan diri anda secara singkat" name="deskripsi_diri" rows="4"
                            class="w-full mt-1 border rounded-md px-3 py-2 text-sm outline-none">{{ old('deskripsi_diri', Auth::user()->pelamar->deskripsi_diri ?? '') }}</textarea>
                    </div>

                    <!-- Sosial Media -->
                    <div class="flex flex-col gap-2 mt-4">
                        <h3 class="text-lg font-bold text-gray-800 border-b-2 border-orange-500 pb-1 mb-2">Sosial Media</h3>
                        
                        <label class="text-sm font-medium">Instagram</label>
                        <input type="text" name="instagram" placeholder="Instagram Username / Link"
                            class="w-full border rounded-md px-3 py-2 text-sm outline-none"
                            value="{{ $pelamar->social_links['instagram'] ?? '' }}">
                        
                        <label class="text-sm font-medium">LinkedIn</label>
                        <input type="text" name="linkedin" placeholder="LinkedIn Profile Link"
                            class="w-full border rounded-md px-3 py-2 text-sm outline-none"
                            value="{{ $pelamar->social_links['linkedin'] ?? '' }}">
                        
                        <label class="text-sm font-medium">Website</label>
                        <input type="text" name="website" placeholder="Website URL"
                            class="w-full border rounded-md px-3 py-2 text-sm outline-none"
                            value="{{ $pelamar->social_links['website'] ?? '' }}">
                        
                        <label class="text-sm font-medium">Twitter</label>
                        <input type="text" name="twitter" placeholder="Twitter Username"
                            class="w-full border rounded-md px-3 py-2 text-sm outline-none"
                            value="{{ $pelamar->social_links['twitter'] ?? '' }}">
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-lg font-bold text-gray-800 border-b-2 border-orange-500 pb-1 mb-2">Informasi Akun</h3>

                    <div>
                        <label class="text-sm font-medium">Nama Pengguna / Username <span class="text-red-500">*</span></label>    
                        <input type="text" placeholder="Nama Pengguna" value="{{ old('username', Auth::user()->username) }}" name="username"
                            class="w-full mt-1 border rounded-md px-3 py-2 text-sm outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email <span class="text-red-500">*</span></label>
                        <div class="relative mt-1">
                            <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <span class="absolute right-3 top-2.5 text-[11px] bg-emerald-100 text-emerald-700 font-semibold px-2 py-0.5 rounded border border-emerald-300">
                                Terverifikasi
                            </span>
                        </div>
                    </div>

                    <!-- Ekspektasi Gaji -->
                    <div class="mt-4">
                        <h3 class="text-lg font-bold text-gray-800 border-b-2 border-orange-500 pb-1 mb-4">Ekspektasi Gaji</h3>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <!-- Minimal -->
                            <div class="flex items-center border border-gray-300 rounded-md px-3 py-2 text-gray-700 w-full sm:w-1/2 gap-2 focus-within:border-orange-500 focus-within:ring-1 focus-within:ring-orange-500">
                                <span class="text-orange-500 font-semibold text-sm">Rp.</span>
                                <input type="number" min="0" step="100000" placeholder="5000000" name="gaji_minimal"
                                    class="border-none w-full outline-none text-sm font-medium text-gray-800"
                                    value="{{ old('gaji_minimal', Auth::user()->pelamar->gaji_minimal ?? '') }}">
                            </div>

                            <span class="text-center hidden sm:block text-gray-400 font-bold">-</span>
                            <span class="text-center sm:hidden text-gray-500 text-xs">sampai</span>

                            <!-- Maksimal -->
                            <div class="flex items-center border border-gray-300 rounded-md px-3 py-2 text-gray-700 w-full sm:w-1/2 gap-2 focus-within:border-orange-500 focus-within:ring-1 focus-within:ring-orange-500">
                                <span class="text-orange-500 font-semibold text-sm">Rp.</span>
                                <input type="number" min="0" step="100000" placeholder="10000000" name="gaji_maksimal"
                                    class="border-none w-full outline-none text-sm font-medium text-gray-800"
                                    value="{{ old('gaji_maksimal', Auth::user()->pelamar->gaji_maksimal ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="removeForm" action="{{ route('profile.destroy', Auth::user()->pelamar->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

@endsection
