{{-- ============================================================
    FOOTER PERUSAHAAN — AreaKerja
    Footer khusus halaman dashboard & panel perusahaan
    ============================================================ --}}

<style>
    .ak-pfooter {
        font-family: 'Poppins', sans-serif;
        background-color: #00509d;
        color: #fff;
    }
    .ak-pfooter-link {
        display: inline-block;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.8125rem;
        line-height: 1.5;
        text-decoration: none;
        transition: color 0.18s ease, transform 0.18s ease;
        padding: 0.1rem 0;
    }
    .ak-pfooter-link:hover,
    .ak-pfooter-link:focus {
        color: #ffffff;
        transform: translateX(3px);
        outline: none;
    }
    .ak-pfooter-heading {
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.45);
        margin-bottom: 1rem;
    }
    .ak-pfooter-social {
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
    }
    .ak-pfooter-social:hover {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 255, 255, 0.5);
        color: #ffffff;
        transform: translateY(-2px);
    }
    .ak-pfooter-bottom-link {
        color: rgba(255, 255, 255, 0.55);
        font-size: 0.75rem;
        text-decoration: none;
        transition: color 0.18s ease;
    }
    .ak-pfooter-bottom-link:hover {
        color: #ffffff;
    }
</style>

<footer class="ak-pfooter" aria-label="Footer Perusahaan AreaKerja">
    <div class="max-w-7xl mx-auto px-6 md:px-10 lg:px-8 pt-12 pb-10">

        {{-- Desktop Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">

            {{-- COL 1: Brand --}}
            <div class="col-span-2 md:col-span-4 lg:col-span-2 space-y-5 pr-4">
                <a href="{{ route('perusahaan.dashboard') }}" class="inline-block" aria-label="Dashboard Perusahaan">
                    <img src="{{ asset('images/logo_area_kerja_putih.png') }}"
                         alt="AreaKerja Logo"
                         class="h-7 w-auto object-contain">
                </a>
                <p class="text-white/70 text-sm leading-relaxed max-w-xs">
                    Kelola lowongan, temukan kandidat terbaik, dan kembangkan tim Anda bersama AreaKerja.
                </p>
                {{-- Social Media --}}
                <div class="flex items-center gap-2.5 pt-1">
                    <a href="#" class="ak-pfooter-social" aria-label="Facebook AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-facebook-logo" style="font-size:16px;"></i>
                    </a>
                    <a href="#" class="ak-pfooter-social" aria-label="Instagram AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-instagram-logo" style="font-size:16px;"></i>
                    </a>
                    <a href="#" class="ak-pfooter-social" aria-label="Twitter / X AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-twitter-logo" style="font-size:16px;"></i>
                    </a>
                    <a href="#" class="ak-pfooter-social" aria-label="LinkedIn AreaKerja" rel="noopener noreferrer">
                        <i class="ph-fill ph-linkedin-logo" style="font-size:16px;"></i>
                    </a>
                </div>
            </div>

            {{-- COL 2: Lowongan --}}
            <div class="space-y-1">
                <p class="ak-pfooter-heading">Lowongan</p>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('lowongan.saya.perusahaan') }}" class="ak-pfooter-link">Lowongan Saya</a></li>
                    <li><a href="{{ route('paket.form') }}" class="ak-pfooter-link">Pasang Lowongan</a></li>
                    <li><a href="{{ route('perusahaan.event.index') }}" class="ak-pfooter-link">Event</a></li>
                </ul>
            </div>

            {{-- COL 3: Kandidat --}}
            <div class="space-y-1">
                <p class="ak-pfooter-heading">Kandidat</p>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('perusahaan.kandidat.ak') }}" class="ak-pfooter-link">Cari Kandidat</a></li>
                    <li><a href="{{ route('perusahaan.kandidat.saya') }}" class="ak-pfooter-link">Kandidat Saya</a></li>
                    <li><a href="{{ route('talent-hunter.index') }}" class="ak-pfooter-link">Talent Hunter</a></li>
                </ul>
            </div>

            {{-- COL 4: Akun --}}
            <div class="space-y-1">
                <p class="ak-pfooter-heading">Akun</p>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('profile.perusahaan') }}" class="ak-pfooter-link">Profil Perusahaan</a></li>
                    <li><a href="{{ route('perusahaan.pengaturan') }}" class="ak-pfooter-link">Pengaturan</a></li>
                    <li><a href="{{ route('pelamar.bantuan') }}" class="ak-pfooter-link">Bantuan</a></li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom Bar --}}
    <hr style="border:none; border-top:1px solid rgba(255,255,255,0.1); margin:0;">
    <div class="max-w-7xl mx-auto px-6 md:px-10 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-white/50 text-xs">
                &copy; {{ date('Y') }} AreaKerja.com. All rights reserved.
            </p>
            <div class="flex items-center gap-4 sm:gap-5">
                <a href="{{ route('syarat.ketentuan') }}" class="ak-pfooter-bottom-link">Syarat &amp; Ketentuan</a>
                <a href="{{ route('pelamar.bantuan') }}" class="ak-pfooter-bottom-link">Bantuan</a>
            </div>
        </div>
    </div>
</footer>
