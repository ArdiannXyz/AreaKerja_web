@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-wrap md:flex-nowrap justify-between items-center mb-6 gap-3">

            <h1 class="text-2xl font-medium whitespace-nowrap">
                Event
            </h1>

            <div class="flex items-center gap-3 flex-wrap md:flex-nowrap w-full md:w-auto">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative">
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>
                    @if ($global_notifikasi_unread > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                <div
                    class="flex items-center justify-between w-full md:w-90 min-h-14 bg-white border border-orange-500 shadow-md rounded-2xl px-3 py-3 overflow-hidden">

                    <div class="flex items-center gap-2 mr-2 min-w-0 w-full">

                        <a href="{{ route('superadmin.profile') }}">
                            @if (Auth::user()->role == 'super_admin')
                                @if (Auth::user()->superadmin?->img_profile)
                                    <img class="w-10 h-10 object-cover rounded-full"
                                        src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}"
                                        alt="">
                                @else
                                    <img class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                        alt="">
                                @endif
                            @else
                                <img class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="">
                            @endif
                        </a>

                        <div class="text-sm min-w-0">
                            <span class="font-semibold block truncate">
                                {{ Auth::user()->username }}
                            </span>
                            <p class="text-gray-500 text-sm truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                    </div>

                </div>

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
                            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-md w-full sm:w-auto text-center">
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
                            class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 w-full sm:w-auto">
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
