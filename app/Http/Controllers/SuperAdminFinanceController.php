<?php

namespace App\Http\Controllers;

use App\Helpers\BrowserPath;
use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class SuperAdminFinanceController extends Controller
{
    /**
     * Halaman Utama Finance & Paket Harga Super Admin
     */
    public function halFinance(Request $request)
    {
        $cash = CatatanCash::where('status', 'diterima')->get();
        $koin1 = CatatanKoin::all();
        $cashTerbaru = CatatanCash::orderBy('created_at', 'desc')->get();
        $koinTerbaru = CatatanKoin::orderBy('created_at', 'desc')->get();

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $cash1 = DB::table('catatan_cashs')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as pendapatan')
            )
            ->where('status', 'diterima')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $koin = DB::table('catatan_koins')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total_koin')
            )
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $laporan = collect();
        $tanggalUnik = $cash1->pluck('tanggal')->merge($koin->pluck('tanggal'))->unique()->sort();

        foreach ($tanggalUnik as $tgl) {
            $laporan->push([
                'tanggal'     => Carbon::parse($tgl)->translatedFormat('d F Y'),
                'pendapatan'  => $cash1->firstWhere('tanggal', $tgl)->pendapatan ?? 0,
                'koin'        => $koin->firstWhere('tanggal', $tgl)->total_koin ?? 0,
                'catatan'     => 'Catatan_Transaksi_' . Carbon::parse($tgl)->translatedFormat('F'),
            ]);
        }

        $koinList = collect([
            (object)['id' => 1, 'nama' => 'Pasang Lowongan Bronze', 'harga' => 100],
            (object)['id' => 2, 'nama' => 'Pasang Lowongan Silver', 'harga' => 200],
            (object)['id' => 3, 'nama' => 'Pasang Lowongan Gold', 'harga' => 300],
            (object)['id' => 4, 'nama' => 'Boost Lowongan', 'harga' => 300],
            (object)['id' => 5, 'nama' => 'Berlangganan', 'harga' => 1000],
        ]);

        $pembayaranList = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000],
        ]);

        return view('super_admin.finance.paket-harga', [
            'title'        => 'Paket Harga',
            'koin'         => $koinList,
            'pembayaran'   => $pembayaranList,
            'cashTerbaru'  => $cashTerbaru,
            'koinTerbaru'  => $koinTerbaru,
            'cash'         => $cash,
            'koin1'        => $koin1,
            'laporan'      => $laporan,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
        ]);
    }

    /**
     * Detail Laporan Keuangan Harian
     */
    public function detail_laporan($tanggal)
    {
        $tanggal = Carbon::createFromFormat('d F Y', $tanggal)->format('Y-m-d');

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
            ->where('status', 'diterima')
            ->get();

        $koins = CatatanKoin::select(
            'id',
            'no_referensi',
            'dari',
            'pesanan',
            'sumber_dana',
            DB::raw('NULL as total'),
            'total as total_koin',
            DB::raw('"koin" as tipe')
        )
            ->whereDate('created_at', $tanggal)
            ->get();

        $semuaTransaksi = $cashs->merge($koins)->sortByDesc('created_at');

        return view('super_admin.finance.detail-laporan', [
            'tanggal'        => Carbon::parse($tanggal)->translatedFormat('d F Y'),
            'totalCash'      => $cashs->sum('total'),
            'totalKoin'      => $koins->sum('total_koin'),
            'semuaTransaksi' => $semuaTransaksi,
        ]);
    }

    /**
     * Unduh Laporan Harian ke Format PDF
     */
    public function unduh_laporan_harian($tanggal)
    {
        $cashs = CatatanCash::whereDate('created_at', $tanggal)
            ->where('status', 'diterima')
            ->get();

        $koins = CatatanKoin::whereDate('created_at', $tanggal)->get();
        $transaksi = collect();

        foreach ($cashs as $c) {
            $transaksi->push((object)[
                'no_referensi' => $c->no_referensi ?? '-',
                'dari'         => $c->dari ?? '-',
                'pesanan'      => $c->pesanan ?? '-',
                'sumber_dana'  => $c->sumberDana ?? 'BCA',
                'nominal'      => $c->total,
                'koin'         => '-',
            ]);
        }

        foreach ($koins as $k) {
            $transaksi->push((object)[
                'no_referensi' => $k->no_referensi ?? '-',
                'dari'         => $k->dari ?? '-',
                'pesanan'      => $k->pesanan ?? '-',
                'sumber_dana'  => $k->sumber_dana ?? 'Koin',
                'nominal'      => '-',
                'koin'         => $k->total,
            ]);
        }

        $totalTunai = $cashs->sum('total');
        $totalKoin = $koins->sum('total');

        $logoPath = public_path('images/logoarea.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';

        $data = [
            'tanggal'      => Carbon::parse($tanggal)->translatedFormat('d F Y'),
            'transaksi'    => $transaksi,
            'totalTunai'   => $totalTunai,
            'totalKoin'    => $totalKoin,
            'logoBase64'   => $logoBase64,
            'tanggalCetak' => Carbon::now()->translatedFormat('F d, Y, H:i a'),
        ];

        $html = View::make('finance.page-unduh-laporan-harian', $data)->render();
        $htmlWithCss = '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Laporan Transaksi Harian</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <style>body { font-family: "Inter", sans-serif; }</style>
        </head>
        <body class="text-[12px] text-black font-sans mx-8 my-6">' . $html . '</body>
        </html>';

        $browserPath = BrowserPath::detect();
        if (!$browserPath) {
            return response()->json([
                'error' => 'Browser Chrome/Edge tidak ditemukan. Pastikan sudah terinstall.'
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
            ->header('Content-Disposition', 'attachment; filename="Laporan_Transaksi_Harian_' . $tanggal . '.pdf"');
    }

    /**
     * Edit Harga Koin
     */
    public function edit_koin()
    {
        $koin = collect([
            (object)['id' => 1, 'nama' => 'Pasang Lowongan Bronze', 'harga' => 100],
            (object)['id' => 2, 'nama' => 'Pasang Lowongan Silver', 'harga' => 200],
            (object)['id' => 3, 'nama' => 'Pasang Lowongan Gold', 'harga' => 300],
            (object)['id' => 4, 'nama' => 'Boost Lowongan', 'harga' => 300],
            (object)['id' => 5, 'nama' => 'Berlangganan', 'harga' => 1000],
        ]);

        return view('super_admin.finance.edit-koin', [
            'title' => 'Edit Harga Koin',
            'koin'  => $koin,
        ]);
    }

    public function update_koin(Request $request)
    {
        return redirect()->route('superadmin.paket-harga')->with('success', 'Harga koin berhasil diperbarui.');
    }

    /**
     * Edit Harga Pembayaran Tunai
     */
    public function edit_pembayaran()
    {
        $pembayaran = collect([
            (object)['id' => 1, 'nama' => 'Top Up 10 Koin Area Kerja', 'jumlah_koin' => 10, 'harga' => 10000],
            (object)['id' => 2, 'nama' => 'Top Up 100 Koin Area Kerja', 'jumlah_koin' => 100, 'harga' => 100000],
            (object)['id' => 3, 'nama' => 'Top Up 1000 Koin Area Kerja', 'jumlah_koin' => 1000, 'harga' => 500000],
        ]);

        return view('super_admin.finance.edit-harga', [
            'title'      => 'Edit Harga Pembayaran',
            'pembayaran' => $pembayaran,
        ]);
    }

    public function update_pembayaran(Request $request)
    {
        return redirect()->route('superadmin.paket-harga')->with('success', 'Harga pembayaran berhasil diperbarui.');
    }
}

