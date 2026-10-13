<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenis Akun | AreaKerja</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_area_kerja_biru.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50/40 to-slate-100 min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <!-- Header Profile Banner -->
        <div class="bg-[#00509d] p-6 sm:p-8 text-white text-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-40 h-40 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center">
                @if(!empty($tempUser['avatar']))
                    <img src="{{ $tempUser['avatar'] }}" alt="{{ $tempUser['name'] }}"
                        class="w-20 h-20 rounded-full border-4 border-white/90 shadow-md object-cover mb-3" />
                @else
                    <div class="w-20 h-20 rounded-full bg-white/20 border-4 border-white/90 flex items-center justify-center text-3xl font-bold mb-3 shadow-md">
                        {{ strtoupper(substr($tempUser['name'] ?? 'U', 0, 1)) }}
                    </div>
                @endif

                <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Selamat Datang, {{ $tempUser['name'] }}!</h1>
                <p class="text-xs sm:text-sm text-blue-100 mt-1 flex items-center gap-1.5 bg-white/10 px-3 py-1 rounded-full">
                    <i class="ph ph-envelope-simple text-sm"></i> {{ $tempUser['email'] }}
                </p>
            </div>
        </div>

        <!-- Role Selection Content -->
        <div class="p-6 sm:p-10">
            <div class="text-center mb-8">
                <h2 class="text-lg sm:text-xl font-bold text-slate-800">Pilih Jenis Akun Anda</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Akun {{ ucfirst($tempUser['provider'] ?? 'Sosial') }} Anda belum terdaftar di AreaKerja. Tentukan peran Anda untuk melanjutkan.
                </p>
            </div>

            <!-- Pilihan Role -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kartu Pelamar -->
                <a href="{{ route('social.simpan-role', ['role' => 'pelamar']) }}"
                    class="group relative flex flex-col text-left p-6 rounded-2xl border-2 border-slate-200 hover:border-[#00509d] hover:bg-blue-50/50 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-[#00509d] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-[#00509d] group-hover:text-white transition duration-300">
                        <i class="ph ph-user"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 group-hover:text-[#00509d] transition">Pencari Kerja (Pelamar)</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Saya ingin mencari lowongan kerja, melamar ke berbagai perusahaan, dan mengelola portofolio karier saya.
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2 text-xs font-semibold text-[#00509d]">
                        <span>Daftar sebagai Pelamar</span>
                        <i class="ph ph-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </a>

                <!-- Kartu Perusahaan -->
                <a href="{{ route('social.simpan-role', ['role' => 'perusahaan']) }}"
                    class="group relative flex flex-col text-left p-6 rounded-2xl border-2 border-slate-200 hover:border-[#00509d] hover:bg-blue-50/50 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                        <i class="ph ph-buildings"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 group-hover:text-indigo-700 transition">Perusahaan (Perekrut)</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Saya ingin mempublikasikan lowongan kerja, mencari talenta kandidat terbaik, dan merekrut calon karyawan.
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2 text-xs font-semibold text-indigo-700">
                        <span>Daftar sebagai Perusahaan</span>
                        <i class="ph ph-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </a>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-slate-600 transition">
                    ← Batalkan dan kembali ke halaman Masuk
                </a>
            </div>
        </div>
    </div>

</body>

</html>
