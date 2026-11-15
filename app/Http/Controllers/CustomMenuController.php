<?php

namespace App\Http\Controllers;

use App\Models\CustomMenu;
use App\Models\ProductOption;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomMenuController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): View
    {
        $bases = ProductOption::where('type', 'base')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $sizes = ProductOption::where('type', 'size')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $sugars = ProductOption::where('type', 'sugar')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $ices = ProductOption::where('type', 'ice')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $toppings = ProductOption::where('type', 'topping')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $savedMenus = Auth::check()
            ? CustomMenu::where('user_id', Auth::id())->latest()->get()
            : collect();

        return view('public.custom.index', compact(
            'bases',
            'sizes',
            'sugars',
            'ices',
            'toppings',
            'savedMenus'
        ));
    }

    /**
     * Save the custom creation to user's saved favorites.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if (! Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Silakan masuk untuk menyimpan kreasi menu Anda.'], 401);
            }

            return redirect()->route('login')->with('status', 'Silakan masuk terlebih dahulu untuk menyimpan racikan kreasi Anda.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'base' => ['required', 'string'],
            'size' => ['required', 'string'],
            'sugar_level' => ['required', 'string'],
            'ice_level' => ['required', 'string'],
            'options' => ['nullable', 'array'],
            'price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $customMenu = CustomMenu::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'base' => $validated['base'],
            'size' => $validated['size'],
            'sugar_level' => $validated['sugar_level'],
            'ice_level' => $validated['ice_level'],
            'options' => $validated['options'] ?? [],
            'price' => $validated['price'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kreasi menu berhasil disimpan!',
                'custom_menu' => $customMenu,
            ]);
        }

        return redirect()->route('custom.index')->with('success', 'Kreasi "'.$customMenu->name.'" berhasil disimpan ke daftar favorit Anda!');
    }

    /**
     * Add the custom crafted drink directly to cart.
     */
    public function addToCart(Request $request): JsonResponse|RedirectResponse
    {
        if (! Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Silakan masuk terlebih dahulu untuk memesan racikan custom.',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->route('login')->with('status', 'Silakan masuk terlebih dahulu untuk memesan racikan custom.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'base' => ['required', 'string'],
            'size' => ['required', 'string'],
            'sugar_level' => ['required', 'string'],
            'ice_level' => ['required', 'string'],
            'options' => ['nullable', 'array'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $options = [
            'base' => $validated['base'],
            'size' => $validated['size'],
            'sugar' => $validated['sugar_level'],
            'ice' => $validated['ice_level'],
            'toppings' => $validated['options'] ?? [],
        ];

        $this->cartService->add([
            'custom_menu_id' => null,
            'name' => 'Custom: '.$validated['name'],
            'price' => (float) $validated['price'],
            'quantity' => (int) ($validated['quantity'] ?? 1),
            'options' => $options,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Minuman kustom berhasil ditambahkan ke keranjang!',
                'cart_count' => $this->cartService->getCount(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Minuman kustom berhasil ditambahkan ke keranjang!');
    }
}
