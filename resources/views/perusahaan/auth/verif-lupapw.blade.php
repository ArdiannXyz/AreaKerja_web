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
                    class="px-20 py-4 border border-white rounded-full hover:bg-white hover:text-black transition">
                    Masuk
                </a>
            </div>
        </section>



        <!-- Kanan -->
        <div class="flex w-full md:w-4/5 bg-white items-center justify-center">
            <div class="w-full max-w-md ">
                <!-- Bagian Kanan -->
                <div class=" flex items-center justify-center bg-white">
                    <div class="max-w-md w-full">
                        <h2 class="text-2xl font-bold text-[#003d7a] mb-2">Lupa Kata Sandi</h2>
                        <p class="text-gray-600 text-sm mb-6">Masukan kata sandi anda.<br>kata sandi harus mengandung:
                        </p>

                        <!-- Verifikasi Syarat Password -->
                        <div class="grid grid-cols-5 gap-4 text-center mb-6">
                            <div><span class="font-bold">8+</span><br><span class="text-xs">Karakter</span></div>
                            <div><span class="font-bold">AA</span><br><span class="text-xs">Huruf Besar</span></div>
                            <div><span class="font-bold">aa</span><br><span class="text-xs">Huruf Kecil</span></div>
                            <div><span class="font-bold">123</span><br><span class="text-xs">Angka</span></div>
                            <div><span class="font-bold">@$#</span><br><span class="text-xs">Simbol</span></div>
                        </div>

                        <!-- Form -->
                        <form action="#" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium m-2">Kata Sandi Baru</label>
                                <div class="relative">
                                    <input type="password" name="password" placeholder="Kata Sandi"
                                        class="w-full border-gray-500 border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#00509d]">
                                    <span class="absolute right-3 top-2.5 text-gray-500 cursor-pointer">ðŸ‘</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium m-2">Konfirmasi Kata Sandi</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" placeholder="Kata Sandi"
                                        class="w-full border-gray-500 border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#00509d]">
                                    <span class="absolute right-3 top-2.5 text-gray-500 cursor-pointer">ðŸ‘</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#00509d] text-white py-3 rounded-md hover:bg-[#003d7a] transition">
                                Ulang Kata Sandi
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="#" class="text-[#003d7a] font-medium hover:underline">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

