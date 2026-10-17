<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\XenditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditCallbackController extends Controller
{
    protected XenditService $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Endpoint webhook callback dari Xendit (Invoice & Payment Events).
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $callbackToken = $request->header('x-callback-token') ?? $request->header('X-Callback-Token');

        Log::info("Xendit Webhook Received", [
            'external_id' => $payload['external_id'] ?? null,
            'status'      => $payload['status'] ?? null,
            'id'          => $payload['id'] ?? null,
        ]);

        $result = $this->xenditService->handleWebhook($payload, $callbackToken);

        $httpCode = $result['code'] ?? 200;

        return response()->json([
            'success' => $result['success'] ?? false,
            'message' => $result['message'] ?? 'Webhook received',
            'status'  => $result['status'] ?? null,
        ], $httpCode);
    }
}

