<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Iklan;
use App\Models\CatatanKoin;

class IklanAdminController extends Controller
{
    /**
     * Tampilkan Halaman Moderasi Iklan
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

        // Hanya refund jika status sebelumnya adalah 'menunggu'
        if ($iklan->status === 'menunggu') {
            $perusahaan = $iklan->perusahaan;

            if ($perusahaan) {
                // Refund koin
                $perusahaan->koin_perusahaan += $iklan->koin_terpotong;
                $perusahaan->save();

                // Catat transaksi refund koin
                CatatanKoin::create([
                    'user_id' => $perusahaan->user_id,
                    'no_referensi' => 'REFUND-' . strtoupper(Str::random(8)),
                    'pesanan' => 'Refund Koin: Iklan "' . Str::limit($iklan->judul_iklan, 30) . '" Ditolak',
                    'dari' => 'Admin AreaKerja',
                    'sumber_dana' => 'Refund Iklan',
                    'total' => '+' . $iklan->koin_terpotong,
                ]);
            }
        }

        $iklan->status = 'ditolak';
        $iklan->alasan_penolakan = $request->alasan_penolakan;
        $iklan->save();

        return redirect()->back()->with('success', 'Iklan ditolak. Saldo ' . number_format($iklan->koin_terpotong, 0, ',', '.') . ' koin telah berhasil di-refund kembali ke perusahaan.');
    }
}
