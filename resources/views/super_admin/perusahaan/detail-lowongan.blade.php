@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <div class="w-full h-screen translate-x-4 overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <main class="flex-1 p-6 bg-white sm:ml-64">
            <div class="flex flex-wrap justify-end items-center gap-3 mb-6">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>


            <!-- Konten utama -->
            <div class="max-w-6xl mx-auto bg-white rounded-xl p-4 sm:p-6 relative">
                <div class="max-w-5xl mx-auto border border-gray-400 rounded-xl shadow">

                    <!-- Header -->
                    <div
                        class="flex flex-col sm:flex-row items-center sm:items-start border border-gray-400 rounded-xl shadow-lg py-2 sm:py-4 gap-4 mb-4">
                        <img src="{{ $lowongan->perusahaan->img_profile ? asset('storage/' . $lowongan->perusahaan->img_profile) : asset('images/seven.png') }}"
                            alt="foto kandidat" class="w-full sm:w-64 sm:h-64 object-cover rounded-lg">
                        <div class="sm:ml-4 text-center sm:text-left w-full">
                            <h2 class="text-xl font-bold break-words">{{ $lowongan->nama }}</h2>
                        </div>
                    </div>

                    <!-- Aksi Lowongan -->
                    <div class="flex flex-col sm:flex-row justify-end sm:justify-end gap-4 text-xs text-blue-800 mb-6">
                        <form action="{{ route('superadmin.lowongan.destroy', $lowongan->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');" class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full sm:w-auto hover:underline flex items-center justify-center gap-1 text-red-600 py-1 px-2 rounded-md border border-red-600">
                                <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <mask id="mask0_733_9200" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                        width="21" height="20">
                                        <rect width="20.0843" height="19.8054" fill="url(#pattern0_733_9200)" />
                                    </mask>
                                    <g mask="url(#mask0_733_9200)">
                                        <rect width="20.0843" height="19.8054" fill="#FF6109" />
                                    </g>
                                    <defs>
                                        <pattern id="pattern0_733_9200" patternContentUnits="objectBoundingBox"
                                            width="1" height="1">
                                            <use xlink:href="#image0_733_9200"
                                                transform="matrix(0.010272 0 0 0.0104167 0.00694319 0)" />
                                        </pattern>
                                        <image id="image0_733_9200" width="96" height="96" preserveAspectRatio="none"
                                            xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAABZ0lEQVR4nO3dMU7DQBBG4R+k3AVySkoKQHAb4BrcgCOQMkihcApEA3Fm8tbhfdK2q/W8xHLkIokkSZIkjeUmye7IdXvyU5+JiuEbYabK4RvhQB3DHzbCRcEeu4I9luyoGV5WnULzGABmAJgBYAaAGQBmAEmSJEk6sYr3AT+d+/uB0pn5SxhmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYB0BNg17juKjesOOAO8Ne46i/No6Arw27DmKZ/oAf7FO8pm+/wKj1jbJVeGcWj2FH1j1eiidULNVplsRPbSq9bK/pkVZJXnM9NWlBzh3bTN98hc3/O/WSe6TvGV6RKWH+tva7M96l+S6YR6SJEmS/rkvrDJThoEm4u8AAAAASUVORK5CYII=" />
                                    </defs>
                                </svg>
                                Hapus Lowongan
                            </button>
                        </form>

                        <a href="{{ route('superadmin.lowongan.edit.form', $lowongan->id) }}"
                            class="w-full sm:w-auto hover:underline flex items-center justify-center gap-1 py-1 px-2 rounded-md border border-blue-800 text-blue-800">
                            <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <mask id="mask0_733_9205" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                    width="23" height="23">
                                    <rect x="0.0859375" width="22.6236" height="22.3094" fill="url(#pattern0_733_9205)" />
                                </mask>
                                <g mask="url(#mask0_733_9205)">
                                    <rect x="0.0859375" width="22.6236" height="22.3094" fill="#FF6109" />
                                </g>
                                <defs>
                                    <pattern id="pattern0_733_9205" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_733_9205"
                                            transform="matrix(0.010272 0 0 0.0104167 0.00694314 0)" />
                                    </pattern>
                                    <image id="image0_733_9205" width="64" height="64" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAABtklEQVR4nO3aMU7DMBiG4Q9YGOAM3IOJQyCuwtgsSByJBQkheoQepBNjGaBShQKJ7d/+6uZ9JI+NnPdvmrSqBAAAAAA49CBpLWkraSPpSdKldUcLspK0G1nvkq6M+1qEQePx92st6dq2uxM3FZ8hVDQ3PkOoIDU+QwiUG58hBCiNXzSE8+Lt9+8z6Di3kl7ElZDlUTFXAR9HBRjCEYi6H+y/MU/+bHERfQade5N0Juku4Fg3+u77GnCsxYn6ONq03vixGn5W6mtKB7AN2Hv3DkO2HsJH+fb7Nhaw5RDui8+gY/+FazGEVfEZdGxOsOfEY6bcmIk/c9UYAvETV+QQiJ+5IoZA/MJVMgTiB62cp6PU15yUyPi5V8Ji1YjPEGaqGZ8hTGgRnyH8oWV8hvCLI371IfTyr4hB3mftqH9OdMn5zt+JL1nEdyG+EfGNiG9EfCPiGxHfiPhGxDcivhHxjYhvRHwj4hsR34j4RsQ3Ir4R8Y2Ib0R8I+IbEd+I+EbENyK+EfGNiG9GfDPimxHfjPhmxDcjvhnxzYhvRnwz4psR34z4ZsQ3I74Z8c2IDwAAAAAAAGT4AmWLJrfB4zyeAAAAAElFTkSuQmCC" />
                                </defs>
                            </svg>
                            Edit Lowongan
                        </a>
                    </div>

                    <!-- Konten Lowongan -->
                    <div class="p-4 sm:p-6 space-y-4">
                        <!-- Gaji -->
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Gaji</h3>
                            <p>Rp.{{ $lowongan->gaji_awal }} – Rp.{{ $lowongan->gaji_akhir }} per bulan</p>
                        </div>

                        <!-- Jenis Lowongan -->
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Jenis Lowongan</h3>
                            <p>{{ $lowongan->jenis }}</p>
                        </div>

                        <!-- Deskripsi Pekerjaan -->
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Deskripsi Pekerjaan</h3>
                            <p class="break-words">{{ $lowongan->deskripsi }}</p>
                        </div>

                        <!-- Syarat Pekerjaan -->
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Syarat Pekerjaan</h3>
                            <p class="break-words">{{ $lowongan->syarat_pekerjaan }}</p>
                        </div>

                        <!-- Tanggung Jawab -->
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Tanggung Jawab</h3>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach (preg_split("/\r\n|\n|\r/", $lowongan->tanggung_jawab) as $res)
                                    @php
                                        $trim = trim($res);
                                        $isNumbered = preg_match('/^\d+[\.\-\)]\s*/', $trim);
                                    @endphp
                                    @if ($trim !== '')
                                        @if ($isNumbered)
                                            <li style="list-style-type: none;">{{ $trim }}</li>
                                        @else
                                            <li>{{ $trim }}</li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <!-- Aktivitas Lowongan -->
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Aktivitas Lowongan</h3>
                            <p>Lowongan Di Pasang Pada {{ $lowongan->published_at }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="flex flex-col items-center space-y-3 max-w-lg mx-auto mt-6 w-full px-4">
                    <!-- Toggle Rekomendasi -->
                    <form action="{{ route('superadmin.lowongan.toggleRekomendasi', $lowongan->id) }}" method="POST"
                        class="w-full">
                        @csrf
                        @if ($lowongan->rekomendasi !== null)
                            <button type="submit"
                                class="w-full bg-blue-800 text-white font-medium py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                                Hapus dari Rekomendasi
                            </button>
                        @else
                            <button type="submit"
                                class="w-full bg-blue-900 text-white font-medium py-2 rounded-lg hover:bg-blue-800 transition duration-300">
                                Jadikan Rekomendasi
                            </button>
                        @endif
                    </form>

                    <!-- Tombol Kembali -->
                    <a href="{{ route('superadmin.perusahaan.detail', $lowongan->perusahaan_id) }}"
                        class="w-full sm:w-auto bg-blue-700 text-white text-center py-2 px-4 rounded-md hover:bg-blue-800 transition duration-300">
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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
