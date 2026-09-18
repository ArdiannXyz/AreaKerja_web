@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-users text-[#00509d] text-2xl"></i> Data Pelamar
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Kelola data pendaftaran calon kandidat baru</p>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')

        <!-- Tab Menu & Search -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

            <!-- Menu Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ route('admin.kandidat') }}"
                    class="{{ request()->is('admin/kandidat*') ? 'bg-[#00509d] text-white border-[#00509d] shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
                    <i class="ph ph-star mr-1"></i> Kandidat
                </a>
                <a href="{{ route('admin.non-kandidat') }}"
                    class="{{ request()->is('admin/non/kandidat*') ? 'bg-[#00509d] text-white border-[#00509d] shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
                    <i class="ph ph-x-circle mr-1"></i> Non Kandidat
                </a>
                <a href="{{ route('admin.calon-kandidat') }}"
                    class="{{ request()->is('admin/calon/kandidat*') ? 'bg-[#00509d] text-white border-[#00509d] shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
                    <i class="ph ph-hourglass mr-1"></i> Calon Kandidat
                </a>
            </div>

            <!-- Pencarian Modern dengan Autocomplete & Filter Otomatis -->
            <div class="relative w-full md:w-80" x-data="{
                open: false,
                query: '{{ request('q') }}',
                recommendations: [
                    { label: 'Jawa Timur', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Jawa Tengah', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Jawa Barat', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'DKI Jakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Yogyakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'S1', category: 'Pendidikan', icon: 'ph-graduation-cap' },
                    { label: 'D3', category: 'Pendidikan', icon: 'ph-graduation-cap' },
                    { label: 'SMK', category: 'Pendidikan', icon: 'ph-graduation-cap' },
                ],
                get filtered() {
                    if (!this.query.trim()) return this.recommendations.slice(0, 4);
                    return this.recommendations.filter(r =>
                        r.label.toLowerCase().includes(this.query.toLowerCase()) ||
                        r.category.toLowerCase().includes(this.query.toLowerCase())
                    );
                },
                select(val) {
                    this.query = val;
                    this.open = false;
                    filterTable(val);
                    $nextTick(() => { $refs.calonKandidatSearchForm.submit(); });
                }
            }" @click.outside="open = false">

                <form x-ref="calonKandidatSearchForm" action="{{ route('admin.calon-kandidat') }}" method="GET" autocomplete="off" class="flex items-center gap-2 w-full">
                    <div class="h-10 flex items-center w-full bg-white rounded-xl overflow-hidden border border-slate-200 focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition shadow-xs">
                        <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-base leading-none"></i>
                        <input type="text" name="q" x-model="query"
                            id="pelamar-search-input"
                            @focus="open = true"
                            @input="open = true; filterTable($event.target.value)"
                            autocomplete="new-password"
                            data-lpignore="true"
                            data-form-type="other"
                            placeholder="Cari nama pelamar atau skill..."
                            class="flex-1 h-full px-3 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                            style="border: none !important; outline: none !important; box-shadow: none !important;">
                        <template x-if="query.length > 0">
                            <a href="{{ route('admin.calon-kandidat') }}"
                               @click.prevent="query = ''; open = false; filterTable(''); window.location.href = '{{ route('admin.calon-kandidat') }}';"
                               class="w-5 h-5 rounded-full bg-slate-200 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center mr-2 flex-shrink-0 transition cursor-pointer"
                               title="Hapus pencarian">
                                <i class="ph ph-x text-[10px] font-bold"></i>
                            </a>
                        </template>
                    </div>
                    <button type="submit" class="bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold px-4 h-10 rounded-xl transition shadow-xs flex-shrink-0 flex items-center gap-1.5">
                        Cari
                    </button>
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

        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-4 text-center w-16">ID</th>
                            <th class="px-5 py-4">Nama Pelamar</th>
                            <th class="px-5 py-4">Skill</th>
                            <th class="px-5 py-4">Pendidikan</th>
                            <th class="px-5 py-4">Alamat</th>
                            <th class="px-5 py-4 text-center w-24">Detail</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700" id="pelamar-table-body">
                        @forelse ($pelamar as $item)
                            <tr class="pelamar-row hover:bg-blue-50/40 transition">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-500">{{ $item->id }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if ($item->img_profile)
                                            <img src="{{ asset('storage/' . $item->img_profile) }}" alt="Avatar" class="w-9 h-9 rounded-xl object-cover border border-slate-200">
                                        @else
                                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#00509d] to-[#0077b6] text-white font-bold flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
                                                {{ strtoupper(substr($item->nama_pelamar ?? ($item->user->username ?? 'P'), 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-snug">
                                                {{ $item->nama_pelamar ?? $item->user->username }}
                                            </span>
                                            <span class="text-xs text-slate-400 font-normal">
                                                {{ $item->user->email ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($item->skill->isNotEmpty())
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @foreach ($item->skill->take(3) as $s)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-200/60">
                                                    {{ $s->skill }}
                                                </span>
                                            @endforeach
                                            @if ($item->skill->count() > 3)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-500">
                                                    +{{ $item->skill->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-xs">
                                    {{ $item->riwayat_pendidikan->first()?->pendidikan ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-xs">
                                    {{ $item->alamat_pelamar->first()?->provinsi ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="{{ route('calon.detail', $item->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white transition shadow-xs"
                                        title="Lihat Detail">
                                        <i class="ph ph-eye text-base"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                    <i class="ph ph-users text-4xl mb-2 block"></i>
                                    Belum ada data calon kandidat tersedia.
                                </td>
                            </tr>
                        @endforelse
                        <tr id="pelamar-empty-search-row" style="display: none;">
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <i class="ph ph-magnifying-glass text-3xl mb-2 block text-slate-300"></i>
                                Tidak ada calon kandidat yang cocok dengan pencarian.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        function filterTable(val) {
            const q = (val || '').toLowerCase().trim();
            const rows = document.querySelectorAll('#pelamar-table-body tr.pelamar-row');
            let visibleCount = 0;
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (!q || text.includes(q)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            const emptyRow = document.getElementById('pelamar-empty-search-row');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }
    </script>
@endsection
