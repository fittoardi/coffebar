@extends('layouts.public')

@section('title', 'Berkreasi Minuman Kustom')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16"
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
    <div class="text-center mb-10">
        <span class="inline-block bg-rustic-800 text-copper border border-copper font-heading uppercase text-xs tracking-widest px-3 py-1 mb-2">
            ✦ Studio Barista Mandiri
        </span>
        <h1 class="font-display font-black text-4xl sm:text-5xl text-rustic-900 tracking-tight">
            Racik Minumanmu Sendiri
        </h1>
        <div class="text-copper text-sm tracking-widest my-2 select-none">
            ❧ ─────── ✦ ─────── ❧
        </div>
        <p class="font-body italic text-rustic-600 text-sm sm:text-base max-w-xl mx-auto">
            Eksplorasi kombinasi rasa yang paling pas dengan seleramu. Pilih bahan dasar, sesuaikan manis dan suhu, lalu tambahkan topping favorit.
        </p>
    </div>

    <!-- Main Builder Grid: Left Preview, Right Controls -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- LEFT COLUMN: STICKY CUP PREVIEW & PRICE -->
        <div class="lg:col-span-5 lg:sticky lg:top-28">
            <div class="bg-[#FFF8F0] border-2 border-copper rounded-sm p-6 sm:p-8 shadow-rustic-lg text-center space-y-6">

                <!-- Dynamic Crafted Cup Illustration -->
                <div class="w-48 h-56 mx-auto bg-rustic-900 border-2 border-rustic-700 rounded-sm p-4 flex flex-col justify-between items-center relative overflow-hidden shadow-inner text-parchment">
                    <span class="absolute top-2 right-2 text-[10px] font-heading uppercase px-2 py-0.5 border border-copper"
                          x-text="selectedIce === 'Dingin (Es Batu)' ? '❄ DINGIN' : '☕ HANGAT'"></span>

                    <!-- Cup Icon -->
                    <div class="mt-4 text-5xl">
                        <span x-show="selectedBase.includes('Espresso') || selectedBase.includes('Cold Brew')">☕</span>
                        <span x-show="selectedBase.includes('Matcha')">🍵</span>
                        <span x-show="selectedBase.includes('Tea')">🫖</span>
                        <span x-show="selectedBase.includes('Milk')">🥛</span>
                    </div>

                    <!-- Selected Base & Size on the Cup -->
                    <div class="space-y-1 text-center w-full px-2">
                        <div class="font-display font-bold text-sm text-copper truncate" x-text="name"></div>
                        <div class="font-heading text-[11px] text-rustic-300 uppercase tracking-wider" x-text="selectedBase"></div>
                        <div class="text-[10px] text-rustic-400 italic">
                            Ukuran: <span x-text="selectedSize"></span> | Gula: <span x-text="selectedSugar"></span>
                        </div>
                    </div>

                    <!-- Topping Badges Preview -->
                    <div class="w-full flex flex-wrap gap-1 justify-center pt-2 border-t border-rustic-800 text-[9px] text-parchment font-heading">
                        <template x-for="t in selectedToppings" :key="t.name">
                            <span class="bg-rustic-800 px-1.5 py-0.5 border border-copper rounded-xs" x-text="'+ ' + t.name"></span>
                        </template>
                        <span x-show="selectedToppings.length === 0" class="text-rustic-500 italic text-[10px]">Tanpa Topping Tambahan</span>
                    </div>
                </div>

                <!-- Price and Calculation -->
                <div class="border-t-2 border-dashed border-rustic-300 pt-4">
                    <div class="text-xs font-heading uppercase tracking-widest text-rustic-600">Perkiraan Harga</div>
                    <div class="font-display font-bold text-3xl sm:text-4xl text-rustic-900 mt-1"
                         x-text="formatRupiah(calculateTotalPrice())"></div>
                    <div class="text-xs text-rustic-500 font-body italic mt-0.5">
                        Harga dihitung otomatis secara real-time
                    </div>
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
                                    class="w-full bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading tracking-widest uppercase text-xs py-3.5 shadow-rustic transition duration-150 active:translate-x-0.5 active:translate-y-0.5 font-bold">
                                🛒 PESAN KREASI INI SEKARANG
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
                                    class="w-full bg-transparent text-rustic-800 border-2 border-rustic-700 hover:bg-rustic-100 font-heading tracking-widest uppercase text-xs py-2.5 transition active:translate-x-0.5 active:translate-y-0.5">
                                ⭐ SIMPAN KE FAVORIT SAYA
                            </button>
                        </form>
                    @else
                        <!-- Guest notice -->
                        <div class="bg-rustic-100 p-3 border border-copper text-xs font-body text-rustic-800 text-left">
                            <span class="font-bold font-heading text-copper block uppercase">✦ Masuk untuk Memesan:</span>
                            Anda bebas mencoba racikan di simulator ini. Untuk menyimpan resep atau memesannya ke kasir, silakan masuk ke akun Anda.
                        </div>

                        <a href="{{ route('login') }}"
                           class="block w-full bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading tracking-widest uppercase text-xs py-3.5 shadow-rustic transition active:translate-x-0.5 active:translate-y-0.5 font-bold">
                            MASUK UNTUK MEMESAN
                        </a>
                    @endauth
                </div>

            </div>
        </div>

        <!-- RIGHT COLUMN: BUILDER OPTIONS CONTROLS -->
        <div class="lg:col-span-7 space-y-8">

            <!-- 1. Nama Kreasi -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 shadow-rustic">
                <label for="craft-name" class="block font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 mb-1">
                    1. Beri Nama Racikan Minumanmu
                </label>
                <p class="font-body italic text-xs text-rustic-600 mb-3">
                    Berikan nama kreatif untuk kreasi unikmu
                </p>
                <input type="text"
                       id="craft-name"
                       x-model="name"
                       class="w-full bg-[#FFF8F0] border-2 border-rustic-400 font-display font-bold text-base px-4 py-2.5 text-rustic-900 rounded-sm focus:border-copper focus:outline-none">
            </div>

            <!-- 2. Pilihan Base Minuman -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 shadow-rustic">
                <label class="block font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 mb-1">
                    2. Pilih Bahan Dasar (Base)
                </label>
                <p class="font-body italic text-xs text-rustic-600 mb-3">
                    Fondasi rasa utama dari minumanmu
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($bases as $base)
                        <div @click="selectBase('{{ $base->name }}', {{ (float) $base->price }})"
                             class="p-3.5 border-2 rounded-sm cursor-pointer transition flex items-center justify-between"
                             :class="selectedBase === '{{ $base->name }}' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400 hover:bg-rustic-100'">
                            <div>
                                <div class="font-heading text-xs uppercase tracking-wide">{{ $base->name }}</div>
                            </div>
                            <div class="font-display font-bold text-xs text-copper">
                                {{ $base->formatted_price }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Ukuran Cup -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 shadow-rustic">
                <label class="block font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 mb-1">
                    3. Pilih Ukuran Cup
                </label>
                <div class="grid grid-cols-3 gap-3 mt-3">
                    @foreach ($sizes as $size)
                        <div @click="selectSize('{{ $size->name }}', {{ (float) $size->price }})"
                             class="p-3 border-2 text-center rounded-sm cursor-pointer transition"
                             :class="selectedSize === '{{ $size->name }}' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400 hover:bg-rustic-100'">
                            <div class="font-heading text-xs uppercase">{{ $size->name }}</div>
                            <div class="text-[11px] text-copper mt-0.5">
                                {{ (float) $size->price > 0 ? '+ ' . $size->formatted_price : '+ Rp 0' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. Gula & Suhu -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 shadow-rustic space-y-6">
                <!-- Gula -->
                <div>
                    <label class="block font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 mb-1">
                        4. Tingkat Kemanisan
                    </label>
                    <div class="grid grid-cols-3 gap-3 mt-2">
                        <div @click="selectedSugar = 'Tanpa Gula (0%)'"
                             class="p-2.5 text-center border-2 rounded-sm cursor-pointer text-xs font-heading uppercase transition"
                             :class="selectedSugar === 'Tanpa Gula (0%)' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400'">
                            0% (Pahit / Plain)
                        </div>
                        <div @click="selectedSugar = 'Sedikit Manis (50%)'"
                             class="p-2.5 text-center border-2 rounded-sm cursor-pointer text-xs font-heading uppercase transition"
                             :class="selectedSugar === 'Sedikit Manis (50%)' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400'">
                            50% (Sedang)
                        </div>
                        <div @click="selectedSugar = 'Normal (100%)'"
                             class="p-2.5 text-center border-2 rounded-sm cursor-pointer text-xs font-heading uppercase transition"
                             :class="selectedSugar === 'Normal (100%)' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400'">
                            100% (Normal)
                        </div>
                    </div>
                </div>

                <!-- Suhu -->
                <div>
                    <label class="block font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 mb-1">
                        5. Suhu Penyajian
                    </label>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <div @click="selectedIce = 'Dingin (Es Batu)'"
                             class="p-3 text-center border-2 rounded-sm cursor-pointer text-xs font-heading uppercase transition"
                             :class="selectedIce === 'Dingin (Es Batu)' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400'">
                            ❄ Dingin (Es Batu)
                        </div>
                        <div @click="selectedIce = 'Hangat / Panas'"
                             class="p-3 text-center border-2 rounded-sm cursor-pointer text-xs font-heading uppercase transition"
                             :class="selectedIce === 'Hangat / Panas' ? 'bg-rustic-800 text-parchment border-copper font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400'">
                            ☕ Hangat / Panas
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Toppings -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 shadow-rustic">
                <label class="block font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 mb-1">
                    6. Tambahan Topping (Bisa Lebih Dari Satu)
                </label>
                <p class="font-body italic text-xs text-rustic-600 mb-3">
                    Klik topping yang ingin kamu tambahkan ke dalam gelasmu
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($toppings as $topping)
                        <div @click="toggleTopping('{{ $topping->name }}', {{ (float) $topping->price }})"
                             class="p-3 border-2 rounded-sm cursor-pointer transition flex items-center justify-between"
                             :class="isToppingSelected('{{ $topping->name }}') ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm font-bold' : 'bg-[#FFF8F0] text-rustic-800 border-rustic-400 hover:bg-rustic-100'">
                            <div class="flex items-center space-x-2">
                                <span x-show="isToppingSelected('{{ $topping->name }}')" class="text-copper">✓</span>
                                <span class="font-heading text-xs uppercase">{{ $topping->name }}</span>
                            </div>
                            <span class="font-display font-bold text-xs text-copper">
                                + {{ $topping->formatted_price }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 6. Jumlah & Catatan Barista -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 shadow-rustic grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block font-heading font-bold text-xs uppercase text-rustic-900 mb-1">
                        Jumlah Gelas
                    </label>
                    <div class="flex items-center border-2 border-rustic-400 rounded-sm bg-[#FFF8F0]">
                        <button type="button" @click="if (quantity > 1) quantity--"
                                class="px-3 py-2 font-bold text-rustic-800 hover:bg-rustic-200">-</button>
                        <input type="number" x-model="quantity" min="1"
                               class="w-full text-center bg-transparent font-bold text-sm border-0 focus:ring-0 p-1">
                        <button type="button" @click="quantity++"
                                class="px-3 py-2 font-bold text-rustic-800 hover:bg-rustic-200">+</button>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-heading font-bold text-xs uppercase text-rustic-900 mb-1">
                        Catatan Khusus untuk Barista
                    </label>
                    <input type="text"
                           x-model="notes"
                           placeholder="Contoh: Tolong diaduk rata, es batu jangan kebanyakan"
                           class="w-full bg-[#FFF8F0] border-2 border-rustic-400 text-xs font-body p-2.5 rounded-sm focus:border-copper focus:outline-none">
                </div>
            </div>

            <!-- Saved User Custom Menus (If Authenticated) -->
            @auth
                @if ($savedMenus->isNotEmpty())
                    <div class="bg-[#FFF8F0] border-2 border-copper rounded-sm p-6 shadow-rustic">
                        <h3 class="font-heading font-bold text-sm uppercase tracking-wider text-rustic-900 mb-3">
                            ⭐ Racikan Favorit yang Pernah Kamu Simpan
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($savedMenus as $saved)
                                <div class="bg-parchment border border-rustic-400 p-3 rounded-sm flex items-center justify-between">
                                    <div>
                                        <div class="font-display font-bold text-sm text-rustic-900">{{ $saved->name }}</div>
                                        <div class="text-[11px] text-rustic-600 italic">
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
                                                class="bg-rustic-800 text-parchment font-heading text-[10px] uppercase px-3 py-1.5 border border-copper hover:bg-rustic-700">
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
