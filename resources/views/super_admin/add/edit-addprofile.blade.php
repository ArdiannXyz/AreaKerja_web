@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto " x-data="{ openNotif: false, openAllNotif: false }">
        <!-- Header -->
        <div class="flex flex-wrap justify-between items-center mb-6 gap-3">

            <h1 class="text-2xl font-medium whitespace-normal break-words">
                Edit User
            </h1>

            <div class="flex items-center gap-3 flex-wrap">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative flex-shrink-0">
                    <!-- Icon Lonceng -->
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>

                    <!-- Badge notif -->
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                <!-- Profil -->
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-shrink-0">

                    <a href="{{ route('superadmin.profile') }}">
                        @if (Auth::user()->role == 'super_admin')
                            @if (Auth::user()->superadmin?->img_profile)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full"
                                    src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
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

                    <div class="text-sm whitespace-normal break-words max-w-[150px] sm:max-w-none">
                        <span class="font-semibold break-words">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm break-words">{{ Auth::user()->email }}</p>
                    </div>

                </div>
            </div>
        </div>



        <div
            class="max-w-4xl mx-auto border-2 border-gray-400 rounded-lg p-6 shadow-sm 
            w-full overflow-x-hidden">

            <h2 class="text-center text-xl font-semibold mb-6 break-words">Edit User</h2>

            @php
                if ($user->role === 'admin') {
                    $detail = $user->admin;
                } elseif ($user->role === 'finance') {
                    $detail = $user->finance;
                } elseif ($user->role === 'perusahaan') {
                    $detail = $user->perusahaan;
                } elseif ($user->role === 'pelamar') {
                    $detail = $user->pelamar;
                } else {
                    $detail = null;
                }
            @endphp



            <form action="{{ route('superadmin.update.user', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 w-full max-w-3xl mx-auto px-3">
                @csrf
                @method('PUT')

                <!-- Foto Profil -->
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <label for="fileinputrole" class="cursor-pointer">
                            <img id="pa" class="w-32 h-32 sm:w-40 sm:h-40 object-cover rounded-full"
                                src="{{ $detail && $detail->img_profile ? asset('storage/' . $detail->img_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username ?? 'User') . '&background=00509d&color=fff&size=128' }}"
                                alt="Profile">
                        </label>
                        <input id="fileinputrole" type="file" name="img_profile" class="hidden" accept="image/*">
                    </div>
                </div>

                <!-- Email & Username -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1">Password</label>
                        <input type="password" name="password" 
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                            <small class="text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                </div>



                <!-- Role -->
                <div class="w-full min-w-0">
                    <label class="block text-sm font-medium mb-1 break-words">Role</label>
                    <select name="role" id="roleSelect"
                        class="w-full border-2 border-gray-400 rounded-md px-3 py-2 overflow-hidden text-ellipsis" required>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="finance" {{ old('role', $user->role) == 'finance' ? 'selected' : '' }}>Finance
                        </option>
                        <option value="perusahaan" {{ old('role', $user->role) == 'perusahaan' ? 'selected' : '' }}>
                            Perusahaan</option>
                        <option value="pelamar" {{ old('role', $user->role) == 'pelamar' ? 'selected' : '' }}>Pelamar
                        </option>
                    </select>
                </div>


                <!-- ================= FORM ADMIN / FINANCE ================= -->
                <div id="form-alamat" class="space-y-4 w-full min-w-0">

                    <!-- Nama Lengkap -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap"
                            value="{{ old('nama_lengkap', $detail->nama_lengkap ?? '') }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>

                    <!-- Provinsi / Kota / Kecamatan / Desa -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 min-w-0">

                        <!-- Provinsi -->
                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">Provinsi</label>
                            <select id="provinsiSelect" name="provinsi_id"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 overflow-hidden text-ellipsis">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsis as $prov)
                                    <option value="{{ $prov->id }}"
                                        {{ $user->provinsi_id == $prov->id ? 'selected' : '' }}>
                                        {{ $prov->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kota/Kabupaten -->
                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">Kota/Kabupaten</label>
                            <select id="kotaSelect" name="kota_id"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 overflow-hidden text-ellipsis">
                                <option value="">Pilih Kota</option>
                                @if ($user->kota)
                                    <option value="{{ $user->kota_id }}" selected>{{ $user->kota->nama }}</option>
                                @endif
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">Kecamatan</label>
                            <select id="kecamatanSelect" name="kecamatan_id"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 overflow-hidden text-ellipsis">
                                <option value="">Pilih Kecamatan</option>
                                @if ($user->kecamatan)
                                    <option value="{{ $user->kecamatan_id }}" selected>{{ $user->kecamatan->nama }}
                                    </option>
                                @endif
                            </select>
                        </div>

                        <!-- Desa -->
                        <div class="min-w-0 sm:col-span-3">
                            <label class="block text-sm font-medium mb-1 break-words">Desa</label>
                            <input type="text" name="desa" value="{{ old('desa', $detail->desa ?? '') }}"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                        </div>

                    </div>

                    <!-- Kode Pos & Detail Alamat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $detail->kode_pos ?? '') }}"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                        </div>

                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">Alamat Lengkap</label>
                            <textarea name="detail_alamat" class="w-full border-2 border-gray-400 rounded-md px-3 py-2 break-words"
                                rows="2">{{ old('detail_alamat', $detail->detail_alamat ?? '') }}</textarea>
                        </div>
                    </div>

                </div>
                

                {{-- ----------------- FORM PELAMAR -------------------- --}}
                <div id="form-pelamar"
                    class="{{ old('role', $user->role ?? '') == 'pelamar' ? '' : 'hidden' }} space-y-4 w-full min-w-0">

                    <h3 class="font-semibold text-gray-700 mt-4 break-words">Data Pelamar</h3>

                    @if (old('role', $user->role ?? '') === 'pelamar')
                        <input type="hidden" name="kategori" value="pelamar">
                    @endif

                    {{-- Nama Pelamar --}}
                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Nama Pelamar</label>
                        <input type="text" name="nama_pelamar"
                            value="{{ old('nama_pelamar', $detail->nama_pelamar ?? '') }}"
                            class="w-full border rounded-md px-3 py-2 truncate" required>
                    </div>

                    {{-- Gender --}}
                    <div class="min-w-0">
                        <label class="block text-md font-medium mb-1 break-words">
                            Gender <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-wrap gap-6 mt-1">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="laki-laki"
                                    class="accent-orange-500 border-2 border-orange-500"
                                    {{ old('gender', $detail->gender ?? '') == 'laki-laki' ? 'checked' : '' }}>
                                <span>Laki-Laki</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="perempuan"
                                    class="accent-orange-500 border-2 border-orange-500"
                                    {{ old('gender', $detail->gender ?? '') == 'perempuan' ? 'checked' : '' }}>
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $detail->tanggal_lahir ?? '') }}"
                            class="w-full border rounded-md px-3 py-2 truncate">
                    </div>

                    {{-- Deskripsi Diri --}}
                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Deskripsi Diri</label>
                        <textarea name="deskripsi_diri" class="w-full border rounded-md px-3 py-2 break-words" rows="2">{{ old('deskripsi_diri', $detail->deskripsi_diri ?? '') }}</textarea>
                    </div>

                    {{-- Kontak --}}
                    <h3 class="font-semibold text-gray-700 mt-4 break-words">Kontak</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">No. Telepon Pelamar</label>
                            <input type="text" name="telepon_pelamar"
                                value="{{ old('telepon_pelamar', $detail->telepon_pelamar ?? '') }}"
                                class="w-full border rounded-md px-3 py-2 truncate">
                        </div>
                    </div>

                    {{-- Lokasi Kerja --}}


                    {{-- Ekspektasi Gaji --}}
                    <div class="min-w-0">
                        <label class="text-lg font-medium break-words">Ekspektasi Gaji</label>

                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <div class="border border-black rounded-md px-4 py-2 text-orange-500 w-32 min-w-0">
                                <span class="text-orange-500">Rp.</span>
                                <input type="number" name="gaji_minimal"
                                    value="{{ old('gaji_minimal', $detail->gaji_minimal ?? '') }}"
                                    class="outline-none border-none w-full truncate">
                            </div>

                            <span>-</span>

                            <div class="border border-black rounded-md px-4 py-2 w-32 min-w-0">
                                <span>Rp.</span>
                                <input type="number" name="gaji_maksimal"
                                    value="{{ old('gaji_maksimal', $detail->gaji_maksimal ?? '') }}"
                                    class="outline-none border-none w-full truncate">
                            </div>
                        </div>
                    </div>

                </div>
 
               
                <!-- ================= FORM PERUSAHAAN ================= -->
                <div id="form-perusahaan" class="hidden space-y-4 w-full min-w-0">

                    <h3 class="font-semibold text-gray-700 mt-4 break-words">Data Perusahaan</h3>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan"
                            value="{{ old('nama_perusahaan', $detail->nama_perusahaan ?? '') }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Jenis Perusahaan</label>
                        <input type="text" name="jenis_perusahaan"
                            value="{{ old('jenis_perusahaan', $detail->jenis_perusahaan ?? '') }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Legalitas</label>
                        <input type="text" name="legalitas" value="{{ old('legalitas', $detail->legalitas ?? '') }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Koin Perusahaan</label>
                        <input type="number" name="koin_perusahaan" value="{{ old('koin_perusahaan', $detail->koin_perusahaan ?? '') }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Website</label>
                        <input type="text" name="website_perusahaan"
                            value="{{ old('website_perusahaan', $detail->website_perusahaan ?? '') }}"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Deskripsi</label>
                        <textarea name="deskripsi" class="w-full border-2 border-gray-400 rounded-md px-3 py-2 break-words" rows="2">{{ old('deskripsi', $detail->deskripsi ?? '') }}</textarea>
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Visi</label>
                        <textarea name="visi" class="w-full border-2 border-gray-400 rounded-md px-3 py-2 break-words" rows="2">{{ old('visi', $detail->visi ?? '') }}</textarea>
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-medium mb-1 break-words">Misi</label>
                        <textarea name="misi" class="w-full border-2 border-gray-400 rounded-md px-3 py-2 break-words" rows="2">{{ old('misi', $detail->misi ?? '') }}</textarea>
                    </div>

                    <h3 class="font-semibold text-gray-700 mt-4 break-words">Kontak</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">No. Telepon Perusahaan</label>
                            <input type="text" name="telepon_perusahaan"
                                value="{{ old('telepon_perusahaan', $detail->telepon_perusahaan ?? '') }}"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                        </div>

                        <div class="min-w-0">
                            <label class="block text-sm font-medium mb-1 break-words">No. Whatsapp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $detail->whatsapp ?? '') }}"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 truncate">
                        </div>
                    </div>

                </div>


                <!-- Tombol -->
                <div class="flex flex-wrap justify-center gap-4 mt-6 text-center">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md w-full md:w-auto break-words">
                        Simpan
                    </button>

                    <a href="{{ route('superadmin.add.user') }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md w-full md:w-auto break-words">
                        Batal
                    </a>
                </div>

            </form>
        </div>
        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

    {{-- Script AJAX Dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinsiSelect = document.getElementById('provinsiSelect');
            const kotaSelect = document.getElementById('kotaSelect');
            const kecamatanSelect = document.getElementById('kecamatanSelect');

            // Saat provinsi berubah
            provinsiSelect.addEventListener('change', function() {
                const provinsiId = this.value;
                kotaSelect.innerHTML = '<option>Memuat...</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                fetch(`{{ route('superadmin.get.kota', '') }}/${provinsiId}`)
                    .then(res => res.json())
                    .then(data => {
                        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                        const options = data.map(k => `<option value="${k.id}">${k.nama}</option>`);
                        kotaSelect.insertAdjacentHTML('beforeend', options.join(''));
                    });
            });

            // Saat kota berubah
            kotaSelect.addEventListener('change', function() {
                const kotaId = this.value;
                kecamatanSelect.innerHTML = '<option>Memuat...</option>';

                fetch(`{{ route('superadmin.get.kecamatan', '') }}/${kotaId}`)
                    .then(res => res.json())
                    .then(data => {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        const options = data.map(k => `<option value="${k.id}">${k.nama}</option>`);
                        kecamatanSelect.insertAdjacentHTML('beforeend', options.join(''));
                    });
            });
        });
    </script>

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
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const roleSelect = document.getElementById("roleSelect");
            const profileImg = document.getElementById("pa");
            const fileInput = document.getElementById("fileinputrole");
            const formAlamat = document.getElementById("form-alamat");
            const formPerusahaan = document.getElementById("form-perusahaan");
            const formPelamar = document.getElementById("form-pelamar");

            const roleImages = {
                "admin": "{{ asset('images/admin-default.png') }}",
                "finance": "{{ asset('images/finance-default.png') }}",
                "perusahaan": "{{ asset('images/company-default.png') }}",
                "pelamar": "{{ asset('images/pelamar-default.png') }}"
            };

            function toggleForms(role) {
                // ambil semua input dari setiap form
                const alamatInputs = formAlamat.querySelectorAll('input, textarea, select');
                const perusahaanInputs = formPerusahaan.querySelectorAll('input, textarea, select');
                const pelamarInputs = formPelamar.querySelectorAll('input, textarea, select');

                // sembunyikan semua form dulu
                formAlamat.classList.add("hidden");
                formPerusahaan.classList.add("hidden");
                formPelamar.classList.add("hidden");

                // hapus atribut required dari semua input (biar tidak error saat hidden)
                alamatInputs.forEach(input => input.removeAttribute('required'));
                perusahaanInputs.forEach(input => input.removeAttribute('required'));
                pelamarInputs.forEach(input => input.removeAttribute('required'));

                // tampilkan dan atur required sesuai role
                if (role === "perusahaan") {
                    formPerusahaan.classList.remove("hidden");
                    formPerusahaan.querySelector('[name="nama_perusahaan"]').setAttribute('required', true);
                } else if (role === "pelamar") {
                    formPelamar.classList.remove("hidden");
                    formPelamar.querySelector('[name="nama_pelamar"]').setAttribute('required', true);
                } else {
                    formAlamat.classList.remove("hidden");
                    const namaLengkap = formAlamat.querySelector('[name="nama_lengkap"]');
                    if (namaLengkap) {
                        namaLengkap.setAttribute('required', true);
                    }
                }
            }

            // Jalankan sekali saat halaman selesai dimuat
            toggleForms(roleSelect.value);

            // Ubah form & gambar profil ketika role berubah
            roleSelect.addEventListener("change", function() {
                const selectedRole = this.value;
                toggleForms(selectedRole);

                if (fileInput.files.length === 0) {
                    profileImg.src = roleImages[selectedRole] ||
                        "https://ui-avatars.com/api/?name=Default&background=00509d&color=fff&size=128";
                }
            });

            // Preview gambar upload
            fileInput.addEventListener("change", function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => profileImg.src = e.target.result;
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
