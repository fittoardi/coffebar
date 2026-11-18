@extends('layouts.public')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
            Checkout & Pembayaran
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Pilih metode pembayaran dan konfirmasi rincian pesanan Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
        @csrf

        <!-- 1. Data Pemesan -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">
                1. Data Pelanggan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-gray-500 block mb-1">Nama Lengkap</span>
                    <strong class="text-sm text-gray-900 font-semibold">{{ $user->name }}</strong>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1">Email</span>
                    <span class="text-sm text-gray-900">{{ $user->email }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1">Nomor WhatsApp</span>
                    <span class="text-sm text-gray-900">{{ $user->phone }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Ringkasan Pesanan -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">
                2. Rincian Pesanan
            </h2>

            <div class="divide-y divide-gray-100">
                @foreach ($items as $item)
                    <div class="py-3 flex justify-between items-start text-xs">
                        <div>
                            <strong class="text-gray-900 text-sm font-semibold">{{ $item['name'] }}</strong>
                            <span class="text-gray-500">× {{ $item['quantity'] }}</span>

                            @if (! empty($item['options']))
                                <div class="text-gray-500 mt-0.5">
                                    {{ implode(' | ', array_filter([
                                        $item['options']['size'] ?? null,
                                        $item['options']['sugar'] ?? null,
                                        $item['options']['ice'] ?? null,
                                        !empty($item['options']['toppings']) ? implode(', ', (array)$item['options']['toppings']) : null,
                                    ])) }}
                                </div>
                            @endif

                            @if (! empty($item['notes']))
                                <div class="text-blue-600 italic mt-0.5">
                                    Catatan: {{ $item['notes'] }}
                                </div>
                            @endif
                        </div>

                        <div class="font-bold text-sm text-gray-900">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-200 pt-4 mt-2 flex justify-between items-center">
                <span class="text-xs uppercase font-medium text-gray-500">Total Tagihan:</span>
                <span class="font-bold text-2xl text-gray-900">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- 3. Pilihan Metode Pembayaran -->
        <div class="bg-white border border-gray-200 rounded-lg p-6"
             x-data="{ method: 'cash' }">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">
                3. Metode Pembayaran
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Option A: Bayar di Kasir -->
                <label class="border p-4 rounded-lg cursor-pointer transition flex flex-col justify-between"
                       :class="method === 'cash' ? 'bg-blue-50 border-blue-600' : 'bg-white border-gray-200 hover:bg-gray-50'">
                    <div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="cash" x-model="method" class="text-blue-600 focus:ring-blue-500">
                            <span class="font-semibold text-xs text-gray-900">
                                Bayar di Kasir (Kode & QR)
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                            Dapatkan kode pesanan dan QR Code. Tunjukkan kepada kasir kami untuk membayar tunai, debit, atau QRIS di tempat.
                        </p>
                    </div>
                </label>

                <!-- Option B: Bayar Online -->
                <label class="border p-4 rounded-lg cursor-pointer transition flex flex-col justify-between"
                       :class="method === 'online' ? 'bg-blue-50 border-blue-600' : 'bg-white border-gray-200 hover:bg-gray-50'">
                    <div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="online" x-model="method" class="text-blue-600 focus:ring-blue-500">
                            <span class="font-semibold text-xs text-gray-900">
                                Bayar Online (QRIS / e-Wallet)
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                            Bayar langsung via Payment Gateway (QRIS, GoPay, OVO, Virtual Account).
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <!-- 4. Catatan Pesanan Tambahan -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <label for="order-notes" class="block text-sm font-semibold text-gray-900 mb-2">
                4. Catatan Tambahan (Opsional)
            </label>
            <textarea name="notes"
                      id="order-notes"
                      rows="2"
                      placeholder="Contoh: Tolong sedotan dipisah, dll."
                      class="w-full bg-white border border-gray-300 text-xs p-3 text-gray-900 rounded-md focus:border-blue-500 focus:outline-none"></textarea>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white hover:bg-blue-700 font-medium text-sm py-3 rounded-md transition cursor-pointer">
                Buat Pesanan
            </button>
        </div>
    </form>

</div>
@endsection
