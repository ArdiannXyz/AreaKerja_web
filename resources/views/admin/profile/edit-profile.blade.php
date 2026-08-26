@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:p-6 sm:ml-64 bg-slate-50 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-note-pencil text-orange-500 text-2xl"></i> Edit Profil Admin
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Perbarui data profil, foto, dan informasi alamat Anda.</p>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto justify-end">
                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative p-2.5 bg-slate-100 hover:bg-orange-50 hover:text-orange-600 rounded-xl text-slate-600 transition shadow-xs">
                    <i class="ph ph-bell text-xl"></i>
                    @if (isset($global_notifikasi_unread) && $global_notifikasi_unread > 0)
                        <span id="notif-badge" class="absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] font-extrabold px-1.5 py-0.5 rounded-full animate-pulse border-2 border-white">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profil Admin Pill --}}
                <div class="flex items-center gap-3 bg-slate-100/80 px-3.5 py-2 rounded-2xl border border-slate-200">
                    @if (Auth::user()?->avatar)
                        <img id="pu" class="w-9 h-9 object-cover rounded-xl border border-slate-200"
                            src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
                    @else
                        <img id="pu" class="w-9 h-9 rounded-xl border border-slate-200"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'Admin') }}&background=f97316&color=fff&size=128">
                    @endif

                    <div class="text-left">
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-slate-800 text-xs leading-tight">{{ Auth::user()->username }}</span>
                            <span class="bg-orange-100 text-orange-700 text-[10px] font-extrabold px-1.5 py-0.2 rounded-md">Admin</span>
                        </div>
                        <p class="text-slate-500 text-[11px] leading-tight mt-0.5">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN FORM CONTAINER -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-xs p-6 md:p-8">

            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-bold space-y-1">
                    <div class="flex items-center gap-1.5 text-sm font-extrabold">
                        <i class="ph ph-warning-circle text-lg"></i> Perhatian: Form Belum Lengkap
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 font-semibold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="profileEditForm" action="{{ route('admin.update.profile', Auth::user()->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- AVATAR UPLOAD SECTION -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-6 border-b border-slate-100">
                    <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                        @if (Auth::user()->admin && Auth::user()->admin->img_profile)
                            <img id="pa" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-2xl border-2 border-orange-500/20 shadow-xs"
                                src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
                        @else
                            <img id="pa" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-2xl border-2 border-orange-500/20 shadow-xs"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=f97316&color=fff&size=128"
                                alt="Profile">
                        @endif

                        <div>
                            <h3 class="font-extrabold text-lg text-slate-900">{{ Auth::user()->username }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Unggah foto profil baru dengan format JPG atau PNG.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="file" name="img_profile" id="fileinputadmin" accept="image/*" class="hidden">

                        <button type="button" onclick="document.getElementById('fileinputadmin').click();"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                            <i class="ph ph-upload-simple text-base"></i> Unggah Foto
                        </button>

                        <button type="button"
                            onclick="event.preventDefault(); document.getElementById('removeadminForm').submit();"
                            class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-2">
                            <i class="ph ph-trash text-base"></i> Hapus Foto
                        </button>
                    </div>
                </div>

                <!-- FORM INPUTS -->
                <div class="space-y-6">

                    <!-- EMAIL & USERNAME -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email</label>
                                <span class="text-[10px] font-extrabold px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md border border-slate-200 flex items-center gap-1">
                                    <i class="ph ph-lock-key"></i> Terverifikasi & Terkunci
                                </span>
                            </div>
                            <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                                class="w-full border border-slate-300 bg-slate-100 text-slate-500 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Username <span class="text-rose-500">*</span></label>
                            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" required
                                class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition">
                        </div>
                    </div>

                    <!-- NAMA LENGKAP -->
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->admin->nama_lengkap ?? '') }}" required
                            placeholder="Masukkan Nama Lengkap Anda"
                            class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition">
                    </div>

                    <!-- ALAMAT SECTION HEADER -->
                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                            <i class="ph ph-map-pin text-base text-orange-500"></i> Detail Alamat & Lokasi
                        </h3>

                        <!-- PROVINSI, KOTA, KECAMATAN -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Provinsi <span class="text-rose-500">*</span></label>
                                <select id="provinsiSelect" name="provinsi_id" required
                                    class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinsis as $prov)
                                        <option value="{{ $prov->id }}"
                                            {{ (string)(old('provinsi_id', $data->provinsi_id ?? '')) === (string)$prov->id ? 'selected' : '' }}>
                                            {{ $prov->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                                <select id="kotaSelect" name="kota_id" required
                                    class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition bg-white">
                                    <option value="">Pilih Kota</option>
                                    @if (isset($data->kota) && $data->kota)
                                        <option value="{{ $data->kota_id }}" selected>{{ $data->kota->nama }}</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Kecamatan <span class="text-rose-500">*</span></label>
                                <select id="kecamatanSelect" name="kecamatan_id" required
                                    class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition bg-white">
                                    <option value="">Pilih Kecamatan</option>
                                    @if (isset($data->kecamatan) && $data->kecamatan)
                                        <option value="{{ $data->kecamatan_id }}" selected>{{ $data->kecamatan->nama }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- DESA & KODE POS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Desa / Kelurahan</label>
                                <input type="text" name="desa" value="{{ old('desa', Auth::user()->admin->desa ?? '') }}"
                                    placeholder="Masukkan Desa / Kelurahan"
                                    class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition">
                            </div>

                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Kode Pos</label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos', Auth::user()->admin->kode_pos ?? '') }}"
                                    placeholder="Masukkan Kode Pos"
                                    class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition">
                            </div>
                        </div>

                        <!-- DETAIL ALAMAT -->
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-slate-600">Alamat Lengkap</label>
                            <input type="text" name="detail_alamat" value="{{ old('detail_alamat', Auth::user()->admin->detail_alamat ?? '') }}"
                                placeholder="Contoh: Jl. Area Kerja No. 123"
                                class="w-full border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition">
                        </div>
                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.profile') }}"
                        class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-xs hover:shadow-md transition flex items-center gap-2">
                        <i class="ph ph-floppy-disk text-base"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

        <form id="removeadminForm" action="{{ route('admin.destroy.profile', Auth::user()->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Script AJAX Dinamis & Validation Alert --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileEditForm = document.getElementById('profileEditForm');
            const provinsiSelect = document.getElementById('provinsiSelect');
            const kotaSelect = document.getElementById('kotaSelect');
            const kecamatanSelect = document.getElementById('kecamatanSelect');
            let isConfirmedSubmit = false;

            if (profileEditForm) {
                profileEditForm.addEventListener('submit', function(e) {
                    if (isConfirmedSubmit) return true;

                    e.preventDefault();

                    if (!provinsiSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dropdown Alamat Belum Lengkap',
                            text: 'Harap pilih Provinsi terlebih dahulu!',
                            confirmButtonColor: '#f97316'
                        }).then(() => provinsiSelect.focus());
                        return false;
                    }
                    if (!kotaSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dropdown Alamat Belum Lengkap',
                            text: 'Harap pilih Kota / Kabupaten terlebih dahulu!',
                            confirmButtonColor: '#f97316'
                        }).then(() => kotaSelect.focus());
                        return false;
                    }
                    if (!kecamatanSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Dropdown Alamat Belum Lengkap',
                            text: 'Harap pilih Kecamatan terlebih dahulu!',
                            confirmButtonColor: '#f97316'
                        }).then(() => kecamatanSelect.focus());
                        return false;
                    }

                    Swal.fire({
                        title: 'Simpan Perubahan Profil & Alamat?',
                        text: 'Pastikan data alamat dan profil yang Anda masukkan sudah benar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#f97316',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Simpan Sekarang!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            isConfirmedSubmit = true;
                            profileEditForm.submit();
                        }
                    });
                });
            }

            if (provinsiSelect) {
                provinsiSelect.addEventListener('change', function() {
                    const provinsiId = this.value;
                    kotaSelect.innerHTML = '<option value="">Memuat Kota...</option>';
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                    if (!provinsiId) {
                        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                        return;
                    }

                    fetch(`{{ route('admin.get.kota', '') }}/${provinsiId}`)
                        .then(res => res.json())
                        .then(data => {
                            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                            const options = data.map(k => `<option value="${k.id}">${k.nama}</option>`);
                            kotaSelect.insertAdjacentHTML('beforeend', options.join(''));
                        })
                        .catch(() => {
                            kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                        });
                });
            }

            if (kotaSelect) {
                kotaSelect.addEventListener('change', function() {
                    const kotaId = this.value;
                    kecamatanSelect.innerHTML = '<option value="">Memuat Kecamatan...</option>';

                    if (!kotaId) {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        return;
                    }

                    fetch(`{{ route('admin.get.kecamatan', '') }}/${kotaId}`)
                        .then(res => res.json())
                        .then(data => {
                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                            const options = data.map(k => `<option value="${k.id}">${k.nama}</option>`);
                            kecamatanSelect.insertAdjacentHTML('beforeend', options.join(''));
                        })
                        .catch(() => {
                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        });
                });
            }
        });
    </script>
@endsection
