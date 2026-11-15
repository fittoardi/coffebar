<?php

namespace App\Http\Controllers;

use App\Services\Payment\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    /**
     * Webhook notification handler for Midtrans payment gateway.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        $processed = $this->midtransService->handleNotification($payload);

        if (! $processed) {
            return response()->json(['message' => 'Notification not processed.'], 400);
        }

        return response()->json(['message' => 'Notification processed successfully.']);
    }
}
