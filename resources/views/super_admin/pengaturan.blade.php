@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <!-- Main Content -->
    <main class="flex-1 p-6 sm:ml-64" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header Topbar -->
        <div class="flex justify-between items-center mb-8 flex-col sm:flex-row gap-4 sm:gap-0 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Pengaturan Akun
                </h1>
                <p class="text-sm text-gray-500 mt-1">Kelola keamanan kredensial dan kata sandi akun Super Admin</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </div>

        {{-- Alerts --}}
        <div class="max-w-4xl space-y-4 mb-6">
            @if (session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-2 text-rose-900">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
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
        <div class="max-w-4xl grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Kolom Kiri: Form Ganti Password (Lebih Lebar) -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Keamanan Kata Sandi</h2>
                            <p class="text-xs text-gray-500">Perbarui kata sandi Anda secara berkala untuk menjaga akun tetap aman</p>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.password.update') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="old_password" required placeholder="Masukkan kata sandi lama"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition duration-150">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="new_password" required placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition duration-150">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="new_password_confirmation" required placeholder="Ulangi kata sandi baru"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#00509d] focus:border-[#00509d] outline-none transition duration-150">
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-[#00509d] hover:bg-[#003d7a] text-white font-medium rounded-xl text-sm shadow-sm transition duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Info Akun & Email -->
            <div class="space-y-6">
                <!-- Card Email -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-[#00509d]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm">Alamat Email</h3>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">Email akun terdaftar saat ini:</p>
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 break-all mb-4">
                        {{ auth()->user()->email ?? '-' }}
                    </div>
                    <a href="{{ route('email.ubah') }}"
                        class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-white border border-[#00509d] text-[#00509d] hover:bg-blue-50 text-xs font-semibold rounded-xl transition duration-150">
                        Ubah Alamat Email
                    </a>
                </div>

                <!-- Card Profil Cepat -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Informasi Akun</h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Username</span>
                            <span class="font-medium text-gray-800">{{ auth()->user()->username ?? 'Super Admin' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-gray-500">Peran</span>
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 font-semibold rounded-md">Super Admin</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-500">Terdaftar Sejak</span>
                            <span class="font-medium text-gray-800">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('superadmin.profile') }}"
                            class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition duration-150">
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
