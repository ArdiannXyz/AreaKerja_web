@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">Akun Freeze</h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    Kelola dan pantau status akun yang dibekukan atau dibanned
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Toolbar: Search -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
                <div class="text-xs font-semibold text-slate-500 flex items-center gap-2 flex-wrap">
                    <span class="flex items-center gap-1.5">
                        <i class="ph ph-snowflake text-base text-blue-500"></i>
                        <span>Total Akun Freeze:</span>
                        <span class="text-slate-800 font-bold bg-slate-100 px-2 py-0.5 rounded-md">{{ count($data) }}</span>
                    </span>
                    @if($search ?? '')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-[#00509d] text-[11px] font-medium border border-blue-100">
                            Filter: "{{ $search }}"
                            <a href="{{ route('superadmin.freeze') }}" class="hover:text-rose-600 ml-0.5 transition" title="Hapus filter">
                                <i class="ph ph-x"></i>
                            </a>
                        </span>
                    @endif
                </div>

                <div class="relative w-full sm:w-80 md:w-96" x-data="{
                    open: false,
                    query: '{{ $search ?? '' }}',
                    recommendations: [
                        { label: 'Jawa Timur', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Jawa Barat', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Jawa Tengah', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'DKI Jakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Yogyakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Pelamar', category: 'Role', icon: 'ph-user' },
                        { label: 'Perusahaan', category: 'Role', icon: 'ph-buildings' },
                        { label: 'Kandidat Aktif', category: 'Kategori', icon: 'ph-star' },
                        { label: 'Aktif', category: 'Status', icon: 'ph-check-circle' },
                        { label: 'Banned', category: 'Status', icon: 'ph-prohibit' }
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
                        $nextTick(() => { $refs.freezeSearchForm.submit(); });
                    }
                }" @click.outside="open = false">
                    
                    <form x-ref="freezeSearchForm" action="{{ route('superadmin.freeze') }}" method="get" autocomplete="off" class="flex items-center w-full">
                        <div class="flex items-center w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                            <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                            <input type="text" name="search" x-model="query"
                                @focus="open = true"
                                @input="open = true"
                                autocomplete="off"
                                placeholder="Cari username, role, wilayah..."
                                class="flex-1 px-2.5 py-2 text-xs sm:text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                                style="border: none !important; outline: none !important; box-shadow: none !important;">
                            
                            <!-- Clear Button -->
                            <template x-if="query.length > 0">
                                <a href="{{ route('superadmin.freeze') }}"
                                   @click.prevent="query = ''; open = false; window.location.href = '{{ route('superadmin.freeze') }}';"
                                   class="w-5 h-5 rounded-full bg-slate-200 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center mr-2.5 flex-shrink-0 transition cursor-pointer"
                                   title="Hapus pencarian">
                                    <i class="ph ph-x text-[10px] font-bold"></i>
                                </a>
                            </template>

                            <button type="submit" class="hidden"></button>
                        </div>
                    </form>

                    <!-- Elegant Autocomplete / Recommendations Dropdown -->
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

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5 w-12 text-center">No</th>
                            <th class="px-5 py-3.5">Username</th>
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5">Role</th>
                            <th class="px-5 py-3.5">Telepon</th>
                            <th class="px-5 py-3.5">Alamat</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($data as $i => $d)
                            @php
                                $provinsi = '-';
                                if ($d->role == 'pelamar') {
                                    $pelamarObj = $d->pelamar;
                                    $provinsi = is_object($pelamarObj) ? ($pelamarObj->provinsi ?? $pelamarObj->alamat ?? '-') : '-';
                                } elseif ($d->role == 'perusahaan') {
                                    $perusahaanObj = $d->perusahaan;
                                    $provinsi = is_object($perusahaanObj) ? ($perusahaanObj->provinsi ?? $perusahaanObj->kota ?? '-') : '-';
                                } elseif ($d->role == 'finance') {
                                    $finObj = $d->finance;
                                    $provinsi = is_object($finObj) ? (is_object($finObj->provinsi ?? null) ? ($finObj->provinsi->nama ?? '-') : ($finObj->provinsi ?? '-')) : '-';
                                } elseif ($d->role == 'admin') {
                                    $admObj = $d->admin;
                                    $provinsi = is_object($admObj) ? (is_object($admObj->provinsi ?? null) ? ($admObj->provinsi->nama ?? '-') : ($admObj->provinsi ?? '-')) : '-';
                                } elseif ($d->role == 'super_admin') {
                                    $saObj = $d->super_admin;
                                    $provinsi = is_object($saObj) ? (is_object($saObj->provinsi ?? null) ? ($saObj->provinsi->nama ?? '-') : ($saObj->provinsi ?? '-')) : '-';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition duration-150">
                                <td class="px-5 py-3.5 text-center text-slate-400 text-xs font-medium">{{ $i + 1 }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">{{ $d->username }}</td>
                                <td class="px-5 py-3.5 text-slate-600 text-xs">{{ $d->email }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                        {{ in_array($d->role, ['superadmin', 'super_admin']) ? 'bg-purple-100 text-purple-700' : 
                                           ($d->role === 'finance' ? 'bg-amber-100 text-amber-700' : 
                                           ($d->role === 'perusahaan' ? 'bg-emerald-100 text-emerald-700' : 
                                           ($d->role === 'pelamar' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-700'))) }}">
                                        {{ ucfirst($d->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 text-xs">
                                    @if ($d->role == 'pelamar')
                                        {{ $d->pelamar->telepon_pelamar ?? '-' }}
                                    @elseif ($d->role == 'perusahaan')
                                        {{ $d->perusahaan->telepon_perusahaan ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs max-w-[160px] truncate">{{ $provinsi }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($d->status == 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <i class="ph ph-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-100 text-rose-700">
                                            <i class="ph ph-prohibit"></i> Banned
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="{{ route('superadmin.detail.freeze', $d->id) }}"
                                       title="Lihat Detail"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white transition">
                                        <i class="ph ph-eye text-base"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @if(count($data) === 0)
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data akun freeze
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
