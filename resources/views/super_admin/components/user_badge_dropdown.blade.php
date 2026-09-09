{{--
    Komponen: User Badge Dropdown untuk Super Admin Topbar
    Usage: @include('super_admin.components.user_badge_dropdown')
--}}
<div class="relative" x-data="{ openUserMenu: false }">
    {{-- Trigger Badge --}}
    <button @click="openUserMenu = !openUserMenu"
        class="flex items-center gap-2 bg-white border border-gray-300 shadow-sm rounded-2xl px-3 py-1.5 transition hover:border-[#00509d] focus:outline-none"
        type="button">
        @if (Auth::user()->superadmin->img_profile)
            <img class="w-8 h-8 rounded-full object-cover flex-shrink-0"
                src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}" alt="Profile">
        @elseif (Auth::user()->avatar)
            <img class="w-8 h-8 rounded-full object-cover flex-shrink-0"
                src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
        @else
            <img class="w-8 h-8 rounded-full flex-shrink-0"
                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'SA') }}&background=00509d&color=fff&size=128"
                alt="Avatar">
        @endif
        <div class="text-left leading-tight">
            <p class="font-semibold text-gray-800 text-xs truncate max-w-[120px]">{{ Auth::user()->username }}</p>
            <p class="text-gray-400 text-[11px] truncate max-w-[120px]">{{ Auth::user()->email }}</p>
        </div>
        <svg class="w-4 h-4 text-gray-400 ml-1 transition-transform duration-200"
            :class="openUserMenu ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
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
        class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-1 overflow-hidden"
        x-cloak>

        {{-- User Info Header --}}
        <div class="px-4 py-3 bg-[#00509d] text-white">
            <p class="text-sm font-bold truncate">{{ Auth::user()->superadmin->nama_lengkap ?: Auth::user()->username }}</p>
            <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email }}</p>
        </div>

        {{-- Menu Items --}}
        <div class="py-1">
            <a href="{{ route('superadmin.profile') }}"
                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition group">
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil Saya
            </a>

            <a href="{{ route('superadmin.edit.profile') }}"
                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition group">
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Profil
            </a>

            <a href="/super_admin/pengaturan"
                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#00509d] transition group">
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#00509d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan
            </a>
        </div>

        <div class="border-t border-gray-100"></div>

        {{-- Logout --}}
        <button type="button" onclick="openModal()"
            class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </button>
    </div>
</div>
