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


                <!-- Text -->
                <h1 class="text-3xl font-bold mt-[-45%] mb-10">Hallo, Pekerja</h1>
                <p class="text-sm mb-10">untuk tetap terhubung dengan kami, silakan<br> masuk dengan informasi pribadi
                    Anda</p>

                <!-- Button -->
                <a href="#"
                    class="px-20 py-3 border border-white rounded-full hover:bg-white hover:text-black transition">
                    Masuk
                </a>
            </div>
        </section>



        <!-- Kanan -->
        <div class="flex w-full md:w-4/2 bg-white items-center justify-center">
            <div class="w-full max-w-md p-8">
                <h2 class="text-2xl font-bold text-center text-[#003d7a] mb-8">Verifikasi Akun</h2>

                <!-- Login Sosial -->
                <div class="flex justify-center space-x-2 mb-3">

                </div>

                <p class="text-center text-gray-500 mb-6 mt-6 text-sm">kata sandi Anda akan diatur ulang melalui email
                </p>

                <!-- Form Login -->
                <form action="{{ route('password.email.perusahaan') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="Email"
                            value="{{ old('email') }}"
                            class="mt-2 block w-full border border-gray-700 rounded-lg p-2.5 focus:ring-[#00509d] focus:border-[#00509d]" />

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>
                    <div class="flex justify-center">
                        <button type="submit"
                            class=" bg-[#00509d] text-white px-40 py-3 rounded font-small text-sm hover:bg-[#003d7a] transition">
                            Lanjutkan
                        </button>
                    </div>
                    <a href="{{ route('login_perusahaan') }}" class="flex justify-center text-sm text-[#00509d]">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

