{{--
    Komponen: Tombol Notifikasi Konsisten Super Admin Topbar
    Usage: @include('super_admin.components.notif_button')
--}}
<button @click="openNotif = true" 
    type="button" 
    title="Notifikasi"
    class="relative p-2 text-gray-500 hover:text-[#00509d] hover:bg-white rounded-2xl transition-all duration-200 shadow-sm border border-gray-200 bg-white focus:outline-none flex items-center justify-center w-10 h-10 shrink-0">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>

    @if (isset($global_notifikasi_unread) && $global_notifikasi_unread > 0)
        <span id="notif-badge" 
            class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-white min-w-[18px] text-center leading-tight">
            {{ $global_notifikasi_unread }}
        </span>
    @endif
</button>
