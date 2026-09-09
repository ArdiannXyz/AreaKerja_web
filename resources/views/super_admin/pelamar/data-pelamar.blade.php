@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 sm:ml-64 p-6 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
            <h1 class="text-2xl font-medium truncate">
                Data Kandidat
            </h1>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')
                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <!-- Tombol & Select -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto flex-wrap">
                <!-- Tombol Add -->
                <a id="btnAdd" href="{{ route('superadmin.pelamar.create', ['kategori' => 'kandidat']) }}"
                    class="bg-blue-700 flex justify-center items-center px-3 py-2 border border-blue-700 rounded-xl text-white hover:bg-blue-800 transition flex-shrink-0">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.35156 10.6295H19.9094M10.6305 1.35059V19.9084" stroke="white" stroke-width="2.65112"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <!-- Tombol Filter -->
                <button class="bg-white border border-blue-800 text-blue-800 px-4 py-3 rounded-xl flex-shrink-0">
                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.99037 14.5893H12.1143V12.2695H7.99037V14.5893ZM0.773438 0.670898V2.99063H19.3313V0.670898H0.773438ZM3.86641 8.78995H16.2383V6.47022H3.86641V8.78995Z"
                            fill="#00509d" />
                    </svg>
                </button>

                <!-- Select Kategori -->
                <div class="relative inline-block w-full sm:w-48">
                    <select id="kategori_select"
                        class="appearance-none bg-blue-700 text-white px-10 py-2 rounded-xl pr-8 focus:outline-none cursor-pointer w-full sm:w-auto">
                        <option value="kandidat">Kandidat</option>
                        <option value="non_kandidat">Non Kandidat</option>
                        <option value="calon_kandidat">Calon Kandidat</option>
                    </select>
                </div>
            </div>

            <!-- Form Search -->
            <div class="flex w-full sm:w-auto mt-3 sm:mt-0">
                <form action="{{ route('superadmin.pelamar') }}" method="get"
                    class="flex flex-col sm:flex-row gap-2 w-full">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="nama/username ..."
                        class="border border-gray-500 rounded-lg px-4 py-2 w-full sm:w-72">
                    <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white font-medium px-10 py-2 rounded-xl w-full sm:w-auto">
                        Cari
                    </button>
                </form>
            </div>
        </div>


        <!-- Table Kandidat -->
        <div id="kandidat" class="overflow-x-auto rounded-2xl border-2 border-gray-400">
            <table class="min-w-full text-left border-collapse">
                <thead class="text-center bg-gray-100">
                    <tr>
                        <th class="p-4 font-medium whitespace-nowrap">ID</th>
                        <th class="p-4 font-medium whitespace-nowrap">Nama</th>
                        <th class="p-4 font-medium whitespace-nowrap">Pendidikan</th>
                        <th class="p-4 font-medium whitespace-nowrap">Skill</th>
                        <th class="p-4 font-medium whitespace-nowrap">Alamat</th>
                        <th class="p-4 font-medium whitespace-nowrap">Detail</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($kandidat as $p)
                        <tr class="border-b-2">
                            <td class="px-2 py-3 break-words max-w-[80px]">{{ $p->id }}</td>
                            <td class="px-2 py-3 break-words max-w-[150px]">{{ $p->nama_pelamar ?? $p->user->username }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[200px]">
                                {{ $p->riwayat_pendidikan->pluck('pendidikan')->implode(', ') }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[200px]">
                                {{ $p->skill->pluck('skill')->implode(', ') }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[150px]">
                                {{ $p->alamat_pelamar->first()?->provinsi ?? '-' }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[100px]">
                                <a href="{{ route('superadmin.detail.kandidat', $p->id) }}"
                                    class="bg-blue-700 hover:bg-blue-800 text-xs text-white px-3 py-1 rounded-lg inline-block w-full text-center">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-gray-500 italic">Tidak ada data kandidat aktif</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{-- selesai tabel kandidat --}}

        {{-- tabel non kandidat --}}
        <div id="non_kandidat" class="hidden overflow-x-auto rounded-2xl border-2 border-gray-400">
            <table class="min-w-full text-left border-collapse">
                <thead class="text-center bg-gray-100">
                    <tr>
                        <th class="p-4 font-medium whitespace-nowrap">ID</th>
                        <th class="p-4 font-medium whitespace-nowrap">Nama</th>
                        <th class="p-4 font-medium whitespace-nowrap">Pendidikan</th>
                        <th class="p-4 font-medium whitespace-nowrap">Skill</th>
                        <th class="p-4 font-medium whitespace-nowrap">Alamat</th>
                        <th class="p-4 font-medium whitespace-nowrap">Detail</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($nonKandidat as $p)
                        <tr class="border-b-2">
                            <td class="px-2 py-3 break-words max-w-[80px]">{{ $p->id }}</td>
                            <td class="px-2 py-3 break-words max-w-[150px]">{{ $p->nama_pelamar ?? $p->user->username }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[200px]">
                                {{ $p->riwayat_pendidikan->pluck('pendidikan')->implode(', ') }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[200px]">
                                {{ $p->skill->pluck('skill')->implode(', ') }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[150px]">
                                {{ $p->alamat_pelamar->first()?->provinsi ?? '-' }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[100px]">
                                <a href="{{ route('superadmin.detail.non.kandidat', $p->id) }}"
                                    class="bg-blue-700 hover:bg-blue-800 text-xs text-white px-3 py-1 rounded-lg inline-block w-full text-center">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-gray-500 italic">Tidak ada data non kandidat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- selesai tabel non kandidat --}}

        {{-- tabel calon kandidat --}}
        <div id="calon_kandidat" class="hidden overflow-x-auto rounded-2xl border-2 border-gray-400">
            <table class="min-w-full text-left border-collapse">
                <thead class="text-center bg-gray-100">
                    <tr>
                        <th class="p-4 font-medium whitespace-nowrap">ID</th>
                        <th class="p-4 font-medium whitespace-nowrap">Nama</th>
                        <th class="p-4 font-medium whitespace-nowrap">Pendidikan</th>
                        <th class="p-4 font-medium whitespace-nowrap">Skill</th>
                        <th class="p-4 font-medium whitespace-nowrap">Alamat</th>
                        <th class="p-4 font-medium whitespace-nowrap">Detail</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($calonKandidat as $p)
                        <tr class="border-b-2">
                            <td class="px-2 py-3 break-words max-w-[80px]">{{ $p->id }}</td>
                            <td class="px-2 py-3 break-words max-w-[150px]">{{ $p->nama_pelamar ?? $p->user->username }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[200px]">
                                {{ $p->riwayat_pendidikan->pluck('pendidikan')->implode(', ') }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[200px]">
                                {{ $p->skill->pluck('skill')->implode(', ') }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[150px]">
                                {{ $p->alamat_pelamar->first()?->provinsi ?? '-' }}
                            </td>
                            <td class="px-2 py-3 break-words max-w-[100px]">
                                <a href="{{ route('superadmin.calon.detail', $p->id) }}"
                                    class="bg-blue-700 hover:bg-blue-800 text-xs text-white px-3 py-1 rounded-lg inline-block w-full text-center">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-gray-500 italic">Tidak ada data calon kandidat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{-- selesai tabel calon kandidat --}}
        <!-- Modal Notifikasi -->
        <div x-data="notifHandler()" x-cloak x-show="openNotif"
            class="fixed inset-0 z-50 flex items-start justify-end p-2 sm:p-4" @click.self="openNotif = false">

            <div class="bg-white w-[80%] sm:w-[360px] rounded-xl shadow-lg overflow-hidden">

                <!-- Header -->
                <div class="flex items-center justify-between px-3 sm:px-4 py-3 border-b">
                    <h2 class="font-semibold text-sm sm:text-lg">Notifikasi</h2>
                    <button @click="openNotif=false; openAllNotif=true" class="text-xs sm:text-sm text-blue-700">
                        Lihat semua
                    </button>
                </div>

                <!-- List Notifikasi -->
                <div class="max-h-[200px] sm:max-h-[400px] overflow-y-auto">
                    @forelse($global_notifikasis as $notif)
                        <div data-id="{{ $notif->id }}"
                            onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                            class="notif-item cursor-pointer flex items-start gap-2 p-3 border-b 
                    {{ $notif->is_read ? 'bg-gray-200' : 'bg-white' }}">

                            <!-- Logo perusahaan -->
                            @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                                <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                        class="w-full h-full object-cover rounded-md">
                                </div>
                            @endif

                            <!-- Pesan -->
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm break-all leading-snug">{!! $notif->pesan !!}</p>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    {{ $notif->created_at->diffForHumans() }}
                                </p>

                                <button @click.stop="hapus({{ $notif->id }})"
                                    class="text-red-500 text-[10px] sm:text-xs hover:underline mt-1">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="p-3 text-gray-500 text-xs text-center">Tidak ada notifikasi</p>
                    @endforelse
                </div>

                <!-- Footer -->
                <iframe name="hiddenFrame" style="display:none;"></iframe>
                <div class="p-3 border-t flex justify-between items-center">
                    <button @click="hapusSemua()" class="text-[11px] sm:text-sm text-red-600 hover:underline">
                        Hapus Semua
                    </button>

                    <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrame">
                        @csrf
                        <button type="submit" class="text-[11px] sm:text-sm text-blue-600 hover:underline">
                            Tandai Baca
                        </button>
                    </form>
                </div>
            </div>
        </div>



        <!-- Modal Semua Notifikasi -->
        <!-- Modal Semua Notifikasi -->
        <div x-data="notifHandler()" x-cloak x-show="openAllNotif"
            class="fixed inset-0 z-50 flex items-start justify-center p-2 sm:p-4 bg-black/30"
            @click.self="openAllNotif = false">

            <div class="bg-white w-[85%] sm:w-full sm:max-w-lg rounded-xl shadow-lg overflow-hidden">

                <!-- Header -->
                <div class="flex items-center justify-between px-3 sm:px-4 py-3 border-b">
                    <h2 class="font-semibold text-base sm:text-lg">Semua Notifikasi</h2>
                    <button @click="openAllNotif=false" class="text-xs sm:text-sm text-gray-500">Tutup</button>
                </div>

                <!-- Semua Notifikasi -->
                <div class="max-h-[300px] sm:max-h-[500px] overflow-y-auto">
                    @foreach (\App\Models\Notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $notif)
                        <div data-id="{{ $notif->id }}"
                            onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                            class="notif-item cursor-pointer flex items-start gap-2 sm:gap-3 p-3 border-b 
                    {{ $notif->is_read ? 'bg-gray-200' : 'bg-white' }}">

                            @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                                <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                        class="w-full h-full object-contain rounded">
                                </div>
                            @endif

                            <div class="flex-1">
                                <p class="text-xs sm:text-sm break-all leading-snug">{!! $notif->pesan !!}</p>
                                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">
                                    {{ $notif->created_at->diffForHumans() }}
                                </p>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="p-3 border-t flex justify-between items-center">

                    <button @click="hapusSemuaBaca()" class="text-xs sm:text-sm text-blue-800 hover:underline">
                        Hapus Semua Dibaca
                    </button>

                    <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrameAll">
                        @csrf
                        <button type="submit" class="text-xs sm:text-sm text-blue-600 hover:underline">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                </div>

                <iframe name="hiddenFrameAll" style="display:none;"></iframe>
            </div>
        </div>


    </main>
    <script>
        // Tandai dibaca
        async function markAsRead(url, el) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {

                    // Ubah warna bg
                    el.classList.remove("bg-white");
                    el.classList.add("bg-gray-200");

                    // Kurangi badge
                    const badge = document.getElementById("notif-badge");
                    if (badge) {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.remove();
                        }
                    }
                }

            } catch (error) {
                console.error("markAsRead error:", error);
            }
        }

        // AlpineJS init
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifHandler', () => ({

                // Hapus satu notifikasi
                async hapus(id) {
                    if (!confirm("Hapus notifikasi ini?")) return;

                    let url = "{{ route('notifikasi.hapus', ':id') }}".replace(':id', id);

                    let res = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
                    }
                },

                // Hapus semua
                async hapusSemua() {
                    if (!confirm("Hapus semua notifikasi?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemua') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item').forEach(e => e.remove());
                    }
                },

                // Hapus semua yang sudah dibaca
                async hapusSemuaBaca() {
                    if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;

                    let res = await fetch("{{ route('notifikasi.hapusSemuaBaca') }}", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    let data = await res.json();

                    if (data.success) {
                        document.querySelectorAll('.notif-item.bg-gray-200')
                            .forEach(e => e.remove());
                    }
                }

            }));
        });
    </script>


    <script>
        document.querySelector('form[target="hiddenFrame"]').addEventListener('submit', () => {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.classList.remove('bg-white');
                item.classList.add('bg-gray-200');
            });
            const badge = document.querySelector('.absolute .bg-red-500');
            if (badge) badge.remove();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
