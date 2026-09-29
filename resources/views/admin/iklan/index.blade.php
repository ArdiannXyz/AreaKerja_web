@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-megaphone text-[#00509d] text-2xl"></i> Moderasi Iklan Beranda
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Review pengajuan banner iklan perusahaan, verifikasi kelayakan konten, dan kelola penayangan</p>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')

        <!-- ALERTS -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-2xs">
                <i class="ph-fill ph-check-circle text-2xl text-emerald-600 shrink-0"></i>
                <p class="text-xs sm:text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl p-4 flex items-center gap-3 shadow-2xs">
                <i class="ph-fill ph-info text-2xl text-[#00509d] shrink-0"></i>
                <p class="text-xs sm:text-sm font-bold">{{ session('info') }}</p>
            </div>
        @endif

        <!-- FILTER TABS & SEARCH TOOLBAR -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs mb-6 space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Status Tabs -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.iklan.index', ['status' => 'menunggu']) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $statusFilter === 'menunggu' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Menunggu Review</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'menunggu' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                            {{ $countMenunggu }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', ['status' => 'aktif']) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $statusFilter === 'aktif' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Sedang Tayang</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'aktif' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $countAktif }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', ['status' => 'ditolak']) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $statusFilter === 'ditolak' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Ditolak</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'ditolak' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">
                            {{ $countDitolak }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', ['status' => 'selesai']) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $statusFilter === 'selesai' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Selesai</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'selesai' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $countSelesai }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', ['status' => 'all']) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $statusFilter === 'all' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Semua ({{ $countSemua }})</span>
                    </a>
                </div>

                <!-- Search Input -->
                <form action="{{ route('admin.iklan.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $search }}"
                            placeholder="Cari judul iklan / perusahaan..."
                            class="bg-slate-50 border border-slate-200 text-slate-800 text-xs font-bold rounded-xl px-3.5 py-2 pl-9 focus:outline-none focus:ring-2 focus:ring-[#00509d] w-64 shadow-2xs">
                        <i class="ph ph-magnifying-glass text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-sm"></i>
                    </div>
                    <button type="submit" class="px-3.5 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl transition shadow-2xs">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.iklan.index', ['status' => $statusFilter]) }}"
                            class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- TABEL MODERASI IKLAN -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50/80 text-slate-600 font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Perusahaan</th>
                            <th class="px-6 py-4">Banner & Materi</th>
                            <th class="px-6 py-4">Paket & Koin</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Periode Tayang</th>
                            <th class="px-6 py-4 text-right">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($iklans as $item)
                            <tr class="hover:bg-blue-50/20 transition group">
                                <!-- Perusahaan Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-[#00509d] flex items-center justify-center font-black text-sm shrink-0 overflow-hidden">
                                            @if($item->perusahaan?->logo)
                                                <img src="{{ asset('storage/' . $item->perusahaan->logo) }}" alt="Logo" class="w-full h-full object-cover">
                                            @else
                                                <i class="ph-fill ph-buildings text-lg"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-extrabold text-slate-900 truncate max-w-xs">{{ $item->perusahaan?->nama_perusahaan ?? 'Perusahaan #' . $item->perusahaan_id }}</p>
                                            <p class="text-[11px] text-slate-400 font-medium truncate">{{ $item->perusahaan?->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Banner & Judul -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 relative group/img cursor-pointer"
                                            onclick="openPreviewModal('{{ $item->banner_url }}', '{{ addslashes($item->judul_iklan) }}', '{{ addslashes($item->url_tujuan) }}')">
                                            <img src="{{ $item->banner_url }}" alt="Banner" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition flex items-center justify-center text-white text-xs">
                                                <i class="ph ph-magnifying-glass-plus text-base"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-extrabold text-slate-800 truncate max-w-xs sm:max-w-sm">{{ $item->judul_iklan }}</p>
                                            <a href="{{ $item->url_tujuan }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-[11px] text-[#00509d] hover:underline font-semibold mt-0.5 truncate max-w-xs">
                                                <span>{{ Str::limit($item->url_tujuan, 35) }}</span>
                                                <i class="ph ph-arrow-square-out text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                <!-- Paket & Koin -->
                                <td class="px-6 py-4">
                                    <p class="font-extrabold text-slate-800">{{ $item->paket_nama }}</p>
                                    <div class="flex items-center gap-1.5 mt-1 text-xs font-bold text-slate-500">
                                        <span>{{ $item->durasi_hari }} Hari</span>
                                        <span>•</span>
                                        <span class="text-amber-600">{{ number_format($item->koin_terpotong, 0, ',', '.') }} Koin</span>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-6 py-4 text-center">
                                    @if ($item->status === 'menunggu')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Menunggu</span>
                                        </span>
                                    @elseif ($item->status === 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif Tayang</span>
                                        </span>
                                    @elseif ($item->status === 'ditolak')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Ditolak</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold rounded-full">
                                            <span>Selesai</span>
                                        </span>
                                    @endif

                                    @if($item->status === 'ditolak' && $item->alasan_penolakan)
                                        <p class="text-[10px] text-rose-600 font-medium mt-1 max-w-[140px] mx-auto truncate" title="{{ $item->alasan_penolakan }}">
                                            "{{ $item->alasan_penolakan }}"
                                        </p>
                                    @endif
                                </td>

                                <!-- Periode Tayang -->
                                <td class="px-6 py-4 text-center text-xs">
                                    @if ($item->tanggal_mulai && $item->tanggal_selesai)
                                        <p class="font-extrabold text-slate-800">
                                            {{ $item->tanggal_mulai->translatedFormat('d M') }} - {{ $item->tanggal_selesai->translatedFormat('d M Y') }}
                                        </p>
                                        @if($item->status === 'aktif')
                                            <span class="inline-block mt-0.5 text-[11px] font-bold text-emerald-600">
                                                Sisa {{ $item->sisa_hari }} hari
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-medium">-</span>
                                    @endif
                                </td>

                                <!-- Aksi Moderasi -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Preview -->
                                        <button type="button" onclick="openPreviewModal('{{ $item->banner_url }}', '{{ addslashes($item->judul_iklan) }}', '{{ addslashes($item->url_tujuan) }}')"
                                            class="p-2 rounded-xl bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white transition shadow-2xs"
                                            title="Lihat Banner">
                                            <i class="ph ph-eye text-base"></i>
                                        </button>

                                        @if ($item->status === 'menunggu')
                                            <!-- Approve Form -->
                                            <form action="{{ route('admin.iklan.approve', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin menyetujui iklan ini? Iklan akan langsung tayang di Homepage selama {{ $item->durasi_hari }} hari.')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-2xs">
                                                    <i class="ph-bold ph-check"></i>
                                                    <span>Setujui</span>
                                                </button>
                                            </form>

                                            <!-- Reject Trigger -->
                                            <button type="button" onclick="openRejectModal('{{ $item->id }}', '{{ addslashes($item->judul_iklan) }}', '{{ $item->koin_terpotong }}', '{{ addslashes($item->perusahaan?->nama_perusahaan ?? '') }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-2xs">
                                                <i class="ph-bold ph-x"></i>
                                                <span>Tolak</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                    <i class="ph ph-megaphone text-4xl text-slate-300 mx-auto mb-2"></i>
                                    <p class="font-extrabold text-sm text-slate-700">Tidak ada data iklan pada kategori ini.</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Pengajuan iklan dari perusahaan akan ditampilkan di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($iklans->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $iklans->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- ================= MODAL PREVIEW BANNER ================= -->
    <div id="adminBannerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-6 relative border border-slate-100">
            <button onclick="closePreviewModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="ph ph-x font-bold"></i>
            </button>

            <h3 id="adminModalTitle" class="text-base font-extrabold text-slate-900 mb-1 pr-8 truncate"></h3>
            <p class="text-xs text-slate-500 mb-4">Link Tujuan: <a id="adminModalLink" href="#" target="_blank" class="text-[#00509d] underline font-bold truncate inline-block max-w-md align-middle"></a></p>

            <div class="w-full rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
                <img id="adminModalImg" src="" alt="Banner Preview" class="w-full h-auto object-contain max-h-[350px]">
            </div>

            <div class="mt-5 flex justify-end">
                <button type="button" onclick="closePreviewModal()"
                    class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL REJECT IKLAN & REFUND ================= -->
    <div id="adminRejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-7 relative border border-slate-100">
            <button onclick="closeRejectModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="ph ph-x font-bold"></i>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                    <i class="ph-fill ph-warning-circle"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Tolak Pengajuan Iklan</h3>
                    <p class="text-xs text-slate-500">Koin akan di-refund otomatis ke perusahaan</p>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800 mb-4">
                <p class="font-bold">⚠️ Perhatian:</p>
                <p class="mt-0.5">Saldo <span id="rejectKoinNominal" class="font-black"></span> Koin milik <span id="rejectPerusahaanNama" class="font-bold"></span> akan otomatis dikembalikan ke akun mereka.</p>
            </div>

            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="alasan_penolakan" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3" required
                        placeholder="Cth: Gambar banner tidak sesuai rasio atau mengandung konten yang tidak diizinkan..."
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-xs font-semibold rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-rose-500 shadow-2xs"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl transition shadow-xs">
                        Tolak & Refund Koin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPreviewModal(url, title, link) {
            document.getElementById('adminModalImg').src = url;
            document.getElementById('adminModalTitle').innerText = title;
            const linkEl = document.getElementById('adminModalLink');
            linkEl.href = link;
            linkEl.innerText = link;
            const m = document.getElementById('adminBannerModal');
            if (m) m.style.display = 'flex';
        }

        function closePreviewModal() {
            const m = document.getElementById('adminBannerModal');
            if (m) m.style.display = 'none';
        }

        function openRejectModal(id, title, koin, perusahaan) {
            document.getElementById('rejectForm').action = "/admin/iklan/" + id + "/reject";
            document.getElementById('rejectKoinNominal').innerText = Number(koin).toLocaleString('id-ID');
            document.getElementById('rejectPerusahaanNama').innerText = perusahaan;
            document.getElementById('alasan_penolakan').value = '';
            const m = document.getElementById('adminRejectModal');
            if (m) m.style.display = 'flex';
        }

        function closeRejectModal() {
            const m = document.getElementById('adminRejectModal');
            if (m) m.style.display = 'none';
        }
    </script>
@endsection
