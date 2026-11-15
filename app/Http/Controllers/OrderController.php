<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    /**
     * Display list of customer's orders.
     */
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'items.customMenu', 'payment'])
            ->latest()
            ->paginate(10);

        return view('public.orders.index', compact('orders'));
    }

    /**
     * Display order ticket and QR code.
     */
    public function show(string $orderCode): View|RedirectResponse|JsonResponse
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['items.product', 'items.customMenu', 'payment'])
            ->firstOrFail();

        // Authorization check: allow owner, or staff (cashier/admin)
        if (Auth::check()) {
            $user = Auth::user();
            if ($order->user_id !== null && $order->user_id !== $user->id && ! $user->isCashier()) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat pesanan ini.');
            }
        }

        // Generate SVG QR Code
        $qrCodeSvg = QrCode::size(180)
            ->color(62, 31, 13) // #3E1F0D rustic-brown
            ->generate($order->order_code);

        if (request()->wantsJson()) {
            return response()->json([
                'order' => $order,
                'qr_code_svg' => (string) $qrCodeSvg,
            ]);
        }

        return view('public.orders.show', compact('order', 'qrCodeSvg'));
    }

    /**
     * Printable digital receipt view.
     */
    public function receipt(string $orderCode): View
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['items', 'payment', 'cashier'])
            ->firstOrFail();

        return view('public.orders.receipt', compact('order'));
    }
}
