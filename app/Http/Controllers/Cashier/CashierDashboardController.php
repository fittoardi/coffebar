<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CashierDashboardController extends Controller
{
    /**
     * Display the main Cashier Real-time Dashboard.
     */
    public function index(Request $request): View
    {
        $currentShift = CashierShift::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        // Categorized counts for summary cards
        $countWaitingPayment = Order::where('status', 'menunggu')
            ->where('payment_method', 'cash')
            ->count();

        $countPaidOnline = Order::where('status', 'dibayar')->count();

        $countInProcess = Order::whereIn('status', ['diterima', 'diproses'])->count();

        $countReady = Order::where('status', 'siap')->count();

        $countCompletedToday = Order::where('status', 'selesai')
            ->whereDate('completed_at', today())
            ->count();

        // Filter active orders based on tab
        $tab = $request->query('tab', 'active');

        $ordersQuery = Order::with(['items', 'payment', 'user'])
            ->latest();

        if ($tab === 'waiting') {
            $ordersQuery->where('status', 'menunggu')->where('payment_method', 'cash');
        } elseif ($tab === 'paid') {
            $ordersQuery->where('status', 'dibayar');
        } elseif ($tab === 'processing') {
            $ordersQuery->whereIn('status', ['diterima', 'diproses']);
        } elseif ($tab === 'ready') {
            $ordersQuery->where('status', 'siap');
        } elseif ($tab === 'completed') {
            $ordersQuery->where('status', 'selesai')->whereDate('completed_at', today());
        } else {
            // Default 'active': all orders needing attention (not finished or cancelled)
            $ordersQuery->whereNotIn('status', ['selesai', 'batal']);
        }

        $orders = $ordersQuery->get();

        $products = Product::with('category')->orderBy('sort_order')->get();

        return view('cashier.dashboard', compact(
            'currentShift',
            'orders',
            'tab',
            'countWaitingPayment',
            'countPaidOnline',
            'countInProcess',
            'countReady',
            'countCompletedToday',
            'products'
        ));
    }

    /**
     * JSON feed for real-time polling without page reload.
     */
    public function feed(): JsonResponse
    {
        $orders = Order::with(['items', 'payment', 'user'])
            ->whereNotIn('status', ['selesai', 'batal'])
            ->latest()
            ->get();

        return response()->json([
            'orders' => $orders,
            'counts' => [
                'waiting' => Order::where('status', 'menunggu')->where('payment_method', 'cash')->count(),
                'paid' => Order::where('status', 'dibayar')->count(),
                'processing' => Order::whereIn('status', ['diterima', 'diproses'])->count(),
                'ready' => Order::where('status', 'siap')->count(),
            ],
        ]);
    }
}
