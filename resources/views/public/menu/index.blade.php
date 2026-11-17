@extends('layouts.public')

@section('title', 'Katalog Menu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16"
    x-data="{
        selectedProduct: null,
        modalOpen: false,
        quantity: 1,
        selectedSize: 'Regular (S)',
        selectedSugar: 'Normal (100%)',
        selectedIce: 'Dingin (Es Batu)',
        selectedToppings: [],
        notes: '',
        openModal(product) {
            this.selectedProduct = product;
            this.quantity = 1;
            this.selectedSize = 'Regular (S)';
            this.selectedSugar = 'Normal (100%)';
            this.selectedIce = 'Dingin (Es Batu)';
            this.selectedToppings = [];
            this.notes = '';
            this.modalOpen = true;
        },
        calculateTotal() {
            if (!this.selectedProduct) return 0;
            let total = parseFloat(this.selectedProduct.price);
            if (this.selectedSize === 'Medium (M)') total += 4000;
            if (this.selectedSize === 'Large (L)') total += 8000;
            this.selectedToppings.forEach(t => {
                if (t === 'Boba Brown Sugar') total += 5000;
                else if (t === 'Grass Jelly') total += 4000;
                else if (t === 'Whipped Cream') total += 5000;
                else if (t === 'Caramel Drizzle') total += 3000;
                else if (t === 'Extra Espresso Shot') total += 6000;
                else if (t === 'Oat Milk Sub') total += 7000;
            });
            return total * this.quantity;
        },
        formatRupiah(num) {
            return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
        }
     }">

    <!-- Page Header with Retro Badge -->
    <div class="text-center mb-12">
        <span class="inline-block bg-retro-orange text-parchment font-heading text-[11px] font-black uppercase tracking-widest px-3 py-1 border-2 border-rustic-900 shadow-rustic-sm mb-3 -rotate-1">
            ★ DAFTAR SEDUHAN & KUDAPAN ★
        </span>
        <h1 class="font-display font-black text-4xl sm:text-5xl text-rustic-900 tracking-tight">
            Menu Kami
        </h1>
        <div class="text-retro-orange text-sm tracking-widest my-2 select-none flex items-center justify-center gap-3">
            <span class="h-0.5 w-12 bg-retro-orange/60 inline-block"></span>
            <span class="text-retro-mustard font-bold">❧ ✦ ❧</span>
            <span class="h-0.5 w-12 bg-retro-orange/60 inline-block"></span>
        </div>
        <p class="font-body italic text-rustic-700 text-sm sm:text-base max-w-lg mx-auto">
            Semua minuman dan kudapan diracik segar dari bahan-bahan terbaik setiap harinya.
        </p>
    </div>

    <!-- Filter Tabs (Retro Ticket Style) & Search Box -->
    <div class="flex flex-col lg:flex-row items-center justify-between gap-4 mb-10 pb-6 border-b-2 border-dashed border-rustic-400">
        <!-- Ticket-style category tabs -->
        <div class="flex flex-wrap gap-2 justify-center">
            <a href="{{ route('menu.index') }}"
                class="px-4 py-2 text-xs font-heading font-black uppercase tracking-wider border-2 border-rustic-900 transition {{ empty($selectedCategorySlug) ? 'bg-retro-orange text-parchment shadow-rustic-sm -translate-y-0.5' : 'bg-parchment text-rustic-900 hover:bg-parchment-dark' }}">
                Semua Menu
            </a>
            @foreach ($categories as $cat)
            <a href="{{ route('menu.index', ['kategori' => $cat->slug]) }}"
                class="px-4 py-2 text-xs font-heading font-black uppercase tracking-wider border-2 border-rustic-900 transition {{ $selectedCategorySlug === $cat->slug ? 'bg-retro-orange text-parchment shadow-rustic-sm -translate-y-0.5' : 'bg-parchment text-rustic-900 hover:bg-parchment-dark' }}">
                @if ($cat->icon === 'coffee') ☕ @elseif ($cat->icon === 'cake') 🥐 @else 🥤 @endif
                {{ $cat->name }} ({{ $cat->products_count }})
            </a>
            @endforeach
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('menu.index') }}" class="w-full lg:w-72">
            @if ($selectedCategorySlug)
            <input type="hidden" name="kategori" value="{{ $selectedCategorySlug }}">
            @endif
            <div class="relative">
                <input type="text"
                    name="q"
                    value="{{ $searchQuery }}"
                    placeholder="🔍 Cari menu favorit..."
                    class="w-full bg-parchment border-2 border-rustic-900 font-body px-3.5 py-2 text-sm text-rustic-900 rounded-sm shadow-rustic-sm focus:border-retro-orange focus:outline-none">
                @if ($searchQuery)
                <a href="{{ route('menu.index', array_filter(['kategori' => $selectedCategorySlug])) }}"
                    class="absolute right-2.5 top-2.5 text-xs text-rustic-600 hover:text-rustic-950 font-bold">
                    ✕ Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Grid -->
    @if ($products->isEmpty())
    <div class="bg-parchment border-4 border-dashed border-rustic-900 rounded-sm p-12 text-center my-8 shadow-rustic">
        <span class="text-5xl block">☕</span>
        <h3 class="font-display font-black text-2xl text-rustic-900 mt-3">Menu Tidak Ditemukan</h3>
        <p class="font-body text-xs text-rustic-700 mt-1">
            Silakan coba kata kunci lain atau pilih kategori yang berbeda.
        </p>
        <div class="mt-4">
            <a href="{{ route('menu.index') }}" class="inline-block bg-retro-orange text-parchment border-2 border-rustic-900 font-heading font-bold text-xs uppercase px-4 py-2 shadow-rustic-sm hover:bg-copper-light hover:text-rustic-900 transition">
                Kembali ke Semua Menu
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($products as $idx => $product)
        <div class="bg-parchment-dark border-2 border-rustic-900 rounded-sm p-4 shadow-rustic hover:-translate-y-1.5 hover:shadow-rustic-lg transition-all duration-300 flex flex-col justify-between relative group">

            <!-- Retro Sticker on Product -->
            <div class="absolute -top-3 left-4 z-10 pointer-events-none select-none">
                <span class="inline-block {{ $idx % 2 == 0 ? '-rotate-2 bg-retro-mustard text-rustic-950' : 'rotate-2 bg-retro-orange text-parchment' }} font-heading font-black text-[9px] uppercase px-2 py-0.5 border-2 border-rustic-900 shadow-sm">
                    ★ {{ $product->is_featured ? 'FAVORIT' : 'ORIGINAL' }}
                </span>
            </div>

            <!-- Out of stock badge overlay if unavailable -->
            @if (! $product->is_available)
            <div class="absolute inset-0 bg-rustic-950/75 z-20 flex items-center justify-center rounded-sm">
                <span class="bg-brick text-parchment border-2 border-parchment font-heading text-xs uppercase tracking-widest px-4 py-1.5 -rotate-6 shadow-rustic font-black">
                    HABIS SEMENTARA
                </span>
            </div>
            @endif

            <div>
                <!-- Product Visual Box -->
                <div class="w-full h-44 bg-rustic-900 border-2 border-rustic-900 rounded-sm flex items-center justify-center text-retro-mustard mb-4 relative overflow-hidden group">
                    <span class="text-5xl select-none group-hover:scale-110 transition duration-300">
                        @if (str_contains(strtolower($product->category->name), 'kopi'))
                        ☕
                        @elseif (str_contains(strtolower($product->category->name), 'makanan'))
                        🥐
                        @else
                        🥤
                        @endif
                    </span>
                    <span class="absolute top-2 right-2 bg-rustic-900/90 text-parchment text-[10px] font-heading font-bold uppercase px-2 py-0.5 border border-retro-orange">
                        {{ $product->category->name }}
                    </span>
                </div>

                <h3 class="font-display font-black text-xl text-rustic-900 leading-snug">
                    {{ $product->name }}
                </h3>

                <p class="font-body text-xs text-rustic-700 mt-2 line-clamp-2 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>

            <div class="mt-4 pt-3 border-t-2 border-dashed border-rustic-400 flex items-center justify-between">
                <span class="font-display font-black text-xl text-rustic-900">
                    {{ $product->formatted_price }}
                </span>

                @if ($product->is_available)
                <button type="button"
                    @click="openModal({{ json_encode($product) }})"
                    class="bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-bold text-xs uppercase px-3.5 py-1.5 shadow-rustic-sm transition active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                    + Pesan
                </button>
                @else
                <span class="text-xs font-heading font-bold uppercase text-brick italic">Habis</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- CUSTOMIZE / ADD TO CART MODAL -->
    <div x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-rustic-900/70 backdrop-blur-sm flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="bg-[#FFF9F0] border-2 border-rustic-900 shadow-rustic-lg rounded-sm w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto"
            @click.outside="modalOpen = false">

            <!-- Close Button -->
            <button @click="modalOpen = false"
                type="button"
                class="absolute top-4 right-4 text-rustic-600 hover:text-rustic-900 text-xl font-bold">
                ✕
            </button>

            <!-- Product Header -->
            <div class="border-b border-rustic-300 pb-4 mb-4">
                <span class="text-copper font-heading text-xs uppercase tracking-widest block"
                    x-text="selectedProduct ? selectedProduct.category?.name : ''"></span>
                <h3 class="font-display font-bold text-2xl text-rustic-900"
                    x-text="selectedProduct ? selectedProduct.name : ''"></h3>
                <p class="font-body text-xs text-rustic-600 mt-1 italic"
                    x-text="selectedProduct ? selectedProduct.description : ''"></p>
            </div>

            <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" :value="selectedProduct ? selectedProduct.id : ''">

                <!-- 1. Pilihan Ukuran (Jika produk minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div>
                        <label class="block font-heading uppercase text-xs tracking-wider text-rustic-800 mb-2">
                            Pilihan Ukuran
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="border-2 p-2.5 text-center cursor-pointer rounded-sm text-xs font-heading uppercase transition"
                                :class="selectedSize === 'Regular (S)' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400'">
                                <input type="radio" name="size" value="Regular (S)" x-model="selectedSize" class="hidden">
                                <div>Regular (S)</div>
                                <div class="text-[10px] text-copper">+Rp 0</div>
                            </label>
                            <label class="border-2 p-2.5 text-center cursor-pointer rounded-sm text-xs font-heading uppercase transition"
                                :class="selectedSize === 'Medium (M)' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400'">
                                <input type="radio" name="size" value="Medium (M)" x-model="selectedSize" class="hidden">
                                <div>Medium (M)</div>
                                <div class="text-[10px] text-copper">+Rp 4.000</div>
                            </label>
                            <label class="border-2 p-2.5 text-center cursor-pointer rounded-sm text-xs font-heading uppercase transition"
                                :class="selectedSize === 'Large (L)' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-parchment text-rustic-800 border-rustic-400'">
                                <input type="radio" name="size" value="Large (L)" x-model="selectedSize" class="hidden">
                                <div>Large (L)</div>
                                <div class="text-[10px] text-copper">+Rp 8.000</div>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- 2. Pilihan Gula & Suhu (Untuk Minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-heading uppercase text-xs tracking-wider text-rustic-800 mb-1">
                                Tingkat Gula
                            </label>
                            <select name="sugar" x-model="selectedSugar"
                                class="w-full bg-parchment border-2 border-rustic-400 text-xs font-body p-2 rounded-sm focus:border-copper focus:outline-none">
                                <option value="Normal (100%)">Normal (100%)</option>
                                <option value="Sedikit Manis (50%)">Sedikit Manis (50%)</option>
                                <option value="Tanpa Gula (0%)">Tanpa Gula (0%)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-heading uppercase text-xs tracking-wider text-rustic-800 mb-1">
                                Suhu Minuman
                            </label>
                            <select name="ice" x-model="selectedIce"
                                class="w-full bg-parchment border-2 border-rustic-400 text-xs font-body p-2 rounded-sm focus:border-copper focus:outline-none">
                                <option value="Dingin (Es Batu)">Dingin (Es Batu)</option>
                                <option value="Hangat / Panas">Hangat / Panas</option>
                            </select>
                        </div>
                    </div>
                </template>

                <!-- 3. Add-on / Topping Tambahan (Minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div>
                        <label class="block font-heading uppercase text-xs tracking-wider text-rustic-800 mb-2">
                            Topping Tambahan (Opsional)
                        </label>
                        <div class="grid grid-cols-2 gap-2 text-xs font-body">
                            <label class="flex items-center space-x-2 bg-parchment p-2 border border-rustic-400 rounded-sm cursor-pointer">
                                <input type="checkbox" name="toppings[]" value="Boba Brown Sugar" x-model="selectedToppings"
                                    class="rounded text-copper focus:ring-copper">
                                <span>Boba (+Rp 5.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-parchment p-2 border border-rustic-400 rounded-sm cursor-pointer">
                                <input type="checkbox" name="toppings[]" value="Grass Jelly" x-model="selectedToppings"
                                    class="rounded text-copper focus:ring-copper">
                                <span>Grass Jelly (+Rp 4.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-parchment p-2 border border-rustic-400 rounded-sm cursor-pointer">
                                <input type="checkbox" name="toppings[]" value="Whipped Cream" x-model="selectedToppings"
                                    class="rounded text-copper focus:ring-copper">
                                <span>Whipped Cream (+Rp 5.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-parchment p-2 border border-rustic-400 rounded-sm cursor-pointer">
                                <input type="checkbox" name="toppings[]" value="Extra Espresso Shot" x-model="selectedToppings"
                                    class="rounded text-copper focus:ring-copper">
                                <span>Extra Shot (+Rp 6.000)</span>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- 4. Jumlah & Catatan -->
                <div class="grid grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block font-heading uppercase text-xs tracking-wider text-rustic-800 mb-1">
                            Jumlah
                        </label>
                        <div class="flex items-center border-2 border-rustic-400 rounded-sm bg-parchment">
                            <button type="button" @click="if (quantity > 1) quantity--"
                                class="px-3 py-1.5 font-bold text-rustic-700 hover:bg-rustic-200">
                                -
                            </button>
                            <input type="number" name="quantity" x-model="quantity" min="1"
                                class="w-full text-center bg-transparent text-sm font-bold border-0 focus:ring-0 p-1">
                            <button type="button" @click="quantity++"
                                class="px-3 py-1.5 font-bold text-rustic-700 hover:bg-rustic-200">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="block font-heading uppercase text-xs tracking-wider text-rustic-800 mb-1">
                            Catatan Khusus
                        </label>
                        <input type="text" name="notes" x-model="notes" placeholder="Contoh: Es dipisah, jangan terlalu manis"
                            class="w-full bg-parchment border-2 border-rustic-400 text-xs font-body p-2 rounded-sm focus:border-copper focus:outline-none">
                    </div>
                </div>

                <!-- Total & Submit Button -->
                <div class="pt-4 border-t-2 border-dashed border-rustic-400 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-heading uppercase text-rustic-600 font-bold">Total Harga</div>
                        <div class="font-display font-black text-2xl text-retro-orange"
                            x-text="formatRupiah(calculateTotal())"></div>
                    </div>

                    <button type="submit"
                            class="bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-black tracking-widest uppercase text-xs py-3.5 px-6 shadow-rustic transition active:translate-x-1 active:translate-y-1 active:shadow-none cursor-pointer">
                        TAMBAHKAN KE KERANJANG →
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection