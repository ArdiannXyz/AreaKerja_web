@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <div class="sm:ml-64 p-6 md:p-10 bg-white min-h-screen font-[Poppins] w-full">


        <!-- Header -->
        <h1 class="text-xl sm:text-2xl font-semibold mb-8 text-gray-800">Detail Calon Kandidat</h1>

        <!-- Kartu Kandidat -->
        <div class="max-w-4xl mx-auto bg-orange-600 rounded-2xl shadow-lg text-white p-6 sm:p-8 md:p-10">
            <div class="flex flex-col md:flex-row items-center gap-6 mb-8">
                @if ($pelamar->img_profile)
                    <img class="w-28 h-28 object-cover rounded-full shadow-md border-4 border-white"
                        src="{{ asset('storage/' . $pelamar->img_profile) }}" alt="Profile">
                @else
                    <img class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-full shadow-md border-4 border-white"
                        src="https://ui-avatars.com/api/?name={{ urlencode($pelamar->nama_pelamar) }}&background=00509d&color=fff&size=128"
                        alt="Profile">
                @endif

                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-semibold">{{ $pelamar->nama_pelamar }}</h3>
                    {{-- <p class="text-orange-100 mt-1">{{ $pelamar->divisi }}</p> --}}
                </div>
            </div>

            <!-- Form Input Tanggal -->
            <form method="POST" action="{{ route('superadmin.calon.update', $pelamar->id) }}"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                @csrf

                <div>
                    <label class="block text-sm text-orange-100 mb-1">Divisi</label>
                    <p class="text-orange-600 bg-white px-3 py-2 rounded-md">
                        {{ is_array($pelamar->divisi) ? implode(', ', $pelamar->divisi) : $pelamar->divisi ?? '-' }}
                    </p>

                </div>

                <div>
                    <label class="block text-sm text-orange-100 mb-1">Mulai Pelatihan</label>
                    <input type="date" name="mulai_pelatihan" value="{{ $pelamar->mulai_pelatihan }}"
                        class="w-full px-3 py-2 rounded-md text-black border border-gray-300 focus:ring-2 focus:ring-orange-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm text-orange-100 mb-1">Selesai Pelatihan</label>
                    <input type="date" name="selesai_pelatihan" value="{{ $pelamar->selesai_pelatihan }}"
                        class="w-full px-3 py-2 rounded-md text-black border border-gray-300 focus:ring-2 focus:ring-orange-400 focus:outline-none">
                </div>

                <div class="col-span-1 sm:col-span-2 md:col-span-3 flex justify-center mt-6">
                    <button type="submit"
                        class="bg-orange-400 text-white hover:bg-orange-500 transition duration-300 font-semibold px-6 py-2 rounded-lg shadow">
                        Simpan Tanggal
                    </button>
                </div>
            </form>
        </div>

        <!-- Tombol Aksi -->
       <div class="mt-10 flex flex-col items-center gap-4 px-4">

            <form action="{{ route('superadmin.calon.lulus', $pelamar->id) }}" method="POST" class="w-full max-w-sm">
                @csrf
                <button
                    class="bg-green-600 hover:bg-green-700 w-full text-white py-3 rounded-lg font-semibold shadow transition">
                    Lulus
                </button>
            </form>

            <form action="{{ route('superadmin.calon.gugur', $pelamar->id) }}" method="POST" class="w-full max-w-sm">
                @csrf
                <button
                    class="bg-red-600 hover:bg-red-700 w-full text-white py-3 rounded-lg font-semibold shadow transition">
                    Gugur
                </button>
            </form>
        </div>

    </div>
@endsection
