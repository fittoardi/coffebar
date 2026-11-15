<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Cart\CartService;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService
    ) {}

    public function show(): View|RedirectResponse
    {
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Keranjang Anda masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        $items = $this->cartService->getItems();
        $total = $this->cartService->getTotal();
        $user = Auth::user();

        return view('public.checkout.index', compact('items', 'total', 'user'));
    }

    public function process(Request $request): JsonResponse|RedirectResponse
    {
        if ($this->cartService->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Keranjang belanja kosong.'], 422);
            }

            return redirect()->route('cart.index')->with('status', 'Keranjang belanja Anda kosong.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,online,qris,transfer'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'payment_method.required' => 'Pilih metode pembayaran yang diinginkan.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $order = $this->orderService->createFromCart(
            $user,
            $validated['payment_method'],
            $validated['notes'] ?? null,
            'online'
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Pesanan berhasil dibuat!',
                'order' => $order,
                'redirect_url' => route('orders.show', $order->order_code),
            ]);
        }

        return redirect()->route('orders.show', $order->order_code)
            ->with('success', 'Pesanan Anda berhasil dibuat dengan kode '.$order->order_code.'!');
    }
}
