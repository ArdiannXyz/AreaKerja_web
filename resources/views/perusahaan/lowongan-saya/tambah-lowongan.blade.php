@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white flex justify-center py-10 mt-20">

        <div class="w-full max-w-[900px] p-4 sm:p-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 border p-4 rounded-md mb-6 shadow-md">

                @if (Auth::user()->role == 'perusahaan')
                    @if (Auth::user()->perusahaan->img_profile)
                        <img id="pu" class="w-20 h-20 object-cover rounded-full profile-img"
                            src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                    @else
                        <img id="pu" class="w-20 h-20 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                            alt="">
                    @endif
                @else
                    <img class="w-10 h-10 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                        alt="">
                @endif
                <div>
                    <h1 class="font-bold text-lg m-1">{{ Auth::user()->perusahaan->nama_perusahaan }}</h1>
                    <p class="text-sm text-gray-600 m-1">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                    <p class="text-sm text-gray-600 m-1">{{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->kecamatan->nama ?? '-' }}</p>
                </div>
            </div>

            <!-- Form -->
            <div class="border shadow-md rounded-md p-6">
                <h2 class="font-semibold text-lg mb-4">Tambah Lowongan</h2>

                @if (session('error') || $errors->has('kuota'))
                    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium flex items-start gap-3">
                        <i class="ph-fill ph-warning-circle text-rose-600 text-xl shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-rose-900">Batas Kuota Tercapai</p>
                            <p class="mt-0.5">{{ session('error') ?? $errors->first('kuota') }}</p>
                            <a href="{{ route('perusahaan.berlangganan') }}" class="inline-flex items-center gap-1 font-bold text-[#00509d] underline mt-2">
                                <i class="ph-fill ph-crown text-amber-500"></i> Upgrade ke VIP Sekarang
                            </a>
                        </div>
                    </div>
                @endif

                <form action="{{ route('lowongan.saya.store') }}" method="POST" class="space-y-5" novalidate>
                    @csrf
                    <!-- Judul & Alamat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium">Judul Lowongan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                placeholder="Contoh: Backend Developer"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-medium">Alamat Penempatan <span class="text-red-500">*</span></label>
                                <a href="{{ route('alamat.perusahaan') }}" class="text-xs text-[#00509d] hover:underline flex items-center gap-1 font-semibold">
                                    <i class="ph ph-gear"></i> Kelola Alamat
                                </a>
                            </div>
                            @if(isset($alamats) && $alamats->count() > 0)
                                <select name="alamat" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] @error('alamat') border-red-500 @enderror">
                                    <option value="" disabled>Pilih Alamat Kantor / Penempatan</option>
                                    @foreach ($alamats as $alm)
                                        @php
                                            $formattedAlm = $alm->alamat_lengkap ?? ($alm->desa ? ($alm->desa . ', ' . $alm->kecamatan . ', ' . $alm->kota . ', ' . $alm->provinsi) : $alm->alamat);
                                        @endphp
                                        <option value="{{ $formattedAlm }}" {{ (old('alamat') == $formattedAlm || (!old('alamat') && $alm->utama == 1)) ? 'selected' : '' }}>
                                            {{ $alm->utama == 1 ? '[Alamat Utama] ' : '' }}{{ $formattedAlm }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="alamat" value="{{ old('alamat', $perusahaan->alamat ?? '') }}" required
                                    placeholder="Contoh: Jakarta Selatan, DKI Jakarta"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] @error('alamat') border-red-500 @enderror">
                            @endif
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenis Lowongan, Kategori, & Benefit -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Jenis Lowongan -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium">
                                Jenis Lowongan <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis" required
                                class="border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] w-full @error('jenis') border-red-500 @enderror">
                                <option value="" disabled {{ old('jenis') ? '' : 'selected' }}>Pilih Jenis Lowongan</option>
                                <option value="Fulltime" {{ old('jenis') == 'Fulltime' ? 'selected' : '' }}>Full Time</option>
                                <option value="Middletime" {{ old('jenis') == 'Middletime' ? 'selected' : '' }}>Middle Time</option>
                                <option value="Freelance" {{ old('jenis') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required
                                class="border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] w-full @error('kategori') border-red-500 @enderror">
                                <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->nama }}"
                                        {{ old('kategori') == $cat->nama ? 'selected' : '' }}>
                                        {{ $cat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Benefit -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium">Benefit <span class="text-red-500">*</span></label>
                            <input type="text" name="benefit" value="{{ old('benefit') }}" required
                                placeholder="Contoh: BPJS, THR, Bonus"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] @error('benefit') border-red-500 @enderror" />
                            @error('benefit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Rentang Gaji & Label Gaji (Uneditable) -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 sm:p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-800">
                                Rentang Gaji (Nominal Angka) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3 mt-2 flex-wrap">
                                <div class="relative w-full sm:w-60">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="gaji_awal" id="gaji_awal_input" value="{{ old('gaji_awal') }}" required min="0"
                                        oninput="autoGenerateLabelGaji()"
                                        placeholder="Gaji Minimal (cth: 5000000)"
                                        class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 outline-none focus:ring-1 focus:ring-[#00509d] bg-white @error('gaji_awal') border-red-500 @enderror" />
                                </div>
                                <span class="text-gray-400 font-bold">-</span>
                                <div class="relative w-full sm:w-60">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number" name="gaji_akhir" id="gaji_akhir_input" value="{{ old('gaji_akhir') }}" required min="0"
                                        oninput="autoGenerateLabelGaji()"
                                        placeholder="Gaji Maksimal (cth: 8000000)"
                                        class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 outline-none focus:ring-1 focus:ring-[#00509d] bg-white @error('gaji_akhir') border-red-500 @enderror" />
                                </div>
                            </div>
                            @error('gaji_awal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('gaji_akhir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Label Gaji (Uneditable / Readonly) -->
                        <div class="pt-3 border-t border-slate-200">
                            <label class="block text-xs font-bold text-slate-600 mb-1.5 flex items-center gap-1.5">
                                <i class="ph ph-lock text-slate-400"></i> Label Gaji
                            </label>
                            <input type="text" name="label_gaji" id="label_gaji_input" readonly value="{{ old('label_gaji') }}"
                                placeholder="Terisi otomatis dari rentang gaji di atas"
                                class="w-full sm:w-80 bg-slate-200/80 border border-slate-300 text-slate-800 font-bold rounded-md px-3.5 py-2 cursor-not-allowed outline-none text-xs select-none" />
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea rows="3" name="deskripsi" required
                            placeholder="Jelaskan gambaran umum pekerjaan..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggung Jawab -->
                    <div>
                        <label class="block text-sm font-medium">Tanggung Jawab <span class="text-red-500">*</span></label>
                        <textarea rows="3" name="tanggung_jawab" required
                            placeholder="Jelaskan rincian tanggung jawab dan tugas pekerjaan..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] @error('tanggung_jawab') border-red-500 @enderror">{{ old('tanggung_jawab') }}</textarea>
                        @error('tanggung_jawab')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Syarat Pekerjaan -->
                    <div>
                        <label class="block text-sm font-medium">Syarat Pekerjaan <span class="text-red-500">*</span></label>
                        <textarea rows="3" name="syarat_pekerjaan" required
                            placeholder="Contoh: Minimal D3/S1, menguasai Vue.js atau React, pengalaman minimal 1 tahun..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-[#00509d] @error('syarat_pekerjaan') border-red-500 @enderror">{{ old('syarat_pekerjaan') }}</textarea>
                        @error('syarat_pekerjaan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Masa Berlaku & Ketentuan Kuota Otomatis Sesuai Tier -->
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <div class="rounded-2xl p-5 {{ ($isVip ?? false) ? 'bg-gradient-to-r from-blue-50 via-white to-amber-50/40 border border-blue-200' : 'bg-slate-50 border border-slate-200' }}">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ ($isVip ?? false) ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-[#00509d]' }} flex items-center justify-center text-xl font-bold shrink-0">
                                        <i class="{{ ($isVip ?? false) ? 'ph-fill ph-crown' : 'ph ph-briefcase' }}"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-black uppercase tracking-wider {{ ($isVip ?? false) ? 'text-amber-700' : 'text-slate-600' }}">
                                                {{ ($isVip ?? false) ? 'Tier VIP Employer' : 'Tier Akun Biasa (Gratis)' }}
                                            </span>
                                            @if ($isVip ?? false)
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded">VIP Aktif</span>
                                            @endif
                                        </div>
                                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900 mt-0.5">
                                            Ketentuan Publikasi & Masa Berlaku Lowongan
                                        </h4>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <span class="text-xs font-bold text-slate-500">Kuota Lowongan Aktif:</span>
                                    <p class="text-sm font-black text-[#00509d]">
                                        {{ $activeLowonganCount ?? 0 }} / {{ $maxActiveLowongan ?? 2 }} Terpakai
                                    </p>
                                </div>
                            </div>

                            @if ($isVip ?? false)
                                <!-- Pilihan Durasi Masa Tayang Khusus VIP -->
                                <div class="pt-3 border-t border-blue-200/80 mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs font-black text-slate-800 uppercase tracking-wider">
                                            Pilihan Masa Berlaku Lowongan (Fitur Eksklusif VIP) <span class="text-rose-500">*</span>
                                        </label>
                                        <span class="text-[11px] font-bold text-amber-700 bg-amber-100/70 px-2 py-0.5 rounded border border-amber-200 flex items-center gap-1">
                                            <i class="ph-fill ph-crown text-amber-600"></i> Pilihan 60 hingga 90 Hari
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        @php
                                            $selectedDurasi = old('durasi_hari', 60);
                                        @endphp
                                        <label class="durasi-card relative p-3.5 rounded-xl border-2 cursor-pointer transition flex flex-col justify-between {{ $selectedDurasi == 30 ? 'border-[#00509d] bg-blue-50/50 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-black text-slate-900">30 Hari</span>
                                                <input type="radio" name="durasi_hari" value="30" {{ $selectedDurasi == 30 ? 'checked' : '' }} class="w-4 h-4 text-[#00509d] focus:ring-[#00509d]" onchange="updateDurasiCards(this)">
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-medium mt-1">1 Bulan Masa Tayang (Standar)</p>
                                            <span class="text-[10px] text-slate-400 mt-2">Exp: {{ now()->addDays(30)->translatedFormat('d M Y') }}</span>
                                        </label>

                                        <label class="durasi-card relative p-3.5 rounded-xl border-2 cursor-pointer transition flex flex-col justify-between {{ $selectedDurasi == 60 ? 'border-[#00509d] bg-blue-50/50 shadow-xs ring-2 ring-blue-100' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-xs font-black text-slate-900">60 Hari</span>
                                                    <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-800 text-[9px] font-black rounded">Populer VIP</span>
                                                </div>
                                                <input type="radio" name="durasi_hari" value="60" {{ $selectedDurasi == 60 ? 'checked' : '' }} class="w-4 h-4 text-[#00509d] focus:ring-[#00509d]" onchange="updateDurasiCards(this)">
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-medium mt-1">2 Bulan Rekrutmen Intensif</p>
                                            <span class="text-[10px] text-slate-400 mt-2">Exp: {{ now()->addDays(60)->translatedFormat('d M Y') }}</span>
                                        </label>

                                        <label class="durasi-card relative p-3.5 rounded-xl border-2 cursor-pointer transition flex flex-col justify-between {{ $selectedDurasi == 90 ? 'border-[#00509d] bg-blue-50/50 shadow-xs ring-2 ring-blue-100' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-xs font-black text-slate-900">90 Hari</span>
                                                    <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[9px] font-black rounded">Maksimal 3 Bulan</span>
                                                </div>
                                                <input type="radio" name="durasi_hari" value="90" {{ $selectedDurasi == 90 ? 'checked' : '' }} class="w-4 h-4 text-[#00509d] focus:ring-[#00509d]" onchange="updateDurasiCards(this)">
                                            </div>
                                            <p class="text-[11px] text-slate-500 font-medium mt-1">3 Bulan Masa Tayang Terpanjang</p>
                                            <span class="text-[10px] text-slate-400 mt-2">Exp: {{ now()->addDays(90)->translatedFormat('d M Y') }}</span>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-slate-200/80 text-xs">
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs">
                                    <span class="text-slate-500 font-medium block">Masa Berlaku Tayang:</span>
                                    <strong class="text-slate-900 font-extrabold text-sm text-[#00509d]">
                                        {{ ($isVip ?? false) ? 'Hingga 90 Hari (Fleksibel VIP)' : '30 Hari (1 Bulan Penuh)' }}
                                    </strong>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        {{ ($isVip ?? false) ? 'Sesuai opsi durasi yang Anda pilih di atas' : 'Kedaluwarsa otomatis: ' . now()->addDays(30)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs">
                                    <span class="text-slate-500 font-medium block">Batas Pelamar Masuk:</span>
                                    <strong class="text-slate-900 font-extrabold text-sm {{ ($isVip ?? false) ? 'text-emerald-700' : 'text-slate-800' }}">
                                        {{ ($isVip ?? false) ? 'Unlimited (Tanpa Batas)' : 'Maksimal 5 / Hari' }}
                                    </strong>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ ($isVip ?? false) ? 'Bebas kandidat setiap saat' : 'Terkunci per hari jika penuh' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs">
                                    <span class="text-slate-500 font-medium block">Job Booster (Pencarian):</span>
                                    <strong class="text-slate-900 font-extrabold text-sm {{ ($isVip ?? false) ? 'text-amber-600' : 'text-slate-600' }}">
                                        {{ ($isVip ?? false) ? '⭐ Selalu Teratas (Top 1)' : 'Urutan Standar' }}
                                    </strong>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ ($isVip ?? false) ? 'Prioritas tertinggi di beranda' : 'Posisi penayangan reguler' }}</p>
                                </div>
                            </div>

                            @if (!($isVip ?? false))
                                <div class="mt-4 pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-amber-50/60 p-3 rounded-xl border border-amber-200/60">
                                    <div class="text-xs text-amber-900 font-medium">
                                        Ingin kuota hingga <strong>20 lowongan aktif</strong>, pilihan durasi <strong>60 hingga 90 hari</strong>, dan otomatis ranking teratas?
                                    </div>
                                    <a href="{{ route('perusahaan.berlangganan') }}"
                                        class="shrink-0 px-3 py-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-lg transition flex items-center gap-1 shadow-2xs">
                                        <i class="ph-fill ph-crown text-amber-300"></i> Upgrade VIP
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-center space-x-4 pt-8">
                        <a href="{{ route('lowongan.saya.perusahaan') }}"
                            class="px-6 py-2.5 border-2 border-[#00509d] rounded-xl text-[#00509d] font-bold hover:bg-blue-50 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-[#00509d] text-white font-bold rounded-xl hover:bg-[#003d7a] transition shadow-md text-sm">
                            Simpan & Pasang Lowongan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TIDAK CUKUP KOIN --}}
    <div x-data="{ open: {{ session('koin_kurang') ? 'true' : 'false' }} }" x-show="open" x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
        <div x-transition class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl max-w-sm w-full text-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-[#00509d] flex items-center justify-center mx-auto mb-3">
                <i class="ph ph-warning-circle text-2xl font-bold"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mb-2">Koin Tidak Mencukupi</h2>
            <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                Jumlah koin Anda saat ini tidak cukup untuk memasang paket lowongan yang dipilih. Silakan lakukan Top Up terlebih dahulu.
            </p>
            <div class="flex gap-3 justify-center">
                <button @click="open = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-semibold">
                    Tutup
                </button>
                <a href="{{ route('perusahaan.dashboard') }}"
                    class="px-5 py-2 bg-[#00509d] text-white rounded-xl text-xs font-bold hover:bg-[#003d7a] transition shadow-xs">
                    Top Up Sekarang
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateDurasiCards(el) {
            document.querySelectorAll('.durasi-card').forEach(card => {
                card.classList.remove('border-[#00509d]', 'bg-blue-50/50', 'shadow-xs', 'ring-2', 'ring-blue-100');
                card.classList.add('border-slate-200', 'bg-white');
            });
            const parent = el.closest('.durasi-card');
            if (parent) {
                parent.classList.remove('border-slate-200', 'bg-white');
                parent.classList.add('border-[#00509d]', 'bg-blue-50/50', 'shadow-xs', 'ring-2', 'ring-blue-100');
            }
        }
        window.updateDurasiCards = updateDurasiCards;

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('lowongan.saya.store') }}"]');
            if (!form) return;

            const errorMessages = {
                nama: 'Judul lowongan wajib diisi.',
                alamat: 'Alamat lowongan wajib diisi.',
                jenis: 'Jenis lowongan wajib dipilih.',
                kategori: 'Kategori lowongan wajib dipilih.',
                label_gaji: 'Label gaji wajib diisi.',
                benefit: 'Benefit lowongan wajib diisi.',
                gaji_awal: 'Gaji minimal wajib diisi.',
                gaji_akhir: 'Gaji maksimal wajib diisi.',
                deskripsi: 'Deskripsi lowongan wajib diisi.',
                tanggung_jawab: 'Tanggung jawab wajib diisi.',
                syarat_pekerjaan: 'Syarat pekerjaan wajib diisi.',
                batas_lamaran: 'Batas waktu lamaran wajib diisi.'
            };

            function clearError(input) {
                input.classList.remove('border-red-500');
                const parent = input.closest('div');
                if (parent) {
                    const existing = parent.querySelector('.js-inline-error');
                    if (existing) existing.remove();
                }
            }

            function showError(input, message) {
                input.classList.add('border-red-500');
                const parent = input.closest('div');
                if (parent) {
                    let err = parent.querySelector('.js-inline-error');
                    if (!err) {
                        err = document.createElement('p');
                        err.className = 'js-inline-error text-red-500 text-xs mt-1';
                        parent.appendChild(err);
                    }
                    err.textContent = message;
                }
            }

            function autoGenerateLabelGaji() {
                const awal = document.getElementById('gaji_awal_input')?.value;
                const akhir = document.getElementById('gaji_akhir_input')?.value;
                const labelInput = document.getElementById('label_gaji_input');
                
                if (labelInput && awal && akhir) {
                    const formatJuta = (val) => {
                        val = Number(val);
                        if (isNaN(val) || val <= 0) return '0';
                        if (val >= 1000000) {
                            const jt = (val / 1000000);
                            return Number.isInteger(jt) ? jt + ' jt' : jt.toFixed(1) + ' jt';
                        }
                        return 'Rp ' + val.toLocaleString('id-ID');
                    };
                    
                    labelInput.value = `Rp ${formatJuta(awal)} - ${formatJuta(akhir)}`;
                    clearError(labelInput);
                }
            }
            window.autoGenerateLabelGaji = autoGenerateLabelGaji;

            // Clear previous client errors
            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('input', () => clearError(el));
                el.addEventListener('change', () => clearError(el));
            });

            form.addEventListener('submit', function(e) {
                // If label_gaji is empty, auto-generate from salary range
                const labelInput = document.getElementById('label_gaji_input');
                if (labelInput && !labelInput.value.trim()) {
                    autoGenerateLabelGaji();
                }

                let isValid = true;
                let firstInvalid = null;

                // Clear previous client errors
                form.querySelectorAll('.js-inline-error').forEach(el => el.remove());

                // Required fields check
                const fields = ['nama', 'alamat', 'jenis', 'kategori', 'benefit', 'gaji_awal', 'gaji_akhir', 'deskripsi', 'tanggung_jawab', 'syarat_pekerjaan', 'batas_lamaran'];
                fields.forEach(fieldName => {
                    const input = form.querySelector(`[name="${fieldName}"]`);
                    if (input && !input.value.trim()) {
                        isValid = false;
                        showError(input, errorMessages[fieldName]);
                        if (!firstInvalid) firstInvalid = input;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (firstInvalid.focus) firstInvalid.focus();
                    }
                }
            });
        });
    </script>

    @include('layouts.footer')
@endsection

