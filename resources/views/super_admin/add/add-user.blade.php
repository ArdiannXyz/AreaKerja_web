@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-gray-50 overflow-y-auto min-h-screen" x-data="{ openNotif: false }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 md:gap-0">
            <h1 class="text-2xl font-semibold text-gray-800 break-words">Kelola Akun</h1>

            <div class="flex items-center gap-3 flex-wrap">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative shrink-0">
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431Z"
                                fill="black" />
                        </g>
                    </svg>

                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                <!-- Profil Admin -->
                <div
                    class="flex items-center gap-3 bg-white px-4 py-2 border border-orange-500 shadow-md rounded-2xl w-full sm:w-auto">

                    <a href="{{ route('superadmin.profile') }}" class="shrink-0">
                        @if (Auth::user()->role == 'super_admin')
                            @if (Auth::user()->superadmin?->img_profile)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
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

                    <div class="text-sm break-words max-w-[130px] sm:max-w-[180px] md:max-w-none">
                        <span class="font-semibold break-words">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm break-words">{{ Auth::user()->email }}</p>
                    </div>

                </div>
            </div>
        </div>


        <!-- Tombol Tambah User -->
        <div class="flex justify-start mb-5 px-2 sm:px-0">
            <a href="{{ route('superadmin.add.user.createForm') }}"
                class="bg-orange-500 hover:bg-orange-600 text-white 
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
                        'text-orange-600 border-b-4 border-orange-500 bg-orange-50' :
                        'text-gray-500 hover:text-orange-500 hover:bg-orange-50'"
                    class="px-4 sm:px-6 py-2 sm:py-3 font-semibold rounded-t-lg transition-all duration-300 focus:outline-none 
                   whitespace-normal break-words text-sm sm:text-base">
                    Admin & Finance
                </button>

                <button @click="tab = 'perusahaanPelamar'"
                    :class="tab === 'perusahaanPelamar'
                        ?
                        'text-orange-600 border-b-4 border-orange-500 bg-orange-50' :
                        'text-gray-500 hover:text-orange-500 hover:bg-orange-50'"
                    class="px-4 sm:px-6 py-2 sm:py-3 font-semibold rounded-t-lg transition-all duration-300 focus:outline-none 
                   whitespace-normal break-words text-sm sm:text-base">
                    Perusahaan & Pelamar
                </button>
            </div>

            <!-- Tab: Admin & Finance -->
            <div x-transition x-cloak x-show="tab === 'adminFinance'" class="space-y-4">

                <div class="overflow-x-auto rounded-lg shadow-md bg-white w-full">
                    <table class="min-w-full text-sm table-auto">

                        <thead class="bg-orange-500 text-white text-center">
                            <tr>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">User</th>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">Email</th>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">Username</th>
                                <th class="px-4 py-3 font-semibold whitespace-normal break-words">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="break-words">
                            @forelse ($usersAdminFinance as $user)
                                <tr class="text-center font-medium border border-gray-300 hover:bg-orange-50 transition">

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

                        <thead class="bg-orange-500 text-white text-center">
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
                                <tr class="text-center font-medium border-b border-gray-300 hover:bg-orange-50 transition">

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
