@extends('layouts.index')
@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-28 pb-16">
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-sm">

            <!-- Header Profil -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-800">Edit Alamat</h2>
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
                <h3 class="text-base font-bold text-slate-900 border-b-2 border-[#00509d] pb-3 mb-6">Edit Formulir Alamat</h3>

                <form action="{{ route('alamat.update', $data->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Label Alamat</label>
                        <input type="text" name="label" value="{{ old('label', $data->label) }}"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Provinsi <span class="text-red-500">*</span></label>
                        <select name="provinsi" id="provinsiSelect" required
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinsis as $p)
                                <option value="{{ $p->nama }}" data-id="{{ $p->id }}" {{ strcasecmp(old('provinsi', $data->provinsi ?? ''), $p->nama) === 0 ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kota / Kabupaten <span class="text-red-500">*</span></label>
                        <select name="kota" id="kotaSelect" required
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                            <option value="">Pilih Kota / Kabupaten</option>
                            @if(isset($kotas) && $kotas->count() > 0)
                                @foreach($kotas as $k)
                                    <option value="{{ $k->nama }}" data-id="{{ $k->id }}" {{ strcasecmp(old('kota', $data->kota ?? ''), $k->nama) === 0 ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            @elseif(old('kota', $data->kota ?? ''))
                                <option value="{{ old('kota', $data->kota) }}" selected>{{ old('kota', $data->kota) }}</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                        <select name="kecamatan" id="kecamatanSelect" required
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                            <option value="">Pilih Kecamatan</option>
                            @if(isset($kecamatans) && $kecamatans->count() > 0)
                                @foreach($kecamatans as $kc)
                                    <option value="{{ $kc->nama }}" {{ strcasecmp(old('kecamatan', $data->kecamatan ?? ''), $kc->nama) === 0 ? 'selected' : '' }}>
                                        {{ $kc->nama }}
                                    </option>
                                @endforeach
                            @elseif(old('kecamatan', $data->kecamatan ?? ''))
                                <option value="{{ old('kecamatan', $data->kecamatan) }}" selected>{{ old('kecamatan', $data->kecamatan) }}</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap (Desa / Jalan)</label>
                        <input type="text" name="desa" value="{{ old('desa', $data->desa) }}"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Detail Alamat</label>
                        <input type="text" name="detail" value="{{ old('detail', $data->detail) }}"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $data->kode_pos) }}"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                    </div>

                    <div class="flex justify-center pt-4">
                        <button class="bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-8 py-2.5 rounded-xl shadow-sm transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const LOCAL_KOTAS = @json($allKotas ?? []);
        const LOCAL_KECAMATANS = @json($allKecamatans ?? []);

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
                            confirmButtonColor: '#f97316'
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
                            text: 'Harap pilih Kota / Kabupaten terlebih dahulu!',
                            confirmButtonColor: '#f97316'
                        }).then(() => {
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

                // Auto populate on page load if province is selected
                if (provinsiSelect.value) {
                    const selectedOpt = provinsiSelect.options[provinsiSelect.selectedIndex];
                    const provId = selectedOpt ? selectedOpt.getAttribute('data-id') : null;
                    const provName = provinsiSelect.value;
                    const currentKota = "{{ old('kota', $data->kota) }}";

                    if (provName) {
                        const target = provId || provName;
                        fetch(`/get-kota/${encodeURIComponent(target)}`)
                            .then(res => res.json())
                            .then(data => {
                                if ((!Array.isArray(data) || data.length === 0) && provName && target !== provName) {
                                    return fetch(`/get-kota/${encodeURIComponent(provName)}`).then(r => r.json());
                                }
                                return data;
                            })
                            .then(data => {
                                if (Array.isArray(data) && data.length > 0) {
                                    kotaSelect.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';
                                    data.forEach(k => {
                                        const isSel = (currentKota && (k.nama.toLowerCase() === currentKota.toLowerCase())) ? 'selected' : '';
                                        kotaSelect.insertAdjacentHTML('beforeend', `<option value="${k.nama}" data-id="${k.id}" ${isSel}>${k.nama}</option>`);
                                    });
                                }
                            })
                            .catch(err => console.error(err));
                    }
                }
            }

            if (kotaSelect) {
                const loadKecamatan = function() {
                    const selectedOpt = kotaSelect.options[kotaSelect.selectedIndex];
                    const kotaId = selectedOpt ? selectedOpt.getAttribute('data-id') : null;
                    const kotaName = kotaSelect.value;
                    const currentKecamatan = "{{ old('kecamatan', $data->kecamatan ?? '') }}";

                    if (!kotaName && !kotaId) {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        return;
                    }

                    // 1. Instant local filter (0ms)
                    let matchedKec = [];
                    if (Array.isArray(LOCAL_KECAMATANS) && LOCAL_KECAMATANS.length > 0) {
                        matchedKec = LOCAL_KECAMATANS.filter(k => {
                            return (kotaId && String(k.kota_id) === String(kotaId)) ||
                                   (kotaName && k.nama.toLowerCase().includes(kotaName.toLowerCase()));
                        });
                    }

                    if (matchedKec.length > 0) {
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        matchedKec.forEach(k => {
                            const opt = document.createElement('option');
                            opt.value = k.nama;
                            opt.setAttribute('data-id', k.id);
                            opt.textContent = k.nama;
                            if (currentKecamatan && k.nama.toLowerCase() === currentKecamatan.toLowerCase()) {
                                opt.selected = true;
                            }
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
                                    if (currentKecamatan && k.nama.toLowerCase() === currentKecamatan.toLowerCase()) {
                                        opt.selected = true;
                                    }
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
                };

                kotaSelect.addEventListener('change', loadKecamatan);

                // Auto populate kecamatan on page load if kota is selected and kecamatan options <= 1
                if (kotaSelect.value && kecamatanSelect.options.length <= 1) {
                    loadKecamatan();
                }
            }
        });
    </script>


    @include('layouts.footer')
@endsection
