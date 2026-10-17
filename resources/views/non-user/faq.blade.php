@extends(auth()->check() && auth()->user()->role === 'perusahaan' ? 'layouts.index-perusahaan' : 'layouts.index')

@section('content')
<style>
/* ── HERO ─────────────────────────────────────────────── */
.faq-hero{
    background:linear-gradient(135deg,#001f4d 0%,#00509d 60%,#0073c4 100%);
    position:relative; overflow:hidden;
}
.faq-hero::before{
    content:''; position:absolute;
    width:500px; height:500px; border-radius:50%;
    top:-140px; right:-140px;
    background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);
    pointer-events:none;
}
.faq-hero::after{
    content:''; position:absolute;
    width:320px; height:320px; border-radius:50%;
    bottom:-80px; left:-60px;
    background:radial-gradient(circle,rgba(0,197,255,.09) 0%,transparent 70%);
    pointer-events:none;
}

/* Search glassmorphism */
.faq-search-wrap{
    background:rgba(255,255,255,.10);
    border:1.5px solid rgba(255,255,255,.22);
    backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
    border-radius:16px;
    transition:border-color .2s,box-shadow .2s;
}
.faq-search-wrap:focus-within{
    border-color:rgba(255,255,255,.5);
    box-shadow:0 0 0 4px rgba(255,255,255,.07);
}
.faq-search-wrap input{
    background:transparent; color:#fff; border:none; outline:none;
}
.faq-search-wrap input::placeholder{color:rgba(255,255,255,.5);}

/* Wave divider */
.faq-wave{display:block;width:100%;overflow:hidden;line-height:0;margin-bottom:-2px;}
.faq-wave svg{display:block;width:100%;}

