@extends('layouts.public')

@section('title', 'Tiket Pesanan ' . $order->order_code)

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-12">

    <!-- Top Alert & Greeting -->
    <div class="text-center mb-8">
        <span class="inline-flex items-center space-x-1.5 bg-[#E8DCC4] text-[#7A8467] border border-[#CDB48F] text-xs font-bold uppercase tracking-wider px-3.5 py-1 rounded-full mb-3 shadow-sm">
            <span>✓</span>
            <span>Pesanan Berhasil Didaftarkan</span>
        </span>
        <h1 class="font-display text-3xl font-semibold text-[#2B1D14]">
            Tiket Pesanan
        </h1>
        <p class="font-hand text-2xl text-[#B5753A] mt-1">
            "Tunjukkan kode ini ke kasir"
        </p>
    </div>

    <!-- KRAFT TICKET CONTAINER (03-DESIGN.md Section 17.4: Tiket/Kupon Kraft) -->
    <div class="bg-[#FFFDF8] border-2 border-dashed border-[#CDB48F] rounded-2xl p-6 sm:p-8 space-y-6 shadow-warm relative"
         x-data="{ copied: false }">

        <!-- Header -->
        <div class="text-center pb-4 border-b border-dashed border-[#CDB48F]">
            <span class="font-display font-bold text-2xl text-[#2B1D14] tracking-tight">
                ☕ COFFEBAR
            </span>
            <p class="text-xs text-[#6B5646] mt-1">
                {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>

        <!-- QR Code Centerpiece -->
        <div class="text-center space-y-4">
            <div class="inline-block p-4 bg-[#FFFDF8] border-2 border-[#CDB48F] rounded-2xl shadow-sm">
                {!! $qrCodeSvg !!}
            </div>

            <!-- Clickable Order Code (Fraunces Display) -->
            <div>
                <button type="button"
                        @click="navigator.clipboard.writeText('{{ $order->order_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex flex-col items-center focus:outline-none group">
                    <span class="font-display font-bold text-3xl sm:text-4xl text-[#B5753A] group-hover:text-[#B4552F] tracking-widest transition">
                        {{ $order->order_code }}
                    </span>
                    <span class="text-xs text-[#6B5646] mt-1 font-medium"
                          x-text="copied ? '✓ Kode berhasil disalin!' : 'Klik untuk menyalin kode'"></span>
                </button>
            </div>

            <!-- Status Pill (Section 20) -->
            <div>
                @php
                    $statusConfig = [
                        'menunggu' => ['bg' => 'bg-[#E8DCC4]', 'text' => 'text-[#2B1D14]', 'icon' => '⏳', 'label' => 'Menunggu Pembayaran'],
                        'diterima' => ['bg' => 'bg-[#B5753A]/20', 'text' => 'text-[#4A3224]', 'icon' => '📥', 'label' => 'Pesanan Diterima'],
                        'diproses' => ['bg' => 'bg-[#B4552F]/20', 'text' => 'text-[#B4552F]', 'icon' => '🔥', 'label' => 'Sedang Diproses'],
                        'siap' => ['bg' => 'bg-[#7A8467]/25', 'text' => 'text-[#4F5A3E]', 'icon' => '✓', 'label' => 'Siap Diambil'],
                        'selesai' => ['bg' => 'bg-[#7A8467]', 'text' => 'text-white', 'icon' => '✓✓', 'label' => 'Selesai'],
                        'dibatalkan' => ['bg' => 'bg-[#1E1A17]/10', 'text' => 'text-[#1E1A17]', 'icon' => '✕', 'label' => 'Dibatalkan'],
                    ];
                    $currentStatus = $statusConfig[$order->status] ?? ['bg' => 'bg-[#E8DCC4]', 'text' => 'text-[#2B1D14]', 'icon' => '•', 'label' => ucfirst($order->status)];
                @endphp
                <span class="inline-flex items-center space-x-1.5 {{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full">
                    <span>{{ $currentStatus['icon'] }}</span>
                    <span>Status: {{ $currentStatus['label'] }}</span>
                </span>
            </div>
        </div>

        <!-- Items Breakdown -->
        <div class="border-t border-dashed border-[#CDB48F] pt-4 space-y-3 text-xs">
            <div class="space-y-2.5 divide-y divide-dashed divide-[#CDB48F]/60">
                @foreach ($order->items as $item)
                    <div class="pt-2.5 first:pt-0 flex justify-between items-start">
                        <div>
                            <span class="font-display font-semibold text-[#2B1D14] text-sm">{{ $item->name }}</span>
                            <span class="text-[#6B5646] font-bold">× {{ $item->quantity }}</span>
                            @if (! empty($item->options))
                                <div class="text-[11px] text-[#6B5646] mt-0.5">
                                    {{ implode(' · ', array_filter([
                                        $item->options['size'] ?? null,
                                        $item->options['sugar'] ?? null,
                                        $item->options['ice'] ?? null,
                                        !empty($item->options['toppings']) ? implode(', ', (array)$item->options['toppings']) : null,
                                    ])) }}
                                </div>
                            @endif
                        </div>
                        <span class="font-display font-bold text-sm text-[#2B1D14]">
                            {{ $item->formatted_subtotal }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Total & Payment Method -->
            <div class="border-t border-dashed border-[#CDB48F] pt-3.5 space-y-1.5">
                <div class="flex justify-between text-xs text-[#6B5646]">
                    <span>Metode Bayar:</span>
                    <strong class="text-[#2B1D14]">
                        {{ $order->payment_method === 'cash' ? 'Bayar di Kasir' : 'Online Gateway' }}
                    </strong>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="font-bold uppercase tracking-wider text-xs text-[#4A3224]">Total Tagihan:</span>
                    <span class="font-display font-bold text-xl text-[#2B1D14]">
                        {{ $order->formatted_total }}
                    </span>
                </div>
            </div>

            @if ($order->notes)
                <div class="p-3 bg-[#E8DCC4]/60 border border-[#CDB48F] rounded-xl text-xs text-[#4A3224]">
                    Catatan: "{{ $order->notes }}"
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex flex-col sm:flex-row gap-3 text-center">
        @auth
            <a href="{{ route('orders.index') }}"
               class="flex-1 bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4] text-xs uppercase tracking-wider font-semibold py-3 rounded-xl transition">
                ← Daftar Pesanan
            </a>
        @endauth
        <a href="{{ route('orders.receipt', $order->order_code) }}"
           target="_blank"
           class="flex-1 bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase tracking-wider font-bold py-3 rounded-xl transition shadow-warm">
            Cetak Struk →
        </a>
    </div>

</div>
@endsection
