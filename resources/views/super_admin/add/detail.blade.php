@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-white overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="w-full max-w-4xl p-4 sm:p-6 rounded-lg border-2 border-gray-400 shadow-sm bg-white">
                <h2 class="text-center text-xl font-semibold mb-6 break-words">View Profile</h2>

                {{-- Foto Profil --}}
                <div class="flex justify-center mb-6">
                    @php
                        $img = null;
                        if ($user->role === 'admin') {
                            $img = $user->admin?->img_profile;
                        } elseif ($user->role === 'finance') {
                            $img = $user->finance?->img_profile;
                        } elseif ($user->role === 'perusahaan') {
                            $img = $user->perusahaan?->img_profile;
                        } elseif ($user->role === 'pelamar') {
                            $img = $user->pelamar?->img_profile;
                        }
                    @endphp

                    @if ($img)
                        <img src="{{ asset('storage/' . $img) }}" class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=00509d&color=fff&size=128"
                            class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover">
                    @endif
                </div>

                {{-- Data Profil --}}
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">ID User</label>
                            <input type="text"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                value="{{ $user->id }}" disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="text"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                value="{{ $user->email }}" disabled>
                        </div>
                    </div>

                    {{-- Nama / Nama Perusahaan --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Lengkap / Perusahaan</label>
                        <input type="text"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                            value="@switch($user->role)
                            @case('admin') {{ $user->admin?->nama_lengkap }} @break
                            @case('finance') {{ $user->finance?->nama_lengkap }} @break
                            @case('perusahaan') {{ $user->perusahaan?->nama_perusahaan }} @break
                            @case('pelamar') {{ $user->pelamar?->nama_pelamar }} @break
                        @endswitch"
                            disabled>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Role</label>
                        <input type="text"
                            class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 capitalize break-words"
                            value="{{ $user->role }}" disabled>
                    </div>

                    {{-- Alamat Admin / Finance --}}
                    @if (in_array($user->role, ['admin', 'finance']))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Provinsi</label>
                                <input type="text"
                                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                    value="{{ $user->{$user->role}?->provinsi->nama ?? 'Data Belum Diisi' }}" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kota</label>
                                <input type="text"
                                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                    value="{{ $user->{$user->role}?->kota->nama ?? 'Data Belum Diisi' }}" disabled>
                            </div>
                        </div>
                    @endif

                    {{-- Detail Perusahaan --}}
                    @if ($user->role === 'perusahaan')
                        <div>
                            <label class="block text-sm font-medium mb-1">Jenis Perusahaan</label>
                            <input type="text"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                value="{{ $user->perusahaan?->jenis_perusahaan }}" disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Website</label>
                            <input type="text"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                value="{{ $user->perusahaan?->website_perusahaan }}" disabled>
                        </div>
                    @endif

                    {{-- Detail Pelamar --}}
                    @if ($user->role === 'pelamar')
                        <div>
                            <label class="block text-sm font-medium mb-1">Deskripsi Diri</label>
                            <textarea rows="2" class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words" disabled>{{ old('deskripsi_diri', $user->pelamar?->deskripsi_diri ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
                            <input type="date" class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100"
                                value="{{ old('tanggal_lahir', $user->pelamar?->tanggal_lahir ?? '') }}" disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">No Telepon Pelamar</label>
                            <input type="text"
                                class="w-full border-2 border-gray-400 rounded-md px-3 py-2 bg-gray-100 break-words"
                                value="{{ old('telepon_pelamar', $user->pelamar?->telepon_pelamar ?? '') }}" disabled>
                        </div>
                    @endif

                    {{-- Tombol --}}
                    <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 pt-4">
                        <a href="{{ route('superadmin.edit.user', $user->id) }}"
                            class="bg-orange-500 hover:bg-orange-700 text-white px-8 py-2 rounded-full text-center">
                            Edit
                        </a>
                        <a href="{{ route('superadmin.add.user') }}"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2 rounded-full text-center">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
