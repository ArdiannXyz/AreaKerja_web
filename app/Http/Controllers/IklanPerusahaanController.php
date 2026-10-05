<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Perusahaan;
use App\Models\Iklan;
use App\Models\CatatanKoin;
use App\Models\LowonganPerusahaan;

class IklanPerusahaanController extends Controller
{
    public static function getPaketList()
    {
        return [
            1 => [
                'id' => 1,
                'nama' => 'Paket Bronze',
                'durasi' => 3,
                'koin' => 150,
                'badge' => 'Event Singkat',
                'deskripsi' => 'Ideal untuk publikasi cepat dan promosi lowongan mendesak.',
            ],
            2 => [
                'id' => 2,
                'nama' => 'Paket Silver',
                'durasi' => 7,
                'koin' => 300,
                'badge' => 'Paling Diminati',
                'deskripsi' => '1 minggu penuh di slot banner utama beranda untuk jangkauan optimal.',
            ],
            3 => [
                'id' => 3,
                'nama' => 'Paket Gold',
                'durasi' => 14,
                'koin' => 550,
                'badge' => 'Hemat 50 Koin',
                'deskripsi' => '2 pekan penayangan berkelanjutan dengan impresi tinggi.',
            ],
            4 => [
                'id' => 4,
                'nama' => 'Paket Platinum',
                'durasi' => 30,
                'koin' => 1000,
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

        return view('perusahaan.iklan.index', compact(
            'perusahaan',
            'iklans',
            'totalIklan',
            'iklanAktif',
            'totalViews',
            'totalClicks'
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
            ->where('status', '!=', 'tutup')
            ->latest()
            ->get();

        return view('perusahaan.iklan.create', compact('perusahaan', 'paketList', 'lowongans'));
    }

    /**
     * Proses Simpan Iklan & Pemotongan Koin
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $perusahaan = Perusahaan::where('user_id', $user->id)->firstOrFail();
        $paketList = self::getPaketList();

        $request->validate([
            'paket_id' => 'required|in:1,2,3,4',
            'judul_iklan' => 'required|string|max:200',
            'gambar_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'url_tujuan' => 'required|url|max:500',
        ], [
            'paket_id.required' => 'Silakan pilih paket iklan.',
            'judul_iklan.required' => 'Judul iklan wajib diisi.',
            'gambar_banner.required' => 'Gambar banner iklan wajib diunggah.',
            'gambar_banner.image' => 'File banner harus berupa gambar.',
            'gambar_banner.max' => 'Ukuran gambar banner maksimal 3MB.',
            'url_tujuan.required' => 'Link tujuan iklan wajib diisi.',
            'url_tujuan.url' => 'Format link tujuan harus berupa URL valid (cth: https://...).',
        ]);

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

        // Potong koin
        $perusahaan->koin_perusahaan -= $hargaKoin;
        $perusahaan->save();

        // Catat pengeluaran koin
        CatatanKoin::create([
            'user_id' => $user->id,
            'no_referensi' => 'IKLAN-' . strtoupper(Str::random(8)),
            'pesanan' => 'Pasang ' . $paket['nama'] . ' (' . $paket['durasi'] . ' Hari)',
            'dari' => $perusahaan->nama_perusahaan,
            'sumber_dana' => 'Pembayaran Iklan',
            'total' => '-' . $hargaKoin,
        ]);

        // Simpan data iklan
        Iklan::create([
            'perusahaan_id' => $perusahaan->id,
            'judul_iklan' => $request->judul_iklan,
            'gambar_banner' => $path,
            'url_tujuan' => $request->url_tujuan,
            'paket_nama' => $paket['nama'],
            'durasi_hari' => $paket['durasi'],
            'koin_terpotong' => $hargaKoin,
            'posisi' => 'home_hero',
            'status' => 'menunggu', // Menunggu persetujuan Admin
            'total_views' => 0,
            'total_clicks' => 0,
        ]);

        return redirect()->route('perusahaan.iklan.index')->with('success', 'Iklan berhasil diajukan dan saldo koin telah dipotong. Iklan akan tayang di homepage setelah disetujui oleh Admin.');
    }

    /**
     * Tracking Klik Iklan
     */
    public function handleClick($id)
    {
        $iklan = Iklan::findOrFail($id);
        $iklan->increment('total_clicks');

        return redirect()->away($iklan->url_tujuan);
    }
}
