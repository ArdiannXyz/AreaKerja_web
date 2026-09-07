@extends('layouts.index')
@section('content')

    <div class="bg-slate-50 min-h-screen text-slate-800 pt-24 sm:pt-28 md:pt-32 pb-20"
        x-data="lamaranKerjaHandler()"
        x-init="initHighlight()">

        {{-- Top Title Header Container (Rounded) --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 mb-8">
            <div class="bg-white border border-slate-200/80 rounded-2xl md:rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-bold text-[#00509d] text-xl md:text-2xl">
                        Lamaran Kerja Saya
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Pantau status, jadwal, dan keputusan dari semua lamaran pekerjaan yang Anda ajukan.
                    </p>
                </div>
                @auth
                    <a href="{{ route('beranda') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#00509d] hover:text-[#003d7a] bg-sky-50 hover:bg-sky-100 px-4 py-2.5 rounded-xl transition shadow-xs shrink-0">
                        <i class="ph ph-plus-circle text-base"></i>
                        <span>Cari Lowongan Baru</span>
                    </a>
                @endauth
            </div>
        </div>

        @guest
            {{-- TAMPILAN BELUM LOGIN --}}
            <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                <div class="w-24 h-24 mb-6 flex items-center justify-center rounded-3xl bg-[#00509d]/10 text-[#00509d]">
                    <svg class="w-14 h-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>

                <h2 class="text-slate-800 font-bold text-lg md:text-xl mb-2">Lacak Status Lamaran Anda</h2>
                <p class="text-slate-500 text-sm leading-relaxed mb-8 max-w-xs">
                    Masuk ke akun AreaKerja Anda untuk melihat riwayat dan menerima panggilan kerja.
                </p>

                <div class="flex items-center justify-center gap-4 w-full max-w-xs">
                    <a href="{{ route('login') }}"
                        class="flex-1 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-md transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex-1 border-2 border-[#00509d] text-[#00509d] hover:bg-[#00509d] hover:text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm transition">
                        Daftar
                    </a>
                </div>
            </div>
        @else
            {{-- TAMPILAN SUDAH LOGIN --}}
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                @if (isset($lamaranList) && $lamaranList->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch mb-10">
                        @foreach ($lamaranList as $item)
                            @php
                                $lowongan = $item->lowongan_perusahaan;
                                $perusahaan = $lowongan?->perusahaan;
                                $isDiterima = $item->status === 'diterima';
                                $isPending = $item->status === 'pending';
                                $isDitolak = $item->status === 'ditolak';
                                $respon = $item->respon_pelamar ?? 'menunggu_konfirmasi';

                                // Kontak Perusahaan WA
                                $noHp = $perusahaan->whatsapp ?? $perusahaan->telepon_perusahaan ?? '';
                                $cleanNoHp = preg_replace('/[^0-9]/', '', $noHp);
                                if (str_starts_with($cleanNoHp, '0')) {
                                    $cleanNoHp = '62' . substr($cleanNoHp, 1);
                                }
                                $waText = urlencode("Halo {$perusahaan?->nama_perusahaan}, saya " . (Auth::user()->pelamar->nama_pelamar ?? 'Pelamar') . " ingin menanyakan terkait proses penerimaan kerja untuk posisi {$lowongan?->nama}.");
                                $waUrl = $cleanNoHp ? "https://wa.me/{$cleanNoHp}?text={$waText}" : null;
                            @endphp

                            <div class="bg-white border {{ $isDiterima ? 'border-emerald-300 ring-2 ring-emerald-100' : 'border-slate-200' }} rounded-2xl p-5 md:p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between relative group">
                                
                                <div>
                                    {{-- Status & Tanggal Row --}}
                                    <div class="flex items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                                        <span class="text-xs text-slate-400 font-medium flex items-center gap-1.5">
                                            <i class="ph ph-calendar-blank text-sm"></i>
                                            {{ $item->created_at ? $item->created_at->format('d M Y') : 'Baru saja' }}
                                        </span>

                                        {{-- Badge Status --}}
                                        <div>
                                            @if ($isDiterima)
                                                @if ($respon === 'bersedia')
                                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full shadow-xs">
                                                        <i class="ph-fill ph-check-circle text-emerald-600 text-sm"></i>
                                                        Resmi Bekerja
                                                    </span>
                                                @elseif ($respon === 'menolak')
                                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full shadow-xs">
                                                        <i class="ph-fill ph-x-circle text-slate-400 text-sm"></i>
                                                        Tawaran Ditolak
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm animate-pulse">
                                                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                                                        Diterima / Offering
                                                    </span>
                                                @endif
                                            @elseif ($isPending)
                                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-3 py-1 rounded-full shadow-xs">
                                                    <i class="ph-fill ph-clock text-amber-500 text-sm"></i>
                                                    Sedang Ditinjau
                                                </span>
                                            @elseif ($isDitolak)
                                                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold px-3 py-1 rounded-full shadow-xs">
                                                    <i class="ph-fill ph-x-circle text-rose-500 text-sm"></i>
                                                    Belum Sesuai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Job Title & Company Info --}}
                                    <div class="flex items-start gap-3.5 mb-3.5">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-slate-100 bg-slate-50 flex items-center justify-center shadow-xs">
                                            @if (!empty($perusahaan?->img_profile))
                                                <img src="{{ asset('storage/' . $perusahaan->img_profile) }}" alt="Logo" class="w-full h-full object-contain">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($perusahaan?->nama_perusahaan ?? 'Perusahaan') }}&background=00509d&color=fff&size=80"
                                                    alt="Logo" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ $lowongan ? route('detail.lowongan.non.user', ['perusahaan' => $perusahaan?->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) : '#' }}"
                                                class="font-bold text-slate-800 hover:text-[#00509d] text-base leading-snug line-clamp-1 transition">
                                                {{ $lowongan?->nama ?? 'Posisi Tidak Ditemukan' }}
                                            </a>
                                            <p class="text-xs font-semibold text-slate-600 truncate mt-0.5">
                                                {{ $perusahaan?->nama_perusahaan ?? 'Perusahaan' }}
                                            </p>
                                            <p class="text-[11px] text-slate-400 truncate flex items-center gap-1 mt-0.5">
                                                <i class="ph ph-map-pin text-slate-400"></i>
                                                {{ $lowongan?->alamat ?? $perusahaan?->alamat ?? 'Lokasi tidak tertera' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Gaji / Info Banner --}}
                                    @if ($lowongan && $lowongan->gaji_awal)
                                        <div class="mb-3.5">
                                            <span class="inline-block bg-[#00509d]/10 text-[#00509d] font-bold px-3 py-1 rounded-lg text-xs">
                                                Rp. {{ number_format($lowongan->gaji_awal, 0, ',', '.') }} per bulan
                                            </span>
                                        </div>
                                    @endif

                                    {{-- BANNER KHUSUS PENERIMAAN / OFFERING --}}
                                    @if ($isDiterima)
                                        <div class="p-4 rounded-xl {{ $respon === 'bersedia' ? 'bg-emerald-50 border border-emerald-200' : ($respon === 'menolak' ? 'bg-slate-50 border border-slate-200' : 'bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200') }} mb-4">
                                            <div class="flex items-start gap-2.5">
                                                <div class="w-8 h-8 rounded-lg {{ $respon === 'bersedia' ? 'bg-emerald-600 text-white' : ($respon === 'menolak' ? 'bg-slate-400 text-white' : 'bg-emerald-600 text-white') }} flex items-center justify-center shrink-0 shadow-xs">
                                                    @if ($respon === 'bersedia')
                                                        <i class="ph-fill ph-check text-base"></i>
                                                    @elseif ($respon === 'menolak')
                                                        <i class="ph-fill ph-x text-base"></i>
                                                    @else
                                                        <i class="ph-fill ph-confetti text-base"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="text-xs font-bold text-slate-800">
                                                        @if ($respon === 'bersedia')
                                                            Anda Telah Resmi Bergabung
                                                        @elseif ($respon === 'menolak')
                                                            Tawaran Telah Ditolak
                                                        @else
                                                            Selamat! Perusahaan Mengirimkan Tawaran
                                                        @endif
                                                    </h4>
                                                    <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                                                        @if ($respon === 'bersedia')
                                                            Status profil kandidat Anda telah aktif sebagai <b>Bekerja</b>.
                                                        @elseif ($respon === 'menolak')
                                                            Anda memilih untuk tidak melanjutkan tawaran ini.
                                                        @else
                                                            Silakan lihat rincian jadwal, lokasi, dan tentukan keputusan Anda.
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Quick Summary of Details --}}
                                            @if ($item->jadwal || $item->lokasi)
                                                <div class="mt-3 pt-2.5 border-t border-emerald-100/80 grid grid-cols-1 gap-1.5 text-[11px] text-slate-600">
                                                    @if ($item->jadwal)
                                                        <div class="flex items-center gap-1.5 truncate">
                                                            <i class="ph ph-calendar text-emerald-600"></i>
                                                            <span class="font-medium text-slate-700 truncate">Jadwal: {{ $item->jadwal }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($item->lokasi)
                                                        <div class="flex items-center gap-1.5 truncate">
                                                            <i class="ph ph-map-pin text-emerald-600"></i>
                                                            <span class="font-medium text-slate-700 truncate">Lokasi: {{ $item->lokasi }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 mt-2">
                                    <span class="text-[11px] text-slate-400">
                                        {{ $lowongan && $lowongan->published_at ? 'Aktif ' . $lowongan->published_at->diffForHumans() : 'Lowongan AreaKerja' }}
                                    </span>

                                    <div class="flex items-center gap-2">
                                        @if ($isDiterima)
                                            <button type="button"
                                                @click="openModal({{ json_encode([
                                                    'id' => $item->id,
                                                    'posisi' => $lowongan?->nama ?? 'Posisi',
                                                    'perusahaan' => $perusahaan?->nama_perusahaan ?? 'Perusahaan',
                                                    'logo' => !empty($perusahaan?->img_profile) ? asset('storage/' . $perusahaan->img_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($perusahaan?->nama_perusahaan ?? 'P') . '&background=00509d&color=fff',
                                                    'jadwal' => $item->jadwal,
                                                    'lokasi' => $item->lokasi,
                                                    'catatan' => $item->catatan,
                                                    'gmaps_url' => $item->gmaps_url,
                                                    'respon' => $respon,
                                                    'wa_url' => $waUrl,
                                                    'telepon' => $perusahaan?->telepon_perusahaan ?? $perusahaan?->whatsapp,
                                                ]) }})"
                                                class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm hover:shadow transition cursor-pointer">
                                                <i class="ph ph-info text-sm"></i>
                                                <span>Lihat Detail Penerimaan</span>
                                            </button>
                                        @else
                                            <a href="{{ $lowongan ? route('detail.lowongan.non.user', ['perusahaan' => $perusahaan?->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) : '#' }}"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00509d] hover:text-[#003d7a] bg-sky-50 hover:bg-sky-100 px-3.5 py-2 rounded-xl transition">
                                                <span>Detail Lowongan</span>
                                                <i class="ph ph-arrow-right text-xs"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- KOSONG --}}
                    <div class="max-w-md mx-auto px-4 py-16 text-center flex flex-col items-center justify-center min-h-[50vh]">
                        <div class="w-20 h-20 mb-4 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <i class="ph ph-briefcase text-4xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-base mb-1">Belum Ada Lamaran Kerja</h3>
                        <p class="text-slate-500 text-xs md:text-sm mb-6">Anda belum mengajukan lamaran ke lowongan pekerjaan apapun.</p>
                        <a href="{{ route('beranda') }}"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-2.5 px-6 rounded-xl text-center text-sm shadow-md transition">
                            Cari Lowongan Sekarang
                        </a>
                    </div>
                @endif
            </div>

            {{-- ========================================================================= --}}
            {{-- MODAL DETAIL PENERIMAAN KERJA / OFFERING --}}
            {{-- ========================================================================= --}}
            <div x-show="modalOpen"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">

                <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all"
                    @click.outside="modalOpen = false"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95">

                    {{-- Modal Header --}}
                    <div class="px-6 py-5 text-white relative flex items-center justify-between gap-3 shadow-sm" style="background: linear-gradient(135deg, #00509d 0%, #003d7a 100%);">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-white p-1 shrink-0 flex items-center justify-center shadow-md">
                                <img :src="activeData.logo" alt="Logo" class="w-full h-full object-contain rounded-xl">
                            </div>
                            <div class="min-w-0 pr-2">
                                <span class="inline-flex items-center gap-1 bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider mb-1">
                                    <i class="ph-bold ph-seal-check text-xs"></i> Tawaran Diterima
                                </span>
                                <h3 class="font-bold text-base md:text-lg leading-snug truncate text-white drop-shadow-xs" x-text="activeData.posisi || 'Posisi Lowongan'"></h3>
                                <p class="text-xs text-blue-100 truncate mt-0.5 font-medium" x-text="activeData.perusahaan || 'Perusahaan'"></p>
                            </div>
                        </div>
                        <button type="button" @click="modalOpen = false"
                            class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition cursor-pointer shrink-0"
                            title="Tutup">
                            <i class="ph ph-x text-base font-bold"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 max-h-[70vh] overflow-y-auto space-y-4">

                        {{-- Status Alert Box --}}
                        <template x-if="activeData.respon === 'menunggu_konfirmasi'">
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="ph-fill ph-info text-lg"></i>
                                </div>
                                <div class="text-xs flex-1">
                                    <p class="font-bold text-amber-900 text-sm">Menunggu Konfirmasi Anda</p>
                                    <p class="text-amber-800 text-xs mt-1 leading-relaxed">
                                        Perusahaan telah menerima lamaran Anda. Silakan tinjau jadwal serta detail di bawah dan tentukan keputusan Anda.
                                    </p>
                                </div>
                            </div>
                        </template>

                        <template x-if="activeData.respon === 'bersedia'">
                            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="ph-fill ph-check-circle text-lg"></i>
                                </div>
                                <div class="text-xs flex-1">
                                    <p class="font-bold text-emerald-900 text-sm">Tawaran Diterima (Resmi Bekerja)</p>
                                    <p class="text-emerald-800 text-xs mt-1 leading-relaxed">
                                        Anda telah mengonfirmasi kesediaan untuk bekerja. Status profil kandidat Anda kini aktif sebagai <b>Bekerja</b>.
                                    </p>
                                </div>
                            </div>
                        </template>

                        <template x-if="activeData.respon === 'menolak'">
                            <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 text-slate-700 flex items-start gap-3.5">
                                <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="ph-fill ph-x-circle text-lg"></i>
                                </div>
                                <div class="text-xs flex-1">
                                    <p class="font-bold text-slate-900 text-sm">Tawaran Telah Ditolak</p>
                                    <p class="text-slate-600 text-xs mt-1 leading-relaxed">
                                        Anda telah menolak tawaran pekerjaan ini.
                                    </p>
                                </div>
                            </div>
                        </template>

                        {{-- Jadwal & Waktu --}}
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/70">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700 mb-2">
                                <div class="w-6 h-6 rounded-lg bg-blue-100 text-[#00509d] flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-calendar text-sm"></i>
                                </div>
                                <span>Jadwal (Interview / Masuk Kerja)</span>
                            </div>
                            <p class="text-xs font-semibold text-slate-800 pl-8" x-text="activeData.jadwal || 'Belum ditentukan oleh perusahaan'"></p>
                        </div>

                        {{-- Lokasi & Google Maps --}}
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/70">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                    <div class="w-6 h-6 rounded-lg bg-blue-100 text-[#00509d] flex items-center justify-center shrink-0">
                                        <i class="ph-bold ph-map-pin text-sm"></i>
                                    </div>
                                    <span>Lokasi Kantor / Pertemuan</span>
                                </div>
                                <template x-if="activeData.gmaps_url">
                                    <a :href="activeData.gmaps_url" target="_blank"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1 rounded-lg border border-emerald-200 transition shadow-xs">
                                        <i class="ph ph-arrow-square-out text-xs"></i>
                                        <span>Buka Google Maps</span>
                                    </a>
                                </template>
                            </div>
                            <p class="text-xs text-slate-700 pl-8 leading-relaxed" x-text="activeData.lokasi || 'Lokasi kantor utama'"></p>
                        </div>

                        {{-- Catatan Khusus --}}
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/70">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700 mb-2">
                                <div class="w-6 h-6 rounded-lg bg-blue-100 text-[#00509d] flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-note-pencil text-sm"></i>
                                </div>
                                <span>Catatan dari Perusahaan</span>
                            </div>
                            <p class="text-xs text-slate-600 pl-8 leading-relaxed whitespace-pre-line" x-text="activeData.catatan || 'Tidak ada catatan khusus.'"></p>
                        </div>

                        {{-- Kontak HRD / Perusahaan --}}
                        <template x-if="activeData.wa_url">
                            <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">Hubungi HRD Perusahaan</p>
                                        <p class="text-xs text-slate-500 font-mono mt-0.5" x-text="activeData.telepon || 'WhatsApp HRD'"></p>
                                    </div>
                                </div>
                                <a :href="activeData.wa_url" target="_blank"
                                    class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-xs hover:shadow transition shrink-0">
                                    <i class="ph ph-chat-circle-dots text-sm"></i>
                                    <span>Chat WA</span>
                                </a>
                            </div>
                        </template>

                    </div>

                    {{-- Modal Action Footer --}}
                    <div class="p-4 sm:p-5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-2.5">
                        <button type="button" @click="modalOpen = false"
                            class="w-full sm:w-auto px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                            Tutup
                        </button>

                        {{-- Tampilkan tombol Terima / Tolak HANYA jika belum merespon --}}
                        <template x-if="activeData.respon === 'menunggu_konfirmasi'">
                            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                                <button type="button"
                                    @click="submitRespon(activeData.id, 'menolak')"
                                    :disabled="isSubmitting"
                                    class="flex-1 sm:flex-none px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-xl text-xs transition disabled:opacity-50 cursor-pointer">
                                    Tolak Tawaran
                                </button>
                                <button type="button"
                                    @click="submitRespon(activeData.id, 'bersedia')"
                                    :disabled="isSubmitting"
                                    class="flex-1 sm:flex-none px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md hover:shadow-lg transition disabled:opacity-50 flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="ph-fill ph-check-circle text-sm"></i>
                                    <span>Terima Tawaran & Bekerja</span>
                                </button>
                            </div>
                        </template>
                    </div>

                </div>
            </div>

        @endguest

    </div>

    @include('layouts.footer')

    <script>
        function lamaranKerjaHandler() {
            return {
                modalOpen: false,
                isSubmitting: false,
                activeData: {
                    id: null,
                    posisi: '',
                    perusahaan: '',
                    logo: '',
                    jadwal: '',
                    lokasi: '',
                    catatan: '',
                    gmaps_url: '',
                    respon: 'menunggu_konfirmasi',
                    wa_url: null,
                    telepon: ''
                },
                openModal(data) {
                    this.activeData = data;
                    this.modalOpen = true;
                },
                initHighlight() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const highlightId = urlParams.get('highlight') || urlParams.get('detail');
                    if (highlightId) {
                        @if (isset($lamaranList) && $lamaranList->count() > 0)
                            const list = @json($lamaranList);
                            const found = list.find(item => item.id == highlightId);
                            if (found && found.status === 'diterima') {
                                const lowongan = found.lowongan_perusahaan || {};
                                const perusahaan = lowongan.perusahaan || {};
                                
                                let noHp = perusahaan.whatsapp || perusahaan.telepon_perusahaan || '';
                                let cleanNoHp = noHp.replace(/[^0-9]/g, '');
                                if (cleanNoHp.startsWith('0')) cleanNoHp = '62' + cleanNoHp.substring(1);
                                let waUrl = cleanNoHp ? `https://wa.me/${cleanNoHp}` : null;

                                this.openModal({
                                    id: found.id,
                                    posisi: lowongan.nama || 'Posisi',
                                    perusahaan: perusahaan.nama_perusahaan || 'Perusahaan',
                                    logo: perusahaan.img_profile ? `/storage/${perusahaan.img_profile}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(perusahaan.nama_perusahaan || 'P')}&background=00509d&color=fff`,
                                    jadwal: found.jadwal,
                                    lokasi: found.lokasi,
                                    catatan: found.catatan,
                                    gmaps_url: found.gmaps_url,
                                    respon: found.respon_pelamar || 'menunggu_konfirmasi',
                                    wa_url: waUrl,
                                    telepon: perusahaan.telepon_perusahaan || perusahaan.whatsapp
                                });
                            }
                        @endif
                    }
                },
                submitRespon(id, responType) {
                    if (responType === 'bersedia') {
                        Swal.fire({
                            title: 'Konfirmasi Penerimaan Kerja',
                            text: 'Apakah Anda yakin ingin menerima tawaran ini? Status profil kandidat Anda akan diperbarui menjadi "Bekerja".',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#059669',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Saya Bersedia Bekerja',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.sendResponRequest(id, 'bersedia', '');
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Tolak Tawaran Kerja',
                            text: 'Berikan alasan singkat penolakan (opsional):',
                            input: 'text',
                            inputPlaceholder: 'Contoh: Sudah mendapatkan tawaran lain',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#e11d48',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Tolak Tawaran',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.sendResponRequest(id, 'menolak', result.value || '');
                            }
                        });
                    }
                },
                sendResponRequest(id, responType, alasan) {
                    this.isSubmitting = true;
                    fetch(`/pelamar/lamaran-kerja/${id}/respon`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            respon: responType,
                            alasan: alasan
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isSubmitting = false;
                        if (data.success) {
                            this.modalOpen = false;
                            Swal.fire({
                                icon: 'success',
                                title: responType === 'bersedia' ? 'Selamat!' : 'Tawaran Ditolak',
                                text: data.message,
                                confirmButtonColor: '#00509d'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan sistem.',
                                confirmButtonColor: '#00509d'
                            });
                        }
                    })
                    .catch(err => {
                        this.isSubmitting = false;
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Jaringan',
                            text: 'Gagal menghubungi server. Silakan coba lagi.',
                            confirmButtonColor: '#00509d'
                        });
                    });
                }
            }
        }
    </script>
@endsection

