@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div>
                    <h1 class="text-base sm:text-xl font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="ph ph-megaphone text-[#00509d] text-lg sm:text-2xl"></i> Pasang Banner Iklan
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Upload banner promosi official atau mitra perusahaan langsung tayang di beranda tanpa biaya</p>
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

        <!-- BREADCRUMB & BACK -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.iklan.index') }}"
                class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 hover:text-[#00509d] transition">
                <i class="ph ph-arrow-left font-bold"></i>
                <span>Kembali ke Moderasi Iklan</span>
            </a>

            <!-- FREE BADGE -->
            <div class="flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs font-bold text-emerald-700 shadow-2xs">
                <i class="ph-fill ph-tag text-emerald-600"></i>
                <span>Biaya: 0 Koin (Gratis)</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 shadow-2xs">
                <div class="flex items-center gap-2 font-bold text-sm mb-1">
                    <i class="ph-fill ph-warning-circle text-lg text-rose-600"></i>
                    <span>Terdapat kesalahan pada isian form:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700 pl-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM UPLOAD BANNER IKLAN -->
        <form action="{{ route('admin.iklan.store') }}" method="POST" enctype="multipart/form-data" id="formUploadIklanAdmin">
            @csrf

            <div class="space-y-6">

                <!-- INFORMASI KAMPANYE IKLAN -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-black text-sm">
                            1
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Detail Kampanye Iklan</h3>
                            <p class="text-xs text-slate-500 font-medium">Informasi nama kampanye, URL tujuan, serta pihak pemilik banner</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Judul Iklan -->
                        <div class="md:col-span-2">
                            <label for="judul_iklan" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                Judul / Nama Kampanye Iklan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="judul_iklan" id="judul_iklan"
                                value="{{ old('judul_iklan') }}"
                                required
                                placeholder="Contoh: Promo Job Fair Nasional 2026 / Diskon Hiring Mitra"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 transition outline-none">
                            <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Judul ini akan menjadi alt-text banner dan judul tampilan di slider.</p>
                        </div>

                        <!-- URL Tujuan -->
                        <div>
                            <label for="url_tujuan" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                URL / Link Tujuan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="ph ph-link text-base"></i>
                                </span>
                                <input type="url" name="url_tujuan" id="url_tujuan"
                                    value="{{ old('url_tujuan') }}"
                                    required
                                    placeholder="https://example.com/promo-atau-event"
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 transition outline-none">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Halaman yang akan terbuka saat pengunjung mengklik banner di Beranda.</p>
                        </div>

                        <!-- Perusahaan Asosiasi (Opsional) -->
                        <div>
                            <label for="perusahaan_id" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                Perusahaan Asosiasi (Opsional)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="ph ph-buildings text-base"></i>
                                </span>
                                <select name="perusahaan_id" id="perusahaan_id"
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 transition outline-none">
                                    <option value="">-- Official / Internal Area Kerja (Tanpa Perusahaan) --</option>
                                    @foreach($perusahaans as $p)
                                        <option value="{{ $p->id }}" {{ old('perusahaan_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_perusahaan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Biarkan default untuk badge Official Area Kerja, atau pilih mitra perusahaan.</p>
                        </div>
                    </div>
                </div>

                <!-- DURASI PENAYANGAN -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-black text-sm">
                            2
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Durasi Masa Tayang</h3>
                            <p class="text-xs text-slate-500 font-medium">Tentukan lama banner iklan akan aktif ditampilkan di beranda</p>
                        </div>
                    </div>

                    <!-- Preset Durasi Pills -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3" id="presetDurasiGrid">
                        @php
                            $presets = [
                                7   => '7 Hari (1 Mgg)',
                                14  => '14 Hari (2 Mgg)',
                                30  => '30 Hari (1 Bln)',
                                90  => '90 Hari (3 Bln)',
                                365 => '365 Hari (1 Thn)',
                            ];
                            $selectedDurasi = old('durasi_hari', 30);
                        @endphp
                        @foreach($presets as $days => $label)
                            <button type="button" onclick="setDurasi({{ $days }})"
                                class="durasi-preset-btn py-3 px-3 rounded-2xl border-2 text-center transition font-bold text-xs {{ $selectedDurasi == $days ? 'border-[#00509d] bg-blue-50/50 text-[#00509d] shadow-2xs' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white' }}"
                                data-days="{{ $days }}">
                                <i class="ph ph-clock text-base block mx-auto mb-1"></i>
                                <span>{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="pt-3 flex flex-col sm:flex-row sm:items-center gap-3">
                        <label for="durasi_hari" class="text-xs font-bold text-slate-600 whitespace-nowrap">
                            Atau input durasi kustom (hari):
                        </label>
                        <div class="w-36">
                            <input type="number" name="durasi_hari" id="durasi_hari"
                                value="{{ $selectedDurasi }}"
                                min="1" max="3650" required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-800 text-center focus:bg-white focus:border-[#00509d] outline-none">
                        </div>
                        <span class="text-xs text-slate-400 font-medium">hari kalender (otomatis non-aktif setelah masa tayang usai)</span>
                    </div>
                </div>

                <!-- UPLOAD BANNER GAMBAR -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-black text-sm">
                            3
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Upload Banner Desain</h3>
                            <p class="text-xs text-slate-500 font-medium">Unggah materi banner dengan orientasi horizontal (rekomendasi: 1200x400 px atau rasio 3:1)</p>
                        </div>
                    </div>

                    <!-- Live Banner Preview Box -->
                    <div id="previewContainer" class="hidden mb-4 relative rounded-2xl overflow-hidden border-2 border-slate-200 bg-slate-900 group shadow-md">
                        <img id="bannerImgPreview" src="" alt="Preview Banner" class="w-full h-auto object-cover max-h-[300px]">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                            <button type="button" onclick="document.getElementById('gambar_banner').click()"
                                class="px-4 py-2 bg-white/90 hover:bg-white text-slate-800 text-xs font-bold rounded-xl shadow-xs transition">
                                Ganti Banner
                            </button>
                            <button type="button" onclick="removeBanner()"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                Hapus
                            </button>
                        </div>
                    </div>

                    <!-- Dropzone Area -->
                    <div id="dropzoneArea"
                        onclick="document.getElementById('gambar_banner').click()"
                        class="border-2 border-dashed border-slate-300 hover:border-[#00509d] hover:bg-blue-50/20 rounded-2xl p-8 sm:p-12 text-center cursor-pointer transition flex flex-col items-center justify-center group">
                        
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 group-hover:bg-blue-100 text-[#00509d] flex items-center justify-center text-3xl mb-3 transition group-hover:scale-105">
                            <i class="ph ph-image"></i>
                        </div>
                        <h4 class="text-sm font-extrabold text-slate-800 group-hover:text-[#00509d] transition">
                            Klik untuk memilih file banner atau seret ke sini
                        </h4>
                        <p class="text-xs text-slate-400 mt-1 font-medium">
                            Mendukung JPG, JPEG, PNG, WEBP, GIF (Maksimal 5MB)
                        </p>
                        <span class="mt-4 px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">
                            Rekomendasi Rasio: 3:1 (1200 x 400 px)
                        </span>

                        <input type="file" name="gambar_banner" id="gambar_banner"
                            accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                            class="hidden" required onchange="handleFileSelect(this)">
                    </div>
                </div>

                <!-- SUMMARY CARD & SUBMIT BUTTON -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="space-y-1.5">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-extrabold uppercase tracking-wider">
                            <i class="ph-fill ph-seal-check"></i>
                            <span>Admin Privilege: Langsung Aktif</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-black">Tayangkan Iklan Tanpa Biaya</h3>
                        <p class="text-xs text-slate-300 max-w-xl font-medium">
                            Banner akan langsung tayang pada slider Beranda Area Kerja seketika setelah disimpan, dengan biaya 0 Koin.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('admin.iklan.index') }}"
                            class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmitIklan"
                            class="px-6 py-3 rounded-xl bg-[#00509d] hover:bg-blue-600 text-white text-xs font-black shadow-lg hover:shadow-blue-500/25 transition flex items-center gap-2">
                            <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                            <span>Tayangkan Sekarang</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </main>

    <script>
        function setDurasi(days) {
            document.getElementById('durasi_hari').value = days;
            document.querySelectorAll('.durasi-preset-btn').forEach(btn => {
                if (btn.getAttribute('data-days') == days) {
                    btn.classList.add('border-[#00509d]', 'bg-blue-50/50', 'text-[#00509d]', 'shadow-2xs');
                    btn.classList.remove('border-slate-200', 'text-slate-700', 'bg-white');
                } else {
                    btn.classList.remove('border-[#00509d]', 'bg-blue-50/50', 'text-[#00509d]', 'shadow-2xs');
                    btn.classList.add('border-slate-200', 'text-slate-700', 'bg-white');
                }
            });
        }

        document.getElementById('durasi_hari').addEventListener('input', function() {
            const val = this.value;
            document.querySelectorAll('.durasi-preset-btn').forEach(btn => {
                if (btn.getAttribute('data-days') == val) {
                    btn.classList.add('border-[#00509d]', 'bg-blue-50/50', 'text-[#00509d]', 'shadow-2xs');
                    btn.classList.remove('border-slate-200', 'text-slate-700', 'bg-white');
                } else {
                    btn.classList.remove('border-[#00509d]', 'bg-blue-50/50', 'text-[#00509d]', 'shadow-2xs');
                    btn.classList.add('border-slate-200', 'text-slate-700', 'bg-white');
                }
            });
        });

        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal adalah 5MB.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('bannerImgPreview').src = e.target.result;
                    document.getElementById('previewContainer').classList.remove('hidden');
                    document.getElementById('dropzoneArea').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeBanner() {
            document.getElementById('gambar_banner').value = '';
            document.getElementById('bannerImgPreview').src = '';
            document.getElementById('previewContainer').classList.add('hidden');
            document.getElementById('dropzoneArea').classList.remove('hidden');
        }
    </script>
@endsection

