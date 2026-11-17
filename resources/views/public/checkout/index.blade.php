@extends('layouts.public')

@section('title', 'Konfirmasi Pesanan & Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">

    <div class="text-center mb-10">
        <h1 class="font-display font-black text-3xl sm:text-4xl text-rustic-900 tracking-tight">
            Checkout & Pembayaran
        </h1>
        <div class="text-copper text-sm tracking-widest my-2 select-none">
            ❧ ─────── ✦ ─────── ❧
        </div>
        <p class="font-body italic text-rustic-600 text-sm">
            Tentukan metode pembayaran dan periksa rincian pesanan Anda
        </p>
    </div>

    <form method="POST" action="{{ route('checkout.process') }}" class="space-y-8">
        @csrf

        <!-- 1. Data Pemesan -->
        <div class="bg-parchment-dark border-2 border-rustic-900 rounded-sm p-6 shadow-rustic">
            <h2 class="font-heading font-black text-sm uppercase tracking-wider text-rustic-900 border-b-2 border-dashed border-rustic-400 pb-2 mb-4">
                ★ 1. Data Pelanggan Terverifikasi
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-body">
                <div>
                    <span class="text-rustic-600 font-heading uppercase text-[10px] block font-bold">Nama Lengkap</span>
                    <strong class="text-sm text-rustic-900">{{ $user->name }}</strong>
                </div>
                <div>
                    <span class="text-rustic-600 font-heading uppercase text-[10px] block font-bold">Alamat Email</span>
                    <span class="text-sm text-rustic-900">{{ $user->email }}</span>
                </div>
                <div>
                    <span class="text-rustic-600 font-heading uppercase text-[10px] block font-bold">Nomor WhatsApp</span>
                    <span class="text-sm text-rustic-900">{{ $user->phone }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Ringkasan Pesanan -->
        <div class="bg-parchment-dark border-2 border-rustic-900 rounded-sm p-6 shadow-rustic">
            <h2 class="font-heading font-black text-sm uppercase tracking-wider text-rustic-900 border-b-2 border-dashed border-rustic-400 pb-2 mb-4">
                ★ 2. Rincian Pesanan
            </h2>

            <div class="divide-y divide-rustic-200">
                @foreach ($items as $item)
                    <div class="py-3 flex justify-between items-start text-xs font-body">
                        <div>
                            <strong class="text-rustic-900 font-display text-sm">{{ $item['name'] }}</strong>
                            <span class="text-rustic-500">× {{ $item['quantity'] }}</span>

                            @if (! empty($item['options']))
                                <div class="text-[11px] text-rustic-600 mt-0.5">
                                    {{ implode(' | ', array_filter([
                                        $item['options']['size'] ?? null,
                                        $item['options']['sugar'] ?? null,
                                        $item['options']['ice'] ?? null,
                                        !empty($item['options']['toppings']) ? implode(', ', (array)$item['options']['toppings']) : null,
                                    ])) }}
                                </div>
                            @endif

                            @if (! empty($item['notes']))
                                <div class="text-[11px] text-copper italic">
                                    Catatan: {{ $item['notes'] }}
                                </div>
                            @endif
                        </div>

                        <div class="font-display font-bold text-sm text-rustic-900">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t-2 border-dashed border-rustic-400 pt-4 mt-2 flex justify-between items-center">
                <span class="font-heading uppercase text-xs tracking-wider text-rustic-700 font-bold">Total Tagihan:</span>
                <span class="font-display font-black text-2xl sm:text-3xl text-retro-orange">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- 3. Pilihan Metode Pembayaran -->
        <div class="bg-parchment-dark border-2 border-rustic-900 rounded-sm p-6 shadow-rustic"
             x-data="{ method: 'cash' }">
            <h2 class="font-heading font-black text-sm uppercase tracking-wider text-rustic-900 border-b-2 border-dashed border-rustic-400 pb-2 mb-4">
                ★ 3. Pilih Metode Pembayaran
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Option A: Bayar di Kasir (Ambil Kode & QR) -->
                <label class="border-2 border-rustic-900 p-4 rounded-sm cursor-pointer transition flex flex-col justify-between"
                       :class="method === 'cash' ? 'bg-rustic-900 text-parchment shadow-rustic-sm -translate-y-0.5' : 'bg-parchment text-rustic-900 hover:bg-rustic-200'">
                    <div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="cash" x-model="method" class="text-retro-orange focus:ring-retro-orange">
                            <span class="font-heading font-black uppercase text-xs tracking-wider">
                                🏪 Bayar di Kasir (Ambil Kode / QR)
                            </span>
                        </div>
                        <p class="font-body text-xs mt-2 leading-relaxed"
                           :class="method === 'cash' ? 'text-rustic-300' : 'text-rustic-700'">
                            Dapatkan kode pesanan dan QR Code. Tunjukkan kepada kasir kami saat tiba untuk membayar tunai, kartu debit, atau QRIS di tempat.
                        </p>
                    </div>
                    <span class="inline-block mt-3 font-heading text-[10px] uppercase text-retro-mustard font-black">
                        ✓ Disarankan jika ingin bayar tunai
                    </span>
                </label>

                <!-- Option B: Bayar Online (Midtrans Sandbox / QRIS) -->
                <label class="border-2 border-rustic-900 p-4 rounded-sm cursor-pointer transition flex flex-col justify-between"
                       :class="method === 'online' ? 'bg-rustic-900 text-parchment shadow-rustic-sm -translate-y-0.5' : 'bg-parchment text-rustic-900 hover:bg-rustic-200'">
                    <div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="online" x-model="method" class="text-retro-orange focus:ring-retro-orange">
                            <span class="font-heading font-black uppercase text-xs tracking-wider">
                                💳 Bayar Online (QRIS / e-Wallet)
                            </span>
                        </div>
                        <p class="font-body text-xs mt-2 leading-relaxed"
                           :class="method === 'online' ? 'text-rustic-300' : 'text-rustic-700'">
                            Bayar langsung secara instan via Payment Gateway (QRIS, GoPay, OVO, ShopeePay, Virtual Account). Pesanan langsung diproses.
                        </p>
                    </div>
                    <span class="inline-block mt-3 font-heading text-[10px] uppercase text-retro-mustard font-black">
                        ✓ Cepat & Tanpa Uang Tunai
                    </span>
                </label>
            </div>
        </div>

        <!-- 4. Catatan Pesanan Tambahan -->
        <div class="bg-parchment-dark border-2 border-rustic-900 rounded-sm p-6 shadow-rustic">
            <label for="order-notes" class="block font-heading font-black text-sm uppercase tracking-wider text-rustic-900 mb-2">
                ★ 4. Catatan Tambahan untuk Kasir / Barista (Opsional)
            </label>
            <textarea name="notes"
                      id="order-notes"
                      rows="2"
                      placeholder="Contoh: Pesanan diambil jam 12.30, sedotan tidak perlu, atau meja di lantai 2"
                      class="w-full bg-parchment border-2 border-rustic-900 font-body text-xs p-3 text-rustic-900 rounded-sm focus:border-retro-orange focus:outline-none shadow-rustic-sm"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-black tracking-widest uppercase text-sm py-4 shadow-rustic transition duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none">
                BUAT PESANAN SEKARANG →
            </button>
        </div>
    </form>

</div>
@endsection
