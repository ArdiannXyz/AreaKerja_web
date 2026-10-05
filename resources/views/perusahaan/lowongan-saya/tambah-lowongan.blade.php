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

                    <!-- Batas Waktu -->
                    <div class="mt-4">
                        <label class="text-sm font-medium">Batas Waktu Lamaran <span class="text-red-500">*</span></label>
                        <input type="date" name="batas_lamaran" value="{{ old('batas_lamaran') }}" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full sm:w-48 border border-gray-300 rounded-md px-3 py-2 mt-2 focus:ring-1 focus:ring-[#00509d] @error('batas_lamaran') border-red-500 @enderror">
                        @error('batas_lamaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Paket Publikasi Lowongan (Bronze, Silver, Gold) -->
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                            <div>
                                <label class="block text-sm font-bold text-slate-800">
                                    Pilih Paket Publikasi Lowongan <span class="text-[#00509d]">*</span>
                                </label>
                                <p class="text-xs text-slate-500 mt-0.5">Pilih salah satu paket (Bronze, Silver, atau Gold) untuk lowongan ini.</p>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-xs font-bold">
                                <i class="ph ph-coins text-base text-amber-500"></i>
                                Saldo Koin Anda: <span class="text-[#003d7a] font-extrabold">{{ $perusahaan->koin_perusahaan ?? 0 }} Koin</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            @php
                                $pkgStyles = [
                                    'Bronze' => ['badge' => 'bg-[#5F554B] text-white', 'border' => 'hover:border-[#5F554B]'],
                                    'Silver' => ['badge' => 'bg-[#8A929A] text-white', 'border' => 'hover:border-[#8A929A]'],
                                    'Gold'   => ['badge' => 'bg-[#F59E0B] text-white', 'border' => 'hover:border-[#F59E0B]'],
                                ];
                            @endphp

                            @foreach ($pakets as $pkg)
                                @php
                                    $style = $pkgStyles[$pkg->nama] ?? ['badge' => 'bg-[#00509d] text-white', 'border' => 'hover:border-[#00509d]'];
                                    $isSelected = old('paket_id') == $pkg->id || (!old('paket_id') && strtolower($pkg->nama) == 'bronze');
                                @endphp
                                <label class="relative flex flex-col justify-between border-2 rounded-2xl p-4 sm:p-5 cursor-pointer transition-all duration-200 shadow-xs hover:shadow-md {{ $style['border'] }} bg-white has-[:checked]:border-[#00509d] has-[:checked]:bg-blue-50/30 has-[:checked]:ring-2 has-[:checked]:ring-[#00509d]">
                                    <input type="radio" name="paket_id" value="{{ $pkg->id }}" {{ $isSelected ? 'checked' : '' }} required class="sr-only">

                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $style['badge'] }}">
                                                {{ $pkg->nama }}
                                            </span>
                                            <span class="text-sm font-black text-slate-800 flex items-center gap-1">
                                                <i class="ph ph-coins text-amber-500"></i> {{ $pkg->harga_koin ?? 50 }} Koin
                                            </span>
                                        </div>

                                        <h4 class="font-extrabold text-sm text-slate-900 mb-1">
                                            {{ $pkg->deskripsi ?? 'Publikasi Lowongan' }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mb-3 font-medium">
                                            Masa Aktif: <strong class="text-slate-800">{{ $pkg->batas_listing ?? 14 }} Hari</strong>
                                        </p>

                                        <ul class="text-[11px] text-slate-600 space-y-1.5 border-t border-slate-100 pt-3">
                                            <li class="flex items-center gap-1.5">
                                                <i class="ph ph-check-circle text-emerald-500 font-bold"></i> Website & Aplikasi
                                            </li>
                                            <li class="flex items-center gap-1.5">
                                                <i class="ph ph-check-circle text-emerald-500 font-bold"></i> Multi Jaringan Medsos
                                            </li>
                                            <li class="flex items-center gap-1.5">
                                                <i class="ph ph-check-circle text-emerald-500 font-bold"></i> Google Jobs & Bisnis
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#003d7a]">
                                        <span>Pilih Paket {{ $pkg->nama }}</span>
                                        <i class="ph ph-arrow-right"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('paket_id')
                            <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
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

