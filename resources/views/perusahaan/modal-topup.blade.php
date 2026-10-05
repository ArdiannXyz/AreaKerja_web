 <!-- ================= MODAL STEP 1 ================= -->
    <div id="modalStep1" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl relative p-6 max-h-[80vh] overflow-y-auto">
            <button onclick="closeAllModal()" class="absolute top-3 right-3 text-gray-400 hover:text-black">âœ•</button>
            <h2 class="text-lg font-semibold mb-4">Top Up Koin</h2>
            <div class="grid grid-cols-3 gap-4">
                @foreach ($hargaPembayarans as $paket)
                    <label
                        class="paketCoinWrapper cursor-pointer border rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col items-center">

                        <!-- Input radio -->
                        <input type="radio" name="paket" value="{{ $paket->id }}"
                            data-jumlah="{{ $paket->jumlah_koin }}" data-harga="{{ $paket->harga }}"
                            class="hidden paketCoin">

                        <!-- Isi kartu -->
                        <div class="flex flex-col items-center flex-1 p-4">
                            <img src="{{ asset('icon/' . ($paket->icon ?? 'default-icon.png')) }}"
                                alt="{{ $paket->nama }}" class="w-14 h-14 mb-3">
                            <span class="text-lg font-bold text-gray-800">
                                {{ number_format($paket->jumlah_koin, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Bagian harga -->
                        <div class="w-full bg-[#00509d] text-white text-center py-2 font-semibold">
                            Rp. {{ number_format($paket->harga, 0, ',', '.') }}
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="flex justify-center mt-6">
                <button onclick="goToStep(2)" class="px-6 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white rounded-md">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL STEP 2 ================= -->
    <div id="modalStep2" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl relative p-6">
            <button onclick="closeAllModal()" class="absolute top-3 right-3 text-gray-400 hover:text-black">âœ•</button>

            <h2 class="text-lg font-semibold mb-4">Metode Pembayaran</h2>

            <!-- Dropdown Transfer Bank -->
            <details class="border rounded-xl overflow-hidden">
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer">
                    <span class="flex items-center gap-2 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#00509d]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a5 5 0 00-10 0v2H5v12h14V9h-2z" />
                        </svg>
                        Transfer Bank
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="divide-y">
                    @foreach ($daftarBank as $bank)
                        @if (strtolower($bank->nama_bank) !== 'qris')
                            <label
                                class="pembayaranWrapper flex justify-between items-center px-4 py-3 cursor-pointer hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($bank->logo ?? 'default-bank.png') }}" class="w-8 h-8">
                                    <span class="font-medium">{{ $bank->nama_bank }}</span>
                                </div>
                                <input type="radio" name="bank" value="{{ $bank->id }}"
                                    data-bank="{{ $bank->nama_bank }}" class="hidden peer metodePembayaran">
                                <span
                                    class="w-5 h-5 border-2 border-[#00509d] rounded-full flex items-center justify-center peer-checked:bg-[#00509d]">
                                    <span class="hidden peer-checked:block w-2.5 h-2.5 bg-white rounded-full"></span>
                                </span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </details>

            <!-- QRIS (pisah dari dropdown) -->
            @foreach ($daftarBank as $bank)
                @if (strtolower($bank->nama_bank) === 'qris')
                    <label
                        class="pembayaranWrapper mt-3 flex justify-between items-center px-4 py-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset($bank->logo ?? 'default-bank.png') }}" class="w-8 h-8">
                            <span class="font-medium">{{ $bank->nama_bank }}</span>
                        </div>
                        <input type="radio" name="bank" value="{{ $bank->id }}"
                            data-bank="{{ $bank->nama_bank }}" class="hidden peer metodePembayaran">
                        <span
                            class="w-5 h-5 border-2 border-[#00509d] rounded-full flex items-center justify-center peer-checked:bg-[#00509d]">
                            <span class="hidden peer-checked:block w-2.5 h-2.5 bg-white rounded-full"></span>
                        </span>
                    </label>
                @endif
            @endforeach


            <!-- Tombol navigasi -->
            <div class="flex justify-between mt-6">
                <button onclick="goToStep(1)" class="text-[#00509d]">Kembali</button>
                <button onclick="goToStep(3)" class="text-[#00509d] font-semibold">Selanjutnya</button>
            </div>
        </div>
    </div>



    <!-- ================= MODAL STEP 3 ================= -->
    <div id="modalStep3" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl relative p-8">
            <button onclick="closeAllModal()"
                class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl">âœ•</button>

            <h2 class="text-xl font-bold">Detail Pembayaran</h2>
            <div class="h-1 w-32 bg-[#00509d] mb-6"></div>

            <div class="border border-[#00509d] rounded-lg p-6 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span>No. Transaksi</span>
                    <span id="detailTransaksi">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Nama Pengirim</span>
                    <span id="detailPengirim">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Nama Penerima</span>
                    <span id="detailPenerima">Area Kerja</span>
                </div>
                <div class="flex justify-between">
                    <span>Metode Pembayaran</span>
                    <span class="bg-[#00509d] text-white text-xs font-medium px-3 py-1 rounded-full"
                        id="detailBank">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Tgl/Waktu</span>
                    <span id="detailWaktu">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Jumlah Deposit</span>
                    <span id="detailHarga">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Admin</span>
                    <span id="detailAdmin">Rp. 2.000</span>
                </div>
                <div class="border-t border-dashed my-3"></div>
                <div class="flex justify-between font-semibold">
                    <span>Total Pembayaran</span>
                    <span id="detailTotal">-</span>
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <button type="button" id="btnKonfirmasi"
                    class="w-full py-3 bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold rounded-full">
                    Konfirmasi
                </button>
            </div>

        </div>

    </div>
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
