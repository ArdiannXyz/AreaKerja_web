{{-- ============================================================
    GLOBAL FOOTER — AreaKerja
    Digunakan oleh semua halaman publik (non-user, perusahaan, kandidat)
    ============================================================ --}}

<style>
    /* ── Footer Variables ── */
    .ak-footer {
        font-family: 'Poppins', sans-serif;
        background-color: #00509d;
        color: #fff;
        position: relative;
    }

    /* ── Nav links ── */
    .ak-footer-link {
        display: inline-block;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.8125rem; /* 13px */
        line-height: 1.5;
        text-decoration: none;
        transition: color 0.18s ease, transform 0.18s ease;
        padding: 0.1rem 0;
    }
    .ak-footer-link:hover,
    .ak-footer-link:focus {
        color: #ffffff;
        transform: translateX(3px);
        outline: none;
    }
    .ak-footer-link:focus-visible {
        outline: 2px solid rgba(255,255,255,0.6);
        outline-offset: 2px;
        border-radius: 3px;
    }

    /* ── Section headings ── */
    .ak-footer-heading {
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.45);
        margin-bottom: 1rem;
    }

    /* ── Social icons ── */
    .ak-social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: rgba(255, 255, 255, 0.8);
        transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        text-decoration: none;
        flex-shrink: 0;
    }
    .ak-social-btn:hover {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 255, 255, 0.5);
        color: #ffffff;
        transform: translateY(-2px);
    }
    .ak-social-btn:focus-visible {
        outline: 2px solid rgba(255,255,255,0.6);
        outline-offset: 2px;
    }

    /* ── Newsletter form ── */
    .ak-newsletter-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    .ak-newsletter-input {
        flex: 1;
        min-width: 0;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 10px;
        padding: 0.55rem 0.9rem;
        color: #fff;
        font-size: 0.8125rem;
        font-family: 'Poppins', sans-serif;
        transition: border-color 0.18s ease, background 0.18s ease;
    }
    .ak-newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }
    .ak-newsletter-input:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.5);
        background: rgba(255, 255, 255, 0.15);
    }
    .ak-newsletter-btn {
        flex-shrink: 0;
        background: #fff;
        color: #00509d;
        border: none;
        border-radius: 10px;
        padding: 0.55rem 1.1rem;
        font-size: 0.8125rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background 0.18s ease, transform 0.18s ease;
        white-space: nowrap;
    }
    .ak-newsletter-btn:hover {
        background: #e8f0fe;
        transform: scale(1.02);
    }
    .ak-newsletter-btn:focus-visible {
        outline: 2px solid rgba(255,255,255,0.6);
        outline-offset: 2px;
    }

    /* ── Divider ── */
    .ak-divider {
        border: none;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin: 0;
    }

    /* ── Bottom bar links ── */
    .ak-bottom-link {
        color: rgba(255, 255, 255, 0.55);
        font-size: 0.75rem;
        text-decoration: none;
        transition: color 0.18s ease;
        white-space: nowrap;
    }
    .ak-bottom-link:hover {
        color: #ffffff;
    }

    /* ── Back to top ── */
    #ak-back-to-top {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #0063bf;
        border: 1px solid rgba(255,255,255,0.18);
        color: #fff;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 999;
        box-shadow: 0 4px 14px rgba(0, 80, 157, 0.4);
        transition: background 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
    }
    #ak-back-to-top.visible {
        display: flex;
    }
    #ak-back-to-top:hover {
        background: #0054a6;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 80, 157, 0.5);
    }
    #ak-back-to-top:focus-visible {
        outline: 2px solid rgba(255,255,255,0.6);
        outline-offset: 2px;
    }

    /* Mobile bottom-nav padding adjustment */
    @media (max-width: 767px) {
        #ak-back-to-top {
            bottom: 5.5rem; /* Above mobile bottom nav bar */
            right: 1rem;
            width: 38px;
            height: 38px;
        }
    }

    /* ── Mobile accordion ── */
    .ak-mobile-section-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: inherit;
        font-family: 'Poppins', sans-serif;
    }
    .ak-mobile-section-content {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        opacity: 0;
    }
    .ak-mobile-section-content.open {
        max-height: 400px;
        opacity: 1;
    }
    .ak-mobile-chevron {
        transition: transform 0.3s ease;
        flex-shrink: 0;
        color: rgba(255,255,255,0.5);
    }
    .ak-mobile-chevron.open {
        transform: rotate(180deg);
    }
</style>

