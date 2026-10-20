@php
    $notifPartial = ($isSuper ?? false) ? 'super_admin.components.notif_button' : 'admin.components.notif_button';
    $isPelamarReport = $report->isPelamar();

    // Context Pelamar
    $pelamar = $report->pelamar;
    $pelamarUser = $pelamar?->user ?? $report->reportedUser;

    // Context Lowongan & Perusahaan
    $lowongan = $report->lowongan;
    $perusahaan = $lowongan?->perusahaan;
    $owner = $perusahaan?->user;

    // Reporter Info
    $reporterUser = $report->reporter;
    $isPdf = $report->bukti_path && strtolower(pathinfo($report->bukti_path, PATHINFO_EXTENSION)) === 'pdf';
@endphp

<main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ freezeOpen: false }">
    <div class="mb-4">
        <a href="{{ route($routes['index']) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#00509d] transition">
            <i class="ph ph-arrow-left"></i> Kembali ke daftar laporan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5">
            <i class="ph ph-check-circle text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-4">
            @if ($isPelamarReport)
                <!-- SUBJEK: PELAMAR -->
                <section class="bg-white rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl font-bold">
                                <i class="ph ph-user"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Subjek Pelamar yang Dilaporkan</p>
                                <h1 class="text-xl font-bold text-slate-900 mt-0.5">{{ $pelamar->nama_pelamar ?? 'Data Pelamar Terhapus' }}</h1>
                                <p class="text-xs text-slate-500">
                                    {{ $pelamarUser->email ?? '-' }} · {{ $pelamar->telepon_pelamar ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1.5">
                            @if ($pelamarUser?->status == 1)
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">Akun Dibekukan</span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">Akun Aktif</span>
                            @endif

                            @if ($pelamar?->kategori)
                                <span class="inline-flex px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">
                                    {{ ucfirst($pelamar->kategori) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($lowongan)
                        <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs text-slate-600">
                            <div>
                                <span class="text-slate-400">Terkait Lowongan:</span>
                                <strong class="text-slate-800 ml-1">{{ $lowongan->nama }}</strong>
                                <span class="text-slate-500">({{ $lowongan->perusahaan->nama_perusahaan ?? '-' }})</span>
                            </div>
                            <a href="{{ route('detail.lowongan.non.user', ['perusahaan' => $lowongan->perusahaan->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) }}"
                                target="_blank" class="font-bold text-[#00509d] hover:underline">
                                Buka Lowongan &rarr;
                            </a>
                        </div>
                    @endif
                </section>
            @else
                <!-- SUBJEK: LOWONGAN -->
                <section class="bg-white rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Subjek Lowongan yang Dilaporkan</p>
                            <h1 class="text-xl font-bold text-slate-900 mt-1">{{ $lowongan->nama ?? 'Lowongan tidak ditemukan' }}</h1>
                            <p class="text-sm text-slate-600">{{ $perusahaan->nama_perusahaan ?? '-' }}</p>
                        </div>
                        @if ($lowongan?->isDisembunyikan())
                            <span class="inline-flex px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">Disembunyikan</span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-600 mt-4 line-clamp-6">{!! strip_tags($lowongan->deskripsi ?? '-') !!}</p>
                    @if ($lowongan && $perusahaan)
                        <a href="{{ route('detail.lowongan.non.user', ['perusahaan' => $perusahaan->slug ?? 'perusahaan', 'lowongan' => $lowongan->slug ?? $lowongan->id]) }}"
                            class="inline-flex mt-4 text-sm font-semibold text-[#00509d] hover:underline" target="_blank">
                            Buka halaman lowongan &rarr;
                        </a>
                    @endif
                </section>
            @endif

            <!-- ISI LAPORAN & BUKTI -->
            <section class="bg-white rounded-2xl border border-slate-200 p-5 space-y-3">
                <h2 class="font-bold text-slate-900">Isi Laporan</h2>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-500 font-medium">Alasan Pelaporan</p>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $report->alasan_label }}</p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 font-medium mb-1">Kronologi / Penjelasan Masalah:</p>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-sm text-slate-700 whitespace-pre-line leading-relaxed">
                        {{ $report->deskripsi }}
                    </div>
                </div>

                @if ($report->bukti_path)
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1.5">Bukti Terlampir:</p>
                        @if ($isPdf)
                            <div class="p-4 bg-blue-50/50 border border-blue-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="ph ph-file-pdf text-3xl text-rose-600"></i>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">Dokumen Bukti (PDF)</p>
                                        <p class="text-[11px] text-slate-500">Klik tombol untuk mengunduh atau membuka dokumen.</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $report->bukti_path) }}" target="_blank"
                                    class="px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-2xs">
                                    Buka Dokumen PDF
                                </a>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $report->bukti_path) }}" target="_blank" class="inline-block group">
                                <img src="{{ asset('storage/' . $report->bukti_path) }}" alt="Bukti" class="max-h-72 rounded-xl border border-slate-200 group-hover:opacity-90 transition">
                                <span class="text-[11px] text-[#00509d] font-semibold mt-1 block group-hover:underline">Buka gambar ukuran penuh &rarr;</span>
                            </a>
                        @endif
                    </div>
                @endif

                <p class="text-xs text-slate-400 pt-2 border-t border-slate-100">
                    {{ $relatedCount }} total laporan untuk subjek ini @if($relatedPending) · <span class="font-bold text-amber-600">{{ $relatedPending }} masih menunggu</span> @endif
                </p>
            </section>
        </div>

        <!-- SIDEBAR INFO & AKSI -->
        <div class="space-y-4">
            <!-- PELAPOR -->
            <section class="bg-white rounded-2xl border border-slate-200 p-5 space-y-2 text-sm">
                <h2 class="font-bold text-slate-900 flex items-center justify-between">
                    <span>Pihak Pelapor</span>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $isPelamarReport ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $isPelamarReport ? 'Perusahaan' : 'Pelamar' }}
                    </span>
                </h2>
                @if ($isPelamarReport)
                    <p class="font-bold text-slate-900">{{ $reporterUser->perusahaan->nama_perusahaan ?? $reporterUser->username }}</p>
                @else
                    <p class="font-bold text-slate-900">{{ $reporterUser->pelamar->nama_pelamar ?? $reporterUser->username }}</p>
                @endif
                <p class="text-slate-500 text-xs">{{ $reporterUser->email ?? '-' }}</p>
                <p class="text-xs text-slate-400">Dilaporkan pada: {{ $report->created_at?->format('d M Y H:i') }}</p>
            </section>

            <!-- AKSI MODERASI -->
            <section class="bg-white rounded-2xl border border-slate-200 p-5 space-y-3">
                <h2 class="font-bold text-slate-900">Aksi Moderasi</h2>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                    <span class="text-slate-500">Status Saat Ini:</span>
                    <span class="font-bold text-slate-800 ml-1">{{ $report->status_label }}</span>
                    @if($report->action_taken)
                        <div class="mt-1 pt-1 border-t border-slate-200/60 text-slate-600">
                            Tindakan: <strong class="text-slate-800">{{ $report->action_label }}</strong>
                        </div>
                    @endif
                </div>

                @if ($report->status === 'pending')
                    <form method="POST" action="{{ route($routes['review'], $report) }}" class="space-y-3">
                        @csrf
                        <label class="block text-xs font-bold text-slate-700">Catatan Internal Admin (Opsional):</label>
                        <textarea name="admin_notes" rows="3" placeholder="Tuliskan alasan keputusan moderasi..." class="w-full rounded-xl border-slate-200 text-sm focus:border-[#00509d] focus:ring-[#00509d]">{{ old('admin_notes') }}</textarea>

                        <div class="grid grid-cols-1 gap-2 pt-1">
                            <button name="action" value="dismiss" class="w-full px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                                Tolak Laporan (Tidak Terbukti)
                            </button>

                            <button name="action" value="warn" class="w-full px-3 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 transition cursor-pointer">
                                {{ $isPelamarReport ? 'Kirim Peringatan ke Pelamar' : 'Kirim Peringatan ke Perusahaan' }}
                            </button>

                            @if ($isPelamarReport)
                                @if ($pelamar?->kategori === 'kandidat aktif')
                                    <button name="action" value="revoke_kandidat" class="w-full px-3 py-2.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold border border-purple-200 transition cursor-pointer">
                                        Cabut Status Kandidat Aktif
                                    </button>
                                @endif

                                <button type="button" @click="freezeOpen = true" class="w-full px-3 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-2xs cursor-pointer">
                                    Bekukan Akun Pelamar (Freeze)
                                </button>
                            @else
                                <button name="action" value="hide" class="w-full px-3 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200 transition cursor-pointer">
                                    Sembunyikan Lowongan
                                </button>
                                <button type="button" @click="freezeOpen = true" class="w-full px-3 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-2xs cursor-pointer">
                                    Bekukan Akun Perusahaan
                                </button>
                            @endif
                        </div>
                    </form>
                @elseif ($isPelamarReport && $pelamarUser?->status == 1)
                    <!-- PELAPORAN SUDAH SELESAI, TAPI AKUN PELAMAR DIBEKUKAN -> OPSI UNFREEZE -->
                    <form method="POST" action="{{ route($routes['review'], $report) }}" class="space-y-3">
                        @csrf
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700">
                            Akun pelamar saat ini masih berstatus <strong>Dibekukan</strong>.
                        </div>
                        <textarea name="admin_notes" rows="2" placeholder="Catatan pemulihan akun (opsional)" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#00509d] focus:ring-[#00509d]">{{ old('admin_notes') }}</textarea>
                        <button name="action" value="unfreeze" class="w-full px-3 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs cursor-pointer">
                            Buka Pembekuan Akun Pelamar
                        </button>
                    </form>
                @elseif (! $isPelamarReport && $lowongan?->isDisembunyikan())
                    <form method="POST" action="{{ route($routes['review'], $report) }}" class="space-y-3">
                        @csrf
                        <textarea name="admin_notes" rows="2" placeholder="Catatan pemulihan (opsional)" class="w-full rounded-xl border-slate-200 text-sm focus:border-[#00509d] focus:ring-[#00509d] mb-2">{{ old('admin_notes') }}</textarea>
                        <button name="action" value="restore" class="w-full px-3 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs cursor-pointer">
                            Pulihkan Lowongan
                        </button>
                    </form>
                @else
                    <p class="text-xs text-slate-500 italic">Laporan ini sudah ditinjau dan ditindaklanjuti.</p>
                @endif
            </section>
        </div>
    </div>

    <!-- MODAL FREEZE -->
    <div x-show="freezeOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <form method="POST" action="{{ route($routes['review'], $report) }}" class="bg-white rounded-2xl p-6 max-w-md w-full space-y-4 shadow-2xl">
            @csrf
            <input type="hidden" name="action" value="freeze">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl font-bold">
                    <i class="ph ph-lock"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">
                        {{ $isPelamarReport ? 'Bekukan Akun Pelamar' : 'Bekukan Akun Perusahaan' }}
                    </h3>
                    <p class="text-xs text-slate-500">Tindakan pembatasan akses sistem.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                {{ $isPelamarReport ? 'Akun pelamar tidak akan bisa login atau melamar pekerjaan sampai pembekuan dibuka.' : 'Lowongan ini juga akan disembunyikan. Akun perusahaan tidak bisa login sampai di-unfreeze.' }}
            </p>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Pembekuan <span class="text-rose-500">*</span></label>
                <textarea name="alasan_freeze" required rows="3" class="w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Jelaskan alasan akun dibekukan...">{{ old('alasan_freeze') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Internal (Opsional)</label>
                <textarea name="admin_notes" rows="2" class="w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Catatan internal admin...">{{ old('admin_notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t">
                <button type="button" @click="freezeOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold cursor-pointer">
                    Batal
                </button>
                <button class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    Konfirmasi Bekukan
                </button>
            </div>
        </form>
    </div>
</main>
