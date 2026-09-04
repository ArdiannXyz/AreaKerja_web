@extends('layouts.index')
@section('content')

    <form action="{{ route('profile.update', Auth::user()->pelamar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-28 pb-16">
            <h2 class="text-2xl font-bold mb-6 text-slate-800 tracking-tight">Edit Profil Akun</h2>
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-sm">
                <!-- Header: Avatar + Tombol Upload/Remove & Simpan -->
                <div class="border-2 border-[#00509d] rounded-2xl p-6 md:p-8 mb-8 bg-blue-50/20">
                    <div class="flex flex-col md:flex-row items-center md:justify-between gap-6">

                        <!-- Kiri: Foto + Upload/Remove -->
                        <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8 w-full md:w-auto">
                            <div class="flex flex-col items-center w-full md:w-auto">
                                <div class="relative inline-flex items-center gap-3">
                                    <div x-data="{ zoom: false }" class="cursor-pointer inline-block" @click="zoom = !zoom">
                                        <img id="pp"
                                            class="w-36 h-36 sm:w-40 sm:h-40 object-cover rounded-full border-2 border-[#00509d] shadow-sm transition-transform duration-300"
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
                                    class="flex items-center gap-1.5 border border-[#00509d] text-[#00509d] px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-50/80 w-full md:w-auto justify-center cursor-pointer transition shadow-2xs">
                                    <input type="file" name="img_profile" id="fileinput" accept="image/*" class="hidden">
                                    <i class="ph ph-upload-simple font-bold text-base"></i>
                                    <span>Upload Foto</span>
                                </label>

                                <button type="button"
                                    onclick="event.preventDefault(); document.getElementById('removeForm').submit();"
                                    class="px-4 py-2.5 flex items-center gap-1.5 border border-slate-300 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 w-full md:w-auto justify-center transition">
                                    <i class="ph ph-trash font-bold text-base text-rose-500"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>

                        <!-- Bagian Kanan (Tombol Batal & Simpan) -->
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                            <a href="{{ route('profile.index') }}"
                                class="bg-slate-200 text-slate-700 text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-slate-300 w-full sm:w-auto text-center transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-[#00509d] text-white text-sm font-bold px-8 py-2.5 rounded-xl hover:bg-[#003d7a] w-full sm:w-auto text-center shadow-sm transition">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grid: Dua Kolom Form Edit -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Kolom Kiri -->
                    <div class="flex flex-col gap-4">
                        <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-2">Data Diri</h3>

                        <div>
                            <label class="text-xs font-bold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Nama Lengkap" name="nama_pelamar"
                                value="{{ old('nama_pelamar', Auth::user()->pelamar->nama_pelamar ?? '') }}"
                                class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700">Gender <span class="text-red-500">*</span></label>
                            <div class="flex gap-6 mt-2 text-sm">
                                <label class="flex items-center gap-2 cursor-pointer text-slate-700">
                                    <input type="radio" name="gender" value="laki-laki"
                                        class="w-4 h-4 text-[#00509d] border-2 border-[#00509d]"
                                        {{ (Auth::user()->pelamar->gender ?? '') === 'laki-laki' ? 'checked' : '' }}>
                                    Laki - Laki
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer text-slate-700">
                                    <input type="radio" name="gender" value="perempuan"
                                        class="w-4 h-4 text-[#00509d] border-2 border-[#00509d]"
                                        {{ (Auth::user()->pelamar->gender ?? '') === 'perempuan' ? 'checked' : '' }}>
                                    Perempuan
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir"
                                class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition"
                                value="{{ old('tanggal_lahir', optional(Auth::user()->pelamar->tanggal_lahir)->format('Y-m-d') ?? '') }}">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700">No. Tlp <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="08xxxxxxxx" name="telepon_pelamar"
                                class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition"
                                value="{{ old('telepon_pelamar', Auth::user()->pelamar->telepon_pelamar ?? '') }}">
                            @error('telepon_pelamar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700">Deskripsi Diri <span class="text-red-500">*</span></label>
                            <textarea placeholder="Deskripsikan diri anda secara singkat" name="deskripsi_diri" rows="4"
                                class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition">{{ old('deskripsi_diri', Auth::user()->pelamar->deskripsi_diri ?? '') }}</textarea>
                        </div>

                        <!-- Sosial Media -->
                        <div class="flex flex-col gap-2 mt-4">
                            <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-2">Sosial Media</h3>
                            
                            <label class="text-xs font-bold text-slate-700">Instagram</label>
                            <input type="text" name="instagram" placeholder="Instagram Username / Link"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition"
                                value="{{ $pelamar->social_links['instagram'] ?? '' }}">
                            
                            <label class="text-xs font-bold text-slate-700">LinkedIn</label>
                            <input type="text" name="linkedin" placeholder="LinkedIn Profile Link"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition"
                                value="{{ $pelamar->social_links['linkedin'] ?? '' }}">
                            
                            <label class="text-xs font-bold text-slate-700">Website</label>
                            <input type="text" name="website" placeholder="Website URL"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition"
                                value="{{ $pelamar->social_links['website'] ?? '' }}">
                            
                            <label class="text-xs font-bold text-slate-700">Twitter</label>
                            <input type="text" name="twitter" placeholder="Twitter Username"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition"
                                value="{{ $pelamar->social_links['twitter'] ?? '' }}">
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="flex flex-col gap-4">
                        <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-2">Informasi Akun</h3>

                        <div>
                            <label class="text-xs font-bold text-slate-700">Nama Pengguna / Username <span class="text-red-500">*</span></label>    
                            <input type="text" placeholder="Nama Pengguna" value="{{ old('username', Auth::user()->username) }}" name="username"
                                class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 outline-none transition">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700">Email <span class="text-red-500">*</span></label>
                            <div class="relative mt-1">
                                <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                                    class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-100 text-slate-500 cursor-not-allowed">
                                <span class="absolute right-3 top-2.5 text-[11px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-lg border border-emerald-300">
                                    Terverifikasi
                                </span>
                            </div>
                        </div>

                        <!-- Ekspektasi Gaji -->
                        <div class="mt-4">
                            <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-4">Ekspektasi Gaji</h3>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <!-- Minimal -->
                                <div class="flex items-center border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-800 w-full sm:w-1/2 gap-2 focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-blue-100 transition">
                                    <span class="text-[#00509d] font-bold text-sm">Rp.</span>
                                    <input type="number" min="0" step="100000" placeholder="5000000" name="gaji_minimal"
                                        class="border-none w-full outline-none text-sm font-semibold text-slate-800 bg-transparent"
                                        value="{{ old('gaji_minimal', Auth::user()->pelamar->gaji_minimal ?? '') }}">
                                </div>

                                <span class="text-center hidden sm:block text-slate-400 font-bold">-</span>
                                <span class="text-center sm:hidden text-slate-500 text-xs">sampai</span>

                                <!-- Maksimal -->
                                <div class="flex items-center border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-800 w-full sm:w-1/2 gap-2 focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-blue-100 transition">
                                    <span class="text-[#00509d] font-bold text-sm">Rp.</span>
                                    <input type="number" min="0" step="100000" placeholder="10000000" name="gaji_maksimal"
                                        class="border-none w-full outline-none text-sm font-semibold text-slate-800 bg-transparent"
                                        value="{{ old('gaji_maksimal', Auth::user()->pelamar->gaji_maksimal ?? '') }}">
                                </div>
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
