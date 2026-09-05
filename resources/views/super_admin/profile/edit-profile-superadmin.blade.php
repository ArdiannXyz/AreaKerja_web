@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-medium truncate">Profile</h1>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                <!-- Tombol Notifikasi -->
                <button @click="openNotif = true" class="relative flex-shrink-0">
                    <!-- Icon Lonceng -->
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_722_7956)">
                            <path
                                d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                fill="black" />
                        </g>
                    </svg>

                    <!-- Badge jumlah notif belum dibaca -->
                    @if ($global_notifikasi_unread > 0)
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $global_notifikasi_unread }}
                        </span>
                    @endif
                </button>

                <!-- Profile Box -->
                <div
                    class="flex items-center gap-2 bg-white px-3 py-2 border border-gray-500 shadow-md rounded-2xl flex-1 min-w-0">
                    <a href="#" class="flex-shrink-0">
                        @if (Auth::user()->role == 'super_admin')
                            @if (Auth::user()->superadmin->img_profile)
                                <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                    src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                            @else
                                <img id="pu" class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="">
                            @endif
                        @else
                            <img class="w-10 h-10 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                alt="">
                        @endif
                    </a>
                    <div class="text-sm min-w-0">
                        <span class="font-semibold truncate">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="max-w-5xl mx-auto bg-white border border-gray-400 rounded-lg p-6 shadow-sm">
            <!-- Header -->
            <h2 class="text-lg font-semibold mb-6 truncate">Edit Profile</h2>

            <!-- Form -->
            <form action="{{ route('superadmin.update.profile', Auth::user()->superadmin->id) }}"
                class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Info -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6 w-full">
                    @if (Auth::user()->superadmin->img_profile)
                        <img id="pa" class="w-24 h-24 object-cover rounded-full flex-shrink-0"
                            src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
                    @else
                        <img id="pa" class="w-24 h-24 object-cover rounded-full flex-shrink-0"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                            alt="Profile">
                    @endif
                    <div class="flex-1 min-w-0">
                        <span class="font-semibold truncate">{{ Auth::user()->username }}</span>
                        <p class="text-gray-500 text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="flex flex-col gap-2 sm:gap-3">
                        <input type="file" name="img_profile" id="fileinputsuperadmin" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('fileinputsuperadmin').click();"
                            class="flex items-center gap-2 px-4 py-2 text-sm border-2 border-orange-500 bg-orange-500 text-white rounded-md flex-shrink-0">
                            <!-- Icon upload -->
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="..." fill="currentColor" />
                            </svg>
                            Upload
                        </button>
                        <button type="button"
                            onclick="event.preventDefault(); document.getElementById('removeSuperadminForm').submit();"
                            class="flex items-center gap-2 px-4 py-2 text-sm border-2 border-orange-500 text-orange-600 rounded-md hover:bg-gray-100 flex-shrink-0">
                            <!-- Icon trash -->
                            <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="..." fill="currentColor" />
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md border border-slate-200 flex items-center gap-1">
                            <i class="ph ph-lock-key"></i> Terverifikasi & Terkunci
                        </span>
                    </div>
                    <input type="text" value="{{ Auth::user()->email }}" disabled readonly
                        class="w-full border text-slate-500 bg-slate-100 border-slate-300 shadow-xs rounded-md px-3 py-2 cursor-not-allowed select-none truncate">
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" value="{{ Auth::user()->username }}" name="username" required
                        class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                </div>

                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" value="{{ Auth::user()->superadmin->nama_lengkap }}" name="nama_lengkap"
                        class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                </div>

                <!-- Provinsi, Kota, Kecamatan -->
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Provinsi <span class="text-red-500">*</span></label>
                        <input type="text" name="provinsi" value="{{ Auth::user()->superadmin->provinsi ?? '' }}"
                            placeholder="Masukkan Provinsi"
                            class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kota/Kabupaten <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="kota" value="{{ Auth::user()->superadmin->kota ?? '' }}"
                            placeholder="Masukkan Kota/Kabupaten"
                            class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kecamatan <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="kecamatan" value="{{ Auth::user()->superadmin->kecamatan ?? '' }}"
                            placeholder="Masukkan Kecamatan"
                            class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                    </div>
                </div>

                <!-- Desa dan Kode Pos -->
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Desa <span class="text-red-500">*</span></label>
                        <input type="text" value="{{ Auth::user()->superadmin->desa }}" name="desa"
                            class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kode Pos <span class="text-red-500">*</span></label>
                        <input type="text" value="{{ Auth::user()->superadmin->kode_pos }}" name="kode_pos"
                            class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                    </div>
                </div>

                <!-- Detail Lainnya -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Detail Lainnya <span
                            class="text-red-500">*</span></label>
                    <input type="text" value="{{ Auth::user()->superadmin->detail_alamat }}" name="detail_alamat"
                        class="w-full border text-black border-gray-300 shadow rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:outline-none truncate">
                </div>

                <!-- Button -->
                <div class="md:col-span-2 flex flex-col sm:flex-row justify-center items-center gap-3 mt-4 w-full">
                    <button type="submit"
                        class="bg-orange-600 text-white font-medium px-10 py-2 rounded-md hover:bg-orange-500 border border-orange-600 transition w-full sm:w-auto">
                        Simpan
                    </button>
                    <a href="{{ route('superadmin.profile') }}"
                        class="bg-white text-orange-600 font-medium px-12 py-2 rounded-md hover:bg-orange-100 border border-orange-600 transition w-full sm:w-auto text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
        <form id="removeSuperadminForm" action="{{ route('superadmin.destroy.profile', Auth::user()->superadmin->id) }}"
            method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection