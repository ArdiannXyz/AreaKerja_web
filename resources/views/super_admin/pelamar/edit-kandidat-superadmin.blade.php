@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-white overflow-x-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h1 class="text-2xl font-medium">
                @if ($kategori === 'non_kandidat')
                    Edit Non Kandidat
                @elseif ($kategori === 'calon_kandidat')
                    Edit Calon Kandidat
                @elseif ($kategori === 'kandidat')
                    Edit Kandidat
                @else
                    Edit Data
                @endif
            </h1>
           <div class="flex items-center gap-3 flex-wrap">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative">
                    <!-- Icon Lonceng -->
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                            {{-- <path
                                d="M22.3629 11.0329C24.0912 10.7376 25.2143 8.97144 24.8714 7.08792C24.5286 5.20441 22.8497 3.91684 21.1214 4.21205C19.3932 4.50727 18.2701 6.27347 18.6129 8.15698C18.9558 10.0405 20.6347 11.3281 22.3629 11.0329Z"
                                fill="black" /> --}}
                        </g>
                    </svg>


                    <!-- Badge jumlah notif belum dibaca -->
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>



                <div class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl">
                    <a href="{{ route('superadmin.profile') }}">
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
                    <div class="text-sm">
                        <span class="font-semibold">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- <select class="appearance-none px-8 py-2 bg-transparent text-gray-600 text-md focus:outline-none">
                        <option>Text 1</option>
                        <option>Text 2</option>
                        <option>Text 3</option>
                    </select> --}}
                </div>
            </div>
        </div>

       <div class="w-full max-w-6xl mx-auto p-4 sm:p-6 bg-white border-2 border-gray-400 rounded-2xl shadow-md form-container">

            <h2 class="text-lg font-semibold mb-10">
                @if ($kategori === 'non_kandidat')
                    Tambahkan Non Kandidat
                @elseif ($kategori === 'calon_kandidat')
                    Tambahkan Calon Kandidat
                @elseif ($kategori === 'kandidat')
                    Tambahkan Kandidat
                @else
                    Tambahkan Data
                @endif
            </h2>



            <!-- Form -->
            @if (!$pelamar)
                <div class="bg-yellow-100 text-yellow-800 p-3 rounded-lg mb-4">
                    ⚠️ Harap buat data pelamar terlebih dahulu sebelum mengisi Sosmed, Alamat, Pendidikan, Organisasi,
                    Pengalaman,
                    dan Skill.
                </div>
            @endif

            <form action="{{ route('superadmin.pelamar.update', $pelamar->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Header -->
                <div class="flex items-center justify-between mb-10">
                   <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div>
                            <div>
                                <img id="pp" class="w-28 h-28 object-cover rounded-full border border-gray-300"
                                    src="{{ $pelamar && $pelamar->img_profile
                                        ? asset('storage/' . $pelamar->img_profile)
                                        : 'https://ui-avatars.com/api/?name=' .
                                            urlencode($pelamar->nama_pelamar ?? 'Pelamar') .
                                            '&background=00509d&color=fff&size=128' }}"
                                    alt="Preview Foto">
                            </div>

                        </div>
                        <label
                            class="flex items-center gap-2 px-4 py-2 text-md border-2 border-orange-600 bg-orange-500 hover:bg-orange-600 text-white rounded-md cursor-pointer">
                            <input type="file" name="img_profile" id="fileinput" accept="image/*" class="hidden">
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.70151 4.04985L6.00476 2.64706V8.49608C6.00476 8.65783 6.06472 8.81297 6.17145 8.92735C6.27818 9.04173 6.42293 9.10598 6.57387 9.10598C6.72481 9.10598 6.86956 9.04173 6.97629 8.92735C7.08302 8.81297 7.14297 8.65783 7.14297 8.49608V2.64706L8.44623 4.04985C8.49913 4.10701 8.56208 4.15239 8.63143 4.18335C8.70078 4.21431 8.77516 4.23026 8.85029 4.23026C8.92542 4.23026 8.99981 4.21431 9.06916 4.18335C9.13851 4.15239 9.20145 4.10701 9.25436 4.04985C9.3077 3.99315 9.35004 3.92569 9.37893 3.85137C9.40782 3.77705 9.4227 3.69733 9.4227 3.61681C9.4227 3.5363 9.40782 3.45658 9.37893 3.38226C9.35004 3.30793 9.3077 3.24048 9.25436 3.18378L6.97793 0.744145C6.92381 0.688618 6.85999 0.645092 6.79013 0.616064C6.65157 0.555062 6.49616 0.555062 6.35761 0.616064C6.28775 0.645092 6.22393 0.688618 6.1698 0.744145L3.89338 3.18378C3.84032 3.24064 3.79823 3.30815 3.76951 3.38246C3.74079 3.45676 3.72601 3.53639 3.72601 3.61681C3.72601 3.69723 3.74079 3.77687 3.76951 3.85117C3.79823 3.92547 3.84032 3.99298 3.89338 4.04985C3.94644 4.10671 4.00944 4.15182 4.07877 4.1826C4.1481 4.21338 4.2224 4.22922 4.29745 4.22922C4.37249 4.22922 4.4468 4.21338 4.51613 4.1826C4.58545 4.15182 4.64845 4.10671 4.70151 4.04985Z"
                                    fill="currentColor" />
                            </svg>
                            Upload
                        </label>
                        <button type="button" id="removeButton"
                            class="flex items-center gap-2 px-4 py-2 text-md border-2 border-orange-600 text-orange-600 rounded-md hover:bg-gray-100">
                            <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.7907 2.77852H9.4194V2.30947C9.4194 1.93628 9.2695 1.57837 9.00268 1.31448C8.73586 1.05059 8.37397 0.902344 7.99663 0.902344H5.1511C4.77376 0.902344 4.41188 1.05059 4.14506 1.31448C3.87824 1.57837 3.72834 1.93628 3.72834 2.30947V2.77852H1.35707C1.23129 2.77852 1.11066 2.82793 1.02172 2.9159C0.932779 3.00386 0.882813 3.12316 0.882812 3.24756C0.882813 3.37196 0.932779 3.49126 1.02172 3.57922C1.11066 3.66719 1.23129 3.7166 1.35707 3.7166H1.83132V12.1594C1.83132 12.4082 1.93125 12.6468 2.10913 12.8227C2.28701 12.9986 2.52827 13.0975 2.77983 13.0975H10.3679C10.6195 13.0975 10.8607 12.9986 11.0386 12.8227C11.2165 12.6468 11.3164 12.4082 11.3164 12.1594V3.7166H11.7907C11.9165 3.7166 12.0371 3.66719 12.126 3.57922C12.215 3.49126 12.2649 3.37196 12.2649 3.24756C12.2649 3.12316 12.215 3.00386 12.126 2.9159C12.0371 2.82793 11.9165 2.77852 11.7907 2.77852Z"
                                    fill="currentColor" />
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>
                <!-- User Info -->
                {{-- <div> 
                    <label class="block text-md font-medium mb-1">User ID <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full mt-1 border-2 border-gray-400 shadow rounded-lg px-3 py-2" placeholder="User ID" />
                </div> --}}
                <div>
                    <label class="block text-md font-medium mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ $pelamar?->user?->username }}" readonly
                        class="w-full mt-1 border-2 border-gray-400 shadow rounded-lg px-3 py-2" placeholder="Username" />
                </div>

                <div>
                    <label class="block text-md font-medium mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ $pelamar?->user?->email }}" readonly
                        class="w-full mt-1 border-2 border-gray-400 shadow rounded-lg px-3 py-2" placeholder="Email" />
                </div>

                <div>
                    <label class="block text-md font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pelamar"
                        class="w-full mt-1 border-2 border-gray-400 shadow rounded-lg px-3 py-2" placeholder="Nama Lengkap"
                        value="{{ $pelamar->nama_pelamar ?? '' }}" />
                </div>
                <div>
                    <label class="block text-md font-medium mb-1">Kata Sandi <span class="text-red-500">*</span></label>
                    <input type="password" name="password" value="{{ $pelamar?->user?->password }}" disabled
                        class="w-full mt-1 border-2 border-gray-400 shadow rounded-lg px-3 py-2" placeholder="Kata Sandi" />
                </div>

                <!-- Gender -->
                <div>
                    <div class="mt-2 mb-4">
                        <label class="block text-md font-medium mb-1">Gender <span class="text-red-500">*</span></label>
                        <div class="flex gap-6 mt-1">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="laki-laki"
                                    class="accent-orange-500 border-2 border-orange-500"
                                    {{ old('gender', $pelamar->gender ?? '') == 'laki-laki' ? 'checked' : '' }}>
                                <span>Laki-Laki</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="perempuan"
                                    class="accent-orange-500 border-2 border-orange-500"
                                    {{ old('gender', $pelamar->gender ?? '') == 'perempuan' ? 'checked' : '' }}>
                                <span>Perempuan</span>
                            </label>
                        </div>

                    </div>

                </div>

                <!-- No. Telepon -->
                <div>
                    <label class="block text-md font-medium mb-1">No. Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="telepon_pelamar" value="{{ $pelamar->telepon_pelamar ?? '' }}"
                        class="w-full mt-1 border-2 border-gray-400 shadow rounded-lg px-3 py-2"
                        placeholder="08********" />
                </div>

                @php
                    $kategori = $kategori ?? request()->route('kategori'); // misalnya dikirim dari controller
                @endphp
                <!-- Hidden input kategori -->
                <input type="hidden" name="kategori"
                    value="@switch($kategori)
        @case('non_kandidat') pelamar @break
        @case('calon_kandidat') calon kandidat @break
        @case('kandidat') kandidat aktif @break
        @default pelamar
    @endswitch">

                {{-- Bidang yang Diminati --}}
                @if (in_array($kategori, ['calon_kandidat', 'kandidat']))
                    <select id="divisi" name="divisi[]" multiple
                        class="w-full border-2 border-gray-400 shadow rounded-lg px-3 py-2">
                        @foreach ($divisis as $divisi)
                            <option value="{{ $divisi->divisi }}"
                                {{ in_array($divisi->divisi, (array) $pelamar->divisi) ? 'selected' : '' }}>
                                {{ $divisi->divisi }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <!-- Alamat -->
                @if (isset($pelamar) && $pelamar->alamat_pelamar->count() > 0)
                    <label class="text-md font-medium mt-4">Alamat</label>
                   <div class="flex flex-col sm:flex-row sm:justify-between gap-4">

                        <div class="p-4 w-full bg-gray-100 rounded-lg">
                            @foreach ($pelamar->alamat_pelamar ?? [] as $almt)
                                <div class="mb-6 border-b border-gray-200 pb-3">
                                    <h3 class="font-semibold text-gray-800 text-lg">
                                        {{ $almt->label }}
                                    </h3>

                                    <p class="text-gray-600 text-sm">
                                        Desa {{ $almt->desa }}, Kecamatan {{ $almt->kecamatan }},
                                        {{ $almt->kota }}, {{ $almt->provinsi }} - {{ $almt->kode_pos }}
                                    </p>

                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ $almt->detail }}
                                    </p>
                                </div>
                            @endforeach

                        </div>
                        <button data-modal-target="show-alamat" data-modal-toggle="show-alamat" type="button"
                            class="mb-20 ml-4">
                            <svg width="18" height="16" viewBox="0 0 10 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.83752 2.87443C10.0542 2.65779 10.0542 2.29673 9.83752 2.0912L8.5377 0.791384C8.33218 0.574747 7.97112 0.574747 7.75448 0.791384L6.7324 1.80791L8.81544 3.89095M0 8.54586V10.6289H2.08304L8.22664 4.47976L6.14359 2.39672L0 8.54586Z"
                                    fill="#FA6601" />
                            </svg>
                        </button>
                    </div>
                @else
                    @php
                        $pelamarSudahAda = isset($pelamar) && $pelamar->id;
                    @endphp
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Alamat</label>
                        <button type="button"
                            class="w-full flex justify-between items-center bg-orange-500 text-white px-4 py-2 rounded-lg"
                            @if (!$pelamarSudahAda) onclick="alert('Harap buat data pelamar terlebih dahulu sebelum menambahkan Alamat.')"
        @else
            data-modal-target="create_alamatmodal2" 
            data-modal-toggle="create_alamatmodal2" @endif>
                            <span>Alamat</span>
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 11.2789H20.5578M11.2789 2V20.5578" stroke="white" stroke-width="2.65112"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                @endif


                <div>
                    <!-- Pendidikan -->
                    @if (isset($pelamar) && $pelamar->riwayat_pendidikan->count() > 0)
                        <label class="text-md font-medium">Pendidikan</label>
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-4">

                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach ($pelamar->riwayat_pendidikan ?? [] as $pend)
                                    <div class="mb-6">
                                        <h3 class="font-semibold text-gray-800 text-lg">
                                            {{ $pend->asal_pendidikan }} - {{ $pend->pendidikan }}
                                            ({{ $pend->tahun_awal }} - {{ $pend->tahun_akhir }})
                                        </h3>
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ $pend->jurusan }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-pendidikan2" data-modal-toggle="show-pendidikan2"
                                type="button" class="mb-20 ml-4">
                                <svg width="18" height="16" viewBox="0 0 10 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.83752 2.87443C10.0542 2.65779 10.0542 2.29673 9.83752 2.0912L8.5377 0.791384C8.33218 0.574747 7.97112 0.574747 7.75448 0.791384L6.7324 1.80791L8.81544 3.89095M0 8.54586V10.6289H2.08304L8.22664 4.47976L6.14359 2.39672L0 8.54586Z"
                                        fill="#FA6601" />
                                </svg>
                            </button>
                        </div>
                    @else
                        @php
                            $pelamarSudahAda = isset($pelamar) && $pelamar->id;
                        @endphp
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Pendidikan</label>
                            <button type="button"
                                class="w-full flex justify-between items-center bg-orange-500 text-white px-4 py-2 rounded-lg"
                                @if (!$pelamarSudahAda) onclick="alert('Harap buat data pelamar terlebih dahulu sebelum menambahkan pendidikan.')"
        @else
            data-modal-target="create_pendidikanmodal2" 
            data-modal-toggle="create_pendidikanmodal2" @endif>
                                <span>Tambahkan Pendidikan</span>
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 11.2789H20.5578M11.2789 2V20.5578" stroke="white" stroke-width="2.65112"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                <div>
                    <!-- Organisasi -->
                    @if (isset($pelamar) && $pelamar->pengalaman_organisasi->count() > 0)
                        <label class="text-md font-medium">Organisasi</label>
                        <div class="flex justify-between mt-2">
                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach ($pelamar->pengalaman_organisasi ?? [] as $org)
                                    <div class="mb-6">
                                        <h3 class="font-semibold text-gray-800 text-lg">
                                            {{ $org->jabatan }} - {{ $org->nama_organisasi }}
                                            ({{ $org->tahun_awal }} - {{ $org->tahun_akhir }})
                                        </h3>
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ $org->deskripsi }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-org2" data-modal-toggle="show-org2" type="button"
                                class="mb-20 ml-4">
                                <svg width="18" height="16" viewBox="0 0 10 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.83752 2.87443C10.0542 2.65779 10.0542 2.29673 9.83752 2.0912L8.5377 0.791384C8.33218 0.574747 7.97112 0.574747 7.75448 0.791384L6.7324 1.80791L8.81544 3.89095M0 8.54586V10.6289H2.08304L8.22664 4.47976L6.14359 2.39672L0 8.54586Z"
                                        fill="#FA6601" />
                                </svg>
                            </button>
                        </div>
                    @else
                        @php
                            $pelamarSudahAda = isset($pelamar) && $pelamar->id;
                        @endphp
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Organisasi</label>
                            <button type="button"
                                class="w-full flex justify-between items-center bg-orange-500 text-white px-4 py-2 rounded-lg"
                                @if (!$pelamarSudahAda) onclick="alert('Harap buat data pelamar terlebih dahulu sebelum menambahkan Organisasi.')"
        @else
            data-modal-target="create_organisasimodal2" 
            data-modal-toggle="create_organisasimodal2" @endif>
                                <span>Tambahkan Organisasi</span>
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 11.2789H20.5578M11.2789 2V20.5578" stroke="white" stroke-width="2.65112"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    @endif

                </div>
                <div>
                    <!-- Pengalaman -->
                    @if (isset($pelamar) && $pelamar->pengalaman_kerja->count() > 0)
                        <label class="text-md font-medium">Pengalaman Kerja <span class="text-red-500"></span></label>
                        <div class="flex justify-between mt-2">
                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach ($pelamar->pengalaman_kerja ?? [] as $kerja)
                                    <div class="mb-6">
                                        <h3 class="font-semibold text-gray-800 text-lg">
                                            {{ $kerja->posisi_pekerjaan }} - {{ $kerja->nama_perusahaan }}
                                            ({{ $kerja->tahun_awal }} - {{ $kerja->tahun_akhir }})
                                        </h3>
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ $kerja->deskripsi }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-kerja2" data-modal-toggle="show-kerja2" type="button"
                                class="mb-20 ml-4">
                                <svg width="18" height="16" viewBox="0 0 10 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.83752 2.87443C10.0542 2.65779 10.0542 2.29673 9.83752 2.0912L8.5377 0.791384C8.33218 0.574747 7.97112 0.574747 7.75448 0.791384L6.7324 1.80791L8.81544 3.89095M0 8.54586V10.6289H2.08304L8.22664 4.47976L6.14359 2.39672L0 8.54586Z"
                                        fill="#FA6601" />
                                </svg>
                            </button>
                        </div>
                    @else
                        @php
                            $pelamarSudahAda = isset($pelamar) && $pelamar->id;
                        @endphp
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Pengalaman</label>
                            <button type="button"
                                class="w-full flex justify-between items-center bg-orange-500 text-white px-4 py-2 rounded-lg"
                                @if (!$pelamarSudahAda) onclick="alert('Harap buat data pelamar terlebih dahulu sebelum menambahkan PEngalaman Kerja.')"
        @else
            data-modal-target="create_kerjamodal2" 
            data-modal-toggle="create_kerjamodal2" @endif>
                                <span>Tambahkan Pengalaman</span>
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 11.2789H20.5578M11.2789 2V20.5578" stroke="white" stroke-width="2.65112"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                <div>
                    <!-- Skill -->
                    @if (isset($pelamar) && $pelamar->skill->count() > 0)
                        <label class="text-md font-medium">Skill</label>
                        <div class="flex justify-between mt-2">
                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach ($pelamar->skill as $sk)
                                    <div class="mb-6">
                                        <h3 class="font-semibold text-gray-800 text-lg">
                                            {{ $sk->skill }}
                                        </h3>
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ $sk->experience_level }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-skill2" data-modal-toggle="show-skill2" type="button"
                                class="mb-20 ml-4">
                                <svg width="18" height="16" viewBox="0 0 10 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.83752 2.87443C10.0542 2.65779 10.0542 2.29673 9.83752 2.0912L8.5377 0.791384C8.33218 0.574747 7.97112 0.574747 7.75448 0.791384L6.7324 1.80791L8.81544 3.89095M0 8.54586V10.6289H2.08304L8.22664 4.47976L6.14359 2.39672L0 8.54586Z"
                                        fill="#FA6601" />
                                </svg>
                            </button>
                        </div>
                    @else
                        @php
                            $pelamarSudahAda = isset($pelamar) && $pelamar->id;
                        @endphp
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Skill</label>
                            <button type="button"
                                class="w-full flex justify-between items-center bg-orange-500 text-white px-4 py-2 rounded-lg"
                                @if (!$pelamarSudahAda) onclick="alert('Harap buat data pelamar terlebih dahulu sebelum menambahkan Skill.')"
        @else
            data-modal-target="create_skillmodal2" 
            data-modal-toggle="create_skillmodal2" @endif>
                                <span>Tambahkan Skill</span>
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 11.2789H20.5578M11.2789 2V20.5578" stroke="white" stroke-width="2.65112"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>



                <!-- Social Media -->
                <div>
                    <label class="block text-lg font-medium mb-5">Social Media</label>

                    <label class="block text-md font-medium">Instagram</label>
                    <input type="text" name="social_media[instagram]" value="{{ $pelamar?->sosmed?->instagram }}"
                        class="w-full mt-1 mb-5 border-2 border-gray-400 shadow rounded-lg px-3 py-2"
                        placeholder="Instagram" />

                    <label class="block text-md font-medium">LinkedIn</label>
                    <input type="text" name="social_media[linkedin]" value="{{ $pelamar?->sosmed?->linkedin }}"
                        class="w-full mt-1 mb-5 border-2 border-gray-400 shadow rounded-lg px-3 py-2"
                        placeholder="LinkedIn" />

                    <label class="block text-md font-medium">Website</label>
                    <input type="text" name="social_media[website]" value="{{ $pelamar?->sosmed?->website }}"
                        class="w-full mt-1 mb-5 border-2 border-gray-400 shadow rounded-lg px-3 py-2"
                        placeholder="Website" />

                    <label class="block text-md font-medium">Twitter</label>
                    <input type="text" name="social_media[twitter]" value="{{ $pelamar?->sosmed?->twitter }}"
                        class="w-full mt-1 mb-5 border-2 border-gray-400 shadow rounded-lg px-3 py-2"
                        placeholder="Twitter" />
                </div>


                <!-- Buttons -->
                <div class="md:col-span-2 flex justify-center items-center gap-4 mt-4">
                    <button type="submit"
                        class="bg-orange-600 text-white font-medium px-10 py-2 rounded-md hover:bg-orange-500 border border-orange-600 transition">Upload
                    </button>
                    <a href={{ route('superadmin.pelamar') }}
                        class="bg-white text-orange-600 font-medium px-12 py-2 rounded-md hover:bg-gray-100 border border-orange-600 transition">Batal
                    </a>
                </div>
            </form>
        </div>

        @include('super_admin.notif.modal_semua')
        @include('super_admin.notif.modal_notif')

        @include('super_admin.pelamar.modal.alamat2')
        @include('super_admin.pelamar.modal.pendidikan2')
        @include('super_admin.pelamar.modal.organisasi2')
        @include('super_admin.pelamar.modal.pengalaman2')
        @include('super_admin.pelamar.modal.skill2')

        {{-- detail --}}
        @include('super_admin.pelamar.modal.detail_pendidikan2')
        @include('super_admin.pelamar.modal.detail_organisasi2')
        @include('super_admin.pelamar.modal.detail_pengalaman2')
        @include('super_admin.pelamar.modal.detail_skill2')
        @include('super_admin.pelamar.modal.detail_alamat')

        {{-- edit --}}

        {{-- Tom Select CSS --}}
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

        {{-- Tom Select JS --}}
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        <script>
            document.getElementById('fileinput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('pp').setAttribute('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('removeButton').addEventListener('click', function() {
                const img = document.getElementById('pp');
                const fileInput = document.getElementById('fileinput');

                // Reset ke avatar default
                img.setAttribute('src',
                    'https://ui-avatars.com/api/?name=Pelamar&background=00509d&color=fff&size=128');
                fileInput.value = ''; // Reset input file
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inisialisasi TomSelect untuk "Divisi"
                const divisiSelect = new TomSelect("#divisi", {
                    plugins: ['remove_button'],
                    persist: false,
                    create: false,
                    hideSelected: true,
                    maxItems: 5,
                    placeholder: "Pilih Divisi",
                    render: {
                        option: function(data, escape) {
                            return `
                        <div class="flex items-center justify-between">
                            <span>${escape(data.text)}</span>
                            <input type="checkbox" class="ml-2 accent-orange-500 pointer-events-none">
                        </div>
                    `;
                        },
                        item: function(data, escape) {
                            return `<div class="py-1 px-2 bg-orange-100 text-orange-700 rounded-lg">${escape(data.text)}</div>`;
                        }
                    }
                });

                // Ambil kategori dari PHP ke JS
                const kategori = "{{ $kategori }}";

                // Kalau bukan calon_kandidat atau kandidat → sembunyikan dan kosongkan pilihan
                if (kategori !== 'calon_kandidat' && kategori !== 'kandidat') {
                    document.getElementById('divisi-wrapper').classList.add('hidden');
                    divisiSelect.clear();
                }
            });
        </script>

        {{-- Notif --}}
        <script>
            // Tandai dibaca
            async function markAsRead(url, el) {
                try {
                    let res = await fetch(url, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {

                        // Ubah warna bg
                        el.classList.remove("bg-white");
                        el.classList.add("bg-gray-200");

                        // Kurangi badge
                        const badge = document.getElementById("notif-badge");
                        if (badge) {
                            let count = parseInt(badge.textContent);
                            if (count > 1) {
                                badge.textContent = count - 1;
                            } else {
                                badge.remove();
                            }
                        }
                    }

                } catch (error) {
                    console.error("markAsRead error:", error);
                }
            }

            // AlpineJS init
            document.addEventListener('alpine:init', () => {
                Alpine.data('notifHandler', () => ({

                    // Hapus satu notifikasi
                    async hapus(id) {
                        if (!confirm("Hapus notifikasi ini?")) return;

                        let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);

                        let res = await fetch(url, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            }
                        });

                        let data = await res.json();

                        if (data.success) {
                            document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                        }
                    },

                    // Hapus semua
                    async hapusSemua() {
                        if (!confirm("Hapus semua notifikasi?")) return;

                        let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            }
                        });

                        let data = await res.json();

                        if (data.success) {
                            document.querySelectorAll('.notif-item').forEach(e => e.remove());
                        }
                    },

                    // Hapus semua yang sudah dibaca
                    async hapusSemuaBaca() {
                        if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;

                        let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            }
                        });

                        let data = await res.json();

                        if (data.success) {
                            document.querySelectorAll('.notif-item.bg-gray-200')
                                .forEach(e => e.remove());
                        }
                    }

                }));
            });
        </script>



        <script>
            document.querySelector('form[target="hiddenFrame"]').addEventListener('submit', () => {
                document.querySelectorAll('.notif-item').forEach(item => {
                    item.classList.remove('bg-white');
                    item.classList.add('bg-gray-200');
                });
                const badge = document.querySelector('.absolute .bg-red-500');
                if (badge) badge.remove();
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    </main>
@endsection
