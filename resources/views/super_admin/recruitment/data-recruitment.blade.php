@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
            <!-- Judul -->
            <h1 class="text-2xl font-medium text-gray-700 truncate sm:truncate-0 w-full sm:w-auto">
                Data Recruitment
            </h1>

            <!-- Notifikasi + Profil -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full sm:w-auto">
                <!-- Tombol Notifikasi -->
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        <!-- Header + Search -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">

                <div class="relative w-full md:w-96" x-data="{
                    open: false,
                    query: '{{ request('search') }}',
                    recommendations: [
                        { label: 'Jawa Timur', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Jawa Barat', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'DKI Jakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                        { label: 'Diterima', category: 'Status', icon: 'ph-check-circle' },
                        { label: 'Ditolak', category: 'Status', icon: 'ph-x-circle' },
                        { label: 'Menunggu', category: 'Status', icon: 'ph-clock' },
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
                        $nextTick(() => { $refs.recruitmentSearchForm.submit(); });
                    }
                }" @click.outside="open = false">

                    <form x-ref="recruitmentSearchForm" action="{{ route('superadmin.recruitment', $perusahaan->id) }}" method="get" autocomplete="off" class="flex items-center w-full">
                        <div class="flex items-center w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                            <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                            <input type="text" name="search" x-model="query"
                                @focus="open = true"
                                @input="open = true"
                                autocomplete="off"
                                placeholder="Cari nama kandidat, lowongan..."
                                class="flex-1 px-2.5 py-2.5 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                                style="border: none !important; outline: none !important; box-shadow: none !important;">
                            <template x-if="query.length > 0">
                                <a href="{{ route('superadmin.recruitment', $perusahaan->id) }}"
                                   @click.prevent="query = ''; open = false; window.location.href = '{{ route('superadmin.recruitment', $perusahaan->id) }}';"
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

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border-2 border-gray-400">
            <table class="w-full min-w-[700px] text-left border-collapse">
                <thead class="text-center bg-blue-700 text-white">
                    <tr class="border-b-[2px] border-gray-300">
                        <th class="p-4 sm:p-7 font-medium">ID</th>
                        <th class="p-4 sm:p-7 font-medium">Nama Kandidat</th>
                        <th class="p-4 sm:p-7 font-medium">Lowongan</th>
                        <th class="p-4 sm:p-7 font-medium">Email</th>
                        <th class="p-4 sm:p-7 font-medium">Telepon</th>
                        <th class="p-4 sm:p-7 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($recruitments as $r)
                        <tr class="border-b border-gray-300">
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->id }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->nama_pelamar }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->lowonganPerusahaan->nama }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->user->email }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->telepon_pelamar }}</td>
                            <td class="px-2 sm:px-4 py-2">
                                <a href="{{ route('superadmin.recruitment.detail', $r->id) }}"
                                    class="bg-blue-700 text-xs sm:text-sm text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg inline-block whitespace-nowrap">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-gray-500 text-center">Belum ada recruitment diterima</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
