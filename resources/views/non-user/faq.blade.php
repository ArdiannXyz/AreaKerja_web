@extends('layouts.index')
@section('content')

    <div class="bg-slate-50 min-h-screen text-slate-800 pt-24 sm:pt-28 md:pt-32 pb-20"
        x-data="faqHandler()">

        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            {{-- ============================================================ --}}
            {{-- HERO BANNER (Pusat Bantuan AreaKerja - Blue Theme) --}}
            {{-- ============================================================ --}}
            <div class="relative overflow-hidden rounded-3xl shadow-lg mb-10 text-white p-7 sm:p-10 md:p-12"
                style="background: linear-gradient(135deg, #00509d 0%, #003870 100%);">
                
                {{-- Decorative background glow --}}
                <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-sky-400/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-blue-600/30 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 max-w-2xl">
                    <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md text-white text-xs font-semibold px-3.5 py-1.5 rounded-full mb-4 border border-white/20 shadow-xs">
                        <i class="ph-fill ph-lifebuoy text-sm text-sky-300"></i>
                        <span>Pusat Bantuan &amp; FAQ</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight tracking-tight">
                        Ada yang bisa kami bantu?
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100/90 mt-2.5 leading-relaxed">
                        Temukan jawaban cepat seputar cara melamar pekerjaan, program kandidat, pasang lowongan, hingga pengelolaan akun di AreaKerja.
                    </p>

                    {{-- Live Search Input --}}
                    <div class="mt-6 sm:mt-8 relative max-w-xl">
                        <div class="relative flex items-center bg-white rounded-2xl p-1.5 shadow-xl border border-white/40">
                            <div class="pl-3.5 pr-2 text-slate-400 flex items-center justify-center">
                                <i class="ph ph-magnifying-glass text-xl text-[#00509d]"></i>
                            </div>
                            <input type="text"
                                x-model="searchQuery"
                                placeholder="Ketik kata kunci pertanyaan (misal: lamar kerja, kandidat, koin, cv)..."
                                class="w-full py-2.5 pr-10 text-xs sm:text-sm text-slate-800 placeholder-slate-400 bg-transparent border-none outline-none focus:ring-0 focus:border-none">
                            
                            <button type="button"
                                x-show="searchQuery.length > 0"
                                @click="searchQuery = ''"
                                class="absolute right-3 text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-100 transition">
                                <i class="ph-fill ph-x-circle text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- CATEGORY FILTER TABS --}}
            {{-- ============================================================ --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-8 no-scrollbar">
                <template x-for="cat in categories" :key="cat.id">
                    <button type="button"
                        @click="selectedCategory = cat.id"
                        :class="selectedCategory === cat.id 
                            ? 'bg-[#00509d] text-white shadow-sm border-[#00509d]' 
                            : 'bg-white text-slate-600 hover:bg-slate-100 border-slate-200'"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs sm:text-sm font-semibold whitespace-nowrap transition cursor-pointer shrink-0">
                        <i :class="cat.icon" class="text-base"></i>
                        <span x-text="cat.name"></span>
                        <span :class="selectedCategory === cat.id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'"
                            class="text-[10px] px-2 py-0.5 rounded-full font-bold ml-1"
                            x-text="countCategory(cat.id)"></span>
                    </button>
                </template>
            </div>

            {{-- ============================================================ --}}
            {{-- FAQ LIST (ACCORDIONS) --}}
            {{-- ============================================================ --}}
            <div class="space-y-4 mb-16">

                <template x-for="(faq, index) in filteredFaqs" :key="faq.id">
                    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs hover:shadow-sm transition-all duration-200">
                        
                        {{-- Accordion Header --}}
                        <button type="button"
                            @click="toggleFaq(faq.id)"
                            class="w-full p-5 sm:p-6 text-left flex items-start justify-between gap-4 cursor-pointer transition select-none hover:bg-slate-50/50">
                            
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5 transition"
                                    :class="openFaqs.includes(faq.id) ? 'bg-[#00509d] text-white' : 'bg-blue-50 text-[#00509d]'">
                                    <i :class="faq.icon" class="text-lg"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md"
                                            :class="getCategoryBadgeClass(faq.category)">
                                            <span x-text="getCategoryName(faq.category)"></span>
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-slate-800 text-sm sm:text-base leading-snug"
                                        x-text="faq.question"></h3>
                                </div>
                            </div>

                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-transform duration-300"
                                :class="openFaqs.includes(faq.id) ? 'rotate-180 bg-slate-100 text-[#00509d]' : 'bg-slate-50 text-slate-400'">
                                <i class="ph-bold ph-caret-down text-base"></i>
                            </div>
                        </button>

                        {{-- Accordion Content Body --}}
                        <div x-show="openFaqs.includes(faq.id)"
                            x-collapse
                            x-cloak
                            class="px-5 sm:px-6 pb-6 pt-1 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/30">
                            <div class="pl-12 pt-3" x-html="faq.answer"></div>
                        </div>
                    </div>
                </template>

                {{-- Empty State (No Search Match) --}}
                <div x-show="filteredFaqs.length === 0" x-cloak
                    class="bg-white border border-slate-200 rounded-3xl p-10 text-center max-w-md mx-auto my-8">
                    <div class="w-16 h-16 rounded-2xl bg-sky-50 text-[#00509d] flex items-center justify-center mx-auto mb-4">
                        <i class="ph ph-magnifying-glass text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base mb-1">Pertanyaan Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-xs sm:text-sm mb-5">
                        Tidak ada pertanyaan yang cocok dengan kata kunci "<span class="font-semibold text-slate-700" x-text="searchQuery"></span>".
                    </p>
                    <button type="button"
                        @click="searchQuery = ''; selectedCategory = 'all'"
                        class="inline-flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">
                        <i class="ph ph-arrow-counter-clockwise"></i>
                        <span>Reset Pencarian</span>
                    </button>
                </div>

            </div>

            {{-- ============================================================ --}}
            {{-- BOTTOM HELP / CONTACT SUPPORT CARD --}}
            {{-- ============================================================ --}}
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 md:p-10 shadow-sm relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    
                    <div class="lg:col-span-2 space-y-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00509d] bg-sky-50 px-3 py-1 rounded-full">
                            <i class="ph-fill ph-headset text-sm"></i> Layanan Bantuan Langsung
                        </span>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 leading-snug">
                            Belum menemukan jawaban yang Anda cari?
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl">
                            Tim layanan pelanggan AreaKerja siap membantu menjawab pertanyaan Anda seputar lowongan, kendala akun, dan kerjasama rekrutmen.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row lg:flex-col gap-3 justify-end shrink-0">
                        {{-- WhatsApp Support --}}
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20AreaKerja,%20saya%20membutuhkan%20bantuan%20terkait%20platform%20AreaKerja."
                            target="_blank"
                            class="inline-flex items-center justify-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm px-6 py-3.5 rounded-xl shadow-md hover:shadow-lg transition">
                            <i class="ph-fill ph-whatsapp-logo text-lg"></i>
                            <span>Hubungi via WhatsApp</span>
                        </a>

                        {{-- Email Support --}}
                        <a href="mailto:support@areakerja.com"
                            class="inline-flex items-center justify-center gap-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs sm:text-sm px-6 py-3.5 rounded-xl border border-slate-200/80 transition">
                            <i class="ph ph-envelope-simple text-lg text-[#00509d]"></i>
                            <span>Kirim Email Bantuan</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>

    @include('layouts.footer')

    {{-- ============================================================ --}}
    {{-- FAQ DATA & ALPINE.JS LOGIC --}}
    {{-- ============================================================ --}}
    <script>
        function faqHandler() {
            return {
                searchQuery: '',
                selectedCategory: 'all',
                openFaqs: [1], // First FAQ open by default
                categories: [
                    { id: 'all', name: 'Semua Pertanyaan', icon: 'ph-bold ph-squares-four' },
                    { id: 'pelamar', name: 'Pelamar & Karir', icon: 'ph-bold ph-user' },
                    { id: 'kandidat', name: 'Program Kandidat', icon: 'ph-bold ph-star' },
                    { id: 'perusahaan', name: 'Perusahaan & Rekrutmen', icon: 'ph-bold ph-buildings' },
                    { id: 'akun', name: 'Akun & Keamanan', icon: 'ph-bold ph-shield-check' }
                ],
                faqs: [
                    // === PELAMAR & KARIR ===
                    {
                        id: 1,
                        category: 'pelamar',
                        icon: 'ph-bold ph-briefcase',
                        question: 'Bagaimana cara melamar pekerjaan di AreaKerja?',
                        answer: `<p class="mb-2">Melamar pekerjaan di AreaKerja sangat mudah dan praktis melalui langkah berikut:</p>
                        <ol class="list-decimal list-inside space-y-1 text-slate-700">
                            <li>Jelajahi lowongan yang sesuai di halaman <b>Beranda</b> atau menu pencarian lowongan.</li>
                            <li>Klik pada kartu lowongan untuk melihat detail kualifikasi, deskripsi pekerjaan, dan benefit.</li>
                            <li>Klik tombol <b>Lamar Sekarang</b> pada halaman detail lowongan.</li>
                            <li>Pilih atau perbarui resume CV Anda, lalu konfirmasi pengajuan lamaran.</li>
                            <li>Pantau status tahapan seleksi Anda secara real-time di menu <b>Lamaran Kerja Saya</b>.</li>
                        </ol>`
                    },
                    {
                        id: 2,
                        category: 'pelamar',
                        icon: 'ph-bold ph-currency-circle-dollar',
                        question: 'Apakah melamar pekerjaan di AreaKerja dikenakan biaya?',
                        answer: `<p><b>Sama sekali tidak!</b> Seluruh layanan bagi pencari kerja untuk mencari, menyimpan, dan melamar lowongan di AreaKerja adalah <b>100% GRATIS</b>. Hati-hati terhadap pihak manapun yang meminta biaya pendaftaran atas nama AreaKerja.</p>`
                    },
                    {
                        id: 3,
                        category: 'pelamar',
                        icon: 'ph-bold ph-file-pdf',
                        question: 'Bagaimana cara mengunduh (Export) CV profesional saya?',
                        answer: `<p class="mb-2">Anda dapat mengekspor data profil Anda menjadi Curriculum Vitae (CV) berstandar profesional:</p>
                        <ol class="list-decimal list-inside space-y-1 text-slate-700">
                            <li>Masuk ke akun Anda dan buka menu <b>Profil Saya</b>.</li>
                            <li>Pastikan riwayat pendidikan, pengalaman kerja, organisasi, dan keterampilan telah terisi lengkap.</li>
                            <li>Klik tombol <b>Export CV</b> di bagian atas profil.</li>
                            <li>Sistem akan otomatis menghasilkan dokumen CV berformat PDF dengan desain modern bernuansa biru AreaKerja siap pakai.</li>
                        </ol>`
                    },
                    {
                        id: 4,
                        category: 'pelamar',
                        icon: 'ph-bold ph-handshake',
                        question: 'Bagaimana jika lamaran saya telah diterima oleh perusahaan?',
                        answer: `<p class="mb-2">Jika perusahaan menerima lamaran Anda, Anda akan menerima notifikasi tawaran kerja (Offering):</p>
                        <ul class="list-disc list-inside space-y-1 text-slate-700">
                            <li>Buka halaman <b>Lamaran Kerja Saya</b> dan klik kartu lamaran yang berstatus <i>Diterima / Offering</i>.</li>
                            <li>Periksa jadwal wawancara / mulai bekerja, lokasi kantor (lengkap dengan navigasi Google Maps), serta catatan khusus dari HRD.</li>
                            <li>Anda dapat langsung menghubungi HRD via tautan <b>Chat WA</b> atau memutuskan untuk <b>Terima Tawaran &amp; Bekerja</b> atau <b>Tolak Tawaran</b>.</li>
                        </ul>`
                    },

                    // === PROGRAM KANDIDAT ===
                    {
                        id: 5,
                        category: 'kandidat',
                        icon: 'ph-bold ph-crown',
                        question: 'Apa itu Program Kandidat AreaKerja dan apa keuntungannya?',
                        answer: `<p class="mb-2"><b>Program Kandidat AreaKerja</b> adalah program akselerasi karir dan sertifikasi kompetensi terakreditasi bagi talenta potensial.</p>
                        <p class="mb-2 font-semibold text-slate-800">Keuntungan Menjadi Kandidat:</p>
                        <ul class="list-disc list-inside space-y-1 text-slate-700">
                            <li><b>Prioritas Utama Mitra:</b> Profil Anda direkomendasikan secara eksklusif ke perusahaan-perusahaan mitra terkemuka.</li>
                            <li><b>Badge Terverifikasi:</b> Mendapatkan lencana verifikasi keahlian khusus di platform.</li>
                            <li><b>Pelatihan Terstandar:</b> Akses ke modul pelatihan industri dan bimbingan karir intensif.</li>
                        </ul>`
                    },
                    {
                        id: 6,
                        category: 'kandidat',
                        icon: 'ph-bold ph-graduation-cap',
                        question: 'Apa perbedaan status "Calon Kandidat" dan "Kandidat Aktif"?',
                        answer: `<p><b>Calon Kandidat:</b> Pendaftar yang telah mengajukan keikutsertaan dan sedang menjalani proses verifikasi dokumen atau masa pelatihan.<br><br><b>Kandidat Aktif:</b> Talenta yang telah menyelesaikan pelatihan, lulus asesmen kompetensi, dan siap direkrut secara langsung oleh perusahaan mitra melalui talent pool.</p>`
                    },
                    {
                        id: 7,
                        category: 'kandidat',
                        icon: 'ph-bold ph-identification-card',
                        question: 'Bagaimana cara mendaftar sebagai Kandidat AreaKerja?',
                        answer: `<p>Kunjungi menu <b>Daftar Kandidat</b> di navigasi utama, lengkapi formulir pendaftaran diri, portofolio keahlian, dan selesaikan tahapan pendaftaran sesuai instruksi yang tertera.</p>`
                    },

                    // === PERUSAHAAN & REKRUTMEN ===
                    {
                        id: 8,
                        category: 'perusahaan',
                        icon: 'ph-bold ph-megaphone',
                        question: 'Bagaimana cara perusahaan memasang lowongan pekerjaan?',
                        answer: `<p class="mb-2">Untuk memasang lowongan pekerjaan sebagai perusahaan:</p>
                        <ol class="list-decimal list-inside space-y-1 text-slate-700">
                            <li>Daftarkan akun perusahaan Anda melalui halaman registrasi perusahaan.</li>
                            <li>Lengkapi data profil perusahaan, legalitas, serta kontak HRD resmi.</li>
                            <li>Pilih paket lowongan (Gold, Silver, atau Bronze) sesuai kebutuhan durasi tayang dan jangkauan listing.</li>
                            <li>Gunakan saldo Koin AreaKerja untuk mempublikasikan lowongan agar segera tampil di halaman utama.</li>
                        </ol>`
                    },
                    {
                        id: 9,
                        category: 'perusahaan',
                        icon: 'ph-bold ph-coins',
                        question: 'Apa itu Koin AreaKerja dan bagaimana cara melakukan Top-Up?',
                        answer: `<p><b>Koin AreaKerja</b> adalah saldo virtual resmi yang digunakan perusahaan untuk memasang lowongan, boost prioritas, dan mengakses database talent hunter.<br><br>Top-up dapat dilakukan dengan mudah melalui Dashboard Perusahaan dengan memilih paket koin, kemudian melakukan pembayaran via <b>Transfer Bank (BCA, Mandiri)</b> atau <b>QRIS</b>.</p>`
                    },
                    {
                        id: 10,
                        category: 'perusahaan',
                        icon: 'ph-bold ph-users-three',
                        question: 'Bagaimana cara mengelola pelamar dan mengirimkan offering kerja?',
                        answer: `<p>Masuk ke <b>Dashboard Perusahaan &gt; Lowongan Saya &gt; Pelamar</b>. Anda dapat menyaring data resume pelamar, mengunduh CV mereka, serta mengirimkan keputusan penerimaan kerja lengkap dengan jadwal interview/kerja, link Google Maps lokasi kantor, dan catatan HRD.</p>`
                    },

                    // === AKUN & KEAMANAN ===
                    {
                        id: 11,
                        category: 'akun',
                        icon: 'ph-bold ph-key',
                        question: 'Bagaimana cara mereset kata sandi jika saya lupa password?',
                        answer: `<p>Klik tautan <b>Lupa Kata Sandi?</b> pada halaman Login. Masukkan alamat email terdaftar Anda untuk menerima kode OTP verifikasi 6-digit. Setelah verifikasi berhasil, Anda dapat langsung membuat kata sandi baru.</p>`
                    },
                    {
                        id: 12,
                        category: 'akun',
                        icon: 'ph-bold ph-lock-key',
                        question: 'Bagaimana AreaKerja menjaga keamanan dan kerahasiaan data saya?',
                        answer: `<p>AreaKerja menerapkan enkripsi data standar industri dan perlindungan privasi ketat. Data pribadi, kontak, dan resume Anda hanya diteruskan ke perusahaan yang Anda lamar secara resmi dan tidak akan diperjualbelikan kepada pihak ketiga manapun.</p>`
                    }
                ],
                get filteredFaqs() {
                    let list = this.faqs;
                    if (this.selectedCategory !== 'all') {
                        list = list.filter(item => item.category === this.selectedCategory);
                    }
                    if (this.searchQuery.trim() !== '') {
                        const q = this.searchQuery.toLowerCase();
                        list = list.filter(item => 
                            item.question.toLowerCase().includes(q) || 
                            item.answer.toLowerCase().includes(q)
                        );
                    }
                    return list;
                },
                toggleFaq(id) {
                    if (this.openFaqs.includes(id)) {
                        this.openFaqs = this.openFaqs.filter(item => item !== id);
                    } else {
                        this.openFaqs.push(id);
                    }
                },
                countCategory(catId) {
                    if (catId === 'all') return this.faqs.length;
                    return this.faqs.filter(f => f.category === catId).length;
                },
                getCategoryName(catId) {
                    const found = this.categories.find(c => c.id === catId);
                    return found ? found.name : 'Umum';
                },
                getCategoryBadgeClass(catId) {
                    switch(catId) {
                        case 'pelamar': return 'bg-sky-100 text-[#00509d]';
                        case 'kandidat': return 'bg-rose-100 text-rose-800';
                        case 'perusahaan': return 'bg-blue-100 text-blue-800';
                        case 'akun': return 'bg-emerald-100 text-emerald-800';
                        default: return 'bg-slate-100 text-slate-700';
                    }
                }
            }
        }
    </script>

@endsection

