<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pelamar;
use App\Models\PelamarLowongan;
use App\Models\PengalamanKerja;
use App\Models\RiwayatPendidikan;
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
                'user_id'      => $user->id,
                'nama_pelamar' => $user->username,
                'kategori'     => 'pelamar',
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

            // Also update avatar on user record
            $user->update(['avatar' => $path]);
        }

        $pelamar->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $pelamar->fresh(),
        ]);
    }

    /**
     * Upload Avatar / Foto Profil Pelamar
     */
    public function uploadAvatar(Request $request)
    {
        $user = $request->user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return response()->json(['success' => false, 'message' => 'Profil pelamar tidak ditemukan.'], 404);
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($pelamar->img_profile && Storage::disk('public')->exists($pelamar->img_profile)) {
            Storage::disk('public')->delete($pelamar->img_profile);
        }

        $path = $request->file('avatar')->store('img/pelamar/profile', 'public');
        $pelamar->img_profile = $path;
        $pelamar->save();

        $user->update(['avatar' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui.',
            'data'    => [
                'avatar_path' => $path,
                'avatar_url'  => asset('storage/' . $path),
            ],
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

    /**
     * Pengajuan Pelamar untuk Upgrade ke Kandidat Terpilih
     */
    public function upgradeKandidat(Request $request)
    {
        $user = $request->user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return response()->json(['success' => false, 'message' => 'Profil pelamar tidak ditemukan.'], 404);
        }

        // Check if CV & biodata is complete
        if (!$pelamar->nama_pelamar || !$pelamar->telepon_pelamar || empty($pelamar->skills)) {
            return response()->json([
                'success' => false,
                'message' => 'Lengkapi nama, nomor telepon, dan keahlian/skill Anda terlebih dahulu sebelum mengajukan upgrade.',
            ], 422);
        }

        $pelamar->update([
            'kategori' => 'kandidat',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Selamat! Profil Anda berhasil di-upgrade menjadi Kandidat Terpilih.',
            'data'    => $pelamar->fresh(),
        ]);
    }

    /**
     * Get & Update Skills
     */
    public function getSkills(Request $request)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        return response()->json([
            'success' => true,
            'data'    => $pelamar->skills ?? [],
        ]);
    }

    public function updateSkills(Request $request)
    {
        $request->validate([
            'skills' => 'required|array',
        ]);

        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $pelamar->update(['skills' => $request->skills]);

        return response()->json([
            'success' => true,
            'message' => 'Daftar skill berhasil disimpan.',
            'data'    => $pelamar->skills,
        ]);
    }

    // ==========================================
    // Pengalaman Kerja Endpoints
    // ==========================================

    public function getExperiences(Request $request)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        return response()->json([
            'success' => true,
            'data'    => $pelamar->pengalaman_kerja()->latest()->get(),
        ]);
    }

    public function addExperience(Request $request)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $validated = $request->validate([
            'posisi_pekerjaan'  => 'required|string|max:255',
            'jabatan_pekerjaan' => 'nullable|string|max:255',
            'nama_perusahaan'   => 'required|string|max:255',
            'tahun_awal'        => 'required|string|max:50',
            'tahun_akhir'       => 'nullable|string|max:50',
            'deskripsi'         => 'nullable|string',
        ]);

        $item = $pelamar->pengalaman_kerja()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengalaman kerja berhasil ditambahkan.',
            'data'    => $item,
        ], 201);
    }

    public function updateExperience(Request $request, $id)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $item = $pelamar->pengalaman_kerja()->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'posisi_pekerjaan'  => 'sometimes|required|string|max:255',
            'jabatan_pekerjaan' => 'nullable|string|max:255',
            'nama_perusahaan'   => 'sometimes|required|string|max:255',
            'tahun_awal'        => 'sometimes|required|string|max:50',
            'tahun_akhir'       => 'nullable|string|max:50',
            'deskripsi'         => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengalaman kerja berhasil diperbarui.',
            'data'    => $item,
        ]);
    }

    public function deleteExperience(Request $request, $id)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $item = $pelamar->pengalaman_kerja()->where('id', $id)->firstOrFail();
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengalaman kerja berhasil dihapus.',
        ]);
    }

    // ==========================================
    // Riwayat Pendidikan Endpoints
    // ==========================================

    public function getEducations(Request $request)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        return response()->json([
            'success' => true,
            'data'    => $pelamar->riwayat_pendidikan()->latest()->get(),
        ]);
    }

    public function addEducation(Request $request)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $validated = $request->validate([
            'pendidikan'      => 'required|string|max:100',
            'jurusan'         => 'nullable|string|max:150',
            'asal_pendidikan' => 'required|string|max:255',
            'tahun_awal'      => 'required|string|max:50',
            'tahun_akhir'     => 'nullable|string|max:50',
        ]);

        $item = $pelamar->riwayat_pendidikan()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pendidikan berhasil ditambahkan.',
            'data'    => $item,
        ], 201);
    }

    public function updateEducation(Request $request, $id)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $item = $pelamar->riwayat_pendidikan()->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'pendidikan'      => 'sometimes|required|string|max:100',
            'jurusan'         => 'nullable|string|max:150',
            'asal_pendidikan' => 'sometimes|required|string|max:255',
            'tahun_awal'      => 'sometimes|required|string|max:50',
            'tahun_akhir'     => 'nullable|string|max:50',
        ]);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pendidikan berhasil diperbarui.',
            'data'    => $item,
        ]);
    }

    public function deleteEducation(Request $request, $id)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $item = $pelamar->riwayat_pendidikan()->where('id', $id)->firstOrFail();
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pendidikan berhasil dihapus.',
        ]);
    }

    // ==========================================
    // Job Offers (Tawaran Kerja dari Perusahaan)
    // ==========================================

    public function getJobOffers(Request $request)
    {
        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $offers = PelamarLowongan::with(['lowongan.perusahaan'])
            ->where('pelamar_id', $pelamar->id)
            ->where('status', 'ditawarkan')
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $offers,
        ]);
    }

    public function respondJobOffer(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|in:terima,tolak',
        ]);

        $pelamar = $request->user()->pelamar;
        if (!$pelamar) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

        $offer = PelamarLowongan::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        $newStatus = $request->response === 'terima' ? 'diterima' : 'ditolak';
        $offer->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Tawaran kerja berhasil di-' . ($request->response === 'terima' ? 'terima' : 'tolak') . '.',
            'data'    => $offer,
        ]);
    }
}
