@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-4 sm:p-6 sm:ml-64 bg-slate-50/70 min-h-screen" x-data="{ openNotif: false, openAllNotif: false }">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 flex-1">
                <a href="{{ route('superadmin.freeze') }}"
                   class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition flex-shrink-0">
                    <i class="ph ph-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400">Akun Freeze /
                        <span class="text-slate-500 font-medium">{{ $data->username }}</span>
                    </p>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight leading-tight">Detail Akun</h1>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                @include('super_admin.components.notif_button')
                @include('super_admin.components.user_badge_dropdown')
            </div>
        </header>

        <!-- Profile Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">

            <!-- Profile Header -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 p-6 border-b border-slate-100">

                <!-- Profile Photo -->
                <div class="flex-shrink-0">
                    @php
                        $profileImg = null;
                        if ($data->pelamar && $data->pelamar->img_profile) $profileImg = $data->pelamar->img_profile;
                        elseif ($data->perusahaan && $data->perusahaan->img_profile) $profileImg = $data->perusahaan->img_profile;
                        elseif ($data->finance && $data->finance->img_profile) $profileImg = $data->finance->img_profile;
                        elseif ($data->admin && $data->admin->img_profile) $profileImg = $data->admin->img_profile;
                        elseif ($data->superadmin && $data->superadmin->img_profile) $profileImg = $data->superadmin->img_profile;
                    @endphp
                    @if($profileImg)
                        <img id="pu" class="w-32 h-32 md:w-36 md:h-36 object-cover rounded-2xl border-2 border-slate-100"
                            src="{{ asset('storage/' . $profileImg) }}" alt="Profile">
                    @else
                        <div id="pu" class="w-32 h-32 md:w-36 md:h-36 rounded-2xl bg-gradient-to-br from-[#00509d] to-[#0077b6] flex items-center justify-center text-white font-bold text-4xl">
                            {{ strtoupper(substr($data->username, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Info & Actions -->
                <div class="flex-1 flex flex-col gap-4 w-full">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">{{ $data->username }}</h2>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $data->email }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold
                                {{ in_array($data->role, ['superadmin', 'super_admin']) ? 'bg-purple-100 text-purple-700' :
                                   ($data->role === 'finance' ? 'bg-amber-100 text-amber-700' :
                                   ($data->role === 'perusahaan' ? 'bg-emerald-100 text-emerald-700' :
                                   ($data->role === 'pelamar' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-700'))) }}">
                                <i class="ph ph-user-circle text-sm"></i>
                                {{ ucfirst($data->role) }}
                            </span>
                            @if ($data->status == 0)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <i class="ph ph-check-circle"></i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-100 text-rose-700">
                                    <i class="ph ph-prohibit"></i> Banned
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <!-- Hidden forms -->
                        <form id="hapus" action="{{ route('superadmin.delete.akun', $data->id) }}" method="post">
                            @csrf @method('DELETE')
                        </form>
                        <form id="unban" action="{{ route('superadmin.unban.freeze', $data->id) }}" method="post">
                            @csrf @method('PUT')
                            <input type="number" name="status" value="0" class="hidden">
                        </form>
                        <form id="ban" action="{{ route('superadmin.ban.freeze', $data->id) }}" method="post">
                            @csrf @method('PUT')
                            <input type="number" name="status" value="1" class="hidden">
                        </form>

                        @if ($data->status == 0)
                            <button type="submit" form="ban"
                                onclick="return confirm('Yakin ingin mem-ban akun ini?')"
                                class="inline-flex items-center gap-1.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                                <i class="ph ph-prohibit text-base"></i>
                                Ban Akun
                            </button>
                        @else
                            <button type="submit" form="unban"
                                onclick="return confirm('Yakin ingin mengaktifkan kembali akun ini?')"
                                class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                                <i class="ph ph-check-circle text-base"></i>
                                Unban Akun
                            </button>
                        @endif

                        <button type="submit" form="hapus"
                            onclick="return confirm('Yakin ingin MENGHAPUS permanen akun ini? Tindakan tidak bisa dibatalkan!')"
                            class="inline-flex items-center gap-1.5 border border-rose-300 text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 text-sm font-semibold px-4 py-2 rounded-xl transition">
                            <i class="ph ph-trash text-base"></i>
                            Hapus Akun
                        </button>
                    </div>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Email -->
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Email</p>
                    <p class="text-sm text-slate-700 font-medium">{{ $data->email }}</p>
                </div>

                <!-- Telepon -->
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Telepon</p>
                    <p class="text-sm text-slate-700 font-medium">
                        @if ($data->role == 'pelamar')
                            {{ $data->pelamar->telepon_pelamar ?? '-' }}
                        @elseif ($data->role == 'perusahaan')
                            {{ $data->perusahaan->telepon_perusahaan ?? '-' }}
                        @else
                            -
                        @endif
                    </p>
                </div>

                <!-- Alamat -->
                <div class="sm:col-span-2">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Alamat</p>
                    @php
                        $provinsi = null; $kota = null; $kecamatan = null; $desa = null; $kode_pos = null; $detail = null;
                        $alamat = $data->finance ?? null;
                        if ($alamat) {
                            $provinsi = $alamat->provinsi->nama ?? null;
                            $kota = $alamat->kota->nama ?? null;
                            $kecamatan = $alamat->kecamatan->nama ?? null;
                            $desa = $alamat->desa ?? null;
                            $kode_pos = $alamat->kode_pos ?? null;
                            $detail = $alamat->detail_alamat ?? null;
                        }
                        $bagian = array_filter([$desa, $kecamatan, $kota, $provinsi, $kode_pos]);
                    @endphp
                    @if($alamat)
                        <div class="text-sm text-slate-700 leading-relaxed">
                            @if (!empty($detail))<p class="mb-1">{{ $detail }}</p>@endif
                            <p class="text-slate-500 text-xs">{{ implode(', ', $bagian) }}</p>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic">Alamat belum diisi.</p>
                    @endif
                </div>

            </div>
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
