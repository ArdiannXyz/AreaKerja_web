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
                    <p class="text-xs text-slate-400">Event / <span class="text-slate-500 font-medium">Edit Event</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Edit Event</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Form Card -->
        <div class="w-full bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('superadmin.event.update', $event->id) }}" method="post" enctype="multipart/form-data"
                class="w-full">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Event</label>
                    <input type="text" placeholder="Masukkan judul event" name="title"
                        value="{{ old('title', $event->title) }}"
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                </div>

                <!-- Upload Media -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Gambar Event</label>
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        <div class="w-full max-w-xs h-40 border border-slate-200 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center">
                            <img id="preview-image"
                                src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/150x150?text=No+Image' }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <label for="uploadMedia"
                                class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl text-slate-700 text-xs font-semibold transition">
                                <i class="ph ph-image text-sm"></i>
                                Ganti Media
                            </label>
                            <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                            <input id="uploadMedia" type="file" name="image" accept="image/*" hidden>
                        </div>
                    </div>
                </div>

                <!-- Editor -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Isi Artikel</label>
                    <textarea id="editor" name="content" class="w-full h-48 border border-slate-200 rounded-xl">
                        {{ old('content', $event->content ?? '') }}
                    </textarea>
                </div>

                <div class="space-y-5">
                    <!-- Waktu Acara -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Waktu Acara</label>
                        <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-2 w-full">
                            <!-- Tanggal Mulai -->
                            <input type="date" name="tgl_mulai" id="tgl_mulai"
                                class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full md:w-40 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                value="{{ old('tgl_mulai', $event->tgl_mulai) }}">

                            <!-- Tanggal Akhir -->
                            <input type="date" name="tgl_akhir" id="tgl_akhir"
                                class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full md:w-40 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                value="{{ old('tgl_akhir', $event->tgl_akhir) }}">

                            <!-- Jam Mulai -->
                            <div class="relative w-full md:w-32">
                                <input type="time" name="jam_mulai" id="jam_mulai"
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                    value="{{ old('jam_mulai', $event->jam_mulai) }}">
                                <span id="ph_mulai"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none">
                                    12:00 PM
                                </span>
                            </div>

                            <span class="text-center text-xs text-slate-400 hidden md:block">Sampai</span>
                            <span class="text-center text-xs text-slate-400 md:hidden">→</span>

                            <!-- Jam Akhir -->
                            <div class="relative w-full md:w-32">
                                <input type="time" name="jam_akhir" id="jam_akhir"
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                    value="{{ old('jam_akhir', $event->jam_akhir) }}">
                                <span id="ph_akhir"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none">
                                    12:00 PM
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Penutupan Pendaftaran -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Penutupan Pendaftaran</label>
                        <input type="date" name="penutupan_pendaftaran" id="penutupan_pendaftaran"
                            class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full sm:w-48 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                            value="{{ old('penutupan_pendaftaran', $event->penutupan_pendaftaran) }}">
                    </div>

                    <!-- Kuota -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kuota Partisipasi</label>
                        <input type="number" name="kuota" value="{{ old('kuota', $event->kuota) }}"
                            class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full sm:w-32 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                            placeholder="000">
                    </div>

                    <!-- Link Form -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Link Pendaftaran (Google Form dsb.)</label>
                        <input type="text" name="link_form" value="{{ old('link_form', $event->link_form) }}"
                            class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full sm:w-96 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                            placeholder="https://forms.gle/...">
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lokasi</label>
                        <textarea name="lokasi"
                            class="w-full sm:w-96 bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 h-28 resize-none focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                            placeholder="Isi Detail Alamat Acara">{{ old('lokasi', $event->lokasi) }}</textarea>
                    </div>

                    <!-- Daftar Kegiatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Daftar Kegiatan (Rundown)</label>

                        <div id="kegiatan-list" class="space-y-2.5 mb-3">
                            @foreach ($event->kegiatan as $k)
                                <div class="kegiatan-item bg-slate-50 p-3 border border-slate-200 rounded-xl cursor-move flex flex-col sm:flex-row sm:items-center gap-2.5">
                                    <!-- Waktu -->
                                    <input type="time" name="kegiatan_waktu[]" value="{{ $k->waktu }}"
                                        class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 w-full sm:w-28 focus:outline-none focus:border-[#00509d]">

                                    <!-- Nama Kegiatan -->
                                    <input type="text" name="kegiatan_nama[]" value="{{ $k->kegiatan }}"
                                        class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 flex-1 focus:outline-none focus:border-[#00509d]"
                                        placeholder="Isi Kegiatan">

                                    <!-- Tombol Hapus -->
                                    <button type="button" onclick="hapusKegiatan(this)"
                                        class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                        Hapus
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tombol Tambah Acara -->
                        <button type="button" @click="openModal = true"
                            class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-4 py-2 rounded-xl transition">
                            <i class="ph ph-plus text-sm"></i>
                            Tambah Acara
                        </button>
                    </div>

                    <!-- Modal Tambah Kegiatan -->
                    <div x-cloak x-show="openModal"
                        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
                        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md border border-slate-100" @click.outside="openModal = false">
                            <h2 class="text-base font-bold text-slate-800 mb-4">Tambah Kegiatan</h2>

                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu</label>
                                    <input type="time" id="modal-waktu"
                                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kegiatan</label>
                                    <input type="text" id="modal-kegiatan"
                                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                        placeholder="Contoh: Registrasi Ulang">
                                </div>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="openModal = false"
                                    class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold rounded-xl transition">
                                    Batal
                                </button>
                                <button type="button" onclick="tambahKegiatan(); openModal=false;"
                                    class="px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold rounded-xl transition shadow-sm">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition shadow-sm">
                            <i class="ph ph-floppy-disk text-base"></i>
                            Update Event
                        </button>

                        <a href="{{ route('superadmin.eventform') }}"
                            class="inline-flex items-center justify-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold px-6 py-2.5 rounded-xl transition">
                            <i class="ph ph-x text-base"></i>
                            Batal
                        </a>
                    </div>
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

        <!-- Sortable & Helpers -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener("trix-file-accept", function(event) {
                event.preventDefault();
                alert("Upload gambar lewat field 'Ganti Media', bukan di deskripsi!");
            });

            new Sortable(document.getElementById("kegiatan-list"), {
                animation: 150,
                handle: ".kegiatan-item",
                ghostClass: "bg-blue-50"
            });

            function tambahKegiatan() {
                let waktu = document.getElementById("modal-waktu").value;
                let kegiatan = document.getElementById("modal-kegiatan").value;

                if (waktu && kegiatan) {
                    let container = document.getElementById("kegiatan-list");
                    let div = document.createElement("div");
                    div.classList.add("flex", "flex-col", "sm:flex-row", "sm:items-center", "gap-2.5", "kegiatan-item", "bg-slate-50", "p-3", "border", "border-slate-200", "rounded-xl", "cursor-move");

                    div.innerHTML = `
                        <input type="time" name="kegiatan_waktu[]" value="${waktu}"
                            class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 w-full sm:w-28 focus:outline-none focus:border-[#00509d]">
                        <input type="text" name="kegiatan_nama[]" value="${kegiatan}"
                            class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 flex-1 focus:outline-none focus:border-[#00509d]">
                        <button type="button" onclick="hapusKegiatan(this)"
                            class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 px-3 py-1.5 rounded-lg text-xs font-medium transition">
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

            // Upload gambar preview
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

            // Sinkronisasi tanggal
            document.getElementById("tgl_mulai").addEventListener("change", function() {
                let tglMulai = this.value;
                let tglAkhir = document.getElementById("tgl_akhir");
                let pendaftaran = document.getElementById("penutupan_pendaftaran");

                if (tglMulai) {
                    pendaftaran.setAttribute("max", tglMulai);
                    if (pendaftaran.value && pendaftaran.value > tglMulai) {
                        pendaftaran.value = tglMulai;
                    }

                    tglAkhir.setAttribute("min", tglMulai);
                    if (tglAkhir.value && tglAkhir.value < tglMulai) {
                        tglAkhir.value = tglMulai;
                    }
                } else {
                    pendaftaran.removeAttribute("max");
                    tglAkhir.removeAttribute("min");
                }
            });

            // Validasi jam
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
    </main>
@endsection
