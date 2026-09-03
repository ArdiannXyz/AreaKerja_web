<?php

namespace App\Http\Controllers;

use App\Models\CatatanKoin;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\PaketLowongan;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LowonganPerusahaanController extends Controller
{
    private function getCategories()
    {
        return collect([
            'IT & Software',
            'Marketing',
            'Finance',
            'Desain & Kreatif',
            'Operasional',
            'Sales',
            'Admin & HRD',
            'Lainnya',
        ])->map(fn($c) => (object)['nama' => $c, 'name' => $c]);
    }

    public function index(Request $request)
    {
        $pakets = PaketLowongan::all();
        $jenisLowongan = LowonganPerusahaan::select('jenis')->distinct()->pluck('jenis');

        $query = LowonganPerusahaan::where('perusahaan_id', auth()->user()->perusahaan->id);

        if ($request->paket) {
            $query->where('paket_id', $request->paket);
        }

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        $lowongans = $query->latest()->get();
        $hargaBoost = 300;

        return view('perusahaan.lowongan-saya.lowongan-kosong', [
            "Data"          => $lowongans,
            'lowongans'     => $lowongans,
            'pakets'        => $pakets,
            'jenisLowongan' => $jenisLowongan,
            'hargaBoost'    => $hargaBoost,
        ]);
    }

    public function paketform()
    {
        $perusahaan = Auth::user()->perusahaan;
        $pakets = PaketLowongan::whereIn('nama', ['Gold', 'Silver', 'Bronze'])->get();

        if ($pakets->isEmpty()) {
            $pakets = collect([
                (object)[
                    'id'            => 1,
                    'nama'          => 'Gold',
                    'deskripsi'     => '5 Kali Publikasi di semua jaringan Areakerja.com',
                    'harga'         => 150,
                    'harga_koin'    => 150,
                    'batas_listing' => 30,
                    'benefit'       => "Website & Aplikasi\nInstagram Post & Story\nHighlight Story Favorit\nGoogle Jobs & Bisnis\nFacebook Post & Story\nTwitter\nLinkedIn\nTelegram",
                ],
                (object)[
                    'id'            => 2,
                    'nama'          => 'Silver',
                    'deskripsi'     => '3 Kali Publikasi di semua jaringan Areakerja.com',
                    'harga'         => 100,
                    'harga_koin'    => 100,
                    'batas_listing' => 21,
                    'benefit'       => "Website & Aplikasi\nInstagram Post & Story\nHighlight Story Favorit\nGoogle Jobs & Bisnis\nFacebook Post & Story\nTwitter\nLinkedIn\nTelegram",
                ],
                (object)[
                    'id'            => 3,
                    'nama'          => 'Bronze',
                    'deskripsi'     => '1 Kali Publikasi di semua jaringan Areakerja.com',
                    'harga'         => 50,
                    'harga_koin'    => 50,
                    'batas_listing' => 14,
                    'benefit'       => "Website & Aplikasi\nInstagram Post & Story\nHighlight Story Favorit\nGoogle Jobs & Bisnis\nFacebook Post & Story\nTwitter\nLinkedIn\nTelegram",
                ],
            ]);
        }

        foreach ($pakets as $paket) {
            $paket->harga = $paket->harga_koin ?? 100;
        }

        return view('perusahaan.pasang-lowongan', compact('perusahaan', 'pakets'));
    }

    public function createForm()
    {
        $perusahaan = Auth::user()->perusahaan;
        $categories = $this->getCategories();
        $alamats = collect();
        if ($perusahaan && \Illuminate\Support\Facades\Schema::hasTable('alamat_perusahaan')) {
            $alamats = $perusahaan->alamatPerusahaan()->get();
        }

        $pakets = PaketLowongan::whereIn('nama', ['Bronze', 'Silver', 'Gold'])
            ->orderByRaw("FIELD(nama, 'Bronze', 'Silver', 'Gold')")
            ->get();

        if ($pakets->isEmpty()) {
            $defaultPakets = [
                ['nama' => 'Bronze', 'deskripsi' => '1 Kali Publikasi di semua jaringan Areakerja.com', 'harga_koin' => 50, 'batas_listing' => 14, 'benefit' => "Website & Aplikasi\nInstagram Post & Story\nHighlight Story Favorit\nGoogle Jobs & Bisnis\nFacebook Post & Story\nTwitter\nLinkedIn\nTelegram"],
                ['nama' => 'Silver', 'deskripsi' => '3 Kali Publikasi di semua jaringan Areakerja.com', 'harga_koin' => 100, 'batas_listing' => 21, 'benefit' => "Website & Aplikasi\nInstagram Post & Story\nHighlight Story Favorit\nGoogle Jobs & Bisnis\nFacebook Post & Story\nTwitter\nLinkedIn\nTelegram"],
                ['nama' => 'Gold',   'deskripsi' => '5 Kali Publikasi di semua jaringan Areakerja.com', 'harga_koin' => 150, 'batas_listing' => 30, 'benefit' => "Website & Aplikasi\nInstagram Post & Story\nHighlight Story Favorit\nGoogle Jobs & Bisnis\nFacebook Post & Story\nTwitter\nLinkedIn\nTelegram"],
            ];
            foreach ($defaultPakets as $dp) {
                try {
                    PaketLowongan::firstOrCreate(['nama' => $dp['nama']], $dp);
                } catch (\Throwable $e) {
                    // ignore
                }
            }
            $pakets = PaketLowongan::whereIn('nama', ['Bronze', 'Silver', 'Gold'])
                ->orderByRaw("FIELD(nama, 'Bronze', 'Silver', 'Gold')")
                ->get();
        }

        return view('perusahaan.lowongan-saya.tambah-lowongan', compact('pakets', 'categories', 'perusahaan', 'alamats'));
    }

    public function store(Request $request)
    {
        $perusahaan = Auth::user()->perusahaan;

        $valid = $request->validate([
            'nama'             => 'required|string|max:255',
            'alamat'           => 'required|string|max:255',
            'jenis'            => 'required|string',
            'kategori'         => 'required|string',
            'label_gaji'       => 'nullable|string|max:100',
            'benefit'          => 'required|string',
            'gaji_awal'        => 'required|numeric|min:0',
            'gaji_akhir'       => 'required|numeric|min:0',
            'deskripsi'        => 'required|string',
            'tanggung_jawab'   => 'required|string',
            'syarat_pekerjaan' => 'required|string',
            'batas_lamaran'    => 'required|date',
            'paket_id'         => 'nullable|exists:paket_lowongans,id',
        ]);

        if (empty($valid['label_gaji'])) {
            $formatAwal = 'Rp ' . number_format($valid['gaji_awal'], 0, ',', '.');
            $formatAkhir = 'Rp ' . number_format($valid['gaji_akhir'], 0, ',', '.');
            $valid['label_gaji'] = "$formatAwal - $formatAkhir";
        }

        $valid['perusahaan_id'] = $perusahaan->id;
        $valid['slug'] = Str::slug($request->nama . '-' . time());

        // Handle selected package purchase
        if (!empty($valid['paket_id'])) {
            $paket = PaketLowongan::find($valid['paket_id']);
            if ($paket) {
                $biayaKoin = $paket->harga_koin ?? 50;
                if (($perusahaan->koin_perusahaan ?? 0) < $biayaKoin) {
                    return redirect()->back()
                        ->withInput()
                        ->with('koin_kurang', true)
                        ->withErrors(['paket_id' => 'Koin Anda tidak mencukupi untuk memilih paket ' . $paket->nama . ' (' . $biayaKoin . ' Koin). Silakan top up koin terlebih dahulu.']);
                }

                $perusahaan->decrement('koin_perusahaan', $biayaKoin);
                $valid['published_at'] = now();
                $valid['expired_at'] = now()->addDays($paket->batas_listing ?? 14);

                try {
                    CatatanKoin::create([
                        'user_id'       => Auth::id(),
                        'koin_terpakai' => $biayaKoin,
                        'keterangan'    => 'Pembelian Paket ' . $paket->nama . ' untuk lowongan: ' . $valid['nama'],
                    ]);
                } catch (\Throwable $e) {
                    // ignore
                }
            }
        }

        LowonganPerusahaan::create($valid);
        return redirect()->route('lowongan.saya.perusahaan')->with('success', 'Lowongan berhasil ditambahkan dan langsung dipublikasikan!');
    }

    public function show(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }

        $lowonganLainnya = LowonganPerusahaan::where('perusahaan_id', $lowongan->perusahaan_id)
            ->where('id', '!=', $lowongan->id)
            ->latest()
            ->take(5)
            ->get();

        $isBoostActive = !is_null($lowongan->boosted_until);
        $boostedAt = $lowongan->boosted_until
            ? \Carbon\Carbon::parse($lowongan->boosted_until)
            : null;

        return view('perusahaan.lowongan-saya.detail-lowongan', [
            "data"           => $lowongan,
            "Data"           => LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
                ->where('id', '!=', $lowongan->id)
                ->latest()
                ->take(5)
                ->get(),
            "lowonganLainnya" => $lowonganLainnya,
            "isBoostActive"   => $isBoostActive,
            "boostedAt"       => $boostedAt,
        ]);
    }

    public function edit(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }
        $categories = $this->getCategories();
        return view('perusahaan.lowongan-saya.edit', [
            "data"       => $lowongan,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, LowonganPerusahaan $lowongan)
    {
        $valid = $request->validate([
            'nama'             => 'nullable|string|max:255',
            'jenis'            => 'nullable|string',
            'gaji_awal'        => 'nullable|numeric',
            'gaji_akhir'       => 'nullable|numeric',
            'alamat'           => 'nullable|string',
            'kategori'         => 'nullable|string',
            'status'           => 'nullable|string',
            'batas_lamaran'    => 'nullable|date',
            'deskripsi'        => 'nullable|string',
            'syarat_pekerjaan' => 'nullable|string',
            'tanggung_jawab'   => 'nullable|string',
            'benefit'          => 'nullable|string',
        ]);

        $valid['perusahaan_id'] = Auth::user()->perusahaan->id;
        if ($request->filled('nama')) {
            $valid['slug'] = Str::slug($request->nama . '-' . time());
        }

        $lowongan->update($valid);
        return redirect()->route('lowongan.saya.perusahaan')->with('success', 'Lowongan berhasil diedit');
    }

    public function formPendidikan(Perusahaan $perusahaan, LowonganPerusahaan $lowongan)
    {
        if ($lowongan->perusahaan_id !== $perusahaan->id) {
            abort(404);
        }
        $categories = $this->getCategories();
        return view('perusahaan.lowongan-saya.pendidikan', [
            "data"       => $lowongan,
            'categories' => $categories,
        ]);
    }

    public function updatePendidikan(Request $request, LowonganPerusahaan $lowongan)
    {
        $valid = $request->validate([
            'syarat_pekerjaan' => 'required|string',
        ]);

        $lowongan->update($valid);
        return redirect()->route('lowongan.saya.perusahaan')->with('success', 'Pendidikan berhasil diperbarui.');
    }

    public function destroy(LowonganPerusahaan $lowongan)
    {
        $lowongan->delete();
        return redirect()->route('lowongan.saya.perusahaan')->with('success', 'Lowongan berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $perusahaan = Auth::user()->perusahaan;
        $lowongan = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->where('id', $id)
            ->firstOrFail();

        $newStatus = ($lowongan->status === 'tutup') ? 'buka' : 'tutup';
        $lowongan->update(['status' => $newStatus]);

        $statusMsg = ($newStatus === 'tutup')
            ? "Lowongan '{$lowongan->nama}' berhasil DITUTUP (Kuota Terpenuhi)."
            : "Lowongan '{$lowongan->nama}' berhasil DIBUKA KEMBALI.";

        return redirect()->back()->with('success', $statusMsg);
    }

    public function destroyPendidikan(LowonganPerusahaan $lowongan)
    {
        $lowongan->syarat_pekerjaan = null;
        $lowongan->save();
        return redirect()->route('lowongan.saya.perusahaan')->with('success', 'Pendidikan berhasil dihapus.');
    }

    public function beliPaket(Request $request)
    {
        $request->validate([
            'paket_id'    => 'required|exists:paket_lowongans,id',
            'lowongan_id' => 'required|exists:lowongan_perusahaans,id',
        ]);

        $user = Auth::user();
        $perusahaan = $user->perusahaan;
        $paket = PaketLowongan::findOrFail($request->paket_id);
        $biayaKoin = $paket->harga_koin ?? 100;

        if ($perusahaan->koin_perusahaan < $biayaKoin) {
            return redirect()->route('paket.form')->with('koin_kurang', true);
        }

        $lowongan = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->where('id', $request->lowongan_id)
            ->firstOrFail();

        $perusahaan->decrement('koin_perusahaan', $biayaKoin);

        if ($lowongan->published_at && $lowongan->expired_at && $lowongan->expired_at > now()) {
            $lowongan->update([
                'paket_id'   => $paket->id,
                'expired_at' => $lowongan->expired_at->addDays($paket->batas_listing),
            ]);
            $pesanSukses = 'Paket berhasil dibeli. Masa aktif lowongan berhasil diperpanjang ' . $paket->batas_listing . ' hari.';
        } else {
            $lowongan->update([
                'paket_id'     => $paket->id,
                'published_at' => now(),
                'expired_at'   => now()->addDays($paket->batas_listing),
            ]);
            $pesanSukses = 'Paket ' . $paket->nama . ' berhasil dibeli! Lowongan Anda kini telah AKTIF dan terbit selama ' . $paket->batas_listing . ' hari.';
        }

        CatatanKoin::create([
            'user_id'      => $user->id,
            'no_referensi' => 'KOIN-' . now()->format('YmdHis') . '-' . $user->id,
            'pesanan'      => 'Pembelian Paket ' . $paket->nama,
            'dari'         => $perusahaan->nama_perusahaan,
            'sumber_dana'  => 'Saldo Koin Perusahaan',
            'total'        => '-' . $biayaKoin,
        ]);

        return redirect()->route('lowongan.saya.perusahaan')->with('success', $pesanSukses);
    }

    public function toggleRekomendasi($id)
    {
        $lowongan = LowonganPerusahaan::findOrFail($id);
        $lowongan->rekomendasi = $lowongan->rekomendasi ? null : now();
        $lowongan->save();

        return redirect()->back()->with('success', 'Status rekomendasi berhasil diubah.');
    }

    public function createSuper($id)
    {
        $pakets = PaketLowongan::all();
        $perusahaan = Perusahaan::findOrFail($id);
        $categories = $this->getCategories();
        return view('super_admin.perusahaan.tambah-lowongan', [
            'perusahaan' => $perusahaan,
            'pakets'     => $pakets,
            'categories' => $categories,
        ]);
    }

    public function storeSuper(Request $request, $id)
    {
        $valid = $request->validate([
            "nama"             => "required",
            "alamat"           => "required",
            "jenis"            => "required",
            "gaji_awal"        => "required",
            "gaji_akhir"       => "required",
            "deskripsi"        => "required",
            "syarat_pekerjaan" => "required",
            "batas_lamaran"    => "required",
            'kategori'         => 'nullable',
            'benefit'          => 'nullable',
            'tanggung_jawab'   => 'nullable',
        ]);

        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat menambahkan lowongan.');
        }

        $perusahaan = Perusahaan::findOrFail($id);
        $valid['perusahaan_id'] = $perusahaan->id;
        $valid['slug'] = Str::slug($request->nama . '-' . time());

        LowonganPerusahaan::create($valid);

        Notifikasi::create([
            'user_id'       => Auth::id(),
            'perusahaan_id' => $perusahaan->id,
            'judul'         => 'Lowongan Baru Ditambahkan',
            'pesan'         => 'Kamu berhasil menambahkan lowongan untuk ' . $perusahaan->nama_perusahaan,
            'is_read'       => false,
            'expired_at'    => now()->addDays(7),
        ]);

        return redirect()
            ->route('superadmin.perusahaan.detail', $perusahaan->id)
            ->with('success', 'Lowongan berhasil ditambahkan!');
    }

    public function editSuper(LowonganPerusahaan $lowongan)
    {
        $categories = $this->getCategories();
        return view('super_admin.perusahaan.edit-lowongan', [
            "lowongan"   => $lowongan,
            "categories" => $categories,
        ]);
    }

    public function updateSuper(Request $request, LowonganPerusahaan $lowongan)
    {
        $valid = $request->validate([
            'nama'             => 'nullable|string|max:255',
            'jenis'            => 'nullable|string',
            'gaji_awal'        => 'nullable|numeric',
            'gaji_akhir'       => 'nullable|numeric',
            'alamat'           => 'nullable|string',
            'kategori'         => 'nullable|string',
            'batas_lamaran'    => 'nullable|date',
            'deskripsi'        => 'nullable|string',
            'syarat_pekerjaan' => 'nullable|string',
            'tanggung_jawab'   => 'nullable|string',
            'benefit'          => 'nullable|string',
        ]);

        $valid['perusahaan_id'] = $lowongan->perusahaan->id;
        if ($request->filled('nama')) {
            $valid['slug'] = Str::slug($request->nama . '-' . time());
        }
        $lowongan->update($valid);
        return redirect()->route('superadmin.lowongan.detail', [
            'perusahaan' => $lowongan->perusahaan->slug ?? $lowongan->perusahaan_id,
            'lowongan'   => $lowongan->id
        ])->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroySuper(LowonganPerusahaan $lowongan)
    {
        $perusahaanId = $lowongan->perusahaan_id;
        $lowongan->delete();
        return redirect()->route('superadmin.perusahaan.detail', $perusahaanId)->with('success', 'Lowongan berhasil dihapus.');
    }

    public function boost(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan_perusahaans,id'
        ]);

        $user = Auth::user();
        $perusahaan = $user->perusahaan;
        $hargaBoost = 300;

        if ($perusahaan->koin_perusahaan < $hargaBoost) {
            return response()->json([
                'success'     => false,
                'koin_kurang' => true,
                'message'     => 'Koin perusahaan tidak mencukupi.',
            ]);
        }

        $lowongan = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->whereNotNull('published_at')
            ->findOrFail($request->lowongan_id);

        $perusahaan->decrement('koin_perusahaan', $hargaBoost);

        $lowongan->update([
            'boosted_until' => now()
        ]);

        CatatanKoin::create([
            'user_id'      => $user->id,
            'no_referensi' => 'BOOST-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'pesanan'      => 'Boost Lowongan: ' . $lowongan->nama,
            'dari'         => 'Koin Perusahaan',
            'sumber_dana'  => 'boost-lowongan',
            'total'        => -$hargaBoost,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil di-boost.',
        ]);
    }
}
