@extends('layouts.index')
@section('content')
    <meta property="og:title" content="{{ $data->nama }}">
    <meta property="og:site_name" content="Area Kerja">
    <meta property="og:description" content="{{ strip_tags(Str::limit($data->deskripsi, 150)) }}">
    <meta property="og:url"
        content="{{ route('detail.lowongan.non.user', [
            'perusahaan' => $data->perusahaan->slug,
            'lowongan' => $data->slug,
        ]) }}">

    <meta property="og:type" content="article">

    <meta property="og:image" content="{{ asset($data->gambar ?? 'default.jpg') }}">
    <meta property="og:image:alt" content="{{ $data->nama }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $data->nama }}">
    <meta name="twitter:description" content="{{ strip_tags(Str::limit($data->deskripsi, 150)) }}">
    <meta name="twitter:image" content="{{ asset($data->gambar ?? 'default.jpg') }}">


    <div class="bg-slate-100 min-h-screen text-slate-800 pt-28 pb-16" x-data="{
        showConfirm: false,
        showSuccess: false,
        showConfirmTerima: false,
        showConfirmTolak: false,
        showAlasan: false
    }">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">

            <!-- 1. HEADER CARD (TOP JOB BANNER CARD) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 bg-white p-1 shrink-0 flex items-center justify-center shadow-sm">
                        @if (!empty($data->perusahaan->img_profile))
                            <img src="{{ asset('storage/' . $data->perusahaan->img_profile) }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($data->perusahaan->nama_perusahaan ?? 'P') }}&background=f97316&color=fff&size=128" alt="Logo" class="w-full h-full object-cover rounded-lg">
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $data->nama }}</h1>
                        <p class="text-sm font-semibold text-slate-500 mt-1">{{ $data->perusahaan->nama_perusahaan }}</p>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                            <i class="ph ph-map-pin text-orange-500"></i> {{ $data->alamat }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-start md:items-end gap-3 w-full md:w-auto">
                    <!-- Salary Text (Orange Text) -->
                    <p class="text-orange-600 font-extrabold text-base sm:text-lg md:text-xl whitespace-nowrap">
                        Rp {{ number_format($data->gaji_awal, 0, ',', '.') }} - {{ number_format($data->gaji_akhir, 0, ',', '.') }} / Bulan
                    </p>

                    <!-- Tombol Lamar & Bookmark -->
                    <div class="flex items-center gap-3">
                        @auth
                            @php
                                $statusLower = strtolower($statusLamaran ?? '');
                                $sudahMelamar = !empty($statusLamaran) && in_array($statusLower, ['pending', 'diterima', 'ditolak', 'proses', 'dikelola']);
                                $sudahDiterima = $statusLower === 'diterima';
                                $disableButton = $isExpired || $sudahMelamar;
                                $kategori = Auth::user()->pelamar->kategori ?? null;
                            @endphp

                            @if ($kategori === 'pelamar' || $kategori === 'calon kandidat' || ($kategori === 'kandidat aktif' && !$tawaran))
                                <button 
                                    @click="{{ $disableButton ? "Swal.fire({ title: 'Informasi Lamaran', text: '" . ($sudahMelamar ? 'Anda sudah mengirimkan lamaran untuk lowongan ini.' : 'Lamaran untuk lowongan ini sudah kadaluarsa.') . "', icon: 'info', confirmButtonColor: '#f97316', confirmButtonText: 'Mengerti', customClass: { popup: 'rounded-2xl shadow-xl' } })" : "showConfirm = true" }}"
                                    :disabled="{{ $disableButton ? 'true' : 'false' }}"
                                    class="px-6 py-2.5 rounded-xl text-white font-extrabold transition shadow-sm text-sm
                                        {{ $disableButton ? 'bg-slate-400 cursor-not-allowed opacity-90' : 'bg-orange-500 hover:bg-orange-600' }}">
                                    @if ($isExpired)
                                        Lamaran Kadaluarsa
                                    @elseif ($sudahDiterima)
                                        Sudah Diterima
                                    @elseif ($sudahMelamar)
                                        Anda Sudah Mengirimkan Lamaran
                                    @else
                                        Lamar Cepat
                                    @endif
                                </button>

                                {{-- Simpan Bookmark Button --}}
                                <div x-data="saveLowongan({{ $data->id }}, {{ $isSaved ? 'true' : 'false' }})">
                                    <button type="button" @click="toggleSave"
                                        class="p-2.5 rounded-xl border transition cursor-pointer flex items-center justify-center"
                                        :class="saved ? 'text-orange-600 bg-orange-50 border-orange-200' : 'bg-slate-100 border-slate-200 text-slate-500 hover:text-orange-600'"
                                        :title="saved ? 'Hapus dari Simpan' : 'Simpan Lowongan'">
                                        <i x-show="!saved" class="ph ph-bookmark-simple text-xl"></i>
                                        <i x-show="saved" class="ph-fill ph-bookmark-simple text-xl text-orange-500"></i>
                                    </button>
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-extrabold rounded-xl shadow-sm transition text-sm">
                                Lamar Cepat
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- 2. CARD DESKRIPSI -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6 md:p-8 space-y-3">
                <h2 class="font-extrabold text-xl text-slate-900">Deskripsi</h2>
                <div class="text-sm md:text-base text-slate-600 leading-relaxed font-medium">
                    {!! $data->deskripsi !!}
                </div>
            </div>

            <!-- 3. CARD DETAIL LOWONGAN -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6 md:p-8 space-y-6">
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900">Detail Lowongan</h2>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Informasi terkait perusahaan yang anda tuju</p>
                </div>

                <!-- Jenis Lowongan -->
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 mb-2">Jenis Lowongan</h3>
                    <span class="inline-block bg-slate-200 text-slate-700 font-bold px-6 py-2 rounded-xl text-xs sm:text-sm">
                        {{ $data->jenis }}
                    </span>
                </div>

                <!-- Lokasi -->
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 mb-2">Lokasi</h3>
                    <span class="inline-block bg-slate-200 text-slate-700 font-bold px-6 py-2 rounded-xl text-xs sm:text-sm">
                        {{ $data->alamat }}
                    </span>
                </div>

                <!-- Requirement -->
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 mb-2">Requirement</h3>
                    <ul class="list-disc list-inside text-xs sm:text-sm text-slate-600 space-y-1.5 leading-relaxed font-medium">
                        @foreach (explode("\n", $data->syarat_pekerjaan) as $req)
                            @if (trim($req) !== '')
                                <li>{{ trim($req) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <!-- Responsibility -->
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 mb-2">Responsibility</h3>
                    <ul class="list-disc list-inside text-xs sm:text-sm text-slate-600 space-y-1.5 leading-relaxed font-medium">
                        @foreach (preg_split("/\r\n|\n|\r/", $data->tanggung_jawab) as $res)
                            @php $trim = trim($res); @endphp
                            @if ($trim !== '')
                                <li>{{ $trim }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- 4. LOWONGAN LAINNYA -->
            <div class="pt-6 pb-8">
                <h2 class="font-black text-2xl text-slate-900 mb-6">Lowongan Lainnya</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse ($lowonganLain as $item)
                        <div class="h-full" onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $item->perusahaan->slug, 'lowongan' => $item->slug]) }}'">
                            @include('non-user.components.card', ['d' => $item])
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 bg-white rounded-2xl border border-dashed border-slate-300">
                            <p class="text-sm text-slate-400 font-medium">Tidak ada lowongan lain.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ===================== MODAL ===================== --}}

        {{-- Lamar Cepat --}}
        <div x-show="showConfirm" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 text-center w-96">
                <h2 class="text-lg font-semibold mb-4">Konfirmasi</h2>
                <p class="mb-6">
                    CV anda akan dikirim ke <b>{{ $data->perusahaan->nama_perusahaan }}</b>
                </p>
                <div class="flex justify-center gap-4">
                    <button @click="showConfirm = false" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
                    <button
                        @click.prevent="
                        fetch('{{ route('lamar.cepat', $data->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showConfirm = false;
                                showSuccess = true;
                            } else if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                alert(data.message ?? 'Gagal mengirim lamaran.');
                            }
                        })
                    "
                        class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                        Kirim
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Sukses --}}
        <div x-show="showSuccess" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 text-center w-96">
                <h2 class="text-lg font-semibold mb-4">Lamaran anda telah terkirim</h2>
                <p class="mb-6">Silahkan menunggu informasi selanjutnya melalui sistem kami.</p>
                <button @click="showSuccess = false"
                    class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    Selesai
                </button>
            </div>
        </div>

        {{-- Modal Terima --}}
        <div x-show="showConfirmTerima" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 text-center w-96">
                <h2 class="text-lg font-semibold mb-4">Konfirmasi</h2>
                <p class="mb-6">Yakin ingin menerima tawaran dari <b>{{ $data->perusahaan->nama_perusahaan }}</b>?</p>
                <div class="flex justify-center gap-4">
                    <button @click="showConfirmTerima = false" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
                    <button
                        @click="
                        fetch('{{ route('kandidat.updateStatus', $tawaran->id ?? 0) }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content 
                            },
                            body: JSON.stringify({ status: 'diterima' })
                        }).then(res => res.json())
                        .then(data => { if (data.status === 'success') showConfirmTerima = false; showSuccess = true; })
                    "
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Ya, Terima
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Tolak --}}
        <div x-show="showConfirmTolak" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 text-center w-96">
                <h2 class="text-lg font-semibold mb-4">Konfirmasi Penolakan</h2>
                <p class="mb-6">Yakin ingin menolak tawaran dari <b>{{ $data->perusahaan->nama_perusahaan }}</b>?</p>
                <div class="flex justify-center gap-4">
                    <button @click="showConfirmTolak = false" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
                    <button @click="showConfirmTolak = false; showAlasan = true"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Lanjut</button>
                </div>
            </div>
        </div>

        {{-- Modal Alasan --}}
        <div x-show="showAlasan" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
            <div class="bg-white rounded-xl p-6 w-11/12 max-w-md shadow-xl">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Pilih Alasan Penolakan</h2>
                <form id="form-penolakan" class="space-y-2.5">
                    @foreach (config('alasan_penolakan') as $alasan)
                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 hover:border-orange-500 hover:bg-orange-50 transition cursor-pointer">
                            <input type="radio" name="alasan_penolakan" value="{{ $alasan }}"
                                class="w-5 h-5 text-orange-500 border-2 border-gray-400 focus:ring-orange-500 accent-orange-500 cursor-pointer flex-shrink-0">
                            <span class="text-sm font-medium text-gray-800">{{ $alasan }}</span>
                        </label>
                    @endforeach
                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lainnya</label>
                        <textarea name="alasan_penolakan_custom" rows="3" placeholder="Tuliskan alasan penolakan lainnya..."
                            class="w-full border-2 border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"></textarea>
                    </div>
                </form>
                <div class="flex justify-end gap-3 mt-5">
                    <button @click="showAlasan = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button
                        @click="
                        const form = document.getElementById('form-penolakan');
                        const data = new FormData(form);
                        const alasan = data.get('alasan_penolakan_custom') || data.get('alasan_penolakan');
                        fetch('{{ route('kandidat.updateStatus', $tawaran->id ?? 0) }}', {
                            method: 'POST',
                            headers: { 
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams({
                                status: 'ditolak',
                                alasan_penolakan: alasan
                            })
                        })
                        .then(res => res.json())
                        .then(resData => {
                            if (resData.status === 'success') {
                                showAlasan = false;
                                showTolakSuccess = true;
                            }
                        })
                        "
                        class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition">Kirim</button>
                </div>
            </div>
        </div>

    </div>

    @include('layouts.footer')


    {{-- SIMPAN LOWONGAN --}}
    <script>
        function saveLowongan(lowonganId, initialState) {
            return {
                saved: initialState,
                loading: false,

                toggleSave() {
                    if (this.loading) return;
                    this.loading = true;

                    let targetUrl = this.saved ? `/pelamar/simpan-lowongan/${lowonganId}` : "{{ route('simpan-lowongan.store') }}";
                    let reqMethod = this.saved ? "DELETE" : "POST";

                    fetch(targetUrl, {
                        method: reqMethod,
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
                        },
                        body: reqMethod === "POST" ? JSON.stringify({ lowongan_id: lowonganId }) : null
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.loading = false;
                        if (data.success) {
                            this.saved = !this.saved;
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: this.saved ? 'Lowongan disimpan' : 'Dihapus dari simpanan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: data.message || 'Gagal memperbarui simpanan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        console.error(err);
                    });
                }
            }
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl shadow-xl' }
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Informasi Lamaran',
                    text: "{{ session('error') }}",
                    icon: 'info',
                    confirmButtonColor: '#f97316',
                    confirmButtonText: 'Mengerti',
                    customClass: { popup: 'rounded-2xl shadow-xl' }
                });
            });
        </script>
    @endif
@endsection
