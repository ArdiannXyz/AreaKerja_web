@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
    <div class="p-6 w-full sm:ml-64 max-h-6xl">
        <div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-2">
                <h2 class="text-xl font-semibold text-gray-700">
                    Data Kandidat Perusahaan - {{ $perusahaan->user->name ?? $perusahaan->nama_perusahaan }}
                </h2>
            </div>

            {{-- FORM SEARCH --}}
            <div class="flex justify-end mb-4">
                <form method="GET" action="{{ route('superadmin.panggilan.list', $perusahaan->id) }}"
                    class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelamar..."
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-72 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all" />

                    <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white font-medium px-6 py-2 rounded-lg text-sm transition-all w-full sm:w-auto">
                        Cari
                    </button>
                </form>
            </div>

            {{-- TABEL --}}
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-blue-700">
                        <tr class="text-left text-white text-sm font-semibold">
                            <th class="px-2 sm:px-4 py-2 border-b">No</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Nama Pelamar</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Email</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Lowongan</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Tanggal Diterima</th>
                            <th class="px-2 sm:px-4 py-2 border-b">Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelamarDiterima as $i => $p)
                            <tr class="text-sm border-b hover:bg-gray-50">
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[50px]">{{ $i + 1 }}
                                </td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[150px]">
                                    {{ $p['nama'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[150px]">
                                    {{ $p['email'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[150px]">
                                    {{ $p['lowongan'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[120px]">
                                    {{ $p['tanggal_diterima'] }}</td>
                                <td class="px-2 sm:px-4 py-2 whitespace-normal break-words max-w-[100px] text-center">
                                    @if ($p['jenis'] === 'pelamar_melamar')
                                        <span class="text-green-600 font-semibold">Wawancara</span>
                                    @else
                                        <span class="text-red-600 font-semibold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-gray-500">
                                    Belum ada pekerja diterima
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>


    </div>
@endsection
