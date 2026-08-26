<!-- Main modal -->
<div id="show-skill" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs overflow-y-auto p-4">

    <div class="relative w-full max-w-md">
        <!-- Modal content -->
        <div class="relative bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-900">
                    Skill & Keahlian
                </h3>
                <button type="button"
                    class="text-slate-400 hover:bg-slate-100 hover:text-slate-700 rounded-full w-8 h-8 flex items-center justify-center transition"
                    data-modal-hide="show-skill">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 bg-white space-y-4">
                @if (Auth::user()->role === 'pelamar')
                    @foreach (Auth::user()->pelamar->skill as $skill)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm md:text-base leading-snug">
                                    {{ $skill->skill }}
                                </h4>
                                <p class="text-xs font-semibold text-slate-900 mt-0.5">
                                    Tingkat: {{ $skill->experience_level }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('skill.edit', $skill->id) }}" class="p-2 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition" title="Edit">
                                    <svg width="14" height="14" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.83752 2.24357C10.0542 2.02693 10.0542 1.66587 9.83752 1.46034L8.5377 0.160524C8.33218 -0.0561123 7.97112 -0.0561123 7.75448 0.160524L6.7324 1.17705L8.81544 3.26009M0 7.915V9.99805H2.08304L8.22664 3.8489L6.14359 1.76586L0 7.915Z" fill="#FA6601" />
                                    </svg>
                                </a>

                                <form action="{{ route('skill.destroy', $skill->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus Skill ini?')">
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

                <!-- Button Tambah -->
                <div class="flex justify-end pt-2">
                    <button data-modal-target="create_skillmodal" data-modal-toggle="create_skillmodal"
                        data-modal-hide="show-skill"
                        class="flex items-center justify-center w-10 h-10 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-sm transition"
                        type="button">
                        <span class="text-xl font-bold">+</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
