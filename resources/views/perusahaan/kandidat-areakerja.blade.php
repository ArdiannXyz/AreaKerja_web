@extends('layouts.index-perusahaan')
@section('content')
    <!-- Hero Section -->
    <section class="relative">
        <img src="{{ header_image('header_daftar_kandidat', 'images/ntap.png') }}"
            alt="Header Image" class="w-full h-[520px] md:h-[580px] object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute bottom-32 sm:bottom-36 left-6 sm:left-12 lg:left-24 text-white">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold max-w-2xl mb-2 sm:mb-3 tracking-tight">
                Kandidat Area Kerja
            </h1>
            <p class="text-sm sm:text-base font-normal max-w-xl text-gray-200 leading-relaxed">
                Rekrut karyawan terakreditasi <br class="hidden sm:inline"> di Area Kerja
            </p>
        </div>
    </section>

    <!-- Bar Pencarian & Koin (Simetris & Sejajar) -->
    <div class="max-w-6xl mx-auto px-4 -mt-10 sm:-mt-12 relative z-20">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            
            <!-- Box Filter Pencarian Kandidat -->
            <form action="{{ route('perusahaan.kandidat.ak') }}" method="get"
                class="flex-1 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 sm:p-2.5">
                <div class="flex flex-wrap lg:flex-nowrap items-center divide-y lg:divide-y-0 lg:divide-x divide-slate-200">
                    
                    <!-- Skill -->
                    <div class="flex items-center gap-2 px-3 py-2 flex-1 min-w-[150px]">
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <select name="skill"
                            class="w-full bg-transparent border-0 text-slate-700 text-sm font-semibold focus:ring-0 cursor-pointer p-0">
                            <option value="">Skill</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill }}" {{ request('skill') == $skill ? 'selected' : '' }}>
                                    {{ $skill }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Umur -->
                    <div class="flex items-center gap-2 px-3 py-2 flex-1 min-w-[130px]">
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <select name="umur"
                            class="w-full bg-transparent border-0 text-slate-700 text-sm font-semibold focus:ring-0 cursor-pointer p-0">
                            <option value="">Umur</option>
                            @foreach ($umurRange as $umur)
                                <option value="{{ $umur }}" {{ request('umur') == $umur ? 'selected' : '' }}>
                                    {{ $umur }} Tahun
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender -->
                    <div class="flex items-center gap-2 px-3 py-2 flex-1 min-w-[130px]">
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <select name="gender"
                            class="w-full bg-transparent border-0 text-slate-700 text-sm font-semibold focus:ring-0 cursor-pointer p-0">
                            <option value="">Gender</option>
                            <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Button Cari Kandidat -->
                    <div class="p-1 shrink-0 w-full lg:w-auto">
                        <button type="submit"
                            class="w-full lg:w-auto bg-[#00509d] hover:bg-[#003d7a] text-white font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-sm cursor-pointer whitespace-nowrap">
                            Cari Kandidat
                        </button>
                    </div>
                </div>
            </form>

            <!-- Box Koin (Simetris Sejajar dengan Form Pencarian) -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 px-5 py-2.5 flex items-center justify-between gap-4 shrink-0 min-w-[190px]">
                <div class="text-left">
                    <div class="flex items-center gap-1.5">
                        <span id="saldoKoinHeader" class="text-2xl font-black text-amber-500 font-mono">
                            {{ number_format($perusahaan->koin_perusahaan ?? 0, 0, ',', '.') }}
                        </span>
                        <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-6 h-6 object-contain">
                    </div>
                    <button type="button" onclick="toggleModal()"
                        class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 transition cursor-pointer">
                        Top Up Koin
                    </button>
                </div>
                <button type="button" onclick="toggleModal()" title="Top Up Koin"
                    class="w-9 h-9 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center font-bold text-xl shadow-sm transition shrink-0 cursor-pointer">
                    <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Tabel Kandidat Sesuai Mockup UI -->
    <div class="max-w-6xl mx-auto px-4 mt-8 mb-16">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-[#00509d] text-white text-center">
                            <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-left">Nama</th>
                            <th class="py-4 px-4 font-bold text-xs uppercase tracking-wider">Skill</th>
                            <th class="py-4 px-4 font-bold text-xs uppercase tracking-wider">Umur</th>
                            <th class="py-4 px-4 font-bold text-xs uppercase tracking-wider">Pengalaman</th>
                            <th class="py-4 px-4 font-bold text-xs uppercase tracking-wider">Gender</th>
                            <th class="py-4 px-4 font-bold text-xs uppercase tracking-wider">CV</th>
                            <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pelamars as $p)
                            @php
                                $isCvUnlocked = in_array($p->id, $boughtCvPelamarIds ?? []);
                                $skillsList = $p->skill->isNotEmpty()
                                    ? $p->skill->pluck('skill')->take(2)->implode(', ')
                                    : ($p->skills ? (is_array($p->skills) ? implode(', ', array_slice($p->skills, 0, 2)) : $p->skills) : '-');
                                $pengalamanTeks = $p->pengalaman_kerja->first()?->posisi ?? 'Expert';
                            @endphp
                            <tr class="hover:bg-blue-50/40 transition text-center" id="row-kandidat-{{ $p->id }}">
                                <td class="py-4 px-6 text-left">
                                    <div class="font-bold text-slate-800 text-sm">{{ $p->nama_pelamar }}</div>
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $skillsList }}
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $p->umur ? $p->umur . ' Tahun' : '-' }}
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $pengalamanTeks }}
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $p->gender_singkat ?? '-' }}
                                </td>
                                <td class="py-4 px-4">
                                    <div id="cv-action-{{ $p->id }}">
                                        @if ($isCvUnlocked)
                                            <a href="{{ route('cv.download', $p->id) }}"
                                                class="inline-flex items-center justify-center w-28 py-1.5 border-2 border-[#00509d] text-[#00509d] hover:bg-[#00509d] hover:text-white font-bold rounded-lg text-xs transition shadow-2xs">
                                                Download
                                            </a>
                                        @else
                                            <button type="button"
                                                class="btn-beli-cv inline-flex items-center justify-center w-28 py-1.5 border-2 border-[#00509d] text-[#00509d] hover:bg-blue-50 font-bold rounded-lg text-xs transition shadow-2xs cursor-pointer"
                                                data-id="{{ $p->id }}"
                                                data-nama="{{ $p->nama_pelamar }}">
                                                Beli
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <button type="button"
                                        class="btn-rekrut w-28 py-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-lg text-xs transition shadow-sm cursor-pointer"
                                        data-id="{{ $p->id }}"
                                        data-nama="{{ $p->nama_pelamar }}">
                                        Rekrut
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-4 text-center text-slate-400 font-medium">
                                    Tidak ada kandidat aktif yang sesuai dengan kriteria filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Beli CV (5 Koin) -->
    <div id="modalConfirmBeliCv"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 transition-all">
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-sm text-center animate-fade-in border border-slate-100">
            <div class="w-14 h-14 bg-amber-50 text-amber-500 border border-amber-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-8 h-8 object-contain">
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Buka CV Kandidat</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Buka dan unduh CV pelamar <span id="namaPelamarBeliCv" class="font-bold text-slate-800"></span> seharga:
            </p>
            <div class="bg-amber-50/70 border border-amber-200 rounded-2xl py-3 px-4 mb-3 flex items-center justify-center gap-2">
                <span class="text-3xl font-black text-amber-500 font-mono">{{ \App\Models\SiteSetting::get('harga_koin_buka_cv', 5) }}</span>
                <span class="text-xs font-bold text-amber-800">Koin AreaKerja</span>
            </div>
            <p class="text-[11px] text-slate-400 mb-5">
                Saldo koin Anda: <span id="saldoKoinModalCv" class="font-bold text-slate-700">{{ $perusahaan->koin_perusahaan ?? 0 }}</span> Koin
            </p>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeModalBeliCv()"
                    class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="button" id="btnProsesBeliCv"
                    class="w-1/2 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold rounded-xl text-xs transition shadow-sm cursor-pointer">
                    Beli Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Koin Tidak Cukup untuk CV -->
    <div id="modalKoinKurangCv"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 transition-all">
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-sm text-center animate-fade-in border border-slate-100">
            <div class="w-14 h-14 bg-rose-50 text-rose-500 border border-rose-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Koin Tidak Mencukupi</h3>
            <p class="text-xs text-slate-500 mb-5 leading-relaxed">
                Saldo koin perusahaan Anda tidak mencukupi untuk membuka CV kandidat ini (Minimal {{ \App\Models\SiteSetting::get('harga_koin_buka_cv', 5) }} koin). Silakan lakukan top up koin.
            </p>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeModalKoinKurangCv()"
                    class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="button" onclick="closeModalKoinKurangCv(); toggleModal()"
                    class="w-1/2 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs transition shadow-sm cursor-pointer">
                    Top Up Koin
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Lowongan untuk Rekrut -->
    <div id="modalPilihLowongan"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 transition-all">
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-md text-center animate-fade-in border border-slate-100">
            <div class="w-14 h-14 bg-blue-50 text-[#00509d] border border-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Rekrut Kandidat</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Pilih lowongan pekerjaan untuk merekrut <span id="namaPelamarRekrut" class="font-bold text-slate-800"></span>:
            </p>
            <div class="mb-5 text-left">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Posisi Lowongan yang Dibuka:</label>
                <select id="selectLowongan"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]">
                    <option value="">-- Pilih Lowongan --</option>
                    @if(auth()->user()->perusahaan && auth()->user()->perusahaan->lowonganPerusahaans)
                        @foreach (auth()->user()->perusahaan->lowonganPerusahaans as $low)
                            <option value="{{ $low->id }}">{{ $low->nama }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="btnCancelRekrut"
                    class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="button" id="btnLanjutRekrut"
                    class="w-1/2 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold rounded-xl text-xs transition shadow-sm cursor-pointer">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Rekrut (100 Koin) -->
    <div id="modalKonfirmasi"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 transition-all">
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-sm text-center animate-fade-in border border-slate-100">
            <div class="w-14 h-14 bg-amber-50 text-amber-500 border border-amber-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <img src="{{ asset('images/coin.png') }}" alt="coin" class="w-8 h-8 object-contain">
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Konfirmasi Rekrutmen</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Biaya rekrutmen kandidat ke lowongan perusahaan:
            </p>
            <div class="bg-amber-50/70 border border-amber-200 rounded-2xl py-3 px-4 mb-4 flex items-center justify-center gap-2">
                <span class="text-3xl font-black text-amber-500 font-mono">100</span>
                <span class="text-xs font-bold text-amber-800">Koin AreaKerja</span>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="btnKonfirmasiBatal"
                    class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="button" id="btnKonfirmasiBeli"
                    class="w-1/2 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs transition shadow-sm cursor-pointer">
                    Konfirmasi Rekrut
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        let currentCoins = {{ $perusahaan->koin_perusahaan ?? 0 }};
        let selectedPelamarId = null;
        let selectedPelamarNama = '';
        let selectedLowonganId = null;

        function updateCoinDisplay(newAmount) {
            currentCoins = newAmount;
            const formatted = new Intl.NumberFormat('id-ID').format(newAmount);
            const elHeader = document.getElementById('saldoKoinHeader');
            if (elHeader) elHeader.textContent = formatted;
            const elModalCv = document.getElementById('saldoKoinModalCv');
            if (elModalCv) elModalCv.textContent = formatted;
        }

        // --- FITUR BELI CV (5 KOIN) ---
        function closeModalBeliCv() {
            document.getElementById('modalConfirmBeliCv').classList.add('hidden');
        }

        function closeModalKoinKurangCv() {
            document.getElementById('modalKoinKurangCv').classList.add('hidden');
        }

        document.querySelectorAll('.btn-beli-cv').forEach(btn => {
            btn.addEventListener('click', function() {
                selectedPelamarId = this.dataset.id;
                selectedPelamarNama = this.dataset.nama || '';

                const minKoinCv = {{ (int) \App\Models\SiteSetting::get('harga_koin_buka_cv', 5) }};
                if (currentCoins < minKoinCv) {
                    document.getElementById('modalKoinKurangCv').classList.remove('hidden');
                    return;
                }

                document.getElementById('namaPelamarBeliCv').textContent = selectedPelamarNama;
                document.getElementById('modalConfirmBeliCv').classList.remove('hidden');
            });
        });

        document.getElementById('btnProsesBeliCv')?.addEventListener('click', async function() {
            if (!selectedPelamarId) return;

            const btn = this;
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                let url = "{{ route('perusahaan.kandidat.beli-cv', ':id') }}".replace(':id', selectedPelamarId);
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await res.json();

                if (data.status === 'success') {
                    closeModalBeliCv();

                    if (data.sisa_koin !== undefined) {
                        updateCoinDisplay(data.sisa_koin);
                    }

                    // Ganti tombol Beli menjadi Download
                    const wrapper = document.getElementById('cv-action-' + selectedPelamarId);
                    if (wrapper) {
                        wrapper.innerHTML = `
                            <a href="${data.download_url}"
                                class="inline-flex items-center justify-center w-28 py-1.5 border-2 border-[#00509d] text-[#00509d] hover:bg-[#00509d] hover:text-white font-bold rounded-lg text-xs transition shadow-2xs">
                                Download
                            </a>
                        `;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'CV Berhasil Dibuka!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else if (data.type === 'coin_insufficient') {
                    closeModalBeliCv();
                    document.getElementById('modalKoinKurangCv').classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal membuka CV kandidat.',
                        confirmButtonColor: '#00509d'
                    });
                }
            } catch (err) {
                console.error('Beli CV Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi kesalahan saat memproses data. Silakan coba lagi.',
                    confirmButtonColor: '#00509d'
                });
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });

        // --- FITUR REKRUT KANDIDAT (100 KOIN) ---
        const modalPilih = document.getElementById('modalPilihLowongan');
        const modalKonfirmasi = document.getElementById('modalKonfirmasi');
        const selectLowongan = document.getElementById('selectLowongan');

        document.querySelectorAll('.btn-rekrut').forEach(btn => {
            btn.addEventListener('click', function() {
                selectedPelamarId = this.dataset.id;
                selectedPelamarNama = this.dataset.nama || '';
                document.getElementById('namaPelamarRekrut').textContent = selectedPelamarNama;
                modalPilih.classList.remove('hidden');
            });
        });

        document.getElementById('btnCancelRekrut')?.addEventListener('click', () => {
            modalPilih.classList.add('hidden');
        });

        document.getElementById('btnLanjutRekrut')?.addEventListener('click', () => {
            selectedLowonganId = selectLowongan.value;
            if (!selectedLowonganId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Lowongan',
                    text: 'Silakan pilih posisi lowongan terlebih dahulu!',
                    confirmButtonColor: '#00509d',
                });
                return;
            }

            if (currentCoins < 100) {
                modalPilih.classList.add('hidden');
                Swal.fire({
                    icon: 'warning',
                    title: 'Koin Tidak Cukup',
                    text: 'Saldo koin perusahaan Anda kurang untuk merekrut kandidat (Dibutuhkan 100 Koin).',
                    showCancelButton: true,
                    confirmButtonText: 'Top Up Koin',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#10b981',
                }).then(result => {
                    if (result.isConfirmed) {
                        toggleModal();
                    }
                });
                return;
            }

            modalPilih.classList.add('hidden');
            modalKonfirmasi.classList.remove('hidden');
        });

        document.getElementById('btnKonfirmasiBatal')?.addEventListener('click', () => {
            modalKonfirmasi.classList.add('hidden');
        });

        document.getElementById('btnKonfirmasiBeli')?.addEventListener('click', async function() {
            modalKonfirmasi.classList.add('hidden');

            const btn = this;
            btn.disabled = true;

            try {
                const res = await fetch("{{ route('kandidat.beli') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pelamar_id: selectedPelamarId,
                        lowongan_perusahaan_id: selectedLowonganId
                    })
                });

                const data = await res.json();

                if (data.status === 'success' || data.success) {
                    updateCoinDisplay(Math.max(0, currentCoins - 100));

                    Swal.fire({
                        icon: 'success',
                        title: 'Kandidat Berhasil Direkrut!',
                        text: data.message || 'Kandidat berhasil dihubungkan ke lowongan Anda.',
                        confirmButtonColor: '#00509d',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan saat merekrut kandidat.',
                        confirmButtonColor: '#00509d'
                    });
                }
            } catch (err) {
                console.error('Rekrut Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
                    confirmButtonColor: '#00509d'
                });
            } finally {
                btn.disabled = false;
            }
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.96);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.2s ease-out;
        }
    </style>

    @include('layouts.footer')
@endsection
