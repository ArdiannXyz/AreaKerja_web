@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <!-- HEADER TOP BAR -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-6 bg-[#00509d] text-white p-3.5 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.talent-hunter.detail', $talentHunter->id) }}"
                       class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition flex-shrink-0"
                       title="Kembali ke Detail Talent Hunter">
                        <i class="ph ph-arrow-left text-base"></i>
                    </a>
                    <div>
                        <h1 class="text-base sm:text-xl font-semibold text-white tracking-tight leading-tight flex items-center gap-2">
                            <i class="ph ph-note-pencil text-white/80"></i> Edit Talent Hunter
                        </h1>
                        <p class="text-[11px] sm:text-xs text-blue-100 mt-0.5">{{ $perusahaan->nama_perusahaan }}</p>
                    </div>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                    @include('super_admin.components.notif_button')
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <div class="w-full max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 sm:p-8">
                <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Form Edit Data Talent Hunter
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi kriteria kebutuhan talent hunter perusahaan ini</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 text-[#00509d] text-xs font-semibold rounded-full border border-blue-100">
                        Talent Hunter
                    </span>
                </div>

                <form action="{{ route('superadmin.talent-hunter.update', $talentHunter->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Perusahaan (Readonly) -->
                    <div class="p-4 sm:p-5 bg-slate-50 rounded-xl border border-slate-200/70 space-y-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            <i class="ph ph-buildings text-base text-[#00509d]"></i> Informasi Perusahaan (Read-only)
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Perusahaan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Perusahaan</label>
                                <input type="text" value="{{ $perusahaan->nama_perusahaan }}" disabled
                                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-100/80 text-slate-600 text-sm cursor-not-allowed" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email Akun</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-100/80 text-slate-600 text-sm cursor-not-allowed" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- No Telepon -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">No. Telepon</label>
                                <input type="text" value="{{ $perusahaan->telepon_perusahaan }}" disabled
                                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-100/80 text-slate-600 text-sm cursor-not-allowed" />
                            </div>

                            <!-- Deskripsi Perusahaan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Deskripsi Perusahaan</label>
                                <textarea disabled class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-100/80 text-slate-600 text-sm cursor-not-allowed resize-none"
                                    rows="1">{{ $perusahaan->deskripsi }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Kebutuhan Talent Hunter (Editable) -->
                    <div class="space-y-5 pt-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600 uppercase tracking-wider pb-1 border-b border-slate-100">
                            <i class="ph ph-user-focus text-base text-[#00509d]"></i> Kriteria Kebutuhan Kandidat
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Penempatan / Lokasi <span class="text-rose-500">*</span></label>
                            <input type="text" name="alamat" value="{{ old('alamat', $talentHunter->alamat) }}" required
                                class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#00509d] transition shadow-sm" />
                            @error('alamat')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Posisi & Pengalaman -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Posisi -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Posisi yang Dibutuhkan <span class="text-rose-500">*</span></label>
                                <input type="text" name="posisi" value="{{ old('posisi', $talentHunter->posisi) }}" required
                                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#00509d] transition shadow-sm" />
                                @error('posisi')
                                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pengalaman Kerja -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pengalaman Kerja <span class="text-rose-500">*</span></label>
                                <input type="text" name="pengalaman_kerja"
                                    value="{{ old('pengalaman_kerja', $talentHunter->pengalaman_kerja) }}" required
                                    class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#00509d] transition shadow-sm" />
                                @error('pengalaman_kerja')
                                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                            <!-- Gender -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-2">Preferensi Gender <span class="text-rose-500">*</span></label>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer p-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition flex-1">
                                        <input type="radio" name="gender" value="Laki-laki"
                                            {{ old('gender', $talentHunter->gender) == 'Laki-laki' ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#00509d] border-slate-300 focus:ring-[#00509d]" />
                                        <span class="text-xs font-medium text-slate-700">Laki-laki</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer p-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition flex-1">
                                        <input type="radio" name="gender" value="Perempuan"
                                            {{ old('gender', $talentHunter->gender) == 'Perempuan' ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#00509d] border-slate-300 focus:ring-[#00509d]" />
                                        <span class="text-xs font-medium text-slate-700">Perempuan</span>
                                    </label>
                                </div>
                                @error('gender')
                                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gaji yang Ditawarkan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-2">Gaji yang Ditawarkan (Min. Rp 3.500.000) <span class="text-rose-500">*</span></label>
                                <div class="flex items-center rounded-xl border border-slate-200 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-[#00509d] transition bg-white shadow-sm">
                                    <span class="px-3.5 py-2.5 bg-slate-50 text-slate-600 text-xs font-bold border-r border-slate-200">Rp</span>
                                    <input type="number" name="gaji_ditawarkan" min="3500000"
                                        value="{{ old('gaji_ditawarkan', $talentHunter->gaji_ditawarkan ?? $talentHunter->gaji_awal) }}"
                                        class="w-full px-3.5 py-2.5 text-slate-800 text-sm focus:outline-none"
                                        placeholder="3500000" required />
                                </div>
                                @error('gaji_ditawarkan')
                                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi Talent Hunter -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi / Catatan Tambahan Kebutuhan</label>
                            <textarea name="deskripsi" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#00509d] transition shadow-sm resize-y" rows="4">{{ old('deskripsi', $talentHunter->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('superadmin.talent-hunter.detail', $talentHunter->id) }}"
                            class="w-full sm:w-auto px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium text-xs rounded-xl transition text-center shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto bg-[#00509d] hover:bg-[#003d7a] text-white font-medium text-xs px-6 py-2.5 rounded-xl shadow-sm hover:shadow transition flex items-center justify-center gap-2">
                            <i class="ph ph-floppy-disk text-base"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
