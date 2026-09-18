@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">
        {{-- Topbar Header --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-lightbulb text-[#00509d] text-2xl"></i> Tips Kerja
                </h1>
                <p class="text-xs font-semibold text-slate-500 mt-1">Kelola artikel panduan karir, tips kerja, dan publikasi</p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                <i class="ph ph-check-circle text-lg text-emerald-600 flex-shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                <i class="ph ph-warning-circle text-lg text-rose-600 flex-shrink-0"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Content Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6">
            {{-- Tabs Filter & Action Toolbar --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                <div class="inline-flex p-1 bg-slate-100 rounded-xl space-x-1 text-xs font-semibold overflow-x-auto">
                    <button type="button" id="btn_all"
                        class="tab-btn px-4 py-2 rounded-lg transition duration-150 bg-[#00509d] text-white font-semibold shadow-xs">
                        Semua ({{ $all }})
                    </button>
                    <button type="button" id="btn_terbit"
                        class="tab-btn px-4 py-2 rounded-lg transition duration-150 text-slate-600 hover:text-slate-900 font-medium">
                        Telah Terbit ({{ $terbit }})
                    </button>
                    <button type="button" id="btn_blmterbit"
                        class="tab-btn px-4 py-2 rounded-lg transition duration-150 text-slate-600 hover:text-slate-900 font-medium">
                        Draf ({{ $noterbit }})
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Search Input --}}
                    <div class="flex items-center bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition w-full sm:w-64 h-10">
                        <i class="ph ph-magnifying-glass text-slate-400 ml-3 flex-shrink-0 text-sm"></i>
                        <input id="search_input" type="text" onkeyup="searchTable()" placeholder="Cari judul/penulis..."
                            autocomplete="off"
                            class="flex-1 px-2.5 py-1.5 text-xs bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400"
                            style="border: none !important; outline: none !important; box-shadow: none !important;">
                        <button type="button" onclick="document.getElementById('search_input').value=''; searchTable();"
                            class="w-5 h-5 rounded-full bg-slate-200 hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center mr-2 flex-shrink-0 transition cursor-pointer"
                            title="Hapus pencarian">
                            <i class="ph ph-x text-[10px]"></i>
                        </button>
                    </div>

                    <a href="{{ route('admin.tips-kerja.createForm') }}"
                        class="h-10 inline-flex items-center justify-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold text-xs px-4 rounded-xl transition duration-150 shadow-xs flex-shrink-0">
                        <i class="ph ph-plus-circle text-base"></i>
                        Buat Post Baru
                    </a>
                </div>
            </div>

            {{-- Table Wrapper --}}
            <div class="mt-5 rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">

                {{-- TAB 1: SEMUA (DEFAULT VISIBLE) --}}
                <div id="semua" class="w-full">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-4 min-w-[220px]">Judul Artikel</th>
                                    <th class="px-5 py-4 min-w-[130px]">Penulis</th>
                                    <th class="px-5 py-4 text-center min-w-[100px]">Status</th>
                                    <th class="px-5 py-4 text-center min-w-[120px]">Tanggal</th>
                                    <th class="px-5 py-4 text-center min-w-[160px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse ($semua as $s)
                                    <tr class="hover:bg-blue-50/40 transition">
                                        <td class="px-5 py-3.5 font-semibold text-slate-800 break-words">
                                            {{ $s->title }}
                                        </td>
                                        <td class="px-5 py-3.5 text-slate-600 font-medium">
                                            {{ $s->penulis ?? 'Admin' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            @if ($s->status == 'terbit')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terbit
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200/60">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draf
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-slate-500 font-medium">
                                            {{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <form action="{{ route('admin.tips-kerja.toggleStatus', $s->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    @if ($s->status == 'terbit')
                                                        <button type="submit"
                                                            class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-700 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                            title="Ubah ke Draf">
                                                            <i class="ph ph-prohibit text-base"></i>
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-200/60 flex items-center justify-center transition"
                                                            title="Terbitkan Artikel">
                                                            <i class="ph ph-check-circle text-base"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                                <a href="{{ route('admin.tips-kerja.edit', $s->id) }}"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-500 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                    title="Edit Artikel">
                                                    <i class="ph ph-pencil-simple text-base"></i>
                                                </a>
                                                <button type="button" onclick="confirmDeleteSingle({{ $s->id }})"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                    title="Hapus Artikel">
                                                    <i class="ph ph-trash text-base"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                                            <i class="ph ph-article text-4xl mb-2 block"></i>
                                            Tidak ada data tips kerja ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: SUDAH TERBIT --}}
                <div id="sudah_terbit" class="w-full hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-4 min-w-[220px]">Judul Artikel</th>
                                    <th class="px-5 py-4 min-w-[130px]">Penulis</th>
                                    <th class="px-5 py-4 text-center min-w-[120px]">Tanggal</th>
                                    <th class="px-5 py-4 text-center min-w-[160px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse ($sudah_terbit as $s)
                                    <tr class="hover:bg-blue-50/40 transition">
                                        <td class="px-5 py-3.5 font-semibold text-slate-800 break-words">
                                            {{ $s->title }}
                                        </td>
                                        <td class="px-5 py-3.5 text-slate-600 font-medium">
                                            {{ $s->penulis ?? 'Admin' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-slate-500 font-medium">
                                            {{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <form action="{{ route('admin.tips-kerja.toggleStatus', $s->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-700 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                        title="Ubah ke Draf">
                                                        <i class="ph ph-prohibit text-base"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.tips-kerja.edit', $s->id) }}"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-500 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                    title="Edit Artikel">
                                                    <i class="ph ph-pencil-simple text-base"></i>
                                                </a>
                                                <button type="button" onclick="confirmDeleteSingle({{ $s->id }})"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                    title="Hapus Artikel">
                                                    <i class="ph ph-trash text-base"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-slate-400 text-xs">
                                            <i class="ph ph-article text-4xl mb-2 block"></i>
                                            Belum ada artikel yang diterbitkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 3: BELUM TERBIT --}}
                <div id="belum_terbit" class="w-full hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-4 min-w-[220px]">Judul Artikel</th>
                                    <th class="px-5 py-4 min-w-[130px]">Penulis</th>
                                    <th class="px-5 py-4 text-center min-w-[120px]">Tanggal</th>
                                    <th class="px-5 py-4 text-center min-w-[160px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse ($belum_terbit as $s)
                                    <tr class="hover:bg-blue-50/40 transition">
                                        <td class="px-5 py-3.5 font-semibold text-slate-800 break-words">
                                            {{ $s->title }}
                                        </td>
                                        <td class="px-5 py-3.5 text-slate-600 font-medium">
                                            {{ $s->penulis ?? 'Admin' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-slate-500 font-medium">
                                            {{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <form action="{{ route('admin.tips-kerja.toggleStatus', $s->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-200/60 flex items-center justify-center transition"
                                                        title="Terbitkan Artikel">
                                                        <i class="ph ph-check-circle text-base"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.tips-kerja.edit', $s->id) }}"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-500 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                    title="Edit Artikel">
                                                    <i class="ph ph-pencil-simple text-base"></i>
                                                </a>
                                                <button type="button" onclick="confirmDeleteSingle({{ $s->id }})"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white flex items-center justify-center transition"
                                                    title="Hapus Artikel">
                                                    <i class="ph ph-trash text-base"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-slate-400 text-xs">
                                            <i class="ph ph-article text-4xl mb-2 block"></i>
                                            Tidak ada draf artikel.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Form Delete Single Hidden -->
            <form id="singleDeleteForm" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>

    <!-- Script Tab & Actions -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btnAll = document.getElementById("btn_all");
            const btnTerbit = document.getElementById("btn_terbit");
            const btnBlmterbit = document.getElementById("btn_blmterbit");

            const tabSemua = document.getElementById('semua');
            const tabTerbit = document.getElementById('sudah_terbit');
            const tabBlmterbit = document.getElementById('belum_terbit');

            const activeClass = "tab-btn px-4 py-2 rounded-lg transition duration-150 bg-[#00509d] text-white font-semibold shadow-xs";
            const inactiveClass = "tab-btn px-4 py-2 rounded-lg transition duration-150 text-slate-600 hover:text-slate-900 font-medium";

            function resetTabs() {
                tabSemua.classList.add('hidden');
                tabTerbit.classList.add('hidden');
                tabBlmterbit.classList.add('hidden');

                btnAll.className = inactiveClass;
                btnTerbit.className = inactiveClass;
                btnBlmterbit.className = inactiveClass;
            }

            btnAll.addEventListener("click", () => {
                resetTabs();
                tabSemua.classList.remove('hidden');
                btnAll.className = activeClass;
            });

            btnTerbit.addEventListener("click", () => {
                resetTabs();
                tabTerbit.classList.remove('hidden');
                btnTerbit.className = activeClass;
            });

            btnBlmterbit.addEventListener("click", () => {
                resetTabs();
                tabBlmterbit.classList.remove('hidden');
                btnBlmterbit.className = activeClass;
            });
        });

        function confirmDeleteSingle(id) {
            if (confirm('Apakah Anda yakin ingin menghapus tips kerja ini?')) {
                let form = document.getElementById('singleDeleteForm');
                form.action = "{{ url('/admin/tips/kerja') }}/" + id;
                form.submit();
            }
        }

        function searchTable() {
            let input = document.getElementById("search_input").value.toLowerCase();
            let visibleContainer = document.querySelector('#semua:not(.hidden), #sudah_terbit:not(.hidden), #belum_terbit:not(.hidden)');
            if (!visibleContainer) return;

            let rows = visibleContainer.querySelectorAll("tbody tr");
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }
    </script>
@endsection
