<!-- Modal Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openNotif"
    class="fixed inset-0 z-50 flex items-start justify-end p-2 sm:p-4 bg-black/10 backdrop-blur-xs"
    @click.self="openNotif = false">

    <div x-show="openNotif"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        @click.outside="openNotif = false"
        class="bg-white w-[88%] sm:w-[360px] rounded-2xl shadow-2xl border border-gray-100 overflow-hidden mt-16 mr-2 sm:mr-6 lg:mr-10">

        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/70">
            <h2 class="font-bold text-sm sm:text-base text-gray-800 flex items-center gap-1.5">
                <i class="ph ph-bell text-[#00509d] text-base"></i>
                Notifikasi
            </h2>
            <button @click="openNotif=false; openAllNotif=true"
                class="text-xs text-[#00509d] font-semibold hover:underline">
                Lihat semua &rarr;
            </button>
        </div>

        <!-- List Notifikasi -->
        <div class="max-h-[240px] sm:max-h-[380px] overflow-y-auto divide-y divide-gray-100">
            @forelse($global_notifikasis as $notif)
                <div data-id="{{ $notif->id }}"
                    onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                    class="notif-item cursor-pointer flex items-start justify-between gap-2.5 p-3.5 hover:bg-blue-50/50 transition duration-150 {{ $notif->is_read ? 'bg-gray-50/80 text-gray-600' : 'bg-white font-medium text-gray-900' }}">

                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-[13px] break-words leading-relaxed text-gray-800">{!! $notif->pesan !!}</p>
                        <p class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                            <i class="ph ph-clock text-[11px]"></i>
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Tombol Hapus Cepat (Tanpa Pop Up Berat) -->
                    <button @click.stop="hapus({{ $notif->id }}, $el)"
                        class="text-gray-300 hover:text-red-500 hover:bg-red-50 p-1 rounded-md transition shrink-0"
                        title="Hapus notifikasi">
                        <i class="ph ph-trash text-sm"></i>
                    </button>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400">
                    <i class="ph ph-bell-slash text-2xl mb-1 text-gray-300"></i>
                    <p class="text-xs">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-3 px-4 border-t border-gray-100 bg-gray-50/70 flex justify-between items-center text-xs">
            <button @click="hapusSemua()" 
                class="text-red-500 hover:text-red-700 font-medium transition flex items-center gap-1 hover:underline">
                <i class="ph ph-trash-simple text-sm"></i> Hapus Semua
            </button>

            <button type="button" @click="bacaSemua()" 
                class="text-[#00509d] hover:text-[#003d7a] font-semibold transition flex items-center gap-1 hover:underline">
                <i class="ph ph-checks text-sm"></i> Tandai Baca
            </button>
        </div>
    </div>
</div>

