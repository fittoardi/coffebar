@extends('layouts.cashier')

@section('title', 'Shift & Rekonsiliasi Kas')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:py-10 space-y-8"
     x-data="{
        actualCash: {{ $activeShift ? (float)$activeShift->total_cash_expected : 0 }},
        expectedCash: {{ $activeShift ? (float)$activeShift->total_cash_expected : 0 }},
        calculateVariance() {
            return this.actualCash - this.expectedCash;
        },
        formatRupiah(num) {
            return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
        }
     }">

    <!-- Header -->
    <div class="border-b-2 border-rustic-300 pb-4">
        <h1 class="font-display font-bold text-2xl sm:text-3xl text-rustic-900">
            Shift & Rekonsiliasi Kas Harian
        </h1>
        <p class="font-body italic text-xs text-rustic-600 mt-1">
            Pencatatan modal kas awal, penerimaan transaksi tunai, dan penghitungan selisih kas fisik
        </p>
    </div>

    <!-- Active Shift Card or Start Shift Form -->
    @if ($activeShift)
        <div class="bg-[#FFF8F0] border-2 border-copper p-6 sm:p-8 rounded-sm shadow-rustic-lg space-y-6">
            <div class="flex items-center justify-between pb-3 border-b-2 border-rustic-300">
                <div>
                    <span class="text-xs font-heading uppercase text-copper font-bold block">Status Shift:</span>
                    <h2 class="font-display font-bold text-2xl text-rustic-900">
                        Shift Sedang Aktif (Berjalan)
                    </h2>
                </div>
                <span class="bg-[#2D6A4F] text-parchment font-heading text-xs uppercase px-3 py-1 font-bold rounded-xs">
                    ● Terbuka
                </span>
            </div>

            <!-- Shift Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-body">
                <div class="p-3 bg-parchment border border-rustic-300 rounded-sm">
                    <span class="text-rustic-500 uppercase font-heading text-[10px] block">Waktu Mulai</span>
                    <strong class="text-sm text-rustic-900">{{ $activeShift->started_at->format('H:i') }} WIB</strong>
                    <div class="text-[10px] text-rustic-500">{{ $activeShift->started_at->diffForHumans() }}</div>
                </div>

                <div class="p-3 bg-parchment border border-rustic-300 rounded-sm">
                    <span class="text-rustic-500 uppercase font-heading text-[10px] block">Modal Kas Awal</span>
                    <strong class="text-sm text-rustic-900">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</strong>
                </div>

                <div class="p-3 bg-parchment border border-rustic-300 rounded-sm">
                    <span class="text-rustic-500 uppercase font-heading text-[10px] block">Total Penjualan Shift</span>
                    <strong class="text-sm text-copper font-bold">Rp {{ number_format($activeShift->total_sales, 0, ',', '.') }}</strong>
                </div>

                <div class="p-3 bg-rustic-100 border border-copper rounded-sm">
                    <span class="text-copper uppercase font-heading text-[10px] block font-bold">Estimasi Kas di Laci</span>
                    <strong class="text-base text-rustic-900 font-display">Rp {{ number_format($activeShift->total_cash_expected, 0, ',', '.') }}</strong>
                </div>
            </div>

            <!-- Form Close Shift & Cash Reconciliation -->
            <form method="POST" action="{{ route('cashier.shifts.close', $activeShift->id) }}" class="space-y-4 pt-4 border-t border-rustic-300">
                @csrf

                <h3 class="font-heading font-bold text-sm uppercase tracking-wider text-rustic-900">
                    Tutup Shift & Hitung Kas Fisik
                </h3>

                <div>
                    <label class="block font-heading uppercase text-xs text-rustic-700 mb-1">
                        Hitung Uang Fisik di Laci Kasir (Rp)
                    </label>
                    <input type="number"
                           name="total_cash_actual"
                           x-model.number="actualCash"
                           required
                           min="0"
                           class="w-full sm:w-80 bg-parchment border-2 border-rustic-400 font-display font-bold text-xl p-2.5 text-rustic-900 rounded-sm focus:border-copper">
                    <span class="text-[11px] text-rustic-500 font-body italic block mt-1">
                        Keluarkan seluruh uang kas di laci kasir dan masukkan total nominal fisik yang ada.
                    </span>
                </div>

                <!-- Live Variance Display -->
                <div class="p-4 rounded-sm border-2"
                     :class="calculateVariance() === 0 ? 'bg-parchment border-copper' : (calculateVariance() > 0 ? 'bg-[#2D6A4F]/10 border-[#2D6A4F]' : 'bg-brick/10 border-brick')">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-heading uppercase text-rustic-700 font-bold">Hasil Rekonsiliasi (Selisih):</span>
                        <span class="font-display font-bold text-lg"
                              :class="calculateVariance() === 0 ? 'text-rustic-900' : (calculateVariance() > 0 ? 'text-[#2D6A4F]' : 'text-brick')"
                              x-text="calculateVariance() === 0 ? '✓ Tepat Seimbang (Rp 0)' : (calculateVariance() > 0 ? '+ Kas Lebih: ' + formatRupiah(calculateVariance()) : '- Kas Kurang: ' + formatRupiah(Math.abs(calculateVariance())))">
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block font-heading uppercase text-xs text-rustic-700 mb-1">
                        Catatan Tutup Shift (Opsional)
                    </label>
                    <input type="text"
                           name="notes"
                           placeholder="Contoh: Selisih uang koin kembalian, laci kasir telah diserahkan ke shift sore"
                           class="w-full bg-parchment border-2 border-rustic-400 text-xs font-body p-2.5 text-rustic-900 rounded-sm focus:border-copper">
                </div>

                <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin menutup shift ini?')"
                        class="bg-brick text-parchment font-heading tracking-widest uppercase text-xs py-3 px-6 hover:opacity-90 font-bold shadow-sm transition">
                    TUTUP SHIFT & KUNCI KASIR
                </button>
            </form>
        </div>
    @else
        <!-- Open New Shift Form -->
        <div class="bg-[#FFF8F0] border-2 border-copper p-6 sm:p-8 rounded-sm shadow-rustic-lg space-y-6">
            <div class="border-b-2 border-rustic-300 pb-3">
                <span class="text-xs font-heading uppercase text-copper font-bold block">Memulai Kerja Kasir:</span>
                <h2 class="font-display font-bold text-2xl text-rustic-900">
                    Buka Shift Kasir Baru
                </h2>
                <p class="font-body italic text-xs text-rustic-600 mt-1">
                    Pastikan Anda menghitung modal uang receh / kas awal di laci sebelum membuka transaksi
                </p>
            </div>

            <form method="POST" action="{{ route('cashier.shifts.start') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-heading uppercase text-xs text-rustic-700 mb-1">
                        Modal Kas Awal di Laci (Rp)
                    </label>
                    <input type="number"
                           name="starting_cash"
                           value="200000"
                           required
                           min="0"
                           placeholder="Contoh: 200000"
                           class="w-full sm:w-80 bg-parchment border-2 border-rustic-400 font-display font-bold text-xl p-2.5 text-rustic-900 rounded-sm focus:border-copper">
                </div>

                <div>
                    <label class="block font-heading uppercase text-xs text-rustic-700 mb-1">
                        Catatan Pembukaan (Opsional)
                    </label>
                    <input type="text"
                           name="notes"
                           placeholder="Contoh: Shift Pagi, laci kasir bersih"
                           class="w-full bg-parchment border-2 border-rustic-400 text-xs font-body p-2.5 text-rustic-900 rounded-sm focus:border-copper">
                </div>

                <button type="submit"
                        class="bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading tracking-widest uppercase text-xs py-3 px-8 shadow-rustic font-bold transition">
                    BUKA SHIFT SEKARANG
                </button>
            </form>
        </div>
    @endif

    <!-- Past Closed Shifts History Table -->
    <div class="bg-[#FFF8F0] border-2 border-rustic-300 p-6 rounded-sm shadow-sm space-y-4">
        <h3 class="font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 border-b border-rustic-300 pb-2">
            Riwayat Shift Sebelumnya
        </h3>

        @if ($pastShifts->isEmpty())
            <div class="py-6 text-center text-xs text-rustic-500 italic">
                Belum ada riwayat shift yang ditutup.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-body">
                    <thead class="bg-rustic-800 text-parchment font-heading uppercase text-[10px]">
                        <tr>
                            <th class="p-2.5">Waktu Shift</th>
                            <th class="p-2.5">Modal Awal</th>
                            <th class="p-2.5">Penjualan</th>
                            <th class="p-2.5">Kas Akhir Aktual</th>
                            <th class="p-2.5">Catatan / Rekonsiliasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rustic-200">
                        @foreach ($pastShifts as $past)
                            <tr class="hover:bg-rustic-100 transition">
                                <td class="p-2.5">
                                    <div class="font-bold">{{ $past->started_at->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-rustic-500">
                                        {{ $past->started_at->format('H:i') }} – {{ $past->ended_at ? $past->ended_at->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="p-2.5">Rp {{ number_format($past->starting_cash, 0, ',', '.') }}</td>
                                <td class="p-2.5 font-bold text-copper">Rp {{ number_format($past->total_sales, 0, ',', '.') }}</td>
                                <td class="p-2.5 font-bold">Rp {{ number_format((float)$past->total_cash_actual, 0, ',', '.') }}</td>
                                <td class="p-2.5 text-rustic-600 text-[11px]">{{ $past->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $pastShifts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
