@extends('layouts.index')
@section('content')
    {{-- <form action="{{ route('profile.update', Auth::user()->pelamar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-28 pb-16">
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-sm">

            <!-- Header Profil -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-800">Daftar Alamat</h2>
                <a href="{{ route('profile.index') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition shadow-xs">
                    ← Kembali ke Profil
                </a>
            </div>

            <div
                class="border-2 border-[#00509d] rounded-2xl p-6 mb-8 bg-blue-50/20 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <!-- Foto Profile -->
                <div class="flex items-center gap-4">
                    @if (Auth::user()->pelamar?->img_profile)
                        <img id="pp" class="w-20 h-20 object-cover rounded-full border-2 border-[#00509d] shadow-sm"
                            src="{{ asset('storage/' . Auth::user()->pelamar->img_profile) }}" alt="Profile">
                    @else
                        <img id="pp" class="w-20 h-20 object-cover rounded-full border-2 border-[#00509d] shadow-sm"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                            alt="Profile">
                    @endif
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">{{ Auth::user()->pelamar->nama_pelamar ?? Auth::user()->username }}</h3>
                        <p class="text-xs text-slate-500 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Tombol kanan -->
                <div class="flex justify-center md:justify-end w-full md:w-auto">
                    <a href="{{ route('cv.download', Auth::user()->pelamar->id) }}"
                        class="bg-[#00509d] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-[#003d7a] w-full sm:w-auto text-center transition shadow-sm">
                        Unduh CV
                    </a>
                </div>
            </div>

            {{-- content --}}
            <div class="my-6">
                <div class="flex items-center justify-between border-b-2 border-[#00509d] pb-3 mb-6">
                    <h3 class="text-base font-bold text-slate-900">Alamat Tersimpan</h3>
                    <span class="text-xs text-[#00509d] font-bold bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                        {{ $alamatCount }} / 3 Alamat Terpakai
                    </span>
                </div>

                <!-- Error & Success -->
                @if (session('success'))
                    <div class="p-3 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-3 mb-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-bold">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="p-3 mb-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-bold">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- GRID Alamat Compact + Card Tambah Setara -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                    @foreach (Auth::user()->pelamar->alamat_pelamar as $almt)
                        <div class="p-5 bg-[#00509d] text-white rounded-2xl shadow-sm flex flex-col justify-between min-h-[175px]">
                            <div>
                                <div class="flex items-center justify-between mb-2 pb-2 border-b border-blue-400/40">
                                    <h4 class="text-sm font-bold text-white truncate max-w-[150px]">
                                        {{ $almt->label ?: 'Alamat' }}
                                    </h4>
                                    @if($loop->first)
                                        <span class="bg-white text-[#00509d] text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-xs">
                                            Utama
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs font-medium text-blue-50 leading-relaxed line-clamp-2">
                                    {{ implode(', ', array_filter([$almt->desa, $almt->kecamatan, $almt->kota, $almt->provinsi, $almt->kode_pos])) }}
                                </p>

                                @if($almt->detail && $almt->detail !== $almt->desa)
                                    <p class="text-[11px] text-blue-200 leading-tight font-normal mt-1 truncate">
                                        {{ $almt->detail }}
                                    </p>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2 mt-4 pt-2">
                                <a class="py-1.5 bg-white hover:bg-blue-50 text-[#00509d] font-bold text-center rounded-xl text-xs shadow-xs transition"
                                    href="{{ route('alamat.edit', $almt->id) }}">
                                    Edit
                                </a>

                                <form action="{{ route('alamat.destroy', $almt->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full py-1.5 bg-white/90 hover:bg-white text-rose-600 font-bold rounded-xl text-xs shadow-xs transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- Tombol Tambah Alamat (+ Card Setara Compact) -->
                    @if ($alamatCount < 3)
                        <a href="{{ route('form_alamat') }}"
                            class="p-5 border-2 border-dashed border-[#00509d]/40 hover:border-[#00509d] bg-blue-50/30 hover:bg-blue-50/60 rounded-2xl shadow-xs flex flex-col items-center justify-center min-h-[175px] transition text-[#00509d] group cursor-pointer">
                            <span class="w-10 h-10 rounded-full bg-[#00509d] text-white flex items-center justify-center text-xl font-bold mb-2 group-hover:scale-110 transition-transform shadow-xs">
                                +
                            </span>
                            <span class="font-bold text-xs text-[#00509d]">Tambah Alamat</span>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- </form> --}}

    @include('non-user.profile.modal-kategori.modal1')
    @include('non-user.profile.modal-kategori.modal2')

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            console.log("JS siap! Listener aktif.");

            // ===============================
            // STATUS PELAMAR (Trigger Modal 1)
            // ===============================
            const statusSelect = document.getElementById('statusSelect');
            const kategoriInput = document.getElementById('kategoriPelamar');

            if (statusSelect) {
                statusSelect.addEventListener('change', function() {

                    let selected = this.value;
                    let kategori = kategoriInput ? kategoriInput.value : null;

                    console.log("Selected:", selected);
                    console.log("Kategori:", kategori);

                    if (selected === 'Bekerja' && kategori === 'kandidat aktif') {
                        document.getElementById('modalPeringatan').classList.remove('hidden');
                    }
                });
            }

            // ===============================
            // MODAL PERINGATAN
            // ===============================
            const yaPeringatan = document.getElementById('yaPeringatan');
            const tidakPeringatan = document.getElementById('tidakPeringatan');

            if (yaPeringatan) {
                yaPeringatan.onclick = function() {
                    document.getElementById('modalPeringatan').classList.add('hidden');
                    document.getElementById('modalNonaktif').classList.remove('hidden');
                };
            }

            if (tidakPeringatan) {
                tidakPeringatan.onclick = function() {
                    document.getElementById('modalPeringatan').classList.add('hidden');
                    statusSelect.value = ""; // Kembali kosong
                };
            }

            // ===============================
            // MODAL NONAKTIF
            // ===============================
            const yaNonaktif = document.getElementById('yaNonaktif');
            const tidakNonaktif = document.getElementById('tidakNonaktif');

            if (yaNonaktif) {
                yaNonaktif.onclick = function() {

                    fetch("/pelamar/update-kategori/{{ $pelamar->id }}", {
                            method: "PUT",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                kategori: "kandidat nonaktif"
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            location.reload();
                        });

                };
            }

            if (tidakNonaktif) {
                tidakNonaktif.onclick = function() {
                    document.getElementById('modalNonaktif').classList.add('hidden');
                    statusSelect.value = "";
                };
            }

        });
    </script>
    @include('layouts.footer')
@endsection
