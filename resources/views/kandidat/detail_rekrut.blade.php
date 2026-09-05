@extends('layouts.index')

@section('content')
    <div class="bg-gray-50 mt-16">
        <div class="max-w-7xl mx-auto py-8 px-4 md:px-8 grid md:grid-cols-3 gap-6">

            <!-- Kiri: Detail Utama -->
            <div class="md:col-span-2 space-y-6" x-data="{
                showConfirmTerima: false,
                showConfirmTolak: false,
                showAlasan: false,
                showSuccess: false,
                showTolakSuccess: false
            }">

                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <!-- Header Info -->
                    <div class="flex items-center gap-3">
                        @if ($tawaran->lowonganPerusahaan->perusahaan->img_profile)
                            <img src="{{ asset('storage/' . $tawaran->lowonganPerusahaan->perusahaan->img_profile) }}"
                                alt="Logo Perusahaan" class="w-12 h-12 rounded">
                        @else
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan" class="w-12 h-12 rounded">
                        @endif

                        <div>
                            <h1 class="text-xl font-semibold">{{ $tawaran->lowonganPerusahaan->nama }}</h1>
                            <p class="text-gray-600">{{ $tawaran->lowonganPerusahaan->perusahaan->nama_perusahaan }}</p>
                            <p class="text-gray-500 text-sm">{{ $tawaran->lowonganPerusahaan->alamat }}</p>
                        </div>
                    </div>

                    <p class="text-[#003d7a] font-medium">
                        Rp. {{ number_format($tawaran->lowonganPerusahaan->gaji_awal, 0, ',', '.') }} -
                        Rp. {{ number_format($tawaran->lowonganPerusahaan->gaji_akhir, 0, ',', '.') }} per bulan
                    </p>

                    {{-- Status Tawaran / Tombol Terima & Tolak --}}
                    @php
                        $statusTawaran = strtolower($tawaran->status ?? 'pending');
                    @endphp

                    <div class="flex items-center gap-3">
                        @if ($statusTawaran === 'diterima')
                            <span class="px-4 py-2 rounded-md bg-green-100 text-green-700 font-semibold text-sm flex items-center gap-2 border border-green-300">
                                <i class="ph ph-check-circle text-lg"></i> Tawaran Telah Diterima
                            </span>
                        @elseif ($statusTawaran === 'ditolak')
                            <span class="px-4 py-2 rounded-md bg-red-100 text-red-700 font-semibold text-sm flex items-center gap-2 border border-red-300">
                                <i class="ph ph-x-circle text-lg"></i> Tawaran Telah Ditolak
                            </span>
                        @else
                            <!-- Tombol Terima -->
                            <button @click="showConfirmTerima = true"
                                class="px-5 py-2 rounded-md text-white bg-green-500 hover:bg-green-600 font-medium transition shadow-sm">
                                Terima
                            </button>

                            <!-- Tombol Tolak -->
                            <button @click="showConfirmTolak = true"
                                class="px-5 py-2 rounded-md text-white bg-red-500 hover:bg-red-600 font-medium transition shadow-sm">
                                Tolak
                            </button>
                        @endif

                        {{-- Tombol Bookmark --}}
                        @auth
                            @php
                                $lowongan = $tawaran->lowonganPerusahaan;
                                $sudahSimpan = Auth::user()->pelamar
                                    ? Auth::user()
                                        ->pelamar->simpanLowongans()
                                        ->where('lowongan_id', $lowongan->id)
                                        ->exists()
                                    : false;
                            @endphp

                            <div>
                                @if (!$sudahSimpan)
                                    <form action="{{ route('simpan-lowongan.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="lowongan_id" value="{{ $lowongan->id }}">
                                        <button type="submit" class="p-2 rounded-md bg-gray-200 hover:bg-gray-300 transition"
                                            title="Simpan Lowongan">
                                            <i class="ph ph-bookmark text-2xl text-gray-600"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('simpan-lowongan.destroy', $lowongan->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-md bg-blue-100 hover:bg-blue-200 transition"
                                            title="Hapus dari Simpan">
                                            <i class="ph-fill ph-bookmark text-2xl text-[#00509d]"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Detail Lowongan (dipindah ke bawah) -->
                <div class="bg-white rounded-lg shadow p-6 space-y-6">
                    <div>
                        <h2 class="font-semibold text-lg mb-2">Detail Lowongan</h2>
                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="ph ph-briefcase text-lg"></i>
                            <span>Jenis Lowongan: <b>{{ $tawaran->lowonganPerusahaan->jenis_lowongan }}</b></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 text-sm mt-1">
                            <i class="ph ph-map-pin text-lg"></i>
                            <span>Lokasi: <b>{{ $tawaran->lowonganPerusahaan->alamat }}</b></span>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2">Deskripsi Lowongan</h3>
                        <div class="prose max-w-none text-gray-700">
                            {!! $tawaran->lowonganPerusahaan->deskripsi !!}
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2">Requirements</h3>
                        <div class="prose max-w-none text-gray-700">
                            {!! $tawaran->lowonganPerusahaan->syarat !!}
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2">Responsibilities</h3>
                        <div class="prose max-w-none text-gray-700">
                            {!! $tawaran->lowonganPerusahaan->tanggung_jawab !!}
                        </div>
                    </div>

                    {{-- Gaji Opsional --}}
                    @if ($tawaran->lowonganPerusahaan->gaji_awal && $tawaran->lowonganPerusahaan->gaji_akhir)
                        <div>
                            <h3 class="font-semibold mb-2">Kisaran Gaji</h3>
                            <p class="text-gray-700 font-medium">
                                Rp. {{ number_format($tawaran->lowonganPerusahaan->gaji_awal, 0, ',', '.') }} -
                                Rp. {{ number_format($tawaran->lowonganPerusahaan->gaji_akhir, 0, ',', '.') }} per bulan
                            </p>
                        </div>
                    @endif
                </div>

                <!-- === MODALS === -->
                <!-- Modal Konfirmasi Terima -->
                <div x-show="showConfirmTerima" x-cloak
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
                    <div class="bg-white rounded-xl p-6 w-11/12 max-w-sm text-center">
                        <h2 class="text-lg font-semibold mb-3">Konfirmasi Penerimaan</h2>
                        <p class="text-gray-600 mb-6">
                            Apakah Anda yakin ingin menerima tawaran dari
                            <b>{{ $tawaran->lowonganPerusahaan->perusahaan->nama_perusahaan ?? 'Perusahaan' }}</b>?
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button @click="showConfirmTerima = false"
                                class="w-full sm:w-auto bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                                Batal
                            </button>
                            <button
                                @click="
                    fetch('{{ route('kandidat.updateStatus', $tawaran->id ?? 0) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: 'diterima',
                            lowongan_id: '{{ $tawaran->lowonganPerusahaan->id }}'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showConfirmTerima = false;
                            showSuccess = true;
                        } else {
                            alert(data.message || 'Gagal memproses respon.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan koneksi.');
                    });
                "
                                class="w-full sm:w-auto bg-[#00509d] text-white px-4 py-2 rounded-lg hover:bg-[#003d7a]">
                                Ya, Terima
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Modal Konfirmasi Tolak -->
                <div x-show="showConfirmTolak" x-cloak
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
                    <div class="bg-white rounded-xl p-6 w-11/12 max-w-sm text-center">
                        <h2 class="text-lg font-semibold mb-3">Konfirmasi Penolakan</h2>
                        <p class="text-gray-600 mb-6">
                            Yakin ingin menolak tawaran dari
                            <b>{{ $tawaran->lowonganPerusahaan->perusahaan->nama_perusahaan ?? 'Perusahaan' }}</b>?
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button @click="showConfirmTolak = false"
                                class="w-full sm:w-auto bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                                Batal
                            </button>
                            <button @click="showConfirmTolak = false; showAlasan = true"
                                class="w-full sm:w-auto bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                Lanjut
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Modal Alasan Penolakan -->
                <div x-show="showAlasan" x-cloak
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
                    <div class="bg-white rounded-xl p-6 w-11/12 max-w-md shadow-xl">
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">Pilih Alasan Penolakan</h2>
                        <form id="form-penolakan" class="space-y-2.5">
                            @foreach (config('alasan_penolakan') as $alasan)
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 hover:border-[#00509d] hover:bg-blue-50 transition cursor-pointer">
                                    <input type="radio" name="alasan_penolakan" value="{{ $alasan }}"
                                        class="w-5 h-5 text-[#00509d] border-2 border-gray-400 focus:ring-[#00509d] accent-[#00509d] cursor-pointer flex-shrink-0">
                                    <span class="text-sm font-medium text-gray-800">{{ $alasan }}</span>
                                </label>
                            @endforeach
                            <div class="pt-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lainnya</label>
                                <textarea name="alasan_penolakan_custom" rows="3" placeholder="Tuliskan alasan penolakan lainnya..."
                                    class="w-full border-2 border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#00509d] focus:ring-1 focus:ring-[#00509d]"></textarea>
                            </div>
                        </form>
                        <div class="flex justify-end gap-3 mt-5">
                            <button @click="showAlasan = false"
                                class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">Batal</button>
                            <button
                                @click="
                    const form = document.getElementById('form-penolakan');
                    const formData = new FormData(form);
                    const alasanDipilih = formData.get('alasan_penolakan_custom') || formData.get('alasan_penolakan') || 'Tidak ada alasan khusus';

                    showAlasan = false;

                    fetch('{{ route('kandidat.updateStatus', $tawaran->id ?? 0) }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: 'ditolak',
                            lowongan_id: '{{ $tawaran->lowonganPerusahaan->id }}',
                            alasan_penolakan: alasanDipilih
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showTolakSuccess = true;
                        } else {
                            alert(data.message || 'Gagal memproses respon.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan koneksi.');
                    });
                "
                                class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition">Kirim</button>
                        </div>
                    </div>
                </div>


                <!-- Modal Sukses Terima -->
                <div x-show="showSuccess" x-cloak
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
                    <div class="bg-white rounded-xl p-6 w-11/12 max-w-sm text-center relative shadow-2xl">
                        <button @click="showSuccess = false; location.reload();"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                        <h2 class="text-lg font-semibold mb-3 text-gray-800">
                            Tawaran yang diberikan kepada Anda telah berhasil dikonfirmasi.
                            <br>
                            <b class="text-green-600">Mohon tunggu tindak lanjut dari perusahaan.</b>
                        </h2>
                        <img src="{{ asset('images/orang.png') }}" alt="Success"
                            class="mx-auto my-4 w-40 h-40 object-contain">
                        <p class="text-gray-600 text-sm mb-4">
                            Kami berharap proses selanjutnya berjalan lancar.
                        </p>
                        <button @click="showSuccess = false; location.reload();"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition">
                            Tutup
                        </button>
                    </div>
                </div>


                <div x-show="showTolakSuccess" x-cloak
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
                    <div class="bg-white rounded-xl p-6 w-11/12 max-w-sm text-center relative shadow-2xl">
                        <button @click="showTolakSuccess = false; location.reload();"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>

                        <h2 class="text-lg font-semibold mb-3 text-gray-800">
                            Anda telah menolak tawaran dari
                            <br>
                            <b class="text-[#003d7a]">{{ $tawaran->lowonganPerusahaan->perusahaan->nama_perusahaan ?? 'Perusahaan' }}</b>
                        </h2>

                        <img src="{{ asset('images/orang.png') }}" alt="Success"
                            class="mx-auto my-4 w-40 h-40 object-contain">

                        <p class="text-gray-600 text-sm mb-4">
                            Terima kasih! Keputusan Anda telah dikirim ke perusahaan dan 100 koin telah dikembalikan ke perusahaan.
                        </p>

                        <button @click="showTolakSuccess = false; location.reload();"
                            class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold py-2 rounded-lg transition">
                            Tutup
                        </button>
                    </div>
                </div>



            </div>

            <!-- Kanan: Lowongan Lain -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold">
                        Lowongan {{ $tawaran->lowonganPerusahaan->perusahaan->nama_perusahaan }} Lainnya
                    </h2>
                    {{-- <a href="#" class="text-[#003d7a] text-sm font-medium">Lihat semua</a> --}}
                </div>

                <div class="bg-white rounded-lg shadow p-4 space-y-4">
                    @foreach ($lowonganLain as $row)
                        @php $low = $row->lowonganPerusahaan ?? $row; @endphp
                        <a href="{{ route('kandidat.detailTawaran', [
                            'perusahaan' => $low->perusahaan->slug,
                            'lowongan' => $low->slug,
                        ]) }}"
                            class="flex items-start gap-3 border-b pb-4 hover:bg-gray-50 transition">
                            <img src="{{ asset('storage/' . ($low->perusahaan->img_profile ?? 'images/logo.png')) }}"
                                alt="Logo" class="w-10 h-10 rounded">
                            <div>
                                <h3 class="font-medium">{{ $low->nama }}</h3>
                                <p class="text-gray-500 text-sm">{{ $low->alamat }}</p>
                                <p class="text-sm text-gray-700">
                                    Rp. {{ number_format($low->gaji_awal, 0, ',', '.') }} -
                                    Rp. {{ number_format($low->gaji_akhir, 0, ',', '.') }} / bulan
                                </p>
                                <span class="text-xs text-gray-400"><span class="text-xs text-gray-400">
                                        Aktif
                                        {{ optional($low->published_at)->diffForHumans() ?? 'Belum Terpublicasikan' }}
                                    </span>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection


