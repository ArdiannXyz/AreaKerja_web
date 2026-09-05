@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64" x-data="{ openNotif: false, openAllNotif: false }">
        <main class="flex-1 p-6 bg-white overflow-y-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">

                <h1 class="text-2xl font-medium">Detail Perusahaan</h1>

                <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">

                    {{-- Tombol Notifikasi --}}
                    <button @click="openNotif = true" class="relative ml-32">
                        <!-- Icon Lonceng -->
                        <svg width="31" height="32" viewBox="0 0 31 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
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

                    {{-- User Profile --}}
                    <div
                        class="flex items-center gap-2 bg-white px-2 py-1 border border-gray-200 shadow-md rounded-2xl w-auto">
                        <a href="#">
                            @if (Auth::user()->role == 'admin')
                                @if (Auth::user()->admin->img_profile)
                                    <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                        src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
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

                        <div class="text-sm">
                            <span class="font-semibold">{{ Auth::user()->username }}</span>
                            <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Konten utama -->
            <div class="w-full mx-auto bg-white rounded-xl p-6 relative">

                <div class="w-full mx-auto border-2 border-gray-400 rounded-xl shadow overflow-hidden">
                    <!-- overflow-hidden added -->

                    <!-- Header -->
                    <div
                        class="flex flex-col md:flex-row items-center border border-gray-400 rounded-xl shadow-lg py-3 gap-4 mb-4 px-4">

                        <img src="{{ $perusahaan->img_profile ? asset('storage/' . $perusahaan->img_profile) : asset('images/seven.png') }}"
                            alt="foto kandidat" class="w-40 h-40 md:w-48 md:h-48 object-cover rounded-lg flex-shrink-0">

                        <div class="text-center md:text-left md:ml-8 break-words w-full">
                            <h2 class="text-xl md:text-2xl font-bold uppercase break-words">
                                {{ $perusahaan->nama_perusahaan }}
                            </h2>
                        </div>
                    </div>

                    <!-- Konten -->
                    <div class="w-full mx-auto bg-white p-6 md:p-10">

                        <!-- Deskripsi -->
                        <h2 class="text-lg font-semibold mb-2">Deskripsi</h2>
                        <p class="text-sm font-medium text-gray-800 mb-6 break-words">
                            {{ $perusahaan->deskripsi ?? 'Belum Ada Data' }}
                        </p>

                        <!-- Visi -->
                        <h2 class="text-lg font-semibold mb-2">Visi</h2>
                        <ul class="list-disc font-medium list-inside text-sm text-gray-800 mb-6 break-words">
                            <li>{{ $perusahaan->visi ?? 'Belum Ada Data' }}</li>
                        </ul>

                        <!-- Misi -->
                        <h2 class="text-lg font-semibold mb-2">Misi</h2>
                        <ul class="list-disc font-medium list-inside text-sm text-gray-800 mb-6 break-words">
                            <li>{{ $perusahaan->misi ?? 'Belum Ada Data' }}</li>
                        </ul>

                        <!-- Data Perusahaan -->
                        <h2 class="text-lg font-semibold mb-2">Data Perusahaan</h2>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 font-medium text-sm text-gray-800 mb-6 gap-y-2 break-words">
                            <p>User ID</p>
                            <p>: {{ $perusahaan->user->id }}</p>

                            <p>Username</p>
                            <p class="break-all">: {{ $perusahaan->user->username }}</p>

                            <p>Email</p>
                            <p class="break-all">: {{ $perusahaan->user->email }}</p>

                            <p>Kata Sandi</p>
                            <p>: ********</p>

                            <p>Nama Perusahaan</p>
                            <p class="break-words">: {{ $perusahaan->nama_perusahaan }}</p>

                            <p>Legalitas</p>
                            <p class="break-words">: {{ $perusahaan->legalitas ?? 'Belum Ada Data' }}</p>
                        </div>

                        <!-- Kontak -->
                        <h2 class="text-lg font-semibold mb-2">Kontak</h2>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 font-medium text-sm text-gray-800 mb-6 gap-y-2 break-words">
                            <p>Perusahaan</p>
                            <p class="font-semibold break-all">: {{ $perusahaan->telepon_perusahaan }}</p>

                            <p>Whatsapp</p>
                            <p class="font-semibold break-all">: {{ $perusahaan->whatsapp ?? 'Belum Ada Data' }}</p>
                        </div>

                        <!-- Lowongan -->
                        <h2 class="text-lg font-semibold mb-2">Lowongan</h2>

                        @if ($perusahaan->lowonganPerusahaans->count())
                            <div class="text-sm font-medium space-y-4 break-words">
                                @foreach ($perusahaan->lowonganPerusahaans as $lowongan)
                                    <div class="break-words">
                                        <a href="{{ route('admin.lowongan.detail', [$perusahaan->slug, $lowongan->slug]) }}"
                                            class="text-blue-500 text-sm font-semibold hover:underline mb-1 block break-words">
                                            {{ $lowongan->nama }}
                                        </a>

                                        <p class="text-gray-400 mb-1 break-words">
                                            {{ $lowongan->alamat }}
                                        </p>

                                        <p class="text-gray-400 break-words">
                                            {{ $lowongan->published_at ?? $lowongan->created_at }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="flex flex-col items-center space-y-3 max-w-lg mx-auto mt-8">
                    <a href="{{ url('/admin/perusahaan') }}"
                        class="bg-gray-600 text-white text-center w-full md:w-96 p-2 rounded-md hover:bg-gray-500 transition duration-300">
                        Kembali
                    </a>
                </div>
            </div>


        </main>
        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </div>
@endsection
