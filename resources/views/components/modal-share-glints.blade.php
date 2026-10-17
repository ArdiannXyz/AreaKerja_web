{{-- Modal Berbagi Pekerjaan Persis Glints (Popup Link Saja) --}}
<div x-data="{
        open: false,
        shareUrl: '',
        shareText: '',
        copied: false,
        openModal(url, text) {
            this.shareUrl = url || window.location.href;
            this.shareText = text || 'Lowongan Kerja di AreaKerja';
            this.open = true;
            this.copied = false;
        },
        closeModal() {
            this.open = false;
        },
        copyLink() {
            if (!this.shareUrl) return;
            navigator.clipboard.writeText(this.shareUrl).then(() => {
                this.copied = true;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Tautan berhasil disalin!',
                        showConfirmButton: false,
                        timer: 1800
                    });
                }
                setTimeout(() => { this.copied = false; }, 2000);
            }).catch(() => {
                // Fallback copy
                const el = document.createElement('textarea');
                el.value = this.shareUrl;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            });
        }
    }"
    x-init="
        window.openShareModal = (url, text) => openModal(url, text);
    "
    @open-share-modal.window="openModal($event.detail.url, $event.detail.text)"
    @keydown.escape.window="closeModal()"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 flex items-center justify-center bg-black/60 z-[9999] p-4 backdrop-blur-xs"
    x-cloak>

    <div @click.away="closeModal()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl p-6 sm:p-7 max-w-md w-full shadow-2xl relative">

        {{-- Header Modal --}}
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
            <h3 class="text-base sm:text-lg font-bold text-slate-900">Berbagi Pekerjaan</h3>
            <button type="button" @click="closeModal()"
                class="text-slate-400 hover:text-slate-700 transition p-1 rounded-lg hover:bg-slate-100">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
        </div>

        <div class="py-5 text-center">
            {{-- Subtitle --}}
            <p class="font-bold text-slate-800 text-sm sm:text-base mb-6">
                Bagikan pekerjaan ini dengan teman-temanmu!
            </p>

            {{-- 5 Lingkaran Ikon Media Sosial --}}
            <div class="flex items-center justify-center gap-3.5 mb-6">
                {{-- WhatsApp --}}
                <a :href="'https://api.whatsapp.com/send?text=' + encodeURIComponent(shareText + '\n' + shareUrl)"
                    target="_blank" rel="noopener noreferrer"
                    class="w-12 h-12 rounded-full bg-[#25D366] hover:brightness-105 text-white flex items-center justify-center text-2xl transition shadow-xs hover:scale-105 active:scale-95 shrink-0"
                    title="WhatsApp">
                    <i class="ph-bold ph-whatsapp-logo"></i>
                </a>

                {{-- LinkedIn --}}
                <a :href="'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(shareUrl)"
                    target="_blank" rel="noopener noreferrer"
                    class="w-12 h-12 rounded-full bg-[#0077b5] hover:brightness-105 text-white flex items-center justify-center text-2xl transition shadow-xs hover:scale-105 active:scale-95 shrink-0"
                    title="LinkedIn">
                    <i class="ph-bold ph-linkedin-logo"></i>
                </a>

                {{-- Telegram --}}
                <a :href="'https://t.me/share/url?url=' + encodeURIComponent(shareUrl) + '&text=' + encodeURIComponent(shareText)"
                    target="_blank" rel="noopener noreferrer"
                    class="w-12 h-12 rounded-full bg-[#0088cc] hover:brightness-105 text-white flex items-center justify-center text-2xl transition shadow-xs hover:scale-105 active:scale-95 shrink-0"
                    title="Telegram">
                    <i class="ph-bold ph-telegram-logo"></i>
                </a>

                {{-- Facebook --}}
                <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl)"
                    target="_blank" rel="noopener noreferrer"
                    class="w-12 h-12 rounded-full bg-[#1877f2] hover:brightness-105 text-white flex items-center justify-center text-2xl transition shadow-xs hover:scale-105 active:scale-95 shrink-0"
                    title="Facebook">
                    <i class="ph-bold ph-facebook-logo"></i>
                </a>

                {{-- X / Twitter --}}
                <a :href="'https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareText) + '&url=' + encodeURIComponent(shareUrl)"
                    target="_blank" rel="noopener noreferrer"
                    class="w-12 h-12 rounded-full bg-black hover:bg-slate-800 text-white flex items-center justify-center text-2xl transition shadow-xs hover:scale-105 active:scale-95 shrink-0"
                    title="X (Twitter)">
                    <i class="ph-bold ph-x-logo"></i>
                </a>
            </div>

            {{-- Input Tautan & Tombol Salin Link --}}
            <div class="flex items-stretch border border-slate-300 rounded-xl overflow-hidden bg-slate-50/70 focus-within:border-[#1877f2] focus-within:ring-2 focus-within:ring-blue-100 transition">
                <input type="text" readonly :value="shareUrl" @focus="$event.target.select()"
                    class="flex-1 bg-transparent px-3.5 py-2.5 text-xs sm:text-sm text-slate-700 outline-none border-none focus:ring-0 truncate select-all">
                <button type="button"
                    @click="copyLink()"
                    class="bg-[#1877f2] hover:bg-[#166fe5] text-white px-4 flex items-center justify-center transition active:scale-95 cursor-pointer shrink-0"
                    title="Salin Tautan">
                    <i :class="copied ? 'ph-bold ph-check' : 'ph-bold ph-link'" class="text-xl"></i>
                </button>
            </div>
        </div>

    </div>
</div>

