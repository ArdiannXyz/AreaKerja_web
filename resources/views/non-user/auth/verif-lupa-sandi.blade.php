<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi | AreaKerja</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_biru.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- ================= PANEL KIRI (BANNER DESKTOP) ================= -->
        <section class="relative lg:w-1/2 hidden lg:flex flex-col justify-between overflow-hidden bg-slate-900 text-white p-12">
            <!-- Background Image -->
            <img src="{{ asset('images/auth_team.png') }}" alt="Team Background"
                class="absolute inset-0 w-full h-full object-cover">

            <!-- Subtle Overlay -->
            <div class="absolute inset-0 bg-black/20"></div>

            <!-- Center Content -->
            <div class="relative z-10 text-center max-w-md mx-auto my-auto py-12">
                <h1 class="text-3xl sm:text-4xl font-bold mb-4 tracking-tight leading-tight">Hallo, Pekerja</h1>
                <p class="text-sm text-white/90 mb-8 leading-relaxed">
                    untuk tetap terhubung dengan kami, silakan masuk dengan informasi pribadi Anda
                </p>
                <a href="{{ url('/login') }}"
                    class="inline-block px-14 py-3 border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-slate-900 transition duration-300 text-sm tracking-wider uppercase shadow-md">
                    MASUK
                </a>
            </div>

            <!-- Bottom Spacer -->
            <div class="relative z-10 text-xs text-white/50 text-center">
                © {{ date('Y') }} AreaKerja. All rights reserved.
            </div>
        </section>

        <!-- ================= PANEL KANAN (FORM RESET PASSWORD) ================= -->
        <div class="flex w-full lg:w-1/2 bg-white items-center justify-center min-h-screen py-8 sm:py-12 px-6 sm:px-12">
            <div class="w-full max-w-md flex flex-col justify-between min-h-[500px] sm:min-h-0 sm:justify-center">



                <!-- Form Container -->
                <div class="my-auto sm:my-0">
                    <!-- Judul -->
                    <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#00509d] mb-1">Lupa Kata Sandi</h2>
                    <p class="text-xs sm:text-sm text-slate-500 text-center mb-8">
                        Masukkan kata sandi baru untuk akun Anda.
                    </p>

                    <form id="reset-passwordForm" action="{{ route('password.update.pelamar', ['token' => $token]) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Kata Sandi Baru -->
                        <div x-data="{ showPass: false }">
                            <div class="relative flex items-center">
                                <input :type="showPass ? 'text' : 'password'" id="password" name="password" placeholder="Kata Sandi Baru" required
                                    class="w-full px-4 py-3.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition pr-11 placeholder:text-slate-400">
                                <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer">
                                    <i :class="showPass ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-xl leading-none"></i>
                                </button>
                            </div>
                            <!-- Inline Alert Text -->
                            <p id="password-error" class="hidden text-rose-500 text-xs mt-1.5 flex items-center gap-1 font-medium">
                                <i class="ph ph-warning-circle text-sm shrink-0"></i>
                                <span id="password-error-text"></span>
                            </p>
                        </div>

                        <!-- Konfirmasi Kata Sandi -->
                        <div x-data="{ showPassConfirm: false }">
                            <div class="relative flex items-center">
                                <input :type="showPassConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" placeholder="Ulangi Kata Sandi" required
                                    class="w-full px-4 py-3.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition pr-11 placeholder:text-slate-400">
                                <button type="button" @click="showPassConfirm = !showPassConfirm" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition cursor-pointer">
                                    <i :class="showPassConfirm ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-xl leading-none"></i>
                                </button>
                            </div>
                            <!-- Inline Alert Text -->
                            <p id="confirm-error" class="hidden text-rose-500 text-xs mt-1.5 flex items-center gap-1 font-medium">
                                <i class="ph ph-warning-circle text-sm shrink-0"></i>
                                <span id="confirm-error-text"></span>
                            </p>
                        </div>

                        <div class="pt-3">
                            <button type="submit" id="submit-btn"
                                class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-3.5 rounded-xl shadow-md transition duration-200 text-sm tracking-normal cursor-pointer">
                                Simpan Kata Sandi
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <a href="{{ route('login') }}" class="text-xs sm:text-sm text-slate-500 hover:text-[#00509d] font-semibold transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Spacer on mobile -->
                <div class="h-6 sm:hidden"></div>

            </div>
        </div>

    </div>

    <!-- Form Inline Validation & Submission Script -->
    <script>
        const form = document.getElementById("reset-passwordForm");
        const passwordInput = document.getElementById("password");
        const confirmInput = document.getElementById("password_confirmation");
        const submitBtn = document.getElementById("submit-btn");

        const passError = document.getElementById("password-error");
        const passErrorText = document.getElementById("password-error-text");
        const confError = document.getElementById("confirm-error");
        const confErrorText = document.getElementById("confirm-error-text");

        function showPassError(msg) {
            passErrorText.textContent = msg;
            passError.classList.remove("hidden");
            passwordInput.classList.add("border-rose-500", "focus:ring-rose-400", "focus:border-rose-500");
            passwordInput.classList.remove("border-slate-300", "focus:ring-[#00509d]", "focus:border-[#00509d]");
        }

        function clearPassError() {
            passError.classList.add("hidden");
            passwordInput.classList.remove("border-rose-500", "focus:ring-rose-400", "focus:border-rose-500");
            passwordInput.classList.add("border-slate-300", "focus:ring-[#00509d]", "focus:border-[#00509d]");
        }

        function showConfError(msg) {
            confErrorText.textContent = msg;
            confError.classList.remove("hidden");
            confirmInput.classList.add("border-rose-500", "focus:ring-rose-400", "focus:border-rose-500");
            confirmInput.classList.remove("border-slate-300", "focus:ring-[#00509d]", "focus:border-[#00509d]");
        }

        function clearConfError() {
            confError.classList.add("hidden");
            confirmInput.classList.remove("border-rose-500", "focus:ring-rose-400", "focus:border-rose-500");
            confirmInput.classList.add("border-slate-300", "focus:ring-[#00509d]", "focus:border-[#00509d]");
        }

        passwordInput.addEventListener("input", clearPassError);
        confirmInput.addEventListener("input", clearConfError);

        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            clearPassError();
            clearConfError();

            const password = passwordInput.value;
            const confirm = confirmInput.value;

            // 1. Cek minimal 8 karakter
            if (password.length < 8) {
                showPassError("Kata sandi tidak boleh kurang dari 8 karakter.");
                passwordInput.focus();
                return;
            }

            // 2. Cek minimal 1 huruf besar
            if (!/[A-Z]/.test(password)) {
                showPassError("Kata sandi harus mengandung minimal 1 huruf besar (A-Z).");
                passwordInput.focus();
                return;
            }

            // 3. Cek minimal 1 huruf kecil
            if (!/[a-z]/.test(password)) {
                showPassError("Kata sandi harus mengandung minimal 1 huruf kecil (a-z).");
                passwordInput.focus();
                return;
            }

            // 4. Cek minimal 1 angka
            if (!/[0-9]/.test(password)) {
                showPassError("Kata sandi harus mengandung minimal 1 angka (0-9).");
                passwordInput.focus();
                return;
            }

            // 5. Cek minimal 1 simbol unik
            if (!/[@$!%*?&#]/.test(password)) {
                showPassError("Kata sandi harus mengandung minimal 1 simbol unik (@$!%*?&#).");
                passwordInput.focus();
                return;
            }

            // 6. Cek konfirmasi kata sandi cocok
            if (password !== confirm) {
                showConfError("Ulangi kata sandi tidak cocok dengan kata sandi baru.");
                confirmInput.focus();
                return;
            }

            // Submit form via fetch / AJAX
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Menyimpan...
            `;

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Kata Sandi Berhasil Diubah!',
                        text: 'Silakan masuk kembali dengan kata sandi baru Anda.',
                        confirmButtonText: 'MASUK',
                        confirmButtonColor: '#00509d'
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Simpan Kata Sandi';

                    if (data.errors && data.errors.password) {
                        showPassError(data.errors.password[0]);
                    } else if (data.message) {
                        showPassError(data.message);
                    }
                }
            } catch (err) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan Kata Sandi';
                showPassError("Terjadi kesalahan sistem. Silakan coba lagi.");
            }
        });
    </script>
</body>

</html>
