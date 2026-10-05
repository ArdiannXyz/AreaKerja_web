<!-- Main modal -->
<div id="show-kerja" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs overflow-y-auto p-4">

    <div class="relative w-full max-w-md">
        <!-- Modal content -->
        <div class="relative bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-900">
                    Pengalaman Kerja
                </h3>

                <button type="button"
                    class="text-slate-400 hover:bg-slate-100 hover:text-slate-700 rounded-full w-8 h-8 flex justify-center items-center transition"
                    data-modal-hide="show-kerja">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6 bg-white space-y-4">
                @if (Auth::user()->role === 'pelamar')
                    @foreach (Auth::user()->pelamar->pengalaman_kerja as $kerja)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm md:text-base leading-snug">
                                    {{ $kerja->posisi_pekerjaan }}
                                </h4>
                                <p class="text-xs font-semibold text-slate-900 mt-0.5">{{ $kerja->nama_perusahaan }}</p>
                                <p class="text-xs text-slate-500 font-medium mt-1">
                                    ({{ $kerja->tahun_awal }} - {{ $kerja->tahun_akhir ?: 'Sekarang' }})
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('kerja.edit', $kerja->id) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-[#00509d] rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple font-bold text-base"></i>
                                </a>

                                <form action="{{ route('kerja.destroy', $kerja->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus Pengalaman Kerja ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                        Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                @endif

                <div class="flex justify-end pt-2">
                    <button data-modal-target="create_kerjamodal" data-modal-toggle="create_kerjamodal"
                        data-modal-hide="show-kerja" type="button"
                        class="flex items-center justify-center w-10 h-10 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl shadow-sm transition">
                        <span class="text-xl font-bold">+</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
