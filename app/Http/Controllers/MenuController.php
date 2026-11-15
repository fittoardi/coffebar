<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $selectedCategorySlug = $request->query('kategori');
        $searchQuery = trim((string) $request->query('q', ''));

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $productsQuery = Product::with('category', 'options')
            ->orderBy('sort_order');

        if (! empty($selectedCategorySlug)) {
            $productsQuery->whereHas('category', function ($q) use ($selectedCategorySlug) {
                $q->where('slug', $selectedCategorySlug);
            });
        }

        if (! empty($searchQuery)) {
            $productsQuery->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        $products = $productsQuery->get();

        // Options available globally for customizing products in modal
        $globalSizes = ProductOption::where('type', 'size')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $globalToppings = ProductOption::where('type', 'topping')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $globalSugar = ProductOption::where('type', 'sugar')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $globalIce = ProductOption::where('type', 'ice')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        return view('public.menu.index', compact(
            'categories',
            'products',
            'selectedCategorySlug',
            'searchQuery',
            'globalSizes',
            'globalToppings',
            'globalSugar',
            'globalIce'
        ));
    }

    public function show(Product $product): JsonResponse|View
    {
        $product->load(['category', 'options']);

        if (request()->wantsJson()) {
            return response()->json([
                'product' => $product,
                'formatted_price' => $product->formatted_price,
            ]);
        }

        return view('public.menu.show', compact('product'));
    }
}
