@extends('layouts.index')
@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-28 pb-16">
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-sm">

            <!-- Header Profil -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-800">Tambah Alamat Baru</h2>
                <a href="{{ route('alamat') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition shadow-xs">
                    ← Kembali
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

                <!-- Tombol Kanan -->
                <div class="flex justify-center md:justify-end w-full md:w-auto">
                    <a href="{{ route('cv.download', Auth::user()->pelamar->id) }}"
                        class="bg-[#00509d] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-[#003d7a] w-full sm:w-auto text-center transition shadow-sm">
                        Unduh CV
                    </a>
                </div>

            </div>

            <!-- Form Alamat -->
            <div class="mt-6">
                <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-3 mb-6">Formulir Alamat</h3>

                <form action="{{ route('alamat.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Label Alamat</label>
                        <input type="text" name="label" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            placeholder="Contoh: Rumah / Kantor / Kos">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Provinsi <span class="text-red-500">*</span></label>
                        <select name="provinsi" id="provinsiSelect" required
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinsis as $p)
                                <option value="{{ $p->nama }}" data-id="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kota / Kabupaten <span class="text-red-500">*</span></label>
                        <select name="kota" id="kotaSelect" required
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                            <option value="">Pilih Kota / Kabupaten</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                        <select name="kecamatan" id="kecamatanSelect" required
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap (Desa / Jalan)</label>
                        <input type="text" name="desa" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            placeholder="Jalan, RT/RW, Desa, Kelurahan">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Detail Alamat</label>
                        <input type="text" name="detail" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            placeholder="Detail lainnya (Cth: Blok/Unit/Patokan)">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Pos</label>
                        <input type="text" name="kode_pos" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            placeholder="Kode Pos">
                    </div>

                    <div class="flex justify-center pt-4">
                        <button class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-8 py-2.5 rounded-xl shadow-sm transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @include('non-user.profile.modal-kategori.modal1')
    @include('non-user.profile.modal-kategori.modal2')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const provinsiSelect = document.getElementById('provinsiSelect');
            const kotaSelect = document.getElementById('kotaSelect');
            const kecamatanSelect = document.getElementById('kecamatanSelect');

            if (kotaSelect) {
                kotaSelect.addEventListener('focus', function() {
                    if (!provinsiSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan Alamat',
                            text: 'Harap pilih Provinsi terlebih dahulu!',
                            confirmButtonColor: '#00509d'
                        }).then(() => {
                            provinsiSelect.focus();
                        });
                    }
                });
            }

            if (kecamatanSelect) {
                kecamatanSelect.addEventListener('focus', function() {
                    if (!kotaSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan Alamat',
                            text: 'Harap pilih Kota/Kabupaten terlebih dahulu!',
                            confirmButtonColor: '#00509d'
                        })}).then(() => {
                            if (!provinsiSelect.value) {
                                provinsiSelect.focus();
                            } else {
                                kotaSelect.focus();
                            }
                        });
                    }
                });
            }

            if (provinsiSelect) {
                provinsiSelect.addEventListener('change', function() {
                    const selectedOpt = this.options[this.selectedIndex];
                    const provId = selectedOpt ? selectedOpt.getAttribute('data-id') : null;
                    const provName = this.value;

                    kotaSelect.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                    if (!provName && !provId) return;

                    // 1. Instant local filter (0ms)
                    let matched = [];
                    if (Array.isArray(LOCAL_KOTAS) && LOCAL_KOTAS.length > 0) {
                        matched = LOCAL_KOTAS.filter(k => {
                            return (provId && String(k.provinsi_id) === String(provId)) ||
                                   (provName && k.nama.toLowerCase().includes(provName.toLowerCase()));
                        });
                    }

                    if (matched.length > 0) {
                        matched.forEach(k => {
                            const opt = document.createElement('option');
                            opt.value = k.nama;
                            opt.setAttribute('data-id', k.id);
                            opt.textContent = k.nama;
                            kotaSelect.appendChild(opt);
                        });
                        return;
                    }

                    // 2. Fallback fetch API
                    kotaSelect.innerHTML = '<option value="">Memuat Kota / Kabupaten...</option>';
                    const target = provId || provName;

                    fetch(`/get-kota/${encodeURIComponent(target)}`)
                        .then(res => res.json())
                        .then(data => {
                            kotaSelect.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(k => {
                                    const opt = document.createElement('option');
                                    opt.value = k.nama;
                                    opt.setAttribute('data-id', k.id);
                                    opt.textContent = k.nama;
                                    kotaSelect.appendChild(opt);
                                });
                            } else {
                                kotaSelect.innerHTML = '<option value="">Kota tidak ditemukan</option>';
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            kotaSelect.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';
                        });
                });
            }

            if (kotaSelect) {
                kotaSelect.addEventListener('change', function() {
                    const selectedOpt = this.options[this.selectedIndex];
                    const kotaId = selectedOpt ? selectedOpt.getAttribute('data-id') : null;
                    const kotaName = this.value;

                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                    if (!kotaName && !kotaId) return;

                    // 1. Instant local filter (0ms)
                    let matched = [];
                    if (Array.isArray(LOCAL_KECAMATANS) && LOCAL_KECAMATANS.length > 0) {
                        matched = LOCAL_KECAMATANS.filter(k => {
                            return (kotaId && String(k.kota_id) === String(kotaId)) ||
                                   (kotaName && k.nama.toLowerCase().includes(kotaName.toLowerCase()));
                        });
                    }

                    if (matched.length > 0) {
                        matched.forEach(k => {
                            const opt = document.createElement('option');
                            opt.value = k.nama;
                            opt.setAttribute('data-id', k.id);
                            opt.textContent = k.nama;
                            kecamatanSelect.appendChild(opt);
                        });
                        return;
                    }

                    // 2. Fallback fetch API
                    kecamatanSelect.innerHTML = '<option value="">Memuat Kecamatan...</option>';
                    const target = kotaId || kotaName;

                    fetch(`/get-kecamatan/${encodeURIComponent(target)}`)
                        .then(res => res.json())
                        .then(data => {
                            if ((!Array.isArray(data) || data.length === 0) && kotaName && target !== kotaName) {
                                return fetch(`/get-kecamatan/${encodeURIComponent(kotaName)}`).then(r => r.json());
                            }
                            return data;
                        })
                        .then(data => {
                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(k => {
                                    const opt = document.createElement('option');
                                    opt.value = k.nama;
                                    opt.setAttribute('data-id', k.id);
                                    opt.textContent = k.nama;
                                    kecamatanSelect.appendChild(opt);
                                });
                            } else {
                                kecamatanSelect.innerHTML = '<option value="">Kecamatan tidak ditemukan</option>';
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        });
                });
            }

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
