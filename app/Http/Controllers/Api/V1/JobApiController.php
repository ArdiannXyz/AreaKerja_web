<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LowonganPerusahaan;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class JobApiController extends Controller
{
    /**
     * Get list of active published jobs with search, category, location, and type filters.
     */
    public function index(Request $request)
    {
        $query = LowonganPerusahaan::with(['perusahaan'])
            ->whereNotNull('published_at')
            ->where('expired_at', '>', now());

        // Search by title or description
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        // Filter category
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter job type (Full-time, Part-time, etc.)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter location
        if ($request->filled('lokasi')) {
            $loc = $request->lokasi;
            $query->where('alamat', 'like', "%{$loc}%");
        }

        // Sort by Boosted first, then Recommendation, then Latest
        $query->orderByRaw("
            CASE
                WHEN boosted_until IS NOT NULL AND boosted_until > NOW() THEN 0
                WHEN rekomendasi IS NOT NULL THEN 1
                ELSE 2
            END
        ")->latest();

        $jobs = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $jobs,
        ]);
    }

    /**
     * Get job details by ID or slug.
     */
    public function show($id)
    {
        $job = LowonganPerusahaan::with(['perusahaan'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $job,
        ]);
    }

    /**
     * Candidate applies to a job.
     */
    public function apply(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role !== 'pelamar' || !$user->pelamar) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya akun pelamar yang dapat melamar pekerjaan.',
            ], 403);
        }

        $pelamar = $user->pelamar;
        $job = LowonganPerusahaan::findOrFail($id);

        // Check if already applied (status other than 'saved')
        $existing = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $job->id)
            ->where('status', '!=', 'saved')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah melamar ke lowongan ini.',
                'data'    => $existing,
            ], 422);
        }

        // If previously saved, update status to pending
        $saved = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $job->id)
            ->where('status', 'saved')
            ->first();

        if ($saved) {
            $saved->update(['status' => 'pending']);
            $application = $saved;
        } else {
            $application = PelamarLowongan::create([
                'pelamar_id'  => $pelamar->id,
                'lowongan_id' => $job->id,
                'status'      => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lamaran berhasil dikirim ke perusahaan.',
            'data'    => $application,
        ], 201);
    }

    /**
     * Get list of jobs applied by current candidate.
     */
    public function myApplications(Request $request)
    {
        $user = $request->user();
        if (!$user->pelamar) {
            return response()->json(['success' => false, 'message' => 'Profil pelamar tidak ditemukan.'], 404);
        }

        $applications = PelamarLowongan::with(['lowongan.perusahaan'])
            ->where('pelamar_id', $user->pelamar->id)
            ->where('status', '!=', 'saved')
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $applications,
        ]);
    }

    /**
     * Bookmark / Save or Unsave a job.
     */
    public function toggleSaveJob(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->pelamar) {
            return response()->json(['success' => false, 'message' => 'Profil pelamar tidak ditemukan.'], 404);
        }

        $pelamar = $user->pelamar;
        $job = LowonganPerusahaan::findOrFail($id);

        $savedRecord = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $job->id)
            ->where('status', 'saved')
            ->first();

        if ($savedRecord) {
            $savedRecord->delete();
            return response()->json([
                'success' => true,
                'is_saved' => false,
                'message' => 'Lowongan berhasil dihapus dari daftar simpan.',
            ]);
        }

        // Create saved record
        $savedRecord = PelamarLowongan::create([
            'pelamar_id'  => $pelamar->id,
            'lowongan_id' => $job->id,
            'status'      => 'saved',
        ]);

        return response()->json([
            'success' => true,
            'is_saved' => true,
            'message' => 'Lowongan berhasil disimpan.',
            'data'    => $savedRecord,
        ]);
    }

    /**
     * Get list of saved/bookmarked jobs.
     */
    public function getSavedJobs(Request $request)
    {
        $user = $request->user();
        if (!$user->pelamar) {
            return response()->json(['success' => false, 'message' => 'Profil pelamar tidak ditemukan.'], 404);
        }

        $savedJobs = PelamarLowongan::with(['lowongan.perusahaan'])
            ->where('pelamar_id', $user->pelamar->id)
            ->where('status', 'saved')
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $savedJobs,
        ]);
    }

    /**
     * Get list of filter options (categories, job types, popular locations).
     */
    public function metaFilters()
    {
        $categories = LowonganPerusahaan::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        $types = LowonganPerusahaan::whereNotNull('jenis')
            ->where('jenis', '!=', '')
            ->distinct()
            ->pluck('jenis');

        return response()->json([
            'success' => true,
            'data'    => [
                'categories' => $categories,
                'job_types'  => $types->isEmpty() ? ['Full-Time', 'Part-Time', 'Freelance', 'Internship', 'Kontrak'] : $types,
            ],
        ]);
    }

    /**
     * Public Banners for mobile home slider.
     */
    public function banners()
    {
        $banners = [
            [
                'id'         => 1,
                'title'      => 'Siap Kerja Bersama AreaKerja',
                'subtitle'   => 'Temukan ribuan lowongan kerja terpercaya di seluruh Indonesia',
                'image_url'  => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop',
                'action_url' => '/jobs',
            ],
            [
                'id'         => 2,
                'title'      => 'Program Kandidat Terpilih',
                'subtitle'   => 'Tingkatkan peluang dipanggil wawancara hingga 5x lebih cepat',
                'image_url'  => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1200&auto=format&fit=crop',
                'action_url' => '/kandidat',
            ],
            [
                'id'         => 3,
                'title'      => 'Tips Lolos Interview Kerja',
                'subtitle'   => 'Pelajari panduan dan trik menghadapi HRD profesional',
                'image_url'  => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1200&auto=format&fit=crop',
                'action_url' => '/tips-kerja',
            ],
        ];

        return response()->json([
            'success' => true,
            'data'    => $banners,
        ]);
    }

    /**
     * Public Company Profile
     */
    public function companyDetail($id)
    {
        $company = Perusahaan::withCount(['pasanglowongan' => function ($q) {
            $q->whereNotNull('published_at')->where('expired_at', '>', now());
        }])
        ->where('id', $id)
        ->orWhere('slug', $id)
        ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $company,
        ]);
    }

    /**
     * Public Company Active Jobs
     */
    public function companyJobs(Request $request, $id)
    {
        $company = Perusahaan::where('id', $id)->orWhere('slug', $id)->firstOrFail();

        $jobs = $company->pasanglowongan()
            ->whereNotNull('published_at')
            ->where('expired_at', '>', now())
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => [
                'company' => $company,
                'jobs'    => $jobs,
            ],
        ]);
    }

    /**
     * Master Data: Locations / Provinces / Cities
     */
    public function getLocations()
    {
        $provinces = [
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara'],
            'Jawa Barat'  => ['Bandung', 'Bekasi', 'Bogor', 'Depok', 'Cimahi', 'Cirebon', 'Sukabumi', 'Tasikmalaya'],
            'Jawa Timur'  => ['Surabaya', 'Malang', 'Sidoarjo', 'Gresik', 'Kediri', 'Madiun', 'Jember', 'Banyuwangi'],
            'Jawa Tengah' => ['Semarang', 'Surakarta (Solo)', 'Yogyakarta', 'Magelang', 'Pekalongan', 'Tegal', 'Purwokerto'],
            'Banten'      => ['Tangerang', 'Tangerang Selatan', 'Serang', 'Cilegon'],
            'Bali'        => ['Denpasar', 'Badung', 'Gianyar', 'Tabanan'],
            'Sumatera Utara' => ['Medan', 'Binjai', 'Pematangsiantar', 'Deli Serdang'],
            'Sumatera Selatan' => ['Palembang', 'Prabumulih', 'Lubuklinggau'],
            'Sulawesi Selatan' => ['Makassar', 'Gowa', 'Maros', 'Parepare'],
            'Kalimantan Timur' => ['Samarinda', 'Balikpapan', 'Bontang'],
        ];

        return response()->json([
            'success' => true,
            'data'    => $provinces,
        ]);
    }

    public function getProvinces()
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'DI Yogyakarta',
                'Banten', 'Bali', 'Sumatera Utara', 'Sumatera Selatan', 'Riau',
                'Lampung', 'Kalimantan Timur', 'Kalimantan Barat', 'Sulawesi Selatan',
                'Sulawesi Utara', 'Nusa Tenggara Barat', 'Papua'
            ],
        ]);
    }

    public function getCities(Request $request)
    {
        $cities = [
            'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara',
            'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang', 'Yogyakarta',
            'Tangerang', 'Tangerang Selatan', 'Bekasi', 'Depok', 'Bogor', 'Malang', 'Sidoarjo',
            'Denpasar', 'Balikpapan', 'Samarinda', 'Batam', 'Pekanbaru', 'Bandar Lampung'
        ];

        return response()->json([
            'success' => true,
            'data'    => $cities,
        ]);
    }

    /**
     * Master Data: FAQs
     */
    public function faqs()
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara melamar pekerjaan di AreaKerja?',
                'answer'   => 'Buat akun pelamar, lengkapi profil biodata serta riwayat pendidikan & pengalaman kerja, lalu pilih lowongan yang diminati dan klik tombol "Kirim Lamaran".',
            ],
            [
                'question' => 'Apa keuntungan menjadi Kandidat Terpilih?',
                'answer'   => 'Profil Anda akan masuk ke halaman Talent Hunter sehingga perusahaan dapat menemukan dan mengirim tawaran interview langsung ke Anda.',
            ],
            [
                'question' => 'Bagaimana perusahaan memasang lowongan kerja?',
                'answer'   => 'Masuk dengan akun Perusahaan, pastikan saldo koin mencukupi, lalu klik Pasang Lowongan dan lengkapi rincian persyaratan pekerjaan.',
            ],
            [
                'question' => 'Apakah melamar pekerjaan di AreaKerja dipungut biaya?',
                'answer'   => 'Tidak. Seluruh pelamar dapat mencari dan melamar lowongan kerja secara 100% gratis tanpa dipungut biaya apapun.',
            ],
        ];

        return response()->json([
            'success' => true,
            'data'    => $faqs,
        ]);
    }

    /**
     * Master Data: Terms & Privacy Policy
     */
    public function legalContent()
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'terms_of_service' => "Syarat dan Ketentuan Penggunaan AreaKerja:\n1. Pengguna wajib memberikan informasi yang akurat dan dapat dipertanggungjawabkan.\n2. Dilarang memasang lowongan kerja palsu, penipuan, atau mengandung unsur SARA.\n3. AreaKerja berhak menonaktifkan akun yang melanggar aturan.",
                'privacy_policy'   => "Kebijakan Privasi AreaKerja:\n1. Kami menjaga kerahasiaan data pribadi pengguna dan tidak akan menjualnya kepada pihak ketiga.\n2. Informasi profil pelamar hanya dibagikan kepada perusahaan yang dilamar atau saat pelamar mengaktifkan status Kandidat Terpilih.\n3. Pengguna memiliki hak penuh untuk meminta penghapusan akun dan data terkait kapan saja.",
            ],
        ]);
    }

    /**
     * Check App Version for In-App Update / Force Update
     */
    public function checkAppVersion(Request $request)
    {
        $currentVersion = $request->query('version', '1.0.0');

        return response()->json([
            'success' => true,
            'data'    => [
                'latest_version'     => '1.0.0',
                'minimum_version'    => '1.0.0',
                'force_update'       => false,
                'release_notes'      => 'Versi perdana aplikasi AreaKerja Mobile.',
                'play_store_url'     => 'https://play.google.com/store/apps/details?id=com.areakerja.app',
                'app_store_url'      => 'https://apps.apple.com/app/id6400000000',
            ],
        ]);
    }
}
