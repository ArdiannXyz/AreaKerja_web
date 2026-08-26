@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:p-6 sm:ml-64 bg-slate-50 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-user text-orange-500 text-2xl"></i> Profil Admin
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Kelola data profil dan alamat akun administrator Anda.</p>
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

        <!-- SUCCESS POP-UP ALERT TOAST -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-emerald-600 text-white px-5 py-4 rounded-2xl shadow-2xl border border-emerald-500 transition-all duration-300 transform translate-y-0">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center font-bold text-white shrink-0">
                    <i class="ph ph-check-circle text-2xl"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm leading-tight">Pembaruan Berhasil!</h4>
                    <p class="text-xs text-emerald-100 font-semibold mt-0.5">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-4 text-emerald-200 hover:text-white transition p-1">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>
        @endif

        <!-- MAIN PROFILE CARD CONTAINER -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-xs p-6 md:p-8">

            <!-- AVATAR & BASIC INFO HEADER -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-6 border-b border-slate-100 mb-6">
                <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                    @if (Auth::user()->admin && Auth::user()->admin->img_profile)
                        <img id="pu" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-2xl border-2 border-orange-500/20 shadow-xs"
                            src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
                    @else
                        <img id="pu" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-2xl border-2 border-orange-500/20 shadow-xs"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=f97316&color=fff&size=128"
                            alt="Profile">
                    @endif

                    <div>
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <h2 class="font-extrabold text-xl text-slate-900">{{ Auth::user()->username }}</h2>
                            <span class="px-2.5 py-0.5 bg-orange-100 text-orange-700 font-extrabold text-xs rounded-full">Administrator</span>
                        </div>
                        <p class="text-sm font-semibold text-slate-500 mt-1 flex items-center justify-center sm:justify-start gap-1">
                            <i class="ph ph-envelope-simple text-base text-slate-400"></i> {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>

                <a href="{{ url('/admin/edit/profile') }}"
                    class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-xs hover:shadow-md transition flex items-center gap-2">
                    <i class="ph ph-note-pencil text-base"></i> Edit Profil
                </a>
            </div>

            <!-- DETAIL INFORMATION FORM (READ-ONLY) -->
            <div class="space-y-6">

                <!-- EMAIL & USERNAME -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Email</label>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md border border-slate-200 flex items-center gap-1">
                                <i class="ph ph-lock-key"></i> Terverifikasi & Terkunci
                            </span>
                        </div>
                        <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                            class="w-full border border-slate-200 bg-slate-100 text-slate-500 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-slate-600 uppercase tracking-wider">Username</label>
                        <input type="text" value="{{ Auth::user()->username }}" disabled readonly
                            class="w-full border border-slate-200 bg-slate-100 text-slate-500 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                    </div>
                </div>

                <!-- NAMA LENGKAP -->
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" value="{{ Auth::user()->admin->nama_lengkap ?? '-' }}" disabled readonly
                        class="w-full border border-slate-200 bg-slate-100 text-slate-700 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                </div>

                <!-- ALAMAT SECTION HEADER -->
                <div class="pt-2 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <i class="ph ph-map-pin text-base text-orange-500"></i> Detail Alamat & Lokasi
                    </h3>

                    <!-- PROVINSI, KOTA, KECAMATAN -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Provinsi</label>
                            <input type="text" disabled readonly
                                value="{{ Auth::user()->admin->provinsi->nama ?? 'Belum Dilengkapi' }}"
                                class="w-full border border-slate-200 bg-slate-100 text-slate-700 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Kota / Kabupaten</label>
                            <input type="text" disabled readonly
                                value="{{ Auth::user()->admin->kota->nama ?? 'Belum Dilengkapi' }}"
                                class="w-full border border-slate-200 bg-slate-100 text-slate-700 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Kecamatan</label>
                            <input type="text" disabled readonly
                                value="{{ Auth::user()->admin->kecamatan->nama ?? 'Belum Dilengkapi' }}"
                                class="w-full border border-slate-200 bg-slate-100 text-slate-700 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                        </div>
                    </div>

                    <!-- DESA & KODE POS -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Desa / Kelurahan</label>
                            <input type="text" disabled readonly value="{{ Auth::user()->admin->desa ?? '-' }}"
                                class="w-full border border-slate-200 bg-slate-100 text-slate-700 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-semibold text-slate-500">Kode Pos</label>
                            <input type="text" disabled readonly value="{{ Auth::user()->admin->kode_pos ?? '-' }}"
                                class="w-full border border-slate-200 bg-slate-100 text-slate-700 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                        </div>
                    </div>

                    <!-- DETAIL ALAMAT -->
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-slate-500">Alamat Lengkap</label>
                        <input type="text" disabled readonly value="{{ Auth::user()->admin->detail_alamat ?? '-' }}"
                            class="w-full border border-slate-200 bg-slate-100 text-slate-700 rounded-xl px-4 py-2.5 text-sm font-semibold cursor-not-allowed select-none">
                    </div>
                </div>

            </div>

        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </div>
@endsection
