@extends('layouts.index-perusahaan')
@section('content')
    <div class="w-full bg-slate-50/50 min-h-screen pt-24 pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Breadcrumbs & Back -->
            <div class="flex items-center justify-between">
                <a href="{{ route('perusahaan.iklan.index') }}"
                    class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 hover:text-[#00509d] transition">
                    <i class="ph ph-arrow-left font-bold"></i>
                    <span>Kembali ke Manajemen Iklan</span>
                </a>

                <!-- Saldo Koin Pill -->
                <div class="flex items-center gap-2 px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-2xl shadow-2xs text-xs font-bold text-slate-700">
                    <span class="text-slate-400">Saldo Koin:</span>
                    <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-4 h-4 object-contain">
                    <span class="text-[#00509d] font-black text-sm">{{ number_format($perusahaan->koin_perusahaan, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Header Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs relative overflow-hidden">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-[#00509d] text-xs font-black uppercase tracking-wide mb-3">
                    <i class="ph-fill ph-plus-circle text-sm"></i>
                    <span>Formulir Pengajuan Iklan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Pasang Iklan Banner Beranda
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1 max-w-2xl">
                    Tampilkan banner promosi perusahaan Anda di slider utama beranda AreaKerja dan jangkau puluhan ribu pencari kerja aktif.
                </p>
            </div>

            <!-- Error Koin Alert -->
            @if(session('error_koin'))
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center text-xl shrink-0 mt-0.5">
                            <i class="ph-fill ph-warning"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-rose-900">Koin Tidak Mencukupi</h4>
                            <p class="text-xs text-rose-700 mt-0.5">{{ session('error_koin')['pesan'] }}</p>
                            <p class="text-xs font-bold text-rose-800 mt-1">
                                Dibutuhkan: {{ number_format(session('error_koin')['butuh'], 0, ',', '.') }} Koin | Milik Anda: {{ number_format(session('error_koin')['punya'], 0, ',', '.') }} Koin
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="toggleModal()"
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition shrink-0">
                        Top Up Koin Sekarang
                    </button>
                </div>
            @endif

            <!-- Form Pasang Iklan -->
            <form action="{{ route('perusahaan.iklan.store') }}" method="POST" enctype="multipart/form-data" id="formPasangIklan">
                @csrf

                <div class="space-y-8">

                    <!-- STEP 1: PILIH PAKET IKLAN -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-black text-sm">
                                1
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Pilih Paket Durasi Tayang</h3>
                                <p class="text-xs text-slate-500 font-medium">Tentukan berapa lama banner iklan Anda akan aktif di homepage</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach ($paketList as $pkt)
                                @php
                                    $isSelected = old('paket_id', '2') == $pkt['id'];
                                @endphp
                                <label class="paket-card relative border-2 {{ $isSelected ? 'border-[#00509d] bg-blue-50/30 ring-2 ring-[#00509d]/20' : 'border-slate-200 bg-white hover:border-slate-300' }} rounded-2xl p-5 cursor-pointer transition flex flex-col justify-between group">
                                    <input type="radio" name="paket_id" value="{{ $pkt['id'] }}" {{ $isSelected ? 'checked' : '' }}
                                        data-koin="{{ $pkt['koin'] }}" data-durasi="{{ $pkt['durasi'] }}" data-nama="{{ $pkt['nama'] }}"
                                        class="sr-only paket-radio" onchange="handlePaketChange()">

                                    <div>
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="px-2.5 py-0.5 bg-blue-100 text-[#00509d] text-[10px] font-black rounded-md">
                                                {{ $pkt['badge'] }}
                                            </span>
                                            <div class="radio-indicator w-5 h-5 rounded-full border-2 {{ $isSelected ? 'border-[#00509d] bg-[#00509d]' : 'border-slate-300 bg-white' }} flex items-center justify-center transition">
                                                <div class="w-2 h-2 rounded-full bg-white {{ $isSelected ? '' : 'hidden' }}"></div>
                                            </div>
                                        </div>

                                        <h4 class="font-black text-slate-900 text-base mt-3">{{ $pkt['nama'] }}</h4>
                                        <p class="text-xs font-bold text-slate-500 mt-0.5">{{ $pkt['durasi'] }} Hari Kalender</p>
                                        <p class="text-[11px] text-slate-400 font-medium mt-2 leading-relaxed">{{ $pkt['deskripsi'] }}</p>
                                    </div>

                                    <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-400">Biaya:</span>
                                        <div class="flex items-center gap-1.5 font-black text-[#00509d]">
                                            <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-4 h-4 object-contain">
                                            <span class="text-base">{{ number_format($pkt['koin'], 0, ',', '.') }}</span>
                                            <span class="text-xs font-bold text-amber-600">Koin</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('paket_id')
                            <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- STEP 2: MATERI & GAMBAR BANNER -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-black text-sm">
                                2
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Upload Banner Iklan</h3>
                                <p class="text-xs text-slate-500 font-medium">Unggah materi desain iklan dengan rasio persegi panjang horizontal</p>
                            </div>
                        </div>

                        <!-- Banner Upload Area -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                File Banner Iklan <span class="text-rose-500">*</span>
                            </label>

                            <!-- Live Banner Preview Box -->
                            <div id="previewContainer" class="hidden mb-4 relative rounded-2xl overflow-hidden border-2 border-slate-200 bg-slate-900 group">
                                <img id="bannerImgPreview" src="" alt="Preview Banner" class="w-full h-auto object-cover max-h-[300px]">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                                    <button type="button" onclick="document.getElementById('gambar_banner').click()"
                                        class="px-4 py-2 bg-white/90 hover:bg-white text-slate-800 text-xs font-bold rounded-xl shadow-xs transition">
                                        Ganti Gambar
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Dropzone -->
                            <div id="uploadDropzone" onclick="document.getElementById('gambar_banner').click()"
                                class="border-2 border-dashed border-slate-300 hover:border-[#00509d] bg-slate-50 hover:bg-blue-50/20 rounded-2xl p-8 text-center cursor-pointer transition group">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#00509d] group-hover:scale-110 flex items-center justify-center text-2xl mx-auto mb-3 transition shadow-2xs">
                                    <i class="ph ph-image"></i>
                                </div>
                                <p class="text-sm font-extrabold text-slate-800">
                                    Klik untuk memilih gambar banner atau seret ke sini
                                </p>
                                <p class="text-xs text-slate-500 font-medium mt-1">
                                    Rekomendasi rasio: <span class="font-bold text-[#00509d]">1200 x 400 px</span> (Rasio 3:1) • Format: JPG, PNG, WEBP (Maksimal 3MB)
                                </p>
                            </div>

                            <input type="file" id="gambar_banner" name="gambar_banner" accept="image/png,image/jpeg,image/webp"
                                class="hidden" onchange="previewImage(event)">

                            @error('gambar_banner')
                                <p class="text-xs font-bold text-rose-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Judul Iklan Input -->
                        <div>
                            <label for="judul_iklan" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                Judul / Nama Kampanye Iklan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="judul_iklan" name="judul_iklan" value="{{ old('judul_iklan') }}"
                                placeholder="Cth: Rekrutmen Massal Management Trainee 2026"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold rounded-2xl p-3.5 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] transition shadow-2xs">
                            <p class="text-[11px] text-slate-400 font-medium mt-1">
                                Digunakan sebagai label identifikasi kampanye dan teks bantuan (*alt-text*).
                            </p>
                            @error('judul_iklan')
                                <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- URL Tujuan Input & Quick Fill -->
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                <label for="url_tujuan" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                    Link / URL Tujuan Saat Diklik <span class="text-rose-500">*</span>
                                </label>
                                @if($lowongans->count() > 0)
                                    <span class="text-[11px] text-[#00509d] font-bold">
                                        💡 Pintasan: Pilih dari lowongan aktif Anda di bawah
                                    </span>
                                @endif
                            </div>

                            @if($lowongans->count() > 0)
                                <div class="mb-3">
                                    <select onchange="fillUrlFromLowongan(this.value)"
                                        class="w-full bg-blue-50/50 border border-blue-200 text-slate-700 text-xs font-bold rounded-xl p-2.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#00509d]">
                                        <option value="">-- Atau pilih lowongan aktif perusahaan Anda --</option>
                                        @foreach ($lowongans as $low)
                                            <option value="{{ route('detail.lowongan.non.user', ['perusahaan' => $perusahaan->slug ?? $perusahaan->id, 'lowongan' => $low->id]) }}">
                                                {{ $low->nama_posisi ?? $low->posisi }} (Dipublikasikan: {{ $low->created_at->format('d M Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="relative">
                                <input type="url" id="url_tujuan" name="url_tujuan" value="{{ old('url_tujuan') }}"
                                    placeholder="https://areakerja.com/pelamar/detail-lowongan/... atau https://perusahaananda.com"
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm font-semibold rounded-2xl p-3.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] transition shadow-2xs">
                                <i class="ph ph-link text-base text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium mt-1">
                                Pelamar yang mengklik banner akan diarahkan ke link ini (bisa ke link lowongan AreaKerja atau website resmi Anda).
                            </p>
                            @error('url_tujuan')
                                <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- STEP 3: RINGKASAN PEMBAYARAN & KONFIRMASI -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-black text-sm">
                                3
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Ringkasan Biaya Koin</h3>
                                <p class="text-xs text-slate-500 font-medium">Pembayaran dipotong langsung dari saldo koin akun perusahaan</p>
                            </div>
                        </div>

                        <!-- Calculation Box -->
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <span class="font-bold text-slate-600">Paket Terpilih:</span>
                                <span id="summaryPaketNama" class="font-black text-slate-900">Paket Silver (7 Hari)</span>
                            </div>
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <span class="font-bold text-slate-600">Saldo Koin Saat Ini:</span>
                                <div class="flex items-center gap-1 font-extrabold text-slate-800">
                                    <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-4 h-4 object-contain">
                                    <span>{{ number_format($perusahaan->koin_perusahaan, 0, ',', '.') }} Koin</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <span class="font-bold text-slate-600">Biaya Pemasangan Iklan:</span>
                                <div class="flex items-center gap-1 font-black text-rose-600">
                                    <span>-</span>
                                    <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-4 h-4 object-contain">
                                    <span id="summaryBiayaKoin">300</span>
                                    <span>Koin</span>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-sm sm:text-base font-black">
                                <span class="text-slate-900">Perkiraan Sisa Koin:</span>
                                <div class="flex items-center gap-1 text-[#00509d]">
                                    <img src="{{ asset('images/coin.png') }}" alt="Koin" class="w-5 h-5 object-contain">
                                    <span id="summarySisaKoin">{{ number_format($perusahaan->koin_perusahaan - 300, 0, ',', '.') }}</span>
                                    <span class="text-xs">Koin</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                            <button type="submit" id="btnSubmitIklan"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs sm:text-sm font-extrabold rounded-2xl shadow-sm hover:shadow-md transition active:scale-98">
                                <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                                <span>Kirim Pengajuan Iklan</span>
                            </button>

                            <button type="button" onclick="toggleModal()"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs sm:text-sm font-extrabold rounded-2xl transition">
                                <i class="ph-bold ph-plus-circle text-base"></i>
                                <span>Top Up Koin Dulu</span>
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>
        const userKoin = {{ (int) $perusahaan->koin_perusahaan }};

        function handlePaketChange() {
            const selectedRadio = document.querySelector('.paket-radio:checked');
            if (!selectedRadio) return;

            const biaya = parseInt(selectedRadio.dataset.koin);
            const nama = selectedRadio.dataset.nama;
            const durasi = selectedRadio.dataset.durasi;

            // Update UI card styling
            document.querySelectorAll('.paket-card').forEach(card => {
                card.classList.remove('border-[#00509d]', 'bg-blue-50/30', 'ring-2', 'ring-[#00509d]/20');
                card.classList.add('border-slate-200', 'bg-white');
                const ind = card.querySelector('.radio-indicator');
                if (ind) {
                    ind.classList.remove('border-[#00509d]', 'bg-[#00509d]');
                    ind.classList.add('border-slate-300', 'bg-white');
                    const dot = ind.querySelector('div');
                    if (dot) dot.classList.add('hidden');
                }
            });

            const parentCard = selectedRadio.closest('.paket-card');
            if (parentCard) {
                parentCard.classList.remove('border-slate-200', 'bg-white');
                parentCard.classList.add('border-[#00509d]', 'bg-blue-50/30', 'ring-2', 'ring-[#00509d]/20');
                const ind = parentCard.querySelector('.radio-indicator');
                if (ind) {
                    ind.classList.remove('border-slate-300', 'bg-white');
                    ind.classList.add('border-[#00509d]', 'bg-[#00509d]');
                    const dot = ind.querySelector('div');
                    if (dot) dot.classList.remove('hidden');
                }
            }

            // Update Summary
            document.getElementById('summaryPaketNama').innerText = nama + ' (' + durasi + ' Hari)';
            document.getElementById('summaryBiayaKoin').innerText = biaya.toLocaleString('id-ID');

            const sisa = userKoin - biaya;
            const sisaEl = document.getElementById('summarySisaKoin');
            sisaEl.innerText = sisa.toLocaleString('id-ID');

            const btnSubmit = document.getElementById('btnSubmitIklan');
            if (sisa < 0) {
                sisaEl.parentElement.classList.remove('text-[#00509d]');
                sisaEl.parentElement.classList.add('text-rose-600');
                btnSubmit.classList.add('opacity-75');
            } else {
                sisaEl.parentElement.classList.add('text-[#00509d]');
                sisaEl.parentElement.classList.remove('text-rose-600');
                btnSubmit.classList.remove('opacity-75');
            }
        }

        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('bannerImgPreview').src = e.target.result;
                    document.getElementById('previewContainer').classList.remove('hidden');
                    document.getElementById('uploadDropzone').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function fillUrlFromLowongan(url) {
            if (url) {
                document.getElementById('url_tujuan').value = url;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            handlePaketChange();
        });
    </script>

    @include('layouts.footer')
@endsection
