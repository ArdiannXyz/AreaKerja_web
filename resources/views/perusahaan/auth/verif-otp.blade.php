<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi | Areakerja</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Background dengan overlay -->
        <section class="relative h-screen w-2/4">
            <img src="{{ asset('images/gambar2.jpg') }}" alt="Background"
                class="absolute inset-0 w-full h-full object-cover">

            <!-- Overlay hitam transparan -->
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>

            <!-- Konten -->
            <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-6">
                <h1 class="text-3xl font-bold mt-[-45%] mb-10">Hallo, Pekerja</h1>
                <p class="text-sm mb-10">
                    untuk tetap terhubung dengan kami, silakan<br> masuk dengan informasi pribadi Anda
                </p>
                <a href="#"
                    class="px-20 py-4 border border-white rounded-full hover:bg-white hover:text-black transition">
                    Masuk
                </a>
            </div>
        </section>

        <!-- Bagian Kanan -->
        <div class="flex w-full md:w-4/5 bg-white items-center justify-center">
            <div class="w-full max-w-md p-8">
                <div class="flex items-center justify-center">
                    <div class="max-w-md w-full px-8">
                        <h2 class="text-2xl font-bold text-[#00509d] mb-4">Verifikasi Akun</h2>
                        <p class="text-gray-600 mb-4">
                            Silahkan verifikasi akun anda terlebih dahulu untuk bisa melakukan penggantian kata sandi
                        </p>
                        <p class="mb-6">
                            Kode verifikasi telah dikirim ke email
                            <span class="font-semibold">emailpengguna@gmail.com</span>.
                        </p>

                        <!-- FORM OTP -->
                        <form action="{{ route('password.otp.verif.perusahaan') }}" method="POST" id="otpForm">
                            @csrf
                            <p class="font-semibold text-center mb-4">Kode Verifikasi</p>

                            <div class="flex justify-center space-x-4 mb-6">
                                @for ($i = 1; $i <= 6; $i++)
                                    <input type="text" maxlength="1" name="otp"
                                        class="w-12 h-12 text-center border-b-4 border-black text-lg focus:outline-none otp-input">
                                @endfor
                            </div>

                            <!-- Hidden input untuk gabung semua OTP -->
                            <input type="hidden" name="otp" id="otp_code">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">

                            <p class="text-center text-gray-500">Belum menerima kode verifikasi melalui email?</p>
                            <p class="text-center mt-1">
                                Kirim Ulang Kode Verifikasi
                                <span id="countdown" class="text-[#00509d] font-semibold">(00:45)</span>
                            </p>

                            <div class="flex justify-center mt-6">
                                <button type="submit"
                                    class="bg-[#00509d] text-white px-40 py-3 rounded-lg font-small text-sm hover:bg-[#003d7a] transition">
                                    Lanjutkan
                                </button>
                            </div>
                        </form>

                        <p class="text-center font-semibold text-[#00509d] hover:text-[#003d7a] mt-4">
                            Ubah Email
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });

        document.getElementById('otpForm').addEventListener('submit', function(e) {
            let otp = '';
            document.querySelectorAll('.otp-input').forEach(input => otp += input.value);
            document.getElementById('otp_code').value = otp;
        });


        const countdownEl = document.getElementById("countdown");
        let timeLeft = 45;
        let timer = null;

        function startCountdown() {
            clearInterval(timer); // bersihkan timer lama
            countdownEl.classList.remove("text-blue-600", "cursor-pointer");
            countdownEl.classList.add("text-[#00509d]");
            countdownEl.style.pointerEvents = "none";

            timer = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    countdownEl.textContent = "Kirim Ulang";
                    countdownEl.classList.remove("text-[#00509d]");
                    countdownEl.classList.add("text-blue-600", "cursor-pointer");
                    countdownEl.style.pointerEvents = "auto"; // aktifkan kembali klik
                } else {
                    let minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
                    let seconds = String(timeLeft % 60).padStart(2, '0');
                    countdownEl.textContent = `(${minutes}:${seconds})`;
                    timeLeft--;
                }
            }, 1000);
        }

        function resendOtp() {
            fetch("{{ route('password.email.perusahaan') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        email: "{{ $email ?? '' }}"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    // reset countdown
                    timeLeft = 45;
                    startCountdown();
                })
                .catch(err => console.error(err));
        }

        // klik event -> cek textContent langsung
        countdownEl.addEventListener("click", () => {
            if (countdownEl.textContent === "Kirim Ulang") {
                resendOtp();
            }
        });

        startCountdown();
    </script>



</body>

</html>

