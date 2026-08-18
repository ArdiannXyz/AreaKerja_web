<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LowonganPerusahaan;
use App\Models\PelamarLowongan;
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

        // Check if already applied
        $existing = PelamarLowongan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $job->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah melamar ke lowongan ini.',
                'data'    => $existing,
            ], 422);
        }

        $application = PelamarLowongan::create([
            'pelamar_id'  => $pelamar->id,
            'lowongan_id' => $job->id,
            'status'      => 'pending',
            'is_read'     => 0,
        ]);

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
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $applications,
        ]);
    }
}
