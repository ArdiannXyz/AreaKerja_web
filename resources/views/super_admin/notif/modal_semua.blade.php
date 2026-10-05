<!-- Modal Semua Notifikasi -->
<div x-data="notifHandler()" x-cloak x-show="openAllNotif"
    class="fixed inset-0 z-50 flex items-start justify-center p-2 sm:p-4 bg-black/30" @click.self="openAllNotif = false">

    <div class="bg-white w-[85%] sm:w-full sm:max-w-lg rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-3 sm:px-4 py-3 border-b">
            <h2 class="font-semibold text-base sm:text-lg">Semua Notifikasi</h2>
            <button @click="openAllNotif=false" class="text-xs sm:text-sm text-gray-500">Tutup</button>
        </div>

        <!-- Semua Notifikasi -->
        <div class="max-h-[300px] sm:max-h-[500px] overflow-y-auto">
            @foreach (\App\Models\Notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $notif)
                <div data-id="{{ $notif->id }}"
                    onclick="markAsRead('{{ route('notifikasi.baca', $notif->id) }}', this)"
                    class="notif-item cursor-pointer flex items-start gap-2 sm:gap-3 p-3 border-b 
                    {{ $notif->is_read ? 'bg-gray-200' : 'bg-white' }}">

                    @if ($notif->perusahaan && $notif->perusahaan->img_profile)
                        <div class="w-8 h-8 sm:w-10 sm:h-10 flex-shrink-0">
                            <img src="{{ asset('storage/' . $notif->perusahaan->img_profile) }}"
                                class="w-full h-full object-contain rounded">
                        </div>
                    @endif

                    <div class="flex-1">
                        <p class="text-xs sm:text-sm break-all leading-snug">{!! $notif->pesan !!}</p>
                        <p class="text-[10px] sm:text-xs text-gray-400 mt-1">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div class="p-3 border-t flex justify-between items-center">

            <button @click="hapusSemuaBaca()" class="text-xs sm:text-sm text-blue-800 hover:underline">
                Hapus Semua Dibaca
            </button>

            <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" target="hiddenFrameAll">
                @csrf
                <button type="submit" class="text-xs sm:text-sm text-blue-600 hover:underline">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>

        <iframe name="hiddenFrameAll" style="display:none;"></iframe>
    </div>
</div>
