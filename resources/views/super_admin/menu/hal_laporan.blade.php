<div class="p-4 sm:ml-64 border rounded-lg shadow">
    <div class="ml-[24px]">
        <h4 class="font-semibold text-lg ">Laporan Transaksi Penghasilan</h4>
        <p class="text-sm mt-3">Hanya catatan transaksi dalam 12 bulan terakhir akan dipertahankan. silahkan download
            salinan PDF anda</p>
    </div>
    {{-- riwayat transaksi --}}
    <div class="mb-8">
        <div class="flex justify-between items-start ml-[380px] mb-3">
            <select
                class="border border-blue-700 rounded-lg px-2 py-2 text-sm ml-[430px] text-blue-700 hover:bg-blue-700 hover:text-white">
                <option selected">Periode</option>
                <div class="font-bold text-black">
                    <option value="">1 Bulan Terakhir</option>
                    <option value="">3 Bulan Terakhir</option>
                </div>
            </select>
        </div>
        <div class="rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Jenis Trnasaksi</th>
                        <th class="px-4 py-2 text-left">Penghasilan</th>
                        <th class="px-4 py-2 text-left">Koin</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Baris -->
                    <tr class="border-t">
                        <td class="px-4 py-2">25 Juni 2024</td>
                        <td class="px-4 py-2">Top Up</td>
                        <td class="px-4 py-2">Rp. 1.000.000</td>
                        <td class="px-4 py-2">10</td>
                        <td class="px-4 py-2 text-blue-700"><i class="fa-solid fa-file-lines">detail</i></td>
                    </tr>
                    <tr class="border-t">
                        <td class="px-4 py-2">25 Juni 2024</td>
                        <td class="px-4 py-2">Top Up</td>
                        <td class="px-4 py-2">Rp. 1.000.000</td>
                        <td class="px-4 py-2">10</td>
                        <td class="px-4 py-2 text-blue-700"><i class="fa-solid fa-file-lines">detail</i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
