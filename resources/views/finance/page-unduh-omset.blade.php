<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Omset Perusahaan</title>
    <link rel="stylesheet" href="{{ public_path('build/assets/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="text-[12px] text-black font-sans mx-8 my-6">

    <!-- Header -->
    <div class="flex justify-between items-start mb-1">
        <div>
            <p><span class="font-bold">Email :</span> Areakerja@gmail.com</p>
            <p><span class="font-bold">Telepon :</span> 081234567009</p>
        </div>
        <div class="flex items-center justify-end gap-1 font-bold text-[#00509d] text-lg">
           <img src="{{ $logoBase64 }}" alt="Logo Areakerja" class="w-9 h-auto mb-1">
            <span>areakerja.com</span>
        </div>
    </div>

    <!-- Garis oranye -->
    <div class="border-t-2 border-[#00509d] my-2"></div>

    <!-- Judul -->
    <h2 class="text-center font-bold mb-4">LAPORAN OMSET PERUSAHAAN</h2>

    <!-- Tabel -->
    <table class="w-full border border-black border-collapse text-[12px]">
        <thead>
            <tr class="bg-[#00509d] text-white text-center font-bold">
                <th class="border border-black py-1 px-2 w-[40px]">No.</th>
                <th class="border border-black py-1 px-2">Bulan</th>
                <th class="border border-black py-1 px-2">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($omsetPerBulan as $index => $item)
                <tr>
                    <td class="border border-black text-center py-1 px-2">{{ $index + 1 }}</td>
                    <td class="border border-black py-1 px-2">{{ $item['bulan'] }}</td>
                    <td class="border border-black text-right py-1 px-2">Rp
                        {{ number_format($item['total'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Ringkasan -->
    <div class="mt-4 space-y-1">
        <p><span class="font-bold">Jumlah Bulan</span> : {{ $jumlahBulan }}</p>
        <p><span class="font-bold">Total Omset</span> : Rp {{ number_format($totalOmset, 2, ',', '.') }}</p>
        <p><span class="font-bold">Rata-rata</span> : Rp {{ number_format($rataRata, 2, ',', '.') }}</p>
    </div>

    <!-- Footer -->
    <div class="border-t border-black mt-10 pt-2 text-[12px]">
        <div class="flex justify-between">
            <div>
                <p class="font-bold">Areakerja.com</p>
                <p class="text-gray-500">Alamat dari Areakerja.com</p>
            </div>
            <div class="text-right">
                <p>{{ $tanggal }}</p>
            </div>
        </div>
    </div>

</body>

</html>

