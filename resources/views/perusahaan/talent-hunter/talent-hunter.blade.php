@extends('layouts.index-perusahaan')
@section('content')
    <!-- Hero Section -->
    <div class="mt-16">
        <section class="relative">
            <img src="{{ asset('images/woi.jpg') }}"
                alt="Header Image" class="w-full h-[600px] object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="absolute bottom-52 left-0 pl-6 text-left
            md:pl-0 md:left-20 text-white">
                <h1 class="text-3xl md:text-4xl font-semibold mt-3 max-w-2xl">
                    Talent Hunter
                </h1>
                <p class="text-sm mt-4">Daftarkan perusahaan anda dan biar kami</p>
                <p class="text-sm"> yang mencarikan kandidat yang cocok untuk anda</p><br>
                <button id="btnDaftarTH">
                    <span class="bg-[#00509d] hover:bg-[#003d7a] text-sm px-8 py-2 rounded-lg">Daftar</span>
                </button>
            </div>
        </section>
    </div>
    <section class="w-full text-white py-20 bg-[#00509d]">
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8 items-center px-6">

            <div class="flex justify-center">
                <img src="{{ asset('images/ntip.png') }}" alt="Talent Hunter" class="h-96 w-96 ">
            </div>

            <div>

                <div>
                    <h2 class="text-2xl font-semibold mb-6 leading-snug">
                        Langkah - Langkah Daftar <br> Talent Hunter
                    </h2>
                    <div class="relative flex max-w-xl">
                        <!-- Garis vertikal -->
                        <div class="flex flex-col items-center mr-6 mt-4">
                            <svg width="16" height="280" viewBox="0 0 16 310" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 8V302.001" stroke="white" stroke-width="3" stroke-linecap="round" />
                                <circle cx="8" cy="8" r="8" fill="white" />
                                <circle cx="8" cy="106" r="8" fill="white" />
                                <circle cx="8" cy="204" r="8" fill="white" />
                                <circle cx="8" cy="302" r="8" fill="white" />
                            </svg>
                        </div>

                        <!-- Konten step -->
                        <div class="flex flex-col">
                            <!-- Step 1 -->
                            <div class="mb-8">
                                <p class="text-lg leading-relaxed">Klik tombol daftar untuk mendaftarkan perusahaan anda</p>
                            </div>

                            <!-- Step 2 -->
                            <div class="mb-8">
                                <p class="text-lg leading-relaxed">Mengisi formulir pendaftaran dan kirim formulir
                                    pendaftaran
                                </p>
                            </div>

                            <!-- Step 3 -->
                            <div class="mb-8">
                                <p class="text-lg leading-relaxed">Tunggu pemberitahuan selanjutnya setelah pendaftaran</p>
                            </div>

                            <!-- Step 4 -->
                            <div>
                                <p class="text-lg leading-relaxed">Perusahaan berhasil didaftarkan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Konfirmasi Pembelian -->
                <div id="modalBeli"
                    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4 transition-all">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-md border border-slate-100 text-center animate-fadeIn relative">
                        <!-- Icon -->
                        <div class="w-16 h-16 bg-blue-50 text-[#00509d] border-2 border-blue-100 flex items-center justify-center rounded-2xl mx-auto mb-4">
                            <i class="ph ph-shopping-cart-simple text-3xl font-bold"></i>
                        </div>

                        <h2 class="text-xl font-extrabold text-slate-900 mb-1">Konfirmasi Pembelian</h2>
                        <p class="text-xs text-slate-500 mb-6">Biaya layanan Talent Hunter:</p>

                        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl py-4 px-6 mb-6">
                            <div class="text-3xl font-extrabold text-[#00509d]">
                                <span id="hargaTH">500</span> <span class="text-base font-bold text-slate-500">Koin</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button onclick="closeModal('modalBeli')"
                                class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition">
                                Batal
                            </button>
                            <button id="btnConfirmBeli"
                                class="w-1/2 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold rounded-xl text-sm shadow-sm transition">
                                Beli Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Form Talent Hunter -->
                <div id="modalFormTH"
                    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4 transition-all">
                    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh] border border-slate-100 animate-fadeIn relative">

                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 shrink-0">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-[#00509d] flex items-center justify-center shrink-0">
                                    <i class="ph ph-briefcase text-lg font-bold"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900">Form Talent Hunter</h2>
                                    <p class="text-[11px] text-slate-500">Lengkapi kriteria kandidat yang dicari perusahaan</p>
                                </div>
                            </div>
                            <button onclick="closeModal('modalFormTH')"
                                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
                                <i class="ph ph-x font-bold text-sm"></i>
                            </button>
                        </div>

                        <!-- Modal Body (Scrollable with nice spacing) -->
                        <div class="p-6 overflow-y-auto space-y-4">
                            <form id="formTalentHunter" class="space-y-4">

                                <!-- Alamat -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Alamat Penempatan / Kantor <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="alamat" required
                                        class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition"
                                        placeholder="Masukkan alamat atau kota penempatan">
                                </div>

                                <!-- Posisi -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Posisi yang Dibutuhkan <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="posisi" required
                                        class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition"
                                        placeholder="Contoh: HR Specialist, Fullstack Developer">
                                </div>

                                <!-- Pengalaman Kerja -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Pengalaman Kerja <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="pengalaman_kerja" required
                                        class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition"
                                        placeholder="Contoh: Minimal 1-2 tahun / Fresh Graduate">
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Kriteria Gender <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="gender" required
                                        class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition cursor-pointer">
                                        <option value="">Pilih Gender</option>
                                        <option value="Semua Gender">Semua Gender</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <!-- Gaji -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                            Gaji Awal (Min) <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="gaji_awal_display" required
                                            class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition"
                                            placeholder="Rp 4.000.000">
                                        <input type="hidden" name="gaji_awal" id="gaji_awal">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                            Gaji Akhir (Maks) <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="gaji_akhir_display" required
                                            class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition"
                                            placeholder="Rp 8.000.000">
                                        <input type="hidden" name="gaji_akhir" id="gaji_akhir">
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Deskripsi & Kualifikasi Tambahan
                                    </label>
                                    <textarea name="deskripsi" rows="3"
                                        class="w-full bg-slate-50/50 border border-slate-300 rounded-xl p-3.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition resize-none leading-relaxed"
                                        placeholder="Tuliskan skill khusus atau deskripsi pekerjaan yang diinginkan..."></textarea>
                                </div>

                                <!-- Button -->
                                <div class="pt-2">
                                    <button type="submit"
                                        class="w-full py-3 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold text-sm rounded-xl shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                                        <span>Lanjutkan Pembelian</span>
                                        <i class="ph ph-arrow-right font-bold text-base"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Koin Tidak Cukup -->
                <div id="modalKoinKurang" class="fixed inset-0 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs z-50 p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-md border border-slate-100 relative text-center animate-fadeIn">
                        <button onclick="closeModal('modalKoinKurang')"
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                            <i class="ph ph-x font-bold"></i>
                        </button>
                        <div class="w-14 h-14 bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="ph ph-warning-circle text-3xl font-bold"></i>
                        </div>
                        <h2 class="text-lg font-extrabold text-slate-900 mb-1.5">Koin Tidak Cukup</h2>
                        <p class="text-xs text-slate-500 mb-5 leading-relaxed">
                            Saldo koin perusahaan Anda saat ini belum mencukupi untuk menggunakan layanan Talent Hunter.
                        </p>
                        <!-- Koin Saya -->
                        <div class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="text-left">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase">Saldo Koin Anda</span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <img src="/images/coin.png" alt="coin" class="w-5 h-5">
                                        <p class="font-extrabold text-[#00509d] text-lg">
                                            {{ number_format($perusahaan->koin_perusahaan, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <button onclick="closeModal('modalKoinKurang'); toggleModal()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                                    <i class="ph ph-plus-circle font-bold"></i> Top Up Koin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Benefit Talent Hunter -->
    <section class="bg-white py-14 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Judul -->
            <h2 class="text-2xl font-bold text-[#003d7a]">Benefit Talent Hunter</h2>
            <div class="w-20 h-1 bg-[#00509d] mx-auto my-3 rounded-full"></div>

            <!-- Grid 4 item -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-12 mt-10">
                <!-- 1. Kandidat -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center mb-3 text-[#00509d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <circle cx="19" cy="11" r="2"></circle>
                            <path d="M19 8v1"></path>
                            <path d="M19 13v1"></path>
                            <path d="M16 11h1"></path>
                            <path d="M21 11h1"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#00509d] text-lg mb-1">Kandidat</h3>
                    <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                        Mendapatkan kandidat sesuai kebutuhan perusahaan dan posisi yang ditujukan.
                    </p>
                </div>

                <!-- 2. Siap Kerja -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center mb-3 text-[#00509d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                            <path d="m9 10 2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#00509d] text-lg mb-1">Siap Kerja</h3>
                    <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                        Kandidat yang didapatkan dipastikan siap kerja dengan perusahaan yang direkomendasikan.
                    </p>
                </div>

                <!-- 3. Memudahkan -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center mb-3 text-[#00509d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
                            <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
                            <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
                            <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#00509d] text-lg mb-1">Memudahkan</h3>
                    <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                        Mempermudah perusahaan dalam penyaringan dan seleksi kandidat.
                    </p>
                </div>

                <!-- 4. Perlindungan & Garansi -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center mb-3 text-[#00509d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-[#00509d] text-lg mb-1">Perlindungan Garansi</h3>
                    <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                        Jaminan penggantian kandidat baru jika tidak cocok dengan spesifikasi perusahaan.
                    </p>
                </div>
            </div>
        </div>
    </section>
    {{-- AUTO RUPIAH --}}
    <script>
        function formatRupiah(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function handleRupiahInput(displayId, realId) {
            const display = document.getElementById(displayId);
            const real = document.getElementById(realId);

            display.addEventListener("input", function() {
                let value = this.value.replace(/[^0-9]/g, "");
                real.value = value;
                this.value = value ? "Rp " + formatRupiah(value) : "";
            });
        }

        handleRupiahInput("gaji_awal_display", "gaji_awal");
        handleRupiahInput("gaji_akhir_display", "gaji_akhir");
    </script>




    <script>
        let formDataTH = null; // GLOBAL (dipakai submit & beli)

        // 1. Klik Daftar Talent Hunter -> buka form
        document.getElementById('btnDaftarTH')?.addEventListener('click', function() {
            openModal('modalFormTH');
        });

        // 2. Submit Form TH -> Validasi dan Tampilkan Konfirmasi Pembelian
        document.getElementById('formTalentHunter')?.addEventListener('submit', function(e) {
            e.preventDefault();

            formDataTH = new FormData(this); // simpan data sementara

            // ambil harga untuk ditampilkan
            fetch('{{ route('talent-hunter.harga') }}')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('hargaTH').innerText = data.harga;

                    closeModal('modalFormTH');
                    openModal('modalBeli');
                })
                .catch(() => {
                    closeModal('modalFormTH');
                    openModal('modalBeli');
                });
        });

        // 3. Klik KONFIRMASI BELI -> Simpan ke Database
        document.getElementById('btnConfirmBeli')?.addEventListener('click', async function() {
            if (!formDataTH) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Formulir belum diisi!'
                });
                return;
            }

            formDataTH.append('_token', '{{ csrf_token() }}');

            try {
                const res = await fetch('{{ route('talent-hunter.store') }}', {
                    method: 'POST',
                    body: formDataTH
                });

                const result = await res.json();

                if (result.success) {
                    closeModal('modalBeli');

                    Swal.fire({
                        icon: 'success',
                        title: 'Pembelian Berhasil!',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        window.location.href = result.redirect_url;
                    }, 1500);

                } else {
                    closeModal('modalBeli');

                    if (result.message && result.message.toLowerCase().includes('koin')) {
                        openModal('modalKoinKurang');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: result.message
                        });
                    }
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi kesalahan saat memproses data. Silakan coba lagi.'
                });
            }
        });

        function openModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            }
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }
    </script>


    {{-- TOP UP --}}
    <script>
        //redirect
        document.getElementById('btnKonfirmasi').addEventListener('click', function() {
            if (!selectedKoin || !selectedBank) {
                alert("Silakan pilih paket dan metode pembayaran dulu.");
                return;
            }

            fetch("{{ route('catatan_cash.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        harga_pembayaran_id: document.querySelector(".paketCoin:checked").value,
                        daftar_bank_id: document.querySelector(".metodePembayaran:checked").value,
                    })
                })
                .then(async res => {
                    if (!res.ok) {
                        let err = await res.text();
                        throw new Error(err);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(err => {
                    console.error("Error detail:", err.message);
                    alert("Gagal membuat transaksi: " + err.message);
                });
        });



        let selectedKoin = null;
        let selectedHarga = null;
        let selectedBank = null;

        function toggleModal() {
            closeAllModal();
            document.getElementById('modalStep1').classList.remove('hidden');
            document.getElementById('modalStep1').classList.add('flex');
            updateButtons();
        }

        function closeAllModal() {
            document.querySelectorAll('[id^="modalStep"]').forEach(m => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            });
        }

        function goToStep(step) {
            // âœ… Validasi sebelum pindah step
            if (step === 2 && !selectedKoin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih paket koin terlebih dahulu!',
                    confirmButtonColor: '#00509d' // warna tombol orange
                });
                return;
            }
            if (step === 3 && !selectedBank) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih metode pembayaran terlebih dahulu!',
                    confirmButtonColor: '#00509d'
                });
                return;
            }

            closeAllModal();
            let modal = document.getElementById('modalStep' + step);
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            updateButtons();

            // Step 3: update detail pembayaran
            if (step === 3) {
                const biayaAdmin = 2000;
                const totalBayar = (selectedHarga ?? 0) + biayaAdmin;

                // ðŸ”‘ Buat No Transaksi random unik
                const randomPart = Math.floor(Math.random() * 1000000);
                const noTransaksi = "TRX" + Date.now() + randomPart;

                document.getElementById('detailTransaksi').innerText = noTransaksi;
                document.getElementById('detailPengirim').innerText = "Nama User";
                document.getElementById('detailBank').innerText = selectedBank ?? '-';
                document.getElementById('detailWaktu').innerText = new Date().toLocaleString('id-ID');
                document.getElementById('detailHarga').innerText = "Rp. " + (selectedHarga ?? 0).toLocaleString('id-ID');
                document.getElementById('detailTotal').innerText = "Rp. " + totalBayar.toLocaleString('id-ID');
            }
        }


        // ðŸ”‘ Update status tombol (disable/enable)
        function updateButtons() {
            // Step 1: tombol konfirmasi paket
            const btnStep1 = document.querySelector('#modalStep1 button');
            if (btnStep1) {
                btnStep1.disabled = !selectedKoin;
                btnStep1.classList.toggle('opacity-50', !selectedKoin);
                btnStep1.classList.toggle('cursor-not-allowed', !selectedKoin);
            }

            // Step 2: tombol selanjutnya metode pembayaran
            const btnStep2 = document.querySelector('#modalStep2 button:last-child');
            if (btnStep2) {
                btnStep2.disabled = !selectedBank;
                btnStep2.classList.toggle('opacity-50', !selectedBank);
                btnStep2.classList.toggle('cursor-not-allowed', !selectedBank);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Step 1: Pilih Paket Koin
            document.querySelectorAll('.paketCoin').forEach(el => {
                el.addEventListener('change', function() {
                    selectedKoin = this.dataset.jumlah;
                    selectedHarga = parseInt(this.dataset.harga);

                    // Highlight kartu terpilih
                    document.querySelectorAll('.paketCoinWrapper').forEach(w => {
                        w.classList.remove('ring-2', 'ring-[#00509d]');
                    });
                    this.closest('.paketCoinWrapper').classList.add('ring-2', 'ring-[#00509d]');

                    updateButtons();
                });
            });

            // Step 2: Pilih Metode Pembayaran
            document.querySelectorAll('.metodePembayaran').forEach(el => {
                el.addEventListener('change', function() {
                    selectedBank = this.dataset.bank;

                    // Highlight bank terpilih
                    document.querySelectorAll('.pembayaranWrapper').forEach(w => {
                        w.classList.remove('ring-2', 'ring-[#00509d]');
                    });
                    this.closest('.pembayaranWrapper').classList.add('ring-2', 'ring-[#00509d]');

                    updateButtons();
                });
            });
        });
    </script>
    @include('layouts.footer')
@endsection

