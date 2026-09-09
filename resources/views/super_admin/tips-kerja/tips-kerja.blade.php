@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-gray-50/50 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">
        {{-- Topbar Header --}}
        <div class="flex justify-between items-center mb-6 flex-col sm:flex-row gap-4 sm:gap-0 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tips Kerja</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola artikel panduan karir, tips kerja, dan publikasi</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Content Card --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            {{-- Tabs Filter & Tombol Buat Post --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-gray-100">
                <div class="inline-flex p-1 bg-gray-100 rounded-xl space-x-1 text-xs sm:text-sm font-medium">
                    <button type="button" id="btn_all"
                        class="tab-btn px-4 py-2 rounded-lg transition duration-150 bg-[#00509d] text-white font-semibold shadow-sm">
                        Semua ({{ $all }})
                    </button>
                    <button type="button" id="btn_terbit"
                        class="tab-btn px-4 py-2 rounded-lg transition duration-150 text-gray-600 hover:text-gray-900 font-medium">
                        Telah Terbit ({{ $terbit }})
                    </button>
                    <button type="button" id="btn_blmterbit"
                        class="tab-btn px-4 py-2 rounded-lg transition duration-150 text-gray-600 hover:text-gray-900 font-medium">
                        Draf ({{ $noterbit }})
                    </button>
                </div>

                <a href="{{ route('superadmin.tips-kerja.createForm') }}"
                    class="inline-flex items-center justify-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Post Baru
                </a>
            </div>

            {{-- Toolbar Filter & Search --}}
            <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 py-4">
                {{-- Kiri: Bulk Actions --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <select id="filter_select" onchange="searchTable()"
                        class="border border-gray-200 bg-gray-50 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-[#00509d]">
                        <option value="title">Cari berdasarkan: Judul</option>
                        <option value="penulis">Cari berdasarkan: Penulis</option>
                        <option value="created_at">Cari berdasarkan: Tanggal</option>
                    </select>

                    <button type="button" onclick="setAction('update')"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition shadow-sm">
                        Terbitkan
                    </button>

                    <button type="button" onclick="setAction('delete')"
                        class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition shadow-sm">
                        Hapus
                    </button>
                </div>

                {{-- Kanan: Search Input --}}
                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <input id="search_input" type="text" onkeyup="searchTable()" placeholder="Ketik kata kunci..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#00509d]">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Form Bulk Action --}}
            <form id="bulkAction" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod">
                <input type="hidden" name="status" id="statusField">

                {{-- TAB 1: SEMUA (DEFAULT TAMPIL) --}}
                <div id="semua" class="rounded-xl overflow-hidden border border-gray-200">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200 text-xs">
                                <tr>
                                    <th class="px-4 py-3.5 w-10 text-center"><input id="checkAllSemua" type="checkbox" class="rounded border-gray-300"></th>
                                    <th class="px-4 py-3.5 min-w-[220px]">Judul Artikel</th>
                                    <th class="px-4 py-3.5 min-w-[120px]">Penulis</th>
                                    <th class="px-4 py-3.5 text-center min-w-[100px]">Status</th>
                                    <th class="px-4 py-3.5 text-center min-w-[120px]">Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse ($semua as $s)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3.5 text-center">
                                            <input name="ids[]" value="{{ $s->id }}" type="checkbox" class="rounded border-gray-300">
                                        </td>
                                        <td class="px-4 py-3.5 font-medium text-gray-900">
                                            {{ $s->title }}
                                        </td>
                                        <td class="px-4 py-3.5 text-gray-600 font-medium">{{ $s->penulis ?? 'Admin' }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            @if ($s->status == 'terbit')
                                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full bg-emerald-100 text-emerald-800">Terbit</span>
                                            @else
                                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full bg-amber-100 text-amber-800">Draf</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-center text-gray-500 font-medium">{{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">Belum ada artikel tips kerja.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: SUDAH TERBIT (HIDDEN BY DEFAULT) --}}
                <div id="sudah_terbit" class="rounded-xl overflow-hidden border border-gray-200 hidden">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200 text-xs">
                                <tr>
                                    <th class="px-4 py-3.5 w-10 text-center"><input id="checkAllTerbit" type="checkbox" class="rounded border-gray-300"></th>
                                    <th class="px-4 py-3.5 min-w-[220px]">Judul Artikel</th>
                                    <th class="px-4 py-3.5 min-w-[120px]">Penulis</th>
                                    <th class="px-4 py-3.5 text-center min-w-[100px]">Status</th>
                                    <th class="px-4 py-3.5 text-center min-w-[120px]">Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse ($sudah_terbit as $s)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3.5 text-center">
                                            <input name="ids[]" value="{{ $s->id }}" type="checkbox" class="rounded border-gray-300">
                                        </td>
                                        <td class="px-4 py-3.5 font-medium text-gray-900">{{ $s->title }}</td>
                                        <td class="px-4 py-3.5 text-gray-600 font-medium">{{ $s->penulis ?? 'Admin' }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full bg-emerald-100 text-emerald-800">Terbit</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center text-gray-500 font-medium">{{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada artikel yang telah terbit.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 3: BELUM TERBIT / DRAFT (HIDDEN BY DEFAULT) --}}
                <div id="belum_terbit" class="rounded-xl overflow-hidden border border-gray-200 hidden">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200 text-xs">
                                <tr>
                                    <th class="px-4 py-3.5 w-10 text-center"><input id="checkAllBelum" type="checkbox" class="rounded border-gray-300"></th>
                                    <th class="px-4 py-3.5 min-w-[220px]">Judul Artikel</th>
                                    <th class="px-4 py-3.5 min-w-[120px]">Penulis</th>
                                    <th class="px-4 py-3.5 text-center min-w-[100px]">Status</th>
                                    <th class="px-4 py-3.5 text-center min-w-[120px]">Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse ($belum_terbit as $s)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3.5 text-center">
                                            <input name="ids[]" value="{{ $s->id }}" type="checkbox" class="rounded border-gray-300">
                                        </td>
                                        <td class="px-4 py-3.5 font-medium text-gray-900">{{ $s->title }}</td>
                                        <td class="px-4 py-3.5 text-gray-600 font-medium">{{ $s->penulis ?? 'Admin' }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full bg-amber-100 text-amber-800">Draf</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center text-gray-500 font-medium">{{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada draf artikel.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

    {{-- Script Tab & Actions --}}
    <script>
        let activeTableId = 'semua';

        const btn_all = document.getElementById("btn_all");
        const btn_terbit = document.getElementById("btn_terbit");
        const btn_blmterbit = document.getElementById("btn_blmterbit");

        const semua = document.getElementById("semua");
        const sudah_terbit = document.getElementById("sudah_terbit");
        const belum_terbit = document.getElementById("belum_terbit");

        function setTabActive(activeBtn) {
            [btn_all, btn_terbit, btn_blmterbit].forEach(btn => {
                btn.className = "tab-btn px-4 py-2 rounded-lg transition duration-150 text-gray-600 hover:text-gray-900 font-medium";
            });
            activeBtn.className = "tab-btn px-4 py-2 rounded-lg transition duration-150 bg-[#00509d] text-white font-semibold shadow-sm";
        }

        btn_all.addEventListener("click", () => {
            setTabActive(btn_all);
            sudah_terbit.classList.add('hidden');
            belum_terbit.classList.add('hidden');
            semua.classList.remove('hidden');
            activeTableId = 'semua';
            searchTable();
        });

        btn_terbit.addEventListener("click", () => {
            setTabActive(btn_terbit);
            semua.classList.add('hidden');
            belum_terbit.classList.add('hidden');
            sudah_terbit.classList.remove('hidden');
            activeTableId = 'sudah_terbit';
            searchTable();
        });

        btn_blmterbit.addEventListener("click", () => {
            setTabActive(btn_blmterbit);
            semua.classList.add('hidden');
            sudah_terbit.classList.add('hidden');
            belum_terbit.classList.remove('hidden');
            activeTableId = 'belum_terbit';
            searchTable();
        });

        // ------------------ Bulk Action ------------------
        function setAction(action) {
            let form = document.getElementById('bulkAction');

            // Hapus checkbox dari tabel yg tidak aktif
            document.querySelectorAll('#bulkAction input[name="ids[]"]').forEach(cb => {
                if (!cb.closest(`#${activeTableId}`)) {
                    cb.remove();
                }
            });

            const checkedCount = document.querySelectorAll(`#${activeTableId} input[name="ids[]"]:checked`).length;
            if (checkedCount === 0) {
                alert("Pilih minimal satu artikel terlebih dahulu.");
                return;
            }

            if (action === 'update') {
                if (!confirm(`Terbitkan ${checkedCount} artikel yang dipilih?`)) return;
                form.action = "{{ route('superadmin.tips-kerja.update.status') }}";
                document.getElementById('formMethod').value = "PUT";
                document.getElementById('statusField').value = "terbit";
            } else if (action === 'delete') {
                if (!confirm(`Yakin ingin menghapus ${checkedCount} artikel yang dipilih?`)) return;
                form.action = "{{ route('superadmin.tips-kerja.destroy') }}";
                document.getElementById('formMethod').value = "DELETE";
            }

            form.submit();
        }

        // ---- Checkbox Select All ----
        ['checkAllSemua', 'checkAllTerbit', 'checkAllBelum'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', function() {
                    const table = document.getElementById(activeTableId);
                    if (table) {
                        table.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
                    }
                });
            }
        });

        // ------------------ Search Filter ------------------
        function searchTable() {
            let input = document.getElementById("search_input").value.toLowerCase();
            let filterBy = document.getElementById("filter_select").value;

            const colIndex = {
                "title": 1,
                "penulis": 2,
                "created_at": 4
            };

            let table = document.querySelector(`#${activeTableId} table`);
            if (!table) return;

            let rows = table.querySelectorAll("tbody tr");
            rows.forEach(row => {
                let cell = row.cells[colIndex[filterBy]];
                if (cell) {
                    let colText = cell.innerText.toLowerCase();
                    row.style.display = colText.includes(input) ? "" : "none";
                }
            });
        }
    </script>
@endsection
