
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-8">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Riwayat Pendidikan</h1>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('superadmin.pendidikan.update', $DT->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Pendidikan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pendidikan</label>
                    <input type="text" name="pendidikan" value="{{ old('pendidikan', $DT->pendidikan) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <!-- Juruasan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jurusan</label>
                    <input type="text" name="jurusan"value="{{ old('jurusan', $DT->jurusan) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <!-- Asal Pendidikan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Asal Pendidikan</label>
                    <input type="text" name="asal_pendidikan" value="{{ old('asal_pendidikan', $DT->asal_pendidikan) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none"></input>
                </div>

                <!-- Tahun Awal & Akhir -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Awal</label>
                        <input type="text" name="tahun_awal" value="{{ old('tahun_awal', $DT->tahun_awal) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Akhir</label>
                        <input type="text" name="tahun_akhir" value="{{ old('tahun_akhir', $DT->tahun_akhir) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <!-- Action -->
                <div class="flex justify-between gap-4">
                    <button type="submit" class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 shadow">
                        Simpan
                    </button>
                    <a href=" {{ route('superadmin.pelamar.edit') }}"
                        class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 shadow">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

