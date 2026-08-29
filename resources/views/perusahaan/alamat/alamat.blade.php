@extends('layouts.index-perusahaan')
@section('content')
    @if (!empty($alamat_perusahaan) && $alamat_perusahaan->count())
        <div class="bg-white min-h-screen p-4 sm:p-8 mt-20">
            <!-- Header -->
           <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">

                @if (Auth::user()->perusahaan->img_profile)
                    <img id="pp" class="w-20 h-20 object-contain mb-3 profile-img"
                        src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                @else
                    <img id="pp" class="w-20 h-20 object-contain mb-3"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                        alt="">
                @endif
                <div>
                    <span class="text-lg font-semibold mb-1">{{ Auth::user()->perusahaan->nama_perusahaan }}</span>
                    <p class="text-lg text-gray-600">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                    <p class="text-sm text-gray-400">{{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->kecamatan->nama ?? '-' }}</p>
                </div>
            </div>

            <!-- Garis & Judul -->
            <div class="mt-6 px-0 sm:px-12">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 text-lg">Alamat Perusahaan</h2>
                    <a href="{{ route('profile.perusahaan') }}"
                        class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white text-xs md:text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">
                        <i class="ph ph-arrow-left text-base"></i>
                        Kembali
                    </a>
                </div>
                <!-- Pesan sukses / error -->
                @if (session('success'))
                    <div class="p-3 my-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-3 my-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3 my-4 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <hr class="border border-orange-500 mt-3 mb-2"/>
                <span class="text-sm text-orange-500">Untuk Melengkapi Profile Silahkan Jadikan Alamat Utama Terlebih Dahulu</span>
            </div>

            <!-- Box Alamat -->
            <div class="px-0 sm:px-12">
                @foreach ($alamat_perusahaan as $almtp)
                  <div class="mt-6 border border-orange-400 rounded-md p-4 sm:p-6 px-6 max-w-full sm:max-w-[500px]">

                        <h3 class="font-semibold text-orange-500">{{ $almtp->label }}</h3>
                        <p class="text-orange-600 text-sm mt-1">
                            {{ $almtp->desa }}, {{ is_object($almtp->kecamatan ?? null) ? $almtp->kecamatan->nama : ($almtp->kecamatan ?? '-') }}, {{ is_object($almtp->kota ?? null) ? $almtp->kota->nama : ($almtp->kota ?? '-') }},
                            {{ is_object($almtp->provinsi ?? null) ? $almtp->provinsi->nama : ($almtp->provinsi ?? '-') }}, {{ $almtp->kode_pos }}
                        </p>
                        <p class="text-orange-500 text-sm mt-1 mb-5">
                            {{ $almtp->detail }}
                        </p>

                        <div class="flex flex-wrap items-center gap-3 mt-4">


                            <!-- Edit -->
                            <a href="{{ route('alamat.edit.perusahaan', $almtp->id) }}"
                                class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition">
                                <i class="ph ph-pencil-simple"></i>
                                Edit Alamat
                            </a>

                            <!-- Hapus -->
                            <form action="{{ route('alamat.destroy.perusahaan', $almtp->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus alamat ini?')">
                                @csrf
                                @method('DELETE')

                                <button
                                    class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition">
                                    <i class="ph ph-trash"></i>
                                    Hapus
                                </button>
                            </form>

                            <a href="{{ route('form.alamat.perusahaan') }}"
                                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition
            @if ($alamatCount >= 4) opacity-50 cursor-not-allowed pointer-events-none @endif">
                                <i class="ph ph-plus"></i>
                                Tambah
                            </a>


                        </div>

                        <!-- Set Utama -->
                        <div class="mt-4">
                            <form action="{{ route('alamat-perusahaan.setUtama', $almtp->id) }}" method="POST">
                                @csrf

                                @if ($almtp->utama)
                                    <button type="submit" class="text-sm text-red-600 hover:underline">
                                        Hapus sebagai Utama
                                    </button>
                                @else
                                    <button type="submit" class="text-sm text-blue-600 hover:underline">
                                        Jadikan Utama
                                    </button>
                                @endif

                            </form>
                        </div>

                    </div>
                @endforeach

                <!-- Bottom Kembali Button -->
                <div class="mt-8">
                    <a href="{{ route('profile.perusahaan') }}"
                        class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow-sm transition">
                        <i class="ph ph-arrow-left text-base"></i>
                        Kembali ke Profil
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white min-h-screen p-8 mt-20">
            <!-- Header -->
            <div class="flex items-center space-x-4">
                @if (Auth::user()->perusahaan->img_profile)
                    <img id="pp" class="w-20 h-20 object-contain mb-3 profile-img"
                        src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                @else
                    <img id="pp" class="w-20 h-20 object-contain mb-3"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                        alt="">
                @endif
                <div>
                    <h1 class="font-semibold text-lg text-gray-800 m-2">{{ Auth::user()->perusahaan->nama_perusahaan }}
                    </h1>
                    <p class="text-lg text-gray-600 m-2">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                    <p class="text-sm text-gray-400">{{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }},
                        {{ Auth::user()->perusahaan->alamatUtama->kecamatan->nama ?? '-' }}</p>
                </div>
            </div>

            <!-- Garis & Judul -->
            <div class="mt-6 px-0 sm:px-12">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 text-lg">Alamat Perusahaan</h2>
                    <a href="{{ route('profile.perusahaan') }}"
                        class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white text-xs md:text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">
                        <i class="ph ph-arrow-left text-base"></i>
                        Kembali
                    </a>
                </div>
                <hr class="border border-orange-500 mt-3 " />
            </div>

            <!-- Box Alamat -->
            <div class="mt-6 px-0 sm:px-12 border border-orange-400 rounded-md p-6 w-[500px]">
                <div class="flex items-center text-gray-400 space-x-2 mb-6">
                    <span class="font-medium">Alamat Kosong</span>
                    <!-- Icon dokumen -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h10M7 11h10M7 15h6M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h7l7 7v9a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('profile.perusahaan') }}"
                        class="inline-flex items-center gap-1.5 bg-gray-500 hover:bg-gray-600 text-white px-4 py-1.5 rounded-md text-sm transition">
                        <i class="ph ph-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('form.alamat.perusahaan') }}"
                        class="block w-max bg-orange-500 text-white px-4 py-1.5 rounded-md text-sm hover:bg-orange-600 transition">
                        Tambah Alamat
                    </a>
                </div>
            </div>
        </div>
    @endif
    @include('layouts.footer')
@endsection
