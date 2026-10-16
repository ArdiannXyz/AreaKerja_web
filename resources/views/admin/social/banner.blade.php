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
            <!-- Card 1: Media Sosial -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Tautan Media Sosial</h2>
                        <p class="text-xs text-gray-500">Tautan akun resmi yang akan ditampilkan pada footer situs dan aplikasi AreaKerja</p>
                    </div>
                </div>

                <form action="{{ route('admin.social.update') }}" method="post" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        @foreach ($socials as $s)
                            @php
                                $lowerName = strtolower($s->nama);
                                $bgColor = 'bg-gray-100 text-gray-600';
                                $borderColor = 'border-gray-200';

                                if (str_contains($lowerName, 'facebook')) {
                                    $bgColor = 'bg-blue-50 text-blue-600';
                                    $iconSvg = '<path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>';
                                } elseif (str_contains($lowerName, 'youtube')) {
                                    $bgColor = 'bg-red-50 text-red-600';
                                    $iconSvg = '<path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.43z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="currentColor"/>';
                                } elseif (str_contains($lowerName, 'instagram')) {
                                    $bgColor = 'bg-pink-50 text-pink-600';
                                    $iconSvg = '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>';
                                } elseif (str_contains($lowerName, 'linkedin')) {
                                    $bgColor = 'bg-sky-50 text-sky-700';
                                    $iconSvg = '<path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>';
                                } else {
                                    $bgColor = 'bg-slate-100 text-slate-800';
                                    $iconSvg = '<path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>';
                                }
                            @endphp

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg {{ $bgColor }} flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            {!! $iconSvg !!}
                                        </svg>
                                    </span>
                                    <span>{{ $s->nama }}</span>
                                </label>

                                <div class="relative">
                                    <input type="url"
                                        name="links[{{ $s->id }}]"
                                        value="{{ old('links.' . $s->id, $s->link) }}"
                                        placeholder="https://{{ $lowerName }}.com/areakerja"
                                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition-all">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-sm transition-all duration-200 shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Tautan</span>
                        </button>
                    </div>
                </form>
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
@endsection
