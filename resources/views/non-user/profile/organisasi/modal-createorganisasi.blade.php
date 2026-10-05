<!-- Main modal -->
<div id="create_organisasimodal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 bg-slate-900/25 overflow-y-auto overflow-x-hidden top-0 right-0 left-0 z-50 flex justify-center items-center p-4">

    <div class="relative w-full max-w-lg">
        <!-- Modal content -->
        <div class="relative bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-900">Tambah Pengalaman Organisasi</h3>

                <button type="button"
                    class="text-slate-400 hover:bg-slate-100 hover:text-slate-700 rounded-full w-8 h-8 flex justify-center items-center transition"
                    data-modal-hide="create_organisasimodal">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6 bg-white">
                <form action="{{ route('organisasi.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Organisasi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_organisasi" placeholder="Contoh: Himpunan Mahasiswa / OSIS"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="jabatan" placeholder="Contoh: Ketua / Anggota Divisi Humas"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            required>
                    </div>

                    <!-- Grid tahun -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Awal <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_awal" placeholder="2020"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                                required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Akhir</label>
                            <input type="number" name="tahun_akhir" placeholder="2022 (Kosongkan jika aktif)"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Tuliskan tugas atau pencapaian Anda selama di organisasi ini..."
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"></textarea>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold px-6 py-2.5 rounded-xl shadow-sm transition text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
