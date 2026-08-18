<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use Illuminate\Http\Request;

class CompanyApiController extends Controller
{
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

        $jobs = $perusahaan->pasanglowongan()->withCount('pelamar')->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => [
                'coin_balance' => $perusahaan->koin_perusahaan,
                'jobs'         => $jobs,
            ],
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
            'pelamar.skill',
            'pelamar.user'
        ])
        ->where('lowongan_id', $job->id)
        ->latest()
        ->paginate(15);

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
