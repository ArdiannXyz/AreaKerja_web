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
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <h2 class="font-semibold text-gray-800 text-lg">Alamat Perusahaan</h2>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-orange-100 text-orange-700 rounded-full">
                            {{ $alamatCount }}/5 Alamat
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($alamatCount < 5)
                            <a href="{{ route('form.alamat.perusahaan') }}"
                                class="inline-flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white text-xs md:text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">
                                <i class="ph ph-plus-circle text-base"></i>
                                Tambah Alamat
                            </a>
                        @endif
                        <a href="{{ route('profile.perusahaan') }}"
                            class="inline-flex items-center gap-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs md:text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">
                            <i class="ph ph-arrow-left text-base"></i>
                            Kembali
                        </a>
                    </div>
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
                @php
                    $hasUtama = $alamat_perusahaan->where('utama', true)->isNotEmpty();
                @endphp
                @if (!$hasUtama)
                    <span class="text-sm text-orange-500 font-medium">Untuk Melengkapi Profile Silahkan Jadikan Salah Satu Alamat Sebagai Alamat Utama</span>
                @endif
            </div>

            <!-- Box Alamat -->
            <div class="px-0 sm:px-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 max-w-5xl">
                    @foreach ($alamat_perusahaan as $almtp)
                      <div class="border {{ $almtp->utama ? 'border-orange-500 bg-orange-50/20' : 'border-gray-300 bg-white' }} rounded-xl p-4 sm:p-6 shadow-xs relative flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-bold text-base {{ $almtp->utama ? 'text-[#ff7a00]' : 'text-gray-800' }}">{{ $almtp->label }}</h3>
                                    @if ($almtp->utama)
                                        <span class="bg-orange-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full shadow-xs">Utama</span>
                                    @endif
                                </div>

                                <p class="text-gray-700 text-sm mt-2">
                                    {{ $almtp->desa }}, {{ is_object($almtp->kecamatan ?? null) ? $almtp->kecamatan->nama : ($almtp->kecamatan ?? '-') }}, {{ is_object($almtp->kota ?? null) ? $almtp->kota->nama : ($almtp->kota ?? '-') }},
                                    {{ is_object($almtp->provinsi ?? null) ? $almtp->provinsi->nama : ($almtp->provinsi ?? '-') }}, {{ $almtp->kode_pos }}
                                </p>
                                <p class="text-gray-500 text-sm mt-1 mb-4">
                                    {{ $almtp->detail }}
                                </p>
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-gray-100">
                                    <!-- Edit -->
                                    <a href="{{ route('alamat.edit.perusahaan', $almtp->id) }}"
                                        class="flex items-center gap-1.5 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-xs transition">
                                        <i class="ph ph-pencil-simple text-sm"></i>
                                        Edit Alamat
                                    </a>

                                    <!-- Hapus -->
                                    <form action="{{ route('alamat.destroy.perusahaan', $almtp->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="flex items-center gap-1.5 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-xs transition">
                                            <i class="ph ph-trash text-sm"></i>
                                            Hapus
                                        </button>
                                    </form>

                                    @if ($alamatCount < 5)
                                        <a href="{{ route('form.alamat.perusahaan') }}"
                                            class="flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-xs transition">
                                            <i class="ph ph-plus text-sm"></i>
                                            Tambah
                                        </a>
                                    @endif
                                </div>

                                <!-- Set Utama -->
                                <div class="mt-3">
                                    <form action="{{ route('alamat-perusahaan.setUtama', $almtp->id) }}" method="POST">
                                        @csrf

                                        @if ($almtp->utama)
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-bold hover:underline inline-flex items-center gap-1">
                                                <i class="ph ph-x-circle text-sm"></i>
                                                Hapus sebagai Utama
                                            </button>
                                        @else
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-700 font-bold hover:underline inline-flex items-center gap-1">
                                                <i class="ph ph-check-circle text-sm"></i>
                                                Jadikan Utama
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endforeach

                    @if ($alamatCount < 5)
                        <!-- Card Tambah Alamat Baru -->
                        <a href="{{ route('form.alamat.perusahaan') }}" class="border-2 border-dashed border-slate-300 hover:border-orange-500 bg-slate-50/50 hover:bg-orange-50/20 rounded-xl p-6 flex flex-col items-center justify-center text-center group transition min-h-[180px]">
                            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                                <i class="ph ph-plus text-xl font-bold"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-800 group-hover:text-orange-600 transition">Tambah Alamat Baru</span>
                            <span class="text-xs text-slate-500 mt-0.5">Tersisa {{ 5 - $alamatCount }} slot alamat</span>
                        </a>
                    @endif
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
