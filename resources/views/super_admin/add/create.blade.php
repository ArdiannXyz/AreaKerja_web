@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-gray-50/50 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">
        {{-- Topbar Header --}}
        <div class="flex justify-between items-center mb-8 flex-col sm:flex-row gap-4 sm:gap-0 border-b border-gray-100 pb-5">
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.add.user') }}"
                    class="p-2 rounded-xl border border-gray-200 hover:bg-gray-100 text-gray-600 transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Pengguna Baru</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Daftarkan akun baru untuk Admin, Finance, Perusahaan, atau Pelamar</p>
                </div>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        {{-- Alerts --}}
        @if ($errors->any())
            <div class="max-w-3xl mx-auto mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm">
                <div class="flex items-center gap-2 font-semibold mb-2">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Terdapat beberapa kesalahan:
                </div>
                <ul class="list-disc ml-6 space-y-1 text-xs">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Card Modern --}}
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-10 mb-10">
            <form action="{{ route('superadmin.add.user.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Foto Profil Uploader -->
                <div class="flex flex-col items-center justify-center pb-6 border-b border-gray-100">
                    <div class="relative group cursor-pointer">
                        <label for="fileinputrole" class="cursor-pointer block">
                            <img id="pa"
                                class="w-28 h-28 sm:w-32 sm:h-32 object-cover rounded-full shadow-sm border-2 border-gray-200 group-hover:opacity-90 transition duration-150"
                                src="https://ui-avatars.com/api/?name=User&background=00509d&color=fff&size=128"
                                alt="Profile">
                            <div class="absolute bottom-1 right-1 bg-[#00509d] text-white p-2 rounded-full shadow-md hover:bg-[#003d7a] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </label>
                        <input id="fileinputrole" type="file" name="img_profile" class="hidden" accept="image/*">
                    </div>
                    <span class="text-xs text-gray-400 mt-3">Klik ikon untuk mengunggah foto profil (JPG/PNG, Maks. 2MB)</span>
                </div>

                <!-- Informasi Dasar Akun -->
                <div>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        1. Informasi Akun
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Username <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}" required placeholder="username_pengguna"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" required placeholder="Minimal 6 karakter"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Peran / Role <span class="text-rose-500">*</span>
                            </label>
                            <select name="role" id="roleSelect" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition bg-white">
                                <option value="">-- Pilih Peran Akun --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="finance" {{ old('role') == 'finance' ? 'selected' : '' }}>Finance</option>
                                <option value="perusahaan" {{ old('role') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                                <option value="pelamar" {{ old('role') == 'pelamar' ? 'selected' : '' }}>Pelamar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ================= SECTION ADMIN / FINANCE ================= -->
                <div id="form-alamat" class="space-y-5 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        2. Biodata & Wilayah Penugasan
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap staf"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Provinsi</label>
                            <select name="provinsi_id" id="provinsi"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition bg-white">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsis as $provinsi)
                                    <option value="{{ $provinsi->id }}">{{ $provinsi->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Kota/Kabupaten</label>
                            <select name="kota_id" id="kota"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition bg-white">
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatan"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition bg-white">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Kode Pos</label>
                            <input type="number" name="kode_pos" value="{{ old('kode_pos') }}" placeholder="55281"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <input type="text" name="detail_alamat" value="{{ old('detail_alamat') }}" placeholder="Jalan, RT/RW, No. Rumah"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- ================= SECTION PERUSAHAAN ================= -->
                <div id="form-perusahaan" class="hidden space-y-5 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        2. Data Profil Perusahaan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nama Perusahaan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" placeholder="PT Contoh Sukses"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Jenis Bidang Usaha</label>
                            <input type="text" name="jenis_perusahaan" value="{{ old('jenis_perusahaan') }}" placeholder="Teknologi / Manufaktur"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Legalitas Perusahaan</label>
                            <input type="text" name="legalitas" value="{{ old('legalitas') }}" placeholder="PT / CV / Firma"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Website Perusahaan</label>
                            <input type="text" name="website_perusahaan" value="{{ old('website_perusahaan') }}" placeholder="https://perusahaan.com"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">No. Telepon Kantor</label>
                            <input type="text" name="telepon_perusahaan" value="{{ old('telepon_perusahaan') }}" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">No. WhatsApp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="2" placeholder="Gambaran umum perusahaan..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <!-- ================= SECTION PELAMAR ================= -->
                <div id="form-pelamar" class="hidden space-y-5 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        2. Data Profil Pelamar
                    </h3>

                    <input type="hidden" name="kategori" value="pelamar">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nama Pelamar <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_pelamar" value="{{ old('nama_pelamar') }}" placeholder="Nama lengkap pelamar"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-medium text-gray-700">
                                    <input type="radio" name="gender" value="laki-laki" class="accent-[#00509d] w-4 h-4"
                                        {{ old('gender') == 'laki-laki' ? 'checked' : '' }}>
                                    <span>Laki-Laki</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-medium text-gray-700">
                                    <input type="radio" name="gender" value="perempuan" class="accent-[#00509d] w-4 h-4"
                                        {{ old('gender') == 'perempuan' ? 'checked' : '' }}>
                                    <span>Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">No. Telepon / WA</label>
                            <input type="text" name="telepon_pelamar" value="{{ old('telepon_pelamar') }}" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Deskripsi Diri Singkat</label>
                        <textarea name="deskripsi_diri" rows="2" placeholder="Pengalaman dan ringkasan kemampuan..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition">{{ old('deskripsi_diri') }}</textarea>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('superadmin.add.user') }}"
                        class="w-full sm:w-auto px-6 py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs sm:text-sm font-semibold rounded-xl text-center transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs sm:text-sm font-semibold rounded-xl transition duration-150 flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cascading Wilayah (Provinsi -> Kota -> Kecamatan)
            const provinsiSelect = document.getElementById('provinsi');
            const kotaSelect = document.getElementById('kota');
            const kecamatanSelect = document.getElementById('kecamatan');

            if (provinsiSelect && kotaSelect && kecamatanSelect) {
                provinsiSelect.addEventListener('change', function() {
                    const provinsiId = this.value;
                    kotaSelect.innerHTML = '<option value="">Memuat...</option>';
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                    if (!provinsiId) {
                        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                        return;
                    }

                    fetch(`/get-kota/${provinsiId}`)
                        .then(res => res.json())
                        .then(data => {
                            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                            data.forEach(kota => {
                                kotaSelect.innerHTML += `<option value="${kota.id}">${kota.nama}</option>`;
                            });
                        })
                        .catch(() => {
                            kotaSelect.innerHTML = '<option value="">Gagal memuat kota</option>';
                        });
                });

                kotaSelect.addEventListener('change', function() {
                    const kotaId = this.value;
                    kecamatanSelect.innerHTML = '<option value="">Memuat...</option>';

                    if (!kotaId) {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        return;
                    }

                    fetch(`/get-kecamatan/${kotaId}`)
                        .then(res => res.json())
                        .then(data => {
                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                            data.forEach(kec => {
                                kecamatanSelect.innerHTML += `<option value="${kec.id}">${kec.nama}</option>`;
                            });
                        })
                        .catch(() => {
                            kecamatanSelect.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
                        });
                });
            }

            // Role Switcher
            const roleSelect = document.getElementById("roleSelect");
            const profileImg = document.getElementById("pa");
            const fileInput = document.getElementById("fileinputrole");
            const formAlamat = document.getElementById("form-alamat");
            const formPerusahaan = document.getElementById("form-perusahaan");
            const formPelamar = document.getElementById("form-pelamar");

            function toggleForms(role) {
                if (!formAlamat || !formPerusahaan || !formPelamar) return;

                formAlamat.classList.add("hidden");
                formPerusahaan.classList.add("hidden");
                formPelamar.classList.add("hidden");

                if (role === "perusahaan") {
                    formPerusahaan.classList.remove("hidden");
                } else if (role === "pelamar") {
                    formPelamar.classList.remove("hidden");
                } else {
                    formAlamat.classList.remove("hidden");
                }
            }

            if (roleSelect) {
                toggleForms(roleSelect.value);
                roleSelect.addEventListener("change", function() {
                    toggleForms(this.value);
                });
            }

            // Preview upload foto profil
            if (fileInput && profileImg) {
                fileInput.addEventListener("change", function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => profileImg.src = e.target.result;
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection
