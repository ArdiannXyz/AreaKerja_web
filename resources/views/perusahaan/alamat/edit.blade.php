@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white min-h-screen p-4 sm:p-10 mt-20">
        <!-- Judul -->
        <h2 class="text-xl font-semibold text-gray-800">Edit Alamat Perusahaan</h2>
        <hr class="border-t-2 border-orange-500 mt-1 mb-6" />

        <!-- Form -->
        <form action="{{ route('alamat.update.perusahaan', $data->id ?? 1) }}" method="POST" class="px-0 sm:px-12 space-y-5 w-full max-w-4xl">
            @csrf
            @method('PUT')

            <!-- Nama Alamat -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">
                    Nama Alamat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="label" placeholder="Nama Alamat" value="{{ $data->label ?? 'Alamat Utama' }}"
                    class="w-full border border-orange-500 rounded-md px-4 py-3 outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Kode Pos -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">
                    Kode Pos <span class="text-red-500">*</span>
                </label>
                <input type="text" name="kode_pos" placeholder="Kode Pos" value="{{ $data->kode_pos ?? '60111' }}"
                    class="w-full border border-orange-500 rounded-md px-4 py-3 outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Desa -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">
                    Desa / Kelurahan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="desa" placeholder="Desa / Kelurahan" value="{{ $data->desa ?? '' }}"
                    class="w-full border border-orange-500 rounded-md px-4 py-3 outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Provinsi -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Provinsi <span
                        class="text-red-500">*</span></label>
                <select id="provinsiSelect" name="provinsi"
                    class="w-full border border-orange-500 rounded-md px-4 py-3 outline-none focus:ring-1 focus:ring-orange-500">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($provinsis as $prov)
                        @php
                            $selectedProv = (string)($data->provinsi_id ?? (is_object($data->provinsi ?? null) ? $data->provinsi->nama : ($data->provinsi ?? '')));
                        @endphp
                        <option value="{{ $prov->id }}" {{ strcasecmp($selectedProv, $prov->id) === 0 || strcasecmp($selectedProv, $prov->nama) === 0 ? 'selected' : '' }}>
                            {{ $prov->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kota -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                <select id="kotaSelect" name="kota"
                    class="w-full border border-orange-500 rounded-md px-4 py-3 outline-none focus:ring-1 focus:ring-orange-500">
                    <option value="">Pilih Kota</option>
                    @php
                        $kotaVal = is_object($data->kota ?? null) ? $data->kota->nama : ($data->kota ?? '');
                    @endphp
                    @if (!empty($kotaVal))
                        <option value="{{ $kotaVal }}" selected>{{ $kotaVal }}</option>
                    @endif
                </select>
            </div>

            <!-- Kecamatan -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">Kecamatan <span
                        class="text-red-500">*</span></label>
                <select id="kecamatanSelect" name="kecamatan"
                    class="w-full border border-orange-500 rounded-md px-4 py-3 outline-none focus:ring-1 focus:ring-orange-500">
                    <option value="">Pilih Kecamatan</option>
                    @php
                        $kecVal = is_object($data->kecamatan ?? null) ? $data->kecamatan->nama : ($data->kecamatan ?? '');
                    @endphp
                    @if (!empty($kecVal))
                        <option value="{{ $kecVal }}" selected>{{ $kecVal }}</option>
                    @endif
                </select>
            </div>

            <!-- Detail Alamat -->
            <div>
                <label class="block text-sm font-medium text-gray-800 mb-1">
                    Detail Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="detail" rows="4" placeholder="Detail Alamat Lengkap"
                    class="w-full border border-orange-500 rounded-md px-4 py-3 outline-none focus:ring-1 focus:ring-orange-500">{{ $data->detail ?? $data->desa ?? '' }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('alamat.perusahaan') }}"
                    class="px-6 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-50">Batal</a>
                <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">
                    Update Alamat
                </button>
            </div>
        </form>
    </div>

    @include('layouts.footer')

    {{-- Script AJAX Dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinsiSelect = document.getElementById('provinsiSelect');
            const kotaSelect = document.getElementById('kotaSelect');
            const kecamatanSelect = document.getElementById('kecamatanSelect');

            // Ganti provinsi → load kota
            provinsiSelect.addEventListener('change', function() {
                const provinsiId = this.value;
                kotaSelect.innerHTML = '<option value="">Memuat...</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                fetch(`/get-kota/${encodeURIComponent(provinsiId)}`)
                    .then(res => res.json())
                    .then(data => {
                        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                        const options = data.map(k => `<option value="${k.nama}">${k.nama}</option>`);
                        kotaSelect.insertAdjacentHTML('beforeend', options.join(''));
                    })
                    .catch(err => {
                        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                    });
            });

            // Ganti kota → load kecamatan
            kotaSelect.addEventListener('change', function() {
                const kotaId = this.value;
                kecamatanSelect.innerHTML = '<option value="">Memuat...</option>';

                fetch(`/get-kecamatan/${encodeURIComponent(kotaId)}`)
                    .then(res => res.json())
                    .then(data => {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        const options = data.map(k => `<option value="${k.nama}">${k.nama}</option>`);
                        kecamatanSelect.insertAdjacentHTML('beforeend', options.join(''));
                    })
                    .catch(err => {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    });
            });
        });
    </script>
@endsection
