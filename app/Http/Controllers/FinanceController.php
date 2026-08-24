<?php

namespace App\Http\Controllers;

use App\Helpers\BrowserPath;
use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class FinanceController extends Controller
{
    public function pageUnduhOmset()
    {
        return view('finance.page-unduh-omset');
    }


    public function verifikasi($id, Request $request)
    {
        $transaksi = CatatanCash::findOrFail($id);
        $perusahaan = $transaksi->user->perusahaan;
        $paket = $transaksi->hargaPembayaran;
        $pelamar = $transaksi->user->pelamar;

        if ($request->action == 'terima' && $transaksi->status !== 'diterima') {
            $transaksi->status = 'diterima';
            $transaksi->save();

            // Kalau transaksi TOP UP → tambah koin
            if ($paket && $paket->jumlah_koin > 0) {
                $perusahaan->koin_perusahaan += $paket->jumlah_koin;
                $perusahaan->save();
            } else {
                // Kalau transaksi PENDAFTARAN KANDIDAT → ubah kategori jadi calon kandidat
                if ($pelamar) {
                    $pelamar->kategori = 'calon kandidat';
                    $pelamar->save();
                }
            }
        } elseif ($request->action == 'tolak' && $transaksi->status === 'diterima') {
            $transaksi->status = 'ditolak';
            $transaksi->save();

            // Kalau transaksi TOP UP → rollback koin
            if ($paket && $paket->jumlah_koin > 0) {
                $perusahaan->koin_perusahaan -= $paket->jumlah_koin;
                if ($perusahaan->koin_perusahaan < 0) {
                    $perusahaan->koin_perusahaan = 0;
                }
                $perusahaan->save();
            } else {
                // Kalau transaksi PENDAFTARAN KANDIDAT → rollback kategori jadi pelamar lagi
                if ($pelamar) {
                    $pelamar->kategori = 'pelamar';
                    $pelamar->save();
                }
            }
        } elseif ($request->action == 'tolak') {
            // Tolak langsung dari pending → hanya ubah status
            $transaksi->status = 'ditolak';
            $transaksi->save();
        }

        return redirect()->route('finance.catatan')
            ->with('success', 'Transaksi berhasil diverifikasi: ' . $transaksi->status);
    }



    public function laporan(Request $request)
    {

        $queryCash = CatatanCash::with(['user', 'bank']);

        if ($request->periode) {
            $queryCash->where('created_at', '>=', now()->subMonths($request->periode));
        }

        // sembunyikan expired_at lewat + status pending / expired
        $queryCash->where(function ($q) {
            $q->whereNull('expired_at')
                ->orWhere('expired_at', '>', now())
                ->orWhere(function ($sub) {
                    $sub->where('expired_at', '<=', now())
                        ->whereIn('status', ['diterima', 'ditolak', 'menunggu_verifikasi']);
                });
        });

        $catatanCash = $queryCash
            ->orderBy('created_at', 'desc')
            ->get();



        $queryKoin = CatatanKoin::with(['user']);
        if ($request->periode) {
            $queryKoin->where('created_at', '>=', now()->subMonths($request->periode));
        }
        $catatanKoin = $queryKoin->orderBy('created_at', 'desc')->take(6)->get();

        return view('finance.catatan-tran', compact('catatanCash', 'catatanKoin'));
    }


    public function detail($id)
    {
        $transaksi = CatatanCash::with(['user', 'bank'])->findOrFail($id);

        return response()->json([
            'id' => $transaksi->id,
            'user' => $transaksi->user->name ?? '-',
            'email' => $transaksi->user->email ?? '-',
            'bank' => $transaksi->bank->nama_bank ?? '-',
            'nomor_rekening' => $transaksi->bank->nomor_rekening ?? '-',
            'harga' => number_format($transaksi->hargaPembayaran->harga ?? 0, 0, ',', '.'),
            'jumlah_koin' => $transaksi->hargaPembayaran->jumlah_koin ?? 0,
            'status' => ucfirst($transaksi->status),
            'created_at' => $transaksi->created_at->format('d M Y H:i')
        ]);
    }

    public function hal_detail()
    {
        $catatanKoins = CatatanKoin::with('user')->latest()->get();
        $catatanCashs = CatatanCash::with(['user', 'bank'])->latest()->get();

        return view('finance.detail-cat-koin', compact('catatanKoins', 'catatanCashs'));
    }



    public function omset_perusahaan(Request $request)
    {
        $periode = $request->periode;

        $cashQuery = CatatanCash::with(['user', 'bank'])
            ->where('status', 'diterima');

        // Filter waktu
        if ($periode && $periode !== 'current') {
            $cashQuery->where('created_at', '>=', now()->subMonths($periode));
        } elseif ($periode === 'current') {
            $cashQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        $cashData = $cashQuery->get();

        // Kelompokkan per bulan
        $omsetPerBulan = $cashData
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format('Y-m'))
            ->map(function ($group) {
                $total = 0;
                foreach ($group as $item) {
                    if ($item->hargaPembayaran) {
                        $total += $item->hargaPembayaran->harga;
                    }
                }

                $first = $group->first();
                return [
                    'bulan' => Carbon::parse($first->created_at)->month,
                    'nama_bulan' => Carbon::parse($first->created_at)->translatedFormat('F'),
                    'tahun' => Carbon::parse($first->created_at)->year,
                    'total' => $total,
                ];
            })
            ->sortByDesc('tahun')
            ->sortByDesc('bulan')
            ->values();

        $totalOmset = $omsetPerBulan->sum('total');
        $rataRata = $omsetPerBulan->count() > 0 ? $totalOmset / $omsetPerBulan->count() : 0;

        return view('finance.omset-perusahaan', [
            'omsetPerBulan' => $omsetPerBulan,
            'totalOmset' => $totalOmset,
            'rataRata' => $rataRata,
            'periodeDipilih' => $periode,
        ]);
    }



    public function unduh_omset(Request $request)
    {
        $periode = $request->periode;

        // Base query
        $cashQuery = CatatanCash::with(['user', 'bank'])
            ->where('status', 'diterima');

        // Terapkan filter periode (SAMA seperti di omset_perusahaan)
        if ($periode && $periode !== 'current') {
            $cashQuery->where('created_at', '>=', now()->subMonths($periode));
        } elseif ($periode === 'current') {
            $cashQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        $cashData = $cashQuery->get();

        // Kelompokkan per bulan
        $omsetPerBulan = $cashData
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format('Y-m'))
            ->map(function ($group) {
                $total = $group->sum(fn($i) => $i->hargaPembayaran?->harga ?? 0);

                $first = $group->first();

                return [
                    'bulan_angka' => Carbon::parse($first->created_at)->month,
                    'bulan'       => Carbon::parse($first->created_at)->translatedFormat('F Y'),
                    'total'       => $total,
                ];
            })
            ->sortBy('bulan_angka')
            ->values();

        $totalOmset = $omsetPerBulan->sum('total');
        $rataRata = $omsetPerBulan->count() > 0 ? $totalOmset / $omsetPerBulan->count() : 0;

        // Logo
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        // Data ke view
        $data = [
            'omsetPerBulan' => $omsetPerBulan,
            'totalOmset' => $totalOmset,
            'rataRata' => $rataRata,
            'jumlahBulan' => $omsetPerBulan->count(),
            'tanggal' => Carbon::now()->translatedFormat('F d, Y, H:i a'),
            'logoBase64' => $logoBase64,
            'periodeDipilih' => $periode, // Tambahan
        ];

        // Render HTML Blade
        $html = View::make('finance.page-unduh-omset', $data)->render();

        // Bungkus HTML
        $htmlWithCss = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Omset Perusahaan</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="text-[12px] text-black font-sans mx-8 my-6">
        ' . $html . '
    </body>
    </html>';

        // Browsershot
        $browserPath = BrowserPath::detect();
        if (!$browserPath) {
            return response()->json([
                "error" => "Browser Chrome/Edge tidak ditemukan. Pastikan sudah terinstall."
            ], 500);
        }

        $pdf = Browsershot::html($htmlWithCss)
            ->setOption('executablePath', $browserPath)
            ->noSandbox()
            ->showBackground()
            ->format('A4')
            ->margins(10, 15, 10, 15)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Omset_Perusahaan.pdf"');
    }



    //LAPORAN TRANSAKSI
    public function laporan_transaksi(Request $request)
    {
        // Ambil bulan yang dipilih (default: bulan ini)
        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = now()->format('Y');

        // Ambil data 12 bulan terakhir
        $startDate = now()->subMonths(11)->startOfMonth();
        $endDate = now()->endOfMonth();

        $cashData = DB::table('catatan_cashs')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(ABS(total)) as total_cash'),
                DB::raw('COUNT(id) as jumlah_transaksi_cash')
            )
            ->where('status', 'diterima')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'));

        $koinData = DB::table('catatan_koins')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(ABS(total)) as total_koin'),
                DB::raw('COUNT(id) as jumlah_transaksi_koin')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'));

        $laporan = DB::query()
            ->fromSub($cashData, 'cash')
            ->leftJoinSub($koinData, 'koin', function ($join) {
                $join->on('cash.tanggal', '=', 'koin.tanggal');
            })
            ->select(
                'cash.tanggal',
                DB::raw('COALESCE(cash.total_cash, 0) as total_cash'),
                DB::raw('COALESCE(koin.total_koin, 0) as total_koin'),
                DB::raw('COALESCE(cash.total_cash, 0) as total_penghasilan'),
                DB::raw('(COALESCE(cash.jumlah_transaksi_cash, 0) + COALESCE(koin.jumlah_transaksi_koin, 0)) as total_transaksi')
            )
            ->whereMonth('cash.tanggal', $bulan)
            ->orderBy('cash.tanggal', 'desc')
            ->get();

        $bulanList = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return view('finance.laporan-tran', [
            'laporan' => $laporan,
            'bulanList' => $bulanList,
            'bulan' => $bulan
        ]);
    }

    public function detail_laporan($tanggal)
    {
        // Ambil data dari catatan_cashs
        $cashs = CatatanCash::select(
            'id',
            'no_referensi',
            'dari',
            'pesanan',
            'sumberDana as sumber_dana',
            'total',
            DB::raw('NULL as total_koin'),
            DB::raw('"cash" as tipe')
        )
            ->whereDate('created_at', $tanggal)
            ->where('status', 'diterima');

        // Ambil data dari catatan_koins
        $koins = CatatanKoin::select(
            'id',
            'no_referensi',
            'dari',
            'pesanan',
            'sumber_dana',
            DB::raw('NULL as total'),
            DB::raw('ABS(total) as total_koin'),
            DB::raw('"koin" as tipe')
        )
            ->whereDate('created_at', $tanggal);

        // Gabungkan keduanya jadi satu koleksi
        $transaksi = $cashs->unionAll($koins)
            ->orderBy('id', 'asc')
            ->get();

        // Hitung total
        $totalCash = $transaksi->where('tipe', 'cash')->sum('total');
        $totalKoin = $transaksi->where('tipe', 'koin')->sum('total_koin');

        return view('finance.laporan-tran2', [
            'transaksi' => $transaksi,
            'totalCash' => $totalCash,
            'totalKoin' => $totalKoin,
            'tanggal' => $tanggal
        ]);
    }

    public function unduh_laporan_harian($tanggal)
    {
        // Ambil data transaksi cash & koin
        $cashs = CatatanCash::whereDate('created_at', $tanggal)
            ->where('status', 'diterima')
            ->get();

        $koins = CatatanKoin::whereDate('created_at', $tanggal)->get();

        // Gabungkan dua jenis transaksi jadi satu tabel
        $transaksi = collect();

        foreach ($cashs as $c) {
            $transaksi->push((object)[
                'no_referensi' => $c->no_referensi ?? '-',
                'dari' => $c->dari ?? '-',
                'pesanan' => $c->pesanan ?? '-',
                'sumber_dana' => $c->sumberDana ?? 'BCA',
                'nominal' => $c->total,
                'koin' => '-',
            ]);
        }

        foreach ($koins as $k) {
            $transaksi->push((object)[
                'no_referensi' => $k->no_referensi ?? '-',
                'dari' => $k->dari ?? '-',
                'pesanan' => $k->pesanan ?? '-',
                'sumber_dana' => $k->sumber_dana ?? 'Koin',
                'nominal' => '-',
                'koin' => $k->total,
            ]);
        }

        $totalTunai = $cashs->sum('total');
        $totalKoin = $koins->sum('total');

        // Konversi logo jadi base64
        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        // Data untuk view
        $data = [
            'tanggal' => Carbon::parse($tanggal)->translatedFormat('d F Y'),
            'transaksi' => $transaksi,
            'totalTunai' => $totalTunai,
            'totalKoin' => $totalKoin,
            'logoBase64' => $logoBase64,
            'tanggalCetak' => Carbon::now()->translatedFormat('F d, Y, H:i a'),
        ];

        // Render view
        $html = View::make('finance.page-unduh-laporan-harian', $data)->render();

        // Tambahkan HTML wrapper + Tailwind
        $htmlWithCss = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Transaksi Harian</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: "Inter", sans-serif; }
        </style>
    </head>
    <body class="text-[12px] text-black font-sans mx-8 my-6">
        ' . $html . '
    </body>
    </html>
    ';

        // Generate PDF pakai Browsershot
        $browserPath = BrowserPath::detect();
        if (!$browserPath) {
            return response()->json([
                "error" => "Browser Chrome/Edge tidak ditemukan. Pastikan sudah terinstall."
            ], 500);
        }

        $pdf = Browsershot::html($htmlWithCss)
            ->setOption('executablePath', $browserPath)
            ->noSandbox()
            ->showBackground()
            ->format('A4')
            ->margins(10, 15, 10, 15)
            ->pdf();

        // Kembalikan file PDF
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Transaksi_Harian_' . $tanggal . '.pdf"');
    }
}
