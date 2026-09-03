@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white text-gray-800 mt-24">
        <!-- Header Perusahaan -->
        @if (Auth::user()->perusahaan->pasanglowongan->count() > 0)
            <div class="max-w-4xl mx-auto px-4 py-6">
                <div class="flex items-center justify-between relative">
                    <!-- Kiri: Logo + Info -->
                    <div class="flex items-center gap-4">
                        @if (Auth::user()->role == 'perusahaan')
                            @if (Auth::user()->perusahaan->img_profile)
                                <img id="pu" class="w-20 h-20 object-cover rounded-full profile-img"
                                    src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                            @else
                                <img id="pu" class="w-20 h-20 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                    alt="">
                            @endif
                        @else
                            <img class="w-10 h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                alt="">
                        @endif
                        <div>
                            <span class="font-bold text-xl">{{ Auth::user()->username }}</span>
                            <p class="text-lg">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                            <p class="text-sm text-gray-400">{{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                                {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }},
                                {{ Auth::user()->perusahaan->alamatUtama->kecamatan->nama ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    <!-- Lowongan -->
    <div class="max-w-5xl mx-auto px-4 mb-10">
        <h2 class="text-lg font-semibold mb-3 flex">Lowongan</h2>

        <!-- Filter Bar -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-6 w-full">
            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Tombol Tambah Lowongan -->
                <a href="{{ route('lowongan.create.form') }}"
                    class="h-10 bg-orange-500 hover:bg-orange-600 text-white font-bold px-4 rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 whitespace-nowrap text-sm">
                    <i class="ph ph-plus text-base font-bold"></i> Tambah Lowongan
                </a>

                <!-- Trigger Boost -->
                <div class="relative group">
                    <button id="openBoostBtn"
                        class="h-10 bg-orange-500 hover:bg-orange-600 text-white px-4 rounded-xl text-sm font-bold shadow-xs flex items-center gap-1.5 transition">
                        <i class="ph ph-rocket-launch text-base"></i> Boost Lowongan
                    </button>

                    <!-- Tooltip -->
                    <div
                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 sm:w-72 bg-slate-800 text-white text-xs rounded-xl px-3.5 py-2 opacity-0 invisible shadow-lg group-hover:opacity-100 group-hover:visible transition duration-200 z-10 text-center pointer-events-none">
                        Boost Lowongan membuat lowongan Anda tampil paling atas dan diprioritaskan.
                    </div>
                </div>
            </div>

            <!-- Form Filter -->
            <form method="GET" class="flex items-center gap-2.5 flex-wrap">
                <select name="paket"
                    class="h-10 bg-white border border-slate-300 rounded-xl text-sm px-3 text-slate-700 focus:outline-none focus:ring-1 focus:ring-orange-400">
                    <option value="">Jenis Paket</option>
                    @foreach ($pakets as $paket)
                        <option value="{{ $paket->id }}" {{ request('paket') == $paket->id ? 'selected' : '' }}>
                            {{ $paket->nama }}
                        </option>
                    @endforeach
                </select>

                <select name="jenis"
                    class="h-10 bg-white border border-slate-300 rounded-xl text-sm px-3 text-slate-700 focus:outline-none focus:ring-1 focus:ring-orange-400">
                    <option value="">Jenis Lowongan</option>
                    @foreach ($jenisLowongan as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="h-10 bg-orange-500 hover:bg-orange-600 text-white px-4 text-sm font-bold rounded-xl shadow-xs transition flex items-center gap-1">
                    <i class="ph ph-funnel text-base"></i> Filter
                </button>

                <a href="{{ route('lowongan.saya.perusahaan') }}"
                    class="h-10 bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 px-3.5 text-sm font-semibold rounded-xl flex items-center justify-center transition shadow-2xs" title="Reset Filter">
                    <i class="ph ph-arrow-counter-clockwise text-base"></i> Reset
                </a>
            </form>
        </div>

        <!-- Modal Boost -->
        <div id="boostModal"
            class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
                <button id="closeBoostBtn" class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">✕</button>
                <h2 class="text-xl font-bold mb-4 text-slate-800">Boost Lowongan</h2>

                <label class="block font-semibold mb-2 text-sm text-slate-700">Pilih Lowongan</label>
                <select id="lowonganSelect" class="w-full border border-slate-300 p-2.5 rounded-xl text-sm focus:ring-1 focus:ring-orange-400 outline-none">
                    @foreach ($lowongans as $l)
                        @if ($l->published_at)
                            <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endif
                    @endforeach
                </select>

                <button onclick="showConfirmBoost()"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-4 py-2.5 rounded-xl mt-5 w-full shadow-xs transition">
                    Boost Sekarang
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            @forelse ($Data as $d)
                @if ($d->paket_id && $d->published_at)
                    <!-- Card Published -->

                    <div class="border rounded-xl shadow-sm bg-white p-5 mb-4 border-slate-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-4 mb-4">
                            <div class="flex items-start gap-4">
                                @if (Auth::user()->perusahaan->img_profile)
                                    <img src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200" alt="Logo">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->perusahaan->nama_perusahaan ?? 'P') }}&background=f97316&color=fff&size=128" class="w-12 h-12 object-cover rounded-xl border border-slate-200" alt="Logo">
                                @endif
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <p class="text-xs font-semibold text-slate-500">{{ Auth::user()->perusahaan->nama_perusahaan }}</p>
                                        @if ($d->status === 'tutup')
                                            <span class="px-2.5 py-0.5 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">
                                                🔒 Pendaftaran Ditutup (Kuota Terpenuhi)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                                🟢 Aktif (Menerima Pelamar)
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('lowongan.detail', ['perusahaan' => $d->perusahaan->slug, 'lowongan' => $d->slug]) }}" class="hover:text-orange-600 transition">
                                        <h1 class="font-extrabold text-lg text-slate-900 leading-snug">{{ $d->nama }} - <span class="text-slate-600 text-base font-medium">{{ $d->jenis }}</span></h1>
                                    </a>
                                    <p class="text-xs text-slate-400 mt-0.5"><i class="ph ph-map-pin text-orange-500"></i> {{ $d->alamat ?? 'Yogyakarta' }}</p>
                                </div>
                            </div>

                            <!-- Tombol Aksi (Tutup/Buka, Edit, Hapus) -->
                            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                <form action="{{ route('lowongan.toggleStatus', $d->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    @if ($d->status === 'tutup')
                                        <button type="submit" class="h-8.5 px-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 text-emerald-700 text-xs font-semibold rounded-lg shadow-2xs transition flex items-center gap-1.5" title="Buka Kembali Pendaftaran">
                                            <i class="ph ph-lock-key-open text-sm font-bold text-emerald-600"></i> Buka Pendaftaran
                                        </button>
                                    @else
                                        <button type="submit" class="h-8.5 px-3 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg shadow-2xs transition flex items-center gap-1.5" onclick="return confirm('Tutup pendaftaran lowongan ini karena kuota sudah terpenuhi?')" title="Tutup Pendaftaran (Kuota Full)">
                                            <i class="ph ph-lock-key text-sm text-slate-500"></i> Tutup Lowongan (Kuota Full)
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('lowongan.edit.form', ['perusahaan' => Auth::user()->perusahaan->slug, 'lowongan' => $d->slug]) }}" class="h-8.5 px-3 bg-white hover:bg-amber-50 border border-amber-300 text-amber-700 text-xs font-semibold rounded-lg shadow-2xs transition flex items-center gap-1">
                                    <i class="ph ph-pencil-simple text-sm text-amber-600"></i> Edit
                                </a>

                                <form action="{{ route('lowongan.destroy', $d->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="h-8.5 px-3 bg-white hover:bg-red-50 border border-red-200 text-red-600 text-xs font-semibold rounded-lg shadow-2xs transition flex items-center gap-1">
                                        <i class="ph ph-trash text-sm text-red-500"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs">
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-700 font-bold rounded-lg border border-slate-200">
                                Gaji: Rp {{ number_format($d->gaji_awal, 0, ',', '.') }} - Rp {{ number_format($d->gaji_akhir, 0, ',', '.') }} / bulan
                            </span>
                            <span class="text-slate-500 font-medium">
                                Sisa Masa Tayang: <span id="countdown-{{ $d->id }}" class="text-red-500 font-bold"></span>
                            </span>
                        </div>
                    </div>

                    <!-- 🔹 Script Countdown per Lowongan -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const countdownEl = document.getElementById('countdown-{{ $d->id }}');
                            const expiredAt = new Date("{{ \Carbon\Carbon::parse($d->expired_at)->format('Y-m-d H:i:s') }}")
                                .getTime();

                            if (countdownEl) {
                                const interval = setInterval(function() {
                                    const now = new Date().getTime();
                                    const distance = expiredAt - now;

                                    if (distance < 0) {
                                        clearInterval(interval);
                                        countdownEl.innerHTML = "Lowongan telah kadaluarsa";
                                    } else {
                                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

                                        countdownEl.innerHTML = `${days}h ${hours}j ${minutes}m lagi`;
                                    }
                                }, 1000);
                            }
                        });
                    </script>
                    @if ($d->expired_at && $d->expired_at < now())
                        <!-- Tombol Publish Ulang -->
                        <button type="button"
                            class="block mt-3 pl-0 lg:pl-10 md:pl-10 bg-orange-500 px-10 py-2 text-center rounded-md text-white hover:bg-orange-600 transition"
                            data-modal-target="modal-expired-{{ $d->id }}"
                            data-modal-toggle="modal-expired-{{ $d->id }}">
                            Publish Ulang
                        </button>

                        <!-- Modal Konfirmasi -->
                        <div id="modal-expired-{{ $d->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-xl shadow-lg w-96 p-6 text-center relative">
                                <h2 class="text-lg font-semibold text-gray-800 mb-2">Lowongan Expired</h2>
                                <p class="text-gray-600 mb-6">
                                    Maaf, lowongan Anda sudah <span class="font-semibold text-red-500">expired</span>.<br>
                                    Silakan beli paket baru untuk mempublish ulang.
                                </p>

                                <div class="flex justify-center gap-4">
                                    <!-- Tombol ke Halaman Pembelian Paket -->
                                    <a href="{{ route('paket.form') }}"
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md transition">
                                        Beli Paket Baru
                                    </a>

                                    <!-- Tombol Tutup -->
                                    <button type="button" data-modal-hide="modal-expired-{{ $d->id }}"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Card Draft / Non Publish -->
                    <div class="flex justify-between items-end text-center gap-3 my-5">
                        <h3 class="font-semibold text-lg text-center text-orange-500">Lowongan Draft</h3>
                    </div>
                    <div
                        class="flex shadow-md p-4 border rounded-md mb-2 hover:shadow-lg hover:scale-105 transition-all duration-500">
                        <div>
                            @if (Auth::user()->perusahaan->img_profile)
                                <img src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200" alt="Logo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->perusahaan->nama_perusahaan ?? 'P') }}&background=f97316&color=fff&size=128" class="w-12 h-12 object-cover rounded-xl border border-slate-200" alt="Logo">
                            @endif
                        </div>
                        <div class="w-full">
                            <a href="{{ route('lowongan.detail', [
                                'perusahaan' => $d->perusahaan->slug,
                                'lowongan' => $d->slug,
                            ]) }}"
                                class="block">
                                <p>{{ Auth::user()->perusahaan->nama_perusahaan }}</p>
                                <h1 class="font-semibold">{{ $d->nama }} - {{ $d->jenis }}</h1>
                                <span>Yogyakarta</span>
                                <div class="mt-5 block lg:flex md:flex justify-between items-center w-full">
                                    <span
                                        class="px-3 bg-[#d7d6d6] text-[#565656] py-2 rounded-md whitespace-nowrap  overflow-hidden text-ellipsis block max-w-full">
                                        Rp.{{ $d->gaji_awal }} - Rp.{{ $d->gaji_akhir }} per bulan
                                    </span>
                                </div>
                            </a>
                            <button type="button"
                                class="publish-btn block mt-5 bg-orange-500 px-10 py-2 rounded-md text-white hover:bg-orange-600 transition"
                                data-id="{{ $d->id }}">
                                Publish
                            </button>
                        </div>
                    </div>
                @endif
            @empty
                <!-- Konten kosong -->
                <div class="flex flex-col items-center justify-center">
                    <svg width="71" height="85" viewBox="0 0 71 85" fill="none"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <mask id="mask0_637_59844" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                            width="71" height="85">
                            <rect x="0.914062" width="69.6298" height="84.5162" fill="url(#pattern0_637_59844)" />
                        </mask>
                        <g mask="url(#mask0_637_59844)">
                            <rect x="9.4375" width="69.6298" height="84.5162" fill="#606060" fill-opacity="0.8" />
                        </g>
                        <defs>
                            <pattern id="pattern0_637_59844" patternContentUnits="objectBoundingBox" width="1"
                                height="1">
                                <use xlink:href="#image0_637_59844"
                                    transform="matrix(0.0111111 0 0 0.00915404 0 0.0880682)" />
                            </pattern>
                            <image id="image0_637_59844" width="90" height="90" preserveAspectRatio="none"
                                xlink:href="data:image/png;base64,..." />
                        </defs>
                    </svg>
                    <p class="text-gray-500 text-sm">Lowongan Kosong</p>
                </div>
            @endforelse
        </div>
        <div class="flex justify-center mt-8">
            {{-- <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2 rounded-lg shadow">
                Memuat
            </button> --}}
        </div>
    </div>
