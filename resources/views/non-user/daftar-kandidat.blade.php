@extends('layouts.index')
@section('content')

    <div class="bg-white min-h-screen text-slate-800 pt-20 sm:pt-22 md:pt-24 pb-20">

        <!-- Hero Section (Full bleed edge-to-edge, no margin gap, no round) -->
        <section class="relative w-full overflow-hidden shadow-sm">
            <img src="{{ asset('images/ntap.png') }}"
                alt="Header Image" class="w-full h-[360px] sm:h-[450px] md:h-[520px] object-cover">

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute left-6 sm:left-12 md:left-20 bottom-12 sm:bottom-20 text-white max-w-md md:max-w-xl">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight">
                    Daftar Kandidat
                </h1>
                <p class="text-sm sm:text-base mt-3 text-white/90">
                    Ikuti pelatihan terakreditasi Areakerja.com dan dapatkan pekerjaan impian anda!
                </p>
                <div class="mt-6">
                    <button onclick="goToStep(1)"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-8 py-2.5 rounded-lg text-sm shadow-md transition">
                        Daftar
                    </button>
                </div>
            </div>
        </section>

        <!-- Benefit Section (Full bleed edge-to-edge, no round) -->
        <section class="w-full bg-[#0066cc] text-white py-16">
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center px-6">

                <!-- Left Content -->
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold mb-8 leading-snug">
                        Benefit Menjadi Kandidat <br> Areakerja.com
                    </h2>

                    <ul class="space-y-5 text-sm sm:text-base">
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-white text-xl mt-0.5"></i>
                            <span>Menjadi prioritas pilihan dari perusahaan mitra Areakerja</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-white text-xl mt-0.5"></i>
                            <span>Areakerja memiliki banyak mitra perusahaan yang sedang membuka lowongan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-white text-xl mt-0.5"></i>
                            <span>Areakerja merupakan perusahaan terpercaya berbadan hukum</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-white text-xl mt-0.5"></i>
                            <span>Server Terbaik</span>
                        </li>
                    </ul>
                </div>

                <!-- Right Man with Laptop Image -->
                <div class="flex justify-center">
                    <img src="{{ asset('images/ntep.png') }}" alt="Kandidat"
                        class="h-64 sm:h-80 md:h-[380px] object-contain drop-shadow-xl">
                </div>

            </div>
        </section>

        <!-- Cara Daftar Kandidat Section -->
        <section class="py-16 max-w-5xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 text-center mb-12">
                Cara Daftar Kandidat
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- Left Illustration (Uploaded Illustration) -->
                <div class="md:col-span-5 flex justify-center">
                    <img src="{{ asset('images/cara_daftar_kandidat.png') }}" alt="Ilustrasi Cara Daftar Kandidat"
                        class="max-h-80 object-contain">
                </div>

                <!-- Right 4 Rows with horizontal dividers matching Figma -->
                <div class="md:col-span-7 divide-y divide-slate-200 border-y border-slate-200">
                    <div class="py-5">
                        <p class="text-base sm:text-lg font-semibold text-slate-800">
                            Klik Daftar untuk registrasi kandidat
                        </p>
                    </div>
                    <div class="py-5">
                        <p class="text-base sm:text-lg font-semibold text-slate-800">
                            Lengkapi data yang diperlukan pada proses registrasi
                        </p>
                    </div>
                    <div class="py-5">
                        <p class="text-base sm:text-lg font-semibold text-slate-800">
                            Tunggu pemberitahuan setelah melakukan registrasi
                        </p>
                    </div>
                    <div class="py-5">
                        <p class="text-base sm:text-lg font-semibold text-slate-800">
                            Ikuti pelatihan sesuai prosedur Areakerja.com
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= MODAL STEP 1 (PILIH DIVISI) ================= -->
        <div id="modalStep1" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full sm:max-w-md rounded-2xl shadow-xl relative p-6 max-h-[90vh] overflow-y-auto">
                <button onclick="closeAllModal()"
                    class="absolute top-4 right-4 text-slate-400 hover:text-black text-xl">✕</button>

                <h2 class="text-xl font-bold text-slate-800 mb-2">Daftar Kandidat</h2>
                <div class="h-1 w-24 bg-[#00509d] mb-4 rounded-full"></div>

                <label for="divisiSelect" class="block text-sm font-medium text-slate-700 mb-2">
                    Bidang yang diminati
                </label>

                <select id="divisiSelect" name="divisi[]" multiple
                    class="w-full border rounded-lg focus:ring-[#00509d] focus:border-[#00509d]">
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}">{{ $divisi->divisi }}</option>
                    @endforeach
                </select>

                <div class="flex justify-between mt-6 gap-3">
                    <button onclick="closeAllModal()" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Kembali</button>
                    <button onclick="saveDivisiAndNext()"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-6 py-2 rounded-xl transition text-sm">Selanjutnya</button>
                </div>
            </div>
        </div>

        <!-- ================= MODAL STEP 2 ================= -->
        @include('kandidat.modal-topup.step2')

        <!-- ================= MODAL STEP 3 ================= -->
        <div id="modalStep3"
            class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full sm:max-w-lg rounded-2xl shadow-xl relative p-6 sm:p-8 max-h-[90vh] overflow-y-auto">
                <button onclick="closeAllModal()"
                    class="absolute top-4 right-4 text-slate-400 hover:text-black text-xl">✕</button>

                <h2 class="text-xl font-bold text-slate-800 mb-2">Detail Pembayaran</h2>
                <div class="h-1 w-24 bg-[#00509d] mb-6 rounded-full"></div>

                <div class="border border-slate-200 rounded-xl p-4 sm:p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Divisi</span>
                        <span id="detailDivisi" class="font-bold text-slate-800">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Nama Pengirim</span>
                        <span id="detailPengirim" class="font-bold text-slate-800">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Nama Penerima</span>
                        <span id="detailPenerima" class="font-bold text-slate-800">Area Kerja</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Metode Pembayaran</span>
                        <span class="bg-[#00509d] text-white text-xs font-bold px-3 py-1 rounded-full"
                            id="detailBank">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tgl/Waktu</span>
                        <span id="detailWaktu" class="text-slate-700">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jumlah Deposit</span>
                        <span id="detailHarga" class="font-bold text-slate-800">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Biaya Admin</span>
                        <span id="detailAdmin" class="text-slate-700">Rp. 2.000</span>
                    </div>
                    <div class="border-t border-dashed my-3"></div>
                    <div class="flex justify-between font-bold text-base text-[#00509d]">
                        <span>Total Pembayaran</span>
                        <span id="detailTotal">-</span>
                    </div>
                </div>

                <form action="{{ route('kandidat.storePendaftaran') }}" method="post" class="mt-6">
                    @csrf
                    <input type="hidden" name="daftar_bank_id" id="inputBank">
                    <input type="hidden" name="divisi" id="inputDivisi">

                    <button type="submit"
                        class="w-full py-3 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl shadow-md transition">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>

        <!-- Back to top button -->
        <a href="#top"
            class="fixed bottom-6 right-6 bg-[#00509d] text-white p-3.5 rounded-full shadow-xl hover:bg-[#003d7a] transition z-40 flex items-center justify-center"
            title="Kembali ke Atas">
            <i class="ph ph-arrow-up font-bold text-lg"></i>
        </a>

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
                @guest
                    window.location.href = "{{ route('login') }}";
                    return;
                @endguest

                const isKandidatAktif = {{ isset($isKandidatAktif) && $isKandidatAktif ? 'true' : 'false' }};
                const pendingTxId = {{ isset($transaksiPending) && $transaksiPending ? $transaksiPending->id : 'null' }};

                if (isKandidatAktif) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sudah Terdaftar',
                        text: 'Anda sudah terdaftar sebagai Kandidat Aktif AreaKerja.',
                        confirmButtonColor: '#00509d',
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
                        confirmButtonColor: '#00509d',
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

                if (step === 2 && !selectedDivisi) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih divisi terlebih dahulu!',
                        confirmButtonColor: '#00509d'
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

                if (step === 3) {
                    const biayaAdmin = 2000;
                    const deposit = 200000;
                    const totalBayar = deposit + biayaAdmin;
                    const namaPengirim = "{{ Auth::user()->pelamar->name_pelamar ?? Auth::user()->username ?? 'Guest' }}";

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
                document.querySelectorAll('.metodePembayaran').forEach(el => {
                    el.addEventListener('change', function() {
                        selectedBank = this.dataset.bank;
                        document.getElementById('inputBank').value = this.value;
                        document.querySelectorAll('.pembayaranWrapper').forEach(w => {
                            w.classList.remove('ring-2', 'ring-[#00509d]');
                        });
                        this.closest('.pembayaranWrapper').classList.add('ring-2', 'ring-[#00509d]');
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                if (document.getElementById('divisiSelect')) {
                    new TomSelect('#divisiSelect', {
                        plugins: ['remove_button'],
                        placeholder: "Pilih divisi",
                        create: false,
                        maxItems: 5,
                    });
                }
            });

            function saveDivisiAndNext() {
                let divisiSelect = document.getElementById('divisiSelect');
                let selectedOptions = Array.from(divisiSelect.selectedOptions).map(o => o.text);

                if (selectedOptions.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih minimal satu divisi!',
                        confirmButtonColor: '#00509d'
                    });
                    return;
                }

                selectedDivisi = selectedOptions.join(', ');
                document.getElementById('inputDivisi').value = selectedOptions.join(', ');
                goToStep(2);
            }
        </script>

    </div>

    @include('layouts.footer')
@endsection
