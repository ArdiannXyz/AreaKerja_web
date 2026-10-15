<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AreaKerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Paksa semua teks pakai Poppins -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class=" bg-gray-100 flex">

    <!-- Container -->
    <div class="flex  w-full">

        <!-- Bagian kiri (Form) -->
        <div class="flex w-full md:w-4/5 bg-white items-center justify-center px-10">
            <div class="w-full max-w-md">

                <!-- Logo + Judul -->
                <div class="absolute top-6 left-6 gap-1 flex items-center">
                    <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="h-12 w-12">
                    <span class="font-bold mb-1 text-[#00509d]">areakerja.com</span>
                </div>


                <div class="pt-20">
                    <h2 class="text-2xl font-semibold text-center text-[#003d7a] mb-6">Buat Akun</h2>
                </div>

                <!-- Tombol Sosial -->
                <!-- Tombol Otentikasi Sosial (Google, Facebook) -->
                <div class="flex space-x-5 mb-6 justify-center">
                    <!-- Google -->
                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}?role=perusahaan" title="Daftar Perusahaan dengan Google"
                        class="w-12 h-12 flex items-center justify-center border-2 border-gray-200 bg-white rounded-full hover:border-[#00509d] hover:bg-gray-50 transition shadow-sm hover:scale-105 duration-200">
                        <svg class="w-6 h-6" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}?role=perusahaan" title="Daftar Perusahaan dengan Facebook"
                        class="w-12 h-12 flex items-center justify-center border-2 border-gray-200 bg-white rounded-full hover:border-blue-600 hover:bg-gray-50 transition shadow-sm hover:scale-105 duration-200">
                        <svg class="w-6 h-6 text-[#1877F2]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>

                <!-- Pilih Role -->
                <div class="flex justify-center mb-6">
                    <div class="bg-gray-200 rounded-full p-1 flex space-x-1">
                        <!-- Tombol Aktif -->
                        <button class="bg-gray-200 text-gray-600 px-6 py-2 rounded-full text-sm font-semibold shadow">
                            Pelamar
                        </button>
                        <!-- Tombol Tidak Aktif -->
                        <button class="bg-[#00509d] text-white  px-6 py-2 rounded-full text-sm font-semibold">
                            Perusahaan
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <form id="registerPerusahaanStandalone" action="{{ route('registerproses_perusahaan') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 m-2">Nama Perusahaan</label>
                        <input type="text" name="username" id="username" placeholder="Nama Perusahaan"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="username"></p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 m-2">Email</label>
                        <input type="email" name="email" id="email" placeholder="E-mail"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="email"></p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 m-2">No.Tlp Perusahaan</label>
                        <input type="text" name="telepon_perusahaan" id="phone" placeholder="08xxxxxxxx"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="telepon_perusahaan"></p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 m-2">Kata Sandi</label>
                        <input type="password" name="password" id="password" placeholder="Kata Sandi"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="password"></p>
                    </div>

                    <input type="hidden" name="role" value="perusahaan">

                    <!-- Checkbox -->
                    <label class="flex items-center text-xs sm:text-sm font-medium gap-1 cursor-pointer">
                        <input type="checkbox" id="agree_perusahaan_standalone" name="agree_perusahaan" required class="mr-2 rounded text-[#00509d] focus:ring-[#00509d]">
                        <span>Saya menyetujui <button type="button" onclick="openTermsModal()" class="text-[#00509d] font-semibold hover:underline cursor-pointer">Syarat dan Ketentuan</button> yang berlaku</span>
                    </label>
                    <p class="text-red-500 text-xs mt-1 error-message" data-field="agree_perusahaan"></p>

                    <!-- Tombol Daftar -->
                    <button type="submit"
                        class="w-full py-3 bg-[#00509d] text-white rounded-lg font-semibold hover:bg-[#003d7a] mt-6 cursor-pointer transition shadow-sm">
                        Daftar
                    </button>
                </form>
            </div>
        </div>

        <!-- Bagian kanan (Gambar) -->
        <section class="relative hidden md:flex w-2/4">
            <img src="{{ asset('images/gambar2.jpg') }}" alt="Background" class="w-full h-full object-cover">

            <!-- Overlay hitam transparan -->
            <div
                class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-center text-white px-6 pb-56">
                <h2 class="text-3xl font-semibold mb-4">Hallo, Pekerja</h2>
                <p class="mb-6">untuk tetap terhubung dengan kami, silakan <br> masuk dengan informasi pribadi Anda
                </p>
                <a href="{{ url('/login') }}"
                    class="px-20 py-4 border border-white rounded-full hover:bg-white hover:text-black transition">MASUK</a>
            </div>
        </section>

    </div>

    <!-- MODAL SYARAT DAN KETENTUAN -->
    <div id="termsModalPerusahaan" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden animate-fadeIn">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-slate-800 text-base sm:text-lg">Syarat &amp; Ketentuan Penggunaan (Perusahaan)</h3>
                </div>
                <button type="button" onclick="closeTermsModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none cursor-pointer">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed text-justify">
                <p>
                    Dokumen Syarat dan Ketentuan Penggunaan ini mengatur tata tertib dan hak serta kewajiban Perusahaan / Employer yang menggunakan platform <strong>AreaKerja</strong>.
                </p>
                <h4 class="font-bold text-slate-900 text-sm">1. Legalitas Perusahaan</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Perusahaan wajib mengunggah data legalitas yang sah (SIUP/NIB/KTP Penanggung Jawab) untuk proses verifikasi.</li>
                    <li>Lowongan yang dipasang tidak boleh memungut biaya apapun dari pelamar (No Paid Recruitment).</li>
                </ul>

                <h4 class="font-bold text-slate-900 text-sm">2. Etika Perekrutan</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Perusahaan dilarang menyalahgunakan data kandidat di luar proses rekrutmen.</li>
                    <li>AreaKerja berhak membatalkan lowongan atau memblokir akun perusahaan jika terbukti melakukan pelanggaran hukum.</li>
                </ul>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" onclick="closeTermsModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-100 transition cursor-pointer">
                    Tutup
                </button>
                <button type="button" onclick="acceptTermsModal()" class="px-5 py-2.5 rounded-xl bg-[#00509d] hover:bg-[#003d7a] text-white font-bold text-xs sm:text-sm transition shadow-sm cursor-pointer">
                    Saya Paham &amp; Setuju
                </button>
            </div>
        </div>
    </div>

    @include('non-user.auth.modal-regsiter')

    <script>
        function openTermsModal() {
            const modal = document.getElementById('termsModalPerusahaan');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeTermsModal() {
            const modal = document.getElementById('termsModalPerusahaan');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function acceptTermsModal() {
            const cb = document.getElementById('agree_perusahaan_standalone');
            if (cb) {
                cb.checked = true;
            }
            const err = document.querySelector(`.error-message[data-field="agree_perusahaan"]`);
            if (err) err.textContent = '';
            closeTermsModal();
        }
    </script>
</body>

</html>

