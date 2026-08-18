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
    <script src="https://unpkg.com/@phosphor-icons/web"></script>


    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex flex-col md:flex-row min-h-screen">

        {{-- Background dengan overlay  --}}
        <section class="relative lg:h-auto md:w-2/4 w-full hidden lg:block">

            <img src="{{ asset('images/gambar2.jpg') }}" alt="Background"
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
                <h1 class="text-3xl font-bold mt-10 md:mt-[-45%] mb-10">Hallo, Jobseeker</h1>
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
        <div class="flex w-full lg:w-4/5 bg-white items-start justify-center min-h-screen py-10">

            <div class="w-full max-w-md p-8 min-h-screen flex flex-col justify-start">

                <div class="w-full flex flex-col">

                    <!-- Judul -->
                    <h2 class="text-2xl font-semibold text-center text-orange-600 mb-6">Masuk</h2>

                    {{-- <!-- Login Sosial -->
                    <div class="flex justify-center mb-5">
                        <div class="flex gap-3">
                            @foreach ($socialLinks as $social)
                                @php
                                    $name = strtolower($social->nama);
                                @endphp

                                @if (in_array($name, ['instagram', 'facebook', 'linkedin']))
                                    <a href="{{ $social->link }}" title="Login dengan {{ ucfirst($social->nama) }}"
                                        class="w-10 h-10 flex items-center justify-center border rounded-full
                          hover:bg-gray-100 text-gray-700 font-bold transition hover:scale-110">

                                        @switch($name)
                                            @case('instagram')
                                                <i class="text-2xl ph ph-instagram-logo"></i>
                                            @break

                                            @case('facebook')
                                                <span class="text-2xl">f</span>
                                            @break

                                            @case('linkedin')
                                                <span class="text-xl">in</span>
                                            @break
                                        @endswitch

                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div> --}}


                    <p class="text-center text-gray-500 mb-6 text-sm">
                        gunakan email Anda untuk pendaftaran
                    </p>

                    <!-- Alert -->
                    @if (session('success'))
                        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm text-center">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form action="{{ route('loginproses') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com"
                                class="mt-2 block w-full border border-gray-700 rounded-lg p-2.5 focus:ring-orange-500 focus:border-orange-500" />
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                            <input type="password" id="password" name="password" required placeholder="Kata Sandi"
                                class="mt-2 block w-full border border-gray-700 rounded-lg p-2.5 focus:ring-orange-500 focus:border-orange-500" />
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="mr-2 border rounded-sm"> Ingat saya
                            </label>
                            <a href="{{ route('verifikasi_pelamar') }}" class="text-orange-500 hover:underline">
                                Lupa kata sandi?
                            </a>
                        </div>

                        <div class="flex justify-center">
                            <button type="submit"
                                class="w-52 h-14 bg-orange-500 text-white py-2.5 rounded-full font-normal text-md hover:bg-orange-600 transition duration-300 hover:scale-95">
                                MASUK
                            </button>
                        </div>

                        <p class="text-center text-sm mt-4">
                            Tidak Memiliki Akun?
                            <a href="register" class="text-orange-500 font-medium">
                                Daftar Sekarang
                            </a>
                        </p>

                    </form>
                </div>

            </div>

        </div>
    </div>
</body>

</html>
