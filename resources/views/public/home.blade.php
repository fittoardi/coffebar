@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    <!-- HERO SECTION WITH RETRO VINTAGE ROASTERY IMMERSION -->
    <section class="relative bg-[#160C06] text-parchment py-16 sm:py-24 lg:py-28 overflow-hidden border-b-4 border-retro-orange"
             x-data="heroInteractive()"
             @mousemove="onMouseMove($event)"
             @mouseleave="onMouseLeave()">

        <!-- 1. Background Layer: Full-Section Cinematic Exploding Beans with Retro Halftone & Warm Vignette -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none select-none">
            
            <!-- Floating Beans Video / Image with Mouse Parallax 3D Tilt -->
            <div class="absolute inset-0 w-full h-full transition-transform duration-300 ease-out"
                 :style="parallaxStyle">
                <video autoplay loop muted playsinline
                       poster="{{ asset('images/coffee-beans-floating.jpg') }}"
                       class="w-full h-full object-cover object-center lg:object-[68%_center] opacity-90 sm:opacity-95 filter brightness-105 contrast-110">
                    <source src="{{ asset('images/coffee-beans-cinematic.mp4') }}" type="video/mp4">
                    <img src="{{ asset('images/coffee-beans-floating.jpg') }}"
                         alt="Cinematic Roasted Coffee Beans Background"
                         class="w-full h-full object-cover object-center lg:object-[68%_center] opacity-90 sm:opacity-95 filter brightness-105 contrast-110">
                </video>
            </div>

            <!-- Warm Roastery Inspection Spotlight (Follows mouse) -->
            <div class="absolute inset-0 transition-opacity duration-500 opacity-70 sm:opacity-85 pointer-events-none"
                 :style="spotlightStyle"></div>

            <!-- Deep Espresso Vignette for Crisp Retro Typography Readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#160C06] via-[#160C06]/85 lg:via-[#160C06]/70 to-transparent pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#160C06]/90 via-transparent to-[#160C06]/95 pointer-events-none"></div>

            <!-- Retro Halftone Print Texture Overlay -->
            <div class="absolute inset-0 opacity-12 bg-[radial-gradient(#CF5328_1.5px,transparent_1.5px)] [background-size:20px_20px] pointer-events-none"></div>

            <!-- Floating Aromatic Embers / Sparks -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <span class="absolute bottom-1/4 right-1/4 w-1.5 h-1.5 rounded-full bg-retro-orange animate-ember-1"></span>
                <span class="absolute bottom-1/3 right-1/3 w-2 h-2 rounded-full bg-retro-mustard animate-ember-2"></span>
                <span class="absolute bottom-1/5 right-1/2 w-1 h-1 rounded-full bg-parchment animate-ember-3"></span>
                <span class="absolute bottom-1/2 right-1/5 w-1.5 h-1.5 rounded-full bg-retro-orange animate-ember-2"></span>
            </div>
        </div>

        <!-- 2. Hero Interactive Content Layer (12-Column Asymmetric Grid) -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Decorative floating side beans for lively ambiance -->
            <div class="hidden xl:block absolute -left-12 top-10 pointer-events-none select-none z-10">
                <img src="{{ asset('images/bean-cutout-1.png') }}" alt="" class="w-14 h-auto opacity-75 animate-float-drift filter drop-shadow-2xl">
            </div>
            <div class="hidden xl:block absolute -left-8 bottom-12 pointer-events-none select-none z-10">
                <img src="{{ asset('images/bean-cutout-3.png') }}" alt="" class="w-12 h-auto opacity-70 animate-float-left filter drop-shadow-xl">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center min-h-[540px]">
                
                <!-- LEFT COLUMN: Headline, Story & Interactive Roast Selector -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    
                    <!-- Retro Tagline Badge with Rotating Roastery Stamp -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                        <div class="inline-flex items-center gap-2 bg-rustic-950/90 backdrop-blur-md text-retro-orange border-2 border-retro-orange font-heading uppercase text-xs tracking-widest px-4 py-1.5 rounded-full shadow-rustic-sm">
                            <span class="w-2 h-2 rounded-full bg-retro-mustard animate-ping"></span>
                            <span>★ {{ $settings['shop_tagline'] ?? 'Kedai Kopi Autentik Nusantara' }} ★</span>
                        </div>
                        <span class="hidden sm:inline-block px-3 py-1 bg-retro-mustard text-rustic-950 border-2 border-rustic-950 font-heading text-[10px] font-black uppercase tracking-wider rounded -rotate-1 shadow-sm">
                            ESTD. 2026
                        </span>
                    </div>

                    <!-- Dramatic Retro Display Headline -->
                    <h1 class="font-display font-black text-4xl sm:text-6xl lg:text-7xl text-parchment tracking-tight leading-[1.08] drop-shadow-2xl">
                        {{ $settings['hero_title'] ?? 'Kopi Terbaik, Diracik Untukmu' }}
                    </h1>

                    <!-- Retro Divider -->
                    <div class="text-retro-orange text-base tracking-widest select-none flex items-center justify-center lg:justify-start gap-3">
                        <span class="h-0.5 w-12 bg-retro-orange/70 inline-block"></span>
                        <span class="text-retro-mustard font-bold">✦ ✿ ✦</span>
                        <span class="h-0.5 w-12 bg-retro-orange/70 inline-block"></span>
                    </div>

                    <!-- Subtitle -->
                    <p class="font-body italic text-base sm:text-xl text-rustic-200 max-w-2xl mx-auto lg:mx-0 leading-relaxed drop-shadow-md">
                        "{{ $settings['hero_subtitle'] ?? 'Setiap tetes seduhan menyimpan cerita kehangatan meja kayu, obrolan akrab, dan biji kopi pilihan yang disangrai dengan penuh cinta.' }}"
                    </p>

                    <!-- Interactive Roast Origin Tabs (Visitors can click to switch tasting profile!) -->
                    <div class="pt-2">
                        <div class="text-[11px] font-heading uppercase tracking-widest text-retro-mustard mb-2 font-bold flex items-center justify-center lg:justify-start gap-2">
                            <span>✦ Karakter Biji Pilihan Barista:</span>
                            <span class="text-xs text-rustic-300 font-normal lowercase">(klik untuk eksplorasi)</span>
                        </div>
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                            <template x-for="(origin, idx) in origins" :key="idx">
                                <button @click="activeOrigin = idx"
                                        type="button"
                                        :class="activeOrigin === idx
                                            ? 'bg-retro-orange text-parchment border-rustic-950 shadow-rustic font-bold scale-102 ring-2 ring-retro-mustard'
                                            : 'bg-rustic-950/80 backdrop-blur-md text-rustic-200 border-rustic-700 hover:border-retro-orange hover:text-parchment'"
                                        class="px-3.5 py-1.5 border-2 rounded-full text-xs font-heading tracking-wider uppercase transition-all duration-200 cursor-pointer">
                                    <span x-text="origin.name"></span>
                                </button>
                            </template>
                        </div>

                        <!-- Active Origin Sensory Specs Card with Animated Meters -->
                        <div class="mt-3 bg-rustic-950/90 backdrop-blur-md border-2 border-retro-orange/60 rounded-xl p-4 max-w-lg mx-auto lg:mx-0 shadow-2xl text-xs font-heading">
                            <div class="flex items-center justify-between border-b border-rustic-800 pb-2 mb-2.5">
                                <div>
                                    <span class="text-retro-mustard font-bold text-sm" x-text="origins[activeOrigin].sub">Aceh Gayo Highland Wine</span>
                                    <div class="text-[10px] text-rustic-400 font-mono mt-0.5" x-text="origins[activeOrigin].altitude + ' • ' + origins[activeOrigin].process"></div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-0.5 bg-retro-orange/20 text-retro-orange rounded text-[11px] font-mono font-bold border border-retro-orange/30" x-text="origins[activeOrigin].score">89.2 SCA</span>
                                    <div class="text-[10px] text-rustic-400 font-mono mt-0.5" x-text="origins[activeOrigin].roast"></div>
                                </div>
                            </div>
                            
                            <!-- Tasting Notes Tags -->
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <template x-for="note in origins[activeOrigin].notes" :key="note">
                                    <span class="px-2 py-0.5 rounded bg-rustic-900 border border-rustic-700 text-[10px] text-rustic-200" x-text="'✦ ' + note"></span>
                                </template>
                            </div>

                            <!-- Sensory Meters: Body, Acidity, Sweetness -->
                            <div class="space-y-1.5 pt-1 border-t border-rustic-800/80 text-[10px]">
                                <div class="flex items-center gap-2">
                                    <span class="w-16 text-rustic-400 uppercase">Body:</span>
                                    <div class="flex-1 bg-rustic-900 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-retro-orange h-full transition-all duration-500 rounded-full" :style="'width: ' + origins[activeOrigin].body + '%'"></div>
                                    </div>
                                    <span class="w-8 text-right font-mono text-rustic-300" x-text="origins[activeOrigin].body + '%'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-16 text-rustic-400 uppercase">Acidity:</span>
                                    <div class="flex-1 bg-rustic-900 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-retro-mustard h-full transition-all duration-500 rounded-full" :style="'width: ' + origins[activeOrigin].acidity + '%'"></div>
                                    </div>
                                    <span class="w-8 text-right font-mono text-rustic-300" x-text="origins[activeOrigin].acidity + '%'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-16 text-rustic-400 uppercase">Sweetness:</span>
                                    <div class="flex-1 bg-rustic-900 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-amber-500 h-full transition-all duration-500 rounded-full" :style="'width: ' + origins[activeOrigin].sweetness + '%'"></div>
                                    </div>
                                    <span class="w-8 text-right font-mono text-rustic-300" x-text="origins[activeOrigin].sweetness + '%'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Retro Tactile Call-to-Action Buttons -->
                    <div class="pt-3 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('menu.index') }}"
                           class="w-full sm:w-auto bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading tracking-widest uppercase text-sm py-3.5 px-8 shadow-rustic transition duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none font-bold flex items-center justify-center gap-2 group">
                            <span>LIHAT DAFTAR MENU</span>
                            <span class="group-hover:translate-x-1 transition duration-150">→</span>
                        </a>
                        <a href="{{ route('custom.index') }}"
                           class="w-full sm:w-auto bg-parchment text-rustic-900 border-2 border-rustic-900 hover:bg-parchment-dark font-heading tracking-widest uppercase text-sm py-3.5 px-8 shadow-rustic transition duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none font-bold">
                            BERKREASI SENDIRI ✦
                        </a>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Floating Sensory Badges & Rotating Seal Stamp -->
                <div class="lg:col-span-5 relative flex flex-col items-center lg:items-end justify-center pointer-events-none min-h-[400px] lg:min-h-[480px]">
                    
                    <!-- Iconic Rotating Retro Seal Stamp Badge (Behance Style) -->
                    <div class="absolute -top-4 right-4 hidden sm:flex items-center justify-center pointer-events-none select-none z-20">
                        <div class="relative w-32 h-32 flex items-center justify-center">
                            <svg class="w-full h-full animate-spin-slow text-retro-mustard" viewBox="0 0 120 120">
                                <path id="circlePath" d="M 60, 60 m -45, 0 a 45,45 0 1,1 90,0 a 45,45 0 1,1 -90,0" fill="none"/>
                                <text font-family="'Libre Baskerville', Georgia, serif" font-size="9" font-weight="bold" fill="currentColor" letter-spacing="2.2">
                                    <textPath href="#circlePath" startOffset="0%">
                                        ★ COFFEBAR ROASTERY ★ ESTD 2026 ★ ARTISAN COFFEE
                                    </textPath>
                                </text>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center text-retro-orange text-2xl font-display font-black">
                                ☕
                            </div>
                        </div>
                    </div>

                    <!-- Floating Badge 1 (Top Right) -->
                    <div class="animate-badge-1 bg-rustic-950/90 backdrop-blur-md border-2 border-retro-orange p-4 rounded-xl shadow-2xl max-w-xs pointer-events-auto transform hover:scale-105 transition duration-300 mb-6 lg:mb-10">
                        <div class="flex items-center gap-2 text-retro-mustard font-heading uppercase tracking-wider text-xs font-bold">
                            <span class="w-2.5 h-2.5 rounded-full bg-retro-orange animate-ping"></span>
                            <span>🔥 205°C First Crack Roasting</span>
                        </div>
                        <p class="font-body text-xs text-rustic-200 mt-1.5 leading-relaxed">
                            Biji kopi meletup melepaskan 800+ senyawa minyak aromatik alami dengan cita rasa maksimal.
                        </p>
                    </div>

                    <!-- Floating Badge 2 (Bottom Right) -->
                    <div class="animate-badge-2 bg-rustic-950/90 backdrop-blur-md border-2 border-retro-mustard/80 p-4 rounded-xl shadow-2xl max-w-xs pointer-events-auto transform hover:scale-105 transition duration-300">
                        <div class="flex items-center justify-between text-[11px] font-heading uppercase text-retro-mustard font-bold mb-1">
                            <span>Specialty Grade 1</span>
                            <span class="px-2 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-500/50 rounded font-mono text-[10px]">100% ARABICA</span>
                        </div>
                        <div class="text-xs text-rustic-200 font-body leading-snug">
                            🌋 Ditanam 1.450 mdpl di lereng vulkanik, dipetik merah manual dan disortir teliti.
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- SECTION: MENU POPULER WITH RETRO DINER CARD STYLING -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">

        <!-- Floating Side Coffee Beans (Isolated Transparent Assets) -->
        <div class="hidden lg:block absolute -left-10 xl:-left-16 top-16 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-hero-1.png') }}" alt="Floating Coffee Bean"
                 class="w-24 xl:w-28 h-auto opacity-85 animate-float-left filter drop-shadow-[0_15px_15px_rgba(42,18,8,0.35)]">
        </div>
        <div class="hidden lg:block absolute -right-10 xl:-right-16 top-32 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-hero-2.png') }}" alt="Floating Coffee Bean"
                 class="w-28 xl:w-32 h-auto opacity-85 animate-float-right filter drop-shadow-[0_15px_15px_rgba(42,18,8,0.35)]">
        </div>
        <div class="hidden xl:block absolute -left-8 bottom-24 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-cutout-2.png') }}" alt="Floating Coffee Bean"
                 class="w-16 h-auto opacity-75 animate-float-drift filter drop-shadow-lg">
        </div>
        <div class="hidden xl:block absolute -right-8 bottom-16 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-cutout-4.png') }}" alt="Floating Coffee Bean"
                 class="w-16 h-auto opacity-75 animate-float-left filter drop-shadow-lg">
        </div>

        <!-- Section Header with Retro Ribbons -->
        <div class="text-center mb-14">
            <span class="inline-block bg-retro-orange text-parchment font-heading text-[11px] font-black uppercase tracking-widest px-3 py-1 border-2 border-rustic-900 shadow-rustic-sm mb-3 -rotate-1">
                ★ SELEKSI PILIHAN HARI INI ★
            </span>
            <h2 class="font-display font-black text-3xl sm:text-5xl text-rustic-900 tracking-tight">
                Menu Yang Paling Dicintai
            </h2>
            <div class="text-retro-orange text-sm tracking-widest my-2 select-none flex items-center justify-center gap-3">
                <span class="h-0.5 w-12 bg-retro-orange/60 inline-block"></span>
                <span class="text-retro-mustard font-bold">❧ ✦ ❧</span>
                <span class="h-0.5 w-12 bg-retro-orange/60 inline-block"></span>
            </div>
            <p class="font-body italic text-rustic-700 text-sm sm:text-base max-w-xl mx-auto">
                Pilihan favorit para penikmat seduhan CoffeBar yang disajikan segar setiap harinya.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($popularProducts as $idx => $product)
                <div class="bg-parchment-dark border-2 border-rustic-900 rounded-sm p-4 shadow-rustic hover:-translate-y-1.5 hover:shadow-rustic-lg transition-all duration-300 flex flex-col justify-between relative group">
                    
                    <!-- Retro Sticker on Top Card -->
                    <div class="absolute -top-3 left-4 z-10 pointer-events-none select-none">
                        <span class="inline-block {{ $idx % 2 == 0 ? '-rotate-2 bg-retro-mustard' : 'rotate-2 bg-retro-orange text-parchment' }} text-rustic-900 font-heading font-black text-[10px] uppercase px-2 py-0.5 border-2 border-rustic-900 shadow-sm">
                            ★ {{ $idx === 0 ? 'BEST SELLER' : ($idx === 1 ? 'FAVORIT' : 'REKOMENDASI') }}
                        </span>
                    </div>

                    <div>
                        <!-- Product Visual Container -->
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
                            <span class="absolute top-2 right-2 bg-rustic-900/90 text-parchment text-[10px] font-heading uppercase px-2 py-0.5 border border-retro-orange font-bold">
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

                        <form method="POST" action="{{ route('cart.add') }}" class="inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                    class="bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-bold text-xs uppercase px-3.5 py-1.5 shadow-rustic-sm transition active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                                + Pesan
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('menu.index') }}"
               class="inline-block bg-parchment border-2 border-rustic-900 text-rustic-900 hover:bg-retro-mustard font-heading font-bold text-sm uppercase tracking-wider py-3 px-6 shadow-rustic transition active:translate-x-1 active:translate-y-1 active:shadow-none">
                Lihat Semua Menu Lengkap →
            </a>
        </div>
    </section>

    <!-- SECTION: TENTANG KAMI WITH VINTAGE EDITORIAL SPREAD -->
    <section class="relative bg-rustic-800 text-parchment py-16 sm:py-20 border-y-4 border-retro-orange overflow-hidden">
        
        <!-- Floating Side Coffee Beans Stream & Isolated Bean -->
        <div class="hidden xl:block absolute -left-8 top-1/2 -translate-y-1/2 pointer-events-none select-none opacity-45">
            <img src="{{ asset('images/beans-falling-stream.png') }}" alt="Falling Beans"
                 class="w-44 h-auto animate-float-left filter brightness-110">
        </div>
        <div class="hidden lg:block absolute -right-6 top-10 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-hero-3.png') }}" alt="Floating Coffee Bean"
                 class="w-24 h-auto opacity-70 animate-float-right filter drop-shadow-[0_12px_12px_rgba(0,0,0,0.6)]">
        </div>
        <div class="hidden xl:block absolute -right-4 bottom-10 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-cutout-5.png') }}" alt="Floating Coffee Bean"
                 class="w-14 h-auto opacity-65 animate-float-drift filter drop-shadow-lg">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-5">
                    <span class="inline-block bg-rustic-900 text-retro-mustard font-heading text-xs uppercase tracking-widest px-3 py-1 border border-retro-orange font-bold">
                        ★ TENTANG KEDAI KAMI ★
                    </span>
                    <h2 class="font-display font-black text-3xl sm:text-5xl text-parchment leading-tight">
                        {{ $settings['about_title'] ?? 'Bukan Sekadar Kedai Kopi, Tapi Rumah Cerita' }}
                    </h2>
                    <div class="text-retro-orange text-sm tracking-widest select-none">
                        ❧ ─────── ✦ ─────── ❧
                    </div>
                    <p class="font-body text-rustic-200 text-sm sm:text-base leading-relaxed">
                        {{ $settings['about_description_1'] ?? 'Lahir dari kecintaan pada aroma biji kopi yang baru digiling dan hangatnya interaksi manusia. Di CoffeBar, kami percaya bahwa secangkir kopi terbaik bukan hanya tentang teknik ekstraksi, melainkan tentang momen hening yang Anda nikmati atau obrolan mendalam bersama sahabat.' }}
                    </p>
                    <p class="font-body text-rustic-200 text-sm sm:text-base leading-relaxed">
                        {{ $settings['about_description_2'] ?? 'Kami bekerja sama langsung dengan para petani kopi lokal dari Gayo, Kintamani, Toraja, hingga Mandailing untuk menyajikan cita rasa asli yang terjaga kesegarannya.' }}
                    </p>
                </div>

                <div class="bg-parchment border-4 border-rustic-900 p-6 sm:p-8 text-rustic-900 shadow-rustic-lg relative">
                    <!-- Corner Vintage Stamp -->
                    <div class="absolute -top-4 -right-4 bg-retro-orange text-parchment font-heading text-[10px] font-black uppercase px-2.5 py-1 border-2 border-rustic-900 shadow-rustic-sm rotate-3">
                        GENUINE TASTE
                    </div>

                    <div class="border-2 border-dashed border-rustic-600 p-6 text-center space-y-4">
                        <span class="text-5xl block">🕰️</span>
                        <h3 class="font-display font-black text-2xl sm:text-3xl text-rustic-900">
                            Racikan Tradisi & Kehangatan
                        </h3>
                        <p class="font-body italic text-sm text-rustic-800 leading-relaxed">
                            "Menikmati waktu yang melambat di tengah hiruk-pikuk kota, ditemani secangkir kopi hangat dan sepotong pastry renyah."
                        </p>
                        <div class="pt-2 text-xs font-heading uppercase tracking-widest text-retro-orange font-black">
                            — CoffeBar Roastery 2026 —
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: CARA PESAN DENGAN PRANGKO VINTAGE -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        
        <!-- Floating Side Beans -->
        <div class="hidden lg:block absolute -left-10 xl:-left-16 top-1/3 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-hero-4.png') }}" alt="Floating Coffee Bean"
                 class="w-20 xl:w-24 h-auto opacity-80 animate-float-drift filter drop-shadow-[0_12px_12px_rgba(42,18,8,0.25)]">
        </div>
        <div class="hidden lg:block absolute -right-10 xl:-right-16 top-1/2 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-cutout-6.png') }}" alt="Floating Coffee Bean"
                 class="w-16 xl:w-20 h-auto opacity-80 animate-float-left filter drop-shadow-[0_12px_12px_rgba(42,18,8,0.25)]">
        </div>

        <div class="text-center mb-14">
            <span class="inline-block bg-retro-mustard text-rustic-900 font-heading text-[11px] font-black uppercase tracking-widest px-3 py-1 border-2 border-rustic-900 shadow-rustic-sm mb-3 rotate-1">
                ★ TIKET & ALUR PESANAN ★
            </span>
            <h2 class="font-display font-black text-3xl sm:text-5xl text-rustic-900">
                Cara Mudah Menikmati
            </h2>
            <div class="text-retro-orange text-sm tracking-widest my-2 select-none">
                ❧ ─────── ✦ ─────── ❧
            </div>
            <p class="font-body italic text-rustic-700 text-sm sm:text-base">
                Pesan langsung lewat web dan nikmati tanpa antre panjang
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="bg-parchment border-2 border-rustic-900 rounded-sm p-6 text-center shadow-rustic space-y-3 relative hover:-translate-y-1 transition">
                <div class="w-14 h-14 mx-auto bg-retro-orange text-parchment border-2 border-rustic-900 rounded-full flex items-center justify-center font-display font-black text-2xl shadow-rustic-sm">
                    ①
                </div>
                <h3 class="font-heading font-black text-base uppercase text-rustic-900">Pilih Menu / Berkreasi</h3>
                <p class="font-body text-xs text-rustic-800 leading-relaxed">
                    Jelajahi menu favorit kami atau racik sendiri minuman unik Anda dengan pilihan base, susu, gula, dan topping sesuka hati.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-parchment border-2 border-rustic-900 rounded-sm p-6 text-center shadow-rustic space-y-3 relative hover:-translate-y-1 transition">
                <div class="w-14 h-14 mx-auto bg-retro-mustard text-rustic-900 border-2 border-rustic-900 rounded-full flex items-center justify-center font-display font-black text-2xl shadow-rustic-sm">
                    ②
                </div>
                <h3 class="font-heading font-black text-base uppercase text-rustic-900">Checkout & Dapatkan QR</h3>
                <p class="font-body text-xs text-rustic-800 leading-relaxed">
                    Pilih bayar online melalui QRIS atau bayar langsung di kasir. Anda akan mendapatkan kode tiket dan QR Code instan.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-parchment border-2 border-rustic-900 rounded-sm p-6 text-center shadow-rustic space-y-3 relative hover:-translate-y-1 transition">
                <div class="w-14 h-14 mx-auto bg-retro-orange text-parchment border-2 border-rustic-900 rounded-full flex items-center justify-center font-display font-black text-2xl shadow-rustic-sm">
                    ③
                </div>
                <h3 class="font-heading font-black text-base uppercase text-rustic-900">Tunjukkan & Nikmati</h3>
                <p class="font-body text-xs text-rustic-800 leading-relaxed">
                    Tunjukkan tiket pesanan Anda ke barista di kasir. Minuman segar siap diracik untuk menemani harimu.
                </p>
            </div>
        </div>
    </section>

    <!-- SECTION: LOKASI & GALERI (KARTU POS SENOPATI) -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        
        <!-- Floating Side Bean -->
        <div class="hidden lg:block absolute -top-8 -right-4 xl:-right-8 pointer-events-none select-none z-20">
            <img src="{{ asset('images/bean-cutout-1.png') }}" alt="Floating Coffee Bean"
                 class="w-16 h-auto opacity-85 animate-float-right filter drop-shadow-xl">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-[#EDD9A3] border-4 border-rustic-900 p-6 sm:p-10 shadow-rustic-lg relative">
            
            <div class="space-y-4">
                <span class="inline-block bg-retro-orange text-parchment font-heading text-xs uppercase tracking-widest font-black px-3 py-1 border border-rustic-900">
                    ★ KARTU POS DARI KEDAI ★
                </span>
                <h2 class="font-display font-black text-3xl sm:text-4xl text-rustic-900">
                    Kedai Suasana Hangat Menunggumu
                </h2>
                <p class="font-body text-sm text-rustic-800 leading-relaxed">
                    Tersedia area indoor ber-AC yang nyaman untuk bekerja (WFC) dengan Wi-Fi kencang dan colokan di setiap meja, serta area outdoor rimbun untuk menikmati senja.
                </p>
                <div class="pt-3 border-t-2 border-dashed border-rustic-500 space-y-2 text-sm font-body text-rustic-900">
                    <div>📍 <strong>Alamat:</strong> {{ $settings['address'] ?? 'Jl. Kopi No. 12, Senopati, Kebayoran Baru, Jakarta Selatan' }}</div>
                    <div>🕒 <strong>Jam Buka:</strong> {{ $settings['opening_hours'] ?? 'Setiap hari 08.00 – 22.00 WIB' }}</div>
                    <div>📞 <strong>Kontak Kasir:</strong> {{ $settings['contact_phone'] ?? '0812-3456-7890' }}</div>
                </div>
            </div>

            <!-- Visual Ambience / Map Card Styled as Vintage Airmail -->
            <div class="border-2 border-rustic-900 bg-rustic-900 text-parchment p-6 sm:p-8 text-center space-y-4 shadow-rustic">
                <span class="text-5xl block">🗺️</span>
                <h3 class="font-display font-black text-2xl text-retro-mustard">Peta Lokasi & Denah</h3>
                <p class="font-body italic text-xs text-rustic-300 leading-relaxed">
                    Hanya 5 menit berjalan kaki dari Halte Senopati. Parkir mobil dan motor luas tersedia di area depan.
                </p>
                <a href="{{ $settings['maps_url'] ?? 'https://maps.google.com' }}" target="_blank" rel="noopener"
                   class="inline-block bg-retro-orange text-parchment font-heading text-xs uppercase tracking-wider py-2.5 px-6 font-bold border-2 border-rustic-900 shadow-rustic-sm hover:bg-copper-light hover:text-rustic-900 transition">
                    Buka Google Maps ↗
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    function heroInteractive() {
        return {
            activeOrigin: 0,
            mouseX: 0,
            mouseY: 0,
            isHovering: false,
            origins: [
                {
                    name: 'Aceh Gayo',
                    sub: 'Arabica Gayo Highland Wine',
                    score: '89.2 SCA',
                    roast: 'Medium Dark',
                    altitude: '1.500 mdpl',
                    process: 'Anaerobic Natural',
                    notes: ['Dark Chocolate', 'Ripe Jackfruit', 'Brown Sugar'],
                    body: 85,
                    acidity: 45,
                    sweetness: 90
                },
                {
                    name: 'Bali Kintamani',
                    sub: 'Arabica Kintamani Volcano Honey',
                    score: '88.5 SCA',
                    roast: 'Medium Light',
                    altitude: '1.400 mdpl',
                    process: 'Yellow Honey',
                    notes: ['Citrus Tangerine', 'Wild Honey', 'Silky Caramel'],
                    body: 60,
                    acidity: 85,
                    sweetness: 80
                },
                {
                    name: 'Toraja Kalosi',
                    sub: 'Arabica Toraja Dark Reserve',
                    score: '88.8 SCA',
                    roast: 'Dark Roast',
                    altitude: '1.600 mdpl',
                    process: 'Giling Basah',
                    notes: ['Warm Cinnamon', 'Roasted Almond', 'Bold Cocoa'],
                    body: 95,
                    acidity: 35,
                    sweetness: 70
                }
            ],
            onMouseMove(e) {
                const rect = e.currentTarget.getBoundingClientRect();
                this.mouseX = e.clientX - rect.left;
                this.mouseY = e.clientY - rect.top;
                this.isHovering = true;
            },
            onMouseLeave() {
                this.isHovering = false;
            },
            get parallaxStyle() {
                if (!this.isHovering) return 'transform: scale(1.02);';
                const tiltX = ((this.mouseY / 600) - 0.5) * -7;
                const tiltY = ((this.mouseX / 1200) - 0.5) * 10;
                return `transform: perspective(1000px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) scale(1.04);`;
            },
            get spotlightStyle() {
                if (!this.isHovering) return 'background: radial-gradient(700px circle at 70% 50%, rgba(207, 83, 40, 0.22), transparent 65%);';
                return `background: radial-gradient(600px circle at ${this.mouseX}px ${this.mouseY}px, rgba(207, 83, 40, 0.32), transparent 60%);`;
            }
        };
    }
    window.heroInteractive = heroInteractive;
    if (window.Alpine) {
        Alpine.data('heroInteractive', heroInteractive);
    } else {
        document.addEventListener('alpine:init', () => {
            Alpine.data('heroInteractive', heroInteractive);
        });
    }
</script>
@endpush
