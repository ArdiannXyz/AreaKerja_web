@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto">
                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
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
