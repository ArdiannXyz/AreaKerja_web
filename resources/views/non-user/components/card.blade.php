@php
    $d = $lowongan ?? $d ?? null;
@endphp
@if ($d && $d->published_at && (!$d->expired_at || $d->expired_at > now()))
    <div x-cloak x-data="{ open: false, showConfirm: false, showSuccess: false }"
        class="bg-white border border-[#00509d] rounded-2xl p-5 md:p-6 shadow-sm hover:shadow-lg transition-all duration-300 relative group flex flex-col justify-between h-full cursor-pointer">

        <div>
            {{-- Header Badges & Bookmark Icon --}}
            <div class="flex items-start justify-between gap-3 mb-2">
                <div class="flex flex-wrap items-center gap-1.5">
                    @if (($d->status ?? 'buka') === 'tutup')
                        <span class="bg-rose-100 text-rose-700 text-[11px] font-bold px-2.5 py-0.5 rounded shadow-sm border border-rose-200">
                            Ditutup
                        </span>
                    @else
                        <span class="bg-[#d7ebfc] text-[#00509d] text-[11px] font-bold px-2.5 py-0.5 rounded shadow-sm">
                            dibutuhkan segera
                        </span>
                    @endif

                    @if (!is_null($d->boosted_until))
                        <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-0.5 rounded shadow-sm">
                            Boosted
                        </span>
                    @endif

                    @if ($d->rekomendasi !== null)
                        <span class="bg-sky-100 text-sky-700 text-[11px] font-bold px-2.5 py-0.5 rounded shadow-sm">
                            Direkomendasikan
                        </span>
                    @endif
                </div>

                {{-- Bookmark Button --}}
                @auth
                    @php
                        $sudahSimpan = Auth::user()->pelamar
                            ? Auth::user()->pelamar->simpanLowongans()->where('lowongan_id', $d->id)->exists()
                            : false;
                    @endphp

                    <div x-data="{ saved: {{ $sudahSimpan ? 'true' : 'false' }}, loading: false }" @click.stop class="shrink-0">
                        <button type="button"
                            @click.prevent.stop="
                                if (loading) return;
                                loading = true;
                                let targetUrl = saved ? '/pelamar/simpan-lowongan/{{ $d->id }}' : '{{ route('simpan-lowongan.store') }}';
                                let reqMethod = saved ? 'DELETE' : 'POST';
                                
                                fetch(targetUrl, {
                                    method: reqMethod,
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    },
                                    body: reqMethod === 'POST' ? JSON.stringify({ lowongan_id: {{ $d->id }} }) : null
                                })
                                .then(res => res.json())
                                .then(data => {
                                    loading = false;
                                    if (data.success) {
                                        saved = !saved;
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'success',
                                            title: saved ? 'Lowongan disimpan' : 'Dihapus dari simpanan',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'error',
                                            title: data.message || 'Gagal menyimpan',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                })
                                .catch(err => {
                                    loading = false;
                                    console.error(err);
                                });
                            "
                            class="transition p-1 text-slate-400 hover:text-[#00509d] cursor-pointer"
                            :title="saved ? 'Hapus dari Simpan' : 'Simpan Lowongan'">
                            
                            <i x-show="!saved" class="ph ph-bookmark-simple text-2xl text-slate-400 hover:text-[#00509d] transition"></i>
                            <i x-show="saved" class="ph-fill ph-bookmark-simple text-2xl text-[#00509d] transition"></i>
                        </button>
                    </div>
                @else
                    <a href="{{ route('login') }}" @click.stop
                        class="p-1 text-slate-400 hover:text-[#00509d] transition shrink-0"
                        title="Simpan Lowongan">
                        <i class="ph ph-bookmark-simple text-2xl"></i>
                    </a>
                @endauth
            </div>

            {{-- Job Title --}}
            <h3 class="font-bold text-[#004e98] text-lg group-hover:text-[#003d7a] transition-colors leading-tight mb-2">
                {{ $d->nama }}
            </h3>

            {{-- Perusahaan Logo, Nama & Lokasi --}}
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-7 h-7 min-w-[28px] min-h-[28px] max-w-[28px] max-h-[28px] rounded overflow-hidden shrink-0 flex items-center justify-center">
                    @if (!empty($d->perusahaan->img_profile))
                        <img src="{{ asset('storage/' . $d->perusahaan->img_profile) }}" alt="Logo"
                            class="w-full h-full object-contain">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($d->perusahaan->nama_perusahaan ?? 'P') }}&background=00509d&color=fff&size=64"
                            alt="Logo" class="w-full h-full object-cover rounded">
                    @endif
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-700 leading-tight">
                        {{ $d->perusahaan->nama_perusahaan ?? 'Perusahaan' }}
                    </p>
                    <p class="text-[11px] text-slate-500 leading-tight mt-0.5">
                        {{ $d->alamat ?? $d->perusahaan->alamat ?? 'Jakarta' }}
                    </p>
                </div>
            </div>

            {{-- Rentang Gaji (Solid Navy Blue Background Bar) --}}
            <div class="my-2.5">
                <span class="inline-block bg-[#004e98] text-white font-bold px-3 py-1.5 rounded-sm text-xs shadow-sm">
                    Rp. {{ number_format($d->gaji_awal, 0, ',', '.') }} per bulan
                </span>
            </div>

            {{-- Indicator Lamar Cepat --}}
            <div class="flex items-center gap-2 text-xs font-bold text-[#004e98] my-2.5">
                <i class="ph-fill ph-navigation-arrow text-base text-[#004e98]"></i>
                <span>Lamar dengan cepat</span>
            </div>

            {{-- Deskripsi / Bullet highlights --}}
            @if (!empty($d->deskripsi))
                <div class="text-xs text-slate-600 leading-relaxed space-y-1 mb-3 line-clamp-2">
                    <span class="inline-block mr-1 font-bold text-slate-700">•</span>{!! Str::limit(strip_tags($d->deskripsi), 120) !!}
                </div>
            @endif
        </div>

        {{-- Card Footer --}}
        <div class="border-t border-slate-100 pt-3 mt-1 text-xs text-slate-400 font-medium">
            <span>{{ $d->published_at ? 'Aktif ' . $d->published_at->diffForHumans() : 'Aktif 2 hari lalu' }}</span>
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
                                    confirmButtonColor: '#00509d',
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
                    " class="px-5 py-2.5 bg-[#004e98] hover:bg-[#003d7a] text-white font-semibold rounded-xl text-sm transition shadow-md">Kirim Lamaran</button>
                </div>
            </div>
        </div>

        {{-- Modal Sukses Lamar --}}
        <div x-show="showSuccess" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4" x-cloak>
            <div class="bg-white rounded-2xl p-6 text-center max-w-sm w-full shadow-2xl">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold">✓</div>
                <h2 class="text-lg font-bold mb-2 text-slate-800">Lamaran Terkirim!</h2>
                <p class="text-sm text-slate-600 mb-6">Lamaran Anda berhasil dikirim ke {{ $d->perusahaan->nama_perusahaan ?? 'Perusahaan' }}.</p>
                <button @click="showSuccess = false" class="px-6 py-2.5 bg-[#004e98] text-white font-semibold rounded-xl text-sm transition">Tutup</button>
            </div>
        </div>

    </div>
@endif
