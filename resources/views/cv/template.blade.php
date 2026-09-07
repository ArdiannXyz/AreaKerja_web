<div class="max-w-5xl mx-auto bg-white p-6 sm:p-10 text-slate-800 shadow-lg rounded-2xl print:shadow-none print:p-6 print:rounded-none">
    
    <!-- ================= HEADER SECTION ================= -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-8 border-b-2 border-slate-100 gap-6">
        
        <!-- Foto Profil & Nama -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start md:items-center gap-5 w-full md:w-auto text-center sm:text-left">
            <div class="relative flex-shrink-0">
                @if (!empty($profileImgBase64))
                    <img src="{{ $profileImgBase64 }}" alt="Foto Profil"
                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-3 border-[#00509d] shadow-md flex-shrink-0 bg-slate-50">
                @elseif (!empty($data->img_profile) && file_exists(public_path('storage/' . $data->img_profile)))
                    <img src="{{ asset('storage/' . $data->img_profile) }}" alt="Foto Profil"
                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-3 border-[#00509d] shadow-md flex-shrink-0 bg-slate-50">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($data->nama_pelamar ?? $data->user->username ?? 'Pelamar') }}&background=00509d&color=fff&size=128" alt="Foto Profil"
                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-3 border-[#00509d] shadow-md flex-shrink-0">
                @endif

                @if (optional($data)->kategori === 'kandidat aktif')
                    <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-1 shadow-md border border-slate-100">
                        <img src="{{ $logoBase64 }}" class="h-6 w-6 object-contain" alt="Badge Kandidat AreaKerja" title="Kandidat Resmi AreaKerja">
                    </div>
                @endif
            </div>

            <div>
                <div class="flex items-center justify-center sm:justify-start gap-2 mb-1 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-bold text-[#00509d] tracking-tight">
                        {{ $data->nama_pelamar ?? $data->user->username ?? 'Nama Pelamar' }}
                    </h1>
                </div>

                @if (optional($data)->kategori === 'kandidat aktif')
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-200 mb-2">
                        ★ Kandidat Terverifikasi AreaKerja
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 mb-2">
                        Pelamar Kerja
                    </span>
                @endif

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-sm">
                    @php
                        $alamat = $data->alamat_pelamar->first();
                    @endphp
                    @if ($alamat)
                        {{ $alamat->detail ? $alamat->detail . ', ' : '' }}
                        {{ $alamat->desa ? 'Desa ' . $alamat->desa . ', ' : '' }}
                        {{ $alamat->kecamatan ? 'Kec. ' . $alamat->kecamatan . ', ' : '' }}
                        <br class="hidden sm:inline">
                        {{ $alamat->kota ?? '' }}{{ $alamat->provinsi ? ', ' . $alamat->provinsi : '' }} {{ $alamat->kode_pos ?? '' }}
                    @else
                        {{ $data->alamat ?? 'Alamat belum diatur' }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Kontak & Media Sosial -->
        <div class="text-xs sm:text-sm space-y-2.5 text-slate-700 w-full md:w-auto bg-slate-50 p-4 rounded-xl border border-slate-100">
            <!-- Email -->
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#00509d]/10 text-[#00509d] flex items-center justify-center flex-shrink-0">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <span class="font-medium text-slate-800 truncate max-w-[200px] sm:max-w-xs">{{ $data->user->email ?? '-' }}</span>
            </div>

            <!-- Telepon -->
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#00509d]/10 text-[#00509d] flex items-center justify-center flex-shrink-0">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <span class="font-medium text-slate-800">{{ $data->telepon_pelamar ?? '-' }}</span>
            </div>

            <!-- Instagram -->
            @php
                $ig = $data->social_links['instagram'] ?? null;
            @endphp
            @if (!empty($ig))
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#00509d]/10 text-[#00509d] flex items-center justify-center flex-shrink-0">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </div>
                    <span class="font-medium text-slate-800 truncate max-w-[200px] sm:max-w-xs">{{ $ig }}</span>
                </div>
            @endif

            <!-- LinkedIn -->
            @php
                $linkedin = $data->social_links['linkedin'] ?? null;
            @endphp
            @if (!empty($linkedin))
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#00509d]/10 text-[#00509d] flex items-center justify-center flex-shrink-0">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                            <rect x="2" y="9" width="4" height="12"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </div>
                    <span class="font-medium text-slate-800 truncate max-w-[200px] sm:max-w-xs">{{ $linkedin }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- ================= BODY CV: 2 KOLOM ================= -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mt-8">
        
        <!-- KOLOM KIRI (5/12): Tentang Saya, Keahlian, Organisasi, Data Pribadi -->
        <div class="md:col-span-5 space-y-7">
            
            <!-- Tentang Saya -->
            <section>
                <div class="flex items-center gap-2 pb-1.5 border-b-2 border-[#00509d] mb-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#00509d]"></div>
                    <h2 class="font-bold text-[#00509d] text-sm uppercase tracking-wider">Tentang Saya</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed text-justify break-words">
                    {{ $data->deskripsi_diri ?: 'Belum ada deskripsi profil diri.' }}
                </p>
            </section>

            <!-- Keahlian & Kompetensi -->
            <section>
                <div class="flex items-center gap-2 pb-1.5 border-b-2 border-[#00509d] mb-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#00509d]"></div>
                    <h2 class="font-bold text-[#00509d] text-sm uppercase tracking-wider">Keahlian & Kompetensi</h2>
                </div>

                @if (!empty($data->skill) && count($data->skill) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($data->skill as $s)
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50/80 border border-blue-200 text-slate-800 text-xs font-semibold rounded-lg">
                                <span>{{ $s->skill }}</span>
                                <span class="text-[10px] text-[#00509d] font-normal border-l border-blue-200 pl-1.5">
                                    {{ $s->experience_level ?? 'Menengah' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada data keahlian.</p>
                @endif
            </section>

            <!-- Pengalaman Organisasi -->
            <section>
                <div class="flex items-center gap-2 pb-1.5 border-b-2 border-[#00509d] mb-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#00509d]"></div>
                    <h2 class="font-bold text-[#00509d] text-sm uppercase tracking-wider">Pengalaman Organisasi</h2>
                </div>

                @if ($data->pengalaman_organisasi && $data->pengalaman_organisasi->count() > 0)
                    <div class="space-y-4">
                        @foreach ($data->pengalaman_organisasi as $org)
                            <div class="border-l-2 border-blue-200 pl-3 py-0.5">
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900">{{ $org->jabatan }}</h3>
                                <p class="text-xs font-semibold text-[#00509d]">{{ $org->nama_organisasi }}</p>
                                <span class="text-[11px] font-medium text-slate-500 block mb-1">
                                    {{ $org->tahun_awal }} — {{ $org->tahun_akhir ?? 'Sekarang' }}
                                </span>
                                @if (!empty($org->deskripsi))
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $org->deskripsi }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada pengalaman organisasi.</p>
                @endif
            </section>

            <!-- Data Pribadi -->
            <section class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div class="flex items-center gap-2 pb-1.5 border-b border-slate-200 mb-3">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Data Pribadi</h3>
                </div>
                <div class="space-y-2 text-xs text-slate-700">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Gender</span>
                        <span class="font-semibold text-slate-800">{{ ucfirst($data->gender ?? '-') }}</span>
                    </div>
                    @if ($data->tanggal_lahir)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal Lahir</span>
                            <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Usia</span>
                            <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($data->tanggal_lahir)->age }} Tahun</span>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <!-- KOLOM KANAN (7/12): Pengalaman Kerja & Latar Belakang Pendidikan -->
        <div class="md:col-span-7 space-y-7">
            
            <!-- Pengalaman Kerja -->
            <section>
                <div class="flex items-center gap-2 pb-1.5 border-b-2 border-[#00509d] mb-4">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#00509d]"></div>
                    <h2 class="font-bold text-[#00509d] text-sm uppercase tracking-wider">Pengalaman Kerja</h2>
                </div>

                @if ($data->pengalaman_kerja && $data->pengalaman_kerja->count() > 0)
                    <div class="space-y-5">
                        @foreach ($data->pengalaman_kerja as $p)
                            <div class="relative border-l-2 border-[#00509d] pl-4 pb-1">
                                <div class="absolute -left-[5px] top-1 w-2 h-2 rounded-full bg-[#00509d]"></div>
                                <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1 mb-1">
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                                        {{ $p->posisi_pekerjaan ?? $p->jabatan_pekerjaan ?? '-' }}
                                    </h3>
                                    <span class="text-xs font-semibold text-[#00509d] bg-blue-50 px-2.5 py-0.5 rounded-md self-start sm:self-auto">
                                        {{ $p->tahun_awal }} — {{ $p->tahun_akhir ?? 'Sekarang' }}
                                    </span>
                                </div>
                                <p class="text-xs font-semibold text-slate-700 mb-2">{{ $p->nama_perusahaan ?? '-' }}</p>
                                @if (!empty($p->deskripsi))
                                    <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line text-justify">{{ $p->deskripsi }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada riwayat pengalaman kerja.</p>
                @endif
            </section>

            <!-- Latar Belakang Pendidikan -->
            <section>
                <div class="flex items-center gap-2 pb-1.5 border-b-2 border-[#00509d] mb-4">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#00509d]"></div>
                    <h2 class="font-bold text-[#00509d] text-sm uppercase tracking-wider">Latar Belakang Pendidikan</h2>
                </div>

                @if ($data->riwayat_pendidikan && $data->riwayat_pendidikan->count() > 0)
                    <div class="space-y-4">
                        @foreach ($data->riwayat_pendidikan as $r)
                            <div class="relative border-l-2 border-[#00509d] pl-4 pb-1">
                                <div class="absolute -left-[5px] top-1 w-2 h-2 rounded-full bg-[#00509d]"></div>
                                <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1 mb-1">
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900">
                                        {{ $r->pendidikan }} {{ $r->jurusan ? '— ' . $r->jurusan : '' }}
                                    </h3>
                                    <span class="text-xs font-semibold text-[#00509d] bg-blue-50 px-2.5 py-0.5 rounded-md self-start sm:self-auto">
                                        {{ $r->tahun_awal }} — {{ $r->tahun_akhir ?? 'Sekarang' }}
                                    </span>
                                </div>
                                <p class="text-xs font-medium text-slate-600">{{ $r->asal_pendidikan ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada riwayat pendidikan.</p>
                @endif
            </section>

        </div>
    </div>

    <!-- ================= FOOTER SECTION ================= -->
    <div class="mt-12 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
        <div class="flex items-center gap-2">
            @if (!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo AreaKerja" class="h-6 w-auto object-contain">
            @endif
            <span class="font-semibold text-slate-700">areakerja.com</span>
        </div>
        <p class="text-center sm:text-right text-[11px] text-slate-400">
            Dokumen resmi Curriculum Vitae (CV) &bull; Hak Cipta &copy; {{ date('Y') }} AreaKerja.com
        </p>
    </div>

</div>
