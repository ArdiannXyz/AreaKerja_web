@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
            <!-- Judul -->
            <h1 class="text-2xl font-medium text-gray-700 truncate sm:truncate-0 w-full sm:w-auto">
                Data Recruitment
            </h1>

            <!-- Notifikasi + Profil -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full sm:w-auto">
                <!-- Tombol Notifikasi -->
                <button @click="openNotif = true" class="relative flex-shrink-0">
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
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

                <!-- Profil -->
                <div
                    class="flex items-center gap-2 bg-white px-2 py-1 border border-gray-300 shadow-md rounded-2xl flex-shrink-0">
                    <a href="{{ route('superadmin.profile') }}">
                        @if (Auth::user()->role == 'super_admin' && Auth::user()->superadmin?->img_profile)
                            <img class="w-10 h-10 object-cover rounded-full"
                                src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                        @else
                            <img class="w-10 h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    </a>
                    <div class="text-sm truncate max-w-[120px] sm:max-w-none">
                        <span class="font-semibold truncate">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-xs sm:text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <!-- Bagian kiri (kosong untuk sekarang, bisa diisi nanti) -->
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <!-- Kosong, placeholder -->
            </div>

            <!-- Bagian search -->
            <div class="w-full sm:w-auto">
                <form action="" method="get"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="nama/lowongan ..."
                        class="border border-gray-500 rounded-lg px-4 py-2 w-full sm:w-72 focus:outline-none">
                    <button
                        class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-4 sm:px-10 py-2 rounded-xl w-full sm:w-auto">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border-2 border-gray-400">
            <table class="w-full min-w-[700px] text-left border-collapse">
                <thead class="text-center bg-orange-500 text-white">
                    <tr class="border-b-[2px] border-gray-300">
                        <th class="p-4 sm:p-7 font-medium">ID</th>
                        <th class="p-4 sm:p-7 font-medium">Nama Kandidat</th>
                        <th class="p-4 sm:p-7 font-medium">Lowongan</th>
                        <th class="p-4 sm:p-7 font-medium">Email</th>
                        <th class="p-4 sm:p-7 font-medium">Telepon</th>
                        <th class="p-4 sm:p-7 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($recruitments as $r)
                        <tr class="border-b border-gray-300">
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->id }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->nama_pelamar }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->lowonganPerusahaan->nama }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->user->email }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->telepon_pelamar }}</td>
                            <td class="px-2 sm:px-4 py-2">
                                <a href="{{ route('superadmin.recruitment.detail', $r->id) }}"
                                    class="bg-orange-500 text-xs sm:text-sm text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg inline-block whitespace-nowrap">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-gray-500 text-center">Belum ada recruitment diterima</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
