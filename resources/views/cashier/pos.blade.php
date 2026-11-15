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

    <!-- Header -->
    <div class="flex items-center justify-between pb-4 mb-4 border-b-2 border-rustic-300">
        <div>
            <h1 class="font-display font-bold text-2xl text-rustic-900">
                Point of Sale: Pesanan Walk-In
            </h1>
            <p class="font-body italic text-xs text-rustic-600">
                Input cepat pesanan pelanggan langsung di meja kasir
            </p>
        </div>

        @if ($activeShift)
            <div class="text-xs font-body text-rustic-700 bg-parchment px-3 py-1.5 border border-copper rounded-xs">
                Shift Aktif • Kas Masuk: <strong>Rp {{ number_format($activeShift->total_sales, 0, ',', '.') }}</strong>
            </div>
        @else
            <div class="text-xs font-body text-brick bg-brick/15 px-3 py-1.5 border border-brick rounded-xs font-bold">
                ⚠ Shift belum dibuka
            </div>
        @endif
    </div>

    <!-- POS Split Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT: PRODUCT CATALOG -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Category Tabs -->
            <div class="flex flex-wrap gap-2 border-b border-rustic-300 pb-2">
                <button type="button"
                        @click="selectedCategory = 'all'"
                        class="px-3 py-1.5 font-heading text-xs uppercase tracking-wider rounded-xs border transition"
                        :class="selectedCategory === 'all' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400 hover:bg-rustic-200'">
                    Semua
                </button>
                @foreach ($categories as $cat)
                    <button type="button"
                            @click="selectedCategory = '{{ $cat->slug }}'"
                            class="px-3 py-1.5 font-heading text-xs uppercase tracking-wider rounded-xs border transition"
                            :class="selectedCategory === '{{ $cat->slug }}' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400 hover:bg-rustic-200'">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <!-- Product Buttons Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-[70vh] overflow-y-auto p-1">
                @foreach ($categories as $cat)
                    @foreach ($cat->products as $p)
                        <div x-show="selectedCategory === 'all' || selectedCategory === '{{ $cat->slug }}'"
                             @click="addToCart({{ json_encode($p) }})"
                             class="bg-[#FFF8F0] border-2 border-rustic-400 p-3 rounded-sm cursor-pointer shadow-rustic-sm hover:shadow-rustic hover:border-copper transition flex flex-col justify-between h-28 relative">
                            @if (! $p->is_available)
                                <div class="absolute inset-0 bg-rustic-900/70 text-parchment flex items-center justify-center font-heading text-[10px] font-bold uppercase rounded-sm">
                                    HABIS
                                </div>
                            @endif
                            <div>
                                <span class="text-[9px] font-heading uppercase text-copper block">{{ $cat->name }}</span>
                                <strong class="font-display text-xs text-rustic-900 leading-tight block truncate">{{ $p->name }}</strong>
                            </div>
                            <div class="font-display font-bold text-xs text-copper">
                                {{ $p->formatted_price }}
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- RIGHT: REGISTER CART & PAYMENT -->
        <div class="lg:col-span-5 bg-[#FFF8F0] border-2 border-copper p-5 rounded-sm shadow-rustic-lg space-y-4">
            <h2 class="font-heading font-bold text-xs uppercase tracking-wider text-rustic-900 border-b border-rustic-300 pb-2">
                Struk Kasir Sementara
            </h2>

            <!-- Customer Name Input -->
            <div>
                <label class="block font-heading uppercase text-[10px] text-rustic-600 mb-1">
                    Nama Pelanggan (Opsional)
                </label>
                <input type="text"
                       x-model="customerName"
                       placeholder="Contoh: Meja 4 / Pak Budi"
                       class="w-full bg-parchment border-2 border-rustic-400 font-body text-xs p-2 text-rustic-900 rounded-sm focus:border-copper focus:outline-none">
            </div>

            <!-- Items List -->
            <div class="max-h-56 overflow-y-auto divide-y divide-rustic-200 border border-rustic-300 rounded-sm bg-parchment p-2">
                <template x-for="(item, idx) in cart" :key="idx">
                    <div class="py-2 flex items-center justify-between text-xs">
                        <div class="flex-1 pr-2">
                            <strong class="font-display text-rustic-900" x-text="item.name"></strong>
                            <div class="text-[11px] text-rustic-600" x-text="formatRupiah(item.price) + ' × ' + item.quantity"></div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div class="flex items-center border border-rustic-500 rounded-xs bg-[#FFF8F0]">
                                <button type="button" @click="updateQuantity(idx, -1)" class="px-2 py-0.5 font-bold hover:bg-rustic-200">-</button>
                                <span class="px-2 text-xs font-bold" x-text="item.quantity"></span>
                                <button type="button" @click="updateQuantity(idx, 1)" class="px-2 py-0.5 font-bold hover:bg-rustic-200">+</button>
                            </div>

                            <span class="font-display font-bold text-xs text-rustic-900 min-w-[65px] text-right"
                                  x-text="formatRupiah(item.price * item.quantity)"></span>

                            <button type="button" @click="removeFromCart(idx)" class="text-brick font-bold p-1">✕</button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="py-8 text-center text-xs text-rustic-500 italic">
                    Belum ada menu yang dipilih. Klik produk di sebelah kiri.
                </div>
            </div>

            <!-- Total Amount -->
            <div class="p-3 bg-rustic-100 border border-copper rounded-sm flex justify-between items-center">
                <span class="font-heading uppercase text-xs font-bold text-rustic-700">Total Tagihan:</span>
                <span class="font-display font-bold text-2xl text-copper"
                      x-text="formatRupiah(calculateTotal())"></span>
            </div>

            <!-- Payment Form -->
            <form method="POST" action="{{ route('cashier.pos.store') }}" class="space-y-3"
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
                    <label class="block font-heading uppercase text-[10px] text-rustic-600 mb-1">
                        Metode Pembayaran
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" @click="paymentMethod = 'cash'"
                                class="p-2 border rounded-sm font-heading text-xs uppercase text-center transition"
                                :class="paymentMethod === 'cash' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400'">
                            Tunai
                        </button>
                        <button type="button" @click="paymentMethod = 'qris'"
                                class="p-2 border rounded-sm font-heading text-xs uppercase text-center transition"
                                :class="paymentMethod === 'qris' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400'">
                            QRIS
                        </button>
                        <button type="button" @click="paymentMethod = 'debit'"
                                class="p-2 border rounded-sm font-heading text-xs uppercase text-center transition"
                                :class="paymentMethod === 'debit' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400'">
                            Debit
                        </button>
                    </div>
                </div>

                <!-- Cash input & change calculation (if cash) -->
                <div x-show="paymentMethod === 'cash'" class="space-y-2">
                    <div>
                        <label class="block font-heading uppercase text-[10px] text-rustic-600 mb-1">
                            Uang Tunai Diterima (Rp)
                        </label>
                        <input type="number"
                               x-model.number="cashGiven"
                               :min="calculateTotal()"
                               class="w-full bg-parchment border-2 border-rustic-400 font-display font-bold text-base p-2 text-rustic-900 rounded-sm focus:border-copper">
                    </div>

                    <div class="flex justify-between items-center text-xs font-body">
                        <span class="font-heading uppercase text-[10px] text-rustic-600">Uang Kembalian:</span>
                        <span class="font-display font-bold text-base text-[#2D6A4F]"
                              x-text="formatRupiah(calculateChange())"></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            :disabled="cart.length === 0"
                            class="w-full bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 disabled:opacity-50 font-heading tracking-widest uppercase text-xs py-3.5 shadow-rustic font-bold transition duration-150 active:translate-x-0.5 active:translate-y-0.5">
                        SELESAIKAN & CETAK STRUK →
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
