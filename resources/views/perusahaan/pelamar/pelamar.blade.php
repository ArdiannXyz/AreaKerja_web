@extends('layouts.index-perusahaan')
@section('content')
    <section class="relative">
        @php
            $header = null;
        @endphp

        <section class="relative w-full">

            <!-- Gambar Header -->
            <img src="{{ \App\Services\SiteSettingService::getHeaderUrl('header_pelamar_perusahaan', 'images/tangan.png') }}"
                alt="Header Image" class="w-full h-52 md:h-96 object-cover">

            <!-- Overlay Hitam -->
            <div class="absolute inset-0 bg-black bg-opacity-10"></div>

            <!-- Text Overlay -->
            <div class="absolute bottom-6 md:bottom-20 left-4 md:left-20 text-white break-words pr-4">

                <h1 class="text-2xl md:text-5xl font-semibold mt-3 max-w-md md:max-w-2xl mb-2">
                    Pelamar
                </h1>

                <h2 class="text-sm md:text-xl">Lihat riwayat lamar yang masuk</h2>
                <h2 class="text-sm md:text-xl">Ke lowongan anda</h2>
            </div>

        </section>


        <div class="bg-slate-50/60 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl xl:max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5 shadow-2xs">
                        <i class="ph ph-check-circle text-lg text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-2xs">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Header Info Lowongan (Panjang Kesamping / Full Width) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <!-- Kiri -->
                    <div class="flex items-center gap-4 w-full">
                        <img src="{{ $data->perusahaan->img_profile ? asset('storage/' . $data->perusahaan->img_profile) : asset('images/logo_area_kerja_biru.png') }}"
                            alt="logo"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover border border-slate-200 shrink-0">

                        <div class="flex-1 min-w-0">
                            <h5 class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                {{ $data->perusahaan->nama_perusahaan }}
                            </h5>
                            <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate">
                                {{ $data->nama }} <span class="text-sm font-normal text-slate-500">({{ $data->jenis }})</span>
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 truncate">
                                {{ $data->alamat }}
                            </p>

                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="inline-flex items-center gap-1 bg-slate-100 border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    <i class="ph ph-money text-slate-500"></i>
                                    Rp. {{ $data->gaji_awal }} - Rp. {{ $data->gaji_akhir }} / bulan
                                </span>
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg px-2.5 py-1 text-xs font-semibold">
                                    <i class="ph ph-users"></i> {{ $woi->count() }} Pelamar Masuk
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan (tanggal) -->
                    <div class="text-right shrink-0">
                        <span class="text-xs text-slate-400 block">Diterbitkan</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-600">
                            {{ $data->published_at?->diffForHumans() ?? 'Baru saja' }}
                        </span>
                    </div>
                </div>

                <!-- TABEL PELAMAR (Panjang Kesamping dan Kebawah) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Daftar Pelamar Masuk</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Kelola pelamar, periksa CV, terima/tolak lamaran, atau laporkan pelamar bermasalah.</p>
                        </div>
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                            Total: {{ $woi->count() }} Pelamar
                        </span>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-sm border-collapse min-w-[750px]">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                                <tr>
                                    <th class="py-3.5 px-6 text-center font-bold">Tanggal</th>
                                    <th class="py-3.5 px-6 text-left font-bold">Nama Pelamar</th>
                                    <th class="py-3.5 px-6 text-center font-bold">Unduh CV</th>
                                    <th class="py-3.5 px-6 text-center font-bold">Status Lamaran</th>
                                    <th class="py-3.5 px-6 text-center font-bold">Moderasi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($woi as $p)
                                    <tr class="hover:bg-slate-50/70 transition text-center">
                                        <td class="py-4 px-6 whitespace-nowrap text-xs text-slate-500">
                                            {{ $p->created_at?->format('d M Y') }}
                                        </td>

                                        <td class="py-4 px-6 text-left">
                                            <p class="font-bold text-slate-800 text-sm">
                                                {{ $p->pelamar->nama_pelamar ?? 'Pelamar' }}
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                {{ $p->pelamar->telepon_pelamar ?? '-' }}
                                            </p>
                                        </td>

                                        <td class="py-4 px-6">
                                            <button onclick="openConfirmModal({{ $p->pelamar->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-[#00509d] hover:bg-blue-50 border border-blue-200 rounded-lg transition cursor-pointer"
                                                title="Unduh CV Pelamar">
                                                <i class="ph ph-file-arrow-down text-base"></i>
                                                <span>Unduh CV</span>
                                            </button>
                                        </td>

                                        <td class="py-4 px-6 whitespace-nowrap">
                                            @if ($p->status === 'diterima')
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-700 font-extrabold text-xs rounded-xl border border-emerald-200">
                                                    <i class="ph ph-check font-bold"></i> Diterima
                                                </span>
                                            @elseif ($p->status === 'ditolak')
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 text-rose-700 font-extrabold text-xs rounded-xl border border-rose-200">
                                                    <i class="ph ph-x font-bold"></i> Ditolak
                                                </span>
                                            @else
                                                <div class="inline-flex items-center gap-1.5">
                                                    <a href="{{ route('pelamar.konfirmasi', $p->id) }}"
                                                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3.5 py-1.5 rounded-lg text-xs transition shadow-2xs">
                                                        Terima
                                                    </a>
                                                    <button onclick="openTolakModal({{ $p->id }})"
                                                        class="bg-rose-500 hover:bg-rose-600 text-white font-bold px-3.5 py-1.5 rounded-lg text-xs transition shadow-2xs cursor-pointer">
                                                        Tolak
                                                    </button>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <button type="button"
                                                onclick="openReportPelamarModal({{ $p->pelamar->id }}, '{{ addslashes($p->pelamar->nama_pelamar ?? 'Pelamar') }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-300 rounded-lg transition cursor-pointer"
                                                title="Laporkan pelamar bermasalah (PHK karena masalah, dokumen palsu, ghosting, dll)">
                                                <i class="ph ph-flag"></i> Laporkan
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 px-4 text-center text-slate-400 font-medium">
                                            <i class="ph ph-users text-4xl text-slate-300 mb-2 block"></i>
                                            Belum ada pelamar yang melamar pada lowongan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- MODAL CV --}}
        <div id="confirmModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 px-4 z-[9999]">

            <div class="bg-white p-5 md:p-6 rounded-lg text-center w-full max-w-sm break-words">

                <!-- Icon -->
                <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#00509d] mb-4" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M12 16l4-5h-3V4h-2v7H8l4 5zM4 20h16v2H4z" />
                    </svg>
                </div>

                <!-- Teks -->
                <p class="mb-4 font-medium break-words">
                    Yakin akan mengunduh CV pelamar?
                </p>

                <!-- Tombol -->
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <button onclick="downloadCV()" class="px-4 py-2 bg-[#00509d] text-white rounded w-full sm:w-auto">
                        Unduh
                    </button>

                    <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-black rounded w-full sm:w-auto">
                        Batal
                    </button>
                </div>

            </div>
        </div>


        <!-- Modal 2: Sukses -->
        <div id="successModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 px-4">

            <div class="bg-white p-5 md:p-6 rounded-lg text-center w-full max-w-sm break-words">

                <div class="flex justify-center">
                    <div class="bg-blue-100 p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#00509d]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 font-semibold break-words">
                    CV Berhasil diunduh
                </p>

                <div class="mt-4">
                    <button onclick="closeSuccessModal()"
                        class="px-4 py-2 bg-[#00509d] text-white rounded w-full sm:w-auto">
                        Tutup
                    </button>
                </div>

            </div>
        </div>


        <!-- Modal Tolak -->
        <div id="modalTolak" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">

            <div class="bg-white p-5 md:p-6 rounded-lg text-center shadow-lg w-full max-w-md break-words">

                <h2 class="text-lg md:text-xl font-bold mb-3 text-center break-words">
                    Konfirmasi Tolak Lamaran
                </h2>

                <p class="mb-4 break-words text-center">
                    Apakah anda yakin ingin menolak lamaran ini?
                </p>

                <form id="tolakForm" method="POST" action="" class="text-center">
                    @csrf

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded w-full sm:w-auto transition duration-200">
                            Tolak
                        </button>

                        <button type="button" onclick="closeTolakModal()"
                            class="bg-gray-400 hover:bg-gray-500 px-5 py-2 rounded w-full sm:w-auto transition duration-200">
                            Batal
                        </button>
                    </div>
                </form>

            </div>
        </div>



        <script>
            function openTolakModal(id) {
                let form = document.getElementById('tolakForm');
                let url = "{{ route('pelamar.tolak', ':id') }}";
                form.action = url.replace(':id', id);
                document.getElementById('modalTolak').classList.remove('hidden');
            }

            function closeTolakModal() {
                document.getElementById('modalTolak').classList.add('hidden');
            }
        </script>


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
        </script>

        <!-- Modal Laporkan Pelamar -->
        <div id="modalReportPelamar" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 px-4 z-[9999]">
            <div class="bg-white p-6 rounded-2xl w-full max-w-lg shadow-xl relative max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                            <i class="ph ph-warning-octagon text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Laporkan Pelamar</h3>
                    </div>
                    <button type="button" onclick="closeReportPelamarModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">&times;</button>
                </div>

                <form id="formReportPelamar" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="lowongan_id" value="{{ $data->id }}">

                    <div class="p-3 bg-slate-50 border rounded-xl text-xs text-slate-600">
                        Melaporkan: <strong id="namaPelamarDilaporkan" class="text-slate-800 text-sm block mt-0.5"></strong>
                        Lowongan: <span class="font-medium text-slate-700">{{ $data->nama }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Pelaporan <span class="text-rose-500">*</span></label>
                        <select name="alasan" required class="w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500">
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
                            placeholder="Jelaskan detail kejadian secara objektif (misal: pelamar tidak masuk kerja tanpa kabar setelah tanda tangan kontrak, atau terbukti melakukan pelanggaran fatal berujung PHK)..."
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500"></textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Minimal 10 karakter, maksimal 2000 karakter.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Bukti Pendukung (Opsional)</label>
                        <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 border border-slate-200 rounded-xl">
                        <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, atau PDF (Surat PHK/SP/Chat). Maks. 5 MB.</p>
                    </div>

                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2 text-xs text-amber-800">
                        <i class="ph ph-info text-base text-amber-600 shrink-0 mt-0.5"></i>
                        <span>Laporan ini akan diverifikasi oleh Admin/Super Admin sebelum tindakan (seperti teguran, pencabutan status kandidat, atau pembekuan akun) diterapkan ke pelamar.</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t">
                        <button type="button" onclick="closeReportPelamarModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openReportPelamarModal(pelamarId, namaPelamar) {
                let form = document.getElementById('formReportPelamar');
                let url = "{{ route('perusahaan.pelamar.report', ':id') }}";
                form.action = url.replace(':id', pelamarId);
                document.getElementById('namaPelamarDilaporkan').textContent = namaPelamar;
                document.getElementById('modalReportPelamar').classList.remove('hidden');
            }

            function closeReportPelamarModal() {
                document.getElementById('modalReportPelamar').classList.add('hidden');
            }
        </script>
        @include('layouts.footer')
    @endsection

