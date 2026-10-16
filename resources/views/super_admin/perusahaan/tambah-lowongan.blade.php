@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <!-- HEADER TOP BAR -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-6 bg-[#00509d] text-white p-3.5 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.detail.perusahaan', $perusahaan->id) }}"
                        class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition flex-shrink-0"
                        title="Kembali ke Detail Perusahaan">
                        <i class="ph ph-arrow-left text-base"></i>
                    </a>
                    <div>
                        <h1 class="text-base sm:text-xl font-bold text-white tracking-tight">
                            Tambah Lowongan - {{ $perusahaan->nama_perusahaan }}
                        </h1>
                        <p class="text-[11px] sm:text-xs text-blue-100 mt-0.5">Buat lowongan pekerjaan baru untuk perusahaan</p>
                    </div>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                    @include('super_admin.components.notif_button')
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>



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
                    if (!await swalConfirm("Hapus notifikasi ini?")) return;

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
                    if (!await swalConfirm("Hapus semua notifikasi?")) return;

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
                    if (!await swalConfirm("Hapus semua notifikasi yang sudah dibaca?")) return;

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
