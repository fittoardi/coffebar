@extends('layouts.admin')

@section('title', 'Laporan Penjualan & Keuangan')

@section('content')
<div class="space-y-8"
     x-data="{
         range: '{{ request('range', 'this_month') }}',
         customOpen: '{{ request('range') === 'custom' }}'
     }">
    <!-- Header (03-DESIGN.md Section 19) -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-dashed border-[#CDB48F] pb-4 gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#B5753A] font-bold block">Akuntansi & Analisis Kedai</span>
            <h1 class="text-3xl font-display font-semibold text-[#2B1D14] tracking-tight mt-1">Laporan Penjualan</h1>
            <p class="text-xs text-[#6B5646] mt-1">Periode Aktif: <strong>{{ $rangeLabel }}</strong></p>
        </div>

        <!-- Export CSV Button -->
        <div>
            <a href="{{ route('admin.reports.export', request()->all()) }}"
               class="px-5 py-2.5 bg-[#2B1D14] text-[#F4ECDD] border border-[#B5753A] text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B5753A] hover:text-white transition inline-flex items-center space-x-2">
                <span>📥</span>
                <span>Unduh Berkas CSV (Excel)</span>
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] p-5 rounded-2xl shadow-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs uppercase tracking-wider text-[#4A3224] font-bold mr-2">Pilih Periode:</span>

                <a href="{{ route('admin.reports.index', ['range' => 'today']) }}"
                   class="px-4 py-2 text-xs uppercase tracking-wider font-bold rounded-xl border transition {{ request('range') === 'today' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]' }}">
                    Hari Ini
                </a>

                <a href="{{ route('admin.reports.index', ['range' => '7days']) }}"
                   class="px-4 py-2 text-xs uppercase tracking-wider font-bold rounded-xl border transition {{ request('range') === '7days' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]' }}">
                    7 Hari Terakhir
                </a>

                <a href="{{ route('admin.reports.index', ['range' => 'this_month']) }}"
                   class="px-4 py-2 text-xs uppercase tracking-wider font-bold rounded-xl border transition {{ !request('range') || request('range') === 'this_month' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]' }}">
                    Bulan Ini
                </a>

                <button type="button" @click="customOpen = !customOpen"
                        class="px-4 py-2 text-xs uppercase tracking-wider font-bold rounded-xl border transition cursor-pointer {{ request('range') === 'custom' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]' }}">
                    Tanggal Kustom 📅
                </button>
            </div>

            <!-- Custom Date Inputs -->
            <div x-show="customOpen" x-cloak class="pt-4 border-t border-dashed border-[#CDB48F] flex flex-wrap items-center gap-3 text-xs">
                <input type="hidden" name="range" value="custom">

                <div class="flex items-center space-x-2">
                    <label class="text-[#6B5646] font-bold">Dari:</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" required
                           class="bg-[#FFFDF8] border border-[#CDB48F] p-2 text-xs font-mono rounded-lg text-[#2B1D14]">
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-[#6B5646] font-bold">Sampai:</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" required
                           class="bg-[#FFFDF8] border border-[#CDB48F] p-2 text-xs font-mono rounded-lg text-[#2B1D14]">
                </div>

                <button type="submit"
                        class="px-5 py-2 bg-[#4A3224] text-[#F4ECDD] text-xs uppercase font-bold rounded-lg hover:bg-[#2B1D14] transition cursor-pointer">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- METRICS OVERVIEW -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Omzet -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Total Pendapatan (Omzet)</div>
            <div class="text-3xl font-display font-bold text-[#B5753A] mt-1.5">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-[#6B5646] mt-2">
                Dari {{ $paidOrdersCount }} pesanan lunas
            </div>
        </div>

        <!-- Rata-rata Transaksi (AOV) -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Rata-Rata Belanja (AOV)</div>
            <div class="text-3xl font-display font-bold text-[#2B1D14] mt-1.5">
                Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-[#6B5646] mt-2">
                Rata-rata per tiket transaksi
            </div>
        </div>

        <!-- Pesanan Online -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Jalur Online (Website)</div>
            <div class="text-3xl font-display font-bold text-[#2B1D14] mt-1.5">
                Rp {{ number_format($onlineRevenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-[#6B5646] mt-2">
                {{ $onlineCount }} pesanan online
            </div>
        </div>

        <!-- Pesanan Kasir / Walk-in -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Jalur Kasir (Walk-In)</div>
            <div class="text-3xl font-display font-bold text-[#2B1D14] mt-1.5">
                Rp {{ number_format($cashierRevenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-[#6B5646] mt-2">
                {{ $cashierCount }} pesanan di tempat
            </div>
        </div>
    </div>

    <!-- TOP SELLING ITEMS IN PERIOD -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm">
        <h2 class="font-display font-semibold text-lg text-[#2B1D14] border-b border-dashed border-[#CDB48F] pb-3 mb-4">
            ⭐ Menu Terlaris Pada Periode Ini
        </h2>

        @if ($topProducts->isEmpty())
            <div class="text-center p-6 text-xs text-[#6B5646]">
                Tidak ada data penjualan produk pada rentang tanggal ini.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($topProducts as $idx => $prod)
                    <div class="p-4 bg-[#F4ECDD]/50 border border-[#CDB48F] rounded-xl flex items-start space-x-3 text-xs">
                        <span class="w-6 h-6 rounded-full bg-[#2B1D14] text-[#F4ECDD] font-display font-bold text-[11px] flex items-center justify-center shrink-0">
                            {{ $idx + 1 }}
                        </span>
                        <div class="flex-1">
                            <strong class="font-display font-semibold text-[#2B1D14] block truncate text-sm">{{ $prod->name }}</strong>
                            <div class="text-[11px] text-[#6B5646] mt-0.5">{{ $prod->total_qty }} porsi terjual</div>
                            <div class="text-[#B5753A] font-display font-bold mt-1 text-sm">
                                Rp {{ number_format($prod->total_revenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ORDERS TABLE -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-dashed border-[#CDB48F] bg-[#E8DCC4] flex items-center justify-between">
            <h2 class="font-display font-semibold text-base text-[#2B1D14]">
                📋 Rincian Transaksi Pesanan ({{ $orders->total() }} Pesanan)
            </h2>
        </div>

        @if ($orders->isEmpty())
            <div class="p-8 text-center text-xs text-[#6B5646]">
                Tidak ada pesanan tercatat pada rentang tanggal ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#E8DCC4]/60 uppercase tracking-wider text-[#4A3224] text-[10px] font-bold">
                            <th class="p-3.5">Kode</th>
                            <th class="p-3.5">Waktu</th>
                            <th class="p-3.5">Pelanggan</th>
                            <th class="p-3.5">Sumber</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Metode Bayar</th>
                            <th class="p-3.5 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dashed divide-[#CDB48F]/60">
                        @foreach ($orders as $ord)
                            <tr class="hover:bg-[#F4ECDD]/50 transition">
                                <td class="p-3.5 font-mono font-bold text-[#2B1D14]">
                                    #{{ $ord->order_code }}
                                </td>
                                <td class="p-3.5 text-[11px] text-[#6B5646] font-mono">
                                    {{ $ord->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-3.5">
                                    <div class="font-bold text-[#2B1D14]">{{ $ord->user ? $ord->user->name : 'Walk-in Kasir' }}</div>
                                    @if ($ord->user)
                                        <div class="text-[10px] text-[#6B5646] font-mono">{{ $ord->user->phone }}</div>
                                    @endif
                                </td>
                                <td class="p-3.5 uppercase text-[10px]">
                                    <span class="px-2.5 py-0.5 rounded-full font-bold {{ $ord->source === 'online' ? 'bg-[#E8DCC4] text-[#4A3224]' : 'bg-[#7A8467]/20 text-[#4F5A3E]' }}">
                                        {{ $ord->source }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-[10px] uppercase font-bold">
                                    <span class="px-2.5 py-0.5 rounded-full border border-[#CDB48F] bg-[#FFFDF8] text-[#2B1D14]">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="p-3.5 uppercase text-[11px]">
                                    {{ $ord->payment ? strtoupper($ord->payment->method) : '—' }}
                                </td>
                                <td class="p-3.5 text-right font-display font-bold text-[#2B1D14] text-sm">
                                    Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="p-4 border-t border-dashed border-[#CDB48F]">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
