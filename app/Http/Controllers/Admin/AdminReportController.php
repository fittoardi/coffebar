<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        [$startDate, $endDate, $rangeLabel] = $this->resolveDateRange($request);

        $validStatuses = ['paid', 'processing', 'ready', 'completed'];

        $baseQuery = Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalRevenue = (float) (clone $baseQuery)
            ->whereIn('status', $validStatuses)
            ->sum('total_amount');

        $totalOrdersCount = (clone $baseQuery)->count();

        $paidOrdersCount = (clone $baseQuery)
            ->whereIn('status', $validStatuses)
            ->count();

        $averageOrderValue = $paidOrdersCount > 0 ? ($totalRevenue / $paidOrdersCount) : 0;

        // Breakdown by source
        $onlineRevenue = (float) (clone $baseQuery)
            ->where('source', 'online')
            ->whereIn('status', $validStatuses)
            ->sum('total_amount');
        $onlineCount = (clone $baseQuery)->where('source', 'online')->count();

        $cashierRevenue = (float) (clone $baseQuery)
            ->where('source', 'cashier')
            ->whereIn('status', $validStatuses)
            ->sum('total_amount');
        $cashierCount = (clone $baseQuery)->where('source', 'cashier')->count();

        // Top selling products in this period
        $topProducts = OrderItem::query()
            ->select('item_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal_price) as total_revenue'))
            ->whereHas('order', function ($q) use ($startDate, $endDate, $validStatuses) {
                $q->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->whereIn('status', $validStatuses);
            })
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();

        // Orders list
        $orders = (clone $baseQuery)
            ->with(['user', 'payment', 'items'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'rangeLabel',
            'totalRevenue',
            'totalOrdersCount',
            'paidOrdersCount',
            'averageOrderValue',
            'onlineRevenue',
            'onlineCount',
            'cashierRevenue',
            'cashierCount',
            'topProducts',
            'orders'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        [$startDate, $endDate, $rangeLabel] = $this->resolveDateRange($request);

        $fileName = 'laporan-penjualan-coffebar-'.$startDate->format('Ymd').'-ke-'.$endDate->format('Ymd').'.csv';

        $orders = Order::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with(['user', 'payment'])
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'No',
                'Kode Pesanan',
                'Tanggal & Waktu',
                'Sumber',
                'Pelanggan',
                'Status Pesanan',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Subtotal (Rp)',
                'Pajak (Rp)',
                'Total (Rp)',
                'Catatan',
            ]);

            $no = 1;
            foreach ($orders as $order) {
                $statusLabels = [
                    'pending_payment' => 'Menunggu Pembayaran',
                    'paid' => 'Sudah Dibayar',
                    'processing' => 'Diproses (Sedang Dibuat)',
                    'ready' => 'Siap Diambil',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ];

                $paymentStatusLabels = [
                    'pending' => 'Menunggu',
                    'paid' => 'Lunas',
                    'failed' => 'Gagal',
                    'refunded' => 'Dikembalikan',
                ];

                fputcsv($handle, [
                    $no++,
                    $order->order_code,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->source === 'online' ? 'Online' : 'Kasir (Walk-in)',
                    $order->user ? $order->user->name.' ('.$order->user->phone.')' : 'Pelanggan Langsung (Walk-in)',
                    $statusLabels[$order->status] ?? $order->status,
                    $order->payment ? strtoupper($order->payment->payment_method) : '-',
                    $order->payment ? ($paymentStatusLabels[$order->payment->payment_status] ?? $order->payment->payment_status) : 'Belum Ada',
                    (float) $order->subtotal_amount,
                    (float) $order->tax_amount,
                    (float) $order->total_amount,
                    $order->notes ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function resolveDateRange(Request $request): array
    {
        $range = $request->input('range', 'this_month');

        if ($range === 'today') {
            $startDate = Carbon::today();
            $endDate = Carbon::today();
            $label = 'Hari Ini ('.Carbon::today()->translatedFormat('d M Y').')';
        } elseif ($range === '7days') {
            $startDate = Carbon::today()->subDays(6);
            $endDate = Carbon::today();
            $label = '7 Hari Terakhir';
        } elseif ($range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $label = 'Kustom: '.$startDate->format('d/m/Y').' - '.$endDate->format('d/m/Y');
        } else {
            // default this_month
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::today();
            $label = 'Bulan Ini ('.Carbon::now()->translatedFormat('F Y').')';
        }

        return [$startDate, $endDate, $label];
    }
}
