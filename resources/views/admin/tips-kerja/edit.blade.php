@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tips-kerja') }}"
                   class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-base"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Tips Kerja / <span class="text-slate-600 font-semibold">Edit Artikel</span></p>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-tight">Edit Post Tips Kerja</h1>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Errors & Alert -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl shadow-xs">
                <div class="flex items-center gap-2 mb-1.5 font-bold text-sm">
                    <i class="ph ph-warning-circle text-lg"></i>
                    <span>Mohon periksa kesalahan berikut:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 font-medium pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl shadow-xs flex items-center gap-2 text-xs font-semibold">
                <i class="ph ph-warning-circle text-lg shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Content Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8 max-w-4xl mx-auto">
            <form action="{{ route('admin.tips-kerja.update', $tips->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Judul Artikel -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Judul Artikel <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $tips->title) }}" placeholder="Tulis judul artikel yang menarik..."
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition font-medium"
                        required>
                </div>

                <!-- Grid: Kategori & Status Publikasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kategori Artikel
                        </label>
                        <select name="kategori"
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition font-medium">
                            <option value="Tips Kerja" {{ old('kategori', $tips->kategori ?? '') == 'Tips Kerja' ? 'selected' : '' }}>Tips Kerja</option>
                            <option value="Interview & Gaji" {{ old('kategori', $tips->kategori ?? '') == 'Interview & Gaji' ? 'selected' : '' }}>Interview & Gaji</option>
                            <option value="CV & Lamaran" {{ old('kategori', $tips->kategori ?? '') == 'CV & Lamaran' ? 'selected' : '' }}>CV & Lamaran</option>
                            <option value="Top News" {{ old('kategori', $tips->kategori ?? '') == 'Top News' ? 'selected' : '' }}>Top News</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Status Publikasi
                        </label>
                        <select name="status"
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition font-medium">
                            <option value="belum terbit" {{ old('status', $tips->status) == 'belum terbit' ? 'selected' : '' }}>Draf / Belum Terbit</option>
                            <option value="terbit" {{ old('status', $tips->status) == 'terbit' ? 'selected' : '' }}>Langsung Diterbitkan</option>
                        </select>
                    </div>
                </div>

                <!-- Cover Image -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Cover File / Image <span class="text-slate-400 font-normal normal-case text-xs">(Opsional)</span>
                    </label>

                    @if ($tips->image)
                        <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                            @if(in_array(pathinfo($tips->image, PATHINFO_EXTENSION), ['jpg','jpeg','png','webp']))
                                <img src="{{ asset('storage/' . $tips->image) }}" alt="Cover" class="w-16 h-12 object-cover rounded-lg border border-slate-200 shadow-xs">
                            @else
                                <div class="w-12 h-12 bg-blue-50 text-[#00509d] flex items-center justify-center rounded-lg">
                                    <i class="ph ph-file-text text-xl"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-700 truncate">File Saat Ini</p>
                                <a href="{{ asset('storage/' . $tips->image) }}" target="_blank"
                                   class="text-[11px] font-semibold text-[#00509d] hover:underline flex items-center gap-1 mt-0.5">
                                    <i class="ph ph-arrow-square-out"></i> Lihat File
                                </a>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="image" id="coverImageInput" accept=".png,.jpg,.jpeg,.webp,.pdf"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00509d] hover:file:bg-blue-100 border border-slate-200 rounded-xl p-1 bg-white cursor-pointer transition">
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Kosongkan jika tidak ingin mengubah cover. Format: PNG, JPG, JPEG, WEBP, atau PDF. Maks 10MB.</p>
                </div>

                <!-- Isi Artikel -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Isi Artikel <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="editor" name="content" class="w-full border border-slate-200 rounded-xl">{{ old('content', $tips->content) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.tips-kerja') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold px-5 py-2.5 rounded-xl transition">
                        <i class="ph ph-x text-sm"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-6 py-2.5 rounded-xl shadow-xs transition">
                        <i class="ph ph-floppy-disk text-sm"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')

        <!-- TinyMCE -->
        <script src="https://cdn.tiny.cloud/1/oqx873eo8a4800gwchmdyn357lbg0rvj9bxkryttzmw9uf7q/tinymce/8/tinymce.min.js"
            referrerpolicy="origin"></script>

        <script>
            tinymce.init({
                selector: '#editor',
                height: 480,
                menubar: false,
                plugins: 'lists link image media code fullscreen mentions',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link image media | code fullscreen',

                setup: function(editor) {
                    editor.ui.registry.addAutocompleter("usermentions", {
                        trigger: '@',
                        minChars: 1,
                        fetch: async function(pattern) {
                            const res = await fetch("/tinymce-mention?q=" + pattern);
                            const users = await res.json();
                            return users.map(user => ({
                                value: user.name,
                                text: user.name
                            }));
                        },
                        onAction: function(api, rng, value) {
                            editor.selection.setRng(rng);
                            editor.insertContent(`<span class="mention">@${value}</span>&nbsp;`);
                            api.hide();
                        }
                    });
                },

                images_upload_handler: function(blobInfo, progress) {
                    return new Promise(function(resolve, reject) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route('tinymce.upload') }}');
                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                        xhr.upload.onprogress = function(e) {
                            progress(e.loaded / e.total * 100);
                        };

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                const json = JSON.parse(xhr.responseText);
                                resolve(json.location);
                            } else {
                                reject('HTTP Error: ' + xhr.status);
                            }
                        };

                        const formData = new FormData();
                        formData.append('file', blobInfo.blob());
                        xhr.send(formData);
                    });
                }
            });
        </script>
    </main>
@endsection
