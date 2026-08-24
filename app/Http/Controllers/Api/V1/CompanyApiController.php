<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LowonganPerusahaan;
use App\Models\PelamarLowongan;
use Illuminate\Http\Request;
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
}
