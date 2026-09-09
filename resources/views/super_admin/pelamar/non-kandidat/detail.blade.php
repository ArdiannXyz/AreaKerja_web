@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
     <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"> 
        <!-- 🔧 UPDATED -->
            <h1 class="text-2xl font-medium">Detail Non Kandidat</h1>
            <div class="flex items-center gap-3">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')
                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')

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
                    class="bg-blue-700 hover:bg-blue-600 text-center text-white px-6 py-2 rounded-lg">
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
