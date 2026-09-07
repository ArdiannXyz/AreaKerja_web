@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 sm:ml-64 p-6 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
            <h1 class="text-2xl font-medium truncate">
                Data Kandidat
            </h1>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative flex-shrink-0">
                    <!-- Icon Lonceng -->
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>

                    <!-- Badge jumlah notif belum dibaca -->
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profile --}}
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-shrink-0">
                    <a href="{{ route('superadmin.profile') }}">
                        @if (Auth::user()->role == 'super_admin')
                            @if (Auth::user()->superadmin?->img_profile)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                    src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                            @else
                                <img id="pu" class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="">
                            @endif
                        @else
                            <img class="w-10 h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    </a>
                    <div class="text-sm truncate max-w-[120px]">
                        <span class="font-semibold truncate block">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <!-- Tombol & Select -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto flex-wrap">
                <!-- Tombol Add -->
                <a id="btnAdd" href="{{ route('superadmin.pelamar.create', ['kategori' => 'kandidat']) }}"
                    class="bg-orange-500 flex justify-center items-center px-3 py-2 border border-orange-500 rounded-md text-white hover:bg-orange-600 transition flex-shrink-0">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.35156 10.6295H19.9094M10.6305 1.35059V19.9084" stroke="white" stroke-width="2.65112"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <!-- Tombol Filter -->
                <button class="bg-white border border-orange-600 text-orange-600 px-4 py-3 rounded-lg flex-shrink-0">
                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.99037 14.5893H12.1143V12.2695H7.99037V14.5893ZM0.773438 0.670898V2.99063H19.3313V0.670898H0.773438ZM3.86641 8.78995H16.2383V6.47022H3.86641V8.78995Z"
                            fill="#FA6601" />
                    </svg>
                </button>

                <!-- Select Kategori -->
                <div class="relative inline-block w-full sm:w-48">
                    <select id="kategori_select"
                        class="appearance-none bg-orange-500 text-white px-10 py-2 rounded-md pr-8 focus:outline-none cursor-pointer w-full sm:w-auto">
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
                        class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-10 py-2 rounded-xl w-full sm:w-auto">
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
                                    class="bg-orange-500 hover:bg-orange-600 text-xs text-white px-3 py-1 rounded-lg inline-block w-full text-center">
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
                                    class="bg-orange-500 hover:bg-orange-600 text-xs text-white px-3 py-1 rounded-lg inline-block w-full text-center">
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
                                    class="bg-orange-500 hover:bg-orange-600 text-xs text-white px-3 py-1 rounded-lg inline-block w-full text-center">
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
                    <button @click="openNotif=false; openAllNotif=true" class="text-xs sm:text-sm text-orange-500">
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

                    <button @click="hapusSemuaBaca()" class="text-xs sm:text-sm text-orange-600 hover:underline">
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
