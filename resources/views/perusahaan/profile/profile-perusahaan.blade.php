@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-slate-50 min-h-screen text-slate-800 pt-28 pb-16" x-data="{ tab: '{{ request('tab', 'profil') }}' }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">

            <!-- Alert Notifikasi -->
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs">
                    <i class="ph ph-check-circle text-emerald-600 text-2xl shrink-0"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs">
                    <i class="ph ph-warning-circle text-rose-600 text-2xl shrink-0"></i>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-sm text-rose-900 mb-1">
                        <i class="ph ph-warning text-lg"></i> Terjadi kesalahan:
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. TOP HEADER BANNER CARD -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/90 p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-[#00509d]/20 bg-slate-50 p-1 shrink-0 flex items-center justify-center shadow-sm relative group">
                        @if (Auth::user()->perusahaan->img_profile)
                            <img src="{{ asset('storage/' . Auth::user()->perusahaan->img_profile) }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username) }}&background=f97316&color=fff&size=128" alt="Logo" class="w-full h-full object-cover rounded-xl">
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                                {{ Auth::user()->perusahaan->nama_perusahaan ?? Auth::user()->username }}
                            </h1>
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                âœ“ Terverifikasi
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-500 mt-1">
                            {{ Auth::user()->perusahaan->jenis_perusahaan ?? 'Sektor Usaha Belum Diatur' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                            <i class="ph ph-map-pin text-[#00509d]"></i>
                            {{ Auth::user()->perusahaan->alamatUtama->kota->nama ?? 'Lokasi Utama' }},
                            {{ Auth::user()->perusahaan->alamatUtama->provinsi->nama ?? 'Indonesia' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('profile.edit.perusahaan') }}" class="px-5 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold rounded-xl shadow-sm transition text-sm flex items-center gap-2">
                        <i class="ph ph-pencil-simple text-base"></i> Edit Profil
                    </a>
                </div>
            </div>

            <!-- 2. TAB NAVIGATION SYSTEM -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/90 overflow-hidden">
                <!-- Tab Headers -->
                <div class="flex border-b border-slate-200 bg-slate-50/50 px-4 md:px-8 gap-2 md:gap-6 overflow-x-auto">
                    <button @click="tab = 'profil'"
                        :class="tab === 'profil' ? 'border-[#00509d] text-[#003d7a] font-extrabold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-800 font-bold'"
                        class="py-4 px-3 text-sm flex items-center gap-2 whitespace-nowrap transition">
                        <i class="ph ph-buildings text-lg"></i> Profil Perusahaan
                    </button>

                    <button @click="tab = 'alamat'"
                        :class="tab === 'alamat' ? 'border-[#00509d] text-[#003d7a] font-extrabold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-800 font-bold'"
                        class="py-4 px-3 text-sm flex items-center gap-2 whitespace-nowrap transition">
                        <i class="ph ph-map-pin text-lg"></i> Alamat & Lokasi
                    </button>

                    <button @click="tab = 'keamanan'"
                        :class="tab === 'keamanan' ? 'border-[#00509d] text-[#003d7a] font-extrabold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-800 font-bold'"
                        class="py-4 px-3 text-sm flex items-center gap-2 whitespace-nowrap transition">
                        <i class="ph ph-shield-check text-lg"></i> Keamanan & Akun
                    </button>
                </div>

                <!-- Tab Contents -->
                <div class="p-6 md:p-8">

                    <!-- TAB 1: PROFIL PERUSAHAAN -->
                    <div x-show="tab === 'profil'" x-transition class="space-y-6">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-1">Informasi Perusahaan</h3>
                            <p class="text-xs text-slate-500">Detail identitas publik perusahaan yang ditampilkan pada lowongan kerja.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Perusahaan</label>
                                <input type="text" readonly value="{{ Auth::user()->perusahaan->nama_perusahaan ?? '-' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Badan Usaha / Jenis Perusahaan</label>
                                <input type="text" readonly value="{{ Auth::user()->perusahaan->jenis_perusahaan ?? '-' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Website Resmi</label>
                                <input type="text" readonly value="{{ Auth::user()->perusahaan->website_perusahaan ?? 'Belum diisi' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp / Telepon HRD</label>
                                <input type="text" readonly value="{{ Auth::user()->perusahaan->telepon_perusahaan ?? Auth::user()->telepon ?? '-' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Perusahaan</label>
                            <div class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs md:text-sm text-slate-700 leading-relaxed font-medium min-h-[100px]">
                                {!! nl2br(e(Auth::user()->perusahaan->deskripsi ?? 'Belum ada deskripsi perusahaan.')) !!}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Visi</label>
                                <div class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs text-slate-700 leading-relaxed font-medium min-h-[80px]">
                                    {{ Auth::user()->perusahaan->visi ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Misi</label>
                                <div class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs text-slate-700 leading-relaxed font-medium min-h-[80px]">
                                    {{ Auth::user()->perusahaan->misi ?? 'Belum diisi' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: ALAMAT & LOKASI -->
                    <div x-show="tab === 'alamat'" x-cloak x-transition class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 mb-1">Daftar Alamat Perusahaan</h3>
                                <p class="text-xs text-slate-500">Kelola lokasi kantor utama dan cabang perusahaan Anda.</p>
                            </div>
                            <a href="{{ route('alamat.perusahaan') }}" class="px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl text-xs transition shadow-xs">
                                Kelola Alamat Lengkap
                            </a>
                        </div>

                        @php
                            $almtUtama = Auth::user()->perusahaan->alamatUtama;
                        @endphp

                        @if ($almtUtama && !empty($almtUtama->alamat_lengkap))
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 bg-blue-100 text-[#003d7a] text-xs font-extrabold rounded-full">
                                        Alamat Utama
                                    </span>
                                </div>
                                <p class="text-sm font-bold text-slate-800">
                                    {{ $almtUtama->alamat_lengkap }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $almtUtama->kecamatan->nama ?? '-' }}, 
                                    {{ $almtUtama->kota->nama ?? '-' }}, 
                                    {{ $almtUtama->provinsi->nama ?? '-' }}
                                </p>
                            </div>
                        @else
                            <div class="bg-slate-50 border border-dashed border-slate-300 rounded-2xl p-6 text-center space-y-3">
                                <div class="w-12 h-12 mx-auto bg-blue-100 text-[#003d7a] rounded-full flex items-center justify-center">
                                    <i class="ph ph-map-pin text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">Alamat Utama Belum Diatur</h4>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Silakan atur salah satu alamat kantor perusahaan Anda sebagai alamat utama agar dapat ditampilkan pada profil dan lowongan kerja.</p>
                                </div>
                                <a href="{{ route('alamat.perusahaan') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl text-xs transition shadow-xs mt-2">
                                    <i class="ph ph-plus-circle"></i>
                                    Atur Alamat Utama
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- TAB 3: KEAMANAN & AKUN -->
                    <div x-show="tab === 'keamanan'" x-cloak x-transition class="space-y-8">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 mb-1">Keamanan Akun & Kredensial</h3>
                            <p class="text-xs text-slate-500">Kelola kata sandi dan informasi keamanan akun terdaftar.</p>
                        </div>

                        <!-- Info Email (Read-Only) -->
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-3">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <label class="block text-xs font-bold text-slate-700">Email Utama Akun</label>
                                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200 flex items-center gap-1">
                                    <i class="ph ph-check-circle"></i> Terverifikasi
                                </span>
                            </div>
                            <input type="email" readonly value="{{ Auth::user()->email }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-extrabold text-slate-800 cursor-not-allowed">
                        </div>

                        <!-- Ganti Password dengan OTP -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-11 h-11 rounded-xl bg-blue-100 text-[#003d7a] flex items-center justify-center shrink-0">
                                    <i class="ph ph-key text-2xl font-bold"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-sm text-slate-900">Ubah Kata Sandi (Password)</h4>
                                    <p class="text-xs text-slate-500 mt-1 max-w-md leading-relaxed">
                                        Untuk menjaga keamanan akun perusahaan Anda, proses penggantian kata sandi memerlukan verifikasi kode OTP yang dikirimkan ke email terdaftar.
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('verifikasi_pelamar') }}" class="inline-flex items-center justify-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold px-5 py-2.5 rounded-xl text-xs transition shadow-xs shrink-0">
                                <i class="ph ph-shield-check text-base"></i>
                                Ganti Password via OTP
                            </a>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    @include('layouts.footer')
@endsection

