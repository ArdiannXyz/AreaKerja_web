@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <h1 class="text-2xl font-medium break-words">Detail Recruitment</h1>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                {{-- Tombol Notifikasi --}}
                <div class="relative">
                    <button @click="openNotif = true" class="relative">
                        <!-- Icon Lonceng -->
                        <svg width="31" height="32" viewBox="0 0 31 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_722_7956)">
                                <path
                                    d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                    fill="black" />
                            </g>
                        </svg>

                        <!-- Badge jumlah notif belum dibaca -->
                        @if ($global_notifikasi_unread > 0)
                            <span id="notif-badge"
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                                {{ $global_notifikasi_unread }}
                            </span>
                        @endif
                </div>

                <!-- Profil -->
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-shrink-0">
                    <a href="{{ route('superadmin.profile') }}">
                        @if (Auth::user()->role == 'super_admin' && Auth::user()->superadmin?->img_profile)
                            <img class="w-10 h-10 object-cover rounded-full profile-img"
                                src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                        @else
                            <img class="w-10 h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    </a>
                    <div class="text-sm break-words">
                        <span class="font-semibold">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Konten utama -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-md p-6 relative overflow-x-hidden">
            <div class="max-w-3xl mx-auto relative">
                <!-- Tombol close -->
                {{-- <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">&times;</button> --}}

                <!-- Header -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 mb-8">
                    <img src="{{ asset('storage/' . $recruitment->pelamar->img_profile) }}"
                        class="w-[100px] h-24 rounded-full border flex-shrink-0" />

                    <div class="break-words text-center sm:text-left">
                        <h2 class="text-lg font-bold break-words">{{ $recruitment->pelamar->nama_pelamar }}</h2>
                        <p class="text-sm font-semibold text-gray-700 break-words">
                            ({{ $recruitment->pelamar->deskripsi_diri ?? 'Data belum diisi' }})
                        </p>
                    </div>
                </div>

                <!-- Grid data recruitment -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <!-- Kolom Kiri -->
                    <div class="break-words">
                        <p><span class="font-semibold mt-4">User ID</span></p>
                        <p class="mb-3">{{ $recruitment->pelamar->user->id }}</p>
                        <p><span class="font-semibold">Nama Lengkap</span></p>
                        <p class="mb-3">{{ $recruitment->pelamar->nama_pelamar }}</p>
                        <p class="font-semibold">Alamat</p>
                        @foreach ($recruitment->pelamar->alamat_pelamar as $alamat)
                            <p class="mb-1 break-words">
                                {{ $alamat->label }}:<br>
                                {{ $alamat->desa }}, {{ $alamat->kecamatan }}, {{ $alamat->kota }} <br>
                                {{ $alamat->provinsi }} - {{ $alamat->kode_pos }} <br>
                                {{ $alamat->detail }}
                            </p>
                        @endforeach
                        <p><span class="font-semibold">No.Telepon</span></p>
                        <p class="mb-3 break-words">{{ $recruitment->pelamar->telepon_pelamar }}</p>

                        <p class="font-semibold mb-3">Social Media</p>
                        <p class="m-1">Instagram:
                            @if ($recruitment->pelamar->sosmed->instagram)
                                <a href="{{ $recruitment->pelamar->sosmed->instagram }}" target="_blank"
                                    class="text-blue-600 underline break-words">
                                    {{ $recruitment->pelamar->sosmed->instagram }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                        <p class="m-1">LinkedIn:
                            @if ($recruitment->pelamar->sosmed->linkedin)
                                <a href="{{ $recruitment->pelamar->sosmed->linkedin }}" target="_blank"
                                    class="text-blue-600 underline break-words">
                                    {{ $recruitment->pelamar->sosmed->linkedin }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                        <p class="m-1">Website:
                            @if ($recruitment->pelamar->sosmed->website)
                                <a href="{{ $recruitment->pelamar->sosmed->website }}" target="_blank"
                                    class="text-blue-600 underline break-words">
                                    {{ $recruitment->pelamar->sosmed->website }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                        <p class="m-1">Twitter:
                            @if ($recruitment->pelamar->sosmed->twitter)
                                <a href="{{ $recruitment->pelamar->sosmed->twitter }}" target="_blank"
                                    class="text-blue-600 underline break-words">
                                    {{ $recruitment->pelamar->sosmed->twitter }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="break-words">
                        <p><span class="font-semibold">Username</span></p>
                        <p class="mb-3 break-words">{{ $recruitment->pelamar->user->username }}</p>
                        <p><span class="font-semibold">Email</span></p>
                        <p class="mb-3 break-words">{{ $recruitment->pelamar->user->email }}</p>
                        <p><span class="font-semibold">Gender</span></p>
                        <p class="mb-3">{{ $recruitment->pelamar->gender }}</p>
                        <p><span class="font-semibold">Keahlian</span></p>
                        @foreach ($recruitment->pelamar->skill as $s)
                            <p class="break-words">{{ $loop->iteration }}. {{ $s->skill }}</p>
                        @endforeach
                    </div>
                </div>

                <!-- Organisasi -->
                <div class="mt-4 text-sm break-words">
                    <h3 class="font-medium mb-2">Organisasi</h3>
                    @foreach ($recruitment->pelamar->pengalaman_organisasi as $o)
                        <p class="mb-1 break-words">{{ $loop->iteration }}. {{ $o->nama_organisasi }}
                            <span class="block sm:inline sm:ml-11">{{ $o->jabatan }}</span>
                            <span class="block sm:inline sm:ml-10">{{ $o->tahun_awal }}–{{ $o->tahun_akhir }}</span>
                        </p>
                    @endforeach
                </div>

                <!-- Pengalaman -->
                <div class="mt-4 text-sm break-words">
                    <h3 class="font-medium mb-2">Pengalaman Kerja</h3>
                    @foreach ($recruitment->pelamar->pengalaman_kerja as $k)
                        <p class="mb-1 break-words">{{ $loop->iteration }}. {{ $k->posisi_pekerjaan }}
                            <span class="block sm:inline sm:ml-12">{{ $k->nama_perusahaan }}</span>
                            <span class="block sm:inline sm:ml-10">
                                {{ $recruitment->lowonganPerusahaan->perusahaan->alamatUtama->kota->nama ?? '-' }}
                            </span>
                            <span class="block sm:inline sm:ml-10">{{ $k->tahun_awal }}–{{ $k->tahun_akhir }}</span>
                        </p>
                    @endforeach
                </div>

                <!-- Pendidikan -->
                <div class="mt-4 text-sm break-words">
                    <h3 class="font-medium mb-2">Riwayat Pendidikan</h3>
                    @foreach ($recruitment->pelamar->riwayat_pendidikan as $p)
                        <p class="mb-1 break-words">{{ $loop->iteration }}. {{ $p->asal_pendidikan }}
                            <span class="block sm:inline sm:ml-24">{{ $p->jurusan }}</span>
                            <span class="block sm:inline sm:ml-8">{{ $p->tahun_awal }}–{{ $p->tahun_akhir }}</span>
                        </p>
                    @endforeach
                </div>
            </div>

            <!-- Tombol aksi -->
            <div class="grid grid-cols-1 space-y-3 mx-auto max-w-xs mt-6">
                <form action="{{ route('superadmin.recruitment.destroy', $recruitment->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus recruitment ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg w-full">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

    </main>
@endsection
