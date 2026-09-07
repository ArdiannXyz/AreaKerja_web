@extends('layouts.index-perusahaan')
@section('content')
    <!-- Hero Section -->
    <section class="relative">
        @php
            $header = null;
        @endphp

        <img src="{{ $header && $header->link ? asset('storage/' . $header->link) : asset('images/ntap.png') }}"
            alt="Header Image" class="w-full h-[600px] object-cover">
        {{-- <img src="{{ asset('images/ntap.png') }}" alt="hero" class="w-full h-[350px] object-cover"> --}}
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="absolute bottom-52 left-0 pl-6 text-left
            md:pl-0 md:left-20 text-white">
            <h1 class="text-3xl md:text-4xl font-semibold mt-3 max-w-2xl">
                Selamat Datang
            </h1>
            <p class="text-sm mt-4">Sambutlah hari ini dengan semangat, dan <br>
                manfaatkan sepenuhnya fasilitas yang kami <br>
                berikan demi kenyamanan anda</p>
        </div>
    </section>


    <div class="w-full py-16 px-6" style="background: linear-gradient(to right, orange, #ff7b00)">
        <h2 class="text-2xl font-bold text-center text-white">Request Data Pekerja</h2>
        <div class="w-20 h-1 bg-white mx-auto my-2"></div>
        <p class="text-sm font-medium leading-relaxed text-center text-white mt-6">
            Dapatkan akses untuk melihat statistik kinerja pekerja secara menyeluruh, <br> termasuk daftar pekerja dengan
            performa terbaik maupun yang memerlukan evaluasi. <br> Anda juga dapat membuat laporan kinerja harian pekerja
            secara
            detail.
        </p>
        
        <p class="text-sm font-medium leading-relaxed text-center text-white mt-6">
            Selain itu, setiap laporan valid terkait pekerja yang bermasalah atau melakukan pelanggaran akan mendapatkan
            <span class="font-bold hover:underline"> reward berupa Koin</span>, <br> sebagai bentuk apresiasi atas kontribusi Anda dalam menjaga kualitas dan
            profesionalisme
            tenaga kerja.
        </p>
        <div class="max-w-6xl mx-auto space-y-12 mt-12">
            <div class="flex flex-col md:flex-row w-full justify-center items-center">
                <div
                    class="bg-white border border-gray-100 rounded-lg p-8 text-center text-[#00509d] w-full max-w-xs mx-auto shadow-lg hover:scale-105 transition duration-500 hover:shadow-xl mb-6">
                    <div class="flex justify-center mb-4">
                        <!-- Icon -->
                        <svg width="82" height="84" viewBox="0 0 82 84" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M47.7279 33.7279C44.3523 37.1036 39.7739 39 35 39C30.2261 39 25.6477 37.1036 22.2721 33.7279C18.8964 30.3523 17 25.7739 17 21C17 16.2261 18.8964 11.6477 22.2721 8.27208C25.6477 4.89642 30.2261 3 35 3C39.7739 3 44.3523 4.89642 47.7279 8.27208C51.1036 11.6477 53 16.2261 53 21C53 25.7739 51.1036 30.3523 47.7279 33.7279Z"
                                stroke="orange" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M47.5553 56.8429C43.5127 55.0231 39.1574 54 35 54C28.1817 54 20.8307 56.7519 15.2005 61.2573C9.56388 65.7679 6 71.7323 6 78C6 79.6569 4.65685 81 3 81C1.34315 81 0 79.6569 0 78C0 69.3742 4.87087 61.8387 11.4517 56.5726C18.0388 51.3014 26.6878 48 35 48C40.5625 48 46.2758 49.4785 51.4211 52.0363C49.8927 53.4193 48.5844 55.0411 47.5553 56.8429Z"
                                fill="orange" />
                            <path
                                d="M77.6253 79.6253L71.2918 73.2918M71.2918 73.2918C72.3751 72.2084 73.2345 70.9222 73.8208 69.5067C74.4072 68.0912 74.7089 66.5741 74.7089 65.042C74.7089 63.5098 74.4072 61.9927 73.8208 60.5772C73.2345 59.1617 72.3751 57.8756 71.2918 56.7922C70.2084 55.7088 68.9222 54.8494 67.5067 54.2631C66.0912 53.6768 64.5741 53.375 63.042 53.375C61.5098 53.375 59.9927 53.6768 58.5772 54.2631C57.1617 54.8494 55.8756 55.7088 54.7922 56.7922C52.6042 58.9802 51.375 61.9477 51.375 65.042C51.375 68.1362 52.6042 71.1038 54.7922 73.2918C56.9802 75.4797 59.9477 76.7089 63.042 76.7089C66.1362 76.7089 69.1038 75.4797 71.2918 73.2918Z"
                                stroke="orange" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="font-medium mb-6 text-yellow-500 text-lg leading-tight">
                        Cari Nama<br>Pekerja
                    </h3>
                    <a href="{{ route('perusahaan.cari.nama.pekerja') }}"
                        class="bg-[#00509d] text-white hover:bg-[#003d7a] text-sm font-medium px-4 py-2 rounded-lg transition duration-300">
                        Lebih Detail
                    </a>
                </div>

                <div
                    class="bg-white border border-gray-100 rounded-lg p-8 text-center text-[#00509d] w-full max-w-xs mx-auto shadow-lg hover:scale-105 transition duration-500 hover:shadow-xl mb-6">
                    <div class="flex justify-center">
                        <!-- Icon -->
                        <svg width="103" height="100" viewBox="0 0 103 100" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M56.2279 40.7279C52.8523 44.1036 48.2739 46 43.5 46C38.7261 46 34.1477 44.1036 30.7721 40.7279C27.3964 37.3523 25.5 32.7739 25.5 28C25.5 23.2261 27.3964 18.6477 30.7721 15.2721C34.1477 11.8964 38.7261 10 43.5 10C48.2739 10 52.8523 11.8964 56.2279 15.2721C59.6036 18.6477 61.5 23.2261 61.5 28C61.5 32.7739 59.6036 37.3523 56.2279 40.7279Z"
                                stroke="orange" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M56.7148 63.4653C52.6313 61.5686 48.2169 60.5 44 60.5C37.1864 60.5 29.8574 63.2897 24.2417 67.8548C18.6237 72.4219 15 78.5194 15 85C15 86.3807 13.8807 87.5 12.5 87.5C11.1193 87.5 10 86.3807 10 85C10 76.5872 14.6793 69.1847 21.0877 63.9751C27.4986 58.7636 35.9195 55.5 44 55.5C49.2836 55.5 54.7126 56.8953 59.6302 59.3181C58.488 60.5625 57.5054 61.9557 56.7148 63.4653Z"
                                fill="orange" />
                            <path
                                d="M75 87C83.0081 87 89.5 80.5081 89.5 72.5C89.5 64.4919 83.0081 58 75 58C66.9919 58 60.5 64.4919 60.5 72.5C60.5 80.5081 66.9919 87 75 87Z"
                                stroke="orange" stroke-width="3" />
                            <path d="M75 65.25V73.95" stroke="orange" stroke-width="3" stroke-linecap="round" />
                            <path
                                d="M74.9969 79.7516C75.7977 79.7516 76.4469 79.1024 76.4469 78.3016C76.4469 77.5007 75.7977 76.8516 74.9969 76.8516C74.1961 76.8516 73.5469 77.5007 73.5469 78.3016C73.5469 79.1024 74.1961 79.7516 74.9969 79.7516Z"
                                fill="orange" />
                        </svg>
                    </div>
                    <h3 class="font-medium mb-6 text-yellow-500 text-lg leading-tight">
                        Laporan Harian <br>Pekerja
                    </h3>
                    <a href="{{ route('perusahaan.laporan.harian') }}"
                        class="bg-[#00509d] text-white hover:bg-[#003d7a] text-sm font-medium px-4 py-2 rounded-lg transition duration-300">
                        Lebih Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="py-16 px-6">
        <div class="max-w-6xl mx-auto space-y-12">
            <!-- Card 2 -->
            <div class="flex flex-col md:flex-row-reverse items-center md:items-start gap-6">
                <!-- Text -->
                <div class="md:w-2/3">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Diskon Fitur Area Kerja</h3>
                    <p class="text-gray-800 font-semibold text-md leading-relaxed mb-4">
                        Dengan berlangganan, Anda mendapatkan diskon fitur serta <br>
                        berbagai manfaat tambahan dan informasi terbaru setiap saat.
                    </p>
                    <div>
                        <ul class="list-disc list-inside text-[#003d7a] font-medium text-sm leading-relaxed mb-4">
                            <span>âœ” Diskon khusus untuk pasang lowongan sebagai bagian dari manfaat berlangganan.</span>
                            <br>
                            <span>âœ” Potongan harga untuk beli kandidat yang hanya tersedia bagi pelanggan
                                berlangganan.</span> <br>
                            <span>âœ” Diskon eksklusif untuk layanan talent hunter sebagai benefit tambahan
                                berlangganan.</span> <br>
                        </ul>
                    </div>
                    <button id="btnDiskon" class="text-[#00509d] text-sm font-semibold hover:underline"> Lebih Detail >
                    </button>
                </div>
                <!-- Image -->
                <div class="md:w-1/3 w-full">
                    <img src="{{ asset('images/nulis.jpg') }}" alt="diskon fitur"
                        class="rounded-lg shadow w-full h-auto object-cover">
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
                    body: JSON.stringify({}) // WAJIB ADA
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

