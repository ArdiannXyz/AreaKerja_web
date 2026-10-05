@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <div class="w-full h-screen translate-x-4 overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 sm:gap-0">
                <h1 class="text-2xl font-medium truncate max-w-full">Detail Talent Hunter</h1>

                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    {{-- Tombol Notifikasi --}}
                    @include('super_admin.components.notif_button')

                    {{-- User Badge Dropdown --}}
                    @include('super_admin.components.user_badge_dropdown')
                </div>
            </div>


            <!-- Konten utama -->
            <div class="max-w-6xl mx-auto bg-white rounded-xl p-4 sm:p-6">
                <div class="max-w-5xl mx-auto border border-gray-400 rounded-xl shadow p-4 sm:p-6">
                    <!-- Header -->
                    <div
                        class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 border border-gray-400 rounded-xl shadow-lg p-4">
                        <img src="{{ $talentHunter->perusahaan->img_profile ? asset('storage/' . $talentHunter->perusahaan->img_profile) : asset('images/seven.png') }}"
                            alt="foto kandidat" class="w-full sm:w-64 h-auto object-cover rounded-xl">

                        <div class="flex-1 text-center sm:text-left">
                            <h2 class="text-xl font-bold uppercase truncate">
                                {{ $talentHunter->perusahaan->nama_perusahaan }}
                            </h2>
                        </div>
                    </div>

                    <div class="max-w-4xl mx-auto bg-white p-4 sm:p-8 mt-4">
                        <h2 class="text-lg font-semibold mb-2">Deskripsi</h2>
                        @if (empty($talentHunter->deskripsi))
                            <p class="font-bold text-red-500 m-2">Perusahaan Belum Menyelesaikan Bagian Ini</p>
                        @else
                            <p class="font-normal text-black m-2 break-words">{{ $talentHunter->deskripsi }}</p>
                        @endif

                        <h2 class="text-lg font-semibold mt-4 mb-2">Alamat Perusahaan</h2>
                        <p class="font-normal mb-4 break-words">{{ $talentHunter->alamat ?? '-' }}</p>

                        <h2 class="text-lg font-semibold mb-2">Kriteria Kandidat</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <p>Posisi Yang Dibutuhkan: <span class="font-medium">{{ $talentHunter->posisi ?? '-' }}</span>
                            </p>
                            <p>Jenis Kelamin: <span class="font-medium">{{ $talentHunter->gender ?? '-' }}</span></p>
                            <p>Kisaran Gaji: <span class="font-medium">{{ $talentHunter->gaji_awal ?? '-' }} -
                                    {{ $talentHunter->gaji_akhir ?? '-' }}</span></p>
                            <p>Pengalaman Kerja: <span
                                    class="font-medium">{{ $talentHunter->pengalaman_kerja ?? '-' }}</span></p>
                            <p>Kontak Perusahaan: <span
                                    class="font-medium">{{ $talentHunter->perusahaan->telepon_perusahaan ?? '-' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div
                    class="flex flex-col sm:flex-row justify-center sm:justify-center space-y-2 sm:space-y-0 sm:space-x-4 mt-6">
                    <a href="{{ route('superadmin.talent-hunter.edit', $talentHunter->id) }}"
                        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-2 rounded-lg shadow text-center w-full sm:w-auto">
                        Edit
                    </a>

                    <a href="{{ route('superadmin.talent-hunter') }}"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow text-center w-full sm:w-auto">
                        Kembali
                    </a>
                </div>
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
@endsection
