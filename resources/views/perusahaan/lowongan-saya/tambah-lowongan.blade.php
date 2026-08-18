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
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                            alt="">
                    @endif
                @else
                    <img class="w-10 h-10 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
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
                            <label class="block text-sm font-medium">Judul <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                placeholder="Contoh: Backend Developer"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Alamat <span class="text-red-500">*</span></label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}" required
                                placeholder="Contoh: Jakarta Selatan, DKI Jakarta"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 @error('alamat') border-red-500 @enderror">
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenis Lowongan & Gaji -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Jenis Lowongan -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium">
                                Jenis Lowongan <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis" required
                                class="border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 w-full @error('jenis') border-red-500 @enderror">
                                <option value="" disabled {{ old('jenis') ? '' : 'selected' }}>Pilih Jenis Lowongan</option>
                                <option value="Fulltime" {{ old('jenis') == 'Fulltime' ? 'selected' : '' }}>Full Time</option>
                                <option value="Middletime" {{ old('jenis') == 'Middletime' ? 'selected' : '' }}>Middle Time</option>
                                <option value="Freelance" {{ old('jenis') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm font-medium">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required
                                class="border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 w-full @error('kategori') border-red-500 @enderror">
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

                        <div>
                            <label class="text-sm font-medium mb-1">Label Gaji <span class="text-red-500">*</span></label>
                            <input type="text" name="label_gaji" value="{{ old('label_gaji') }}" required
                                placeholder="Contoh: Rp 5jt - 8jt"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 outline-none focus:ring-1 focus:ring-orange-500 @error('label_gaji') border-red-500 @enderror" />
                            @error('label_gaji')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium mb-1">Benefit <span class="text-red-500">*</span></label>
                            <input type="text" name="benefit" value="{{ old('benefit') }}" required
                                placeholder="Contoh: BPJS, THR, Bonus"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 outline-none focus:ring-1 focus:ring-orange-500 @error('benefit') border-red-500 @enderror" />
                            @error('benefit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaji -->
                        <div class="flex flex-col sm:col-span-2 lg:col-span-4">
                            <label class="text-sm font-medium">
                                Rentang Gaji (Nominal Angka) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2 mt-1">
                                <input type="number" name="gaji_awal" value="{{ old('gaji_awal') }}" required min="0"
                                    placeholder="Gaji Minimal (cth: 5000000)"
                                    class="w-full sm:w-60 border border-gray-300 rounded-md px-3 py-2 outline-none focus:ring-1 focus:ring-orange-500 @error('gaji_awal') border-red-500 @enderror" />
                                <span class="text-gray-500 font-bold">-</span>
                                <input type="number" name="gaji_akhir" value="{{ old('gaji_akhir') }}" required min="0"
                                    placeholder="Gaji Maksimal (cth: 8000000)"
                                    class="w-full sm:w-60 border border-gray-300 rounded-md px-3 py-2 outline-none focus:ring-1 focus:ring-orange-500 @error('gaji_akhir') border-red-500 @enderror" />
                            </div>
                            @error('gaji_awal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('gaji_akhir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea rows="3" name="deskripsi" required
                            placeholder="Jelaskan gambaran umum pekerjaan..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggung Jawab -->
                    <div>
                        <label class="block text-sm font-medium">Tanggung Jawab <span class="text-red-500">*</span></label>
                        <textarea rows="3" name="tanggung_jawab" required
                            placeholder="Jelaskan rincian tanggung jawab dan tugas pekerjaan..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 @error('tanggung_jawab') border-red-500 @enderror">{{ old('tanggung_jawab') }}</textarea>
                        @error('tanggung_jawab')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Syarat Pekerjaan -->
                    <div>
                        <label class="block text-sm font-medium">Syarat Pekerjaan <span class="text-red-500">*</span></label>
                        <textarea rows="3" name="syarat_pekerjaan" required
                            placeholder="Contoh: Minimal D3/S1, menguasai Vue.js atau React, pengalaman minimal 1 tahun..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 outline-none focus:ring-1 focus:ring-orange-500 @error('syarat_pekerjaan') border-red-500 @enderror">{{ old('syarat_pekerjaan') }}</textarea>
                        @error('syarat_pekerjaan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Batas Waktu -->
                    <div class="mt-4">
                        <label class="text-sm font-medium">Batas Waktu Lamaran <span class="text-red-500">*</span></label>
                        <input type="date" name="batas_lamaran" value="{{ old('batas_lamaran') }}" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full sm:w-48 border border-gray-300 rounded-md px-3 py-2 mt-2 focus:ring-1 focus:ring-orange-500 @error('batas_lamaran') border-red-500 @enderror">
                        @error('batas_lamaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-center space-x-4 pt-6">
                        <a href="{{ route('lowongan.saya.perusahaan') }}"
                            class="px-6 py-2 border-2 border-orange-500 rounded-md text-orange-500 hover:bg-orange-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition shadow">
                            Simpan
                        </button>
                    </div>
                </form>
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

            // Real-time clear on input / change
            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('input', () => clearError(el));
                el.addEventListener('change', () => clearError(el));
            });

            form.addEventListener('submit', function(e) {
                let isValid = true;
                let firstInvalid = null;

                // Clear previous client errors
                form.querySelectorAll('.js-inline-error').forEach(el => el.remove());

                // All fields check
                const fields = ['nama', 'alamat', 'jenis', 'kategori', 'label_gaji', 'benefit', 'gaji_awal', 'gaji_akhir', 'deskripsi', 'tanggung_jawab', 'syarat_pekerjaan', 'batas_lamaran'];
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
