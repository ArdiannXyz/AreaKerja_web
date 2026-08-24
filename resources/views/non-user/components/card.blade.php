@if ($d->published_at && (!$d->expired_at || $d->expired_at > now()))
    <div x-cloak x-data="{ open: false, showConfirm: false, showSuccess: false }"
        class="bg-white border border-slate-200/90 rounded-2xl p-5 md:p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 relative group flex flex-col justify-between h-full cursor-pointer border-l-4 border-l-transparent hover:border-l-orange-500">

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

                {{-- Bookmark Button (AJAX Top Right Header) --}}
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
                            class="transition p-1.5 rounded-full hover:bg-orange-50 cursor-pointer text-slate-400 hover:text-orange-600"
                            :title="saved ? 'Hapus dari Simpan' : 'Simpan Lowongan'">
                            
                            <i x-show="!saved" class="ph ph-bookmark-simple text-xl text-slate-400 hover:text-orange-600 transition"></i>
                            <i x-show="saved" class="ph-fill ph-bookmark-simple text-xl text-orange-600 hover:text-orange-700 transition"></i>
                        </button>
                    </div>
                @else
                    <a href="{{ route('login') }}" @click.stop
                        class="p-1.5 text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-full transition shrink-0"
                        title="Simpan Lowongan">
                        <i class="ph ph-bookmark-simple text-xl"></i>
                    </a>
                @endauth
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

            {{-- Rentang Gaji (Solid Orange Background Bar) --}}
            <div class="my-3">
                <span class="inline-block bg-orange-500 text-white font-extrabold px-4 py-1.5 rounded-md text-xs sm:text-sm shadow-sm">
                    Rp {{ number_format($d->gaji_awal, 0, ',', '.') }} per bulan
                </span>
            </div>

            {{-- Indicator Lamar Cepat --}}
            <div class="flex items-center gap-2 text-xs md:text-sm font-extrabold text-orange-600 mb-3">
                <i class="ph-fill ph-caret-right text-base text-orange-600"></i> Lamar dengan cepat:
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
