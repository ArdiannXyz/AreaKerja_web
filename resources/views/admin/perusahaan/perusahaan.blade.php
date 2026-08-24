@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-3 md:gap-0">

            <!-- Judul -->
            <h1 class="text-xl md:text-2xl font-medium">
                Data Perusahaan
            </h1>

            <!-- Bagian kanan -->
            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative ml-44">
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431Z"
                                fill="black" />
                        </g>
                    </svg>

                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profile --}}
                <div class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl">
                    <a href="#">
                        @if (Auth::user()->role == 'admin')
                            @if (Auth::user()->admin->img_profile)
                                <img id="pu" class="w-8 h-8 md:w-10 md:h-10 object-cover rounded-full"
                                    src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
                            @else
                                <img id="pu" class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                    alt="">
                            @endif
                        @else
                            <img class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                alt="">
                        @endif
                    </a>

                    <div class="text-xs md:text-sm max-w-[120px] md:max-w-none truncate">
                        <span class="font-semibold block">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

            <!-- TAB MENU -->
            <div class="flex items-center gap-4 overflow-x-auto pb-1">

                <a href="{{ route('admin.perusahaan') }}"
                    class="{{ request()->is('admin/perusahaan') ? 'bg-gray-500 text-white border-gray-500' : 'bg-white text-gray-500 border-gray-500 hover:bg-gray-500 hover:text-white' }} 
            px-6 py-2 text-md font-medium border-2 rounded-lg transition duration-300 whitespace-nowrap">
                    Perusahaan
                </a>

                <a href="{{ route('admin.recruitment.perusahaan') }}"
                    class="{{ request()->is('admin/recruitment/perusahaan') ? 'bg-gray-500 text-white border-gray-500' : 'bg-white text-gray-500 border-gray-500 hover:bg-gray-500 hover:text-white' }} 
            px-6 py-2 text-md font-medium border-2 rounded-lg transition duration-300 whitespace-nowrap">
                    Recruitment
                </a>

                <a href="{{ route('admin.talent-hunter') }}"
                    class="{{ request()->is('admin/talent/hunter') ? 'bg-gray-500 text-white border-gray-500' : 'bg-white text-gray-500 border-gray-500 hover:bg-gray-500 hover:text-white' }} 
            px-6 py-2 text-md font-medium border-2 rounded-lg transition duration-300 whitespace-nowrap">
                    Talent Hunter
                </a>

            </div>

            <!-- SEARCH -->
            <div class="flex gap-2 w-full md:w-auto">
                <form action="{{ route('admin.perusahaan') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="{{ $search }}" placeholder="nama/username ..."
                        class="border border-gray-500 rounded-lg px-4 py-2 w-full md:w-72">
                    <button type="submit"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-10 py-2 rounded-xl whitespace-nowrap">
                        Cari
                    </button>
                </form>
            </div>

        </div>


        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border-2 border-gray-400">
            <table class="min-w-[900px] w-full text-left border-collapse">
                <thead class="text-center">
                    <tr>
                        <th class="p-7 font-semibold">ID</th>
                        <th class="p-7 font-semibold">Nama Perusahaan</th>
                        <th class="p-7 font-semibold">Email</th>
                        <th class="p-7 font-semibold">Telepon</th>
                        <th class="p-7 font-semibold">Alamat</th>
                        <th class="p-7 font-semibold">Aksi</th>
                        <th class="p-7 font-semibold">Status</th>
                        <th class="p-7 font-semibold">Verif</th>
                    </tr>
                </thead>

                <tbody class="text-center">
                    @forelse ($perusahaan as $p)
                        <tr
                            class="border-b border-gray-300 hover:bg-gray-100 {{ $p->user->status == '1' ? 'opacity-50' : '' }}">
                            <td class="px-4 py-3">{{ $p->id }}</td>

                            <td class="px-4 py-3">
                                <a href="{{ route('admin.perusahaan.detail', $p->id) }}"
                                    class="text-blue-600 hover:underline break-all">
                                    {{ $p->nama_perusahaan ?? $p->user->username }}
                                </a>
                            </td>

                            <td class="px-4 py-3">{{ $p->user->email }}</td>
                            <td class="px-4 py-3">{{ $p->telepon_perusahaan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $p->kota ?? $p->alamat ?? '-' }}</td>

                            <td class="px-4 py-3 flex items-center justify-center gap-2">
                                <!-- Tombol Lihat Lowongan -->
                                <a href="{{ route('admin.perusahaan.detail', $p->id) }}"
                                    class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded-md" title="Lihat Lowongan">
                                    <!-- SVG -->
                                    <svg width="20" height="20" viewBox="0 0 20 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.9184 7.53619C19.8885 7.45905 19.1822 5.60667 17.622 3.76381C15.5344 1.3019 12.9034 0 10.0006 0C7.09784 0 4.46681 1.3019 2.38166 3.76381C0.82143 5.60667 0.115092 7.45905 0.0828386 7.53619C0.0282128 7.68247 0 7.8406 0 8.00048C0 8.16036 0.0282128 8.31848 0.0828386 8.46476C0.112673 8.54286 0.819011 10.3943 2.38005 12.2371C4.46681 14.699 7.09784 16 10.0006 16C12.9034 16 15.5344 14.699 17.6187 12.2371C19.1798 10.3943 19.8861 8.54286 19.9159 8.46476C19.971 8.31868 19.9996 8.16066 20 8.00078C20.0004 7.8409 19.9726 7.68267 19.9184 7.53619ZM16.2044 10.679C14.4733 12.6924 12.3865 13.7143 10.0006 13.7143C7.6147 13.7143 5.52793 12.6924 3.79918 10.6781C3.11895 9.88304 2.5338 8.98203 2.05994 8C2.53395 7.01838 3.11908 6.1177 3.79918 5.32286C5.52874 3.30762 7.6147 2.28571 10.0006 2.28571C12.3865 2.28571 14.4725 3.30762 16.202 5.32286C16.8822 6.11762 17.4673 7.01831 17.9413 8C17.4673 8.98196 16.8822 9.88296 16.202 10.6781L16.2044 10.679ZM10.0006 3.80952C9.29891 3.80952 8.61298 4.05529 8.02954 4.51575C7.44611 4.9762 6.99137 5.63067 6.72285 6.39637C6.45432 7.16208 6.38406 8.00465 6.52096 8.81752C6.65785 9.63039 6.99575 10.3771 7.49192 10.9631C7.98809 11.5492 8.62025 11.9483 9.30846 12.11C9.99667 12.2716 10.71 12.1887 11.3583 11.8715C12.0066 11.5543 12.5607 11.0172 12.9505 10.3281C13.3403 9.63898 13.5484 8.8288 13.5484 8C13.5474 6.889 13.1732 5.82387 12.5081 5.03828C11.843 4.25268 10.9412 3.81078 10.0006 3.80952Z"
                                            fill="white" />
                                    </svg>
                                </a>

                                @if ($p->user->status === 0)
                                    <!-- Tombol Nonaktifkan -->
                                    <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md open-freeze-modal"
                                        title="Nonaktifkan" data-id="{{ $p->user->id }}">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10 2.5C8.01088 2.5 6.10322 3.29018 4.6967 4.6967C3.29018 6.10322 2.5 8.01088 2.5 10C2.5 11.9891 3.29018 13.8968 4.6967 15.3033C6.10322 16.7098 8.01088 17.5 10 17.5C11.9891 17.5 13.8968 16.7098 15.3033 15.3033C16.7098 13.8968 17.5 11.9891 17.5 10C17.5 8.01088 16.7098 6.10322 15.3033 4.6967C13.8968 3.29018 11.9891 2.5 10 2.5ZM0 10C0 7.34784 1.05357 4.8043 2.92893 2.92893C4.8043 1.05357 7.34784 0 10 0C12.6522 0 15.1957 1.05357 17.0711 2.92893C18.9464 4.8043 20 7.34784 20 10C20 12.6522 18.9464 15.1957 17.0711 17.0711C15.1957 18.9464 12.6522 20 10 20C7.34784 20 4.8043 18.9464 2.92893 17.0711C1.05357 15.1957 0 12.6522 0 10Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.0711 2.92893C17.3054 3.16334 17.4372 3.48105 17.4372 3.8125C17.4372 4.14396 17.3056 4.46184 17.0712 4.69625L4.69625 17.0712C4.4605 17.2989 4.14474 17.4249 3.817 17.4221C3.48925 17.4192 3.17574 17.2878 2.94398 17.056C2.71222 16.8243 2.58076 16.5107 2.57791 16.183C2.57506 15.8553 2.70105 15.5395 2.92875 15.3038L15.3038 2.92875C15.5382 2.69441 15.856 2.56277 16.1875 2.56277C16.519 2.56277 16.8367 2.69459 17.0711 2.92893Z"
                                                fill="white" />
                                        </svg>
                                    </button>
                                @else
                                    <!-- Tombol Aktifkan -->
                                    <button
                                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md open-unfreeze-modal"
                                        title="Aktifkan" data-id="{{ $p->user->id }}">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10 2.5C8.01088 2.5 6.10322 3.29018 4.6967 4.6967C3.29018 6.10322 2.5 8.01088 2.5 10C2.5 11.9891 3.29018 13.8968 4.6967 15.3033C6.10322 16.7098 8.01088 17.5 10 17.5C11.9891 17.5 13.8968 16.7098 15.3033 15.3033C16.7098 13.8968 17.5 11.9891 17.5 10C17.5 8.01088 16.7098 6.10322 15.3033 4.6967C13.8968 3.29018 11.9891 2.5 10 2.5ZM0 10C0 7.34784 1.05357 4.8043 2.92893 2.92893C4.8043 1.05357 7.34784 0 10 0C12.6522 0 15.1957 1.05357 17.0711 2.92893C18.9464 4.8043 20 7.34784 20 10C20 12.6522 18.9464 15.1957 17.0711 17.0711C15.1957 18.9464 12.6522 20 10 20C7.34784 20 4.8043 18.9464 2.92893 17.0711C1.05357 15.1957 0 12.6522 0 10Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.0711 2.92893C17.3054 3.16334 17.4372 3.48105 17.4372 3.8125C17.4372 4.14396 17.3056 4.46184 17.0712 4.69625L4.69625 17.0712C4.4605 17.2989 4.14474 17.4249 3.817 17.4221C3.48925 17.4192 3.17574 17.2878 2.94398 17.056C2.71222 16.8243 2.58076 16.5107 2.57791 16.183C2.57506 15.8553 2.70105 15.5395 2.92875 15.3038L15.3038 2.92875C15.5382 2.69441 15.856 2.56277 16.1875 2.56277C16.519 2.56277 16.8367 2.69459 17.0711 2.92893Z"
                                                fill="white" />
                                        </svg>
                                    </button>
                                @endif
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
                                    <form method="POST" action="{{ route('admin.perusahaan.approve', $p->id) }}">
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

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </div>
    </div>

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


    <!-- Modal Konfirmasi -->
    <div id="confirmModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 text-center w-96">
            <div class="text-6xl text-red-500 mb-4">⚠️</div>
            <p class="mb-6">Yakin akan membekukan?</p>
            <div class="flex justify-center gap-4">
                <button id="yesFreeze" class="bg-green-500 text-white px-6 py-2 rounded">Ya</button>
                <button id="cancelFreeze" class="bg-red-500 text-white px-6 py-2 rounded">Tidak</button>
            </div>
        </div>
    </div>

    <!-- Modal Alasan -->
    <div id="reasonModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 text-center w-96">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi</h2>
            <textarea id="alasan" rows="4" class="w-full border rounded p-2 mb-4" placeholder="Masukkan Alasan"></textarea>
            <button id="submitReason" class="bg-green-500 text-white px-6 py-2 rounded">Kirim</button>
        </div>
    </div>

    <!-- Modal Unfreeze -->
    <div id="unfreezeModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 text-center w-96">
            <div class="text-6xl text-green-500 mb-4">🔓</div>
            <p class="mb-6">Yakin ingin mengaktifkan kembali akun ini?</p>
            <div class="flex justify-center gap-4">
                <button id="yesUnfreeze" class="bg-green-500 text-white px-6 py-2 rounded">Ya</button>
                <button id="cancelUnfreeze" class="bg-red-500 text-white px-6 py-2 rounded">Tidak</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let selectedUserId = null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // === OPEN FREEZE ===
            document.querySelectorAll('.open-freeze-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedUserId = btn.dataset.id;
                    document.getElementById('confirmModal').classList.remove('hidden');
                });
            });

            document.getElementById('cancelFreeze').addEventListener('click', () => {
                document.getElementById('confirmModal').classList.add('hidden');
            });

            document.getElementById('yesFreeze').addEventListener('click', () => {
                document.getElementById('confirmModal').classList.add('hidden');
                document.getElementById('reasonModal').classList.remove('hidden');
            });

            document.getElementById('submitReason').addEventListener('click', async () => {
                const alasan = document.getElementById('alasan').value.trim();
                if (!alasan) {
                    alert('⚠️ Silakan isi alasan terlebih dahulu.');
                    return;
                }

                try {
                    const response = await fetch(`/admin/user/freeze/${selectedUserId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            alasan
                        })
                    });

                    const result = await response.json();
                    alert(result.message || '✅ Akun berhasil dibekukan.');
                    location.reload();
                } catch (error) {
                    alert('❌ Terjadi kesalahan: ' + error.message);
                }
            });

            // === OPEN UNFREEZE ===
            document.querySelectorAll('.open-unfreeze-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedUserId = btn.dataset.id;
                    document.getElementById('unfreezeModal').classList.remove('hidden');
                });
            });

            document.getElementById('cancelUnfreeze').addEventListener('click', () => {
                document.getElementById('unfreezeModal').classList.add('hidden');
            });

            document.getElementById('yesUnfreeze').addEventListener('click', async () => {
                try {
                    const response = await fetch(`/admin/user/unfreeze/${selectedUserId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    const result = await response.json();
                    alert(result.message || '✅ Akun berhasil diaktifkan kembali.');
                    location.reload();
                } catch (error) {
                    alert('❌ Terjadi kesalahan: ' + error.message);
                }
            });
        });
    </script>

    <script>
        function openRejectModal(id) {
            const modal = document.getElementById('modalReject');
            const form = document.getElementById('rejectForm');

            form.action = `/admin/perusahaan/reject/${id}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('modalReject').classList.add('hidden');
        }
    </script>

@endsection
