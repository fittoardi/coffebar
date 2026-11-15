<?php

namespace App\Services\Cart;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'coffebar_cart';

    /**
     * Get all cart items as a Collection.
     *
     * @return Collection<string, array{row_id: string, product_id: ?int, custom_menu_id: ?int, name: string, price: float, quantity: int, options: array, notes: ?string, subtotal: float}>
     */
    public function getItems(): Collection
    {
        return collect(Session::get(self::SESSION_KEY, []));
    }

    /**
     * Add an item to the cart.
     *
     * @param array{product_id?: ?int, custom_menu_id?: ?int, name: string, price: float, quantity?: int, options?: array, notes?: ?string} $attributes
     * @return string The unique row_id
     */
    public function add(array $attributes): string
    {
        $items = $this->getItems();

        $quantity = max(1, (int) ($attributes['quantity'] ?? 1));
        $price = (float) $attributes['price'];
        $options = $attributes['options'] ?? [];
        $notes = $attributes['notes'] ?? null;

        // Generate consistent row_id based on item type and chosen options
        $serializedOptions = json_encode($options);
        $rowId = md5(($attributes['product_id'] ?? 'cm_' . ($attributes['custom_menu_id'] ?? 'custom')) . '_' . $serializedOptions);

        if ($items->has($rowId)) {
            $existing = $items->get($rowId);
            $newQuantity = $existing['quantity'] + $quantity;
            $items->put($rowId, array_merge($existing, [
                'quantity' => $newQuantity,
                'subtotal' => $newQuantity * $price,
                'notes' => $notes ?? $existing['notes'],
            ]));
        } else {
            $items->put($rowId, [
                'row_id' => $rowId,
                'product_id' => $attributes['product_id'] ?? null,
                'custom_menu_id' => $attributes['custom_menu_id'] ?? null,
                'name' => $attributes['name'],
                'price' => $price,
                'quantity' => $quantity,
                'options' => $options,
                'notes' => $notes,
                'subtotal' => $quantity * $price,
            ]);
        }

        Session::put(self::SESSION_KEY, $items->all());

        return $rowId;
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(string $rowId, int $quantity): void
    {
        $items = $this->getItems();

        if (! $items->has($rowId)) {
            return;
        }

        if ($quantity <= 0) {
            $this->remove($rowId);

            return;
        }

        $item = $items->get($rowId);
        $item['quantity'] = $quantity;
        $item['subtotal'] = $quantity * $item['price'];

        $items->put($rowId, $item);
        Session::put(self::SESSION_KEY, $items->all());
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(string $rowId): void
    {
        $items = $this->getItems();
        $items->forget($rowId);
        Session::put(self::SESSION_KEY, $items->all());
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Calculate total price of all items in cart.
     */
    public function getTotal(): float
    {
        return (float) $this->getItems()->sum('subtotal');
    }

    /**
     * Get total number of distinct items / quantities in cart.
     */
    public function getCount(): int
    {
        return (int) $this->getItems()->sum('quantity');
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return $this->getItems()->isEmpty();
    }
}
