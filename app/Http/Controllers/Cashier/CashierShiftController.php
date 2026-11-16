<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CashierShiftController extends Controller
{
    /**
     * Show Cashier Shifts Management Page.
     */
    public function index(): View
    {
        $activeShift = CashierShift::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        $pastShifts = CashierShift::where('cashier_id', Auth::id())
            ->where('status', 'closed')
            ->latest()
            ->paginate(10);

        return view('cashier.shifts.index', compact('activeShift', 'pastShifts'));
    }

    /**
     * Open a new cashier shift with starting cash balance.
     */
    public function start(Request $request): JsonResponse|RedirectResponse
    {
        // Check if there is already an open shift
        $existing = CashierShift::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda masih memiliki shift yang sedang aktif.');
        }

        $validated = $request->validate([
            'starting_cash' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'starting_cash.required' => 'Masukkan modal kas awal laci kasir.',
        ]);

        $startingCash = (float) $validated['starting_cash'];

        $shift = CashierShift::create([
            'cashier_id' => Auth::id(),
            'started_at' => now(),
            'starting_cash' => $startingCash,
            'total_cash_expected' => $startingCash,
            'total_sales' => 0,
            'status' => 'open',
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Shift kasir berhasil dibuka.',
                'shift' => $shift,
            ]);
        }

        return redirect()->route('cashier.dashboard')->with('success', 'Shift kasir berhasil dibuka dengan modal awal Rp '.number_format($startingCash, 0, ',', '.'));
    }

    /**
     * Close the active cashier shift and reconcile physical cash vs expected cash.
     */
    public function close(Request $request, CashierShift $shift): JsonResponse|RedirectResponse
    {
        if ($shift->cashier_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak berhak menutup shift kasir lain.');
        }

        if ($shift->status === 'closed') {
            return back()->with('error', 'Shift ini sudah ditutup sebelumnya.');
        }

        $validated = $request->validate([
            'total_cash_actual' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'total_cash_actual.required' => 'Hitung dan masukkan uang fisik kas akhir di laci kasir.',
        ]);

        $actualCash = (float) $validated['total_cash_actual'];
        $expectedCash = (float) $shift->total_cash_expected;
        $variance = $actualCash - $expectedCash;

        $varianceText = $variance === 0.0
            ? 'Tepat seimbang (Balance)'
            : ($variance > 0
                ? 'Lebih Kas: +Rp '.number_format($variance, 0, ',', '.')
                : 'Kurang Kas: -Rp '.number_format(abs($variance), 0, ',', '.'));

        $shift->update([
            'ended_at' => now(),
            'total_cash_actual' => $actualCash,
            'status' => 'closed',
            'notes' => ($validated['notes'] ? $validated['notes'].' | ' : '').$varianceText,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Shift berhasil ditutup.',
                'variance' => $variance,
                'shift' => $shift,
            ]);
        }

        return redirect()->route('cashier.shifts.index')
            ->with('success', 'Shift berhasil ditutup! '.$varianceText);
    }
}
