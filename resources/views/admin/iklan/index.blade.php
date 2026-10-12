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
        <!-- TABEL MODERASI IKLAN (TABLE FIXED 100% - PASTI PAS DI LAYAR) -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="w-full overflow-hidden">
                <table class="w-full table-fixed text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="w-[42%] px-4 py-3.5">Iklan & Perusahaan</th>
                            <th class="w-[18%] px-3 py-3.5">Paket & Biaya</th>
                            <th class="w-[20%] px-3 py-3.5">Status & Periode</th>
                            <th class="w-[20%] px-4 py-3.5 text-right">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold">
                        @forelse ($iklans as $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <!-- Iklan & Perusahaan -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <!-- Thumbnail Banner (Dapat diklik preview) -->
                                        <div class="relative w-14 h-9 sm:w-16 sm:h-10 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 cursor-pointer group/img shadow-2xs"
                                            onclick="openPreviewModal('{{ $item->banner_url }}', '{{ addslashes($item->judul_iklan) }}', '{{ addslashes($item->url_tujuan) }}')"
                                            title="Klik untuk melihat banner dan link tujuan">
                                            <img src="{{ $item->banner_url }}" alt="Banner" class="w-full h-full object-cover group-hover/img:scale-105 transition duration-200">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition flex items-center justify-center text-white text-xs">
                                                <i class="ph ph-magnifying-glass-plus text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-extrabold text-slate-900 truncate" title="{{ $item->judul_iklan }}">{{ $item->judul_iklan }}</p>
                                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5 font-medium truncate">
                                                <i class="ph ph-buildings text-slate-400 shrink-0"></i>
                                                <span class="truncate font-bold text-slate-700" title="{{ $item->perusahaan?->nama_perusahaan }}">{{ $item->perusahaan?->nama_perusahaan ?? 'Perusahaan #' . $item->perusahaan_id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Paket & Biaya -->
                                <td class="px-3 py-3.5 whitespace-nowrap">
                                    <p class="font-extrabold text-slate-800 text-xs">{{ $item->paket_nama ?? 'Paket Iklan' }}</p>
                                    <div class="flex items-center gap-1 mt-0.5 text-[11px] font-bold">
                                        <span class="text-slate-500">{{ $item->durasi_hari }} Hari</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-amber-600 flex items-center gap-0.5">
                                            <i class="ph-fill ph-coin text-xs"></i>
                                            {{ number_format($item->koin_terpotong, 0, ',', '.') }} Koin
                                        </span>
                                    </div>
                                </td>

                                <!-- Status & Periode -->
                                <td class="px-3 py-3.5 whitespace-nowrap">
                                    @if ($item->status === 'menunggu')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Menunggu Review</span>
                                        </span>
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-medium">Diajukan: {{ $item->created_at->translatedFormat('d M Y') }}</p>
                                    @elseif ($item->status === 'aktif')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-extrabold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif Tayang</span>
                                        </span>
                                        @if ($item->tanggal_mulai && $item->tanggal_selesai)
                                            <p class="text-[10px] text-emerald-600 mt-0.5 font-bold">
                                                Sisa {{ $item->sisa_hari }} hari (s/d {{ $item->tanggal_selesai->translatedFormat('d M') }})
                                            </p>
                                        @endif
                                    @elseif ($item->status === 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-extrabold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Ditolak</span>
                                        </span>
                                        @if($item->alasan_penolakan)
                                            <p class="text-[10px] text-rose-600 font-medium mt-0.5 truncate" title="{{ $item->alasan_penolakan }}">
                                                "{{ $item->alasan_penolakan }}"
                                            </p>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 text-[10px] font-extrabold rounded-full">
                                            <span>Selesai</span>
                                        </span>
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $item->views }} views • {{ $item->clicks }} klik</p>
                                    @endif
                                </td>

                                <!-- Aksi Moderasi -->
                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Tombol Preview -->
                                        <button type="button" onclick="openPreviewModal('{{ $item->banner_url }}', '{{ addslashes($item->judul_iklan) }}', '{{ addslashes($item->url_tujuan) }}')"
                                            class="p-1.5 rounded-xl bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white transition shadow-2xs"
                                            title="Lihat Banner & Link Tujuan">
                                            <i class="ph ph-eye text-sm"></i>
                                        </button>

                                        @if ($item->status === 'menunggu')
                                            <!-- Approve Form -->
                                            <form id="approveForm{{ $item->id }}" action="{{ route('admin.iklan.approve', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="button" onclick="confirmApprove('approveForm{{ $item->id }}', '{{ addslashes($item->judul_iklan) }}', '{{ $item->durasi_hari }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-2xs active:scale-95">
                                                    <i class="ph-bold ph-check text-xs"></i>
                                                    <span>Setujui</span>
                                                </button>
                                            </form>

                                            <!-- Reject Trigger -->
                                            <button type="button" onclick="openRejectModal('{{ $item->id }}', '{{ addslashes($item->judul_iklan) }}', '{{ $item->koin_terpotong }}', '{{ addslashes($item->perusahaan?->nama_perusahaan ?? '') }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-2xs active:scale-95">
                                                <i class="ph-bold ph-x text-xs"></i>
                                                <span>Tolak</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-slate-400">
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
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 relative border border-slate-100">
            <button onclick="closePreviewModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="ph ph-x text-sm font-bold"></i>
            </button>

            <h3 id="adminModalTitle" class="text-base font-extrabold text-slate-900 mb-1 pr-8 truncate"></h3>
            <p class="text-xs text-slate-500 mb-3">Link Tujuan: <a id="adminModalLink" href="#" target="_blank" class="text-[#00509d] underline font-bold truncate inline-block max-w-sm align-middle"></a></p>

            <div class="w-full rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
                <img id="adminModalImg" src="" alt="Banner Preview" class="w-full h-auto object-contain max-h-[300px]">
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
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative border border-slate-100">
            <button onclick="closeRejectModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="ph ph-x text-sm font-bold"></i>
            </button>

            <div class="flex items-center gap-3 mb-3.5">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0">
                    <i class="ph-fill ph-warning-circle"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Tolak Pengajuan Iklan</h3>
                    <p class="text-xs text-slate-500">Koin akan di-refund otomatis ke perusahaan</p>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800 mb-3.5">
                <p class="font-bold">⚠️ Perhatian:</p>
                <p class="mt-0.5">Saldo <span id="rejectKoinNominal" class="font-black"></span> Koin milik <span id="rejectPerusahaanNama" class="font-bold"></span> otomatis dikembalikan ke akun mereka.</p>
            </div>

            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-3.5">
                    <label for="alasan_penolakan" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3" required
                        placeholder="Tuliskan alasan penolakan iklan secara jelas..."
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-xs font-semibold rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-rose-500 shadow-2xs"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl transition shadow-xs">
                        Tolak & Refund Koin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Modal Preview Banner
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

        // Modal Tolak Iklan
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

        // Konfirmasi SweetAlert2 untuk ACC / Setujui Iklan (Ukuran Medium Pas)
        function confirmApprove(formId, judul, durasi) {
            Swal.fire({
                title: 'Setujui Iklan Ini?',
                html: `<p class="text-sm text-slate-600">Apakah Anda yakin ingin menyetujui iklan <b>"${judul}"</b>?</p><p class="text-xs text-slate-400 mt-2">Iklan akan langsung aktif tayang di Homepage selama <b>${durasi} hari</b>.</p>`,
                icon: 'question',
                showCancelButton: true,
                width: '410px',
                padding: '1.5rem',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="ph-bold ph-check mr-1"></i> Ya, Setujui',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl font-sans',
                    title: 'text-base font-extrabold text-slate-800 m-0',
                    htmlContainer: 'text-sm text-slate-500 m-0 pt-1.5',
                    confirmButton: 'rounded-xl px-4 py-2.5 text-xs font-bold shadow-xs',
                    cancelButton: 'rounded-xl px-4 py-2.5 text-xs font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang mengaktifkan iklan',
                        width: '320px',
                        padding: '1.25rem',
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-2xl font-sans',
                            title: 'text-sm font-bold text-slate-700'
                        },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById(formId).submit();
                }
            });
        }

        // Flash Alert Messages (Ukuran Medium Pas)
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    width: '380px',
                    padding: '1.5rem',
                    confirmButtonColor: '#00509d',
                    confirmButtonText: 'Oke',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl font-sans',
                        title: 'text-base font-extrabold text-slate-800',
                        htmlContainer: 'text-sm text-slate-600',
                        confirmButton: 'rounded-xl px-5 py-2 text-xs font-bold'
                    }
                });
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    width: '380px',
                    padding: '1.5rem',
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl font-sans',
                        title: 'text-base font-extrabold text-slate-800',
                        htmlContainer: 'text-sm text-slate-600',
                        confirmButton: 'rounded-xl px-5 py-2 text-xs font-bold'
                    }
                });
            });
        @endif

        @if(session('info'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: "{{ session('info') }}",
                    width: '380px',
                    padding: '1.5rem',
                    confirmButtonColor: '#00509d',
                    confirmButtonText: 'Mengerti',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl font-sans',
                        title: 'text-base font-extrabold text-slate-800',
                        htmlContainer: 'text-sm text-slate-600',
                        confirmButton: 'rounded-xl px-5 py-2 text-xs font-bold'
                    }
                });
            });
        @endif
    </script>
@endsection
