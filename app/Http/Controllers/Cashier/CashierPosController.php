<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CashierPosController extends Controller
{
    /**
     * Show Point of Sale screen for walk-in orders.
     */
    public function index(): View
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $sizes = ProductOption::where('type', 'size')->where('is_available', true)->get();
        $toppings = ProductOption::where('type', 'topping')->where('is_available', true)->get();

        $activeShift = CashierShift::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        return view('cashier.pos', compact('categories', 'sizes', 'toppings', 'activeShift'));
    }

    /**
     * Process and store walk-in order directly from cashier.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cash,qris,debit'],
            'cash_given' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.name' => ['required', 'string'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.options' => ['nullable', 'array'],
        ], [
            'items.required' => 'Pilih minimal satu menu untuk membuat pesanan walk-in.',
            'items.min' => 'Pilih minimal satu menu untuk membuat pesanan walk-in.',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += ((float) $item['price'] * (int) $item['quantity']);
            }

            $method = $validated['payment_method'];
            $cashGiven = ! empty($validated['cash_given']) ? (float) $validated['cash_given'] : $totalAmount;

            if ($method === 'cash' && $cashGiven < $totalAmount) {
                throw new \InvalidArgumentException('Uang tunai kurang dari total tagihan.');
            }

            $changeGiven = max(0, $cashGiven - $totalAmount);

            $order = Order::create([
                'order_code' => Order::generateOrderCode(),
                'user_id' => null, // Walk-in guest
                'customer_name' => $validated['customer_name'] ?? 'Pelanggan Walk-In',
                'customer_phone' => null,
                'source' => 'cashier',
                'status' => 'diterima',
                'total_amount' => $totalAmount,
                'payment_method' => $method,
                'payment_status' => 'paid',
                'notes' => $validated['notes'] ?? null,
                'cashier_id' => Auth::id(),
                'paid_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => (float) $item['price'] * (int) $item['quantity'],
                    'options' => $item['options'] ?? null,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'method' => $method,
                'status' => 'paid',
                'cash_given' => $cashGiven,
                'change_given' => $changeGiven,
                'paid_at' => now(),
            ]);

            // Update shift balance
            $activeShift = CashierShift::where('cashier_id', Auth::id())
                ->where('status', 'open')
                ->latest()
                ->first();

            if ($activeShift) {
                $activeShift->increment('total_sales', $totalAmount);
                if ($method === 'cash') {
                    $activeShift->increment('total_cash_expected', $totalAmount);
                }
            }

            return $order;
        });

        /** @var \App\Models\User $cashier */
        $cashier = Auth::user();
        Log::info("Kasir [ID: {$cashier->id}, Nama: {$cashier->name}] membuat pesanan walk-in [#{$order->order_code}] senilai Rp {$order->total_amount}");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Pesanan walk-in berhasil diproses!',
                'order' => $order,
                'receipt_url' => route('orders.receipt', $order->order_code),
            ]);
        }

        return redirect()->route('orders.receipt', $order->order_code)
            ->with('success', 'Pesanan walk-in '.$order->order_code.' berhasil diproses!');
    }
}
