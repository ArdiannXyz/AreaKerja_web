@extends('layouts.index')
@section('content')
    <meta property="og:title" content="{{ $data->nama }}">
    <meta property="og:site_name" content="Area Kerja">
    <meta property="og:description" content="{{ strip_tags(Str::limit($data->deskripsi, 150)) }}">
    <meta property="og:url"
        content="{{ route('detail.lowongan.non.user', [
            'perusahaan' => $data->perusahaan->slug ?? 'perusahaan',
            'lowongan' => $data->slug ?? $data->id,
        ]) }}">

    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ asset($data->gambar ?? 'default.jpg') }}">
    <meta property="og:image:alt" content="{{ $data->nama }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $data->nama }}">
    <meta name="twitter:description" content="{{ strip_tags(Str::limit($data->deskripsi, 150)) }}">
    <meta name="twitter:image" content="{{ asset($data->gambar ?? 'default.jpg') }}">

    <div class="bg-slate-50/50 min-h-screen text-slate-800 pt-28 pb-20" x-data="{
        showConfirm: false,
        showSuccess: false,
        showConfirmTerima: false,
        showConfirmTolak: false,
        showAlasan: false
    }">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-6">

            @if ($data->status === 'tutup')
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph ph-lock-key text-rose-600 text-2xl shrink-0"></i>
                    <div>
                        <p class="font-bold text-sm text-rose-900">Pendaftaran Ditutup (Kuota Terpenuhi)</p>
                        <p class="text-xs text-rose-700 mt-0.5">Perusahaan telah menutup pendaftaran untuk lowongan ini.</p>
                    </div>
                </div>
            @endif

            <!-- 1. HEADER CARD (TOP JOB BANNER CARD MATCHING FIGMA) -->
            <div class="bg-white rounded-2xl shadow-sm border border-[#00509d] p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-100 bg-white p-1 shrink-0 flex items-center justify-center shadow-sm">
                        @if (!empty($data->perusahaan->img_profile))
                            <img src="{{ asset('storage/' . $data->perusahaan->img_profile) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($data->perusahaan->nama_perusahaan ?? 'P') }}&background=00509d&color=fff&size=128" alt="Logo" class="w-full h-full object-cover rounded-lg">
                        @endif
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $data->nama }}</h1>
                        <p class="text-sm font-semibold text-slate-600 mt-1">{{ $data->perusahaan->nama_perusahaan }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $data->alamat }}</p>
                    </div>
                </div>

                <div class="flex flex-col items-start md:items-end gap-3 w-full md:w-auto">
                    <!-- Salary Plain Text -->
                    <div class="text-slate-800 font-bold text-sm sm:text-base whitespace-nowrap">
                        Rp. {{ number_format($data->gaji_awal, 0, ',', '.') }} - {{ number_format($data->gaji_akhir, 0, ',', '.') }} / Bulan
                    </div>

                    <!-- Tombol Lamar & Bookmark -->
                    <div class="flex items-center gap-2.5 w-full md:w-auto justify-end">
                        @auth
                            @php
                                $statusLower = strtolower($statusLamaran ?? '');
                                $sudahMelamar = !empty($statusLamaran) && in_array($statusLower, ['pending', 'diterima', 'ditolak', 'proses', 'dikelola']);
                                $isTutup = ($data->status === 'tutup');
                                $disableButton = $isExpired || $sudahMelamar || $isTutup;
                            @endphp

                            <button @click="showConfirm = true"
                                @if ($disableButton) disabled @endif
                                class="flex-1 md:flex-initial bg-[#004e98] hover:bg-[#003d7a] text-white font-bold text-sm px-8 py-2.5 rounded-lg shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                                @if ($sudahMelamar)
                                    Lamaran Terkirim
                                @elseif ($isTutup)
                                    Ditutup
                                @else
                                    Lamar cepat
                                @endif
                            </button>

                            @php
                                $sudahSimpan = Auth::user()->pelamar
                                    ? Auth::user()->pelamar->simpanLowongans()->where('lowongan_id', $data->id)->exists()
                                    : false;
                            @endphp

                            <div x-data="{ saved: {{ $sudahSimpan ? 'true' : 'false' }}, loading: false }">
                                <button type="button"
                                    @click.prevent="
                                        if (loading) return;
                                        loading = true;
                                        let targetUrl = saved ? '/pelamar/simpan-lowongan/{{ $data->id }}' : '{{ route('simpan-lowongan.store') }}';
                                        let reqMethod = saved ? 'DELETE' : 'POST';
                                        fetch(targetUrl, {
                                            method: reqMethod,
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: reqMethod === 'POST' ? JSON.stringify({ lowongan_id: {{ $data->id }} }) : null
                                        })
                                        .then(res => res.json())
                                        .then(resData => {
                                            loading = false;
                                            if (resData.success) {
                                                saved = !saved;
                                                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: saved ? 'Lowongan disimpan' : 'Dihapus dari simpanan', showConfirmButton: false, timer: 1500 });
                                            }
                                        })
                                        .catch(() => { loading = false; });
                                    "
                                    class="w-10 h-10 bg-[#004e98] hover:bg-[#003d7a] text-white rounded-lg flex items-center justify-center shadow-sm transition">
                                    <i x-show="!saved" class="ph ph-bookmark-simple text-xl"></i>
                                    <i x-show="saved" class="ph-fill ph-bookmark-simple text-xl"></i>
                                </button>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex-1 md:flex-initial bg-[#004e98] hover:bg-[#003d7a] text-white font-bold text-sm px-8 py-2.5 rounded-lg shadow-sm transition text-center">
                                Lamar cepat
                            </a>
                            <a href="{{ route('login') }}"
                                class="w-10 h-10 bg-[#004e98] hover:bg-[#003d7a] text-white rounded-lg flex items-center justify-center shadow-sm transition">
                                <i class="ph ph-bookmark-simple text-xl"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- 2. DESKRIPSI CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-[#00509d] p-6 md:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Deskripsi</h2>
                <div class="text-sm text-slate-600 leading-relaxed space-y-3 prose max-w-none">
                    {!! $data->deskripsi ?? 'Tidak ada deskripsi pekerjaan.' !!}
                </div>
            </div>

            <!-- 3. DETAIL LOWONGAN CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-[#00509d] p-6 md:p-8 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Detail Lowongan</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Informasi terkait perusahaan yang anda tuju</p>
                </div>

                <!-- Jenis Lowongan -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Jenis Lowongan</h3>
                    <p class="text-sm text-slate-600 font-normal">
                        {{ $data->jenis ?? 'Full time' }}
                    </p>
                </div>

                <!-- Lokasi -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Lokasi</h3>
                    <p class="text-sm text-slate-600 font-normal">
                        {{ $data->alamat ?? 'Yogyakarta' }}
                    </p>
                </div>

                <!-- Requirement -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-2">Requirement</h3>
                    <div class="text-sm text-slate-600 space-y-1.5 leading-relaxed">
                        @if (!empty($data->syarat_pekerjaan))
                            {!! $data->syarat_pekerjaan !!}
                        @elseif (!empty($data->syarat_lowongan) || !empty($data->syarat_khusus))
                            {!! $data->syarat_lowongan ?? $data->syarat_khusus !!}
                        @else
                            <ul class="space-y-1.5 text-slate-600 text-sm">
                                <li class="flex items-start gap-2">
                                    <span class="text-slate-400">•</span>
                                    <span>Lulusan S1 atau setara / SMA/SMK</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-slate-400">•</span>
                                    <span>Memiliki pengalaman kerja relevan minimal 1 tahun</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-slate-400">•</span>
                                    <span>Umur minimal 20 tahun, maksimal 35 tahun</span>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Responsibility / Tanggung Jawab -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-2">Responsibility</h3>
                    <div class="text-sm text-slate-600 space-y-1.5 leading-relaxed">
                        @if (!empty($data->tanggung_jawab))
                            {!! $data->tanggung_jawab !!}
                        @elseif (!empty($data->tugas_tanggung_jawab))
                            {!! $data->tugas_tanggung_jawab !!}
                        @else
                            <ul class="space-y-1.5 text-slate-600 text-sm">
                                <li class="flex items-start gap-2">
                                    <span class="text-slate-400">•</span>
                                    <span>Menjalankan tugas dan tanggung jawab sesuai standar operasional perusahaan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-slate-400">•</span>
                                    <span>Mengerjakan tugas dan target pekerjaan tepat waktu</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-slate-400">•</span>
                                    <span>Menjaga rahasia perusahaan terkait proyek yang diampu</span>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 4. LOWONGAN LAINNYA -->
            @php
                $otherJobs = (isset($lowonganLain) && $lowonganLain->count() > 0)
                    ? $lowonganLain->take(3)
                    : (isset($Data) ? $Data->where('id', '!=', $data->id)->take(3) : collect());
            @endphp

            @if ($otherJobs->count() > 0)
                <div class="pt-8">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-900 mb-6">Lowongan Lainnya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
                        @foreach ($otherJobs as $item)
                            <div class="h-full cursor-pointer"
                                onclick="window.location='{{ route('detail.lowongan.non.user', ['perusahaan' => $item->perusahaan->slug ?? 'perusahaan', 'lowongan' => $item->slug ?? $item->id]) }}'">
                                @include('non-user.components.card', ['lowongan' => $item])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Modal Konfirmasi Lamar --}}
        <div x-show="showConfirm" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4" x-cloak>
            <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full shadow-2xl">
                <h2 class="text-lg font-bold mb-2 text-slate-800">Konfirmasi Lamaran</h2>
                <p class="text-sm text-slate-600 mb-6">CV Anda akan dikirimkan ke <b>{{ $data->perusahaan->nama_perusahaan ?? 'Perusahaan' }}</b></p>
                <div class="flex justify-center gap-3">
                    <button @click="showConfirm = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-sm transition">Batal</button>
                    <button @click.prevent="
                        fetch('{{ route('lamar.cepat', $data->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(resData => {
                            if (resData.unauthenticated) {
                                window.location.href = resData.redirect;
                                return;
                            }
                            if (resData.success) {
                                showConfirm = false;
                                showSuccess = true;
                                return;
                            }
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: resData.message ?? 'Terjadi kesalahan.' });
                        })
                    " class="px-5 py-2.5 bg-[#004e98] hover:bg-[#003d7a] text-white font-semibold rounded-xl text-sm transition shadow-md">Kirim Lamaran</button>
                </div>
            </div>
        </div>

        {{-- Modal Sukses Lamar --}}
        <div x-show="showSuccess" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4" x-cloak>
            <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full shadow-2xl">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold">✓</div>
                <h2 class="text-lg font-bold mb-2 text-slate-800">Lamaran Terkirim!</h2>
                <p class="text-sm text-slate-600 mb-6">Lamaran Anda berhasil dikirim ke {{ $data->perusahaan->nama_perusahaan ?? 'Perusahaan' }}.</p>
                <button @click="showSuccess = false; window.location.reload();" class="px-6 py-2.5 bg-[#004e98] text-white font-semibold rounded-xl text-sm transition">Tutup</button>
            </div>
        </div>

    </div>

    @include('layouts.footer')
@endsection
