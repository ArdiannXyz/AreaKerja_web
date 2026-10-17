<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePelamarReportRequest;
use App\Http\Requests\StoreReportRequest;
use App\Models\LowonganPerusahaan;
use App\Models\Pelamar;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    /**
     * Pelamar melaporkan lowongan pekerjaan
     */
    public function store(StoreReportRequest $request, LowonganPerusahaan $lowongan, ReportService $service): RedirectResponse
    {
        $service->store(
            $request->user(),
            $lowongan,
            $request->validated(),
            $request->file('bukti')
        );

        return back()->with('success', 'Laporan Anda sudah dikirim dan akan ditinjau oleh tim moderasi.');
    }

    /**
     * Perusahaan melaporkan pelamar kerja
     */
    public function storePelamar(StorePelamarReportRequest $request, Pelamar $pelamar, ReportService $service): RedirectResponse
    {
        $lowongan = null;
        if ($request->filled('lowongan_id')) {
            $lowongan = LowonganPerusahaan::find($request->lowongan_id);
        }

        $service->storePelamarReport(
            $request->user(),
            $pelamar,
            $request->validated(),
            $request->file('bukti'),
            $lowongan
        );

        return back()->with('success', 'Laporan Anda terhadap pelamar berhasil dikirim dan akan segera ditinjau oleh tim moderasi AreaKerja.');
    }
}
