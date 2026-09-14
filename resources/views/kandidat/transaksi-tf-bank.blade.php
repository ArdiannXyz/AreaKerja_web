@extends('layouts.index')
@section('content')
    <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 md:px-14 py-12 bg-white rounded-xl shadow border mt-16">
        <!-- Header -->
        <h2 class="text-2xl font-medium mb-2">Detail Transaksi</h2>
        <hr class="border-b border-gray-200 mb-10">

        <!-- Grid 2 kolom -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- Kiri -->
            <div class="text-sm">
                <p class="font-medium text-gray-600">No. Referensi</p>
                <p class="text-lg font-semibold text-gray-800 mb-6">{{ $transaksi->no_referensi }}</p>

                <p class="font-medium text-gray-600 mb-2">Status Tagihan</p>
                <span
                    class="inline-block mb-2 px-8 py-2 rounded-full text-sm 
                @if ($transaksi->status == 'pending') bg-blue-100 text-[#003d7a]
                @elseif($transaksi->status == 'menunggu_verifikasi') bg-blue-100 text-blue-600
                @elseif($transaksi->status == 'diterima') bg-green-100 text-green-600
                @elseif($transaksi->status == 'expired') bg-gray-200 text-gray-600
                @else bg-red-100 text-red-600 @endif">
                    {{ $transaksi->status == 'pending' ? 'Menunggu Pembayaran' : ucfirst(str_replace('_', ' ', $transaksi->status)) }}
                </span>

                @if ($transaksi->status == 'pending')
                    <div class="mb-6">
                        <p class="inline text-gray-800 text-sm font-semibold">Batas Pembayaran :</p>
                        <span id="countdown" class="inline text-[#003d7a] font-semibold"></span>
                    </div>
                @endif

                <div class="mb-6">
                    <p class="font-medium mb-1 text-gray-600">Waktu</p>
                    <span class="text-gray-900 font-semibold">
                        {{ $transaksi->created_at->translatedFormat('d F Y H:i') }}
                    </span>
                </div>

                <div>
                    <p class="font-medium text-gray-600 mb-1">Metode Pembayaran</p>
                    <span class="text-gray-900 font-semibold">
                        Transfer {{ $transaksi->bank->nama_bank }}
                    </span>
                </div>
            </div>

            <!-- Rekening Tujuan -->
            <div class="border rounded-lg p-5 w-full text-left shadow-sm overflow-x-auto">
                @if ($transaksi->sumberDana === 'Qris')
                    <div class="flex flex-col items-center text-center">
                        <p class="text-gray-500 text-sm mb-2">Bayar Melalui</p>
                        <img src="{{ asset('images/qrrrr-removebg-preview.png') }}" alt="QRIS Logo"
                            class="w-24 mb-3 max-w-full h-auto">
                        <p class="text-lg font-semibold">QRIS</p>
                        <p class="text-gray-600 text-sm mb-3">NMID : ID12233445566778</p>
                        <img src="{{ asset('images/barcode.jpg') }}" alt="QRIS QR Code"
                            class="w-40 h-40 max-w-full object-contain">
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Rekening Tujuan</p>
                    <div class="flex justify-center items-center gap-3 overflow-x-auto">
                        <img src="{{ asset($transaksi->bank->logo_image) }}" alt="Bank Logo"
                            class="w-40 h-28 object-contain max-w-full">
                    </div>
                    <p class="font-semibold text-lg mt-2">{{ $transaksi->bank->nama_bank }}</p>
                    <p class="text-gray-600 text-sm mt-1">a/n {{ $transaksi->bank->owner }}</p>
                    <span class="copy-rek cursor-pointer text-gray-800 text-lg font-bold mt-1"
                        data-rek="{{ $transaksi->bank->no_rek }}">
                        {{ $transaksi->bank->no_rek }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm border border-gray-100 rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left font-semibold text-gray-500">Keterangan</th>
                        <th class="p-3 text-left font-semibold text-gray-500">Jumlah</th>
                        <th class="p-3 text-right font-semibold text-gray-500">Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b-2 border-gray-200">
                        <td class="p-3">{{ $transaksi->pesanan }}</td>
                        <td class="p-3 text-left">1</td>
                        <td class="p-3 text-right">Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot class="text-sm">
                    <tr>
                        <td></td>
                        <td class="p-3 text-left">Tagihan</td>
                        <td class="p-3 text-right">Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="p-3 text-left">Admin</td>
                        <td class="p-3 text-right">Rp. 2.000</td>
                    </tr>
                    <tr class="font-medium">
                        <td></td>
                        <td class="p-3 text-left">Total</td>
                        <td class="p-3 text-right">Rp. {{ number_format($transaksi->total + 2000, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="font-semibold border-b-4 border-gray-200">
                        <td></td>
                        <td class="p-3 text-left">Total Tagihan</td>
                        <td class="p-3 text-right">Rp. {{ number_format($transaksi->total + 2000, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Upload Bukti -->
        @if ($transaksi->status == 'pending' || $transaksi->status == 'ditolak')
            <div class="mb-8 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                <div class="flex items-center gap-2.5 mb-4">
                    <i class="ph-fill ph-upload-simple text-xl text-[#00509d]"></i>
                    <h3 class="text-base font-extrabold text-slate-900">Upload Bukti Pembayaran</h3>
                </div>

                @if ($transaksi->status == 'ditolak')
                    <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-2.5 text-rose-700 text-xs font-semibold">
                        <i class="ph-fill ph-warning-circle text-lg shrink-0"></i>
                        <span>Bukti transfer sebelumnya ditolak. Silakan unggah ulang foto/dokumen bukti pembayaran yang valid.</span>
                    </div>
                @endif

                <form action="{{ route('kandidat.catatan_cash.upload_bukti', $transaksi->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <!-- Tombol Upload Custom -->
                        <label for="bukti_kandidat"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl cursor-pointer transition shadow-2xs shrink-0">
                            <i class="ph ph-upload-simple text-base font-bold"></i>
                            <span>Pilih File Bukti</span>
                        </label>

                        <!-- Input File Hidden -->
                        <input type="file" id="bukti_kandidat" name="bukti" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,image/jpg,application/pdf" required class="hidden">

                        <div class="flex-1 min-w-0">
                            <!-- Nama File -->
                            <p id="kandidat-file-name" class="text-xs font-bold text-slate-700 truncate">
                                Belum ada file yang dipilih
                            </p>
                            <!-- Format dan Ukuran File -->
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Format: JPG, JPEG, PNG, atau PDF (Maks. 5MB)
                            </p>
                        </div>
                    </div>

                    @error('bukti')
                        <p class="text-xs font-semibold text-rose-500 mt-1 flex items-center gap-1">
                            <i class="ph ph-warning-circle font-bold"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror

                    <div class="pt-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                            <i class="ph ph-check-circle font-bold text-base"></i>
                            <span>{{ $transaksi->status == 'pending' ? 'Kirim Bukti Pembayaran' : 'Kirim Ulang Bukti Pembayaran' }}</span>
                        </button>
                    </div>
                </form>

                <script>
                    document.getElementById('bukti_kandidat')?.addEventListener('change', function() {
                        const fileNameEl = document.getElementById('kandidat-file-name');
                        if (this.files && this.files.length > 0) {
                            const file = this.files[0];
                            const sizeKb = (file.size / 1024).toFixed(1);
                            fileNameEl.textContent = file.name + ' (' + (sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb + ' KB') + ')';
                            fileNameEl.classList.add('text-[#00509d]');
                        } else {
                            fileNameEl.textContent = 'Belum ada file yang dipilih';
                            fileNameEl.classList.remove('text-[#00509d]');
                        }
                    });
                </script>
            </div>
        @endif

        <!-- Petunjuk Pembayaran -->
        <div class="w-full">
            <h3 class="text-2xl font-medium mb-3">Petunjuk Pembayaran</h3>
            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between py-3 border-b-2 border-gray-300">
                <span class="font-medium text-md">Transfer mBanking</span>
            </div>
            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between py-3 border-b-2 border-gray-300">
                <span class="font-medium text-md">Transfer iBanking</span>
            </div>
            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between py-3 border-b-2 border-gray-300">
                <span class="font-medium text-md">Transfer ATM</span>
            </div>
        </div>
    </div>


    <script>
        //SALIN NO REK
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".copy-rek").forEach(function(el) {
                el.addEventListener("click", function() {
                    const noRek = this.getAttribute("data-rek");

                    // Copy ke clipboard
                    navigator.clipboard.writeText(noRek).then(() => {
                        alert("Nomor rekening berhasil disalin: " + noRek);
                    }).catch(err => {
                        console.error("Gagal menyalin: ", err);
                    });
                });
            });
        });
        @if ($transaksi->status == 'pending' && $transaksi->expired_at)
            let expireTime = new Date("{{ $transaksi->expired_at }}").getTime();

            let timer = setInterval(function() {
                let now = new Date().getTime();
                let distance = expireTime - now;

                if (distance < 0) {
                    clearInterval(timer);
                    document.getElementById("countdown").innerHTML = "Expired";

                    // optional: auto reload untuk ubah status
                    location.reload();
                } else {
                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("countdown").innerHTML =
                        hours + " Jam " + minutes + " Menit " + seconds + " Detik";
                }
            }, 1000);
        @endif
    </script>

    @include('layouts.footer')
@endsection

