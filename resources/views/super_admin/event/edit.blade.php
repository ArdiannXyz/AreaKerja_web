@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    {{-- <div class="p-4 sm:ml-64"> --}}
    <div class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openModal: false }">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-10 gap-3">

            <h1 class="text-2xl font-medium break-words">
                Edit Event
            </h1>

        </div>


        <!-- Container Responsif -->
        <div class="w-full bg-white p-4 md:p-6">

            <form action="{{ route('superadmin.event.update', $event->id) }}" method="post" enctype="multipart/form-data"
                class="w-full max-w-full">

                @csrf
                @method('PUT')

                <!-- Judul -->
                <input type="text" placeholder="Masukkan judul event" name="title"
                    value="{{ old('title', $event->title) }}"
                    class="w-full bg-gray-200 border-2 border-gray-400 rounded-md 
                      px-4 py-2 mb-8 break-words max-w-full">

                <!-- Upload Media -->
                <div class="mb-4">
                    <p class="mb-2 break-words">Gambar Saat Ini:</p>

                    <img id="preview-image"
                        src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/150x150?text=No+Image' }}"
                        class="w-full max-w-xs h-40 object-cover rounded-md mb-2">

                    <label for="uploadMedia"
                        class="cursor-pointer px-4 py-2 bg-gray-100 border-2 border-gray-400 
                       rounded-lg shadow hover:bg-gray-200 text-sm font-medium inline-block">
                        Ganti Media
                    </label>

                    <input id="uploadMedia" type="file" name="image" accept="image/*" hidden>
                </div>

                <!-- Editor -->
                <div class="rounded-md overflow-hidden mt-4 w-full">
                    <label class="block mb-2 text-lg font-medium break-words">Isi Artikel</label>

                    <textarea id="editor" name="content" class="w-full h-48 border border-gray-400 rounded-lg break-words">
                {{ old('content', $event->content ?? '') }}
            </textarea>
                </div>


                <div class="space-y-4 mt-4">
                    <!-- Waktu Acara -->
                    <div>
                        <label class="block font-medium mb-1 break-words">Waktu Acara</label>

                        <div class="flex flex-wrap items-center gap-3 md:gap-2">

                            <!-- Tanggal Mulai -->
                            <input type="date" name="tgl_mulai" id="tgl_mulai"
                                class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm
                       w-full sm:w-auto sm:min-w-[140px] break-words"
                                value="{{ old('tgl_mulai', $event->tgl_mulai) }}">

                            <!-- Tanggal Akhir -->
                            <input type="date" name="tgl_akhir" id="tgl_akhir"
                                class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm
                       w-full sm:w-auto sm:min-w-[140px] break-words"
                                value="{{ old('tgl_akhir', $event->tgl_akhir) }}">

                            <!-- Jam Mulai -->
                            <div class="relative w-full sm:w-32 min-w-0">
                                <input type="time" name="jam_mulai" id="jam_mulai"
                                    class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full
                           focus:border-blue-700 focus:ring-0 break-words"
                                    value="{{ old('jam_mulai', $event->jam_mulai) }}">

                                <!-- Fake Placeholder -->
                                <span id="ph_mulai"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none">
                                    12:00 PM
                                </span>
                            </div>

                            <span class="text-center break-words w-full sm:w-auto">Sampai</span>

                            <!-- Jam Akhir -->
                            <div class="relative w-full sm:w-32 min-w-0">
                                <input type="time" name="jam_akhir" id="jam_akhir"
                                    class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full
                           focus:border-blue-700 focus:ring-0 break-words"
                                    value="{{ old('jam_akhir', $event->jam_akhir) }}">

                                <!-- Fake Placeholder -->
                                <span id="ph_akhir"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none">
                                    12:00 PM
                                </span>
                            </div>

                            <script>
                                const inputMulai = document.getElementById('jam_mulai');
                                const phMulai = document.getElementById('ph_mulai');
                                inputMulai.addEventListener('input', () => {
                                    phMulai.style.display = inputMulai.value ? 'none' : 'block';
                                });

                                const inputAkhir = document.getElementById('jam_akhir');
                                const phAkhir = document.getElementById('ph_akhir');
                                inputAkhir.addEventListener('input', () => {
                                    phAkhir.style.display = inputAkhir.value ? 'none' : 'block';
                                });
                            </script>

                        </div>
                    </div>

                    <!-- Penutupan Pendaftaran -->
                    <div class="w-full">
                        <label class="block font-medium mb-1 break-words">Penutupan Pendaftaran</label>
                        <input type="date" name="penutupan_pendaftaran" id="penutupan_pendaftaran"
                            class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm
               w-full sm:w-40 break-words"
                            value="{{ old('penutupan_pendaftaran', $event->penutupan_pendaftaran) }}">
                    </div>

                    <!-- Kuota -->
                    <div class="w-full sm:w-auto">
                        <label class="block font-medium mb-1 break-words">Kuota Partisipasi</label>
                        <input type="number" name="kuota" value="{{ old('kuota', $event->kuota) }}"
                            class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm
               w-full sm:w-24 break-words"
                            placeholder="000">
                    </div>

                    <!-- Link Form -->
                    <div class="w-full">
                        <label class="block font-medium mb-1 break-words">Link Form</label>
                        <input type="text" name="link_form" value="{{ old('link_form', $event->link_form) }}"
                            class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm
               w-full sm:w-[350px] min-w-0 break-words"
                            placeholder="https://forms.gle/...">
                    </div>

                    <!-- Lokasi -->
                    <div class="w-full">
                        <label class="block font-medium mb-1 break-words">Lokasi</label>
                        <textarea name="lokasi"
                            class="w-full sm:w-96 bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm
               h-32 max-h-64 break-words resize-none"
                            placeholder="Isi Detail Alamat Acara">{{ old('lokasi', $event->lokasi) }}</textarea>
                    </div>

                    <!-- Daftar Kegiatan -->
                    <div class="w-full">
                        <label class="block font-medium mb-2 break-words">Daftar Kegiatan</label>

                        <div id="kegiatan-list" class="space-y-3">
                            @foreach ($event->kegiatan as $k)
                                <div
                                    class="kegiatan-item bg-gray-100 p-3 border-2 border-gray-400 rounded-md cursor-move
                       flex flex-col sm:flex-row sm:items-center gap-3">

                                    <!-- Waktu -->
                                    <input type="time" name="kegiatan_waktu[]" value="{{ $k->waktu }}"
                                        class="bg-gray-200 border border-gray-400 rounded-md px-3 py-2 text-sm
                           w-full sm:w-24 min-w-0 break-words">

                                    <!-- Nama Kegiatan -->
                                    <input type="text" name="kegiatan_nama[]" value="{{ $k->kegiatan }}"
                                        class="bg-gray-200 border border-gray-400 rounded-md px-3 py-2 text-sm
                           w-full sm:w-80 min-w-0 break-words"
                                        placeholder="Isi Kegiatan">

                                    <!-- Tombol Hapus -->
                                    <button type="button" onclick="hapusKegiatan(this)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm w-full sm:w-auto text-center">
                                        Hapus
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol Tambah Acara -->
                    <div class="mt-3">
                        <button type="button" @click="openModal = true"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow w-full sm:w-auto text-center">
                            Tambah Acara
                        </button>
                    </div>


                    <!-- Modal Tambah Kegiatan -->
                    <div x-cloak x-show="openModal"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">

                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                            <h2 class="text-lg font-medium mb-4 break-words">Tambah Kegiatan</h2>

                            <!-- Waktu -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Waktu</label>
                                <input type="time" id="modal-waktu"
                                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100">
                            </div>

                            <!-- Nama Kegiatan -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Nama Kegiatan</label>
                                <input type="text" id="modal-kegiatan"
                                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                    placeholder="Isi Kegiatan">
                            </div>

                            <!-- Tombol -->
                            <div class="flex flex-col sm:flex-row justify-end gap-3">
                                <button type="button" @click="openModal = false"
                                    class="w-full sm:w-auto px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                    Batal
                                </button>

                                <button type="button" onclick="tambahKegiatan(); openModal=false;"
                                    class="w-full sm:w-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-6 w-full">
                        <button type="submit"
                            class="w-full sm:w-auto bg-green-600 text-white px-14 py-2 text-lg rounded-md shadow hover:bg-green-700">
                            Update
                        </button>

                        <a href="{{ route('superadmin.eventform') }}"
                            class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-16 py-2 text-lg rounded-md shadow text-center">
                            Batal
                        </a>
                    </div>

                </div>
            </form>
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

                // FIX UPLOAD GAMBAR
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


        <!-- Script -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener("trix-file-accept", function(event) {
                event.preventDefault(); // cegah upload file ke trix
                alert("Upload gambar lewat field 'Ganti Media', bukan di deskripsi!");
            });
        </script>

        <script>
            new Sortable(document.getElementById("kegiatan-list"), {
                animation: 150,
                handle: ".kegiatan-item",
                ghostClass: "bg-yellow-100"
            });

            function tambahKegiatan() {
                let waktu = document.getElementById("modal-waktu").value;
                let kegiatan = document.getElementById("modal-kegiatan").value;

                if (waktu && kegiatan) {
                    let container = document.getElementById("kegiatan-list");
                    let div = document.createElement("div");
                    div.classList.add("flex", "items-center", "gap-2", "kegiatan-item", "bg-gray-100", "p-2", "rounded-md",
                        "cursor-move");

                    div.innerHTML = `
                <input type="time" name="kegiatan_waktu[]" value="${waktu}"
                    class="bg-gray-200 border rounded-md px-3 py-2 text-sm w-24">
                <input type="text" name="kegiatan_nama[]" value="${kegiatan}"
                    class="bg-gray-200 border rounded-md px-3 py-2 text-sm w-80">
                <button type="button" onclick="hapusKegiatan(this)"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                    Hapus
                </button>
            `;

                    container.appendChild(div);
                    document.getElementById("modal-waktu").value = "";
                    document.getElementById("modal-kegiatan").value = "";
                }
            }

            function hapusKegiatan(button) {
                button.closest(".kegiatan-item").remove();
            }

            // Upload gambar 
            document.getElementById("uploadMedia").addEventListener("change", function(e) {
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById("preview-image").setAttribute("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Sinkronisasi tanggal mulai, akhir, dan penutupan
            document.getElementById("tgl_mulai").addEventListener("change", function() {
                let tglMulai = this.value;
                let tglAkhir = document.getElementById("tgl_akhir");
                let pendaftaran = document.getElementById("penutupan_pendaftaran");

                if (tglMulai) {
                    // batasi pendaftaran <= tgl_mulai
                    pendaftaran.setAttribute("max", tglMulai);
                    if (pendaftaran.value && pendaftaran.value > tglMulai) {
                        pendaftaran.value = tglMulai;
                    }

                    // batasi tgl_akhir >= tgl_mulai
                    tglAkhir.setAttribute("min", tglMulai);
                    if (tglAkhir.value && tglAkhir.value < tglMulai) {
                        tglAkhir.value = tglMulai;
                    }
                } else {
                    pendaftaran.removeAttribute("max");
                    tglAkhir.removeAttribute("min");
                }
            });

            // Validasi jam (jam_mulai < jam_akhir)
            document.getElementById("jam_mulai").addEventListener("change", validateJam);
            document.getElementById("jam_akhir").addEventListener("change", validateJam);

            function validateJam() {
                let jamMulai = document.getElementById("jam_mulai").value;
                let jamAkhir = document.getElementById("jam_akhir").value;

                if (jamMulai && jamAkhir && jamAkhir <= jamMulai) {
                    alert("Jam akhir harus lebih besar dari jam mulai!");
                    document.getElementById("jam_akhir").value = "";
                }
            }
        </script>

    </div>
    {{-- </div> --}}
@endsection
