@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 sm:gap-0">
            <h1 class="text-2xl font-medium break-words">
                Data Panggilan
            </h1>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-3 w-full sm:w-auto">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')
                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>


        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap items-center gap-2 sm:gap-4 w-full sm:w-auto">
                <a href="{{ route('superadmin.add.user.createForm') }}"
                    class="bg-blue-700 hover:bg-blue-800 border border-blue-800 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center flex-shrink-0">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.35156 10.6295H19.9094M10.6305 1.35059V19.9084" stroke="white" stroke-width="2.65112"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <button
                    class="bg-white hover:bg-gray-100 border border-blue-800 text-blue-800 px-4 py-3 rounded-lg flex-shrink-0">
                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.99037 14.5893H12.1143V12.2695H7.99037V14.5893ZM0.773438 0.670898V2.99063H19.3313V0.670898H0.773438ZM3.86641 8.78995H16.2383V6.47022H3.86641V8.78995Z"
                            fill="#00509d" />
                    </svg>
                </button>

                <div class="relative inline-block w-full sm:w-48">
                    <!-- Select utama -->
                    <button id="dropdownButton"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 border border-blue-700 rounded-md flex justify-between items-center focus:outline-none truncate">
                        <span id="dropdownText" class="truncate">Pilih Opsi</span>
                        <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownMenu"
                        class="absolute hidden mt-2 w-full bg-white rounded-md shadow-lg overflow-hidden z-10">
                        <ul class="text-blue-700">
                            <li>
                                <a href="{{ route('superadmin.perusahaan') }}"
                                    class="block px-4 py-2 hover:bg-blue-700 hover:text-white transition truncate">Perusahaan</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.recruitment.perusahaan') }}"
                                    class="block px-4 py-2 hover:bg-blue-700 hover:text-white transition truncate">Recruitment</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.talent-hunter') }}"
                                    class="block px-4 py-2 hover:bg-blue-700 hover:text-white transition truncate">Talent
                                    Hunter</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.panggilan') }}"
                                    class="block px-4 py-2 hover:bg-blue-700 hover:text-white transition truncate">Panggilan</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="relative w-full sm:w-80" x-data="{
                open: false,
                query: '{{ $search ?? '' }}',
                recommendations: [
                    { label: 'Jawa Timur', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Jawa Barat', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'DKI Jakarta', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Jawa Tengah', category: 'Wilayah', icon: 'ph-map-pin' },
                    { label: 'Disetujui', category: 'Status', icon: 'ph-check-circle' },
                    { label: 'Pending', category: 'Status', icon: 'ph-clock' },
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
                    $nextTick(() => { $refs.panggilanSearchForm.submit(); });
                }
            }" @click.outside="open = false">
                <form x-ref="panggilanSearchForm" action="{{ route('superadmin.panggilan') }}" method="GET" autocomplete="off" class="flex items-center w-full">
                    <div class="flex items-center w-full bg-slate-100 rounded-xl overflow-hidden border border-slate-200 focus-within:bg-white focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                        <i class="ph ph-magnifying-glass text-slate-400 ml-3.5 flex-shrink-0 text-sm"></i>
                        <input type="text" name="search" x-model="query"
                            @focus="open = true" @input="open = true"
                            autocomplete="off" placeholder="Cari nama perusahaan..."
                            class="flex-1 px-2.5 py-2.5 text-sm bg-transparent border-0 border-none outline-none ring-0 focus:ring-0 focus:outline-none focus:border-transparent text-slate-700 placeholder-slate-400 min-w-0"
                            style="border: none !important; outline: none !important; box-shadow: none !important;">
                        <template x-if="query.length > 0">
                            <a href="{{ route('superadmin.panggilan') }}"
                               @click.prevent="query = ''; open = false; window.location.href = '{{ route('superadmin.panggilan') }}';"
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



        <!-- Table Responsif -->
        <div class="overflow-x-auto rounded-2xl border border-gray-300">
            <table class="min-w-full text-left border-collapse">
                <thead class="text-center">
                    <tr class="border-b-[2px] border-gray-300">
                        <th class="p-4 sm:p-7 font-medium">ID</th>
                        <th class="p-4 sm:p-7 font-medium">Nama Perusahaan</th>
                        <th class="p-4 sm:p-7 font-medium">Email</th>
                        <th class="p-4 sm:p-7 font-medium">Telepon</th>
                        <th class="p-4 sm:p-7 font-medium">Alamat</th>
                        <th class="p-4 sm:p-7 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($perusahaans as $p)
                        @php
                            $alamat = $p->alamat ?? $p->kota ?? '-';
                        @endphp
                        <tr class="border-b-[2px] border-gray-300">
                            <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-normal break-words max-w-[150px]">
                                {{ str_pad($p->id, 8, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-normal break-words max-w-[200px]">
                                {{ $p->nama_perusahaan ?? $p->user->username }}
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-normal break-words max-w-[200px]">
                                {{ $p->user->email }}
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-normal break-words max-w-[150px]">
                                {{ $p->telepon_perusahaan }}
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-left whitespace-normal break-words max-w-[250px]">
                                {{ is_object($alamat) ? ($alamat->alamat ?? '-') : $alamat }}
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-center">
                                <a href="{{ route('superadmin.panggilan.list', $p->id) }}"
                                    class="inline-flex flex-wrap items-center justify-center gap-1 sm:gap-2 bg-blue-100 text-blue-900 hover:bg-[#00509d]-200 
        border border-blue-600 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 8h10M7 12h10M7 16h10" />
                                    </svg>
                                    <span class="truncate max-w-[100px] sm:max-w-[150px]">List Nama Kandidat</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

    <script>
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownText = document.getElementById('dropdownText');

        // Toggle dropdown
        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Ganti teks tombol saat klik opsi
        dropdownMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', (e) => {
                dropdownText.textContent = link.textContent; // ubah teks tombol
                dropdownMenu.classList.add('hidden'); // tutup dropdown
                // Navigasi tetap terjadi karena tag <a> ada href-nya
            });
        });

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', (e) => {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>
@endsection
