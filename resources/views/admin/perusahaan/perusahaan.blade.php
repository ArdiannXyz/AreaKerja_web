@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- HEADER TOP BAR -->
        <header class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-buildings text-[#00509d] text-2xl"></i> Data Perusahaan
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Kelola dan verifikasi data perusahaan mitra terdaftar</p>
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

            <!-- TAB MENU -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <a href="{{ route('admin.perusahaan') }}"
                    class="{{ request()->is('admin/perusahaan*') ? 'bg-[#00509d] text-white border-[#00509d] shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
                    <i class="ph ph-buildings mr-1"></i> Perusahaan
                </a>
                <a href="{{ route('admin.recruitment.perusahaan') }}"
                    class="{{ request()->is('admin/recruitment/perusahaan*') ? 'bg-[#00509d] text-white border-[#00509d] shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
                    <i class="ph ph-briefcase mr-1"></i> Recruitment
                </a>
                <a href="{{ route('admin.talent-hunter') }}"
                    class="{{ request()->is('admin/talent/hunter*') ? 'bg-[#00509d] text-white border-[#00509d] shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} px-5 py-2.5 text-sm font-semibold border rounded-xl transition whitespace-nowrap">
                    <i class="ph ph-crosshair mr-1"></i> Talent Hunter
                </a>
            </div>

            <!-- SEARCH -->
            <div class="relative w-full md:w-80" x-data="{
                open: false,
                query: '{{ $search }}',
                recommendations: [
                    { label: 'Teknologi', category: 'Sektor', icon: 'ph-cpu' },
                    { label: 'Pemasaran', category: 'Sektor', icon: 'ph-megaphone' },
                    { label: 'Keuangan', category: 'Sektor', icon: 'ph-coins' },
                    { label: 'Kesehatan', category: 'Sektor', icon: 'ph-first-aid' },
                    { label: 'Pendidikan', category: 'Sektor', icon: 'ph-graduation-cap' },
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
                    $nextTick(() => { $refs.perusahaanSearchForm.submit(); });
                }
            }" @click.outside="open = false">

                <form x-ref="perusahaanSearchForm" action="{{ route('admin.perusahaan') }}" method="GET" autocomplete="off" class="flex items-center gap-2 w-full">
                    <div class="h-10 flex items-center w-full bg-white rounded-xl overflow-hidden border border-slate-200 focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition shadow-xs">
                        <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-base leading-none"></i>
                        <input type="text" name="search" x-model="query"
                            @focus="open = true"
                            @input="open = true"
                            autocomplete="new-password"
                            data-lpignore="true"
                            data-form-type="other"
                            placeholder="Cari nama perusahaan..."
                            class="flex-1 h-full px-3 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                            style="border: none !important; outline: none !important; box-shadow: none !important;">
                        <template x-if="query.length > 0">
                            <a href="{{ route('admin.perusahaan') }}"
                               @click.prevent="query = ''; open = false; window.location.href = '{{ route('admin.perusahaan') }}';"
                               class="w-5 h-5 rounded-full bg-slate-200 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center mr-2 flex-shrink-0 transition cursor-pointer"
                               title="Hapus pencarian">
                                <i class="ph ph-x text-[10px] font-bold"></i>
                            </a>
                        </template>
                    </div>
                    <button type="submit"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold px-4 h-10 rounded-xl transition shadow-xs flex-shrink-0 flex items-center gap-1.5">
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
                <table class="min-w-[950px] w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">
                            <th class="px-5 py-4 w-16">ID</th>
                            <th class="px-5 py-4 text-left">Perusahaan</th>
                            <th class="px-5 py-4 text-left">Telepon</th>
                            <th class="px-5 py-4 text-left">Alamat</th>
                            <th class="px-5 py-4">Status Akun</th>
                            <th class="px-5 py-4">Verifikasi</th>
                            <th class="px-5 py-4 text-center whitespace-nowrap min-w-[140px]">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse ($perusahaan as $p)
                            <tr class="hover:bg-blue-50/40 transition {{ $p->user->status == 1 ? 'opacity-60 bg-slate-50/50' : '' }}">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-500">{{ $p->id }}</td>

                                <td class="px-5 py-3.5 text-left">
                                    <div class="flex items-center gap-3">
                                        @if ($p->img_profile)
                                            <img src="{{ asset('storage/' . $p->img_profile) }}" alt="Logo" class="w-9 h-9 rounded-xl object-contain border border-slate-200 p-0.5 bg-white">
                                        @else
                                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#00509d] to-[#0077b6] text-white font-bold flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
                                                {{ strtoupper(substr($p->nama_perusahaan ?? 'P', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.perusahaan.detail', $p->id) }}"
                                                class="font-bold text-slate-900 hover:text-[#00509d] block leading-snug transition">
                                                {{ $p->nama_perusahaan ?? $p->user->username }}
                                            </a>
                                            <span class="text-xs text-slate-400 font-normal">
                                                {{ $p->user->email }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-3.5 text-left text-slate-600 text-xs">
                                    {{ $p->telepon_perusahaan ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-left text-slate-600 text-xs max-w-xs truncate">
                                    {{ $p->kota ?? $p->alamat ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    @if ($p->user->status == 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                            Dibekukan
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $p->verification_status === 'approved'
                                            ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'
                                            : ($p->verification_status === 'rejected'
                                                ? 'bg-rose-50 text-rose-700 border border-rose-200/60'
                                                : 'bg-amber-50 text-amber-700 border border-amber-200/60') }}">
                                        {{ ucfirst($p->verification_status ?? 'Pending') }}
                                    </span>
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    <div class="inline-flex items-center gap-1.5 whitespace-nowrap justify-center">
                                        <!-- Detail Button -->
                                        <a href="{{ route('admin.perusahaan.detail', $p->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white transition shadow-xs flex-shrink-0"
                                            title="Lihat Detail">
                                            <i class="ph ph-eye text-base"></i>
                                        </a>

                                        <!-- Freeze / Unfreeze -->
                                        @if ($p->user->status === 0)
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 transition shadow-xs open-freeze-modal flex-shrink-0"
                                                title="Bekukan Akun" data-id="{{ $p->user->id }}">
                                                <i class="ph ph-prohibit text-base"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200/80 transition shadow-xs open-unfreeze-modal flex-shrink-0"
                                                title="Aktifkan Kembali" data-id="{{ $p->user->id }}">
                                                <i class="ph ph-lock-key-open text-base"></i>
                                            </button>
                                        @endif

                                        <!-- Approve / Reject Verification -->
                                        @if ($p->verification_status !== 'approved')
                                            <form method="POST" action="{{ route('admin.perusahaan.approve', $p->id) }}" class="inline m-0 p-0">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-xs flex-shrink-0"
                                                    title="Setujui Verifikasi">
                                                    <i class="ph ph-check text-base font-bold"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if ($p->verification_status !== 'rejected')
                                            <button type="button" onclick="openRejectModal({{ $p->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 transition shadow-xs flex-shrink-0"
                                                title="Tolak Verifikasi">
                                                <i class="ph ph-x text-base font-bold"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <i class="ph ph-buildings text-4xl mb-2 block"></i>
                                    Belum ada data perusahaan terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Modal Reject Verif Perusahaan -->
    <div id="modalReject" class="fixed inset-0 bg-slate-950/40 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 border border-slate-100 shadow-2xl">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-x-circle text-3xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 text-center mb-1">Tolak Verifikasi Perusahaan</h3>
            <p class="text-xs text-slate-500 text-center mb-4 leading-relaxed">Berikan catatan atau alasan penolakan verifikasi dokumen perusahaan ini.</p>

            <form method="POST" id="rejectForm">
                @csrf
                <textarea name="note" class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] outline-none transition"
                    rows="3" placeholder="Contoh: Dokumen SIUP/NIB belum valid atau kurang jelas..."></textarea>

                <div class="flex items-center gap-3 mt-4">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition">
                        Tolak Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Freeze -->
    <div id="confirmModal" class="fixed inset-0 hidden bg-slate-950/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full border border-slate-100 shadow-2xl">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-warning-circle text-3xl"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Bekukan Akun Perusahaan?</h3>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">Perusahaan ini tidak akan dapat masuk ke sistem atau memasang lowongan pekerjaan sampai akun diaktifkan kembali.</p>

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
            <p class="text-xs text-slate-500 text-center mb-4 leading-relaxed">Silakan tuliskan alasan pembekuan akun perusahaan ini.</p>

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
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">Perusahaan akan dapat kembali mengakses akun dan mengelola lowongan pekerjaan.</p>

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

        function openRejectModal(id) {
            const modal = document.getElementById('modalReject');
            const form = document.getElementById('rejectForm');
            form.action = `/admin/perusahaan/reject/${id}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            const modal = document.getElementById('modalReject');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
