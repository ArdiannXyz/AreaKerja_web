@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-[#00509d] text-white p-3.5 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.iklan.index') }}"
                       class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition flex-shrink-0"
                       title="Kembali">
                        <i class="ph ph-arrow-left text-base"></i>
                    </a>
                    <div>
                        <h1 class="text-base sm:text-xl font-semibold text-white tracking-tight leading-tight">Pasang Iklan Baru</h1>
                        <p class="text-[11px] sm:text-xs text-blue-100 mt-0.5">Iklan / Pasang Iklan Baru</p>
                    </div>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                    @include('admin.components.notif_button')
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')

        {{-- Flash Alerts --}}
        @if (session('success'))
            <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-check-circle text-emerald-600 text-lg"></i>
                </div>
                <div>
                    <p class="font-bold text-xs">Berhasil!</p>
                    <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-warning-circle text-rose-600 text-lg"></i>
                </div>
                <div>
                    <p class="font-bold text-xs">Terjadi Kendala</p>
                    <p class="text-xs text-rose-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.iklan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

                {{-- ============ KOLOM KIRI (2/3) ============ --}}
                <div class="xl:col-span-2 space-y-5">

                    {{-- Card: Identitas Iklan --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-[#00509d]/10 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-identification-card text-[#00509d] text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Identitas Iklan</p>
                                <p class="text-[11px] text-slate-400">Judul dan tautan tujuan iklan</p>
                            </div>
                        </div>

                        {{-- Judul Iklan --}}
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Iklan <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul_iklan" value="{{ old('judul_iklan') }}" required
                                placeholder="Contoh: Bergabunglah Bersama AreaKerja!"
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition shadow-xs placeholder-slate-300">
                            @error('judul_iklan')
                                <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="ph ph-warning-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- URL Tujuan --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Link Tujuan Saat Banner Diklik <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="ph ph-link text-slate-400 text-sm"></i>
                                </div>
                                <input type="url" name="url_tujuan" value="{{ old('url_tujuan') }}"
                                    placeholder="https://areakerja.com/..."
                                    class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition shadow-xs placeholder-slate-300">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5">Pengunjung akan diarahkan ke URL ini saat mengklik banner. Kosongkan jika tidak ada tautan khusus.</p>
                            @error('url_tujuan')
                                <p class="text-rose-500 text-xs mt-1 flex items-center gap-1"><i class="ph ph-warning-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Card: Upload Banner --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-violet-50 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-image text-violet-500 text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Banner Iklan</p>
                                <p class="text-[11px] text-slate-400">Gambar yang tampil di halaman beranda</p>
                            </div>
                        </div>

                        <div x-data="{ hasImage: false, fileName: '' }">
                            {{-- Preview Area --}}
                            <div class="relative w-full h-44 rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 mb-4 group cursor-pointer transition hover:border-[#00509d]/40 hover:bg-blue-50/30"
                                 onclick="document.getElementById('uploadBanner').click()">
                                {{-- Placeholder state --}}
                                <div x-show="!hasImage" class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 group-hover:bg-[#00509d]/10 flex items-center justify-center transition">
                                        <i class="ph ph-image-square text-2xl text-slate-400 group-hover:text-[#00509d] transition"></i>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-slate-500 group-hover:text-[#00509d] transition">Klik untuk upload banner</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Rasio 3:1 direkomendasikan (misal 1200×400 px)</p>
                                    </div>
                                </div>
                                {{-- Image preview --}}
                                <img id="previewBannerImg" src="" alt="Preview Banner"
                                     x-show="hasImage"
                                     class="w-full h-full object-cover">
                                {{-- Overlay hint saat ada gambar --}}
                                <div x-show="hasImage" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                    <span class="text-white text-xs font-semibold flex items-center gap-1.5">
                                        <i class="ph ph-pencil-simple"></i> Ganti Gambar
                                    </span>
                                </div>
                            </div>

                            <input id="uploadBanner" type="file" name="gambar_banner" accept="image/png,image/jpeg,image/webp" hidden
                                   @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            hasImage = true;
                                            fileName = file.name;
                                            const reader = new FileReader();
                                            reader.onload = e => { document.getElementById('previewBannerImg').src = e.target.result; };
                                            reader.readAsDataURL(file);
                                        }
                                   ">

                            <div class="flex items-center justify-between">
                                <p class="text-[11px] text-slate-400">Format: PNG, JPG, WEBP. Maks 3 MB.</p>
                                <span x-show="hasImage" x-text="fileName" class="text-[11px] text-emerald-600 font-semibold truncate max-w-[180px]"></span>
                            </div>

                            @error('gambar_banner')
                                <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="ph ph-warning-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- ============ KOLOM KANAN (1/3) ============ --}}
                <div class="xl:col-span-1 space-y-5">

                    {{-- Card: Pemilik Iklan --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-buildings text-amber-500 text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Pemilik Iklan</p>
                                <p class="text-[11px] text-slate-400">Opsional — bisa internal</p>
                            </div>
                        </div>

                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Perusahaan / Pemilik</label>
                        <select name="perusahaan_id" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition shadow-xs">
                            <option value="">— Official AreaKerja (Internal) —</option>
                            @foreach ($perusahaans as $p)
                                <option value="{{ $p->id }}" {{ old('perusahaan_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                        @error('perusahaan_id')
                            <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror

                        {{-- Info badge --}}
                        <div class="mt-4 p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <p class="text-[11px] text-[#00509d] font-semibold flex items-start gap-1.5">
                                <i class="ph-fill ph-seal-check text-sm shrink-0 mt-0.5"></i>
                                <span>Biarkan kosong agar iklan tampil sebagai <b>Official AreaKerja</b> tanpa nama perusahaan.</span>
                            </p>
                        </div>
                    </div>

                    {{-- Card: Info Penayangan --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center gap-2.5 mb-4 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-broadcast text-[#00509d] text-base"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Info Penayangan</p>
                                <p class="text-[11px] text-slate-400">Pengaturan tayang banner</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <span class="text-xs text-slate-500 font-medium">Status Publikasi</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Langsung Aktif
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <span class="text-xs text-slate-500 font-medium">Posisi Banner</span>
                                <span class="text-xs font-semibold text-slate-800 flex items-center gap-1">
                                    <i class="ph-fill ph-slideshow text-[#00509d] text-sm"></i>
                                    Slider Beranda
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <span class="text-xs text-slate-500 font-medium">Target Audiens</span>
                                <span class="text-xs font-semibold text-slate-800 flex items-center gap-1">
                                    <i class="ph-fill ph-users-three text-amber-500 text-sm"></i>
                                    Semua Pengunjung
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-xs text-slate-500 font-medium">Tampilan</span>
                                <span class="text-xs font-semibold text-slate-700 flex items-center gap-1">
                                    <i class="ph-fill ph-devices text-purple-500 text-sm"></i>
                                    Desktop & Mobile
                                </span>
                            </div>
                        </div>

                        {{-- Catatan Penayangan --}}
                        <div class="mt-4 pt-3.5 border-t border-slate-100 space-y-2">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60 text-[11px] text-slate-600 space-y-1.5">
                                <div class="flex items-start gap-1.5 font-medium">
                                    <i class="ph-fill ph-check-circle text-emerald-500 text-sm shrink-0 mt-0.5"></i>
                                    <span>Banner berotasi secara otomatis di halaman utama.</span>
                                </div>
                                <div class="flex items-start gap-1.5 font-medium">
                                    <i class="ph-fill ph-check-circle text-emerald-500 text-sm shrink-0 mt-0.5"></i>
                                    <span>Klik banner akan membuka link tujuan (jika diisi).</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col gap-3">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-sm active:scale-[.98]">
                            <i class="ph ph-megaphone text-base"></i>
                            Pasang Iklan Sekarang
                        </button>
                        <a href="{{ route('admin.iklan.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-3 rounded-xl transition">
                            <i class="ph ph-arrow-left text-base"></i>
                            Kembali
                        </a>
                    </div>

                </div>
            </div>
        </form>

    </main>
@endsection
