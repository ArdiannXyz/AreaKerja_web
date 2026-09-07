@extends('layouts.index')
@section('content')
    <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 md:px-8 pt-28 sm:pt-32 pb-16">
        
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 flex items-center p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm">
                <i class="ph-fill ph-check-circle text-2xl text-emerald-500 mr-3 flex-shrink-0"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-sm">
                <i class="ph-fill ph-warning-circle text-2xl text-rose-500 mr-3 flex-shrink-0"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-[#00509d] to-[#003d7a] p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm mb-2">
                            <i class="ph-bold ph-receipt"></i> Detail Transaksi
                        </span>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Pendaftaran Kandidat AreaKerja</h1>
                        <p class="text-white/80 text-xs sm:text-sm mt-1">No. Referensi: <span class="font-mono font-semibold tracking-wider text-white">{{ $transaksi->no_referensi }}</span></p>
                    </div>

                    <div class="text-left sm:text-right">
                        @if ($transaksi->status == 'pending')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-amber-400 text-amber-950 shadow-sm">
                                <i class="ph-bold ph-clock animate-pulse"></i> Menunggu Pembayaran
                            </span>
                        @elseif($transaksi->status == 'menunggu_verifikasi')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-sky-400 text-sky-950 shadow-sm">
                                <i class="ph-bold ph-hourglass-medium"></i> Menunggu Verifikasi Admin
                            </span>
                        @elseif($transaksi->status == 'diterima')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-emerald-400 text-emerald-950 shadow-sm">
                                <i class="ph-bold ph-check-circle"></i> Pembayaran Berhasil
                            </span>
                        @elseif($transaksi->status == 'expired')
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-rose-400 text-rose-950 shadow-sm">
                                <i class="ph-bold ph-x-circle"></i> Transaksi Kadaluarsa
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-rose-500 text-white shadow-sm">
                                <i class="ph-bold ph-warning-octagon"></i> Bukti Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Body Content -->
            <div class="p-6 sm:p-8 space-y-8">

                <!-- Countdown Timer (Pending Only) -->
                @if ($transaksi->status == 'pending' && $transaksi->expired_at)
                    <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 text-amber-600">
                                <i class="ph-bold ph-timer text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-amber-900">Batas Waktu Pembayaran</h4>
                                <p class="text-xs text-amber-700">Selesaikan pembayaran sebelum batas waktu berakhir</p>
                            </div>
                        </div>
                        <div class="bg-white px-4 py-2 rounded-lg border border-amber-300 shadow-sm flex items-center gap-2">
                            <span id="countdown" class="font-mono text-base font-bold text-amber-700">Memuat waktu...</span>
                        </div>
                    </div>
                @elseif($transaksi->status == 'expired')
                    <div class="bg-rose-50 border border-rose-200 rounded-xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0 text-rose-600">
                                <i class="ph-bold ph-x-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-rose-900">Waktu Pembayaran Telah Habis</h4>
                                <p class="text-xs text-rose-700">Transaksi ini telah kedaluwarsa. Silakan lakukan pendaftaran ulang kandidat.</p>
                            </div>
                        </div>
                        <a href="{{ route('pelamar.daftar-kandidat') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold rounded-xl transition shadow-sm whitespace-nowrap">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Daftar Ulang Kandidat
                        </a>
                    </div>
                @endif

                <!-- Grid Informasi & Rekening Tujuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Info Transaksi -->
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-5 space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-slate-200">
                            <i class="ph-bold ph-info text-[#00509d]"></i> Ringkasan Pembayaran
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Waktu Transaksi</span>
                                <span class="font-medium text-slate-800">{{ $transaksi->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Metode</span>
                                <span class="font-medium text-slate-800">
                                    {{ $transaksi->sumberDana === 'Qris' ? 'QRIS' : 'Transfer Bank ' . ($transaksi->bank->nama_bank ?? '') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Pesanan</span>
                                <span class="font-medium text-slate-800">{{ $transaksi->pesanan ?? 'Pendaftaran Kandidat' }}</span>
                            </div>
                            <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                                <span class="text-sm font-bold text-slate-700">Total Pembayaran</span>
                                <span class="text-xl font-extrabold text-[#00509d]">
                                    Rp {{ number_format($transaksi->total + 2000, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Rekening Tujuan / QRIS -->
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-5 flex flex-col justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-slate-200">
                                <i class="ph-bold ph-bank text-[#00509d]"></i> Rekening Tujuan
                            </h3>

                            @if ($transaksi->sumberDana === 'Qris')
                                <div class="mt-4 flex flex-col items-center text-center">
                                    <img src="{{ asset('images/qrrrr-removebg-preview.png') }}" alt="QRIS Logo" class="h-8 mb-2 max-w-full object-contain">
                                    <p class="text-xs text-slate-500 mb-2">Scan kode QRIS di bawah menggunakan aplikasi mBanking atau e-Wallet:</p>
                                    <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-sm inline-block">
                                        <img src="{{ asset('images/barcode.jpg') }}" alt="QRIS QR Code" class="w-36 h-36 object-contain">
                                    </div>
                                    <p class="text-[11px] font-mono text-slate-500 mt-2">NMID : ID12233445566778</p>
                                </div>
                            @else
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-center gap-4 bg-white p-3.5 rounded-xl border border-slate-200">
                                        @if ($transaksi->bank && $transaksi->bank->logo_image)
                                            <div class="w-16 h-12 flex-shrink-0 flex items-center justify-center p-1 bg-slate-50 rounded-lg border border-slate-100">
                                                <img src="{{ asset($transaksi->bank->logo_image) }}" alt="{{ $transaksi->bank->nama_bank }}" class="max-h-full max-w-full object-contain">
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-base font-bold text-slate-800">{{ $transaksi->bank->nama_bank ?? 'Bank Transfer' }}</p>
                                            <p class="text-xs text-slate-500">a/n <span class="font-medium text-slate-700">{{ $transaksi->bank->owner ?? 'AreaKerja' }}</span></p>
                                        </div>
                                    </div>

                                    <div class="bg-white p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                                        <div>
                                            <span class="text-xs text-slate-400 block mb-0.5">Nomor Rekening</span>
                                            <span id="targetRekening" class="font-mono text-base sm:text-lg font-bold text-slate-800 tracking-wider">
                                                {{ $transaksi->bank->no_rek ?? '-' }}
                                            </span>
                                        </div>
                                        <button type="button" onclick="copyToClipboard('{{ $transaksi->bank->no_rek ?? '' }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#00509d]/10 hover:bg-[#00509d] text-[#00509d] hover:text-white transition-all">
                                            <i class="ph-bold ph-copy"></i>
                                            <span>Salin</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Rincian Biaya Tabel -->
                <div class="overflow-hidden border border-slate-200 rounded-xl">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-100/80 text-xs text-slate-600 uppercase font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5 sm:p-4">Rincian Tagihan</th>
                                <th class="p-3.5 sm:p-4 text-center">Jumlah</th>
                                <th class="p-3.5 sm:p-4 text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr>
                                <td class="p-3.5 sm:p-4 font-medium text-slate-800">
                                    {{ $transaksi->pesanan ?? 'Pendaftaran Kandidat Siap Kerja' }}
                                    <span class="block text-xs text-slate-400 mt-0.5">Akses prioritas rekomendasi ke perusahaan mitra</span>
                                </td>
                                <td class="p-3.5 sm:p-4 text-center text-slate-600">1</td>
                                <td class="p-3.5 sm:p-4 text-right font-medium text-slate-800">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-3.5 sm:p-4 text-slate-500 text-right">Biaya Admin / Layanan</td>
                                <td class="p-3.5 sm:p-4 text-right font-medium text-slate-800">Rp 2.000</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50 font-bold border-t-2 border-slate-200">
                            <tr>
                                <td colspan="2" class="p-3.5 sm:p-4 text-right text-slate-800">Total yang harus ditransfer</td>
                                <td class="p-3.5 sm:p-4 text-right text-base font-extrabold text-[#00509d]">Rp {{ number_format($transaksi->total + 2000, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Section: Upload Bukti / Bukti Info -->
                <div class="border-t border-slate-200 pt-6">
                    @if ($transaksi->status == 'pending' || $transaksi->status == 'ditolak')
                        <div>
                            <div class="mb-4">
                                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <i class="ph-bold ph-upload-simple text-[#00509d]"></i> Upload Bukti Pembayaran
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    Unggah struk transfer / tangkapan layar bukti transaksi untuk segera diproses dan diverifikasi oleh admin.
                                </p>
                            </div>

                            @if ($transaksi->status == 'ditolak')
                                <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 flex items-start gap-3">
                                    <i class="ph-fill ph-warning-octagon text-xl text-rose-500 flex-shrink-0 mt-0.5"></i>
                                    <div class="text-xs sm:text-sm text-rose-800">
                                        <p class="font-bold">Bukti Transfer Ditolak</p>
                                        <p class="mt-0.5">Bukti transfer sebelumnya belum valid atau tidak sesuai. Silakan upload ulang bukti pembayaran yang sah.</p>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('kandidat.catatan_cash.upload_bukti', $transaksi->id) }}" method="POST" enctype="multipart/form-data" 
                                  x-data="uploadPreviewHandler()" class="space-y-4">
                                @csrf

                                <!-- Modern Drag & Drop / File Input Box -->
                                <div class="relative border-2 border-dashed rounded-2xl p-6 transition-all text-center"
                                     :class="isDragging ? 'border-[#00509d] bg-[#00509d]/5' : (hasFile ? 'border-emerald-400 bg-emerald-50/30' : (clientError ? 'border-rose-300 bg-rose-50/20' : 'border-slate-300 hover:border-[#00509d] bg-slate-50/50 hover:bg-[#00509d]/5'))"
                                     @dragover.prevent="isDragging = true"
                                     @dragleave.prevent="isDragging = false"
                                     @drop.prevent="handleDrop($event)">
                                    
                                    <input type="file" name="bukti" id="buktiInput" accept="image/png, image/jpeg, image/jpg, image/webp"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           @change="handleFileChange($event)" required>

                                    <!-- State: No File Selected -->
                                    <div x-show="!hasFile" class="flex flex-col items-center justify-center py-4">
                                        <div class="w-14 h-14 rounded-2xl bg-[#00509d]/10 text-[#00509d] flex items-center justify-center mb-3 transition-transform hover:scale-110">
                                            <i class="ph-bold ph-cloud-arrow-up text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-800 mb-1">
                                            <span class="text-[#00509d] underline cursor-pointer">Klik untuk memilih file</span> atau tarik dan lepas file di sini
                                        </p>
                                        <p class="text-xs text-slate-500 font-medium">Format: <strong class="text-slate-700">JPG, JPEG, PNG, WEBP</strong></p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Batas ukuran: <span class="font-semibold text-slate-600">Min. 20 KB — Maks. 2 MB</span></p>
                                    </div>

                                    <!-- State: File Selected & Preview -->
                                    <div x-show="hasFile" x-cloak class="flex flex-col items-center justify-center py-2 relative z-20">
                                        <div class="relative mb-3 group">
                                            <img :src="previewUrl" alt="Preview Bukti" class="max-h-48 max-w-full rounded-xl border border-slate-200 shadow-sm object-contain bg-white">
                                            <button type="button" @click="removeFile()" class="absolute -top-2 -right-2 w-7 h-7 bg-rose-500 text-white rounded-full flex items-center justify-center shadow hover:bg-rose-600 transition">
                                                <i class="ph-bold ph-x text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs font-semibold text-slate-800 truncate max-w-xs" x-text="fileName"></p>
                                            <p class="text-[11px] text-slate-500 mt-0.5" x-text="fileSize"></p>
                                            <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center justify-center gap-1">
                                                <i class="ph-bold ph-check-circle"></i> File gambar valid & siap diunggah
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="clientError" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 font-medium flex items-center gap-2">
                                    <i class="ph-bold ph-warning-circle text-base text-rose-500 flex-shrink-0"></i>
                                    <span x-text="clientError"></span>
                                </div>

                                @error('bukti')
                                    <p class="text-xs text-rose-500 font-medium flex items-center gap-1 mt-1">
                                        <i class="ph-bold ph-warning-circle"></i> {{ $message }}
                                    </p>
                                @enderror

                                <div class="flex justify-end pt-2">
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#00509d] hover:bg-[#003d7a] text-white text-sm font-semibold rounded-xl transition shadow hover:shadow-md w-full sm:w-auto">
                                        <i class="ph-bold ph-paper-plane-tilt"></i>
                                        <span>{{ $transaksi->status == 'pending' ? 'Kirim Bukti Pembayaran' : 'Upload Ulang Bukti' }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @elseif($transaksi->status == 'menunggu_verifikasi' || $transaksi->status == 'diterima')
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 pb-4 border-b border-slate-200">
                                <div>
                                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                        <i class="ph-bold ph-file-check text-[#00509d]"></i> Bukti Pembayaran Terkirim
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $transaksi->status == 'menunggu_verifikasi' ? 'Admin sedang memverifikasi pembayaran Anda.' : 'Bukti pembayaran telah disetujui.' }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $transaksi->status == 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700' }}">
                                    {{ $transaksi->status == 'diterima' ? 'Terverifikasi' : 'Proses Verifikasi' }}
                                </span>
                            </div>

                            @if ($transaksi->bukti)
                                <div class="flex flex-col sm:flex-row items-center gap-5">
                                    <div class="relative group cursor-pointer" onclick="window.open('{{ asset('storage/' . $transaksi->bukti) }}', '_blank')">
                                        <img src="{{ asset('storage/' . $transaksi->bukti) }}" alt="Bukti Transfer" class="w-36 h-36 object-cover rounded-xl border border-slate-300 shadow-sm group-hover:opacity-90 transition">
                                        <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1">
                                            <i class="ph-bold ph-magnifying-glass-plus"></i> Perbesar
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-600 space-y-1.5">
                                        <p><strong class="text-slate-800">Status:</strong> {{ $transaksi->status == 'menunggu_verifikasi' ? 'Menunggu Konfirmasi Admin' : 'Pembayaran Sukses' }}</p>
                                        <p><strong class="text-slate-800">File:</strong> <a href="{{ asset('storage/' . $transaksi->bukti) }}" target="_blank" class="text-[#00509d] underline font-medium">Lihat Bukti Full Size</a></p>
                                        <p class="text-slate-400">Jika ada kendala pembayaran, silakan hubungi tim support AreaKerja.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Petunjuk Pembayaran Accordion -->
                <div class="border-t border-slate-200 pt-6" x-data="{ activeTab: null }">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ph-bold ph-book-open text-[#00509d]"></i> Petunjuk Pembayaran
                    </h3>

                    <div class="space-y-3">
                        <!-- mBanking -->
                        <div class="border border-slate-200 rounded-xl overflow-hidden transition-all">
                            <button type="button" @click="activeTab = activeTab === 1 ? null : 1" class="w-full flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/70 text-left font-semibold text-sm text-slate-800 transition">
                                <span class="flex items-center gap-2">
                                    <i class="ph-bold ph-device-mobile text-[#00509d]"></i> Transfer via Mobile Banking (m-Banking)
                                </span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200" :class="activeTab === 1 ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="activeTab === 1" x-collapse x-cloak class="p-4 bg-white border-t border-slate-100 text-xs text-slate-600 leading-relaxed space-y-2">
                                <ol class="list-decimal list-inside space-y-1.5">
                                    <li>Buka aplikasi Mobile Banking pada smartphone Anda.</li>
                                    <li>Pilih menu <strong>Transfer</strong> &gt; <strong>Transfer Antar Bank / Sesama Bank</strong>.</li>
                                    <li>Masukkan nomor rekening tujuan di atas dan pastikan nama penerima sesuai.</li>
                                    <li>Masukkan nominal pembayaran tepat sesuai tagihan: <strong>Rp {{ number_format($transaksi->total + 2000, 0, ',', '.') }}</strong>.</li>
                                    <li>Konfirmasi transaksi dan selesaikan pembayaran.</li>
                                    <li>Simpan tangkapan layar (screenshot) bukti transfer lalu upload pada form di atas.</li>
                                </ol>
                            </div>
                        </div>

                        <!-- iBanking -->
                        <div class="border border-slate-200 rounded-xl overflow-hidden transition-all">
                            <button type="button" @click="activeTab = activeTab === 2 ? null : 2" class="w-full flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/70 text-left font-semibold text-sm text-slate-800 transition">
                                <span class="flex items-center gap-2">
                                    <i class="ph-bold ph-globe text-[#00509d]"></i> Transfer via Internet Banking (i-Banking)
                                </span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200" :class="activeTab === 2 ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="activeTab === 2" x-collapse x-cloak class="p-4 bg-white border-t border-slate-100 text-xs text-slate-600 leading-relaxed space-y-2">
                                <ol class="list-decimal list-inside space-y-1.5">
                                    <li>Login ke portal Internet Banking bank Anda.</li>
                                    <li>Pilih menu <strong>Transfer Dana</strong>.</li>
                                    <li>Masukkan nomor rekening tujuan yang tertera di atas.</li>
                                    <li>Masukkan nominal transfer sesuai tagihan.</li>
                                    <li>Otorisasi transaksi menggunakan Token / OTP Anda.</li>
                                    <li>Unduh atau screenshot bukti transfer untuk diunggah.</li>
                                </ol>
                            </div>
                        </div>

                        <!-- ATM -->
                        <div class="border border-slate-200 rounded-xl overflow-hidden transition-all">
                            <button type="button" @click="activeTab = activeTab === 3 ? null : 3" class="w-full flex items-center justify-between p-4 bg-slate-50/70 hover:bg-slate-100/70 text-left font-semibold text-sm text-slate-800 transition">
                                <span class="flex items-center gap-2">
                                    <i class="ph-bold ph-credit-card text-[#00509d]"></i> Transfer via Mesin ATM
                                </span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200" :class="activeTab === 3 ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="activeTab === 3" x-collapse x-cloak class="p-4 bg-white border-t border-slate-100 text-xs text-slate-600 leading-relaxed space-y-2">
                                <ol class="list-decimal list-inside space-y-1.5">
                                    <li>Masukkan Kartu ATM dan PIN Anda di mesin ATM.</li>
                                    <li>Pilih menu <strong>Transaksi Lainnya</strong> &gt; <strong>Transfer</strong>.</li>
                                    <li>Pilih bank tujuan dan masukkan nomor rekening di atas.</li>
                                    <li>Masukkan nominal pembayaran secara tepat.</li>
                                    <li>Ambil dan foto struk bukti pembayaran dari mesin ATM lalu upload pada halaman ini.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Upload Handler & Countdown & Copy -->
    <script>
        function uploadPreviewHandler() {
            return {
                isDragging: false,
                hasFile: false,
                previewUrl: '',
                fileName: '',
                fileSize: '',
                clientError: '',
                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.loadFile(file);
                    }
                },
                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file) {
                        const input = document.getElementById('buktiInput');
                        if (input) input.files = event.dataTransfer.files;
                        this.loadFile(file);
                    }
                },
                loadFile(file) {
                    this.clientError = '';
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    const ext = file.name.split('.').pop().toLowerCase();
                    const validExts = ['jpeg', 'jpg', 'png', 'webp'];

                    // 1. Validasi Format Gambar
                    if (!validTypes.includes(file.type) && !validExts.includes(ext)) {
                        this.removeFile();
                        this.clientError = 'Format file tidak didukung. Harap pilih gambar dengan format JPG, JPEG, PNG, atau WEBP.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Format Tidak Valid',
                                text: 'File harus berupa gambar (JPG, JPEG, PNG, atau WEBP). Berkas lain seperti PDF atau dokumen tidak diizinkan.',
                                confirmButtonColor: '#00509d',
                                confirmButtonText: 'Tutup'
                            });
                        }
                        return;
                    }

                    // 2. Validasi Ukuran Minimum (20 KB = 20 * 1024 bytes)
                    const minSize = 20 * 1024;
                    if (file.size < minSize) {
                        this.removeFile();
                        this.clientError = 'Ukuran file gambar terlalu kecil (minimal 20 KB). Harap unggah foto bukti yang jelas.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Ukuran Terlalu Kecil',
                                text: 'Ukuran file bukti pembayaran minimal adalah 20 KB agar gambar dapat terbaca jelas.',
                                confirmButtonColor: '#00509d',
                                confirmButtonText: 'Paham'
                            });
                        }
                        return;
                    }

                    // 3. Validasi Ukuran Maksimum (2 MB = 2 * 1024 * 1024 bytes)
                    const maxSize = 2 * 1024 * 1024;
                    if (file.size > maxSize) {
                        this.removeFile();
                        this.clientError = 'Ukuran file gambar terlalu besar (maksimal 2 MB). Harap perkecil atau pilih file lain.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Ukuran Terlalu Besar',
                                text: 'Ukuran file maksimal adalah 2 MB. Silakan kompres atau pilih gambar yang lebih kecil.',
                                confirmButtonColor: '#00509d',
                                confirmButtonText: 'Paham'
                            });
                        }
                        return;
                    }

                    this.hasFile = true;
                    this.fileName = file.name;
                    this.fileSize = (file.size / 1024).toFixed(1) + ' KB' + (file.size >= 1024 * 1024 ? ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)' : '');
                    this.previewUrl = URL.createObjectURL(file);
                },
                removeFile() {
                    this.hasFile = false;
                    this.previewUrl = '';
                    this.fileName = '';
                    this.fileSize = '';
                    const input = document.getElementById('buktiInput');
                    if (input) input.value = '';
                }
            };
        }

        function copyToClipboard(text) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersalin!',
                        text: 'Nomor rekening ' + text + ' berhasil disalin ke clipboard.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert('Nomor rekening ' + text + ' berhasil disalin!');
                }
            }).catch(err => {
                console.error('Gagal menyalin:', err);
            });
        }

        @if ($transaksi->status == 'pending' && $transaksi->expired_at)
            document.addEventListener('DOMContentLoaded', function () {
                let expireTime = new Date("{{ $transaksi->expired_at->format('Y-m-d H:i:s') }}").getTime();
                let hasReloaded = false;

                function updateCountdown() {
                    let now = new Date().getTime();
                    let distance = expireTime - now;

                    let countdownEl = document.getElementById("countdown");
                    if (!countdownEl) return;

                    if (distance <= 0) {
                        countdownEl.innerHTML = "<span class='text-rose-600 font-bold'>Waktu Pembayaran Habis</span>";
                        if (!hasReloaded) {
                            hasReloaded = true;
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    } else {
                        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        let minutes = Math.floor((distance % (1000 * 60)) / (1000 * 60));
                        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        countdownEl.innerHTML =
                            String(hours).padStart(2, '0') + "j : " +
                            String(minutes).padStart(2, '0') + "m : " +
                            String(seconds).padStart(2, '0') + "s";
                    }
                }

                updateCountdown();
                let timer = setInterval(updateCountdown, 1000);
            });
        @endif
    </script>

    @include('layouts.footer')
@endsection
