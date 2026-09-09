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

        {{-- SUCCESS / ERROR ALERTS --}}
        @if (session('success'))
            <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-300 text-green-700 text-sm px-4 py-3 rounded-lg">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- CARD --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm max-w-4xl">
            <h2 class="text-base font-semibold text-gray-800 mb-5">Edit Profile</h2>

            {{-- AVATAR + NAME --}}
            <div class="flex items-center gap-4 mb-6">
                @if (Auth::user()->superadmin->img_profile)
                    <img class="w-16 h-16 rounded-full object-cover flex-shrink-0 ring-2 ring-gray-200"
                        src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center flex-shrink-0 ring-2 ring-gray-200">
                        <span class="text-white text-xl font-bold">
                            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-gray-800 text-sm">
                        {{ Auth::user()->superadmin->nama_lengkap ?: Auth::user()->username }}
                    </p>
                    <p class="text-[#00509d] text-sm">{{ Auth::user()->email }}</p>
                </div>
            </div>

            {{-- FORM (read-only view) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"
                        value="{{ Auth::user()->email }}" disabled>
                </div>

                {{-- Username --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"
                        value="{{ Auth::user()->username }}" disabled>
                </div>

                {{-- Nama Lengkap --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"
                        value="{{ Auth::user()->superadmin->nama_lengkap ?: '-' }}" disabled>
                </div>

                {{-- Provinsi / Kota / Kecamatan --}}
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white pr-8"
                                value="{{ Auth::user()->superadmin->provinsi ?: '-' }}" disabled>
                            <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kota/Kabupaten <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white pr-8"
                                value="{{ Auth::user()->superadmin->kota ?: '-' }}" disabled>
                            <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white pr-8"
                                value="{{ Auth::user()->superadmin->kecamatan ?: '-' }}" disabled>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Desa <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"
                            value="{{ Auth::user()->superadmin->desa ?: '-' }}" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Pos <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"
                            value="{{ Auth::user()->superadmin->kode_pos ?: '-' }}" disabled>
                    </div>
                </div>

                {{-- Detail Lainnya --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Detail Lainnya <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"
                        value="{{ Auth::user()->superadmin->detail_alamat ?: '-' }}" disabled>
                </div>

                {{-- Button Edit --}}
                <div class="md:col-span-2 flex justify-center mt-2">
                    <a href="{{ route('superadmin.edit.profile') }}"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-medium px-10 py-2.5 rounded-lg transition duration-200">
                        Edit
                    </a>
                </div>

            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
