@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        {{-- Header Top Bar --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.profile') }}"
                   class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-base"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Profil / <span class="text-slate-600 font-semibold">Edit Profil</span></p>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-tight">Edit Profil Admin</h1>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        {{-- Error Alerts --}}
        @if ($errors->any())
            <div class="max-w-4xl mx-auto mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3.5 rounded-2xl shadow-xs text-xs font-semibold space-y-1">
                <div class="flex items-center gap-1.5 text-sm font-bold">
                    <i class="ph ph-warning-circle text-lg"></i> Mohon Periksa Input Form
                </div>
                <ul class="list-disc list-inside space-y-0.5 font-medium pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main Form Card --}}
        <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8">

            <form id="profileEditForm" action="{{ route('admin.update.profile', Auth::user()->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Avatar Upload Section --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-6 border-b border-slate-100">
                    <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                        @php
                            $profileImg = Auth::user()->avatar ?? (Auth::user()->admin?->img_profile ?? null);
                        @endphp

                        <div class="relative">
                            <img id="avatar-preview"
                                class="w-20 h-20 rounded-2xl object-cover ring-2 ring-slate-200 shadow-xs {{ $profileImg ? '' : 'hidden' }}"
                                src="{{ $profileImg ? asset('storage/' . $profileImg) : '' }}" alt="Profile Photo">

                            <div id="avatar-initials"
                                class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-[#00509d] to-[#003d7a] flex items-center justify-center text-white font-bold text-2xl shadow-xs {{ $profileImg ? 'hidden' : '' }}">
                                {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 2)) }}
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-base text-slate-900">{{ Auth::user()->username }}</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Format foto: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <input type="file" name="img_profile" id="fileinputadmin" accept="image/*" class="hidden">

                        <button type="button" onclick="document.getElementById('fileinputadmin').click();"
                            class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-xs px-4 py-2 rounded-xl shadow-xs transition">
                            <i class="ph ph-upload-simple text-sm"></i> Unggah Foto
                        </button>

                        @if ($profileImg)
                            <button type="button" onclick="confirmRemovePhoto()"
                                class="inline-flex items-center gap-1.5 border border-slate-200 text-rose-600 hover:bg-rose-50 hover:border-rose-200 font-semibold text-xs px-4 py-2 rounded-xl transition">
                                <i class="ph ph-trash text-sm"></i> Hapus
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="space-y-5">

                    {{-- Row 1: Email & Username --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email</label>
                                <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">
                                    <i class="ph ph-lock-key"></i> Terkunci
                                </span>
                            </div>
                            <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                                class="w-full border border-slate-200 bg-slate-50 text-slate-500 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Username <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" required
                                class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 transition">
                        </div>
                    </div>

                    {{-- Row 2: Nama Lengkap --}}
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->admin?->nama_lengkap ?? '') }}" required
                            placeholder="Masukkan Nama Lengkap Anda"
                            class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 transition">
                    </div>

                    {{-- Section Alamat --}}
                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                            <i class="ph ph-map-pin text-base text-[#00509d]"></i> Detail Alamat & Lokasi
                        </h3>

                        {{-- Provinsi, Kota, Kecamatan --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Provinsi <span class="text-rose-500">*</span></label>
                                <select id="provinsiSelect" name="provinsi_id" required
                                    class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 transition bg-white">
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
                                    class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 transition bg-white">
                                    <option value="">Pilih Kota</option>
                                    @if (isset($data->kota) && $data->kota)
                                        <option value="{{ $data->kota_id }}" selected>{{ $data->kota->nama }}</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Kecamatan <span class="text-rose-500">*</span></label>
                                <select id="kecamatanSelect" name="kecamatan_id" required
                                    class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 transition bg-white">
                                    <option value="">Pilih Kecamatan</option>
                                    @if (isset($data->kecamatan) && $data->kecamatan)
                                        <option value="{{ $data->kecamatan_id }}" selected>{{ $data->kecamatan->nama }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Desa & Kode Pos --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Desa / Kelurahan</label>
                                <input type="text" name="desa" value="{{ old('desa', Auth::user()->admin?->desa ?? '') }}"
                                    placeholder="Masukkan Desa / Kelurahan"
                                    class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 transition">
                            </div>

                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-600">Kode Pos</label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos', Auth::user()->admin?->kode_pos ?? '') }}"
                                    placeholder="Masukkan Kode Pos"
                                    class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 transition">
                            </div>
                        </div>

                        {{-- Detail Alamat --}}
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-slate-600">Alamat Lengkap</label>
                            <input type="text" name="detail_alamat" value="{{ old('detail_alamat', Auth::user()->admin?->detail_alamat ?? '') }}"
                                placeholder="Contoh: Jl. Area Kerja No. 123"
                                class="w-full border border-slate-200 focus:border-[#00509d] focus:ring-2 focus:ring-[#00509d]/20 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 transition">
                        </div>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.profile') }}"
                        class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-xs rounded-xl transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-xs rounded-xl shadow-xs transition">
                        <i class="ph ph-floppy-disk text-sm"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

        {{-- Hidden Form for Removing Profile Photo --}}
        <form id="removeadminForm" action="{{ route('admin.destroy.profile', Auth::user()->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Image Live Preview
        document.getElementById('fileinputadmin')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const preview = document.getElementById('avatar-preview');
                    const initials = document.getElementById('avatar-initials');
                    if (preview) {
                        preview.src = evt.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (initials) {
                        initials.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Confirm Remove Photo
        function confirmRemovePhoto() {
            Swal.fire({
                title: 'Hapus Foto Profil?',
                text: 'Foto profil Anda akan dihapus dan kembali ke inisial nama.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('removeadminForm').submit();
                }
            });
        }

        // Dependent dropdowns & submit validation
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
                            title: 'Pilih Provinsi',
                            text: 'Harap pilih Provinsi terlebih dahulu!',
                            confirmButtonColor: '#00509d'
                        }).then(() => provinsiSelect.focus());
                        return false;
                    }
                    if (!kotaSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Kota / Kabupaten',
                            text: 'Harap pilih Kota / Kabupaten terlebih dahulu!',
                            confirmButtonColor: '#00509d'
                        }).then(() => kotaSelect.focus());
                        return false;
                    }
                    if (!kecamatanSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Kecamatan',
                            text: 'Harap pilih Kecamatan terlebih dahulu!',
                            confirmButtonColor: '#00509d'
                        }).then(() => kecamatanSelect.focus());
                        return false;
                    }

                    Swal.fire({
                        title: 'Simpan Perubahan?',
                        text: 'Pastikan data profil dan alamat sudah benar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#00509d',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Simpan',
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
