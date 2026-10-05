@extends('layouts.index-perusahaan')
@section('content')
    <!-- Hero Section -->
    <section class="relative">
        @php
            $header = null;
        @endphp

        <img src="{{ $header && $header->link ? asset('storage/' . $header->link) : asset('images/ntap.png') }}"
            alt="Header Image" class="w-full h-[400px] md:h-[500px] object-cover">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex flex-col justify-center px-6 md:px-20 text-white max-w-5xl">
            <h1 class="text-3xl md:text-4xl font-bold">
                Selamat Datang
            </h1>
            <p class="text-sm md:text-base mt-4 leading-relaxed max-w-xl text-gray-200">
                Sambutlah hari ini dengan semangat, dan <br class="hidden sm:inline">
                manfaatkan sepenuhnya fasilitas yang kami <br class="hidden sm:inline">
                berikan demi kenyamanan anda
            </p>
        </div>
    </section>

    <!-- Articles Section -->
    <div class="py-16 px-6 bg-white">
        <div class="max-w-6xl mx-auto space-y-16">
            <!-- Article 1: Request Data Pekerja -->
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <div class="md:w-2/3">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Request Data Pekerja</h3>
                    <p class="text-gray-700 text-sm md:text-base leading-relaxed mb-4">
                        Dapatkan akses untuk melihat statistik kinerja pekerja secara menyeluruh, termasuk daftar pekerja dengan
                        performa terbaik maupun yang memerlukan evaluasi. Anda juga dapat membuat laporan kinerja harian pekerja
                        secara detail.
                    </p>
                    <p class="text-gray-700 text-sm md:text-base leading-relaxed mb-6">
                        Selain itu, setiap laporan valid terkait pekerja yang bermasalah atau melakukan pelanggaran akan mendapatkan
                        <span class="font-bold text-[#00509d]">reward berupa Koin</span>, sebagai bentuk apresiasi atas kontribusi Anda dalam menjaga kualitas dan
                        profesionalisme tenaga kerja.
                    </p>
                    <a href="{{ route('perusahaan.data.pekerja') }}"
                        class="inline-flex items-center text-[#00509d] text-sm font-bold hover:underline">
                        Lebih Detail &gt;
                    </a>
                </div>
                <div class="md:w-1/3 w-full flex justify-center">
                    <img src="{{ asset('images/gambar1.jpg') }}" alt="Request Data Pekerja"
                        class="rounded-xl shadow-md w-full max-w-md h-auto object-cover">
                </div>
            </div>

            <!-- Article 2: Diskon Fitur Area Kerja -->
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 pt-8 border-t border-gray-100">
                <div class="md:w-2/3">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Diskon Fitur Area Kerja</h3>
                    <p class="text-gray-700 text-sm md:text-base leading-relaxed mb-4">
                        Dengan berlangganan, Anda mendapatkan diskon fitur serta berbagai manfaat tambahan dan informasi terbaru setiap saat.
                    </p>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-start gap-2 text-sm text-gray-700">
                            <span class="text-[#00509d] font-bold">✔</span>
                            <span>Diskon khusus untuk pasang lowongan sebagai bagian dari manfaat berlangganan.</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm text-gray-700">
                            <span class="text-[#00509d] font-bold">✔</span>
                            <span>Potongan harga untuk beli kandidat yang hanya tersedia bagi pelanggan berlangganan.</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm text-gray-700">
                            <span class="text-[#00509d] font-bold">✔</span>
                            <span>Diskon eksklusif untuk layanan talent hunter sebagai benefit tambahan berlangganan.</span>
                        </div>
                    </div>
                    <button id="btnDiskon" class="inline-flex items-center text-[#00509d] text-sm font-bold hover:underline">
                        Lebih Detail &gt;
                    </button>
                </div>
                <div class="md:w-1/3 w-full flex justify-center">
                    <img src="{{ asset('images/nulis.jpg') }}" alt="Diskon Fitur Area Kerja"
                        class="rounded-xl shadow-md w-full max-w-md h-auto object-cover">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnDiskon').addEventListener('click', function() {
            fetch("{{ route('diskon.fitur') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert(data.message || "Gagal memproses permintaan.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Terjadi kesalahan. Coba lagi.");
                });
        });
    </script>
    @include('layouts.footer')
@endsection

