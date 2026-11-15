@extends('layouts.public')

@section('title', 'Tiket Pesanan ' . $order->order_code)

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-10 sm:py-16">

    <!-- Top Success Alert -->
    <div class="text-center mb-6">
        <span class="inline-block bg-[#2D6A4F]/15 text-[#2D6A4F] border border-[#2D6A4F] font-heading text-xs uppercase tracking-widest px-3 py-1 font-bold mb-2">
            ✓ Pesanan Berhasil Didaftarkan
        </span>
        <h1 class="font-display font-bold text-2xl text-rustic-900">
            Tiket Pesanan Kedai
        </h1>
        <p class="font-body italic text-xs text-rustic-600 mt-1">
            Tunjukkan kode atau pindai QR Code ini kepada kasir kami saat tiba
        </p>
    </div>

    <!-- VINTAGE TICKET CONTAINER -->
    <div class="bg-[#FFF8F0] border-4 border-double border-copper rounded-sm p-6 sm:p-8 shadow-rustic-lg relative overflow-hidden"
         x-data="{ copied: false }">

        <!-- Top Perforation Dots / Sawtooth Effect -->
        <div class="text-center pb-4 border-b-2 border-dashed border-rustic-400">
            <span class="text-2xl font-display font-black text-rustic-900 tracking-wider">
                ☕ COFFEBAR
            </span>
            <div class="text-copper text-xs tracking-widest my-1 select-none">
                ❧ ─────── ✦ ─────── ❧
            </div>
            <p class="text-[11px] font-body text-rustic-500 italic">
                {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>

        <!-- QR Code Centerpiece -->
        <div class="py-6 text-center space-y-4">
            <div class="inline-block p-3 bg-parchment border-2 border-rustic-700 rounded-sm shadow-inner">
                {!! $qrCodeSvg !!}
            </div>

            <!-- Clickable Order Code -->
            <div>
                <button type="button"
                        @click="navigator.clipboard.writeText('{{ $order->order_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="group inline-flex flex-col items-center focus:outline-none">
                    <span class="font-display font-black text-3xl sm:text-4xl text-rustic-900 tracking-wider group-hover:text-copper transition">
                        {{ $order->order_code }}
                    </span>
                    <span class="text-[10px] font-heading uppercase text-copper mt-1 tracking-wider"
                          x-text="copied ? '✓ KODE DISALIN KE CLIPBOARD!' : 'Ketuk untuk menyalin kode'"></span>
                </button>
            </div>

            <!-- Status Stamp Badge -->
            <div class="pt-1">
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
                <span class="inline-block border-2 {{ $badgeStyle }} font-heading text-xs uppercase tracking-widest px-3 py-1 font-bold -rotate-1 shadow-sm">
                    ● Status: {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <!-- Middle Ticket Perforation Divider -->
        <div class="relative flex items-center justify-between -mx-6 sm:-mx-8 my-2">
            <div class="w-4 h-8 bg-rustic-100 rounded-r-full border-r-2 border-y-2 border-copper"></div>
            <div class="flex-1 border-t-2 border-dashed border-rustic-400 mx-2"></div>
            <div class="w-4 h-8 bg-rustic-100 rounded-l-full border-l-2 border-y-2 border-copper"></div>
        </div>

        <!-- Items Breakdown -->
        <div class="pt-4 space-y-3 font-body text-xs">
            <div class="space-y-2 divide-y divide-rustic-200">
                @foreach ($order->items as $item)
                    <div class="pt-2 first:pt-0 flex justify-between items-start">
                        <div>
                            <span class="font-bold text-rustic-900">{{ $item->name }}</span>
                            <span class="text-rustic-500">× {{ $item->quantity }}</span>
                            @if (! empty($item->options))
                                <div class="text-[10px] text-rustic-600 italic">
                                    {{ implode(', ', array_filter([
                                        $item->options['size'] ?? null,
                                        $item->options['sugar'] ?? null,
                                        $item->options['ice'] ?? null,
                                        !empty($item->options['toppings']) ? implode(', ', (array)$item->options['toppings']) : null,
                                    ])) }}
                                </div>
                            @endif
                        </div>
                        <span class="font-display font-bold text-rustic-900">
                            {{ $item->formatted_subtotal }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Total & Payment Method -->
            <div class="border-t-2 border-rustic-300 pt-3 space-y-1">
                <div class="flex justify-between text-xs font-heading uppercase text-rustic-600">
                    <span>Metode Bayar:</span>
                    <strong class="text-rustic-900">
                        {{ $order->payment_method === 'cash' ? 'Bayar di Kasir' : 'Online Gateway' }}
                    </strong>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="font-heading uppercase text-xs tracking-wider text-rustic-900 font-bold">Total:</span>
                    <span class="font-display font-bold text-xl text-copper">
                        {{ $order->formatted_total }}
                    </span>
                </div>
            </div>

            @if ($order->notes)
                <div class="p-2 bg-parchment border border-rustic-300 rounded-sm text-[11px] italic text-rustic-700">
                    Catatan: "{{ $order->notes }}"
                </div>
            @endif
        </div>

        <!-- Ticket Footer Notice -->
        <div class="border-t border-dashed border-rustic-400 mt-6 pt-4 text-center text-[10px] text-rustic-500 font-body">
            Simpan tiket ini atau buka kembali sewaktu-waktu melalui menu <strong>Pesanan Saya</strong>.
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex flex-col sm:flex-row gap-3 text-center">
        @auth
            <a href="{{ route('orders.index') }}"
               class="flex-1 bg-parchment text-rustic-800 border-2 border-rustic-700 hover:bg-rustic-200 font-heading text-xs uppercase tracking-wider py-3 shadow-rustic-sm transition">
                ← DAFTAR PESANAN SAYA
            </a>
        @endauth
        <a href="{{ route('orders.receipt', $order->order_code) }}"
           target="_blank"
           class="flex-1 bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading text-xs uppercase tracking-wider py-3 shadow-rustic transition font-bold">
            🖨 CETAK STRUK DIGITAL
        </a>
    </div>

</div>
@endsection
