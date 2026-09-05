@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <div class="w-full h-screen translate-x-4 overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <main class="flex-1 p-6 bg-white sm:ml-64">
            <div class="flex flex-wrap justify-end items-center gap-3 mb-6">
                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative flex-shrink-0">
                    <!-- Icon Lonceng -->
                    <svg class="w-8 h-8 sm:w-10 sm:h-10" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>

                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                {{-- Profile --}}
                <div
                    class="flex items-center gap-2 bg-white px-2 sm:px-4 py-1 border border-gray-600 shadow-md rounded-2xl flex-shrink-0">
                    <a href="{{ route('superadmin.profile') }}">
                        @if (Auth::user()->role == 'super_admin')
                            @if (Auth::user()->superadmin?->img_profile)
                                <img id="pu" class="w-8 h-8 sm:w-10 sm:h-10 object-cover rounded-full profile-img"
                                    src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                            @else
                                <img id="pu" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="">
                            @endif
                        @else
                            <img class="w-8 h-8 sm:w-10 sm:h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    </a>
                    <div class="text-sm truncate max-w-[120px] sm:max-w-xs">
                        <span class="font-semibold truncate">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-xs sm:text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
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
                    <div class="flex flex-col sm:flex-row justify-end sm:justify-end gap-4 text-xs text-orange-600 mb-6">
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
                            class="w-full sm:w-auto hover:underline flex items-center justify-center gap-1 py-1 px-2 rounded-md border border-orange-600 text-orange-600">
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
                                class="w-full bg-orange-600 text-white font-medium py-2 rounded-lg hover:bg-orange-500 transition duration-300">
                                Hapus dari Rekomendasi
                            </button>
                        @else
                            <button type="submit"
                                class="w-full bg-orange-700 text-white font-medium py-2 rounded-lg hover:bg-orange-600 transition duration-300">
                                Jadikan Rekomendasi
                            </button>
                        @endif
                    </form>

                    <!-- Tombol Kembali -->
                    <a href="{{ route('superadmin.perusahaan.detail', $lowongan->perusahaan_id) }}"
                        class="w-full sm:w-auto bg-orange-500 text-white text-center py-2 px-4 rounded-md hover:bg-orange-600 transition duration-300">
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
