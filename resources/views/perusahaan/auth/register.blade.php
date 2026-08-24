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
                    <span class="font-bold mb-1 text-orange-500">areakerja.com</span>
                </div>


                <div class="pt-20">
                    <h2 class="text-2xl font-semibold text-center text-orange-600 mb-6">Buat Akun</h2>
                </div>

                <!-- Tombol Sosial -->
                <!-- Tombol Otentikasi Sosial (Google, Facebook, LinkedIn) -->
                <div class="flex space-x-5 mb-6 justify-center">
                    <!-- Google -->
                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}?role=perusahaan" title="Daftar Perusahaan dengan Google"
                        class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-orange-500 hover:bg-orange-50 transition shadow-sm text-gray-800 font-bold">
                        <span class="text-xl font-bold font-sans">G</span>
                    </a>

                    <!-- Facebook -->
                    <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}?role=perusahaan" title="Daftar Perusahaan dengan Facebook"
                        class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-blue-600 hover:bg-blue-50 transition shadow-sm text-gray-800 font-bold">
                        <span class="text-xl font-bold font-sans">f</span>
                    </a>

                    <!-- LinkedIn -->
                    <a href="{{ route('social.redirect', ['provider' => 'linkedin']) }}?role=perusahaan" title="Daftar Perusahaan dengan LinkedIn"
                        class="w-12 h-12 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-blue-700 hover:bg-blue-50 transition shadow-sm text-gray-800 font-bold">
                        <span class="text-lg font-bold font-sans">in</span>
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
                        <button class="bg-orange-500 text-white  px-6 py-2 rounded-full text-sm font-semibold">
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
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-orange-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="username"></p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 m-2">Email</label>
                        <input type="email" name="email" id="email" placeholder="E-mail"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-orange-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="email"></p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 m-2">No.Tlp Perusahaan</label>
                        <input type="text" name="telepon_perusahaan" id="phone" placeholder="08xxxxxxxx"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-orange-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="telepon_perusahaan"></p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 m-2">Kata Sandi</label>
                        <input type="password" name="password" id="password" placeholder="Kata Sandi"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-orange-300 focus:outline-none">
                        <p class="text-red-500 text-sm mt-1 error-message" data-field="password"></p>
                    </div>

                    <input type="hidden" name="role" value="perusahaan">

                    <!-- Checkbox -->
                    <label class="flex items-center text-sm font-medium gap-1">
                        <input type="checkbox" id="agree_perusahaan_standalone" name="agree_perusahaan" class="mr-2">
                        Saya menyetujui <a href="{{ url('syarat/ketentuan') }}" class="text-orange-500"> Syarat dan Ketentuan </a> yang berlaku
                    </label>
                    <p class="text-red-500 text-sm mt-1 error-message" data-field="agree_perusahaan"></p>

                    <!-- Tombol Daftar -->
                    <button type="submit"
                        class="w-full py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 mt-6">
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

    @include('non-user.auth.modal-regsiter')
</body>

</html>
