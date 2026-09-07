@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white min-h-screen p-4 sm:p-10 mt-20">
        <h2 class="text-xl font-semibold text-gray-800">Alamat Perusahaan</h2>
        <hr class="border-t-2 border-[#00509d] mt-1 mb-6" />

        <form action="{{ route('alamat.store.perusahaan') }}" method="POST" class="px-0 sm:px-12 space-y-5 w-full max-w-4xl">
            @csrf

            <!-- Nama Alamat -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Nama Alamat</label>
                <input type="text" name="label"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Contoh: Kantor Pusat">
            </div>

            <!-- Kode Pos -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Kode Pos</label>
                <input type="text" name="kode_pos"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Kode Pos">
            </div>

            <!-- Desa -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Desa</label>
                <input type="text" name="desa"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Nama Desa">
            </div>

            <!-- Provinsi -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Provinsi</label>
                <select name="provinsi_id" id="provinsi"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($provinsis as $provinsi)
                        <option value="{{ $provinsi->id }}">{{ $provinsi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kota -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Kota</label>
                <select name="kota_id" id="kota"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]">
                    <option value="">Pilih Kota</option>
                </select>
            </div>

            <!-- Kecamatan -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Kecamatan</label>
                <select name="kecamatan_id" id="kecamatan"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]">
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>

            <!-- Detail -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Detail Alamat</label>
                <textarea name="detail" rows="4"
                    class="w-full border border-[#00509d] rounded-md px-4 py-3 focus:ring-1 focus:ring-[#00509d]"
                    placeholder="Detail Alamat"></textarea>
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
            const kecamatanSelect = document.getElementById('kecamatan');

            provinsiSelect.addEventListener('change', function() {
                const provinsiId = this.value;
                kotaSelect.innerHTML = '<option value="">Memuat...</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                console.time('loadKota'); // mulai ukur waktu
                fetch(`/get-kota/${provinsiId}`)
                    .then(res => res.json())
                    .then(data => {
                        console.timeEnd('loadKota'); // tampilkan waktu di console
                        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                        data.forEach(kota => {
                            kotaSelect.innerHTML +=
                                `<option value="${kota.id}">${kota.nama}</option>`;
                        });
                    });
            });


            kotaSelect.addEventListener('change', function() {
                const kotaId = this.value;
                kecamatanSelect.innerHTML = '<option value="">Memuat...</option>';

                fetch(`/get-kecamatan/${kotaId}`)
                    .then(res => res.json())
                    .then(data => {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        data.forEach(kec => {
                            kecamatanSelect.innerHTML +=
                                `<option value="${kec.id}">${kec.nama}</option>`;
                        });
                    });
            });
        });
    </script>

    @include('layouts.footer')
@endsection

