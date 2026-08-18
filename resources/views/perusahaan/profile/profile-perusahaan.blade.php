@extends('layouts.index-perusahaan')
@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <div class="bg-white p-6 font-medium mt-24">

        <!-- Header -->
        <div class="flex items-start space-x-4 flex-col sm:flex-row">

            <!-- Logo -->
            @if (Auth::user()->perusahaan->img_profile)
                <img id="pu" class="w-20 h-20 object-contain mb-3 profile-img"
                    src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
            @else
                <img id="pu" class="w-20 h-20 object-contain mb-3"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                    alt="">
            @endif
            <!-- Info Perusahaan -->
            <div>
                <span
                    class="text-xl font-bold mb-4">{{ Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username }}</span>
                <p class="text-sm font-semibold mb-1">{{ Auth::user()->perusahaan->jenis_perusahaan }}</p>
                <p class="text-xs text-gray-400 mb-4">{{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? '-' }},
                    {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? '-' }},
                    {{ Auth::user()->perusahaan->alamatUtama->kecamatan->nama ?? '-' }}</p>
                <a href="{{ route('profile.edit.perusahaan') }}"
                    class="px-4 py-1 rounded-md border border-orange-400 text-orange-500 text-sm">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Deskripsi -->
        @if (Auth::user()->perusahaan->deskripsi)
            <div class="mt-6">
                <div class="flex flex-col sm:flex-row items-start">

                    <label class="w-32 text-sm mt-2 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" readonly
                        class="auto-grow flex-1 border border-orange-400 rounded-md p-2 focus:outline-none resize-none overflow-hidden text-gray-800 text-sm">{{ Auth::user()->perusahaan->deskripsi }}</textarea>
                </div>
            </div>
        @else
            <div class="mt-6">
                <div class="flex flex-col sm:flex-row items-start">

                    <label class="w-32 text-sm mt-2 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" readonly
                        class="auto-grow flex-1 border border-orange-400 rounded-md p-2 focus:outline-none resize-none overflow-hidden text-gray-800 text-sm"></textarea>
                </div>
            </div>
        @endif

        <!-- Grid Form & Kontak -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">


            <!-- Kolom Kiri (span 2 kolom) -->
            <div class="col-span-2 space-y-4">
                <!-- Badan Usaha -->
                <div class="flex flex-col sm:flex-row items-start mt-4">
                    <label class="text-sm mb-1 sm:mb-0 sm:w-32">Badan Usaha</label>
                    <input type="text" name="jenis_perusahaan" readonly
                        value="{{ Auth::user()->perusahaan->jenis_perusahaan }}"
                        class="w-full sm:flex-1 border border-orange-400 rounded-md px-4 py-4 focus:outline-none text-gray-800 text-sm">
                </div>


                <!-- Visi -->
                <div class="flex flex-col sm:flex-row items-start mt-4">
                    <label class="text-sm mb-1 sm:mb-0 sm:w-32">Visi</label>
                    <textarea name="visi" readonly
                        class="auto-grow w-full sm:flex-1 border border-orange-400 rounded-md p-2 focus:outline-none resize-none text-gray-800 text-sm">{{ Auth::user()->perusahaan->visi }}</textarea>
                </div>


                <!-- Misi -->
                <div class="flex flex-col sm:flex-row items-start mt-4">
                    <label class="text-sm mb-1 sm:mb-0 sm:w-32">Misi</label>
                    <textarea name="misi" readonly
                        class="auto-grow w-full sm:flex-1 border border-orange-400 rounded-md p-2 focus:outline-none resize-none text-gray-800 text-sm">{{ Auth::user()->perusahaan->misi }}</textarea>
                </div>

            </div>


            <!-- Kolom Kanan (Kontak) -->
            <div class="border border-orange-400 rounded-xl p-5 bg-white shadow-sm self-start min-h-[250px]">
                <h2 class="font-semibold text-lg mb-4 flex items-center gap-2 text-orange-600">
                    Kontak
                </h2>

                <ul class="space-y-3 text-sm">

                    <!-- Website -->
                    <li class="flex flex-col sm:flex-row">

                        <span class="font-medium w-24 text-gray-700">Website</span>
                        <span class="text-gray-800">
                            :
                            <a href="{{ Auth::user()->perusahaan->website_perusahaan }}"
                                class="text-blue-600 hover:underline break-all">
                                {{ Auth::user()->perusahaan->website_perusahaan }}
                            </a>
                        </span>
                    </li>

                    <!-- Telepon -->
                    <li class="flex flex-col sm:flex-row">

                        <span class="font-medium w-24 text-gray-700">Telepon</span>
                        <span class="text-gray-800">: {{ Auth::user()->perusahaan->telepon_perusahaan }}</span>
                    </li>

                    <!-- Whatsapp -->
                    <li class="flex flex-col sm:flex-row">

                        <span class="font-medium w-24 text-gray-700">Whatsapp</span>
                        <span class="text-gray-800">: {{ Auth::user()->perusahaan->whatsapp }}</span>
                    </li>

                    <!-- Email -->
                    <li class="flex flex-col sm:flex-row">

                        <span class="font-medium w-24 text-gray-700">Email</span>
                        <span class="text-gray-800 break-all">: {{ Auth::user()->email }}</span>
                    </li>

                </ul>
            </div>

        </div>
    </div>


    <script>
        function autoGrow(el) {
            el.style.height = "auto";
            el.style.height = el.scrollHeight + "px";
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".auto-grow").forEach((el) => {
                autoGrow(el);
                el.addEventListener("input", () => autoGrow(el));
            });
        });
    </script>

    @include('layouts.footer')
@endsection
