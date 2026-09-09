@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">

            <h1 class="text-2xl font-medium">Buat Post Baru</h1>

            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">

                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>


        {{-- content --}}
        <div class="mx-auto p-6 max-w-4xl">

            <form action="{{ route('superadmin.tips-kerja.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <label class="block mb-2 text-lg font-medium">Judul Artikel</label>
                    <input type="text" name="title" placeholder="Tulis judul artikel..."
                        class="w-full border-2 border-gray-400 rounded-lg px-3 py-2 break-words" required>
                </div>

                <div>
                    <label class="block mb-2 text-lg font-medium">Cover Image</label>
                    <input type="file" name="image"
                        class="w-full border-2 border-gray-400 rounded-lg px-3 py-2 break-all">
                </div>

                <div>
                    <label class="block mb-2 text-lg font-medium">Isi Artikel</label>
                    <textarea id="editor" name="content" class="w-full h-48 border border-gray-400 rounded-lg break-words"></textarea>
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

                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-4">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow w-full sm:w-auto">
                        Simpan
                    </button>
                    <a href="{{ route('superadmin.tips-kerja') }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg shadow w-full sm:w-auto text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Script inject gambar ke Trix --}}
        <script>
            document.getElementById("uploadMedia").addEventListener("change", function(e) {
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        const trixEditor = document.querySelector("trix-editor");
                        trixEditor.editor.insertHTML(`<img src="${event.target.result}" class="my-3">`);
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
