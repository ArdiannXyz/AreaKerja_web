<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Perusahaan;
use App\Models\Iklan;
use App\Models\CatatanKoin;
use App\Models\LowonganPerusahaan;
use App\Models\SiteSetting;
use App\Services\CoinTransactionService;
use App\Http\Requests\StoreIklanRequest;

class IklanPerusahaanController extends Controller
{
    public static function getPaketList()
    {
        return [
            2 => [
                'id' => 2,
                'nama' => 'Paket Silver',
                'durasi' => 7,
                'koin' => (int) SiteSetting::get('harga_koin_iklan_7_hari', 300),
                'badge' => 'Paling Diminati',
                'deskripsi' => '1 minggu penuh di slot banner utama beranda untuk jangkauan optimal.',
            ],
            3 => [
                'id' => 3,
                'nama' => 'Paket Gold',
                'durasi' => 14,
                'koin' => (int) SiteSetting::get('harga_koin_iklan_14_hari', 550),
                'badge' => 'Hemat 50 Koin',
                'deskripsi' => '2 pekan penayangan berkelanjutan dengan impresi tinggi.',
            ],
            4 => [
                'id' => 4,
                'nama' => 'Paket Platinum',
                'durasi' => 30,
                'koin' => (int) SiteSetting::get('harga_koin_iklan_30_hari', 1000),
                'badge' => 'Maksimal 1 Bulan',
                'deskripsi' => 'Sebulan penuh tampil terdepan untuk branding perusahaan secara masif.',
            ],
        ];
    }

    /**
     * Daftar & Statistik Iklan Perusahaan
     */
    public function index()
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();

        // Update status expired untuk iklan perusahaan ini jika waktu sudah lewat
        Iklan::where('perusahaan_id', $perusahaan->id)
            ->where('status', 'aktif')
            ->where('tanggal_selesai', '<', now())
            ->update(['status' => 'selesai']);

        $iklans = Iklan::where('perusahaan_id', $perusahaan->id)
            ->latest()
            ->paginate(10);

        $totalIklan = Iklan::where('perusahaan_id', $perusahaan->id)->count();
        $iklanAktif = Iklan::where('perusahaan_id', $perusahaan->id)->where('status', 'aktif')->count();
        $totalViews = Iklan::where('perusahaan_id', $perusahaan->id)->sum('total_views');
        $totalClicks = Iklan::where('perusahaan_id', $perusahaan->id)->sum('total_clicks');
        $paketList = self::getPaketList();

