@extends('admin.sidebar.index')

@section('sidebaradmin')
<main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen">

    @php
        $user = $chat->user;
        $userName = $user?->nama_lengkap ?: ($user?->username ?: 'Pengguna #'.$chat->user_id);
        $roleBadge = $user?->role === 'perusahaan' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-[#00509d]';
        $roleLabel = $user?->role === 'perusahaan' ? 'Perusahaan' : ($user?->role === 'pelamar' ? 'Pelamar' : ucfirst($user?->role ?? 'User'));
    @endphp

    <!-- BREADCRUMB & HEADER -->
    <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <a href="{{ route('admin.bantuan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#00509d] transition">
            <i class="ph ph-arrow-left text-base"></i>
            <span>Kembali ke Daftar Bantuan</span>
        </a>

        <!-- Status & Actions -->
        <div class="flex items-center gap-2">
            @if($chat->status !== 'selesai')
                <form action="{{ route('admin.bantuan.status', $chat->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="selesai">
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold transition active:scale-95">
                        <i class="ph ph-check-circle text-sm"></i>
                        <span>Tandai Selesai</span>
                    </button>
                </form>
            @else
                <form action="{{ route('admin.bantuan.status', $chat->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="menunggu">
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition active:scale-95">
                        <i class="ph ph-arrow-counter-clockwise text-sm"></i>
                        <span>Buka Kembali</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- MAIN CHAT PANEL -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-[80vh] min-h-[600px]">

        <!-- TOP BAR USER INFO -->
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                @if($user?->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $userName }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-xs">
                @else
                    <div class="w-12 h-12 rounded-full bg-[#00509d] text-white font-bold text-base flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr($userName, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-800">{{ $userName }}</h2>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $roleBadge }}">
                            {{ $roleLabel }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        <span>{{ $user?->email }}</span>
                        @if($user?->telepon)
                            <span class="mx-1">&bull;</span>
                            <span>{{ $user->telepon }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Tiket #AK-{{ str_pad($chat->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $chat->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($chat->status === 'dibalas' ? 'bg-blue-100 text-[#00509d]' : 'bg-amber-100 text-amber-700') }}">
                    {{ ucfirst($chat->status) }}
                </span>
            </div>
        </div>

        <!-- MESSAGES CONTAINER -->
        <div id="adminChatMessagesContainer" class="flex-1 p-4 sm:p-6 overflow-y-auto space-y-4 bg-slate-50/40 overscroll-contain">
            @forelse($messages as $msg)
                @if($msg->sender_type === 'user')
                    {{-- User Message (Left Aligned for Admin) --}}
                    <div class="flex items-start gap-3 max-w-[85%] sm:max-w-[70%]">
                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                            {{ strtoupper(substr($userName, 0, 1)) }}
                        </div>
                        <div class="flex flex-col">
                            <div class="bg-white border border-slate-200 text-slate-800 p-3.5 sm:p-4 rounded-2xl rounded-tl-sm shadow-xs">
                                <div class="flex items-center justify-between gap-3 mb-1">
                                    <span class="text-xs font-bold text-slate-700">{{ $userName }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">{!! nl2br(e($msg->pesan)) !!}</p>
                            </div>
                            <span class="text-[10px] text-slate-400 mt-1 ml-1">{{ $msg->created_at->isoFormat('D MMM Y, H:i') }}</span>
                        </div>
                    </div>
                @else
                    {{-- Admin / Superadmin Reply (Right Aligned) --}}
                    <div class="flex flex-col items-end">
                        <div class="max-w-[85%] sm:max-w-[70%] bg-[#00509d] text-white p-3.5 sm:p-4 rounded-2xl rounded-tr-sm shadow-xs">
                            <div class="flex items-center justify-between gap-3 mb-1 border-b border-white/20 pb-1">
                                <span class="text-[11px] font-semibold text-blue-100 flex items-center gap-1">
                                    <i class="ph ph-shield-check"></i>
                                    {{ $msg->sender?->nama_lengkap ?: 'Admin AreaKerja' }}
                                </span>
                                <span class="text-[10px] text-blue-200">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">{!! nl2br(e($msg->pesan)) !!}</p>
                        </div>
                        <div class="flex items-center gap-1 text-[10px] text-slate-400 mt-1 mr-1">
                            <span>{{ $msg->created_at->isoFormat('D MMM Y, H:i') }}</span>
                            <i class="ph ph-check-circle text-emerald-500 font-bold" title="Terkirim ke pengguna"></i>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12 text-slate-400 text-xs">
                    Pengguna belum mengirimkan pesan dalam tiket ini.
                </div>
            @endforelse
        </div>

        <!-- REPLY INPUT FORM -->
        <div class="p-4 bg-white border-t border-slate-200">
            <form id="adminReplyForm" action="{{ route('admin.bantuan.reply', $chat->id) }}" method="POST" class="flex items-end gap-3">
                @csrf
                <div class="flex-1 relative">
                    <textarea id="adminPesanInput" name="pesan" rows="2" required
                              placeholder="Tulis balasan untuk {{ $userName }}... (Tekan Enter untuk kirim, Shift+Enter untuk baris baru)"
                              class="w-full text-xs sm:text-sm p-3 max-h-36 min-h-[50px] resize-none bg-slate-50 border border-slate-300 rounded-2xl focus:bg-white focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all placeholder:text-slate-400"></textarea>
                </div>
                <button type="submit" id="adminSendBtn"
                        class="h-[50px] px-5 bg-[#00509d] hover:bg-[#003d7a] text-white rounded-2xl font-semibold flex items-center justify-center gap-2 shadow-xs hover:shadow-md transition active:scale-95 shrink-0">
                    <span class="hidden sm:inline text-xs sm:text-sm">Kirim Balasan</span>
                    <i class="ph-fill ph-paper-plane-tilt text-base sm:text-lg"></i>
                </button>
            </form>
        </div>

    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('adminChatMessagesContainer');
        const input = document.getElementById('adminPesanInput');
        const form = document.getElementById('adminReplyForm');

        function scrollToBottom() {
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
        scrollToBottom();

        if (input && form) {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.value.trim().length > 0) {
                        form.submit();
                    }
                }
            });
        }
    });
</script>
@endsection

