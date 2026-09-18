@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        {{-- Header Top Bar --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-tight">Profil Admin</h1>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Informasi akun dan data alamat administrator Anda</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('admin.edit.profile') }}"
                   class="inline-flex items-center gap-1.5 border border-slate-200 text-slate-700 hover:bg-[#00509d] hover:text-white hover:border-[#00509d] text-xs font-semibold px-4 py-2 rounded-xl transition shadow-xs">
                    <i class="ph ph-pencil-simple text-sm"></i>
                    Edit Profil
                </a>
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="max-w-4xl mx-auto mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold px-4 py-3.5 rounded-2xl shadow-xs">
                <i class="ph ph-check-circle text-lg text-emerald-600 shrink-0"></i>
                <div class="flex-1">{{ session('success') }}</div>
            </div>
        @endif

        {{-- Main Profile Card --}}
        <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8">

            {{-- Avatar & Basic Info --}}
            <div class="flex flex-col sm:flex-row items-center gap-5 pb-6 border-b border-slate-100 mb-6 text-center sm:text-left">
                @php
                    $profileImg = Auth::user()->avatar ?? (Auth::user()->admin?->img_profile ?? null);
                @endphp

                @if ($profileImg)
                    <img class="w-20 h-20 rounded-2xl object-cover ring-2 ring-slate-100 shadow-xs"
                        src="{{ asset('storage/' . $profileImg) }}" alt="Profile Photo">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-[#00509d] to-[#003d7a] flex items-center justify-center text-white font-bold text-2xl shadow-xs">
                        {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 2)) }}
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-center sm:justify-start gap-2">
                        <h2 class="font-bold text-lg text-slate-900 truncate">
                            {{ Auth::user()->admin?->nama_lengkap ?: Auth::user()->username }}
                        </h2>
                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-[#00509d] font-bold text-[10px] rounded-lg border border-blue-100">
                            Administrator
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 mt-1 flex items-center justify-center sm:justify-start gap-1.5">
                        <i class="ph ph-envelope-simple text-sm text-slate-400"></i> {{ Auth::user()->email }}
                    </p>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5 flex items-center justify-center sm:justify-start gap-1">
                        <i class="ph ph-user text-xs"></i> @<span>{{ Auth::user()->username }}</span>
                    </p>
                </div>
            </div>

            {{-- Form Fields (Read-Only) --}}
            <div class="space-y-6">

                {{-- Row 1: Email & Username --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Email</label>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">
                                <i class="ph ph-lock-key"></i> Terkunci
                            </span>
                        </div>
                        <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                            class="w-full border border-slate-200 bg-slate-50 text-slate-600 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-slate-600 uppercase tracking-wider">Username</label>
                        <input type="text" value="{{ Auth::user()->username }}" disabled readonly
                            class="w-full border border-slate-200 bg-slate-50 text-slate-600 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                    </div>
                </div>

                {{-- Row 2: Nama Lengkap --}}
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" value="{{ Auth::user()->admin?->nama_lengkap ?? '-' }}" disabled readonly
                        class="w-full border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                </div>

                {{-- Section Alamat --}}
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <i class="ph ph-map-pin text-base text-[#00509d]"></i> Detail Alamat & Lokasi
                    </h3>

                    {{-- Provinsi, Kota, Kecamatan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Provinsi</label>
                            <input type="text" disabled readonly
                                value="{{ Auth::user()->admin?->provinsi?->nama ?? 'Belum Dilengkapi' }}"
                                class="w-full border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Kota / Kabupaten</label>
                            <input type="text" disabled readonly
                                value="{{ Auth::user()->admin?->kota?->nama ?? 'Belum Dilengkapi' }}"
                                class="w-full border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Kecamatan</label>
                            <input type="text" disabled readonly
                                value="{{ Auth::user()->admin?->kecamatan?->nama ?? 'Belum Dilengkapi' }}"
                                class="w-full border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                        </div>
                    </div>

                    {{-- Desa & Kode Pos --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Desa / Kelurahan</label>
                            <input type="text" disabled readonly value="{{ Auth::user()->admin?->desa ?? '-' }}"
                                class="w-full border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Kode Pos</label>
                            <input type="text" disabled readonly value="{{ Auth::user()->admin?->kode_pos ?? '-' }}"
                                class="w-full border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                        </div>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-slate-500">Alamat Lengkap</label>
                        <input type="text" disabled readonly value="{{ Auth::user()->admin?->detail_alamat ?? '-' }}"
                            class="w-full border border-slate-200 bg-slate-50 text-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold cursor-not-allowed select-none">
                    </div>
                </div>

            </div>

        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>
@endsection
