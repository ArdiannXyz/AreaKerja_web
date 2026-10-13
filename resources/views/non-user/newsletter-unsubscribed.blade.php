@extends('layouts.index')

@section('content')
<div class="bg-gray-50 min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center">
        @if (session('status_message'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                {{ session('status_message') }}
            </div>
        @endif

        @if ($success)
            <!-- Unsubscribe Success State -->
            <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-5 ring-8 ring-amber-50/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    <line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Berhenti Berlangganan</h2>
            <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                Email <span class="font-semibold text-gray-800">{{ $email }}</span> telah dinonaktifkan dari daftar penerima newsletter mingguan AreaKerja.
            </p>

            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100 text-left text-xs text-gray-500 leading-relaxed">
                <p class="font-medium text-gray-700 mb-1">Tidak sengaja mengklik tautan ini?</p>
                Anda selalu dapat mengaktifkan kembali langganan newsletter kapan saja tanpa perlu mendaftar ulang.
            </div>

            <div class="space-y-3">
                @if ($email)
                    <form action="{{ route('newsletter.resubscribe') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border-2 border-orange-500 text-orange-600 text-sm font-semibold rounded-xl hover:bg-orange-50 transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Aktifkan Kembali Langganan
                        </button>
                    </form>
                @endif

                <a href="{{ route('home') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-orange-500 text-white text-sm font-semibold rounded-xl hover:bg-orange-600 transition shadow-sm shadow-orange-500/20">
                    Kembali ke Beranda
                </a>
            </div>

        @else
            <!-- Error / Invalid Token State -->
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 ring-8 ring-red-50/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Tautan Tidak Valid</h2>
            <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                {{ $message ?? 'Tautan verifikasi pembatalan langganan tidak valid atau telah kedaluwarsa demi keamanan akun Anda.' }}
            </p>

            <a href="{{ route('home') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-orange-500 text-white text-sm font-semibold rounded-xl hover:bg-orange-600 transition shadow-sm">
                Kembali ke Beranda
            </a>
        @endif
    </div>
</div>
@endsection

