@extends('layouts.index')
@section('content')


    <form action="{{ route('profile.update', Auth::user()->pelamar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h2 class="text-xl font-semibold mb-6 mt-28 ml-12">Profil Akun</h2>
        <div class="bg-white  mx-12">
            <!-- Header: Avatar + Tombol -->
              <div class="border-2 border-orange-300 rounded-md p-4 md:p-0">

                <div
                    class="border-orange-500 rounded-lg p-6 md:p-8 flex flex-col md:flex-row items-center md:justify-between gap-6">

                    <!-- Kiri: Foto + Upload/Remove -->
                    <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8 w-full md:w-auto">

                        <!-- Avatar + Select -->
                        <div class="flex flex-col items-center w-full md:w-auto">
                            <div class="relative inline-flex items-center gap-3">

                                <div x-data="{ zoom: false }" class="cursor-pointer inline-block" @click="zoom = !zoom">
                                    <img id="pp"
                                        class="w-40 h-40 object-cover rounded-full transition-transform duration-300"
                                        :class="zoom ? 'scale-[2] z-50 relative' : 'scale-100'"
                                        src="{{ Auth::user()->pelamar->img_profile
                                            ? asset('storage/' . Auth::user()->pelamar->img_profile)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->username) . '&background=random&color=fff&size=128' }}"
                                        alt="Profile">
                                </div>

                                <!-- Badge Areakerja -->
                                @if (optional($pelamar)->kategori === 'kandidat aktif')
                                    <div class="absolute bottom-1 right-1 z-20">
                                        <div class="relative group bg-white rounded-full">
                                            <img src="{{ asset('images/logoarea.png') }}" class="h-10 w-11"
                                                alt="Badge Areakerja">


                                            <!-- Tooltip -->
                                            <div
                                                class="absolute top-full left-1/2 -translate-x-1/2 mt-2
                           w-56 bg-gray-200 text-gray-800 text-xs
                           rounded-md rounded-tr-none
                           px-3 py-2
                           opacity-0 invisible
                           shadow-lg
                           group-hover:opacity-100 group-hover:visible
                           transition duration-200
                           z-50 text-center">
                                                Badge Areakerja diberikan kepada pengguna yang telah resmi
                                                menjadi <strong>Kandidat Areakerja</strong>.
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Select Box -->
                            <div class="relative flex items-center mt-4 w-full gap-2 md:w-[95%]">

                                @php                                
                                    $status = '';
                                    if ($pelamar->kategori === 'pelamar') {
                                        $status = 'Pelamar Aktif';
                                    } elseif (in_array($pelamar->kategori, ['calon kandidat', 'kandidat aktif'])) {
                                        $status = 'Belum Bekerja';
                                    } elseif ($pelamar->kategori === 'kandidat nonaktif') {
                                        $status = 'Bekerja';
                                    }
                                @endphp


                                <select id="statusSelect"
                                    class="w-full border border-orange-500 text-orange-500 font-semibold rounded-md px-2 py-1 text-xs cursor-pointer appearance-none">
                                    <option value="Pelamar Aktif" {{ $status == 'Pelamar Aktif' ? 'selected' : '' }}>
                                        Pelamar Aktif
                                    </option>
                                    <option value="Belum Bekerja" {{ $status == 'Belum Bekerja' ? 'selected' : '' }}>
                                        Belum Bekerja
                                    </option>
                                    <option value="Bekerja" {{ $status == 'Bekerja' ? 'selected' : '' }}>
                                        Bekerja
                                    </option>
                                </select>

                                <input type="hidden" id="kategoriPelamar"
                                    value="{{ strtolower($pelamar->kategori ?? '') }}">


                            </div>

                        </div>

                        <!-- Tombol Upload & Remove -->
                        <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">

                            <label
                                class="flex items-center gap-1 border border-orange-400 text-orange-500 px-3 py-2 rounded-md text-sm font-medium hover:bg-orange-50 w-full md:w-auto justify-center">
                                <input type="file" name="img_profile" id="fileinput" accept="image/*" class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                Upload
                            </label>

                            <button type="button"
                                onclick="event.preventDefault(); document.getElementById('removeForm').submit();"
                                class="px-3 py-2 flex items-center gap-1 border border-gray-400 rounded text-sm text-gray-600 hover:bg-gray-100 w-full md:w-auto justify-center">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.7946 2.44649H9.4233V1.97744C9.4233 1.60425 9.27341 1.24634 9.00659 0.982451C8.73977 0.718563 8.37788 0.570313 8.00054 0.570312H5.15501C4.77767 0.570313 4.41579 0.718563 4.14896 0.982451C3.88214 1.24634 3.73225 1.60425 3.73225 1.97744V2.44649H1.36097C1.23519 2.44649 1.11456 2.4959 1.02562 2.58386C0.936685 2.67183 0.886719 2.79113 0.886719 2.91553C0.886719 3.03993 0.936685 3.15923 1.02562 3.24719C1.11456 3.33515 1.23519 3.38457 1.36097 3.38457H1.83523V11.8273C1.83523 12.0761 1.93516 12.3147 2.11304 12.4907C2.29092 12.6666 2.53218 12.7654 2.78374 12.7654H10.3718C10.6234 12.7654 10.8646 12.6666 11.0425 12.4907C11.2204 12.3147 11.3203 12.0761 11.3203 11.8273V3.38457H11.7946C11.9204 3.38457 12.041 3.33515 12.1299 3.24719C12.2189 3.15923 12.2688 3.03993 12.2688 2.91553C12.2688 2.79113 12.2189 2.67183 12.1299 2.58386C12.041 2.4959 11.9204 2.44649 11.7946 2.44649Z"
                                        fill="#606060" />
                                </svg>
                                Remove
                            </button>

                        </div>
                    </div>

                    <!-- Bagian Kanan (Tombol CV & Simpan) -->
                    <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('cv.download', Auth::user()->pelamar->id) }}"
                            class="bg-orange-500 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-orange-600 w-full md:w-auto text-center">
                            Unduh CV
                        </a>
                        <button type="submit"
                            class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-green-700 w-full md:w-auto text-center">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
            <br>


            <!-- Grid: Dua Kolom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kolom Kiri -->
                <div class="flex flex-col gap-4">
                    <label class="text-lg font-medium">Data Diri</label>
                    <div class="w-30 h-1 bg-orange-500 mt-1"></div><br>
                    @csrf
                    @method('PUT')

                    @if (Auth::user()->pelamar->nama_pelamar)
                        <div>
                            <label class="text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Nama Lengkap" name="nama_pelamar"
                                value="{{ Auth::user()->pelamar->nama_pelamar }}"
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm">
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Nama Lengkap" name="nama_pelamar"
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm">
                        </div>
                    @endif

                    @if (Auth::user()->pelamar && Auth::user()->pelamar->gender)
                        <div>
                            <label class="text-sm font-medium">Gender <span class="text-red-500">*</span></label>
                            <div class="flex gap-6 mt-2 text-sm">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="gender" value="laki-laki"
                                        class="w-4 h-4 text-orange-500 border-2 border-orange-500"
                                        {{ Auth::user()->pelamar->gender === 'laki-laki' ? 'checked' : '' }}>
                                    Laki - Laki
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="gender" value="perempuan"
                                        class="w-4 h-4 text-orange-500 border-2 border-orange-500"
                                        {{ Auth::user()->pelamar->gender === 'perempuan' ? 'checked' : '' }}>
                                    Perempuan
                                </label>
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium">Gender <span class="text-red-500">*</span></label>
                            <div class="flex gap-6 mt-2 text-sm">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="gender" value="laki-laki"
                                        class="w-4 h-4 text-orange-500 border-2 border-orange-500"> Laki - Laki </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="gender" value="perempuan"
                                        class="w-4 h-4 text-orange-500 border-2 border-orange-500"> Perempuan </label>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->pelamar->tanggal_lahir)
                        <div>
                            <label class="text-sm font-medium">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="Date" name="tanggal_lahir"
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm text-gray-500 " name="tempat_lahir"
                                value="{{ Auth::user()->pelamar->tanggal_lahir }}">
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="Date" name="tanggal_lahir"
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm text-gray-500 ">
                        </div>
                    @endif

                    @if (Auth::user()->pelamar->telepon_pelamar)
                        <div>
                            <label class="text-sm font-medium">No. Tlp <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="08xxxxxxxx" name="telepon_pelamar"
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm"
                                value="{{ Auth::user()->pelamar->telepon_pelamar }}">
                            @error('telepon_pelamar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium">No. Tlp <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="No. Tlp" name="telepon_pelamar"
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm">
                            <p class="text-red-500 text-sm mt-1 error-message" data-field="telepon_pelamar"></p>
                        </div>
                    @endif

                    @if (Auth::user()->pelamar->deskripsi_diri)
                        <div>
                            <label class="text-sm font-medium">Deskripsi Diri <span class="text-red-500">*</span></label>
                            <textarea placeholder="Deskripsikan diri anda secara singkat" name="deskripsi_diri"
                                class="auto-grow w-full mt-1 border rounded-md px-3 py-2 text-sm">{{ Auth::user()->pelamar->deskripsi_diri }}</textarea>
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium">Deskripsi Diri <span class="text-red-500">*</span></label>
                            <textarea placeholder="Deskripsikan diri anda secara singkat" name="deskripsi_diri"
                                class="auto-grow w-full mt-1 border rounded-md px-3 py-2 text-sm"></textarea>
                        </div>
                    @endif

                    <div>
                        @if (Auth::user()->pelamar->alamat_pelamar->count() >= 1)
                            <a href="{{ route('alamat') }}"
                                class="w-full flex justify-between items-center px-4 py-3 bg-orange-500 hover:bg-orange-600 
                              border border-orange-500 text-white rounded-md text-sm">
                                <label class="text-sm font-medium">Alamat <span class="text-red-500"></span></label>
                            </a>
                        @else
                            <a href="{{ route('form_alamat') }}"
                                class="w-full flex justify-between items-center px-4 py-3 bg-orange-500 hover:bg-orange-600 
                              border border-orange-500 text-white rounded-md text-sm">
                                <label class="text-sm font-medium">Alamat <span class="text-red-500"></span></label>
                            </a>
                        @endif
                    </div>

                    {{-- bagian pendidikan --}}
                    @if (Auth::user()->pelamar->riwayat_pendidikan->count() > 0)
                        <label class="text-sm font-medium">Pendidikan</label>
                        <div class="flex justify-between">
                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach (Auth::user()->pelamar->riwayat_pendidikan as $pend)
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
                            <button data-modal-target="show-pendidikan" data-modal-toggle="show-pendidikan"
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
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Pendidikan</label>
                            <button data-modal-target="create_pendidikanmodal" data-modal-toggle="create_pendidikanmodal"
                                type="button"
                                class="flex items-center justify-between border border-orange-500 rounded-md w-full px-4 py-3 text-orange-500 font-semibold">
                                <span>Tambahkan Pendidikan</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif

                    <!-- Bagian Organisasi -->
                    {{-- Jika user punya data organisasi --}}
                    @if (Auth::user()->pelamar->pengalaman_organisasi->count() > 0)
                        <label class="text-sm font-medium">Organisasi</label>
                        <div class="flex justify-between">
                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach (Auth::user()->pelamar->pengalaman_organisasi as $org)
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
                            <button data-modal-target="show-org" data-modal-toggle="show-org" type="button"
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
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Organisasi</label>
                            <button data-modal-target="create_organisasimodal" data-modal-toggle="create_organisasimodal"
                                type="button"
                                class="flex items-center justify-between border border-orange-500 rounded-md w-full px-4 py-3 text-orange-500 font-semibold">
                                <span>Tambahkan Organisasi</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif

                    {{-- Jika user punya data kerja --}}
                    @if (Auth::user()->pelamar->pengalaman_kerja->count() > 0)
                        <label class="text-sm font-medium">Pengalaman Kerja <span class="text-red-500"></span></label>
                        <div class="flex justify-between">
                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach (Auth::user()->pelamar->pengalaman_kerja as $kerja)
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
                            <button data-modal-target="show-kerja" data-modal-toggle="show-kerja" type="button"
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
                        {{-- Kalau belum ada data, tampil tombol --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Pengalaman Kerja</label>
                            <button data-modal-target="create_kerjamodal" data-modal-toggle="create_kerjamodal"
                                type="button"
                                class="flex items-center justify-between border border-orange-500 rounded-md w-full px-4 py-3 text-orange-500 font-semibold">
                                <span>Tambahkan Pengalaman Kerja</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif


                    {{-- SKILL --}}
                    @if (Auth::user()->pelamar->skill->count() > 0)
                        <label class="text-sm font-medium">Skill</label>
                        <div class="flex justify-between">
                            <div class="p-4 w-full bg-gray-100 rounded-lg">
                                @foreach (Auth::user()->pelamar->skill as $sk)
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
                            <button data-modal-target="show-skill" data-modal-toggle="show-skill" type="button"
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
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Skill</label>
                            <button data-modal-target="create_skillmodal" data-modal-toggle="create_skillmodal"
                                type="button"
                                class="flex items-center justify-between border border-orange-500 rounded-md w-full px-4 py-3 text-orange-500 font-semibold">
                                <span>Tambahkan Skill</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif

                    <!-- Sosial Media -->
                    <div class="flex flex-col gap-2">
                        <label class="text-lg font-medium">Sosial Media</label>
                        <div class="w-30 h-1 bg-orange-500 mt-1"></div><br>
                        <label class="text-sm font-medium">Instagram <span class="text-red-500"></span></label>
                        <input type="text" name="instagram" placeholder="Instagram"
                            class="w-full border rounded-md px-3 py-2 text-sm"
                            value="{{ $pelamar->social_links['instagram'] ?? '' }}">
                        <label class="text-sm font-medium">Linkedin<span class="text-red-500"></span></label>
                        <input type="text" name="linkedin" placeholder="LinkedIn"
                            class="w-full border rounded-md px-3 py-2 text-sm"
                            value="{{ $pelamar->social_links['linkedin'] ?? '' }}">
                        <label class="text-sm font-medium">Website<span class="text-red-500"></span></label>
                        <input type="text" name="website" placeholder="Website"
                            class="w-full border rounded-md px-3 py-2 text-sm"
                            value="{{ $pelamar->social_links['website'] ?? '' }}">
                        <label class="text-sm font-medium">Twitter<span class="text-red-500"></span></label>
                        <input type="text" name="twitter" placeholder="Twitter"
                            class="w-full border rounded-md px-3 py-2 text-sm"
                            value="{{ $pelamar->social_links['twitter'] ?? '' }}">
                    </div><br>
                </div>

                <!-- Kolom Kanan -->
                <div class="flex flex-col gap-4">
                    <label class="text-lg font-medium">Informasi Akun</label>
                    <div class="w-30 h-1 bg-orange-500 mt-1"></div><br>


                    <div>
                        <label class="text-sm font-medium">Nama Pengguna/Username<span class="text-red-500">*</span></label>    
                        <input type="text" placeholder="Nama Pengguna" value="{{ Auth::user()->username }}" name="username"
                            class="w-full mt-1 border rounded-md px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <!-- Input -->
                            <input type="email" placeholder="Email" value="{{ Auth::user()->email }}" readonly
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm focus:outline-none">


                            <!-- Icon di luar border -->
                            <a href="{{ route('email.ubah') }}" class="mt-1 text-orange-500 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5"
                                    viewBox="0 0 24 24">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71
                                                                                7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003
                                                                               1.003 0 0 0-1.42 0l-1.83 1.83 3.75
                                                                               3.75 1.84-1.82z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Kata Sandi <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <!-- Input -->
                            <input type="password" placeholder="Kata sandi" value="" readonly
                                class="w-full mt-1 border rounded-md px-3 py-2 text-sm focus:outline-none">

                            <!-- Icon di luar border -->
                            <button data-modal-target="gantipwmodal" data-modal-toggle="gantipwmodal" type="button">
                                <svg width="18" height="16" viewBox="0 0 10 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.83752 2.87443C10.0542 2.65779 10.0542 2.29673 9.83752 2.0912L8.5377 0.791384C8.33218 0.574747 7.97112 0.574747 7.75448 0.791384L6.7324 1.80791L8.81544 3.89095M0 8.54586V10.6289H2.08304L8.22664 4.47976L6.14359 2.39672L0 8.54586Z"
                                        fill="#FA6601" />
                                </svg>
                            </button>
                        </div>

                    </div>

                    <!-- Ekspektasi Gaji -->
                    <div>
                        <label class="text-lg font-medium">Ekspektasi Gaji</label>
                        <div class="w-30 h-1 bg-orange-500 mt-3"></div><br>

                        <!-- Input Gaji (Responsif) -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-1">

                            <!-- Minimal -->
                            <div
                                class="flex items-center border border-black rounded-md px-3 py-2 text-orange-500 w-full sm:w-56 gap-2">
                                <span class="text-orange-500">Rp.</span>
                                <input type="number" placeholder="" name="gaji_minimal"
                                    class="border-none w-full outline-none"
                                    value="{{ Auth::user()->pelamar->gaji_minimal ?? '' }}">
                            </div>

                            <span class="text-center hidden sm:block">-</span>
                            <span class="text-center sm:hidden">sampai</span>

                            <!-- Maksimal -->
                            <div class="flex items-center border border-black rounded-md px-3 py-2 w-full sm:w-56 gap-2">
                                <span>Rp.</span>
                                <input type="number" placeholder="" name="gaji_maksimal"
                                    class="border-none w-full outline-none"
                                    value="{{ Auth::user()->pelamar->gaji_maksimal ?? '' }}">
                            </div>
                        </div>

                    </div>

                    <!-- Catatan -->
                    <div class="text-orange-500 text-sm space-y-2 mt-2 mb-2">
                        <p>✓ Setelah menjadi kandidat AreaKerja, CV anda akan otomatis terunggah ke etalase perusahaan</p>
                        <p>✓ Range gaji akan tampil pada profil anda di etalase perusahaan</p>
                    </div>

                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </form>

    {{-- Form khusus remove (hidden, tidak kelihatan) --}}
    <form id="removeForm" action="{{ route('profile.destroy', Auth::user()->pelamar->id) }}" method="POST"
        class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @include('non-user.profile.kerja.modal-createkerja')
    @include('non-user.profile.skill.modal-create')
    @include('non-user.profile.organisasi.modal-createorganisasi')
    @include('non-user.profile.pendidikan.modal-create')

    @include('non-user.profile.modal-kategori.modal1')
    @include('non-user.profile.modal-kategori.modal2')

    @include('non-user.profile.organisasi.modal-show')
    @include('non-user.profile.skill.modal-show')
    @include('non-user.profile.kerja.modal-show')
    @include('non-user.profile.pendidikan.modal-show')
    @include('non-user.components.gantipw')
    <script>
        document.getElementById('fileinput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('pp').setAttribute('src', event.target.result);
                    document.getElementById('pi').setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>


    <script>
        function autoGrow(el) {
            el.style.height = "auto";
            el.style.height = el.scrollHeight + "px";
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".auto-grow").forEach((el) => {
                autoGrow(el);
                el.addEventListener("input", () => autoGrow(el));
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            console.log("JS siap! Listener aktif.");

            // ===============================
            // STATUS PELAMAR (Trigger Modal 1)
            // ===============================
            const statusSelect = document.getElementById('statusSelect');
            const kategoriInput = document.getElementById('kategoriPelamar');

            if (statusSelect) {
                statusSelect.addEventListener('change', function() {

                    let selected = this.value;
                    let kategori = kategoriInput ? kategoriInput.value : null;

                    console.log("Selected:", selected);
                    console.log("Kategori:", kategori);

                    if (selected === 'Bekerja' && kategori === 'kandidat aktif') {
                        document.getElementById('modalPeringatan').classList.remove('hidden');
                    }
                });
            }

            // ===============================
            // MODAL PERINGATAN
            // ===============================
            const yaPeringatan = document.getElementById('yaPeringatan');
            const tidakPeringatan = document.getElementById('tidakPeringatan');

            if (yaPeringatan) {
                yaPeringatan.onclick = function() {
                    document.getElementById('modalPeringatan').classList.add('hidden');
                    document.getElementById('modalNonaktif').classList.remove('hidden');
                };
            }

            if (tidakPeringatan) {
                tidakPeringatan.onclick = function() {
                    document.getElementById('modalPeringatan').classList.add('hidden');
                    statusSelect.value = ""; // Kembali kosong
                };
            }

            // ===============================
            // MODAL NONAKTIF
            // ===============================
            const yaNonaktif = document.getElementById('yaNonaktif');
            const tidakNonaktif = document.getElementById('tidakNonaktif');

            if (yaNonaktif) {
                yaNonaktif.onclick = function() {

                    fetch("/pelamar/update-kategori/{{ $pelamar->id }}", {
                            method: "PUT",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                kategori: "kandidat nonaktif"
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            location.reload();
                        });

                };
            }

            if (tidakNonaktif) {
                tidakNonaktif.onclick = function() {
                    document.getElementById('modalNonaktif').classList.add('hidden');
                    statusSelect.value = "";
                };
            }

        });
    </script>



    @include('layouts.footer')
@endsection
