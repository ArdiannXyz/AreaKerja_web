@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white text-gray-800 mt-24">
        <!-- Header Perusahaan -->
        <div class="max-w-5xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between relative">
                <!-- Kiri: Logo + Info -->
                <div class="flex items-center gap-4">
                    @if (Auth::user()->role == 'perusahaan')
                        @if (Auth::user()->perusahaan->img_profile)
                            <img id="pu" class="w-20 h-20 object-cover rounded-full profile-img"
                                src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                        @else
                            <img id="pu" class="w-20 h-20 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="Profile">
                        @endif
                    @else
                        <img class="w-10 h-10 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                            alt="Profile">
                    @endif
                    <div>
                        <span class="font-bold text-xl">{{ Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username }}</span>
                        <p class="text-base text-gray-600 mt-0.5">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                        <p class="text-sm text-gray-400 mt-0.5 flex items-center gap-1">
                            <i class="ph ph-map-pin text-[#00509d]"></i>
                            {{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                            {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lowongan Saya -->
    <div class="max-w-5xl mx-auto px-4 mb-16">
        <!-- Title & Filter Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Lowongan Saya</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh lowongan aktif dan status pendaftaran kerja perusahaan Anda.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Tombol Tambah Lowongan -->
                <a href="{{ route('lowongan.create.form') }}"
                    class="h-10 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-4 rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 whitespace-nowrap text-sm">
                    <i class="ph ph-plus text-base font-bold"></i> Tambah Lowongan
                </a>

                <!-- Form Filter -->
                <form method="GET" class="flex items-center gap-2 flex-wrap">
                    <select name="jenis"
                        class="h-10 bg-white border border-slate-300 rounded-xl text-sm px-3 text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                        <option value="">Semua Jenis</option>
                        @foreach ($jenisLowongan as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="h-10 bg-[#00509d] hover:bg-[#003d7a] text-white px-3.5 text-sm font-bold rounded-xl shadow-xs transition flex items-center gap-1">
                        <i class="ph ph-funnel text-base"></i> Filter
                    </button>

                    @if(request('jenis'))
                        <a href="{{ route('lowongan.saya.perusahaan') }}"
                            class="h-10 bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 px-3 text-sm font-semibold rounded-xl flex items-center justify-center transition shadow-2xs" title="Reset Filter">
                            <i class="ph ph-arrow-counter-clockwise text-base"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Alert Success / Error -->
        @if (session('success'))
            <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2.5">
                <i class="ph ph-check-circle text-emerald-600 text-xl shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center gap-2.5">
                <i class="ph ph-warning-circle text-rose-600 text-xl shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- List Lowongan -->
        <div class="flex flex-col gap-4">
            @forelse ($Data as $d)
                <div class="border rounded-2xl shadow-sm bg-white p-5 md:p-6 border-slate-200 hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-4 mb-4">
                        <div class="flex items-start gap-4">
                            @if (Auth::user()->perusahaan->img_profile)
                                <img src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200" alt="Logo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->perusahaan->nama_perusahaan ?? 'P') }}&background=00509d&color=fff&size=128" class="w-12 h-12 object-cover rounded-xl border border-slate-200" alt="Logo">
                            @endif
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <p class="text-xs font-semibold text-slate-500">{{ Auth::user()->perusahaan->nama_perusahaan }}</p>
                                    @if ($d->status === 'disembunyikan')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">
                                            <i class="ph-bold ph-flag text-xs"></i> Ditinjau moderator
                                        </span>
                                    @elseif ($d->status === 'tutup')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">
                                            <i class="ph-bold ph-lock-key text-xs"></i> Pendaftaran Ditutup (Kuota Terpenuhi)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Aktif (Menerima Pelamar)
                                        </span>
                                    @endif

                                    @if (method_exists($d, 'isBoosted') && $d->isBoosted())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-blue-50 text-[#00509d] text-xs font-bold rounded-full border border-blue-200">
                                            <i class="ph-fill ph-rocket text-xs"></i> VIP Boost Aktif
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('lowongan.detail', ['perusahaan' => $d->perusahaan->slug, 'lowongan' => $d->slug]) }}" class="hover:text-[#003d7a] transition">
                                    <h1 class="font-extrabold text-lg text-slate-900 leading-snug">{{ $d->nama }} - <span class="text-slate-600 text-base font-medium">{{ $d->jenis }}</span></h1>
                                </a>
                                <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1"><i class="ph ph-map-pin text-[#00509d]"></i> {{ $d->alamat ?? 'Lokasi Penempatan' }}</p>
                            </div>
                        </div>

                        <!-- Tombol Aksi (Tutup/Buka, Edit, Hapus) -->
                        <div class="flex items-center gap-2 shrink-0 flex-wrap">
                            @if ($d->status !== 'disembunyikan')
                                <form action="{{ route('lowongan.toggleStatus', $d->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    @if ($d->status === 'tutup')
                                        <button type="submit" class="h-8.5 px-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 text-emerald-700 text-xs font-semibold rounded-lg shadow-2xs transition flex items-center gap-1.5" title="Buka Kembali Pendaftaran">
                                            <i class="ph-bold ph-lock-key-open text-sm font-bold text-emerald-600"></i> Buka Pendaftaran
                                        </button>
                                    @else
                                        <button type="submit" class="h-8.5 px-3 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg shadow-2xs transition flex items-center gap-1.5" title="Tutup Pendaftaran (Kuota Full)">
                                            <i class="ph ph-lock-key text-sm text-slate-500"></i> Tutup Lowongan (Kuota Full)
                                        </button>
                                    @endif
                                </form>
                            @endif

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
                        @if (!$d->gaji_hidden)
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-700 font-bold rounded-lg border border-slate-200">
                                Gaji: Rp {{ number_format($d->gaji_awal, 0, ',', '.') }} - Rp {{ number_format($d->gaji_akhir, 0, ',', '.') }} / bulan
                            </span>
                        @else
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-500 font-bold rounded-lg border border-slate-200">
                                Gaji tidak ditampilkan
                            </span>
                        @endif

                        @if ($d->expired_at)
                            <span class="text-slate-500 font-medium">
                                Sisa Masa Tayang: <span id="countdown-{{ $d->id }}" class="text-red-500 font-bold"></span>
                            </span>
                        @endif
                    </div>
                </div>

                @if ($d->expired_at)
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const countdownEl = document.getElementById('countdown-{{ $d->id }}');
                            const expiredAt = new Date("{{ \Carbon\Carbon::parse($d->expired_at)->format('Y-m-d H:i:s') }}").getTime();

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
                @endif
            @empty
                <!-- Konten kosong -->
                <div class="border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center bg-slate-50/50 flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#00509d] flex items-center justify-center mb-4">
                        <i class="ph ph-briefcase text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-800 mb-1">Belum Ada Lowongan</h3>
                    <p class="text-xs text-slate-500 max-w-sm mb-6">Mulai pasang lowongan pekerjaan perusahaan Anda sekarang untuk menjangkau ribuan talenta terbaik di AreaKerja.</p>
                    <a href="{{ route('lowongan.create.form') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl text-sm transition shadow-sm">
                        <i class="ph ph-plus-circle text-base"></i> Pasang Lowongan Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    @include('layouts.footer')
@endsection
