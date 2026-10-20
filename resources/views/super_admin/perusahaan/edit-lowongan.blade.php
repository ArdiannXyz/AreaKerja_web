@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <!-- HEADER TOP BAR -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-6 bg-[#00509d] text-white p-3.5 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.lowongan.detail', ['perusahaan' => $lowongan->perusahaan->slug, 'lowongan' => $lowongan->id]) }}"
                        class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition flex-shrink-0"
                        title="Kembali ke Detail Lowongan">
                        <i class="ph ph-arrow-left text-base"></i>
                    </a>
                    <div>
                        <h1 class="text-base sm:text-xl font-bold text-white tracking-tight">
                            Edit Lowongan - {{ $lowongan->namaPekerjaan }}
                        </h1>
                        <p class="text-[11px] sm:text-xs text-blue-100 mt-0.5">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
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
            <div class="border border-gray-500 rounded-xl p-6 sm:p-16 max-w-4xl w-full mx-auto">    

                <h2 class="font-bold mb-6 text-gray-600 text-lg px-2 sm:px-6 py-2">Tambah Data Lowongan</h2>

                <!-- Form -->
                <form action="{{ route('superadmin.lowongan.update', $lowongan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Baris 1: Judul & Alamat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="judul" class="block text-sm font-bold mb-1">Judul <span
                                    class="text-red-600">*</span></label>
                            <input type="text" id="judul" name="nama" required
                                value="{{ old('nama', $lowongan->nama) }}"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words" />
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-bold mb-1">
                                Alamat <span class="text-red-600">*</span>
                            </label>

                            <select id="alamat" name="alamat"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words text-sm sm:text-base">
                                <option disabled value="">Pilih Alamat</option>
                                <option value="jakarta"
                                    {{ old('alamat', $lowongan->alamat) == 'jakarta' ? 'selected' : '' }}>Jakarta</option>
                                <option value="bandung"
                                    {{ old('alamat', $lowongan->alamat) == 'bandung' ? 'selected' : '' }}>Bandung</option>
                                <option value="ciamis"
                                    {{ old('alamat', $lowongan->alamat) == 'ciamis' ? 'selected' : '' }}>Ciamis</option>
                                <option value="yogyakarta"
                                    {{ old('alamat', $lowongan->alamat) == 'yogyakarta' ? 'selected' : '' }}>Yogyakarta
                                </option>
                                <option value="solo" {{ old('alamat', $lowongan->alamat) == 'solo' ? 'selected' : '' }}>
                                    Solo</option>
                                <option value="semarang"
                                    {{ old('alamat', $lowongan->alamat) == 'semarang' ? 'selected' : '' }}>Semarang
                                </option>
                                <option value="surabaya"
                                    {{ old('alamat', $lowongan->alamat) == 'surabaya' ? 'selected' : '' }}>Surabaya
                                </option>
                            </select>
                        </div>

                    </div>

                    <!-- Baris 2: Jenis Lowongan & Gaji -->
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
                        <div class="col-span-1 sm:col-span-2">
                            <label for="jenis" class="block text-sm font-bold mb-1">
                                Jenis Lowongan <span class="text-red-600">*</span>
                            </label>

                            <select id="jenis" name="jenis"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words text-sm sm:text-base">
                                <option disabled value="">Pilih Jenis</option>
                                <option value="full_time"
                                    {{ old('jenis', $lowongan->jenis) == 'full_time' ? 'selected' : '' }}>Full Time
                                </option>
                                <option value="part_time"
                                    {{ old('jenis', $lowongan->jenis) == 'part_time' ? 'selected' : '' }}>Part Time
                                </option>
                                <option value="middle_time"
                                    {{ old('jenis', $lowongan->jenis) == 'middle_time' ? 'selected' : '' }}>Middle Time
                                </option>
                                <option value="freelance"
                                    {{ old('jenis', $lowongan->jenis) == 'freelance' ? 'selected' : '' }}>Freelance
                                </option>
                            </select>
                        </div>

                        <div class="col-span-1">
                            <label for="gaji_awal" class="block text-sm font-bold mb-1">Gaji <span
                                    class="text-red-600">*</span></label>
                            <input type="number" id="gaji_awal" name="gaji_awal"
                                value="{{ old('gaji_awal', $lowongan->gaji_awal) }}" placeholder="Min"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none" />
                        </div>

                        <div class="col-span-1">
                            <label for="gaji_akhir" class="block mb-1 invisible">Max</label>
                            <input type="number" id="gaji_akhir" name="gaji_akhir"
                                value="{{ old('gaji_akhir', $lowongan->gaji_akhir) }}" placeholder="Max"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none" />
                        </div>

                        <div class="col-span-1 sm:col-span-1">
                            <label class="block font-semibold text-sm mb-1">Kategori</label>
                            <select name="kategori"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:ring-2 focus:ring-blue-700 focus:outline-none break-words">
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
                        <div>
                            <label for="label_gaji" class="block text-sm font-bold mb-1">
                                Label gaji <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="label_gaji" name="label_gaji" required
                                value="{{ old('label_gaji', $lowongan->label_gaji) }}"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words" />
                        </div>

                        <div>
                            <label for="benefit" class="block text-sm font-bold mb-1">
                                Benefit <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="benefit" name="benefit" required
                                value="{{ old('benefit', $lowongan->benefit) }}"
                                class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words" />
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold mb-1">
                            Deskripsi <span class="text-red-600">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" required
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words resize-none sm:resize-y">{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                    </div>

                    <!-- Tanggung Jawab -->
                    <div>
                        <label for="tanggung_jawab" class="block text-sm font-bold mb-1">
                            Tanggung Jawab <span class="text-red-600">*</span>
                        </label>
                        <textarea id="tanggung_jawab" name="tanggung_jawab" rows="5" required
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none break-words resize-none sm:resize-y">{{ old('tanggung_jawab', $lowongan->tanggung_jawab) }}</textarea>
                    </div>


                    <!-- Syarat Pekerjaan -->
                    <div>
                        <p class="text-sm font-semibold mb-2">Syarat Pekerjaan</p>

                        <!-- Pendidikan -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-start">
                            <label class="w-full sm:w-32 text-sm font-medium mb-2 sm:mb-0">
                                Pendidikan <span class="text-red-600">*</span>
                            </label>

                            <div class="col-span-2 w-full">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-2 gap-y-2">
                                    @foreach (['SD', 'SMP', 'SMA', 'SMK', 'S1', 'S2', 'S3'] as $pend)
                                        <label class="flex items-center gap-2 text-sm whitespace-nowrap">
                                            <input class="border border-blue-700" type="radio"
                                                name="syarat_pekerjaan" value="{{ $pend }}"
                                                {{ old('syarat_pekerjaan', $lowongan->syarat_pekerjaan) == $pend ? 'checked' : '' }}>
                                            <span>{{ $pend }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Batas Waktu -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:gap-4">
                            <label for="batas_lamaran" class="w-full sm:w-32 text-sm font-medium mb-2 sm:mb-0">
                                Batas Waktu <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="batas_lamaran"
                                value="{{ old('batas_lamaran', $lowongan->batas_lamaran) }}"
                                class="w-full sm:w-1/2 border border-gray-400 rounded px-3 py-2 focus:outline-none text-sm" />
                        </div>

                        <!-- Tombol -->
                        <div class="flex flex-col sm:flex-row justify-center gap-3 mt-6">
                            <button type="submit"
                                class="bg-blue-700 text-white text-sm px-7 py-2 rounded-lg hover:bg-blue-800 transition w-full sm:w-auto">
                                Simpan
                            </button>
                            <a href="{{ route('superadmin.lowongan.detail', ['perusahaan' => $lowongan->perusahaan->slug, 'lowongan' => $lowongan->id]) }}"
                                class="border border-blue-800 text-blue-800 text-sm px-7 py-2 rounded-lg hover:bg-gray-100 transition w-full sm:w-auto text-center">
                                Batal
                            </a>
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
