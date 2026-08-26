<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyApiController extends Controller
{
    /**
     * Dashboard statistics for company.
     */
    public function dashboardSummary(Request $request)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat mengakses data ini.'], 403);
        }

        $totalJobs = $perusahaan->pasanglowongan()->count();
        $activeJobs = $perusahaan->pasanglowongan()
            ->whereNotNull('published_at')
            ->where('expired_at', '>', now())
            ->count();

        $totalApplicants = PelamarLowongan::whereHas('lowongan', function ($q) use ($perusahaan) {
            $q->where('perusahaan_id', $perusahaan->id);
        })->where('status', '!=', 'saved')->count();

        $pendingApplicants = PelamarLowongan::whereHas('lowongan', function ($q) use ($perusahaan) {
            $q->where('perusahaan_id', $perusahaan->id);
        })->where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'perusahaan'          => $perusahaan,
                'coin_balance'        => $perusahaan->koin_perusahaan,
                'total_jobs'          => $totalJobs,
                'active_jobs'         => $activeJobs,
                'total_applicants'    => $totalApplicants,
                'pending_applicants'  => $pendingApplicants,
            ],
        ]);
    }

    /**
     * Get Company Profile
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Profil perusahaan tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $perusahaan,
        ]);
    }

    /**
     * Update Company Profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Profil perusahaan tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'nama_perusahaan'     => 'nullable|string|max:255',
            'legalitas'           => 'nullable|string|max:100',
            'website_perusahaan'  => 'nullable|string|max:255',
            'telepon_perusahaan'  => 'nullable|string|max:30',
            'whatsapp'            => 'nullable|string|max:30',
            'deskripsi'           => 'nullable|string',
            'visi'                => 'nullable|string',
            'misi'                => 'nullable|string',
            'alamat'              => 'nullable|string|max:255',
            'kota'                => 'nullable|string|max:100',
            'provinsi'            => 'nullable|string|max:100',
        ]);

        $perusahaan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil perusahaan berhasil diperbarui.',
            'data'    => $perusahaan->fresh(),
        ]);
    }

    /**
     * Upload Logo / Gambar Perusahaan
     */
    public function uploadLogo(Request $request)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;

        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Profil perusahaan tidak ditemukan.'], 404);
        }

        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($perusahaan->img_profile && Storage::disk('public')->exists($perusahaan->img_profile)) {
            Storage::disk('public')->delete($perusahaan->img_profile);
        }

        $path = $request->file('logo')->store('img/perusahaan/profile', 'public');
        $perusahaan->img_profile = $path;
        $perusahaan->save();

        return response()->json([
            'success' => true,
            'message' => 'Logo perusahaan berhasil diunggah.',
            'data'    => [
                'logo_path' => $path,
                'logo_url'  => asset('storage/' . $path),
            ],
        ]);
    }

    /**
     * Get company's posted jobs (Active & Drafts).
     */
    public function myJobs(Request $request)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;
        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat mengakses data ini.'], 403);
        }

        $jobs = $perusahaan->pasanglowongan()->withCount(['pelamar' => function ($q) {
            $q->where('status', '!=', 'saved');
        }])->latest()->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => [
                'coin_balance' => $perusahaan->koin_perusahaan,
                'jobs'         => $jobs,
            ],
        ]);
    }

    /**
     * Get specific company job by ID (for editing).
     */
    public function showJob(Request $request, $id)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;
        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat mengakses data ini.'], 403);
        }

        $job = $perusahaan->pasanglowongan()->where('id', $id)->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $job,
        ]);
    }

    /**
     * Create a new job opening from company app.
     */
    public function storeJob(Request $request)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;
        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat memasang lowongan.'], 403);
        }

        $validated = $request->validate([
            'nama'             => 'required|string|max:255',
            'jenis'            => 'required|string',
            'kategori'         => 'required|string',
            'gaji_awal'        => 'nullable|numeric',
            'gaji_akhir'       => 'nullable|numeric',
            'label_gaji'       => 'nullable|string',
            'deskripsi'        => 'required|string',
            'alamat'           => 'required|string',
            'batas_lamaran'    => 'required|date',
            'syarat_pekerjaan' => 'nullable|string',
            'tanggung_jawab'   => 'nullable|string',
            'benefit'          => 'nullable|string',
        ]);

        $validated['perusahaan_id'] = $perusahaan->id;
        $validated['slug'] = Str::slug($request->nama) . '-' . Str::random(6);
        $validated['published_at'] = now();
        $validated['expired_at'] = $request->batas_lamaran ? \Carbon\Carbon::parse($request->batas_lamaran)->endOfDay() : now()->addDays(30);

        $job = LowonganPerusahaan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil dipublikasikan.',
            'data'    => $job,
        ], 201);
    }

    /**
     * Update an existing job opening.
     */
    public function updateJob(Request $request, $id)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;
        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat mengedit lowongan.'], 403);
        }

        $job = $perusahaan->pasanglowongan()->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'nama'             => 'sometimes|required|string|max:255',
            'jenis'            => 'sometimes|required|string',
            'kategori'         => 'sometimes|required|string',
            'gaji_awal'        => 'nullable|numeric',
            'gaji_akhir'       => 'nullable|numeric',
            'label_gaji'       => 'nullable|string',
            'deskripsi'        => 'sometimes|required|string',
            'alamat'           => 'sometimes|required|string',
            'batas_lamaran'    => 'nullable|date',
            'syarat_pekerjaan' => 'nullable|string',
            'tanggung_jawab'   => 'nullable|string',
            'benefit'          => 'nullable|string',
        ]);

        if (!empty($request->batas_lamaran)) {
            $validated['expired_at'] = \Carbon\Carbon::parse($request->batas_lamaran)->endOfDay();
        }

        $job->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil diperbarui.',
            'data'    => $job,
        ]);
    }

    /**
     * Delete a job opening.
     */
    public function deleteJob(Request $request, $id)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;
        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat menghapus lowongan.'], 403);
        }

        $job = $perusahaan->pasanglowongan()->where('id', $id)->firstOrFail();
        $job->pelamar()->detach();
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil dihapus.',
        ]);
    }

    /**
     * Toggle job active/closed status.
     */
    public function toggleJobStatus(Request $request, $id)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;
        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat mengubah status lowongan.'], 403);
        }

        $job = $perusahaan->pasanglowongan()->where('id', $id)->firstOrFail();

        if ($job->expired_at && $job->expired_at->isPast()) {
            // Re-activate for 30 days
            $job->update([
                'expired_at'   => now()->addDays(30),
                'published_at' => now(),
            ]);
            $msg = 'Lowongan berhasil diaktifkan kembali.';
        } else {
            // Close / Expire immediately
            $job->update([
                'expired_at' => now()->subMinute(),
            ]);
            $msg = 'Lowongan berhasil ditutup.';
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'data'    => $job->fresh(),
        ]);
    }

    /**
     * Get candidates who applied for a specific job.
     */
    public function jobApplicants(Request $request, $jobId)
    {
        $user = $request->user();
        $perusahaan = $user->perusahaan;

        $job = $perusahaan->pasanglowongan()->where('id', $jobId)->firstOrFail();

        $applicants = PelamarLowongan::with([
            'pelamar.riwayat_pendidikan',
            'pelamar.pengalaman_kerja',
            'pelamar.user'
        ])
        ->where('lowongan_id', $job->id)
        ->where('status', '!=', 'saved')
        ->latest()
        ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => [
                'job'        => $job->nama,
                'applicants' => $applicants,
            ],
        ]);
    }

    /**
     * Update applicant status (terima / tolak).
     */
    public function updateApplicantStatus(Request $request, $applicationId)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $user = $request->user();
        $perusahaan = $user->perusahaan;

        $application = PelamarLowongan::whereHas('lowongan', function ($q) use ($perusahaan) {
            $q->where('perusahaan_id', $perusahaan->id);
        })->where('id', $applicationId)->firstOrFail();

        $application->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status lamaran pelamar berhasil diperbarui menjadi ' . $request->status,
            'data'    => $application,
        ]);
    }

    /**
     * Talent Hunter: Search and browse candidates.
     */
    public function talents(Request $request)
    {
        $query = Pelamar::with(['riwayat_pendidikan', 'pengalaman_kerja', 'user'])
            ->whereNotNull('nama_pelamar');

        // Filter search keyword
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_pelamar', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi_diri', 'like', "%{$keyword}%");
            });
        }

        // Filter category/kategori (pelamar vs kandidat)
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter kota
        if ($request->filled('kota')) {
            $query->where('kota', 'like', "%{$request->kota}%");
        }

        $talents = $query->latest()->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $talents,
        ]);
    }

    /**
     * Talent Hunter: Candidate detail.
     */
    public function talentDetail(Request $request, $id)
    {
        $talent = Pelamar::with(['riwayat_pendidikan', 'pengalaman_kerja', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $talent,
        ]);
    }

    /**
     * Send direct Job Offer / Invitation to a candidate.
     */
    public function sendJobOffer(Request $request, $id)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan_perusahaans,id',
            'pesan'       => 'nullable|string',
        ]);

        $user = $request->user();
        $perusahaan = $user->perusahaan;
        if (!$perusahaan) {
            return response()->json(['success' => false, 'message' => 'Hanya akun perusahaan yang dapat mengirim tawaran.'], 403);
        }

        $pelamar = Pelamar::findOrFail($id);
        $job = $perusahaan->pasanglowongan()->where('id', $request->lowongan_id)->firstOrFail();

        $existing = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $job->id)
            ->first();

        if ($existing) {
            $existing->update(['status' => 'ditawarkan']);
            $offer = $existing;
        } else {
            $offer = PelamarLowongan::create([
                'pelamar_id'  => $pelamar->id,
                'lowongan_id' => $job->id,
                'status'      => 'ditawarkan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Undangan / Tawaran kerja berhasil dikirim ke kandidat.',
            'data'    => $offer,
        ], 201);
    }
}
