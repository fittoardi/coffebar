@extends('layouts.public')

@section('title', 'Berkreasi Minuman Kustom')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="{
        name: 'Racikan Pilihan Saya',
        selectedBase: 'Espresso Blend',
        basePrice: 16000,
        selectedSize: 'Regular (S)',
        sizePrice: 0,
        selectedSugar: 'Normal (100%)',
        selectedIce: 'Dingin (Es Batu)',
        selectedToppings: [],
        notes: '',
        quantity: 1,

        selectBase(name, price) {
            this.selectedBase = name;
            this.basePrice = parseFloat(price);
        },
        selectSize(name, price) {
            this.selectedSize = name;
            this.sizePrice = parseFloat(price);
        },
        toggleTopping(name, price) {
            const index = this.selectedToppings.findIndex(t => t.name === name);
            if (index > -1) {
                this.selectedToppings.splice(index, 1);
            } else {
                this.selectedToppings.push({ name: name, price: parseFloat(price) });
            }
        },
        isToppingSelected(name) {
            return this.selectedToppings.some(t => t.name === name);
        },
        calculateUnitPrice() {
            let toppingsTotal = this.selectedToppings.reduce((sum, t) => sum + t.price, 0);
            return this.basePrice + this.sizePrice + toppingsTotal;
        },
        calculateTotalPrice() {
            return this.calculateUnitPrice() * this.quantity;
        },
        formatRupiah(num) {
            return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
        }
     }">

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
            Racik Minumanmu Sendiri
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Eksplorasi kombinasi rasa yang pas dengan seleramu. Pilih bahan dasar, ukuran, tingkat gula, suhu, dan topping.
        </p>
    </div>

    <!-- Main Builder Grid: Left Preview, Right Controls -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- LEFT COLUMN: SUMMARY & PRICE -->
        <div class="lg:col-span-5 lg:sticky lg:top-24">
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">

                <!-- Summary Box -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 text-center space-y-3">
                    <div class="text-4xl">☕</div>
                    <div class="font-bold text-lg text-gray-900 truncate" x-text="name"></div>
                    <div class="text-sm font-semibold text-blue-600" x-text="selectedBase"></div>
                    <div class="text-xs text-gray-500 space-y-1">
                        <div>Ukuran: <span class="font-medium text-gray-700" x-text="selectedSize"></span></div>
                        <div>Gula: <span class="font-medium text-gray-700" x-text="selectedSugar"></span></div>
                        <div>Suhu: <span class="font-medium text-gray-700" x-text="selectedIce"></span></div>
                    </div>

                    <!-- Toppings List -->
                    <div class="pt-3 border-t border-gray-200 text-xs text-gray-600">
                        <div class="font-medium mb-1">Topping:</div>
                        <template x-if="selectedToppings.length === 0">
                            <span class="text-gray-400">Tidak ada</span>
                        </template>
                        <div class="flex flex-wrap gap-1 justify-center">
                            <template x-for="t in selectedToppings" :key="t.name">
                                <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded text-xs" x-text="t.name"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Price and Calculation -->
                <div class="border-t border-gray-200 pt-4 text-center">
                    <div class="text-xs text-gray-500">Total Harga</div>
                    <div class="font-bold text-3xl text-gray-900 mt-1"
                         x-text="formatRupiah(calculateTotalPrice())"></div>
                </div>

                <!-- Forms / Buttons: Save & Order -->
                <div class="space-y-3 pt-2">
                    @auth
                        <!-- Authenticated Form: Pesan Langsung -->
                        <form method="POST" action="{{ route('custom.add-to-cart') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="name" :value="name">
                            <input type="hidden" name="base" :value="selectedBase">
                            <input type="hidden" name="size" :value="selectedSize">
                            <input type="hidden" name="sugar_level" :value="selectedSugar">
                            <input type="hidden" name="ice_level" :value="selectedIce">
                            <template x-for="t in selectedToppings" :key="t.name">
                                <input type="hidden" name="options[]" :value="t.name">
                            </template>
                            <input type="hidden" name="price" :value="calculateUnitPrice()">
                            <input type="hidden" name="quantity" :value="quantity">
                            <input type="hidden" name="notes" :value="notes">

                            <button type="submit"
                                    class="w-full bg-blue-600 text-white hover:bg-blue-700 font-medium text-sm py-3 rounded-md transition cursor-pointer">
                                Tambahkan ke Keranjang
                            </button>
                        </form>

                        <!-- Authenticated Form: Simpan ke Favorit -->
                        <form method="POST" action="{{ route('custom.store') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="name" :value="name">
                            <input type="hidden" name="base" :value="selectedBase">
                            <input type="hidden" name="size" :value="selectedSize">
                            <input type="hidden" name="sugar_level" :value="selectedSugar">
                            <input type="hidden" name="ice_level" :value="selectedIce">
                            <template x-for="t in selectedToppings" :key="t.name">
                                <input type="hidden" name="options[]" :value="t.name">
                            </template>
                            <input type="hidden" name="price" :value="calculateUnitPrice()">
                            <input type="hidden" name="notes" :value="notes">

                            <button type="submit"
                                    class="w-full bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-medium text-sm py-2.5 rounded-md transition cursor-pointer">
                                Simpan ke Favorit
                            </button>
                        </form>
                    @else
                        <!-- Guest notice -->
                        <div class="bg-blue-50 border border-blue-200 p-3 rounded-md text-xs text-blue-800 text-left">
                            <span class="font-bold block mb-1">Masuk untuk Memesan:</span>
                            Silakan masuk atau daftar terlebih dahulu untuk memesan racikan ini.
                        </div>

                        <a href="{{ route('login') }}"
                           class="block text-center w-full bg-blue-600 text-white hover:bg-blue-700 font-medium text-sm py-3 rounded-md transition">
                            Masuk untuk Memesan
                        </a>
                    @endauth
                </div>

            </div>
        </div>

        <!-- RIGHT COLUMN: BUILDER OPTIONS CONTROLS -->
        <div class="lg:col-span-7 space-y-6">

            <!-- 1. Nama Kreasi -->
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <label for="craft-name" class="block font-semibold text-sm text-gray-900 mb-1">
                    1. Beri Nama Racikan Minumanmu
                </label>
                <input type="text"
                       id="craft-name"
                       x-model="name"
                       class="w-full bg-white border border-gray-300 font-medium text-sm px-3.5 py-2 text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
            </div>

            <!-- 2. Pilihan Base Minuman -->
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <label class="block font-semibold text-sm text-gray-900 mb-2">
                    2. Pilih Bahan Dasar (Base)
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($bases as $base)
                        <div @click="selectBase('{{ $base->name }}', {{ (float) $base->price }})"
                             class="p-3 border rounded-md cursor-pointer transition flex items-center justify-between"
                             :class="selectedBase === '{{ $base->name }}' ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            <div class="font-medium text-sm">{{ $base->name }}</div>
                            <div class="font-bold text-xs text-gray-900">
                                {{ $base->formatted_price }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Ukuran Cup -->
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <label class="block font-semibold text-sm text-gray-900 mb-2">
                    3. Pilih Ukuran
                </label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($sizes as $size)
                        <div @click="selectSize('{{ $size->name }}', {{ (float) $size->price }})"
                             class="p-3 border text-center rounded-md cursor-pointer transition"
                             :class="selectedSize === '{{ $size->name }}' ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            <div class="font-medium text-xs">{{ $size->name }}</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                {{ (float) $size->price > 0 ? '+ ' . $size->formatted_price : '+ Rp 0' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. Gula & Suhu -->
            <div class="bg-white border border-gray-200 rounded-lg p-5 space-y-5">
                <!-- Gula -->
                <div>
                    <label class="block font-semibold text-sm text-gray-900 mb-2">
                        4. Tingkat Kemanisan
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <div @click="selectedSugar = 'Tanpa Gula (0%)'"
                             class="p-2.5 text-center border rounded-md cursor-pointer text-xs font-medium transition"
                             :class="selectedSugar === 'Tanpa Gula (0%)' ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            0% (Tanpa Gula)
                        </div>
                        <div @click="selectedSugar = 'Sedikit Manis (50%)'"
                             class="p-2.5 text-center border rounded-md cursor-pointer text-xs font-medium transition"
                             :class="selectedSugar === 'Sedikit Manis (50%)' ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            50% (Sedikit Manis)
                        </div>
                        <div @click="selectedSugar = 'Normal (100%)'"
                             class="p-2.5 text-center border rounded-md cursor-pointer text-xs font-medium transition"
                             :class="selectedSugar === 'Normal (100%)' ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            100% (Normal)
                        </div>
                    </div>
                </div>

                <!-- Suhu -->
                <div>
                    <label class="block font-semibold text-sm text-gray-900 mb-2">
                        5. Suhu Penyajian
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div @click="selectedIce = 'Dingin (Es Batu)'"
                             class="p-2.5 text-center border rounded-md cursor-pointer text-xs font-medium transition"
                             :class="selectedIce === 'Dingin (Es Batu)' ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            Dingin (Es Batu)
                        </div>
                        <div @click="selectedIce = 'Hangat / Panas'"
                             class="p-2.5 text-center border rounded-md cursor-pointer text-xs font-medium transition"
                             :class="selectedIce === 'Hangat / Panas' ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            Hangat / Panas
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Toppings -->
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <label class="block font-semibold text-sm text-gray-900 mb-2">
                    6. Tambahan Topping (Opsional)
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($toppings as $topping)
                        <div @click="toggleTopping('{{ $topping->name }}', {{ (float) $topping->price }})"
                             class="p-3 border rounded-md cursor-pointer transition flex items-center justify-between"
                             :class="isToppingSelected('{{ $topping->name }}') ? 'bg-blue-50 border-blue-600 text-blue-900' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                            <div class="flex items-center space-x-2">
                                <span x-show="isToppingSelected('{{ $topping->name }}')" class="text-blue-600 font-bold">✓</span>
                                <span class="text-xs font-medium">{{ $topping->name }}</span>
                            </div>
                            <span class="text-xs font-bold text-gray-900">
                                + {{ $topping->formatted_price }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 6. Jumlah & Catatan Barista -->
            <div class="bg-white border border-gray-200 rounded-lg p-5 grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block font-semibold text-xs text-gray-700 mb-1">
                        Jumlah Gelas
                    </label>
                    <div class="flex items-center border border-gray-300 rounded-md bg-white">
                        <button type="button" @click="if (quantity > 1) quantity--"
                                class="px-3 py-2 font-bold text-gray-600 hover:bg-gray-100">-</button>
                        <input type="number" x-model="quantity" min="1"
                               class="w-full text-center bg-transparent font-semibold text-sm border-0 focus:ring-0 p-1">
                        <button type="button" @click="quantity++"
                                class="px-3 py-2 font-bold text-gray-600 hover:bg-gray-100">+</button>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-xs text-gray-700 mb-1">
                        Catatan Khusus
                    </label>
                    <input type="text"
                           x-model="notes"
                           placeholder="Contoh: Es dipisah, dll."
                           class="w-full bg-white border border-gray-300 text-xs p-2 rounded-md focus:border-blue-500 focus:outline-none">
                </div>
            </div>

            <!-- Saved User Custom Menus (If Authenticated) -->
            @auth
                @if ($savedMenus->isNotEmpty())
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="font-semibold text-sm text-gray-900 mb-3">
                            Racikan Favorit yang Disimpan
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($savedMenus as $saved)
                                <div class="bg-gray-50 border border-gray-200 p-3 rounded-md flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-sm text-gray-900">{{ $saved->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $saved->base }} ({{ $saved->size }}) • {{ $saved->formatted_price }}
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('custom.add-to-cart') }}">
                                        @csrf
                                        <input type="hidden" name="name" value="{{ $saved->name }}">
                                        <input type="hidden" name="base" value="{{ $saved->base }}">
                                        <input type="hidden" name="size" value="{{ $saved->size }}">
                                        <input type="hidden" name="sugar_level" value="{{ $saved->sugar_level }}">
                                        <input type="hidden" name="ice_level" value="{{ $saved->ice_level }}">
                                        @if ($saved->options)
                                            @foreach ($saved->options as $opt)
                                                <input type="hidden" name="options[]" value="{{ $opt }}">
                                            @endforeach
                                        @endif
                                        <input type="hidden" name="price" value="{{ $saved->price }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                                class="bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded hover:bg-blue-700 transition cursor-pointer">
                                            + Pesan
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endauth

        </div>

    </div>
</div>
@endsection
