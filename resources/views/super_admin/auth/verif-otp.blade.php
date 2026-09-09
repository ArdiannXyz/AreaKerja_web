<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Areakerja</title>
    @vite('resources/css/app.css')
    <link rel="icon" sizes="512x512" type="image/png" href="{{ asset('images/logoarea.png') }}">
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

        {{-- Background dengan overlay  --}}
        <section class="relative h-screen w-2/4">
            <img src="{{ asset('images/gambarkom.jpg') }}" alt="Background"
                class="absolute inset-0 w-full h-full object-cover">

            <!-- Overlay hitam transparan -->
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>

            <!-- Konten -->
            <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-6">

                <!-- Logo -->
                <div class="absolute top-6 left-6 flex items-center">
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="Logo" class="h-12 w-12">
                    <span class="font-semibold mb-1">areakerja.com</span>
                </div>

                <!-- Text -->
                <h1 class="text-3xl font-bold mt-[-20%] mb-10">Super Admin Area Kerja</h1>
                <p class="text-sm mb-10">untuk tetap terhubung dengan kami, silakan<br> masuk dengan informasi pribadi
                    Anda</p>

                <!-- Button -->
                <a href="{{ url('/register') }}"
                    class="px-20 py-4 border border-white rounded-full hover:bg-white hover:text-black transition">
                    DAFTAR
                </a>
            </div>
        </section>


        <!-- Kanan -->
        <div class="flex w-full md:w-4/5 bg-white items-center justify-center">
            <div class="w-full max-w-md p-8">


                <!-- Login Sosial -->
                <div class="flex justify-center space-x-3 mb-5">

                </div>

                <div class="flex items-center justify-center">
                    <div class="max-w-md w-full px-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Verifikasi Akun</h2>
                        <p class="text-blue-700 font-medium mb-4">
                            Silahkan verifikasi akun anda terlebih dahulu untuk bisa melakukan penggantian kata sandi
                        </p>
                        <p class="mb-2 text-blue-700 font-medium">
                            Kode verifikasi telah dikirim ke email 
                        </p>
                           <span class="font-semibold">emailpengguna@gmail.com</span>.

                        <p class="font-semibold text-center mt-8 mb-4">Kode Verifikasi</p>

                        <!-- Input OTP -->
                        <div class="flex justify-center space-x-4 mb-6">
                            <input type="text" maxlength="1"
                                class="w-12 h-12 text-center border-b-4 border-black text-lg focus:outline-none">
                            <input type="text" maxlength="1"
                                class="w-12 h-12 text-center border-b-4 border-black text-lg focus:outline-none">
                            <input type="text" maxlength="1"
                                class="w-12 h-12 text-center border-b-4 border-black text-lg focus:outline-none">
                            <input type="text" maxlength="1"
                                class="w-12 h-12 text-center border-b-4 border-black text-lg focus:outline-none">
                            <input type="text" maxlength="1"
                                class="w-12 h-12 text-center border-b-4 border-black text-lg focus:outline-none">
                            <input type="text" maxlength="1"
                                class="w-12 h-12 text-center border-b-4 border-black text-lg focus:outline-none">
                        </div>

                        <p class="text-center text-blue-700">Belum menerima kode verifikasi melalui email?</p>
                        <p class="text-center mt-1 text-blue-700">Kirim Ulang Kode Verifikasi 
                              <span id="countdown" class="text-blue-700 font-semibold">(00:45)</span>
            </p>
        </div>
    </div>

    <script>
        let timeLeft = 45;
        const countdownEl = document.getElementById("countdown");

        function startCountdown() {
            const timer = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    countdownEl.textContent = "(00:00)";
                    countdownEl.classList.remove("text-blue-700");
                    countdownEl.classList.add("text-blue-600", "cursor-pointer");
                    countdownEl.textContent = " Kirim Ulang";
                } else {
                    let minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
                    let seconds = String(timeLeft % 60).padStart(2, '0');
                    countdownEl.textContent = `(${minutes}:${seconds})`;
                    timeLeft--;
                }
            }, 1000);
        }

        startCountdown();
    </script><br>
                        <div class="flex justify-center">
                            <button type="submit"
                                class=" bg-blue-700 text-white px-40 py-3 rounded-lg font-small text-sm hover:bg-blue-800 transition">
                                Lanjutkan
                            </button>
                        </div><br>
                        <p class="text-center font-semibold text-blue-700 hover:text-blue-800">Ubah Email</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

      <script>
        let timeLeft = 45;
        const countdownEl = document.getElementById("countdown");

        function startCountdown() {
            const timer = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    countdownEl.textContent = "(00:00)";
                    countdownEl.classList.remove("text-blue-700");
                    countdownEl.classList.add("text-blue-600", "cursor-pointer");
                    countdownEl.textContent = " Kirim Ulang";
                } else {
                    let minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
                    let seconds = String(timeLeft % 60).padStart(2, '0');
                    countdownEl.textContent = `(${minutes}:${seconds})`;
                    timeLeft--;
                }
            }, 1000);
        }

        startCountdown();
    </script>
</body>

</html>
