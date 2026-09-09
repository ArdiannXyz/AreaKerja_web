 @extends('super_admin.sidebar.index')
 @section('sidebarsuperadmin')
     <div class="flex-1 p-6 sm:ml-64 bg-white overflow-x-auto">
         <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-8">
             <!-- Header -->
             <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                 <h1 class="text-2xl font-semibold text-gray-800">Edit Pengalaman Kerja</h1>
             </div>

             @if (session('success'))
                 <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                     {{ session('success') }}
                 </div>
             @endif

             <!-- Form -->
             <form action="{{ route('superadmin.kerja.update', $DK->id) }}" method="POST" class="space-y-5">
                 @csrf
                 @method('PUT')


                 <input type="hidden" name="pelamar_id" value="{{ $DK->pelamar_id }}">
                 <!-- Nama Organisasi -->
                 <div>
                     <label class="block text-sm font-semibold text-gray-900 mb-1">Nama Organisasi</label>
                     <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $DK->nama_perusahaan) }}"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                 </div>

                 <!-- Posisi_pekerjaan -->
                 <div>
                     <label class="block text-sm font-semibold text-gray-900 mb-1">Posisi Pekerjaan</label>
                     <input type="text"
                         name="posisi_pekerjaan"value="{{ old('posisi_pekerjaan', $DK->posisi_pekerjaan) }}"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                 </div>

                 <!-- Tahun Awal & Akhir -->
                 <div class="grid grid-cols-2 gap-4">
                     <div>
                         <label class="block text-sm font-semibold text-gray-900 mb-1">Tahun Awal</label>
                         <input type="text" name="tahun_awal" value="{{ old('tahun_awal', $DK->tahun_awal) }}"
                             class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                     </div>
                     <div>
                         <label class="block text-sm font-semibold text-gray-900 mb-1">Tahun Akhir</label>
                         <input type="text" name="tahun_akhir" value="{{ old('tahun_akhir', $DK->tahun_akhir) }}"
                             class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                     </div>
                 </div>

                 <!-- Deskripsi -->
                 <div>
                     <label class="block text-sm font-semibold text-gray-900 mb-1">Deskripsi</label>
                     <textarea name="deskripsi" rows="4"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ $DK->deskripsi }}</textarea>
                 </div>

                 <!-- Action -->
                 <div class="flex justify-between gap-4">
                     <button type="submit"
                         class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 shadow">
                         Simpan
                     </button>
                     @php
                         $mapKategori = [
                             'pelamar' => 'non_kandidat',
                             'calon kandidat' => 'calon_kandidat',
                             'kandidat aktif' => 'kandidat',
                         ];

                         $kategoriAsli = $DK->pelamar->kategori ?? '';
                         $kategori = $mapKategori[strtolower($kategoriAsli)] ?? 'non_kandidat';
                     @endphp

                     <a href="{{ route('superadmin.pelamar.edit', ['kategori' => $kategori, 'id' => $DK->pelamar->id]) }}"
                         class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg text-center transition duration-300">
                         Batal
                     </a>
                 </div>
             </form>
         </div>
     </div>
 @endsection
