@extends('layouts.public')

@section('title', 'Katalog Menu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
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

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
            Menu Kami
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Pilih dan pesan langsung menu kopi, minuman segar, dan kudapan favoritmu.
        </p>
    </div>

    <!-- Filter Tabs & Search Box -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <a href="{{ route('menu.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-md transition {{ empty($selectedCategorySlug) ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                Semua
            </a>
            @foreach ($categories as $cat)
            <a href="{{ route('menu.index', ['kategori' => $cat->slug]) }}"
                class="px-4 py-2 text-sm font-medium rounded-md transition {{ $selectedCategorySlug === $cat->slug ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                {{ $cat->name }} ({{ $cat->products_count }})
            </a>
            @endforeach
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('menu.index') }}" class="w-full md:w-72">
            @if ($selectedCategorySlug)
            <input type="hidden" name="kategori" value="{{ $selectedCategorySlug }}">
            @endif
            <div class="relative">
                <input type="text"
                    name="q"
                    value="{{ $searchQuery }}"
                    placeholder="Cari menu..."
                    class="w-full bg-white border border-gray-300 px-3.5 py-2 text-sm text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
                @if ($searchQuery)
                <a href="{{ route('menu.index', array_filter(['kategori' => $selectedCategorySlug])) }}"
                    class="absolute right-2.5 top-2.5 text-xs text-gray-400 hover:text-gray-600">
                    ✕
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Grid -->
    @if ($products->isEmpty())
    <div class="bg-white border border-gray-200 rounded-lg p-12 text-center my-8">
        <h3 class="font-bold text-lg text-gray-900">Menu Tidak Ditemukan</h3>
        <p class="text-sm text-gray-500 mt-1">
            Silakan coba kata kunci lain atau pilih kategori yang berbeda.
        </p>
        <div class="mt-4">
            <a href="{{ route('menu.index') }}" class="inline-block bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Kembali ke Semua Menu
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($products as $product)
        <div class="bg-white border border-gray-200 rounded-lg p-5 flex flex-col justify-between hover:shadow-sm transition relative">

            <!-- Out of stock badge overlay if unavailable -->
            @if (! $product->is_available)
            <div class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center rounded-lg">
                <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-md">
                    Habis Sementara
                </span>
            </div>
            @endif

            <div>
                <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                    <span>{{ $product->category->name }}</span>
                    @if ($product->is_featured)
                        <span class="bg-blue-50 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded">Favorit</span>
                    @endif
                </div>

                <h3 class="font-bold text-gray-900 text-lg">
                    {{ $product->name }}
                </h3>

                <p class="text-gray-500 text-sm mt-1 line-clamp-2 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>

            <div class="mt-6 pt-3 border-t border-gray-100 flex items-center justify-between">
                <span class="font-bold text-gray-900">
                    {{ $product->formatted_price }}
                </span>

                @if ($product->is_available)
                <button type="button"
                    @click="openModal({{ json_encode($product) }})"
                    class="bg-blue-600 text-white hover:bg-blue-700 text-xs font-medium px-3.5 py-1.5 rounded-md transition cursor-pointer">
                    + Pesan
                </button>
                @else
                <span class="text-xs font-medium text-red-600">Habis</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- CUSTOMIZE / ADD TO CART MODAL -->
    <div x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="bg-white border border-gray-200 shadow-xl rounded-lg w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto"
            @click.outside="modalOpen = false">

            <!-- Close Button -->
            <button @click="modalOpen = false"
                type="button"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold">
                ✕
            </button>

            <!-- Product Header -->
            <div class="border-b border-gray-200 pb-4 mb-4">
                <span class="text-xs font-medium text-blue-600 block"
                    x-text="selectedProduct ? selectedProduct.category?.name : ''"></span>
                <h3 class="font-bold text-xl text-gray-900"
                    x-text="selectedProduct ? selectedProduct.name : ''"></h3>
                <p class="text-xs text-gray-500 mt-1"
                    x-text="selectedProduct ? selectedProduct.description : ''"></p>
            </div>

            <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" :value="selectedProduct ? selectedProduct.id : ''">

                <!-- 1. Pilihan Ukuran (Jika produk minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Pilihan Ukuran
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="border p-2.5 text-center cursor-pointer rounded-md text-xs font-medium transition"
                                :class="selectedSize === 'Regular (S)' ? 'bg-blue-50 border-blue-600 text-blue-700' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                                <input type="radio" name="size" value="Regular (S)" x-model="selectedSize" class="hidden">
                                <div>Regular (S)</div>
                                <div class="text-[10px] text-gray-500">+Rp 0</div>
                            </label>
                            <label class="border p-2.5 text-center cursor-pointer rounded-md text-xs font-medium transition"
                                :class="selectedSize === 'Medium (M)' ? 'bg-blue-50 border-blue-600 text-blue-700' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                                <input type="radio" name="size" value="Medium (M)" x-model="selectedSize" class="hidden">
                                <div>Medium (M)</div>
                                <div class="text-[10px] text-gray-500">+Rp 4.000</div>
                            </label>
                            <label class="border p-2.5 text-center cursor-pointer rounded-md text-xs font-medium transition"
                                :class="selectedSize === 'Large (L)' ? 'bg-blue-50 border-blue-600 text-blue-700' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                                <input type="radio" name="size" value="Large (L)" x-model="selectedSize" class="hidden">
                                <div>Large (L)</div>
                                <div class="text-[10px] text-gray-500">+Rp 8.000</div>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- 2. Pilihan Gula & Suhu (Untuk Minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Tingkat Gula
                            </label>
                            <select name="sugar" x-model="selectedSugar"
                                class="w-full bg-white border border-gray-300 text-xs p-2 rounded-md focus:border-blue-500 focus:outline-none">
                                <option value="Normal (100%)">Normal (100%)</option>
                                <option value="Sedikit Manis (50%)">Sedikit Manis (50%)</option>
                                <option value="Tanpa Gula (0%)">Tanpa Gula (0%)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Suhu Minuman
                            </label>
                            <select name="ice" x-model="selectedIce"
                                class="w-full bg-white border border-gray-300 text-xs p-2 rounded-md focus:border-blue-500 focus:outline-none">
                                <option value="Dingin (Es Batu)">Dingin (Es Batu)</option>
                                <option value="Hangat / Panas">Hangat / Panas</option>
                            </select>
                        </div>
                    </div>
                </template>

                <!-- 3. Add-on / Topping Tambahan (Minuman) -->
                <template x-if="selectedProduct && !selectedProduct.category?.name?.toLowerCase().includes('makanan')">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Topping Tambahan (Opsional)
                        </label>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <label class="flex items-center space-x-2 bg-white p-2 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="toppings[]" value="Boba Brown Sugar" x-model="selectedToppings"
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Boba (+Rp 5.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-white p-2 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="toppings[]" value="Grass Jelly" x-model="selectedToppings"
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Grass Jelly (+Rp 4.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-white p-2 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="toppings[]" value="Whipped Cream" x-model="selectedToppings"
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Whipped Cream (+Rp 5.000)</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-white p-2 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="toppings[]" value="Extra Espresso Shot" x-model="selectedToppings"
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Extra Shot (+Rp 6.000)</span>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- 4. Jumlah & Catatan -->
                <div class="grid grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Jumlah
                        </label>
                        <div class="flex items-center border border-gray-300 rounded-md bg-white">
                            <button type="button" @click="if (quantity > 1) quantity--"
                                class="px-3 py-1.5 font-bold text-gray-600 hover:bg-gray-100">
                                -
                            </button>
                            <input type="number" name="quantity" x-model="quantity" min="1"
                                class="w-full text-center bg-transparent text-sm font-semibold border-0 focus:ring-0 p-1">
                            <button type="button" @click="quantity++"
                                class="px-3 py-1.5 font-bold text-gray-600 hover:bg-gray-100">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Catatan Khusus
                        </label>
                        <input type="text" name="notes" x-model="notes" placeholder="Contoh: Kurangi es, dll."
                            class="w-full bg-white border border-gray-300 text-xs p-2 rounded-md focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Total & Submit Button -->
                <div class="pt-4 border-t border-gray-200 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500">Total Harga</div>
                        <div class="font-bold text-xl text-gray-900"
                            x-text="formatRupiah(calculateTotal())"></div>
                    </div>

                    <button type="submit"
                            class="bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium py-2 px-5 rounded-md transition cursor-pointer">
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
