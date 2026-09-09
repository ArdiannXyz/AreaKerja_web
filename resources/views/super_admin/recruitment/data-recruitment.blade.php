@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
            <!-- Judul -->
            <h1 class="text-2xl font-medium text-gray-700 truncate sm:truncate-0 w-full sm:w-auto">
                Data Recruitment
            </h1>

            <!-- Notifikasi + Profil -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full sm:w-auto">
                <!-- Tombol Notifikasi -->
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <!-- Bagian kiri (kosong untuk sekarang, bisa diisi nanti) -->
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <!-- Kosong, placeholder -->
            </div>

            <!-- Bagian search -->
            <div class="w-full sm:w-auto">
                <form action="" method="get"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="nama/lowongan ..."
                        class="border border-gray-500 rounded-lg px-4 py-2 w-full sm:w-72 focus:outline-none">
                    <button
                        class="bg-blue-700 hover:bg-blue-800 text-white font-medium px-4 sm:px-10 py-2 rounded-xl w-full sm:w-auto">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border-2 border-gray-400">
            <table class="w-full min-w-[700px] text-left border-collapse">
                <thead class="text-center bg-blue-700 text-white">
                    <tr class="border-b-[2px] border-gray-300">
                        <th class="p-4 sm:p-7 font-medium">ID</th>
                        <th class="p-4 sm:p-7 font-medium">Nama Kandidat</th>
                        <th class="p-4 sm:p-7 font-medium">Lowongan</th>
                        <th class="p-4 sm:p-7 font-medium">Email</th>
                        <th class="p-4 sm:p-7 font-medium">Telepon</th>
                        <th class="p-4 sm:p-7 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($recruitments as $r)
                        <tr class="border-b border-gray-300">
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->id }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->nama_pelamar }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->lowonganPerusahaan->nama }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->user->email }}</td>
                            <td class="px-2 sm:px-4 py-2 break-words">{{ $r->pelamar->telepon_pelamar }}</td>
                            <td class="px-2 sm:px-4 py-2">
                                <a href="{{ route('superadmin.recruitment.detail', $r->id) }}"
                                    class="bg-blue-700 text-xs sm:text-sm text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg inline-block whitespace-nowrap">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-gray-500 text-center">Belum ada recruitment diterima</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
