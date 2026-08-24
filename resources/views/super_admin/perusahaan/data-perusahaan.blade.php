@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 sm:gap-0">
            <h1 class="text-2xl font-medium break-words">
                Data Perusahaan
            </h1>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-3 w-full sm:w-auto">
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

                    {{-- Badge jumlah notif --}}
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 left-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profile --}}
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-shrink-0 overflow-hidden">
                    <a href="{{ route('superadmin.profile') }}">
                        @php
                            $profileImage =
                                Auth::user()->role == 'super_admin' && Auth::user()->superadmin?->img_profile
                                    ? asset('storage/' . Auth::user()->superadmin->img_profile)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode(Auth::user()->username) .
                                        '&background=random&color=fff&size=128';
                        @endphp
                        <img class="w-10 h-10 object-cover rounded-full" src="{{ $profileImage }}" alt="Profile">
                    </a>

                    <div class="text-sm truncate">
                        <span class="font-semibold block">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap items-center gap-2 sm:gap-4 w-full sm:w-auto">
                <a href="{{ route('superadmin.add.user.createForm') }}"
                    class="bg-orange-500 hover:bg-orange-600 border border-orange-600 text-white px-3 py-2 rounded-lg inline-flex items-center justify-center flex-shrink-0">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.35156 10.6295H19.9094M10.6305 1.35059V19.9084" stroke="white" stroke-width="2.65112"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <button
                    class="bg-white hover:bg-gray-100 border border-orange-600 text-orange-600 px-4 py-3 rounded-lg flex-shrink-0">
                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.99037 14.5893H12.1143V12.2695H7.99037V14.5893ZM0.773438 0.670898V2.99063H19.3313V0.670898H0.773438ZM3.86641 8.78995H16.2383V6.47022H3.86641V8.78995Z"
                            fill="#FA6601" />
                    </svg>
                </button>

                <div class="relative inline-block w-full sm:w-48">
                    <!-- Select utama -->
                    <button id="dropdownButton"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium px-4 py-2 border border-orange-500 rounded-md flex justify-between items-center focus:outline-none truncate">
                        <span id="dropdownText" class="truncate">Pilih Opsi</span>
                        <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownMenu"
                        class="absolute hidden mt-2 w-full bg-white rounded-md shadow-lg overflow-hidden z-10">
                        <ul class="text-orange-500">
                            <li>
                                <a href="{{ route('superadmin.perusahaan') }}"
                                    class="block px-4 py-2 hover:bg-orange-500 hover:text-white transition truncate">Perusahaan</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.recruitment.perusahaan') }}"
                                    class="block px-4 py-2 hover:bg-orange-500 hover:text-white transition truncate">Recruitment</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.talent-hunter') }}"
                                    class="block px-4 py-2 hover:bg-orange-500 hover:text-white transition truncate">Talent
                                    Hunter</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.panggilan') }}"
                                    class="block px-4 py-2 hover:bg-orange-500 hover:text-white transition truncate">Panggilan</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-2 w-full sm:w-auto">
                <form action="{{ route('superadmin.perusahaan') }}" method="GET"
                    class="flex w-full sm:w-auto gap-2 flex-wrap">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="nama/username ..."
                        class="border-2 border-gray-400 rounded-lg px-4 py-2 w-full sm:w-72 truncate">
                    <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-10 py-2 rounded-xl flex-shrink-0">Cari</button>
                </form>
            </div>
        </div>


        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border-2 border-gray-400 shadow-md">
            <table class="w-full min-w-[800px] text-left border-collapse">
                <thead class="text-center bg-orange-500 text-white">
                    <tr>
                        <th class="p-4 sm:p-7 font-medium">ID</th>
                        <th class="p-4 sm:p-7 font-medium">Nama Perusahaan</th>
                        <th class="p-4 sm:p-7 font-medium">Email</th>
                        <th class="p-4 sm:p-7 font-medium">Telepon</th>
                        <th class="p-4 sm:p-7 font-medium">Alamat</th>
                        <th class="p-4 sm:p-7 font-medium">Aksi</th>
                        <th class="p-4 sm:p-7 font-medium">Status</th>
                        <th class="p-4 sm:p-7 font-medium">Verif</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($perusahaan as $p)
                        <tr class="border-b border-gray-300">
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $p->id }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $p->nama_perusahaan ?? $p->user->username }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $p->user->email }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $p->telepon_perusahaan }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">
                                {{ $p->kota ?? $p->alamat ?? '-' }}</td>
                            <td class="px-2 sm:px-4 py-2">
                                <a href="{{ route('superadmin.perusahaan.detail', $p->id) }}"
                                    class="bg-orange-500 hover:bg-orange-600 text-xs sm:text-sm text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg inline-block whitespace-nowrap">
                                    View
                                </a>
                            </td>
                            <td>
                                <span
                                    class="
            {{ $p->verification_status === 'approved'
                ? 'text-green-600'
                : ($p->verification_status === 'rejected'
                    ? 'text-red-600'
                    : 'text-orange-500') }}">
                                    {{ ucfirst($p->verification_status) }}
                                </span>
                            </td>
                            <td>
                                @if ($p->verification_status !== 'approved')
                                    <form method="POST" action="{{ route('superadmin.perusahaan.approve', $p->id) }}">
                                        @csrf
                                        <button
                                            class="bg-green-600 text-white px-3 py-1 rounded mb-2 mt-2 mr-2">Approve</button>
                                    </form>
                                @endif

                                <button onclick="openRejectModal({{ $p->id }})"
                                    class="bg-red-600 text-white px-3 py-1 rounded mb-2 mr-2">
                                    Reject
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-gray-500">Belum ada data perusahaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')

        <!-- Modal Reject Verif Perusahaan -->
        <div id="modalReject" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg w-[400px] p-5">
                <h2 class="text-lg font-semibold mb-3">Reject Verif Perusahaan</h2>

                <form method="POST" id="rejectForm">
                    @csrf
                    <label class="block text-sm mb-1">
                        Alasan Penolakan (opsional)
                    </label>
                    <textarea name="note" class="w-full border rounded p-2 text-sm" rows="3"
                        placeholder="Contoh: Dokumen belum lengkap"></textarea>

                    <div class="flex justify-end mt-4 gap-2">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 rounded">
                            Batal
                        </button>
                        <button class="px-4 py-2 bg-red-600 text-white rounded">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>

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


    <script>
        function openRejectModal(id) {
            const modal = document.getElementById('modalReject');
            const form = document.getElementById('rejectForm');

            form.action = `/super_admin/perusahaan/reject/${id}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('modalReject').classList.add('hidden');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
