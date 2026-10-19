@extends('layouts.index-perusahaan')
@section('content')
    <div class="max-w-6xl mt-24 mx-auto p-4 sm:p-7 rounded-lg">
        <h2 class="text-lg font-semibold mb-4">Kandidat Saya</h2>

        @if (session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-medium flex items-center gap-2">
                <i class="ph ph-check-circle text-green-600 text-xl shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center gap-2">
                <i class="ph ph-warning-circle text-rose-600 text-xl shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                    <tr class="text-xs text-gray-600 uppercase tracking-wide bg-gray-50">
                        <th class="p-3 text-center font-semibold">Profil</th>
                        <th class="p-3 text-center font-semibold">Nama</th>
                        <th class="p-3 text-center font-semibold">Skill</th>
                        <th class="p-3 text-center font-semibold">CV</th>
                        <th class="p-3 text-center font-semibold">Aksi</th>
                        <th class="p-3 text-center font-semibold">Lowongan</th>
                        <th class="p-3 text-center font-semibold">Ekspektasi Gaji</th>
                        <th class="p-3 text-center font-semibold">Status</th>
                        <th class="p-3 text-center font-semibold">Sumber</th>
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
                        <tr class="border-b hover:bg-gray-50 transition-colors text-sm text-gray-700">
                            <!-- Profil (Foto) -->
                            <td class="p-2.5 text-center">
                                <div class="flex justify-center">
                                    @if (!empty($pelamar->img_profile))
                                        <img src="{{ asset('storage/' . $pelamar->img_profile) }}"
                                            alt="Foto Profile" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center text-gray-800 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 256 256">
                                                <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24ZM74.08,197.5a64,64,0,0,1,107.84,0,87.83,87.83,0,0,1-107.84,0ZM96,120a32,32,0,1,1,32,32A32,32,0,0,1,96,120Zm97.76,66.41a79.66,79.66,0,0,0-36.06-28.75,48,48,0,1,0-59.4,0,79.66,79.66,0,0,0-36.06,28.75,88,88,0,1,1,131.52,0Z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <!-- Nama -->
                            <td class="p-2.5 text-center">
                                <span class="font-medium text-gray-800">{{ $pelamar->nama_pelamar }}</span>
                            </td>
                            <!-- Skill -->
                            <td class="p-2.5 text-center text-gray-700">{{ $skillUtama }}</td>
                            <!-- CV -->
                            <td class="p-2.5 text-center">
                                <button onclick="openConfirmModal({{ $pelamar->id }})" class="text-gray-500 hover:text-gray-700 transition-colors" title="Unduh CV">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <rect x="4" y="19" width="16" height="3" />
                                        <rect x="10" y="3" width="4" height="11" />
                                        <path d="M7 13l5 5 5-5z" />
                                    </svg>
                                </button>
                            </td>
                            <!-- Aksi -->
                            <td class="p-2.5 text-center">
                                <div class="flex flex-col items-center gap-1 justify-center">
                                    <form action="{{ route('perusahaan.destroy.kandidat', $r->id) }}" method="POST"
                                        onsubmit="confirmDeleteAction(event, this, '{{ addslashes($confirmTitle) }}', '{{ addslashes($confirmMsg) }}', '{{ addslashes($confirmBtn) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs text-gray-600 hover:text-gray-900 hover:underline transition flex items-center gap-1"
                                            title="{{ $actionLabel }}">
                                            <span>{{ $actionLabel }}</span>
                                        </button>
                                    </form>
                                    @if ($pelamar)
                                        <button type="button"
                                            onclick="openReportKandidatModal({{ $pelamar->id }}, '{{ addslashes($pelamar->nama_pelamar ?? 'Kandidat') }}', '{{ $r->lowongan_id ?? '' }}')"
                                            class="text-xs text-gray-500 hover:text-gray-800 hover:underline transition flex items-center gap-1"
                                            title="Laporkan Pelamar / Kandidat">
                                            <span>Laporkan</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <!-- Lowongan -->
                            <td class="p-2.5 text-center text-gray-700">{{ $r->lowonganPerusahaan->nama ?? $r->lowongan_perusahaan->nama }}</td>
                            <!-- Ekspektasi Gaji -->
                            <td class="p-2.5 text-center text-gray-700">Rp. {{ number_format($pelamar->gaji_maksimal, 0, ',', '.') }}</td>
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
                            <td class="p-2.5 text-center font-medium">
                                @if ($pelamar && in_array(strtolower($pelamar->kategori ?? ''), ['kandidat aktif', 'kandidat', 'calon_kandidat', 'calon kandidat']))
                                    <span class="text-gray-700 text-sm">Pembelian Kandidat</span>
                                @else
                                    <span class="text-gray-700 text-sm">Melamar Lowongan</span>
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

    <!-- Modal Laporkan Kandidat / Pelamar -->
    <div id="modalReportKandidat" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 px-4 z-[9999]">
        <div class="bg-white p-6 rounded-2xl w-full max-w-lg shadow-xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                        <i class="ph ph-warning-octagon text-lg"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Laporkan Pelamar / Kandidat</h3>
                </div>
                <button type="button" onclick="closeReportKandidatModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">&times;</button>
            </div>

            <form id="formReportKandidat" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="lowongan_id" id="reportLowonganId" value="">

                <div class="p-3 bg-slate-50 border rounded-xl text-xs text-slate-600">
                    Melaporkan: <strong id="namaKandidatDilaporkan" class="text-slate-800 text-sm block mt-0.5"></strong>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Pelaporan <span class="text-rose-500">*</span></label>
                    <select name="alasan" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500">
                        <option value="">-- Pilih Alasan --</option>
                        <option value="pemalsuan_dokumen">Pemalsuan dokumen / identitas / CV palsu</option>
                        <option value="ghosting">Tidak hadir interview tanpa kabar (Ghosting)</option>
                        <option value="pelanggaran_kontrak">Pelanggaran kontrak / indisipliner berat / PHK bermasalah</option>
                        <option value="perilaku_buruk">Perilaku tidak pantas / pelecehan / ancaman</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kronologi / Penjelasan Masalah <span class="text-rose-500">*</span></label>
                    <textarea name="deskripsi" rows="4" required minlength="10" maxlength="2000"
                        placeholder="Jelaskan detail masalah (misal: portofolio atau riwayat pekerjaan di CV palsu, di-PHK bermasalah di tempat kerja sebelumnya, atau tindakan fraud)..."
                        class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-rose-500 focus:ring-rose-500"></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Minimal 10 karakter, maksimal 2000 karakter.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Bukti Pendukung <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <label for="inputBuktiKandidat"
                        class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-slate-300 rounded-xl p-4 cursor-pointer hover:border-rose-400 hover:bg-rose-50 transition-colors group">
                        <div class="w-9 h-9 rounded-full bg-slate-100 group-hover:bg-rose-100 flex items-center justify-center transition-colors">
                            <i class="ph ph-upload-simple text-lg text-slate-400 group-hover:text-rose-500 transition-colors"></i>
                        </div>
                        <div class="text-center">
                            <p id="buktiFileName" class="text-xs text-slate-500 group-hover:text-rose-600 transition-colors">
                                Klik untuk pilih file
                            </p>
                            <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, atau PDF · Maks. 5 MB</p>
                        </div>
                        <input id="inputBuktiKandidat" type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                            onchange="updateBuktiFileName(this)">
                    </label>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2 text-xs text-amber-800">
                    <i class="ph ph-info text-base text-amber-600 shrink-0 mt-0.5"></i>
                    <span>Laporan ini akan ditinjau langsung oleh Tim Moderasi AreaKerja sebelum diambil tindakan moderasi.</span>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t">
                    <button type="button" onclick="closeReportKandidatModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.footer')

    {{-- CV & Laporkan Script --}}
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

        // Modal Laporkan Kandidat
        function updateBuktiFileName(input) {
            const label = document.getElementById('buktiFileName');
            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                label.textContent = `${file.name} (${sizeMB} MB)`;
                label.classList.add('text-rose-600', 'font-semibold');
                label.classList.remove('text-slate-500');
            } else {
                label.textContent = 'Klik untuk pilih file';
                label.classList.remove('text-rose-600', 'font-semibold');
                label.classList.add('text-slate-500');
            }
        }

        function openReportKandidatModal(pelamarId, namaPelamar, lowonganId) {
            let form = document.getElementById('formReportKandidat');
            let url = "{{ route('perusahaan.pelamar.report', ':id') }}";
            form.action = url.replace(':id', pelamarId);
            document.getElementById('namaKandidatDilaporkan').textContent = namaPelamar;
            document.getElementById('reportLowonganId').value = lowonganId || '';
            document.getElementById('modalReportKandidat').classList.remove('hidden');
        }

        function closeReportKandidatModal() {
            document.getElementById('modalReportKandidat').classList.add('hidden');
            // Reset file input
            const fileInput = document.getElementById('inputBuktiKandidat');
            if (fileInput) fileInput.value = '';
            const label = document.getElementById('buktiFileName');
            if (label) {
                label.textContent = 'Klik untuk pilih file';
                label.classList.remove('text-rose-600', 'font-semibold');
                label.classList.add('text-slate-500');
            }
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

