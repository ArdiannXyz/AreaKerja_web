<?php

namespace App\Http\Controllers;

use App\Models\CatatanKoin;
use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\PaketLowongan;
use App\Models\Perusahaan;
use App\Models\User;
use App\Services\CoinTransactionService;
use App\Services\NotificationService;
use App\Http\Requests\StoreLowonganRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $isVip = $perusahaan ? $perusahaan->isVip() : false;
        $activeCount = $perusahaan ? $perusahaan->activeLowonganCount() : 0;
        $maxActive = $perusahaan ? $perusahaan->maxActiveLowongan() : 2;
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

        return view('perusahaan.pasang-lowongan', compact('perusahaan', 'pakets', 'isVip', 'activeCount', 'maxActive'));
    }

    public function createForm()
    {
        $perusahaan = Auth::user()->perusahaan;
        $categories = $this->getCategories();
        $alamats = collect();
        if ($perusahaan && \Illuminate\Support\Facades\Schema::hasTable('alamat_perusahaan')) {
            $alamats = $perusahaan->alamatPerusahaan()->get();
        }

        $isVip = $perusahaan ? $perusahaan->isVip() : false;
        $activeLowonganCount = $perusahaan ? $perusahaan->activeLowonganCount() : 0;
        $maxActiveLowongan = $perusahaan ? $perusahaan->maxActiveLowongan() : 2;

        $pakets = PaketLowongan::whereIn('nama', ['Bronze', 'Silver', 'Gold'])
            ->orderByRaw("FIELD(nama, 'Bronze', 'Silver', 'Gold')")
            ->get();

        return view('perusahaan.lowongan-saya.tambah-lowongan', compact(
            'pakets', 'categories', 'perusahaan', 'alamats', 'isVip', 'activeLowonganCount', 'maxActiveLowongan'
        ));
    }

    public function store(StoreLowonganRequest $request)
    {
        $perusahaan = Auth::user()->perusahaan;
        $isVip = $perusahaan ? $perusahaan->isVip() : false;
        $maxAllowed = $perusahaan ? $perusahaan->maxActiveLowongan() : 2;
        $currentActive = $perusahaan ? $perusahaan->activeLowonganCount() : 0;

        // Validasi kuota lowongan aktif (Gratis: max 2, VIP: max 20)
        if ($currentActive >= $maxAllowed) {
            $msg = $isVip
                ? "Anda telah mencapai batas kuota maksimal {$maxAllowed} lowongan aktif untuk akun VIP."
                : "Anda telah mencapai batas kuota {$maxAllowed} lowongan aktif untuk akun Biasa (Gratis). Silakan upgrade ke langganan VIP untuk memasang hingga 20 lowongan aktif.";

            return redirect()->back()
                ->withInput()
                ->withErrors(['kuota' => $msg])
                ->with('error', $msg);
        }

        $valid = $request->validated();

        if (empty($valid['label_gaji'])) {
            $formatAwal = 'Rp ' . number_format($valid['gaji_awal'], 0, ',', '.');
            $formatAkhir = 'Rp ' . number_format($valid['gaji_akhir'], 0, ',', '.');
            $valid['label_gaji'] = "$formatAwal - $formatAkhir";
        }

        $valid['perusahaan_id'] = $perusahaan->id;
        $valid['slug'] = Str::slug($request->nama . '-' . time());
        $valid['published_at'] = now();
        $valid['status'] = 'buka';

        // Durasi otomatis & Job Booster sesuai Tier (Freemium vs VIP):
        // Akun Biasa: Masa aktif 30 hari (1 bulan), kuota pelamar harian maks 5.
        // VIP: Pilihan masa aktif 30, 60, hingga 90 hari, unlimited pelamar, Job Booster otomatis aktif (selalu top).
        if ($isVip) {
            $allowedDurasi = [30, 60, 90];
            $durasiHari = (int) $request->input('durasi_hari', 60);
            if (!in_array($durasiHari, $allowedDurasi)) {
                $durasiHari = 60;
            }
            $valid['expired_at'] = now()->addDays($durasiHari);
            $valid['boosted_until'] = $perusahaan->tanggal_expired && \Carbon\Carbon::now()->lt($perusahaan->tanggal_expired)
                ? $perusahaan->tanggal_expired
                : now()->addDays($durasiHari);
        } else {
            $durasiHari = 30;
            $valid['expired_at'] = now()->addDays(30);
            $valid['boosted_until'] = null;
        }

        $valid['batas_lamaran'] = $valid['expired_at']->toDateString();

        LowonganPerusahaan::create($valid);

        $successMsg = $isVip
            ? "Lowongan VIP berhasil diterbitkan! Masa aktif {$durasiHari} hari dan Job Booster otomatis aktif (selalu teratas di pencarian)."
            : 'Lowongan berhasil diterbitkan secara gratis! Masa aktif berlaku selama 30 hari.';

        return redirect()->route('lowongan.saya.perusahaan')->with('success', $successMsg);
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

        try {
            $pesanSukses = DB::transaction(function () use ($perusahaan, $paket, $lowongan, $biayaKoin, $user) {
                // 1. Potong koin via CoinTransactionService
                $coinService = app(CoinTransactionService::class);
                $coinService->deduct(
                    $perusahaan,
                    $biayaKoin,
                    'Pembelian Paket ' . $paket->nama,
                    'Saldo Koin Perusahaan',
                    'KOIN-' . now()->format('YmdHis') . '-' . $user->id,
                    $perusahaan->nama_perusahaan
                );

                // 2. Update status & expired_at lowongan
                if ($lowongan->published_at && $lowongan->expired_at && $lowongan->expired_at > now()) {
                    $lowongan->update([
                        'paket_id'   => $paket->id,
                        'expired_at' => $lowongan->expired_at->addDays($paket->batas_listing),
                    ]);
                    return 'Paket berhasil dibeli. Masa aktif lowongan berhasil diperpanjang ' . $paket->batas_listing . ' hari.';
                } else {
                    $lowongan->update([
                        'paket_id'     => $paket->id,
                        'published_at' => now(),
                        'expired_at'   => now()->addDays($paket->batas_listing),
                    ]);
                    return 'Paket ' . $paket->nama . ' berhasil dibeli! Lowongan Anda kini telah AKTIF dan terbit selama ' . $paket->batas_listing . ' hari.';
                }
            });

            return redirect()->route('lowongan.saya.perusahaan')->with('success', $pesanSukses);
        } catch (\Throwable $e) {
            Log::error("Gagal memproses pembelian paket lowongan: " . $e->getMessage(), [
                'perusahaan_id' => $perusahaan->id,
                'lowongan_id'   => $lowongan->id,
                'paket_id'      => $paket->id,
            ]);

            return redirect()->route('paket.form')->with('error', 'Terjadi kesalahan sistem saat memproses pembelian paket.');
        }
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

    public function storeSuper(StoreLowonganRequest $request, $id)
    {
        $valid = $request->validated();

        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat menambahkan lowongan.');
        }

        $perusahaan = Perusahaan::findOrFail($id);
        $valid['perusahaan_id'] = $perusahaan->id;
        $valid['slug'] = Str::slug($request->nama . '-' . time());

        LowonganPerusahaan::create($valid);

        NotificationService::send(
            Auth::id(),
            'Lowongan Baru Ditambahkan',
            'Kamu berhasil menambahkan lowongan untuk ' . $perusahaan->nama_perusahaan,
            ['perusahaan_id' => $perusahaan->id]
        );

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

        try {
            DB::transaction(function () use ($perusahaan, $lowongan, $hargaBoost, $user) {
                $coinService = app(CoinTransactionService::class);
                $coinService->deduct(
                    $perusahaan,
                    $hargaBoost,
                    'Boost Lowongan: ' . $lowongan->nama,
                    'boost-lowongan',
                    'BOOST-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                    'Koin Perusahaan'
                );

                $lowongan->update([
                    'boosted_until' => now()
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil di-boost.',
            ]);
        } catch (\Throwable $e) {
            Log::error("Gagal melakukan boost lowongan: " . $e->getMessage(), [
                'perusahaan_id' => $perusahaan->id,
                'lowongan_id'   => $lowongan->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses booster lowongan.',
            ], 500);
        }
    }
}
