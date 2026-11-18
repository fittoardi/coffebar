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

    <!-- Header (03-DESIGN.md Section 18) -->
    <div class="border-b border-dashed border-[#CDB48F] pb-4">
        <h1 class="font-display font-semibold text-2xl sm:text-3xl text-[#2B1D14]">
            Shift & Rekonsiliasi Kas Harian
        </h1>
        <p class="text-xs text-[#6B5646] mt-1">
            Pencatatan modal kas awal, penerimaan transaksi tunai, dan penghitungan selisih kas fisik
        </p>
    </div>

    <!-- Active Shift Card or Start Shift Form -->
    @if ($activeShift)
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-warm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-dashed border-[#CDB48F]">
                <div>
                    <span class="text-xs uppercase text-[#B5753A] font-bold block tracking-wider">Status Shift:</span>
                    <h2 class="font-display font-semibold text-2xl text-[#2B1D14]">
                        Shift Sedang Aktif (Berjalan)
                    </h2>
                </div>
                <span class="bg-[#7A8467] text-white text-xs uppercase px-3.5 py-1.5 font-bold rounded-full shadow-sm">
                    ● Terbuka
                </span>
            </div>

            <!-- Shift Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div class="p-4 bg-[#F4ECDD] border border-[#CDB48F] rounded-xl">
                    <span class="text-[#6B5646] uppercase text-[10px] font-bold tracking-wider block">Waktu Mulai</span>
                    <strong class="text-sm text-[#2B1D14]">{{ $activeShift->started_at->format('H:i') }} WIB</strong>
                    <div class="text-[10px] text-[#6B5646] mt-0.5">{{ $activeShift->started_at->diffForHumans() }}</div>
                </div>

                <div class="p-4 bg-[#F4ECDD] border border-[#CDB48F] rounded-xl">
                    <span class="text-[#6B5646] uppercase text-[10px] font-bold tracking-wider block">Modal Kas Awal</span>
                    <strong class="text-sm text-[#2B1D14]">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</strong>
                </div>

                <div class="p-4 bg-[#F4ECDD] border border-[#CDB48F] rounded-xl">
                    <span class="text-[#6B5646] uppercase text-[10px] font-bold tracking-wider block">Total Penjualan Shift</span>
                    <strong class="text-sm text-[#B5753A] font-bold">Rp {{ number_format($activeShift->total_sales, 0, ',', '.') }}</strong>
                </div>

                <div class="p-4 bg-[#E8DCC4] border border-[#CDB48F] rounded-xl">
                    <span class="text-[#4A3224] uppercase text-[10px] block font-bold tracking-wider">Estimasi Kas di Laci</span>
                    <strong class="text-base text-[#2B1D14] font-display font-bold">Rp {{ number_format($activeShift->total_cash_expected, 0, ',', '.') }}</strong>
                </div>
            </div>

            <!-- Form Close Shift & Cash Reconciliation -->
            <form method="POST" action="{{ route('cashier.shifts.close', $activeShift->id) }}" class="space-y-4 pt-4 border-t border-dashed border-[#CDB48F]">
                @csrf

                <h3 class="font-bold text-sm uppercase tracking-wider text-[#2B1D14]">
                    Tutup Shift & Hitung Kas Fisik
                </h3>

                <div>
                    <label class="block uppercase text-xs font-bold text-[#4A3224] mb-1.5">
                        Hitung Uang Fisik di Laci Kasir (Rp)
                    </label>
                    <input type="number"
                           name="total_cash_actual"
                           x-model.number="actualCash"
                           required
                           min="0"
                           class="w-full sm:w-80 bg-[#FFFDF8] border border-[#CDB48F] font-display font-bold text-xl p-3 text-[#2B1D14] rounded-xl focus:border-[#B5753A]">
                    <span class="text-[11px] text-[#6B5646] italic block mt-1">
                        Keluarkan seluruh uang kas di laci kasir dan masukkan total nominal fisik yang ada.
                    </span>
                </div>

                <!-- Live Variance Display -->
                <div class="p-4 rounded-xl border"
                     :class="calculateVariance() === 0 ? 'bg-[#F4ECDD] border-[#CDB48F]' : (calculateVariance() > 0 ? 'bg-[#7A8467]/15 border-[#7A8467]' : 'bg-[#B4552F]/15 border-[#B4552F]')">
                    <div class="flex justify-between items-center text-xs">
                        <span class="uppercase text-[#4A3224] font-bold">Hasil Rekonsiliasi (Selisih):</span>
                        <span class="font-display font-bold text-lg"
                              :class="calculateVariance() === 0 ? 'text-[#2B1D14]' : (calculateVariance() > 0 ? 'text-[#7A8467]' : 'text-[#B4552F]')"
                              x-text="calculateVariance() === 0 ? '✓ Tepat Seimbang (Rp 0)' : (calculateVariance() > 0 ? '+ Kas Lebih: ' + formatRupiah(calculateVariance()) : '- Kas Kurang: ' + formatRupiah(Math.abs(calculateVariance())))">
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block uppercase text-xs font-bold text-[#4A3224] mb-1.5">
                        Catatan Tutup Shift (Opsional)
                    </label>
                    <input type="text"
                           name="notes"
                           placeholder="Contoh: Selisih uang koin kembalian, laci kasir telah diserahkan ke shift sore"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-3 text-[#2B1D14] rounded-xl focus:border-[#B5753A]">
                </div>

                <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin menutup shift ini?')"
                        class="bg-[#B4552F] text-white tracking-wider uppercase text-xs py-3.5 px-6 rounded-xl hover:opacity-90 font-bold shadow-warm transition cursor-pointer">
                    TUTUP SHIFT & KUNCI KASIR
                </button>
            </form>
        </div>
    @else
        <!-- Open New Shift Form -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-warm space-y-6">
            <div class="border-b border-dashed border-[#CDB48F] pb-4">
                <span class="text-xs uppercase text-[#B5753A] font-bold tracking-wider block">Memulai Kerja Kasir:</span>
                <h2 class="font-display font-semibold text-2xl text-[#2B1D14]">
                    Buka Shift Kasir Baru
                </h2>
                <p class="text-xs text-[#6B5646] mt-1">
                    Pastikan Anda menghitung modal uang receh / kas awal di laci sebelum membuka transaksi
                </p>
            </div>

            <form method="POST" action="{{ route('cashier.shifts.start') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block uppercase text-xs font-bold text-[#4A3224] mb-1.5">
                        Modal Kas Awal di Laci (Rp)
                    </label>
                    <input type="number"
                           name="starting_cash"
                           value="200000"
                           required
                           min="0"
                           placeholder="Contoh: 200000"
                           class="w-full sm:w-80 bg-[#FFFDF8] border border-[#CDB48F] font-display font-bold text-xl p-3 text-[#2B1D14] rounded-xl focus:border-[#B5753A]">
                </div>

                <div>
                    <label class="block uppercase text-xs font-bold text-[#4A3224] mb-1.5">
                        Catatan Pembukaan (Opsional)
                    </label>
                    <input type="text"
                           name="notes"
                           placeholder="Contoh: Shift Pagi, laci kasir bersih"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-3 text-[#2B1D14] rounded-xl focus:border-[#B5753A]">
                </div>

                <button type="submit"
                        class="bg-[#B5753A] text-white hover:bg-[#B4552F] tracking-wider uppercase text-xs py-3.5 px-8 rounded-xl shadow-warm font-bold transition cursor-pointer">
                    BUKA SHIFT SEKARANG
                </button>
            </form>
        </div>
    @endif

    <!-- Past Closed Shifts History Table -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-7 rounded-2xl shadow-sm space-y-4">
        <h3 class="font-bold text-sm uppercase tracking-wider text-[#2B1D14] border-b border-dashed border-[#CDB48F] pb-3">
            Riwayat Shift Sebelumnya
        </h3>

        @if ($pastShifts->isEmpty())
            <div class="py-6 text-center text-xs text-[#6B5646] italic">
                Belum ada riwayat shift yang ditutup.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#2B1D14] text-[#F4ECDD] uppercase text-[10px] tracking-wider font-bold">
                        <tr>
                            <th class="p-3 rounded-l-lg">Waktu Shift</th>
                            <th class="p-3">Modal Awal</th>
                            <th class="p-3">Penjualan</th>
                            <th class="p-3">Kas Akhir Aktual</th>
                            <th class="p-3 rounded-r-lg">Catatan / Rekonsiliasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dashed divide-[#CDB48F]/60">
                        @foreach ($pastShifts as $past)
                            <tr class="hover:bg-[#F4ECDD]/50 transition">
                                <td class="p-3">
                                    <div class="font-bold text-[#2B1D14]">{{ $past->started_at->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-[#6B5646]">
                                        {{ $past->started_at->format('H:i') }} – {{ $past->ended_at ? $past->ended_at->format('H:i') : '-' }}
                                    </div>
                                </td>
                                <td class="p-3">Rp {{ number_format($past->starting_cash, 0, ',', '.') }}</td>
                                <td class="p-3 font-bold text-[#B5753A]">Rp {{ number_format($past->total_sales, 0, ',', '.') }}</td>
                                <td class="p-3 font-bold text-[#2B1D14]">Rp {{ number_format((float)$past->total_cash_actual, 0, ',', '.') }}</td>
                                <td class="p-3 text-[#6B5646] text-[11px]">{{ $past->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-3">
                {{ $pastShifts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
