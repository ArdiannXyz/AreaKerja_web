@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
    <div class="p-6 w-full sm:ml-64 max-h-6xl">
        <div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-2">
                <h2 class="text-xl font-semibold text-gray-700">
                    Data Kandidat Perusahaan - {{ $perusahaan->user->name ?? $perusahaan->nama_perusahaan }}
                </h2>
            </div>

            {{-- FORM SEARCH --}}
            <div class="flex justify-end mb-4">
                <div class="relative w-full sm:w-80" x-data="{
                    open: false,
                    query: '{{ request('search') }}',
                    recommendations: [
                        { label: 'Wawancara', category: 'Kegiatan', icon: 'ph-microphone' },
                        { label: 'Diterima', category: 'Status', icon: 'ph-check-circle' },
                        { label: 'Ditolak', category: 'Status', icon: 'ph-x-circle' },
                    ],
                    get filtered() {
                        if (!this.query.trim()) return this.recommendations;
                        return this.recommendations.filter(r =>
                            r.label.toLowerCase().includes(this.query.toLowerCase()) ||
                            r.category.toLowerCase().includes(this.query.toLowerCase())
                        );
                    },
                    select(val) {
                        this.query = val;
                        this.open = false;
                        $nextTick(() => { $refs.listPekerjaForm.submit(); });
                    }
                }" @click.outside="open = false">
                    <form x-ref="listPekerjaForm" method="GET" action="{{ route('superadmin.panggilan.list', $perusahaan->id) }}" autocomplete="off" class="flex items-center w-full">
                        <div class="flex items-center w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                            <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                            <input type="text" name="search" x-model="query"
                                @focus="open = true" @input="open = true"
                                autocomplete="off" placeholder="Cari nama pelamar..."
                                class="flex-1 px-2.5 py-2.5 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                                style="border: none !important; outline: none !important; box-shadow: none !important;">
                            <template x-if="query.length > 0">
                                <a href="{{ route('superadmin.panggilan.list', $perusahaan->id) }}"
                                   @click.prevent="query = ''; open = false; window.location.href = '{{ route('superadmin.panggilan.list', $perusahaan->id) }}';"
                                   class="w-5 h-5 rounded-full bg-slate-200 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center mr-2.5 flex-shrink-0 transition cursor-pointer"
                                   title="Hapus pencarian">
                                    <i class="ph ph-x text-[10px] font-bold"></i>
                                </a>
                            </template>
                            <button type="submit" class="hidden"></button>
                        </div>
                    </form>
                    <div x-cloak x-show="open && filtered.length > 0"
                         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                         class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-100 shadow-xl p-2 z-50 max-h-72 overflow-y-auto">
                        <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center justify-between border-b border-slate-50 mb-1">
                            <span>Saran Pencarian</span><i class="ph ph-sparkle text-[#00509d]"></i>
                        </div>
                        <ul class="space-y-0.5">
                            <template x-for="(item, idx) in filtered" :key="idx">
                                <li>
                                    <button type="button" @click="select(item.label)" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left hover:bg-blue-50/70 transition group">
                                        <span class="flex items-center gap-2.5 text-xs text-slate-700 group-hover:text-[#00509d] font-medium">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center text-slate-500 group-hover:text-[#00509d] transition">
                                                <i :class="'ph ' + item.icon" class="text-xs"></i>
                                            </span>
                                            <span x-text="item.label"></span>
                                        </span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 group-hover:bg-blue-100 text-slate-500 group-hover:text-[#00509d] font-medium" x-text="item.category"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-blue-700">
                        <tr class="text-left text-white text-sm font-semibold">
                            <th class="px-2 sm:px-4 py-2 border-b">No</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Nama Pelamar</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Email</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Lowongan</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Tanggal Diterima</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelamarDiterima as $i => $p)
                            <tr class="text-sm border-b hover:bg-gray-50">
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[50px]">{{ $i + 1 }}
                                </td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[150px]">
                                    {{ $p['nama'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[150px]">
                                    {{ $p['email'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[150px]">
                                    {{ $p['lowongan'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[120px]">
                                    {{ $p['tanggal_diterima'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[100px] text-center">
                                    @if ($p['jenis'] === 'pelamar_melamar')
                                        <span class="text-green-600 font-semibold">Wawancara</span>
                                    @else
                                        <span class="text-red-600 font-semibold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-gray-500">
                                    Belum ada pekerja diterima
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>


    </div>
@endsection
