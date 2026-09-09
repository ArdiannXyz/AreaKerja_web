@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-gray-50 overflow-y-auto min-h-screen" x-data="{ 
        openNotif: false,
        openDeleteModal: false,
        search: '',
        filterSource: 'all',
        selectedCount: 0,
        updateCount() {
            this.selectedCount = document.querySelectorAll('.itemCheckbox:checked').length;
        },
        openDeleteConfirm() {
            if (this.selectedCount === 0) return;
            this.openDeleteModal = true;
        },
        submitDelete() {
            document.getElementById('subscriberForm').submit();
        },
        toggleAll(checked) {
            document.querySelectorAll('.itemCheckbox').forEach(cb => {
                const tr = cb.closest('tr');
                if (tr && tr.style.display !== 'none') {
                    cb.checked = checked;
                }
            });
            this.updateCount();
        },
        filterRows() {
            const q = this.search.toLowerCase().trim();
            const source = this.filterSource;
            const rows = document.querySelectorAll('.subscriber-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const email = row.dataset.email.toLowerCase();
                const nama = row.dataset.nama.toLowerCase();
                const rowSource = row.dataset.source;

                const matchesSearch = !q || email.includes(q) || nama.includes(q);
                const matchesSource = (source === 'all') || (rowSource === source);

                if (matchesSearch && matchesSource) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    const cb = row.querySelector('.itemCheckbox');
                    if (cb) cb.checked = false;
                }
            });

            this.updateCount();
            const emptyEl = document.getElementById('searchEmptyState');
            if (emptyEl) {
                emptyEl.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }
    }">
        <!-- Topbar Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Email Subscribers</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola daftar email newsletter dan pelanggan berita lowongan AreaKerja</p>
            </div>

            <div class="flex items-center gap-3 self-end md:self-auto">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-sm shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Quick Stats Cards -->
        @php
            $totalCount = $subscribers->count();
            $pelamarCount = $subscribers->whereNotNull('pelamar_id')->count();
            $perusahaanCount = $subscribers->whereNotNull('perusahaan_id')->count();
            $guestCount = $subscribers->whereNull('pelamar_id')->whereNull('perusahaan_id')->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Subscribers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalCount) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dari Pelamar</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-0.5">{{ number_format($pelamarCount) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dari Perusahaan</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-0.5">{{ number_format($perusahaanCount) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tamu / Guest</p>
                    <p class="text-2xl font-bold text-slate-700 mt-0.5">{{ number_format($guestCount) }}</p>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <!-- Form Pembungkus Bulk Delete & PDF Export -->
            <form id="subscriberForm" action="{{ route('superadmin.email-subs.bulk-delete') }}" method="POST">
                @csrf
                @method('DELETE')

                <!-- Action Toolbar -->
                <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                    <!-- Left: Action Buttons -->
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <!-- Tombol Hapus Terpilih -->
                        <button type="button"
                            @click="openDeleteConfirm()"
                            :disabled="selectedCount === 0"
                            :class="selectedCount > 0 ? 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-600 hover:text-white cursor-pointer shadow-sm' : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed opacity-60'"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-sm font-semibold transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Hapus Terpilih</span>
                            <span x-show="selectedCount > 0" class="px-2 py-0.5 text-xs bg-rose-100 text-rose-700 rounded-full font-bold ml-0.5" x-text="selectedCount"></span>
                        </button>

                        <!-- Tombol Unduh PDF -->
                        <button type="submit" 
                            formaction="{{ route('superadmin.email-subscribers.pdf') }}" 
                            formmethod="GET"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50 text-[#00509d] hover:bg-[#00509d] hover:text-white text-sm font-semibold transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Unduh PDF</span>
                            <span x-show="selectedCount > 0" class="text-xs opacity-80" x-text="'(' + selectedCount + ' dipilih)'"></span>
                        </button>

                        <!-- Filter Sumber Dropdown -->
                        <div class="relative inline-block">
                            <select x-model="filterSource" @change="filterRows()"
                                class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl px-3 py-2.5 pr-8 focus:ring-2 focus:ring-[#00509d] focus:border-transparent cursor-pointer">
                                <option value="all">Semua Sumber</option>
                                <option value="pelamar">Pelamar</option>
                                <option value="perusahaan">Perusahaan</option>
                                <option value="guest">Guest / Tamu</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right: Search Input -->
                    <div class="relative w-full md:w-72">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" 
                            x-model="search" 
                            @input="filterRows()"
                            placeholder="Cari email atau nama..." 
                            class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-600 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                                <th class="py-3.5 px-4 w-12 text-center">
                                    <input type="checkbox" id="checkAll" 
                                        @change="toggleAll($el.checked)"
                                        class="w-4 h-4 rounded text-[#00509d] border-gray-300 focus:ring-[#00509d] cursor-pointer">
                                </th>
                                <th class="py-3.5 px-4">Email Subscriber</th>
                                <th class="py-3.5 px-4">Sumber</th>
                                <th class="py-3.5 px-4">Nama / Pengguna</th>
                                <th class="py-3.5 px-4 text-center">Tanggal Berlangganan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @forelse ($subscribers as $sub)
                                @php
                                    $sourceType = 'guest';
                                    $displayName = '-';
                                    if ($sub->pelamar_id) {
                                        $sourceType = 'pelamar';
                                        $displayName = $sub->pelamar->nama_pelamar ?? ($sub->pelamar->user->username ?? 'Pelamar');
                                    } elseif ($sub->perusahaan_id) {
                                        $sourceType = 'perusahaan';
                                        $displayName = $sub->perusahaan->nama_perusahaan ?? ($sub->perusahaan->user->username ?? 'Perusahaan');
                                    }
                                @endphp
                                <tr class="subscriber-row hover:bg-blue-50/40 transition-colors"
                                    data-email="{{ $sub->email }}"
                                    data-nama="{{ $displayName }}"
                                    data-source="{{ $sourceType }}">
                                    <!-- Checkbox -->
                                    <td class="py-3.5 px-4 text-center">
                                        <input type="checkbox" name="ids[]" value="{{ $sub->id }}" 
                                            @change="updateCount()"
                                            class="itemCheckbox w-4 h-4 rounded text-[#00509d] border-gray-300 focus:ring-[#00509d] cursor-pointer">
                                    </td>

                                    <!-- Email -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#00509d] flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-900 block">{{ $sub->email }}</span>
                                                <span class="text-xs text-gray-400">ID: #{{ $sub->id }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Sumber -->
                                    <td class="py-3.5 px-4">
                                        @if ($sub->pelamar_id)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                Pelamar
                                            </span>
                                        @elseif ($sub->perusahaan_id)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                Perusahaan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                                Guest
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Nama -->
                                    <td class="py-3.5 px-4 font-medium text-gray-800">
                                        {{ $displayName }}
                                    </td>

                                    <!-- Tanggal Daftar -->
                                    <td class="py-3.5 px-4 text-center text-gray-500 text-xs">
                                        {{ $sub->created_at ? $sub->created_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-base font-semibold text-gray-700">Belum Ada Subscriber</p>
                                            <p class="text-sm text-gray-400 mt-1">Daftar pelanggan newsletter akan muncul otomatis di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Search Empty State -->
                            <tr id="searchEmptyState" style="display: none;">
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-600">Tidak ada subscriber yang cocok dengan pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Info -->
                <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-2">
                    <span>Menampilkan <strong>{{ $subscribers->count() }}</strong> total data subscriber</span>
                    <span class="text-gray-400">Centang baris untuk melakukan aksi massal</span>
                </div>
            </form>
        </div>

        <!-- Modal Konfirmasi Hapus Modern -->
        <div x-show="openDeleteModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <!-- Backdrop Blur -->
            <div x-show="openDeleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                @click="openDeleteModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="openDeleteModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-md p-6 border border-gray-100">
                    
                    <!-- Close button -->
                    <button @click="openDeleteModal = false" type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <!-- Icon & Content -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4 ring-8 ring-rose-50/60">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900" id="modal-title">Hapus Subscriber?</h3>
                        
                        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus <span class="font-bold text-rose-600" x-text="selectedCount + ' subscriber'"></span> yang dipilih? Tindakan ini bersifat permanen dan tidak dapat dipulihkan.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex items-center gap-3">
                        <button type="button" 
                            @click="openDeleteModal = false"
                            class="flex-1 py-2.5 px-4 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 text-sm font-semibold transition shadow-sm">
                            Batal
                        </button>
                        <button type="button"
                            @click="submitDelete()"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Ya, Hapus Sekarang</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection


