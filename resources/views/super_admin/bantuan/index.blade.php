@extends('super_admin.sidebar.index')

@section('sidebarsuperadmin')
<main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen">

    <!-- HEADER TOP BAR -->
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-6 bg-[#00509d] text-white p-4 sm:p-5 rounded-2xl border border-blue-800/30 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-2xl">
                <i class="ph ph-chats-circle"></i>
            </div>
            <div>
                <h1 class="text-base sm:text-xl font-bold text-white tracking-tight flex items-center gap-2">
                    Layanan Bantuan & Keluhan Pengguna
                </h1>
                <p class="text-xs text-blue-100 mt-0.5">Monitoring komprehensif pusat kendala dan pengaduan pengguna AreaKerja</p>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            @include('super_admin.components.notif_button')
            @include('super_admin.components.user_badge_dropdown')
        </div>
    </header>

    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2.5">
            <i class="ph ph-check-circle text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- STATS CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Total Tiket Masuk</p>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-1">{{ $counts['semua'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center text-xl">
                    <i class="ph ph-chat-centered-text"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Menunggu Respon</p>
                    <p class="text-xl sm:text-2xl font-bold text-amber-600 mt-1">{{ $counts['menunggu'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    <i class="ph ph-hourglass-medium"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Sudah Dibalas</p>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 mt-1">{{ $counts['dibalas'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="ph ph-paper-plane-tilt"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Selesai</p>
                    <p class="text-xl sm:text-2xl font-bold text-emerald-600 mt-1">{{ $counts['selesai'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="ph ph-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
        <form method="GET" action="{{ route('superadmin.bantuan.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Filter Status Pills -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <a href="{{ route('superadmin.bantuan.index', array_merge(request()->except('status', 'page'), ['status' => ''])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($status) ? 'bg-[#00509d] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $counts['semua'] }})
                </a>
                <a href="{{ route('superadmin.bantuan.index', array_merge(request()->except('status', 'page'), ['status' => 'menunggu'])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'menunggu' ? 'bg-amber-500 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Menunggu ({{ $counts['menunggu'] }})
                </a>
                <a href="{{ route('superadmin.bantuan.index', array_merge(request()->except('status', 'page'), ['status' => 'dibalas'])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'dibalas' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Dibalas ({{ $counts['dibalas'] }})
                </a>
                <a href="{{ route('superadmin.bantuan.index', array_merge(request()->except('status', 'page'), ['status' => 'selesai'])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Selesai ({{ $counts['selesai'] }})
                </a>
            </div>

            <!-- Search Field -->
            <div class="flex items-center gap-2 w-full md:w-80">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, isi pesan..."
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d]">
                    <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                </div>
                <button type="submit" class="px-3 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold rounded-xl transition">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- LIST OF SUPPORT TICKETS -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($chats as $c)
                @php
                    $user = $c->user;
                    $roleBadge = $user?->role === 'perusahaan' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-[#00509d]';
                    $roleLabel = $user?->role === 'perusahaan' ? 'Perusahaan' : ($user?->role === 'pelamar' ? 'Pelamar' : ucfirst($user?->role ?? 'User'));
                    $userName = $user?->nama_lengkap ?: ($user?->username ?: 'Pengguna #'.$c->user_id);
                    $unread = $c->unread_messages_for_admin_count;
                @endphp
                <div class="p-4 sm:p-5 hover:bg-slate-50/80 transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ $unread > 0 ? 'bg-blue-50/30' : '' }}">
                    <div class="flex items-start gap-3.5 flex-1 min-w-0">
                        <!-- Avatar -->
                        <div class="relative shrink-0 mt-0.5">
                            @if($user?->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $userName }}" class="w-11 h-11 rounded-full object-cover border border-slate-200">
                            @else
                                <div class="w-11 h-11 rounded-full bg-[#00509d] text-white font-bold text-sm flex items-center justify-center shadow-xs">
                                    {{ strtoupper(substr($userName, 0, 2)) }}
                                </div>
                            @endif
                            @if($unread > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center border-2 border-white">
                                    {{ $unread }}
                                </span>
                            @endif
                        </div>

                        <!-- Chat Info -->
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h3 class="text-sm font-bold text-slate-800 hover:text-[#00509d] transition truncate">
                                    {{ $userName }}
                                </h3>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $roleBadge }}">
                                    {{ $roleLabel }}
                                </span>
                                <span class="text-xs text-slate-400">&bull; {{ $user?->email }}</span>
                            </div>

                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                @if($c->latestMessage)
                                    <span class="font-semibold text-slate-700">{{ $c->latestMessage->sender_type === 'admin' ? 'Admin: ' : '' }}</span>{{ $c->latestMessage->pesan }}
                                @else
                                    <span class="italic text-slate-400">Belum ada pesan dari pengguna</span>
                                @endif
                            </p>

                            <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-400">
                                <span><i class="ph ph-clock mr-1"></i>{{ $c->last_message_at ? $c->last_message_at->diffForHumans() : $c->updated_at->diffForHumans() }}</span>
                                <span>&bull;</span>
                                <span>Tiket ID: #AK-{{ str_pad($c->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Status & Action -->
                    <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-2 shrink-0">
                        <div>
                            @if($c->status === 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <i class="ph ph-check-circle"></i> Selesai
                                </span>
                            @elseif($c->status === 'dibalas')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-[#00509d]">
                                    <i class="ph ph-paper-plane-tilt"></i> Dibalas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Respon
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('superadmin.bantuan.show', $c->id) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold rounded-xl transition shadow-xs">
                            <span>Buka Chat</span>
                            <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-3xl mx-auto mb-3">
                        <i class="ph ph-chats-circle"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Belum Ada Percakapan Bantuan</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Pesan dan keluhan yang dikirim pengguna melalui FAQ akan otomatis muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

        @if($chats->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $chats->links() }}
            </div>
        @endif
    </div>

</main>
@endsection

