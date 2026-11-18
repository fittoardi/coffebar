@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8">
    <!-- Header Page (03-DESIGN.md Section 19) -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-dashed border-[#CDB48F] pb-4 gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#B5753A] font-bold block">Ikhtisar Bisnis Kedai</span>
            <h1 class="text-3xl font-display font-semibold text-[#2B1D14] tracking-tight mt-1">Dashboard Administrasi</h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.create') }}"
               class="px-5 py-2.5 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition">
                + Tambah Produk
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="px-5 py-2.5 border border-[#4A3224] text-[#4A3224] text-xs uppercase tracking-wider font-bold rounded-xl hover:bg-[#4A3224] hover:text-[#F4ECDD] transition">
                Lihat Laporan
            </a>
        </div>
    </div>

    <!-- METRICS GRID (03-DESIGN.md Section 19: 4 Kartu Metrik) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Hari Ini -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Omzet Hari Ini</div>
            <div class="text-3xl font-display font-bold text-[#B5753A] mt-1.5">
                Rp {{ number_format($todaySales, 0, ',', '.') }}
            </div>
            <div class="text-xs text-[#6B5646] mt-2">
                {{ $todayOrdersCount }} pesanan masuk
            </div>
            <div class="absolute -right-2 -bottom-2 text-[#CDB48F] text-5xl font-black select-none pointer-events-none opacity-20">
                ☕
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Omzet Bulan Ini</div>
            <div class="text-3xl font-display font-bold text-[#2B1D14] mt-1.5">
                Rp {{ number_format($monthSales, 0, ',', '.') }}
            </div>
            <div class="text-xs text-[#6B5646] mt-2">
                {{ $monthOrdersCount }} total pesanan
            </div>
            <div class="absolute -right-2 -bottom-2 text-[#CDB48F] text-5xl font-black select-none pointer-events-none opacity-20">
                📅
            </div>
        </div>

        <!-- Produk Aktif -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Katalog Menu</div>
            <div class="text-3xl font-display font-bold text-[#2B1D14] mt-1.5">
                {{ $activeProductsCount }} <span class="text-sm font-normal text-[#6B5646]">/ {{ $totalProductsCount }} Aktif</span>
            </div>
            <div class="text-xs text-[#6B5646] mt-2">
                <a href="{{ route('admin.products.index') }}" class="text-[#B5753A] underline hover:text-[#B4552F]">Kelola ketersediaan menu →</a>
            </div>
            <div class="absolute -right-2 -bottom-2 text-[#CDB48F] text-5xl font-black select-none pointer-events-none opacity-20">
                📋
            </div>
        </div>

        <!-- Staf Kasir -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="text-[11px] uppercase tracking-wider font-bold text-[#6B5646]">Tim & Barista</div>
            <div class="text-3xl font-display font-bold text-[#2B1D14] mt-1.5">
                {{ $staffCount }} <span class="text-sm font-normal text-[#6B5646]">Orang</span>
            </div>
            <div class="text-xs text-[#6B5646] mt-2">
                <a href="{{ route('admin.staff.index') }}" class="text-[#B5753A] underline hover:text-[#B4552F]">Kelola akun staf →</a>
            </div>
            <div class="absolute -right-2 -bottom-2 text-[#CDB48F] text-5xl font-black select-none pointer-events-none opacity-20">
                👥
            </div>
        </div>
    </div>

    <!-- TWO COLUMN: TOP SELLING & RECENT ORDERS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 5 TOP SELLING PRODUCTS -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between border-b border-dashed border-[#CDB48F] pb-3 mb-4">
                <h2 class="font-display font-semibold text-lg text-[#2B1D14]">⭐ Menu Terlaris</h2>
                <span class="text-[10px] uppercase tracking-wider text-[#B5753A] font-bold">Terfavorit</span>
            </div>

            @if ($topProducts->isEmpty())
                <div class="p-6 text-center text-xs text-[#6B5646]">
                    Belum ada data pesanan selesai tercatat.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($topProducts as $index => $item)
                        <div class="flex items-center justify-between p-3 bg-[#F4ECDD]/50 border border-[#CDB48F] rounded-xl text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="w-6 h-6 rounded-full bg-[#2B1D14] text-[#F4ECDD] font-display font-bold text-[11px] flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <div class="font-bold text-[#2B1D14]">{{ $item->name }}</div>
                                    <div class="text-[11px] text-[#6B5646]">{{ $item->total_qty }} porsi terjual</div>
                                </div>
                            </div>
                            <div class="text-right font-display font-bold text-[#B5753A]">
                                Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- RECENT ORDERS -->
        <div class="lg:col-span-2 bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between border-b border-dashed border-[#CDB48F] pb-3 mb-4">
                <h2 class="font-display font-semibold text-lg text-[#2B1D14]">🕒 Pesanan Terbaru</h2>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-[#B5753A] underline font-medium">Semua Pesanan →</a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="p-8 text-center text-xs text-[#6B5646]">
                    Belum ada transaksi pesanan yang masuk.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-[#E8DCC4] uppercase tracking-wider text-[#4A3224] text-[10px] font-bold">
                                <th class="p-3 rounded-l-lg">Kode</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Sumber</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right rounded-r-lg">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dashed divide-[#CDB48F]/60">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-[#F4ECDD]/40 transition">
                                    <td class="p-3 font-mono font-bold text-[#2B1D14]">
                                        #{{ $order->order_code }}
                                    </td>
                                    <td class="p-3">
                                        <div class="font-bold text-[#2B1D14]">{{ $order->user ? $order->user->name : 'Walk-in Kasir' }}</div>
                                        <div class="text-[10px] text-[#6B5646]">{{ $order->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-3 uppercase text-[10px]">
                                        <span class="px-2 py-0.5 rounded-full font-bold {{ $order->source === 'online' ? 'bg-[#E8DCC4] text-[#4A3224]' : 'bg-[#7A8467]/20 text-[#4F5A3E]' }}">
                                            {{ $order->source }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-[10px] uppercase font-bold">
                                        <span class="px-2.5 py-0.5 rounded-full border border-[#CDB48F] bg-[#FFFDF8] text-[#2B1D14]">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right font-display font-bold text-[#2B1D14]">
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
