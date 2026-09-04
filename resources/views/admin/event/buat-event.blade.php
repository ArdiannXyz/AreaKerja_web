@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64">
        <div class="flex-1 p-6 bg-white overflow-y-auto" x-data="{
            openModal: false,
            openNotif: false,
            openAllNotif: false
        }">
            <div class="flex flex-wrap lg:flex-nowrap justify-between items-center mb-10 gap-4">

                <h1 class="text-2xl font-medium w-full lg:w-auto">Buat Event Baru</h1>

                <!-- Profil Admin -->
                <div class="flex items-center gap-3 w-full lg:w-auto justify-end">

                    {{-- Tombol Notifikasi --}}
                    <button @click="openNotif = true" class="relative">
                        <!-- Icon Lonceng -->
                        <svg width="31" height="32" viewBox="0 0 31 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_722_7956)">
                                <path
                                    d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                    fill="black" />
                            </g>
                        </svg>

                        <!-- Badge Notifikasi -->
                        @if ($global_notifikasi_unread > 0)
                            <span id="notif-badge"
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                                {{ $global_notifikasi_unread }}
                            </span>
                        @endif
                    </button>

                    <!-- Profile Card -->
                    <div
                        class="flex items-center justify-between bg-white border border-gray-400 shadow-md 
                   rounded-2xl px-3 py-2 w-full sm:w-auto h-auto sm:h-14">

                        <div class="flex items-center gap-2">
                            <a href="#">
                                @if (Auth::user()->role == 'admin')
                                    @if (Auth::user()->admin->img_profile)
                                        <img class="w-10 h-10 object-cover rounded-full"
                                            src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
                                    @else
                                        <img class="w-10 h-10 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                            alt="">
                                    @endif
                                @else
                                    <img class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                        alt="">
                                @endif
                            </a>

                            <div class="text-sm break-words max-w-[200px]">
                                <span class="font-semibold">{{ Auth::user()->username }}</span>
                                <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>


            <div class="w-full bg-white p-4 sm:p-6">
                <form action="{{ route('admin.event.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Judul -->
                    <input type="text" placeholder="Masukkan judul artikel" name="title"
                        class="w-full bg-gray-200 border-2 border-gray-400 rounded-md px-4 py-2 mb-8 break-words">

                    <!-- Upload Media -->
                    <div class="mb-4">
                        <label for="uploadMedia"
                            class="cursor-pointer px-4 py-2 bg-gray-100 border-2 border-gray-400 rounded-lg shadow hover:bg-gray-200 text-sm font-medium inline-block">
                            Tambahkan Media
                        </label>
                        <input id="uploadMedia" type="file" name="image" accept="image/*" hidden>

                        <!-- Preview Gambar -->
                        <div class="mt-3 w-full max-w-xs border-2 border-gray-400 rounded-md overflow-hidden">
                            <img id="previewImage"
                                src="{{ isset($event) && $event->image ? asset('storage/' . $event->image) : asset('images/no images.jpg') }}"
                                class="w-full h-40 object-contain">
                        </div>
                    </div>

                    <!-- Editor -->
                    <div class="mt-4">
                        <label class="block mb-2 text-lg font-medium">Isi Artikel</label>
                        <textarea id="editor" name="content" class="w-full h-48 border border-gray-400 rounded-lg break-words">{{ old('content', $event->content ?? '') }}</textarea>
                    </div>

                    <div class="space-y-6 mt-6">

                        <!-- Waktu Acara -->
                        <div>
                            <label class="block font-medium mb-1">Waktu Acara</label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-center lg:gap-3 gap-3">
                                <input type="date" name="tgl_mulai" id="tgl_mulai"
                                    class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full"
                                    value="{{ old('tgl_mulai') }}">

                                <input type="date" name="tgl_akhir" id="tgl_akhir"
                                    class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full"
                                    value="{{ old('tgl_akhir') }}">

                                <!-- Jam Mulai -->
                                <div class="relative w-full sm:w-32">
                                    <input type="time" name="jam_mulai" id="jam_mulai"
                                        class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full
        focus:border-orange-500 focus:ring-0">

                                    <!-- Fake Placeholder -->
                                    <span id="ph_mulai"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none">
                                        12:00 PM
                                    </span>
                                </div>

                                <span class="text-center">Sampai</span>

                                <!-- Jam Akhir -->
                                <div class="relative w-full sm:w-32">
                                    <input type="time" name="jam_akhir" id="jam_akhir"
                                        class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full
        focus:border-orange-500 focus:ring-0">

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
                        <div>
                            <label class="block font-medium mb-1">Penutupan Pendaftaran</label>
                            <input type="date" name="penutupan_pendaftaran" id="penutupan_pendaftaran"
                                class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full max-w-xs"
                                value="{{ old('penutupan_pendaftaran') }}">
                        </div>

                        <!-- Kuota -->
                        <div>
                            <label class="block font-medium mb-1">Kuota Partisipasi</label>
                            <input type="number" name="kuota"
                                class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full max-w-[100px]"
                                placeholder="000">
                        </div>

                        <!-- Link Form -->
                        <div>
                            <label class="block font-medium mb-1">Link Form</label>
                            <input type="url" name="link_form"
                                class="bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm w-full"
                                placeholder="https://example.com">
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label class="block font-medium mb-1">Lokasi</label>
                            <textarea name="lokasi"
                                class="w-full bg-gray-200 border-2 border-gray-400 rounded-md px-3 py-2 text-sm h-32 max-h-64 break-words"
                                placeholder="Isi Detail Alamat Acara"></textarea>
                        </div>

                        <!-- Daftar Kegiatan -->
                        <div>
                            <label class="block font-medium mb-2">Daftar Kegiatan</label>
                            <div id="kegiatan-list" class="space-y-2"></div>
                        </div>

                        <!-- Tombol Tambah Acara -->
                        <button type="button" @click="openModal = true"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                            Tambah Acara
                        </button>

                        <!-- Modal -->
                        <div x-show="openModal"
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4" x-cloak>

                            <div
                                class="bg-white rounded-lg shadow-lg w-full max-w-md p-6
                max-h-[90vh] overflow-y-auto">

                                <h2 class="text-lg font-semibold mb-4 break-words">
                                    Tambah Kegiatan
                                </h2>

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium">Waktu</label>
                                        <input type="time" id="modal-waktu"
                                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Kegiatan</label>
                                        <input type="text" id="modal-kegiatan"
                                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                            placeholder="Nama kegiatan">
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                                    <button type="button" @click="openModal = false"
                                        class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md w-full sm:w-auto">
                                        Batal
                                    </button>

                                    <button type="button" onclick="tambahKegiatan(); openModal=false"
                                        class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-md w-full sm:w-auto">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>


                        <!-- Submit -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-4">
                            <button type="submit"
                                class="bg-green-600 text-white px-14 py-2 text-lg rounded-md shadow hover:bg-green-500 w-full sm:w-auto">
                                Simpan
                            </button>

                            <a href="/admin/event"
                                class="bg-red-600 hover:bg-red-500 text-white px-16 py-2 text-lg rounded-md shadow text-center w-full sm:w-auto">
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
              <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 kegiatan-item bg-gray-100 p-2 rounded-md w-full">

    <input type="time" name="kegiatan_waktu[]" value="${waktu}"
        class="bg-gray-200 border rounded-md px-3 py-2 text-sm w-full sm:w-24">

    <input type="text" name="kegiatan_nama[]" value="${kegiatan}"
        class="bg-gray-200 border rounded-md px-3 py-2 text-sm w-full sm:w-80 break-words">

    <button type="button" onclick="hapusKegiatan(this)"
        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm w-full sm:w-auto">
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

            @include('admin.notif.modal_notif')
            @include('admin.notif.modal_semua')
        </div>
    </div>
@endsection
