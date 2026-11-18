@extends('layouts.public')

@section('title', 'Pesanan ' . $order->order_code)

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-10">

    <!-- Top Alert -->
    <div class="text-center mb-6">
        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full mb-2">
            ✓ Pesanan Berhasil Didaftarkan
        </span>
        <h1 class="text-2xl font-bold text-gray-900">
            Detail Pesanan
        </h1>
        <p class="text-xs text-gray-500 mt-1">
            Tunjukkan kode atau QR Code ini kepada kasir saat mengambil/membayar pesanan.
        </p>
    </div>

    <!-- CLEAN TICKET CONTAINER -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6"
         x-data="{ copied: false }">

        <!-- Header -->
        <div class="text-center pb-4 border-b border-gray-100">
            <span class="text-xl font-bold text-gray-900">
                ☕ COFFEBAR
            </span>
            <p class="text-xs text-gray-500 mt-1">
                {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>

        <!-- QR Code Centerpiece -->
        <div class="text-center space-y-4">
            <div class="inline-block p-3 bg-white border border-gray-200 rounded-md">
                {!! $qrCodeSvg !!}
            </div>

            <!-- Clickable Order Code -->
            <div>
                <button type="button"
                        @click="navigator.clipboard.writeText('{{ $order->order_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex flex-col items-center focus:outline-none">
                    <span class="font-bold text-3xl text-blue-600 hover:text-blue-800 tracking-wider transition">
                        {{ $order->order_code }}
                    </span>
                    <span class="text-xs text-gray-400 mt-1"
                          x-text="copied ? '✓ Kode berhasil disalin!' : 'Klik untuk menyalin kode'"></span>
                </button>
            </div>

            <!-- Status Badge -->
            <div>
                <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                    Status: {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <!-- Items Breakdown -->
        <div class="border-t border-gray-100 pt-4 space-y-3 text-xs">
            <div class="space-y-2 divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <div class="pt-2 first:pt-0 flex justify-between items-start">
                        <div>
                            <span class="font-semibold text-gray-900">{{ $item->name }}</span>
                            <span class="text-gray-500">× {{ $item->quantity }}</span>
                            @if (! empty($item->options))
                                <div class="text-[11px] text-gray-500">
                                    {{ implode(', ', array_filter([
                                        $item->options['size'] ?? null,
                                        $item->options['sugar'] ?? null,
                                        $item->options['ice'] ?? null,
                                        !empty($item->options['toppings']) ? implode(', ', (array)$item->options['toppings']) : null,
                                    ])) }}
                                </div>
                            @endif
                        </div>
                        <span class="font-semibold text-gray-900">
                            {{ $item->formatted_subtotal }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Total & Payment Method -->
            <div class="border-t border-gray-200 pt-3 space-y-1">
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Metode Bayar:</span>
                    <strong class="text-gray-900">
                        {{ $order->payment_method === 'cash' ? 'Bayar di Kasir' : 'Online Gateway' }}
                    </strong>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="font-semibold text-gray-900">Total:</span>
                    <span class="font-bold text-lg text-gray-900">
                        {{ $order->formatted_total }}
                    </span>
                </div>
            </div>

            @if ($order->notes)
                <div class="p-2 bg-gray-50 border border-gray-200 rounded text-xs text-gray-600">
                    Catatan: "{{ $order->notes }}"
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex flex-col sm:flex-row gap-3 text-center">
        @auth
            <a href="{{ route('orders.index') }}"
               class="flex-1 bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 text-xs font-medium py-2.5 rounded-md transition">
                ← Daftar Pesanan
            </a>
        @endauth
        <a href="{{ route('orders.receipt', $order->order_code) }}"
           target="_blank"
           class="flex-1 bg-blue-600 text-white hover:bg-blue-700 text-xs font-medium py-2.5 rounded-md transition">
            Cetak Struk →
        </a>
    </div>

</div>
@endsection
