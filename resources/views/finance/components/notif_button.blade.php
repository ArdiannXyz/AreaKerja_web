@php
    use App\Models\CatatanCash;
    $notifCount = CatatanCash::where('status', 'menunggu_verifikasi')->count();
    $notifikasiCash = CatatanCash::where('status', 'menunggu_verifikasi')->latest()->take(5)->get();
@endphp

<div x-data="{ notifOpen: false }" class="relative">
    <button @click="notifOpen = !notifOpen"
        type="button"
        title="Notifikasi"
        class="relative w-10 h-10 rounded-2xl bg-white border border-slate-200/80 hover:border-[#00509d]/50 hover:bg-blue-50/40 text-slate-700 flex items-center justify-center transition shadow-2xs focus:outline-none">
        <i class="ph ph-bell text-xl"></i>

        @if ($notifCount > 0)
            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-600 text-[10px] font-black text-white shadow-xs animate-bounce">
                {{ $notifCount > 9 ? '9+' : $notifCount }}
            </span>
        @endif
    </button>

    <!-- Notifikasi Menu -->
    <div x-show="notifOpen" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        @click.outside="notifOpen = false" x-cloak
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white shadow-2xl rounded-2xl border border-slate-100 overflow-hidden z-50">
        
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ph-fill ph-bell-ringing text-[#00509d] text-base"></i>
                <span class="font-extrabold text-xs text-slate-800">Menunggu Verifikasi</span>
            </div>
            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 font-extrabold text-[10px] rounded-full">
                {{ $notifCount }} Pending
            </span>
        </div>

        <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 text-xs">
            @forelse ($notifikasiCash as $notif)
                <a href="{{ route('finance.catatan') }}" class="p-3 flex items-start gap-3 hover:bg-blue-50/40 transition group">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0 font-bold">
                        <i class="ph ph-receipt text-base"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 group-hover:text-[#00509d] truncate">
                            {{ $notif->pesanan ?? 'Top Up Koin' }}
                        </p>
                        <p class="text-[11px] text-slate-500 truncate">
                            Dari: <span class="font-semibold text-slate-700">{{ $notif->dari ?? 'Pelanggan' }}</span>
                        </p>
                        <span class="inline-block font-extrabold text-[#00509d] mt-1 text-[11px]">
                            Rp {{ number_format($notif->total, 0, ',', '.') }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="py-8 text-center text-slate-400">
                    <i class="ph ph-check-circle text-3xl text-emerald-500 mx-auto mb-1"></i>
                    <p class="text-xs font-semibold">Semua transaksi sudah diverifikasi.</p>
                </div>
            @endforelse
        </div>

        <div class="p-2.5 border-t border-slate-100 bg-slate-50 text-center">
            <a href="{{ route('finance.catatan') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00509d] hover:text-[#003d7a] transition">
                <span>Buka Catatan Transaksi</span>
                <i class="ph ph-arrow-right font-bold"></i>
            </a>
        </div>
    </div>
</div>
