    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register - AreaKerja</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
            <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
        </style>
    </head>

    <body class="bg-gray-100 min-h-screen flex flex-col">
       
        <!-- Container -->
        <div class="flex flex-col md:flex-row w-full min-h-screen">

            <!-- Form -->
            <div class="flex w-full md:w-3/5 bg-white items-center justify-center px-6 sm:px-10 py-10">

                <div class="w-full max-w-md mb-24">

                    <!-- Logo (Mobile only link to Landing Page) -->
                    <div class="flex md:hidden items-center justify-center gap-2 mb-6">
                        <a href="{{ route('beranda') }}" class="flex items-center gap-2 group" title="Kembali ke Beranda">
                            <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="h-10 w-10 object-contain group-hover:scale-105 transition">
                            <span class="font-bold mb-1 text-orange-500 group-hover:text-orange-600 transition">areakerja.com</span>
                        </a>
                    </div>

                    <div class="pt-4">
                        <h2 class="text-2xl font-semibold text-center text-orange-600 mb-6">Buat Akun</h2>
                    </div>

                    @if (session('error'))
                        <div class="mb-6 p-3 rounded-lg bg-red-100 border border-red-400 text-red-700 text-sm text-center shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tombol Otentikasi Sosial (Google, Facebook, LinkedIn) -->
                    <div class="flex space-x-5 mb-6 justify-center">
                        <!-- Google -->
                        <a href="{{ route('social.redirect', ['provider' => 'google']) }}?role=pelamar" title="Daftar dengan Google"
                            class="social-auth-btn w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-orange-500 hover:bg-orange-50 transition shadow-sm text-gray-800 font-bold" data-provider="google">
                            <span class="text-xl font-bold font-sans">G</span>
                        </a>

                        <!-- Facebook -->
                        <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}?role=pelamar" title="Daftar dengan Facebook"
                            class="social-auth-btn w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-blue-600 hover:bg-blue-50 transition shadow-sm text-gray-800 font-bold" data-provider="facebook">
                            <span class="text-xl font-bold font-sans">f</span>
                        </a>

                        <!-- LinkedIn -->
                        <a href="{{ route('social.redirect', ['provider' => 'linkedin']) }}?role=pelamar" title="Daftar dengan LinkedIn"
                            class="social-auth-btn w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-blue-700 hover:bg-blue-50 transition shadow-sm text-gray-800 font-bold" data-provider="linkedin">
                            <span class="text-lg font-bold font-sans">in</span>
                        </a>
                    </div>

                    <!-- Pilih Role -->
                    <div class="flex justify-center mb-6">
                        <div class="bg-gray-200 rounded-full p-1 flex space-x-1">
                            <button id="btn_pelamar"
                                class="bg-orange-500 text-white px-6 py-2 rounded-full text-sm font-semibold">
                                Pelamar
                            </button>
                            <button id="btn_perusahaan"
                                class="bg-gray-200 text-gray-600 px-6 py-2 rounded-full text-sm font-semibold">
                                Perusahaan
                            </button>
                        </div>
                    </div>

                    {{-- ====================== FORM PELAMAR ====================== --}}
                    <div id="regis_pelamar">
                        <form id="registerForm" action="{{ route('registerproses') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">Nama Pengguna</label>
                                <input type="text" name="username" placeholder="Nama Pengguna"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="username"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">Email</label>
                                <input type="email" name="email" placeholder="E-mail"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="email"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">No. Tlp</label>
                                <input type="text" name="telepon_pelamar" placeholder="08xxxxxxxx"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="telepon_pelamar"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">Kata Sandi</label>
                                <input type="password" name="password" placeholder="Kata Sandi"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="password"></p>
                            </div>

                            <label class="flex items-center text-xs sm:text-sm font-medium gap-1 cursor-pointer">
                                <input type="checkbox" id="agree_pelamar" name="agree_pelamar" required class="mr-2 rounded text-orange-500 focus:ring-orange-500">
                                <span>Saya menyetujui <button type="button" onclick="openTermsModal('pelamar')" class="text-orange-500 font-semibold hover:underline cursor-pointer">Syarat dan Ketentuan</button> yang berlaku</span>
                            </label>
                            <p class="error-message text-red-500 text-xs mt-1" data-field="agree_pelamar"></p>

                            <input type="hidden" name="role" value="pelamar">

                            <button type="submit"
                                class="w-full py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 mt-6 cursor-pointer transition shadow-sm">
                                Daftar
                            </button>
                        </form>
                    </div>


                    {{-- ====================== FORM PERUSAHAAN ====================== --}}
                    <div id="regis_perusahaan" class="hidden">
                        <form id="register_perusahaanForm" action="{{ route('registerproses_perusahaan') }}"
                            method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">Nama Perusahaan</label>
                                <input type="text" name="username" placeholder="Nama Perusahaan"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="username"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">Email</label>
                                <input type="email" name="email" placeholder="E-mail"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="email"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">No. Tlp Perusahaan</label>
                                <input type="text" name="telepon_perusahaan" placeholder="08xxxxxxxx"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="telepon_perusahaan">
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 m-2">Kata Sandi</label>
                                <input type="password" name="password" placeholder="Kata Sandi"
                                    class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-red-500 text-sm mt-1 error-message" data-field="password"></p>
                            </div>

                            <label class="flex items-center text-xs sm:text-sm font-medium gap-1 cursor-pointer">
                                <input type="checkbox" id="agree_perusahaan" name="agree_perusahaan" required class="mr-2 rounded text-orange-500 focus:ring-orange-500">
                                <span>Saya menyetujui <button type="button" onclick="openTermsModal('perusahaan')" class="text-orange-500 font-semibold hover:underline cursor-pointer">Syarat dan Ketentuan</button> yang berlaku</span>
                            </label>
                            <p class="error-message text-red-500 text-xs mt-1" data-field="agree_perusahaan"></p>


                            <input type="hidden" name="role" value="perusahaan">

                            <button type="submit"
                                class="w-full py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 mt-6 cursor-pointer transition shadow-sm">
                                Daftar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- =============== MODAL SYARAT DAN KETENTUAN =============== --}}
            <div id="termsModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-xs p-4">
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden animate-fadeIn">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-file-text text-orange-500 text-xl font-bold"></i>
                            <h3 class="font-bold text-slate-800 text-base sm:text-lg">Syarat &amp; Ketentuan Penggunaan</h3>
                        </div>
                        <button type="button" onclick="closeTermsModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none cursor-pointer">&times;</button>
                    </div>

                    <!-- Modal Body (Scrollable) -->
                    <div class="p-6 overflow-y-auto space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed text-justify">
                        <p>
                            Dokumen Syarat dan Ketentuan Penggunaan ("S&amp;K") ini menyatakan hak, kewajiban, dan ketentuan yang perlu Anda setujui untuk dapat menggunakan layanan, fitur, dan platform <strong>AreaKerja</strong>. Harap baca dengan seksama sebelum melakukan registrasi.
                        </p>

                        <h4 class="font-bold text-slate-900 text-sm">1. Pihak yang Terlibat</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>AreaKerja</strong>, sebagai penyedia platform bursa kerja, manajemen talenta, dan perantara rekrutmen.</li>
                            <li><strong>Pencari Kerja (Pelamar/Kandidat)</strong>, yaitu individu yang mencari informasi dan melamar lowongan kerja.</li>
                            <li><strong>Perusahaan (Mitra Employer)</strong>, yaitu entitas/organisasi yang mempublikasikan lowongan kerja dan merekrut talenta.</li>
                        </ul>

                        <h4 class="font-bold text-slate-900 text-sm">2. Ketentuan Akun &amp; Keamanan</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Pengguna wajib berusia minimal 18 tahun atau memiliki izin yang sah sesuai hukum yang berlaku.</li>
                            <li>Data yang dimasukkan saat pendaftaran harus valid, akurat, dan dapat dipertanggungjawabkan.</li>
                            <li>Pengguna bertanggung jawab penuh atas kerahasiaan kata sandi dan seluruh aktivitas akun.</li>
                            <li>Dilarang membuat akun palsu atau mengatasnamakan entitas lain tanpa wewenang legal.</li>
                        </ul>

                        <h4 class="font-bold text-slate-900 text-sm">3. Etika &amp; Larangan Penggunaan</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Dilarang menyalahgunakan data pelamar atau perusahaan untuk penipuan, pungutan liar, atau tindakan melanggar hukum.</li>
                            <li>AreaKerja berhak membatasi, membekukan, atau menghapus akun yang melanggar ketentuan tanpa pemberitahuan sebelumnya.</li>
                        </ul>

                        <h4 class="font-bold text-slate-900 text-sm">4. Privasi &amp; Data Pribadi</h4>
                        <p>
                            Data pribadi Anda akan diproses sesuai Kebijakan Privasi AreaKerja dan hanya digunakan untuk keperluan proses rekrutmen, penelusuran lowongan, serta notifikasi resmi platform.
                        </p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeTermsModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs sm:text-sm hover:bg-slate-100 transition cursor-pointer">
                            Tutup
                        </button>
                        <button type="button" onclick="acceptTermsModal()" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs sm:text-sm transition shadow-sm cursor-pointer flex items-center gap-1.5">
                            <i class="ph ph-check-circle text-base"></i>
                            <span>Saya Paham &amp; Setuju</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- =============== MODAL PELAMAR =============== --}}
            <div id="successModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
                <div class="relative bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-8 text-center">
                    <button onclick="closeModal()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
                    <h2 class="text-2xl font-bold mb-3">Selamat!</h2>
                    <h2 class="text-xl font-semibold mb-3">Akun anda berhasil dibuat</h2>
                    <p class="text-gray-700 mb-8">Silakan login untuk melanjutkan ke areakerja.</p>
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('images/orang.png') }}" alt="Ilustrasi" class="w-30 h-28">
                    </div>
                    <div class="flex justify-center gap-6">
                        <button id="goLogin"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg">Masuk</button>
                    </div>
                </div>
            </div>

            {{-- =============== MODAL PERUSAHAAN =============== --}}
            <div id="successModal_perusahaan"
                class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
                <div class="relative bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-6 sm:p-8 text-center">
                    <button onclick="closeModal()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
                    <h2 class="text-2xl font-bold mb-3">Selamat!</h2>
                    <h2 class="text-xl font-semibold mb-3">Akun anda berhasil dibuat</h2>
                    <p class="text-gray-700 mb-8">Silakan login untuk melanjutkan ke areakerja.</p>
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('images/orang.png') }}" alt="Ilustrasi" class="w-30 h-28">
                    </div>
                    <div class="flex justify-center gap-6">
                        <button id="gooLogin"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg">Masuk</button>
                    </div>
                </div>
            </div>

            <section class="relative hidden md:flex md:w-2/5 lg:w-2/4 min:h-screen overflow-hidden">
                <img src="{{ asset('images/gambar2.jpg') }}" alt="Background" class="w-full h-full object-cover">
                <!-- Logo (Link to Landing Page) -->
                <a href="{{ route('beranda') }}" class="absolute top-6 right-6 flex items-center gap-2 group transition z-20" title="Kembali ke Beranda">
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="Logo" class="h-12 w-12 object-contain group-hover:scale-105 transition">
                    <span class="font-semibold mb-1 text-white group-hover:text-orange-200 transition">areakerja.com</span>
                </a>
                <div
                    class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-center text-white px-6 pb-56">
                    <h2 class="text-3xl font-semibold mb-4">Hallo, Jobseeker</h2>
                    <p class="mb-6">Untuk tetap terhubung dengan kami, silakan masuk dengan informasi pribadi Anda.
                    </p>
                    <a href="{{ url('/login') }}"
                        class="px-20 py-4 border border-white rounded-full hover:bg-white hover:text-black transition">MASUK</a>
                </div>
            </section>
        </div>

        {{-- Email sama --}}

        {{-- SCRIPT TOGGLE FORM --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const btnPelamar = document.getElementById("btn_pelamar");
                const btnPerusahaan = document.getElementById("btn_perusahaan");
                const regisPelamar = document.getElementById("regis_pelamar");
                const regisPerusahaan = document.getElementById("regis_perusahaan");

                btnPelamar.addEventListener("click", () => {
                    function updateSocialLinksRole(role) {
                        document.querySelectorAll('.social-auth-btn').forEach(btn => {
                            let provider = btn.getAttribute('data-provider');
                            btn.href = `/auth/${provider}/redirect?role=${role}`;
                        });
                    }

                    regisPelamar.classList.remove("hidden");
                    regisPerusahaan.classList.add("hidden");
                    btnPelamar.classList.add("bg-orange-500", "text-white");
                    btnPerusahaan.classList.remove("bg-orange-500", "text-white");
                    btnPerusahaan.classList.add("bg-gray-200", "text-gray-600");
                    updateSocialLinksRole('pelamar');
                });

                btnPerusahaan.addEventListener("click", () => {
                    regisPerusahaan.classList.remove("hidden");
                    regisPelamar.classList.add("hidden");
                    btnPerusahaan.classList.add("bg-orange-500", "text-white");
                    btnPelamar.classList.remove("bg-orange-500", "text-white");
                    btnPelamar.classList.add("bg-gray-200", "text-gray-600");
                    updateSocialLinksRole('perusahaan');
                });
            });
        </script>

        {{-- FETCH REGISTER PERUSAHAAN --}}
        <script>
            document.getElementById("register_perusahaanForm").addEventListener("submit", async function(e) {
                e.preventDefault();

                document.querySelectorAll("#register_perusahaanForm .error-message").forEach(el => el.textContent =
                    "");

                // CEK CHECKBOX
                if (!document.getElementById("agree_perusahaan").checked) {
                    document.querySelector(`#register_perusahaanForm .error-message[data-field="agree_perusahaan"]`)
                        .textContent = "Anda harus menyetujui syarat dan ketentuan.";
                    return;
                }

                let formData = new FormData(this);

                try {
                    let response = await fetch(this.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": this.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            document.getElementById("successModal_perusahaan").classList.remove("hidden");
                            document.getElementById("successModal_perusahaan").classList.add("flex");
                        }
                    } else if (response.status === 422) {
                        const errorData = await response.json();
                        Object.keys(errorData.errors).forEach(field => {
                            const el = document.querySelector(
                                `#register_perusahaanForm .error-message[data-field="${field}"]`
                            );
                            if (el) el.textContent = errorData.errors[field][0];
                        });
                    } else {
                        alert("Terjadi kesalahan server.");
                    }

                } catch (err) {
                    alert("Gagal menghubungi server. Coba lagi.");
                }
            });

            document.getElementById("goLogin")?.addEventListener("click", function() {
                window.location.href = "/login";
            });

            document.getElementById("gooLogin")?.addEventListener("click", function() {
                window.location.href = "/login";
            });

            // tombol close modal
            function closeModal() {
                document.getElementById("successModal").classList.add("hidden");
                document.getElementById("successModal").classList.remove("flex");
                document.getElementById("successModal_perusahaan").classList.add("hidden");
                document.getElementById("successModal_perusahaan").classList.remove("flex");
            }
        </script>

        {{-- FETCH REGISTER PELAMAR --}}
        <script>
            document.getElementById("registerForm").addEventListener("submit", async function(e) {
                e.preventDefault();

                document.querySelectorAll("#registerForm .error-message").forEach(el => el.textContent = "");

                // CEK CHECKBOX
                if (!document.getElementById("agree_pelamar").checked) {
                    document.querySelector(`#registerForm .error-message[data-field="agree_pelamar"]`)
                        .textContent = "Anda harus menyetujui syarat dan ketentuan.";
                    return;
                }

                let formData = new FormData(this);

                try {
                    let response = await fetch(this.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": this.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            document.getElementById("successModal").classList.remove("hidden");
                            document.getElementById("successModal").classList.add("flex");
                        }
                    } else if (response.status === 422) {
                        const errorData = await response.json();
                        Object.keys(errorData.errors).forEach(field => {
                            const el = document.querySelector(
                                `#registerForm .error-message[data-field="${field}"]`
                            );
                            if (el) el.textContent = errorData.errors[field][0];
                        });
                    } else {
                        alert("Terjadi kesalahan server.");
                    }

                } catch (err) {
                    alert("Gagal menghubungi server. Coba lagi.");
                }
            });

            document.getElementById("goLogin")?.addEventListener("click", function() {
                window.location.href = "/login";
            });

            document.getElementById("gooLogin")?.addEventListener("click", function() {
                window.location.href = "/login";
            });

            // tombol close modal
            function closeModal() {
                document.getElementById("successModal").classList.add("hidden");
                document.getElementById("successModal").classList.remove("flex");
                document.getElementById("successModal_perusahaan").classList.add("hidden");
                document.getElementById("successModal_perusahaan").classList.remove("flex");
            }

            // ================= TERMS MODAL HANDLERS =================
            let currentTermsTarget = 'agree_pelamar';

            function openTermsModal(target) {
                currentTermsTarget = target === 'perusahaan' ? 'agree_perusahaan' : 'agree_pelamar';
                const modal = document.getElementById('termsModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            }

            function closeTermsModal() {
                const modal = document.getElementById('termsModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }

            function acceptTermsModal() {
                const cb = document.getElementById(currentTermsTarget) || document.getElementById('agree_pelamar');
                if (cb) {
                    cb.checked = true;
                }
                const err = document.querySelector(`.error-message[data-field="${currentTermsTarget}"]`);
                if (err) err.textContent = '';
                closeTermsModal();
            }
        </script>

    </body>

    </html>
