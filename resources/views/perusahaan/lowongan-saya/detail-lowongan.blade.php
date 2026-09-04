@extends('layouts.index-perusahaan')
@section('content')
    <div class="border-b pb-4">
        <div class="flex flex-row sm:flex-row items-center sm:items-center gap-4 pt-28 sm:pt-16 ml-4 sm:ml-11">

            @if (Auth::user()->perusahaan->img_profile)
                <img id="pp"
                    class="w-14 h-14 sm:w-20 sm:h-20 object-contain mt-0 sm:mt-[50px] profile-img flex-shrink-0"
                    src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
            @else
                <img id="pp"
                    class="w-14 h-14 sm:w-20 sm:h-20 object-contain mt-0 sm:mt-[50px] flex-shrink-0"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                    alt="">
            @endif

            <div class="mt-0 sm:mt-10 w-full">
                <h3 class="font-semibold text-base sm:text-xl m-0 sm:m-2 break-words">
                    {{ $data->perusahaan->nama_perusahaan }}
                </h3>

                <p class="text-sm sm:text-medium font-semibold m-0 sm:m-2 break-words">
                    {{ $data->perusahaan->jenis_perusahaan }}
                </p>

                <p class="text-xs sm:text-sm text-gray-400 mt-1 m-0 sm:m-2 break-words">
                    {{ $data->perusahaan->alamatUtama->kota->nama ?? '-' }}
                </p>
            </div>

        </div>

    </div>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <div class="max-w-7xl mx-auto p-4 sm:p-8 text-gray-800 text-sm">

        <!-- Gunakan grid agar otomatis responsif -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KIRI: Detail Job -->
            <div class="lg:col-span-2 break-words">
                <h1 class="text-xl font-bold mb-4">Lowongan</h1>
                <!-- Header Job -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">

                    <img src="{{ asset('storage/' . $data->perusahaan->img_profile) }}" alt="logo"
                        class="w-20 h-20 rounded-full flex-shrink-0">

                    <div>
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <!-- KIRI: Nama -->
                            <h2 class="font-bold text-lg break-words">
                                {{ $data->nama }}
                            </h2>

                            <!-- KANAN: Booster -->
                            @if ($isBoostActive)
                                <div
                                    class="flex items-center text-sm font-semibold text-[#003d7a] bg-blue-100 px-2 rounded-md whitespace-nowrap animate-pulse">
                                    <i class="ph ph-rocket-launch text-lg mr-1"></i>
                                    Booster Aktif
                                </div>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 break-words">{{ $data->perusahaan->nama_perusahaan }}</p>
                        <p class="text-sm mt-1 text-gray-500 break-words">{{ $data->alamat }}</p>
                        <p class="mt-1 text-gray-700 font-semibold">
                            Rp. {{ $data->gaji_awal }} - Rp. {{ $data->gaji_akhir }}
                        </p>
                    </div>
                </div>

                <!-- Aksi -->
                <div class="flex flex-wrap items-center gap-4 mb-6 ml-0 sm:ml-24">

                    <form action="{{ route('lowongan.destroy', $data->id) }}" method="post">
                        @csrf
                        @method('delete')

                        <div
                            class="flex items-center gap-2 hover:bg-blue-100 shadow bg-white px-4 py-2 rounded-md border border-[#00509d] transition-colors duration-300">
                            <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <mask id="mask0_643_6018" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                    width="23" height="23">
                                    <rect width="22.8395" height="22.8395" fill="url(#pattern0_643_6018)" />
                                </mask>
                                <g mask="url(#mask0_643_6018)">
                                    <rect width="22.8395" height="22.8395" fill="#F26419" />
                                </g>
                                <defs>
                                    <pattern id="pattern0_643_6018" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_643_6018" transform="scale(0.0104167)" />
                                    </pattern>
                                    <image id="image0_643_6018" width="96" height="96" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAABZ0lEQVR4nO3dMU7DQBBG4R+k3AVySkoKQHAb4BrcgCOQMkihcApEA3Fm8tbhfdK2q/W8xHLkIokkSZIkjeUmye7IdXvyU5+JiuEbYabK4RvhQB3DHzbCRcEeu4I9luyoGV5WnULzGABmAJgBYAaAGQBmAEmSJEk6sYr3AT+d+/uB0pn5SxhmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYAaAGQBmAJgBYB0BNg17juKjesOOAO8Ne46i/No6Arw27DmKZ/oAf7FO8pm+/wKj1jbJVeGcWj2FH1j1eiidULNVplsRPbSq9bK/pkVZJXnM9NWlBzh3bTN98hc3/O/WSe6TvGV6RKWH+tva7M96l+S6YR6SJEmS/rkvrDJThoEm4u8AAAAASUVORK5CYII=" />
                                </defs>
                            </svg>

                            <button type="submit" class="text-[#003d7a] text-md font-medium">Tutup Lowongan</button>
                        </div>
                    </form>

                    <div
                        class="flex items-center gap-2 hover:bg-blue-100 shadow bg-white px-4 py-2 rounded-md border border-[#00509d] transition-colors duration-300">
                        <svg width="24" height="23" viewBox="0 0 24 23" fill="none"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <mask id="mask0_643_6012" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                width="24" height="23">
                                <rect x="0.984375" width="22.8395" height="22.8395" fill="url(#pattern0_643_6012)" />
                            </mask>
                            <g mask="url(#mask0_643_6012)">
                                <rect x="0.984375" width="22.8395" height="22.8395" fill="#F26419" />
                            </g>
                            <defs>
                                <pattern id="pattern0_643_6012" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_643_6012" transform="scale(0.0104167)" />
                                </pattern>
                                <image id="image0_643_6012" width="96" height="96" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAABtklEQVR4nO3aMU7DMBiG4Q9YGOAM3IOJQyCuwtgsSByJBQkheoQepBNjGaBShQKJ7d/+6uZ9JI+NnPdvmrSqBAAAAAA49CBpLWkraSPpSdKldUcLspK0G1nvkq6M+1qEQePx92st6dq2uxM3FZ8hVDQ3PkOoIDU+QwiUG58hBCiNXzSE8+Lt9+8z6Di3kl7ElZDlUTFXAR9HBRjCEYi6H+y/MU/+bHERfQade5N0Juku4Fg3+u77GnCsxYn6ONq03vixGn5W6mtKB7AN2Hv3DkO2HsJH+fb7Nhaw5RDui8+gY/+FazGEVfEZdGxOsOfEY6bcmIk/c9UYAvETV+QQiJ+5IoZA/MJVMgTiB62cp6PU15yUyPi5V8Ji1YjPEGaqGZ8hTGgRnyH8oWV8hvCLI371IfTyr4hB3mftqH9OdMn5zt+JL1nEdyG+EfGNiG9EfCPiGxHfiPhGxDcivhHxjYhvRHwj4hsR34j4RsQ3Ir4R8Y2Ib0R8I+IbEd+I+EbENyK+EfGNiG9GfDPimxHfjPhmxDcjvhnxzYhvRnwz4psR34z4ZsQ3I74Z8c2IDwAAAAAAAGT4AmWLJrfB4zyeAAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>
                        <a href="{{ route('lowongan.edit.form', [
                            'perusahaan' => $data->perusahaan->slug,
                            'lowongan' => $data->slug,
                        ]) }}"
                            class="text-[#003d7a] text-md font-medium">Edit Lowongan</a>
                    </div>
                </div>

                <hr class="my-4 border-gray-300/70">

                <!-- Detail Lowongan -->
                <div class="mb-6 break-words">
                    <h3 class="text-xl font-bold mb-2">Detail Lowongan</h3>
                    <p class="mb-3 text-gray-600 break-words">{{ $data->deskripsi }}</p>

                    <div class="flex items-start gap-3">
                        <svg width="23" height="19" viewBox="0 0 23 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="mt-0.5">
                            <path
                                d="M17.2902 6.19204H19.0314V4.45084H17.2902V6.19204ZM17.2902 10.7147H19.0314V8.97345H17.2902V10.7147ZM17.2902 15.2373H19.0314V13.4961H17.2902V15.2373ZM0.0703125 18.8893V9.27873L6.85423 4.45084L13.6382 9.27873V18.8893H8.94142V13.149H4.76705V18.8893H0.0703125ZM15.8995 18.8893V8.14807L9.55084 3.59154V0.798828H22.6834V18.8893H15.8995Z"
                                fill="black" fill-opacity="0.6" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold">Jenis lowongan</p>
                            <span
                                class="inline-block bg-gray-200 text-gray-700 rounded px-3 py-1 text-xs mt-2 break-words">
                                {{ $data->jenis }}
                            </span>
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-gray-300/70">

                <!-- Lokasi -->
                <div class="mb-6 break-words">
                    <h3 class="text-xl font-bold mb-2">Lokasi</h3>

                    <div class="flex items-start gap-3 text-gray-700 break-words">
                        <svg width="14" height="21" viewBox="0 0 14 21" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.26916 10.1905C6.66942 10.1905 6.09425 9.92847 5.67017 9.46199C5.2461 8.9955 5.00785 8.36281 5.00785 7.7031C5.00785 7.0434 5.2461 6.41071 5.67017 5.94422C6.09425 5.47774 6.66942 5.21567 7.26916 5.21567C7.86889 5.21567 8.44407 5.47774 8.86814 5.94422C9.29222 6.41071 9.53047 7.0434 9.53047 7.7031C9.53047 8.02976 9.47197 8.35322 9.35833 8.65501C9.24469 8.9568 9.07812 9.23101 8.86814 9.46199C8.65816 9.69297 8.40888 9.87619 8.13452 10.0012C7.86017 10.1262 7.56612 10.1905 7.26916 10.1905ZM7.26916 0.738281C5.5899 0.738281 3.97942 1.47207 2.792 2.77823C1.60458 4.08439 0.9375 5.85592 0.9375 7.7031C0.9375 12.9267 7.26916 20.6378 7.26916 20.6378C7.26916 20.6378 13.6008 12.9267 13.6008 7.7031C13.6008 5.85592 12.9337 4.08439 11.7463 2.77823C10.5589 1.47207 8.94842 0.738281 7.26916 0.738281Z"
                                fill="black" fill-opacity="0.6" />
                        </svg>
                        <span class="break-words">{{ $data->alamat }}</span>
                    </div>
                </div>

                <hr class="my-4 border-gray-300/70">

                <!-- Requirements -->
                <div class="mb-6 break-words">
                    <h2 class="text-xl font-bold mb-2">Deskripsi Lowongan</h2>

                    <h3 class="font-medium text-sm">Requirements</h3>
                    <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1 break-words">
                        @foreach (explode("\n", $data->syarat_pekerjaan) as $req)
                            <li class="break-words">{{ $req }}</li>
                        @endforeach
                    </ul>
                </div>

                <hr class="my-4 border-gray-300/70">

                <!-- Responsibilities -->
                <div class="mb-2 break-words">
                    <h3 class="text-xl font-bold mb-2">Responsibilities</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1 break-words">
                        @foreach (preg_split("/\r\n|\n|\r/", $data->tanggung_jawab) as $res)
                            @php
                                $trim = trim($res);
                                $isNumbered = preg_match('/^\d+[\.\-\)]\s*/', $trim);
                            @endphp

                            @if ($trim !== '')
                                <li class="{{ $isNumbered ? 'list-none' : '' }} break-words">{{ $trim }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

            </div>

            <!-- KANAN: Lowongan Lainnya -->
            <aside class="w-full lg:w-96 break-words">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-xl break-words">
                        Lowongan <span class="text-[#00509d]">{{ $data->perusahaan->nama_perusahaan }}</span> Lainnya
                    </h3>
                </div>

                @forelse ($lowonganLainnya as $ll)
                    <a href="{{ route('lowongan.detail', [
                        'perusahaan' => $ll->perusahaan->slug,
                        'lowongan' => $ll->slug,
                    ]) }}"
                        class="block border shadow-md rounded-md p-3 mb-4 hover:shadow-lg hover:scale-105 transition-all duration-500">

                        <div class="flex items-start gap-3">
                            <img src="{{ asset('storage/' . $ll->perusahaan->img_profile) }}" alt="logo"
                                class="w-14 h-14 rounded-full flex-shrink-0">

                            <div class="min-w-0 break-words">
                                <p class="text-xs text-gray-600 break-words">
                                    {{ $ll->perusahaan->nama_perusahaan }}
                                </p>

                                <h4 class="font-bold text-sm truncate">{{ $ll->nama }}</h4>

                                <p class="text-xs text-gray-600 break-words">
                                    {{ $ll->alamat }}
                                </p>

                                <div class="mt-1">
                                    <span
                                        class="inline-block bg-gray-200 border rounded px-3 py-1 text-xs text-gray-700 whitespace-nowrap">
                                        Rp. {{ number_format($ll->gaji_awal, 0, ',', '.') }} - Rp.
                                        {{ number_format($ll->gaji_akhir, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <p class="ml-auto self-start text-xs text-gray-600 whitespace-nowrap">Aktif 2 jam lalu</p>
                        </div>

                    </a>
                @empty
                    <p class="text-center text-gray-500">Tidak ada lowongan lainnya</p>
                @endforelse
            </aside>

        </div>
    </div>

    @include('layouts.footer')
@endsection

