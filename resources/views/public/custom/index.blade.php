@extends('layouts.public')

@section('title', 'Meja Barista — Racik Minumanmu Sendiri')

@section('content')
<div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 py-12 relative overflow-hidden"
     x-data="{
        name: 'Racikan Pilihan Saya',
        selectedBase: 'Espresso Blend',
        basePrice: 16000,
        selectedSize: '{{ $sizes->first()?->name ?? '18 oz (Large)' }}',
        sizePrice: {{ (float) ($sizes->first()?->price ?? 8000) }},
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
        },
        getLiquidColor() {
            const b = this.selectedBase.toLowerCase();
            if (b.includes('matcha')) return '#7A8467';
            if (b.includes('chocolate') || b.includes('cokelat')) return '#4A3224';
            if (b.includes('latte') || b.includes('milk') || b.includes('susu')) return '#CDB48F';
            if (b.includes('tea') || b.includes('earl')) return '#B5753A';
            return '#2B1D14';
        },
        getCupImage() {
            const s = this.selectedSize.toLowerCase();
            if (s.includes('18') || s.includes('large') || s.includes('l')) {
                return '{{ asset('images/cup-18oz.png') }}';
            }
            if (s.includes('12') || s.includes('regular') || s.includes('medium') || s.includes('m')) {
                return '{{ asset('images/cup-12oz.png') }}';
            }
            return '{{ asset('images/cup-8oz.png') }}';
        },
        getCupHeightClass() {
            const s = this.selectedSize.toLowerCase();
            if (s.includes('18') || s.includes('large') || s.includes('l')) {
                return 'h-56 sm:h-60';
            }
            if (s.includes('12') || s.includes('regular') || s.includes('medium') || s.includes('m')) {
                return 'h-48 sm:h-52';
            }
            return 'h-40 sm:h-44';
        },
        getLiquidClipPath() {
            const s = this.selectedSize.toLowerCase();
            if (s.includes('18') || s.includes('large') || s.includes('l')) {
                return 'polygon(6.5% 25%, 93.5% 25%, 81% 96%, 19% 96%)';
            }
            if (s.includes('12') || s.includes('regular') || s.includes('medium') || s.includes('m')) {
                return 'polygon(7% 28%, 93% 28%, 81% 95.5%, 19% 95.5%)';
            }
            return 'polygon(7.5% 32%, 92.5% 32%, 81% 95%, 19% 95%)';
        }
     }">

    <!-- Pinggiran Biji Kopi Meja Barista: Kiri & Kanan Melayang -->
    <img src="{{ asset('images/beans-falling.png') }}" class="absolute left-2 lg:left-6 top-16 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-45 -rotate-12 drop-shadow-md select-none hidden sm:block animate-float-slow" alt="" />
    <img src="{{ asset('images/beans-cluster.png') }}" class="absolute right-4 lg:right-8 top-12 w-20 sm:w-24 h-24 object-contain pointer-events-none opacity-50 rotate-45 select-none hidden md:block animate-float-reverse" alt="" />
    <img src="{{ asset('images/beans-falling.png') }}" class="absolute right-2 lg:right-6 bottom-24 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-45 rotate-6 drop-shadow-md select-none hidden sm:block animate-float" alt="" />
    <img src="{{ asset('images/beans-cluster.png') }}" class="absolute left-4 lg:left-8 bottom-16 w-20 sm:w-24 h-24 object-contain pointer-events-none opacity-40 -rotate-12 select-none hidden md:block animate-float-drift" alt="" />

    <!-- Page Header (Rustic Modern) -->
    <div class="mb-10 border-b border-dashed border-[#CDB48F] pb-6 relative z-10 reveal-on-scroll">
        <span class="font-hand text-2xl text-[#B5753A]">Meja Barista Interaktif</span>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-semibold text-[#2B1D14] tracking-tight mt-1">
            Racik Minumanmu Sendiri
        </h1>
        <p class="text-[#6B5646] text-sm sm:text-base mt-2 max-w-2xl">
            Eksplorasi kombinasi rasa yang pas dengan seleramu. Pilih bahan dasar, ukuran, tingkat gula, suhu, dan topping.
        </p>
    </div>

    <!-- Main Builder Grid: Left Preview & Summary, Right Controls (03-DESIGN.md Section 17.1) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12 items-start">

        <!-- LEFT COLUMN: SUMMARY & PREVIEW (STICKY MEJA BARISTA) -->
        <div class="lg:col-span-5 xl:col-span-5 2xl:col-span-4 lg:sticky lg:top-24 order-2 lg:order-1">
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 sm:p-7 space-y-6 shadow-warm relative overflow-hidden motion-frame">
                <img src="{{ asset('images/beans-cluster.png') }}" class="absolute -bottom-4 -right-4 w-20 h-20 object-contain pointer-events-none drop-shadow-sm opacity-60 z-0" alt="Biji Kopi" />

                <!-- Subtle kraft tag header -->
                <div class="flex items-center justify-between border-b border-dashed border-[#CDB48F] pb-4">
                    <span class="text-xs uppercase tracking-wider font-bold text-[#4A3224]">✦ Ringkasan Seduhan</span>
                    <span class="font-hand text-xl text-[#B5753A]">"Diracik segar"</span>
                </div>

                <!-- Interactive Cup Preview (Realistic Clear Plastic Cup with CoffeBar Kraft Sticker) -->
                <div class="bg-[#F4ECDD] border border-[#CDB48F] rounded-2xl p-6 text-center space-y-4">
                    <div class="relative h-60 mx-auto flex items-end justify-center">
                        <!-- Single Cup Wrapper: exactly bounds the cup image and liquid together -->
                        <div class="relative inline-flex items-end justify-center" :class="getCupHeightClass()">
                            <!-- Liquid & Ice Layer (Clipped to cup trapezoid slope, sits directly behind transparent cup body) -->
                            <div class="absolute inset-0 transition-all duration-500 pointer-events-none overflow-hidden"
                                 :style="'background-color: ' + getLiquidColor() + '; clip-path: ' + getLiquidClipPath() + '; opacity: 0.94;'">
                                <!-- Ice cubes floating at liquid surface -->
                                <div x-show="selectedIce.includes('Dingin')"
                                     class="absolute inset-x-3 top-[30%] flex flex-wrap gap-1.5 justify-center opacity-90 pointer-events-none">
                                    <span class="w-3.5 h-3.5 bg-white/85 border border-white/70 rounded-sm rotate-6 shadow-sm"></span>
                                    <span class="w-3.5 h-3.5 bg-white/85 border border-white/70 rounded-sm -rotate-12 shadow-sm"></span>
                                    <span class="w-3 h-3 bg-white/85 border border-white/70 rounded-sm rotate-45 shadow-sm"></span>
                                </div>
                                <!-- Foam / Crema layer at liquid surface -->
                                <div class="absolute inset-x-0 top-[26%] h-2 bg-white/20 blur-[1px]"></div>
                            </div>

                            <!-- Realistic Transparent Cup Image with CoffeBar Kraft Sticker (Foreground) -->
                            <img :src="getCupImage()"
                                 class="relative z-10 h-full w-auto object-contain drop-shadow-lg transition-all duration-300 pointer-events-none select-none"
                                 alt="Gelas CoffeBar">
                        </div>
                    </div>

                    <!-- Drink Name (Caveat Styled) -->
                    <div>
                        <div class="font-hand text-3xl font-semibold text-[#2B1D14] truncate" x-text="name"></div>
                        <div class="font-display font-medium text-sm text-[#B5753A] mt-0.5" x-text="selectedBase"></div>
                    </div>

                    <!-- Config Breakdown List -->
                    <div class="pt-3 border-t border-dashed border-[#CDB48F]/70 text-xs text-[#6B5646] space-y-1 text-left">
                        <div class="flex justify-between">
                            <span>Ukuran:</span>
                            <strong class="text-[#2B1D14]" x-text="selectedSize"></strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Tingkat Gula:</span>
                            <strong class="text-[#2B1D14]" x-text="selectedSugar"></strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Suhu:</span>
                            <strong class="text-[#2B1D14]" x-text="selectedIce"></strong>
                        </div>
                    </div>

                    <!-- Toppings Chips in Preview -->
                    <div class="pt-3 border-t border-dashed border-[#CDB48F]/70 text-left text-xs">
                        <span class="text-[#6B5646] block mb-1.5 font-medium">Topping Terpilih:</span>
                        <template x-if="selectedToppings.length === 0">
                            <span class="text-[#6B5646]/60 italic">Tanpa topping tambahan</span>
                        </template>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="t in selectedToppings" :key="t.name">
                                <span class="bg-[#E8DCC4] border border-[#CDB48F] text-[#4A3224] font-semibold px-2 py-0.5 rounded text-[11px]" x-text="t.name"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown with Dotted Leader & Total -->
                <div class="border-t border-dashed border-[#CDB48F] pt-4 space-y-2 text-xs text-[#6B5646]">
                    <div class="flex justify-between items-baseline">
                        <span>Harga Satuan</span>
                        <span class="font-medium text-[#2B1D14]" x-text="formatRupiah(calculateUnitPrice())"></span>
                    </div>
                    <div class="flex justify-between items-baseline">
                        <span>Jumlah</span>
                        <span class="font-medium text-[#2B1D14]" x-text="quantity + ' Gelas'"></span>
                    </div>
                    <div class="flex justify-between items-baseline pt-2 border-t border-dashed border-[#CDB48F]">
                        <span class="font-bold text-sm text-[#4A3224]">Total Harga</span>
                        <span class="font-display font-bold text-3xl text-[#2B1D14]"
                              x-text="formatRupiah(calculateTotalPrice())"></span>
                    </div>
                </div>

                <!-- Action Forms (Order & Save) -->
                <div class="space-y-3 pt-2">
                    @auth
                        <!-- Authenticated Form: Pesan Langsung (route: custom.add-to-cart) -->
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
                                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition shadow-warm cursor-pointer">
                                Tambahkan ke Keranjang
                            </button>
                        </form>

                        <!-- Authenticated Form: Simpan ke Favorit (route: custom.store) -->
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
                                    class="w-full bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4] font-semibold text-xs uppercase tracking-wider py-3 rounded-xl transition cursor-pointer">
                                Simpan ke Favorit
                            </button>
                        </form>
                    @else
                        <!-- Guest notice -->
                        <div class="bg-[#E8DCC4] border border-[#CDB48F] p-4 rounded-xl text-xs text-[#4A3224] space-y-1">
                            <strong class="block text-sm font-display text-[#2B1D14]">Masuk untuk Memesan</strong>
                            <p>Silakan masuk atau daftar terlebih dahulu untuk memesan racikan ini.</p>
                        </div>

                        <a href="{{ route('login') }}"
                           class="block text-center w-full bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition shadow-warm">
                            Masuk untuk Memesan
                        </a>
                    @endauth
                </div>

            </div>
        </div>

        <!-- RIGHT COLUMN: STEPPER OPTIONS CONTROLS (MEJA RACIK) -->
        <div class="lg:col-span-7 xl:col-span-7 2xl:col-span-8 space-y-6 order-1 lg:order-2">

            <!-- 1. Nama Kreasi (Caveat Style) -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <label for="craft-name" class="font-display font-semibold text-base text-[#2B1D14]">
                        1. Beri Nama Racikan Minumanmu
                    </label>
                    <span class="font-hand text-xl text-[#B5753A]">Bisa diganti</span>
                </div>
                <input type="text"
                       id="craft-name"
                       x-model="name"
                       placeholder="Beri nama kreasimu..."
                       class="w-full bg-[#F4ECDD] border border-[#CDB48F] font-hand text-2xl px-4 py-2.5 text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <!-- 2. Pilihan Base Minuman -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 shadow-sm">
                <label class="block font-display font-semibold text-base text-[#2B1D14] mb-3">
                    2. Pilih Bahan Dasar (Base)
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($bases as $base)
                        <div @click="selectBase('{{ $base->name }}', {{ (float) $base->price }})"
                             class="p-4 border rounded-xl cursor-pointer transition flex items-center justify-between"
                             :class="selectedBase === '{{ $base->name }}' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            <div class="font-display font-semibold text-sm flex items-center gap-2">
                                @if(str_contains(strtolower($base->name), 'espresso') || str_contains(strtolower($base->name), 'kopi'))
                                    <img src="{{ asset('images/bean-single.png') }}" class="w-4 h-4 object-contain inline-block" alt="Kopi">
                                @endif
                                <span>{{ $base->name }}</span>
                            </div>
                            <div class="font-bold text-xs text-[#2B1D14]">
                                {{ $base->formatted_price }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Ukuran Cup (18 oz, 12 oz, 8 oz dengan Visual Gelas Asli CoffeBar) -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <label class="block font-display font-semibold text-base text-[#2B1D14]">
                            3. Pilih Ukuran Gelas
                        </label>
                        <p class="text-xs text-[#6B5646]">Tersedia dalam 3 ukuran cup CoffeBar: 18 oz, 12 oz, dan 8 oz.</p>
                    </div>
                    <span class="font-hand text-xl text-[#B5753A]">Cup CoffeBar</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($sizes as $size)
                        @php
                            $sName = strtolower($size->name);
                            $cupImg = str_contains($sName, '18') || str_contains($sName, 'large') || str_contains($sName, 'l')
                                ? asset('images/cup-18oz.png')
                                : (str_contains($sName, '12') || str_contains($sName, 'medium') || str_contains($sName, 'regular') || str_contains($sName, 'm')
                                    ? asset('images/cup-12oz.png')
                                    : asset('images/cup-8oz.png'));
                            $cupHeight = str_contains($sName, '18') || str_contains($sName, 'large') || str_contains($sName, 'l')
                                ? 'h-28'
                                : (str_contains($sName, '12') || str_contains($sName, 'medium') || str_contains($sName, 'regular') || str_contains($sName, 'm')
                                    ? 'h-24'
                                    : 'h-20');
                            $ozLabel = str_contains($sName, '18') ? '18 oz' : (str_contains($sName, '12') ? '12 oz' : '8 oz');
                            $typeLabel = str_contains($sName, '18') ? 'Large (Besar)' : (str_contains($sName, '12') ? 'Regular (Sedang)' : 'Small (Kecil)');
                        @endphp
                        <div @click="selectSize('{{ $size->name }}', {{ (float) $size->price }})"
                             class="p-4 border rounded-2xl cursor-pointer transition flex flex-col items-center justify-between text-center group hover:-translate-y-1 hover:shadow-warm"
                             :class="selectedSize === '{{ $size->name }}' ? 'bg-[#E8DCC4] border-[#B5753A] ring-2 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/40'">

                            <!-- Cup Thumbnail Visual -->
                            <div class="h-32 w-full flex items-end justify-center pb-2 mb-1">
                                <img src="{{ $cupImg }}"
                                     class="{{ $cupHeight }} w-auto object-contain drop-shadow-md group-hover:scale-105 transition transform duration-300 pointer-events-none select-none"
                                     alt="{{ $size->name }}">
                            </div>

                            <div class="w-full pt-3 border-t border-dashed border-[#CDB48F]/70">
                                <div class="font-display font-bold text-base text-[#2B1D14]">{{ $ozLabel }}</div>
                                <div class="text-[11px] text-[#6B5646] font-medium">{{ $typeLabel }}</div>
                                <div class="text-xs font-bold text-[#B5753A] mt-1.5">
                                    {{ (float) $size->price > 0 ? '+ ' . $size->formatted_price : '+ Rp 0' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. Gula & Suhu -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 shadow-sm space-y-6">
                <!-- Gula -->
                <div>
                    <label class="block font-display font-semibold text-base text-[#2B1D14] mb-3">
                        4. Tingkat Kemanisan
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <div @click="selectedSugar = 'Tanpa Gula (0%)'"
                             class="p-3 text-center border rounded-xl cursor-pointer text-xs font-semibold transition"
                             :class="selectedSugar === 'Tanpa Gula (0%)' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            0% (Tanpa Gula)
                        </div>
                        <div @click="selectedSugar = 'Sedikit Manis (50%)'"
                             class="p-3 text-center border rounded-xl cursor-pointer text-xs font-semibold transition"
                             :class="selectedSugar === 'Sedikit Manis (50%)' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            50% (Sedikit Manis)
                        </div>
                        <div @click="selectedSugar = 'Normal (100%)'"
                             class="p-3 text-center border rounded-xl cursor-pointer text-xs font-semibold transition"
                             :class="selectedSugar === 'Normal (100%)' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            100% (Normal)
                        </div>
                    </div>
                </div>

                <!-- Suhu -->
                <div>
                    <label class="block font-display font-semibold text-base text-[#2B1D14] mb-3">
                        5. Suhu Penyajian
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div @click="selectedIce = 'Dingin (Es Batu)'"
                             class="p-3 text-center border rounded-xl cursor-pointer text-xs font-semibold transition"
                             :class="selectedIce === 'Dingin (Es Batu)' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            Dingin (Es Batu)
                        </div>
                        <div @click="selectedIce = 'Hangat / Panas'"
                             class="p-3 text-center border rounded-xl cursor-pointer text-xs font-semibold transition"
                             :class="selectedIce === 'Hangat / Panas' ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            Hangat / Panas
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Toppings -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 shadow-sm">
                <label class="block font-display font-semibold text-base text-[#2B1D14] mb-3">
                    6. Tambahan Topping (Opsional)
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($toppings as $topping)
                        <div @click="toggleTopping('{{ $topping->name }}', {{ (float) $topping->price }})"
                             class="p-3.5 border rounded-xl cursor-pointer transition flex items-center justify-between"
                             :class="isToppingSelected('{{ $topping->name }}') ? 'bg-[#E8DCC4] border-[#B5753A] ring-1 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            <div class="flex items-center space-x-2">
                                <span x-show="isToppingSelected('{{ $topping->name }}')" class="text-[#B5753A] font-bold">✓</span>
                                <span class="text-xs font-semibold">{{ $topping->name }}</span>
                            </div>
                            <span class="text-xs font-bold text-[#2B1D14]">
                                + {{ $topping->formatted_price }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 6. Jumlah & Catatan Barista -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block font-semibold text-xs text-[#4A3224] mb-1.5 uppercase tracking-wider">
                        Jumlah Gelas
                    </label>
                    <div class="flex items-center border border-[#CDB48F] rounded-xl bg-[#FFFDF8]">
                        <button type="button" @click="if (quantity > 1) quantity--"
                                class="px-3.5 py-2 font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-l-xl transition">-</button>
                        <input type="number" x-model="quantity" min="1"
                               class="w-full text-center bg-transparent font-semibold text-sm border-0 focus:ring-0 p-1 text-[#2B1D14]">
                        <button type="button" @click="quantity++"
                                class="px-3.5 py-2 font-bold text-[#4A3224] hover:bg-[#E8DCC4] rounded-r-xl transition">+</button>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-xs text-[#4A3224] mb-1.5 uppercase tracking-wider">
                        Catatan Khusus Barista
                    </label>
                    <input type="text"
                           x-model="notes"
                           placeholder="Contoh: Es dipisah, less sweet..."
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] text-xs p-2.5 rounded-xl text-[#2B1D14] focus:border-[#B5753A] focus:outline-none">
                </div>
            </div>

            <!-- Saved User Custom Menus (If Authenticated) -->
            @auth
                @if ($savedMenus->isNotEmpty())
                    <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4 border-b border-dashed border-[#CDB48F] pb-3">
                            <h3 class="font-display font-semibold text-base text-[#2B1D14]">
                                Racikan Favorit yang Disimpan
                            </h3>
                            <span class="text-xs text-[#6B5646] font-medium">{{ $savedMenus->count() }} Kreasi</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($savedMenus as $saved)
                                <div class="bg-[#F4ECDD] border border-[#CDB48F] p-4 rounded-xl flex items-center justify-between">
                                    <div>
                                        <div class="font-hand text-xl font-bold text-[#2B1D14]">{{ $saved->name }}</div>
                                        <div class="text-xs text-[#6B5646]">
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
                                                class="bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase tracking-wider font-bold px-3.5 py-2 rounded-lg transition shadow-sm cursor-pointer">
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
