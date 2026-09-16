@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">Event</h1>
                <p class="text-xs text-slate-400 mt-0.5">Kelola semua event dan webinar yang ada di platform AreaKerja</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">

                <!-- Search -->
                <div class="relative w-full md:w-80" x-data="{
                    open: false,
                    query: '{{ request('q') }}',
                    recommendations: [
                        { label: 'Buka', category: 'Status', icon: 'ph-check-circle' },
                        { label: 'Tutup', category: 'Status', icon: 'ph-prohibit' },
                        { label: 'Draft', category: 'Status', icon: 'ph-pencil-simple-line' },
                        { label: 'Webinar', category: 'Tipe', icon: 'ph-video-camera' },
                        { label: 'Workshop', category: 'Tipe', icon: 'ph-briefcase' },
                        { label: 'Seminar', category: 'Tipe', icon: 'ph-presentation-chart' },
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
                        $nextTick(() => { $refs.eventSearchForm.submit(); });
                    }
                }" @click.outside="open = false">

                    <form x-ref="eventSearchForm" method="GET" action="{{ route('superadmin.eventform') }}" autocomplete="off" class="flex items-center w-full">
                        <div class="h-10 flex items-center w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                            <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                            <input type="text" name="q" x-model="query"
                                @focus="open = true"
                                @input="open = true"
                                autocomplete="off"
                                placeholder="Cari event..."
                                class="flex-1 h-full px-2.5 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                                style="border: none !important; outline: none !important; box-shadow: none !important;">
                            <template x-if="query.length > 0">
                                <a href="{{ route('superadmin.eventform') }}"
                                   @click.prevent="query = ''; open = false; window.location.href = '{{ route('superadmin.eventform') }}';"
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

                <a href="{{ route('superadmin.event.createForm') }}"
                   class="h-10 inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-4 rounded-xl transition duration-200 shadow-xs flex-shrink-0">
                    <i class="ph ph-plus-circle text-base"></i>
                    Buat Event
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5 text-center w-24">Status</th>
                            <th class="px-5 py-3.5 min-w-[200px]">Nama Event</th>
                            <th class="px-5 py-3.5 text-center w-20">Kuota</th>
                            <th class="px-5 py-3.5 min-w-[140px]">Mulai</th>
                            <th class="px-5 py-3.5 min-w-[140px]">Selesai</th>
                            <th class="px-5 py-3.5 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($events as $event)
                            <tr class="hover:bg-slate-50/60 transition duration-150">

                                <td class="px-5 py-3.5 text-center">
                                    @if ($event->status == 'buka')
                                        <button onclick="openStatusModal({{ $event->id }}, 'tutup')"
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">
                                            <i class="ph ph-check-circle"></i> Buka
                                        </button>
                                    @elseif ($event->status == 'tutup')
                                        <button onclick="openStatusModal({{ $event->id }}, 'buka')"
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-100 text-rose-700 hover:bg-rose-200 transition">
                                            <i class="ph ph-prohibit"></i> Tutup
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-500">
                                            <i class="ph ph-pencil-simple-line"></i> Draft
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5">
                                    <a href="{{ route('superadmin.detail.event', $event->id) }}"
                                       class="font-semibold text-[#00509d] hover:underline">
                                        {{ $event->title }}
                                    </a>
                                </td>

                                <td class="px-5 py-3.5 text-center text-slate-600 text-xs font-medium">
                                    {{ $event->kuota ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-slate-600 text-xs">
                                    {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') }}
                                    <span class="text-slate-400">{{ $event->jam_mulai }}</span>
                                </td>

                                <td class="px-5 py-3.5 text-slate-600 text-xs">
                                    @if ($event->tgl_akhir)
                                        {{ \Carbon\Carbon::parse($event->tgl_akhir)->format('d M Y') }}
                                        <span class="text-slate-400">{{ $event->jam_akhir }}</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('superadmin.detail.event', $event->id) }}"
                                           title="Lihat Detail"
                                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#00509d] text-slate-600 hover:text-white flex items-center justify-center transition">
                                            <i class="ph ph-eye text-base"></i>
                                        </a>
                                        <a href="{{ route('superadmin.edit.event', $event->id) }}"
                                           title="Edit Event"
                                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                                            <i class="ph ph-pencil-simple text-base"></i>
                                        </a>
                                        <form action="{{ route('superadmin.event.destroy', $event->id) }}" method="post"
                                              onsubmit="return confirm('Yakin ingin menghapus event ini? Data tidak bisa dikembalikan!')">
                                            @csrf @method('delete')
                                            <button type="submit"
                                                    title="Hapus Event"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada data event
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $events->links() }}
                </div>
            @endif
        </div>

        <!-- Status Modal -->
        <div id="statusModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md border border-slate-100">
                <h2 class="text-base font-semibold text-slate-800 mb-1" id="modalTitle">Ubah Status Event</h2>
                <p id="modalMessage" class="mb-5 text-sm text-slate-500"></p>

                <form id="statusForm" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" id="statusInput">

                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" onclick="closeStatusModal()"
                                class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-sm font-semibold transition w-full sm:w-auto">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white rounded-xl text-sm font-semibold transition w-full sm:w-auto">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

    <script>
        function openStatusModal(id, status) {
            const modal = document.getElementById('statusModal');
            const title = document.getElementById('modalTitle');
            const msg = document.getElementById('modalMessage');
            const statusInput = document.getElementById('statusInput');
            const form = document.getElementById('statusForm');

            form.action = `/super_admin/events/status/${id}`;
            statusInput.value = status;

            if (status === 'tutup') {
                title.textContent = "Tutup Event?";
                msg.textContent = "Event akan ditutup dan tidak bisa lagi menerima pendaftaran.";
            } else {
                title.textContent = "Buka Event?";
                msg.textContent = "Event akan dibuka kembali dan bisa menerima pendaftaran.";
            }

            modal.classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }
    </script>

@endsection
