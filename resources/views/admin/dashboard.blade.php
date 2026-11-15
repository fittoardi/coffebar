@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b-2 border-copper/30 pb-4 gap-4">
        <div>
            <span class="text-xs uppercase font-heading tracking-widest text-copper block">Ikhtisar Bisnis Kedai</span>
            <h1 class="text-3xl font-display font-black text-rustic-900 tracking-tight">Dashboard Administrasi</h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.create') }}"
               class="px-4 py-2 bg-copper text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-copper-dark transition">
                + Tambah Produk
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="px-4 py-2 border-2 border-rustic-800 text-rustic-800 font-heading text-xs uppercase tracking-wider font-bold hover:bg-rustic-800 hover:text-parchment transition">
                Lihat Laporan
            </a>
        </div>
    </div>

    <!-- METRICS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Hari Ini -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm relative overflow-hidden">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Omzet Hari Ini</div>
            <div class="text-2xl font-display font-black text-copper mt-1">
                Rp {{ number_format($todaySales, 0, ',', '.') }}
            </div>
            <div class="text-xs text-rustic-500 mt-2 font-heading">
                {{ $todayOrdersCount }} pesanan masuk
            </div>
            <div class="absolute -right-2 -bottom-2 text-rustic-200 text-5xl font-black select-none pointer-events-none opacity-40">
                ☕
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm relative overflow-hidden">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Omzet Bulan Ini</div>
            <div class="text-2xl font-display font-black text-rustic-900 mt-1">
                Rp {{ number_format($monthSales, 0, ',', '.') }}
            </div>
            <div class="text-xs text-rustic-500 mt-2 font-heading">
                {{ $monthOrdersCount }} total pesanan
            </div>
            <div class="absolute -right-2 -bottom-2 text-rustic-200 text-5xl font-black select-none pointer-events-none opacity-40">
                📅
            </div>
        </div>

        <!-- Produk Aktif -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm relative overflow-hidden">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Katalog Menu</div>
            <div class="text-2xl font-display font-black text-rustic-900 mt-1">
                {{ $activeProductsCount }} <span class="text-sm font-normal text-rustic-500">/ {{ $totalProductsCount }} Aktif</span>
            </div>
            <div class="text-xs text-rustic-500 mt-2 font-heading">
                <a href="{{ route('admin.products.index') }}" class="text-copper underline hover:text-copper-dark">Kelola ketersediaan menu →</a>
            </div>
            <div class="absolute -right-2 -bottom-2 text-rustic-200 text-5xl font-black select-none pointer-events-none opacity-40">
                📋
            </div>
        </div>

        <!-- Staf Kasir -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-5 shadow-rustic-sm relative overflow-hidden">
            <div class="text-[11px] font-heading uppercase tracking-wider text-rustic-600">Tim & Barista</div>
            <div class="text-2xl font-display font-black text-rustic-900 mt-1">
                {{ $staffCount }} <span class="text-sm font-normal text-rustic-500">Orang</span>
            </div>
            <div class="text-xs text-rustic-500 mt-2 font-heading">
                <a href="{{ route('admin.staff.index') }}" class="text-copper underline hover:text-copper-dark">Kelola akun staf →</a>
            </div>
            <div class="absolute -right-2 -bottom-2 text-rustic-200 text-5xl font-black select-none pointer-events-none opacity-40">
                👥
            </div>
        </div>
    </div>

    <!-- TWO COLUMN: TOP SELLING & RECENT ORDERS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 5 TOP SELLING PRODUCTS -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm">
            <div class="flex items-center justify-between border-b border-rustic-200 pb-3 mb-4">
                <h2 class="font-display font-bold text-lg text-rustic-900">⭐ Menu Terlaris</h2>
                <span class="text-[10px] font-heading uppercase tracking-wider text-copper font-bold">Terfavorit</span>
            </div>

            @if ($topProducts->isEmpty())
                <div class="p-4 text-center text-xs text-rustic-500 font-heading">
                    Belum ada data pesanan selesai tercatat.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($topProducts as $index => $item)
                        <div class="flex items-center justify-between p-2.5 bg-rustic-50 border border-rustic-200 text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="w-6 h-6 rounded-full bg-rustic-800 text-parchment font-heading font-black text-[11px] flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <div class="font-bold text-rustic-900">{{ $item->name }}</div>
                                    <div class="text-[11px] text-rustic-600">{{ $item->total_qty }} porsi terjual</div>
                                </div>
                            </div>
                            <div class="text-right font-display font-bold text-copper">
                                Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- RECENT ORDERS -->
        <div class="lg:col-span-2 bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm">
            <div class="flex items-center justify-between border-b border-rustic-200 pb-3 mb-4">
                <h2 class="font-display font-bold text-lg text-rustic-900">🕒 Pesanan Terbaru</h2>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-copper underline font-heading">Semua Pesanan →</a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="p-8 text-center text-xs text-rustic-500 font-heading">
                    Belum ada transaksi pesanan yang masuk.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b-2 border-rustic-800 bg-rustic-100 font-heading uppercase tracking-wider text-rustic-700">
                                <th class="p-2.5">Kode</th>
                                <th class="p-2.5">Pelanggan</th>
                                <th class="p-2.5">Sumber</th>
                                <th class="p-2.5">Status</th>
                                <th class="p-2.5 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rustic-200">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-rustic-50/70 transition">
                                    <td class="p-2.5 font-mono font-bold text-rustic-900">
                                        #{{ $order->order_code }}
                                    </td>
                                    <td class="p-2.5">
                                        <div class="font-bold text-rustic-900">{{ $order->user ? $order->user->name : 'Walk-in Kasir' }}</div>
                                        <div class="text-[10px] text-rustic-500">{{ $order->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-2.5 font-heading uppercase text-[10px]">
                                        <span class="px-2 py-0.5 rounded {{ $order->source === 'online' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-800 border border-slate-300' }}">
                                            {{ $order->source }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 font-heading text-[10px] uppercase font-bold">
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
                                        <span class="px-2 py-0.5 border {{ $badgeColors[$order->status] ?? 'bg-slate-100 text-slate-800 border-slate-300' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-right font-display font-bold text-copper">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
