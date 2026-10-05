<div class="border-b-2 border-[#00509d] pb-4 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#00509d] tracking-tight">AreaKerja</h1>
            <p class="text-xs text-gray-500">Platform Lowongan Kerja & Karir Indonesia</p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-gray-800">Laporan Email Subscriber</h2>
            <p class="text-xs text-gray-500">Tanggal Cetak: <strong>{{ $tanggal }}</strong></p>
        </div>
    </div>
</div>

<div class="mb-4 flex justify-between items-center text-xs text-gray-600">
    <span>Total Data: <strong>{{ count($subscribers) }} Subscriber</strong></span>
    <span>Status: <strong>Aktif</strong></span>
</div>

<table class="w-full border-collapse text-[11px]">
    <thead>
        <tr class="bg-[#00509d] text-white">
            <th class="border border-gray-300 px-3 py-2 text-center w-10">No</th>
            <th class="border border-gray-300 px-3 py-2 text-left">Email Subscriber</th>
            <th class="border border-gray-300 px-3 py-2 text-center w-28">Sumber</th>
            <th class="border border-gray-300 px-3 py-2 text-left">Nama / Pengguna</th>
            <th class="border border-gray-300 px-3 py-2 text-center w-28">Tanggal Daftar</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($subscribers as $i => $sub)
            <tr class="{{ $i % 2 === 1 ? 'bg-gray-50' : 'bg-white' }}">
                <td class="border border-gray-200 px-3 py-2 text-center text-gray-500">
                    {{ $i + 1 }}
                </td>

                <td class="border border-gray-200 px-3 py-2 font-medium text-gray-900">
                    {{ $sub->email }}
                </td>

                <td class="border border-gray-200 px-3 py-2 text-center">
                    @if ($sub->pelamar_id)
                        <span class="text-blue-700 font-semibold">Pelamar</span>
                    @elseif ($sub->perusahaan_id)
                        <span class="text-emerald-700 font-semibold">Perusahaan</span>
                    @else
                        <span class="text-gray-500">Guest</span>
                    @endif
                </td>

                <td class="border border-gray-200 px-3 py-2 text-gray-800">
                    @if ($sub->pelamar)
                        {{ $sub->pelamar->nama_pelamar ?? '-' }}
                    @elseif ($sub->perusahaan)
                        {{ $sub->perusahaan->nama_perusahaan ?? '-' }}
                    @else
                        -
                    @endif
                </td>

                <td class="border border-gray-200 px-3 py-2 text-center text-gray-600">
                    {{ $sub->created_at ? $sub->created_at->format('d-m-Y') : '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="border border-gray-200 px-3 py-6 text-center text-gray-400">
                    Tidak ada data subscriber yang tersedia
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-8 pt-4 border-t border-gray-200 flex justify-between items-center text-[10px] text-gray-400">
    <span>AreaKerja Super Admin Panel</span>
    <span>Dicetak otomatis oleh sistem pada {{ now()->format('d M Y, H:i') }} WIB</span>
</div>

