@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-[#00509d] text-white p-3.5 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
            <!-- Kiri: Judul + tombol pasang -->
            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-start gap-4">
                <div class="flex items-center gap-3">
                    <div>
                        <h1 class="text-base sm:text-xl font-semibold text-white tracking-tight flex items-center gap-2">
                            <i class="ph ph-megaphone text-[#00509d] text-lg sm:text-2xl"></i> Moderasi Iklan Beranda
                        </h1>
                        <p class="text-[11px] sm:text-xs text-blue-100 mt-0.5">Review pengajuan banner iklan perusahaan, verifikasi kelayakan konten, dan kelola penayangan</p>
                    </div>
                </div>
                <!-- Tombol pasang iklan (mobile) -->
                <div class="sm:hidden flex items-center gap-2">
                    @include('admin.components.notif_button')
                </div>
            </div>

            <!-- Kanan: Notif + Avatar saja -->
            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
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
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">

                <!-- Status Filter Tabs -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.iklan.index', array_filter(['status' => 'menunggu', 'q' => $search])) }}"
                        class="inline-flex items-center gap-2 h-10 px-3.5 rounded-xl text-xs font-bold transition shadow-2xs {{ $statusFilter === 'menunggu' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Menunggu Review</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'menunggu' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                            {{ $countMenunggu }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', array_filter(['status' => 'aktif', 'q' => $search])) }}"
                        class="inline-flex items-center gap-2 h-10 px-3.5 rounded-xl text-xs font-bold transition shadow-2xs {{ $statusFilter === 'aktif' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Sedang Tayang</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'aktif' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $countAktif }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', array_filter(['status' => 'ditolak', 'q' => $search])) }}"
                        class="inline-flex items-center gap-2 h-10 px-3.5 rounded-xl text-xs font-bold transition shadow-2xs {{ $statusFilter === 'ditolak' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Ditolak</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'ditolak' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">
                            {{ $countDitolak }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', array_filter(['status' => 'selesai', 'q' => $search])) }}"
                        class="inline-flex items-center gap-2 h-10 px-3.5 rounded-xl text-xs font-bold transition shadow-2xs {{ $statusFilter === 'selesai' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Selesai</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'selesai' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $countSelesai }}
                        </span>
                    </a>

                    <a href="{{ route('admin.iklan.index', array_filter(['status' => 'all', 'q' => $search])) }}"
                        class="inline-flex items-center gap-1.5 h-10 px-3.5 rounded-xl text-xs font-bold transition shadow-2xs {{ $statusFilter === 'all' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>Semua ({{ $countSemua }})</span>
                    </a>
                </div>

                <!-- Search + Tombol Pasang (dikelompokkan berdampingan) -->
                <div class="flex items-center gap-2.5 w-full lg:w-auto">

                    <!-- Search with Smart Recommendations -->
                    <div class="relative flex-1 min-w-0 sm:w-72 sm:flex-initial" x-data="{
                        open: false,
                        query: '{{ $search ?? '' }}',
                        recommendations: [
                            { label: 'PT', category: 'Perusahaan', icon: 'ph-buildings' },
                            { label: 'CV', category: 'Perusahaan', icon: 'ph-buildings' },
                            { label: 'Teknologi', category: 'Industri', icon: 'ph-laptop' },
                            { label: 'Lowongan', category: 'Topik', icon: 'ph-briefcase' },
                            { label: 'Rekrutmen', category: 'Topik', icon: 'ph-users' },
                            { label: 'Promo', category: 'Topik', icon: 'ph-tag' },
                            { label: 'Hiring', category: 'Topik', icon: 'ph-megaphone' },
                            { label: 'Banner', category: 'Media', icon: 'ph-image' },
                            { label: 'Official', category: 'Perusahaan', icon: 'ph-seal-check' },
                            { label: 'Karir', category: 'Topik', icon: 'ph-chart-line-up' }
                        ],
                        get filtered() {
                            if (!this.query.trim()) return this.recommendations.slice(0, 5);
                            return this.recommendations.filter(r =>
                                r.label.toLowerCase().includes(this.query.toLowerCase()) ||
                                r.category.toLowerCase().includes(this.query.toLowerCase())
                            );
                        },
                        select(val) {
                            this.query = val;
                            this.open = false;
                            $nextTick(() => { $refs.adminIklanSearchForm.submit(); });
                        }
                    }" @click.outside="open = false">

                        <form x-ref="adminIklanSearchForm" action="{{ route('admin.iklan.index') }}" method="GET" autocomplete="off" class="flex items-center w-full">
                            <div class="h-10 flex items-center w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                                <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                                <input type="text" name="q" x-model="query"
                                    @focus="open = true"
                                    @input="open = true"
                                    autocomplete="off"
                                    placeholder="Cari judul iklan / perusahaan..."
                                    class="flex-1 h-full px-2.5 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                                    style="border: none !important; outline: none !important; box-shadow: none !important;">
                                <template x-if="query.length > 0">
                                    <a href="{{ route('admin.iklan.index') }}"
                                       @click.prevent="query = ''; open = false; window.location.href = '{{ route('admin.iklan.index') }}';"
                                       class="w-5 h-5 rounded-full bg-slate-200 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center mr-2.5 flex-shrink-0 transition cursor-pointer"
                                       title="Hapus pencarian">
                                        <i class="ph ph-x text-[10px] font-bold"></i>
                                    </a>
                                </template>
                                <button type="submit" class="hidden"></button>
                            </div>
                        </form>

                        <!-- Autocomplete Dropdown -->
                        <div x-cloak x-show="open && filtered.length > 0"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-100 shadow-xl p-2 z-50 max-h-72 overflow-y-auto">
                            <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center justify-between border-b border-slate-50 mb-1">
                                <span>Saran Pencarian</span>
                                <i class="ph ph-sparkle text-[#00509d]"></i>
                            </div>
                            <ul class="space-y-0.5">
                                <template x-for="(item, idx) in filtered" :key="idx">
                                    <li>
                                        <button type="button" @click="select(item.label)"
                                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left hover:bg-blue-50/70 hover:text-[#00509d] transition group">
                                            <span class="flex items-center gap-2.5 text-xs text-slate-700 group-hover:text-[#00509d] font-medium">
                                                <span class="w-6 h-6 rounded-lg bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center text-slate-500 group-hover:text-[#00509d] transition">
                                                    <i :class="'ph ' + item.icon" class="text-xs"></i>
                                                </span>
                                                <span x-text="item.label"></span>
                                            </span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 group-hover:bg-blue-100 text-slate-500 group-hover:text-[#00509d] font-medium"
                                                  x-text="item.category"></span>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Tombol Pasang Iklan -->
                    <a href="{{ route('admin.iklan.create') }}"
                       class="h-10 inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-4 rounded-xl transition duration-200 shadow-xs flex-shrink-0">
                        <i class="ph ph-plus-circle text-base"></i>
                        Pasang Iklan
                    </a>

                </div>
            </div>
        </div>
        </div>

        @if ($search)
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3.5 bg-blue-50/80 border border-blue-100 rounded-2xl text-xs text-[#00509d]">
                <div class="flex items-center gap-2 font-medium">
                    <i class="ph-fill ph-magnifying-glass text-base text-[#00509d] shrink-0"></i>
                    <span>Hasil pencarian untuk: <strong class="text-slate-800">"{{ $search }}"</strong></span>
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-[11px] font-bold text-[#00509d]">
                        {{ $iklans->total() }} ditemukan
                    </span>
                </div>
                <a href="{{ route('admin.iklan.index') }}" class="font-bold hover:underline flex items-center gap-1 text-xs text-rose-600">
                    <i class="ph ph-x-circle text-sm"></i> Reset Pencarian
                </a>
            </div>
        @endif

        <!-- TABEL MODERASI IKLAN -->
        <!-- TABEL MODERASI IKLAN (TABLE FIXED 100% - PASTI PAS DI LAYAR) -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="w-full overflow-hidden">
                <table class="w-full table-fixed text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="w-[42%] px-4 py-3.5">Iklan & Perusahaan</th>
                            <th class="w-[18%] px-3 py-3.5">Paket & Durasi</th>
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
                                                <span class="truncate font-bold {{ $item->perusahaan ? 'text-slate-700' : 'text-[#00509d]' }}" title="{{ $item->perusahaan?->nama_perusahaan ?? 'Official AreaKerja' }}">
                                                    @if($item->perusahaan)
                                                        {{ $item->perusahaan->nama_perusahaan }}
                                                    @else
                                                        <span class="inline-flex items-center gap-1">
                                                            <i class="ph-fill ph-seal-check text-xs"></i> Official AreaKerja
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Paket & Durasi -->
                                <td class="px-3 py-3.5 whitespace-nowrap">
                                    <p class="font-extrabold text-slate-800 text-xs">{{ $item->paket_nama ?? 'Paket Iklan' }}</p>
                                    <div class="flex items-center gap-1 mt-0.5 text-[11px] font-bold">
                                        @if($item->durasi_hari > 0)
                                            <span class="text-slate-500">{{ $item->durasi_hari }} Hari</span>
                                        @else
                                            <span class="text-[#00509d]">Tanpa Batas</span>
                                        @endif
                                        @if($item->perusahaan && $item->koin_terpotong > 0)
                                            <span class="text-slate-300">•</span>
                                            <span class="text-amber-600 flex items-center gap-0.5" title="Koin Perusahaan">
                                                <i class="ph-fill ph-coin text-xs"></i>
                                                {{ number_format($item->koin_terpotong, 0, ',', '.') }} Koin
                                            </span>
                                        @endif
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
                                        @if ($item->tanggal_selesai)
                                            <p class="text-[10px] text-emerald-600 mt-0.5 font-bold">
                                                Sisa {{ $item->sisa_hari }} hari (s/d {{ $item->tanggal_selesai->translatedFormat('d M') }})
                                            </p>
                                        @else
                                            <p class="text-[10px] text-[#00509d] mt-0.5 font-bold">
                                                Aktif Beranda
                                            </p>
                                        @endif
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $item->total_views }} views • {{ $item->total_clicks }} klik</p>
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
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $item->total_views }} views • {{ $item->total_clicks }} klik</p>
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

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.iklan.edit', $item->id) }}"
                                            class="p-1.5 rounded-xl bg-slate-100 hover:bg-amber-500 text-slate-600 hover:text-white transition shadow-2xs"
                                            title="Edit Iklan">
                                            <i class="ph ph-note-pencil text-sm"></i>
                                        </a>

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

                                        <!-- Hapus Iklan -->
                                        <form id="deleteForm{{ $item->id }}" action="{{ route('admin.iklan.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('deleteForm{{ $item->id }}', '{{ addslashes($item->judul_iklan) }}')"
                                                class="p-1.5 rounded-xl bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white transition shadow-2xs"
                                                title="Hapus Iklan">
                                                <i class="ph ph-trash text-sm"></i>
                                            </button>
                                        </form>
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

        // Konfirmasi Hapus Iklan Permanen
        function confirmDelete(formId, judul) {
            Swal.fire({
                title: 'Hapus Iklan Ini?',
                html: `<p class="text-sm text-slate-600">Apakah Anda yakin ingin menghapus iklan <b>"${judul}"</b>?</p><p class="text-xs text-rose-500 mt-2 font-semibold">Tindakan ini permanen dan banner akan langsung dihapus dari sistem.</p>`,
                icon: 'warning',
                showCancelButton: true,
                width: '410px',
                padding: '1.5rem',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Iklan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl font-sans',
                    title: 'text-base font-extrabold text-slate-800',
                    confirmButton: 'rounded-xl px-4 py-2.5 text-xs font-bold shadow-xs',
                    cancelButton: 'rounded-xl px-4 py-2.5 text-xs font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
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
