@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-gray-50 overflow-y-auto min-h-screen" x-data="{ openNotif: false }">
        <!-- Topbar Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Link & Image Header</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola tautan media sosial resmi dan banner header halaman publik AreaKerja</p>
            </div>

            <div class="flex items-center gap-3 self-end md:self-auto">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-sm shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
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

                <form action="{{ route('superadmin.social.update') }}" method="post" class="p-6">
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
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        @foreach ($headers as $header)
                            @php
                                $title = ucwords(str_replace(['header_', '_'], ['', ' '], $header->nama));
                                $current = !empty($header->link)
                                    ? asset('storage/' . $header->link)
                                    : asset($header->default ?? 'images/logoarea.png');
                            @endphp

                            <div class="bg-gray-50/70 rounded-2xl border border-gray-200 overflow-hidden flex flex-col justify-between hover:border-gray-300 transition-all duration-200">
                                <form action="{{ route('superadmin.header.update', $header->nama) }}" 
                                    method="POST"
                                    enctype="multipart/form-data" 
                                    class="p-5 flex flex-col h-full">
                                    @csrf
                                    @method('PUT')

                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 bg-white px-2.5 py-1 rounded-lg border border-gray-100 shadow-2xs">
                                            Header Banner
                                        </span>
                                        <span class="text-xs text-gray-400">1200 x 400</span>
                                    </div>

                                    <h3 class="text-base font-bold text-gray-900 mb-3">{{ $title }}</h3>

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

                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-sm transition-all duration-200 shadow-sm hover:shadow">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Simpan Header</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

    <script>
        function handleHeaderFile(input, previewId, filenameId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const filenameEl = document.getElementById(filenameId);

            if (file) {
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
    </script>
@endsection

