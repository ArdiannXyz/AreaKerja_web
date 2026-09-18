@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.eventform') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Event / <span class="text-slate-500 font-medium">Detail</span></p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Detail Event</h1>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Action Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <span class="text-xs font-semibold text-slate-500">Status Event:</span>
                    @if ($event->status == 'buka')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Buka (Aktif)
                        </span>
                    @elseif ($event->status == 'tutup')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Tutup
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            Draft
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                    <!-- Edit Button -->
                    <a href="{{ route('admin.edit.event', $event->id) }}"
                       class="h-10 inline-flex items-center justify-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-4 rounded-xl transition duration-150 shadow-xs">
                        <i class="ph ph-pencil-simple text-base"></i>
                        Edit Event
                    </a>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.event.destroy', $event->id) }}" method="post"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')" class="inline-block">
                        @csrf
                        @method('delete')
                        <button type="submit"
                                class="h-10 inline-flex items-center justify-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-semibold px-4 rounded-xl transition duration-150 cursor-pointer">
                            <i class="ph ph-trash text-base"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8 space-y-6">

            <!-- Event Image -->
            <div class="w-full max-h-[420px] rounded-2xl overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center">
                @if ($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                         class="w-full h-full object-cover max-h-[420px]">
                @else
                    <img src="{{ asset('images/no images.jpg') }}" alt="Event Image"
                         class="w-full h-full object-cover max-h-[420px]">
                @endif
            </div>

            <!-- Title & Date -->
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-1.5">
                    <i class="ph ph-calendar text-sm text-[#00509d]"></i>
                    {{ \Carbon\Carbon::parse($event->tgl_mulai)->translatedFormat('d F Y') }}
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                    {{ $event->title }}
                </h2>
            </div>

            <!-- Key Info Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50/70 border border-slate-100">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-clock text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Waktu Pelaksanaan</p>
                        <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $event->jam_mulai ?? '-' }} - {{ $event->jam_akhir ?? '-' }} WIB</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-map-pin text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Lokasi</p>
                        <p class="text-xs font-bold text-slate-700 mt-0.5 break-words">{{ $event->lokasi ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-users text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kuota Partisipasi</p>
                        <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $event->kuota ? $event->kuota . ' Peserta' : 'Tidak Dibatasi' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-link text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Formulir</p>
                        @if ($event->link_form)
                            <a href="{{ $event->link_form }}" target="_blank"
                               class="text-xs font-bold text-[#00509d] hover:underline mt-0.5 block truncate max-w-[160px]">
                                Buka Link <i class="ph ph-arrow-square-out text-xs"></i>
                            </a>
                        @else
                            <p class="text-xs font-bold text-slate-500 mt-0.5">Belum ditentukan</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3">Deskripsi Event</h3>
                <div class="tinymce-content text-sm text-slate-600 leading-relaxed space-y-2">
                    {!! $event->content !!}
                </div>
            </div>

            <!-- Rundown Table -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="ph ph-list-numbers text-base text-[#00509d]"></i>
                    Rundown Acara
                </h3>

                <div class="rounded-2xl border border-slate-200/80 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Waktu</th>
                                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Agenda Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @if(isset($event->kegiatan))
                                @forelse ($event->kegiatan as $k)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-5 py-3 text-xs font-semibold text-slate-600 whitespace-nowrap">
                                            {{ $k->waktu }}
                                        </td>
                                        <td class="px-5 py-3 text-xs text-slate-800">
                                            {{ $k->kegiatan }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-5 py-6 text-center text-xs text-slate-400">
                                            Belum ada jadwal kegiatan yang ditambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="2" class="px-5 py-6 text-center text-xs text-slate-400">
                                        Belum ada jadwal kegiatan yang ditambahkan.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>
@endsection
