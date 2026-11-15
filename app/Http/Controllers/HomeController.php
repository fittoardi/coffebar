<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $popularProducts = Product::with('category')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('public.home', compact('popularProducts', 'categories'));
    }
}
