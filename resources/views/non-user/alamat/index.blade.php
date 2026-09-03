@extends('layouts.index')
@section('content')
    {{-- <form action="{{ route('profile.update', Auth::user()->pelamar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') --}}
    <div class="flex justify-center py-8 mt-10">
        <div class="w-full max-w-6xl bg-white p-4 sm:p-6">

            <!-- Header Profil -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Profil Akun</h2>
                <a href="{{ route('profile.index') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-md transition shadow-xs">
                    ← Kembali
                </a>
            </div>

            <div
                class="border border-orange-400 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <!-- Foto Profile -->
                <div class="flex items-center space-x-4 md:ml-5">
                    @if (Auth::user()->pelamar?->img_profile)
                        <img id="pp" class="w-24 h-24 object-cover rounded-full border-2 border-orange-400"
                            src="{{ asset('storage/' . Auth::user()->pelamar->img_profile) }}" alt="Profile">
                    @else
                        <img id="pp" class="w-24 h-24 object-cover rounded-full border-2 border-orange-400"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&color=fff&size=128"
                            alt="Profile">
                    @endif
                </div>

                <!-- Tombol kanan -->
                <div class="flex justify-center md:justify-end w-full md:w-auto">
                    <a href="{{ route('cv.download', Auth::user()->pelamar->id) }}"
                        class="bg-orange-500 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-orange-600 w-full sm:w-auto text-center">
                        Unduh CV
                    </a>
                </div>
            </div>

            {{-- content --}}
            <div class="my-8">
                <div class="flex items-center justify-between border-b border-orange-500 pb-2 mb-4">
                    <h2 class="text-base font-bold text-gray-800">Alamat</h2>
                    <span class="text-xs text-gray-500 font-semibold bg-gray-100 px-2.5 py-1 rounded-full border border-gray-200">
                        {{ $alamatCount }} / 3 Alamat Terpakai
                    </span>
                </div>

                <!-- Error & Success -->
                @if (session('success'))
                    <div class="p-3 mb-4 bg-green-100 border border-green-200 text-green-700 rounded-md text-xs font-medium">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-3 mb-4 bg-red-100 border border-red-200 text-red-700 rounded-md text-xs font-medium">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="p-3 mb-4 bg-red-100 border border-red-200 text-red-700 rounded-md text-xs font-medium">
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
                        <div class="p-4 bg-orange-500 text-white rounded-lg shadow-sm flex flex-col justify-between min-h-[170px]">
                            <div>
                                <div class="flex items-center justify-between mb-2 pb-1.5 border-b border-orange-400">
                                    <h3 class="text-sm font-bold text-white truncate max-w-[150px]">
                                        {{ $almt->label ?: 'Alamat Utama' }}
                                    </h3>
                                    @if($loop->first)
                                        <span class="bg-white text-orange-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-xs">
                                            Utama
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs font-medium text-white leading-relaxed line-clamp-2">
                                    {{ implode(' ', array_filter([$almt->desa, $almt->kecamatan, $almt->kota, $almt->provinsi, $almt->kode_pos])) }}
                                </p>

                                @if($almt->detail && $almt->detail !== $almt->desa)
                                    <p class="text-[11px] text-orange-100 leading-tight font-normal mt-1 truncate">
                                        {{ $almt->detail }}
                                    </p>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2 mt-3 pt-2">
                                <a class="py-1.5 bg-white hover:bg-orange-50 text-orange-600 font-bold text-center rounded text-xs shadow-xs transition"
                                    href="{{ route('alamat.edit', $almt->id) }}">
                                    Edit
                                </a>

                                <form action="{{ route('alamat.destroy', $almt->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full py-1.5 bg-white/95 hover:bg-white text-orange-600 font-bold rounded text-xs shadow-xs transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- Tombol Tambah Alamat (+ Card Setara Compact) -->
                    @if ($alamatCount < 3)
                        <a href="{{ route('form_alamat') }}"
                            class="p-4 border-2 border-dashed border-orange-400 hover:border-orange-500 bg-orange-50/40 hover:bg-orange-100/50 rounded-lg shadow-xs flex flex-col items-center justify-center min-h-[170px] transition text-orange-500 group cursor-pointer">
                            <span class="w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center text-2xl font-bold mb-2 group-hover:scale-110 transition-transform shadow-xs">
                                +
                            </span>
                            <span class="font-bold text-xs text-orange-600">Tambah Alamat</span>
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
