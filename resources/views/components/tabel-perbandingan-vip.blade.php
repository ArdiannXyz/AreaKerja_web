@props([
    'isVip' => false,
    'showCta' => true,
    'ctaTarget' => 'modal', // 'modal' or 'link'
])

<div class="w-full my-8">
    <div class="text-center max-w-2xl mx-auto mb-8 space-y-2">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50 border border-blue-100 text-[#00509d] text-xs font-black tracking-wide uppercase">
            <i class="ph-fill ph-scales text-amber-500 text-sm"></i>
            <span>Transparansi Fitur Rekrutmen</span>
        </div>
        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Perbandingan Akun Biasa vs <span class="text-[#00509d]">VIP Employer</span>
        </h3>
        <p class="text-xs sm:text-sm text-slate-600 font-medium">
            Pasang lowongan gratis atau buka potensi rekrutmen tanpa batas dengan keanggotaan VIP AreaKerja.
        </p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
        {{-- Header Summary Cards (Mobile & Desktop) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200 border-b border-slate-200 bg-slate-50/50">
            {{-- Free Tier Card --}}
            <div class="p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-slate-200 text-slate-700">
                            Akun Biasa (Reguler)
                        </span>
                        <span class="text-xs font-bold text-slate-500">Tier Gratis</span>
                    </div>
                    <div class="flex items-baseline gap-1 my-2">
                        <span class="text-3xl sm:text-4xl font-black text-slate-900">Gratis</span>
                        <span class="text-xs font-bold text-slate-500">/ selamanya</span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Cocok untuk UMKM atau bisnis yang baru mulai membuka lowongan sesekali dengan kebutuhan kandidat dasar.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-200/70">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600">
                        <i class="ph ph-check-circle text-emerald-600 font-bold"></i> Siap pakai langsung tanpa biaya awal
                    </span>
                </div>
            </div>

            {{-- VIP Tier Card --}}
            <div class="p-6 sm:p-8 flex flex-col justify-between bg-gradient-to-b from-blue-50/50 via-white to-blue-50/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 transform translate-x-8 -translate-y-8 w-32 h-32 bg-amber-200/30 rounded-full blur-2xl pointer-events-none"></div>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-xs flex items-center gap-1">
                            <i class="ph-fill ph-crown text-amber-200"></i> VIP Employer
                        </span>
                        <span class="text-[11px] font-extrabold px-2 py-0.5 rounded-md bg-blue-100 text-[#00509d]">Paling Direkomendasikan</span>
                    </div>
                    <div class="flex items-baseline gap-1 my-2">
                        <span class="text-3xl sm:text-4xl font-black text-[#00509d]">VIP Akses Penuh</span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Solusi rekrutmen prioritas seperti Glints VIP: lowongan teratas, tanpa batas pelamar harian, dan masa aktif maksimal.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-blue-200/50 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-[#00509d]">
                        <i class="ph-fill ph-sparkle text-amber-500 font-bold"></i> Prioritas Top 1 di Mesin Pencarian
                    </span>
                    @if ($isVip)
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[11px] font-black rounded-lg">Status Anda: VIP</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detailed Table Rows --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-700 font-black text-[11px] sm:text-xs uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-6 w-5/12">Fitur & Kapasitas</th>
                        <th class="py-3.5 px-4 sm:px-6 w-3/12 text-slate-600">Akun Biasa (Gratis)</th>
                        <th class="py-3.5 px-4 sm:px-6 w-4/12 bg-blue-50/70 text-[#00509d] border-l border-blue-100">
                            <div class="flex items-center gap-1.5">
                                <i class="ph-fill ph-crown text-amber-500"></i>
                                <span>VIP Employer</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    {{-- Row 1: Biaya Pasang --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Biaya Pasang Lowongan</div>
                            <div class="text-[11px] text-slate-500">Biaya dasar untuk menayangkan lowongan kerja baru</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-bold text-slate-800">
                            Gratis (0 Koin)
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-black text-emerald-700 bg-blue-50/30 border-l border-blue-100">
                            Gratis (Termasuk Paket VIP)
                        </td>
                    </tr>

                    {{-- Row 2: Kuota Lowongan Aktif --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Kuota Lowongan Aktif Bersamaan</div>
                            <div class="text-[11px] text-slate-500">Jumlah lowongan yang dapat ditayangkan aktif secara bersamaan</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-bold text-slate-700">
                            <span class="inline-flex items-center gap-1">
                                <i class="ph ph-check text-slate-500 font-bold"></i> Maksimal <strong>2 Lowongan</strong>
                            </span>
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-extrabold text-[#00509d] bg-blue-50/30 border-l border-blue-100">
                            <span class="inline-flex items-center gap-1.5 bg-blue-100/70 text-[#00509d] px-2.5 py-1 rounded-lg">
                                <i class="ph-fill ph-sparkle text-amber-500"></i> Hingga <strong>20 Lowongan Aktif</strong>
                            </span>
                        </td>
                    </tr>

                    {{-- Row 3: Masa Aktif Lowongan --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Masa Berlaku Penayangan</div>
                            <div class="text-[11px] text-slate-500">Durasi tayang otomatis sebelum lowongan kedaluwarsa (expired)</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-bold text-slate-700">
                            <strong>7 Hari (1 Minggu)</strong>
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-extrabold text-[#00509d] bg-blue-50/30 border-l border-blue-100">
                            <strong>30 Hari (1 Bulan Penuh)</strong>
                        </td>
                    </tr>

                    {{-- Row 4: Kuota Pelamar Harian --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Batas Pelamar Masuk per Lowongan</div>
                            <div class="text-[11px] text-slate-500">Jumlah kandidat yang diizinkan melamar dalam satu hari</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-bold text-slate-700">Maks. 5 Pelamar / Hari</div>
                            <div class="text-[11px] text-amber-700 mt-0.5 flex items-center gap-1">
                                <i class="ph ph-lock-key"></i> Terkunci jika kuota hari itu penuh (dibuka besok)
                            </div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 bg-blue-50/30 border-l border-blue-100">
                            <div class="font-black text-emerald-700 flex items-center gap-1.5">
                                <i class="ph-fill ph-infinity text-base text-emerald-600"></i>
                                <span>Tanpa Batas (Unlimited Pelamar)</span>
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Semua kandidat potensial dapat langsung melamar tanpa terkunci</div>
                        </td>
                    </tr>

                    {{-- Row 5: Job Booster & Prioritas Pencarian --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Prioritas Pencarian (Job Booster)</div>
                            <div class="text-[11px] text-slate-500">Peringkat posisi lowongan pada beranda dan filter pencarian pelamar</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 text-slate-500 font-medium">
                            Urutan Reguler / Standar
                        </td>
                        <td class="py-4 px-4 sm:px-6 bg-blue-50/30 border-l border-blue-100">
                            <div class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-900 px-2.5 py-1 rounded-lg text-xs font-black">
                                <i class="ph-fill ph-rocket-launch text-amber-500"></i>
                                <span>Selalu Top / Teratas di Pencarian (Otomatis)</span>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 6: Badge Eksklusif --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Badge Verifikasi Eksklusif</div>
                            <div class="text-[11px] text-slate-500">Tanda pengenal kredibilitas di profil perusahaan & kartu lowongan</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 text-slate-400 font-semibold">
                            <span class="inline-flex items-center gap-1">
                                <i class="ph ph-minus"></i> Tidak Ada
                            </span>
                        </td>
                        <td class="py-4 px-4 sm:px-6 bg-blue-50/30 border-l border-blue-100">
                            <span class="inline-flex items-center gap-1 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black text-xs px-2.5 py-1 rounded-md shadow-2xs">
                                <i class="ph-fill ph-crown text-amber-200"></i> ⭐ VIP Employer
                            </span>
                        </td>
                    </tr>

                    {{-- Row 7: Akses Database Kandidat --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Akses Database Talenta Terverifikasi</div>
                            <div class="text-[11px] text-slate-500">Akses pencarian resume kandidat siap kerja AreaKerja</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 text-slate-500 font-medium">
                            Akses Terbatas
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-extrabold text-slate-900 bg-blue-50/30 border-l border-blue-100">
                            <span class="inline-flex items-center gap-1.5 text-emerald-700">
                                <i class="ph-fill ph-check-circle text-emerald-600"></i> Akses Database Penuh & Portofolio
                            </span>
                        </td>
                    </tr>

                    {{-- Row 8: Event Rekrutmen & Webinar --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Event Rekrutmen & Job Fair</div>
                            <div class="text-[11px] text-slate-500">Undangan khusus ke dalam agenda bursa kerja AreaKerja</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 text-slate-400 font-semibold">
                            <span class="inline-flex items-center gap-1">
                                <i class="ph ph-minus"></i> Tidak Termasuk
                            </span>
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-extrabold text-slate-900 bg-blue-50/30 border-l border-blue-100">
                            <span class="inline-flex items-center gap-1.5 text-emerald-700">
                                <i class="ph-fill ph-check-circle text-emerald-600"></i> Prioritas Undangan Event Eksklusif
                            </span>
                        </td>
                    </tr>

                    {{-- Row 9: Konsultasi Rekrutmen HR --}}
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 sm:px-6">
                            <div class="font-extrabold text-slate-900">Dukungan Konsultasi HR</div>
                            <div class="text-[11px] text-slate-500">Pendampingan pemilihan kriteria dan filter kandidat</div>
                        </td>
                        <td class="py-4 px-4 sm:px-6 text-slate-500 font-medium">
                            Dukungan Standar
                        </td>
                        <td class="py-4 px-4 sm:px-6 font-extrabold text-slate-900 bg-blue-50/30 border-l border-blue-100">
                            <span class="inline-flex items-center gap-1.5 text-emerald-700">
                                <i class="ph-fill ph-check-circle text-emerald-600"></i> Konsultasi Intensif HR Specialist
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Footer Action Bar --}}
        @if ($showCta)
            <div class="p-6 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <p class="text-sm font-black text-slate-900">Ingin percepat perekrutan dengan fasilitas VIP?</p>
                    <p class="text-xs text-slate-500">Dapatkan keistimewaan 20 lowongan aktif, unlimited pelamar, dan top ranking pencarian.</p>
                </div>
                <div class="flex items-center gap-3">
                    @if ($isVip)
                        <a href="{{ route('lowongan.create.form') }}"
                            class="inline-flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2.5 rounded-xl shadow-xs text-xs font-black transition">
                            <i class="ph-bold ph-plus-circle"></i> Pasang Lowongan VIP
                        </a>
                    @else
                        @if ($ctaTarget === 'modal')
                            <button onclick="openModal()" type="button"
                                class="inline-flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2.5 rounded-xl shadow-xs text-xs font-black transition hover:scale-102 active:scale-98">
                                <i class="ph-fill ph-crown text-amber-300"></i> Upgrade ke VIP Sekarang
                            </button>
                        @else
                            @php
                                $vipLink = Auth::check() && Auth::user()->role === 'perusahaan'
                                    ? route('perusahaan.berlangganan')
                                    : (Auth::check() ? route('perusahaan.berlangganan') : route('login'));
                            @endphp
                            <a href="{{ $vipLink }}"
                                class="inline-flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2.5 rounded-xl shadow-xs text-xs font-black transition hover:scale-102 active:scale-98">
                                <i class="ph-fill ph-crown text-amber-300"></i> Upgrade ke VIP Sekarang
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