        return view('perusahaan.iklan.index', compact(
            'perusahaan',
            'iklans',
            'totalIklan',
            'iklanAktif',
            'totalViews',
            'totalClicks',
            'paketList'
        ));
    }

    /**
     * Form Pasang Iklan Baru
     */
    public function create()
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();
        $paketList = self::getPaketList();

        // Ambil lowongan aktif perusahaan untuk opsi pintasan URL
        $lowongans = LowonganPerusahaan::where('perusahaan_id', $perusahaan->id)
            ->publik()
            ->where('status', '!=', 'tutup')
            ->latest()
            ->get();

        return view('perusahaan.iklan.create', compact('perusahaan', 'paketList', 'lowongans'));
    }

    /**
     * Proses Simpan Iklan & Pemotongan Koin
     */
    public function store(StoreIklanRequest $request)
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();
        $paketList = self::getPaketList();

        $paket = $paketList[$request->paket_id];
        $hargaKoin = $paket['koin'];

        // Cek kecukupan saldo koin
        if ($perusahaan->koin_perusahaan < $hargaKoin) {
            return redirect()->back()->withInput()->with('error_koin', [
                'butuh' => $hargaKoin,
                'punya' => $perusahaan->koin_perusahaan,
                'pesan' => 'Koin perusahaan tidak mencukupi untuk memasang iklan ini. Silakan top up koin terlebih dahulu.',
            ]);
        }

        // Upload banner
        $path = $request->file('gambar_banner')->store('iklan-banners', 'public');

        try {
            DB::transaction(function () use ($perusahaan, $hargaKoin, $paket, $request, $path) {
                // 1. Potong koin via CoinTransactionService secara aman
                $coinService = app(CoinTransactionService::class);
                $coinService->deduct(
                    $perusahaan,
                    $hargaKoin,
                    'Pasang ' . $paket['nama'] . ' (' . $paket['durasi'] . ' Hari)',
                    'Pembayaran Iklan',
                    'IKLAN-' . strtoupper(Str::random(8)),
                    $perusahaan->nama_perusahaan
                );

                // 2. Simpan data iklan
                Iklan::create([
                    'perusahaan_id'   => $perusahaan->id,
                    'judul_iklan'     => $request->judul_iklan,
                    'gambar_banner'   => $path,
                    'url_tujuan'      => $request->url_tujuan,
                    'paket_nama'      => $paket['nama'],
                    'durasi_hari'     => $paket['durasi'],
                    'koin_terpotong'  => $hargaKoin,
                    'posisi'          => 'home_hero',
                    'status'          => 'menunggu', // Menunggu persetujuan Admin
                    'total_views'     => 0,
                    'total_clicks'    => 0,
                ]);
            });

            return redirect()->route('perusahaan.iklan.index')->with('success', 'Iklan berhasil diajukan dan saldo koin telah dipotong. Iklan akan tayang di homepage setelah disetujui oleh Admin.');
        } catch (\Throwable $e) {
            // Hapus file banner jika transaksi database gagal
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error("Gagal mengajukan pasang iklan perusahaan: " . $e->getMessage(), [
                'perusahaan_id' => $perusahaan->id,
                'paket_id'      => $request->paket_id,
            ]);

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat mengajukan iklan.');
        }
    }

    /**
     * Perpanjang Durasi Iklan oleh Perusahaan (Menggunakan Saldo Koin)
     */
    public function perpanjang(Request $request, $id)
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();
        $iklan = Iklan::where('id', $id)->where('perusahaan_id', $perusahaan->id)->firstOrFail();

        $request->validate([
            'paket_id' => 'required|integer|in:2,3,4',
        ], [
            'paket_id.required' => 'Silakan pilih paket perpanjangan durasi.',
            'paket_id.in'       => 'Pilihan paket perpanjangan tidak valid.',
        ]);

        $paketList = self::getPaketList();
        $paket = $paketList[$request->paket_id];
        $hargaKoin = (int) $paket['koin'];
        $tambahHari = (int) $paket['durasi'];

        // Cek kecukupan saldo koin perusahaan
        if ($perusahaan->koin_perusahaan < $hargaKoin) {
            return redirect()->back()->with('error', "Saldo koin Anda (" . number_format($perusahaan->koin_perusahaan, 0, ',', '.') . " koin) tidak mencukupi untuk memperpanjang iklan dengan {$paket['nama']} ({$hargaKoin} koin). Silakan top up koin terlebih dahulu.");
        }

        try {
            DB::transaction(function () use ($perusahaan, $iklan, $paket, $hargaKoin, $tambahHari) {
                // 1. Potong koin perusahaan
                $coinService = app(CoinTransactionService::class);
                $coinService->deduct(
                    $perusahaan,
                    $hargaKoin,
                    'Perpanjang ' . $paket['nama'] . ' (' . $tambahHari . ' Hari) - ' . Str::limit($iklan->judul_iklan, 25),
                    'Perpanjangan Iklan',
                    'EXT-' . strtoupper(Str::random(8)),
                    $perusahaan->nama_perusahaan
                );

                // 2. Kalkulasi masa tayang baru
                // Jika iklan masih aktif dan tanggal_selesai di masa depan, lanjutkan dari tanggal_selesai
                if ($iklan->status === 'aktif' && $iklan->tanggal_selesai && $iklan->tanggal_selesai->isFuture()) {
                    $iklan->tanggal_selesai = $iklan->tanggal_selesai->copy()->addDays($tambahHari);
                } else {
                    // Jika sudah selesai / expired, mulai aktif kembali dari hari ini
                    $now = now();
                    $iklan->tanggal_mulai = $now;
                    $iklan->tanggal_selesai = $now->copy()->addDays($tambahHari);
                }

                $iklan->status = 'aktif';
                $iklan->durasi_hari = (int) $iklan->durasi_hari + $tambahHari;
                $iklan->koin_terpotong = (int) $iklan->koin_terpotong + $hargaKoin;
                $iklan->paket_nama = $paket['nama'];
                $iklan->save();
            });

            return redirect()->route('perusahaan.iklan.index')
                ->with('success', "Masa tayang iklan '{$iklan->judul_iklan}' berhasil diperpanjang selama {$tambahHari} hari menggunakan {$paket['nama']}.");
        } catch (\Throwable $e) {
            Log::error("Gagal memperpanjang iklan perusahaan: " . $e->getMessage(), [
                'perusahaan_id' => $perusahaan->id,
                'iklan_id'      => $id,
                'paket_id'      => $request->paket_id,
                'trace'         => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memperpanjang durasi iklan: ' . $e->getMessage());
        }
    }

    /**
     * Tracking Klik Iklan
     */
    public function handleClick($id)
    {
        $iklan = Iklan::findOrFail($id);

        if (!empty($iklan->url_tujuan)) {
            $iklan->increment('total_clicks');
            return redirect()->away($iklan->url_tujuan);
        }

        return redirect('/');
    }
}
