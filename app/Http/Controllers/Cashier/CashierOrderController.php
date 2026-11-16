<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CashierOrderController extends Controller
{
    /**
     * Show verification page / search order by code or QR code.
     */
    public function verify(Request $request): View|JsonResponse
    {
        $code = trim((string) $request->query('code', ''));
        $order = null;
        $qrCodeSvg = null;

        if (! empty($code)) {
            $order = Order::where('order_code', strtoupper($code))
                ->with(['items', 'payment', 'user'])
                ->first();

            if ($order) {
                $qrCodeSvg = (string) QrCode::size(140)->color(62, 31, 13)->generate($order->order_code);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'found' => $order !== null,
                'order' => $order,
            ]);
        }

        return view('cashier.verify', compact('order', 'code', 'qrCodeSvg'));
    }

    /**
     * Update order workflow status (diterima -> diproses -> siap -> selesai / batal).
     */
    public function updateStatus(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:diterima,diproses,siap,selesai,batal'],
        ]);

        $newStatus = $validated['status'];
        $updateData = [
            'status' => $newStatus,
            'cashier_id' => Auth::id(),
        ];

        if ($newStatus === 'selesai') {
            $updateData['completed_at'] = now();
        }

        $order->update($updateData);

        /** @var User $cashier */
        $cashier = Auth::user();
        Log::info("Kasir [ID: {$cashier->id}, Nama: {$cashier->name}] memperbarui status pesanan [#{$order->order_code}] menjadi: {$newStatus}");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Status pesanan berhasil diperbarui ke: '.ucfirst($newStatus),
                'order' => $order->fresh(),
            ]);
        }

        return back()->with('success', 'Status pesanan '.$order->order_code.' berhasil diubah menjadi: '.ucfirst($newStatus));
    }

    /**
     * Process payment at cashier counter for orders with cash/debit/qris.
     */
    public function processPayment(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,qris,debit,transfer'],
            'cash_given' => ['nullable', 'numeric', 'min:0'],
        ]);

        $method = $validated['payment_method'];
        $total = (float) $order->total_amount;
        $cashGiven = ! empty($validated['cash_given']) ? (float) $validated['cash_given'] : $total;

        if ($method === 'cash' && $cashGiven < $total) {
            return back()->with('error', 'Uang tunai yang diberikan (Rp '.number_format($cashGiven, 0, ',', '.').') kurang dari total tagihan (Rp '.number_format($total, 0, ',', '.').')!');
        }

        $changeGiven = max(0, $cashGiven - $total);

        // Update Order
        $order->update([
            'status' => 'diterima',
            'payment_method' => $method,
            'payment_status' => 'paid',
            'paid_at' => now(),
            'cashier_id' => Auth::id(),
        ]);

        // Update or create payment record
        $payment = Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount' => $total,
                'method' => $method,
                'status' => 'paid',
                'cash_given' => $cashGiven,
                'change_given' => $changeGiven,
                'paid_at' => now(),
            ]
        );

        // Update active shift sales if open
        $activeShift = CashierShift::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if ($activeShift) {
            $activeShift->increment('total_sales', $total);
            if ($method === 'cash') {
                $activeShift->increment('total_cash_expected', $total);
            }
        }

        /** @var User $cashier */
        $cashier = Auth::user();
        Log::info("Kasir [ID: {$cashier->id}, Nama: {$cashier->name}] memproses pembayaran pesanan [#{$order->order_code}] senilai Rp {$total} dengan metode {$method}");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Pembayaran berhasil diproses!',
                'change_given' => $changeGiven,
                'order' => $order,
            ]);
        }

        return redirect()->route('orders.receipt', $order->order_code)
            ->with('success', 'Pembayaran pesanan '.$order->order_code.' berhasil! Kembalian: Rp '.number_format($changeGiven, 0, ',', '.'));
    }

    /**
     * Quick toggle product availability (habis / tersedia) directly from cashier counter.
     */
    public function toggleProductAvailability(Product $product): JsonResponse|RedirectResponse
    {
        $product->update([
            'is_available' => ! $product->is_available,
        ]);

        $statusText = $product->is_available ? 'TERSEDIA' : 'HABIS';

        if (request()->wantsJson()) {
            return response()->json([
                'message' => "Status {$product->name} berhasil diubah menjadi {$statusText}.",
                'is_available' => $product->is_available,
            ]);
        }

        return back()->with('success', "Status menu '{$product->name}' berhasil diubah menjadi: {$statusText}.");
    }
}
