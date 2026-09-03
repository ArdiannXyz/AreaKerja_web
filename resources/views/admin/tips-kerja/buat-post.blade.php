@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64">
        <div class="mx-auto p-6 max-w-3xl w-full">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
                        <span class="font-bold">Mohon perbaiki kesalahan berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.tips-kerja.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Judul -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Tulis judul artikel..."
                        class="w-full border-2 @error('title') border-red-500 @else border-gray-400 @enderror rounded-lg px-3 py-2 text-base break-words">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Cover File / Image <span class="text-xs text-gray-500 font-normal">(PNG, JPG, JPEG, WEBP, PDF - maks 10MB)</span></label>
                    <input type="file" name="image" accept=".png,.jpg,.jpeg,.webp,.pdf"
                        class="w-full border-2 @error('image') border-red-500 @else border-gray-400 @enderror rounded-lg px-3 py-2 text-base">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Status Publikasi</label>
                    <select name="status" class="w-full border-2 border-gray-400 rounded-lg px-3 py-2 text-base">
                        <option value="belum terbit" {{ old('status') == 'belum terbit' ? 'selected' : '' }}>Draf / Belum Terbit</option>
                        <option value="terbit" {{ old('status') == 'terbit' ? 'selected' : '' }}>Langsung Diterbitkan</option>
                    </select>
                </div>

                <!-- Kategori Artikel -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Kategori Artikel</label>
                    <select name="kategori" class="w-full border-2 border-gray-400 rounded-lg px-3 py-2 text-base">
                        <option value="Tips Kerja" {{ old('kategori') == 'Tips Kerja' ? 'selected' : '' }}>Tips Kerja</option>
                        <option value="Interview & Gaji" {{ old('kategori') == 'Interview & Gaji' ? 'selected' : '' }}>Interview & Gaji</option>
                        <option value="CV & Lamaran" {{ old('kategori') == 'CV & Lamaran' ? 'selected' : '' }}>CV & Lamaran</option>
                        <option value="Top News" {{ old('kategori') == 'Top News' ? 'selected' : '' }}>Top News</option>
                    </select>
                </div>

                <!-- Content -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Isi Artikel <span class="text-red-500">*</span></label>
                    <textarea id="editor" name="content" class="w-full h-48 border @error('content') border-red-500 @else border-gray-400 @enderror rounded-lg break-words">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

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
                                fetch: async function(pattern, maxResults) {
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

                        // Upload gambar
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

                <!-- Button -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow w-full sm:w-auto">
                        Simpan
                    </button>

                    <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg shadow w-full sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
