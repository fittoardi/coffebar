<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Create a new Order from current cart contents.
     *
     * @throws InvalidArgumentException
     */
    public function createFromCart(
        User $user,
        string $paymentMethod = 'cash',
        ?string $notes = null,
        string $source = 'online'
    ): Order {
        if ($this->cartService->isEmpty()) {
            throw new InvalidArgumentException('Keranjang belanja Anda masih kosong.');
        }

        return DB::transaction(function () use ($user, $paymentMethod, $notes, $source) {
            $totalAmount = $this->cartService->getTotal();
            $orderCode = Order::generateOrderCode();

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone,
                'source' => $source,
                'status' => 'menunggu',
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($this->cartService->getItems() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'custom_menu_id' => $item['custom_menu_id'] ?? null,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'options' => $item['options'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'method' => $paymentMethod,
                'status' => 'pending',
                'gateway_name' => $paymentMethod === 'online' ? 'midtrans' : null,
                'gateway_reference' => $paymentMethod === 'online' ? 'MID-' . random_int(100000, 999999) : null,
            ]);

            $this->cartService->clear();

            return $order->load(['items', 'payment']);
        });
    }
}
