@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white flex flex-col lg:flex-row items-start mb-36 mt-32 px-4 sm:px-8">

        <div class="w-full max-w-3xl px-8 pl-12 mb-12">
            <!-- Logo + Info Perusahaan -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-12">

                @if (Auth::user()->perusahaan->img_profile)
                    <img id="pp" class="w-32 h-32 object-contain mb-3 profile-img"
                        src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                @else
                    <img id="pp" class="w-32 h-32 object-contain mb-3"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                        alt="">
                @endif
                <div>
                    <h1 class="font-semibold text-lg">{{ Auth::user()->perusahaan->nama_perusahaan }}</h1>
                    <p class="text-gray-700">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                    <p class="text-gray-500 text-sm">Jakarta Timur, DKI Jakarta, Indonesia</p>
                </div>
            </div>

          

            <!-- Pesan sukses / error -->
            @if (session('success'))
                <div class="p-3 mb-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-3 mb-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3 mb-4 bg-red-100 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="mt-2 space-y-8">
                <!-- Ganti Password -->
                <button onclick="document.getElementById('passwordForm').classList.toggle('hidden')"
                    class="w-full bg-[#00509d] hover:bg-[#003d7a] text-white py-3 rounded-lg text-left px-5">
                    Ganti Password
                </button>
                <!-- Form Ganti Password (hidden default) -->
                <form id="passwordForm" action="{{ route('password.update') }}" method="POST"
                    class="hidden mt-4 space-y-4 bg-gray-50 p-6 border-2 border-gray-400 rounded-lg shadow">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium">Kata Sandi Lama</label>
                        <input type="password" name="old_password" required
                            class="mt-1 w-full border px-3 py-2 rounded focus:ring-2 focus:ring-[#00509d]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Kata Sandi Baru</label>
                        <input type="password" name="new_password" required
                            class="mt-1 w-full border px-3 py-2 rounded focus:ring-2 focus:ring-[#00509d]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" required
                            class="mt-1 w-full border px-3 py-2 rounded focus:ring-2 focus:ring-[#00509d]">
                    </div>

                    <button type="submit"
                        class="w-full bg-[#003d7a] hover:bg-[#003d7a] text-white py-2 rounded-lg font-medium">
                        Simpan Password
                    </button>
                </form>

                <!-- Ganti Email -->
                <a href="{{ route('email.ubah') }}"
                    class="block w-full bg-[#00509d] hover:bg-[#003d7a] text-white py-3 rounded-lg text-left px-5">
                    Ganti Email
                </a>

            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection

