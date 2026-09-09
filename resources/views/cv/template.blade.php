    {{-- <div class="p-4 sm:ml-64"> --}}
    <div class="overflow-y-auto">
        <div class="max-w-6xl mx-auto bg-white p-6 md:p-10 text-gray-800">
            <main class="flex-1 p-4 md:p-6 bg-white overflow-y-auto">

                <!-- Header -->
                <div class="flex flex-col md:flex-row items-center md:justify-between pb-6 gap-4 md:gap-0">
                    <!-- Foto & Nama -->
                    <div class="flex flex-col md:flex-row items-center gap-4 md:gap-4 w-full md:w-auto">
                        <div class="relative flex-shrink-0">
                            @if (!empty($pdf))
                                <img src="{{ public_path('storage/' . $data->img_profile) }}" alt="Profile"
                                    class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover border-4 border-gray-300 flex-shrink-0">
                            @else
                                <img src="{{ asset('storage/' . $data->img_profile) }}" alt="Profile"
                                    class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover border-4 border-gray-300 flex-shrink-0">
                            @endif
                            @if (optional($data->user->pelamar)->kategori === 'kandidat aktif')
                                <div class="absolute bottom-1 right-1 z-20">
                                    <div class="relative group bg-white rounded-full">
                                        <img src="{{ $logoBase64 }}" class="h-7 w-8" alt="Badge Areakerja">

                                        <!-- Tooltip -->
                                        <div
                                            class="absolute top-full mt-2 left-1/2 -translate-x-[60%] sm:-translate-x-1 w-56 bg-gray-200 text-gray-800 text-xs rounded-md  px-3 py-2 opacity-0 invisible shadow-lg group-hover:opacity-100 group-hover:visible transition duration-200 z-50 text-center">
                                            Badge Areakerja diberikan kepada pengguna yang telah resmi
                                            menjadi <strong>Kandidat Areakerja</strong>.
                                        </div>

                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="w-full md:w-auto overflow-x-auto">
                            <div class="flex items-center gap-2 mb-2">
                                <h1 class="text-xl md:text-2xl font-bold text-[#00509d] break-words">
                                    {{ $data->user->pelamar->nama_pelamar ?? $data->user->username }}
                                </h1>
                            </div>

                            <p class="text-sm font-semibold break-words">
                                {{ optional($data->alamat_pelamar->first())->label ?? '-' }},
                                {{ optional($data->alamat_pelamar->first())->desa ?? '-' }} <br>
                                {{ optional($data->alamat_pelamar->first())->kecamatan ?? '-' }},
                                {{ optional($data->alamat_pelamar->first())->kota ?? '-' }},<br>
                                {{ optional($data->alamat_pelamar->first())->provinsi ?? '-' }},
                                {{ optional($data->alamat_pelamar->first())->kode_pos ?? '-' }}
                            </p>
                        </div>
                    </div>


                    <!-- Kontak -->
                    <div class="text-sm space-y-2 text-right font-semibold w-full md:w-auto break-words">
                        <p class="flex items-center gap-2 flex-wrap">
                            <svg width="17" height="12" viewBox="0 0 17 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.6779 3.9625C16.8074 3.86563 17 3.95625 17 4.10938V10.5C17 11.3281 16.2861 12 15.4062 12H1.59375C0.713867 12 0 11.3281 0 10.5V4.1125C0 3.95625 0.189258 3.86875 0.32207 3.96562C1.06582 4.50937 2.05195 5.2 5.43867 7.51562C6.13926 7.99687 7.32129 9.00938 8.5 9.00313C9.68535 9.0125 10.8906 7.97813 11.5646 7.51562C14.9514 5.2 15.9342 4.50625 16.6779 3.9625ZM8.5 8C9.27031 8.0125 10.3793 7.0875 10.9371 6.70625C15.3432 3.69688 15.6785 3.43437 16.6945 2.68437C16.8871 2.54375 17 2.325 17 2.09375V1.5C17 0.671875 16.2861 0 15.4062 0H1.59375C0.713867 0 0 0.671875 0 1.5V2.09375C0 2.325 0.112891 2.54062 0.305469 2.68437C1.32148 3.43125 1.65684 3.69688 6.06289 6.70625C6.6207 7.0875 7.72969 8.0125 8.5 8Z"
                                    fill="#00509d" />
                            </svg>
                            {{ $data->user->email }}
                        </p>
                        <p class="flex items-center gap-2 flex-wrap">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5152 12.0128L12.7964 10.419C12.6375 10.3513 12.461 10.3371 12.2933 10.3784C12.1256 10.4197 11.9759 10.5144 11.8667 10.6481L10.2198 12.6603C7.63511 11.4416 5.55505 9.36157 4.3364 6.77691L6.34855 5.13C6.48258 5.02099 6.57745 4.87127 6.61879 4.70352C6.66013 4.53577 6.64569 4.35911 6.57765 4.2003L4.98388 0.481485C4.90921 0.310289 4.77714 0.170514 4.61045 0.0862606C4.44376 0.00200721 4.2529 -0.0214431 4.07077 0.0199532L0.617589 0.816842C0.441997 0.85739 0.285334 0.956258 0.173169 1.09731C0.0610036 1.23836 -4.04491e-05 1.41326 2.01088e-08 1.59348C2.01088e-08 10.1102 6.90305 17 15.4065 17C15.5868 17.0001 15.7618 16.9391 15.9029 16.8269C16.044 16.7148 16.1429 16.5581 16.1835 16.3824L16.9804 12.9292C17.0215 12.7462 16.9976 12.5546 16.9127 12.3873C16.8277 12.2201 16.6872 12.0876 16.5152 12.0128Z"
                                    fill="#00509d" />
                            </svg>
                            {{ $data->telepon_pelamar }}
                        </p>
                        <p class="flex items-center gap-2 flex-wrap">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.5019 4.14141C6.08985 4.14141 4.14428 6.08742 4.14428 8.5C4.14428 10.9126 6.08985 12.8586 8.5019 12.8586C10.9139 12.8586 12.8595 10.9126 12.8595 8.5C12.8595 6.08742 10.9139 4.14141 8.5019 4.14141ZM8.5019 11.3336C6.94317 11.3336 5.66888 10.0629 5.66888 8.5C5.66888 6.93713 6.93938 5.66635 8.5019 5.66635C10.0644 5.66635 11.3349 6.93713 11.3349 8.5C11.3349 10.0629 10.0606 11.3336 8.5019 11.3336ZM14.0542 3.96313C14.0542 4.52834 13.5991 4.97975 13.0378 4.97975C12.4727 4.97975 12.0214 4.52455 12.0214 3.96313C12.0214 3.40171 12.4765 2.9465 13.0378 2.9465C13.5991 2.9465 14.0542 3.40171 14.0542 3.96313ZM16.9403 4.99492C16.8758 3.6331 16.5648 2.42681 15.5674 1.43295C14.5737 0.439083 13.3677 0.128026 12.0062 0.0597456C10.603 -0.0199152 6.39704 -0.0199152 4.99381 0.0597456C3.63609 0.124233 2.43006 0.43529 1.43263 1.42915C0.435193 2.42302 0.127998 3.62931 0.0597323 4.99113C-0.0199108 6.39468 -0.0199108 10.6015 0.0597323 12.0051C0.124205 13.3669 0.435193 14.5732 1.43263 15.5671C2.43006 16.5609 3.63229 16.872 4.99381 16.9403C6.39704 17.0199 10.603 17.0199 12.0062 16.9403C13.3677 16.8758 14.5737 16.5647 15.5674 15.5671C16.561 14.5732 16.872 13.3669 16.9403 12.0051C17.0199 10.6015 17.0199 6.39847 16.9403 4.99492ZM15.1274 13.511C14.8316 14.2545 14.259 14.8273 13.5118 15.127C12.393 15.5708 9.73826 15.4684 8.5019 15.4684C7.26553 15.4684 4.60697 15.5671 3.49197 15.127C2.74863 14.8311 2.17596 14.2583 1.87635 13.511C1.43263 12.392 1.53503 9.73664 1.53503 8.5C1.53503 7.26336 1.43642 4.60421 1.87635 3.48895C2.17217 2.74545 2.74484 2.17265 3.49197 1.87298C4.61076 1.42915 7.26553 1.53157 8.5019 1.53157C9.73826 1.53157 12.3968 1.43295 13.5118 1.87298C14.2552 2.16886 14.8278 2.74166 15.1274 3.48895C15.5712 4.608 15.4688 7.26336 15.4688 8.5C15.4688 9.73664 15.5712 12.3958 15.1274 13.511Z"
                                    fill="#00509d" />
                            </svg>
                            {{ $sosmed && !empty($sosmed->instagram) ? $sosmed->instagram : 'tidak ada data' }}
                        </p>
                        <p class="flex items-center gap-2 flex-wrap">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7857 0H1.21049C0.542634 0 0 0.550223 0 1.22567V15.7743C0 16.4498 0.542634 17 1.21049 17H15.7857C16.4536 17 17 16.4498 17 15.7743V1.22567C17 0.550223 16.4536 0 15.7857 0ZM5.13795 14.5714H2.6183V6.45848H5.14174V14.5714H5.13795ZM3.87812 5.35045C3.06987 5.35045 2.41719 4.69397 2.41719 3.88951C2.41719 3.08504 3.06987 2.42857 3.87812 2.42857C4.68259 2.42857 5.33906 3.08504 5.33906 3.88951C5.33906 4.69777 4.68638 5.35045 3.87812 5.35045ZM14.5828 14.5714H12.0632V10.625C12.0632 9.68393 12.0442 8.47344 10.754 8.47344C9.44107 8.47344 9.23996 9.49799 9.23996 10.5567V14.5714H6.72031V6.45848H9.1375V7.56652H9.17165C9.50937 6.92902 10.3328 6.25737 11.5585 6.25737C14.1085 6.25737 14.5828 7.93839 14.5828 10.1241V14.5714Z"
                                    fill="#00509d" />
                            </svg>
                            {{ $sosmed && !empty($sosmed->linkedin) ? $sosmed->linkedin : 'tidak ada data' }}
                        </p>
                    </div>
                </div>


                <!-- Body CV: 2 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                    <!-- Kolom Kiri -->
                    <div class="space-y-8 break-words">
                        <!-- Tentang Saya -->
                        <section>
                            <div class="font-bold text-[#00509d] text-lg mb-2">TENTANG SAYA</div>
                            <div class="w-10 h-1 bg-[#00509d] mb-4"></div>
                            <p class="text-sm break-words">{{ optional($data)->deskripsi_diri ?? '-' }}</p>
                        </section>

                        <!-- Keahlian & Kompetensi -->
                        <section>
                            <div class="font-bold text-[#00509d] text-lg mb-2">KEAHLIAN & KOMPETENSI</div>
                            <div class="w-10 h-1 bg-[#00509d] mb-4"></div>
                            <table class="w-full text-sm">
                                @forelse ($data->skill as $skill)
                                    <tr class="flex justify-between">
                                        <td class="font-bold py-1 break-words">﹒{{ $skill->skill }} -
                                            {{ $skill->experience_level }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center italic py-2">Tidak ada data keahlian.</td>
                                    </tr>
                                @endforelse
                            </table>
                        </section>

                        <!-- Organisasi -->
                        <section>
                            <div class="font-bold text-[#00509d] text-lg mb-2">ORGANISASI</div>
                            <div class="w-10 h-1 bg-[#00509d] mb-4"></div>
                            @forelse ($data->pengalaman_organisasi as $org)
                                <p class="space-y-4">
                                    <b class="text-sm">Jabatan – {{ $org->jabatan }}</b>
                                    <b>({{ $org->tahun_awal }}–{{ $org->tahun_akhir }})</b><br>
                                    <b class="text-sm">{{ $org->nama_organisasi }}</b>
                                <h4 class="text-sm mt-1 break-words">{{ $org->deskripsi }}</h4>
                                </p>
                            @empty
                                <p class="text-sm text-gray-600">Tidak ada pengalaman organisasi.</p>
                            @endforelse
                        </section>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-8 break-words">
                        <!-- Pengalaman Kerja -->
                        <section>
                            <div class="font-bold text-[#00509d] text-lg mb-2">PENGALAMAN KERJA</div>
                            <div class="w-10 h-1 bg-[#00509d] mb-4"></div>
                            <div class="space-y-4">
                                @forelse ($data->pengalaman_kerja as $p)
                                    <p class="break-words">
                                        <b class="text-sm">Jabatan – {{ $p->jabatan_pekerjaan ?? '-' }}</b>
                                        <b>({{ $p->tahun_awal }}–{{ $p->tahun_akhir }})</b><br>
                                        <b class="text-sm">{{ $p->nama_perusahaan ?? '-' }}</b><br>
                                        {{ $p->deskripsi ?? '-' }}
                                    </p>
                                @empty
                                    <p class="text-sm text-gray-600">Tidak ada pengalaman kerja.</p>
                                @endforelse
                            </div>
                        </section>

                        <!-- Pendidikan -->
                        <section>
                            <div class="font-bold text-[#00509d] text-lg mb-2">LATAR BELAKANG PENDIDIKAN</div>
                            <div class="w-10 h-1 bg-[#00509d] mb-4"></div>
                            <div class="space-y-4">
                                @forelse ($data->riwayat_pendidikan as $r)
                                    <p class="break-words">
                                        <b class="text-sm">{{ $r->pendidikan ?? '-' }}</b>
                                        <b>({{ $r->tahun_awal }}–{{ $r->tahun_akhir }})</b><br>
                                        <b class="text-sm">{{ $r->jurusan ?? '-' }}</b>
                                    </p>
                                @empty
                                    <p class="text-sm text-gray-600">Tidak ada riwayat pendidikan.</p>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col items-center justify-center mt-10 text-sm font-semibold text-gray-800">
                    <img src="{{ $logoBase64 }}" alt="Logo Areakerja" class="w-20 h-auto mb-1">
                    Copyright &copy; AREAKERJA.com
                </div>

            </main>
        </div>
    </div>

    {{-- </div> --}}
