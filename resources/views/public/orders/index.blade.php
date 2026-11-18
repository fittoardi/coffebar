@extends('layouts.public')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Header (Rustic Modern) -->
    <div class="mb-10 border-b border-dashed border-[#CDB48F] pb-6">
        <span class="font-hand text-2xl text-[#B5753A]">Buku Catatan Kedai</span>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[#2B1D14] tracking-tight mt-1">
            Pesanan Saya
        </h1>
        <p class="text-[#6B5646] text-sm sm:text-base mt-2">
            Pantau status pesanan aktif dan riwayat seduhan yang pernah Anda nikmati.
        </p>
    </div>

    @if ($orders->isEmpty())
        <div class="bg-[#FFFDF8] border border-dashed border-[#CDB48F] rounded-2xl p-16 text-center space-y-4 shadow-sm">
            <span class="text-5xl block select-none">📋</span>
            <h2 class="font-display font-semibold text-2xl text-[#2B1D14]">
                Belum Ada Riwayat Pesanan
            </h2>
            <p class="font-hand text-2xl text-[#B5753A]">
                "Ayo mulai petualangan rasamu hari ini."
            </p>
            <p class="text-xs sm:text-sm text-[#6B5646] max-w-sm mx-auto">
                Anda belum pernah melakukan pemesanan. Jelajahi menu kami atau racik minuman pilihanmu sekarang.
            </p>
            <div class="pt-4">
                <a href="{{ route('menu.index') }}"
                   class="inline-block bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase tracking-wider font-bold px-7 py-3 rounded-xl transition shadow-warm">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 hover:shadow-warm transition duration-200">
                    <!-- Left: Order Code, Date & Items -->
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center space-x-3">
                            <span class="font-display font-bold text-xl text-[#2B1D14]">
                                {{ $order->order_code }}
                            </span>
                            @php
                                $statusConfig = [
                                    'menunggu' => ['bg' => 'bg-[#E8DCC4]', 'text' => 'text-[#2B1D14]', 'icon' => '⏳'],
                                    'diterima' => ['bg' => 'bg-[#B5753A]/20', 'text' => 'text-[#4A3224]', 'icon' => '📥'],
                                    'diproses' => ['bg' => 'bg-[#B4552F]/20', 'text' => 'text-[#B4552F]', 'icon' => '🔥'],
                                    'siap' => ['bg' => 'bg-[#7A8467]/25', 'text' => 'text-[#4F5A3E]', 'icon' => '✓'],
                                    'selesai' => ['bg' => 'bg-[#7A8467]', 'text' => 'text-white', 'icon' => '✓✓'],
                                    'dibatalkan' => ['bg' => 'bg-[#1E1A17]/10', 'text' => 'text-[#1E1A17]', 'icon' => '✕'],
                                ];
                                $cur = $statusConfig[$order->status] ?? ['bg' => 'bg-[#E8DCC4]', 'text' => 'text-[#2B1D14]', 'icon' => '•'];
                            @endphp
                            <span class="inline-flex items-center space-x-1 {{ $cur['bg'] }} {{ $cur['text'] }} text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                <span>{{ $cur['icon'] }}</span>
                                <span>{{ ucfirst($order->status) }}</span>
                            </span>
                        </div>

                        <div class="text-xs text-[#6B5646]">
                            {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB •
                            Metode: <strong class="text-[#4A3224] uppercase">{{ $order->payment_method === 'cash' ? 'Di Kasir' : 'Online' }}</strong>
                        </div>

                        <!-- Item Summary Preview -->
                        <div class="text-xs text-[#4A3224] pt-1">
                            @foreach ($order->items as $item)
                                <span class="inline-block mr-2 font-medium">
                                    {{ $item->quantity }}× {{ $item->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right: Total & Action -->
                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6 border-t sm:border-t-0 pt-4 sm:pt-0 border-dashed border-[#CDB48F]">
                        <div class="text-left sm:text-right">
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-[#6B5646]">Total</span>
                            <span class="font-display font-bold text-xl text-[#2B1D14]">
                                {{ $order->formatted_total }}
                            </span>
                        </div>

                        <a href="{{ route('orders.show', $order->order_code) }}"
                           class="bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase tracking-wider font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
                            Lihat Tiket & QR
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
