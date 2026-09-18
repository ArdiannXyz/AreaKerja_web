@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-users text-[#00509d] text-2xl"></i> Data Pelamar
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Kelola data pelamar non-kandidat (publik)</p>
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
                    $nextTick(() => { $refs.nonKandidatSearchForm.submit(); });
                }
            }" @click.outside="open = false">

                <form x-ref="nonKandidatSearchForm" action="{{ route('admin.non-kandidat') }}" method="GET" autocomplete="off" class="flex items-center gap-2 w-full">
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
                            <a href="{{ route('admin.non-kandidat') }}"
                               @click.prevent="query = ''; open = false; filterTable(''); window.location.href = '{{ route('admin.non-kandidat') }}';"
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
                            <th class="px-5 py-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700" id="pelamar-table-body">
                        @forelse ($pelamar as $item)
                            <tr class="pelamar-row hover:bg-blue-50/40 transition {{ $item->user->status == 1 ? 'opacity-60 bg-slate-50/50' : '' }}">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-500">
                                    {{ $item->id }}
                                </td>
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
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('admin.detail.non.kandidat', $item->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white transition shadow-xs"
                                            title="Lihat Detail">
                                            <i class="ph ph-eye text-base"></i>
                                        </a>
                                        @if ($item->user->status === 0)
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 transition shadow-xs open-freeze-modal"
                                                title="Bekukan Akun" data-id="{{ $item->user->id }}">
                                                <i class="ph ph-prohibit text-base"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200/80 transition shadow-xs open-unfreeze-modal"
                                                title="Aktifkan Kembali" data-id="{{ $item->user->id }}">
                                                <i class="ph ph-lock-key-open text-base"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                    <i class="ph ph-users text-4xl mb-2 block"></i>
                                    Belum ada data non-kandidat tersedia.
                                </td>
                            </tr>
                        @endforelse
                        <tr id="pelamar-empty-search-row" style="display: none;">
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <i class="ph ph-magnifying-glass text-3xl mb-2 block text-slate-300"></i>
                                Tidak ada non-kandidat yang cocok dengan pencarian.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Modal Konfirmasi Freeze -->
    <div id="confirmModal" class="fixed inset-0 hidden bg-slate-950/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full border border-slate-100 shadow-2xl">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-warning-circle text-3xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Bekukan Akun Pelamar?</h3>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">Pengguna tidak akan dapat masuk ke sistem sampai akun diaktifkan kembali.</p>

            <div class="flex items-center gap-3">
                <button id="cancelFreeze" type="button"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                    Batal
                </button>
                <button id="yesFreeze" type="button"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Alasan Freeze -->
    <div id="reasonModal" class="fixed inset-0 hidden bg-slate-950/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full border border-slate-100 shadow-2xl">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-note-pencil text-3xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 text-center mb-1">Alasan Pembekuan</h3>
            <p class="text-xs text-slate-500 text-center mb-4 leading-relaxed">Silakan tuliskan alasan pembekuan akun pengguna ini.</p>

            <textarea id="alasan" rows="4"
                class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] outline-none transition"
                placeholder="Masukkan alasan pembekuan akun..."></textarea>

            <div class="flex items-center gap-3 mt-4">
                <button id="cancelReason" type="button"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                    Batal
                </button>
                <button id="submitReason" type="button"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition">
                    Kirim & Bekukan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Unfreeze -->
    <div id="unfreezeModal" class="fixed inset-0 hidden bg-slate-950/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full border border-slate-100 shadow-2xl">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-lock-key-open text-3xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Aktifkan Kembali Akun?</h3>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">Pengguna akan dapat kembali mengakses akun dan melamar lowongan pekerjaan.</p>

            <div class="flex items-center gap-3">
                <button id="cancelUnfreeze" type="button"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                    Batal
                </button>
                <button id="yesUnfreeze" type="button"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition">
                    Ya, Aktifkan
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let selectedUserId = null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // === OPEN FREEZE ===
            document.querySelectorAll('.open-freeze-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedUserId = btn.dataset.id;
                    document.getElementById('confirmModal').classList.remove('hidden');
                });
            });

            document.getElementById('cancelFreeze').addEventListener('click', () => {
                document.getElementById('confirmModal').classList.add('hidden');
            });

            document.getElementById('cancelReason')?.addEventListener('click', () => {
                document.getElementById('reasonModal').classList.add('hidden');
            });

            document.getElementById('yesFreeze').addEventListener('click', () => {
                document.getElementById('confirmModal').classList.add('hidden');
                document.getElementById('reasonModal').classList.remove('hidden');
            });

            document.getElementById('submitReason').addEventListener('click', async () => {
                const alasan = document.getElementById('alasan').value.trim();
                if (!alasan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan isi alasan pembekuan terlebih dahulu.',
                        confirmButtonColor: '#00509d',
                    });
                    return;
                }

                try {
                    const response = await fetch(`/admin/user/freeze/${selectedUserId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ alasan })
                    });

                    const result = await response.json();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message || 'Akun berhasil dibekukan.',
                        confirmButtonColor: '#00509d',
                    }).then(() => location.reload());
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan: ' + error.message,
                        confirmButtonColor: '#00509d',
                    });
                }
            });

            // === OPEN UNFREEZE ===
            document.querySelectorAll('.open-unfreeze-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedUserId = btn.dataset.id;
                    document.getElementById('unfreezeModal').classList.remove('hidden');
                });
            });

            document.getElementById('cancelUnfreeze').addEventListener('click', () => {
                document.getElementById('unfreezeModal').classList.add('hidden');
            });

            document.getElementById('yesUnfreeze').addEventListener('click', async () => {
                try {
                    const response = await fetch(`/admin/user/unfreeze/${selectedUserId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    const result = await response.json();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message || 'Akun berhasil diaktifkan kembali.',
                        confirmButtonColor: '#00509d',
                    }).then(() => location.reload());
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan: ' + error.message,
                        confirmButtonColor: '#00509d',
                    });
                }
            });
        });

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
