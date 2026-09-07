@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">

            <h1 class="text-2xl font-medium">Buat Post Baru</h1>

            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative shrink-0">
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>

                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span> 
                    @endif
                </button>

                <div
                    class="flex items-center justify-between w-full md:w-90 h-14 bg-white border border-orange-500 shadow-md rounded-2xl px-3 py-2">

                    <div class="flex items-center gap-2">
                        <a href="{{ route('superadmin.profile') }}">
                            @if (Auth::user()->role == 'super_admin')
                                @if (Auth::user()->superadmin?->img_profile)
                                    <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                        src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}"
                                        alt="Profile">
                                @else
                                    <img id="pu" class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                        alt="">
                                @endif
                            @else
                                <img class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="">
                            @endif
                        </a>

                        <div class="text-sm max-w-[150px] md:max-w-none truncate">
                            <span class="font-semibold block truncate">{{ Auth::user()->username }}</span>
                            <p class="text-gray-500 text-sm truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                </div>
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
