<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\Controller;

use App\Models\LowonganPerusahaan;
use Illuminate\Http\Request;

class CleanInactiveLowonganController extends Controller
{
    /**
     * Menghapus lowongan yang tidak memiliki aktivitas
     * selama lebih dari 6 bulan
     */
    public function index()
    {
        $deleted = LowonganPerusahaan::where(function ($q) {
            $q->whereNull('last_activity')
                ->orWhere('last_activity', '<', now()->subMonths(6));
        })
            ->whereDoesntHave('pelamar')
            ->delete();

        return response()->json([
            'task'    => 'clean_inactive_lowongan',
            'deleted' => $deleted,
        ]);
    }
}
