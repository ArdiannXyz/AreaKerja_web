<!-- Modal Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openNotif" class="fixed inset-0 z-50 flex items-start justify-end p-2 sm:p-4"
    @click.self="openNotif = false">

    <div class="bg-white w-[80%] sm:w-[360px] rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-3 sm:px-4 py-3 border-b">
            <h2 class="font-semibold text-sm sm:text-lg">Notifikasi</h2>
            <button @click="openNotif=false; openAllNotif=true" class="text-xs sm:text-sm text-blue-700">
                Lihat semua
            </button>
        </div>

        <!-- List Notifikasi -->
        <div class="max-h-[200px] sm:max-h-[400px] overflow-y-auto">
            @forelse($global_notifikasis as $notif)
                <div data-id="{{ $notif->id }}"
                    onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                    class="notif-item cursor-pointer flex items-start gap-2 p-3 border-b 
                    {{ $notif->is_read ? 'bg-gray-200' : 'bg-white' }}">

                    <!-- Logo perusahaan -->
                    @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                        <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                            <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                class="w-full h-full object-cover rounded-md">
                        </div>
                    @endif

                    <!-- Pesan -->
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm break-all leading-snug">{!! $notif->pesan !!}</p>
                        <p class="text-[10px] text-gray-400 mt-1">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>

                        <button @click.stop="hapus({{ $notif->id }})"
                            class="text-red-500 text-[10px] sm:text-xs hover:underline mt-1">
                            Hapus
                        </button>
                    </div>
                </div>
            @empty
                <p class="p-3 text-gray-500 text-xs text-center">Tidak ada notifikasi</p>
            @endforelse
        </div>

        <!-- Footer -->
        <iframe name="hiddenFrame" style="display:none;"></iframe>
        <div class="p-3 border-t flex justify-between items-center">
            <button @click="hapusSemua()" class="text-[11px] sm:text-sm text-red-600 hover:underline">
                Hapus Semua
            </button>

            <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrame">
                @csrf
                <button type="submit" class="text-[11px] sm:text-sm text-blue-600 hover:underline">
                    Tandai Baca
                </button>
            </form>
        </div>
    </div>
</div>
