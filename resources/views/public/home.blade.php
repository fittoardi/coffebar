@extends('layouts.public')

@section('title', 'Kedai Kopi & Cerita Autentik')

@section('content')

    <!-- 1. FULLSCREEN HERO VIDEO SECTION (03-DESIGN.md Section 7 & Section 6.1) -->
    <section class="relative min-h-[90vh] lg:min-h-screen flex items-center overflow-hidden border-b border-dashed border-[#CDB48F]">

        <!-- Fullscreen Video Background (Autoplay & Continuous Loop) -->
        <video autoplay
               loop
               muted
               playsinline
               poster="{{ asset('images/coffee-story-poster.jpg') }}"
               class="absolute inset-0 w-full h-full object-cover select-none pointer-events-none z-0">
            <source src="{{ asset('images/coffee-story.mp4') }}" type="video/mp4">
        </video>

        <!-- Cinematic Gradient Overlays (Rustic Dark Roast & Warmth) -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#1E1A17]/95 via-[#2B1D14]/85 to-[#2B1D14]/65 z-0 pointer-events-none"></div>
        <div class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-[#1E1A17] via-[#1E1A17]/60 to-transparent z-0 pointer-events-none"></div>
        <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#1E1A17]/70 to-transparent z-0 pointer-events-none"></div>

        <!-- Pinggiran Luar Hero: Taburan Biji Kopi Organik Melayang di Tepi Kiri & Kanan Layar -->
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute -left-3 sm:left-2 lg:left-6 xl:left-10 top-6 sm:top-10 w-20 sm:w-28 lg:w-32 xl:w-36 h-auto object-contain pointer-events-none opacity-45 lg:opacity-70 -rotate-12 drop-shadow-xl select-none hidden sm:block animate-float-slow z-10" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute left-4 sm:left-8 lg:left-16 bottom-8 sm:bottom-12 w-16 sm:w-22 lg:w-26 h-auto object-contain pointer-events-none opacity-40 lg:opacity-60 rotate-12 drop-shadow-lg select-none hidden sm:block animate-float-reverse z-10" alt="" />
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute -right-3 sm:right-2 lg:right-6 xl:right-10 top-10 sm:top-14 w-20 sm:w-28 lg:w-32 xl:w-36 h-auto object-contain pointer-events-none opacity-45 lg:opacity-70 rotate-6 drop-shadow-xl select-none hidden sm:block animate-float z-10" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute right-4 sm:right-10 lg:right-20 bottom-16 sm:bottom-20 w-16 sm:w-22 lg:w-26 h-auto object-contain pointer-events-none opacity-40 lg:opacity-60 -rotate-12 drop-shadow-lg select-none hidden md:block animate-float-drift z-10" alt="" />

        <!-- Main Hero Content Container -->
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 py-16 sm:py-24 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 xl:gap-16 items-center">

                <!-- Left Column: Editorial Headline & Actions -->
                <div class="lg:col-span-8 xl:col-span-7 space-y-6 sm:space-y-8 text-left reveal-on-scroll">
                    <div class="inline-flex items-center space-x-2 bg-[#E8DCC4]/95 backdrop-blur-md border border-[#CDB48F] px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider text-[#2B1D14] shadow-md">
                        <img src="{{ asset('images/bean-single.png') }}" class="w-4 h-4 object-contain inline-block -mt-0.5" alt="Kopi" />
                        <span>{{ $settings['shop_tagline'] ?? 'Kedai Kopi Autentik Nusantara' }}</span>
                    </div>

                    <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl xl:text-8xl font-bold text-[#FFFDF8] leading-[1.08] tracking-tight drop-shadow-md">
                        Disangrai pelan,<br>
                        <span class="italic font-normal text-[#CDB48F]">diseduh dengan hati.</span>
                    </h1>

                    <div class="space-y-3">
                        <p class="font-display text-xl sm:text-2xl xl:text-3xl font-medium text-[#F4ECDD] drop-shadow-sm">
                            COFFEBAR — {{ $settings['hero_title'] ?? 'Kopi Terbaik, Diracik Untukmu' }}
                        </p>
                        <p class="text-[#E8DCC4]/90 text-base sm:text-lg xl:text-xl max-w-2xl leading-relaxed">
                            {{ $settings['hero_subtitle'] ?? 'Cita Rasa Juara Dari Bumi Nusantara' }}
                        </p>
                    </div>

                    <!-- 2 Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-3 sm:pt-5">
                        <a href="{{ route('menu.index') }}"
                           class="inline-flex justify-center items-center bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-sm sm:text-base px-8 py-4 rounded-xl shadow-warm hover:-translate-y-0.5 transition duration-200 text-center">
                            Lihat Daftar Menu
                        </a>
                        <a href="{{ route('custom.index') }}"
                           class="inline-flex justify-center items-center bg-[#FFFDF8]/10 hover:bg-[#FFFDF8] text-[#FFFDF8] hover:text-[#2B1D14] border-1.5 border-[#CDB48F]/70 backdrop-blur-sm font-semibold text-sm sm:text-base px-7 py-4 rounded-xl transition duration-200 text-center shadow-sm">
                            Racik Minuman Sendiri ✦
                        </a>
                    </div>
                </div>

                <!-- Right Column: Rotating Artisan Stamp & Atmospheric Coffee Badge -->
                <div class="lg:col-span-4 xl:col-span-5 flex flex-col items-center lg:items-end justify-center space-y-6 reveal-on-scroll">
                    <!-- Glass Badge: Seduhan Murni -->
                    <div class="bg-[#1E1A17]/80 backdrop-blur-md border border-[#CDB48F]/40 rounded-2xl p-6 sm:p-7 text-center lg:text-right max-w-sm shadow-2xl space-y-2 motion-frame">
                        <span class="font-hand text-3xl text-[#B5753A]">Seduhan Murni</span>
                        <p class="font-display text-lg text-[#F4ECDD] font-semibold">"Aroma pagi yang tak pernah bohong"</p>
                        <p class="text-xs text-[#CDB48F] uppercase tracking-wider pt-1 border-t border-dashed border-[#CDB48F]/30">
                            Single Origin & House Blend · Sejak 2024
                        </p>
                    </div>

                    <!-- Rotating Stamp Badge -->
                    <div class="relative w-32 h-32 sm:w-36 sm:h-36 bg-[#FFFDF8]/95 backdrop-blur-md border-2 border-dashed border-[#B5753A] rounded-full flex items-center justify-center shadow-2xl animate-spin-slow select-none pointer-events-none">
                        <svg class="w-28 h-28 sm:w-32 sm:h-32 text-[#4A3224]" viewBox="0 0 100 100">
                            <path id="stamp-circle" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="none" />
                            <text class="text-[9px] font-bold uppercase tracking-widest fill-current">
                                <textPath href="#stamp-circle" startOffset="0%">
                                    ✦ ARTISAN ROASTERY ✦ COFFEBAR ✦
                                </textPath>
                            </text>
                        </svg>
                        <img src="{{ asset('images/bean-single.png') }}" class="absolute w-8 h-8 sm:w-9 sm:h-9 object-contain drop-shadow-md" alt="Kopi" />
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. STRIP INFO (03-DESIGN.md Section 7: Bar Oat Full Width) -->
    <div class="bg-[#E8DCC4] border-b border-dashed border-[#CDB48F] py-4">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-xs sm:text-sm text-[#4A3224]">
                <div class="flex items-center space-x-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#7A8467] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-[#7A8467]"></span>
                    </span>
                    <span class="font-bold text-[#7A8467] uppercase tracking-wider text-xs">Buka Sekarang</span>
                    <span class="text-[#CDB48F]">·</span>
                    <span class="font-medium">{{ $settings['shop_hours'] ?? 'Setiap Hari: 08:00 - 22:00 WIB' }}</span>
                </div>

                <div class="flex items-center space-x-2 text-center">
                    <span>📍</span>
                    <span>{{ $settings['shop_address'] ?? 'Jl. Senopati No. 88, Jakarta Selatan' }}</span>
                </div>

                <div class="hidden lg:flex items-center space-x-2 font-hand text-xl xl:text-2xl text-[#2B1D14]">
                    <span>"Hangatnya seduhan Nusantara"</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. HIGHLIGHT MENU & POPULAR PRODUCTS (03-DESIGN.md Section 6.3 & 7: Full Width) -->
    <section class="py-16 sm:py-24 w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 relative overflow-hidden">
        <!-- Pinggiran Biji Kopi Melayang: Pojok Kiri & Kanan Section -->
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute left-2 lg:left-6 top-1/4 w-24 sm:w-32 h-auto object-contain pointer-events-none opacity-45 -rotate-12 drop-shadow-md select-none hidden sm:block animate-float-slow" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute left-4 lg:left-12 -bottom-6 w-20 h-20 object-contain pointer-events-none opacity-40 rotate-45 select-none hidden lg:block animate-float-reverse" alt="" />
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute right-2 lg:right-6 top-1/2 w-24 sm:w-32 h-auto object-contain pointer-events-none opacity-45 rotate-12 drop-shadow-md select-none hidden sm:block animate-float" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute right-4 lg:right-12 top-4 w-20 h-20 object-contain pointer-events-none opacity-50 -rotate-12 select-none hidden md:block animate-float-drift" alt="" />

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 border-b border-dashed border-[#CDB48F] pb-6 relative z-10 reveal-on-scroll">
            <div>
                <span class="font-hand text-2xl xl:text-3xl text-[#B5753A]">Pilihan Barista</span>
                <h2 class="font-display text-3xl sm:text-4xl xl:text-5xl font-semibold text-[#2B1D14] mt-1">Pilihan Menu Favorit</h2>
                <p class="text-[#6B5646] text-sm sm:text-base mt-2">Jelajahi berbagai pilihan kopi dan sajian pelengkap kami.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('menu.index') }}" class="font-semibold text-sm sm:text-base text-[#4A3224] hover:text-[#B5753A] underline underline-offset-4 decoration-[#B5753A] transition">
                    Lihat Semua Menu ({{ count($popularProducts ?? []) }}+) →
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-6 xl:gap-8 relative z-10">
            @forelse ($popularProducts ?? [] as $product)
                <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-5 sm:p-6 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-warm-lg transition-all duration-300 group reveal-on-scroll">
                    <div>
                        <!-- Product Visual (Cups for beverages, realistic pastries/fries for food) -->
                        <div class="w-full h-40 bg-[#F4ECDD]/70 rounded-xl mb-4 flex items-center justify-center p-3 overflow-hidden border border-[#CDB48F]/40 group-hover:bg-[#E8DCC4]/50 transition">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     class="h-32 w-32 object-contain drop-shadow-md group-hover:scale-105 transition transform duration-400">
                            @else
                                <div class="h-28 w-28 rounded-full bg-[#E8DCC4] border border-[#CDB48F] flex items-center justify-center text-3xl shadow-inner group-hover:scale-105 transition duration-300 select-none">
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

                        <!-- Category Eyebrow & Status Badge -->
                        <div class="flex items-center justify-between text-xs text-[#6B5646] mb-2.5">
                            <span class="uppercase tracking-wider font-semibold text-[11px] bg-[#E8DCC4] text-[#4A3224] px-2.5 py-0.5 rounded">
                                {{ $product->category->name ?? 'Minuman' }}
                            </span>
                            @if (!$product->is_available)
                                <span class="text-[#B4552F] font-bold text-xs uppercase tracking-wider">Habis</span>
                            @else
                                <span class="text-[#7A8467] font-bold text-xs uppercase tracking-wider">Tersedia</span>
                            @endif
                        </div>

                        <!-- Product Title (Fraunces) & Description -->
                        <h3 class="font-display font-semibold text-[#2B1D14] text-xl mb-1.5 leading-snug group-hover:text-[#B5753A] transition">
                            {{ $product->name }}
                        </h3>
                        <p class="text-[#6B5646] text-xs sm:text-sm line-clamp-2 leading-relaxed mb-4">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Price with Dotted Leader & Order Link -->
                    <div class="flex items-baseline justify-between pt-3 border-t border-dashed border-[#CDB48F]">
                        <span class="font-display font-bold text-lg text-[#2B1D14]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('menu.index') }}"
                           class="text-xs uppercase tracking-wider font-bold bg-[#B5753A] text-white hover:bg-[#B4552F] px-3.5 py-1.5 rounded-lg transition shadow-sm hover:scale-105 active:scale-95 duration-200">
                            Pesan →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-[#6B5646] text-sm bg-[#FFFDF8] border border-dashed border-[#CDB48F] rounded-xl">
                    <p class="font-display text-lg text-[#4A3224]">Menu belum tersedia saat ini.</p>
                    <p class="text-xs text-[#6B5646] mt-1">Nantikan racikan istimewa dari barista kami segera.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 4. CERITA KAMI (ABOUT SECTION - Full Width) -->
    <section id="tentang" class="bg-[#E8DCC4] border-y border-dashed border-[#CDB48F] py-16 sm:py-24 relative overflow-hidden">
        <!-- Pinggiran Biji Kopi Melayang: Kiri & Kanan -->
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute left-2 lg:left-8 top-8 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-50 rotate-12 drop-shadow-md select-none hidden sm:block animate-float-slow" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute left-1/4 -bottom-6 w-20 h-20 object-contain pointer-events-none opacity-40 -rotate-12 select-none hidden lg:block animate-float-reverse" alt="" />
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute right-2 lg:right-8 bottom-6 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-50 -rotate-12 drop-shadow-md select-none hidden sm:block animate-float" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute right-12 top-4 w-20 h-20 object-contain pointer-events-none opacity-45 rotate-45 select-none hidden lg:block animate-float-drift" alt="" />

        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-16 items-center">
                <div class="lg:col-span-7 xl:col-span-8 space-y-6 text-left reveal-on-scroll">
                    <span class="font-hand text-3xl xl:text-4xl text-[#B5753A]">Dedikasi & Rasa</span>
                    <h2 class="font-display text-3xl sm:text-5xl xl:text-6xl font-semibold text-[#2B1D14]">Tentang CoffeBar</h2>
                    <div class="w-20 h-0.5 bg-[#B5753A]"></div>
                    <p class="text-[#4A3224] text-base sm:text-lg xl:text-xl leading-relaxed max-w-4xl">
                        {{ $settings['about_text'] ?? 'CoffeBar didirikan dengan semangat menyajikan kopi berkualitas dari berbagai pelosok Nusantara. Kami percaya setiap cangkir memiliki cerita dan kehangatan yang layak dinikmati bersama.' }}
                    </p>
                    <p class="font-hand text-2xl sm:text-3xl text-[#2B1D14] pt-2">
                        "Secangkir kopi, sejuta obrolan bermakna."
                    </p>
                </div>

                <div class="lg:col-span-5 xl:col-span-4 relative flex justify-center lg:justify-end reveal-on-scroll">
                    <div class="relative w-64 h-64 sm:w-80 sm:h-80 xl:w-96 xl:h-96 bg-[#FFFDF8] border-2 border-[#CDB48F] rounded-full p-6 shadow-warm flex items-center justify-center motion-frame">
                        <img src="{{ asset('images/cup-latte.png') }}" class="w-52 h-52 sm:w-64 sm:h-64 xl:w-80 xl:h-80 object-contain drop-shadow-xl hover:scale-105 transition transform duration-500" alt="Latte Art CoffeBar">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. PROSES KAMI: 3 LANGKAH IKONIK (Full Width) -->
    <section class="bg-[#2B1D14] text-[#F4ECDD] py-20 border-b border-[#4A3224] relative overflow-hidden">
        <!-- Pinggiran Biji Kopi Gelap Melayang: Kiri & Kanan -->
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute left-2 lg:left-8 top-10 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-30 rotate-12 select-none hidden sm:block animate-float-slow" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute left-8 lg:left-14 -bottom-6 w-20 h-20 object-contain pointer-events-none opacity-30 -rotate-12 select-none hidden md:block animate-float-reverse" alt="" />
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute right-2 lg:right-8 bottom-10 w-24 sm:w-28 h-auto object-contain pointer-events-none opacity-30 -rotate-6 select-none hidden sm:block animate-float" alt="" />
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute right-10 lg:right-16 top-4 w-20 h-20 object-contain pointer-events-none opacity-30 rotate-45 select-none hidden md:block animate-float-drift" alt="" />

        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal-on-scroll">
                <span class="font-hand text-2xl xl:text-3xl text-[#CDB48F]">Dari Kebun ke Meja</span>
                <h2 class="font-display text-3xl sm:text-4xl xl:text-5xl font-semibold text-[#F4ECDD] mt-1">Proses Seduhan Kami</h2>
                <p class="text-[#CDB48F] text-sm sm:text-base mt-2">Tiga prinsip tak terpisahkan dalam setiap tetes kopi CoffeBar.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 xl:gap-10">
                <!-- Step 1: Petik -->
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] hover:-translate-y-1 transition duration-300 group reveal-on-scroll">
                    <div class="w-20 h-20 mx-auto flex items-center justify-center">
                        <img src="{{ asset('images/beans-cluster.png') }}" class="w-16 h-16 object-contain group-hover:scale-110 transition duration-300 drop-shadow-md" alt="Petik Pilihan">
                    </div>
                    <div class="inline-block bg-[#4A3224] px-3 py-1 rounded-full font-display text-xs font-bold text-[#B5753A]">
                        01 · SELEKSI
                    </div>
                    <h3 class="font-display text-xl font-semibold text-[#F4ECDD]">Petik Pilihan</h3>
                    <p class="text-[#CDB48F] text-sm leading-relaxed">
                        Hanya buah kopi matang optimal dari petani binaan di dataran tinggi Gayo, Toraja, dan Ijen.
                    </p>
                </div>

                <!-- Step 2: Sangrai -->
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] hover:-translate-y-1 transition duration-300 group reveal-on-scroll">
                    <div class="w-20 h-20 mx-auto flex items-center justify-center">
                        <img src="{{ asset('images/bean-single.png') }}" class="w-16 h-16 object-contain group-hover:scale-110 transition duration-300 drop-shadow-md" alt="Sangrai Presisi">
                    </div>
                    <div class="inline-block bg-[#4A3224] px-3 py-1 rounded-full font-display text-xs font-bold text-[#B5753A]">
                        02 · ROASTING
                    </div>
                    <h3 class="font-display text-xl font-semibold text-[#F4ECDD]">Sangrai Presisi</h3>
                    <p class="text-[#CDB48F] text-sm leading-relaxed">
                        Artisan slow roast dalam batch kecil untuk mengunci kompleksitas rasa manis alami dan aroma rempah.
                    </p>
                </div>

                <!-- Step 3: Seduh -->
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] hover:-translate-y-1 transition duration-300 group reveal-on-scroll">
                    <div class="w-20 h-20 mx-auto flex items-center justify-center">
                        <img src="{{ asset('images/cup-black.png') }}" class="w-16 h-16 object-contain group-hover:scale-110 transition duration-300 drop-shadow-md" alt="Seduh Berjiwa">
                    </div>
                    <div class="inline-block bg-[#4A3224] px-3 py-1 rounded-full font-display text-xs font-bold text-[#B5753A]">
                        03 · BREWING
                    </div>
                    <h3 class="font-display text-xl font-semibold text-[#F4ECDD]">Seduh Berjiwa</h3>
                    <p class="text-[#CDB48F] text-sm leading-relaxed">
                        Kalibrasi rasio air, suhu, dan kelembutan gilingan secara disiplin di tangan barista berpengalaman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. LOKASI & KONTAK SECTION (Full Width) -->
    <section id="kontak" class="py-16 sm:py-24 w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 relative overflow-hidden">
        <!-- Pinggiran Biji Kopi: Kiri & Kanan -->
        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute left-2 lg:left-8 bottom-12 w-20 sm:w-24 h-24 object-contain pointer-events-none opacity-40 rotate-12 select-none hidden sm:block animate-float-slow" alt="" />
        <img src="{{ asset('images/beans-falling.png') }}" class="absolute right-2 lg:right-8 top-10 w-20 sm:w-24 h-auto object-contain pointer-events-none opacity-40 -rotate-12 select-none hidden sm:block animate-float-reverse" alt="" />

        <div class="text-center max-w-2xl mx-auto mb-14 relative z-10 reveal-on-scroll">
            <span class="font-hand text-2xl xl:text-3xl text-[#B5753A]">Mampir & Bertegur Sapa</span>
            <h2 class="font-display text-3xl sm:text-4xl xl:text-5xl font-semibold text-[#2B1D14] mt-1">Lokasi & Kontak</h2>
            <p class="text-[#6B5646] text-sm sm:text-base mt-2">Kunjungi kedai kami atau hubungi kami untuk informasi lebih lanjut.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 xl:gap-8 relative z-10">
            <!-- Card 1: Alamat -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-7 flex flex-col justify-between shadow-sm hover:-translate-y-1 hover:shadow-warm transition duration-300 reveal-on-scroll">
                <div>
                    <div class="w-10 h-10 bg-[#E8DCC4] rounded-xl flex items-center justify-center text-[#B5753A] text-lg mb-4">
                        📍
                    </div>
                    <h3 class="font-display font-semibold text-[#2B1D14] text-xl mb-2">Alamat Kedai</h3>
                    <p class="text-[#6B5646] text-sm leading-relaxed">
                        {{ $settings['shop_address'] ?? 'Jl. Senopati No. 88, Jakarta Selatan' }}
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-dashed border-[#CDB48F]/60 text-xs text-[#7A8467] font-semibold">
                    ✓ Parkir luas & area outdoor asri
                </div>
            </div>

            <!-- Card 2: Jam Buka -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-7 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="w-10 h-10 bg-[#E8DCC4] rounded-xl flex items-center justify-center text-[#B5753A] text-lg mb-4">
                        🕒
                    </div>
                    <h3 class="font-display font-semibold text-[#2B1D14] text-xl mb-2">Jam Buka</h3>
                    <p class="text-[#6B5646] text-sm leading-relaxed">
                        {{ $settings['shop_hours'] ?? 'Setiap Hari: 08:00 - 22:00 WIB' }}
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-dashed border-[#CDB48F]/60 text-xs text-[#4A3224] font-medium">
                    Pelayanan dine-in & take-away
                </div>
            </div>

            <!-- Card 3: Kontak & Maps -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-7 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="w-10 h-10 bg-[#E8DCC4] rounded-xl flex items-center justify-center text-[#B5753A] text-lg mb-4">
                        💬
                    </div>
                    <h3 class="font-display font-semibold text-[#2B1D14] text-xl mb-2">Kontak Kami</h3>
                    <p class="text-[#6B5646] text-sm mb-4">
                        WhatsApp: <strong class="text-[#2B1D14]">{{ $settings['contact_phone'] ?? '0812-3456-7890' }}</strong>
                    </p>
                </div>
                <div>
                    <a href="{{ $settings['maps_url'] ?? 'https://maps.google.com' }}"
                       target="_blank" rel="noopener"
                       class="inline-flex w-full justify-center items-center bg-[#4A3224] text-[#F4ECDD] hover:bg-[#2B1D14] text-xs font-bold uppercase tracking-wider py-3 px-4 rounded-xl transition shadow-sm">
                        Buka Google Maps ↗
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
