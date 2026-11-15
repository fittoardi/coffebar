<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $validStatuses = ['paid', 'processing', 'ready', 'completed'];

        $todaySales = (float) Order::query()
            ->whereIn('status', $validStatuses)
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        $monthSales = (float) Order::query()
            ->whereIn('status', $validStatuses)
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        $todayOrdersCount = Order::query()
            ->whereDate('created_at', $today)
            ->count();

        $monthOrdersCount = Order::query()
            ->where('created_at', '>=', $startOfMonth)
            ->count();

        $activeProductsCount = Product::query()->where('is_available', true)->count();
        $totalProductsCount = Product::query()->count();
        $staffCount = User::query()->whereIn('role', ['admin', 'cashier'])->count();

        $recentOrders = Order::query()
            ->with(['user', 'items', 'payment'])
            ->latest()
            ->take(6)
            ->get();

        $topProducts = OrderItem::query()
            ->select('product_id', 'name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function ($query) use ($validStatuses) {
                $query->whereIn('status', $validStatuses);
            })
            ->whereNotNull('product_id')
            ->groupBy('product_id', 'name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todaySales',
            'monthSales',
            'todayOrdersCount',
            'monthOrdersCount',
            'activeProductsCount',
            'totalProductsCount',
            'staffCount',
            'recentOrders',
            'topProducts'
        ));
    }
}
