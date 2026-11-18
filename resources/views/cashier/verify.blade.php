@extends('layouts.cashier')

@section('title', 'Verifikasi Kode & QR Pesanan')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:py-10">

    <div class="text-center mb-8">
        <h1 class="font-display font-semibold text-3xl text-[#2B1D14]">
            Verifikasi Kode & QR Pesanan
        </h1>
        <p class="text-xs text-[#6B5646] mt-1.5">
            Pindai QR code pelanggan atau masukkan kode pesanan secara manual
        </p>
    </div>

    <!-- Search Form -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm mb-8">
        <form method="GET" action="{{ route('cashier.verify') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text"
                       name="code"
                       value="{{ $code }}"
                       required
                       autofocus
                       placeholder="Masukkan kode pesanan (contoh: CF-20417)..."
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] font-display font-bold text-lg px-4 py-3 uppercase text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
            </div>

            <button type="submit"
                    class="bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-widest px-8 py-3 rounded-xl shadow-warm transition">
                CARI PESANAN
            </button>
        </form>
    </div>

    @if (! empty($code))
        @if ($order)
            <!-- Found Order Card -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-warm space-y-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-dashed border-[#CDB48F] gap-4">
                    <div>
                        <span class="text-xs uppercase text-[#B5753A] block font-bold tracking-wider">Kode Tiket Terverifikasi:</span>
                        <h2 class="font-display font-bold text-3xl text-[#2B1D14]">{{ $order->order_code }}</h2>
                        <span class="text-xs text-[#6B5646]">Didaftarkan {{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                    </div>

                    <div class="text-left sm:text-right">
                        @php
                            $statusStyles = [
                                'menunggu' => 'border-[#CDB48F] text-[#4A3224] bg-[#E8DCC4]',
                                'dibayar' => 'border-[#B5753A] text-[#B5753A] bg-[#B5753A]/10 font-bold',
                                'diterima' => 'border-[#B5753A] text-[#B5753A] bg-[#B5753A]/15 font-bold',
                                'diproses' => 'border-[#B4552F] text-[#B4552F] bg-[#B4552F]/10 font-bold',
                                'siap' => 'border-[#7A8467] text-[#4F5A3E] bg-[#7A8467]/20 font-bold',
                                'selesai' => 'border-[#7A8467] text-white bg-[#7A8467] font-bold',
                                'batal' => 'border-[#2B1D14] text-[#2B1D14] bg-[#1E1A17]/10',
                            ];
                            $badge = $statusStyles[$order->status] ?? 'border-[#CDB48F] text-[#6B5646]';
                        @endphp
                        <span class="inline-block border {{ $badge }} text-xs uppercase tracking-wider px-3.5 py-1.5 rounded-full">
                            ● Status: {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Customer & QR Details -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center">
                    <div class="sm:col-span-2 space-y-2.5 text-xs">
                        <div>
                            <span class="text-[#6B5646] block uppercase text-[10px] font-bold tracking-wider">Nama Pelanggan</span>
                            <strong class="text-base text-[#2B1D14] font-semibold">{{ $order->customer_name ?? 'Pelanggan' }}</strong>
                        </div>
                        <div>
                            <span class="text-[#6B5646] block uppercase text-[10px] font-bold tracking-wider">Nomor Kontak</span>
                            <span class="text-[#4A3224] text-sm">{{ $order->customer_phone ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[#6B5646] block uppercase text-[10px] font-bold tracking-wider">Metode & Status Pembayaran</span>
                            <span class="font-bold text-[#B5753A] uppercase">{{ $order->payment_method === 'cash' ? 'Bayar di Kasir' : 'Online Gateway' }}</span>
                            ({{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }})
                        </div>
                    </div>

                    <!-- QR code display -->
                    <div class="text-center p-4 bg-[#FFFDF8] border border-[#CDB48F] rounded-xl shadow-sm">
                        {!! $qrCodeSvg !!}
                        <span class="block text-[10px] uppercase font-bold text-[#7A8467] mt-2">QR Valid</span>
                    </div>
                </div>

                <!-- Items Breakdown -->
                <div class="border-t border-dashed border-[#CDB48F] pt-4">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-[#4A3224] mb-3">
                        Daftar Menu Pesanan
                    </h3>
                    <div class="space-y-2.5 divide-y divide-dashed divide-[#CDB48F]/60 text-xs">
                        @foreach ($order->items as $item)
                            <div class="pt-2 first:pt-0 flex justify-between items-start">
                                <div>
                                    <strong class="text-[#2B1D14]">{{ $item->quantity }}×</strong> {{ $item->name }}
                                    @if (! empty($item->options))
                                        <div class="text-[11px] text-[#6B5646] italic mt-0.5">
                                            {{ implode(', ', array_filter([
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

                    <div class="border-t border-dashed border-[#CDB48F] mt-4 pt-3.5 flex justify-between items-center">
                        <span class="uppercase text-xs font-bold text-[#4A3224]">Total Tagihan:</span>
                        <span class="font-display font-bold text-2xl text-[#2B1D14]">
                            {{ $order->formatted_total }}
                        </span>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="pt-4 border-t border-dashed border-[#CDB48F] flex flex-wrap gap-3">
                    @if ($order->status === 'menunggu' && $order->payment_method === 'cash')
                        <form method="POST" action="{{ route('cashier.order.pay', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="payment_method" value="cash">
                            <input type="hidden" name="cash_given" value="{{ $order->total_amount }}">
                            <button type="submit"
                                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase py-3.5 px-4 rounded-xl font-bold shadow-warm transition cursor-pointer">
                                💵 TERIMA PEMBAYARAN DI KASIR
                            </button>
                        </form>
                    @endif

                    @if ($order->status === 'diterima')
                        <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="diproses">
                            <button type="submit"
                                    class="w-full bg-[#4A3224] text-[#F4ECDD] hover:bg-[#2B1D14] text-xs uppercase py-3.5 px-4 rounded-xl font-bold shadow-warm transition cursor-pointer">
                                ⚙️ PROSES RACIK
                            </button>
                        </form>
                    @elseif ($order->status === 'diproses')
                        <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="siap">
                            <button type="submit"
                                    class="w-full bg-[#7A8467] text-white hover:opacity-90 text-xs uppercase py-3.5 px-4 rounded-xl font-bold shadow-sm transition cursor-pointer">
                                ☕ TANDAI SIAP DISAJIKAN
                            </button>
                        </form>
                    @elseif ($order->status === 'siap')
                        <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit"
                                    class="w-full bg-[#7A8467] text-white hover:opacity-90 text-xs uppercase py-3.5 px-4 rounded-xl font-bold shadow-sm transition cursor-pointer">
                                ✓ SELESAI / SERAHKAN
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('orders.receipt', $order->order_code) }}"
                       target="_blank"
                       class="px-5 py-3.5 bg-[#FFFDF8] border border-[#CDB48F] text-xs uppercase font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-xl transition">
                        🖨 Cetak Struk
                    </a>
                </div>
            </div>
        @else
            <!-- Not Found Alert -->
            <div class="bg-[#FFFDF8] border border-[#B4552F] p-8 rounded-2xl text-center shadow-sm space-y-2">
                <span class="text-4xl text-[#B4552F] block">⚠</span>
                <h3 class="font-display font-semibold text-xl text-[#B4552F]">Pesanan Tidak Ditemukan</h3>
                <p class="text-xs text-[#6B5646]">
                    Tidak ditemukan pesanan dengan kode <strong>{{ $code }}</strong>. Pastikan kode yang dimasukkan sudah sesuai.
                </p>
            </div>
        @endif
    @endif

</div>
@endsection
