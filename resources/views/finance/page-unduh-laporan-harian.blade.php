<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Harian</title>

    {{-- CSS Lokal (jika ada) --}}
    <link rel="stylesheet" href="{{ public_path('build/assets/app.css') }}">

    {{-- Tailwind CDN (fallback, untuk tampilan di browser) --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="text-[12px] text-black font-sans mx-8 my-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-2">
        <div>
            <p><span class="font-bold">Email:</span> areakerja@gmail.com</p>
            <p><span class="font-bold">Telepon:</span> 0812-3456-7009</p>
        </div>
        <div class="flex items-center gap-2 font-bold text-[#00509d] text-lg">
            <img src="{{ $logoBase64 }}" alt="Logo Areakerja" class="w-9 h-auto mb-1">
            <span>areakerja.com</span>
        </div>
    </div>

    {{-- GARIS PEMBATAS --}}
    <div class="border-t-2 border-[#00509d] my-3"></div>

    {{-- JUDUL --}}
    <h2 class="text-center font-bold text-base mb-2">LAPORAN TRANSAKSI HARIAN</h2>
    <p class="text-center text-sm mb-4">Tanggal: {{ $tanggal ?? now()->translatedFormat('d F Y') }}</p>

    {{-- TABEL TRANSAKSI --}}
    <table class="w-full border border-black border-collapse text-[12px]">
        <thead>
            <tr class="bg-[#00509d] text-white text-center font-bold">
                <th class="border border-black py-1 px-2 w-[40px]">No.</th>
                <th class="border border-black py-1 px-2">No. Referensi</th>
                <th class="border border-black py-1 px-2">Perusahaan</th>
                <th class="border border-black py-1 px-2">Jenis Transaksi</th>
                <th class="border border-black py-1 px-2">Sumber Dana</th>
                <th class="border border-black py-1 px-2">Nominal (IDR)</th>
                <th class="border border-black py-1 px-2">Koin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $i => $t)
                <tr class="text-center">
                    <td class="border border-black py-1 px-2">{{ $i + 1 }}</td>
                    <td class="border border-black py-1 px-2">{{ $t->no_referensi ?? '-' }}</td>
                    <td class="border border-black py-1 px-2">{{ $t->dari ?? '-' }}</td>
                    <td class="border border-black py-1 px-2">{{ $t->pesanan ?? '-' }}</td>
                    <td class="border border-black py-1 px-2">{{ $t->sumber_dana ?? ($t->sumberDana ?? '-') }}</td>
                    <td class="border border-black text-right py-1 px-2">
                        {{ isset($t->nominal) && is_numeric($t->nominal) ? 'Rp ' . number_format($t->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="border border-black text-right py-1 px-2">{{ $t->koin ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="border border-black text-center py-2">Tidak ada transaksi untuk tanggal
                        ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- RINGKASAN --}}
    <div class="mt-5 space-y-1">
        <p><span class="font-bold">Total Tunai:</span> Rp{{ number_format($totalTunai ?? 0, 0, ',', '.') }}</p>
        <p><span class="font-bold">Total Koin:</span> {{ $totalKoin ?? 0 }} Koin</p>
        <p><span class="font-bold">Total Transaksi:</span> {{ $totalTransaksi ?? count($transaksi) }} Transaksi</p>
    </div>

    {{-- FOOTER --}}
    <div class="border-t border-black mt-10 pt-2 text-[12px]">
        <div class="flex justify-between">
            <div>
                <p class="font-bold text-[#00509d]">Areakerja.com</p>
                <p class="text-gray-600">Jl. Contoh No. 123, Jakarta, Indonesia</p>
            </div>
            <div class="text-right">
                <p>{{ $tanggalCetak ?? now()->translatedFormat('d F Y H:i') }}</p>
            </div>
        </div>
    </div>

</body>

</html>

