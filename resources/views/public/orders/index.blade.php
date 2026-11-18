@extends('layouts.public')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
            Pesanan Saya
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Pantau status pesanan aktif dan riwayat transaksi Anda.
        </p>
    </div>

    @if ($orders->isEmpty())
        <div class="bg-white border border-gray-200 rounded-lg p-12 text-center space-y-4">
            <span class="text-4xl block">📋</span>
            <h2 class="font-bold text-xl text-gray-900">
                Belum Ada Riwayat Pesanan
            </h2>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">
                Anda belum pernah melakukan pemesanan. Jelajahi menu kami sekarang.
            </p>
            <div class="pt-2">
                <a href="{{ route('menu.index') }}"
                   class="inline-block bg-blue-600 text-white hover:bg-blue-700 text-xs font-medium px-5 py-2.5 rounded-md transition">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="bg-white border border-gray-200 rounded-lg p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <!-- Left: Order Code, Date & Items -->
                    <div class="space-y-1 flex-1">
                        <div class="flex items-center space-x-3">
                            <span class="font-bold text-lg text-gray-900">
                                {{ $order->order_code }}
                            </span>
                            <span class="inline-block bg-gray-100 text-gray-800 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB •
                            Metode: <strong class="text-gray-700 uppercase">{{ $order->payment_method === 'cash' ? 'Di Kasir' : 'Online' }}</strong>
                        </div>

                        <!-- Item Summary Preview -->
                        <div class="text-xs text-gray-600 pt-1">
                            @foreach ($order->items as $item)
                                <span class="inline-block mr-2">
                                    {{ $item->quantity }}× {{ $item->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right: Total & Action -->
                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6 border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-100">
                        <div class="text-left sm:text-right">
                            <span class="block text-[10px] uppercase text-gray-400">Total</span>
                            <span class="font-bold text-lg text-gray-900">
                                {{ $order->formatted_total }}
                            </span>
                        </div>

                        <a href="{{ route('orders.show', $order->order_code) }}"
                           class="bg-blue-600 text-white hover:bg-blue-700 text-xs font-medium px-4 py-2 rounded-md transition">
                            Lihat Tiket & QR
                        </a>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="pt-4">
                {{ $orders->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
