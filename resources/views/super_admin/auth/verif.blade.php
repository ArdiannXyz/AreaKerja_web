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
        <div class="flex w-full md:w-4/2 bg-white items-center justify-center">
            <div class="w-full max-w-md p-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Verifikasi Akun</h2>

                <!-- Login Sosial -->
                <div class="flex justify-center space-x-2 mb-3">

                </div>

                <p class="text-center text-blue-700 mb-6 mt-6 text-sm">kata sandi Anda akan diatur ulang melalui email
                </p>

                <!-- Form Login -->
                <form class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="e-mail" id="e-mail" name="email" placeholder="E-mail"
                            class="mt-3 block w-full border border-gray-700 rounded-lg p-2.5 focus:ring-blue-700 focus:border-blue-700" />
                    </div>
                    <div class="flex justify-center">
                        <button type="submit"
                            class=" bg-blue-700 text-white px-40 py-3 rounded font-small text-sm hover:bg-blue-800 transition">
                            Lanjutkan
                        </button>
                    </div>
                    <a href="#" class="flex justify-center text-sm font-medium text-blue-700">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
