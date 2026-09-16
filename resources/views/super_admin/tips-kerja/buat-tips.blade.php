@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.tips-kerja') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Tips Kerja / <span class="text-slate-500 font-medium">Buat Baru</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Buat Post Baru</h1>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        {{-- Form Content Card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
            <form action="{{ route('superadmin.tips-kerja.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <!-- Judul Artikel -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Judul Artikel <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" placeholder="Tulis judul artikel..."
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition"
                        required>
                </div>

                <!-- Cover Image -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Cover Image</label>
                    <input type="file" name="image" id="coverImageInput" accept="image/*"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00509d] hover:file:bg-blue-100 border border-slate-200 rounded-xl p-1 bg-white cursor-pointer transition">
                    <p class="text-[11px] text-slate-400 mt-1">Format gambar: JPG, PNG, WEBP. Maksimal 2MB.</p>
                </div>

                <!-- Isi Artikel -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Isi Artikel</label>
                    <textarea id="editor" name="content" class="w-full h-48 border border-slate-200 rounded-xl"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('superadmin.tips-kerja') }}"
                        class="inline-flex items-center justify-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold px-5 py-2.5 rounded-xl transition">
                        <i class="ph ph-x text-sm"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-6 py-2.5 rounded-xl shadow-sm transition">
                        <i class="ph ph-floppy-disk text-sm"></i>
                        Simpan Post
                    </button>
                </div>
            </form>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')

        <!-- TinyMCE -->
        <script src="https://cdn.tiny.cloud/1/oqx873eo8a4800gwchmdyn357lbg0rvj9bxkryttzmw9uf7q/tinymce/8/tinymce.min.js"
            referrerpolicy="origin"></script>

        <script>
            tinymce.init({
                selector: '#editor',
                height: 500,
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
