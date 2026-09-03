<!-- Modal Semua Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openAllNotif"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm" @click.self="openAllNotif = false">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-3.5 border-b bg-gray-50">
            <h2 class="font-bold text-gray-800 text-base flex items-center gap-2">
                <i class="ph ph-bell text-orange-500 text-lg"></i>
                Semua Notifikasi
            </h2>
            <button @click="openAllNotif=false" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
        </div>

        <!-- Semua Notifikasi -->
        <div class="max-h-[500px] overflow-y-auto divide-y divide-gray-100">
            @forelse (\App\Models\Notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $notif)
                <div data-id="{{ $notif->id }}"
                    @click="viewDetail({{ $notif->id }}, '{{ addslashes($notif->judul ?? 'Detail Notifikasi') }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $notif->pesan)) }}', '{{ $notif->created_at->diffForHumans() }}', '{{ route('notifikasi.baca', $notif->id) }}', $el)"
                    class="notif-item cursor-pointer flex items-start gap-3 p-4 hover:bg-orange-50/60 transition {{ $notif->is_read ? 'bg-gray-50/70 text-gray-600' : 'bg-white font-medium text-gray-900' }}">

                    <div class="flex-1 min-w-0">
                        @if(!empty($notif->judul))
                            <h4 class="text-sm font-bold text-gray-800 mb-0.5 truncate">{{ $notif->judul }}</h4>
                        @endif
                        <p class="text-xs text-gray-600 leading-snug">{!! strip_tags($notif->pesan) !!}</p>
                        <p class="text-[11px] text-gray-400 mt-1.5 flex items-center gap-1">
                            <i class="ph ph-clock"></i> {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <button @click.stop="hapus({{ $notif->id }})"
                        class="text-red-400 hover:text-red-600 text-xs p-1 rounded hover:bg-red-50 transition flex-shrink-0"
                        title="Hapus Notifikasi">
                        <i class="ph ph-trash text-base"></i>
                    </button>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="ph ph-bell-slash text-4xl mb-2"></i>
                    <p class="text-sm font-medium">Belum ada notifikasi yang tersimpan.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-3.5 border-t bg-gray-50 flex justify-between items-center text-xs">
            <!-- Hapus Semua Dibaca -->
            <button @click="hapusSemuaBaca()" class="text-red-500 hover:text-red-700 font-medium transition flex items-center gap-1">
                <i class="ph ph-trash"></i> Hapus Semua Dibaca
            </button>

            <!-- Tandai Semua Dibaca -->
            <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrameAll">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center gap-1">
                    <i class="ph ph-checks"></i> Tandai Semua Dibaca
                </button>
            </form>
        </div>

        <iframe name="hiddenFrameAll" style="display:none;"></iframe>
    </div>
</div>
