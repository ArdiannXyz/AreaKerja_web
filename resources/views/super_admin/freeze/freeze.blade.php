@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-hidden" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
            <h1 class="text-2xl font-medium break-words">Akun Freeze</h1>

            <div class="flex items-center gap-3 flex-wrap">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative">
                    <!-- Icon Lonceng -->
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168
                                                        15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876
                                                        22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861
                                                        17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186
                                                        9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555
                                                        6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595
                                                        4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37
                                                        4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109
                                                        12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149
                                                        20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702
                                                        24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647
                                                        24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971
                                                        27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612
                                                        25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987
                                                        20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183
                                                        20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076
                                                        14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945
                                                        25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283
                                                        25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504
                                                        22.1997L18.9055 23.0523Z" fill="black" />
                        </g>
                    </svg>

                    {{-- Badge --}}
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                <!-- Profile Card -->
                <div
                    class="flex items-center justify-between bg-white border border-orange-500 shadow-md rounded-2xl 
                   px-3 py-2 
                   w-full sm:w-auto
                   max-w-full">

                    <div class="flex items-center gap-2 overflow-hidden">
                        <a href="{{ route('superadmin.profile') }}">
                            @if (Auth::user()->role == 'super_admin')
                                @if (Auth::user()->superadmin?->img_profile)
                                    <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                        src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}"
                                        alt="Profile">
                                @else
                                    <img id="pu" class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                        alt="">
                                @endif
                            @else
                                <img class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                    alt="">
                            @endif
                        </a>

                        <div class="text-sm overflow-hidden">
                            <span class="font-semibold block truncate max-w-[120px] sm:max-w-none">
                                {{ Auth::user()->username }}
                            </span>
                            <p class="text-gray-500 text-xs sm:text-sm block truncate max-w-[150px] sm:max-w-none">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- content --}}
        <div class="flex justify-end mb-6 mt-14">

            <form action="{{ route('superadmin.freeze') }}" method="get"
                class="flex flex-wrap md:flex-nowrap gap-2 w-full md:w-auto">

                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="username/role ..."
                    class="border border-gray-600 rounded-lg px-4 py-2 w-full md:w-72 break-words">

                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-10 py-2 rounded-xl w-full md:w-auto">
                    Cari
                </button>

            </form>

        </div>


        <!-- Wrapper -->
        <div class="w-[102%] border border-gray-400 rounded-2xl shadow-sm overflow-hidden">


            <!-- Table -->

            <div class="w-full border border-gray-400 rounded-2xl shadow-sm overflow-y-auto">
                <table class="w-full text-sm text-center">
                    <thead class="border-b border-gray-300">
                        <tr class="text-gray-700">
                            <th class="p-4 font-semibold">No</th>
                            <th class="p-4 font-semibold">Username</th>
                            <th class="p-4 font-semibold">Email</th>
                            <th class="p-4 font-semibold">Role</th>
                            <th class="p-4 font-semibold">Telepon</th>
                            <th class="p-4 font-semibold">Alamat</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-400">
                        @foreach ($data as $d)
                            <!-- Baris Data -->

                            <tr class="border-2 border-gray-400">
                                <td class="px-6 py-4">{{ $d->id }}</td>
                                <td class="px-6 py-4">{{ $d->username }}</td>
                                <td class="px-6 py-4">{{ $d->email }}</td>
                                <td class="px-6 py-4">{{ $d->role }}</td>
                                <td class="px-6 py-4">
                                    @if ($d->role == 'pelamar')
                                        {{ $d->pelamar->telepon_pelamar ?? '-' }}
                                    @elseif ($d->role == 'perusahaan')
                                        {{ $d->perusahaan->telepon_perusahaan ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $provinsi = '-';
                                        if ($d->role == 'pelamar') {
                                            $pelamarObj = $d->pelamar;
                                            $provinsi = is_object($pelamarObj) ? ($pelamarObj->provinsi ?? $pelamarObj->alamat ?? '-') : '-';
                                        } elseif ($d->role == 'perusahaan') {
                                            $perusahaanObj = $d->perusahaan;
                                            $provinsi = is_object($perusahaanObj) ? ($perusahaanObj->provinsi ?? $perusahaanObj->kota ?? '-') : '-';
                                        } elseif ($d->role == 'finance') {
                                            $finObj = $d->finance;
                                            $provinsi = is_object($finObj) ? (is_object($finObj->provinsi ?? null) ? ($finObj->provinsi->nama ?? '-') : ($finObj->provinsi ?? '-')) : '-';
                                        } elseif ($d->role == 'admin') {
                                            $admObj = $d->admin;
                                            $provinsi = is_object($admObj) ? (is_object($admObj->provinsi ?? null) ? ($admObj->provinsi->nama ?? '-') : ($admObj->provinsi ?? '-')) : '-';
                                        } elseif ($d->role == 'super_admin') {
                                            $saObj = $d->super_admin;
                                            $provinsi = is_object($saObj) ? (is_object($saObj->provinsi ?? null) ? ($saObj->provinsi->nama ?? '-') : ($saObj->provinsi ?? '-')) : '-';
                                        }
                                    @endphp

                                    {{ $provinsi }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($d->status == 0)
                                        <span
                                            class="bg-blue-500 text-green-100 text-sm font-medium px-2.5 py-0.5 rounded-sm">Aktif</span>
                                    @else
                                        <span
                                            class="bg-red-500 text-green-100 text-sm font-medium px-2.5 py-0.5 rounded-sm">Banned</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 flex items-center justify-center">
                                    <a href="{{ route('superadmin.detail.freeze', $d->id) }}">
                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <mask id="mask0_743_14496" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="25" height="25">
                                                <rect width="25" height="25" fill="url(#pattern0_743_14496)" />
                                            </mask>
                                            <g mask="url(#mask0_743_14496)">
                                                <rect x="0.5" y="-2.5" width="31.3889" height="30" fill="#000AFF" />
                                            </g>
                                            <defs>
                                                <pattern id="pattern0_743_14496" patternContentUnits="objectBoundingBox"
                                                    width="1" height="1">
                                                    <use xlink:href="#image0_743_14496" transform="scale(0.00195312)" />
                                                </pattern>
                                                <image id="image0_743_14496" width="512" height="512"
                                                    preserveAspectRatio="none"
                                                    xlink:href="data:image/png;base64,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" />
                                            </defs>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
    <script>
        document.getElementById("cari").addEventListener("keyup", function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll("#myTable tbody tr");

            rows.forEach(row => {
                let rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(input) ? "" : "none";
            });
        });
    </script>

    {{-- notif --}}
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
@endsection
