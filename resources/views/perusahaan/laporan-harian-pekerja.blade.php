@extends('layouts.index-perusahaan')
@section('content')
    <div class="max-w-4xl mx-auto my-12 mt-16 px-4">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Laporan Harian Pekerja</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola dan laporkan aktivitas harian serta kehadiran pekerja perusahaan</p>
                </div>
                <div class="p-3 bg-blue-50 text-blue-500 rounded-xl">
                    <i class="ph ph-clipboard-text text-2xl"></i>
                </div>
            </div>

            <!-- Form Laporan Harian -->
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pekerja</label>
                        <input type="text" name="nama_pekerja" placeholder="Masukkan nama pekerja..." required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Laporan</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                    <select name="status_kehadiran" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Tanpa Keterangan">Tanpa Keterangan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pekerjaan / Aktivitas</label>
                    <textarea name="catatan" rows="3" placeholder="Tuliskan ringkasan tugas atau catatan aktivitas harian pekerja..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"></textarea>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t">
                    <button type="button" onclick="kirimLaporanWA()" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-medium px-5 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition">
                        <i class="ph ph-whatsapp-logo text-lg"></i>
                        Kirim Laporan via WA
                    </button>
                    <button type="submit" class="w-full sm:w-auto bg-orange-500 hover:bg-orange-600 text-white font-medium px-6 py-2 rounded-lg text-sm transition">
                        Simpan Laporan Harian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function kirimLaporanWA() {
            fetch("{{ route('perusahaan.laporan.harian.pekerja.wa') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.redirect_url) {
                    window.open(data.redirect_url, '_blank');
                }
            });
        }
    </script>

    @include('layouts.footer')
@endsection
