@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-slate-50 min-h-screen text-slate-800 pt-28 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">

            <!-- Alert Notifikasi -->
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs">
                    <i class="ph ph-check-circle text-emerald-600 text-2xl shrink-0"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs">
                    <i class="ph ph-warning-circle text-rose-600 text-2xl shrink-0"></i>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-sm text-rose-900 mb-1">
                        <i class="ph ph-warning text-lg"></i> Terjadi kesalahan:
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. TOP HEADER BANNER CARD -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/90 p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-[#00509d]/20 bg-slate-50 p-1 shrink-0 flex items-center justify-center shadow-sm relative group">
                        @if (Auth::user()->perusahaan->img_profile)
                            <img id="header-logo-preview" src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                        @else
                            <img id="header-logo-preview" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username) }}&background=00509d&color=fff&size=128" alt="Logo" class="w-full h-full object-cover rounded-xl">
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                                {{ Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username }}
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                <i class="ph ph-check-circle text-xs font-bold"></i> Terverifikasi
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-500 mt-1">
                            {{ Auth::user()->perusahaan->jenis_perusahaan ?? 'Sektor Usaha Belum Diatur' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                            <i class="ph ph-map-pin text-[#00509d]"></i>
                            {{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? 'Lokasi Utama' }},
                            {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? 'Indonesia' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('profile.perusahaan') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl shadow-sm transition text-sm flex items-center gap-2">
                        <i class="ph ph-arrow-left text-base"></i> Kembali ke Profil
                    </a>
                </div>
            </div>

            <!-- 2. EDIT FORM CARD -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/90 p-6 md:p-8">
                <!-- Card Header -->
                <div class="border-b border-slate-200 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                            <i class="ph ph-pencil-simple text-[#00509d] text-2xl"></i> Edit Profil Perusahaan
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Perbarui data profil, kontak, visi misi, dan logo perusahaan Anda.</p>
                    </div>
                    <span class="text-xs text-rose-500 font-semibold bg-rose-50 px-3 py-1 rounded-full border border-rose-100 w-fit">
                        * Bidang wajib diisi
                    </span>
                </div>

                <form action="{{ route('profile.update.perusahaan', Auth::user()->perusahaan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- SECTION: UPLOAD LOGO -->
                    <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-5 md:p-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-4">
                            Logo Perusahaan
                        </label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border-2 border-[#00509d]/30 bg-white p-1.5 shrink-0 flex items-center justify-center shadow-xs relative">
                                @if (Auth::user()->perusahaan->img_profile)
                                    <img id="form-logo-preview" src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <img id="form-logo-preview" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username) }}&background=00509d&color=fff&size=128" alt="Logo" class="w-full h-full object-cover rounded-xl">
                                @endif
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <input type="file" name="img_profile" id="fileinputperusahaan" accept="image/*" class="hidden">
                                    <button type="button" onclick="document.getElementById('fileinputperusahaan').click();"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl text-xs transition shadow-xs">
                                        <i class="ph ph-upload-simple text-base"></i> Pilih File Foto
                                    </button>

                                    @if (Auth::user()->perusahaan->img_profile)
                                        <button type="button" onclick="if(confirm('Yakin ingin menghapus foto logo profil?')) document.getElementById('removeperusahaanForm').submit();"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-bold rounded-xl text-xs transition">
                                            <i class="ph ph-trash text-sm"></i> Hapus Logo
                                        </button>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Format: JPG, JPEG, PNG (Maks. 2MB). Disarankan menggunakan logo dengan rasio 1:1.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: INFORMASI UTAMA -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
                            <i class="ph ph-buildings text-xl text-[#00509d]"></i>
                            <h3 class="text-base font-extrabold text-slate-900">Informasi Perusahaan</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nama Perusahaan <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nama_perusahaan" required
                                    value="{{ old('nama_perusahaan', Auth::user()->perusahaan->nama_perusahaan) }}"
                                    placeholder="Contoh: PT. AreaKerja Teknologi"
                                    class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Badan Usaha / Jenis Perusahaan <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="jenis_perusahaan" required
                                    value="{{ old('jenis_perusahaan', Auth::user()->perusahaan->jenis_perusahaan) }}"
                                    placeholder="Contoh: Teknologi Informasi, Manufaktur, Retail"
                                    class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Website Resmi
                                </label>
                                <input type="text" name="website_perusahaan"
                                    value="{{ old('website_perusahaan', Auth::user()->perusahaan->website_perusahaan) }}"
                                    placeholder="https://perusahaan.com"
                                    class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nomor Telepon Kantor / HRD
                                </label>
                                <input type="text" name="telepon_perusahaan"
                                    value="{{ old('telepon_perusahaan', Auth::user()->perusahaan->telepon_perusahaan) }}"
                                    placeholder="081234567890"
                                    class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Nomor WhatsApp
                                </label>
                                <input type="text" name="whatsapp"
                                    value="{{ old('whatsapp', Auth::user()->perusahaan->whatsapp) }}"
                                    placeholder="081234567890"
                                    class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Email Akun Terdaftar
                                </label>
                                <input type="email" readonly value="{{ Auth::user()->email }}"
                                    class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-500 cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Deskripsi Perusahaan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="deskripsi" rows="4" required
                                placeholder="Jelaskan profil singkat, bidang keahlian, budaya kerja, atau latar belakang perusahaan..."
                                class="w-full bg-slate-50/50 border border-slate-300 rounded-xl p-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition leading-relaxed">{{ old('deskripsi', Auth::user()->perusahaan->deskripsi) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Visi Perusahaan</label>
                                <textarea name="visi" rows="3"
                                    placeholder="Visi perusahaan ke depan..."
                                    class="w-full bg-slate-50/50 border border-slate-300 rounded-xl p-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition leading-relaxed">{{ old('visi', Auth::user()->perusahaan->visi) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Misi Perusahaan</label>
                                <textarea name="misi" rows="3"
                                    placeholder="Misi perusahaan untuk mencapai visi..."
                                    class="w-full bg-slate-50/50 border border-slate-300 rounded-xl p-3.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition leading-relaxed">{{ old('misi', Auth::user()->perusahaan->misi) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: LOKASI PERUSAHAAN SHORTCUT -->
                    <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#00509d] flex items-center justify-center shrink-0">
                                <i class="ph ph-map-pin text-xl font-bold"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-slate-900">Alamat & Lokasi Kantor</h4>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Lokasi utama: 
                                    <span class="font-bold text-slate-700">
                                        {{ Auth::user()->perusahaan->alamatUtama->alamat_lengkap ?? (Auth::user()->perusahaan->alamatUtama->kota->nama ?? 'Belum Diatur') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('alamat.perusahaan') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-[#00509d]/30 text-[#00509d] hover:bg-blue-50 font-bold rounded-xl text-xs transition shadow-2xs shrink-0">
                            <i class="ph ph-gear"></i> Kelola Alamat Lengkap
                        </a>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('profile.perusahaan') }}"
                            class="px-6 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-sm transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-7 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold rounded-xl text-sm shadow-sm transition">
                            <i class="ph ph-check text-base font-bold"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

                <!-- Hidden Delete Profile Logo Form -->
                <form id="removeperusahaanForm" action="{{ route('profile.destroy.perusahaan', Auth::user()->perusahaan->id) }}"
                    method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

        </div>
    </div>

    <!-- SCRIPT PREVIEW LOGO -->
    <script>
        document.getElementById('fileinputperusahaan')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const formPreview = document.getElementById('form-logo-preview');
                    const headerPreview = document.getElementById('header-logo-preview');
                    if (formPreview) formPreview.setAttribute('src', event.target.result);
                    if (headerPreview) headerPreview.setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    @include('layouts.footer')
@endsection

