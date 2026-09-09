@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <div class="w-full sm:ml-64 mb-12">
        <div class="bg-white">
            <div class="w-full max-w-4xl mx-auto mt-10 px-4 sm:px-6 overflow-y-auto">

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 mb-8 flex-wrap">
                    <h1 class="text-xl font-semibold text-gray-800 break-words">
                        Edit Talent Hunter
                    </h1>
                </div>
                <div
                    class="w-full max-w-4xl mx-auto mt-10 px-4 sm:px-6 bg-white shadow-md rounded-lg border border-gray-400">
                    <div class="mb-6 pb-5 border-b border-gray-200">
                        <h1 class="text-lg font-semibold text-gray-800 break-words">
                            Edit Data Talent Hunter
                        </h1>
                    </div>

                    <form action="{{ route('superadmin.talent-hunter.update', $talentHunter->id) }}" method="POST"
                        class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Nama Perusahaan (disable) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 break-words">Nama Perusahaan</label>
                            <input type="text" value="{{ $perusahaan->nama_perusahaan }}" disabled
                                class="w-full border border-gray-400 rounded-md px-3 py-2 mt-1 bg-gray-100 text-gray-600 break-words" />
                        </div>

                        <!-- Email (disable) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 break-words">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full border border-gray-400 rounded-md px-3 py-2 mt-1 bg-gray-100 text-gray-600 break-words" />
                        </div>

                        <!-- No Telepon (disable) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 break-words">No. Telepon</label>
                            <input type="text" value="{{ $perusahaan->telepon_perusahaan }}" disabled
                                class="w-full border border-gray-400 rounded-md px-3 py-2 mt-1 bg-gray-100 text-gray-600 break-words" />
                        </div>

                        <!-- Deskripsi Perusahaan (disable) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 break-words">Deskripsi
                                Perusahaan</label>
                            <textarea disabled class="w-full border border-gray-400 rounded-md px-3 py-2 mt-1 bg-gray-100 text-gray-600 break-words"
                                rows="3">{{ $perusahaan->deskripsi }}</textarea>
                        </div>

                        <hr class="my-6 border-gray-300">

                        <!-- Alamat (editable) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 break-words">Alamat</label>
                            <input type="text" name="alamat" value="{{ old('alamat', $talentHunter->alamat) }}"
                                class="w-full border border-gray-400 rounded-md px-3 py-2 mt-1 break-words" />
                        </div>

                        <!-- Posisi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 break-words">Posisi yang
                                Dibutuhkan</label>
                            <input type="text" name="posisi" value="{{ old('posisi', $talentHunter->posisi) }}"
                                class="w-full border border-gray-400 rounded-md px-3 py-2 mt-1 break-words" />
                        </div>


                        <!-- Pengalaman Kerja -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 break-words">Pengalaman Kerja</label>
                            <input type="text" name="pengalaman_kerja"
                                value="{{ old('pengalaman_kerja', $talentHunter->pengalaman_kerja) }}"
                                class="w-full border border-gray-400 rounded-md px-3 py-2 mt-1 break-words" />
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="gender" value="Laki-laki"
                                        {{ $talentHunter->gender == 'Laki-laki' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-700 border-2 border-blue-600 focus:ring-blue-700" />
                                    <span class="text-sm font-semibold text-gray-700 break-words">Laki-laki</span>
                                </label>

                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="gender" value="Perempuan"
                                        {{ $talentHunter->gender == 'Perempuan' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-700 border-2 border-blue-600 focus:ring-blue-700" />
                                    <span class="text-sm font-semibold text-gray-700 break-words">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Gaji -->
                        <div class="mb-4">
                            <label class="block font-semibold text-gray-800 text-sm mb-1 break-words">Gaji</label>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 space-y-2 sm:space-y-0">
                                <input type="number" name="gaji_awal"
                                    value="{{ old('gaji_awal', $talentHunter->gaji_awal) }}"
                                    class="w-full sm:w-40 border border-gray-400 rounded-md px-3 py-2 focus:outline-none"
                                    placeholder="Min" />
                                <span class="text-gray-500 sm:hidden">sampai</span>
                                <span class="hidden sm:inline text-gray-500">-</span>
                                <input type="number" name="gaji_akhir"
                                    value="{{ old('gaji_akhir', $talentHunter->gaji_akhir) }}"
                                    class="w-full sm:w-40 border border-gray-400 rounded-md px-3 py-2 focus:outline-none"
                                    placeholder="Max" />
                            </div>
                        </div>

                        <!-- Deskripsi Talent Hunter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 break-words">Deskripsi Talent
                                Hunter</label>
                            <textarea name="deskripsi" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 resize-none" rows="3">{{ old('deskripsi', $talentHunter->deskripsi) }}</textarea>
                        </div>

                        <!-- Tombol -->
                        <div class="flex flex-col sm:flex-row justify-center sm:space-x-4 space-y-2 sm:space-y-0 mt-6">
                            <button type="submit"
                                class="w-full sm:w-auto bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm px-8 py-2 rounded-md shadow-sm transition duration-150">
                                Simpan
                            </button>

                            <a href="{{ route('superadmin.talent-hunter.detail', $talentHunter->id) }}"
                                class="w-full sm:w-auto border border-blue-700 text-blue-700 hover:bg-gray-100 font-medium text-sm px-9 py-2 rounded-md transition duration-150 text-center">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
