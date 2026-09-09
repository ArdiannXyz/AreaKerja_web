@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-gray-50 overflow-y-auto min-h-screen" x-data="{ openNotif: false }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 md:gap-0">
            <h1 class="text-2xl font-semibold text-gray-800 break-words">Kelola Akun</h1>

            <div class="flex items-center gap-3 flex-wrap">

                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>


        <!-- Tombol Tambah User -->
        <div class="flex justify-start mb-5 px-2 sm:px-0">
            <a href="{{ route('superadmin.add.user.createForm') }}"
                class="bg-blue-700 hover:bg-blue-800 text-white 
               px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg 
               flex items-center gap-2 font-medium shadow-md transition-all
               whitespace-normal break-words
               max-w-full text-sm sm:text-base">

                <span class="whitespace-normal break-words">
                    Tambah User
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>


        <!-- Tabs -->
        <div x-data="{ tab: 'adminFinance' }" class="mt-6">

            <!-- Tabs Header -->
            <div class="flex flex-wrap justify-center mb-6 border-b gap-2 border-gray-200 px-2">
                <button @click="tab = 'adminFinance'"
                    :class="tab === 'adminFinance'
                        ?
                        'text-blue-800 border-b-4 border-blue-700 bg-blue-50' :
                        'text-gray-500 hover:text-blue-700 hover:bg-blue-50'"
                    class="px-4 sm:px-6 py-2 sm:py-3 font-semibold rounded-t-lg transition-all duration-300 focus:outline-none 
                   whitespace-normal break-words text-sm sm:text-base">
                    Admin & Finance
                </button>

                <button @click="tab = 'perusahaanPelamar'"
                    :class="tab === 'perusahaanPelamar'
                        ?
                        'text-blue-800 border-b-4 border-blue-700 bg-blue-50' :
                        'text-gray-500 hover:text-blue-700 hover:bg-blue-50'"
                    class="px-4 sm:px-6 py-2 sm:py-3 font-semibold rounded-t-lg transition-all duration-300 focus:outline-none 
                   whitespace-normal break-words text-sm sm:text-base">
                    Perusahaan & Pelamar
                </button>
            </div>

            <!-- Tab: Admin & Finance -->
            <div x-transition x-cloak x-show="tab === 'adminFinance'" class="space-y-4">

                <div class="overflow-x-auto rounded-lg shadow-md bg-white w-full">
                    <table class="min-w-full text-sm table-auto">

                        <thead class="bg-blue-700 text-white text-center">
                            <tr>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">User</th>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">Email</th>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">Username</th>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="break-words">
                            @forelse ($usersAdminFinance as $user)
                                <tr class="text-center font-medium border border-gray-300 hover:bg-blue-50 transition">

                                    <td class="px-4 py-3 capitalize whitespace-normal break-words">
                                        {{ $user->role }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-normal break-words">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-normal break-words">
                                        {{ $user->username }}
                                    </td>

                                    <td class="px-4 py-3 flex gap-2 justify-center flex-wrap">

                                        <form action="{{ route('superadmin.destroy.user', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini? Data tidak bisa dikembalikan!')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white w-9 h-9 flex items-center justify-center rounded-md transition flex-shrink-0">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('superadmin.detail.user', $user->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white w-9 h-9 flex items-center justify-center rounded-md transition flex-shrink-0">
                                            <i class="ph ph-eye text-lg"></i>
                                        </a>

                                        <a href="{{ route('superadmin.edit.user', $user->id) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white w-9 h-9 flex items-center justify-center rounded-md transition flex-shrink-0">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>

                                    </td>
                                </tr>

                            @empty
                                <tr class="text-center">
                                    <td colspan="6" class="py-4 text-gray-500 whitespace-normal break-words">
                                        Belum ada user
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>



            <!-- Tab: Perusahaan & Pelamar -->
            <div x-transition x-cloak x-show="tab === 'perusahaanPelamar'" class="space-y-4">

                <div class="overflow-x-auto rounded-lg shadow-md bg-white w-full">
                    <table class="min-w-full text-sm table-auto">

                        <thead class="bg-blue-700 text-white text-center">
                            <tr>
                                <th class="px-4 py-3 whitespace-normal break-words">User</th>
                                <th class="px-4 py-3 whitespace-normal break-words">Nama / Perusahaan</th>
                                <th class="px-4 py-3 whitespace-normal break-words">Email</th>
                                <th class="px-4 py-3 whitespace-normal break-words">Telepon</th>
                                <th class="px-4 py-3 whitespace-normal break-words">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="break-words">
                            @forelse ($usersPerusahaanPelamar as $user)
                                <tr class="text-center font-medium border-b border-gray-300 hover:bg-blue-50 transition">

                                    <td class="px-4 py-3 capitalize whitespace-normal break-words">
                                        {{ $user->role }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-normal break-words">
                                        @if ($user->role === 'perusahaan' && $user->perusahaan)
                                            {{ $user->perusahaan->nama_perusahaan ?? $user->username }}
                                        @elseif ($user->role === 'pelamar' && $user->pelamar)
                                            {{ $user->pelamar->nama_pelamar ?? $user->username }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 whitespace-normal break-words">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-normal break-words">
                                        @if ($user->role === 'perusahaan' && $user->perusahaan)
                                            {{ $user->perusahaan->telepon_perusahaan }}
                                        @elseif ($user->role === 'pelamar' && $user->pelamar)
                                            {{ $user->pelamar->telepon_pelamar }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 flex gap-2 justify-center flex-wrap">

                                        <form action="{{ route('superadmin.destroy.user', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini? Data tidak bisa dikembalikan!')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white w-9 h-9 flex items-center justify-center rounded-md transition flex-shrink-0">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('superadmin.detail.user', $user->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white w-9 h-9 flex items-center justify-center rounded-md transition flex-shrink-0">
                                            <i class="ph ph-eye text-lg"></i>
                                        </a>

                                        <a href="{{ route('superadmin.edit.user', $user->id) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white w-9 h-9 flex items-center justify-center rounded-md transition flex-shrink-0">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>

                                    </td>
                                </tr>

                            @empty
                                <tr class="text-center">
                                    <td colspan="7" class="py-4 text-gray-500 whitespace-normal break-words">
                                        Belum ada perusahaan atau pelamar
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
