@if ($d->published_at && (!$d->expired_at || $d->expired_at > now()))
    <div x-cloak x-data="{ open: false, showConfirm: false, showSuccess: false }"
        class="bg-white border border-slate-200/90 rounded-2xl p-5 md:p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 relative group flex flex-col justify-between cursor-pointer border-l-4 border-l-transparent hover:border-l-orange-500">

        <div>
            {{-- Header Badges & Option Menu --}}
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex flex-wrap items-center gap-1.5">
                    @if (!is_null($d->boosted_until))
                        <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                            <i class="ph ph-rocket-launch text-xs"></i> Boosted
                        </span>
                    @endif

                    @if ($d->rekomendasi !== null)
                        <span class="bg-sky-100 text-sky-700 text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                            Direkomendasikan
                        </span>
                    @endif

                    @if ($d->urgent ?? true)
                        <span class="bg-rose-100 text-rose-700 text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                            dibutuhkan segera
                        </span>
                    @endif
                </div>

                {{-- Option Menu (3 dots) --}}
                <div x-data="{ showMenu: false }" class="relative shrink-0">
                    <button @click.stop="showMenu = !showMenu"
                        class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition">
                        <i class="ph ph-dots-three-vertical text-xl"></i>
                    </button>

                    <!-- Share Popup Menu -->
                    <div x-show="showMenu" @click.outside="showMenu = false" @click.stop x-transition x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-slate-100 z-30 py-2">
                        <!-- LinkedIn -->
                        <a href="{{ route('lowongan.share', ['platform' => 'linkedin', 'companySlug' => $d->perusahaan->slug, 'jobSlug' => $d->slug]) }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition">
                            <i class="ph ph-linkedin-logo text-lg text-blue-700"></i> LinkedIn
                        </a>
                        <!-- Email -->
                        <a href="{{ route('lowongan.share', ['platform' => 'email', 'companySlug' => $d->perusahaan->slug, 'jobSlug' => $d->slug]) }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition">
                            <i class="ph ph-envelope text-lg text-red-500"></i> Gmail
                        </a>
                        <!-- WhatsApp -->
                        <a href="{{ route('lowongan.share', ['platform' => 'whatsapp', 'companySlug' => $d->perusahaan->slug, 'jobSlug' => $d->slug]) }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition">
                            <i class="ph ph-whatsapp-logo text-lg text-green-600"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            {{-- Job Title & Perusahaan Logo --}}
            <div class="flex items-start justify-between gap-4 mb-3">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-slate-900 text-lg md:text-xl group-hover:text-orange-600 transition-colors line-clamp-1">
                        {{ $d->nama }}
                    </h3>
                    <p class="text-sm font-semibold text-slate-600 mt-0.5 truncate flex items-center gap-1.5">
                        <i class="ph ph-buildings text-slate-400"></i> {{ $d->perusahaan->nama_perusahaan ?? 'Perusahaan' }}
                    </p>
                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                        <i class="ph ph-map-pin text-orange-500 text-sm"></i> {{ $d->alamat ?? $d->perusahaan->alamat ?? 'Lokasi tidak ditentukan' }}
                    </p>
                </div>

                <div class="w-14 h-14 min-w-[56px] min-h-[56px] max-w-[56px] max-h-[56px] rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-white p-0.5 shrink-0 flex items-center justify-center">
                    @if (!empty($d->perusahaan->img_profile))
                        <img src="{{ asset('storage/' . $d->perusahaan->img_profile) }}" alt="Logo"
                            class="w-full h-full object-cover rounded-lg max-w-full max-h-full">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($d->perusahaan->nama_perusahaan ?? 'P') }}&background=f97316&color=fff&size=128"
                            alt="Logo" class="w-full h-full object-cover rounded-lg max-w-full max-h-full">
                    @endif
                </div>
            </div>

            {{-- Rentang Gaji --}}
            <div class="my-3">
                <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-800 font-bold px-3 py-1.5 rounded-lg text-xs border border-slate-200/80">
                    <i class="ph ph-currency-dollar text-orange-500 text-sm"></i>
                    Rp {{ number_format($d->gaji_awal, 0, ',', '.') }} – Rp {{ number_format($d->gaji_akhir, 0, ',', '.') }} / bulan
                </span>
            </div>

            {{-- Indicator Lamar Cepat --}}
            <div class="flex items-center gap-1.5 text-xs font-bold text-orange-600 mb-3">
                <i class="ph-fill ph-paper-plane-right text-sm"></i> Lamar dengan cepat
            </div>

            {{-- Deskripsi / Bullet highlights --}}
            @if (!empty($d->deskripsi))
                <div class="text-xs text-slate-600 space-y-1 mb-4 line-clamp-2 leading-relaxed">
                    {!! Str::limit(strip_tags($d->deskripsi), 130) !!}
                </div>
            @endif
        </div>

        {{-- Card Footer --}}
        <div class="flex items-center justify-between border-t border-slate-100 pt-3 mt-2 text-xs text-slate-400">
            <span class="flex items-center gap-1">
                <i class="ph ph-clock text-slate-400"></i> Aktif {{ $d->published_at ? $d->published_at->diffForHumans() : 'Baru saja' }}
            </span>

            {{-- Bookmark Button --}}
            @auth
                @php
                    $sudahSimpan = Auth::user()->pelamar
                        ? Auth::user()->pelamar->simpanLowongans()->where('lowongan_id', $d->id)->exists()
                        : false;
                @endphp

                @if (!$sudahSimpan)
                    <form action="{{ route('simpan-lowongan.store') }}" method="POST" @click.stop>
                        @csrf
                        <input type="hidden" name="lowongan_id" value="{{ $d->id }}">
                        <button type="submit" class="text-slate-400 hover:text-orange-600 transition p-1" title="Simpan Lowongan">
                            <i class="ph ph-bookmark-simple text-xl"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('simpan-lowongan.destroy', $d->id) }}" method="POST" @click.stop>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-orange-600 hover:text-red-500 transition p-1" title="Hapus dari Simpan">
                            <i class="ph-fill ph-bookmark-simple text-xl"></i>
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        {{-- Modal Konfirmasi Lamar --}}
        <div x-show="showConfirm" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4" x-cloak>
            <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full shadow-2xl">
                <h2 class="text-lg font-bold mb-2 text-slate-800">Konfirmasi Lamaran</h2>
                <p class="text-sm text-slate-600 mb-6">CV Anda akan dikirimkan ke <b>{{ $d->perusahaan->nama_perusahaan ?? 'Perusahaan' }}</b></p>
                <div class="flex justify-center gap-3">
                    <button @click="showConfirm = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-sm transition">Batal</button>
                    <button @click.prevent="
                        fetch('{{ route('lamar.cepat', $d->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.unauthenticated) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Login Diperlukan',
                                    text: data.message,
                                    confirmButtonText: 'Login Sekarang',
                                    confirmButtonColor: '#f97316',
                                }).then(() => { window.location.href = data.redirect; });
                                return;
                            }
                            if (data.success) {
                                showConfirm = false;
                                showSuccess = true;
                                return;
                            }
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message ?? 'Terjadi kesalahan.' });
                        })
                    " class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl text-sm transition shadow-md">Kirim Lamaran</button>
                </div>
            </div>
        </div>

        {{-- Modal Sukses Lamar --}}
        <div x-show="showSuccess" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4" x-cloak>
            <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full shadow-2xl">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold">✓</div>
                <h2 class="text-lg font-bold mb-2 text-slate-800">Lamaran Terkirim!</h2>
                <p class="text-sm text-slate-600 mb-6">Lamaran Anda berhasil dikirim ke {{ $d->perusahaan->nama_perusahaan ?? 'Perusahaan' }}.</p>
                <button @click="showSuccess = false" class="px-6 py-2.5 bg-orange-500 text-white font-semibold rounded-xl text-sm transition">Tutup</button>
            </div>
        </div>

    </div>
@endif
