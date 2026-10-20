@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white min-h-screen p-4 sm:p-10 mt-20">
        <h2 class="text-xl font-semibold text-gray-800">Alamat Perusahaan</h2>
        <hr class="border-t-2 border-[#00509d] mt-1 mb-6" />
        <!-- Alerts -->
        @if (session('success'))
            <div class="p-3 my-4 bg-green-100 text-green-700 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 my-4 bg-red-100 text-red-700 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="p-3 my-4 bg-red-100 text-red-700 rounded-md text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('alamat.store.perusahaan') }}" method="POST" class="px-0 sm:px-12 space-y-5 w-full max-w-4xl">
            @csrf

            <!-- Nama Alamat -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Nama Alamat</label>
                <input type="text" name="label" value="{{ old('label') }}"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Contoh: Kantor Pusat">
            </div>

            <!-- Kode Pos -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos') }}"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Kode Pos">
            </div>

            <!-- Desa -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Desa / Kelurahan</label>
                <input type="text" name="desa" value="{{ old('desa') }}"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Nama Desa">
            </div>

            <!-- Provinsi -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Provinsi <span class="text-red-500">*</span></label>
                <select name="provinsi" id="provinsi" required
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($provinsis as $provinsi)
                        <option value="{{ $provinsi->nama }}" {{ old('provinsi') == $provinsi->nama ? 'selected' : '' }}>{{ $provinsi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kota -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                <select name="kota" id="kota" required
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]">
                    <option value="">Pilih Kota</option>
                    @if (old('kota'))
                        <option value="{{ old('kota') }}" selected>{{ old('kota') }}</option>
                    @endif
                </select>
            </div>

            <!-- Detail -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Detail Alamat Lengkap <span class="text-red-500">*</span></label>
                <textarea name="detail" rows="4" required
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Detail Alamat Lengkap">{{ old('detail') }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('alamat.perusahaan') }}"
                    class="px-6 py-2 border border-[#00509d] text-[#00509d] rounded-md hover:bg-blue-50">Batal</a>
                <button type="submit"
                    class="px-6 py-2 bg-[#00509d] text-white rounded-md hover:bg-[#003d7a]">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinsiSelect = document.getElementById('provinsi');
            const kotaSelect = document.getElementById('kota');

            function loadKota(provinsiId, selectedKota = null) {
                if (!provinsiId) {
                    kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                    return;
                }
                kotaSelect.innerHTML = '<option value="">Memuat...</option>';
                fetch(`/get-kota/${encodeURIComponent(provinsiId)}`)
                    .then(res => res.json())
                    .then(data => {
                        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                        data.forEach(kota => {
                            const isSelected = selectedKota && selectedKota.toLowerCase() === kota.nama.toLowerCase();
                            kotaSelect.innerHTML +=
                                `<option value="${kota.nama}" ${isSelected ? 'selected' : ''}>${kota.nama}</option>`;
                        });
                    })
                    .catch(() => {
                        kotaSelect.innerHTML = '<option value="">Gagal memuat kota</option>';
                    });
            }

            provinsiSelect.addEventListener('change', function() {
                loadKota(this.value);
            });

            @if(old('provinsi'))
                loadKota("{{ old('provinsi') }}", "{{ old('kota') }}");
            @endif
        });
    </script>

    @include('layouts.footer')
@endsection

