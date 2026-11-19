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
                            <!-- Liquid Layer (Clipped to cup trapezoid slope, sits directly behind transparent cup body) -->
                            <div class="absolute inset-0 transition-all duration-500 pointer-events-none overflow-hidden"
                                 :style="'background-color: ' + getLiquidColor() + '; clip-path: ' + getLiquidClipPath() + '; opacity: 0.94;'">
                                <!-- Foam / Crema layer at liquid surface -->
                                <div class="absolute inset-x-0 top-[26%] h-2 bg-white/20 blur-[1px]"></div>
                            </div>

                            <!-- Realistic 3D Glassy Submerged Ice Cubes Layer (Submerged in liquid, inside cup behind sticker & rim) -->
                            <div x-show="selectedIce.includes('Dingin')"
                                 x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-600"
                                 x-transition:enter-start="opacity-0 -translate-y-6 scale-75"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-3 scale-75"
                                 class="absolute inset-x-0 z-[5] pointer-events-none flex justify-center items-center"
                                 :style="'top: ' + (selectedSize.toLowerCase().includes('18') ? '19%' : (selectedSize.toLowerCase().includes('12') ? '21%' : '24%'))">
                                <div class="relative w-full max-w-[210px] h-16 flex items-center justify-center">
                                    <!-- Cube 1 (Left Submerged Ice Chunk) -->
                                    <svg viewBox="0 0 50 50" class="w-10 h-10 sm:w-11 sm:h-11 absolute left-2 -top-1 -rotate-12 drop-shadow-[0_4px_8px_rgba(0,0,0,0.35)] animate-float-slow">
                                        <!-- Submerged Left & Right Facets: semi-translucent crystal tinted by liquid below -->
                                        <polygon points="6,15 25,26 25,44 6,33" fill="rgba(255,255,255,0.22)" stroke="rgba(255,255,255,0.65)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <polygon points="25,26 44,15 44,33 25,44" fill="rgba(255,255,255,0.14)" stroke="rgba(255,255,255,0.55)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <!-- Top Face Floating Above Water: crisp reflection & specular frost -->
                                        <polygon points="25,4 44,15 25,26 6,15" fill="rgba(255,255,255,0.72)" stroke="rgba(255,255,255,0.95)" stroke-width="1.5" stroke-linejoin="round"/>
                                        <!-- Internal refraction fracture line -->
                                        <line x1="14" y1="18" x2="22" y2="38" stroke="rgba(255,255,255,0.85)" stroke-width="1.2" stroke-linecap="round"/>
                                        <!-- Frost glint -->
                                        <circle cx="26" cy="15" r="1.8" fill="white" opacity="0.95"/>
                                    </svg>

                                    <!-- Cube 2 (Center-Left Chunky Ice Block) -->
                                    <svg viewBox="0 0 50 50" class="w-11 h-11 sm:w-12 sm:h-12 absolute left-14 -top-3 rotate-6 drop-shadow-[0_5px_10px_rgba(0,0,0,0.4)] animate-float">
                                        <!-- Submerged Left & Right Facets -->
                                        <polygon points="6,15 25,26 25,44 6,33" fill="rgba(255,255,255,0.28)" stroke="rgba(255,255,255,0.75)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <polygon points="25,26 44,15 44,33 25,44" fill="rgba(255,255,255,0.18)" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <!-- Top Floating Face -->
                                        <polygon points="25,4 44,15 25,26 6,15" fill="rgba(255,255,255,0.78)" stroke="rgba(255,255,255,0.98)" stroke-width="1.5" stroke-linejoin="round"/>
                                        <!-- Fracture & Highlights -->
                                        <line x1="12" y1="17" x2="20" y2="36" stroke="rgba(255,255,255,0.9)" stroke-width="1.4" stroke-linecap="round"/>
                                        <circle cx="25" cy="14" r="2" fill="white" opacity="0.95"/>
                                    </svg>

                                    <!-- Cube 3 (Center-Right Floating Ice Block) -->
                                    <svg viewBox="0 0 50 50" class="w-11 h-11 sm:w-12 sm:h-12 absolute right-12 -top-2.5 -rotate-8 drop-shadow-[0_4px_8px_rgba(0,0,0,0.4)] animate-float-reverse">
                                        <!-- Submerged Left & Right Facets -->
                                        <polygon points="6,15 25,26 25,44 6,33" fill="rgba(255,255,255,0.24)" stroke="rgba(255,255,255,0.7)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <polygon points="25,26 44,15 44,33 25,44" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.58)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <!-- Top Floating Face -->
                                        <polygon points="25,4 44,15 25,26 6,15" fill="rgba(255,255,255,0.74)" stroke="rgba(255,255,255,0.95)" stroke-width="1.5" stroke-linejoin="round"/>
                                        <!-- Fracture Line -->
                                        <line x1="15" y1="18" x2="21" y2="37" stroke="rgba(255,255,255,0.85)" stroke-width="1.2" stroke-linecap="round"/>
                                        <circle cx="27" cy="16" r="1.6" fill="white" opacity="0.9"/>
                                    </svg>

                                    <!-- Cube 4 (Right Dipping Ice Chunk) -->
                                    <svg viewBox="0 0 50 50" class="w-10 h-10 sm:w-11 sm:h-11 absolute right-2 -top-0.5 rotate-16 drop-shadow-[0_4px_8px_rgba(0,0,0,0.35)] animate-float">
                                        <!-- Submerged Facets -->
                                        <polygon points="6,15 25,26 25,44 6,33" fill="rgba(255,255,255,0.20)" stroke="rgba(255,255,255,0.65)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <polygon points="25,26 44,15 44,33 25,44" fill="rgba(255,255,255,0.12)" stroke="rgba(255,255,255,0.52)" stroke-width="1.4" stroke-linejoin="round"/>
                                        <!-- Top Floating Face -->
                                        <polygon points="25,4 44,15 25,26 6,15" fill="rgba(255,255,255,0.70)" stroke="rgba(255,255,255,0.92)" stroke-width="1.5" stroke-linejoin="round"/>
                                        <!-- Highlights -->
                                        <line x1="14" y1="17" x2="23" y2="35" stroke="rgba(255,255,255,0.8)" stroke-width="1.2" stroke-linecap="round"/>
                                        <circle cx="24" cy="15" r="1.5" fill="white" opacity="0.9"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Warm Steam Wisps (When Hangat / Panas is selected) -->
                            <div x-show="selectedIce.includes('Hangat')"
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0 translate-y-3"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-3"
                                 class="absolute inset-x-0 -top-6 flex justify-center space-x-3 pointer-events-none z-20">
                                <svg class="w-4 h-10 text-white/50 animate-float-slow" viewBox="0 0 20 60" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                    <path d="M 10 55 Q 16 40 8 28 Q 2 15 12 5" />
                                </svg>
                                <svg class="w-4 h-12 text-white/45 animate-float -mt-2" viewBox="0 0 20 60" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                    <path d="M 10 55 Q 4 40 12 28 Q 18 15 8 5" />
                                </svg>
                                <svg class="w-4 h-9 text-white/35 animate-float-drift mt-1" viewBox="0 0 20 60" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M 10 55 Q 15 42 7 30 Q 3 18 11 8" />
                                </svg>
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
                             class="p-4 text-center border rounded-xl cursor-pointer text-xs font-semibold transition flex items-center justify-center space-x-2"
                             :class="selectedIce === 'Dingin (Es Batu)' ? 'bg-[#E8DCC4] border-[#B5753A] ring-2 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            <span class="text-lg">🧊</span>
                            <span>Dingin (Es Batu)</span>
                        </div>
                        <div @click="selectedIce = 'Hangat / Panas'"
                             class="p-4 text-center border rounded-xl cursor-pointer text-xs font-semibold transition flex items-center justify-center space-x-2"
                             :class="selectedIce === 'Hangat / Panas' ? 'bg-[#E8DCC4] border-[#B5753A] ring-2 ring-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4]/50'">
                            <span class="text-lg">♨️</span>
                            <span>Hangat / Panas</span>
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
