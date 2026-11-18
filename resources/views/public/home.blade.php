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
                        <span>☕</span>
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
                        <!-- Arch Image Wrapper -->
                        <div class="w-full h-[460px] bg-[#E8DCC4] border-2 border-[#CDB48F] rounded-arch overflow-hidden shadow-warm relative flex flex-col justify-between p-8 text-center bg-gradient-to-b from-[#F4ECDD] to-[#E8DCC4]">
                            <!-- Subtle decorative steam / coffee artwork -->
                            <div class="pt-6">
                                <span class="text-6xl select-none">☕</span>
                            </div>

                            <div class="space-y-3">
                                <p class="font-display text-2xl font-semibold text-[#2B1D14]">Seduhan Murni</p>
                                <p class="text-xs text-[#6B5646] uppercase tracking-widest">Single Origin & House Blend</p>
                                <div class="w-12 h-0.5 bg-[#B5753A] mx-auto"></div>
                                <p class="font-hand text-2xl text-[#4A3224]">"Aroma pagi yang tak pernah bohong"</p>
                            </div>

                            <div class="pb-2 text-xs text-[#6B5646]">
                                Setiap hari · Sejak 2024
                            </div>
                        </div>

                        <!-- Rotating Stamp Badge (Section 5: stempel bulat rotasi pelan) -->
                        <div class="absolute -bottom-6 -left-6 w-28 h-28 bg-[#FFFDF8] border-2 border-dashed border-[#B5753A] rounded-full flex items-center justify-center shadow-md animate-spin-slow select-none pointer-events-none">
                            <svg class="w-24 h-24 text-[#4A3224]" viewBox="0 0 100 100">
                                <path id="stamp-circle" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="none" />
                                <text class="text-[9px] font-bold uppercase tracking-widest fill-current">
                                    <textPath href="#stamp-circle" startOffset="0%">
                                        ✦ ARTISAN ROASTERY ✦ COFFEBAR ✦
                                    </textPath>
                                </text>
                            </svg>
                            <span class="absolute text-xl">☕</span>
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
                <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-xl p-5 flex flex-col justify-between hover:-translate-y-1 hover:shadow-warm transition duration-200">
                    <div>
                        <!-- Category Eyebrow & Status Badge -->
                        <div class="flex items-center justify-between text-xs text-[#6B5646] mb-3">
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
                        <h3 class="font-display font-semibold text-[#2B1D14] text-xl mb-1.5 leading-snug">
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
                           class="text-xs uppercase tracking-wider font-bold bg-[#B5753A] text-white hover:bg-[#B4552F] px-3 py-1.5 rounded-lg transition shadow-sm">
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
    <section id="tentang" class="bg-[#E8DCC4] border-y border-dashed border-[#CDB48F] py-16 sm:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-6">
            <span class="font-hand text-3xl text-[#B5753A]">Dedikasi & Rasa</span>
            <h2 class="font-display text-3xl sm:text-5xl font-semibold text-[#2B1D14]">Tentang CoffeBar</h2>
            <div class="w-16 h-0.5 bg-[#B5753A] mx-auto"></div>
            <p class="text-[#4A3224] text-base sm:text-lg leading-relaxed max-w-2xl mx-auto">
                {{ $settings['about_text'] ?? 'CoffeBar didirikan dengan semangat menyajikan kopi berkualitas dari berbagai pelosok Nusantara. Kami percaya setiap cangkir memiliki cerita dan kehangatan yang layak dinikmati bersama.' }}
            </p>
            <p class="font-hand text-2xl text-[#2B1D14] pt-2">
                "Secangkir kopi, sejuta obrolan bermakna."
            </p>
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
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] transition">
                    <div class="w-16 h-16 mx-auto bg-[#4A3224] rounded-full flex items-center justify-center font-display text-2xl text-[#B5753A]">
                        01
                    </div>
                    <h3 class="font-display text-xl font-semibold text-[#F4ECDD]">Petik Pilihan</h3>
                    <p class="text-[#CDB48F] text-sm leading-relaxed">
                        Hanya buah kopi matang optimal dari petani binaan di dataran tinggi Gayo, Toraja, dan Ijen.
                    </p>
                </div>

                <!-- Step 2: Sangrai -->
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] transition">
                    <div class="w-16 h-16 mx-auto bg-[#4A3224] rounded-full flex items-center justify-center font-display text-2xl text-[#B5753A]">
                        02
                    </div>
                    <h3 class="font-display text-xl font-semibold text-[#F4ECDD]">Sangrai Presisi</h3>
                    <p class="text-[#CDB48F] text-sm leading-relaxed">
                        Artisan slow roast dalam batch kecil untuk mengunci kompleksitas rasa manis alami dan aroma rempah.
                    </p>
                </div>

                <!-- Step 3: Seduh -->
                <div class="bg-[#1E1A17] border border-[#4A3224] rounded-2xl p-8 text-center space-y-4 hover:border-[#B5753A] transition">
                    <div class="w-16 h-16 mx-auto bg-[#4A3224] rounded-full flex items-center justify-center font-display text-2xl text-[#B5753A]">
                        03
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
