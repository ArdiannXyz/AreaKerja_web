@extends(Auth::check() && Auth::user()->role === 'perusahaan' ? 'layouts.index-perusahaan' : 'layouts.index')
@section('content')
    <div class="bg-white text-gray-800 mt-16">
        <div class="max-w-4xl mx-auto px-4 py-10">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
                <a href="{{ route('faq') }}" class="hover:text-[#00509d] transition-colors">FAQ</a>
                <i class="ph ph-caret-right text-xs"></i>
                <span class="text-gray-600 font-medium truncate max-w-xs">{{ $faq['title'] }}</span>
            </nav>

            {{-- Hero Image --}}
            @if(!empty($faq['image']))
                <div class="w-full h-56 md:h-72 rounded-2xl overflow-hidden mb-8 shadow-sm bg-gray-100">
                    <img src="{{ str_starts_with($faq['image'], 'http') ? $faq['image'] : asset($faq['image']) }}"
                        alt="{{ $faq['title'] }}"
                        class="w-full h-full object-cover">
                </div>
            @endif

            {{-- Title --}}
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-2">{{ $faq['title'] }}</h1>
                @if(!empty($faq['subtitle']))
                    <p class="text-gray-500 text-sm md:text-base">{{ $faq['subtitle'] }}</p>
                @endif
            </div>

            {{-- Article Sections --}}
            <div class="space-y-10">
                @foreach ($faq['sections'] as $index => $section)
                    <div class="prose-like">

                        {{-- Section Heading --}}
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-7 h-7 rounded-full bg-[#00509d] text-white text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <h2 class="text-lg md:text-xl font-bold text-gray-800">{{ $section['heading'] }}</h2>
                        </div>

                        {{-- Section Body --}}
                        <div class="ml-10 text-gray-600 text-sm md:text-base leading-relaxed whitespace-pre-line">
                            {!! $section['body'] !!}
                        </div>

                        {{-- Section Image (if any) --}}
                        @if(!empty($section['image']))
                            <div class="ml-10 mt-4">
                                <img src="{{ str_starts_with($section['image'], 'http') ? $section['image'] : asset($section['image']) }}"
                                    alt="{{ $section['heading'] }}"
                                    class="rounded-xl w-full max-h-64 object-cover shadow-sm border border-gray-100">
                            </div>
                        @endif

                    </div>

                    @if(!$loop->last)
                        <hr class="border-gray-100">
                    @endif
                @endforeach
            </div>

            {{-- Navigation: Prev / Next --}}
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t pt-8">
                @if($prevSlug)
                    <a href="{{ route('faq.detail', $prevSlug) }}"
                        class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-[#00509d] hover:bg-blue-50 transition-all group">
                        <i class="ph ph-arrow-left text-xl text-gray-400 group-hover:text-[#00509d]"></i>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Sebelumnya</p>
                            <p class="text-sm font-semibold text-gray-700 group-hover:text-[#00509d] line-clamp-2">{{ $prevTitle }}</p>
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextSlug)
                    <a href="{{ route('faq.detail', $nextSlug) }}"
                        class="flex items-center justify-end gap-3 p-4 rounded-xl border border-gray-200 hover:border-[#00509d] hover:bg-blue-50 transition-all group text-right">
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-0.5">Berikutnya</p>
                            <p class="text-sm font-semibold text-gray-700 group-hover:text-[#00509d] line-clamp-2">{{ $nextTitle }}</p>
                        </div>
                        <i class="ph ph-arrow-right text-xl text-gray-400 group-hover:text-[#00509d]"></i>
                    </a>
                @endif
            </div>

            {{-- Back to FAQ + Contact Admin --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-4 items-center justify-between bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-700">Apakah artikel ini membantu?</p>
                    <p class="text-xs text-gray-400 mt-0.5">Jika masalah belum terpecahkan, hubungi tim admin kami.</p>
                </div>
                <div class="flex gap-3 shrink-0">
                    <a href="{{ route('faq') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:border-gray-400 text-gray-700 font-semibold rounded-xl text-xs transition-all">
                        <i class="ph ph-list-bullets"></i> Semua FAQ
                    </a>
                    <a href="{{ route('bantuan.chat') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-semibold rounded-xl text-xs shadow-sm transition-all">
                        <i class="ph ph-chats-circle"></i> Hubungi Admin
                    </a>
                </div>
            </div>

        </div>
    </div>

    @include('layouts.footer')
@endsection

