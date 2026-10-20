@extends(Auth::check() && Auth::user()->role === 'perusahaan' ? 'layouts.index-perusahaan' : 'layouts.index')
@section('content')
    <div class="bg-white text-gray-800 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10">

            {{-- Header --}}
            <div class="mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold mb-2">Frequently Asked Questions</h2>
                <p class="text-gray-500 text-sm md:text-base">Temukan jawaban dari pertanyaan yang sering ditanyakan tentang AreaKerja.</p>
            </div>

            {{-- Search Bar --}}
            <div class="flex flex-col sm:flex-row items-center w-full max-w-xl border border-gray-300 rounded-lg px-3 py-2 sm:py-3 overflow-hidden mb-10 gap-3 sm:gap-0 shadow-sm">
                <div class="relative w-full flex items-center">
                    <svg width="20" height="20" class="ml-2 shrink-0 text-gray-400" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.7954 3.125C9.27835 3.125 7.79535 3.57486 6.53396 4.4177C5.27257 5.26053 4.28943 6.45849 3.70888 7.86007C3.12832 9.26166 2.97642 10.8039 3.27239 12.2918C3.56835 13.7797 4.29889 15.1465 5.37161 16.2192C6.44434 17.2919 7.81108 18.0225 9.29899 18.3184C10.7869 18.6144 12.3292 18.4625 13.7308 17.8819C15.1323 17.3014 16.3303 16.3183 17.1731 15.0569C18.016 13.7955 18.4658 12.3125 18.4658 10.7954C18.4657 8.76113 17.6575 6.81021 16.2191 5.37175C14.7806 3.9333 12.8297 3.12513 10.7954 3.125Z" stroke="currentColor" stroke-opacity="0.5" stroke-width="1.5625" stroke-miterlimit="10"/>
                        <path d="M16.5176 16.5176L21.8745 21.8745" stroke="currentColor" stroke-opacity="0.5" stroke-width="1.5625" stroke-miterlimit="10" stroke-linecap="round"/>
                    </svg>
                    <input id="faqSearch" type="text" placeholder="Apa yang bisa kami bantu?"
                        class="w-full pl-3 pr-3 py-2 text-sm sm:text-base focus:ring-0 focus:outline-0 border-none bg-transparent">
                </div>
                <button onclick="searchFaq()" class="bg-[#00509d] hover:bg-[#003d7a] text-white font-medium px-6 py-2 rounded-md w-full sm:w-auto text-center transition-colors">
                    Cari
                </button>
            </div>

            {{-- FAQ Cards Grid --}}
            <div id="faqGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
                @foreach ($faqs as $faq)
                    <a href="{{ route('faq.detail', $faq['slug']) }}"
                        class="faq-card group flex flex-col border border-gray-200 rounded-2xl p-5 hover:shadow-md hover:border-[#00509d] transition-all duration-200 bg-white"
                        data-title="{{ strtolower($faq['title']) }}" data-excerpt="{{ strtolower($faq['excerpt']) }}">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-gray-100 group-hover:bg-[#00509d] flex items-center justify-center shrink-0 transition-colors duration-200">
                                <i class="ph {{ $faq['icon'] }} text-xl text-gray-500 group-hover:text-white transition-colors duration-200"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 text-sm md:text-base leading-snug mt-1">{{ $faq['title'] }}</h3>
                        </div>
                        <p class="text-gray-500 text-xs md:text-sm leading-relaxed flex-1">{{ $faq['excerpt'] }}</p>
                        <div class="flex items-center gap-1 mt-4 text-xs font-semibold text-gray-400 group-hover:text-[#00509d] group-hover:gap-2 transition-all">
                            <span>Baca selengkapnya</span>
                            <i class="ph ph-arrow-right"></i>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- No Result --}}
            <div id="noResult" class="hidden text-center py-12 text-gray-400">
                <i class="ph ph-magnifying-glass text-5xl mb-3 block"></i>
                <p class="text-base">Tidak ada FAQ yang cocok dengan pencarian Anda.</p>
            </div>

            {{-- Banner Hubungi Admin --}}
            <div class="mt-6 bg-[#00509d] rounded-2xl p-6 md:p-8 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-52 h-52 bg-white/10 rounded-full pointer-events-none"></div>
                <div class="absolute right-20 -top-10 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                    <div class="flex items-start sm:items-center gap-4 text-left w-full md:w-auto">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-3xl shrink-0">
                            <i class="ph ph-headset"></i>
                        </div>
                        <div>
                            <h3 class="text-xl md:text-2xl font-bold text-white leading-tight">Tidak menemukan jawaban?</h3>
                            <p class="text-sm md:text-base text-blue-100 mt-1 max-w-xl">Hubungi admin kami untuk pertanyaan, kendala teknis, atau permasalahan akun Anda.</p>
                        </div>
                    </div>
                    <div class="w-full md:w-auto shrink-0">
                        <a href="{{ route('bantuan.chat') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white hover:bg-gray-50 text-[#00509d] font-semibold rounded-xl shadow transition-all text-sm md:text-base">
                            <i class="ph ph-chats-circle text-xl"></i>
                            <span>Hubungi Admin</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('layouts.footer')

    <script>
        function searchFaq() {
            const query = document.getElementById('faqSearch').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.faq-card');
            let visible = 0;
            cards.forEach(card => {
                const title   = card.dataset.title   || '';
                const excerpt = card.dataset.excerpt  || '';
                const match   = !query || title.includes(query) || excerpt.includes(query);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            document.getElementById('noResult').classList.toggle('hidden', visible > 0);
        }

        document.getElementById('faqSearch').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') searchFaq();
        });
    </script>
@endsection
