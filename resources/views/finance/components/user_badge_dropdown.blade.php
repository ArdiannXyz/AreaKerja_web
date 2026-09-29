{{--
    Komponen: User Badge Dropdown untuk Finance Topbar
    Usage: @include('finance.components.user_badge_dropdown')
--}}
<div class="relative hidden sm:block" x-data="{ openUserMenu: false }">
    {{-- Trigger Badge --}}
    <button @click="openUserMenu = !openUserMenu"
        class="flex items-center gap-2 bg-white border border-slate-200/80 shadow-2xs rounded-2xl px-3 py-1.5 transition hover:border-[#00509d] focus:outline-none"
        type="button">
        @if (Auth::user()?->avatar)
            <img class="w-8 h-8 rounded-full object-cover flex-shrink-0"
                src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
        @else
            <img class="w-8 h-8 rounded-full flex-shrink-0"
                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'FN') }}&background=00509d&color=fff&size=128"
                alt="Avatar">
        @endif
        <div class="text-left leading-tight">
            <p class="font-semibold text-slate-800 text-xs truncate max-w-[120px]">{{ Auth::user()->username }}</p>
            <p class="text-slate-400 text-[11px] truncate max-w-[120px]">{{ Auth::user()->email }}</p>
        </div>
        <i class="ph ph-caret-down text-slate-400 ml-1 transition-transform duration-200"
            :class="openUserMenu ? 'rotate-180' : ''"></i>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="openUserMenu"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        @click.outside="openUserMenu = false"
        class="absolute right-0 mt-2 w-56 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 py-1 overflow-hidden"
        x-cloak>

        {{-- User Info Header --}}
        <div class="px-4 py-3 bg-[#00509d] text-white">
            <p class="text-sm font-bold truncate">{{ Auth::user()->username }}</p>
            <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email }}</p>
            <span class="inline-block mt-1 px-2 py-0.5 bg-white/20 text-white text-[10px] font-bold rounded">Finance Panel</span>
        </div>

        {{-- Menu Items --}}
        <div class="py-1">
            <a href="{{ route('finance.profile') }}"
                class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-[#00509d] transition">
                <i class="ph ph-user-circle text-base text-slate-400"></i>
                Profil Saya
            </a>
            <a href="{{ route('finance.edit.profile') }}"
                class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-[#00509d] transition">
                <i class="ph ph-pencil-simple text-base text-slate-400"></i>
                Edit Profil
            </a>
        </div>

        <div class="border-t border-slate-100"></div>

        {{-- Logout --}}
        <button type="button" onclick="openLogoutModal()"
            class="flex w-full items-center gap-3 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
            <i class="ph ph-sign-out text-base"></i>
            Keluar
        </button>
    </div>
</div>
