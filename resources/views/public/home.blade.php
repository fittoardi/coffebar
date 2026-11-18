@extends('layouts.public')

@section('title', 'Kedai Kopi & Cerita Autentik')

@section('content')

    <!-- 1. HERO SECTION (03-DESIGN.md Section 7 & Section 6.1) -->
    <section class="relative overflow-hidden py-16 lg:py-24 border-b border-dashed border-[#CDB48F]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Left Column (5-6 cols editorial text) -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center space-x-2 bg-[#E8DCC4] border border-[#CDB48F] px-3.5 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider text-[#4A3224]">
                        <img src="{{ asset('images/bean-single.png') }}" class="w-4 h-4 object-contain inline-block -mt-0.5" alt="Kopi" />
                        <span>{{ $settings['shop_tagline'] ?? 'Kedai Kopi Autentik Nusantara' }}</span>
                    </div>

                    <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-semibold text-[#2B1D14] leading-[1.08] tracking-tight">
                        Disangrai pelan,<br>
                        <span class="italic font-normal text-[#B5753A]">diseduh dengan hati.</span>
                    </h1>

                    <div class="space-y-2">
                        <p class="font-display text-xl sm:text-2xl font-medium text-[#4A3224]">
                            COFFEBAR — {{ $settings['hero_title'] ?? 'Kopi Terbaik, Diracik Untukmu' }}
                        </p>
                        <p class="text-[#6B5646] text-base sm:text-lg max-w-xl leading-relaxed">
                            {{ $settings['hero_subtitle'] ?? 'Cita Rasa Juara Dari Bumi Nusantara' }}
                        </p>
                    </div>

                    <!-- 2 Action Buttons (Section 6.2) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-4">
                        <a href="{{ route('menu.index') }}"
                           class="inline-flex justify-center items-center bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-sm px-8 py-3.5 rounded-xl shadow-warm hover:-translate-y-0.5 transition duration-200">
                            Lihat Daftar Menu
                        </a>
                        <a href="{{ route('custom.index') }}"
                           class="inline-flex justify-center items-center border-1.5 border-[#4A3224] text-[#4A3224] hover:bg-[#4A3224] hover:text-[#F4ECDD] font-semibold text-sm px-7 py-3.5 rounded-xl transition duration-200">
                            Racik Minuman Sendiri ✦
                        </a>
                    </div>
                </div>

                <!-- Right Column: Arch Visual with Rotating Stamp Badge (Section 4 & 5) -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md">
                        <!-- Arch Video Wrapper (03-DESIGN.md: Elemen Arch Lengkung) -->
                        <div class="w-full h-[470px] bg-[#2B1D14] border-2 border-[#CDB48F] rounded-arch overflow-hidden shadow-warm relative group">
                            <video autoplay loop muted playsinline poster="{{ asset('images/coffee-story-poster.jpg') }}" class="w-full h-full object-cover">
                                <source src="{{ asset('images/coffee-story.mp4') }}" type="video/mp4">
                            </video>

                            <!-- Gradient shade for rustic warmth & text readability -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#2B1D14]/90 via-[#2B1D14]/25 to-transparent pointer-events-none"></div>

                            <!-- Bottom Arch Content -->
                            <div class="absolute bottom-6 inset-x-6 text-center text-[#FFFDF8] pointer-events-none space-y-1 z-10">
                                <p class="font-display text-2xl font-semibold text-[#F4ECDD]">Seduhan Murni</p>
                                <p class="font-hand text-2xl text-[#B5753A]">"Aroma pagi yang tak pernah bohong"</p>
                                <p class="text-[11px] text-[#E8DCC4]/80 uppercase tracking-widest pt-1">Single Origin & House Blend · Sejak 2024</p>
                            </div>
                        </div>

                        <!-- Floating decorative falling beans -->
                        <img src="{{ asset('images/beans-falling.png') }}"
                             class="absolute -top-7 -right-6 w-20 sm:w-24 h-auto object-contain pointer-events-none drop-shadow-lg z-20 opacity-90 hidden sm:block"
                             alt="Biji Kopi Sangrai" />

                        <!-- Rotating Stamp Badge (Section 5: stempel bulat rotasi pelan) -->
                        <div class="absolute -bottom-6 -left-6 w-28 h-28 bg-[#FFFDF8] border-2 border-dashed border-[#B5753A] rounded-full flex items-center justify-center shadow-md animate-spin-slow select-none pointer-events-none z-20">
                            <svg class="w-24 h-24 text-[#4A3224]" viewBox="0 0 100 100">
                                <path id="stamp-circle" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="none" />
                                <text class="text-[9px] font-bold uppercase tracking-widest fill-current">
                                    <textPath href="#stamp-circle" startOffset="0%">
                                        ✦ ARTISAN ROASTERY ✦ COFFEBAR ✦
                                    </textPath>
                                </text>
                            </svg>
                            <img src="{{ asset('images/bean-single.png') }}" class="absolute w-7 h-7 object-contain drop-shadow-sm" alt="Kopi" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. STRIP INFO (03-DESIGN.md Section 7: Bar Oat) -->
    <div class="bg-[#E8DCC4] border-b border-dashed border-[#CDB48F] py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

                <div class="hidden lg:flex items-center space-x-2 font-hand text-xl text-[#2B1D14]">
                    <span>"Hangatnya seduhan Nusantara"</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. HIGHLIGHT MENU & POPULAR PRODUCTS (03-DESIGN.md Section 6.3 & 7) -->
    <section class="py-16 sm:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 border-b border-dashed border-[#CDB48F] pb-6">
            <div>
                <span class="font-hand text-2xl text-[#B5753A]">Pilihan Barista</span>
                <h2 class="font-display text-3xl sm:text-4xl font-semibold text-[#2B1D14] mt-1">Pilihan Menu Favorit</h2>
                <p class="text-[#6B5646] text-sm sm:text-base mt-2">Jelajahi berbagai pilihan kopi dan sajian pelengkap kami.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('menu.index') }}" class="font-semibold text-sm text-[#4A3224] hover:text-[#B5753A] underline underline-offset-4 decoration-[#B5753A] transition">
                    Lihat Semua Menu ({{ count($popularProducts ?? []) }}+) →
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($popularProducts ?? [] as $product)
                <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-5 flex flex-col justify-between hover:-translate-y-1 hover:shadow-warm transition duration-200 group">
                    <div>
                        <!-- Product Thumbnail with Saucer & Shadow -->
                        <div class="w-full h-36 bg-[#F4ECDD]/70 rounded-xl mb-4 flex items-center justify-center p-3 overflow-hidden border border-[#CDB48F]/40 group-hover:bg-[#E8DCC4]/50 transition">
                            <img src="{{ $product->image_url }}"
                                 alt="{{ $product->name }}"
                                 class="h-28 w-28 object-contain drop-shadow-md group-hover:scale-105 transition transform duration-300">
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
                           class="text-xs uppercase tracking-wider font-bold bg-[#B5753A] text-white hover:bg-[#B4552F] px-3.5 py-1.5 rounded-lg transition shadow-sm">
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

    <!-- 4. CERITA KAMI (ABOUT SECTION - 03-DESIGN.md Section 7) -->
    <section id="tentang" class="bg-[#E8DCC4] border-y border-dashed border-[#CDB48F] py-16 sm:py-24 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-6 text-left">
                    <span class="font-hand text-3xl text-[#B5753A]">Dedikasi & Rasa</span>
                    <h2 class="font-display text-3xl sm:text-5xl font-semibold text-[#2B1D14]">Tentang CoffeBar</h2>
                    <div class="w-16 h-0.5 bg-[#B5753A]"></div>
                    <p class="text-[#4A3224] text-base sm:text-lg leading-relaxed">
                        {{ $settings['about_text'] ?? 'CoffeBar didirikan dengan semangat menyajikan kopi berkualitas dari berbagai pelosok Nusantara. Kami percaya setiap cangkir memiliki cerita dan kehangatan yang layak dinikmati bersama.' }}
                    </p>
                    <p class="font-hand text-2xl text-[#2B1D14] pt-2">
                        "Secangkir kopi, sejuta obrolan bermakna."
                    </p>
                </div>

                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-64 h-64 sm:w-80 sm:h-80 bg-[#FFFDF8] border-2 border-[#CDB48F] rounded-full p-6 shadow-warm flex items-center justify-center">
                        <img src="{{ asset('images/cup-latte.png') }}" class="w-52 h-52 sm:w-64 sm:h-64 object-contain drop-shadow-xl" alt="Latte Art CoffeBar">
                        <img src="{{ asset('images/beans-cluster.png') }}" class="absolute -bottom-4 -left-4 w-24 h-24 object-contain drop-shadow-md z-10" alt="Biji Kopi">
                        <img src="{{ asset('images/bean-single.png') }}" class="absolute -top-3 -right-3 w-12 h-12 object-contain drop-shadow-md z-10 rotate-12" alt="Biji Kopi Tunggal">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. PROSES KAMI: 3 LANGKAH IKONIK (03-DESIGN.md Section 7: Espresso Dark Section) -->
    <section class="bg-[#2B1D14] text-[#F4ECDD] py-20 border-b border-[#4A3224]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-16">
                <span class="font-hand text-2xl text-[#CDB48F]">Dari Kebun ke Meja</span>
                <h2 class="font-display text-3xl sm:text-4xl font-semibold text-[#F4ECDD] mt-1">Proses Seduhan Kami</h2>
                <p class="text-[#CDB48F] text-sm mt-2">Tiga prinsip tak terpisahkan dalam setiap tetes kopi CoffeBar.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1: Petik -->
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] transition group">
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
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] transition group">
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
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] transition group">
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

    <!-- 6. LOKASI & KONTAK SECTION (03-DESIGN.md Section 7) -->
    <section id="kontak" class="py-16 sm:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-14">
            <span class="font-hand text-2xl text-[#B5753A]">Mampir & Bertegur Sapa</span>
            <h2 class="font-display text-3xl sm:text-4xl font-semibold text-[#2B1D14] mt-1">Lokasi & Kontak</h2>
            <p class="text-[#6B5646] text-sm sm:text-base mt-2">Kunjungi kedai kami atau hubungi kami untuk informasi lebih lanjut.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Alamat -->
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-7 flex flex-col justify-between shadow-sm">
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