@else
    <!-- Jika tidak ada pasanglowongan -->
    <div class="bg-white text-gray-800">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between relative">
                <!-- Kiri: Logo + Info -->
                <div class="flex items-center gap-4">
                    @if (Auth::user()->role == 'perusahaan')
                        @if (Auth::user()->perusahaan->img_profile)
                            <img id="pu" class="w-20 h-20 object-cover rounded-full profile-img"
                                src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                        @else
                            <img id="pu" class="w-20 h-20 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                                alt="">
                        @endif
                    @else
                        <img class="w-10 h-10 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                            alt="">
                    @endif
                    <div>
                        <span class="font-semibold">{{ Auth::user()->username }}</span>
                        <p class="text-lg m-1">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                        <p class="text-sm text-gray-400">{{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                            {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }},
                            {{ Auth::user()->perusahaan->alamatUtama->kecamatan->nama ?? '-' }}</p>
                    </div>
                </div>


                <!-- Tombol tambah -->
                <div class="hidden md:block">
                    <a href="{{ route('lowongan.create.form') }}"
                        class="absolute h-16 w-16 border border-orange-500 rounded-md flex items-center justify-center text-orange-500 hover:bg-orange-50">
                        <i class="ph ph-plus text-md"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Kosong -->
    <div class="max-w-5xl mx-auto px-4 mb-10">
        <h2 class="text-base font-medium mb-3 flex">Lowongan</h2> 

        <div class="flex justify-end gap-2 mb-3">
            <select class="border rounded-md text-sm px-6 py-2">
                <option>Jenis Paket</option>
            </select>
            <select class="border rounded-md text-sm px-6 py-2">
                <option>Jenis Lowongan</option>
            </select>
        </div>

        <div class="border rounded-xl p-6 min-h-[400px] flex flex-col items-center justify-center relative">
            <!-- Tombol Tambah -->
            <a href="{{ route('lowongan.create.form') }}"
                class="absolute top-4 left-4 w-10 h-10 flex items-center justify-center border-2 border-orange-500 rounded-md text-orange-500 text-2xl">
                +
            </a>

            <!-- Konten kosong -->
            <div class="flex flex-col items-center justify-center">
                <svg width="71" height="85" viewBox="0 0 71 85" fill="none"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <mask id="mask0_637_59844" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                        width="71" height="85">
                        <rect x="0.914062" width="69.6298" height="84.5162" fill="url(#pattern0_637_59844)" />
                    </mask>
                    <g mask="url(#mask0_637_59844)">
                        <rect x="9.4375" width="69.6298" height="84.5162" fill="#606060" fill-opacity="0.8" />
                    </g>
                    <defs>
                        <pattern id="pattern0_637_59844" patternContentUnits="objectBoundingBox" width="1"
                            height="1">
                            <use xlink:href="#image0_637_59844"
                                transform="matrix(0.0111111 0 0 0.00915404 0 0.0880682)" />
                        </pattern>
                        <image id="image0_637_59844" width="90" height="90" preserveAspectRatio="none"
                            xlink:href="data:image/png;base64,..." />
                    </defs>
                </svg>
                <p class="text-gray-500 text-sm">Lowongan Kosong</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Publish -->
    <div id="publishModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
        <div class="bg-white p-6 rounded-md w-96 relative">
            <h2 class="text-lg font-bold mb-4">Konfirmasi Publish</h2>
            <p>Apakah Anda yakin ingin mem-publish lowongan ini?</p>

            <form id="publishForm" method="POST">
                @csrf
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" id="closeModal" class="px-4 py-2 border rounded-md">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md">Ya, Publish</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Konfirmasi Boost -->
    <div id="confirmBoostModal"
        class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 px-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-5 sm:p-6 relative text-center">

            <h2 class="text-lg sm:text-xl font-semibold mb-3">
                Konfirmasi Boost
            </h2>

            <p class="text-gray-600 mb-5">
                Anda akan menggunakan
                <span class="font-bold text-orange-500">
                    {{ number_format($hargaBoost) }} koin
                </span>
                untuk melakukan boost lowongan ini
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button onclick="closeConfirmBoost()"
                    class="px-4 py-2 rounded-lg border bg-gray-400 hover:bg-gray-500 w-full sm:w-auto">
                    Batal
                </button>

                <button onclick="processBoost()"
                    class="px-4 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600 w-full sm:w-auto">
                    Ya, Boost Sekarang
                </button>
            </div>

        </div>
    </div>  


    <!-- Modal Koin Tidak Cukup -->
    <div id="modalKoinKurang"
        class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl relative">

            <h2 class="text-xl font-semibold text-red-600 mb-3">
                Koin Tidak Cukup
            </h2>

            <p class="text-gray-700 mb-5">
                Koin Anda tidak mencukupi untuk melakukan boost.
                Silakan top up terlebih dahulu.
            </p>

            <div class="flex justify-end gap-3">
                <button onclick="closeModal('modalKoinKurang')"
                    class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    Tutup
                </button>

                {{-- <button onclick="goToTopup()" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">
                    Top Up Sekarang
                </button> --}}
            </div>
        </div>
    </div>




    @include('layouts.footer')

    <script>
        /*
                                              Unified modal script
                                              - Menyediakan global window.openModal(id) & window.closeModal(id) (aman)
                                              - Fungsi khusus untuk boost dan publish tanpa menimpa global names
                                              - Robust fetch handling (JSON / non-JSON) dan error logging
                                            */

        /* ---------- Safe global modal helpers ---------- */
        window.openModal = function(id) {
            const el = document.getElementById(id);
            if (!el) {
                console.warn('[openModal] element not found:', id);
                return;
            }
            el.classList.remove('hidden');
        };

        window.closeModal = function(id) {
            const el = document.getElementById(id);
            if (!el) {
                console.warn('[closeModal] element not found:', id);
                return;
            }
            el.classList.add('hidden');
        };

        /* ---------- Publish modal (avoid name collisions) ---------- */
        (function initPublishModal() {
            const publishModal = document.getElementById('publishModal');
            const closePublishBtn = document.getElementById('closeModal'); // note: button id in markup is `closeModal`
            const publishForm = document.getElementById('publishForm');

            if (!publishModal || !publishForm) return;

            document.querySelectorAll('.publish-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const lowonganId = this.dataset.id;
                    let route = "{{ route('lowongan.publish', ':id') }}";
                    route = route.replace(':id', lowonganId);
                    publishForm.action = route;

                    publishModal.classList.remove('hidden');
                    publishModal.classList.add('flex');
                });
            });

            if (closePublishBtn) {
                closePublishBtn.addEventListener('click', function() {
                    publishModal.classList.add('hidden');
                    publishModal.classList.remove('flex');
                });
            }

            // Tutup modal otomatis setelah submit
            publishForm.addEventListener('submit', function() {
                publishModal.classList.add('hidden');
                publishModal.classList.remove('flex');
            });
        })();

        /* ---------- Boost modal logic (does NOT override global open/close) ---------- */
        (function initBoostModal() {
            const openBtn = document.getElementById('openBoostBtn');
            const closeBtn = document.getElementById('closeBoostBtn');
            const boostModal = document.getElementById('boostModal');
            const boostSelect = document.getElementById('lowonganSelect');
            // selectedLowonganID will be set when opening confirm modal via showConfirmBoost()
            let selectedLowonganID = null;

            function showBoostModal() {
                if (!boostModal) return;
                boostModal.classList.remove('hidden');
                document.documentElement.style.overflow = 'hidden';
            }

            function hideBoostModal() {
                if (!boostModal) return;
                boostModal.classList.add('hidden');
                document.documentElement.style.overflow = '';
            }

            openBtn?.addEventListener('click', showBoostModal);
            closeBtn?.addEventListener('click', hideBoostModal);

            // close when clicking backdrop
            boostModal?.addEventListener('click', function(e) {
                if (e.target === boostModal) hideBoostModal();
            });

            // close on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && boostModal && !boostModal.classList.contains('hidden'))
                    hideBoostModal();
            });

            // expose safe names for compatibility
            window.showBoostModal = showBoostModal;
            window.hideBoostModal = hideBoostModal;

            /* ---------- Confirm Boost flow (uses global openModal/closeModal) ---------- */
            window.showConfirmBoost = function() {
                selectedLowonganID = boostSelect?.value ?? null;

                // TUTUP modal boost
                hideBoostModal();

                // BUKA modal konfirmasi boost
                window.openModal('confirmBoostModal');
            };


            window.closeConfirmBoost = function() {
                window.closeModal('confirmBoostModal');
            };

            // processBoost -> uses fetch and handles non-JSON responses safely
            window.processBoost = function() {
                if (!selectedLowonganID) {
                    // fallback: try reading directly from DOM select
                    selectedLowonganID = document.getElementById('lowonganSelect')?.value ?? null;
                }

                const url = "{{ route('boost.lowongan') }}";
                const payload = {
                    lowongan_id: selectedLowonganID
                };

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                             'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => {
                        // try parse json if possible, otherwise return text
                        const contentType = res.headers.get('content-type') || '';
                        if (contentType.includes('application/json')) {
                            return res.json();
                        }
                        return res.text().then(txt => {
                            // not JSON: return object for unified handling
                            return {
                                __rawText: txt,
                                ok: res.ok,
                                status: res.status
                            };
                        });
                    })
                    .then(data => {
                        // Always try to close the confirm modal first (safe)
                        try {
                            window.closeConfirmBoost();
                        } catch (e) {
                            console.warn(e);
                        }

                        if (data && data.type === 'verification') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Akun Belum Terverifikasi',
                                text: data.message || 'Akun perusahaan Anda belum terverifikasi.',
                                confirmButtonText: 'Mengerti'
                            });
                            return; // STOP SEMUA PROSES
                        }

                        // If server returned non-json object shape
                        if (data && data.__rawText !== undefined) {
                            console.warn('[processBoost] server returned non-JSON response:', data);
                            alert('Terjadi kesalahan. Silakan coba lagi atau cek console.');
                            console.log(data.__rawText);
                            return;
                        }

                        // expected JSON structure: { success: bool, koin_kurang?: bool, message?: string }
                        if (data.koin_kurang) {
                            window.openModal('modalKoinKurang');
                            return;
                        }

                        if (data.success) {
                            alert(data.message ?? 'Berhasil di-boost');
                            location.reload();
                            return;
                        }

                        // fallback
                        console.warn('[processBoost] unexpected response:', data);
                        alert(data.message ?? 'Terjadi kesalahan pada proses boost.');
                    })
                    .catch(err => {
                        console.error('[processBoost] fetch error:', err);
                        alert('Gagal terhubung ke server. Silakan coba lagi.');
                    });
            };

            // go to topup (close koin modal and open topup modal if exists)
            window.goToTopup = function() {
                window.closeModal('modalKoinKurang');
                window.openModal('modalTopup');
            };
        })();

        /* ---------- Utility: ensure there is no accidental global variable named `closeModal` overriding the function ---------- */
        (function guardNamingCollisions() {
            // If someone accidentally declared const closeModal = ... as element, warn them
            if (typeof closeModal !== 'function') {
                // but do not overwrite: ensure our functions exist
                if (typeof window.closeModal === 'function') {
                    // re-expose to global name in case it was shadowed
                    window.closeModal = window.closeModal;
                } else {
                    console.warn('[guardNamingCollisions] window.closeModal missing — modal helpers may not work');
                }
            }
        })();
    </script>


    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.96);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>




@endsection
