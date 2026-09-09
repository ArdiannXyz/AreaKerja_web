@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <div class="p-2 sm:ml-64 bg-white min-h-screen font-sans overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <!-- Konten utama -->
        <main class="flex-1 p-4 sm:p-8 md:p-20 bg-white font-sans text-gray-900">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 space-y-2 sm:space-y-0">
                <!-- Kiri: Judul -->
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-700 truncate">
                    Tambah Lowongan
                </h1>

                <!-- Kanan: Notifikasi + Profil -->
                <div class="flex items-center space-x-2 sm:space-x-1 flex-wrap sm:flex-nowrap">
                    <!-- Ikon Notifikasi -->
                    @include('super_admin.components.notif_button')

                    {{-- User Badge Dropdown --}}
                    @include('super_admin.components.user_badge_dropdown')
                </div>
            </div>



            <!-- Card Container -->
            <div class="border border-gray-500 rounded-xl p-6 sm:p-16 max-w-4xl mx-auto">
                <h2 class="font-bold mb-6 text-gray-600 text-lg px-2 sm:px-6 py-2 truncate">Tambah Data Lowongan</h2>

                <!-- Form -->
                <form action="{{ route('superadmin.lowongan.saya.store', $perusahaan->id) }}" method="POST"
                    class="space-y-6">
                    @csrf

                    <!-- Baris 1: Judul & Alamat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="w-full">
                            <label for="judul" class="block text-sm font-bold mb-1 truncate">
                                Judul <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="judul" name="nama" required
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none truncate" />
                        </div>

                        <!-- Alamat -->
                        <div class="w-full">
                            <label for="alamat" class="block text-sm font-medium truncate">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="alamat" name="alamat"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none truncate" />
                        </div>
                    </div>

                    <!-- Baris 2: Jenis Lowongan, Gaji, Kategori -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                        <!-- Jenis Lowongan -->
                        <div class="w-full">
                            <label for="jenis" class="block font-semibold text-sm mb-1 truncate">
                                Jenis Lowongan <span class="text-red-600">*</span>
                            </label>
                            <select id="jenis" name="jenis"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-700 focus:outline-none truncate">
                                <option selected disabled value="">Pilih Jenis</option>
                                <option value="Fulltime">Full Time</option>
                                <option value="Partime">Part Time</option>
                                <option value="Freelance">Freelance</option>
                            </select>
                        </div>
                        
                        <!-- Gaji Min -->
                        <div class="w-full">
                            <label for="gaji_awal" class="block font-semibold text-sm mb-1 truncate">
                                Gaji <span class="text-red-600">*</span>
                            </label>
                            <input type="number" id="gaji_awal" name="gaji_awal" placeholder="Min"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-700 focus:outline-none truncate">
                        </div>

                        <!-- Gaji Max -->
                        <div class="w-full">
                            <label for="gaji_akhir" class="block font-semibold text-sm mb-1 invisible">Max</label>
                            <input type="number" id="gaji_akhir" name="gaji_akhir" placeholder="Max"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-700 focus:outline-none truncate">
                        </div>

                        <!-- Kategori -->
                        <div class="w-full">
                            <label class="block font-semibold text-sm mb-1 truncate">Kategori</label>
                            <select name="kategori"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-700 focus:outline-none truncate">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->nama }}"
                                        {{ old('kategori', $lowongan->kategori ?? '') == $cat->nama ? 'selected' : '' }}>
                                        {{ $cat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>



                    <!-- Label Gaji & Benefit -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Label Gaji -->
                        <div class="w-full">
                            <label for="label_gaji" class="block text-sm font-bold mb-1 truncate">
                                Label Gaji <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="label_gaji" name="label_gaji" required
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none truncate" />
                        </div>

                        <!-- Benefit -->
                        <div class="w-full">
                            <label for="benefit" class="block text-sm font-medium truncate">
                                Benefit <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="benefit" name="benefit" required
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none truncate" />
                        </div>

                    </div>


                    <!-- Deskripsi -->
                    <div class="w-full">
                        <label for="deskripsi" class="block text-sm font-bold mb-1 truncate">
                            Deskripsi <span class="text-red-600">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" required
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none resize-none"></textarea>
                    </div>

                    <!-- Tanggung Jawab -->
                    <div class="w-full">
                        <label for="tanggung_jawab" class="block text-sm font-bold mb-1 truncate">
                            Tanggung Jawab <span class="text-red-600">*</span>
                        </label>
                        <textarea id="tanggung_jawab" name="tanggung_jawab" rows="5" required
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none resize-none"></textarea>
                    </div>


                    <!-- Syarat Pekerjaan -->
                    <div class="w-full">
                        <p class="text-sm font-semibold mb-2 truncate">Syarat Pekerjaan</p>

                        <!-- Pendidikan -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-start">
                            <label class="w-full sm:w-32 text-sm font-medium mb-1 sm:mb-0 pt-1 truncate">
                                Pendidikan <span class="text-red-600">*</span>
                            </label>
                            <div class="flex-1">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-4 gap-y-2">
                                    @foreach (['SD', 'SMP', 'SMA', 'SMK', 'S1', 'S2', 'S3'] as $pend)
                                        <label class="flex items-center gap-2 text-sm truncate">
                                            <input class="border border-blue-700" type="radio"
                                                value="{{ $pend }}" name="syarat_pekerjaan">
                                            <span>{{ $pend }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Batas Waktu -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-2">
                            <label for="batas_lamaran" class="w-full sm:w-32 text-sm font-medium truncate">
                                Batas Waktu <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="batas_lamaran"
                                class="w-full sm:w-40 border border-gray-400 rounded px-3 py-2 focus:outline-none text-sm" />
                        </div>

                        <!-- Tombol -->
                        <div class="flex flex-col sm:flex-row justify-center gap-3 mt-8">
                            <button type="submit"
                                class="bg-blue-700 text-white text-sm px-7 py-2 rounded-lg hover:bg-blue-800 transition w-full sm:w-auto">
                                Simpan
                            </button>
                        </div>
                    </div>

                </form>

            </div>
            @include('super_admin.notif.modal_notif')
            @include('super_admin.notif.modal_semua')
        </main>
    </div>

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
