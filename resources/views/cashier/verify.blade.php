@extends('layouts.cashier')

@section('title', 'Verifikasi Kode & QR Pesanan')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:py-10">

    <div class="text-center mb-8">
        <h1 class="font-display font-bold text-3xl text-rustic-900">
            Verifikasi Kode & QR Pesanan
        </h1>
        <p class="font-body italic text-xs text-rustic-600 mt-1">
            Pindai QR code pelanggan atau masukkan kode pesanan secara manual
        </p>
    </div>

    <!-- Search Form -->
    <div class="bg-[#FFF8F0] border-2 border-copper p-6 rounded-sm shadow-rustic mb-8">
        <form method="GET" action="{{ route('cashier.verify') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text"
                       name="code"
                       value="{{ $code }}"
                       required
                       autofocus
                       placeholder="Masukkan kode pesanan (contoh: CF-20417)..."
                       class="w-full bg-parchment border-2 border-rustic-400 font-display font-bold text-lg px-4 py-3 uppercase text-rustic-900 rounded-sm focus:border-copper focus:outline-none">
            </div>

            <button type="submit"
                    class="bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading text-xs uppercase tracking-widest px-8 py-3 shadow-rustic transition font-bold">
                CARI PESANAN
            </button>
        </form>
    </div>

    @if (! empty($code))
        @if ($order)
            <!-- Found Order Card -->
            <div class="bg-[#FFF8F0] border-2 border-copper p-6 sm:p-8 rounded-sm shadow-rustic-lg space-y-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b-2 border-rustic-300 gap-4">
                    <div>
                        <span class="text-xs font-heading uppercase text-copper block font-bold">Kode Tiket Terverifikasi:</span>
                        <h2 class="font-display font-black text-3xl text-rustic-900">{{ $order->order_code }}</h2>
                        <span class="text-xs text-rustic-500 font-body">Didaftarkan {{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                    </div>

                    <div class="text-left sm:text-right">
                        @php
                            $statusStyles = [
                                'menunggu' => 'border-rustic-500 text-rustic-700 bg-rustic-100',
                                'dibayar' => 'border-info text-info bg-info/10',
                                'diterima' => 'border-copper text-copper bg-rustic-200 font-bold',
                                'diproses' => 'border-warning text-warning bg-warning/10 font-bold',
                                'siap' => 'border-[#2D6A4F] text-[#2D6A4F] bg-[#2D6A4F]/10 font-bold',
                                'selesai' => 'border-[#2D6A4F] text-[#2D6A4F] bg-[#2D6A4F]/20 font-bold',
                                'batal' => 'border-brick text-brick bg-brick/10',
                            ];
                            $badge = $statusStyles[$order->status] ?? 'border-rustic-400 text-rustic-600';
                        @endphp
                        <span class="inline-block border {{ $badge }} font-heading text-xs uppercase tracking-wider px-3 py-1">
                            ● Status: {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Customer & QR Details -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center">
                    <div class="sm:col-span-2 space-y-2 text-xs font-body">
                        <div>
                            <span class="text-rustic-500 block uppercase font-heading text-[10px]">Nama Pelanggan</span>
                            <strong class="text-sm text-rustic-900">{{ $order->customer_name ?? 'Pelanggan' }}</strong>
                        </div>
                        <div>
                            <span class="text-rustic-500 block uppercase font-heading text-[10px]">Nomor Kontak</span>
                            <span class="text-rustic-800">{{ $order->customer_phone ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-rustic-500 block uppercase font-heading text-[10px]">Metode & Status Pembayaran</span>
                            <span class="font-bold text-copper uppercase">{{ $order->payment_method === 'cash' ? 'Bayar di Kasir' : 'Online Gateway' }}</span>
                            ({{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }})
                        </div>
                    </div>

                    <!-- QR code display -->
                    <div class="text-center p-3 bg-parchment border border-rustic-300 rounded-sm">
                        {!! $qrCodeSvg !!}
                        <span class="block text-[10px] font-heading uppercase text-rustic-500 mt-1">QR Valid</span>
                    </div>
                </div>

                <!-- Items Breakdown -->
                <div class="border-t border-rustic-300 pt-4">
                    <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-rustic-800 mb-3">
                        Daftar Menu Pesanan
                    </h3>
                    <div class="space-y-2 divide-y divide-rustic-200 text-xs font-body">
                        @foreach ($order->items as $item)
                            <div class="pt-2 first:pt-0 flex justify-between items-start">
                                <div>
                                    <strong class="text-rustic-900">{{ $item->quantity }}×</strong> {{ $item->name }}
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

                    <div class="border-t-2 border-rustic-300 mt-4 pt-3 flex justify-between items-center">
                        <span class="font-heading uppercase text-xs font-bold text-rustic-700">Total Tagihan:</span>
                        <span class="font-display font-bold text-2xl text-copper">
                            {{ $order->formatted_total }}
                        </span>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="pt-4 border-t-2 border-rustic-300 flex flex-wrap gap-3">
                    @if ($order->status === 'menunggu' && $order->payment_method === 'cash')
                        <form method="POST" action="{{ route('cashier.order.pay', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="payment_method" value="cash">
                            <input type="hidden" name="cash_given" value="{{ $order->total_amount }}">
                            <button type="submit"
                                    class="w-full bg-copper text-rustic-900 border border-rustic-700 font-heading text-xs uppercase py-3 font-bold hover:bg-copper-light shadow-rustic-sm transition">
                                💵 TERIMA PEMBAYARAN DI KASIR
                            </button>
                        </form>
                    @endif

                    @if ($order->status === 'diterima')
                        <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="diproses">
                            <button type="submit"
                                    class="w-full bg-rustic-800 text-parchment border border-copper font-heading text-xs uppercase py-3 font-bold hover:bg-rustic-700 shadow-rustic-sm transition">
                                ⚙️ PROSES RACIK
                            </button>
                        </form>
                    @elseif ($order->status === 'diproses')
                        <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="siap">
                            <button type="submit"
                                    class="w-full bg-[#2D6A4F] text-parchment font-heading text-xs uppercase py-3 font-bold hover:opacity-90 shadow-sm transition">
                                ☕ TANDAI SIAP DISAJIKAN
                            </button>
                        </form>
                    @elseif ($order->status === 'siap')
                        <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit"
                                    class="w-full bg-[#2D6A4F] text-parchment font-heading text-xs uppercase py-3 font-bold hover:opacity-90 shadow-sm transition">
                                ✓ SELESAI / SERAHKAN
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('orders.receipt', $order->order_code) }}"
                       target="_blank"
                       class="px-5 py-3 bg-parchment border-2 border-rustic-500 font-heading text-xs uppercase text-rustic-800 hover:bg-rustic-200 transition">
                        🖨 Cetak Struk
                    </a>
                </div>
            </div>
        @else
            <!-- Not Found Alert -->
            <div class="bg-[#FFF8F0] border-2 border-brick p-8 rounded-sm text-center shadow-sm space-y-2">
                <span class="text-4xl text-brick">⚠</span>
                <h3 class="font-display font-bold text-xl text-brick">Pesanan Tidak Ditemukan</h3>
                <p class="font-body text-xs text-rustic-600">
                    Tidak ditemukan pesanan dengan kode <strong>{{ $code }}</strong>. Pastikan kode yang dimasukkan sudah sesuai.
                </p>
            </div>
        @endif
    @endif

</div>
@endsection
