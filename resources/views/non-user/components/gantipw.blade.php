<!-- Main modal Ganti Password -->
<div id="gantipwmodal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto overflow-x-hidden flex justify-center items-center z-[100] p-4">

    <div class="relative w-full max-w-md">
        <!-- Modal content -->
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#00509d] flex items-center justify-center font-bold">
                        <i class="ph ph-lock-key text-xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Ganti Kata Sandi</h3>
                </div>

                <button type="button"
                    class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg w-8 h-8 flex justify-center items-center transition"
                    data-modal-hide="gantipwmodal"
                    data-modal-toggle="gantipwmodal">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            <!-- Pesan sukses / error -->
            <div class="px-6 pt-4">
                @if (session('success'))
                    <div class="p-3 mb-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm flex items-center gap-2">
                        <i class="ph ph-check-circle text-lg flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-3 mb-2 bg-red-50 text-red-600 border border-red-200 rounded-xl text-sm flex items-center gap-2">
                        <i class="ph ph-warning-circle text-lg flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3 mb-2 bg-red-50 text-red-600 border border-red-200 rounded-xl text-sm">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Modal body -->
            <div class="p-6 pt-2">
                <form id="passwordForm" action="{{ route('pelamar.password.update') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Kata Sandi Lama</label>
                        <div class="relative">
                            <input type="password" name="old_password" required placeholder="Masukkan kata sandi lama"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="new_password" required minlength="3" placeholder="Minimal 3 karakter"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="new_password_confirmation" required placeholder="Ulangi kata sandi baru"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00509d] focus:border-transparent transition">
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button type="button"
                            class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition"
                            data-modal-hide="gantipwmodal"
                            data-modal-toggle="gantipwmodal">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 py-2.5 px-4 bg-[#00509d] hover:bg-[#003d7a] text-white font-bold rounded-xl text-sm shadow-sm transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
