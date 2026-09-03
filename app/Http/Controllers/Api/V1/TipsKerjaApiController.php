<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TipsKerja;
use Illuminate\Http\Request;

class TipsKerjaApiController extends Controller
{
    /**
     * Get list of published career tips.
     */
    public function index(Request $request)
    {
        $query = TipsKerja::where('status', 'terbit');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('intro', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        $tips = $query->latest()->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data'    => $tips,
        ]);
    }

    /**
     * Get detail of a career tip by ID or slug.
     */
    public function show($id)
    {
        $tips = TipsKerja::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $tips,
        ]);
    }
}
