<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected ?string $serverKey;

    protected ?string $clientKey;

    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
    }

    public function getBaseUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    /**
     * Create a Snap token and payment redirect URL for an order.
     *
     * @return array{token: string, redirect_url: string}
     */
    public function createSnapTransaction(Order $order): array
    {
        // If no server key configured (e.g. testing / sandbox dummy without credentials)
        if (empty($this->serverKey)) {
            $mockToken = 'SNAP-MOCK-'.md5($order->order_code.time());

            return [
                'token' => $mockToken,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/'.$mockToken,
            ];
        }

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id' => (string) ($item->product_id ?? $item->id),
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => mb_substr($item->name, 0, 50),
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name ?? 'Pelanggan CoffeBar',
                'email' => $order->user?->email ?? 'customer@coffebar.id',
                'phone' => $order->customer_phone ?? '08123456789',
            ],
            'item_details' => $items,
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->post($this->getBaseUrl().'/transactions', $payload);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'token' => $data['token'] ?? '',
                    'redirect_url' => $data['redirect_url'] ?? '',
                ];
            }

            Log::error('[Midtrans Snap Error] '.$response->body());
        } catch (\Throwable $e) {
            Log::error('[Midtrans Exception] '.$e->getMessage());
        }

        // Fallback simulation
        $mockToken = 'SNAP-MOCK-'.md5($order->order_code);

        return [
            'token' => $mockToken,
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/'.$mockToken,
        ];
    }

    /**
     * Verify SHA512 signature key sent by Midtrans webhook.
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        if (empty($this->serverKey)) {
            return true;
        }

        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$this->serverKey);

        return hash_equals($expected, $signatureKey);
    }

    /**
     * Handle incoming notification payload from Midtrans.
     */
    public function handleNotification(array $payload): bool
    {
        $orderCode = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? 'accept';

        if (! $orderCode) {
            return false;
        }

        $order = Order::where('order_code', $orderCode)->first();
        if (! $order) {
            Log::warning("[Midtrans Webhook] Order not found: {$orderCode}");

            return false;
        }

        // Validate signature
        if (! empty($signatureKey) && ! $this->verifySignature($orderCode, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning("[Midtrans Webhook] Invalid signature for order: {$orderCode}");

            return false;
        }

        $payment = $order->payment ?? Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'method' => 'online',
            'status' => 'pending',
            'gateway_name' => 'midtrans',
        ]);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->markOrderAsPaid($order, $payment, $payload);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markOrderAsPaid($order, $payment, $payload);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
            $order->update([
                'status' => 'batal',
                'payment_status' => 'failed',
            ]);
            $payment->update([
                'status' => 'failed',
                'gateway_payload' => $payload,
            ]);
        }

        return true;
    }

    protected function markOrderAsPaid(Order $order, Payment $payment, array $payload): void
    {
        $order->update([
            'status' => 'dibayar',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $payment->update([
            'status' => 'paid',
            'gateway_reference' => $payload['transaction_id'] ?? null,
            'gateway_payload' => $payload,
            'paid_at' => now(),
        ]);
    }
}
