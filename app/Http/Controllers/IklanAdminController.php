<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Iklan;
use App\Models\Perusahaan;
use App\Models\LowonganPerusahaan;
use App\Models\CatatanKoin;
use App\Services\CoinTransactionService;
use App\Http\Requests\StoreAdminIklanRequest;
use Carbon\Carbon;

class IklanAdminController extends Controller
{
    /**
     * Daftar preset paket durasi iklan
     */
    public static function getPaketList()
    {
        return [
            1 => [
                'id' => 1,
                'nama' => 'Paket Bronze',
                'durasi' => 3,
                'badge' => 'Event Singkat',
                'deskripsi' => 'Publikasi cepat 3 hari untuk promosi mendesak.',
            ],
            2 => [
                'id' => 2,
                'nama' => 'Paket Silver',
                'durasi' => 7,
                'badge' => 'Populer',
                'deskripsi' => '1 minggu penuh di slot banner utama beranda.',
            ],
            3 => [
                'id' => 3,
                'nama' => 'Paket Gold',
                'durasi' => 14,
                'badge' => 'Rekomendasi',
                'deskripsi' => '2 pekan penayangan berkelanjutan dengan impresi tinggi.',
            ],
            4 => [
                'id' => 4,
                'nama' => 'Paket Platinum',
                'durasi' => 30,
                'badge' => 'Maksimal 1 Bulan',
                'deskripsi' => 'Sebulan penuh tampil terdepan untuk branding secara masif.',
            ],
        ];
    }