/* ── SIDEBAR ──────────────────────────────────────────── */
.faq-sidebar{
    position:sticky; top:90px;
}
.cat-btn{
    width:100%; display:flex; align-items:center; gap:10px;
    padding:10px 14px; border-radius:12px;
    font-size:13px; font-weight:600; color:#475569;
    border:1.5px solid transparent;
    background:transparent;
    text-align:left; cursor:pointer;
    transition:all .18s ease;
}
.cat-btn:hover{background:#eff6ff; color:#00509d; border-color:#bfdbfe;}
.cat-btn.active{
    background:linear-gradient(135deg,#dbeafe,#bfdbfe);
    color:#1d4ed8; border-color:#93c5fd; font-weight:700;
}
.cat-btn .cat-icon{
    width:32px; height:32px; border-radius:9px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:15px; background:rgba(0,80,157,.08); color:#00509d;
    transition:background .18s;
}
.cat-btn.active .cat-icon{background:#00509d; color:#fff;}

/* Stat mini card in sidebar */
.sidebar-stat{
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; border-radius:12px;
    background:#f8fafc; border:1px solid #e2e8f0;
}
.sidebar-stat-icon{
    width:36px; height:36px; border-radius:10px;
    background:linear-gradient(135deg,#dbeafe,#bfdbfe);
    color:#1d4ed8; display:flex; align-items:center;
    justify-content:center; font-size:16px; flex-shrink:0;
}

/* ── FAQ ACCORDION ────────────────────────────────────── */
.faq-item{
    background:#fff; border:1.5px solid #e2e8f0;
    border-radius:16px; overflow:hidden;
    box-shadow:0 1px 3px rgba(0,80,157,.04);
    transition:border-color .18s, box-shadow .18s, transform .15s;
}
.faq-item:hover{
    border-color:#93c5fd;
    box-shadow:0 4px 18px rgba(0,80,157,.09);
    transform:translateY(-1px);
}
.faq-item.open{
    border-color:#3b82f6;
    box-shadow:0 6px 24px rgba(0,80,157,.13);
}
.faq-toggle{
    width:100%; display:flex; align-items:center;
    justify-content:space-between; gap:12px;
    padding:18px 20px; cursor:pointer;
    border:none; background:transparent; text-align:left;
}
.faq-toggle:focus{outline:none;}

.faq-num{
    min-width:34px; height:34px; border-radius:10px;
    background:linear-gradient(135deg,#dbeafe,#bfdbfe);
    color:#1d4ed8; font-size:11px; font-weight:800;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; transition:background .18s,color .18s;
}
.faq-item.open .faq-num{
    background:linear-gradient(135deg,#00509d,#0073c4); color:#fff;
}

.faq-question-text{
    flex:1; font-size:14px; font-weight:700;
    color:#0f172a; line-height:1.45;
}

.faq-caret-wrap{
    width:30px; height:30px; border-radius:9px;
    background:#f1f5f9; display:flex; align-items:center;
    justify-content:center; color:#64748b; font-size:13px;
    flex-shrink:0; transition:all .22s ease;
}
.faq-item.open .faq-caret-wrap{
    background:linear-gradient(135deg,#00509d,#0073c4);
    color:#fff; transform:rotate(180deg);
}

.faq-answer-body{display:none;}
.faq-answer-inner{
    padding:0 20px 18px 66px;
    font-size:13.5px; line-height:1.8; color:#475569;
    border-top:1.5px solid #f1f5f9;
    padding-top:14px;
}

</style>


<!-- ═══════════════════ HERO ═══════════════════ -->
<div class="faq-hero pt-20 pb-0">
    <div class="relative z-10 max-w-4xl mx-auto px-5 pt-10 pb-16 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-white/10 text-blue-100 border border-white/20 backdrop-blur-sm mb-4">
            <i class="ph-fill ph-question text-sm"></i> Pusat Bantuan & Informasi
        </span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-3">
            Frequently Asked <span class="text-blue-200">Questions</span>
        </h1>
        <p class="text-sm sm:text-base text-blue-100/80 max-w-xl mx-auto mb-8 leading-relaxed">
            Temukan jawaban seputar pendaftaran akun, melamar kerja, pasang lowongan, dan fitur AreaKerja lainnya.
        </p>

        <!-- Search Bar -->
        <div class="faq-search-wrap max-w-2xl mx-auto flex items-center gap-3 px-5 py-3.5 mb-3">
            <i class="ph ph-magnifying-glass text-white/60 text-xl shrink-0"></i>
            <input type="text" id="faqSearchInput"
                placeholder="Ketik pertanyaan Anda... cth: cara lamar, koin, kata sandi"
                class="flex-1 py-0.5 text-sm sm:text-base"
                autocomplete="off">
            <button id="faqClearBtn" class="hidden shrink-0 p-1 text-white/50 hover:text-white transition rounded-full">
                <i class="ph ph-x-circle-fill text-lg"></i>
            </button>
        </div>
        <p id="faqCounterText" class="text-xs text-blue-200/70">Menampilkan 10 pertanyaan</p>
    </div>

    <!-- Wave divider into white body -->
    <div class="faq-wave">
        <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 56 C360 0 1080 0 1440 56 L1440 56 L0 56 Z" fill="#f1f5f9"/>
        </svg>
    </div>
</div>

<!-- ═══════════════════ BODY ═══════════════════ -->
<div class="bg-slate-100 pb-16 px-4 sm:px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            <!-- ─── LEFT SIDEBAR ─── -->
            <aside class="faq-sidebar w-full lg:w-72 shrink-0">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 mb-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Kategori</p>
                    <div class="space-y-1.5" id="catBtnWrapper">
                        <button type="button" class="cat-btn active" data-category="all">
                            <span class="cat-icon"><i class="ph-fill ph-squares-four"></i></span>
                            <span>Semua Pertanyaan</span>
                            <span class="ml-auto text-xs font-bold text-slate-400 bg-slate-100 rounded-full px-2 py-0.5">10</span>
                        </button>
                        <button type="button" class="cat-btn" data-category="pelamar">
                            <span class="cat-icon"><i class="ph-fill ph-user-circle"></i></span>
                            <span>Pelamar Kerja</span>
                            <span class="ml-auto text-xs font-bold text-slate-400 bg-slate-100 rounded-full px-2 py-0.5">4</span>
                        </button>
                        <button type="button" class="cat-btn" data-category="perusahaan">
                            <span class="cat-icon"><i class="ph-fill ph-buildings"></i></span>
                            <span>Perusahaan</span>
                            <span class="ml-auto text-xs font-bold text-slate-400 bg-slate-100 rounded-full px-2 py-0.5">2</span>
                        </button>
                        <button type="button" class="cat-btn" data-category="akun">
                            <span class="cat-icon"><i class="ph-fill ph-shield-check"></i></span>
                            <span>Akun & Keamanan</span>
                            <span class="ml-auto text-xs font-bold text-slate-400 bg-slate-100 rounded-full px-2 py-0.5">2</span>
                        </button>
                        <button type="button" class="cat-btn" data-category="umum">
                            <span class="cat-icon"><i class="ph-fill ph-info"></i></span>
                            <span>Umum</span>
                            <span class="ml-auto text-xs font-bold text-slate-400 bg-slate-100 rounded-full px-2 py-0.5">2</span>
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-2.5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Info Layanan</p>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon"><i class="ph-fill ph-question"></i></div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-800">10+</p>
                            <p class="text-xs text-slate-500">Total Pertanyaan</p>
                        </div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon"><i class="ph-fill ph-lightning"></i></div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-800">Instan</p>
                            <p class="text-xs text-slate-500">Pencarian Real-time</p>
                        </div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-icon"><i class="ph-fill ph-headset"></i></div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-800">24/7</p>
                            <p class="text-xs text-slate-500">Tim Support Siap</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- ─── RIGHT: FAQ LIST ─── -->
            <div class="flex-1 min-w-0">
                <!-- FAQ List -->
                <div id="faqList" class="space-y-3">

                    <div class="faq-item" data-category="pelamar">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">01</div>
                            <span class="faq-question-text">Bagaimana cara melamar pekerjaan di AreaKerja?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Masuk atau daftar akun Pelamar, lalu lengkapi biodata, riwayat pendidikan, dan pengalaman kerja. Telusuri lowongan yang sesuai dan klik <strong class="text-slate-700">"Lamar Sekarang"</strong> — lampirkan CV terbaru Anda.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="umum">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">02</div>
                            <span class="faq-question-text">Apa itu AreaKerja?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                AreaKerja adalah portal karir dan platform rekrutmen terpercaya di Indonesia yang menghubungkan pencari kerja bertalenta dengan perusahaan-perusahaan terkemuka secara cepat, transparan, dan mudah digunakan.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="pelamar">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">03</div>
                            <span class="faq-question-text">Apa itu fitur Talent Hunter dan bagaimana cara kerjanya?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Talent Hunter adalah layanan kurasi eksklusif AreaKerja. Ketika profil Anda terpilih, Anda bisa mendapatkan undangan interview dan penawaran kerja langsung dari HRD perusahaan tanpa harus melamar satu per satu.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="perusahaan">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">04</div>
                            <span class="faq-question-text">Bagaimana cara perusahaan memasang lowongan kerja?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Masuk dengan akun Perusahaan, pastikan saldo Koin mencukupi, lalu akses menu <strong class="text-slate-700">"Pasang Lowongan"</strong>. Lengkapi detail posisi, kualifikasi, benefit, dan durasi penayangan, kemudian publikasikan.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="umum">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">05</div>
                            <span class="faq-question-text">Apakah melamar pekerjaan di AreaKerja dipungut biaya?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                <span class="text-emerald-600 font-bold">100% Gratis!</span> Semua pencari kerja bisa mencari lowongan dan mengirim lamaran tanpa biaya. AreaKerja menentang keras segala bentuk pungutan liar dalam proses rekrutmen.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="perusahaan">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">06</div>
                            <span class="faq-question-text">Bagaimana cara kerja sistem Koin untuk perusahaan?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Koin AreaKerja adalah mata uang virtual untuk layanan berbayar seperti mempublikasikan lowongan dan membuka detail kontak kandidat. Pengisian saldo bisa dilakukan via transfer bank atau e-wallet.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="akun">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">07</div>
                            <span class="faq-question-text">Bagaimana cara mengganti kata sandi atau memperbarui profil?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Setelah login, klik menu profil di sudut kanan atas dan pilih <strong class="text-slate-700">"Edit Profil"</strong>. Untuk ganti password, buka bagian keamanan akun, verifikasi dengan password lama, lalu simpan password baru.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="akun">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">08</div>
                            <span class="faq-question-text">Apa yang harus dilakukan jika lupa kata sandi akun?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Klik <span class="text-[#00509d] font-semibold">"Lupa Kata Sandi?"</span> di halaman login, lalu masukkan email terdaftar. Tautan pemulihan akan dikirimkan ke inbox atau folder spam email Anda.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="pelamar">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">09</div>
                            <span class="faq-question-text">Bagaimana cara memantau status lamaran yang sudah dikirim?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Buka menu <strong class="text-slate-700">"Lamaran Kerja"</strong> di dashboard. Di sana tercantum status terkini: <span class="text-amber-600 font-semibold">Terkirim</span>, <span class="text-blue-600 font-semibold">Ditinjau</span>, <span class="text-emerald-600 font-semibold">Panggilan Interview</span>, dan lainnya.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item" data-category="pelamar">
                        <button type="button" class="faq-toggle">
                            <div class="faq-num">10</div>
                            <span class="faq-question-text">Bagaimana cara melaporkan lowongan yang mencurigakan?</span>
                            <div class="faq-caret-wrap"><i class="ph ph-caret-down"></i></div>
                        </button>
                        <div class="faq-answer-body">
                            <div class="faq-answer-inner">
                                Jika ada perusahaan meminta biaya tiket atau akomodasi — jangan transfer! Laporkan ke customer support AreaKerja dengan menyertakan nama perusahaan dan screenshot agar tim kami segera menindaklanjuti.
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Empty State -->
                <div id="faqEmptyState" class="hidden text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-xs mt-3">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-100 text-[#00509d] flex items-center justify-center text-3xl mx-auto mb-4">
                        <i class="ph ph-magnifying-glass"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Tidak ada hasil</h3>
                    <p class="text-xs text-slate-500 mb-5">Coba kata kunci lain atau klik reset.</p>
                    <button id="faqResetBtn"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-[#00509d] hover:bg-[#003d7a] text-white transition shadow-sm">
                        <i class="ph ph-arrow-counter-clockwise"></i> Reset Pencarian
                    </button>
                </div>


            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('faqSearchInput');
    const clearBtn    = document.getElementById('faqClearBtn');
    const counterText = document.getElementById('faqCounterText');
    const emptyState  = document.getElementById('faqEmptyState');
    const resetBtn    = document.getElementById('faqResetBtn');
    const catBtns     = document.querySelectorAll('.cat-btn');
    const items       = document.querySelectorAll('.faq-item');
    let currentCat    = 'all';

    // Accordion
    items.forEach(item => {
        const toggle = item.querySelector('.faq-toggle');
        const body   = item.querySelector('.faq-answer-body');
        body.style.display = 'none';
        toggle.addEventListener('click', () => {
            const isOpen = body.style.display === 'block';
            body.style.display = isOpen ? 'none' : 'block';
            item.classList.toggle('open', !isOpen);
        });
    });

    // Filter
    function filterFaqs() {
        const q = (searchInput.value || '').toLowerCase().trim();
        clearBtn.classList.toggle('hidden', q.length === 0);
        let visible = 0;
        items.forEach(item => {
            const cat   = item.getAttribute('data-category');
            const qTxt  = (item.querySelector('.faq-question-text')?.textContent || '').toLowerCase();
            const aTxt  = (item.querySelector('.faq-answer-body')?.textContent   || '').toLowerCase();
            const match = (currentCat === 'all' || cat === currentCat) && (!q || qTxt.includes(q) || aTxt.includes(q));
            item.style.display = match ? '' : 'none';
            if (match) {
                visible++;
                if (q.length >= 2) {
                    item.querySelector('.faq-answer-body').style.display = 'block';
                    item.classList.add('open');
                }
            }
        });
        emptyState.classList.toggle('hidden', visible > 0);
        counterText.textContent = visible === 0
            ? 'Tidak ada hasil'
            : `Menampilkan ${visible} dari ${items.length} pertanyaan`;
    }

    function resetAll() {
        searchInput.value = '';
        currentCat = 'all';
        catBtns.forEach(b => {
            b.classList.remove('active');
            if (b.getAttribute('data-category') === 'all') b.classList.add('active');
        });
        items.forEach(i => {
            i.querySelector('.faq-answer-body').style.display = 'none';
            i.classList.remove('open');
        });
        filterFaqs();
    }

    searchInput.addEventListener('input', filterFaqs);
    clearBtn.addEventListener('click', () => { searchInput.value = ''; filterFaqs(); });
    if (resetBtn) resetBtn.addEventListener('click', resetAll);

    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            catBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentCat = btn.getAttribute('data-category');
            filterFaqs();
        });
    });

    filterFaqs();
});
</script>

@if(auth()->check() && auth()->user()->role === 'perusahaan')
    @include('layouts.footer-perusahaan')
@else
    @include('layouts.footer')
@endif
@endsection
