<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Email</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoarea.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-[Poppins] bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-gray-200 scale-[0.85] md:scale-100">
            {{-- Judul --}}
            <div class="flex flex-col items-center justify-center mb-6 gap-y-3">
                <div class="bg-blue-100 text-[#00509d] p-3 rounded-full shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2
                            2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="ml-3 text-2xl font-bold text-gray-700">Ganti Email</h2>
            </div>

            {{-- Alert sukses --}}
            @if (session('success'))
                <div
                    class="mb-4 flex items-center gap-2 p-3 text-green-800 rounded-lg bg-green-100 border border-green-300 text-sm">
                    âœ… {{ session('success') }}
                </div>
            @endif

            {{-- Alert error --}}
            @if ($errors->any())
                <div
                    class="mb-4 flex items-center gap-2 p-3 text-red-800 rounded-lg bg-red-100 border border-red-300 text-sm">
                    âš ï¸ {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('email.send.verification') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Kalau belum login, tampilkan input email lama --}}
                @guest
                    <div>
                        <label for="old_email" class="block text-sm font-medium text-gray-700 mb-1">Email Lama</label>
                        <input type="email" name="old_email" id="old_email" value="{{ old('old_email') }}"
                            placeholder="Masukkan email lama Anda"
                            class="w-full border-2 border-gray-400 rounded-lg px-4 py-2.5 text-sm transition shadow-sm"
                            required>
                    </div>
                @endguest

                <div>
                    <label for="new_email" class="block text-sm font-medium text-gray-700 mb-1">Email Baru</label>
                    <input type="email" name="new_email" id="new_email" value="{{ old('new_email') }}"
                        placeholder="Masukkan email baru Anda"
                        class="w-full border-2 border-gray-400 rounded-lg px-4 py-2.5 text-sm transition shadow-sm"
                        required>
                </div>

                <button type="submit"
                    class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold py-2.5 rounded-lg shadow-md transition transform hover:scale-[1.02]">
                    Kirim Link Verifikasi
                </button>
            </form>
        </div>
    </div>
</body>

</html>

