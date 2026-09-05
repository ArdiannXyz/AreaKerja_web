@extends('layouts.index')
@section('content')

@guest
    <div class="bg-white min-h-screen text-slate-800 pt-20 pb-20">
        {{-- Top Title Bar --}}
        <div class="border-b-2 border-[#00509d] bg-white py-4 mb-8">
            <h1 class="text-center font-bold text-[#00509d] text-lg md:text-xl">
                Profil
            </h1>
        </div>

        {{-- TAMPILAN BELUM LOGIN SESUAI FIGMA (Profil - Belum Login.png) --}}
        <div class="max-w-md mx-auto px-4 py-20 text-center flex flex-col items-center justify-center min-h-[50vh]">
            {{-- Message --}}
            <p class="text-[#00509d] font-bold text-base md:text-lg leading-relaxed mb-10 max-w-xs">
                Siapkan CV mu dengan melengkapi data diri untuk kemudahan dalam melamar pekerjaan
            </p>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-center gap-4 w-full max-w-xs">
                <a href="{{ route('login') }}"
                    class="flex-1 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-sm transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="flex-1 border-2 border-[#00509d] text-[#00509d] hover:bg-[#00509d] hover:text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm transition">
                    Daftar
                </a>
            </div>
        </div>
    </div>
@else
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-28 pb-16">
        <h2 class="text-2xl font-bold mb-6 text-slate-800 tracking-tight">Profil Akun</h2>
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-sm">
            <!-- Header: Avatar & Tombol Aksi View -->
            <div class="border-2 border-[#00509d] rounded-2xl p-6 md:p-8 mb-8 bg-blue-50/20">
                <div class="flex flex-col md:flex-row items-center md:justify-between gap-6">

                <!-- Kiri: Foto Profil & Status -->
                    <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8 w-full md:w-auto">
                        <div class="flex flex-col items-center w-full md:w-auto">
                            <div class="relative inline-flex items-center gap-3">
                                <div x-data="{ zoom: false }" class="cursor-pointer inline-block" @click="zoom = !zoom">
                                    <img id="pp"
                                        class="w-36 h-36 sm:w-40 sm:h-40 object-cover rounded-full border-2 border-[#00509d] shadow-sm transition-transform duration-300"
                                        :class="zoom ? 'scale-[2] z-50 relative' : 'scale-100'"
                                        src="{{ Auth::user()->pelamar->img_profile
                                            ? asset('storage/' . Auth::user()->pelamar->img_profile)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->username) . '&background=00509d&color=fff&size=128' }}"
                                        alt="Profile">
                                </div>

                                @if (optional($pelamar)->kategori === 'kandidat aktif')
                                    <div class="absolute bottom-1 right-1 z-20">
                                        <div class="relative group bg-white rounded-full shadow-sm">
                                            <img src="{{ asset('images/logo_area_kerja_biru.png') }}" class="h-10 w-11 object-contain" alt="Badge Areakerja">
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-56 bg-slate-800 text-white text-xs rounded-md px-3 py-2 opacity-0 invisible shadow-lg group-hover:opacity-100 group-hover:visible transition duration-200 z-50 text-center">
                                                Badge Areakerja diberikan kepada pengguna yang telah resmi menjadi <strong>Kandidat Areakerja</strong>.
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Status Select Box -->
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
                                    class="w-full border-2 border-[#00509d] text-[#00509d] bg-white font-semibold rounded-xl px-3 py-1.5 text-xs cursor-pointer appearance-none shadow-2xs">
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

                                <input type="hidden" id="kategoriPelamar" value="{{ strtolower($pelamar->kategori ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Tombol Unduh CV & Edit Profil -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <a href="{{ route('cv.download', Auth::user()->pelamar->id) }}"
                            class="bg-[#00509d] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-[#003d7a] w-full sm:w-auto text-center shadow-sm transition">
                            Unduh CV
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="bg-[#00509d] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-[#003d7a] w-full sm:w-auto text-center shadow-sm flex items-center justify-center gap-2 transition">
                            <i class="ph ph-pencil-simple font-bold"></i>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Grid Dua Kolom -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Kolom Kiri -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-2">Data Diri</h3>

                    <div>
                        <label class="text-xs font-bold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" value="{{ Auth::user()->pelamar->nama_pelamar ?? '-' }}" disabled readonly
                            class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700">Gender <span class="text-red-500">*</span></label>
                        <div class="flex gap-6 mt-2 text-sm">
                            <label class="flex items-center gap-2 text-slate-700">
                                <input type="radio" disabled {{ (Auth::user()->pelamar->gender ?? '') === 'laki-laki' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#00509d] border-2 border-[#00509d]">
                                Laki - Laki
                            </label>
                            <label class="flex items-center gap-2 text-slate-700">
                                <input type="radio" disabled {{ (Auth::user()->pelamar->gender ?? '') === 'perempuan' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#00509d] border-2 border-[#00509d]">
                                Perempuan
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="text" value="{{ optional(Auth::user()->pelamar->tanggal_lahir)->format('d/m/Y') ?? '-' }}" disabled readonly
                            class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700">No. Tlp <span class="text-red-500">*</span></label>
                        <input type="text" value="{{ Auth::user()->pelamar->telepon_pelamar ?? '-' }}" disabled readonly
                            class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700">Deskripsi Diri <span class="text-red-500">*</span></label>
                        <textarea disabled readonly rows="3"
                            class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">{{ Auth::user()->pelamar->deskripsi_diri ?? '-' }}</textarea>
                    </div>

                    <!-- Bagian Alamat Pelamar -->
                    <div class="mt-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-bold text-slate-700">Alamat Pelamar</label>
                            <a href="{{ route('form_alamat') }}" class="text-[#00509d] text-xs font-bold hover:underline">
                                + Tambah Alamat
                            </a>
                        </div>

                        @if (Auth::user()->pelamar->alamat_pelamar->count() > 0)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                                @foreach (Auth::user()->pelamar->alamat_pelamar as $almt)
                                    <div class="flex items-start justify-between border-b border-slate-200 pb-3 last:border-0 last:pb-0">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-800 text-sm">{{ $almt->label ?? 'Alamat' }}</span>
                                                @if (!empty($almt->is_primary))
                                                    <span class="bg-blue-100 text-[#00509d] text-[10px] font-bold px-2 py-0.5 rounded">Utama</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-600 mt-1">
                                                {{ implode(', ', array_filter([$almt->desa, $almt->kecamatan, $almt->kota, $almt->provinsi, $almt->kode_pos])) }}
                                            </p>
                                            @if(!empty($almt->detail))
                                                <p class="text-xs text-slate-500 italic mt-0.5">{{ $almt->detail }}</p>
                                            @endif
                                        </div>
                                        <a href="{{ route('alamat.edit', $almt->id) }}" class="text-[#00509d] hover:text-[#003d7a] p-1" title="Edit Alamat">
                                            <i class="ph ph-pencil-simple font-bold text-base"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ route('form_alamat') }}"
                                class="flex items-center justify-between border-2 border-[#00509d] rounded-xl w-full px-4 py-3 text-[#00509d] font-bold hover:bg-blue-50/60 transition">
                                <span>Tambahkan Alamat</span>
                                <span class="text-2xl font-bold">+</span>
                            </a>
                        @endif
                    </div>

                    <!-- Pendidikan -->
                    @if (Auth::user()->pelamar->riwayat_pendidikan->count() > 0)
                        <label class="text-xs font-bold text-slate-700 mt-2">Pendidikan</label>
                        <div class="flex justify-between items-start gap-2">
                            <div class="p-4 w-full bg-slate-50 border border-slate-200 rounded-xl">
                                @foreach (Auth::user()->pelamar->riwayat_pendidikan as $pend)
                                    <div class="mb-4 last:mb-0">
                                        <h4 class="font-bold text-slate-800 text-sm">
                                            {{ $pend->asal_pendidikan }} - {{ $pend->pendidikan }}
                                            ({{ $pend->tahun_awal }} - {{ $pend->tahun_akhir }})
                                        </h4>
                                        <p class="text-slate-600 text-xs mt-0.5">
                                            {{ $pend->jurusan }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-pendidikan" data-modal-toggle="show-pendidikan" type="button" class="p-2 text-[#00509d] hover:text-[#003d7a]">
                                <i class="ph ph-pencil-simple font-bold text-lg"></i>
                            </button>
                        </div>
                    @else
                        <div class="mb-2 mt-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Pendidikan</label>
                            <button data-modal-target="create_pendidikanmodal" data-modal-toggle="create_pendidikanmodal" type="button"
                                class="flex items-center justify-between border-2 border-[#00509d] rounded-xl w-full px-4 py-3 text-[#00509d] font-bold hover:bg-blue-50/60 transition">
                                <span>Tambahkan Pendidikan</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif

                    <!-- Organisasi -->
                    @if (Auth::user()->pelamar->pengalaman_organisasi->count() > 0)
                        <label class="text-xs font-bold text-slate-700 mt-2">Organisasi</label>
                        <div class="flex justify-between items-start gap-2">
                            <div class="p-4 w-full bg-slate-50 border border-slate-200 rounded-xl">
                                @foreach (Auth::user()->pelamar->pengalaman_organisasi as $org)
                                    <div class="mb-4 last:mb-0">
                                        <h4 class="font-bold text-slate-800 text-sm">
                                            {{ $org->jabatan }} - {{ $org->nama_organisasi }}
                                            ({{ $org->tahun_awal }} - {{ $org->tahun_akhir }})
                                        </h4>
                                        <p class="text-slate-600 text-xs mt-0.5">
                                            {{ $org->deskripsi }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-org" data-modal-toggle="show-org" type="button" class="p-2 text-[#00509d] hover:text-[#003d7a]">
                                <i class="ph ph-pencil-simple font-bold text-lg"></i>
                            </button>
                        </div>
                    @else
                        <div class="mb-2 mt-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Organisasi</label>
                            <button data-modal-target="create_organisasimodal" data-modal-toggle="create_organisasimodal" type="button"
                                class="flex items-center justify-between border-2 border-[#00509d] rounded-xl w-full px-4 py-3 text-[#00509d] font-bold hover:bg-blue-50/60 transition">
                                <span>Tambahkan Organisasi</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif

                    <!-- Pengalaman Kerja -->
                    @if (Auth::user()->pelamar->pengalaman_kerja->count() > 0)
                        <label class="text-xs font-bold text-slate-700 mt-2">Pengalaman Kerja</label>
                        <div class="flex justify-between items-start gap-2">
                            <div class="p-4 w-full bg-slate-50 border border-slate-200 rounded-xl">
                                @foreach (Auth::user()->pelamar->pengalaman_kerja as $kerja)
                                    <div class="mb-4 last:mb-0">
                                        <h4 class="font-bold text-slate-800 text-sm">
                                            {{ $kerja->posisi_pekerjaan }} - {{ $kerja->nama_perusahaan }}
                                            ({{ $kerja->tahun_awal }} - {{ $kerja->tahun_akhir }})
                                        </h4>
                                        <p class="text-slate-600 text-xs mt-0.5">
                                            {{ $kerja->deskripsi }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-kerja" data-modal-toggle="show-kerja" type="button" class="p-2 text-[#00509d] hover:text-[#003d7a]">
                                <i class="ph ph-pencil-simple font-bold text-lg"></i>
                            </button>
                        </div>
                    @else
                        <div class="mb-2 mt-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Pengalaman Kerja</label>
                            <button data-modal-target="create_kerjamodal" data-modal-toggle="create_kerjamodal" type="button"
                                class="flex items-center justify-between border-2 border-[#00509d] rounded-xl w-full px-4 py-3 text-[#00509d] font-bold hover:bg-blue-50/60 transition">
                                <span>Tambahkan Pengalaman Kerja</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif

                    <!-- Skill -->
                    @if (Auth::user()->pelamar->skill->count() > 0)
                        <label class="text-xs font-bold text-slate-700 mt-2">Skill</label>
                        <div class="flex justify-between items-start gap-2">
                            <div class="p-4 w-full bg-slate-50 border border-slate-200 rounded-xl">
                                @foreach (Auth::user()->pelamar->skill as $sk)
                                    <div class="mb-4 last:mb-0">
                                        <h4 class="font-bold text-slate-800 text-sm">
                                            {{ $sk->skill }}
                                        </h4>
                                        <p class="text-slate-600 text-xs mt-0.5">
                                            {{ $sk->experience_level }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <button data-modal-target="show-skill" data-modal-toggle="show-skill" type="button" class="p-2 text-[#00509d] hover:text-[#003d7a]">
                                <i class="ph ph-pencil-simple font-bold text-lg"></i>
                            </button>
                        </div>
                    @else
                        <div class="mb-2 mt-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Skill</label>
                            <button data-modal-target="create_skillmodal" data-modal-toggle="create_skillmodal" type="button"
                                class="flex items-center justify-between border-2 border-[#00509d] rounded-xl w-full px-4 py-3 text-[#00509d] font-bold hover:bg-blue-50/60 transition">
                                <span>Tambahkan Skill</span>
                                <span class="text-2xl font-bold">+</span>
                            </button>
                        </div>
                    @endif

                    <!-- Sosial Media View -->
                    <div class="flex flex-col gap-2 mt-4">
                        <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-2">Sosial Media</h3>
                        
                        <label class="text-xs font-bold text-slate-700">Instagram</label>
                        <input type="text" value="{{ $pelamar->social_links['instagram'] ?? '-' }}" disabled readonly
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                        
                        <label class="text-xs font-bold text-slate-700">LinkedIn</label>
                        <input type="text" value="{{ $pelamar->social_links['linkedin'] ?? '-' }}" disabled readonly
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                        
                        <label class="text-xs font-bold text-slate-700">Website</label>
                        <input type="text" value="{{ $pelamar->social_links['website'] ?? '-' }}" disabled readonly
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                        
                        <label class="text-xs font-bold text-slate-700">Twitter</label>
                        <input type="text" value="{{ $pelamar->social_links['twitter'] ?? '-' }}" disabled readonly
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-2">Informasi Akun</h3>

                    <div>
                        <label class="text-xs font-bold text-slate-700">Nama Pengguna/Username <span class="text-red-500">*</span></label>    
                        <input type="text" value="{{ Auth::user()->username }}" disabled readonly
                            class="w-full mt-1 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 text-slate-800 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700">Email <span class="text-red-500">*</span></label>
                        <div class="relative mt-1">
                            <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-slate-100 text-slate-500 cursor-not-allowed">
                            <span class="absolute right-3 top-2.5 text-[11px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-lg border border-emerald-300">
                                Terverifikasi
                            </span>
                        </div>
                    </div>

                    <!-- Ekspektasi Gaji -->
                    <div class="mt-4">
                        <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-2 mb-4">Ekspektasi Gaji</h3>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex items-center border border-slate-300 bg-slate-50 rounded-xl px-3.5 py-2.5 text-slate-800 w-full sm:w-56 gap-2">
                                <span class="text-[#00509d] font-bold text-sm">Rp.</span>
                                <input type="text" value="{{ Auth::user()->pelamar->gaji_minimal ? number_format(Auth::user()->pelamar->gaji_minimal, 0, ',', '.') : '-' }}" disabled readonly
                                    class="border-none w-full outline-none text-sm font-semibold bg-transparent text-slate-800 cursor-not-allowed">
                            </div>

                            <span class="text-center hidden sm:block text-slate-400 font-bold">-</span>
                            <span class="text-center sm:hidden text-slate-500 text-xs">sampai</span>

                            <div class="flex items-center border border-slate-300 bg-slate-50 rounded-xl px-3.5 py-2.5 text-slate-800 w-full sm:w-56 gap-2">
                                <span class="text-[#00509d] font-bold text-sm">Rp.</span>
                                <input type="text" value="{{ Auth::user()->pelamar->gaji_maksimal ? number_format(Auth::user()->pelamar->gaji_maksimal, 0, ',', '.') : '-' }}" disabled readonly
                                    class="border-none w-full outline-none text-sm font-semibold bg-transparent text-slate-800 cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
@endguest

    @include('layouts.footer')

@endsection
