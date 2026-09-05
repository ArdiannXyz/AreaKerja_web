@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
     <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"> 
        <!-- 🔧 UPDATED -->
            <h1 class="text-2xl font-medium">Detail Non Kandidat</h1>
            <div class="flex items-center gap-3">
                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative">
                    <!-- Icon Lonceng -->
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                            {{-- <path
                                d="M22.3629 11.0329C24.0912 10.7376 25.2143 8.97144 24.8714 7.08792C24.5286 5.20441 22.8497 3.91684 21.1214 4.21205C19.3932 4.50727 18.2701 6.27347 18.6129 8.15698C18.9558 10.0405 20.6347 11.3281 22.3629 11.0329Z"
                                fill="black" /> --}}
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

                <div class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-shrink-0 w-full sm:w-auto">
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
                    <div class="text-sm">
                        <span class="font-semibold">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- <select class="appearance-none px-8 py-2 bg-transparent text-gray-600 text-sm focus:outline-none">
                        <option>Text 1</option>
                        <option>Text 2</option>
                        <option>Text 3</option>
                    </select> --}}
                </div>
            </div>
        </div>

        <!-- Konten utama -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl border shadow-md p-6 relative">
            <div class="max-w-3xl mx-auto">
                <!-- Tombol close -->
                <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>

                <!-- Header -->
               <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 mb-8"> 

                    @if ($data->img_profile)
                        <img id="pp" class="w-32 h-32 object-cover rounded-full"
                            src="{{ asset('storage/' . $data->img_profile) }}" alt="Profile">
                    @else
                        <img id="pp" class="w-32 h-32 object-cover rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode($data->nama_pelamar) }}&background=00509d&color=fff&size=128"
                            alt="Profile">
                    @endif

                    <div>
                        <h2 class="text-lg font-bold">{{ $data->nama_pelamar }}</h2>
                        <p class="text-sm font-semibold text-gray-700">
                            {{ $data->deskripsi_diri ?? 'Data Belum Diisi' }}
                        </p>
                    </div>
                </div>

                <!-- Grid data kandidat -->
               <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm"> 

                    <!-- Kolom Kiri -->
                    @if ($data->sosmed)
                        <div>
                            <p><span class="font-semibold mt-4">User ID</span></p>
                            <p class="mb-3">{{ $data->user->id }}</p>
                            <p><span class="font-semibold">Nama Lengkap</span></p>
                            <p class="mb-3">{{ $data->nama_pelamar }}</p>
                            <p><span class="font-semibold">Alamat</span></p>
                            <p class="mb-3">
                                {{ $data->alamat_pelamar->sortByDesc('created_at')->first()->detail ?? 'belum ada data' }}
                            </p>
                            <p><span class="font-semibold">No.Telepon</span></p>
                            <p class="mb-3">{{ $data->telepon_pelamar }}</p>

                            <p class="font-semibold mb-3">Social Media</p>
                            <p class="m-1">Instagram <span class="ml-8"> :
                                    {{ $data->sosmed->instagram ?? 'tidak ada data' }}</span></p>
                            <p class="m-1">Linkedln <span class="ml-12"> :
                                    {{ $data->sosmed->linkedin ?? 'tidak ada data' }}</span></p>
                            <p class="m-1">Website <span class="ml-12"> :
                                    {{ $data->sosmed->website ?? 'tidak ada data' }}</span></p>
                            <p class="m-1">Twitter <span class="ml-14"> :
                                    {{ $data->sosmed->twitter ?? 'tidak ada data' }}</span></p>
                        </div>
                    @else
                        <p class="text-gray-500">Data Belum Diisi</p>
                    @endif

                    <!-- Kolom Kanan -->
                    <div>
                        <p><span class="font-semibold">Username</span></p>
                        <p class="mb-3">{{ $data->user->username }}</p>
                        <p><span class="font-semibold">Email</span></p>
                        <p class="mb-3"> {{ $data->user->email }}</p>
                        <p><span class="font-semibold">Gender</span></p>
                        <p class="mb-3"> {{ $data->gender ?? 'Laki Laki' }}</p>
                        <p><span class="font-semibold">Keahlian</span></p>
                        <p> {{ $data->skill->sortByDesc('created_at')->first()->skill ?? 'Sepakbola' }}</p>
                    </div>
                </div>

                <!-- Organisasi -->
                <div class="mt-6 text-sm space-y-4"> 

                    <h3 class="font-medium mb-2">Organisasi</h3>
                    @forelse ($data->pengalaman_organisasi as $org)
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                {{ $org->jabatan }} - {{ $org->nama_organisasi }}
                                ({{ $org->tahun_awal }} - {{ $org->tahun_akhir }})
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $org->deskripsi }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">Data Belum Terisi</p>
                    @endforelse
                </div>

                <!-- Pengalaman -->
                <div class="mt-4 text-sm">
                    <h3 class="font-medium mb-2">Pengalaman Kerja</h3>
                    @forelse ($data->pengalaman_kerja as $kerja)
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                {{ $kerja->posisi_pekerjaan }} - {{ $kerja->nama_perusahaan }}
                                ({{ $kerja->tahun_awal }} - {{ $kerja->tahun_akhir }})
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $kerja->deskripsi }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">Data Belum Terisi</p>
                    @endforelse
                </div>

                <!-- Pendidikan -->
                <div class="mt-4 text-sm">
                    <h3 class="font-medium mb-2">Riwayat Pendidikan</h3>
                    @forelse ($data->riwayat_pendidikan as $pend)
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                {{ $pend->asal_pendidikan }} - {{ $pend->pendidikan }}
                                ({{ $pend->tahun_awal }} - {{ $pend->tahun_akhir }})
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $pend->jurusan }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">Data Belum Terisi</p>
                    @endforelse
                </div>
            </div>

            <!-- Tombol aksi -->
            <div class="grid grid-cols-1 space-y-3 mx-auto max-w-72 mt-20">
                @php
                    $mapKategori = [
                        'pelamar' => 'non_kandidat',
                        'calon kandidat' => 'calon_kandidat',
                        'kandidat aktif' => 'kandidat',
                    ];
                    $kategori = $mapKategori[strtolower($data->kategori)] ?? 'non_kandidat';
                @endphp

                <a href="{{ route('superadmin.pelamar.edit', ['kategori' => $kategori, 'id' => $data->id]) }}"
                    class="bg-blue-500 hover:bg-blue-400 text-white px-6 py-2 rounded-lg text-center transition duration-300">
                    Edit
                </a>
                {{-- <a href="{{ route('cv.save', $data->id) }}"
                        class="bg-green-600 hover:bg-navy-500 text-white px-6 py-2 rounded-lg">
                        Simpan CV Ke Server
                    </a> --}}

                <a href="{{ route('cv.preview', $data->id) }}"
                    class="bg-orange-500 hover:bg-orange-400 text-center text-white px-6 py-2 rounded-lg">
                    Preview
                </a>

                <a href="{{ route('cv.download', $data->id) }}"
                    class="bg-green-600 hover:bg-green-500 text-center text-white px-6 py-2 rounded-lg ">
                    Unduh
                </a>

                <form action="{{ route('superadmin.pelamar.destroy', $data->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus pelamar ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-500 text-white px-[120px] py-2 rounded-lg">Hapus</button>
                </form>
            </div><br>


            @include('super_admin.notif.modal_notif')
            @include('super_admin.notif.modal_semua')
            @include('cv.template')
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
