<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Kode OTP | AreaKerja</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_biru.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

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

        <!-- ================= PANEL KANAN (FORM OTP) ================= -->
        <div class="flex w-full lg:w-1/2 bg-white items-center justify-center min-h-screen py-8 sm:py-12 px-6 sm:px-12">
            <div class="w-full max-w-md flex flex-col justify-between min-h-[500px] sm:min-h-0 sm:justify-center">



                <!-- Form Container -->
                <div class="my-auto sm:my-0">
                    <!-- Judul -->
                    <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#00509d] mb-2">Verifikasi Akun</h2>

                    <p class="text-xs text-slate-500 text-center mb-1 leading-relaxed">
                        Silakan verifikasi akun anda terlebih dahulu untuk bisa melakukan penggantian kata sandi.
                    </p>
                    <p class="text-xs text-slate-700 text-center mb-6">
                        Kode verifikasi telah dikirim ke email <span class="font-bold text-[#00509d]">{{ $email }}</span>.
                    </p>

                    <!-- FORM OTP -->
                    <form action="{{ route('password.otp.verif.pelamar') }}" method="POST" id="otpForm" class="space-y-6">
                        @csrf

                        <div>
                            <p class="text-xs font-bold text-slate-700 text-center mb-3">Kode Verifikasi</p>

                            <div class="flex justify-center gap-2 sm:gap-3">
                                @for ($i = 1; $i <= 6; $i++)
                                    <input type="text" maxlength="1" inputmode="numeric"
                                        class="otp-input w-10 h-12 sm:w-12 sm:h-14 text-center border-b-2 sm:border-b-4 border-slate-900 text-xl font-bold focus:border-[#00509d] focus:bg-blue-50/30 outline-none transition">
                                @endfor
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" name="otp" id="otp">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="text-center text-xs text-slate-500 space-y-1">
                            <p>Belum menerima kode verifikasi melalui email?</p>
                            <p class="text-slate-700 text-xs">
                                Kirim Ulang Kode Verifikasi
                                <span id="countdown" class="text-amber-600 font-bold">(00:59)</span>
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-3.5 rounded-xl shadow-md transition duration-200 text-sm tracking-normal cursor-pointer">
                            Lanjutkan
                        </button>

                        <div class="flex justify-between items-center text-xs text-slate-500 pt-1">
                            <a href="{{ route('verifikasi_pelamar') }}" class="text-[#00509d] hover:underline font-semibold">
                                Ubah Email
                            </a>
                            <a href="{{ route('login') }}" class="text-slate-500 hover:text-slate-800 transition">
                                Kembali ke Masuk
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Spacer on mobile -->
                <div class="h-6 sm:hidden"></div>

            </div>
        </div>

    </div>

    <!-- OTP Input script -->
    <script>
        const inputs = document.querySelectorAll(".otp-input");
        const hiddenOtp = document.getElementById("otp");

        inputs.forEach((input, index) => {
            input.addEventListener("input", (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHiddenOtp();
            });

            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateHiddenOtp() {
            let fullOtp = "";
            inputs.forEach(input => fullOtp += input.value);
            hiddenOtp.value = fullOtp;
        }

        // Countdown script
        let timeLeft = 59;
        const countdownEl = document.getElementById("countdown");
        const timer = setInterval(() => {
            timeLeft--;
            if (timeLeft < 0) {
                clearInterval(timer);
                countdownEl.innerHTML = `<a href="javascript:location.reload()" class="text-[#00509d] underline font-bold cursor-pointer">Kirim Sekarang</a>`;
            } else {
                let sec = timeLeft < 10 ? '0' + timeLeft : timeLeft;
                countdownEl.textContent = `(00:${sec})`;
            }
        }, 1000);
    </script>
</body>

</html>
