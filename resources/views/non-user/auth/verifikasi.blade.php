<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lupa Kata Sandi | AreaKerja</title>
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

        <!-- ================= PANEL KANAN (FORM VERIFIKASI EMAIL) ================= -->
        <div class="flex w-full lg:w-1/2 bg-white items-center justify-center min-h-screen py-8 sm:py-12 px-6 sm:px-12">
            <div class="w-full max-w-md flex flex-col justify-between min-h-[500px] sm:min-h-0 sm:justify-center">



                <!-- Form Container -->
                <div class="my-auto sm:my-0">
                    <!-- Judul -->
                    <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#00509d] mb-2">Verifikasi Akun</h2>
                    <p class="text-center text-slate-500 mb-8 text-xs sm:text-sm">
                        kata sandi Anda akan diatur ulang melalui email
                    </p>

                    <form action="{{ route('password.email.pelamar') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <input type="email" id="email" name="email" placeholder="E-mail"
                                value="{{ old('email') }}" required
                                class="w-full px-4 py-3.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition placeholder:text-slate-400" />

                            @error('email')
                                <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-bold py-3.5 rounded-xl shadow-md transition duration-200 text-sm tracking-normal cursor-pointer">
                                Lanjutkan
                            </button>
                        </div>

                        <div class="text-center pt-3">
                            <a href="{{ route('login') }}" class="text-xs sm:text-sm text-slate-500 hover:text-[#00509d] font-semibold transition">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Spacer on mobile -->
                <div class="h-6 sm:hidden"></div>

            </div>
        </div>

    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('alert'))
        <script>
            Swal.fire({
                title: "{{ session('alert')['title'] }}",
                text: "{{ session('alert')['text'] }}",
                icon: "{{ session('alert')['icon'] }}"
            });

            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif

</body>

</html>
