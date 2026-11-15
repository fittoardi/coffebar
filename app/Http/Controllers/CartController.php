<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOption;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): View
    {
        $items = $this->cartService->getItems();
        $total = $this->cartService->getTotal();

        return view('public.cart.index', compact('items', 'total'));
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'size' => ['nullable', 'string'],
            'sugar' => ['nullable', 'string'],
            'ice' => ['nullable', 'string'],
            'toppings' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $unitPrice = (float) $product->price;

        $options = [];

        // Calculate size extra price if any
        if (! empty($validated['size'])) {
            $options['size'] = $validated['size'];
            $sizeOption = ProductOption::where('type', 'size')->where('name', $validated['size'])->first();
            if ($sizeOption) {
                $unitPrice += (float) $sizeOption->price;
            }
        }

        if (! empty($validated['sugar'])) {
            $options['sugar'] = $validated['sugar'];
        }

        if (! empty($validated['ice'])) {
            $options['ice'] = $validated['ice'];
        }

        if (! empty($validated['toppings'])) {
            $options['toppings'] = $validated['toppings'];
            foreach ($validated['toppings'] as $toppingName) {
                $toppingOpt = ProductOption::where('type', 'topping')->where('name', $toppingName)->first();
                if ($toppingOpt) {
                    $unitPrice += (float) $toppingOpt->price;
                }
            }
        }

        $rowId = $this->cartService->add([
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $unitPrice,
            'quantity' => (int) ($validated['quantity'] ?? 1),
            'options' => $options,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'row_id' => $rowId,
                'cart_count' => $this->cartService->getCount(),
                'cart_total' => $this->cartService->getTotal(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'row_id' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->update($validated['row_id'], (int) $validated['quantity']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Keranjang diperbarui.',
                'cart_count' => $this->cartService->getCount(),
                'cart_total' => $this->cartService->getTotal(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(Request $request, string $rowId): JsonResponse|RedirectResponse
    {
        $this->cartService->remove($rowId);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Item dihapus dari keranjang.',
                'cart_count' => $this->cartService->getCount(),
                'cart_total' => $this->cartService->getTotal(),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Item telah dihapus dari keranjang.');
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return redirect()->route('cart.index')->with('status', 'Keranjang telah dikosongkan.');
    }
}
