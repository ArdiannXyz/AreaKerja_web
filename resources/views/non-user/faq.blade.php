@extends('layouts.index')
@section('content')
    <div class="bg-white text-gray-800 mt-16">

        <div class="max-w-6xl mx-auto px-4 py-10">

            <!-- Header -->
            <h2 class="text-2xl md:text-3xl font-semibold mb-6">Frequently Asked Questions</h2>

            <!-- Search Bar -->
            <div
                class="flex flex-col sm:flex-row items-center w-full max-w-xl border border-gray-400 rounded-lg 
           px-3 py-2 sm:py-3 overflow-hidden mb-10 gap-3 sm:gap-0">

                <!-- Wadah Input + Icon -->
                <div class="relative w-full flex items-center">
                    <!-- Icon Search -->
                    <svg width="24" height="24" class="ml-2" viewBox="0 0 25 25" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.7954 3.125C9.27835 3.125 7.79535 3.57486 6.53396 4.4177C5.27257 5.26053 4.28943 6.45849 3.70888 7.86007C3.12832 9.26166 2.97642 10.8039 3.27239 12.2918C3.56835 13.7797 4.29889 15.1465 5.37161 16.2192C6.44434 17.2919 7.81108 18.0225 9.29899 18.3184C10.7869 18.6144 12.3292 18.4625 13.7308 17.8819C15.1323 17.3014 16.3303 16.3183 17.1731 15.0569C18.016 13.7955 18.4658 12.3125 18.4658 10.7954C18.4657 8.76113 17.6575 6.81021 16.2191 5.37175C14.7806 3.9333 12.8297 3.12513 10.7954 3.125Z"
                            stroke="gray" stroke-opacity="0.6" stroke-width="1.5625" stroke-miterlimit="10" />
                        <path d="M16.5176 16.5176L21.8745 21.8745" stroke="gray" stroke-opacity="0.6" stroke-width="1.5625"
                            stroke-miterlimit="10" stroke-linecap="round" />
                    </svg>

                    <!-- Input -->
                    <input type="text" placeholder="Apa yang bisa kami bantu?"
                        class="w-full pl-4 pr-3 py-2 text-sm sm:text-base focus:ring-0 focus:outline-0 border-none">
                </div>

                <!-- Tombol Cari -->
                <button
                    class="bg-[#00509d] hover:bg-[#003d7a] text-white font-medium 
               px-6 py-2 rounded-md w-full sm:w-auto text-center">
                    Cari
                </button>
            </div>


            <!-- FAQ Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">

                <!-- Kolom Kiri -->
                <div class="space-y-6 md:space-y-8 text-sm md:text-base">
                    <div>
                        <h3 class="font-semibold mb-1 text-base md:text-lg">Bagaimana Melamar Pekerjaan di Area Kerja?</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            <span class="text-[#00509d]">Viverra faucibus lectus viverra id</span>.
                            Lectus habitant nisl, posuere at urna ut vitae hac ultricies.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-1 text-base md:text-lg">Apa itu kandidat Area Kerja?</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            <span class="text-[#00509d]">Viverra faucibus lectus viverra id</span>.
                            Commodo ridiculus augue condimentum molestie dolor.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-1 text-base md:text-lg">Apa itu daftar kandidat?</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            <span class="text-[#00509d]">Viverra faucibus lectus viverra id</span>.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-1 text-base md:text-lg">Apa itu kandidat area kerja?</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            <span class="text-gray-500">Malesuada eget eu ultricies</span>.
                        </p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6 md:space-y-8 text-sm md:text-base">
                    <div>
                        <h3 class="font-semibold mb-1 text-base md:text-lg">Apa itu Area Kerja?</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            <span class="text-[#00509d]">Viverra faucibus lectus viverra id</span>.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-1 text-base md:text-lg">Bagaimana cara melamar kerja lewat Areakerja.com
                            ?</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            <span class="text-[#00509d]">Viverra faucibus lectus viverra id</span>.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-1 text-base md:text-lg">Bagaimana cara mengganti sandi?</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            <span class="text-[#00509d]">Condimentum molestie dolor sit amet</span>.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Tombol bawah -->
            <div class="flex justify-center mt-12">
                <button class="bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-md text-sm md:text-base">
                    Lihat Semua FAQ â†’
                </button>
            </div>

        </div>

    </div>

    @include('layouts.footer')
@endsection

