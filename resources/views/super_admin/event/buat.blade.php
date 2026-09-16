@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openModal: false, openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 flex-1">
                <a href="{{ route('superadmin.eventform') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Event / <span class="text-slate-500 font-medium">Buat Event Baru</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Buat Event</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        {{-- content --}}
        <div class="w-full bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('superadmin.event.store') }}" method="post" enctype="multipart/form-data"
                class="w-full">
                @csrf

                <!-- Judul -->
                <input type="text" placeholder="Masukkan judul artikel" name="title"
                    class="w-full bg-gray-200 border-2 border-gray-400 rounded-md px-4 py-2 mb-8 
               break-words max-w-full">

                <!-- Upload Media -->
                <div class="mb-4">
                    <label for="uploadMedia"
                        class="cursor-pointer inline-block px-4 py-2 bg-gray-100 border-2 border-gray-400 
                   rounded-lg shadow hover:bg-gray-200 text-sm font-medium">
                        Tambahkan Media
                    </label>

                    <input id="uploadMedia" type="file" name="image" accept="image/*" hidden>

                    <!-- Preview Gambar -->
                    <div
                        class="mt-3 w-full max-w-xs sm:max-w-sm md:max-w-md h-40 
                    border-2 border-gray-400 rounded-md overflow-hidden">
                        <img id="previewImage"
                            src="{{ isset($event) && $event->image ? asset('storage/' . $event->image) : asset('images/no images.jpg') }}"
                            class="w-full h-full object-contain">
                    </div>
                </div>


                <!-- Editor -->
                <div class="rounded-md overflow-hidden mt-4 w-full">
                    <label class="block mb-2 text-lg font-medium break-words">Isi Artikel</label>

                    <textarea id="editor" name="content"
                        class="w-full min-h-48 border border-gray-400 rounded-lg p-3
               break-words overflow-y-auto resize-y">
        {{ old('content', $event->content ?? '') }}
    </textarea>
                </div>


                <div class="space-y-4 mt-4">

                    <!-- Waktu Acara -->
                    <div>
                        <label class="block font-medium mb-1">Waktu Acara</label>

                        <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-2 w-full">

                            <!-- Tanggal Mulai -->
                            <input type="date" name="tgl_mulai" id="tgl_mulai"
                                class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full md:w-40"
                                value="{{ old('tgl_mulai') }}">

                            <!-- Tanggal Akhir -->
                            <input type="date" name="tgl_akhir" id="tgl_akhir"
                                class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full md:w-40"
                                value="{{ old('tgl_akhir') }}">

                            <!-- Jam Mulai -->
                            <div class="relative w-full md:w-32">
                                <input type="time" name="jam_mulai" id="jam_mulai"
                                    class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full
                    focus:border-blue-700 focus:ring-0">
                                <span id="ph_mulai"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none">
                                    12:00 PM
                                </span>
                            </div>

                            <span class="text-center hidden md:block">Sampai</span>
                            <span class="text-center md:hidden">→</span>

                            <!-- Jam Akhir -->
                            <div class="relative w-full md:w-32">
                                <input type="time" name="jam_akhir" id="jam_akhir"
                                    class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full
                    focus:border-blue-700 focus:ring-0">
                                <span id="ph_akhir"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none">
                                    12:00 PM
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Penutupan Pendaftaran -->
                    <div>
                        <label class="block font-medium mb-1">Penutupan Pendaftaran</label>
                        <input type="date" name="penutupan_pendaftaran" id="penutupan_pendaftaran"
                            class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full md:w-40"
                            value="{{ old('penutupan_pendaftaran') }}">
                    </div>

                    <!-- Kuota -->
                    <div>
                        <label class="block font-medium mb-1">Kuota Partisipasi</label>
                        <input type="number" name="kuota"
                            class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full md:w-24"
                            placeholder="000">
                    </div>

                    <!-- Link Form -->
                    <div>
                        <label class="block font-medium mb-1">Link Form</label>
                        <input type="text" name="link_form"
                            class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full md:w-[350px] break-words"
                            placeholder="https://forms.gle/...">
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block font-medium mb-1">Lokasi</label>
                        <textarea name="lokasi"
                            class="w-full md:w-96 bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm h-32 max-h-64 break-words"
                            placeholder="Isi Detail Alamat Acara"></textarea>
                    </div>

                    <!-- Daftar Kegiatan -->
                    <div>
                        <label class="block font-medium mb-2">Daftar Kegiatan</label>
                        <div id="kegiatan-list" class="space-y-2"></div>
                    </div>

                    <!-- Tombol Tambah Acara -->
                    <div>
                        <button type="button" @click="openModal = true"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow w-full md:w-auto">
                            Tambah Acara
                        </button>
                    </div>

                    <!-- Modal -->
                    <div x-show="openModal"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" x-cloak>
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                            <h2 class="text-lg font-semibold mb-4">Tambah Kegiatan</h2>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium">Waktu</label>
                                    <input type="time" id="modal-waktu"
                                        class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Kegiatan</label>
                                    <input type="text" id="modal-kegiatan"
                                        class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100"
                                        placeholder="Nama kegiatan">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="openModal = false"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                                    Batal
                                </button>

                                <button type="button" onclick="tambahKegiatan(); openModal=false"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex flex-col md:flex-row gap-4 mt-6 w-full">
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition shadow-sm w-full md:w-auto justify-center">
                            <i class="ph ph-floppy-disk text-base"></i>
                            Simpan Event
                        </button>

                        <a href="{{ route('superadmin.eventform') }}"
                           class="inline-flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold px-6 py-2.5 rounded-xl transition w-full md:w-auto justify-center">
                            <i class="ph ph-x text-base"></i>
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
                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 kegiatan-item bg-gray-100 p-2 rounded-md w-full">

    <input type="time" name="kegiatan_waktu[]" value="${waktu}"
        class="bg-gray-200 border rounded-md px-3 py-2 text-sm w-full md:w-24">

    <input type="text" name="kegiatan_nama[]" value="${kegiatan}"
        class="bg-gray-200 border rounded-md px-3 py-2 text-sm w-full md:w-80 break-words">

    <button type="button" onclick="hapusKegiatan(this)"
        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm w-full md:w-auto text-center">
        Hapus
    </button>

</div>

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
                        document.getElementById("previewImage").src = event.target.result;
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


        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
