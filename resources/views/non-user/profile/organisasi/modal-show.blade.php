<!-- Main modal -->
<div id="show-org" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 bg-slate-900/25 top-0 left-0 z-50 flex justify-center items-center p-4">

    <div class="relative w-full max-w-lg">
        <!-- Modal content -->
        <div class="bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-900">Pengalaman Organisasi</h3>
                <button type="button"
                    class="text-slate-400 hover:bg-slate-100 hover:text-slate-700 rounded-full w-8 h-8 flex justify-center items-center transition"
                    data-modal-hide="show-org">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6 bg-white max-h-[75vh] overflow-y-auto">
                @if (Auth::user()?->role === 'pelamar')
                    @forelse (Auth::user()->pelamar->pengalaman_organisasi as $org)
                        <div class="mb-4 pb-4 border-b border-slate-100 last:border-0 last:mb-0 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-base leading-snug">
                                        {{ $org->jabatan }}
                                    </h4>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $org->nama_organisasi }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $org->tahun_awal }} - {{ $org->tahun_akhir ?? 'Sekarang' }}
                                    </p>
                                    @if ($org->deskripsi)
                                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                                            {{ $org->deskripsi }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('organisasi.edit', $org->id) }}" class="p-1.5 text-[#00509d] hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <i class="ph ph-pencil-simple text-lg"></i>
                                    </a>
                                    <form action="{{ route('organisasi.destroy', $org->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus organisasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-sm text-slate-500 py-4">Belum ada pengalaman organisasi yang ditambahkan.</p>
                    @endforelse
                @endif

                <!-- Tombol Tambah -->
                <div class="flex justify-end pt-4 border-t border-slate-100 mt-4">
                    <button data-modal-target="create_organisasimodal" data-modal-toggle="create_organisasimodal"
                        data-modal-hide="show-org" type="button"
                        class="bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold px-5 py-2 rounded-xl text-sm shadow-sm transition flex items-center gap-2">
                        <i class="ph ph-plus font-bold"></i> Tambah Pengalaman Organisasi
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
