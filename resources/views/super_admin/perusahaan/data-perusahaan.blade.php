@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 sm:gap-0">
            <h1 class="text-2xl font-medium break-words">
                Data Perusahaan
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
                    class="bg-blue-700 hover:bg-blue-800 border border-blue-800 text-white px-3 py-2 rounded-xl inline-flex items-center justify-center flex-shrink-0">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.35156 10.6295H19.9094M10.6305 1.35059V19.9084" stroke="white" stroke-width="2.65112"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <button
                    class="bg-white hover:bg-gray-100 border border-blue-800 text-blue-800 px-4 py-3 rounded-xl flex-shrink-0">
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
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 py-2 border border-blue-700 rounded-xl flex justify-between items-center focus:outline-none truncate">
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

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-2 w-full sm:w-auto">
                <form action="{{ route('superadmin.perusahaan') }}" method="GET"
                    class="flex w-full sm:w-auto gap-2 flex-wrap">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="nama/username ..."
                        class="border-2 border-gray-400 rounded-lg px-4 py-2 w-full sm:w-72 truncate">
                    <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white font-medium px-10 py-2 rounded-xl flex-shrink-0">Cari</button>
                </form>
            </div>
        </div>


        <!-- Table -->
        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm bg-white">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">ID</th>
                        <th class="px-4 py-3.5 min-w-[160px]">Nama Perusahaan</th>
                        <th class="px-4 py-3.5 min-w-[160px]">Email</th>
                        <th class="px-4 py-3.5 min-w-[130px]">Telepon</th>
                        <th class="px-4 py-3.5 min-w-[140px]">Alamat / Kota</th>
                        <th class="px-4 py-3.5 text-center min-w-[110px]">Status</th>
                        <th class="px-4 py-3.5 text-center min-w-[150px]">Verifikasi</th>
                        <th class="px-4 py-3.5 text-center min-w-[90px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($perusahaan as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5 text-center text-gray-500 font-mono text-xs">{{ $p->id }}</td>
                            <td class="px-4 py-3.5 font-medium text-gray-900">{{ $p->nama_perusahaan ?? $p->user->username }}</td>
                            <td class="px-4 py-3.5 text-gray-600 text-xs">{{ $p->user->email }}</td>
                            <td class="px-4 py-3.5 text-gray-600 text-xs">{{ $p->telepon_perusahaan ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-gray-600 text-xs">{{ $p->kota ?? $p->alamat ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-center">
                                @if ($p->verification_status === 'approved')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                        Approved
                                    </span>
                                @elseif ($p->verification_status === 'rejected')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5 justify-center">
                                    @if ($p->verification_status !== 'approved')
                                        <form method="POST" action="{{ route('superadmin.perusahaan.approve', $p->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-2.5 py-1 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition shadow-sm">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" onclick="openRejectModal({{ $p->id }})"
                                        class="px-2.5 py-1 text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition shadow-sm">
                                        Reject
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <a href="{{ route('superadmin.perusahaan.detail', $p->id) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-[#00509d] hover:bg-blue-50 border border-blue-200 rounded-lg transition">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">Belum ada data perusahaan.</td>
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
