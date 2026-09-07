@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <div class="p-2 sm:ml-64 bg-white min-h-screen font-sans overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <!-- Konten utama -->
        <main class="flex-1 p-4 sm:p-20 bg-white font-sans text-gray-900 break-words">
            <!-- Header atas form -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 space-y-2 sm:space-y-0">
                <!-- Kiri: Judul -->
                <h1 class="text-2xl font-semibold text-gray-700 break-words w-full sm:w-auto">Edit Lowongan</h1>

                <!-- Kanan: Notifikasi + Profil -->
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center sm:space-x-2 space-y-2 sm:space-y-0 w-full sm:w-auto">
                    <!-- Ikon Notifikasi -->
                    <div class="relative flex-shrink-0">
                        <button @click="openNotif = true" class="relative">
                            <svg width="28" height="28" viewBox="0 0 31 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
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
                    </div>

                    <!-- Profil -->
                    <div class="flex items-center bg-white px-3 py-2 rounded-lg shadow text-sm w-full sm:w-auto">
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
                        <div class="ml-2 truncate">
                            <span
                                class="font-semibold block text-ellipsis overflow-hidden">{{ Auth::user()->username }}</span>
                            <p class="text-gray-500 text-xs truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Card Container -->
            <div class="border border-gray-500 rounded-xl p-6 sm:p-16 max-w-4xl w-full mx-auto">    

                <h2 class="font-bold mb-6 text-gray-600 text-lg px-2 sm:px-6 py-2">Tambah Data Lowongan</h2>

                <!-- Form -->
                <form action="{{ route('superadmin.lowongan.update', $lowongan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Baris 1: Judul & Alamat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="judul" class="block text-sm font-bold mb-1">Judul <span
                                    class="text-red-600">*</span></label>
                            <input type="text" id="judul" name="nama" required
                                value="{{ old('nama', $lowongan->nama) }}"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words" />
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-bold mb-1">
                                Alamat <span class="text-red-600">*</span>
                            </label>

                            <select id="alamat" name="alamat"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words text-sm sm:text-base">
                                <option disabled value="">Pilih Alamat</option>
                                <option value="jakarta"
                                    {{ old('alamat', $lowongan->alamat) == 'jakarta' ? 'selected' : '' }}>Jakarta</option>
                                <option value="bandung"
                                    {{ old('alamat', $lowongan->alamat) == 'bandung' ? 'selected' : '' }}>Bandung</option>
                                <option value="ciamis"
                                    {{ old('alamat', $lowongan->alamat) == 'ciamis' ? 'selected' : '' }}>Ciamis</option>
                                <option value="yogyakarta"
                                    {{ old('alamat', $lowongan->alamat) == 'yogyakarta' ? 'selected' : '' }}>Yogyakarta
                                </option>
                                <option value="solo" {{ old('alamat', $lowongan->alamat) == 'solo' ? 'selected' : '' }}>
                                    Solo</option>
                                <option value="semarang"
                                    {{ old('alamat', $lowongan->alamat) == 'semarang' ? 'selected' : '' }}>Semarang
                                </option>
                                <option value="surabaya"
                                    {{ old('alamat', $lowongan->alamat) == 'surabaya' ? 'selected' : '' }}>Surabaya
                                </option>
                            </select>
                        </div>

                    </div>

                    <!-- Baris 2: Jenis Lowongan & Gaji -->
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
                        <div class="col-span-1 sm:col-span-2">
                            <label for="jenis" class="block text-sm font-bold mb-1">
                                Jenis Lowongan <span class="text-red-600">*</span>
                            </label>

                            <select id="jenis" name="jenis"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words text-sm sm:text-base">
                                <option disabled value="">Pilih Jenis</option>
                                <option value="full_time"
                                    {{ old('jenis', $lowongan->jenis) == 'full_time' ? 'selected' : '' }}>Full Time
                                </option>
                                <option value="part_time"
                                    {{ old('jenis', $lowongan->jenis) == 'part_time' ? 'selected' : '' }}>Part Time
                                </option>
                                <option value="middle_time"
                                    {{ old('jenis', $lowongan->jenis) == 'middle_time' ? 'selected' : '' }}>Middle Time
                                </option>
                                <option value="freelance"
                                    {{ old('jenis', $lowongan->jenis) == 'freelance' ? 'selected' : '' }}>Freelance
                                </option>
                            </select>
                        </div>

                        <div class="col-span-1">
                            <label for="gaji_awal" class="block text-sm font-bold mb-1">Gaji <span
                                    class="text-red-600">*</span></label>
                            <input type="number" id="gaji_awal" name="gaji_awal"
                                value="{{ old('gaji_awal', $lowongan->gaji_awal) }}" placeholder="Min"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none" />
                        </div>

                        <div class="col-span-1">
                            <label for="gaji_akhir" class="block mb-1 invisible">Max</label>
                            <input type="number" id="gaji_akhir" name="gaji_akhir"
                                value="{{ old('gaji_akhir', $lowongan->gaji_akhir) }}" placeholder="Max"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none" />
                        </div>

                        <div class="col-span-1 sm:col-span-1">
                            <label class="block font-semibold text-sm mb-1">Kategori</label>
                            <select name="kategori"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-2 focus:ring-orange-500 focus:outline-none break-words">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->nama }}"
                                        {{ old('kategori', $lowongan->kategori ?? '') == $cat->nama ? 'selected' : '' }}>
                                        {{ $cat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Label Gaji & Benefit -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="label_gaji" class="block text-sm font-bold mb-1">
                                Label gaji <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="label_gaji" name="label_gaji" required
                                value="{{ old('label_gaji', $lowongan->label_gaji) }}"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words" />
                        </div>

                        <div>
                            <label for="benefit" class="block text-sm font-bold mb-1">
                                Benefit <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="benefit" name="benefit" required
                                value="{{ old('benefit', $lowongan->benefit) }}"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words" />
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold mb-1">
                            Deskripsi <span class="text-red-600">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" required
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words resize-none sm:resize-y">{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                    </div>

                    <!-- Tanggung Jawab -->
                    <div>
                        <label for="tanggung_jawab" class="block text-sm font-bold mb-1">
                            Tanggung Jawab <span class="text-red-600">*</span>
                        </label>
                        <textarea id="tanggung_jawab" name="tanggung_jawab" rows="5" required
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words resize-none sm:resize-y">{{ old('tanggung_jawab', $lowongan->tanggung_jawab) }}</textarea>
                    </div>


                    <!-- Syarat Pekerjaan -->
                    <div>
                        <p class="text-sm font-semibold mb-2">Syarat Pekerjaan</p>

                        <!-- Pendidikan -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-start">
                            <label class="w-full sm:w-32 text-sm font-medium mb-2 sm:mb-0">
                                Pendidikan <span class="text-red-600">*</span>
                            </label>

                            <div class="col-span-2 w-full">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-2 gap-y-2">
                                    @foreach (['SD', 'SMP', 'SMA', 'SMK', 'S1', 'S2', 'S3'] as $pend)
                                        <label class="flex items-center gap-2 text-sm whitespace-nowrap">
                                            <input class="border border-orange-500" type="radio"
                                                name="syarat_pekerjaan" value="{{ $pend }}"
                                                {{ old('syarat_pekerjaan', $lowongan->syarat_pekerjaan) == $pend ? 'checked' : '' }}>
                                            <span>{{ $pend }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Batas Waktu -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:gap-4">
                            <label for="batas_lamaran" class="w-full sm:w-32 text-sm font-medium mb-2 sm:mb-0">
                                Batas Waktu <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="batas_lamaran"
                                value="{{ old('batas_lamaran', $lowongan->batas_lamaran) }}"
                                class="w-full sm:w-1/2 border border-gray-400 rounded px-3 py-2 focus:outline-none text-sm" />
                        </div>

                        <!-- Tombol -->
                        <div class="flex flex-col sm:flex-row justify-center gap-3 mt-6">
                            <button type="submit"
                                class="bg-orange-500 text-white text-sm px-7 py-2 rounded-lg hover:bg-orange-600 transition w-full sm:w-auto">
                                Simpan
                            </button>
                            <a href="{{ route('superadmin.lowongan.detail', ['perusahaan' => $lowongan->perusahaan->slug, 'lowongan' => $lowongan->id]) }}"
                                class="border border-orange-600 text-orange-600 text-sm px-7 py-2 rounded-lg hover:bg-gray-100 transition w-full sm:w-auto text-center">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @include('super_admin.notif.modal_notif')
            @include('super_admin.notif.modal_semua')
        </main>
    </div>

    <script>
        // Tandai dibaca
        async function markAsRead(url, el) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {

                    // Ubah warna bg
                    el.classList.remove("bg-white");
                    el.classList.add("bg-gray-200");

                    // Kurangi badge
                    const badge = document.getElementById("notif-badge");
                    if (badge) {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove();
                        }
                    }
                }

            } catch (error) {
                console.error("markAsRead error:", error);
            }
        }

        // AlpineJS init
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifHandler', () => ({

                // Hapus satu notifikasi
                async hapus(id) {
                    if (!confirm("Hapus notifikasi ini?")) return;

                    let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);

                    let res = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                    }
                },

                // Hapus semua
                async hapusSemua() {
                    if (!confirm("Hapus semua notifikasi?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item').forEach(e => e.remove());
                    }
                },

                // Hapus semua yang sudah dibaca
                async hapusSemuaBaca() {
                    if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item.bg-gray-200')
                            .forEach(e => e.remove());
                    }
                }

            }));
        });
    </script>



    <script>
        document.querySelector('form[target="hiddenFrame"]').addEventListener('submit', () => {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.classList.remove('bg-white');
                item.classList.add('bg-gray-200');
            });
            const badge = document.querySelector('.absolute .bg-red-500');
            if (badge) badge.remove();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
