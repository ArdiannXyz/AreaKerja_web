<!-- Modal Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openNotif"
    class="fixed inset-0 z-50 flex items-start justify-end p-2 sm:p-4"
    @click.self="openNotif = false">

    <div class="bg-white w-[85%] sm:w-[380px] rounded-2xl shadow-2xl border border-slate-200/80 overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3.5 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <i class="ph ph-bell text-[#00509d] font-bold text-lg"></i>
                <h2 class="font-bold text-sm sm:text-base text-slate-800">Notifikasi</h2>
            </div>
            <button @click="openNotif=false; openAllNotif=true"
                class="text-xs sm:text-sm text-[#00509d] hover:text-[#003d7a] font-bold transition">
                Lihat semua
            </button>
        </div>

        <!-- List Notifikasi -->
        <div class="max-h-[260px] sm:max-h-[420px] overflow-y-auto divide-y divide-slate-100">
            @forelse($global_notifikasis as $notif)
                <div data-id="{{ $notif->id }}"
                    onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                    class="notif-item cursor-pointer flex items-start gap-3 p-3.5 transition 
                    {{ $notif->is_read ? 'bg-white hover:bg-slate-50' : 'bg-blue-50/60 hover:bg-blue-100/50' }}">

                    <!-- Logo perusahaan / Icon -->
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

                    <!-- Pesan -->
                    <div class="flex-1 min-w-0">
                        <div class="text-xs sm:text-sm text-slate-800 leading-snug break-words">{!! $notif->pesan !!}</div>
                        <div class="flex items-center justify-between mt-1.5">
                            <span class="text-[10px] sm:text-[11px] text-slate-400 font-medium">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>

                            <button @click.stop="hapus({{ $notif->id }})"
                                class="text-rose-500 hover:text-rose-700 text-[10px] sm:text-xs font-semibold hover:underline transition">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-8 px-4 text-center">
                    <i class="ph ph-bell-slash text-3xl text-slate-300 mx-auto mb-2 block"></i>
                    <p class="text-slate-400 text-xs font-medium">Tidak ada notifikasi baru</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <button type="button" @click="hapusSemua()" 
                class="text-xs sm:text-sm text-rose-600 hover:text-rose-700 font-bold hover:underline transition">
                Hapus Semua
            </button>

            <button type="button" @click="bacaSemua()" 
                class="text-xs sm:text-sm text-[#00509d] hover:text-[#003d7a] font-bold hover:underline transition">
                Tandai Baca
            </button>
        </div>
    </div>
</div>
