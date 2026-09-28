@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')

@php
    function parseDurasiListing($days) {
        $days = (int) $days;
        if ($days <= 0) return ['nilai' => 30, 'satuan' => 'hari'];
        if ($days >= 365 && $days % 365 === 0) {
            return ['nilai' => $days / 365, 'satuan' => 'tahun'];
        }
        if ($days >= 30 && $days % 30 === 0) {
            return ['nilai' => $days / 30, 'satuan' => 'bulan'];
        }
        if ($days >= 7 && $days % 7 === 0) {
            return ['nilai' => $days / 7, 'satuan' => 'minggu'];
        }
        return ['nilai' => $days, 'satuan' => 'hari'];
    }

    $goldDurasi = parseDurasiListing($gold->batas_listing ?? 30);
    $silverDurasi = parseDurasiListing($silver->batas_listing ?? 20);
    $bronzeDurasi = parseDurasiListing($bronze->batas_listing ?? 10);
@endphp

    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70" 
          x-data="{ openNotif: false, openAllNotif: false, tab: '{{ $activeTab ?? 'gold' }}' }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm w-full">
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
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-1.5 mb-6 flex flex-wrap sm:flex-nowrap gap-1.5 w-full sm:max-w-md">
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
            <div class="mb-5 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium px-4 py-3 rounded-xl shadow-xs w-full">
                <i class="ph ph-check-circle text-base text-emerald-600"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Card: Gold -->
        <div x-cloak x-show="tab === 'gold'" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sm:p-7 w-full">
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
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Durasi Lowongan
                        </label>
                        <div x-data="{
                            nilai: {{ $goldDurasi['nilai'] }},
                            satuan: '{{ $goldDurasi['satuan'] }}',
                            get multiplier() {
                                if (this.satuan === 'minggu') return 7;
                                if (this.satuan === 'bulan') return 30;
                                if (this.satuan === 'tahun') return 365;
                                return 1;
                            },
                            get totalHari() {
                                return (parseInt(this.nilai) || 0) * this.multiplier;
                            }
                        }" class="w-full max-w-sm">
                            <input type="hidden" name="batas_listing" :value="totalHari">
                            <div class="flex items-stretch rounded-xl border border-slate-200 bg-white overflow-hidden shadow-xs focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                                <div class="pointer-events-none pl-3.5 flex items-center text-slate-400">
                                    <i class="ph ph-clock text-base"></i>
                                </div>
                                <input type="number" min="1" name="durasi_nilai" x-model="nilai"
                                       class="w-full pl-2.5 pr-2 py-2.5 text-sm font-semibold text-slate-800 bg-transparent border-0 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                       placeholder="1">
                                <div class="border-l border-slate-200 bg-slate-50 flex items-center">
                                    <select name="durasi_satuan" x-model="satuan"
                                            class="h-full pl-3 pr-8 py-2.5 bg-transparent border-0 text-slate-700 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer">
                                        <option value="hari">Hari</option>
                                        <option value="minggu">Minggu</option>
                                        <option value="bulan">Bulan</option>
                                        <option value="tahun">Tahun</option>
                                    </select>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1.5">
                                <i class="ph ph-info text-xs text-[#00509d]"></i>
                                <span>Masa aktif tayang lowongan: <strong class="text-slate-700 font-semibold" x-text="totalHari + ' hari'"></strong></span>
                            </p>
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

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-6 py-2.5 rounded-xl transition shadow-xs cursor-pointer w-full sm:w-auto">
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
             class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sm:p-7 w-full">
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
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Durasi Lowongan
                        </label>
                        <div x-data="{
                            nilai: {{ $silverDurasi['nilai'] }},
                            satuan: '{{ $silverDurasi['satuan'] }}',
                            get multiplier() {
                                if (this.satuan === 'minggu') return 7;
                                if (this.satuan === 'bulan') return 30;
                                if (this.satuan === 'tahun') return 365;
                                return 1;
                            },
                            get totalHari() {
                                return (parseInt(this.nilai) || 0) * this.multiplier;
                            }
                        }" class="w-full max-w-sm">
                            <input type="hidden" name="batas_listing" :value="totalHari">
                            <div class="flex items-stretch rounded-xl border border-slate-200 bg-white overflow-hidden shadow-xs focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                                <div class="pointer-events-none pl-3.5 flex items-center text-slate-400">
                                    <i class="ph ph-clock text-base"></i>
                                </div>
                                <input type="number" min="1" name="durasi_nilai" x-model="nilai"
                                       class="w-full pl-2.5 pr-2 py-2.5 text-sm font-semibold text-slate-800 bg-transparent border-0 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                       placeholder="1">
                                <div class="border-l border-slate-200 bg-slate-50 flex items-center">
                                    <select name="durasi_satuan" x-model="satuan"
                                            class="h-full pl-3 pr-8 py-2.5 bg-transparent border-0 text-slate-700 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer">
                                        <option value="hari">Hari</option>
                                        <option value="minggu">Minggu</option>
                                        <option value="bulan">Bulan</option>
                                        <option value="tahun">Tahun</option>
                                    </select>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1.5">
                                <i class="ph ph-info text-xs text-[#00509d]"></i>
                                <span>Masa aktif tayang lowongan: <strong class="text-slate-700 font-semibold" x-text="totalHari + ' hari'"></strong></span>
                            </p>
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

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-6 py-2.5 rounded-xl transition shadow-xs cursor-pointer w-full sm:w-auto">
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
             class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sm:p-7 w-full">
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
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Durasi Lowongan
                        </label>
                        <div x-data="{
                            nilai: {{ $bronzeDurasi['nilai'] }},
                            satuan: '{{ $bronzeDurasi['satuan'] }}',
                            get multiplier() {
                                if (this.satuan === 'minggu') return 7;
                                if (this.satuan === 'bulan') return 30;
                                if (this.satuan === 'tahun') return 365;
                                return 1;
                            },
                            get totalHari() {
                                return (parseInt(this.nilai) || 0) * this.multiplier;
                            }
                        }" class="w-full max-w-sm">
                            <input type="hidden" name="batas_listing" :value="totalHari">
                            <div class="flex items-stretch rounded-xl border border-slate-200 bg-white overflow-hidden shadow-xs focus-within:border-[#00509d] focus-within:ring-2 focus-within:ring-[#00509d]/20 transition">
                                <div class="pointer-events-none pl-3.5 flex items-center text-slate-400">
                                    <i class="ph ph-clock text-base"></i>
                                </div>
                                <input type="number" min="1" name="durasi_nilai" x-model="nilai"
                                       class="w-full pl-2.5 pr-2 py-2.5 text-sm font-semibold text-slate-800 bg-transparent border-0 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                       placeholder="1">
                                <div class="border-l border-slate-200 bg-slate-50 flex items-center">
                                    <select name="durasi_satuan" x-model="satuan"
                                            class="h-full pl-3 pr-8 py-2.5 bg-transparent border-0 text-slate-700 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer">
                                        <option value="hari">Hari</option>
                                        <option value="minggu">Minggu</option>
                                        <option value="bulan">Bulan</option>
                                        <option value="tahun">Tahun</option>
                                    </select>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1.5">
                                <i class="ph ph-info text-xs text-[#00509d]"></i>
                                <span>Masa aktif tayang lowongan: <strong class="text-slate-700 font-semibold" x-text="totalHari + ' hari'"></strong></span>
                            </p>
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

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white text-xs font-semibold px-6 py-2.5 rounded-xl transition shadow-xs cursor-pointer w-full sm:w-auto">
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
