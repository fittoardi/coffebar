@extends('layouts.cashier')

@section('title', 'POS Pesanan Walk-In')

@section('content')
<div class="h-full"
     x-data="{
        cart: [],
        customerName: 'Pelanggan Walk-In',
        paymentMethod: 'cash',
        cashGiven: 0,
        selectedCategory: 'all',

        addToCart(product) {
            let existing = this.cart.find(i => i.product_id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({
                    product_id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    quantity: 1,
                    options: []
                });
            }
            this.updateCashGiven();
        },
        removeFromCart(index) {
            this.cart.splice(index, 1);
            this.updateCashGiven();
        },
        updateQuantity(index, delta) {
            this.cart[index].quantity += delta;
            if (this.cart[index].quantity <= 0) {
                this.removeFromCart(index);
            }
            this.updateCashGiven();
        },
        calculateTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },
        calculateChange() {
            if (this.paymentMethod !== 'cash') return 0;
            return Math.max(0, this.cashGiven - this.calculateTotal());
        },
        updateCashGiven() {
            if (this.cashGiven < this.calculateTotal()) {
                this.cashGiven = this.calculateTotal();
            }
        },
        formatRupiah(num) {
            return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
        }
     }">

    <!-- Header (03-DESIGN.md Section 18) -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-dashed border-[#CDB48F]">
        <div>
            <h1 class="font-display font-semibold text-2xl sm:text-3xl text-[#2B1D14]">
                Point of Sale: Pesanan Walk-In
            </h1>
            <p class="text-xs text-[#6B5646] mt-1">
                Input cepat pesanan pelanggan langsung di meja kasir
            </p>
        </div>

        @if ($activeShift)
            <div class="text-xs text-[#4A3224] bg-[#FFFDF8] px-3.5 py-2 border border-[#CDB48F] rounded-xl shadow-sm">
                <span class="text-[#7A8467] font-bold">● Shift Aktif</span> • Kas Masuk: <strong>Rp {{ number_format($activeShift->total_sales, 0, ',', '.') }}</strong>
            </div>
        @else
            <div class="text-xs text-[#B4552F] bg-[#B4552F]/15 px-3.5 py-2 border border-[#B4552F] rounded-xl font-bold">
                ⚠ Shift belum dibuka
            </div>
        @endif
    </div>

    <!-- POS Split Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT: PRODUCT CATALOG -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Category Tabs -->
            <div class="flex flex-wrap gap-2 border-b border-dashed border-[#CDB48F] pb-3">
                <button type="button"
                        @click="selectedCategory = 'all'"
                        class="px-4 py-2 text-xs uppercase tracking-wider rounded-xl border transition cursor-pointer font-bold"
                        :class="selectedCategory === 'all' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border-[#CDB48F] hover:bg-[#E8DCC4]'">
                    Semua
                </button>
                @foreach ($categories as $cat)
                    <button type="button"
                            @click="selectedCategory = '{{ $cat->slug }}'"
                            class="px-4 py-2 text-xs uppercase tracking-wider rounded-xl border transition cursor-pointer font-bold"
                            :class="selectedCategory === '{{ $cat->slug }}' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border-[#CDB48F] hover:bg-[#E8DCC4]'">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <!-- Product Buttons Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 max-h-[70vh] overflow-y-auto p-1">
                @foreach ($categories as $cat)
                    @foreach ($cat->products as $p)
                        <div x-show="selectedCategory === 'all' || selectedCategory === '{{ $cat->slug }}'"
                             @click="addToCart({{ json_encode($p) }})"
                             class="bg-[#FFFDF8] border border-[#CDB48F] p-3.5 rounded-2xl cursor-pointer shadow-sm hover:shadow-warm hover:border-[#B5753A] transition flex flex-col justify-between h-32 relative group">
                            @if (! $p->is_available)
                                <div class="absolute inset-0 bg-[#2B1D14]/75 text-white flex items-center justify-center text-[10px] font-bold uppercase rounded-2xl backdrop-blur-[1px] z-10">
                                    HABIS
                                </div>
                            @endif
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] uppercase font-bold text-[#B5753A] block">{{ $cat->name }}</span>
                                    <strong class="font-display text-sm text-[#2B1D14] leading-tight block truncate mt-1 group-hover:text-[#B5753A] transition">{{ $p->name }}</strong>
                                </div>
                                @if ($p->image_url)
                                    <img src="{{ $p->image_url }}" class="w-8 h-8 object-contain flex-shrink-0 drop-shadow-sm" alt="{{ $p->name }}">
                                @else
                                    <span class="text-base flex-shrink-0 select-none">
                                        @if (str_contains(strtolower($p->name), 'croissant') || str_contains(strtolower($p->name), 'roti'))
                                            🥐
                                        @elseif (str_contains(strtolower($p->name), 'nasi'))
                                            🍲
                                        @elseif (str_contains(strtolower($p->name), 'fries'))
                                            🍟
                                        @else
                                            🍽️
                                        @endif
                                    </span>
                                @endif
                            </div>
                            <div class="font-display font-bold text-sm text-[#2B1D14] pt-2 border-t border-dashed border-[#CDB48F]/60">
                                {{ $p->formatted_price }}
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- RIGHT: REGISTER CART & PAYMENT (03-DESIGN.md Section 18) -->
        <div class="lg:col-span-5 bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-warm space-y-4">
            <h2 class="uppercase tracking-wider font-bold text-xs text-[#4A3224] border-b border-dashed border-[#CDB48F] pb-3">
                Struk Kasir Sementara
            </h2>

            <!-- Customer Name Input -->
            <div>
                <label class="block uppercase text-[10px] font-bold text-[#6B5646] mb-1.5">
                    Nama Pelanggan (Opsional)
                </label>
                <input type="text"
                       x-model="customerName"
                       placeholder="Contoh: Meja 4 / Pak Budi"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-2.5 text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <!-- Items List -->
            <div class="max-h-56 overflow-y-auto divide-y divide-dashed divide-[#CDB48F]/70 border border-[#CDB48F] rounded-xl bg-[#F4ECDD]/50 p-3">
                <template x-for="(item, idx) in cart" :key="idx">
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex-1 pr-2">
                            <strong class="font-display text-[#2B1D14]" x-text="item.name"></strong>
                            <div class="text-[11px] text-[#6B5646]" x-text="formatRupiah(item.price) + ' × ' + item.quantity"></div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div class="flex items-center border border-[#CDB48F] rounded-lg bg-[#FFFDF8]">
                                <button type="button" @click="updateQuantity(idx, -1)" class="px-2 py-0.5 font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-l-lg">-</button>
                                <span class="px-2 text-xs font-bold text-[#2B1D14]" x-text="item.quantity"></span>
                                <button type="button" @click="updateQuantity(idx, 1)" class="px-2 py-0.5 font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-r-lg">+</button>
                            </div>

                            <span class="font-display font-bold text-xs text-[#2B1D14] min-w-[70px] text-right"
                                  x-text="formatRupiah(item.price * item.quantity)"></span>

                            <button type="button" @click="removeFromCart(idx)" class="text-[#B4552F] hover:text-[#2B1D14] font-bold p-1">✕</button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="py-8 text-center text-xs text-[#6B5646] italic">
                    Belum ada menu yang dipilih. Klik produk di sebelah kiri.
                </div>
            </div>

            <!-- Total Amount -->
            <div class="p-3.5 bg-[#E8DCC4] border border-[#CDB48F] rounded-xl flex justify-between items-center">
                <span class="uppercase text-xs font-bold text-[#4A3224]">Total Tagihan:</span>
                <span class="font-display font-bold text-2xl text-[#2B1D14]"
                      x-text="formatRupiah(calculateTotal())"></span>
            </div>

            <!-- Payment Form -->
            <form method="POST" action="{{ route('cashier.pos.store') }}" class="space-y-3.5"
                  @submit="if (cart.length === 0) { $event.preventDefault(); alert('Pilih minimal satu menu!'); }">
                @csrf
                <input type="hidden" name="customer_name" :value="customerName">
                <input type="hidden" name="payment_method" :value="paymentMethod">
                <input type="hidden" name="cash_given" :value="cashGiven">

                <!-- Hidden inputs for cart items -->
                <template x-for="(item, idx) in cart" :key="idx">
                    <div>
                        <input type="hidden" :name="'items[' + idx + '][product_id]'" :value="item.product_id">
                        <input type="hidden" :name="'items[' + idx + '][name]'" :value="item.name">
                        <input type="hidden" :name="'items[' + idx + '][price]'" :value="item.price">
                        <input type="hidden" :name="'items[' + idx + '][quantity]'" :value="item.quantity">
                    </div>
                </template>

                <!-- Payment Method Buttons -->
                <div>
                    <label class="block uppercase text-[10px] font-bold text-[#6B5646] mb-1.5">
                        Metode Pembayaran
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" @click="paymentMethod = 'cash'"
                                class="p-2.5 border rounded-xl text-xs uppercase text-center transition font-bold cursor-pointer"
                                :class="paymentMethod === 'cash' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border-[#CDB48F]'">
                            Tunai
                        </button>
                        <button type="button" @click="paymentMethod = 'qris'"
                                class="p-2.5 border rounded-xl text-xs uppercase text-center transition font-bold cursor-pointer"
                                :class="paymentMethod === 'qris' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border-[#CDB48F]'">
                            QRIS
                        </button>
                        <button type="button" @click="paymentMethod = 'debit'"
                                class="p-2.5 border rounded-xl text-xs uppercase text-center transition font-bold cursor-pointer"
                                :class="paymentMethod === 'debit' ? 'bg-[#B5753A] text-white border-[#B5753A] shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border-[#CDB48F]'">
                            Debit
                        </button>
                    </div>
                </div>

                <!-- Cash input & change calculation (if cash) -->
                <div x-show="paymentMethod === 'cash'" class="space-y-2">
                    <div>
                        <label class="block uppercase text-[10px] font-bold text-[#6B5646] mb-1">
                            Uang Tunai Diterima (Rp)
                        </label>
                        <input type="number"
                               x-model.number="cashGiven"
                               :min="calculateTotal()"
                               class="w-full bg-[#FFFDF8] border border-[#CDB48F] font-display font-bold text-lg p-2.5 text-[#2B1D14] rounded-xl focus:border-[#B5753A]">
                    </div>

                    <div class="flex justify-between items-center text-xs">
                        <span class="uppercase text-[10px] font-bold text-[#6B5646]">Uang Kembalian:</span>
                        <span class="font-display font-bold text-xl text-[#7A8467]"
                              x-text="formatRupiah(calculateChange())"></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            :disabled="cart.length === 0"
                            class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] disabled:opacity-50 tracking-wider uppercase text-xs font-bold py-4 rounded-xl shadow-warm transition cursor-pointer">
                        SELESAIKAN & CETAK STRUK →
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
