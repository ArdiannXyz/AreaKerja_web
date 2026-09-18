@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header Topbar -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.recruitment', $recruitment->lowonganPerusahaan->perusahaan_id) }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0"
                   title="Kembali ke Daftar Recruitment">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Recruitment / <span class="text-slate-500 font-medium">Detail</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Detail Recruitment</h1>
                </div>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end flex-wrap">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <div class="space-y-6 max-w-5xl mx-auto">

            <!-- Hero Banner Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    @if ($recruitment->pelamar->img_profile)
                        <img src="{{ asset('storage/' . $recruitment->pelamar->img_profile) }}"
                             alt="{{ $recruitment->pelamar->nama_pelamar }}"
                             class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover border border-slate-100 flex-shrink-0 shadow-xs">
                    @else
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-br from-[#00509d] to-[#0077b6] flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                            {{ strtoupper(substr($recruitment->pelamar->nama_pelamar ?? 'P', 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                                {{ $recruitment->pelamar->nama_pelamar }}
                            </h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 self-center sm:self-auto">
                                Direkrut
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mb-2">
                            Posisi Lowongan: <span class="font-semibold text-[#00509d]">{{ $recruitment->lowonganPerusahaan->nama ?? '-' }}</span> &bull; 
                            Perusahaan: <span class="font-medium text-slate-700">{{ $recruitment->lowonganPerusahaan->perusahaan->nama_perusahaan ?? '-' }}</span>
                        </p>
                        <p class="text-xs text-slate-500 italic max-w-2xl">
                            "{{ $recruitment->pelamar->deskripsi_diri ?? 'Belum ada ringkasan deskripsi diri.' }}"
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <form action="{{ route('admin.recruitment.destroy', $recruitment->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus recruitment ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-semibold px-4 py-2 rounded-xl transition shadow-xs">
                                <i class="ph ph-trash text-sm"></i>
                                Hapus Recruitment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Informasi Kontak & Akun -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-user-circle text-base text-[#00509d]"></i>
                            Data Pribadi & Kontak
                        </h3>

                        <div class="space-y-3.5 text-xs">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">User ID</span>
                                <span class="font-bold text-slate-800">#{{ $recruitment->pelamar->user->id ?? '-' }}</span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Email</span>
                                <span class="font-semibold text-slate-800 break-all">{{ $recruitment->pelamar->user->email ?? '-' }}</span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">No. Telepon / WhatsApp</span>
                                <span class="font-semibold text-slate-800">{{ $recruitment->pelamar->telepon_pelamar ?? '-' }}</span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Gender</span>
                                <span class="font-semibold text-slate-800 capitalize">{{ $recruitment->pelamar->gender ?? '-' }}</span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-400 font-medium block mb-0.5">Alamat Domisili</span>
                                @if ($recruitment->pelamar->alamat_pelamar && $recruitment->pelamar->alamat_pelamar->isNotEmpty())
                                    @php $alamat = $recruitment->pelamar->alamat_pelamar->first(); @endphp
                                    <p class="font-semibold text-slate-800 leading-relaxed">{{ $alamat->detail ?: $alamat->desa }}</p>
                                    <p class="text-slate-500 mt-0.5">{{ $alamat->kecamatan }}, {{ $alamat->kota }}, {{ $alamat->provinsi }}</p>
                                @else
                                    <span class="text-slate-400 italic">Belum diisi</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-share-network text-base text-[#00509d]"></i>
                            Media Sosial
                        </h3>

                        <div class="space-y-2.5 text-xs">
                            @if ($recruitment->pelamar->sosmed?->instagram)
                                <a href="{{ $recruitment->pelamar->sosmed->instagram }}" target="_blank"
                                    class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-blue-50 transition border border-slate-100 text-slate-700">
                                    <span class="flex items-center gap-2">
                                        <i class="ph ph-instagram-logo text-pink-600 text-base"></i> Instagram
                                    </span>
                                    <span class="text-[#00509d] font-semibold truncate max-w-[120px]">Buka</span>
                                </a>
                            @endif

                            @if ($recruitment->pelamar->sosmed?->linkedin)
                                <a href="{{ $recruitment->pelamar->sosmed->linkedin }}" target="_blank"
                                    class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-blue-50 transition border border-slate-100 text-slate-700">
                                    <span class="flex items-center gap-2">
                                        <i class="ph ph-linkedin-logo text-blue-700 text-base"></i> LinkedIn
                                    </span>
                                    <span class="text-[#00509d] font-semibold truncate max-w-[120px]">Buka</span>
                                </a>
                            @endif

                            @if (!$recruitment->pelamar->sosmed?->instagram && !$recruitment->pelamar->sosmed?->linkedin)
                                <p class="text-xs text-slate-400 italic">Belum ada media sosial ditambahkan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Riwayat & Portofolio -->
                <div class="md:col-span-2 space-y-6">

                    <!-- Keahlian -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <i class="ph ph-star text-base text-[#00509d]"></i>
                            Keahlian
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($recruitment->pelamar->skill ?? [] as $s)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200/60 text-[#00509d] text-xs font-semibold">
                                    {{ $s->skill }}
                                </span>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada keahlian ditambahkan.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pengalaman Kerja -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-briefcase text-base text-[#00509d]"></i>
                            Pengalaman Kerja
                        </h3>
                        <div class="space-y-3">
                            @forelse ($recruitment->pelamar->pengalaman_kerja ?? [] as $k)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex justify-between items-start gap-2">
                                        <h4 class="text-sm font-bold text-slate-800">{{ $k->posisi_pekerjaan }}</h4>
                                        <span class="text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md">
                                            {{ $k->tahun_awal }} - {{ $k->tahun_akhir ?: 'Sekarang' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 font-medium mt-1">{{ $k->nama_perusahaan }}</p>
                                    @if ($k->deskripsi)
                                        <p class="text-xs text-slate-500 mt-2 leading-relaxed whitespace-pre-line">{{ $k->deskripsi }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada pengalaman kerja.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Riwayat Pendidikan -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-graduation-cap text-base text-[#00509d]"></i>
                            Riwayat Pendidikan
                        </h3>
                        <div class="space-y-3">
                            @forelse ($recruitment->pelamar->riwayat_pendidikan ?? [] as $pend)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex justify-between items-start gap-2">
                                        <div>
                                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded mr-1">
                                                {{ $pend->pendidikan }}
                                            </span>
                                            <span class="text-sm font-bold text-slate-800">{{ $pend->asal_pendidikan }}</span>
                                        </div>
                                        <span class="text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md">
                                            {{ $pend->tahun_awal }} - {{ $pend->tahun_akhir ?: 'Sekarang' }}
                                        </span>
                                    </div>
                                    @if ($pend->jurusan)
                                        <p class="text-xs text-slate-600 mt-1">Jurusan: {{ $pend->jurusan }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada data pendidikan.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Organisasi -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ph ph-users-three text-base text-[#00509d]"></i>
                            Pengalaman Organisasi
                        </h3>
                        <div class="space-y-3">
                            @forelse ($recruitment->pelamar->pengalaman_organisasi ?? [] as $org)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex justify-between items-start gap-2">
                                        <h4 class="text-sm font-bold text-slate-800">{{ $org->jabatan }} &mdash; <span class="text-[#00509d]">{{ $org->nama_organisasi }}</span></h4>
                                        <span class="text-[11px] font-semibold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md">
                                            {{ $org->tahun_awal }} - {{ $org->tahun_akhir ?: 'Sekarang' }}
                                        </span>
                                    </div>
                                    @if ($org->deskripsi)
                                        <p class="text-xs text-slate-500 mt-2 leading-relaxed whitespace-pre-line">{{ $org->deskripsi }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada pengalaman organisasi.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>
@endsection