    /**
     * Tampilkan Halaman Moderasi Iklan untuk Admin
     */
    public function index(Request $request)
    {
        // Update otomatis status selesai jika masa tayang habis (khusus yang memiliki tanggal_selesai)
        Iklan::where('status', 'aktif')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<', now())
            ->update(['status' => 'selesai']);

        $search = trim($request->query('q', ''));

        // Jika status tidak disertakan di URL, default cari di seluruh status ('all') jika sedang mencari, atau 'menunggu' jika browsing biasa
        if ($request->has('status') && $request->query('status') !== '') {
            $statusFilter = $request->query('status');
        } else {
            $statusFilter = $search !== '' ? 'all' : 'menunggu';
        }

        $query = Iklan::with(['perusahaan', 'user']);

        if ($statusFilter && in_array($statusFilter, ['menunggu', 'aktif', 'ditolak', 'selesai'])) {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul_iklan', 'like', "%{$search}%")
                  ->orWhere('url_tujuan', 'like', "%{$search}%")
                  ->orWhereHas('perusahaan', function ($qp) use ($search) {
                      $qp->where('nama_perusahaan', 'like', "%{$search}%");
                  });

                if (stripos('areakerja', $search) !== false || stripos('official', $search) !== false || stripos('admin', $search) !== false) {
                    $q->orWhereNull('perusahaan_id');
                }
            });
        }

        $iklans = $query->latest()->paginate(10)->withQueryString();

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
     * Form Pasang Iklan untuk Admin
     */
    public function create()
    {
        $perusahaans = Perusahaan::select('id', 'nama_perusahaan', 'img_profile')->orderBy('nama_perusahaan')->get();
        return view('admin.iklan.create', compact('perusahaans'));
    }

    /**
     * Simpan Iklan Baru oleh Admin (Tanpa Koin & Tanpa Durasi - Langsung Aktif)
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_iklan'   => 'required|string|max:200',
            'gambar_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'url_tujuan'    => 'nullable|url|max:500',
            'perusahaan_id' => 'nullable|exists:perusahaans,id',
        ], [
            'judul_iklan.required'   => 'Judul iklan wajib diisi.',
            'gambar_banner.required' => 'Gambar banner iklan wajib diunggah.',
            'gambar_banner.image'    => 'File banner harus berupa gambar.',
            'gambar_banner.max'      => 'Ukuran gambar banner maksimal 3MB.',
            'url_tujuan.url'         => 'Format link tujuan harus berupa URL valid (cth: https://...).',
        ]);

        $path = $request->file('gambar_banner')->store('iklan-banners', 'public');

        try {
            Iklan::create([
                'perusahaan_id'   => $request->filled('perusahaan_id') ? $request->perusahaan_id : null,
                'judul_iklan'     => $request->judul_iklan,
                'gambar_banner'   => $path,
                'url_tujuan'      => $request->url_tujuan,
                'paket_nama'      => 'Iklan Admin',
                'durasi_hari'     => 0,
                'koin_terpotong'  => 0,
                'posisi'          => 'home_hero',
                'status'          => 'aktif', // Langsung aktif
                'tanggal_mulai'   => now(),
                'tanggal_selesai' => null, // Aktif seterusnya sampai dihapus manual
                'total_views'     => 0,
                'total_clicks'    => 0,
            ]);

            return redirect()->route('admin.iklan.index', ['status' => 'aktif'])
                ->with('success', 'Iklan berhasil dipasang dan langsung aktif tayang di Homepage.');
        } catch (\Throwable $e) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error("Gagal memasang iklan oleh admin: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan iklan: ' . $e->getMessage());
        }
    }

    /**
     * Form Edit Iklan untuk Admin
     */
    public function edit($id)
    {
        $iklan = Iklan::with('perusahaan')->findOrFail($id);
        $perusahaans = Perusahaan::select('id', 'nama_perusahaan', 'img_profile')->orderBy('nama_perusahaan')->get();
        return view('admin.iklan.edit', compact('iklan', 'perusahaans'));
    }

    /**
     * Update Iklan oleh Admin
     */
    public function update(Request $request, $id)
    {
        $iklan = Iklan::findOrFail($id);

        $request->validate([
            'judul_iklan'     => 'required|string|max:200',
            'gambar_banner'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'url_tujuan'      => 'nullable|url|max:500',
            'perusahaan_id'   => 'nullable|exists:perusahaans,id',
            'status'          => 'nullable|in:menunggu,aktif,ditolak,selesai',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'tambah_hari'     => 'nullable|integer|min:0',
            'tanpa_batas'     => 'nullable',
        ], [
            'judul_iklan.required' => 'Judul iklan wajib diisi.',
            'gambar_banner.image'  => 'File banner harus berupa gambar.',
            'gambar_banner.max'    => 'Ukuran gambar banner maksimal 3MB.',
            'url_tujuan.url'       => 'Format link tujuan harus berupa URL valid (cth: https://...).',
        ]);

        try {
            $data = [
                'judul_iklan'   => $request->judul_iklan,
                'url_tujuan'    => $request->filled('url_tujuan') ? $request->url_tujuan : null,
                'perusahaan_id' => $request->filled('perusahaan_id') ? $request->perusahaan_id : null,
            ];

            if ($request->filled('status')) {
                $data['status'] = $request->status;
                if ($request->status === 'aktif' && !$iklan->tanggal_mulai) {
                    $data['tanggal_mulai'] = now();
                }
            }

            // Manajemen Periode & Perpanjangan Masa Tayang
            if ($request->filled('tanggal_mulai')) {
                $data['tanggal_mulai'] = Carbon::parse($request->tanggal_mulai);
            }

            if ($request->has('tanpa_batas') && $request->tanpa_batas) {
                $data['tanggal_selesai'] = null;
                $data['durasi_hari'] = 0;
                if ($request->status === 'aktif' || $iklan->status === 'selesai') {
                    $data['status'] = 'aktif';
                }
            } elseif ($request->filled('tambah_hari') && (int)$request->tambah_hari > 0) {
                $tambah = (int)$request->tambah_hari;
                $base = ($iklan->tanggal_selesai && $iklan->tanggal_selesai->isFuture()) 
                    ? $iklan->tanggal_selesai->copy() 
                    : now();
                $data['tanggal_selesai'] = $base->addDays($tambah);
                $data['durasi_hari'] = ($iklan->durasi_hari ?: 0) + $tambah;
                $data['status'] = 'aktif';
            } elseif ($request->filled('tanggal_selesai')) {
                $tglSelesai = Carbon::parse($request->tanggal_selesai);
                $data['tanggal_selesai'] = $tglSelesai;
                if ($tglSelesai->isFuture() && ($iklan->status === 'selesai' || $request->status === 'aktif')) {
                    $data['status'] = 'aktif';
                }
                $tglMulai = isset($data['tanggal_mulai']) ? $data['tanggal_mulai'] : ($iklan->tanggal_mulai ?: now());
                $data['durasi_hari'] = max(0, $tglMulai->diffInDays($tglSelesai));
            }

            if ($request->hasFile('gambar_banner')) {
                $newPath = $request->file('gambar_banner')->store('iklan-banners', 'public');
                if ($iklan->gambar_banner && !str_starts_with($iklan->gambar_banner, 'http') && Storage::disk('public')->exists($iklan->gambar_banner)) {
                    Storage::disk('public')->delete($iklan->gambar_banner);
                }
                $data['gambar_banner'] = $newPath;
            }

            $iklan->update($data);

            return redirect()->route('admin.iklan.index')
                ->with('success', 'Iklan berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error("Gagal memperbarui iklan: " . $e->getMessage(), [
                'iklan_id' => $id,
                'trace'    => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui iklan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan Halaman Moderasi Iklan untuk Super Admin
     */
    public function indexSuper(Request $request)
    {
        // Update otomatis status selesai jika masa tayang habis (khusus yang memiliki tanggal_selesai)
        Iklan::where('status', 'aktif')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<', now())
            ->update(['status' => 'selesai']);

        $search = trim($request->query('q', ''));

        // Jika status tidak disertakan di URL, default cari di seluruh status ('all') jika sedang mencari, atau 'menunggu' jika browsing biasa
        if ($request->has('status') && $request->query('status') !== '') {
            $statusFilter = $request->query('status');
        } else {
            $statusFilter = $search !== '' ? 'all' : 'menunggu';
        }

        $query = Iklan::with(['perusahaan', 'user']);

        if ($statusFilter && in_array($statusFilter, ['menunggu', 'aktif', 'ditolak', 'selesai'])) {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul_iklan', 'like', "%{$search}%")
                  ->orWhere('url_tujuan', 'like', "%{$search}%")
                  ->orWhere('paket_nama', 'like', "%{$search}%")
                  ->orWhereHas('perusahaan', function ($qp) use ($search) {
                      $qp->where('nama_perusahaan', 'like', "%{$search}%");
                  });

                // Cari iklan official AreaKerja (tanpa perusahaan) — perbaikan: cek apakah $search mengandung kata kunci, bukan sebaliknya
                if (
                    stripos($search, 'areakerja') !== false ||
                    stripos($search, 'official') !== false ||
                    stripos($search, 'admin') !== false
                ) {
                    $q->orWhereNull('perusahaan_id');
                }
            });
        }

        $iklans = $query->latest()->paginate(10)->withQueryString();

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
     * Form Pasang Iklan untuk Super Admin
     */
    public function createSuper()
    {
        $perusahaans = Perusahaan::select('id', 'nama_perusahaan', 'img_profile')->orderBy('nama_perusahaan')->get();
        return view('super_admin.iklan.create', compact('perusahaans'));
    }

    /**
     * Simpan Iklan Baru oleh Super Admin (Tanpa Koin & Tanpa Durasi - Langsung Aktif)
     */
    public function storeSuper(Request $request)
    {
        $request->validate([
            'judul_iklan'   => 'required|string|max:200',
            'gambar_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'url_tujuan'    => 'nullable|url|max:500',
            'perusahaan_id' => 'nullable|exists:perusahaans,id',
        ], [
            'judul_iklan.required'   => 'Judul iklan wajib diisi.',
            'gambar_banner.required' => 'Gambar banner iklan wajib diunggah.',
            'gambar_banner.image'    => 'File banner harus berupa gambar.',
            'gambar_banner.max'      => 'Ukuran gambar banner maksimal 3MB.',
            'url_tujuan.url'         => 'Format link tujuan harus berupa URL valid (cth: https://...).',
        ]);

        $path = $request->file('gambar_banner')->store('iklan-banners', 'public');

        try {
            Iklan::create([
                'perusahaan_id'   => $request->filled('perusahaan_id') ? $request->perusahaan_id : null,
                'judul_iklan'     => $request->judul_iklan,
                'gambar_banner'   => $path,
                'url_tujuan'      => $request->url_tujuan,
                'paket_nama'      => 'Iklan Super Admin',
                'durasi_hari'     => 0,
                'koin_terpotong'  => 0,
                'posisi'          => 'home_hero',
                'status'          => 'aktif', // Langsung aktif
                'tanggal_mulai'   => now(),
                'tanggal_selesai' => null, // Aktif seterusnya sampai dihapus manual
                'total_views'     => 0,
                'total_clicks'    => 0,
            ]);

            return redirect()->route('superadmin.iklan.index', ['status' => 'aktif'])
                ->with('success', 'Iklan berhasil dipasang dan langsung aktif tayang di Homepage.');
        } catch (\Throwable $e) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error("Gagal memasang iklan oleh super admin: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan iklan: ' . $e->getMessage());
        }
    }

    /**
     * Form Edit Iklan untuk Super Admin
     */
    public function editSuper($id)
    {
        $iklan = Iklan::with('perusahaan')->findOrFail($id);
        $perusahaans = Perusahaan::select('id', 'nama_perusahaan', 'img_profile')->orderBy('nama_perusahaan')->get();
        return view('super_admin.iklan.edit', compact('iklan', 'perusahaans'));
    }

    /**
     * Update Iklan oleh Super Admin
     */
    public function updateSuper(Request $request, $id)
    {
        $iklan = Iklan::findOrFail($id);

        $request->validate([
            'judul_iklan'     => 'required|string|max:200',
            'gambar_banner'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'url_tujuan'      => 'nullable|url|max:500',
            'perusahaan_id'   => 'nullable|exists:perusahaans,id',
            'status'          => 'nullable|in:menunggu,aktif,ditolak,selesai',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'tambah_hari'     => 'nullable|integer|min:0',
            'tanpa_batas'     => 'nullable',
        ], [
            'judul_iklan.required' => 'Judul iklan wajib diisi.',
            'gambar_banner.image'  => 'File banner harus berupa gambar.',
            'gambar_banner.max'    => 'Ukuran gambar banner maksimal 3MB.',
            'url_tujuan.url'       => 'Format link tujuan harus berupa URL valid (cth: https://...).',
        ]);

        try {
            $data = [
                'judul_iklan'   => $request->judul_iklan,
                'url_tujuan'    => $request->filled('url_tujuan') ? $request->url_tujuan : null,
                'perusahaan_id' => $request->filled('perusahaan_id') ? $request->perusahaan_id : null,
            ];

            if ($request->filled('status')) {
                $data['status'] = $request->status;
                if ($request->status === 'aktif' && !$iklan->tanggal_mulai) {
                    $data['tanggal_mulai'] = now();
                }
            }

            // Manajemen Periode & Perpanjangan Masa Tayang
            if ($request->filled('tanggal_mulai')) {
                $data['tanggal_mulai'] = Carbon::parse($request->tanggal_mulai);
            }

            if ($request->has('tanpa_batas') && $request->tanpa_batas) {
                $data['tanggal_selesai'] = null;
                $data['durasi_hari'] = 0;
                if ($request->status === 'aktif' || $iklan->status === 'selesai') {
                    $data['status'] = 'aktif';
                }
            } elseif ($request->filled('tambah_hari') && (int)$request->tambah_hari > 0) {
                $tambah = (int)$request->tambah_hari;
                $base = ($iklan->tanggal_selesai && $iklan->tanggal_selesai->isFuture()) 
                    ? $iklan->tanggal_selesai->copy() 
                    : now();
                $data['tanggal_selesai'] = $base->addDays($tambah);
                $data['durasi_hari'] = ($iklan->durasi_hari ?: 0) + $tambah;
                $data['status'] = 'aktif';
            } elseif ($request->filled('tanggal_selesai')) {
                $tglSelesai = Carbon::parse($request->tanggal_selesai);
                $data['tanggal_selesai'] = $tglSelesai;
                if ($tglSelesai->isFuture() && ($iklan->status === 'selesai' || $request->status === 'aktif')) {
                    $data['status'] = 'aktif';
                }
                $tglMulai = isset($data['tanggal_mulai']) ? $data['tanggal_mulai'] : ($iklan->tanggal_mulai ?: now());
                $data['durasi_hari'] = max(0, $tglMulai->diffInDays($tglSelesai));
            }

            if ($request->hasFile('gambar_banner')) {
                $newPath = $request->file('gambar_banner')->store('iklan-banners', 'public');
                if ($iklan->gambar_banner && !str_starts_with($iklan->gambar_banner, 'http') && Storage::disk('public')->exists($iklan->gambar_banner)) {
                    Storage::disk('public')->delete($iklan->gambar_banner);
                }
                $data['gambar_banner'] = $newPath;
            }

            $iklan->update($data);

            return redirect()->route('superadmin.iklan.index')
                ->with('success', 'Iklan berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error("Gagal memperbarui iklan super admin: " . $e->getMessage(), [
                'iklan_id' => $id,
                'trace'    => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui iklan: ' . $e->getMessage());
        }
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

    /**
     * Hapus Iklan (Admin)
     */
    public function destroy($id)
    {
        $iklan = Iklan::findOrFail($id);

        if ($iklan->gambar_banner && Storage::disk('public')->exists($iklan->gambar_banner)) {
            Storage::disk('public')->delete($iklan->gambar_banner);
        }

        $iklan->delete();

        return redirect()->back()->with('success', 'Iklan berhasil dihapus secara permanen.');
    }

    /**
     * Hapus Iklan (Super Admin)
     */
    public function destroySuper($id)
    {
        return $this->destroy($id);
    }

    /**
     * Perpanjang Masa Tayang Iklan (Admin)
     */
    public function perpanjang(Request $request, $id)
    {
        $iklan = Iklan::findOrFail($id);

        $request->validate([
            'durasi_tambah'   => 'nullable|integer|min:1',
            'tanggal_selesai' => 'nullable|date',
            'tanpa_batas'     => 'nullable',
        ]);

        try {
            if ($request->has('tanpa_batas') && $request->tanpa_batas) {
                $iklan->tanggal_selesai = null;
                $iklan->durasi_hari = 0;
                $iklan->status = 'aktif';
                if (!$iklan->tanggal_mulai) {
                    $iklan->tanggal_mulai = now();
                }
                $iklan->save();

                return redirect()->back()->with('success', 'Masa tayang iklan "' . $iklan->judul_iklan . '" berhasil diubah menjadi Tanpa Batas (Aktif seterusnya).');
            }

            if ($request->filled('durasi_tambah')) {
                $hari = (int) $request->durasi_tambah;
                $base = ($iklan->tanggal_selesai && $iklan->tanggal_selesai->isFuture())
                    ? $iklan->tanggal_selesai->copy()
                    : now();

                $iklan->tanggal_selesai = $base->addDays($hari);
                $iklan->durasi_hari = ($iklan->durasi_hari ?: 0) + $hari;
                $iklan->status = 'aktif';
                if (!$iklan->tanggal_mulai) {
                    $iklan->tanggal_mulai = now();
                }
                $iklan->save();

                return redirect()->back()->with('success', 'Masa tayang iklan "' . $iklan->judul_iklan . '" berhasil diperpanjang ' . $hari . ' hari (s/d ' . $iklan->tanggal_selesai->translatedFormat('d M Y') . ').');
            }

            if ($request->filled('tanggal_selesai')) {
                $tgl = Carbon::parse($request->tanggal_selesai);
                $iklan->tanggal_selesai = $tgl;
                if ($tgl->isFuture()) {
                    $iklan->status = 'aktif';
                }
                if (!$iklan->tanggal_mulai) {
                    $iklan->tanggal_mulai = now();
                }
                if ($iklan->tanggal_mulai) {
                    $iklan->durasi_hari = max(0, $iklan->tanggal_mulai->diffInDays($tgl));
                }
                $iklan->save();

                return redirect()->back()->with('success', 'Masa tayang iklan "' . $iklan->judul_iklan . '" berhasil diperbarui s/d ' . $iklan->tanggal_selesai->translatedFormat('d M Y') . '.');
            }

            return redirect()->back()->with('error', 'Silakan pilih durasi perpanjangan atau tanggal selesai yang valid.');
        } catch (\Throwable $e) {
            Log::error("Gagal memperpanjang masa tayang iklan: " . $e->getMessage(), [
                'iklan_id' => $id,
                'trace'    => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memperpanjang iklan: ' . $e->getMessage());
        }
    }

    /**
     * Perpanjang Masa Tayang Iklan (Super Admin)
     */
    public function perpanjangSuper(Request $request, $id)
    {
        return $this->perpanjang($request, $id);
    }
}

