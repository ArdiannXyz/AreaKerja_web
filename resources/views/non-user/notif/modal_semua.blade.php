<!-- Modal Semua Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openAllNotif"
    class="fixed inset-0 z-50 flex items-start justify-center p-2 sm:p-4 bg-black/30" @click.self="openAllNotif = false">

    <div class="bg-white w-[85%] sm:w-full sm:max-w-lg rounded-2xl shadow-2xl border border-slate-200/80 overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3.5 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <i class="ph ph-bell text-[#00509d] font-bold text-lg"></i>
                <h2 class="font-bold text-base sm:text-lg text-slate-800">Semua Notifikasi</h2>
            </div>
            <button @click="openAllNotif=false" class="text-xs sm:text-sm text-slate-400 hover:text-slate-700 font-semibold transition">
                Tutup
            </button>
        </div>

        <!-- Semua Notifikasi -->
        <div class="max-h-[320px] sm:max-h-[500px] overflow-y-auto divide-y divide-slate-100">
            @foreach (\App\Models\Notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $notif)
                <div data-id="{{ $notif->id }}"
                    onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                    class="notif-item cursor-pointer flex items-start gap-3 p-3.5 transition 
                    {{ $notif->is_read ? 'bg-white hover:bg-slate-50' : 'bg-blue-50/60 hover:bg-blue-100/50' }}">

                    @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                        <div class="w-9 h-9 sm:w-10 sm:h-10 flex-shrink-0 rounded-xl overflow-hidden border border-slate-200/80 bg-white shadow-xs">
                            <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-9 h-9 sm:w-10 sm:h-10 flex-shrink-0 rounded-xl bg-blue-100 text-[#00509d] flex items-center justify-center font-bold shadow-xs">
                            <i class="ph ph-info text-lg"></i>
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <div class="text-xs sm:text-sm text-slate-800 leading-snug break-words">{!! $notif->pesan !!}</div>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <button type="button" @click="hapusSemuaBaca()" class="text-xs sm:text-sm text-rose-600 hover:text-rose-700 font-bold hover:underline transition">
                Hapus Semua Dibaca
            </button>

            <button type="button" @click="bacaSemua()" class="text-xs sm:text-sm text-[#00509d] hover:text-[#003d7a] font-bold hover:underline transition">
                Tandai Semua Dibaca
            </button>
        </div>
    </div>
</div>
