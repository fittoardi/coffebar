@extends('layouts.admin')

@section('title', 'Laporan Penjualan & Keuangan')

@section('content')
<div class="space-y-8"
     x-data="{
         range: '{{ request('range', 'this_month') }}',
         customOpen: '{{ request('range') === 'custom' }}'
     }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b-2 border-copper/30 pb-4 gap-4">
        <div>
            <span class="text-xs uppercase font-heading tracking-widest text-copper block">Akuntansi & Analisis Kedai</span>
            <h1 class="text-3xl font-display font-black text-rustic-900 tracking-tight">Laporan Penjualan</h1>
            <p class="text-xs font-heading text-rustic-600 mt-1">Periode Aktif: <strong>{{ $rangeLabel }}</strong></p>
        </div>

        <!-- Export CSV Button -->
        <div>
            <a href="{{ route('admin.reports.export', request()->all()) }}"
               class="px-4 py-2.5 bg-rustic-900 text-copper border-2 border-copper font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-rustic-800 hover:text-parchment transition inline-flex items-center space-x-2">
                <span>📥</span>
                <span>Unduh Berkas CSV (Excel)</span>
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-4 shadow-rustic-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mr-2">Pilih Periode:</span>

                <a href="{{ route('admin.reports.index', ['range' => 'today']) }}"
                   class="px-3 py-1.5 text-xs font-heading uppercase tracking-wider border {{ request('range') === 'today' ? 'bg-copper text-parchment border-copper font-bold' : 'bg-rustic-50 border-rustic-300 text-rustic-700 hover:border-copper' }}">
                    Hari Ini
                </a>

                <a href="{{ route('admin.reports.index', ['range' => '7days']) }}"
                   class="px-3 py-1.5 text-xs font-heading uppercase tracking-wider border {{ request('range') === '7days' ? 'bg-copper text-parchment border-copper font-bold' : 'bg-rustic-50 border-rustic-300 text-rustic-700 hover:border-copper' }}">
                    7 Hari Terakhir
                </a>

                <a href="{{ route('admin.reports.index', ['range' => 'this_month']) }}"
                   class="px-3 py-1.5 text-xs font-heading uppercase tracking-wider border {{ !request('range') || request('range') === 'this_month' ? 'bg-copper text-parchment border-copper font-bold' : 'bg-rustic-50 border-rustic-300 text-rustic-700 hover:border-copper' }}">
                    Bulan Ini
                </a>

                <button type="button" @click="customOpen = !customOpen"
                        class="px-3 py-1.5 text-xs font-heading uppercase tracking-wider border {{ request('range') === 'custom' ? 'bg-copper text-parchment border-copper font-bold' : 'bg-rustic-50 border-rustic-300 text-rustic-700 hover:border-copper' }}">
                    Tanggal Kustom 📅
                </button>
            </div>

            <!-- Custom Date Inputs -->
            <div x-show="customOpen" x-cloak class="pt-3 border-t border-rustic-200 flex flex-wrap items-center gap-3 text-xs font-heading">
                <input type="hidden" name="range" value="custom">

                <div class="flex items-center space-x-2">
                    <label class="text-rustic-600">Dari:</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" required
                           class="bg-parchment-light border border-rustic-300 p-1.5 text-xs font-mono">
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-rustic-600">Sampai:</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" required
                           class="bg-parchment-light border border-rustic-300 p-1.5 text-xs font-mono">
                </div>

                <button type="submit"
                        class="px-4 py-1.5 bg-rustic-800 text-parchment text-xs uppercase font-bold hover:bg-rustic-900 transition">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- METRICS OVERVIEW -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Omzet -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Total Pendapatan (Omzet)</div>
            <div class="text-2xl font-display font-black text-copper mt-1">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-rustic-500 mt-2 font-heading">
                Dari {{ $paidOrdersCount }} pesanan lunas
            </div>
        </div>

        <!-- Rata-rata Transaksi (AOV) -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Rata-Rata Belanja (AOV)</div>
            <div class="text-2xl font-display font-black text-rustic-900 mt-1">
                Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-rustic-500 mt-2 font-heading">
                Rata-rata per tiket transaksi
            </div>
        </div>

        <!-- Pesanan Online -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Jalur Online (Website)</div>
            <div class="text-2xl font-display font-black text-rustic-900 mt-1">
                Rp {{ number_format($onlineRevenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-rustic-500 mt-2 font-heading">
                {{ $onlineCount }} pesanan online
            </div>
        </div>

        <!-- Pesanan Kasir / Walk-in -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Jalur Kasir (Walk-In)</div>
            <div class="text-2xl font-display font-black text-rustic-900 mt-1">
                Rp {{ number_format($cashierRevenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-rustic-500 mt-2 font-heading">
                {{ $cashierCount }} pesanan di tempat
            </div>
        </div>
    </div>

    <!-- TOP SELLING ITEMS IN PERIOD -->
    <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm">
        <h2 class="font-display font-bold text-lg text-rustic-900 border-b border-rustic-200 pb-2 mb-4">
            ⭐ Menu Terlaris Pada Periode Ini
        </h2>

        @if ($topProducts->isEmpty())
            <div class="text-center p-6 text-xs text-rustic-500 font-heading">
                Tidak ada data penjualan produk pada rentang tanggal ini.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($topProducts as $idx => $prod)
                    <div class="p-3 bg-rustic-50 border border-rustic-300 flex items-start space-x-3 text-xs">
                        <span class="w-6 h-6 rounded-full bg-rustic-800 text-parchment font-heading font-black text-[11px] flex items-center justify-center shrink-0">
                            {{ $idx + 1 }}
                        </span>
                        <div class="flex-1">
                            <strong class="font-display font-bold text-rustic-900 block truncate">{{ $prod->name }}</strong>
                            <div class="text-[11px] text-rustic-600 mt-0.5">{{ $prod->total_qty }} porsi terjual</div>
                            <div class="text-copper font-display font-bold mt-1">
                                Rp {{ number_format($prod->total_revenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ORDERS TABLE -->
    <div class="bg-[#FFFDF9] border-2 border-rustic-300 shadow-rustic-sm overflow-hidden">
        <div class="p-4 border-b border-rustic-200 bg-rustic-100 flex items-center justify-between">
            <h2 class="font-display font-bold text-base text-rustic-900">
                📋 Rincian Transaksi Pesanan ({{ $orders->total() }} Pesanan)
            </h2>
        </div>

        @if ($orders->isEmpty())
            <div class="p-8 text-center text-xs text-rustic-500 font-heading">
                Tidak ada pesanan tercatat pada rentang tanggal ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b-2 border-rustic-800 bg-rustic-50 font-heading uppercase tracking-wider text-rustic-800">
                            <th class="p-3">Kode</th>
                            <th class="p-3">Waktu</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Sumber</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Metode Bayar</th>
                            <th class="p-3 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rustic-200">
                        @foreach ($orders as $ord)
                            <tr class="hover:bg-rustic-50/70 transition">
                                <td class="p-3 font-mono font-bold text-rustic-900">
                                    #{{ $ord->order_code }}
                                </td>
                                <td class="p-3 text-[11px] text-rustic-600 font-mono">
                                    {{ $ord->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-3">
                                    <div class="font-bold text-rustic-900">
                                        {{ $ord->user ? $ord->user->name : 'Walk-in Kasir' }}
                                    </div>
                                    @if ($ord->user)
                                        <div class="text-[10px] text-rustic-500 font-mono">{{ $ord->user->phone }}</div>
                                    @endif
                                </td>
                                <td class="p-3 font-heading uppercase text-[10px]">
                                    <span class="px-2 py-0.5 rounded {{ $ord->source === 'online' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-800 border border-slate-300' }}">
                                        {{ $ord->source }}
                                    </span>
                                </td>
                                <td class="p-3 font-heading text-[10px] uppercase font-bold">
                                    @php
                                        $badgeColors = [
                                            'pending_payment' => 'bg-amber-50 text-amber-900 border-amber-400',
                                            'paid' => 'bg-blue-50 text-blue-900 border-blue-400',
                                            'processing' => 'bg-purple-50 text-purple-900 border-purple-400',
                                            'ready' => 'bg-emerald-50 text-emerald-900 border-emerald-400',
                                            'completed' => 'bg-rustic-100 text-rustic-900 border-rustic-400',
                                            'cancelled' => 'bg-rose-50 text-rose-900 border-rose-400',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 border {{ $badgeColors[$ord->status] ?? 'bg-slate-100 text-slate-800 border-slate-300' }}">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="p-3 font-heading text-[11px] uppercase">
                                    {{ $ord->payment ? strtoupper($ord->payment->method) : '—' }}
                                </td>
                                <td class="p-3 text-right font-display font-bold text-copper text-sm">
                                    Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="p-4 border-t border-rustic-200 bg-rustic-50">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
