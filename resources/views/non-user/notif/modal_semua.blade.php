<!-- Modal Semua Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openAllNotif"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-start justify-center p-2 sm:p-4 bg-black/40 backdrop-blur-sm" @click.self="openAllNotif = false">

    <div class="bg-white w-[90%] sm:w-full sm:max-w-lg rounded-2xl shadow-2xl border border-gray-100 overflow-hidden mt-14">

        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50/70">
            <h2 class="font-bold text-base text-gray-800 flex items-center gap-2">
                <i class="ph ph-bell text-[#00509d] text-lg"></i>
                Semua Notifikasi
            </h2>
            <button @click="openAllNotif=false" class="text-gray-400 hover:text-gray-600 font-bold text-xl leading-none">&times;</button>
        </div>

        <!-- Semua Notifikasi -->
        <div class="max-h-[340px] sm:max-h-[480px] overflow-y-auto divide-y divide-gray-100">
            @forelse (\App\Models\Notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $notif)
                <div data-id="{{ $notif->id }}"
                    onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                    class="notif-item cursor-pointer flex items-start justify-between gap-3 p-4 hover:bg-blue-50/50 transition duration-150 {{ $notif->is_read ? 'bg-gray-50/80 text-gray-600' : 'bg-white font-medium text-gray-900' }}">

                    @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                        <div class="w-9 h-9 flex-shrink-0 mt-0.5">
                            <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                class="w-full h-full object-cover rounded-lg border border-gray-100">
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm break-words leading-relaxed text-gray-800">{!! $notif->pesan !!}</p>
                        <p class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                            <i class="ph ph-clock text-[11px]"></i>
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Tombol Hapus Cepat -->
                    <button @click.stop="hapus({{ $notif->id }}, $el)"
                        class="text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition shrink-0"
                        title="Hapus notifikasi">
                        <i class="ph ph-trash text-base"></i>
                    </button>

                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="ph ph-bell-slash text-3xl mb-2 text-gray-300"></i>
                    <p class="text-xs sm:text-sm font-medium">Belum ada notifikasi yang tersimpan.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-3.5 px-5 border-t border-gray-100 bg-gray-50/70 flex justify-between items-center text-xs">
            <button @click="hapusSemuaBaca()" class="text-[#00509d] hover:text-[#003d7a] font-semibold transition hover:underline flex items-center gap-1">
                <i class="ph ph-trash-simple text-sm"></i> Hapus Semua Dibaca
            </button>

            <button type="button" @click="bacaSemua()" class="text-[#00509d] hover:text-[#003d7a] font-semibold transition hover:underline flex items-center gap-1">
                <i class="ph ph-checks text-sm"></i> Tandai Semua Dibaca
            </button>
        </div>
    </div>
</div>
