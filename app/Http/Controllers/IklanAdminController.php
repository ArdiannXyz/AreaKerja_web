<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Iklan;
use App\Models\CatatanKoin;
use App\Services\CoinTransactionService;

class IklanAdminController extends Controller
{
    /**
     * Tampilkan Halaman Moderasi Iklan untuk Admin
     */
    public function index(Request $request)
    {
        // Update otomatis status selesai jika masa tayang habis
        Iklan::where('status', 'aktif')
            ->where('tanggal_selesai', '<', now())
            ->update(['status' => 'selesai']);

        $statusFilter = $request->query('status', 'menunggu');
        $search = $request->query('q');

        $query = Iklan::with('perusahaan');

        if ($statusFilter && in_array($statusFilter, ['menunggu', 'aktif', 'ditolak', 'selesai'])) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_iklan', 'like', "%{$search}%")
                  ->orWhereHas('perusahaan', function ($qp) use ($search) {
                      $qp->where('nama_perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        $iklans = $query->latest()->paginate(10);

        // Hitung count per status untuk tab badge
        $countMenunggu = Iklan::where('status', 'menunggu')->count();
        $countAktif = Iklan::where('status', 'aktif')->count();
        $countDitolak = Iklan::where('status', 'ditolak')->count();
        $countSelesai = Iklan::where('status', 'selesai')->count();
        $countSemua = Iklan::count();

        return view('admin.iklan.index', compact(
            'iklans',
            'statusFilter',
            'search',
            'countMenunggu',
            'countAktif',
            'countDitolak',
            'countSelesai',
            'countSemua'
        ));
    }

    /**
     * Tampilkan Halaman Moderasi Iklan untuk Super Admin
     */
    public function indexSuper(Request $request)
    {
        // Update otomatis status selesai jika masa tayang habis
        Iklan::where('status', 'aktif')
            ->where('tanggal_selesai', '<', now())
            ->update(['status' => 'selesai']);

        $statusFilter = $request->query('status', 'menunggu');
        $search = $request->query('q');

        $query = Iklan::with('perusahaan');

        if ($statusFilter && in_array($statusFilter, ['menunggu', 'aktif', 'ditolak', 'selesai'])) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_iklan', 'like', "%{$search}%")
                  ->orWhereHas('perusahaan', function ($qp) use ($search) {
                      $qp->where('nama_perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        $iklans = $query->latest()->paginate(10);

        // Hitung count per status untuk tab badge
        $countMenunggu = Iklan::where('status', 'menunggu')->count();
        $countAktif = Iklan::where('status', 'aktif')->count();
        $countDitolak = Iklan::where('status', 'ditolak')->count();
        $countSelesai = Iklan::where('status', 'selesai')->count();
        $countSemua = Iklan::count();

        return view('super_admin.iklan.index', compact(
            'iklans',
            'statusFilter',
            'search',
            'countMenunggu',
            'countAktif',
            'countDitolak',
            'countSelesai',
            'countSemua'
        ));
    }

    /**
     * Setujui Iklan (Approve)
     */
    public function approve($id)
    {
        $iklan = Iklan::findOrFail($id);

        if ($iklan->status === 'aktif') {
            return redirect()->back()->with('info', 'Iklan ini sudah berstatus aktif.');
        }

        $now = now();
        $iklan->status = 'aktif';
        $iklan->tanggal_mulai = $now;
        $iklan->tanggal_selesai = (clone $now)->addDays($iklan->durasi_hari);
        $iklan->alasan_penolakan = null;
        $iklan->save();

        return redirect()->back()->with('success', 'Iklan berhasil disetujui dan langsung tayang di Homepage selama ' . $iklan->durasi_hari . ' hari.');
    }

    /**
     * Setujui Iklan untuk Super Admin
     */
    public function approveSuper($id)
    {
        return $this->approve($id);
    }

    /**
     * Tolak Iklan & Refund Koin ke Perusahaan (Reject)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ], [
            'alasan_penolakan.required' => 'Wajib menyertakan alasan penolakan agar perusahaan dapat merevisi banner mereka.',
        ]);

        $iklan = Iklan::with('perusahaan')->findOrFail($id);

        try {
            DB::transaction(function () use ($iklan, $request) {
                // Hanya refund jika status sebelumnya adalah 'menunggu'
                if ($iklan->status === 'menunggu') {
                    $perusahaan = $iklan->perusahaan;

                    if ($perusahaan && $iklan->koin_terpotong > 0) {
                        $pengirim = (Auth::user()?->role === 'super_admin') ? 'Super Admin AreaKerja' : 'Admin AreaKerja';

                        $coinService = app(CoinTransactionService::class);
                        $coinService->refund(
                            $perusahaan,
                            $iklan->koin_terpotong,
                            'Refund Koin: Iklan "' . Str::limit($iklan->judul_iklan, 30) . '" Ditolak',
                            'Refund Iklan',
                            'REFUND-' . strtoupper(Str::random(8)),
                            $pengirim
                        );
                    }
                }

                $iklan->status = 'ditolak';
                $iklan->alasan_penolakan = $request->alasan_penolakan;
                $iklan->save();
            });

            return redirect()->back()->with('success', 'Iklan ditolak. Saldo ' . number_format($iklan->koin_terpotong, 0, ',', '.') . ' koin telah berhasil di-refund kembali ke perusahaan.');
        } catch (\Throwable $e) {
            Log::error("Gagal menolak dan me-refund iklan: " . $e->getMessage(), [
                'iklan_id' => $id,
                'trace'    => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses penolakan iklan.');
        }
    }

    /**
     * Tolak Iklan untuk Super Admin
     */
    public function rejectSuper(Request $request, $id)
    {
        return $this->reject($request, $id);
    }
}
