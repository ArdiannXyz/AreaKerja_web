@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
            <h1 class="text-2xl font-bold text-gray-800 flex-1 min-w-[150px]">
                Detail Perusahaan
            </h1>

            <div class="flex flex-wrap items-center gap-3 flex-1 justify-end min-w-[200px]">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>


        <!-- Konten utama -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl p-6 relative">
            <div class="max-w-5xl mx-auto">
                <!-- Header -->
                <div class="flex flex-wrap items-center border border-gray-400 rounded-xl shadow-lg py-4 gap-4">
                    <!-- Gambar -->
                    <img src="{{ $perusahaan->img_profile ? asset('storage/' . $perusahaan->img_profile) : asset('images/seven.png') }}"
                        alt="foto kandidat" class="w-full sm:w-64 h-auto object-cover rounded-lg flex-shrink-0">

                    <!-- Nama Perusahaan -->
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl font-bold uppercase break-words">{{ $perusahaan->nama_perusahaan }}</h2>
                    </div>
                </div>


                <!-- Grid data kandidat -->
                <div class="max-w-4xl mx-auto bg-white p-6 sm:p-8">
                    <!-- Deskripsi -->
                    <h2 class="text-lg font-semibold mb-2">Deskripsi</h2>
                    <p class="text-sm font-medium text-gray-800 mb-6 break-words">
                        {{ $perusahaan->deskripsi ?? 'Belum Ada Data' }}
                    </p>

                    <!-- Visi -->
                    <h2 class="text-lg font-semibold mb-2">Visi</h2>
                    <ul class="list-disc font-medium list-inside text-sm text-gray-800 mb-6 break-words">
                        <li>{{ $perusahaan->visi ?? 'Belum Ada Data' }}</li>
                    </ul>

                    <!-- Misi -->
                    <h2 class="text-lg font-semibold mb-2">Misi</h2>
                    <ul class="list-disc font-medium list-inside text-sm text-gray-800 mb-6 break-words">
                        <li>{{ $perusahaan->misi ?? 'Belum Ada Data' }}</li>
                    </ul>

                    <!-- Data Perusahaan -->
                    <h2 class="text-lg font-semibold mb-2">Data Perusahaan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 font-medium text-sm text-gray-800 mb-6 gap-y-2 gap-x-4">
                        <p>User ID</p>
                        <p class="break-words">: {{ $perusahaan->user->id }}</p>

                        <p>Username</p>
                        <p class="break-words">: {{ $perusahaan->user->username }}</p>

                        <p>Email</p>
                        <p class="break-words">: {{ $perusahaan->user->email }}</p>

                        <p>Kata Sandi</p>
                        <p>: ********</p>

                        <p>Nama Perusahaan</p>
                        <p class="break-words">: {{ $perusahaan->nama_perusahaan }}</p>

                        <p>Legalitas</p>
                        <p class="break-words">: {{ $perusahaan->legalitas ?? 'Belum Ada Data' }}</p>
                    </div>

                    <!-- Kontak -->
                    <h2 class="text-lg font-semibold mb-2">Kontak</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 font-medium text-sm text-gray-800 mb-6 gap-y-2 gap-x-4">
                        <p>Perusahaan</p>
                        <p class="break-words font-semibold">: {{ $perusahaan->telepon_perusahaan }}</p>
                        <p>Whatsapp</p>
                        <p class="break-words font-semibold">: {{ $perusahaan->whatsapp ?? 'Belum Ada Data' }}</p>
                    </div>

                    <!-- Lowongan -->
                    <h2 class="text-lg font-semibold mb-2">Lowongan</h2>
                    @if ($perusahaan->lowonganPerusahaans->count())
                        <div class="text-sm font-medium space-y-2">
                            @foreach ($perusahaan->lowonganPerusahaans as $l)
                                <div class="break-words">
                                    <a href="{{ route('superadmin.lowongan.detail', [
                                        'perusahaan' => $perusahaan->slug,
                                        'lowongan' => $l->slug,
                                    ]) }}"
                                        class="text-blue-500 text-sm font-semibold hover:underline mb-1 block sm:inline">{{ $l->nama }}</a>
                                    <p class="text-gray-400 mb-1">{{ $l->alamat }}</p>
                                    <p class="text-gray-400">{{ $l->published_at ?? $l->created_at }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tombol aksi -->
                <div class="flex flex-col items-center space-y-3 w-full max-w-lg mx-auto mt-10 sm:mt-44 px-4">
                    <!-- Tombol Tambah Lowongan -->
                    <a href="{{ route('superadmin.lowongan.create.form', $perusahaan->id) }}"
                        class="w-full sm:px-24 px-6 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 mb-4 text-center">
                        Tambah Lowongan
                    </a>

                    <!-- Tombol Edit -->
                    <a href="{{ route('superadmin.edit.user', $perusahaan->user->id) }}"
                        class="w-full sm:px-24 px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-800 text-center">
                        Edit
                    </a>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('superadmin.delete.akun', $perusahaan->user->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus user ini? Data tidak bisa dikembalikan!')"
                        class="w-full">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full sm:px-24 px-6 py-2 bg-red-700 text-white rounded-md hover:bg-red-600 text-center">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            @include('super_admin.notif.modal_notif')
            @include('super_admin.notif.modal_semua')
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
