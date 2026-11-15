@extends('layouts.public')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">

    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="font-display font-black text-3xl sm:text-4xl text-rustic-900 tracking-tight">
            Pesanan Saya
        </h1>
        <div class="text-copper text-sm tracking-widest my-2 select-none">
            ❧ ─────── ✦ ─────── ❧
        </div>
        <p class="font-body italic text-rustic-600 text-sm">
            Pantau status pesanan aktif dan riwayat transaksi seduhan Anda
        </p>
    </div>

    @if ($orders->isEmpty())
        <div class="bg-[#FFF8F0] border-2 border-dashed border-rustic-400 rounded-sm p-12 text-center shadow-rustic space-y-4">
            <span class="text-5xl select-none">📋</span>
            <h2 class="font-display font-bold text-2xl text-rustic-900">
                Belum Ada Riwayat Pesanan
            </h2>
            <p class="font-body text-sm text-rustic-600 max-w-sm mx-auto">
                Anda belum pernah melakukan pemesanan. Jelajahi menu kami atau racik minuman kustom pertama Anda!
            </p>
            <div class="pt-2">
                <a href="{{ route('menu.index') }}"
                   class="inline-block bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading text-xs uppercase tracking-widest px-6 py-3 shadow-rustic transition">
                    PESAN SEKARANG
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                @php
                    $statusStyles = [
                        'menunggu' => 'border-rustic-400 text-rustic-600 bg-rustic-100',
                        'dibayar' => 'border-info text-info bg-info/10',
                        'diterima' => 'border-copper text-copper bg-rustic-200',
                        'diproses' => 'border-warning text-warning bg-warning/10',
                        'siap' => 'border-success text-success bg-success/10 font-bold',
                        'selesai' => 'border-success text-success bg-success/20 font-bold',
                        'batal' => 'border-brick text-brick bg-brick/10',
                    ];
                    $badgeStyle = $statusStyles[$order->status] ?? 'border-rustic-400 text-rustic-600';
                @endphp

                <div class="bg-[#FFF8F0] border-2 border-rustic-500 rounded-sm p-5 sm:p-6 shadow-rustic hover:shadow-rustic-lg transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <!-- Left: Order Code, Date & Items -->
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center space-x-3">
                            <span class="font-display font-black text-xl text-rustic-900">
                                {{ $order->order_code }}
                            </span>
                            <span class="inline-block border {{ $badgeStyle }} font-heading text-[10px] uppercase tracking-wider px-2 py-0.5">
                                ● {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="text-xs text-rustic-500 font-body">
                            {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB •
                            Metode: <strong class="text-rustic-800 uppercase">{{ $order->payment_method === 'cash' ? 'Di Kasir' : 'Online' }}</strong>
                        </div>

                        <!-- Item Summary Preview -->
                        <div class="text-xs text-rustic-700 font-body pt-1">
                            @foreach ($order->items as $item)
                                <span class="inline-block mr-2">
                                    {{ $item->quantity }}× {{ $item->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right: Total & Action -->
                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6 border-t sm:border-t-0 pt-3 sm:pt-0 border-rustic-200">
                        <div class="text-left sm:text-right">
                            <span class="block text-[10px] font-heading uppercase text-rustic-500">Total</span>
                            <span class="font-display font-bold text-lg text-copper">
                                {{ $order->formatted_total }}
                            </span>
                        </div>

                        <a href="{{ route('orders.show', $order->order_code) }}"
                           class="bg-rustic-800 text-parchment border border-copper hover:bg-rustic-700 font-heading text-xs uppercase px-4 py-2.5 shadow-rustic-sm transition font-bold">
                            LIHAT TIKET & QR →
                        </a>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="pt-6">
                {{ $orders->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
