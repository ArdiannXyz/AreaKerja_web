@extends('finance.sidebar.index')
@section('sidebar')
    <div class="sm:ml-64 p-4 sm:p-6 lg:p-8 space-y-6 min-h-screen bg-slate-50/70"
         x-data="{
             openTambah: false,
             openEdit: false,
             bankEdit: { id: '', nama_bank: '', owner: '', no_rek: '', logo_image: '' },
             setEdit(bank) {
                 this.bankEdit = { ...bank };
                 this.openEdit = true;
             }
         }"
         x-cloak>

        <!-- TOP NAVBAR & HEADER -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 bg-white p-3.5 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-full sm:w-auto flex items-center justify-between">
                <div>
                    <h1 class="text-base sm:text-xl font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="ph ph-bank text-[#00509d] text-lg sm:text-2xl"></i> Rekening &amp; Metode Pembayaran
                    </h1>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Kelola rekening bank dan QRIS tujuan transfer untuk top up koin dan pendaftaran.</p>
                </div>
                <div class="sm:hidden flex items-center gap-2">
                    @include('finance.components.notif_button')
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('finance.components.notif_button')
                @include('finance.components.user_badge_dropdown')
            </div>
        </header>


        <!-- Banner Info Card -->
        <div style="background: linear-gradient(135deg, #00509d 0%, #002d5a 100%);"
            class="bg-[#00509d] text-white p-5 sm:p-6 rounded-3xl shadow-lg relative overflow-hidden border border-[#00509d]/30">
            <div class="absolute -right-6 -bottom-10 opacity-15 pointer-events-none text-white">
                <i class="ph-fill ph-bank text-9xl"></i>
            </div>
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 rounded-xl text-xs font-bold text-blue-50 mb-2.5 backdrop-blur-md">
                    <i class="ph-fill ph-sparkle text-amber-300 text-sm"></i>
                    <span>Tujuan Transfer &amp; Kanal Pembayaran</span>
                </div>
                <h2 class="text-lg sm:text-xl font-black text-white tracking-tight">Pengaturan Rekening Bank &amp; QRIS</h2>
                <p class="text-xs sm:text-sm text-blue-100/90 mt-1 font-normal leading-relaxed">
                    Daftar rekening bank dan barcode QRIS di bawah ini adalah rekening resmi yang ditampilkan kepada perusahaan saat top up koin dan pelamar saat mendaftar kandidat.
                </p>
            </div>
        </div>

        <!-- SECTION BAR: DAFTAR REKENING & TOMBOL TAMBAH -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
            <div>
                <h2 class="text-base sm:text-lg font-extrabold text-slate-800 flex items-center gap-2">
                    <span>Metode Pembayaran Tersedia</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-[#00509d]">
                        {{ $banks->count() }} Metode
                    </span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Daftar rekening bank dan QRIS aktif yang digunakan oleh pengguna</p>
            </div>
            <div>
                <button @click="openTambah = true"
                        class="inline-flex items-center gap-2 bg-[#00509d] hover:bg-[#003f7a] text-white text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-xl shadow-xs transition active:scale-95">
                    <i class="ph ph-plus-circle text-base"></i>
                    <span>Tambah Rekening</span>
                </button>
            </div>
        </div>

        <!-- DAFTAR REKENING BANK (GRID) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($banks as $b)
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                @if($b->logo_image)
                                    <img src="{{ asset('storage/' . $b->logo_image) }}"
                                         alt="{{ $b->nama_bank }}"
                                         class="h-8 w-auto max-w-[80px] object-contain rounded"
                                         onerror="this.onerror=null; this.src='{{ asset($b->logo_image) }}';">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($b->nama_bank, 0, 3)) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-slate-800 text-base leading-tight">{{ $b->nama_bank }}</h3>
                                    <span class="text-[11px] text-slate-400 font-medium">Tujuan Transfer</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">Aktif</span>
                        </div>

                        <div class="mt-4 space-y-2">
                            <div>
                                <p class="text-[11px] text-slate-400 font-medium">Nomor Rekening / ID</p>
                                <p class="text-base font-bold text-slate-900 tracking-wider font-mono">{{ $b->no_rek }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-400 font-medium">Atas Nama</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $b->owner }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button @click="setEdit({{ json_encode($b) }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            <i class="ph ph-pencil-simple text-sm"></i> Edit
                        </button>
                        <form id="form-delete-bank-{{ $b->id }}" action="{{ route('finance.bank.destroy', $b->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="confirmDeleteBank('{{ $b->id }}', '{{ addslashes($b->nama_bank) }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition cursor-pointer">
                                <i class="ph ph-trash text-sm"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-dashed border-slate-200">
                    <i class="ph ph-bank text-4xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-600">Belum ada rekening atau metode pembayaran terdaftar.</p>
                    <p class="text-xs text-slate-400 mt-1">Tambahkan nomor rekening agar pengguna dapat melakukan transfer pembayaran.</p>
                </div>
            @endforelse
        </div>

        <!-- ================= MODAL TAMBAH BANK ================= -->
        <div x-show="openTambah"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
             style="display: none;">
            <div @click.away="openTambah = false"
                 class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl relative space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Tambah Rekening Bank / QRIS</h3>
                    <button @click="openTambah = false" class="text-slate-400 hover:text-slate-600">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('finance.bank.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Bank / Metode <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_bank" placeholder="Contoh: BCA, Mandiri, BNI, QRIS" required
                               class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00509d]/30 focus:border-[#00509d]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Rekening / NMID <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_rek" placeholder="Contoh: 1234567890" required
                               class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00509d]/30 focus:border-[#00509d]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Atas Nama (Owner) <span class="text-rose-500">*</span></label>
                        <input type="text" name="owner" placeholder="Contoh: PT Area Kerja Global" required
                               class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00509d]/30 focus:border-[#00509d]">
                    </div>

                    <!-- Logo / QRIS Image Upload (Styled) -->
                    <div x-data="{ fileName: '' }">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Logo Bank / Barcode QRIS (Opsional)</label>
                        <label class="flex items-center justify-between px-3.5 py-2.5 bg-slate-50 border border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-blue-50/50 hover:border-[#00509d] transition group">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-[#00509d] flex items-center justify-center shrink-0">
                                    <i class="ph ph-image text-lg"></i>
                                </div>
                                <span class="text-xs text-slate-600 truncate font-medium" x-text="fileName || 'Pilih gambar (JPG, PNG, WEBP)'"></span>
                            </div>
                            <span class="text-[11px] font-bold text-[#00509d] bg-white px-2.5 py-1 rounded-lg border border-slate-200 group-hover:border-[#00509d] shrink-0">
                                Jelajahi
                            </span>
                            <input type="file" name="logo_image" accept="image/*" class="hidden"
                                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        </label>
                        <p class="text-[10px] text-slate-400 mt-1">Ukuran file maksimal 2MB. Format PNG latar transparan disarankan.</p>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="openTambah = false"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 text-xs font-semibold text-white bg-[#00509d] hover:bg-[#003f7a] rounded-xl transition">
                            Simpan Rekening
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL EDIT BANK ================= -->
        <div x-show="openEdit"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
             style="display: none;">
            <div @click.away="openEdit = false"
                 class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl relative space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Edit Rekening Bank / QRIS</h3>
                    <button @click="openEdit = false" class="text-slate-400 hover:text-slate-600">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form :action="`{{ url('finance/bank') }}/${bankEdit.id}`" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Bank / Metode <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_bank" x-model="bankEdit.nama_bank" required
                               class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00509d]/30 focus:border-[#00509d]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Rekening / NMID <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_rek" x-model="bankEdit.no_rek" required
                               class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00509d]/30 focus:border-[#00509d]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Atas Nama (Owner) <span class="text-rose-500">*</span></label>
                        <input type="text" name="owner" x-model="bankEdit.owner" required
                               class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00509d]/30 focus:border-[#00509d]">
                    </div>

                    <!-- Logo / QRIS Image Upload Edit (Styled) -->
                    <div x-data="{ fileName: '' }">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ganti Logo / Barcode QRIS (Opsional)</label>
                        <label class="flex items-center justify-between px-3.5 py-2.5 bg-slate-50 border border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-blue-50/50 hover:border-[#00509d] transition group">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-[#00509d] flex items-center justify-center shrink-0">
                                    <i class="ph ph-image text-lg"></i>
                                </div>
                                <span class="text-xs text-slate-600 truncate font-medium" x-text="fileName || 'Klik untuk mengganti gambar logo/QRIS'"></span>
                            </div>
                            <span class="text-[11px] font-bold text-[#00509d] bg-white px-2.5 py-1 rounded-lg border border-slate-200 group-hover:border-[#00509d] shrink-0">
                                Jelajahi
                            </span>
                            <input type="file" name="logo_image" accept="image/*" class="hidden"
                                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        </label>
                        <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah logo yang sudah ada.</p>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="openEdit = false"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 text-xs font-semibold text-white bg-[#00509d] hover:bg-[#003f7a] rounded-xl transition">
                            Perbarui Rekening
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    function confirmDeleteBank(id, name) {
        Swal.fire({
            title: 'Hapus Rekening?',
            html: `Apakah Anda yakin ingin menghapus rekening <strong class="text-rose-600 font-semibold">${name}</strong>?<br><span class="text-[11px] sm:text-xs text-slate-400 mt-1 block">Tindakan ini tidak dapat dibatalkan.</span>`,
            icon: 'warning',
            width: 'min(90vw, 360px)',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="ph ph-trash"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: '!rounded-2xl !p-6 shadow-2xl border border-slate-100',
                title: '!text-base font-bold text-slate-800 !pt-0',
                htmlContainer: '!text-xs text-slate-600 !mt-1.5 leading-relaxed',
                actions: '!gap-2 !mt-4 w-full !justify-center',
                confirmButton: '!px-4 !py-2 !rounded-xl !text-xs !font-semibold shadow-md shadow-rose-200 transition',
                cancelButton: '!px-4 !py-2 !rounded-xl !text-xs !font-semibold transition'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus Rekening...',
                    text: 'Mohon tunggu sebentar',
                    width: 'min(85vw, 300px)',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        popup: '!rounded-2xl !p-5 shadow-xl border border-slate-100',
                        title: '!text-sm font-bold text-slate-800',
                        htmlContainer: '!text-xs text-slate-500'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('form-delete-bank-' + id).submit();
            }
        });
    }


</script>
@endpush
