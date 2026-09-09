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
                    <span class="font-bold mb-1 text-blue-700">areakerja.com</span>
                </div>


                <div class="pt-20">
                    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Buat Akun</h2>
                </div>

                <!-- Tombol Sosial -->
                <div class="flex space-x-4 mb-6 justify-center">
                    <button
                        class="w-10 h-10 flex text-2xl items-center justify-center border rounded-full hover:bg-gray-100 text-gray-700 font-bold">G</button>
                    <button
                        class="w-10 h-10 flex items-center justify-center border rounded-full hover:bg-gray-100 text-gray-700 font-bold">f</button>
                    <button
                        class="w-10 h-10 flex items-center justify-center border rounded-full hover:bg-gray-100 text-gray-700 font-bold">in</button>
                </div>

                <!-- Form -->
                <form id="registerForm" action="{{ route('registerproses_superadmin') }}" method="POST"
                    class="space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 m-2">Nama Pengguna</label>
                        <input type="text" id="username" placeholder="Nama Pengguna" name="username"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 m-2">Email</label>
                        <input type="email" id="email" placeholder="E-mail" name="email"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                    </div>
                    {{-- 
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 m-2">No.Tlp</label>
                        <input type="text" id="phone" placeholder="No. Tlp"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                    </div> --}}

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 m-2">Kata Sandi</label>
                        <input type="password" id="password" placeholder="Kata Sandi" name="password"
                            class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                    </div>

                      <input id="role" hidden class="pl-2 w-full outline-none border-none"
                        name="role" value="super_admin">

                    <!-- Checkbox -->
                    <label class="flex items-center text-sm font-medium gap-1">
                        <input type="checkbox" class="mr-2">
                        Saya menyetujui <a href="{{ url('syarat/ketentuan') }}" class="text-blue-700"> Syarat dan
                            Ketentuan </a> yang
                        berlaku
                    </label>

                    <!-- Tombol Daftar -->
                    <button type="submit" 
                        class="w-60 ml-24 bg-blue-700 text-white rounded-full font-semibold hover:bg-blue-600 py-2 mt-4">
                        Daftar
                    </button>
                </form>
            </div>

            {{-- overlay register --}}
            <div id="successModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
                <!-- Konten Modal -->
                <div class="relative bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-8 text-center animate-fadeIn">

                    <!-- Tombol X -->
                    <button onclick="closeModal()"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
                        &times;
                    </button>

                    <!-- Judul -->
                    <h2 class="text-2xl font-bold mb-3">Selamat Akun anda berhasil dibuat</h2>

                    <!-- Pesan -->
                    <p class="text-gray-700 mb-8">
                        setelah ini anda hanya perlu login <br>untuk terhubung dengan areakerja
                    </p>

                    <!-- Gambar ilustrasi -->
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('images/orang.png') }}" alt="Ilustrasi" class="w-30 h-28">
                    </div>

                    <!-- Tombol aksi -->
                    <div class="flex justify-center gap-6">
                        <button id="goLogin"
                            class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg">
                            Masuk
                        </button>

                    </div>
                </div>
            </div>
        </div>




        <!-- Bagian kanan (Gambar) -->
        <section class="relative hidden md:flex w-2/4">
            <img src="{{ asset('images/gambarkom.jpg') }}" alt="Background" class="w-full h-full object-cover">

            <!-- Logo -->
            <div class="absolute top-6 left-6 flex items-center">
                <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="Logo" class="h-12 w-12">
                <span class="font-semibold mb-1 text-white">areakerja.com</span>
            </div>

            <!-- Overlay hitam transparan -->
            <div
                class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-center text-white px-6 pb-56">
                <h2 class="text-3xl font-semibold mb-4">Admin Area Kerja</h2>
                <p class="mb-6">untuk tetap terhubung dengan kami, silakan <br> masuk dengan informasi pribadi Anda
                </p>
                <button
                    class="px-20 py-4 border border-white rounded-full hover:bg-white hover:text-black transition">MASUK</button>
            </div>
        </section>

    </div>
    {{-- @include('super_admin.auth.modal-register') --}}

 {{-- modal register admin --}}
    <script>
        document.getElementById("registerForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            let response = await fetch("{{ route('registerproses_superadmin') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: formData
            });

            let data = await response.json();

            if (data.success) {
                let modal = document.getElementById("successModal");
                modal.classList.remove("hidden");
                modal.classList.add("flex"); // ✅ bikin tampil
            } else {
                alert("Terjadi kesalahan, coba lagi.");
            }
        });

        // tombol "Masuk" → redirect ke halaman login (GET)
        document.getElementById("goLogin").addEventListener("click", function() {
            window.location.href = "{{ route('superadmin.login') }}";
        });

        // tombol X → tutup modal
        function closeModal() {
            let modal = document.getElementById("successModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    </script>

</body>

</html>
