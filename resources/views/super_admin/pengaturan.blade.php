@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header Topbar -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">
                    Pengaturan Akun
                </h1>
                <p class="text-xs text-slate-400 mt-0.5">Kelola keamanan kredensial dan kata sandi akun Super Admin</p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        {{-- Alerts --}}
        <div class="space-y-4 mb-6">
            @if (session('success'))
                <div class="flex items-center gap-2.5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium">
                    <i class="ph ph-check-circle text-base text-emerald-600 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-2.5 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-medium">
                    <i class="ph ph-warning-circle text-base text-rose-600 flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs">
                    <div class="flex items-center gap-2 font-bold mb-2 text-rose-900">
                        <i class="ph ph-warning text-base text-rose-600 flex-shrink-0"></i>
                        Terdapat beberapa kesalahan input:
                    </div>
                    <ul class="list-disc ml-6 space-y-1 text-xs">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Kolom Kiri: Form Ganti Password -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                            <i class="ph ph-lock-key text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Keamanan Kata Sandi</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Perbarui kata sandi Anda secara berkala untuk menjaga akun tetap aman</p>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.password.update') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="old_password" required placeholder="Masukkan kata sandi lama"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="new_password" required placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="new_password_confirmation" required placeholder="Ulangi kata sandi baru"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] transition">
                        </div>

                        <div class="pt-3">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                <i class="ph ph-floppy-disk text-base"></i>
                                Simpan Perubahan Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Info Akun & Email -->
            <div class="space-y-6">
                <!-- Card Email -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                            <i class="ph ph-envelope-simple text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm">Alamat Email</h3>
                    </div>
                    <p class="text-xs text-slate-400 mb-2">Email akun terdaftar saat ini:</p>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 break-all mb-4">
                        {{ auth()->user()->email ?? '-' }}
                    </div>
                    <a href="{{ route('email.ubah') }}"
                        class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-white border border-[#00509d] text-[#00509d] hover:bg-blue-50 text-xs font-semibold rounded-xl transition">
                        <i class="ph ph-pencil-simple text-sm mr-1.5"></i>
                        Ubah Alamat Email
                    </a>
                </div>

                <!-- Card Profil Cepat -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-800 text-sm mb-3">Informasi Akun</h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-400">Username</span>
                            <span class="font-semibold text-slate-800">{{ auth()->user()->username ?? 'Super Admin' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-400">Peran</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-[#00509d] border border-blue-100">Super Admin</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-slate-400">Terdaftar Sejak</span>
                            <span class="font-semibold text-slate-800">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('superadmin.profile') }}"
                            class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                            <i class="ph ph-user text-sm mr-1.5"></i>
                            Buka Profil Lengkap
                        </a>
                    </div>
                </div>
            </div>

        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