<footer class="ak-footer" aria-label="Footer AreaKerja">

    {{-- ══════════════════════════════════════════════
        MAIN FOOTER — Desktop
    ══════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-6 md:px-10 lg:px-8 pt-12 pb-10">

        {{-- ── Desktop Grid (hidden on mobile) ── --}}
        <div class="hidden lg:grid lg:grid-cols-5 xl:grid-cols-5 gap-8 xl:gap-10">

            {{-- COL 1: Brand --}}
            <div class="lg:col-span-2 xl:col-span-2 space-y-5 pr-4">
                {{-- Logo --}}
                <a href="{{ route('beranda') }}" class="inline-block" aria-label="AreaKerja Beranda">
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}"
                         alt="AreaKerja Logo"
                         class="h-7 w-auto object-contain">
                </a>

                {{-- Tagline --}}
                <p class="text-white/70 text-sm leading-relaxed max-w-xs">
                    Temukan peluang kerja yang tepat dan bangun kariermu bersama AreaKerja.
                </p>

                {{-- Social Media --}}
                <div class="flex items-center gap-2.5 pt-1">
                    <a href="#" class="ak-social-btn" aria-label="Facebook AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-facebook-logo" style="font-size:16px;"></i>
                    </a>
                    <a href="#" class="ak-social-btn" aria-label="Instagram AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-instagram-logo" style="font-size:16px;"></i>
                    </a>
                    <a href="#" class="ak-social-btn" aria-label="Twitter / X AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-twitter-logo" style="font-size:16px;"></i>
                    </a>
                    <a href="#" class="ak-social-btn" aria-label="LinkedIn AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-linkedin-logo" style="font-size:16px;"></i>
                    </a>
                </div>
            </div>

            {{-- COL 2: Jelajahi --}}
            <div class="space-y-1">
                <p class="ak-footer-heading">Jelajahi</p>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('beranda') }}" class="ak-footer-link">Beranda</a>
                    </li>
                    <li>
                        <a href="{{ url('/search') }}" class="ak-footer-link">Cari Lowongan</a>
                    </li>
                    <li>
                        <a href="{{ url('/pelamar/tips-kerja') }}" class="ak-footer-link">Tips Kerja</a>
                    </li>
                    <li>
                        <a href="{{ route('pelamar.daftar-kandidat') }}" class="ak-footer-link">Daftar Kandidat</a>
                    </li>
                    <li>
                        <a href="{{ url('/talent-hunter') }}" class="ak-footer-link">Talent Hunter</a>
                    </li>
                </ul>
            </div>

            {{-- COL 3: Untuk Perusahaan --}}
            <div class="space-y-1">
                <p class="ak-footer-heading">Perusahaan</p>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('paket.form') }}" class="ak-footer-link">Pasang Lowongan</a>
                    </li>
                    <li>
                        <a href="{{ route('perusahaan.kandidat.ak') }}" class="ak-footer-link">Cari Kandidat</a>
                    </li>
                    <li>
                        <a href="{{ route('talent-hunter.index') }}" class="ak-footer-link">Talent Hunter</a>
                    </li>
                    <li>
                        <a href="{{ route('perusahaan.dashboard') }}" class="ak-footer-link">Dashboard</a>
                    </li>
                </ul>
            </div>

            {{-- COL 4: Bantuan --}}
            <div class="space-y-1">
                <p class="ak-footer-heading">Bantuan</p>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ url('/bantuan') }}" class="ak-footer-link">Pusat Bantuan</a>
                    </li>
                    <li>
                        <a href="{{ route('syarat.ketentuan') }}" class="ak-footer-link">Syarat &amp; Ketentuan</a>
                    </li>
                    @guest
                    <li>
                        <a href="{{ route('login') }}" class="ak-footer-link">Masuk</a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="ak-footer-link">Daftar</a>
                    </li>
                    @endguest
                    @auth
                    @php $authRole = Auth::user()->role ?? null; @endphp
                    @if($authRole === 'pelamar')
                    <li>
                        <a href="{{ route('profile.index') }}" class="ak-footer-link">Profil Saya</a>
                    </li>
                    <li>
                        <a href="{{ route('pelamar.lamaran-kerja') }}" class="ak-footer-link">Lamaran Kerja</a>
                    </li>
                    @elseif($authRole === 'perusahaan')
                    <li>
                        <a href="{{ route('perusahaan.dashboard') }}" class="ak-footer-link">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('perusahaan.kandidat.saya') }}" class="ak-footer-link">Kandidat Saya</a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </div>

        </div>

        {{-- ── Newsletter strip (Desktop, below grid) ── --}}
        <div class="hidden lg:block mt-10 pt-8" style="border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                {{-- Newsletter text --}}
                <div class="max-w-xs">
                    <p class="font-semibold text-sm text-white mb-1">Tetap Terhubung</p>
                    <p class="text-white/65 text-xs leading-relaxed">
                        Dapatkan informasi lowongan dan tips karier terbaru melalui email.
                    </p>
                </div>
                {{-- Newsletter form --}}
                <form action="{{ route('subscribe.email') }}" method="POST"
                      class="ak-newsletter-form flex-shrink-0 w-full max-w-sm"
                      aria-label="Formulir berlangganan newsletter">
                    @csrf
                    <input type="email"
                           name="email"
                           placeholder="Email kamu..."
                           class="ak-newsletter-input"
                           aria-label="Alamat email untuk berlangganan">
                    <button type="submit" class="ak-newsletter-btn">Daftar</button>
                </form>
            </div>
            {{-- Form feedback --}}
            @error('email')
                <p class="text-red-300 mt-2 text-xs">{{ $message }}</p>
            @enderror
            @if (session('success'))
                <p class="text-green-300 mt-2 text-xs">{{ session('success') }}</p>
            @endif
            <p class="text-white/40 text-xs mt-2">Tidak ada spam. Hanya informasi yang relevan.</p>
        </div>

        {{-- ══════════════════════════════════════════════
            MOBILE / TABLET LAYOUT (< lg)
        ══════════════════════════════════════════════ --}}
        <div class="lg:hidden">

            {{-- Brand --}}
            <div class="flex items-start gap-4 mb-8">
                <a href="{{ route('beranda') }}" aria-label="AreaKerja Beranda">
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}"
                         alt="AreaKerja Logo"
                         class="h-7 w-auto object-contain">
                </a>
                <p class="text-white/65 text-xs leading-relaxed mt-0.5">
                    Temukan peluang kerja yang tepat dan bangun kariermu bersama AreaKerja.
                </p>
            </div>

            {{-- Accordion sections --}}
            <div class="divide-y divide-white/10 border-t border-white/10">

                {{-- Jelajahi --}}
                <div class="py-3.5" x-data="{ open: false }">
                    <button class="ak-mobile-section-toggle" @click="open = !open" aria-expanded="open" aria-controls="mobile-jelajahi">
                        <span class="text-sm font-semibold text-white">Jelajahi</span>
                        <i class="ph ph-caret-down ak-mobile-chevron" :class="open && 'open'" style="font-size:14px;"></i>
                    </button>
                    <div id="mobile-jelajahi" class="ak-mobile-section-content" :class="open && 'open'">
                        <ul class="pt-3 space-y-3 pb-1">
                            <li><a href="{{ route('beranda') }}" class="ak-footer-link">Beranda</a></li>
                            <li><a href="{{ url('/search') }}" class="ak-footer-link">Cari Lowongan</a></li>
                            <li><a href="{{ url('/pelamar/tips-kerja') }}" class="ak-footer-link">Tips Kerja</a></li>
                            <li><a href="{{ route('pelamar.daftar-kandidat') }}" class="ak-footer-link">Daftar Kandidat</a></li>
                            <li><a href="{{ url('/talent-hunter') }}" class="ak-footer-link">Talent Hunter</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Untuk Perusahaan --}}
                <div class="py-3.5" x-data="{ open: false }">
                    <button class="ak-mobile-section-toggle" @click="open = !open" aria-expanded="open" aria-controls="mobile-perusahaan">
                        <span class="text-sm font-semibold text-white">Untuk Perusahaan</span>
                        <i class="ph ph-caret-down ak-mobile-chevron" :class="open && 'open'" style="font-size:14px;"></i>
                    </button>
                    <div id="mobile-perusahaan" class="ak-mobile-section-content" :class="open && 'open'">
                        <ul class="pt-3 space-y-3 pb-1">
                            <li><a href="{{ route('paket.form') }}" class="ak-footer-link">Pasang Lowongan</a></li>
                            <li><a href="{{ route('perusahaan.kandidat.ak') }}" class="ak-footer-link">Cari Kandidat</a></li>
                            <li><a href="{{ route('talent-hunter.index') }}" class="ak-footer-link">Talent Hunter</a></li>
                            <li><a href="{{ route('perusahaan.dashboard') }}" class="ak-footer-link">Dashboard</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Bantuan --}}
                <div class="py-3.5" x-data="{ open: false }">
                    <button class="ak-mobile-section-toggle" @click="open = !open" aria-expanded="open" aria-controls="mobile-bantuan">
                        <span class="text-sm font-semibold text-white">Bantuan</span>
                        <i class="ph ph-caret-down ak-mobile-chevron" :class="open && 'open'" style="font-size:14px;"></i>
                    </button>
                    <div id="mobile-bantuan" class="ak-mobile-section-content" :class="open && 'open'">
                        <ul class="pt-3 space-y-3 pb-1">
                            <li><a href="{{ url('/bantuan') }}" class="ak-footer-link">Pusat Bantuan</a></li>
                            <li><a href="{{ route('syarat.ketentuan') }}" class="ak-footer-link">Syarat &amp; Ketentuan</a></li>
                            @guest
                            <li><a href="{{ route('login') }}" class="ak-footer-link">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="ak-footer-link">Daftar</a></li>
                            @endguest
                            @auth
                            @if((Auth::user()->role ?? null) === 'pelamar')
                            <li><a href="{{ route('profile.index') }}" class="ak-footer-link">Profil Saya</a></li>
                            <li><a href="{{ route('pelamar.lamaran-kerja') }}" class="ak-footer-link">Lamaran Kerja</a></li>
                            @elseif((Auth::user()->role ?? null) === 'perusahaan')
                            <li><a href="{{ route('perusahaan.dashboard') }}" class="ak-footer-link">Dashboard</a></li>
                            <li><a href="{{ route('perusahaan.kandidat.saya') }}" class="ak-footer-link">Kandidat Saya</a></li>
                            @endif
                            @endauth
                        </ul>
                    </div>
                </div>

            </div>

            {{-- Newsletter (Mobile) --}}
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="font-semibold text-sm text-white mb-1">Tetap Terhubung</p>
                <p class="text-white/60 text-xs leading-relaxed mb-3">
                    Dapatkan informasi lowongan dan tips karier terbaru melalui email.
                </p>
                <form action="{{ route('subscribe.email') }}" method="POST"
                      class="ak-newsletter-form"
                      aria-label="Formulir berlangganan newsletter">
                    @csrf
                    <input type="email"
                           name="email"
                           placeholder="Email kamu..."
                           class="ak-newsletter-input"
                           aria-label="Alamat email untuk berlangganan">
                    <button type="submit" class="ak-newsletter-btn">Daftar</button>
                </form>
                @error('email')
                    <p class="text-red-300 mt-2 text-xs">{{ $message }}</p>
                @enderror
                @if (session('success'))
                    <p class="text-green-300 mt-2 text-xs">{{ session('success') }}</p>
                @endif
                <p class="text-white/40 text-xs mt-2">Tidak ada spam. Hanya informasi yang relevan.</p>
            </div>

            {{-- Social Media (Mobile) --}}
            <div class="mt-6 flex items-center gap-2.5">
                <a href="#" class="ak-social-btn" aria-label="Facebook AreaKerja" rel="noopener noreferrer">
                    <i class="ph-fill ph-facebook-logo" style="font-size:15px;"></i>
                </a>
                <a href="#" class="ak-social-btn" aria-label="Instagram AreaKerja" rel="noopener noreferrer">
                    <i class="ph-fill ph-instagram-logo" style="font-size:15px;"></i>
                </a>
                <a href="#" class="ak-social-btn" aria-label="Twitter / X AreaKerja" rel="noopener noreferrer">
                    <i class="ph-fill ph-twitter-logo" style="font-size:15px;"></i>
                </a>
                <a href="#" class="ak-social-btn" aria-label="LinkedIn AreaKerja" rel="noopener noreferrer">
                    <i class="ph-fill ph-linkedin-logo" style="font-size:15px;"></i>
                </a>
            </div>

        </div>

    </div>{{-- /max-w-7xl --}}

    {{-- ══════════════════════════════════════════════
        BOTTOM BAR
    ══════════════════════════════════════════════ --}}
    <hr class="ak-divider">
    <div class="max-w-7xl mx-auto px-6 md:px-10 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            {{-- Copyright --}}
            <p class="text-white/50 text-xs">
                &copy; {{ date('Y') }} AreaKerja.com. All rights reserved.
            </p>

            {{-- Legal links --}}
            <div class="flex items-center gap-4 sm:gap-5">
                <a href="{{ route('syarat.ketentuan') }}" class="ak-bottom-link">Syarat &amp; Ketentuan</a>
                <a href="{{ url('/bantuan') }}" class="ak-bottom-link">Bantuan</a>
            </div>

        </div>
    </div>

</footer>

{{-- Back to Top Button --}}
<button id="ak-back-to-top"
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
        aria-label="Kembali ke atas halaman"
        title="Kembali ke atas">
    <i class="ph ph-arrow-up" style="font-size:17px; font-weight:700;"></i>
</button>

<script>
    (function () {
        'use strict';
        var btn = document.getElementById('ak-back-to-top');
        if (!btn) return;

        function onScroll() {
            if (window.scrollY > 300) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
    })();
</script>
