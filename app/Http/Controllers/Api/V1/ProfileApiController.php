<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileApiController extends Controller
{
    /**
     * Update Pelamar profile data (biodata, skills, social links, dll).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'pelamar') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pelamar yang dapat memperbarui profil ini.',
            ], 403);
        }

        $pelamar = $user->pelamar;
        if (!$pelamar) {
            $pelamar = Pelamar::create([
                'user_id' => $user->id,
                'nama_pelamar' => $user->username,
                'kategori' => 'pelamar',
            ]);
        }

        $validated = $request->validate([
            'nama_pelamar'    => 'nullable|string|max:255',
            'telepon_pelamar' => 'nullable|string|max:20',
            'gender'          => 'nullable|string|in:Laki-laki,Perempuan,L,P',
            'tanggal_lahir'   => 'nullable|date',
            'deskripsi_diri'  => 'nullable|string',
            'alamat'          => 'nullable|string|max:255',
            'kota'            => 'nullable|string|max:100',
            'provinsi'        => 'nullable|string|max:100',
            'skills'          => 'nullable|array',
            'social_links'    => 'nullable|array',
            'gaji_minimal'    => 'nullable|numeric',
            'gaji_maksimal'   => 'nullable|numeric',
            'divisi'          => 'nullable|array',
            'img_profile'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Upload Profile Picture if present
        if ($request->hasFile('img_profile')) {
            if ($pelamar->img_profile && Storage::disk('public')->exists($pelamar->img_profile)) {
                Storage::disk('public')->delete($pelamar->img_profile);
            }
            $path = $request->file('img_profile')->store('img/pelamar/profile', 'public');
            $validated['img_profile'] = $path;
        }

        $pelamar->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $pelamar->fresh(),
        ]);
    }

    /**
     * Upload resume/CV document for Pelamar.
     */
    public function uploadCv(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'pelamar' || !$user->pelamar) {
            return response()->json([
                'success' => false,
                'message' => 'Profil pelamar tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'resume_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ]);

        $pelamar = $user->pelamar;

        if ($pelamar->resume_file && Storage::disk('public')->exists($pelamar->resume_file)) {
            Storage::disk('public')->delete($pelamar->resume_file);
        }

        $path = $request->file('resume_file')->store('resumes', 'public');
        $pelamar->resume_file = $path;
        $pelamar->save();

        return response()->json([
            'success' => true,
            'message' => 'CV / Resume berhasil diunggah.',
            'data'    => [
                'resume_path' => $path,
                'resume_url'  => asset('storage/' . $path),
            ],
        ]);
    }
}
