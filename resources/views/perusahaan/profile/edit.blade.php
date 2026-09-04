@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white w-full rounded-lg shadow p-8 mt-16">
        <!-- Judul -->
        <h2 class="text-lg font-semibold mb-6">Profil Akun</h2>

        <form action="{{ route('profile.update.perusahaan', Auth::user()->perusahaan->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Upload Logo -->
            <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center mb-10">
                <label class="text-sm font-medium mb-2">Logo Perusahaan <span class="text-red-500">*</span></label>
                <div class="col-span-2 flex items-center space-x-4">
                    <div
                        class="w-40 h-40 sm:w-56 sm:h-52 rounded-lg flex items-center justify-center overflow-hidden">
                        @if (Auth::user()->perusahaan->img_profile)
                            <img id="pa" class="w-56 h-56 object-contain mb-3 profile-img"
                                src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Profile">
                        @else
                            <img id="pa" class="w-56 h-34 object-contain mb-3"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="Profile">
                        @endif
                    </div>
                    <div class="flex flex-col sm:flex-row gap-y-6 gap-x-4">
                        <input type="file" name="img_profile" id="fileinputperusahaan" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('fileinputperusahaan').click();"
                            class="flex items-center gap-3 px-6 py-2 text-sm border rounded-md text-[#00509d] border-[#00509d] hover:bg-blue-50">
                            Upload
                        </button>
                        <button type="button"
                            onclick="event.preventDefault(); document.getElementById('removeperusahaanForm').submit();"
                            class="px-3 py-1 border border-gray-500 rounded text-sm text-gray-600 hover:bg-gray-100">
                            Remove
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Profil -->
            <div class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center">
                    <label class="text-sm font-medium">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" value="{{ Auth::user()->perusahaan->nama_perusahaan }}" name="nama_perusahaan"
                        class="pl-2 border border-gray-300 rounded-md flex w-full h-11 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <label class="w-40 font-medium">Alamat Perusahaan <span class="text-red-500">*</span></label>
                    <a href="{{ route('alamat.perusahaan') }}"
                        class="px-4 py-2 bg-[#00509d] text-white rounded-md md:ml-20 w-fit">Alamat</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center">
                    <label class="text-sm font-medium">Bidang Usaha <span class="text-red-500">*</span></label>
                    <input type="text" value="{{ Auth::user()->perusahaan->jenis_perusahaan }}" name="jenis_perusahaan"
                        name="jenis_perusahaan"
                        class="pl-2 border border-gray-300 rounded-md flex w-full h-11 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
                @if (Auth::user()->perusahaan->deskripsi)
                    <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-start">
                        <label class="text-sm font-medium mt-2">Deskripsi <span class="text-red-500">*</span></label>
                        <input type="text" value="{{ Auth::user()->perusahaan->deskripsi }}" name="deskripsi"
                            class="pl-2 col-span-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-start">
                        <label class="text-sm font-medium mt-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi"
                            class="pl-2 col-span-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00509d]"></textarea>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center">
                    <label class="text-sm font-medium">Visi<span class="text-red-500 ml-1">*</span></label>
                    <input type="text" value="{{ Auth::user()->perusahaan->visi }}" name="visi"
                        class="pl-2 col-span-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-start">
                    <label class="text-sm font-medium mt-2">Misi<span class="text-red-500 ml-1">*</span></label>
                    <input type="text" value="{{ Auth::user()->perusahaan->misi }}" name="misi"
                        class="pl-2 col-span-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
            </div>
            <hr class="border border-gray-200 mt-10">
            <!-- Kontak -->
            <h2 class="text-lg font-semibold mt-8 mb-6">Kontak</h2>
            <div class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center">
                    <label class="text-sm font-medium">Website</label>
                    <input type="text" value="{{ Auth::user()->perusahaan->website_perusahaan }}"
                        name="website_perusahaan"
                        class="pl-2 border border-gray-300 rounded-md flex w-full h-11 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center">
                    <label class="text-sm font-medium">Telepon</label>
                    <input type="text" value="{{ Auth::user()->perusahaan->telepon_perusahaan }}"
                        name="telepon_perusahaan"
                        class="pl-2 border border-gray-300 rounded-md flex w-full h-11 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center">
                    <label class="text-sm font-medium">Whatsapp</label>
                    <input type="text" value="{{ Auth::user()->perusahaan->whatsapp }}" name="whatsapp"
                        class="pl-2 border border-gray-300 rounded-md flex w-full h-11 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3gap-4 items-center">
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" value="{{ Auth::user()->email }}"
                        class="pl-2 border border-gray-300 rounded-md flex w-full h-11 focus:outline-none focus:ring-1 focus:ring-[#00509d]">
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-center gap-3 mt-8">
                <a href="/perusahaan/profile"
                    class="px-6 py-2 border rounded-md text-[#003d7a] border-[#003d7a]">Batal</a>
                <button type="submit"
                    class="px-6 py-2 bg-[#00509d] text-white rounded-md hover:bg-[#003d7a]">Simpan</button>
            </div>
        </form>
        <form id="removeperusahaanForm" action="{{ route('profile.destroy.perusahaan', Auth::user()->perusahaan->id) }}"
            method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
    <script>
        document.getElementById('fileinputperusahaan').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('pu').setAttribute('src', event.target.result);
                    document.getElementById('pa').setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    @include('layouts.footer')
@endsection

