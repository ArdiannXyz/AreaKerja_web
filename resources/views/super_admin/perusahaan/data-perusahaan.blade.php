@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
<main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

    {{-- Header Bar --}}
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">Data Perusahaan</h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Kelola dan verifikasi seluruh mitra perusahaan yang terdaftar di platform AreaKerja
            </p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            @include('super_admin.components.notif_button')
            @include('super_admin.components.user_badge_dropdown')
        </div>
    </header>

    {{-- Stats Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <a href="{{ route('superadmin.perusahaan') }}"
           class="bg-white rounded-xl p-4 border {{ !request('status') ? 'border-[#00509d]/40 shadow-sm' : 'border-slate-100 shadow-xs' }} hover:shadow-md hover:-translate-y-0.5 transition duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg {{ !request('status') ? 'bg-[#00509d]' : 'bg-slate-100' }} flex items-center justify-center transition">
                    <i class="ph ph-buildings text-base {{ !request('status') ? 'text-white' : 'text-slate-500' }}"></i>
                </div>
                <span class="text-[10px] font-bold {{ !request('status') ? 'text-[#00509d]' : 'text-slate-400' }} uppercase tracking-widest">Semua</span>
            </div>
            <span class="text-2xl font-bold {{ !request('status') ? 'text-[#00509d]' : 'text-slate-800' }}">{{ $totalPerusahaan }}</span>
            <p class="text-[11px] text-slate-400 mt-0.5">Total Mitra</p>
        </a>

        <a href="{{ route('superadmin.perusahaan', ['status' => 'approved']) }}"
           class="bg-white rounded-xl p-4 border {{ request('status') === 'approved' ? 'border-[#00509d]/40 shadow-sm' : 'border-slate-100 shadow-xs' }} hover:shadow-md hover:-translate-y-0.5 transition duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg {{ request('status') === 'approved' ? 'bg-[#00509d]' : 'bg-slate-100' }} flex items-center justify-center transition">
                    <i class="ph ph-check-circle text-base {{ request('status') === 'approved' ? 'text-white' : 'text-slate-500' }}"></i>
                </div>
                <span class="text-[10px] font-bold {{ request('status') === 'approved' ? 'text-[#00509d]' : 'text-slate-400' }} uppercase tracking-widest">Disetujui</span>
            </div>
            <span class="text-2xl font-bold {{ request('status') === 'approved' ? 'text-[#00509d]' : 'text-slate-800' }}">{{ $totalApproved }}</span>
            <p class="text-[11px] text-slate-400 mt-0.5">Terverifikasi</p>
        </a>

        <a href="{{ route('superadmin.perusahaan', ['status' => 'pending']) }}"
           class="bg-white rounded-xl p-4 border {{ request('status') === 'pending' ? 'border-[#00509d]/40 shadow-sm' : 'border-slate-100 shadow-xs' }} hover:shadow-md hover:-translate-y-0.5 transition duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg {{ request('status') === 'pending' ? 'bg-[#00509d]' : 'bg-slate-100' }} flex items-center justify-center transition">
                    <i class="ph ph-clock text-base {{ request('status') === 'pending' ? 'text-white' : 'text-slate-500' }}"></i>
                </div>
                <span class="text-[10px] font-bold {{ request('status') === 'pending' ? 'text-[#00509d]' : 'text-slate-400' }} uppercase tracking-widest">Menunggu</span>
            </div>
            <span class="text-2xl font-bold {{ request('status') === 'pending' ? 'text-[#00509d]' : 'text-slate-800' }}">{{ $totalPending }}</span>
            <p class="text-[11px] text-slate-400 mt-0.5">Pending Review</p>
        </a>

        <a href="{{ route('superadmin.perusahaan', ['status' => 'rejected']) }}"
           class="bg-white rounded-xl p-4 border {{ request('status') === 'rejected' ? 'border-[#00509d]/40 shadow-sm' : 'border-slate-100 shadow-xs' }} hover:shadow-md hover:-translate-y-0.5 transition duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg {{ request('status') === 'rejected' ? 'bg-[#00509d]' : 'bg-slate-100' }} flex items-center justify-center transition">
                    <i class="ph ph-x-circle text-base {{ request('status') === 'rejected' ? 'text-white' : 'text-slate-500' }}"></i>
                </div>
                <span class="text-[10px] font-bold {{ request('status') === 'rejected' ? 'text-[#00509d]' : 'text-slate-400' }} uppercase tracking-widest">Ditolak</span>
            </div>
            <span class="text-2xl font-bold {{ request('status') === 'rejected' ? 'text-[#00509d]' : 'text-slate-800' }}">{{ $totalRejected }}</span>
            <p class="text-[11px] text-slate-400 mt-0.5">Perusahaan Ditolak</p>
        </a>
    </div>

    {{-- Toolbar: Search + Filter + Actions --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 mb-5">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">

            {{-- Search --}}
            <div class="relative w-full md:w-96" x-data="{
                open: false,
                query: '{{ $search ?? '' }}',
                recommendations: [
                    { label: 'Disetujui', category: 'Status', icon: 'ph-check-circle' },
                    { label: 'Pending', category: 'Status', icon: 'ph-clock' },
                    { label: 'Ditolak', category: 'Status', icon: 'ph-x-circle' },
                    { label: 'Jawa Timur', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Jawa Barat', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'DKI Jakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Yogyakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Jawa Tengah', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'PT', category: 'Jenis', icon: 'ph-buildings' },
                    { label: 'CV', category: 'Jenis', icon: 'ph-buildings' },
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
                    $nextTick(() => { $refs.perusahaanSearchForm.submit(); });
                }
            }" @click.outside="open = false">

                <form x-ref="perusahaanSearchForm" action="{{ route('superadmin.perusahaan') }}" method="GET" autocomplete="off" class="flex items-center w-full">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="flex items-center flex-1 h-10 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                        <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0" style="font-size:16px;"></i>
                        <input type="text" name="search" x-model="query"
                            @focus="open = true"
                            @input="open = true"
                            autocomplete="off"
                            placeholder="Cari perusahaan, kota, status..."
                            class="flex-1 h-full px-2.5 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                            style="border: none !important; outline: none !important; box-shadow: none !important;">
                        <template x-if="query.length > 0">
                            <a href="{{ route('superadmin.perusahaan', request()->except('search')) }}"
                               @click.prevent="query = ''; open = false; window.location.href = '{{ route('superadmin.perusahaan', request()->except('search')) }}';"
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



            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- Filter Tab Links --}}
                <div class="hidden sm:flex items-center h-10 gap-1 bg-slate-100 rounded-xl p-1 text-xs font-semibold">
                    <a href="{{ route('superadmin.perusahaan', array_merge(request()->except('status'), [])) }}" 
                       class="h-full flex items-center px-3.5 rounded-lg transition {{ !request('status') ? 'bg-white text-[#00509d] shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.perusahaan', array_merge(request()->except('status'), ['status' => 'approved'])) }}"
                       class="h-full flex items-center px-3.5 rounded-lg transition {{ request('status') === 'approved' ? 'bg-white text-emerald-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Approved
                    </a>
                    <a href="{{ route('superadmin.perusahaan', array_merge(request()->except('status'), ['status' => 'pending'])) }}"
                       class="h-full flex items-center px-3.5 rounded-lg transition {{ request('status') === 'pending' ? 'bg-white text-amber-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Pending
                    </a>
                    <a href="{{ route('superadmin.perusahaan', array_merge(request()->except('status'), ['status' => 'rejected'])) }}"
                       class="h-full flex items-center px-3.5 rounded-lg transition {{ request('status') === 'rejected' ? 'bg-white text-rose-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Rejected
                    </a>
                </div>

                {{-- Tambah Perusahaan --}}
                <a href="{{ route('superadmin.add.user.createForm') }}"
                   class="h-10 inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-4 rounded-xl transition duration-200 shadow-xs flex-shrink-0">
                    <i class="ph ph-plus-circle text-base"></i>
                    Tambah
                </a>

                {{-- Menu Lainnya --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="h-10 w-10 inline-flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition duration-200 flex-shrink-0">
                        <i class="ph ph-dots-three-vertical text-base"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                         class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-slate-100 z-20 overflow-hidden text-xs font-semibold">
                        <a href="{{ route('superadmin.recruitment.perusahaan') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-slate-700 transition">
                            <i class="ph ph-newspaper-clipping text-base text-slate-400"></i> Recruitment
                        </a>
                        <a href="{{ route('superadmin.talent-hunter') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-slate-700 transition">
                            <i class="ph ph-crosshair text-base text-slate-400"></i> Talent Hunter
                        </a>
                        <a href="{{ route('superadmin.panggilan') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-slate-700 transition">
                            <i class="ph ph-phone text-base text-slate-400"></i> Panggilan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Filter Notice --}}
        @if($search || request('status'))
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-500">
                <i class="ph ph-funnel text-slate-400"></i>
                <span>Filter aktif:
                    @if($search) <strong class="text-slate-700">"{{ $search }}"</strong> @endif
                    @if(request('status')) &nbsp;status: <strong class="text-slate-700 capitalize">{{ request('status') }}</strong> @endif
                </span>
                <a href="{{ route('superadmin.perusahaan') }}" class="ml-auto text-[#00509d] hover:underline font-semibold">Reset Filter</a>
            </div>
        @endif
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3.5 w-10 text-center">No</th>
                        <th class="px-4 py-3.5 min-w-[220px]">Perusahaan</th>
                        <th class="px-4 py-3.5 min-w-[160px]">Email & Telepon</th>
                        <th class="px-4 py-3.5 min-w-[140px]">Lokasi & Jenis</th>
                        <th class="px-4 py-3.5 text-center min-w-[80px]">Lowongan</th>
                        <th class="px-4 py-3.5 text-center min-w-[100px]">Koin</th>
                        <th class="px-4 py-3.5 text-center min-w-[110px]">Status</th>
                        <th class="px-4 py-3.5 text-center min-w-[160px]">Verifikasi</th>
                        <th class="px-4 py-3.5 text-center min-w-[80px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($perusahaan as $i => $p)
                        <tr class="hover:bg-slate-50/60 transition duration-150 group">

                            {{-- No --}}
                            <td class="px-4 py-3.5 text-center text-slate-400 text-xs font-medium">
                                {{ $perusahaan->firstItem() + $i }}
                            </td>

                            {{-- Perusahaan Identity --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($p->img_profile)
                                        <img src="{{ asset('storage/' . $p->img_profile) }}"
                                             class="w-10 h-10 object-cover rounded-xl border border-slate-200 flex-shrink-0" alt="Logo">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00509d] to-[#0077b6] flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                                            {{ strtoupper(substr($p->nama_perusahaan ?? 'P', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 text-sm truncate max-w-[180px]">
                                            {{ $p->nama_perusahaan ?? $p->user->username }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 truncate">
                                            {{ $p->legalitas ?? 'PT' }} &bull; {{ $p->user->username ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Email & Telepon --}}
                            <td class="px-4 py-3.5">
                                <p class="text-xs text-slate-700 truncate max-w-[160px]">{{ $p->user->email ?? '-' }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $p->telepon_perusahaan ?? '-' }}</p>
                            </td>

                            {{-- Lokasi & Jenis --}}
                            <td class="px-4 py-3.5">
                                <p class="text-xs text-slate-700">{{ $p->kota ?? '-' }}</p>
                                <p class="text-[11px] text-slate-400 truncate max-w-[140px]">{{ $p->jenis_perusahaan ?? 'Industri Umum' }}</p>
                            </td>

                            {{-- Lowongan Count --}}
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold
                                    {{ $p->lowongan_perusahaans_count > 0 ? 'bg-blue-50 text-[#00509d]' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="ph ph-briefcase text-xs"></i>
                                    {{ $p->lowongan_perusahaans_count }}
                                </span>
                            </td>

                            {{-- Koin --}}
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600">
                                    <i class="ph ph-coins text-sm"></i>
                                    {{ number_format($p->koin_perusahaan ?? 0) }}
                                </span>
                            </td>

                            {{-- Status Verifikasi --}}
                            <td class="px-4 py-3.5 text-center">
                                @if ($p->verification_status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved
                                    </span>
                                @elseif ($p->verification_status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Tombol Verifikasi --}}
                            <td class="px-4 py-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5 justify-center">
                                    @if ($p->verification_status !== 'approved')
                                        <form method="POST" action="{{ route('superadmin.perusahaan.approve', $p->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition shadow-xs">
                                                <i class="ph ph-check text-xs"></i> Approve
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-bold bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-200">
                                            <i class="ph ph-check-circle text-xs"></i> Aktif
                                        </span>
                                    @endif

                                    <button type="button" onclick="openRejectModal({{ $p->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-bold bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 rounded-lg border border-rose-200 hover:border-transparent transition">
                                        <i class="ph ph-x text-xs"></i> Tolak
                                    </button>
                                </div>
                            </td>

                            {{-- Aksi Detail --}}
                            <td class="px-4 py-3.5 text-center">
                                <a href="{{ route('superadmin.perusahaan.detail', $p->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-500 hover:bg-[#00509d] hover:border-[#00509d] hover:text-white transition duration-150"
                                   title="Lihat Detail">
                                    <i class="ph ph-eye text-sm"></i>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="ph ph-buildings text-5xl text-slate-200"></i>
                                    <p class="text-slate-400 font-medium text-sm">
                                        {{ $search ? 'Tidak ditemukan hasil untuk "' . $search . '"' : 'Belum ada perusahaan terdaftar.' }}
                                    </p>
                                    @if($search)
                                        <a href="{{ route('superadmin.perusahaan') }}" class="text-[#00509d] text-xs hover:underline font-semibold">Hapus Filter</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($perusahaan->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <span>
                    Menampilkan <strong class="text-slate-700">{{ $perusahaan->firstItem() }}</strong>–<strong class="text-slate-700">{{ $perusahaan->lastItem() }}</strong>
                    dari <strong class="text-slate-700">{{ $perusahaan->total() }}</strong> perusahaan
                </span>
                <div class="flex items-center gap-1">
                    {{-- Previous --}}
                    @if ($perusahaan->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg text-slate-300 cursor-not-allowed">
                            <i class="ph ph-caret-left"></i>
                        </span>
                    @else
                        <a href="{{ $perusahaan->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 transition">
                            <i class="ph ph-caret-left"></i>
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach ($perusahaan->getUrlRange(1, $perusahaan->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold transition
                               {{ $page === $perusahaan->currentPage() ? 'bg-[#00509d] text-white border border-[#00509d]' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    {{-- Next --}}
                    @if ($perusahaan->hasMorePages())
                        <a href="{{ $perusahaan->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 transition">
                            <i class="ph ph-caret-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg text-slate-300 cursor-not-allowed">
                            <i class="ph ph-caret-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @include('super_admin.notif.modal_notif')
    @include('super_admin.notif.modal_semua')

    {{-- Modal Reject Verifikasi Perusahaan --}}
    <div id="modalReject" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-xs">
        <div class="bg-white rounded-2xl w-[90%] max-w-md p-6 shadow-xl border border-slate-100">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-x-circle text-xl"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Tolak Verifikasi Perusahaan</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Berikan alasan penolakan agar perusahaan dapat memperbaiki data</p>
                </div>
            </div>

            <form method="POST" id="rejectForm">
                @csrf
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="note" rows="3"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-200 focus:border-rose-400 resize-none transition"
                    placeholder="Contoh: Dokumen tidak lengkap, logo belum diunggah..."></textarea>

                <div class="flex justify-end mt-5 gap-2.5">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-xs">
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<script>
    function openRejectModal(id) {
        const modal = document.getElementById('modalReject');
        document.getElementById('rejectForm').action = `/super_admin/perusahaan/reject/${id}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('modalReject').classList.remove('flex');
        document.getElementById('modalReject').classList.add('hidden');
    }

    // Tutup modal ketika klik backdrop
    document.getElementById('modalReject').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>

<script>
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
                    if (count > 1) badge.textContent = count - 1;
                    else badge.remove();
                }
            }
        } catch (error) { console.error("markAsRead error:", error); }
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('notifHandler', () => ({
            async hapus(id) {
                if (!confirm("Hapus notifikasi ini?")) return;
                let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);
                let res = await fetch(url, { method: "DELETE", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" } });
                let data = await res.json();
                if (data.success) document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
            },
            async hapusSemua() {
                if (!confirm("Hapus semua notifikasi?")) return;
                let res = await fetch("{{ route('notifikasi.hapusSemua') }}", { method: "DELETE", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" } });
                let data = await res.json();
                if (data.success) document.querySelectorAll('.notif-item').forEach(e => e.remove());
            },
            async hapusSemuaBaca() {
                if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;
                let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", { method: "DELETE", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" } });
                let data = await res.json();
                if (data.success) document.querySelectorAll('.notif-item.bg-gray-200').forEach(e => e.remove());
            }
        }));
    });
</script>

<script>
    document.querySelector('form[target="hiddenFrame"]')?.addEventListener('submit', () => {
        document.querySelectorAll('.notif-item').forEach(item => {
            item.classList.remove('bg-white');
            item.classList.add('bg-gray-200');
        });
        const badge = document.querySelector('.absolute .bg-red-500');
        if (badge) badge.remove();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
