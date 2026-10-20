@extends(Auth::check() && Auth::user()->role === 'perusahaan' ? 'layouts.index-perusahaan' : 'layouts.index')

@section('content')
<div class="bg-slate-50 min-h-screen pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        <!-- Breadcrumb / Back button -->
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('faq') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-[#00509d] transition">
                <i class="ph ph-arrow-left text-base"></i>
                <span>Kembali ke FAQ</span>
            </a>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $chat->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($chat->status === 'dibalas' ? 'bg-blue-100 text-[#00509d]' : 'bg-amber-100 text-amber-700') }}">
                    <span class="w-2 h-2 rounded-full {{ $chat->status === 'selesai' ? 'bg-emerald-500' : ($chat->status === 'dibalas' ? 'bg-[#00509d]' : 'bg-amber-500 animate-pulse') }}"></span>
                    {{ $chat->status === 'selesai' ? 'Permasalahan Selesai' : ($chat->status === 'dibalas' ? 'Dibalas oleh Admin' : 'Menunggu Tanggapan Admin') }}
                </span>
            </div>
        </div>

        <!-- Chat Container Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[75vh] min-h-[550px]">

            <!-- Chat Header -->
            <div class="px-5 py-4 bg-gradient-to-r from-[#00509d] to-[#003d7a] text-white flex items-center justify-between shadow-xs z-10">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-2xl text-white">
                            <i class="ph ph-headset"></i>
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 border-2 border-[#00509d] rounded-full"></span>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold leading-tight flex items-center gap-2">
                            Layanan Bantuan & Keluhan
                            <span class="text-[10px] font-medium bg-white/20 px-2 py-0.5 rounded-full text-blue-100">Official Support</span>
                        </h2>
                        <p class="text-xs text-blue-100 flex items-center gap-1 mt-0.5">
                            <i class="ph ph-shield-check text-emerald-300"></i>
                            Admin AreaKerja siap merespon pertanyaan & kendala Anda
                        </p>
                    </div>
                </div>

                <div class="text-right hidden sm:block">
                    <p class="text-xs text-blue-100">Tiket ID</p>
                    <p class="text-xs font-mono font-bold text-white">#AK-{{ str_pad($chat->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <!-- Chat Messages Body (Scrollable) -->
            <div id="chatMessagesContainer" class="flex-1 p-4 sm:p-6 overflow-y-auto space-y-4 bg-slate-50/60 overscroll-contain">

                <!-- Welcome Note from AreaKerja -->
                <div class="flex items-start gap-3 max-w-[85%] sm:max-w-[75%]">
                    <div class="w-8 h-8 rounded-full bg-[#00509d] text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5 shadow-xs">
                        <i class="ph ph-headset"></i>
                    </div>
                    <div class="bg-white border border-slate-200 text-slate-800 p-4 rounded-2xl rounded-tl-sm shadow-xs space-y-2">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs font-bold text-[#00509d] flex items-center gap-1">
                                Customer Care AreaKerja
                                <i class="ph-fill ph-seal-check text-blue-500"></i>
                            </span>
                            <span class="text-[10px] text-slate-400">Otomatis</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                            Halo <strong>{{ Auth::user()->nama_lengkap ?: Auth::user()->username }}</strong>, selamat datang di Layanan Bantuan AreaKerja! 👋
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Silakan ceritakan kendala, pertanyaan tentang akun/lowongan/pembayaran, atau masukan Anda di bawah ini. Riwayat percakapan Anda akan tersimpan di sini agar mudah dicek kembali kapan saja.
                        </p>
                    </div>
                </div>

                <!-- Messages Stream -->
                @forelse($messages as $msg)
                    @if($msg->sender_type === 'user')
                        {{-- User Message (Right Aligned) --}}
                        <div class="flex flex-col items-end">
                            <div class="max-w-[85%] sm:max-w-[70%] bg-[#00509d] text-white p-3.5 sm:p-4 rounded-2xl rounded-tr-sm shadow-xs">
                                <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">{!! nl2br(e($msg->pesan)) !!}</p>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-slate-400 mt-1 mr-1">
                                <span>{{ $msg->created_at->format('H:i') }}</span>
                                @if($msg->is_read)
                                    <i class="ph ph-checks text-blue-500 font-bold" title="Sudah dibaca oleh Admin"></i>
                                @else
                                    <i class="ph ph-check text-slate-400" title="Terkirim"></i>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Admin Message (Left Aligned) --}}
                        <div class="flex items-start gap-3 max-w-[85%] sm:max-w-[75%]">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5 shadow-xs">
                                <i class="ph ph-shield-check"></i>
                            </div>
                            <div class="flex flex-col">
                                <div class="bg-white border border-slate-200 text-slate-800 p-3.5 sm:p-4 rounded-2xl rounded-tl-sm shadow-xs">
                                    <div class="flex items-center justify-between gap-3 mb-1">
                                        <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                            {{ $msg->sender?->nama_lengkap ?: 'Admin AreaKerja' }}
                                            <span class="text-[9px] bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded font-semibold">Admin</span>
                                        </span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">{!! nl2br(e($msg->pesan)) !!}</p>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 ml-1">{{ $msg->created_at->format('H:i') }} &bull; {{ $msg->created_at->isoFormat('D MMM Y') }}</span>
                            </div>
                        </div>
                    @endif
                @empty
                    {{-- Empty state handled by default welcome message above --}}
                @endforelse

            </div>

            <!-- Chat Input Area -->
            <div class="p-3 sm:p-4 bg-white border-t border-slate-200">
                @if(session('success'))
                    <div class="mb-2 text-xs text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 flex items-center gap-1.5">
                        <i class="ph ph-check-circle text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form id="chatForm" action="{{ route('bantuan.chat.send') }}" method="POST" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea id="pesanInput" name="pesan" rows="1" required
                                  placeholder="Tulis pesan atau keluhan Anda di sini... (Tekan Enter untuk baris baru)"
                                  class="w-full text-xs sm:text-sm p-3 max-h-32 min-h-[46px] resize-none bg-slate-50 border border-slate-300 rounded-2xl focus:bg-white focus:border-[#00509d] focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all placeholder:text-slate-400"></textarea>
                    </div>
                    <button type="submit" id="sendBtn"
                            class="h-[46px] px-4 sm:px-5 bg-[#00509d] hover:bg-[#003d7a] text-white rounded-2xl font-semibold flex items-center justify-center gap-2 shadow-xs hover:shadow-md transition active:scale-95 shrink-0">
                        <span class="hidden sm:inline text-xs sm:text-sm">Kirim</span>
                        <i class="ph-fill ph-paper-plane-tilt text-base sm:text-lg"></i>
                    </button>
                </form>
            </div>

        </div>

        <!-- Help Info Footer -->
        <div class="mt-4 text-center">
            <p class="text-xs text-slate-500">
                Pesan Anda aman dan terenkripsi. Jam operasional admin: <strong>Senin - Sabtu (08.00 - 17.00 WIB)</strong>.
            </p>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatContainer = document.getElementById('chatMessagesContainer');
        const pesanInput = document.getElementById('pesanInput');
        const chatForm = document.getElementById('chatForm');
        const sendBtn = document.getElementById('sendBtn');

        // Auto scroll to bottom
        function scrollToBottom() {
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }
        scrollToBottom();

        // Auto-expand textarea
        if (pesanInput) {
            pesanInput.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Submit on Ctrl+Enter
            pesanInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.value.trim().length > 0) {
                        chatForm.requestSubmit();
                    }
                }
            });
        }

        // AJAX submit without page refresh
        if (chatForm) {
            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const text = pesanInput.value.trim();
                if (!text) return;

                sendBtn.disabled = true;
                sendBtn.classList.add('opacity-70');

                const formData = new FormData(chatForm);

                fetch(chatForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.message) {
                        // Append new bubble
                        const bubble = document.createElement('div');
                        bubble.className = 'flex flex-col items-end';
                        bubble.innerHTML = `
                            <div class="max-w-[85%] sm:max-w-[70%] bg-[#00509d] text-white p-3.5 sm:p-4 rounded-2xl rounded-tr-sm shadow-xs">
                                <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">${data.message.pesan}</p>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-slate-400 mt-1 mr-1">
                                <span>${data.message.time}</span>
                                <i class="ph ph-check text-slate-400" title="Terkirim"></i>
                            </div>
                        `;
                        chatContainer.appendChild(bubble);
                        pesanInput.value = '';
                        pesanInput.style.height = 'auto';
                        scrollToBottom();
                    } else {
                        // Fallback submit
                        chatForm.submit();
                    }
                })
                .catch(err => {
                    // Fallback to standard form submission
                    chatForm.submit();
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtn.classList.remove('opacity-70');
                });
            });
        }
    });
</script>

@include('layouts.footer')
@endsection

