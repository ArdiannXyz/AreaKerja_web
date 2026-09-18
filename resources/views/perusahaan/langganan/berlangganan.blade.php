@extends('layouts.index-perusahaan')
@section('content')
    <div class="w-full bg-slate-50/50 min-h-screen pb-20">

        <!-- ================= HERO SECTION ================= -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
            <div class="bg-white rounded-3xl p-6 sm:p-10 lg:p-12 border border-slate-200/80 shadow-xs flex flex-col-reverse lg:flex-row items-center justify-between gap-8 sm:gap-12 relative overflow-hidden">
                <div class="absolute -top-24 -left-24 w-72 h-72 bg-blue-50 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Text Content -->
                <div class="max-w-xl space-y-5 relative z-10">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-[#00509d] text-xs font-black tracking-wide uppercase">
                        <i class="ph-fill ph-crown text-amber-500 text-sm"></i>
                        <span>Paket Langganan Eksklusif</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        Berlangganan Bersama Kami, <br>
                        <span class="text-[#00509d]">Menjadi Yang Terdepan.</span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed">
                        Dapatkan akses prioritas tanpa batas ke ribuan talenta terbaik, event rekrutmen eksklusif, serta konsultasi rekrutmen intensif dari tim ahli AreaKerja.
                    </p>

                    <div class="pt-2">
                        @if ($perusahaan->is_berlangganan && \Carbon\Carbon::now()->lt($perusahaan->tanggal_expired))
                            <div class="inline-flex items-center gap-3 px-5 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-2xs">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-base shrink-0">
                                    <i class="ph-fill ph-check-circle"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-700">Status Langganan Aktif</p>
                                    <p class="text-xs sm:text-sm font-bold text-slate-700">
                                        Berlaku hingga {{ \Carbon\Carbon::parse($perusahaan->tanggal_expired)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <button onclick="openModal()"
                                class="inline-flex items-center gap-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white px-8 py-3.5 rounded-2xl shadow-sm hover:shadow-md transition text-sm font-extrabold transform active:scale-98">
                                <i class="ph-bold ph-lightning text-amber-400 text-base"></i>
                                <span>Mulai Berlangganan</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Hero Illustration -->
                <div class="w-full lg:w-1/2 flex justify-center relative z-10">
                    <img src="{{ asset('images/brolbaru.png') }}"
                        alt="Ilustrasi Berlangganan" 
                        class="w-full max-w-[420px] sm:max-w-[480px] h-auto object-contain drop-shadow-sm">
                </div>
            </div>
        </div>

        <!-- ================= BENEFIT SECTION ================= -->
        <div class="w-full py-16 px-4 sm:px-6 lg:px-8 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #00509d 0%, #003366 100%);">
            
            <div class="max-w-6xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
                    <span class="px-3 py-1 rounded-full bg-white/10 text-blue-200 text-xs font-extrabold uppercase tracking-wider">
                        Keuntungan Mitra
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Benefit Berlangganan Di AreaKerja
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100 font-medium">
                        Solusi terpadu untuk percepatan dan efisiensi proses *hiring* perusahaan Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Benefit 1 -->
                    <div class="bg-white/10 backdrop-blur-xs border border-white/15 rounded-3xl p-6 text-center flex flex-col items-center hover:bg-white/15 transition group">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition shadow-inner">
                            <i class="ph-fill ph-calendar-star text-amber-400"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-white mb-2">Event Eksklusif</h3>
                        <p class="text-xs text-blue-100 font-normal leading-relaxed">
                            Diundang khusus ke dalam bursa kerja dan webinar rekrutmen yang diadakan oleh AreaKerja.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="bg-white/10 backdrop-blur-xs border border-white/15 rounded-3xl p-6 text-center flex flex-col items-center hover:bg-white/15 transition group">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition shadow-inner">
                            <i class="ph-fill ph-chats-circle text-sky-300"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-white mb-2">Konsultasi Lifetime</h3>
                        <p class="text-xs text-blue-100 font-normal leading-relaxed">
                            Dukungan konsultasi berkelanjutan dari spesialis HR dalam menyaring pekerja yang tepat.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="bg-white/10 backdrop-blur-xs border border-white/15 rounded-3xl p-6 text-center flex flex-col items-center hover:bg-white/15 transition group">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition shadow-inner">
                            <i class="ph-fill ph-megaphone-simple text-emerald-300"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-white mb-2">Prioritas Listing</h3>
                        <p class="text-xs text-blue-100 font-normal leading-relaxed">
                            Lowongan pekerjaan perusahaan akan diposisikan di daftar teratas untuk menarik pelamar aktif.
                        </p>
                    </div>

                    <!-- Benefit 4 -->
                    <div class="bg-white/10 backdrop-blur-xs border border-white/15 rounded-3xl p-6 text-center flex flex-col items-center hover:bg-white/15 transition group">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition shadow-inner">
                            <i class="ph-fill ph-users-three text-rose-300"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-white mb-2">Database Terverifikasi</h3>
                        <p class="text-xs text-blue-100 font-normal leading-relaxed">
                            Akses cepat ke profil dan portofolio kandidat siap kerja yang telah divalidasi tim.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SUBSCRIPTION PRICING CARD ================= -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="bg-white rounded-3xl border-2 border-[#00509d] p-8 sm:p-12 shadow-sm flex flex-col lg:flex-row justify-between items-center gap-10">
                <div class="max-w-xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-blue-50 text-[#00509d] text-xs font-extrabold">
                        <i class="ph-fill ph-sparkle"></i>
                        <span>Akses Lengkap 1 Tahun Penuh</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Paket Langganan Tahunan
                    </h2>

                    <p class="text-sm text-slate-600 font-medium leading-relaxed">
                        Investasi cerdas untuk seluruh kebutuhan rekrutmen perusahaan. Nikmati seluruh fitur premium tanpa biaya tambahan tersembunyi.
                    </p>

                    <div class="pt-2 flex items-center gap-3">
                        <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 px-4 py-2 rounded-2xl">
                            <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-6 h-6 object-contain">
                            <span class="text-2xl font-black text-[#00509d]">{{ number_format($hargaLangganan, 0, ',', '.') }}</span>
                            <span class="text-xs font-extrabold text-amber-700">Koin / Tahun</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        @if ($perusahaan->is_berlangganan && \Carbon\Carbon::now()->lt($perusahaan->tanggal_expired))
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-xl">
                                <i class="ph-fill ph-check text-base"></i>
                                <span>Paket Anda sedang aktif</span>
                            </div>
                        @else
                            <button onclick="openModal()"
                                class="inline-flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white px-8 py-3.5 rounded-2xl shadow-sm text-sm font-extrabold transition transform active:scale-98">
                                <span>Langganan Sekarang</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="w-full lg:w-1/2 flex justify-center">
                    <img src="{{ asset('images/jempol.png') }}"
                        alt="Ilustrasi Sukses" 
                        class="w-full max-w-[320px] sm:max-w-[380px] h-auto object-contain">
                </div>
            </div>
        </div>

    </div>

    <!-- ================= MODAL 1: CHECKOUT PEMBAYARAN ================= -->
    <div id="modalBayar" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-7 relative border border-slate-100 transform transition-all">
            <!-- Close Button -->
            <button onclick="closeModal()"
                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition focus:outline-none">
                <i class="ph ph-x font-bold text-base"></i>
            </button>

            <!-- Header -->
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-xl font-bold shrink-0">
                    <i class="ph-fill ph-crown"></i>
                </div>
                <div>
                    <h2 class="text-base font-black text-slate-900">Konfirmasi Langganan</h2>
                    <p class="text-xs text-slate-500 font-medium">Pembayaran paket tahunan AreaKerja</p>
                </div>
            </div>

            <!-- Price Card -->
            <div class="bg-blue-50/60 border border-blue-200/80 rounded-2xl p-4 mb-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tagihan Tahunan</span>
                    <span class="px-2 py-0.5 bg-blue-100 text-[#00509d] text-[10px] font-black rounded-md">12 Bulan</span>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-7 h-7 object-contain">
                    <span class="text-2xl font-black text-[#00509d]">{{ number_format($hargaLangganan, 0, ',', '.') }}</span>
                    <span class="text-xs font-bold text-slate-600">Koin</span>
                </div>
            </div>

            <!-- Current Coin Balance -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500">Saldo Koin Perusahaan</p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-5 h-5 object-contain">
                            <span class="text-base font-black text-slate-900">{{ number_format($perusahaan->koin_perusahaan, 0, ',', '.') }} Koin</span>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal(); toggleModal();"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-extrabold rounded-xl transition shadow-2xs">
                        <i class="ph-bold ph-plus-circle text-sm"></i>
                        <span>Top Up Koin</span>
                    </button>
                </div>
            </div>

            <!-- Action Button -->
            <div class="space-y-2">
                <button id="btnBayar" type="button"
                    class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold py-3 rounded-2xl shadow-xs hover:shadow-md transition text-sm flex items-center justify-center gap-2">
                    <i class="ph-bold ph-check"></i>
                    <span>Bayar Sekarang ({{ $hargaLangganan }} Koin)</span>
                </button>
                <button type="button" onclick="closeModal()"
                    class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-2xl transition text-xs">
                    Batalkan
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL 2: PEMBAYARAN SUKSES ================= -->
    <div id="modalSukses" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8 relative text-center border border-slate-100 transform transition-all">
            
            <div class="w-16 h-16 rounded-3xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-3xl mx-auto mb-4 shadow-inner">
                <i class="ph-fill ph-check-circle text-emerald-500"></i>
            </div>

            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-1">
                Pembayaran Sukses!
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 font-medium">
                Selamat, status langganan perusahaan Anda di <span class="font-bold text-[#00509d]">areakerja.com</span> telah aktif selama 1 tahun ke depan.
            </p>

            <!-- Email Notification Box -->
            <div class="mt-5 bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between text-left">
                <div>
                    <p class="text-xs font-extrabold text-slate-700">Kirim invoice & bukti ke email</p>
                    <p class="text-[11px] font-semibold text-slate-500 truncate max-w-[220px]">{{ Auth::user()->email }}</p>
                </div>

                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="sendEmailToggle" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00509d]"></div>
                </label>
            </div>

            <button onclick="closeSukses()"
                class="mt-6 w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold py-3 rounded-2xl shadow-xs transition text-sm">
                Selesai
            </button>
        </div>
    </div>

    <!-- ================= MODAL 3: PERMINTAAN PANGGILAN / ONBOARDING ================= -->
    <div id="modalPanggilan" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8 text-center border border-slate-100 transform transition-all">
            <div class="w-16 h-16 rounded-3xl bg-blue-50 text-[#00509d] border border-blue-100 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="ph-fill ph-headset"></i>
            </div>

            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-1">
                Layanan Prioritas Aktif
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 font-medium">
                Permintaan konsultasi rekrutmen Anda telah dicatat. Tim account executive kami siap mendampingi Anda dalam 1x24 jam kerja.
            </p>

            <button onclick="closePanggilan()"
                class="mt-6 w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold py-3 rounded-2xl shadow-xs transition text-sm">
                Mengerti & Lanjutkan
            </button>
        </div>
    </div>

    <!-- ================= MODAL 4: ERROR KOIN KURANG ================= -->
    <div id="modalErrorKoin" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 sm:p-7 text-center border border-slate-100 transform transition-all">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="ph-fill ph-warning-circle"></i>
            </div>

            <h2 class="text-lg font-black text-slate-900 mb-1">Saldo Koin Tidak Cukup</h2>
            <p class="text-xs text-slate-600 font-medium mb-6">
                Saldo koin perusahaan Anda tidak mencukupi untuk berlangganan (Dibutuhkan {{ $hargaLangganan }} Koin).
            </p>

            <div class="space-y-2">
                <button type="button" onclick="closeErrorKoin(); toggleModal();"
                    class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold py-2.5 rounded-2xl transition text-xs shadow-xs">
                    Top Up Koin Sekarang
                </button>
                <button type="button" onclick="closeErrorKoin()"
                    class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-2xl transition text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= JAVASCRIPT LOGIC ================= -->
    <script>
        function openModal() {
            const m = document.getElementById('modalBayar');
            if (m) m.style.display = 'flex';
        }

        function closeModal() {
            const m = document.getElementById('modalBayar');
            if (m) m.style.display = 'none';
        }

        function showErrorKoin() {
            const m = document.getElementById('modalErrorKoin');
            if (m) m.style.display = 'flex';
        }

        function closeErrorKoin() {
            const m = document.getElementById('modalErrorKoin');
            if (m) m.style.display = 'none';
        }

        function openSukses() {
            const m = document.getElementById('modalSukses');
            if (m) m.style.display = 'flex';
        }

        function closeSukses() {
            const m = document.getElementById('modalSukses');
            if (m) m.style.display = 'none';
            const p = document.getElementById('modalPanggilan');
            if (p) p.style.display = 'flex';
        }

        function closePanggilan() {
            const p = document.getElementById('modalPanggilan');
            if (p) p.style.display = 'none';
            window.location.reload();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const btnBayar = document.getElementById('btnBayar');
            if (btnBayar) {
                btnBayar.addEventListener('click', function() {
                    btnBayar.disabled = true;
                    btnBayar.innerHTML = '<i class="ph ph-spinner animate-spin text-lg"></i> Memproses...';

                    fetch('{{ route('berlangganan.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(res => res.json())
                    .then(data => {
                        btnBayar.disabled = false;
                        btnBayar.innerHTML = '<i class="ph-bold ph-check"></i> <span>Bayar Sekarang ({{ $hargaLangganan }} Koin)</span>';

                        if (!data.success && data.type === 'verification') {
                            closeModal();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Akun Belum Terverifikasi',
                                text: data.message,
                                confirmButtonColor: '#00509d',
                                confirmButtonText: 'Mengerti'
                            });
                            return;
                        }

                        if (data.error === 'koin_kurang') {
                            closeModal();
                            showErrorKoin();
                            return;
                        }

                        if (data.success) {
                            closeModal();
                            openSukses();

                            const sendEmail = document.getElementById('sendEmailToggle');
                            if (sendEmail && sendEmail.checked) {
                                fetch('{{ route('send.email') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({})
                                });
                            }
                        } else {
                            showErrorKoin();
                        }
                    })
                    .catch(err => {
                        btnBayar.disabled = false;
                        btnBayar.innerHTML = '<i class="ph-bold ph-check"></i> <span>Bayar Sekarang ({{ $hargaLangganan }} Koin)</span>';
                        console.error(err);
                        showErrorKoin();
                    });
                });
            }
        });
    </script>

    @include('layouts.footer')
@endsection

