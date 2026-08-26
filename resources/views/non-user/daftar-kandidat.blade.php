@extends('layouts.index')
@section('content')
    {{-- <div x-data="{ open: false, step: 1, selectedDivisi: [], selectedBank: null }" x-cloak> --}}
    <div>
        <!-- Hero Section -->
        <section class="relative">
            <img src="{{ asset('images/ntap.png') }}"
                alt="Header Image" class="w-full h-[400px] sm:h-[500px] md:h-[600px] object-cover">

            <div class="absolute inset-0 bg-black bg-opacity-30"></div>

             <div class="absolute left-5 sm:left-10 md:left-20 bottom-20 sm:bottom-32 md:bottom-52 text-white max-w-xs sm:max-w-md md:max-w-2xl">
                <h1 class="text-xl md:text-3xl lg:text-4xl font-semibold mt-3 max-w-xs md:max-w-2xl">
                    Daftar Kandidat
                </h1>
                <p class="text-sm mt-2 sm:mt-4">Ikuti pelatihan terakreditasi AreaKerja.com</p>
                <p class="text-sm">dan dapatkan pekerjaan impian anda!</p><br>
                <!-- Tombol Daftar -->
                <button onclick="goToStep(1)"
                    class="bg-orange-500 hover:bg-orange-600 text-sm sm:text-base px-4 sm:px-8 py-2 rounded-lg">
                    Daftar
                </button>
            </div>
        </section>


        <section class="text-white py-12" style="background: linear-gradient(to left, rgb(255, 196, 0), #ff7b00)">
            <div class="max-w-6xl mx-auto grid md:grid-cols-2 items-center gap-8 px-6">

                <!-- Left Content -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6">Benefit Menjadi Kandidat <br> Areakerja.com</h2>
                    <ul class="space-y-4 text-base">
                        <li class="flex items-center">
                            <span class="text-white mr-2">
                                <svg width="18" height="18" viewBox="0 0 23 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.094 2.28125L8.36467 15.7594L2 9.02032" stroke="white"
                                        stroke-width="3.36953" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <p class="ml-4">
                                Menjadi prioritas pilihan dari perusahaan <br> mitra Areakerja
                            </p>
                        </li>
                        <li class="flex items-center">
                            <span class="text-white mr-2">
                                <svg width="18" height="18" viewBox="0 0 23 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.094 2.28125L8.36467 15.7594L2 9.02032" stroke="white"
                                        stroke-width="3.36953" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <p class="ml-4">
                                Areakerja memiliki banyak mitra <br> perusahaan yang sedang membuka lowongan
                            </p>
                        </li>
                        <li class="flex items-center">
                            <span class="text-white mr-2">
                                <svg width="18" height="18" viewBox="0 0 23 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.094 2.28125L8.36467 15.7594L2 9.02032" stroke="white"
                                        stroke-width="3.36953" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <p class="ml-4">
                                Areakerja merupakan perusahaan terpercaya <br> berbadan hukum
                            </p>
                        </li>
                        <li class="flex items-center">
                            <span class="text-white mr-2">
                                <svg width="18" height="18" viewBox="0 0 23 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.094 2.28125L8.36467 15.7594L2 9.02032" stroke="white"
                                        stroke-width="3.36953" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <p class="ml-4">
                                Server Terbaik
                            </p>
                        </li>
                    </ul>
                </div>

                <!-- Right Image -->
                <div class="flex justify-center">
                    <img src="{{ asset('images/ntep.png') }}" alt="Kandidat" class="rounded-lg mr-[140px]">
                </div>
            </div>
        </section>
        <!-- Steps Section -->
        <section class="bg-white py-12">
            <div class="max-w-4xl mx-auto text-center px-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Cara Daftar Kandidat</h2>
                <div class="border-t border-gray-300 divide-y divide-gray-300">

                    <div class="flex items-center py-4 text-left">
                        <span
                            class="flex-shrink-0 w-10 h-10 bg-orange-500 text-white flex items-center justify-center rounded-full mr-4 hover:scale-105">1</span>
                        <p class="text-gray-700 font-semibold ">Klik Daftar untuk registrasi kandidat</p>
                    </div>

                    <div class="flex items-center py-4 text-left">
                        <span
                            class="flex-shrink-0 w-10 h-10 bg-orange-500 text-white flex items-center justify-center rounded-full mr-4 hover:scale-105">2</span>
                        <p class="text-gray-700  font-semibold">Lengkapi data yang diperlukan pada proses registrasi</p>
                    </div>

                    <div class="flex items-center py-4 text-left">
                        <span
                            class="flex-shrink-0 w-10 h-10 bg-orange-500 text-white flex items-center justify-center rounded-full mr-4 hover:scale-105">3</span>
                        <p class="text-gray-700  font-semibold">Tunggu pemberitahuan setelah melakukan registrasi</p>
                    </div>

                    <div class="flex items-center py-4 text-left">
                        <span
                            class="flex-shrink-0 w-10 h-10 bg-orange-500 text-white flex items-center justify-center rounded-full mr-4 hover:scale-105">4</span>
                        <p class="text-gray-700  font-semibold">Ikuti pelatihan sesuai prosedur Areakerja.com</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= MODAL STEP 1 (PILIH DIVISI) ================= -->
        <div id="modalStep1" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full sm:max-w-md rounded-2xl shadow-xl relative p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
                <button onclick="closeAllModal()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-black text-xl">✕</button>

                <!-- Judul -->
                <h2 class="text-xl font-bold text-gray-800 mb-2">Daftar Kandidat</h2>
                <div class="h-1 w-32 bg-orange-500 mb-4"></div>

                <!-- Label -->
                <label for="divisiSelect" class="block text-sm font-medium text-gray-700 mb-2">
                    Bidang yang diminati
                </label>

                <!-- Multi select (pakai tom-select / choices.js) -->
                <select id="divisiSelect" name="divisi[]" multiple
                    class="w-full border rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}">{{ $divisi->divisi }}</option>
                    @endforeach
                </select>

                <!-- Footer Button -->
                <div class="flex flex-col sm:flex-row justify-between mt-6 gap-3 sm:gap-0">
                    <button onclick="closeAllModal()" class="text-orange-500 font-medium w-full sm:w-auto">Kembali</button>
                    <button onclick="saveDivisiAndNext()"
                        class="text-orange-500 font-semibold w-full sm:w-auto">Selanjutnya</button>
                </div>
            </div>
        </div>




        <!-- ================= MODAL STEP 2 ================= -->
        @include('kandidat.modal-topup.step2')


        <!-- ================= MODAL STEP 3 ================= -->
        <div id="modalStep3"
            class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div
                class="bg-white w-full sm:max-w-lg rounded-2xl shadow-xl relative p-4 sm:p-8 max-h-[90vh] overflow-y-auto">
                <button onclick="closeAllModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl">✕</button>

                <h2 class="text-xl font-bold">Detail Pembayaran</h2>
                <div class="h-1 w-32 bg-orange-500 mb-6"></div>

                <div class="border border-orange-400 rounded-lg p-4 sm:p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span>Divisi</span>
                        <span id="detailDivisi">-</span>
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
                        <span class="bg-orange-500 text-white text-xs font-medium px-3 py-1 rounded-full"
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

                <form action="{{ route('kandidat.storePendaftaran') }}" method="post" class="mt-6">
                    @csrf
                    <!-- hidden input -->
                    <input type="hidden" name="daftar_bank_id" id="inputBank">
                    <input type="hidden" name="divisi" id="inputDivisi">

                    <div class="flex justify-center mt-4">
                        <button type="submit"
                            class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-full">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>




        <script>
            let selectedDivisi = null;
            let selectedBank = null;

            function closeAllModal() {
                document.querySelectorAll('[id^="modalStep"]').forEach(m => {
                    m.classList.add('hidden');
                    m.classList.remove('flex');
                });
            }

            function goToStep(step) {
                @auth
                    const isKandidatAktif = {{ isset($isKandidatAktif) && $isKandidatAktif ? 'true' : 'false' }};
                    const pendingTxId = {{ isset($transaksiPending) && $transaksiPending ? $transaksiPending->id : 'null' }};

                    if (isKandidatAktif) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sudah Terdaftar',
                            text: 'Anda sudah terdaftar sebagai Kandidat Aktif AreaKerja.',
                            confirmButtonColor: '#f97316',
                            confirmButtonText: 'Mengerti'
                        });
                        return;
                    }

                    if (pendingTxId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pendaftaran Dalam Proses',
                            text: 'Anda sudah mengajukan pendaftaran kandidat. Silakan selesaikan proses transaksi pembayaran Anda.',
                            showCancelButton: true,
                            confirmButtonColor: '#f97316',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Lihat Transaksi Saya',
                            cancelButtonText: 'Tutup'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `/pelamar/kandidat/transaksi/${pendingTxId}`;
                            }
                        });
                        return;
                    }
                @endauth

                if (step === 2 && !selectedDivisi) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih divisi terlebih dahulu!',
                        confirmButtonColor: '#f97316'
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

                if (step === 3) {
                    const biayaAdmin = 2000;
                    const deposit = 200000;
                    const totalBayar = deposit + biayaAdmin;

                    // const randomPart = Math.floor(Math.random() * 1000000);
                    // const noTransaksi = "TRX" + Date.now() + randomPart;
                    const namaPengirim = "{{ Auth::user()->pelamar->name_pelamar ?? Auth::user()->username ?? 'Guest' }}";

                    // document.getElementById('detailTransaksi').innerText = noTransaksi;
                    document.getElementById('detailDivisi').innerText = selectedDivisi ?? '-';
                    document.getElementById('detailPengirim').innerText = namaPengirim;
                    document.getElementById('detailBank').innerText = selectedBank ?? '-';
                    document.getElementById('detailWaktu').innerText = new Date().toLocaleString('id-ID');
                    document.getElementById('detailHarga').innerText = "Rp. " + deposit.toLocaleString('id-ID');
                    document.getElementById('detailAdmin').innerText = "Rp. " + biayaAdmin.toLocaleString('id-ID');
                    document.getElementById('detailTotal').innerText = "Rp. " + totalBayar.toLocaleString('id-ID');

                    document.getElementById('inputBank').value = selectedBankId;
                    document.getElementById('inputDivisi').value = selectedDivisi;
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                // Step 2: pilih bank
                document.querySelectorAll('.metodePembayaran').forEach(el => {
                    el.addEventListener('change', function() {
                        selectedBank = this.dataset.bank;
                        document.getElementById('inputBank').value = this.value;
                        document.querySelectorAll('.pembayaranWrapper').forEach(w => {
                            w.classList.remove('ring-2', 'ring-orange-500');
                        });
                        this.closest('.pembayaranWrapper').classList.add('ring-2', 'ring-orange-500');
                    });
                });
            });

            // TomSelect init
            document.addEventListener('DOMContentLoaded', () => {
                new TomSelect('#divisiSelect', {
                    plugins: ['remove_button'],
                    placeholder: "Pilih divisi",
                    create: false,
                    maxItems: 5,
                });
            });

            function saveDivisiAndNext() {
                let divisiSelect = document.getElementById('divisiSelect');
                let selectedOptions = Array.from(divisiSelect.selectedOptions).map(o => o.text);

                if (selectedOptions.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih minimal satu divisi!',
                        confirmButtonColor: '#f97316'
                    });
                    return;
                }

                selectedDivisi = selectedOptions.join(', ');
                document.getElementById('inputDivisi').value = selectedOptions.join(', ');
                goToStep(2);
            }
        </script>
        <a href="#top"
            class="fixed bottom-6 right-6 bg-orange-500 text-white p-3 rounded-full shadow-lg hover:bg-orange-600 transition z-40 flex items-center justify-center"
            title="Kembali ke Atas">
            <svg width="24" height="23" viewBox="0 0 31 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_231_4417)">
                    <path
                        d="M26.6695 18.25L15.532 7.31684L4.3945 18.25L0.973172 14.8841L15.532 0.561196L30.0908 14.8841L26.6695 18.25Z"
                        fill="white" />
                </g>
                <defs>
                    <clipPath id="clip0_231_4417">
                        <rect width="29.1176" height="26.9608" fill="white"
                            transform="translate(30.0586 27.2148) rotate(-180)" />
                    </clipPath>
                </defs>
            </svg>
        </a>
    </div>

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Informasi Pendaftaran',
                    text: "{{ session('error') }}",
                    icon: 'warning',
                    confirmButtonColor: '#f97316',
                    confirmButtonText: 'Mengerti'
                });
            });
        </script>
    @endif

    @include('layouts.footer')
@endsection
