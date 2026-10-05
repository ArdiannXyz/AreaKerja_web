@extends('admin.sidebar.index')
@section('sidebaradmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen"
        x-data="{ openModal: false, detail: {}, openNotif: false, openAllNotif: false }" x-cloak>

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.finance') }}"
                   class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-base"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Finance / <span class="text-slate-600 font-semibold">Catatan Transaksi</span></p>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-tight">Riwayat Koin</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('admin.components.notif_button')
                @include('admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
            <div class="p-5 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph ph-coins text-[#00509d] text-base"></i> Riwayat Koin
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-4 text-center">No</th>
                            <th class="px-5 py-4">No. Referensi</th>
                            <th class="px-5 py-4">User</th>
                            <th class="px-5 py-4">Email</th>
                            <th class="px-5 py-4">Bank</th>
                            <th class="px-5 py-4">Rekening</th>
                            <th class="px-5 py-4">Harga</th>
                            <th class="px-5 py-4">Jumlah Koin</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach ($transaksi as $item)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-500">{{ $loop->iteration }}</td>

                                <td class="px-5 py-3.5 max-w-[150px] truncate text-xs font-mono text-slate-600">
                                    {{ $item->no_referensi }}
                                </td>

                                <td class="px-5 py-3.5 font-semibold text-slate-800">
                                    {{ $item->user->username ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-slate-500 text-xs max-w-[200px] truncate">
                                    {{ $item->user->email ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-slate-600">{{ $item->bank->nama_bank ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-600 font-mono text-xs">{{ $item->bank->no_rek ?? '-' }}</td>

                                <td class="px-5 py-3.5 font-semibold text-slate-800">
                                    Rp {{ number_format($item->hargaPembayaran->harga ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="ph ph-coin text-xs"></i>
                                        {{ $item->hargaPembayaran->jumlah_koin ?? 0 }} Koin
                                    </span>
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    @php $st = $item->status; @endphp
                                    @if($st == 'diterima')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="ph ph-check-circle text-xs"></i> Diterima
                                        </span>
                                    @elseif($st == 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <i class="ph ph-x-circle text-xs"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            <i class="ph ph-clock text-xs"></i> Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 text-center">
                                    <button
                                        @click="
                                            detail = {
                                                id: '{{ $item->id }}',
                                                user: '{{ addslashes($item->user->username ?? '-') }}',
                                                email: '{{ addslashes($item->user->email ?? '-') }}',
                                                sumber_dana: '{{ addslashes($item->sumberDana ?? '-') }}',
                                                bank: '{{ addslashes($item->bank->nama_bank ?? '-') }}',
                                                rekening: '{{ addslashes($item->bank->no_rekening ?? '-') }}',
                                                harga: '{{ number_format($item->hargaPembayaran->harga ?? 0, 0, ',', '.') }}',
                                                koin: '{{ $item->hargaPembayaran->jumlah_koin ?? 0 }}',
                                                status: '{{ ucfirst($item->status) }}',
                                                tanggal: '{{ $item->created_at->format('d M Y H:i') }}'
                                            };
                                            openModal = true;
                                        "
                                        class="inline-flex items-center justify-center w-8 h-8 bg-[#00509d] hover:bg-[#003d7a] text-white rounded-lg transition"
                                        title="Lihat Detail">
                                        <i class="ph ph-eye text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Detail -->
        <div x-show="openModal"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative max-h-[90vh] overflow-y-auto"
                @click.outside="openModal = false">

                <!-- Close Button -->
                <button @click="openModal = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                    <i class="ph ph-x text-sm"></i>
                </button>

                <!-- Title -->
                <h2 class="text-base font-bold text-slate-900 text-center mb-5"
                    x-text="detail.status == 'Diterima' ? 'Top Up Berhasil' : (detail.status == 'Ditolak' ? 'Top Up Ditolak' : 'Menunggu Verifikasi')">
                </h2>

                <!-- Status Icon -->
                <div class="flex justify-center mb-5">
                    <template x-if="detail.status == 'Diterima'">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="ph ph-check-circle text-3xl text-emerald-600"></i>
                        </div>
                    </template>
                    <template x-if="detail.status == 'Ditolak'">
                        <div class="w-16 h-16 rounded-full bg-rose-100 flex items-center justify-center">
                            <i class="ph ph-x-circle text-3xl text-rose-600"></i>
                        </div>
                    </template>
                    <template x-if="detail.status != 'Diterima' && detail.status != 'Ditolak'">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                            <i class="ph ph-clock text-3xl text-slate-500"></i>
                        </div>
                    </template>
                </div>

                <!-- Detail Rows -->
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">No. Transaksi</span>
                        <span class="font-mono text-slate-800 font-bold text-xs" x-text="detail.id"></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">Status</span>
                        <span :class="detail.status == 'Diterima' ? 'bg-emerald-100 text-emerald-700' : (detail.status == 'Ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600')"
                            class="text-xs font-bold px-2.5 py-1 rounded-full" x-text="detail.status"></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">Nama Pengirim</span>
                        <span class="text-slate-800 font-semibold text-xs" x-text="detail.user"></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">Nama Penerima</span>
                        <span class="text-slate-800 font-semibold text-xs">Area Kerja</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">Metode Pembayaran</span>
                        <span class="text-slate-800 font-semibold text-xs" x-text="detail.sumber_dana"></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">Tgl / Waktu</span>
                        <span class="text-slate-800 font-semibold text-xs" x-text="detail.tanggal"></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">Nominal</span>
                        <span class="text-slate-800 font-semibold text-xs">Rp <span x-text="detail.harga"></span></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="font-semibold text-slate-600 text-xs uppercase tracking-wider">Biaya Admin</span>
                        <span class="text-slate-800 font-semibold text-xs">Rp 2.500</span>
                    </div>
                    <div class="flex justify-between items-center py-3 bg-slate-50 rounded-xl px-3">
                        <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Total Pembayaran</span>
                        <span class="font-bold text-[#00509d] text-sm">Rp <span x-text="parseInt(detail.harga.replaceAll('.', '')) + 2500"></span></span>
                    </div>
                </div>

                <!-- Logo -->
                <div class="flex justify-center mt-6">
                    <img src="{{ asset('images/logoarea.png') }}" alt="Logo" class="h-8 object-contain opacity-60">
                </div>

                <div class="mt-5 flex justify-end">
                    <button @click="openModal = false" class="px-5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </main>
@endsection
