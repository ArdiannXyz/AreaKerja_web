@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openModal: false, openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-3 flex-1">
                <a href="{{ route('admin.eventform') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Event / <span class="text-slate-500 font-medium">Buat Event Baru</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Buat Event</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Form Card -->
        <div class="w-full bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <form action="{{ route('admin.event.store') }}" method="post" enctype="multipart/form-data" class="w-full">
                @csrf

                <!-- Judul -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Event <span class="text-rose-500">*</span></label>
                    <input type="text" placeholder="Masukkan judul event" name="title" value="{{ old('title') }}" required
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition shadow-xs">
                    @error('title')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status & Upload Media Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Event <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition shadow-xs">
                            <option value="buka" {{ old('status') == 'buka' ? 'selected' : '' }}>Buka (Menerima Pendaftaran)</option>
                            <option value="tutup" {{ old('status') == 'tutup' ? 'selected' : '' }}>Tutup</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <!-- Upload Media -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Gambar Banner Event</label>
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-16 border border-slate-200 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center flex-shrink-0">
                                <img id="previewImage" src="{{ asset('images/no images.jpg') }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <label for="uploadMedia"
                                    class="cursor-pointer inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl text-slate-700 text-xs font-semibold transition">
                                    <i class="ph ph-image text-sm"></i>
                                    Pilih Gambar
                                </label>
                                <p class="text-[11px] text-slate-400 mt-1">PNG, JPG, WEBP maks 5MB.</p>
                                <input id="uploadMedia" type="file" name="image" accept="image/*" hidden>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Editor -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Isi Deskripsi Event</label>
                    <textarea id="editor" name="content" class="w-full min-h-48 border border-slate-200 rounded-xl p-3">
                        {{ old('content') }}
                    </textarea>
                </div>

                <div class="space-y-5 pt-2 border-t border-slate-100">
                    <!-- Waktu Acara -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Waktu Pelaksanaan Acara <span class="text-rose-500">*</span></label>
                        <div class="flex flex-col md:flex-row md:items-center gap-3 w-full">
                            <!-- Tanggal Mulai -->
                            <div class="flex-1">
                                <span class="text-[11px] text-slate-400 block mb-1">Tanggal Mulai</span>
                                <input type="date" name="tgl_mulai" id="tgl_mulai" required
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                    value="{{ old('tgl_mulai') }}">
                            </div>

                            <!-- Tanggal Akhir -->
                            <div class="flex-1">
                                <span class="text-[11px] text-slate-400 block mb-1">Tanggal Selesai</span>
                                <input type="date" name="tgl_akhir" id="tgl_akhir" required
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                    value="{{ old('tgl_akhir') }}">
                            </div>

                            <!-- Jam Mulai -->
                            <div class="w-full md:w-36">
                                <span class="text-[11px] text-slate-400 block mb-1">Jam Mulai</span>
                                <input type="time" name="jam_mulai" id="jam_mulai"
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                    value="{{ old('jam_mulai') }}">
                            </div>

                            <span class="text-center text-xs text-slate-400 hidden md:block pt-4">sampai</span>

                            <!-- Jam Akhir -->
                            <div class="w-full md:w-36">
                                <span class="text-[11px] text-slate-400 block mb-1">Jam Selesai</span>
                                <input type="time" name="jam_akhir" id="jam_akhir"
                                    class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                    value="{{ old('jam_akhir') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Penutupan & Kuota Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Penutupan Pendaftaran -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Batas Akhir Pendaftaran</label>
                            <input type="date" name="penutupan_pendaftaran" id="penutupan_pendaftaran"
                                class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                value="{{ old('penutupan_pendaftaran') }}">
                        </div>

                        <!-- Kuota -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kuota Partisipasi</label>
                            <input type="number" name="kuota"
                                class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                placeholder="Contoh: 100" value="{{ old('kuota') }}">
                        </div>
                    </div>

                    <!-- Link Form -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Link Form Pendaftaran Eksternal (Opsional)</label>
                        <input type="url" name="link_form"
                            class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-800 w-full focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                            placeholder="https://forms.gle/..." value="{{ old('link_form') }}">
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lokasi / Tempat Acara</label>
                        <textarea name="lokasi" rows="3"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                            placeholder="Isi Detail Alamat Acara atau link online (e.g. Zoom Meeting)">{{ old('lokasi') }}</textarea>
                    </div>

                    <!-- Daftar Kegiatan -->
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold text-slate-600">Jadwal Rangkaian Kegiatan</label>
                            <button type="button" @click="openModal = true"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-[#00509d] hover:underline">
                                <i class="ph ph-plus-circle text-sm"></i> Tambah Kegiatan
                            </button>
                        </div>
                        <div id="kegiatan-list" class="space-y-2"></div>
                    </div>

                    <!-- Modal Tambah Kegiatan -->
                    <div x-show="openModal"
                        class="fixed inset-0 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs z-50 p-4" x-cloak>
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 border border-slate-100">
                            <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="ph ph-calendar-plus text-[#00509d] text-lg"></i> Tambah Rangkaian Kegiatan
                            </h2>

                            <div class="space-y-3 text-xs">
                                <div>
                                    <label class="block font-semibold text-slate-600 mb-1">Waktu</label>
                                    <input type="time" id="modal-waktu"
                                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]">
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-600 mb-1">Nama Kegiatan</label>
                                    <input type="text" id="modal-kegiatan"
                                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]"
                                        placeholder="Contoh: Pembukaan & Sambutan">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="openModal = false"
                                    class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-semibold transition">
                                    Batal
                                </button>
                                <button type="button" onclick="tambahKegiatan(); openModal=false"
                                    class="px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white rounded-xl text-xs font-semibold transition">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit & Cancel Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-6 py-2.5 rounded-xl transition shadow-xs justify-center">
                            <i class="ph ph-floppy-disk text-base"></i>
                            Simpan Event
                        </button>

                        <a href="{{ route('admin.eventform') }}"
                           class="inline-flex items-center gap-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold px-6 py-2.5 rounded-xl transition justify-center">
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
                height: 400,
                menubar: false,
                plugins: 'lists link image media code fullscreen mentions',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link image media | code fullscreen',
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

        <!-- Sortable and Event Form Script -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
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
                    div.classList.add("flex", "items-center", "gap-2", "kegiatan-item", "bg-slate-50", "p-2.5", "rounded-xl",
                        "border", "border-slate-200/80", "cursor-move");

                    div.innerHTML = `
                        <i class="ph ph-dots-six-vertical text-slate-400"></i>
                        <input type="time" name="kegiatan_waktu[]" value="${waktu}"
                            class="bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs w-28">
                        <input type="text" name="kegiatan_nama[]" value="${kegiatan}"
                            class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs flex-1">
                        <button type="button" onclick="hapusKegiatan(this)"
                            class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition">
                            <i class="ph ph-trash text-sm"></i>
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

            // Upload preview
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

            // Date validation sync
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
        </script>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>
@endsection
