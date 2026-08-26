@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64" x-data="{
        openNotif: false,
        openAllNotif: false
    }">
        <main class="flex-1 p-6 bg-white overflow-y-auto">
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">

                <!-- Judul Halaman -->
                <h1 class="text-2xl font-bold text-gray-800">
                    Tips Kerja
                </h1>

                <!-- Profile & Header Right -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <!-- Profile -->
                    <div class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl w-max max-w-full overflow-hidden">
                        <a href="#" class="shrink-0">
                            @if (Auth::user()?->avatar)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full"
                                    src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
                            @else
                                <img id="pu" class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'Admin') }}&background=random&color=fff&size=128">
                            @endif
                        </a>

                        <div class="text-sm min-w-0">
                            <span class="font-semibold block whitespace-normal break-words">
                                {{ Auth::user()->username }}
                            </span>
                            <p class="text-gray-500 text-sm whitespace-normal break-words">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Main Content --}}
            <div class="flex justify-center py-3">
                <div class="w-full">

                    {{-- Tab Headers & Create Button --}}
                    <div class="flex flex-wrap justify-between items-center gap-3 mb-6 border-b border-gray-200 pb-3">
                        <div class="flex gap-4 text-sm font-medium">
                            <button type="button" id="btn_all" class="pb-2 border-b-2 border-orange-500 font-bold text-orange-600 cursor-pointer">
                                Semua ({{ $all }})
                            </button>
                            <button type="button" id="btn_terbit" class="pb-2 text-gray-600 hover:text-orange-500 cursor-pointer">
                                Telah Terbit ({{ $terbit }})
                            </button>
                            <button type="button" id="btn_blmterbit" class="pb-2 text-gray-600 hover:text-orange-500 cursor-pointer">
                                Draf / Belum Terbit ({{ $noterbit }})
                            </button>
                        </div>

                        <a href="{{ route('admin.tips-kerja.createForm') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Buat Post Baru
                        </a>
                    </div>

                    {{-- Search Bar --}}
                    <div class="flex justify-end items-center mb-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div class="flex items-center gap-2 w-full sm:w-80">
                            <input id="search_input" type="text" onkeyup="searchTable()" placeholder="Cari judul atau penulis..."
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white w-full focus:ring-2 focus:ring-orange-500 focus:outline-none">
                            <button type="button" onclick="searchTable()"
                                class="bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm shrink-0">
                                Cari
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200">

                        {{-- TAB 1: SEMUA (DEFAULT VISIBLE) --}}
                        <div id="semua" class="w-full">
                            <table class="w-full text-sm text-left text-gray-700 min-w-[700px]">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="px-4 py-3.5 font-semibold">Judul Artikel</th>
                                        <th class="px-4 py-3.5 font-semibold">Penulis</th>
                                        <th class="px-4 py-3.5 font-semibold text-center">Status</th>
                                        <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($semua as $s)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3.5 font-medium text-gray-900 break-words">
                                                {{ $s->title }}
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-600">
                                                {{ $s->penulis ?? 'Admin' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                @if ($s->status == 'terbit')
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-bold">Terbit</span>
                                                @else
                                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded-full font-bold">Draf</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-500 text-xs">
                                                {{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <form action="{{ route('admin.tips-kerja.toggleStatus', $s->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        @if ($s->status == 'terbit')
                                                            <button type="submit"
                                                                class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition"
                                                                title="Ubah ke Draf">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                                Drafkan
                                                            </button>
                                                        @else
                                                            <button type="submit"
                                                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition"
                                                                title="Terbitkan Artikel">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                Terbitkan
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <a href="{{ route('admin.tips-kerja.edit', $s->id) }}"
                                                        class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" onclick="confirmDeleteSingle({{ $s->id }})"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                Tidak ada data tips kerja ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- TAB 2: SUDAH TERBIT --}}
                        <div id="sudah_terbit" class="w-full hidden">
                            <table class="w-full text-sm text-left text-gray-700 min-w-[700px]">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="px-4 py-3.5 font-semibold">Judul Artikel</th>
                                        <th class="px-4 py-3.5 font-semibold">Penulis</th>
                                        <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($sudah_terbit as $s)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3.5 font-medium text-gray-900 break-words">
                                                {{ $s->title }}
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-600">
                                                {{ $s->penulis ?? 'Admin' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-500 text-xs">
                                                {{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <form action="{{ route('admin.tips-kerja.toggleStatus', $s->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition"
                                                            title="Ubah ke Draf">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                            Drafkan
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.tips-kerja.edit', $s->id) }}"
                                                        class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" onclick="confirmDeleteSingle({{ $s->id }})"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                Belum ada artikel yang diterbitkan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- TAB 3: BELUM TERBIT --}}
                        <div id="belum_terbit" class="w-full hidden">
                            <table class="w-full text-sm text-left text-gray-700 min-w-[700px]">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="px-4 py-3.5 font-semibold">Judul Artikel</th>
                                        <th class="px-4 py-3.5 font-semibold">Penulis</th>
                                        <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($belum_terbit as $s)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3.5 font-medium text-gray-900 break-words">
                                                {{ $s->title }}
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-600">
                                                {{ $s->penulis ?? 'Admin' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-gray-500 text-xs">
                                                {{ $s->created_at ? $s->created_at->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <form action="{{ route('admin.tips-kerja.toggleStatus', $s->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition"
                                                            title="Terbitkan Artikel">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            Terbitkan
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.tips-kerja.edit', $s->id) }}"
                                                        class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Edit
                                                    </a>
                                                    <button type="button" onclick="confirmDeleteSingle({{ $s->id }})"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                Tidak ada draf artikel.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Form Delete Single Hidden -->
                    <form id="singleDeleteForm" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>
            </div>
        </main>
    </div>

    <!-- Script Tab & Actions -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btnAll = document.getElementById("btn_all");
            const btnTerbit = document.getElementById("btn_terbit");
            const btnBlmterbit = document.getElementById("btn_blmterbit");

            const tabSemua = document.getElementById('semua');
            const tabTerbit = document.getElementById('sudah_terbit');
            const tabBlmterbit = document.getElementById('belum_terbit');

            function resetTabs() {
                tabSemua.classList.add('hidden');
                tabTerbit.classList.add('hidden');
                tabBlmterbit.classList.add('hidden');

                btnAll.className = "pb-2 text-gray-600 hover:text-orange-500 cursor-pointer";
                btnTerbit.className = "pb-2 text-gray-600 hover:text-orange-500 cursor-pointer";
                btnBlmterbit.className = "pb-2 text-gray-600 hover:text-orange-500 cursor-pointer";
            }

            btnAll.addEventListener("click", () => {
                resetTabs();
                tabSemua.classList.remove('hidden');
                btnAll.className = "pb-2 border-b-2 border-orange-500 font-bold text-orange-600 cursor-pointer";
            });

            btnTerbit.addEventListener("click", () => {
                resetTabs();
                tabTerbit.classList.remove('hidden');
                btnTerbit.className = "pb-2 border-b-2 border-orange-500 font-bold text-orange-600 cursor-pointer";
            });

            btnBlmterbit.addEventListener("click", () => {
                resetTabs();
                tabBlmterbit.classList.remove('hidden');
                btnBlmterbit.className = "pb-2 border-b-2 border-orange-500 font-bold text-orange-600 cursor-pointer";
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
