@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-wrap md:flex-nowrap justify-between items-center mb-6 gap-3">

            <h1 class="text-2xl font-medium whitespace-nowrap">
                Event
            </h1>

            <div class="flex items-center gap-3 flex-wrap md:flex-nowrap w-full md:w-auto">

                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')

            </div>

        </div>



        {{-- content --}}
        <div class="w-full">
            <div class="flex flex-wrap justify-between items-start lg:items-center mb-4 gap-4">

                <!-- Tombol Buat Post -->
                <div class="space-x-2 grid grid-cols-2 gap-2 lg:inline-flex md:inline-flex w-full sm:w-auto">
                    <a href="{{ route('superadmin.event.createForm') }}"
                        class="bg-blue-500 hover:bg-blue-600 transition duration-300 text-white px-4 py-2 rounded-md text-center w-full sm:w-auto">
                        Buat Post
                    </a>
                </div>

                <!-- Search -->
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">

                    <form method="GET" action="{{ route('superadmin.eventform') }}"
                        class="flex flex-wrap items-center gap-2 w-full sm:w-auto">

                        <input type="text" name="q" placeholder="Cari Event" value="{{ request('q') }}"
                            class="border border-gray-500 hover:bg-gray-100 rounded-md px-3 py-2 
                           w-full sm:w-56 md:w-60 focus:outline-none focus:ring-2 focus:ring-gray-400
                           break-words">

                        <button type="submit"
                            class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-md w-full sm:w-auto text-center">
                            Cari
                        </button>
                    </form>

                </div>
            </div>



            {{-- Table --}}
            <div class="w-full border-2 border-gray-400 rounded-3xl shadow-md overflow-hidden">

                {{-- Tambah scroll horizontal untuk mobile --}}
                <div class="overflow-x-auto">

                    <table class="w-full border-collapse table-fixed md:table-fixed min-w-max md:min-w-full">

                        <thead class="bg-gray-50">
                            <tr class="text-center">
                                <th class="p-4 font-semibold text-gray-700 text-center w-[15%]">Status</th>
                                <th class="p-4 font-semibold text-gray-700 w-[65%] break-words">Nama</th>
                                <th class="p-4 font-semibold text-gray-700 w-[10%]">Kuota</th>
                                <th class="p-4 font-semibold text-gray-700 w-[25%]">Mulai</th>
                                <th class="p-4 font-semibold text-gray-700 w-[25%]">Selesai</th>
                                <th class="px-6 py-4 font-semibold text-gray-700 w-[12%] text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($events as $event)
                                <tr class="text-center">

                                    <td class="px-6 py-3 text-white whitespace-nowrap">
                                        @if ($event->status == 'buka')
                                            <button onclick="openStatusModal({{ $event->id }}, 'tutup')"
                                                class="bg-green-500 px-5 py-1 rounded-lg">
                                                Buka
                                            </button>
                                        @elseif ($event->status == 'tutup')
                                            <button onclick="openStatusModal({{ $event->id }}, 'buka')"
                                                class="bg-red-500 px-5 py-1 rounded-lg">
                                                Tutup
                                            </button>
                                        @else
                                            <span class="bg-gray-500 px-5 py-1 rounded-lg">
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-3 text-blue-400 font-medium break-words">
                                        <a href="{{ route('superadmin.detail.event', $event->id) }}">
                                            {{ $event->title }}
                                        </a>
                                    </td>

                                    <td class="px-6 py-3 text-gray-700 whitespace-nowrap">{{ $event->kuota ?? '-' }}</td>

                                    <td class="px-6 py-3 text-gray-700 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') }}
                                        {{ $event->jam_mulai }}
                                    </td>

                                    <td class="px-6 py-3 text-gray-700 whitespace-nowrap">
                                        @if ($event->tgl_akhir)
                                            {{ \Carbon\Carbon::parse($event->tgl_akhir)->format('d M Y') }}
                                            {{ $event->jam_akhir }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 flex items-center gap-2 whitespace-nowrap">
                                        <form action="{{ route('superadmin.event.destroy', $event->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600 flex items-center justify-center">
                                                <svg width="19" height="20" viewBox="0 0 19 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3.42593 20C2.79784 20 2.25997 19.7822 1.81231 19.3467C1.36466 18.9111 1.14121 18.3881 1.14198 17.7778V3.33333H0V1.11111H5.70988V0H12.5617V1.11111H18.2716V3.33333H17.1296V17.7778C17.1296 18.3889 16.9058 18.9122 16.4581 19.3478C16.0105 19.7833 15.473 20.0007 14.8457 20H3.42593ZM14.8457 3.33333H3.42593V17.7778H14.8457V3.33333ZM5.70988 15.5556H7.99383V5.55556H5.70988V15.5556ZM10.2778 15.5556H12.5617V5.55556H10.2778V15.5556Z"
                                                        fill="white" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-gray-500">
                                        Belum ada event.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                <div class="mt-4">
                    {{ $events->links() }}
                </div>

            </div>

            {{-- End Table --}}

        </div>

        <!-- Modal -->
        <div id="statusModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50 p-4">

            <div
                class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md 
                max-h-[90vh] overflow-y-auto break-words">

                <h2 class="text-lg font-semibold mb-3" id="modalTitle">
                    Ubah Status Event
                </h2>

                <p id="modalMessage" class="mb-5 text-gray-700"></p>

                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" id="statusInput">

                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" onclick="closeStatusModal()"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 w-full sm:w-auto">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-4 py-2 bg-blue-800 text-white rounded hover:bg-blue-900 w-full sm:w-auto">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>



        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')

    </main>


    <script>
        function openStatusModal(id, status) {
            const modal = document.getElementById('statusModal');
            const title = document.getElementById('modalTitle');
            const msg = document.getElementById('modalMessage');
            const statusInput = document.getElementById('statusInput');
            const form = document.getElementById('statusForm');

            // Isi form action
            form.action = `/super_admin/events/status/${id}`;

            // Isi status input
            statusInput.value = status;

            // Ubah tulisan modal
            if (status === 'tutup') {
                title.textContent = "Tutup Event?";
                msg.textContent = "Event akan ditutup dan tidak bisa lagi menerima pendaftaran.";
            } else {
                title.textContent = "Buka Event?";
                msg.textContent = "Event akan dibuka kembali dan bisa menerima pendaftaran.";
            }

            modal.classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }
    </script>

@endsection
