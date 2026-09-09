@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">

            <h1 class="text-2xl font-medium">Akun Freeze</h1>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">

                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')

            </div>
        </div>


        <div class="max-w-4xl mx-auto bg-white border border-gray-600 rounded-lg shadow-md overflow-hidden">

            <!-- Header dengan foto dan tombol -->
            <div
                class="flex flex-col md:flex-row items-center md:items-start gap-6 p-6 border-b border-gray-600 rounded-lg shadow-lg">

                <!-- FOTO PROFIL -->
                <div class="flex-shrink-0">
                    @if ($data->pelamar)
                        @if ($data->pelamar->img_profile)
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full"
                                src="{{ asset('storage/' . $data->pelamar->img_profile) }}" alt="Profile">
                        @else
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode($data->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    @elseif ($data->perusahaan) 
                        @if ($data->perusahaan->img_profile)
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full"
                                src="{{ asset('storage/' . $data->perusahaan->img_profile) }}" alt="Profile">
                        @else
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode($data->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    @elseif ($data->finance)
                        @if ($data->finance->img_profile)
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full"
                                src="{{ asset('storage/' . $data->finance->img_profile) }}" alt="Profile">
                        @else
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode($data->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    @elseif ($data->admin)
                        @if ($data->admin->img_profile)
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full"
                                src="{{ asset('storage/' . $data->admin->img_profile) }}" alt="Profile">
                        @else
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode($data->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    @elseif ($data->superadmin)
                        @if ($data->superadmin->img_profile)
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full"
                                src="{{ asset('storage/' . $data->superadmin->img_profile) }}" alt="Profile">
                        @else
                            <img id="pu" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ urlencode($data->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    @endif
                </div>

                <!-- FORM HAPUS / BAN / UNBAN -->
                <form id="hapus" action="{{ route('superadmin.delete.akun', $data->id) }}" method="post">
                    @csrf @method('DELETE')
                </form>

                <form id="unban" action="{{ route('superadmin.unban.freeze', $data->id) }}" method="post">
                    @csrf @method('PUT')
                    <input type="number" name="status" value="0" class="hidden">
                </form>

                <form id="ban" action="{{ route('superadmin.ban.freeze', $data->id) }}" method="post">
                    @csrf @method('PUT')
                    <input type="number" name="status" value="1" class="hidden">
                </form>

                <!-- BUTTON AREA -->
                <div class="w-full md:w-auto bg-white p-4 flex flex-wrap gap-3 justify-center md:justify-start">

                    @if ($data->status == 0)
                        <button type="submit" form="ban"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-xl shadow whitespace-nowrap">
                            Banned
                        </button>
                    @else
                        <button type="submit" form="unban"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-xl shadow whitespace-nowrap">
                            Unbanned
                        </button>
                    @endif

                    <button form="hapus" type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-xl shadow whitespace-nowrap">
                        Hapus Akun
                    </button>
                </div>
            </div>


            <!-- Body -->
            <div class="p-6 space-y-3">

                <!-- USERNAME -->
                <div
                    class="bg-gray-300 text-center font-semibold text-sm border-gray-300 shadow rounded-md border py-2 break-words">
                    {{ $data->username }}
                </div>

                <!-- EMAIL & TELEPON -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div
                        class="flex-1 bg-gray-300 text-center font-semibold text-sm border shadow border-gray-300 rounded-md py-2 break-words px-2">
                        {{ $data->email }}
                    </div>
                    <div
                        class="flex-1 bg-gray-300 text-center font-semibold text-sm border shadow border-gray-300 rounded-md py-2 break-words px-2">
                        @if ($data->role == 'pelamar')
                            {{ $data->pelamar->telepon_pelamar ?? '-' }}
                        @elseif ($data->role == 'perusahaan')
                            {{ $data->perusahaan->telepon_perusahaan ?? '-' }}
                        @elseif ($data->role == 'finance')
                            -
                        @elseif ($data->role == 'admin')
                            -
                        @elseif ($data->role == 'super_admin')
                            -
                        @endif
                    </div>
                </div>

                <!-- ALAMAT -->
                <div class="bg-gray-300 rounded-md p-3 min-h-32 overflow-x-auto break-words">

                    @php
                        // Inisialisasi dulu (WAJIB)
                        $provinsi = null;
                        $kota = null;
                        $kecamatan = null;
                        $desa = null;
                        $kode_pos = null;
                        $detail = null;

                        $alamat = $user->finance ?? null;

                        if ($alamat) {
                            $provinsi = $alamat->provinsi->nama ?? null;
                            $kota = $alamat->kota->nama ?? null;
                            $kecamatan = $alamat->kecamatan->nama ?? null;
                            $desa = $alamat->desa ?? null;
                            $kode_pos = $alamat->kode_pos ?? null;
                            $detail = $alamat->detail_alamat ?? null;
                        }

                        $bagian = array_filter([$desa, $kecamatan, $kota, $provinsi, $kode_pos]);
                    @endphp


                    @if ($alamat)
                        <div class="leading-relaxed text-gray-800 break-words">

                            @if (!empty($detail))
                                <p class="mb-1 break-words">{{ $detail }}</p>
                            @endif

                            <p class="text-sm text-gray-700 break-words">
                                {{ implode(', ', $bagian) }}
                            </p>

                        </div>
                    @else
                        <p class="text-gray-500 italic">Alamat belum diisi.</p>
                    @endif


                </div>
            </div>

        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
    <script>
        document.getElementById('fileinputsuperadmin').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('pu').setAttribute('src', event.target.result);
                    document.getElementById('pa').setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
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
