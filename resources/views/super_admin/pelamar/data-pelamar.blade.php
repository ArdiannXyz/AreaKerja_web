@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-[#f8fafc] min-h-screen overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">

        {{-- TOP NAVIGATION & ADMIN USER BAR --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Kandidat</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh data pelamar, calon kandidat, dan kandidat aktif</p>
            </div>
            <div class="flex items-center gap-3 self-end sm:self-auto">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        {{-- TOOLBAR: Add + Category Tabs + Search --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
            <div class="flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-3">

                {{-- Left: Add Button + Category Tabs --}}
                <div class="flex items-center gap-2.5 overflow-x-auto no-scrollbar flex-shrink-0">
                    {{-- Add Button --}}
                    <a id="btnAdd" href="{{ route('superadmin.pelamar.create', ['kategori' => 'kandidat']) }}"
                        class="h-10 inline-flex items-center gap-1.5 px-4 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white text-xs sm:text-sm font-semibold transition shadow-xs flex-shrink-0 whitespace-nowrap">
                        <i class="ph ph-plus-bold text-sm"></i>
                        <span id="btnAddLabel">Tambah Kandidat</span>
                    </a>

                    {{-- Category Tab Buttons --}}
                    <div class="h-10 flex items-center gap-1 bg-slate-100 rounded-xl p-1 flex-shrink-0">
                        <button onclick="switchTab('kandidat')" id="tab-kandidat"
                            class="tab-btn h-full flex items-center px-3.5 rounded-lg text-xs font-semibold transition whitespace-nowrap bg-white text-slate-900 shadow-sm">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Kandidat Aktif
                            </span>
                        </button>
                        <button onclick="switchTab('non_kandidat')" id="tab-non_kandidat"
                            class="tab-btn h-full flex items-center px-3.5 rounded-lg text-xs font-semibold transition whitespace-nowrap text-slate-500 hover:text-slate-700">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Pelamar
                            </span>
                        </button>
                        <button onclick="switchTab('calon_kandidat')" id="tab-calon_kandidat"
                            class="tab-btn h-full flex items-center px-3.5 rounded-lg text-xs font-semibold transition whitespace-nowrap text-slate-500 hover:text-slate-700">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Calon Kandidat
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Right: Alpine.js Search --}}
                <div class="relative w-full xl:w-80 flex-shrink-0" x-data="{
                    open: false,
                    query: '{{ $search ?? '' }}',
                    recommendations: [
                        { label: 'Kandidat Aktif', category: 'Kategori', icon: 'ph-star' },
                        { label: 'Calon Kandidat', category: 'Kategori', icon: 'ph-clock' },
                        { label: 'Pelamar', category: 'Kategori', icon: 'ph-user' },
                        { label: 'Jawa Timur', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Jawa Barat', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'DKI Jakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Yogyakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Jawa Tengah', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Aktif', category: 'Status', icon: 'ph-check-circle' },
                        { label: 'Nonaktif', category: 'Status', icon: 'ph-prohibit' },
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
                        $nextTick(() => { $refs.pelamarSearchForm.submit(); });
                    }
                }" @click.outside="open = false">

                    <form x-ref="pelamarSearchForm" action="{{ route('superadmin.pelamar') }}" method="get" autocomplete="off" class="flex items-center w-full">
                        <div class="flex items-center w-full h-10 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                            <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                            <input type="text" name="search" x-model="query"
                                @focus="open = true"
                                @input="open = true"
                                autocomplete="off"
                                placeholder="Cari nama, wilayah, kategori..."
                                class="flex-1 h-full px-2.5 text-xs sm:text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                                style="border: none !important; outline: none !important; box-shadow: none !important;">
                            <template x-if="query.length > 0">
                                <a href="{{ route('superadmin.pelamar') }}"
                                   @click.prevent="query = ''; open = false; window.location.href = '{{ route('superadmin.pelamar') }}';"
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
            </div>
        </div>

        {{-- STATS SUMMARY CARDS --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Kandidat Aktif</p>
                    <p class="text-xl font-bold text-slate-900">{{ $kandidat->count() }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Total Pelamar</p>
                    <p class="text-xl font-bold text-slate-900">{{ $nonKandidat->count() }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Calon Kandidat</p>
                    <p class="text-xl font-bold text-slate-900">{{ $calonKandidat->count() }}</p>
                </div>
            </div>
        </div>

        {{-- TABLE: Kandidat Aktif --}}
        <div id="panel-kandidat" class="tab-panel">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h2 class="text-sm font-bold text-slate-900">Kandidat Aktif</h2>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full">
                            {{ $kandidat->count() }} data
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Kandidat</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pendidikan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Keahlian</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Domisili</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($kandidat as $p)
                                <tr class="hover:bg-slate-50/60 transition group">
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs font-mono text-slate-400">#{{ $p->id }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <img class="w-9 h-9 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                                src="{{ $p->img_profile ? asset('storage/' . $p->img_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($p->nama_pelamar ?? $p->user->username ?? 'K') . '&background=00509d&color=fff&size=64' }}"
                                                alt="{{ $p->nama_pelamar }}">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $p->nama_pelamar ?? $p->user->username ?? '-' }}</p>
                                                <p class="text-xs text-slate-400">{{ '@' . ($p->user->username ?? '') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs text-slate-600">
                                            {{ $p->riwayat_pendidikan->pluck('pendidikan')->filter()->implode(', ') ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($p->skill->take(3) as $s)
                                                <span class="inline-block px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[11px] font-medium border border-blue-100">{{ $s->skill }}</span>
                                            @endforeach
                                            @if ($p->skill->count() > 3)
                                                <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[11px] font-medium">+{{ $p->skill->count() - 3 }}</span>
                                            @endif
                                            @if ($p->skill->isEmpty())
                                                <span class="text-xs text-slate-400 italic">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs text-slate-600">{{ $p->alamat_pelamar->first()?->provinsi ?? '-' }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <a href="{{ route('superadmin.detail.kandidat', $p->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-slate-500">Belum ada data kandidat aktif</p>
                                            <a href="{{ route('superadmin.pelamar.create', ['kategori' => 'kandidat']) }}"
                                                class="text-xs text-blue-600 font-semibold hover:underline">+ Tambah Kandidat</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TABLE: Non Kandidat (Pelamar) --}}
        <div id="panel-non_kandidat" class="tab-panel hidden">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <h2 class="text-sm font-bold text-slate-900">Data Pelamar</h2>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-full">
                            {{ $nonKandidat->count() }} data
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pelamar</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pendidikan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Keahlian</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Domisili</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($nonKandidat as $p)
                                <tr class="hover:bg-slate-50/60 transition group">
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs font-mono text-slate-400">#{{ $p->id }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <img class="w-9 h-9 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                                src="{{ $p->img_profile ? asset('storage/' . $p->img_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($p->nama_pelamar ?? $p->user->username ?? 'P') . '&background=1d4ed8&color=fff&size=64' }}"
                                                alt="{{ $p->nama_pelamar }}">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $p->nama_pelamar ?? $p->user->username ?? '-' }}</p>
                                                <p class="text-xs text-slate-400">{{ '@' . ($p->user->username ?? '') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs text-slate-600">
                                            {{ $p->riwayat_pendidikan->pluck('pendidikan')->filter()->implode(', ') ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($p->skill->take(3) as $s)
                                                <span class="inline-block px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[11px] font-medium border border-blue-100">{{ $s->skill }}</span>
                                            @endforeach
                                            @if ($p->skill->count() > 3)
                                                <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[11px] font-medium">+{{ $p->skill->count() - 3 }}</span>
                                            @endif
                                            @if ($p->skill->isEmpty())
                                                <span class="text-xs text-slate-400 italic">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs text-slate-600">{{ $p->alamat_pelamar->first()?->provinsi ?? '-' }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <a href="{{ route('superadmin.detail.non.kandidat', $p->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-slate-500">Belum ada data pelamar</p>
                                            <a href="{{ route('superadmin.pelamar.create', ['kategori' => 'non_kandidat']) }}"
                                                class="text-xs text-blue-600 font-semibold hover:underline">+ Tambah Pelamar</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TABLE: Calon Kandidat --}}
        <div id="panel-calon_kandidat" class="tab-panel hidden">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h2 class="text-sm font-bold text-slate-900">Calon Kandidat</h2>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 rounded-full">
                            {{ $calonKandidat->count() }} data
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Calon Kandidat</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pendidikan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Keahlian</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Domisili</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($calonKandidat as $p)
                                <tr class="hover:bg-slate-50/60 transition group">
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs font-mono text-slate-400">#{{ $p->id }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <img class="w-9 h-9 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                                src="{{ $p->img_profile ? asset('storage/' . $p->img_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($p->nama_pelamar ?? $p->user->username ?? 'C') . '&background=d97706&color=fff&size=64' }}"
                                                alt="{{ $p->nama_pelamar }}">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $p->nama_pelamar ?? $p->user->username ?? '-' }}</p>
                                                <p class="text-xs text-slate-400">{{ '@' . ($p->user->username ?? '') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs text-slate-600">
                                            {{ $p->riwayat_pendidikan->pluck('pendidikan')->filter()->implode(', ') ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($p->skill->take(3) as $s)
                                                <span class="inline-block px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 text-[11px] font-medium border border-amber-100">{{ $s->skill }}</span>
                                            @endforeach
                                            @if ($p->skill->count() > 3)
                                                <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[11px] font-medium">+{{ $p->skill->count() - 3 }}</span>
                                            @endif
                                            @if ($p->skill->isEmpty())
                                                <span class="text-xs text-slate-400 italic">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="text-xs text-slate-600">{{ $p->alamat_pelamar->first()?->provinsi ?? '-' }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <a href="{{ route('superadmin.calon.detail', $p->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-slate-500">Belum ada data calon kandidat</p>
                                            <a href="{{ route('superadmin.pelamar.create', ['kategori' => 'calon_kandidat']) }}"
                                                class="text-xs text-blue-600 font-semibold hover:underline">+ Tambah Calon Kandidat</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modals Notifikasi --}}
        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')

    </main>

    <script>
        // Tab routing config
        const tabConfig = {
            'kandidat': {
                label: 'Tambah Kandidat',
                url: '{{ route("superadmin.pelamar.create", ["kategori" => "kandidat"]) }}'
            },
            'non_kandidat': {
                label: 'Tambah Pelamar',
                url: '{{ route("superadmin.pelamar.create", ["kategori" => "non_kandidat"]) }}'
            },
            'calon_kandidat': {
                label: 'Tambah Calon Kandidat',
                url: '{{ route("superadmin.pelamar.create", ["kategori" => "calon_kandidat"]) }}'
            }
        };

        let activeTab = 'kandidat';

        function switchTab(tab) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));

            // Deactivate all tabs
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                b.classList.add('text-slate-500');
            });

            // Show selected panel
            document.getElementById('panel-' + tab)?.classList.remove('hidden');

            // Activate selected tab
            const activeBtn = document.getElementById('tab-' + tab);
            if (activeBtn) {
                activeBtn.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
                activeBtn.classList.remove('text-slate-500');
            }

            // Update Add button
            const config = tabConfig[tab];
            if (config) {
                document.getElementById('btnAdd').href = config.url;
                document.getElementById('btnAddLabel').textContent = config.label;
            }

            activeTab = tab;
        }

        // Initialize on load
        switchTab('kandidat');

        // Tandai dibaca
        async function markAsRead(url, el) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {
                    el.classList.remove("bg-white");
                    el.classList.add("bg-gray-200");

                    const badge = document.getElementById("notif-badge");
                    if (badge) {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove();
                        }
                    }
                }
            } catch (error) {
                console.error("markAsRead error:", error);
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('notifHandler', () => ({
                async hapus(id) {
                    if (!confirm("Hapus notifikasi ini?")) return;
                    let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);
                    let res = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });
                    let data = await res.json();
                    if (data.success) {
                        document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                    }
                },
                async hapusSemua() {
                    if (!confirm("Hapus semua notifikasi?")) return;
                    let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });
                    let data = await res.json();
                    if (data.success) {
                        document.querySelectorAll('.notif-item').forEach(e => e.remove());
                    }
                },
                async hapusSemuaBaca() {
                    if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;
                    let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });
                    let data = await res.json();
                    if (data.success) {
                        document.querySelectorAll('.notif-item.bg-gray-200').forEach(e => e.remove());
                    }
                }
            }));
        });
    </script>

@endsection
