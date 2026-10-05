@extends('layouts.index-perusahaan')
@section('content')

<div class="w-full mx-auto bg-slate-50 min-h-screen pb-10 mt-20">

    {{-- ===== HERO HEADER ===== --}}
    <div style="background-color: #00509d;" class="px-6 py-8 shadow-md">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm font-medium mb-1" style="color: #bfdbfe;">Selamat Datang di Area Kerja 👋</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $perusahaan->nama_perusahaan }}</h1>
                <p class="text-sm mt-1" style="color: #93c5fd;">Kelola rekrutmen Anda dengan mudah dan efisien</p>
            </div>
            <div class="flex items-center gap-3 rounded-2xl px-5 py-3 w-fit" style="background: rgba(255,255,255,0.15);">
                <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-9 h-9">
                <div>
                    <p class="text-xs" style="color: #bfdbfe;">Saldo Koin</p>
                    <p class="text-white text-2xl font-bold leading-none">{{ number_format($perusahaan->koin_perusahaan ?? 0) }}</p>
                </div>
                <button onclick="toggleModal()"
                    class="ml-2 text-white text-xs font-semibold px-3 py-2 rounded-xl transition flex items-center gap-1"
                    style="background: #22c55e;">
                    <i class="ph ph-plus-circle text-base"></i> Top Up
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6 space-y-6">

        {{-- ===== KPI STAT CARDS ===== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Total Lowongan Aktif --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-briefcase text-2xl text-[#00509d]"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-medium">Lowongan Aktif</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalLowonganAktif }}</p>
                </div>
            </div>

            {{-- Total Pelamar --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-users text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-medium">Total Pelamar</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalPelamar }}</p>
                </div>
            </div>

            {{-- Iklan Aktif --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-megaphone text-2xl text-[#00509d]"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-medium">Iklan Aktif</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalIklanAktif }}</p>
                </div>
            </div>

            {{-- Saldo Koin --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-7 h-7">
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-medium">Saldo Koin</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($perusahaan->koin_perusahaan ?? 0) }}</p>
                </div>
            </div>

        </div>

        {{-- ===== GRAFIK + AKSI CEPAT ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Grafik Pelamar 6 Bulan --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-semibold text-slate-800">Tren Pelamar</h3>
                        <p class="text-slate-400 text-xs mt-0.5">6 bulan terakhir</p>
                    </div>
                    <div class="bg-blue-50 text-[#00509d] text-xs font-semibold px-3 py-1.5 rounded-lg">
                        Total: {{ array_sum($chartData) }}
                    </div>
                </div>
                <canvas id="pelamarChart" height="130"></canvas>
            </div>

            {{-- Aksi Cepat --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-800 mb-1">Aksi Cepat</h3>
                    <p class="text-slate-400 text-xs mb-5">Navigasi ke fitur utama</p>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('lowongan.saya.perusahaan') }}"
                        class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#00509d] hover:bg-blue-50 transition group">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 group-hover:bg-[#00509d] flex items-center justify-center transition">
                            <i class="ph ph-briefcase text-lg text-[#00509d] group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Kelola Lowongan</p>
                            <p class="text-xs text-slate-400">Tambah & atur lowongan kerja</p>
                        </div>
                        <i class="ph ph-caret-right text-slate-300 ml-auto group-hover:text-[#00509d] transition"></i>
                    </a>

                    <a href="{{ route('perusahaan.kandidat.ak') }}"
                        class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-green-500 hover:bg-green-50 transition group">
                        <div class="w-10 h-10 rounded-lg bg-green-50 group-hover:bg-green-500 flex items-center justify-center transition">
                            <i class="ph ph-magnifying-glass text-lg text-green-600 group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Cari Kandidat</p>
                            <p class="text-xs text-slate-400">Temukan kandidat terbaik</p>
                        </div>
                        <i class="ph ph-caret-right text-slate-300 ml-auto group-hover:text-green-500 transition"></i>
                    </a>

                    <a href="{{ route('perusahaan.kandidat.saya') }}"
                        class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#00509d] hover:bg-blue-50 transition group">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 group-hover:bg-[#00509d] flex items-center justify-center transition">
                            <i class="ph ph-users text-lg text-[#00509d] group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Kandidat Saya</p>
                            <p class="text-xs text-slate-400">Kandidat yang disimpan</p>
                        </div>
                        <i class="ph ph-caret-right text-slate-300 ml-auto group-hover:text-[#00509d] transition"></i>
                    </a>

                    <a href="{{ route('perusahaan.iklan.index') }}"
                        class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-green-500 hover:bg-green-50 transition group">
                        <div class="w-10 h-10 rounded-lg bg-green-50 group-hover:bg-green-500 flex items-center justify-center transition">
                            <i class="ph ph-megaphone text-lg text-green-600 group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Iklan Saya</p>
                            <p class="text-xs text-slate-400">Kelola kampanye iklan</p>
                        </div>
                        <i class="ph ph-caret-right text-slate-300 ml-auto group-hover:text-green-500 transition"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- ===== LOWONGAN AKTIF ===== --}}
        @php
            $publish = $lowongans->filter(fn($l) => !is_null($l->published_at));
            $draft   = $lowongans->filter(fn($l) => is_null($l->published_at));
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="ph ph-briefcase text-lg text-[#00509d]"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800">Lowongan Saya</h3>
                        <p class="text-xs text-slate-400">Lowongan aktif & draft</p>
                    </div>
                </div>
                <a href="{{ route('lowongan.saya.perusahaan') }}"
                    class="text-sm font-semibold text-[#00509d] hover:text-[#003d7a] flex items-center gap-1 transition">
                    Kelola <i class="ph ph-arrow-right"></i>
                </a>
            </div>

            {{-- Content --}}
            @if ($lowongans->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                        <i class="ph ph-briefcase text-3xl text-[#00509d]"></i>
                    </div>
                    <p class="text-slate-600 font-semibold mb-1">Belum Ada Lowongan</p>
                    <p class="text-slate-400 text-sm mb-4">Mulai pasang lowongan untuk menemukan kandidat terbaik</p>
                    <a href="{{ route('lowongan.saya.perusahaan') }}"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white px-5 py-2 rounded-xl text-sm font-semibold transition">
                        + Pasang Lowongan
                    </a>
                </div>

            @elseif ($publish->isEmpty() && $draft->isNotEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                    <div class="w-16 h-16 rounded-full bg-yellow-50 flex items-center justify-center mb-4">
                        <i class="ph ph-clock text-3xl text-yellow-500"></i>
                    </div>
                    <p class="text-slate-600 font-semibold mb-1">Lowongan Masih Draft</p>
                    <p class="text-slate-400 text-sm mb-4">Publish lowongan agar bisa dilihat oleh pelamar</p>
                    <a href="{{ route('lowongan.saya.perusahaan') }}"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white px-5 py-2 rounded-xl text-sm font-semibold transition">
                        Kelola Lowongan
                    </a>
                </div>

            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($lowongans as $lowongan)
                        @if ($lowongan->published_at)
                            @php
                                $paket = strtolower($lowongan->paket->nama ?? '');
                                $paketStyle = match ($paket) {
                                    'gold'   => 'bg-yellow-50 text-yellow-700 border-yellow-300',
                                    'silver' => 'bg-slate-50 text-slate-600 border-slate-300',
                                    'bronze' => 'bg-amber-50 text-amber-700 border-amber-300',
                                    default  => 'bg-slate-50 text-slate-500 border-slate-200',
                                };
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $lowongan->perusahaan->img_profile) }}"
                                        alt="logo" class="w-11 h-11 rounded-xl object-cover border border-slate-100 flex-shrink-0">
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm">{{ $lowongan->nama }}</p>
                                        <p class="text-slate-500 text-xs">{{ $lowongan->jenis }} · {{ $lowongan->alamat }}</p>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">
                                                Rp {{ number_format($lowongan->gaji_awal, 0, ',', '.') }} – {{ number_format($lowongan->gaji_akhir, 0, ',', '.') }}
                                            </span>
                                            <span class="text-xs text-slate-400">· {{ $lowongan->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 ml-15 sm:ml-0">
                                    <span class="text-xs px-2.5 py-1 rounded-lg border font-medium {{ $paketStyle }}">
                                        {{ ucfirst($lowongan->paket->nama ?? '-') }}
                                    </span>
                                    <a href="{{ route('perusahaan.pelamar', $lowongan->slug) }}"
                                        class="bg-[#00509d] hover:bg-[#003d7a] text-white text-xs px-4 py-2 rounded-xl font-semibold transition whitespace-nowrap">
                                        Lihat Pelamar
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===== TENTANG AREA KERJA ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Tentang Area Kerja</h3>
                <p class="text-xs text-slate-400 mt-0.5">Platform rekrutmen modern untuk perusahaan Anda</p>
            </div>
            <div class="grid md:grid-cols-2 gap-0">
                <div class="flex justify-center items-center p-6">
                    <img src="{{ asset('images/nari.jpg') }}" alt="Ilustrasi"
                        class="w-full max-w-xs object-contain rounded-xl">
                </div>
                <div class="p-6 grid sm:grid-cols-1 gap-4">
                    <div class="flex gap-4 p-4 rounded-xl bg-[#00509d] text-white">
                        <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-sm">01</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm mb-1">Cari Kandidat Terbaik</p>
                            <p class="text-blue-100 text-xs leading-relaxed">Area Kerja membantu Anda menemukan kandidat yang sesuai dengan kebutuhan posisi perusahaan.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-4 rounded-xl border-2 border-[#00509d] text-[#00509d]">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-sm">02</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm mb-1">Lowongan Selalu Terbaru</p>
                            <p class="text-slate-500 text-xs leading-relaxed">Sistem diperbarui setiap hari agar kandidat selalu mendapat informasi lowongan terkini dari Anda.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-4 rounded-xl border-2 border-[#00509d] text-[#00509d]">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-sm">03</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm mb-1">Kandidat Siap Kerja</p>
                            <p class="text-slate-500 text-xs leading-relaxed">Kandidat di Area Kerja sudah terverifikasi dan siap bekerja secara mental maupun keahlian.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('pelamarChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 220);
    gradient.addColorStop(0, 'rgba(0, 80, 157, 0.18)');
    gradient.addColorStop(1, 'rgba(0, 80, 157, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pelamar',
                data: {!! json_encode($chartData) !!},
                borderColor: '#00509d',
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: '#00509d',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#00509d',
                    titleColor: '#fff',
                    bodyColor: '#c1dcfd',
                    padding: 10,
                    cornerRadius: 10,
                    callbacks: {
                        label: (ctx) => ` ${ctx.parsed.y} pelamar`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        stepSize: 1,
                        precision: 0
                    },
                    grid: { color: '#f1f5f9' }
                }
            }
        }
    });
</script>

@include('layouts.footer')
@endsection
