<!-- Main modal -->
<div id="create_pendidikanmodal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs overflow-y-auto p-4">

    <div class="relative w-full max-w-lg">

        <!-- Modal content -->
        <div class="relative bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-900">Tambah Riwayat Pendidikan</h3>
                <button type="button"
                    class="text-slate-400 hover:bg-slate-100 hover:text-slate-700 rounded-full w-8 h-8 flex justify-center items-center transition"
                    data-modal-hide="create_pendidikanmodal">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6 bg-white">
                <form action="{{ route('pendidikan.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Pendidikan / Jenjang <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="pendidikan" required
                                class="w-full appearance-none border border-slate-300 rounded-xl px-3.5 py-2.5 pr-10 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition cursor-pointer">
                                <option value="" disabled selected>Pilih Jenjang Pendidikan</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA/SMK">SMA / SMK</option>
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="D4">D4</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                        <input type="text" name="jurusan" placeholder="Contoh: Teknik Informatika"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Asal Sekolah / Universitas</label>
                        <input name="asal_pendidikan" type="text" placeholder="Contoh: Universitas Brawijaya"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Awal <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_awal" placeholder="2018"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                                required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Akhir</label>
                            <input type="number" name="tahun_akhir" placeholder="2022"
                                class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition">
                        </div>
                    </div>

                        <button type="submit"
                            class="bg-[#00509d] hover:bg-[#003d7a] text-white font-extrabold px-6 py-2.5 rounded-xl shadow-sm transition text-sm">
                            Simpan
                        </button>

                </form>
            </div>

        </div>
    </div>
</div>
