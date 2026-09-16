@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" 
          x-data="{ openNotif: false, openAllNotif: false, tab: 'adminFinance', searchAdmin: '', searchPerusahaan: '' }">
        
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">Kelola Akun</h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    Manajemen data pengguna, admin, finance, perusahaan, dan pelamar di AreaKerja
                </p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Toolbar: Tabs, Search & Action -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                
                {{-- Tabs Switcher --}}
                <div class="h-10 flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold w-full sm:w-auto flex-shrink-0">
                    <button @click="tab = 'adminFinance'"
                        :class="tab === 'adminFinance' ? 'bg-white text-[#00509d] shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                        class="h-full flex-1 sm:flex-initial px-4 rounded-lg transition duration-150 flex items-center justify-center gap-1.5">
                        <i class="ph ph-shield-check text-sm"></i>
                        Admin & Finance ({{ count($usersAdminFinance) }})
                    </button>
                    <button @click="tab = 'perusahaanPelamar'"
                        :class="tab === 'perusahaanPelamar' ? 'bg-white text-[#00509d] shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                        class="h-full flex-1 sm:flex-initial px-4 rounded-lg transition duration-150 flex items-center justify-center gap-1.5">
                        <i class="ph ph-users text-sm"></i>
                        Perusahaan & Pelamar ({{ count($usersPerusahaanPelamar) }})
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                    {{-- Search Input --}}
                    <div class="h-10 flex items-center bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition w-full sm:w-72">
                        <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                        <input type="text" 
                            x-show="tab === 'adminFinance'" 
                            x-model="searchAdmin" 
                            autocomplete="off"
                            placeholder="Cari admin / finance..."
                            class="w-full h-full px-2.5 text-xs sm:text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400"
                            style="border: none !important; outline: none !important; box-shadow: none !important;">
                        <input type="text" 
                            x-show="tab === 'perusahaanPelamar'" 
                            x-model="searchPerusahaan" 
                            autocomplete="off"
                            placeholder="Cari user / nama..."
                            class="w-full h-full px-2.5 text-xs sm:text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400"
                            style="border: none !important; outline: none !important; box-shadow: none !important;">
                        <button type="button" 
                            x-show="(tab === 'adminFinance' && searchAdmin) || (tab === 'perusahaanPelamar' && searchPerusahaan)" 
                            @click="searchAdmin = ''; searchPerusahaan = ''"
                            class="w-5 h-5 rounded-full bg-slate-200 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center mr-2.5 flex-shrink-0 transition cursor-pointer"
                            title="Hapus pencarian">
                            <i class="ph ph-x text-[10px] font-bold"></i>
                        </button>
                    </div>

                    {{-- Tombol Tambah User --}}
                    <a href="{{ route('superadmin.add.user.createForm') }}"
                       class="h-10 inline-flex items-center justify-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-4 rounded-xl transition duration-200 shadow-xs flex-shrink-0">
                        <i class="ph ph-plus-circle text-base"></i>
                        Tambah User
                    </a>
                </div>
            </div>
        </div>

        <!-- Tab Content: Admin & Finance -->
        <div x-cloak x-show="tab === 'adminFinance'" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Role</th>
                            <th class="px-5 py-3.5">Username</th>
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($usersAdminFinance as $user)
                            <tr class="hover:bg-slate-50/60 transition duration-150"
                                x-show="!searchAdmin || '{{ strtolower($user->role . ' ' . $user->username . ' ' . $user->email) }}'.includes(searchAdmin.toLowerCase())">
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                        {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'finance' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    {{ $user->username }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('superadmin.detail.user', $user->id) }}"
                                           title="Detail User"
                                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white flex items-center justify-center transition">
                                            <i class="ph ph-eye text-base"></i>
                                        </a>
                                        <a href="{{ route('superadmin.edit.user', $user->id) }}"
                                           title="Edit User"
                                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                                            <i class="ph ph-pencil-simple text-base"></i>
                                        </a>
                                        <form action="{{ route('superadmin.destroy.user', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus user ini? Data tidak bisa dikembalikan!')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus User"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada data Admin atau Finance
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content: Perusahaan & Pelamar -->
        <div x-cloak x-show="tab === 'perusahaanPelamar'" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Role</th>
                            <th class="px-5 py-3.5">Nama / Perusahaan</th>
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5">Telepon</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($usersPerusahaanPelamar as $user)
                            @php
                                $nama = '-';
                                $telepon = '-';
                                if ($user->role === 'perusahaan' && $user->perusahaan) {
                                    $nama = $user->perusahaan->nama_perusahaan ?? $user->username;
                                    $telepon = $user->perusahaan->telepon_perusahaan ?? '-';
                                } elseif ($user->role === 'pelamar' && $user->pelamar) {
                                    $nama = $user->pelamar->nama_pelamar ?? $user->username;
                                    $telepon = $user->pelamar->telepon_pelamar ?? '-';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition duration-150"
                                x-show="!searchPerusahaan || '{{ strtolower($user->role . ' ' . $nama . ' ' . $user->email . ' ' . $telepon) }}'.includes(searchPerusahaan.toLowerCase())">
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                        {{ $user->role === 'perusahaan' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    {{ $nama }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-xs">
                                    {{ $telepon }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('superadmin.detail.user', $user->id) }}"
                                           title="Detail User"
                                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white flex items-center justify-center transition">
                                            <i class="ph ph-eye text-base"></i>
                                        </a>
                                        <a href="{{ route('superadmin.edit.user', $user->id) }}"
                                           title="Edit User"
                                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                                            <i class="ph ph-pencil-simple text-base"></i>
                                        </a>
                                        <form action="{{ route('superadmin.destroy.user', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus user ini? Data tidak bisa dikembalikan!')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus User"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada data Perusahaan atau Pelamar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
