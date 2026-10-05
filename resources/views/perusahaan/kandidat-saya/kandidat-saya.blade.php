@extends('layouts.index-perusahaan')
@section('content')
    <div class="max-w-6xl mt-24 mx-auto p-4 sm:p-7 rounded-lg">
        <h2 class="text-lg font-semibold mb-4">Kandidat Saya</h2>
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center mb-4 gap-6">
            
            @php
                // Ambil semua skill unik dari seluruh pelamar yang ada di data recruitments
                $skillList = collect($recruitments)->pluck('pelamar.skill')->flatten()->unique('skill')->values();
            @endphp

            <div class="flex flex-col sm:flex-row sm:gap-4 gap-6">
                <form action="" method="get" class="flex flex-col sm:flex-row sm:items-center sm:gap-4 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="nama kandidat/username ..." class="border rounded-lg px-8 py-2 text-sm w-full sm:w-72 border-gray-300 shadow-lg">

                    <select name="skill" class="border border-gray-300 rounded-lg px-10 py-2 text-sm shadow-lg">
                        <option value="">Skill</option>
                        @foreach ($skillList as $skill)
                            <option value="{{ $skill->skill }}" {{ request('skill') == $skill->skill ? 'selected' : '' }}>
                                {{ $skill->skill }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-[#00509d] text-white px-8 py-2 rounded-lg hover:bg-[#003d7a] shadow-lg">
                        Cari
                    </button>
                </form>
            </div>

        </div>

        <!-- Table -->
        <div class="overflow-x-auto border border-gray-300 rounded-2xl mb-4 mt-8 shadow-lg">
            <table class="w-full border-collapse bg-white">
                <thead>
                    <tr>
                        <th class="p-4 sm:p-5 text-center font-semibold">Profil</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">Nama</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">Skill</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">CV</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">Aksi</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">Lowongan</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">Ekspektasi Range Gaji</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">Status</th>
                        <th class="p-4 sm:p-5 text-center font-semibold">Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recruitments as $r)
                        @php
                            $pelamar = $r->pelamar;
                            $skillUtama = $pelamar->skill->first()->skill ?? '-';
                            $isKandidat = ($pelamar && in_array(strtolower($pelamar->kategori ?? ''), ['kandidat aktif', 'kandidat', 'calon_kandidat', 'calon kandidat']));
                            $statusLower = strtolower($r->status ?? '');

                            if ($statusLower === 'ditolak') {
                                $actionLabel = 'Hapus';
                                $confirmTitle = 'Hapus Data Rekrutan?';
                                $confirmMsg = 'Apakah Anda yakin ingin menghapus data kandidat yang telah menolak ini?';
                                $confirmBtn = 'Ya, Hapus!';
                            } elseif ($isKandidat) {
                                $actionLabel = 'Batalkan Rekrutan';
                                $confirmTitle = 'Batalkan Rekrutan?';
                                $confirmMsg = 'Apakah Anda yakin ingin membatalkan rekrutan kandidat ini?';
                                $confirmBtn = 'Ya, Batalkan!';
                            } else {
                                $actionLabel = 'Tolak Lamaran';
                                $confirmTitle = 'Tolak Lamaran?';
                                $confirmMsg = 'Apakah Anda yakin ingin menolak lamaran pelamar ini?';
                                $confirmBtn = 'Ya, Tolak!';
                            }
                        @endphp
                        <tr class="border-b">
                            <!-- Profil (Foto) -->
                            <td class="p-3 text-center">
                                <div class="flex justify-center">
                                    @if (!empty($pelamar->img_profile))
                                        <img src="{{ asset('storage/' . $pelamar->img_profile) }}"
                                            alt="Foto Profile" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center text-gray-800 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 256 256">
                                                <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24ZM74.08,197.5a64,64,0,0,1,107.84,0,87.83,87.83,0,0,1-107.84,0ZM96,120a32,32,0,1,1,32,32A32,32,0,0,1,96,120Zm97.76,66.41a79.66,79.66,0,0,0-36.06-28.75,48,48,0,1,0-59.4,0,79.66,79.66,0,0,0-36.06,28.75,88,88,0,1,1,131.52,0Z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <!-- Nama -->
                            <td class="p-3 text-center">
                                <span class="font-medium text-gray-800">{{ $pelamar->nama_pelamar }}</span>
                            </td>
                            <!-- Skill -->
                            <td class="p-3 text-center">{{ $skillUtama }}</td>
                            <!-- CV -->
                            <td class="p-3">
                                <div class="flex flex-col items-center text-[#00509d]">
                                    <button onclick="openConfirmModal({{ $pelamar->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="#fb923c">
                                            <rect x="4" y="19" width="16" height="3" />
                                            <rect x="10" y="3" width="4" height="11" />
                                            <path d="M7 13l5 5 5-5z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <!-- Aksi (Batalkan Rekrutan / Tolak Lamaran / Hapus) -->
                            <td class="p-3 text-center">
                                <form action="{{ route('perusahaan.destroy.kandidat', $r->id) }}" method="POST"
                                    onsubmit="confirmDeleteAction(event, this, '{{ addslashes($confirmTitle) }}', '{{ addslashes($confirmMsg) }}', '{{ addslashes($confirmBtn) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2.5 py-1 text-xs font-semibold rounded-md transition flex items-center justify-center gap-1 mx-auto bg-red-50 text-red-600 hover:bg-red-100 border border-red-200"
                                        title="{{ $actionLabel }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                        </svg>
                                        <span>{{ $actionLabel }}</span>
                                    </button>
                                </form>
                            </td>
                            <!-- Gaji -->
                            <td class="p-3 text-center">{{ $r->lowonganPerusahaan->nama ?? $r->lowongan_perusahaan->nama }}
                            </td>
                            <td class="p-3 text-center">Rp. {{ number_format($pelamar->gaji_maksimal, 0, ',', '.') }}</td>
                            <!-- Status -->
                            <td class="p-3 text-center font-medium">
                                @if (strtolower($r->status) === 'pending')
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold inline-block">
                                        Pending (Menunggu)
                                    </span>
                                @elseif (strtolower($r->status) === 'diterima')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold inline-block">
                                        Diterima
                                    </span>
                                @elseif (strtolower($r->status) === 'ditolak')
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold inline-block" title="Alasan: {{ $r->alasan_penolakan }}">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold inline-block">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-center font-medium">
                                @if ($pelamar && in_array(strtolower($pelamar->kategori ?? ''), ['kandidat aktif', 'kandidat', 'calon_kandidat', 'calon kandidat']))
                                    <span class="text-purple-600 font-semibold bg-purple-50 px-2.5 py-1 rounded-md border border-purple-200 inline-block text-xs">
                                        Pembelian Kandidat
                                    </span>
                                @else
                                    <span class="text-blue-600 font-semibold bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200 inline-block text-xs">
                                        Melamar Lowongan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="border-b">
                            <td colspan="9" class="p-4 text-center text-gray-500 font-medium">Tidak ada kandidat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- MODAL CV --}}
    <div id="confirmModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white p-6 scale-[0.85] md:scale-100 rounded-lg text-center max-w-sm w-full">
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#00509d] mb-4" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M12 16l4-5h-3V4h-2v7H8l4 5zM4 20h16v2H4z" />
                </svg>
            </div>
            <p class="mb-4 font-medium">Yakin akan mengunduh CV pelamar?</p>
            <div class="flex justify-center gap-4">
                <button onclick="downloadCV()" class="px-4 py-2 bg-[#00509d] text-white rounded">Unduh</button>
                <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-black rounded">Batal</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Sukses -->
    <div id="successModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white p-6 rounded-lg text-center max-w-sm w-full">
            <div class="flex justify-center">
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#00509d]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 font-semibold">CV Berhasil diunduh</p>
            <button onclick="closeSuccessModal()" class="mt-4 px-4 py-2 bg-[#00509d] text-white rounded">Tutup</button>
        </div>
    </div>

    @include('layouts.footer')

    {{-- CV --}}
    <script>
        let selectedId = null;

        function openConfirmModal(id) {
            selectedId = id;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function downloadCV() {
            if (!selectedId) return;
            closeConfirmModal();
            document.getElementById('successModal').classList.remove('hidden');
            setTimeout(() => {
                let url = "{{ route('cv.download', ':id') }}";
                url = url.replace(':id', selectedId);
                window.location.href = url;
            }, 500);
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
        }

        // SweetAlert2 Konfirmasi Aksi (Batalkan Rekrutan / Tolak Lamaran / Hapus)
        function confirmDeleteAction(e, form, title, text, confirmBtn) {
            e.preventDefault();

            Swal.fire({
                title: title || 'Konfirmasi Aksi',
                text: text || 'Apakah Anda yakin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmBtn || 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl shadow-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection

