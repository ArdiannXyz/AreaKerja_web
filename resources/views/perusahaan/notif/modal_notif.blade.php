<!-- Modal Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openNotif" class="fixed inset-0 z-50 flex items-start justify-end p-4"
    @click.self="openNotif = false">

    <div class="bg-white w-[380px] rounded-xl shadow-xl border border-gray-200 overflow-hidden mt-14 mr-4">

        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-800 text-base flex items-center gap-2">
                <i class="ph ph-bell text-[#00509d] text-lg"></i>
                Notifikasi
            </h2>
            <button @click="openNotif=false; openAllNotif=true" class="text-xs font-semibold text-[#00509d] hover:text-[#003d7a] transition">
                Lihat semua &rarr;
            </button>
        </div>

        <!-- List Notifikasi -->
        <div class="max-h-[400px] overflow-y-auto divide-y divide-gray-100">
            @forelse($global_notifikasis as $notif)
                <div data-id="{{ $notif->id }}"
                    @click="viewDetail({{ $notif->id }}, '{{ addslashes($notif->judul ?? 'Detail Notifikasi') }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $notif->pesan)) }}', '{{ $notif->created_at->diffForHumans() }}', '{{ route('notifikasi.baca', $notif->id) }}', $el)"
                    class="notif-item cursor-pointer flex items-start gap-3 p-3.5 hover:bg-blue-50/60 transition {{ $notif->is_read ? 'bg-gray-50/70 text-gray-600' : 'bg-white font-medium text-gray-900' }}">

                    <!-- Pesan & Judul -->
                    <div class="flex-1 min-w-0">
                        @if(!empty($notif->judul))
                            <h4 class="text-xs font-bold text-gray-800 mb-0.5 truncate">{{ $notif->judul }}</h4>
                        @endif
                        <p class="text-xs text-gray-600 leading-snug line-clamp-2">{!! strip_tags($notif->pesan) !!}</p>
                        <p class="text-[11px] text-gray-400 mt-1.5 flex items-center gap-1">
                            <i class="ph ph-clock"></i> {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Tombol Hapus -->
                    <button @click.stop="hapus({{ $notif->id }})"
                        class="text-red-400 hover:text-red-600 text-xs p-1 rounded hover:bg-red-50 transition flex-shrink-0"
                        title="Hapus Notifikasi">
                        <i class="ph ph-trash text-base"></i>
                    </button>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400">
                    <i class="ph ph-bell-slash text-3xl mb-1"></i>
                    <p class="text-xs">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <iframe name="hiddenFrame" style="display:none;"></iframe>
        <div class="p-3 border-t bg-gray-50 flex justify-between items-center text-xs">
            <!-- Hapus Semua -->
            <button @click="hapusSemua()" class="text-red-500 hover:text-red-700 font-medium transition flex items-center gap-1">
                <i class="ph ph-trash-simple"></i> Hapus Semua
            </button>

            <!-- Tandai Semua Dibaca -->
            <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrame">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center gap-1">
                    <i class="ph ph-checks"></i> Tandai Baca
                </button>
            </form>
        </div>
    </div>
</div>

