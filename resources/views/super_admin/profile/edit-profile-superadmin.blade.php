@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-[#f3f4f6] min-h-screen overflow-y-auto"
        x-data="{ openNotif: false, openAllNotif: false }">

        {{-- TOP BAR --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-semibold text-gray-800">Profile</h1>

            <div class="flex items-center gap-3">
                {{-- Bell --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        {{-- ERROR ALERTS --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-300 text-red-700 text-sm px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- CARD --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm max-w-4xl">
            <h2 class="text-base font-semibold text-gray-800 mb-5">Edit Profile</h2>

            {{-- FORM --}}
            <form action="{{ route('superadmin.update.profile', Auth::user()->superadmin->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- AVATAR ROW --}}
                <div class="flex items-center gap-4 mb-6">
                    {{-- Avatar preview --}}
                    @if (Auth::user()->superadmin->img_profile)
                        <img id="avatar-preview"
                            class="w-16 h-16 rounded-full object-cover flex-shrink-0 ring-2 ring-gray-200"
                            src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                    @else
                        <div id="avatar-initials"
                            class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center flex-shrink-0 ring-2 ring-gray-200">
                            <span class="text-white text-xl font-bold">
                                {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                            </span>
                        </div>
                        <img id="avatar-preview"
                            class="w-16 h-16 rounded-full object-cover flex-shrink-0 ring-2 ring-gray-200 hidden"
                            src="" alt="Profile">
                    @endif

                    {{-- Name + email --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">
                            {{ Auth::user()->superadmin->nama_lengkap ?: Auth::user()->username }}
                        </p>
                        <p class="text-[#00509d] text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- Upload / Remove buttons --}}
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        {{-- Hidden file input --}}
                        <input type="file" name="img_profile" id="img_profile_input"
                            accept="image/png,image/jpeg,image/jpg" class="hidden">

                        <button type="button" onclick="document.getElementById('img_profile_input').click()"
                            class="flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-medium px-4 py-1.5 rounded-md transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Upload
                        </button>

                        <button type="button" onclick="document.getElementById('remove-photo-form').submit()"
                            class="flex items-center gap-1.5 border border-gray-300 text-gray-600 hover:bg-gray-50 text-xs font-medium px-4 py-1.5 rounded-md transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>

                {{-- FIELDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Email (locked) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-400 bg-gray-50 cursor-not-allowed"
                            value="{{ Auth::user()->email }}" disabled readonly>
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username"
                            value="{{ old('username', Auth::user()->username) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                            placeholder="Masukkan username">
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap"
                            value="{{ old('nama_lengkap', Auth::user()->superadmin->nama_lengkap) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    {{-- Provinsi / Kota / Kecamatan --}}
                    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <div class="relative">
                                <input type="text" name="provinsi"
                                    value="{{ old('provinsi', Auth::user()->superadmin->provinsi) }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 pr-8 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                                    placeholder="Masukkan provinsi">
                                <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                            <div class="relative">
                                <input type="text" name="kota"
                                    value="{{ old('kota', Auth::user()->superadmin->kota) }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 pr-8 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                                    placeholder="Masukkan kota/kabupaten">
                                <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <div class="relative">
                                <input type="text" name="kecamatan"
                                    value="{{ old('kecamatan', Auth::user()->superadmin->kecamatan) }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 pr-8 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                                    placeholder="Masukkan kecamatan">
                                <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Desa / Kode Pos --}}
                    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Desa</label>
                            <input type="text" name="desa"
                                value="{{ old('desa', Auth::user()->superadmin->desa) }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                                placeholder="-">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos"
                                value="{{ old('kode_pos', Auth::user()->superadmin->kode_pos) }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                                placeholder="Contoh: 55284">
                        </div>
                    </div>

                    {{-- Detail Lainnya --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Detail Lainnya</label>
                        <input type="text" name="detail_alamat"
                            value="{{ old('detail_alamat', Auth::user()->superadmin->detail_alamat) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent"
                            placeholder="Contoh: Jl. Perintis Kemerdekaan No. 118">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="md:col-span-2 flex flex-col sm:flex-row justify-center items-center gap-3 mt-2">
                        <button type="submit"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-medium px-10 py-2.5 rounded-lg transition duration-200 w-full sm:w-auto">
                            Simpan
                        </button>
                        <a href="{{ route('superadmin.profile') }}"
                            class="border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium px-10 py-2.5 rounded-lg transition duration-200 w-full sm:w-auto text-center">
                            Batal
                        </a>
                    </div>

                </div>
            </form>
        </div>

        {{-- Hidden remove-photo form --}}
        @if (Auth::user()->superadmin->id)
            <form id="remove-photo-form"
                action="{{ route('superadmin.destroy.profile', Auth::user()->superadmin->id) }}"
                method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

    {{-- Live avatar preview on file select --}}
    <script>
        document.getElementById('img_profile_input')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                const preview = document.getElementById('avatar-preview');
                const initials = document.getElementById('avatar-initials');

                if (preview) {
                    preview.src = ev.target.result;
                    preview.classList.remove('hidden');
                }
                if (initials) {
                    initials.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        });
    </script>
@endsection
