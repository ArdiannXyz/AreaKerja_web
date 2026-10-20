<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\LowonganPerusahaan;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ReportApiController extends Controller
{
    public function reasons(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => collect(Report::ALASAN)->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])->values(),
        ]);
    }

    public function store(StoreReportRequest $request, ReportService $service): JsonResponse
    {
        $request->validate([
            'lowongan_id' => ['required', 'exists:lowongan_perusahaans,id'],
        ]);

        $lowongan = LowonganPerusahaan::findOrFail($request->input('lowongan_id'));

        try {
            $report = $service->store(
                $request->user(),
                $lowongan,
                $request->validated(),
                $request->file('bukti')
            );
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first(),
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan Anda sudah dikirim dan akan ditinjau oleh tim moderasi.',
            'data'    => [
                'id'     => $report->id,
                'status' => $report->status,
            ],
        ], 201);
    }
}
