@extends('layouts.public')

@section('title', 'Keranjang Pesanan')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-8 lg:px-12 py-12 relative overflow-hidden">
    <!-- Pinggiran Biji Kopi Keranjang: Kiri & Kanan Melayang -->
    <img src="{{ asset('images/beans-falling.png') }}" class="absolute left-2 lg:left-6 top-20 w-24 h-auto object-contain pointer-events-none opacity-40 -rotate-6 select-none hidden lg:block animate-float-slow" alt="" />
    <img src="{{ asset('images/beans-cluster.png') }}" class="absolute right-4 lg:right-6 bottom-12 w-24 h-24 object-contain pointer-events-none opacity-45 rotate-12 select-none hidden lg:block animate-float-reverse" alt="" />

    <!-- Header (Rustic Modern) -->
    <div class="mb-10 border-b border-dashed border-[#CDB48F] pb-6">
        <span class="font-hand text-2xl text-[#B5753A]">Struk Pilihanmu</span>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[#2B1D14] tracking-tight mt-1">
            Keranjang Pesanan
        </h1>
        <p class="text-[#6B5646] text-sm sm:text-base mt-2">
            Periksa kembali pesanan Anda sebelum melanjutkan ke pembayaran.
        </p>
    </div>

    @if ($items->isEmpty())
    <!-- Empty Cart State (03-DESIGN.md Section 20) -->
    <div class="bg-[#FFFDF8] border border-dashed border-[#CDB48F] rounded-2xl p-16 text-center space-y-4 shadow-sm">
        <img src="{{ asset('images/cup-black.png') }}" class="w-24 h-24 object-contain mx-auto drop-shadow-md select-none" alt="Cangkir Kosong" />
        <h2 class="font-display font-semibold text-2xl text-[#2B1D14]">
            Keranjang Anda Masih Kosong
        </h2>
        <p class="font-hand text-2xl text-[#B5753A]">
            "Cangkirmu masih kosong. Yuk pilih sesuatu."
        </p>
        <p class="text-xs sm:text-sm text-[#6B5646] max-w-sm mx-auto">
            Belum ada menu yang Anda masukkan ke keranjang. Jelajahi menu kami atau racik minuman impianmu.
        </p>
        <div class="pt-4 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('menu.index') }}"
                class="bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase tracking-wider font-bold px-6 py-3 rounded-xl transition shadow-warm">
                Lihat Menu
            </a>
            <a href="{{ route('custom.index') }}"
                class="bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4] text-xs uppercase tracking-wider font-semibold px-6 py-3 rounded-xl transition">
                Racik Minuman Sendiri ✦
            </a>
        </div>
    </div>
    @else
    <!-- Cart Items List (03-DESIGN.md Section 17.3: Struk Kertas) -->
    <div class="space-y-6">
        <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl shadow-warm divide-y divide-dashed divide-[#CDB48F]/70 overflow-hidden">
            @foreach ($items as $rowId => $item)
            @php
                $itemName = strtolower($item['name'] ?? '');
                $itemImg = null;
                if (!empty($item['image'])) {
                    $itemImg = $item['image'];
                } elseif (str_contains($itemName, 'croissant')) {
                    $itemImg = asset('images/croissant.png');
                } elseif (str_contains($itemName, 'fries') || str_contains($itemName, 'singkong')) {
                    $itemImg = asset('images/french-fries.png');
                } elseif (str_contains($itemName, 'roti') || str_contains($itemName, 'churros') || str_contains($itemName, 'coklat') || str_contains($itemName, 'pastry') || str_contains($itemName, 'cromboloni')) {
                    $itemImg = asset('images/chocolate-pastry.png');
                } elseif (str_contains($itemName, 'latte') || str_contains($itemName, 'susu') || str_contains($itemName, 'cappuccino') || str_contains($itemName, 'fusion') || str_contains($itemName, 'matcha') || str_contains($itemName, 'tea') || str_contains($itemName, 'chocolate') || str_contains($itemName, 'cokelat') || str_contains($itemName, 'sparkling')) {
                    $itemImg = asset('images/cup-latte.png');
                } elseif (str_contains($itemName, 'espresso') || str_contains($itemName, 'americano') || str_contains($itemName, 'cold brew') || str_contains($itemName, 'kopi')) {
                    $itemImg = asset('images/cup-black.png');
                }
            @endphp
            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 hover:bg-[#F4ECDD]/30 transition">
                <!-- Left: Item Info & Options -->
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-[#F4ECDD] rounded-xl flex items-center justify-center p-1.5 flex-shrink-0 border border-[#CDB48F]/50 mt-0.5 overflow-hidden">
                        @if ($itemImg)
                            <img src="{{ $itemImg }}" class="w-full h-full object-contain" alt="{{ $item['name'] }}">
                        @else
                            <span class="text-xl select-none">
                                @if (str_contains($itemName, 'nasi'))
                                    🍲
                                @else
                                    🍽️
                                @endif
                            </span>
                        @endif
                    </div>
                    <div class="space-y-1.5 flex-1">
                        <h3 class="font-display font-semibold text-lg text-[#2B1D14]">
                            {{ $item['name'] }}
                        </h3>

                        @if (! empty($item['options']))
                        <div class="text-xs text-[#6B5646] space-y-0.5">
                            @if (! empty($item['options']['size']))
                            <div>Ukuran: <strong class="text-[#4A3224]">{{ $item['options']['size'] }}</strong></div>
                            @endif
                            @if (! empty($item['options']['sugar']))
                            <div>Gula: <strong class="text-[#4A3224]">{{ $item['options']['sugar'] }}</strong></div>
                            @endif
                            @if (! empty($item['options']['ice']))
                            <div>Suhu: <strong class="text-[#4A3224]">{{ $item['options']['ice'] }}</strong></div>
                            @endif
                            @if (! empty($item['options']['toppings']) && is_array($item['options']['toppings']))
                            <div>Topping: <strong class="text-[#4A3224]">{{ implode(', ', $item['options']['toppings']) }}</strong></div>
                            @endif
                        </div>
                        @endif

                        @if (! empty($item['notes']))
                        <div class="text-xs font-hand text-xl text-[#B5753A]">
                            Catatan: "{{ $item['notes'] }}"
                        </div>
                        @endif

                        <div class="text-xs text-[#6B5646] pt-1">
                            Satuan: Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Right: Quantity Form & Subtotal & Delete -->
                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6">
                    <!-- Qty Stepper Form -->
                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center border border-[#CDB48F] rounded-xl bg-[#FFFDF8]">
                        @csrf
                        <input type="hidden" name="row_id" value="{{ $rowId }}">
                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                            class="px-3 py-1.5 text-sm font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-l-xl transition">
                            -
                        </button>
                        <span class="px-3 py-1.5 text-xs font-bold text-[#2B1D14]">
                            {{ $item['quantity'] }}
                        </span>
                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                            class="px-3 py-1.5 text-sm font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-r-xl transition">
                            +
                        </button>
                    </form>

                    <!-- Subtotal -->
                    <div class="text-right min-w-[110px]">
                        <span class="block text-[10px] text-[#6B5646] uppercase font-bold tracking-wider">Subtotal</span>
                        <span class="font-display font-bold text-base text-[#2B1D14]">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Remove Button -->
                    <form method="POST" action="{{ route('cart.remove', $rowId) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            title="Hapus Item"
                            class="text-[#B4552F] hover:text-[#2B1D14] p-1.5 rounded-lg hover:bg-[#E8DCC4] text-sm font-bold transition">
                            ✕
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Summary & Actions (Section 17.3) -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-warm">
            <!-- Total Amount -->
            <div>
                <span class="block text-xs uppercase text-[#6B5646] font-bold tracking-wider">
                    Total Pembayaran
                </span>
                <span class="font-display font-bold text-3xl sm:text-4xl text-[#2B1D14] mt-1 block">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')"
                        class="px-4 py-3 border border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4] text-xs uppercase tracking-wider font-semibold rounded-xl transition">
                        Kosongkan
                    </button>
                </form>

                <a href="{{ route('checkout.show') }}"
                    class="flex-1 sm:flex-none text-center bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-3.5 px-7 rounded-xl transition shadow-warm">
                    Lanjut ke Pembayaran →
                </a>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
