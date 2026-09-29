<?php

namespace App\Http\Controllers;

use App\Helpers\BrowserPath;
use App\Models\CatatanCash;
use App\Models\CatatanKoin;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class FinanceController extends Controller
{
    // =============================================
    // PROFILE FINANCE
    // =============================================

    public function profile_finance()
    {
        return view('finance.profile.profile');
    }

    public function edit_profile_finance($id = null)
    {
        $user = Auth::user();
        $provinsis = collect();

        try {
            if (Schema::hasTable('provinsis')) {
                $provinsis = DB::table('provinsis')->get();
            }
        } catch (\Throwable $e) {
            $provinsis = collect();
        }

        if ($provinsis->isEmpty() && file_exists(database_path('data/provinces.json'))) {
            $json = json_decode(file_get_contents(database_path('data/provinces.json')), true);
            $provinsis = collect($json)->map(function ($item) {
                return (object)[
                    'id'   => (string)$item['id'],
                    'nama' => ucwords(strtolower($item['name'])),
                ];
            });
        }

        return view('finance.profile.edit-profile', [
            'provinsis' => $provinsis,
        ]);
    }

    private function loadJsonFileFinance($filepath)
    {
        if (!file_exists($filepath)) return [];
        $content = file_get_contents($filepath);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = trim($content);
        $data = json_decode($content, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);
            $data = json_decode($content, true);
        }
        return $data ?? [];
    }

    public function getKotaFinance($provinsi_id)
    {
        $input = trim(urldecode((string)$provinsi_id));
        if (empty($input)) return response()->json([]);

        $regencies = $this->loadJsonFileFinance(database_path('data/regencies.json'));
        $provinces = $this->loadJsonFileFinance(database_path('data/provinces.json'));
        if (empty($regencies)) return response()->json([]);

        $targetProvId = null;
        foreach ($provinces as $p) {
            $pId = trim((string)($p['id'] ?? ''));
            $pName = trim((string)($p['name'] ?? ''));
            if ($pId === $input || (is_numeric($input) && (int)$pId === (int)$input)
                || strcasecmp($pName, $input) === 0
                || str_contains(strtolower($pName), strtolower($input))) {
                $targetProvId = $pId;
                break;
            }
        }
        if (!$targetProvId) $targetProvId = $input;

        $kotas = collect($regencies)
            ->filter(fn($item) =>
                trim((string)($item['province_id'] ?? '')) === (string)$targetProvId
                || (is_numeric($targetProvId) && (int)trim((string)($item['province_id'] ?? '')) === (int)$targetProvId)
            )->values()
            ->map(fn($item) => [
                'id'          => (string)$item['id'],
                'provinsi_id' => (string)$item['province_id'],
                'nama'        => ucwords(strtolower($item['name'])),
            ]);

        return response()->json($kotas);
    }

    public function getKecamatanFinance($kota_id)
    {
        $input = trim(urldecode((string)$kota_id));
        if (empty($input)) return response()->json([]);

        $districts = $this->loadJsonFileFinance(database_path('data/districts.json'));
        $regencies = $this->loadJsonFileFinance(database_path('data/regencies.json'));
        if (empty($districts)) return response()->json([]);

        $targetKotaId = null;
        foreach ($regencies as $r) {
            $rId = trim((string)($r['id'] ?? ''));
            $rName = trim((string)($r['name'] ?? ''));
            if ($rId === $input || (is_numeric($input) && (int)$rId === (int)$input)
                || strcasecmp($rName, $input) === 0) {
                $targetKotaId = $rId;
                break;
            }
        }
        if (!$targetKotaId) $targetKotaId = $input;

        $kecamatans = collect($districts)
            ->filter(fn($item) =>
                trim((string)($item['regency_id'] ?? '')) === (string)$targetKotaId
                || (is_numeric($targetKotaId) && (int)trim((string)($item['regency_id'] ?? '')) === (int)$targetKotaId)
            )->values()
            ->map(fn($item) => [
                'id'      => (string)$item['id'],
                'kota_id' => (string)$item['regency_id'],
                'nama'    => ucwords(strtolower($item['name'])),
            ]);

        return response()->json($kecamatans);
    }

    public function update_profile_finance(Request $request, $id = null)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'username'     => 'required|string|unique:users,username,' . $user->id,
                'nama_lengkap' => 'required|string',
                'provinsi_id'  => 'required',
                'kota_id'      => 'required',
                'kecamatan_id' => 'required',
            ], [
                'username.required'     => 'Username wajib diisi.',
                'username.unique'       => 'Username sudah digunakan oleh akun lain.',
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'provinsi_id.required'  => 'Provinsi wajib dipilih.',
                'kota_id.required'      => 'Kota / Kabupaten wajib dipilih.',
                'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            ]);

            $imagePath = null;
            if ($request->hasFile('img_profile')) {
                $imagePath = $request->file('img_profile')->store('images', 'public');
                $user->avatar = $imagePath;
            }

            $user->username = $request->username;
            $user->save();

            if (Schema::hasTable('provinsis') && $request->provinsi_id) {
                $provName = null;
                if (file_exists(database_path('data/provinces.json'))) {
                    $json = json_decode(file_get_contents(database_path('data/provinces.json')), true);
                    $found = collect($json)->firstWhere('id', (string)$request->provinsi_id);
                    if ($found) $provName = ucwords(strtolower($found['name']));
                }
                DB::table('provinsis')->updateOrInsert(
                    ['id' => $request->provinsi_id],
                    ['nama' => $provName ?? 'Provinsi ' . $request->provinsi_id, 'updated_at' => now()]
                );
            }

            if (Schema::hasTable('kotas') && $request->kota_id) {
                $kotaName = null;
                if (file_exists(database_path('data/regencies.json'))) {
                    $json = json_decode(file_get_contents(database_path('data/regencies.json')), true);
                    $found = collect($json)->firstWhere('id', (string)$request->kota_id);
                    if ($found) $kotaName = ucwords(strtolower($found['name']));
                }
                DB::table('kotas')->updateOrInsert(
                    ['id' => $request->kota_id],
                    ['provinsi_id' => $request->provinsi_id, 'nama' => $kotaName ?? 'Kota ' . $request->kota_id, 'updated_at' => now()]
                );
            }

            if (Schema::hasTable('kecamatans') && $request->kecamatan_id) {
                $kecName = null;
                if (file_exists(database_path('data/districts.json'))) {
                    $json = json_decode(file_get_contents(database_path('data/districts.json')), true);
                    $found = collect($json)->firstWhere('id', (string)$request->kecamatan_id);
                    if ($found) $kecName = ucwords(strtolower($found['name']));
                }
                DB::table('kecamatans')->updateOrInsert(
                    ['id' => $request->kecamatan_id],
                    ['kota_id' => $request->kota_id, 'nama' => $kecName ?? 'Kecamatan ' . $request->kecamatan_id, 'updated_at' => now()]
                );
            }

            if (Schema::hasTable('finances')) {
                $financeUpdate = [
                    'nama_lengkap'  => $request->nama_lengkap,
                    'provinsi_id'   => $request->provinsi_id,
                    'kota_id'       => $request->kota_id,
                    'kecamatan_id'  => $request->kecamatan_id,
                    'desa'          => $request->desa,
                    'kode_pos'      => $request->kode_pos,
                    'detail_alamat' => $request->detail_alamat,
                    'updated_at'    => now(),
                ];
                if ($imagePath) $financeUpdate['img_profile'] = $imagePath;

                DB::table('finances')->updateOrInsert(
                    ['user_id' => $user->id],
                    $financeUpdate
                );
            }

            try {
                Notifikasi::create([
                    'user_id'       => Auth::id(),
                    'perusahaan_id' => null,
                    'judul'         => 'Profil Berhasil Diperbarui',
                    'pesan'         => 'Profil Finance Anda berhasil diperbarui.',
                    'is_read'       => 0,
                    'expired_at'    => now()->addDays(7),
                ]);
            } catch (\Throwable $e) {
                // Notifikasi tidak wajib
            }

            return redirect()->route('finance.profile')->with('success', 'Profil Finance berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy_profile_finance($id = null)
    {
        $user = Auth::user();

        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        $user->avatar = null;
        $user->save();

        try {
            if (Schema::hasTable('finances')) {
                DB::table('finances')->where('user_id', $user->id)->update(['img_profile' => null]);
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return redirect()->route('finance.edit.profile')->with('success', 'Foto profil berhasil dihapus');
    }

    // =============================================
    // END PROFILE FINANCE
    // =============================================

    public function pageUnduhOmset()
    {
        return view('finance.page-unduh-omset');
    }


    public function verifikasi($id, Request $request)
    {
        $transaksi = CatatanCash::findOrFail($id);

        // Null check agar tidak crash jika user sudah dihapus
        $perusahaan = $transaksi->user?->perusahaan ?? null;
        $paket      = $transaksi->hargaPembayaran ?? null;
        $pelamar    = $transaksi->user?->pelamar ?? null;

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
                $total = $group->sum(fn($item) => (float)($item->total ?? ($item->hargaPembayaran?->harga ?? 0)));

                $first = $group->first();
                return [
                    'bulan' => Carbon::parse($first->created_at)->month,
                    'nama_bulan' => Carbon::parse($first->created_at)->translatedFormat('F'),
                    'tahun' => Carbon::parse($first->created_at)->year,
                    'total' => $total,
                ];
            })
            ->sortByDesc(fn($item) => $item['tahun'] . '-' . str_pad($item['bulan'], 2, '0', STR_PAD_LEFT))
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
                $total = $group->sum(fn($i) => (float)($i->total ?? ($i->hargaPembayaran?->harga ?? 0)));

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
