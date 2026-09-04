<!-- Main modal -->
<div id="create_skillmodal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs overflow-y-auto p-4">

    <div class="relative w-full max-w-md">
        <!-- Modal content -->
        <div class="relative bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-900">Tambah Skill</h3>
                <button type="button"
                    class="text-slate-400 hover:bg-slate-100 hover:text-slate-700 rounded-full w-8 h-8 flex justify-center items-center transition"
                    data-modal-toggle="create_skillmodal">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6 bg-white">
                <form action="{{ route('skill.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Skill <span class="text-red-500">*</span></label>
                        <input type="text" name="skill" placeholder="Contoh: Laravel / Graphic Design"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kemahiran / Experience Level <span class="text-red-500">*</span></label>
                        <input type="text" name="experience_level" placeholder="Contoh: Pemula / Menengah / Mahir"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm bg-white text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-[#00509d] focus:outline-none transition"
                            required>
                    </div>

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
