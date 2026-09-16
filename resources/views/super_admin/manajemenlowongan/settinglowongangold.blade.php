@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')

    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" 
          x-data="{ openNotif: false, openAllNotif: false, tab: '{{ $activeTab ?? 'gold' }}' }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">Manajemen Lowongan</h1>
                <p class="text-xs text-slate-400 mt-0.5">Konfigurasi batas durasi dan benefit untuk setiap paket lowongan</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Tabs Nav (Smooth Client-Side Switcher) -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-1.5 mb-6 flex gap-1.5 max-w-xl">
            <button type="button" @click="tab = 'gold'"
               :class="tab === 'gold' ? 'bg-amber-400 text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 font-medium'"
               class="flex-1 flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-xs transition duration-150 cursor-pointer">
                <i class="ph ph-medal text-base"></i> Gold
            </button>
            <button type="button" @click="tab = 'silver'"
               :class="tab === 'silver' ? 'bg-slate-500 text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 font-medium'"
               class="flex-1 flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-xs transition duration-150 cursor-pointer">
                <i class="ph ph-medal text-base"></i> Silver
            </button>
            <button type="button" @click="tab = 'bronze'"
               :class="tab === 'bronze' ? 'bg-[#b45309] text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 font-medium'"
               class="flex-1 flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-xs transition duration-150 cursor-pointer">
                <i class="ph ph-medal text-base"></i> Bronze
            </button>
        </div>

        @if (session('success'))
            <div class="mb-5 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium px-4 py-3 rounded-xl shadow-xs">
                <i class="ph ph-check-circle text-base text-emerald-600"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Card: Gold -->
        <div x-cloak x-show="tab === 'gold'" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-3xl">
            <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="ph ph-medal text-amber-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Paket Lowongan Gold</h2>
                    <p class="text-xs text-slate-400">Atur masa aktif penayangan dan fasilitas paket gold</p>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.manajemen.lowongan.gold.update') }}">
                @csrf
                <div class="space-y-5">
                    <!-- Batas Listing -->
                    <div>
                        <label for="batas_listing_gold" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Durasi Lowongan
                        </label>
                        <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition max-w-xs bg-slate-50/50">
                            <input id="batas_listing_gold" name="batas_listing" type="number"
                                   value="{{ $gold->batas_listing ?? 30 }}"
                                   class="w-full px-3 py-2 text-sm focus:outline-none text-slate-800 bg-transparent">
                            <span class="px-3 py-2 bg-slate-100 text-slate-500 text-xs font-semibold border-l border-slate-200 whitespace-nowrap">
                                hari
                            </span>
                        </div>
                    </div>

                    <!-- Benefit -->
                    <div>
                        <label for="benefit_gold" class="block text-xs font-semibold text-slate-700 mb-1.5">Benefit Paket Gold</label>
                        <textarea id="benefit_gold" name="benefit"
                                  placeholder="Contoh: BPJS, work from home, fleksibel"
                                  class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] resize-none transition"
                                  style="min-height: 120px;">{{ $gold->benefit ?? '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-slate-100">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-xs cursor-pointer">
                        <i class="ph ph-floppy-disk text-base"></i>
                        Simpan Perubahan Gold
                    </button>
                </div>
            </form>
        </div>

        <!-- Form Card: Silver -->
        <div x-cloak x-show="tab === 'silver'" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-3xl">
            <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <i class="ph ph-medal text-slate-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Paket Lowongan Silver</h2>
                    <p class="text-xs text-slate-400">Atur masa aktif penayangan dan fasilitas paket silver</p>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.manajemen.lowongan.silver.update') }}">
                @csrf
                <div class="space-y-5">
                    <!-- Batas Listing -->
                    <div>
                        <label for="batas_listing_silver" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Durasi Lowongan
                        </label>
                        <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition max-w-xs bg-slate-50/50">
                            <input id="batas_listing_silver" name="batas_listing" type="number"
                                   value="{{ $silver->batas_listing ?? 20 }}"
                                   class="w-full px-3 py-2 text-sm focus:outline-none text-slate-800 bg-transparent">
                            <span class="px-3 py-2 bg-slate-100 text-slate-500 text-xs font-semibold border-l border-slate-200 whitespace-nowrap">
                                hari
                            </span>
                        </div>
                    </div>

                    <!-- Benefit -->
                    <div>
                        <label for="benefit_silver" class="block text-xs font-semibold text-slate-700 mb-1.5">Benefit Paket Silver</label>
                        <textarea id="benefit_silver" name="benefit"
                                  placeholder="Contoh: BPJS, fleksibel"
                                  class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] resize-none transition"
                                  style="min-height: 120px;">{{ $silver->benefit ?? '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-slate-100">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-xs cursor-pointer">
                        <i class="ph ph-floppy-disk text-base"></i>
                        Simpan Perubahan Silver
                    </button>
                </div>
            </form>
        </div>

        <!-- Form Card: Bronze -->
        <div x-cloak x-show="tab === 'bronze'" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-3xl">
            <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="ph ph-medal text-[#b45309] text-lg"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Paket Lowongan Bronze</h2>
                    <p class="text-xs text-slate-400">Atur masa aktif penayangan dan fasilitas paket bronze</p>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.manajemen.lowongan.bronze.update') }}">
                @csrf
                <div class="space-y-5">
                    <!-- Batas Listing -->
                    <div>
                        <label for="batas_listing_bronze" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Durasi Lowongan
                        </label>
                        <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition max-w-xs bg-slate-50/50">
                            <input id="batas_listing_bronze" name="batas_listing" type="number"
                                   value="{{ $bronze->batas_listing ?? 10 }}"
                                   class="w-full px-3 py-2 text-sm focus:outline-none text-slate-800 bg-transparent">
                            <span class="px-3 py-2 bg-slate-100 text-slate-500 text-xs font-semibold border-l border-slate-200 whitespace-nowrap">
                                hari
                            </span>
                        </div>
                    </div>

                    <!-- Benefit -->
                    <div>
                        <label for="benefit_bronze" class="block text-xs font-semibold text-slate-700 mb-1.5">Benefit Paket Bronze</label>
                        <textarea id="benefit_bronze" name="benefit"
                                  placeholder="Contoh: Lowongan standar"
                                  class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#00509d]/20 focus:border-[#00509d] resize-none transition"
                                  style="min-height: 120px;">{{ $bronze->benefit ?? '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-slate-100">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-xs cursor-pointer">
                        <i class="ph ph-floppy-disk text-base"></i>
                        Simpan Perubahan Bronze
                    </button>
                </div>
            </form>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>

@endsection
