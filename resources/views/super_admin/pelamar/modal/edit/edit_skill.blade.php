 @extends('super_admin.sidebar.index')
 @section('sidebarsuperadmin')
     <div class="flex-1 p-6 sm:ml-64 bg-white overflow-x-auto">
         <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-8">
             <!-- Header -->
             <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                 <h1 class="text-2xl font-semibold text-gray-800">Edit Skill</h1>
             </div>

             @if (session('success'))
                 <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                     {{ session('success') }}
                 </div>
             @endif

             <!-- Form -->
             <form action="{{ route('superadmin.skill.update', $DS->id) }}" method="POST" class="space-y-5">
                 @csrf
                 @method('PUT')

                 <input type="hidden" name="pelamar_id" value="{{ $DS->pelamar_id }}">
                 <!-- Skill -->
                 <div>
                     <label class="block text-sm font-semibold text-gray-700 mb-1">Skill</label>
                     <input type="text" name="skill" value="{{ old('skill', $DS->skill) }}"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                 </div>

                 <!-- Experience -->
                 <div>
                     <label class="block text-sm font-semibold text-gray-700 mb-1">Experience Level</label>
                     <input type="text"
                         name="experience_level"value="{{ old('experience_level', $DS->experience_level) }}"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
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

                         $kategoriAsli = $DS->pelamar->kategori ?? '';
                         $kategori = $mapKategori[strtolower($kategoriAsli)] ?? 'non_kandidat';
                     @endphp

                     <a href="{{ route('superadmin.pelamar.edit', ['kategori' => $kategori, 'id' => $DS->pelamar->id]) }}"
                         class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg text-center transition duration-300">
                         Batal
                     </a>
                 </div>
             </form>
         </div>
     </div>
 @endsection
