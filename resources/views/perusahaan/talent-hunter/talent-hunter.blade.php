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
                    <span class="bg-orange-500 hover:bg-orange-600 text-sm px-8 py-2 rounded-lg">Daftar</span>
                </button>
            </div>
        </section>
    </div>
    <section class="w-full text-white py-20" style="background: linear-gradient(to right, orange, #ff7b00)">
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
                    class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 transition">
                    <div class="bg-white rounded-2xl shadow-xl p-8 w-[420px] scale-[0.85] sm:scale-100 animate-[fadeIn_0.2s_ease-out]">

                        <!-- Icon -->
                        <div
                            class="w-16 h-16 bg-green-100 text-green-600 flex items-center justify-center rounded-full mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M12 1.5A10.5 10.5 0 1022.5 12 10.513 10.513 0 0012 1.5zm3.712 9.03l-4.2 4.2a.75.75 0 01-1.06 0l-2.1-2.1a.75.75 0 111.06-1.06l1.57 1.57 3.67-3.67a.75.75 0 111.06 1.06z" />
                            </svg>
                        </div>

                        <h2 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Pembelian</h2>
                        <p class="text-gray-600 text-center mb-6">Harga paket Talent Hunter:</p>

                        <div class="text-3xl font-bold text-orange-500 text-center mb-6">
                            <span id="hargaTH"></span> <span class="text-lg text-gray-500">koin</span>
                        </div>

                        <div class="flex gap-3">
                            <button id="btnConfirmBeli"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold transition">
                                Beli Sekarang
                            </button>
                            <button onclick="closeModal('modalBeli')"
                                class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 py-2.5 rounded-lg font-semibold transition">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Modal Form Talent Hunter -->
                <div id="modalFormTH"
                    class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 px-4">
       <div class="bg-white rounded-2xl shadow-xl p-8 
            w-[500px] max-w-full
            scale-[0.85] md:scale-100
            max-h-[90vh] overflow-y-auto
            animate-fadeIn relative">




                        <!-- Close -->
                        <button onclick="document.getElementById('modalFormTH').classList.add('hidden')"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 transition text-xl">
                            ✕
                        </button>

                        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Form Talent Hunter</h2>

                        <form id="formTalentHunter" class="space-y-4">

                            <!-- Alamat -->
                            <div>
                                <label class="text-sm font-medium text-gray-600">Alamat</label>
                                <input type="text" name="alamat"
                                    class="w-full border rounded-lg px-3 py-2 text-gray-800 bg-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Masukkan alamat">
                            </div>

                            <!-- Posisi -->
                            <div>
                                <label class="text-sm font-medium text-gray-600">Posisi</label>
                                <input type="text" name="posisi"
                                    class="w-full border rounded-lg px-3 py-2 text-gray-800 bg-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Contoh: HR, Recruiter">
                            </div>

                            <!-- Pengalaman -->
                            <div>
                                <label class="text-sm font-medium text-gray-600">Pengalaman Kerja</label>
                                <input type="text" name="pengalaman_kerja"
                                    class="w-full border rounded-lg px-3 py-2 text-gray-800 bg-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Contoh: 2 tahun">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="text-sm font-medium text-gray-600">Gender</label>
                                <select name="gender"
                                    class="w-full border rounded-lg px-3 py-2 text-gray-800 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="">Pilih Gender</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <!-- Gaji -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Gaji Awal</label>
                                    <input type="text" id="gaji_awal_display"
                                        class="w-full border rounded-lg px-3 py-2 text-gray-800 bg-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Rp 4.000.000">
                                    <input type="hidden" name="gaji_awal" id="gaji_awal">
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-600">Gaji Akhir</label>
                                    <input type="text" id="gaji_akhir_display"
                                        class="w-full border rounded-lg px-3 py-2 text-gray-800 bg-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="Rp 8.000.000">
                                    <input type="hidden" name="gaji_akhir" id="gaji_akhir">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="text-sm font-medium text-gray-600">Deskripsi</label>
                                <textarea name="deskripsi"
                                    class="w-full border rounded-lg px-3 py-2 h-24 text-gray-800 bg-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Tuliskan deskripsi..."></textarea>
                            </div>

                            <!-- Button -->
                            <button type="submit"
                                class="w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-400 transition font-semibold shadow">
                                Simpan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Modal Koin Tidak Cukup -->
                <div id="modalKoinKurang" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 px-4 w-full max-w-[400px] scale-[0.85] sm:scale-100 transform-gpu relative text-center">
                        <button onclick="closeModal('modalKoinKurang')"
                            class="absolute top-3 right-3 text-gray-500 text-2xl">&times;</button>
                        <h2 class="text-lg font-bold text-red-600 mb-3">Koin Tidak Cukup</h2>
                        <p class="text-gray-600 mb-4">Saldo koin perusahaan kamu tidak mencukupi untuk membeli Talent
                            Hunter.</p>
                        <!-- Koin Saya -->
                        <div class="mb-6">
                            <p class="text-gray-700 text-sm mb-1">Koin saya :</p>
                            <div
                                class="flex items-center justify-between bg-orange-50 border border-orange-200 rounded-lg px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <img src="/images/coin.png" alt="coin" class="w-6 h-6">
                                    <p class="font-bold text-orange-500 text-lg">
                                        {{ number_format($perusahaan->koin_perusahaan, 0, ',', '.') }}</p>
                                </div>
                                <button onclick="toggleModal()"
                                    class="flex items-center text-green-600 text-xs font-semibold hover:underline">
                                    <span class="mr-1">Top Up Koin</span>
                                    <svg width="18" height="18" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.773" y="0.968" width="20" height="20" fill="#42BB72" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- ================= MODAL STEP 1 ================= -->
    @include('perusahaan.modal-topup.step1')
    <!-- ================= MODAL STEP 2 ================= -->
    @include('perusahaan.modal-topup.step2')
    <!-- ================= MODAL STEP 3 ================= -->
    @include('perusahaan.modal-topup.step3')

    <!-- Benefit Talent Hunter -->
    <section class="bg-white py-12">
        <div class="text-center py-10">
            <!-- Judul -->
            <h2 class="text-2xl font-bold text-orange-600">Benefit Talent Hunter</h2>
            <div class="w-20 h-1 bg-orange-500 mx-auto my-2"></div>

            <!-- Atas: 2 item -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10 max-w-3xl mx-auto">
                <!-- Kandidat -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/jam.png') }}" class="w-12 h-12 mb-3" />
                    <h3 class="font-bold text-orange-500">Kandidat</h3>
                    <p class="text-sm text-orange-500">Mendapatkan kandidat sesuai kebutuhan perusahaan dan posisi yang
                        ditujukan.</p>
                </div>

                <!-- Siap Kerja -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/komputer.jpg') }}" class="w-12 h-12 mb-2" />
                    <h3 class="font-bold text-orange-500">Siap Kerja</h3>
                    <p class="text-sm text-orange-500">Kandidat yang didapatkan dipastikan siap kerja dengan perusahaan
                        yang
                        direkomendasikan.</p>
                </div>
            </div>

            <!-- Bawah: 2 item -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10 max-w-3xl mx-auto">
                <!-- Memudahkan -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/roket.png') }}" class="w-12 h-12 mb-3" />
                    <h3 class="font-bold text-orange-500">Memudahkan</h3>
                    <p class="text-sm text-orange-500">Mempermudah perusahaan dalam penyaringan kandidat.</p>
                </div>

                <!-- Jaminan -->
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/roket.png') }}" class="w-12 h-12 mb-3" />
                    <h3 class="font-bold text-orange-500">Jaminan</h3>
                    <p class="text-sm text-orange-500">Jaminan ganti kandidat baru jika tidak cocok dengan spesifikasi
                        perusahaan.</p>
                </div>
            </div>
            {{-- @include('perusahaan.modal-talent') --}}
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

        // 1️⃣ Klik Daftar Talent Hunter → buka form
        document.getElementById('btnDaftarTH').addEventListener('click', function() {
            openModal('modalFormTH');
        });

        // 2️⃣ Submit Form TH → JANGAN SIMPAN KE DB
        document.getElementById('formTalentHunter').addEventListener('submit', function(e) {
            e.preventDefault();

            formDataTH = new FormData(this); // simpan data sementara

            // ambil harga untuk ditampilkan
            fetch('{{ route('talent-hunter.harga') }}')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('hargaTH').innerText = data.harga;

                    closeModal('modalFormTH');
                    openModal('modalBeli');
                });
        });

        // 3️⃣ Klik KONFIRMASI BELI → BARU PROSES DB
        document.getElementById('btnConfirmBeli').addEventListener('click', async function() {

            if (!formDataTH) {
                alert("Form belum diisi!");
                return;
            }

            formDataTH.append('_token', '{{ csrf_token() }}');

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
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: result.message
                });
            }
        });

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
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
            // ✅ Validasi sebelum pindah step
            if (step === 2 && !selectedKoin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih paket koin terlebih dahulu!',
                    confirmButtonColor: '#f97316' // warna tombol orange
                });
                return;
            }
            if (step === 3 && !selectedBank) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih metode pembayaran terlebih dahulu!',
                    confirmButtonColor: '#f97316'
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

                // 🔑 Buat No Transaksi random unik
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


        // 🔑 Update status tombol (disable/enable)
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
                        w.classList.remove('ring-2', 'ring-orange-500');
                    });
                    this.closest('.paketCoinWrapper').classList.add('ring-2', 'ring-orange-500');

                    updateButtons();
                });
            });

            // Step 2: Pilih Metode Pembayaran
            document.querySelectorAll('.metodePembayaran').forEach(el => {
                el.addEventListener('change', function() {
                    selectedBank = this.dataset.bank;

                    // Highlight bank terpilih
                    document.querySelectorAll('.pembayaranWrapper').forEach(w => {
                        w.classList.remove('ring-2', 'ring-orange-500');
                    });
                    this.closest('.pembayaranWrapper').classList.add('ring-2', 'ring-orange-500');

                    updateButtons();
                });
            });
        });
    </script>
    @include('layouts.footer')
@endsection
