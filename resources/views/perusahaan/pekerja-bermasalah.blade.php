@extends('layouts.index-perusahaan')
@section('content')
    <div class="max-w-4xl mx-auto my-12 mt-16 px-4">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Daftar Pekerja Bermasalah</h2>
                    <p class="text-sm text-gray-500 mt-1">Daftar rekaman pekerja bermasalah dan pelanggaran disiplin kerja</p>
                </div>
                <div class="p-3 bg-red-50 text-red-500 rounded-xl">
                    <i class="ph ph-warning-circle text-2xl"></i>
                </div>
            </div>

            <!-- Header Action & WA Contact -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-red-500 text-white rounded-lg">
                        <i class="ph ph-shield-warning text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">Konsultasi atau Pelaporan Pekerja Bermasalah</h4>
                        <p class="text-xs text-gray-600">Dapatkan bantuan penanganan dan pengecekan pekerja bermasalah dari Admin AreaKerja.</p>
                    </div>
                </div>
                <button onclick="hubungiPekerjaBermasalahWA()" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-medium px-5 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition">
                    <i class="ph ph-whatsapp-logo text-lg"></i>
                    Laporkan via WA
                </button>
            </div>

            <!-- List Pekerja Bermasalah -->
            <div>
                <h3 class="font-semibold text-gray-700 text-sm mb-3">Catatan Pekerja Bermasalah</h3>
                <div class="border border-dashed border-gray-300 rounded-xl p-8 text-center">
                    <i class="ph ph-user-minus text-4xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-medium text-gray-600">Belum ada catatan pekerja bermasalah yang terdaftar di akun Anda.</p>
                    <a href="{{ route('perusahaan.data.pekerja') }}" class="inline-block mt-3 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                        + Buat Laporan Pekerja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hubungiPekerjaBermasalahWA() {
            fetch("{{ route('perusahaan.pekerja.bermasalah.wa') }}", {
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
