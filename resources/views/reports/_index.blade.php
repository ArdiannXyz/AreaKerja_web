@php
    $pendingReportsCount = $counts['pending'] ?? 0;
    $notifPartial = ($isSuper ?? false) ? 'super_admin.components.notif_button' : 'admin.components.notif_button';
    $badgePartial = ($isSuper ?? false) ? 'super_admin.components.user_badge_dropdown' : 'admin.components.user_badge_dropdown';
    $currentTipe = $tipeFilter ?? 'all';
@endphp

<main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen">
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-6 bg-[#00509d] text-white p-4 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-2xl">
                <i class="ph ph-shield-check"></i>
            </div>
            <div>
                <h1 class="text-base sm:text-xl font-bold text-white tracking-tight">Moderasi & Laporan Pelanggaran</h1>
                <p class="text-xs text-blue-100 mt-0.5">Tinjau laporan dari pelamar (terhadap lowongan) dan perusahaan (terhadap pelamar problematik).</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            @include($notifPartial)
            @include($badgePartial)
        </div>
    </header>

    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5">
            <i class="ph ph-check-circle text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- TABS TIPE LAPORAN -->
    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-1">
        <a href="{{ route($routes['index'], array_merge(request()->query(), ['tipe' => 'all', 'alasan' => null])) }}"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $currentTipe === 'all' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Semua Laporan</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentTipe === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route($routes['index'], array_merge(request()->query(), ['tipe' => 'lowongan', 'alasan' => null])) }}"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $currentTipe === 'lowongan' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="ph ph-briefcase"></i>
            <span>Laporan Lowongan</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentTipe === 'lowongan' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['tipe_lowongan'] }}</span>
        </a>
        <a href="{{ route($routes['index'], array_merge(request()->query(), ['tipe' => 'pelamar', 'alasan' => null])) }}"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $currentTipe === 'pelamar' ? 'bg-[#00509d] text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <i class="ph ph-user"></i>
            <span>Laporan Pelamar</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentTipe === 'pelamar' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700' }}">{{ $counts['tipe_pelamar'] }}</span>
        </a>
    </div>

    <!-- STATISTIK RINGKAS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Menunggu Tinjauan</p>
            <p class="text-xl sm:text-2xl font-bold text-amber-600 mt-1">{{ $counts['pending'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Telah Diselesaikan</p>
            <p class="text-xl sm:text-2xl font-bold text-emerald-600 mt-1">{{ $counts['resolved'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Ditolak / Dismiss</p>
            <p class="text-xl sm:text-2xl font-bold text-slate-700 mt-1">{{ $counts['dismissed'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Total Sesuai Filter</p>
            <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-1">{{ $counts['all'] }}</p>
        </div>
    </div>

    <!-- FILTER PENCARIAN -->
    <form method="get" action="{{ route($routes['index']) }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-6 flex flex-col lg:flex-row gap-3">
        <input type="hidden" name="tipe" value="{{ $currentTipe }}">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama subjek, lowongan, perusahaan, atau pelapor..."
            class="flex-1 rounded-xl border-slate-200 text-sm focus:border-[#00509d] focus:ring-[#00509d]">
        <select name="status" class="rounded-xl border-slate-200 text-sm focus:border-[#00509d] focus:ring-[#00509d]">
            <option value="pending" @selected($statusFilter === 'pending')>Status: Menunggu</option>
            <option value="resolved" @selected($statusFilter === 'resolved')>Status: Selesai</option>
            <option value="dismissed" @selected($statusFilter === 'dismissed')>Status: Ditolak</option>
            <option value="all" @selected($statusFilter === 'all')>Status: Semua</option>
        </select>
        <select name="alasan" class="rounded-xl border-slate-200 text-sm focus:border-[#00509d] focus:ring-[#00509d]">
            <option value="">Semua Alasan</option>
            @foreach ($alasanOptions as $value => $label)
                <option value="{{ $value }}" @selected($alasanFilter === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="px-5 py-2 bg-[#00509d] text-white text-sm font-semibold rounded-xl hover:bg-[#003d7a] transition cursor-pointer">
            Filter
        </button>
    </form>

    <!-- TABEL LAPORAN -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3.5">Subjek Dilaporkan</th>
                        <th class="px-4 py-3.5">Pelapor</th>
                        <th class="px-4 py-3.5">Alasan</th>
                        <th class="px-4 py-3.5">Status & Tindakan</th>
                        <th class="px-4 py-3.5">Waktu</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($reports as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3.5">
                                @if ($item->isPelamar())
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 text-[10px] font-bold border border-purple-200">
                                            <i class="ph ph-user"></i> Pelamar
                                        </span>
                                    </div>
                                    <p class="font-bold text-slate-900">{{ $item->pelamar->nama_pelamar ?? 'Pelamar (Dihapus)' }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $item->pelamar->telepon_pelamar ?? '-' }}
                                        @if($item->lowongan)
                                            · Lowongan: <span class="text-slate-700">{{ $item->lowongan->nama }}</span>
                                        @endif
                                    </p>
                                @else
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200">
                                            <i class="ph ph-briefcase"></i> Lowongan
                                        </span>
                                    </div>
                                    <p class="font-bold text-slate-900">{{ $item->lowongan->nama ?? 'Lowongan dihapus' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->lowongan->perusahaan->nama_perusahaan ?? '-' }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($item->isPelamar())
                                    <p class="font-semibold text-slate-800">{{ $item->reporter->perusahaan->nama_perusahaan ?? $item->reporter->username }}</p>
                                    <span class="inline-block text-[11px] text-blue-600 font-medium">Perusahaan</span>
                                @else
                                    <p class="font-semibold text-slate-800">{{ $item->reporter->pelamar->nama_pelamar ?? $item->reporter->username ?? '-' }}</p>
                                    <span class="inline-block text-[11px] text-emerald-600 font-medium">Pelamar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ $item->alasan_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($item->status === 'pending')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                                        Menunggu
                                    </span>
                                @elseif ($item->status === 'resolved')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                        Selesai
                                    </span>
                                    @if ($item->action_taken && $item->action_taken !== 'none')
                                        <p class="text-[11px] text-slate-500 mt-1 font-medium">{{ $item->action_label }}</p>
                                    @endif
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 text-xs whitespace-nowrap">
                                {{ $item->created_at?->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route($routes['show'], $item) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#00509d] text-white hover:bg-[#003d7a] rounded-lg text-xs font-bold transition shadow-2xs">
                                    <span>Tinjau</span>
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                <i class="ph ph-tray text-3xl mb-1 block"></i>
                                Tidak ada laporan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $reports->links() }}</div>
    </div>
</main>
