@extends('layouts.index-perusahaan')
@section('content')
    <div class="max-w-4xl mx-auto my-12 mt-16 px-4">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Cari Nama Pekerja</h2>
                    <p class="text-sm text-gray-500 mt-1">Cari dan periksa riwayat rekam jejak pekerja atau calon kandidat</p>
                </div>
                <div class="p-3 bg-blue-50 text-[#00509d] rounded-xl">
                    <i class="ph ph-magnifying-glass text-2xl"></i>
                </div>
            </div>

            <!-- Search Form -->
            <form action="{{ route('perusahaan.cari.nama.pekerja') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Masukkan Nama Pekerja atau NIK..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#00509d] focus:ring-1 focus:ring-[#00509d]">
                </div>
                <button type="submit" class="bg-[#00509d] hover:bg-[#003d7a] text-white font-medium px-6 py-2.5 rounded-xl text-sm transition">
                    Cari Pekerja
                </button>
            </form>

            <!-- Status Banner / Hubungi Admin WA -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-[#00509d] text-white rounded-lg">
                        <i class="ph ph-user-focus text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">Butuh Rekam Jejak Detail Pekerja?</h4>
                        <p class="text-xs text-gray-600">Tim AreaKerja siap membantu verifikasi dan pemeriksaan latar belakang pekerja.</p>
                    </div>
                </div>
                <button onclick="hubungiAdminWA()" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-medium px-5 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition">
                    <i class="ph ph-whatsapp-logo text-lg"></i>
                    Tanya Admin WA
                </button>
            </div>

            <!-- Hasil Pencarian -->
            <div class="mt-6">
                <h3 class="font-semibold text-gray-700 text-sm mb-3">Hasil Pencarian</h3>
                @if(request('q'))
                    <div class="border rounded-xl p-4 text-center text-gray-500 text-sm">
                        Menampilkan hasil pencarian untuk "<span class="font-semibold text-gray-800">{{ request('q') }}</span>". Jika data belum tersedia, Anda dapat menghubungi admin.
                    </div>
                @else
                    <div class="border border-dashed border-gray-300 rounded-xl p-8 text-center">
                        <i class="ph ph-user-search text-4xl text-gray-400 mb-2"></i>
                        <p class="text-sm font-medium text-gray-600">Ketik nama pekerja di kolom pencarian di atas untuk memulai.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function hubungiAdminWA() {
            fetch("{{ route('perusahaan.cari.nama.pekerja.wa') }}", {
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


