@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <!-- Topbar Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-6 bg-[#00509d] text-white p-3.5 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div>
                    <h1 class="text-base sm:text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                        <i class="ph ph-link text-white/80 text-xl sm:text-2xl"></i> Link & Image Header
                    </h1>
                    <p class="text-xs sm:text-sm text-white/90 mt-1">Kelola tautan media sosial resmi dan banner header halaman publik AreaKerja</p>
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

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-sm shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 text-sm shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm shadow-sm animate-fade-in">
                <p class="font-bold mb-1">Perhatian:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-8">
            <!-- Card 1: Kelola Media Sosial -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" id="social-card">
                <div class="p-5 sm:p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center shrink-0">
                            <i class="ph ph-share-network text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">Kelola Sosial Media Footer</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Tambah, ubah, hapus, dan atur ikon sosial media yang tampil di footer situs</p>
                        </div>
                    </div>
                    {{-- Preview footer --}}
                    <div class="flex items-center gap-2 shrink-0">
                        @foreach($socials as $s)
                            @if(!empty($s->link) && $s->link !== '#')
                                <span class="w-8 h-8 rounded-lg bg-[#00509d] text-white flex items-center justify-center text-sm" title="{{ $s->nama }}">
                                    <i class="{{ $s->icon ?? 'ph-fill ph-globe' }}"></i>
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('admin.social.config') }}" method="POST" id="social-form" class="p-5 sm:p-6">
                    @csrf

                    <div id="social-rows" class="space-y-3 mb-5">
                        @foreach($socials as $idx => $s)
                            <div class="social-row flex flex-col sm:flex-row items-start sm:items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                {{-- Icon Picker --}}
                                <div class="relative shrink-0">
                                    <button type="button"
                                        onclick="openIconPicker(this)"
                                        class="icon-pick-btn w-10 h-10 rounded-lg bg-[#00509d] text-white flex items-center justify-center hover:bg-[#003d7a] transition shrink-0"
                                        title="Klik untuk ganti ikon">
                                        <i class="{{ $s->icon ?? 'ph-fill ph-globe' }} text-lg"></i>
                                    </button>
                                    <input type="hidden" name="socials[{{ $idx }}][icon]" value="{{ $s->icon ?? 'ph-fill ph-globe' }}" class="icon-input">
                                </div>

                                {{-- Platform Name --}}
                                <input type="text"
                                    name="socials[{{ $idx }}][nama]"
                                    value="{{ old("socials.$idx.nama", $s->nama) }}"
                                    placeholder="Nama Platform"
                                    required
                                    class="w-full sm:w-36 px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition-all">

                                {{-- URL --}}
                                <input type="url"
                                    name="socials[{{ $idx }}][link]"
                                    value="{{ old("socials.$idx.link", $s->link) }}"
                                    placeholder="https://..."
                                    class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition-all">

                                {{-- Delete Row --}}
                                <button type="button"
                                    onclick="removeRow(this)"
                                    class="shrink-0 w-8 h-8 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition"
                                    title="Hapus">
                                    <i class="ph ph-trash text-sm"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    {{-- Add Row --}}
                    <button type="button" onclick="addRow()"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-dashed border-[#00509d] text-[#00509d] hover:bg-blue-50 text-sm font-medium transition mb-5">
                        <i class="ph ph-plus-circle text-base"></i>
                        Tambah Sosial Media
                    </button>

                    <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        {{-- Reset to default --}}
                        <form action="{{ route('admin.social.reset') }}" method="POST" onsubmit="return confirm('Reset semua ke bawaan?')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-500 text-sm font-medium transition">
                                <i class="ph ph-arrow-counter-clockwise text-base"></i>
                                Reset ke Bawaan
                            </button>
                        </form>

                        <button type="submit" form="social-form"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-sm transition-all duration-200 shadow-sm hover:shadow">
                            <i class="ph ph-floppy-disk text-base"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Icon Picker Modal --}}
            <div id="icon-picker-modal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden"
                onclick="closeIconPicker(event)">
                <div class="bg-white rounded-2xl shadow-xl p-5 w-80 max-w-[95vw]" onclick="event.stopPropagation()">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-800">Pilih Ikon Platform</h3>
                        <button onclick="closeIconPicker()" class="text-gray-400 hover:text-gray-600 transition">
                            <i class="ph ph-x text-lg"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach(\App\Services\SiteSettingService::getAvailableIcons() as $iconClass => $iconLabel)
                            <button type="button"
                                onclick="selectIcon('{{ $iconClass }}')"
                                title="{{ $iconLabel }}"
                                class="icon-option-btn flex flex-col items-center gap-1 p-2 rounded-xl border border-gray-100 hover:border-[#00509d] hover:bg-blue-50 transition group">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-[#00509d] group-hover:text-white text-gray-600 flex items-center justify-center text-lg transition">
                                    <i class="{{ $iconClass }}"></i>
                                </span>
                                <span class="text-[10px] text-gray-500 text-center leading-tight">{{ $iconLabel }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Card 2: Image Header -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Banner Image Header</h2>
                        <p class="text-xs text-gray-500">Kustomisasi gambar banner utama untuk berbagai halaman publik di AreaKerja</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($headers as $header)
                            @php
                                $title = $header->title ?? ucwords(str_replace(['header_', '_'], ['', ' '], $header->nama));
                                $deskripsi = $header->deskripsi ?? 'Banner hero halaman';
                                $current = \App\Services\SiteSettingService::getHeaderUrl($header->nama, $header->default ?? 'images/logoarea.png');
                            @endphp

                            <div class="bg-gray-50/70 rounded-2xl border border-gray-200 overflow-hidden flex flex-col justify-between hover:border-gray-300 transition-all duration-200">
                                <form action="{{ route('admin.header.update', $header->nama) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    onsubmit="return handleHeaderSubmit(event, this, '{{ addslashes($title) }}')"
                                    class="p-5 flex flex-col h-full">
                                    @csrf
                                    @method('PUT')

                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 bg-white px-2.5 py-1 rounded-lg border border-gray-100 shadow-2xs">
                                                Header Banner
                                            </span>
                                            @if (!empty($header->link))
                                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                    Kustom Aktif
                                                </span>
                                            @else
                                                <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                                    Default
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-400">1200 x 400</span>
                                    </div>

                                    <div class="mb-3">
                                        <h3 class="text-base font-bold text-gray-900 leading-snug">{{ $title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-1" title="{{ $deskripsi }}">{{ $deskripsi }}</p>
                                    </div>

                                    <!-- Image Preview Box -->
                                    <div class="relative w-full h-44 rounded-xl overflow-hidden bg-gray-200 border border-gray-200 mb-4 group shadow-inner">
                                        <img id="preview_{{ $header->id }}"
                                            src="{{ $current }}"
                                            alt="{{ $title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">
                                            <span>Pratinjau Banner</span>
                                        </div>
                                    </div>

                                    <!-- File Upload Input Custom -->
                                    <div class="mt-auto space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Gambar Baru</label>
                                            <label class="cursor-pointer block group">
                                                <input type="file"
                                                    name="image"
                                                    accept="image/*"
                                                    onchange="handleHeaderFile(this, 'preview_{{ $header->id }}', 'filename_{{ $header->id }}')"
                                                    class="hidden">
                                                <div class="flex items-center gap-2.5 px-3 py-2 bg-white border border-gray-200 rounded-xl group-hover:border-[#00509d] group-hover:bg-blue-50/40 transition-all duration-200 shadow-2xs">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#00509d] text-white text-xs font-semibold rounded-lg shadow-sm group-hover:bg-[#003d7a] transition shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                        Pilih File
                                                    </span>
                                                    <span id="filename_{{ $header->id }}" class="text-xs text-gray-500 truncate select-none">
                                                        Belum ada berkas dipilih
                                                    </span>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button type="submit"
                                                class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-sm transition-all duration-200 shadow-sm hover:shadow">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>Simpan Header</span>
                                            </button>
                                            @if (!empty($header->link))
                                                <button type="button"
                                                    onclick="confirmResetHeader('{{ $header->id }}', '{{ addslashes($title) }}')"
                                                    class="inline-flex items-center justify-center px-3 py-2.5 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 transition"
                                                    title="Kembalikan ke gambar default">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                @if (!empty($header->link))
                                    <form id="reset-header-{{ $header->id }}"
                                        action="{{ route('admin.header.destroy', $header->nama) }}"
                                        method="POST"
                                        class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>

    <script>
        function handleHeaderFile(input, previewId, filenameId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const filenameEl = document.getElementById(filenameId);

            if (file) {
                if (!file.type.startsWith('image/')) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format Tidak Sesuai',
                            html: 'Harap pilih berkas berupa gambar dengan format <b>JPG, JPEG, PNG, WEBP, atau GIF</b>.',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#00509d',
                            customClass: {
                                popup: '!rounded-2xl !p-6 shadow-2xl border border-slate-100',
                                title: '!text-base font-bold text-slate-800 !pt-1',
                                htmlContainer: '!text-xs text-slate-600 !mt-2 leading-relaxed',
                                confirmButton: '!px-5 !py-2.5 !rounded-xl !text-xs !font-semibold transition'
                            }
                        });
                    } else {
                        alert('Format berkas harus berupa gambar.');
                    }
                    input.value = '';
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ukuran Terlalu Besar',
                            html: 'Ukuran gambar maksimal adalah <b>5MB</b>. Silakan gunakan berkas dengan resolusi lebih optimal.',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#00509d',
                            customClass: {
                                popup: '!rounded-2xl !p-6 shadow-2xl border border-slate-100',
                                title: '!text-base font-bold text-slate-800 !pt-1',
                                htmlContainer: '!text-xs text-slate-600 !mt-2 leading-relaxed',
                                confirmButton: '!px-5 !py-2.5 !rounded-xl !text-xs !font-semibold transition'
                            }
                        });
                    } else {
                        alert('Ukuran berkas melebihi 5MB.');
                    }
                    input.value = '';
                    return;
                }

                if (filenameEl) {
                    filenameEl.textContent = file.name;
                    filenameEl.classList.remove('text-gray-500');
                    filenameEl.classList.add('text-gray-900', 'font-medium');
                }
                const reader = new FileReader();
                reader.onload = e => {
                    if (preview) preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function handleHeaderSubmit(event, form, title) {
            const fileInput = form.querySelector('input[type="file"][name="image"]');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                event.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pilih Berkas Dulu',
                        html: `Silakan klik tombol <b>Pilih File</b> untuk memilih gambar banner <b>${title}</b> sebelum menekan Simpan.`,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#00509d',
                        customClass: {
                            popup: '!rounded-2xl !p-6 shadow-2xl border border-slate-100',
                            title: '!text-base font-bold text-slate-800 !pt-1',
                            htmlContainer: '!text-xs text-slate-600 !mt-2 leading-relaxed',
                            confirmButton: '!px-5 !py-2.5 !rounded-xl !text-xs !font-semibold transition'
                        }
                    });
                }
                return false;
            }
        }

        function confirmResetHeader(id, title) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Kembalikan ke Default?',
                    html: `Apakah Anda yakin ingin mengembalikan banner <b>${title}</b> ke gambar bawaan (*default*)? Banner kustom saat ini akan dihapus.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kembalikan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true,
                    customClass: {
                        popup: '!rounded-2xl !p-6 shadow-2xl border border-slate-100',
                        title: '!text-base font-bold text-slate-800 !pt-1',
                        htmlContainer: '!text-xs text-slate-600 !mt-2 leading-relaxed',
                        confirmButton: '!px-4 !py-2.5 !rounded-xl !text-xs !font-semibold transition',
                        cancelButton: '!px-4 !py-2.5 !rounded-xl !text-xs !font-semibold transition'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('reset-header-' + id);
                        if (form) form.submit();
                    }
                });
            } else {
                if (confirm(`Kembalikan header ${title} ke gambar default?`)) {
                    const form = document.getElementById('reset-header-' + id);
                    if (form) form.submit();
                }
            }
        }
    </script>

    {{-- Social Media Management JS --}}
    <script>
        let _activeIconBtn = null;

        function openIconPicker(btn) {
            _activeIconBtn = btn;
            document.getElementById('icon-picker-modal').classList.remove('hidden');
        }

        function closeIconPicker(e) {
            document.getElementById('icon-picker-modal').classList.add('hidden');
        }

        function selectIcon(iconClass) {
            if (!_activeIconBtn) return;
            const icon = _activeIconBtn.querySelector('i');
            if (icon) { icon.className = iconClass + ' text-lg'; }
            const row = _activeIconBtn.closest('.social-row');
            if (row) {
                const inp = row.querySelector('.icon-input');
                if (inp) inp.value = iconClass;
            }
            closeIconPicker();
        }

        function addRow() {
            const container = document.getElementById('social-rows');
            const idx = container.querySelectorAll('.social-row').length;
            const html = `
                <div class="social-row flex flex-col sm:flex-row items-start sm:items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="relative shrink-0">
                        <button type="button"
                            onclick="openIconPicker(this)"
                            class="icon-pick-btn w-10 h-10 rounded-lg bg-[#00509d] text-white flex items-center justify-center hover:bg-[#003d7a] transition shrink-0"
                            title="Klik untuk ganti ikon">
                            <i class="ph-fill ph-globe text-lg"></i>
                        </button>
                        <input type="hidden" name="socials[${idx}][icon]" value="ph-fill ph-globe" class="icon-input">
                    </div>
                    <input type="text" name="socials[${idx}][nama]" placeholder="Nama Platform" required
                        class="w-full sm:w-36 px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition-all">
                    <input type="url" name="socials[${idx}][link]" placeholder="https://..."
                        class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition-all">
                    <button type="button" onclick="removeRow(this)"
                        class="shrink-0 w-8 h-8 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition" title="Hapus">
                        <i class="ph ph-trash text-sm"></i>
                    </button>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            reindexRows();
        }

        function removeRow(btn) {
            const row = btn.closest('.social-row');
            if (document.querySelectorAll('.social-row').length <= 1) {
                alert('Minimal harus ada 1 sosial media.');
                return;
            }
            row.remove();
            reindexRows();
        }

        function reindexRows() {
            document.querySelectorAll('.social-row').forEach((row, i) => {
                row.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/socials\[\d+\]/, `socials[${i}]`);
                });
            });
        }
    </script>
@endsection
