@extends('layouts.public')

@section('title', 'Konfirmasi Pesanan & Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Header (Rustic Modern) -->
    <div class="mb-10 border-b border-dashed border-[#CDB48F] pb-6">
        <span class="font-hand text-2xl text-[#B5753A]">Langkah Terakhir</span>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[#2B1D14] tracking-tight mt-1">
            Checkout & Pembayaran
        </h1>
        <p class="text-[#6B5646] text-sm sm:text-base mt-2">
            Pilih metode pembayaran dan konfirmasi rincian pesanan Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
        @csrf

        <!-- 1. Data Pemesan -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 sm:p-7 shadow-sm">
            <h2 class="font-display text-base font-semibold text-[#2B1D14] mb-4 border-b border-dashed border-[#CDB48F] pb-3 flex items-center justify-between">
                <span>1. Data Pelanggan</span>
                <span class="font-hand text-xl text-[#B5753A]">Identitas Pemesan</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                <div>
                    <span class="text-[#6B5646] block mb-1 uppercase tracking-wider font-semibold">Nama Lengkap</span>
                    <strong class="font-display text-base text-[#2B1D14] font-semibold">{{ $user->name }}</strong>
                </div>
                <div>
                    <span class="text-[#6B5646] block mb-1 uppercase tracking-wider font-semibold">Email</span>
                    <span class="text-sm text-[#4A3224] font-medium">{{ $user->email }}</span>
                </div>
                <div>
                    <span class="text-[#6B5646] block mb-1 uppercase tracking-wider font-semibold">Nomor WhatsApp</span>
                    <span class="text-sm text-[#4A3224] font-medium">{{ $user->phone }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Ringkasan Pesanan (03-DESIGN.md Section 17.3: Rincian Struk) -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 sm:p-7 shadow-sm">
            <h2 class="font-display text-base font-semibold text-[#2B1D14] mb-4 border-b border-dashed border-[#CDB48F] pb-3">
                2. Rincian Pesanan
            </h2>

            <div class="divide-y divide-dashed divide-[#CDB48F]/70">
                @foreach ($items as $item)
                    <div class="py-3.5 flex justify-between items-start text-xs">
                        <div class="space-y-1">
                            <strong class="font-display text-[#2B1D14] text-base font-semibold">{{ $item['name'] }}</strong>
                            <span class="text-[#6B5646] font-bold">× {{ $item['quantity'] }}</span>

                            @if (! empty($item['options']))
                                <div class="text-[#6B5646]">
                                    {{ implode(' · ', array_filter([
                                        $item['options']['size'] ?? null,
                                        $item['options']['sugar'] ?? null,
                                        $item['options']['ice'] ?? null,
                                        !empty($item['options']['toppings']) ? implode(', ', (array)$item['options']['toppings']) : null,
                                    ])) }}
                                </div>
                            @endif

                            @if (! empty($item['notes']))
                                <div class="font-hand text-lg text-[#B5753A]">
                                    Catatan: {{ $item['notes'] }}
                                </div>
                            @endif
                        </div>

                        <div class="font-display font-bold text-base text-[#2B1D14]">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-dashed border-[#CDB48F] pt-5 mt-3 flex justify-between items-center">
                <span class="text-xs uppercase font-bold tracking-wider text-[#6B5646]">Total Tagihan</span>
                <span class="font-display font-bold text-2xl sm:text-3xl text-[#2B1D14]">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- 3. Pilihan Metode Pembayaran (03-DESIGN.md Section 17.3: 2 Kartu Besar) -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 sm:p-7 shadow-sm"
             x-data="{ method: 'cash' }">
            <h2 class="font-display text-base font-semibold text-[#2B1D14] mb-4 border-b border-dashed border-[#CDB48F] pb-3">
                3. Metode Pembayaran
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Option A: Bayar di Kasir -->
                <label class="border p-5 rounded-xl cursor-pointer transition flex flex-col justify-between"
                       :class="method === 'cash' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A]' : 'bg-[#FFFDF8] border-[#CDB48F] hover:bg-[#E8DCC4]/40'">
                    <div>
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="cash" x-model="method" class="text-[#B5753A] focus:ring-[#B5753A]">
                            <span class="font-display font-bold text-sm text-[#2B1D14]">
                                🧾 Bayar di Kasir (Kode & QR)
                            </span>
                        </div>
                        <p class="text-xs text-[#6B5646] mt-2.5 leading-relaxed">
                            Dapatkan kode pesanan dan QR Code. Tunjukkan kepada kasir kami untuk membayar tunai, debit, atau QRIS saat tiba di kedai.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-dashed border-[#CDB48F]/60 text-[11px] text-[#7A8467] font-semibold">
                        ✓ Bayar saat ambil pesanan
                    </div>
                </label>

                <!-- Option B: Bayar Online -->
                <label class="border p-5 rounded-xl cursor-pointer transition flex flex-col justify-between"
                       :class="method === 'online' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A]' : 'bg-[#FFFDF8] border-[#CDB48F] hover:bg-[#E8DCC4]/40'">
                    <div>
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="online" x-model="method" class="text-[#B5753A] focus:ring-[#B5753A]">
                            <span class="font-display font-bold text-sm text-[#2B1D14]">
                                💳 Bayar Online (QRIS / e-Wallet)
                            </span>
                        </div>
                        <p class="text-xs text-[#6B5646] mt-2.5 leading-relaxed">
                            Bayar instan via Payment Gateway (QRIS, GoPay, OVO, Virtual Account). Langsung diproses barista.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-dashed border-[#CDB48F]/60 text-[11px] text-[#4A3224] font-medium">
                        Instant konfirmasi otomatis
                    </div>
                </label>
            </div>
        </div>

        <!-- 4. Catatan Pesanan Tambahan -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 sm:p-7 shadow-sm">
            <label for="order-notes" class="block font-display text-base font-semibold text-[#2B1D14] mb-2">
                4. Catatan Tambahan (Opsional)
            </label>
            <textarea name="notes"
                      id="order-notes"
                      rows="2"
                      placeholder="Contoh: Tolong sedotan dipisah, parkir motor, dll."
                      class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-3 text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none"></textarea>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-4 rounded-xl transition shadow-warm cursor-pointer">
                Buat Pesanan
            </button>
        </div>
    </form>

</div>
@endsection
