@extends('layouts.index-perusahaan')
@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-28 pb-16">
        <!-- Breadcrumb / Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('perusahaan.dashboard') }}" class="text-xs text-slate-500 hover:text-[#00509d] inline-flex items-center gap-1 mb-2">
                    <i class="ph ph-arrow-left"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Pembayaran & Tagihan</h1>
                <p class="text-xs text-slate-500 mt-0.5">Sistem Pembayaran Otomatis &bull; Powered by Xendit Payment Gateway</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if ($transaksi->status == 'pending') bg-amber-100 text-amber-700 border border-amber-200
                    @elseif($transaksi->status == 'diterima' || $transaksi->status == 'lunas') bg-emerald-100 text-emerald-700 border border-emerald-200
                    @elseif($transaksi->status == 'expired') bg-slate-100 text-slate-600 border border-slate-200
                    @else bg-rose-100 text-rose-700 border border-rose-200 @endif">
                    @if ($transaksi->status == 'pending')
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse mr-1.5"></span>Menunggu Pembayaran
                    @elseif ($transaksi->status == 'diterima' || $transaksi->status == 'lunas')
                        <i class="ph ph-check-circle mr-1"></i>Lunas / Terverifikasi
                    @elseif ($transaksi->status == 'expired')
                        <i class="ph ph-clock mr-1"></i>Kedaluwarsa
                    @else
                        {{ ucfirst($transaksi->status) }}
                    @endif
                </span>
            </div>
        </div>

        @if ($transaksi->status === 'diterima' || $transaksi->status === 'lunas')
            <!-- SUCCESS STATE CARD -->
            <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-8 text-center mb-8">
                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xs">
                    <i class="ph ph-check-circle text-5xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800">Pembayaran Berhasil & Terverifikasi!</h2>
                <p class="text-sm text-slate-600 mt-2 max-w-lg mx-auto">
                    Transaksi pembayaran tagihan <strong>{{ $transaksi->pesanan }}</strong> telah diverifikasi otomatis oleh sistem Xendit Payment Gateway. Saldo koin telah ditambahkan ke akun perusahaan Anda.
                </p>

                <div class="inline-flex flex-wrap items-center justify-center gap-6 mt-6 py-3 px-6 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600">
                    <div>
                        <span class="text-slate-400 block font-medium">No. Referensi:</span>
                        <strong class="text-slate-800 font-bold text-sm">{{ $transaksi->no_referensi }}</strong>
                    </div>
                    <div class="border-l border-slate-200 pl-6">
                        <span class="text-slate-400 block font-medium">Total Dibayar:</span>
                        <strong class="text-emerald-600 font-extrabold text-sm">Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
                    </div>
                    @if ($transaksi->paid_at)
                        <div class="border-l border-slate-200 pl-6">
                            <span class="text-slate-400 block font-medium">Waktu Lunas:</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $transaksi->paid_at->translatedFormat('d F Y H:i') }} WIB</strong>
                        </div>
                    @endif
                </div>

                <div class="mt-8 flex justify-center gap-3">
                    <a href="{{ route('perusahaan.dashboard') }}"
                       class="px-6 py-3 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow transition">
                        Kembali ke Dashboard Perusahaan
                    </a>
                </div>
            </div>
        @else
            <!-- PENDING / IN-PROGRESS PAYMENT CARD -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Kolom Kiri: Rincian Tagihan & Informasi Transaksi -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <i class="ph ph-receipt text-base text-[#00509d]"></i> Rincian Tagihan
                        </h3>

                        <div class="space-y-4 text-xs">
                            <div>
                                <p class="text-slate-400 font-medium">No. Referensi / Transaksi</p>
                                <div class="flex items-center justify-between mt-1 p-2 bg-slate-50 rounded-lg border border-slate-100">
                                    <span class="font-mono font-bold text-slate-800 text-sm">{{ $transaksi->no_referensi }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $transaksi->no_referensi }}'); alert('No. Referensi berhasil disalin!');"
                                            class="text-[#00509d] hover:underline text-[11px] font-semibold flex items-center gap-1">
                                        <i class="ph ph-copy"></i> Salin
                                    </button>
                                </div>
                            </div>

                            <div>
                                <p class="text-slate-400 font-medium">Pesanan</p>
                                <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $transaksi->pesanan }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400 font-medium">Waktu Transaksi</p>
                                <p class="font-semibold text-slate-700 mt-0.5">{{ $transaksi->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                            </div>

                            @if ($transaksi->status === 'pending' && $transaksi->expired_at)
                                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                    <p class="text-amber-800 font-medium text-[11px]">Batas Waktu Pembayaran:</p>
                                    <p id="countdown" class="text-amber-600 font-extrabold text-sm mt-0.5"></p>
                                </div>
                            @endif

                            <div class="pt-3 border-t border-slate-100">
                                <div class="flex justify-between items-center text-slate-600 mb-1">
                                    <span>Subtotal</span>
                                    <span>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-600 mb-2">
                                    <span>Biaya Layanan Gateway</span>
                                    <span class="text-emerald-600 font-semibold">Gratis</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-900 font-extrabold text-base pt-2 border-t border-dashed border-slate-200">
                                    <span>Total Tagihan</span>
                                    <span class="text-[#00509d]">Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Tampilan Pembayaran Dalam Aplikasi (Embedded Payment Gateway) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                        <!-- Header Card Pembayaran -->
                        <div class="bg-gradient-to-r from-[#003d7a] to-[#00509d] text-white px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white text-xl">
                                    <i class="ph ph-credit-card"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white">Portal Pembayaran AreaKerja</h3>
                                    <p class="text-[11px] text-blue-100">QRIS, Virtual Account (BCA, BRI, BNI, Mandiri), E-Wallet, & Kartu</p>
                                </div>
                            </div>

                            @if ($transaksi->checkout_url)
                                <a href="{{ $transaksi->checkout_url }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/15 hover:bg-white/25 text-white text-xs font-bold rounded-lg transition self-start sm:self-auto"
                                   title="Buka tampilan pembayaran di tab baru">
                                    <i class="ph ph-arrow-square-out text-sm"></i>
                                    <span>Buka Tab Baru</span>
                                </a>
                            @endif
                        </div>

                        <!-- Embedded Payment Window / Iframe -->
                        @if ($transaksi->checkout_url)
                            <div class="relative w-full flex-1 bg-slate-100" style="min-height: 680px;">
                                <div id="iframe-loader" class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10">
                                    <div class="w-10 h-10 border-4 border-blue-200 border-t-[#00509d] rounded-full animate-spin mb-3"></div>
                                    <p class="text-xs font-semibold text-slate-600">Memuat antarmuka pembayaran aman Xendit...</p>
                                    <p class="text-[10px] text-slate-400 mt-1">Harap tunggu beberapa detik</p>
                                </div>

                                <iframe id="xendit-payment-frame"
                                        src="{{ $transaksi->checkout_url }}"
                                        class="w-full h-full border-0"
                                        style="min-height: 680px;"
                                        allow="payment"
                                        onload="document.getElementById('iframe-loader').style.display='none';">
                                </iframe>
                            </div>
                        @else
                            <div class="p-12 text-center my-auto">
                                <div class="w-16 h-16 rounded-full bg-blue-50 text-[#00509d] flex items-center justify-center mx-auto mb-4">
                                    <i class="ph ph-credit-card text-3xl"></i>
                                </div>
                                <h4 class="text-base font-bold text-slate-800">Menyiapkan Invoice Pembayaran</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto mb-6">
                                    Klik tombol di bawah ini untuk menginisiasi invoice pembayaran aman Xendit.
                                </p>
                                <a href="{{ route('catatan_cash.pay_now', $transaksi->id) }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold rounded-xl shadow transition">
                                    <i class="ph ph-lightning"></i> Buat Halaman Pembayaran
                                </a>
                            </div>
                        @endif

                        <!-- Footer Card: Polling Realtime Status -->
                        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2 text-slate-500 text-[11px]">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Menunggu pelunasan pembayaran secara real-time...</span>
                            </div>
                            <button type="button" onclick="checkPaymentStatus(true);"
                                    class="text-[#00509d] hover:text-[#003d7a] font-bold text-xs inline-flex items-center gap-1">
                                <i class="ph ph-arrows-clockwise"></i> Cek Status Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Script Polling Realtime & Countdown -->
    <script>
        // Frame-busting: Jika halaman ini dimuat di dalam iframe (misal redirect success Xendit), arahkan window utama
        if (window.top !== window.self) {
            window.top.location.href = window.location.href;
        }
        // 1. Countdown Batas Waktu Pembayaran
        @if ($transaksi->status === 'pending' && $transaksi->expired_at)
            const expireTime = new Date("{{ $transaksi->expired_at }}").getTime();
            const countdownEl = document.getElementById("countdown");

            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = expireTime - now;

                if (distance <= 0) {
                    clearInterval(timer);
                    if (countdownEl) {
                        countdownEl.innerHTML = "Waktu Pembayaran Habis (Expired)";
                        countdownEl.classList.remove("text-amber-600");
                        countdownEl.classList.add("text-rose-600");
                    }
                    setTimeout(() => location.reload(), 2000);
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % 1000) / 1000);

                if (countdownEl) {
                    countdownEl.innerHTML = `${hours} Jam ${minutes} Menit ${seconds} Detik`;
                }
            }, 1000);
        @endif

        // 2. Real-time Status Polling (Otomatis deteksi bila user sudah membayar)
        @if ($transaksi->status === 'pending')
            function checkPaymentStatus(isManual = false) {
                fetch("{{ route('catatan_cash.status', $transaksi->id) }}")
                    .then(res => res.json())
                    .then(data => {
                        if (data.is_paid) {
                            clearInterval(pollingInterval);
                            if (window.Swal) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Diterima!',
                                    text: 'Pembayaran Anda berhasil diverifikasi otomatis oleh sistem.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                alert('Pembayaran berhasil diverifikasi!');
                                window.location.reload();
                            }
                        } else if (data.is_expired) {
                            clearInterval(pollingInterval);
                            window.location.reload();
                        } else if (isManual) {
                            alert('Status saat ini: Belum ada pembayaran terdeteksi. Silakan selesaikan pembayaran di jendela pembayaran.');
                        }
                    })
                    .catch(err => {
                        if (isManual) console.error("Error cek status:", err);
                    });
            }

            // Polling otomatis setiap 4 detik
            const pollingInterval = setInterval(checkPaymentStatus, 4000);
        @endif
    </script>

    @include('layouts.footer')
@endsection
