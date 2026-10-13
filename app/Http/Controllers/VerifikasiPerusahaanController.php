<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

class VerifikasiPerusahaanController extends Controller
{
    /**
     * Setujui verifikasi perusahaan
     */
    public function approve($id)
    {
        Perusahaan::findOrFail($id)->update([
            'verification_status' => 'approved',
            'verified_at'         => now(),
            'verification_note'   => null,
        ]);

        return back()->with('success', 'Perusahaan berhasil diverifikasi.');
    }

    /**
     * Tolak verifikasi perusahaan
     */
    public function reject(Request $request, $id)
    {
        Perusahaan::findOrFail($id)->update([
            'verification_status' => 'rejected',
            'verified_at'         => null,
            'verification_note'   => $request->note,
        ]);

        return back()->with('success', 'Perusahaan ditolak.');
    }

    /**
     * Delegasi untuk rute Super Admin (DRY - No duplicate queries/mutations)
     */
    public function approveSuper($id)
    {
        return $this->approve($id);
    }

    public function rejectSuper(Request $request, $id)
    {
        return $this->reject($request, $id);
    }
}
