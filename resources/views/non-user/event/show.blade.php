@extends('layouts.index')

@section('content')
    <div class="min-h-screen bg-slate-50/50 pt-28 pb-20 text-slate-800" x-data="{
        showConfirm: false,
        showSuccess: false,
        loading: false
    }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-4">
                <a href="{{ route('beranda') }}" class="hover:text-[#00509d] transition">Beranda</a>
                <i class="ph ph-caret-right text-[10px]"></i>
                <a href="{{ route('pelamar.event.index') }}" class="hover:text-[#00509d] transition">Event</a>
                <i class="ph ph-caret-right text-[10px]"></i>
                <span class="text-slate-800 truncate max-w-xs">{{ $event->title }}</span>
            </nav>

            <!-- Tanggal Event di Atas -->
            <p class="text-xs sm:text-sm font-semibold text-slate-500 mb-3">
                {{ \Carbon\Carbon::parse($event->tgl_mulai)->translatedFormat('d F Y') }}
            </p>

            <!-- Banner Utama (Matching Figma) -->
            <div class="rounded-3xl overflow-hidden w-full h-64 sm:h-80 md:h-[380px] shadow-sm border border-slate-200 relative mb-8 bg-slate-100">
                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&auto=format&fit=crop&q=80' }}"
                    alt="{{ $event->title }}" class="w-full h-full object-cover">
            </div>

            <!-- Konten Deskripsi Event -->
            <div class="space-y-4">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 leading-tight">
                    {{ $event->title }}
                </h1>

                <div class="text-xs sm:text-sm text-slate-600 leading-relaxed space-y-3 prose max-w-none">
                    {!! nl2br(e($event->content)) !!}
                </div>
            </div>

            <!-- Detail Acara (Matching Figma) -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-3">Detail Acara</h2>
                <div class="space-y-2.5 text-xs sm:text-sm text-slate-700">
                    <div class="flex items-center gap-2.5">
                        <i class="ph ph-buildings text-lg text-[#00509d] shrink-0"></i>
                        <span class="font-medium">{{ $event->lokasi ?: 'Auditorium Utama & Online Hall' }}</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="ph ph-map-pin text-lg text-[#00509d] shrink-0 mt-0.5"></i>
                        <span>{{ $event->alamat_lengkap ?: ($event->lokasi ?: 'Jalan Malioboro No. 10, Sosromenduran, Gedong Tengen, Kota Yogyakarta, Daerah Istimewa Yogyakarta') }}</span>
                    </div>
                </div>
            </div>

            <!-- Daftar Kegiatan Table (Matching Figma) -->
            <div class="mt-8">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-3">Daftar Kegiatan :</h2>
                <div class="border border-[#93c5fd] rounded-xl overflow-hidden shadow-xs bg-white">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-blue-50/50 text-slate-800 text-xs sm:text-sm font-bold border-b border-[#93c5fd]">
                                <th class="py-3 px-4 w-1/3 sm:w-1/4 border-r border-[#93c5fd] text-center">Waktu</th>
                                <th class="py-3 px-4 text-center">Acara</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#bfdbfe] text-xs sm:text-sm text-slate-700">
                            @if ($event->kegiatan && $event->kegiatan->count() > 0)
                                @foreach ($event->kegiatan as $keg)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="py-3 px-4 font-mono text-center font-semibold border-r border-[#bfdbfe]">{{ $keg->waktu }}</td>
                                        <td class="py-3 px-4 text-center">{{ $keg->kegiatan }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="py-3 px-4 font-mono text-center font-semibold border-r border-[#bfdbfe]">09:00 - 09:30</td>
                                    <td class="py-3 px-4 text-center">Pembukaan & Registrasi Peserta</td>
                                </tr>
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="py-3 px-4 font-mono text-center font-semibold border-r border-[#bfdbfe]">09:30 - 11:30</td>
                                    <td class="py-3 px-4 text-center">Sesi 1: Presentasi & Job Pitching Perusahaan</td>
                                </tr>
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="py-3 px-4 font-mono text-center font-semibold border-r border-[#bfdbfe]">11:30 - 13:00</td>
                                    <td class="py-3 px-4 text-center">Istirahat & Sholat</td>
                                </tr>
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="py-3 px-4 font-mono text-center font-semibold border-r border-[#bfdbfe]">13:00 - 15:30</td>
                                    <td class="py-3 px-4 text-center">Sesi 2: Walk-in Interview & CV Review</td>
                                </tr>
                                <tr class="hover:bg-blue-50/30 transition">
                                    <td class="py-3 px-4 font-mono text-center font-semibold border-r border-[#bfdbfe]">15:30 - 16:00</td>
                                    <td class="py-3 px-4 text-center">Penutupan Acara</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Perusahaan yang Berpartisipasi (Horizontal Slider / Carousel) -->
            @if (isset($perusahaanList) && $perusahaanList->count() > 0)
                <div class="mt-10 pt-8 border-t border-slate-200" x-data="{
                    scrollLeft() {
                        this.$refs.companySlider.scrollBy({ left: -260, behavior: 'smooth' });
                    },
                    scrollRight() {
                        this.$refs.companySlider.scrollBy({ left: 260, behavior: 'smooth' });
                    }
                }">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Perusahaan yang Tergabung</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Temui langsung tim recruiter & hiring manager dari perusahaan mitra berikut</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-[#00509d] text-xs font-bold rounded-full border border-blue-200 shadow-xs">
                                <i class="ph ph-buildings text-sm"></i> {{ $perusahaanList->count() }}+ Perusahaan
                            </span>
                            <!-- Slider Nav Buttons -->
                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="scrollLeft()"
                                    class="w-8 h-8 rounded-full border border-slate-300 hover:border-[#00509d] hover:bg-blue-50 text-slate-600 hover:text-[#00509d] flex items-center justify-center transition shadow-xs cursor-pointer"
                                    title="Sebelumnya">
                                    <i class="ph ph-caret-left font-bold text-sm"></i>
                                </button>
                                <button type="button" @click="scrollRight()"
                                    class="w-8 h-8 rounded-full border border-slate-300 hover:border-[#00509d] hover:bg-blue-50 text-slate-600 hover:text-[#00509d] flex items-center justify-center transition shadow-xs cursor-pointer"
                                    title="Selanjutnya">
                                    <i class="ph ph-caret-right font-bold text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Horizontal Scrollable Container -->
                    <div x-ref="companySlider"
                        class="flex gap-4 overflow-x-auto scroll-smooth py-2 px-1 select-none"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach ($perusahaanList as $p)
                            <div class="w-[180px] sm:w-[210px] shrink-0 bg-white p-5 rounded-2xl border border-[#00509d]/30 hover:border-[#00509d] shadow-xs hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center text-center group cursor-default min-h-[125px]">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 p-1 mb-2.5 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    @if (!empty($p->img_profile))
                                        <img src="{{ asset('storage/' . $p->img_profile) }}" alt="{{ $p->nama_perusahaan }}" class="w-full h-full object-contain">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($p->nama_perusahaan ?? 'P') }}&background=00509d&color=fff&size=128" alt="{{ $p->nama_perusahaan }}" class="w-full h-full object-cover rounded-lg">
                                    @endif
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-800 line-clamp-2 group-hover:text-[#00509d] transition leading-snug">
                                    {{ $p->nama_perusahaan }}
                                </h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tombol Aksi Mendaftar (Matching Figma) -->
            <div class="mt-10 flex justify-center">
                @if ($isEnded)
                    <button disabled
                        class="bg-[#a0a5ad] text-white font-bold px-12 py-3 rounded-lg shadow-sm text-sm cursor-not-allowed">
                        Event Selesai
                    </button>
                @elseif (Auth::check())
                    @if ($isRegistered)
                        <button disabled
                            class="bg-emerald-600 text-white font-bold px-10 py-3 rounded-lg shadow-sm text-sm cursor-not-allowed flex items-center gap-2">
                            <i class="ph ph-check-circle text-lg"></i> Sudah Terdaftar
                        </button>
                    @else
                        <button @click="showConfirm = true"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-14 py-3 rounded-lg shadow-md text-sm transition duration-200 cursor-pointer">
                            Mendaftar
                        </button>
                    @endif
                @else
                    {{-- Non User / Tamu -> Klik Mendaftar langsung diarahkan ke Login --}}
                    <a href="{{ route('login') }}"
                        class="inline-block bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-14 py-3 rounded-lg shadow-md text-sm transition duration-200 text-center">
                        Mendaftar
                    </a>
                @endif
            </div>

        </div>

        {{-- ================= MODAL 1: KONFIRMASI (Matching Figma) ================= --}}
        <div x-show="showConfirm" x-cloak x-transition class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
            <div class="bg-white rounded-3xl p-8 text-center max-w-sm w-full shadow-2xl border border-slate-100" @click.outside="showConfirm = false">
                <!-- Red Exclamation Circle -->
                <div class="w-14 h-14 bg-rose-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-md">
                    !
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-800 mb-6 leading-snug">
                    Apakah anda yakin ingin bergabung ke acara ini?
                </h3>
                <div class="flex justify-center gap-3">
                    <button type="button"
                        @click="
                            loading = true;
                            fetch('{{ route('pelamar.event.daftar', $event->id) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                loading = false;
                                showConfirm = false;
                                if (data.unauthenticated) {
                                    window.location.href = data.redirect || '{{ route('login') }}';
                                    return;
                                }
                                if (data.success) {
                                    showSuccess = true;
                                } else {
                                    Swal.fire({ icon: 'warning', title: 'Pemberitahuan', text: data.message });
                                }
                            })
                            .catch(() => {
                                loading = false;
                                showConfirm = false;
                                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat memproses pendaftaran.' });
                            });
                        "
                        :disabled="loading"
                        class="px-8 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs sm:text-sm transition shadow-sm cursor-pointer">
                        Ya
                    </button>
                    <button type="button" @click="showConfirm = false"
                        class="px-8 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs sm:text-sm transition shadow-sm cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        {{-- ================= MODAL 2: SUKSES (Matching Figma) ================= --}}
        <div x-show="showSuccess" x-cloak x-transition class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
            <div class="bg-white rounded-3xl p-8 text-center max-w-sm w-full shadow-2xl border border-slate-100">
                <!-- Green Checkmark Circle -->
                <div class="w-14 h-14 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-md">
                    ✓
                </div>
                <h3 class="text-xs sm:text-sm font-bold text-slate-800 mb-6 leading-relaxed">
                    Selamat! Anda telah terdaftar pada acara ini. Silahkan cek email Anda untuk informasi lebih lanjut.
                </h3>
                <button type="button" @click="showSuccess = false; window.location.reload();"
                    class="px-10 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs sm:text-sm transition shadow-sm cursor-pointer">
                    Selesai
                </button>
            </div>
        </div>

    </div>

    @include('layouts.footer')
@endsection
