@extends('layouts.public')

@section('title', 'Katalog Menu Autentik')

@section('content')
<div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 py-12 relative overflow-hidden"
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

    <!-- Pinggiran Biji Kopi Halaman Menu: Kiri & Kanan Layar Melayang -->
    <img src="{{ asset('images/beans-falling.png') }}" class="absolute left-2 lg:left-6 top-16 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-45 -rotate-12 drop-shadow-md select-none hidden sm:block animate-float-slow" alt="" />
    <img src="{{ asset('images/beans-cluster.png') }}" class="absolute right-4 lg:right-8 top-10 w-20 sm:w-24 h-24 object-contain pointer-events-none opacity-50 rotate-45 select-none hidden md:block animate-float-reverse" alt="" />
    <img src="{{ asset('images/beans-falling.png') }}" class="absolute right-2 lg:right-6 top-1/2 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-45 rotate-12 drop-shadow-md select-none hidden sm:block animate-float" alt="" />
    <img src="{{ asset('images/beans-cluster.png') }}" class="absolute left-4 lg:left-8 bottom-16 w-20 sm:w-24 h-24 object-contain pointer-events-none opacity-40 -rotate-12 select-none hidden md:block animate-float-drift" alt="" />

    <!-- Page Header (Rustic Modern: Fraunces + Caveat) -->
    <div class="mb-10 border-b border-dashed border-[#CDB48F] pb-6 relative z-10">
        <span class="font-hand text-2xl text-[#B5753A]">Seduhan Hari Ini</span>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-semibold text-[#2B1D14] tracking-tight mt-1">
            Menu Kami
        </h1>
        <p class="text-[#6B5646] text-sm sm:text-base mt-2 max-w-2xl">
            Pilih dan pesan langsung menu kopi, minuman segar, dan kudapan favoritmu.
        </p>
    </div>

    <!-- Filter Tabs & Search Box (03-DESIGN.md Section 6.3 & 6.4) -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-10">
        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <a href="{{ route('menu.index') }}"
                class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition duration-200 {{ empty($selectedCategorySlug) ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] border border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]' }}">
                Semua Menu
            </a>
            @foreach ($categories as $cat)
            <a href="{{ route('menu.index', ['kategori' => $cat->slug]) }}"
                class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition duration-200 {{ $selectedCategorySlug === $cat->slug ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] border border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]' }}">
                {{ $cat->name }} <span class="text-xs opacity-75">({{ $cat->products_count }})</span>
            </a>
            @endforeach
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('menu.index') }}" class="w-full md:w-80">
            @if ($selectedCategorySlug)
            <input type="hidden" name="kategori" value="{{ $selectedCategorySlug }}">
            @endif
            <div class="relative">
                <input type="text"
                    name="q"
                    value="{{ $searchQuery }}"
                    placeholder="Cari seduhan atau kudapan..."
                    class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A] placeholder-[#6B5646]/60">
                @if ($searchQuery)
                <a href="{{ route('menu.index', array_filter(['kategori' => $selectedCategorySlug])) }}"
                    class="absolute right-3 top-2.5 text-xs text-[#6B5646] hover:text-[#2B1D14]">
                    ✕
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Grid (03-DESIGN.md Section 6.3: Kartu Menu) -->
    @if ($products->isEmpty())
    <div class="bg-[#FFFDF8] border border-dashed border-[#CDB48F] rounded-2xl p-16 text-center my-8">
        <span class="text-4xl mb-3 block">☕</span>
        <h3 class="font-display font-semibold text-2xl text-[#2B1D14]">Menu Tidak Ditemukan</h3>
        <p class="text-sm text-[#6B5646] mt-2 max-w-md mx-auto">
            Silakan coba kata kunci lain atau pilih kategori yang berbeda.
        </p>
        <div class="mt-6">
            <a href="{{ route('menu.index') }}"
               class="inline-block bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold px-6 py-3 rounded-xl hover:bg-[#B4552F] transition shadow-sm">
                Kembali ke Semua Menu
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-6 xl:gap-8">
        @foreach ($products as $product)
        <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-5 sm:p-6 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-warm-lg transition-all duration-300 relative group reveal-on-scroll">

            <!-- Out of stock badge overlay if unavailable -->
            @if (! $product->is_available)
            <div class="absolute inset-0 bg-[#F4ECDD]/85 z-10 flex flex-col items-center justify-center rounded-2xl backdrop-blur-[1px]">
                <span class="bg-[#4A3224] text-[#F4ECDD] text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-lg shadow-sm">
                    Habis Sementara
                </span>
                <span class="text-[11px] text-[#6B5646] mt-1 font-medium">Restock segera</span>
            </div>
            @endif

            <div>
                <!-- Product Thumbnail with Saucer & Shadow (Beverage cups or food badges - NO beans inside) -->
                <div class="w-full h-40 bg-[#F4ECDD]/70 rounded-xl mb-4 flex items-center justify-center p-3 overflow-hidden border border-[#CDB48F]/40 group-hover:bg-[#E8DCC4]/50 transition">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="h-32 w-32 object-contain drop-shadow-md group-hover:scale-105 transition transform duration-300">
                    @else
                        <div class="h-28 w-28 rounded-full bg-[#E8DCC4] border border-[#CDB48F] flex items-center justify-center text-4xl shadow-inner group-hover:scale-105 transition duration-300 select-none">
                            @if (str_contains(strtolower($product->name), 'croissant') || str_contains(strtolower($product->name), 'roti'))
                                🥐
                            @elseif (str_contains(strtolower($product->name), 'nasi'))
                                🍲
                            @elseif (str_contains(strtolower($product->name), 'fries'))
                                🍟
                            @elseif (str_contains(strtolower($product->name), 'churros'))
                                🥖
                            @else
                                🍽️
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Category pill & favorite flag -->
                <div class="flex items-center justify-between text-xs text-[#6B5646] mb-2.5">
                    <span class="uppercase tracking-wider font-semibold text-[11px] bg-[#E8DCC4] text-[#4A3224] px-2.5 py-0.5 rounded-md">
                        {{ $product->category->name }}
                    </span>
                    @if ($product->is_featured)
                        <span class="bg-[#B5753A]/15 text-[#B5753A] text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md">
                            ★ Favorit
                        </span>
                    @endif
                </div>

                <!-- Product Name (Fraunces) -->
                <h3 class="font-display font-semibold text-[#2B1D14] text-xl leading-snug group-hover:text-[#B5753A] transition">
                    {{ $product->name }}
                </h3>

                <!-- Product Description -->
                <p class="text-[#6B5646] text-xs sm:text-sm mt-2 line-clamp-2 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>

            <!-- Price with Dotted Leader & Order Action -->
            <div class="mt-6 pt-4 border-t border-dashed border-[#CDB48F] flex items-baseline justify-between">
                <span class="font-display font-bold text-lg text-[#2B1D14]">
                    {{ $product->formatted_price }}
                </span>

                @if ($product->is_available)
                <button type="button"
                    @click="openModal({{ json_encode($product) }})"
                    class="bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase tracking-wider font-bold px-4 py-2 rounded-xl transition shadow-sm cursor-pointer">
                    + Pesan
                </button>
                @else
                <span class="text-xs font-bold text-[#B4552F] uppercase tracking-wider">Habis</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- CUSTOMIZE / ADD TO CART MODAL (03-DESIGN.md Section 17.2: Detail Produk & Add-on) -->
    <div x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-[#1E1A17]/60 backdrop-blur-sm flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="bg-[#FFFDF8] border border-[#CDB48F] shadow-warm rounded-2xl w-full max-w-lg p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto"
            @click.outside="modalOpen = false">

            <!-- Close Button -->
            <button @click="modalOpen = false"
                type="button"
                class="absolute top-5 right-5 text-[#6B5646] hover:text-[#2B1D14] text-xl font-bold p-1 rounded-lg hover:bg-[#E8DCC4] transition">
                ✕
            </button>

            <!-- Product Header with Real Image Thumbnail -->
            <div class="border-b border-dashed border-[#CDB48F] pb-5 mb-5 flex items-start gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#F4ECDD] rounded-xl flex items-center justify-center p-2 flex-shrink-0 border border-[#CDB48F]/50">
                    <template x-if="selectedProduct && selectedProduct.image_url">
                        <img :src="selectedProduct.image_url"
                             :alt="selectedProduct.name"
                             class="w-full h-full object-contain drop-shadow-sm">
                    </template>
                    <template x-if="selectedProduct && !selectedProduct.image_url">
                        <span class="text-3xl select-none">🍽️</span>
                    </template>
                </div>
                <div class="flex-1 pr-6">
                    <span class="text-xs uppercase tracking-wider font-semibold text-[#B5753A] block mb-1"
                        x-text="selectedProduct ? selectedProduct.category?.name : ''"></span>
                    <h3 class="font-display font-bold text-xl sm:text-2xl text-[#2B1D14]"
                        x-text="selectedProduct ? selectedProduct.name : ''"></h3>
                    <p class="text-xs sm:text-sm text-[#6B5646] mt-1 leading-relaxed line-clamp-2"
                        x-text="selectedProduct ? selectedProduct.description : ''"></p>
                </div>
            </div>

            <form method="POST" action="{{ route('cart.add') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="product_id" :value="selectedProduct ? selectedProduct.id : ''">

                <!-- 1. Pilihan Ukuran (Jika produk minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-2">
                            Pilihan Ukuran
                        </label>
                        <div class="grid grid-cols-3 gap-2.5">
                            <label class="border p-3 text-center cursor-pointer rounded-xl text-xs font-semibold transition"
                                :class="selectedSize === 'Regular (S)' ? 'bg-[#E8DCC4] border-[#B5753A] text-[#2B1D14] ring-1 ring-[#B5753A]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                                <input type="radio" name="size" value="Regular (S)" x-model="selectedSize" class="hidden">
                                <div>Regular (S)</div>
                                <div class="text-[11px] text-[#6B5646] mt-0.5">+Rp 0</div>
                            </label>
                            <label class="border p-3 text-center cursor-pointer rounded-xl text-xs font-semibold transition"
                                :class="selectedSize === 'Medium (M)' ? 'bg-[#E8DCC4] border-[#B5753A] text-[#2B1D14] ring-1 ring-[#B5753A]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                                <input type="radio" name="size" value="Medium (M)" x-model="selectedSize" class="hidden">
                                <div>Medium (M)</div>
                                <div class="text-[11px] text-[#6B5646] mt-0.5">+Rp 4.000</div>
                            </label>
                            <label class="border p-3 text-center cursor-pointer rounded-xl text-xs font-semibold transition"
                                :class="selectedSize === 'Large (L)' ? 'bg-[#E8DCC4] border-[#B5753A] text-[#2B1D14] ring-1 ring-[#B5753A]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                                <input type="radio" name="size" value="Large (L)" x-model="selectedSize" class="hidden">
                                <div>Large (L)</div>
                                <div class="text-[11px] text-[#6B5646] mt-0.5">+Rp 8.000</div>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- 2. Pilihan Gula & Suhu (Untuk Minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                                Tingkat Gula
                            </label>
                            <select name="sugar" x-model="selectedSugar"
                                class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-2.5 rounded-xl text-[#2B1D14] focus:border-[#B5753A] focus:outline-none">
                                <option value="Normal (100%)">Normal (100%)</option>
                                <option value="Sedikit Manis (50%)">Sedikit Manis (50%)</option>
                                <option value="Tanpa Gula (0%)">Tanpa Gula (0%)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                                Suhu Minuman
                            </label>
                            <select name="ice" x-model="selectedIce"
                                class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-2.5 rounded-xl text-[#2B1D14] focus:border-[#B5753A] focus:outline-none">
                                <option value="Dingin (Es Batu)">Dingin (Es Batu)</option>
                                <option value="Hangat / Panas">Hangat / Panas</option>
                            </select>
                        </div>
                    </div>
                </template>

                <!-- 3. Add-on / Topping Tambahan (Minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-2">
                            Topping Tambahan (Opsional)
                        </label>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <label class="flex items-center space-x-2 bg-[#FFFDF8] p-2.5 border border-[#CDB48F] rounded-xl cursor-pointer hover:bg-[#E8DCC4]/50 transition">
                                <input type="checkbox" name="toppings[]" value="Boba Brown Sugar" x-model="selectedToppings"
                                    class="rounded text-[#B5753A] focus:ring-[#B5753A]">
                                <span class="text-[#4A3224] font-medium">Boba (+Rp 5.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-[#FFFDF8] p-2.5 border border-[#CDB48F] rounded-xl cursor-pointer hover:bg-[#E8DCC4]/50 transition">
                                <input type="checkbox" name="toppings[]" value="Grass Jelly" x-model="selectedToppings"
                                    class="rounded text-[#B5753A] focus:ring-[#B5753A]">
                                <span class="text-[#4A3224] font-medium">Grass Jelly (+Rp 4.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-[#FFFDF8] p-2.5 border border-[#CDB48F] rounded-xl cursor-pointer hover:bg-[#E8DCC4]/50 transition">
                                <input type="checkbox" name="toppings[]" value="Whipped Cream" x-model="selectedToppings"
                                    class="rounded text-[#B5753A] focus:ring-[#B5753A]">
                                <span class="text-[#4A3224] font-medium">Whipped Cream (+Rp 5.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-[#FFFDF8] p-2.5 border border-[#CDB48F] rounded-xl cursor-pointer hover:bg-[#E8DCC4]/50 transition">
                                <input type="checkbox" name="toppings[]" value="Extra Espresso Shot" x-model="selectedToppings"
                                    class="rounded text-[#B5753A] focus:ring-[#B5753A]">
                                <span class="text-[#4A3224] font-medium">Extra Shot (+Rp 6.000)</span>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- 4. Jumlah & Catatan -->
                <div class="grid grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                            Jumlah
                        </label>
                        <div class="flex items-center border border-[#CDB48F] rounded-xl bg-[#FFFDF8]">
                            <button type="button" @click="if (quantity > 1) quantity--"
                                class="px-3.5 py-2 font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-l-xl transition">
                                -
                            </button>
                            <input type="number" name="quantity" x-model="quantity" min="1"
                                class="w-full text-center bg-transparent text-sm font-semibold border-0 focus:ring-0 p-1 text-[#2B1D14]">
                            <button type="button" @click="quantity++"
                                class="px-3.5 py-2 font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-r-xl transition">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                            Catatan Khusus
                        </label>
                        <input type="text" name="notes" x-model="notes" placeholder="Contoh: Kurangi es, tanpa sedotan..."
                            class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-2.5 rounded-xl text-[#2B1D14] focus:border-[#B5753A] focus:outline-none">
                    </div>
                </div>

                <!-- Total & Submit Button -->
                <div class="pt-5 border-t border-dashed border-[#CDB48F] flex items-center justify-between">
                    <div>
                        <div class="text-xs text-[#6B5646]">Total Harga</div>
                        <div class="font-display font-bold text-2xl text-[#2B1D14]"
                            x-text="formatRupiah(calculateTotal())"></div>
                    </div>

                    <button type="submit"
                            class="bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase tracking-wider font-bold py-3 px-6 rounded-xl transition shadow-warm cursor-pointer">
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
