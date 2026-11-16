@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    <!-- HERO SECTION -->
    <section class="relative bg-rustic-900 text-parchment py-20 sm:py-28 overflow-hidden border-b-2 border-copper">
        <!-- Background Overlay / Pattern -->
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#B87333_1px,transparent_1px)] [background-size:16px_16px]"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 text-center space-y-6">
            <span class="inline-block bg-rustic-800 text-copper border border-copper font-heading uppercase text-xs tracking-widest px-3 py-1 mb-2">
                ☕ {{ $settings['shop_tagline'] ?? 'Kedai Kopi Autentik Nusantara' }}
            </span>

            <h1 class="font-display font-black text-4xl sm:text-6xl lg:text-7xl text-parchment tracking-tight leading-tight">
                {{ $settings['hero_title'] ?? 'Kopi Terbaik, Diracik Untukmu' }}
            </h1>

            <div class="text-copper text-base tracking-widest select-none">
                ❧ ─────── ✦ ─────── ❧
            </div>

            <p class="font-body italic text-base sm:text-xl text-rustic-200 max-w-2xl mx-auto leading-relaxed">
                "{{ $settings['hero_subtitle'] ?? 'Setiap tetes seduhan menyimpan cerita kehangatan meja kayu, obrolan akrab, dan biji kopi pilihan yang disangrai dengan penuh cinta.' }}"
            </p>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('menu.index') }}"
                   class="w-full sm:w-auto bg-copper text-rustic-900 border-2 border-rustic-700 hover:bg-copper-light font-heading tracking-widest uppercase text-sm py-3.5 px-8 shadow-rustic transition duration-150 active:translate-x-0.5 active:translate-y-0.5 font-bold">
                    LIHAT DAFTAR MENU
                </a>
                <a href="{{ route('custom.index') }}"
                   class="w-full sm:w-auto bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading tracking-widest uppercase text-sm py-3.5 px-8 shadow-rustic transition duration-150 active:translate-x-0.5 active:translate-y-0.5">
                    BERKREASI SENDIRI ✦
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION: MENU POPULER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="text-center mb-12">
            <h2 class="font-display font-bold text-3xl sm:text-4xl text-rustic-900">
                Menu Yang Paling Dicintai
            </h2>
            <div class="text-copper text-sm tracking-widest my-2 select-none">
                ❧ ─────── ✦ ─────── ❧
            </div>
            <p class="font-body italic text-rustic-600 text-sm sm:text-base">
                Pilihan favorit para penikmat seduhan CoffeBar setiap harinya
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($popularProducts as $product)
                <div class="bg-parchment-dark border-2 border-rustic-500 rounded-sm p-4 shadow-rustic hover:-translate-y-1.5 hover:shadow-rustic-lg transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Product Placeholder / Image Container -->
                        <div class="w-full h-44 bg-rustic-800 border border-rustic-600 rounded-sm flex items-center justify-center text-copper mb-4 relative overflow-hidden group">
                            <span class="text-4xl select-none group-hover:scale-110 transition duration-300">
                                @if (str_contains(strtolower($product->category->name), 'kopi'))
                                    ☕
                                @elseif (str_contains(strtolower($product->category->name), 'makanan'))
                                    🥐
                                @else
                                    🥤
                                @endif
                            </span>
                            <span class="absolute top-2 right-2 bg-rustic-900/80 text-parchment text-[10px] font-heading uppercase px-2 py-0.5 border border-copper">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        <h3 class="font-display font-bold text-lg text-rustic-900 leading-snug">
                            {{ $product->name }}
                        </h3>

                        <p class="font-body text-xs text-rustic-700 mt-2 line-clamp-2 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-rustic-400/50 flex items-center justify-between">
                        <span class="font-display font-bold text-lg text-rustic-900">
                            {{ $product->formatted_price }}
                        </span>

                        <form method="POST" action="{{ route('cart.add') }}" class="inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                    class="bg-rustic-800 text-parchment border border-copper hover:bg-rustic-700 font-heading text-xs uppercase px-3 py-1.5 shadow-rustic-sm transition active:translate-x-0.5 active:translate-y-0.5">
                                + Pesan
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('menu.index') }}"
               class="inline-block font-heading text-sm uppercase tracking-wider text-copper hover:text-copper-light underline font-bold">
                Lihat Semua Menu Lengkap →
            </a>
        </div>
    </section>

    <!-- SECTION: TENTANG KAMI -->
    <section class="bg-rustic-800 text-parchment py-16 border-y-2 border-copper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div class="space-y-4">
                    <span class="inline-block text-copper font-heading text-xs uppercase tracking-widest">
                        Tentang Kedai Kami
                    </span>
                    <h2 class="font-display font-bold text-3xl sm:text-4xl text-parchment">
                        {{ $settings['about_title'] ?? 'Bukan Sekadar Kedai Kopi, Tapi Rumah Cerita' }}
                    </h2>
                    <div class="text-copper text-sm tracking-widest select-none">
                        ❧ ─────── ✦ ─────── ❧
                    </div>
                    <p class="font-body text-rustic-200 text-sm sm:text-base leading-relaxed">
                        {{ $settings['about_description_1'] ?? 'Lahir dari kecintaan pada aroma biji kopi yang baru digiling dan hangatnya interaksi manusia. Di CoffeBar, kami percaya bahwa secangkir kopi terbaik bukan hanya tentang teknik ekstraksi, melainkan tentang momen hening yang Anda nikmati atau obrolan mendalam bersama sahabat.' }}
                    </p>
                    <p class="font-body text-rustic-200 text-sm sm:text-base leading-relaxed">
                        {{ $settings['about_description_2'] ?? 'Kami bekerja sama langsung dengan para petani kopi lokal dari Gayo, Kintamani, Toraja, hingga Mandailing untuk menyajikan cita rasa asli yang terjaga kesegarannya.' }}
                    </p>
                </div>

                <div class="bg-parchment border-2 border-copper p-6 text-rustic-900 shadow-rustic-lg">
                    <div class="border border-dashed border-rustic-500 p-6 text-center space-y-4">
                        <span class="text-4xl">🕰️</span>
                        <h3 class="font-display font-bold text-2xl text-rustic-900">
                            Racikan Tradisi & Kehangatan
                        </h3>
                        <p class="font-body italic text-sm text-rustic-700">
                            "Menikmati waktu yang melambat di tengah hiruk-pikuk kota, ditemani secangkir kopi hangat dan sepotong pastry renyah."
                        </p>
                        <div class="pt-2 text-xs font-heading uppercase tracking-widest text-copper font-bold">
                            — CoffeBar Roastery 2026
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: CARA PESAN -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="text-center mb-12">
            <h2 class="font-display font-bold text-3xl sm:text-4xl text-rustic-900">
                Cara Mudah Menikmati
            </h2>
            <div class="text-copper text-sm tracking-widest my-2 select-none">
                ❧ ─────── ✦ ─────── ❧
            </div>
            <p class="font-body italic text-rustic-600 text-sm">
                Pesan langsung lewat web dan ambil tanpa antre panjang
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 text-center shadow-rustic space-y-3">
                <div class="w-14 h-14 mx-auto bg-rustic-800 text-copper border border-copper rounded-full flex items-center justify-center font-display font-bold text-xl">
                    ①
                </div>
                <h3 class="font-heading font-bold text-base uppercase text-rustic-900">Pilih Menu / Berkreasi</h3>
                <p class="font-body text-xs text-rustic-700 leading-relaxed">
                    Jelajahi menu favorit kami atau racik sendiri minuman unik Anda dengan pilihan base, susu, gula, dan topping sesuka hati.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 text-center shadow-rustic space-y-3">
                <div class="w-14 h-14 mx-auto bg-rustic-800 text-copper border border-copper rounded-full flex items-center justify-center font-display font-bold text-xl">
                    ②
                </div>
                <h3 class="font-heading font-bold text-base uppercase text-rustic-900">Checkout & Dapatkan QR</h3>
                <p class="font-body text-xs text-rustic-700 leading-relaxed">
                    Pilih bayar online melalui QRIS atau bayar langsung di kasir. Anda akan mendapatkan kode tiket dan QR Code instan.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-parchment border-2 border-rustic-500 rounded-sm p-6 text-center shadow-rustic space-y-3">
                <div class="w-14 h-14 mx-auto bg-rustic-800 text-copper border border-copper rounded-full flex items-center justify-center font-display font-bold text-xl">
                    ③
                </div>
                <h3 class="font-heading font-bold text-base uppercase text-rustic-900">Tunjukkan & Nikmati</h3>
                <p class="font-body text-xs text-rustic-700 leading-relaxed">
                    Tunjukkan tiket pesanan Anda ke barista di kasir. Minuman segar siap diracik untuk menemani harimu.
                </p>
            </div>
        </div>
    </section>

    <!-- SECTION: LOKASI & GALERI -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-[#EDD9A3] border-2 border-rustic-500 p-6 sm:p-10 shadow-rustic-lg">
            <div class="space-y-4">
                <span class="text-copper font-heading text-xs uppercase tracking-widest font-bold">
                    Kunjungi Kedai Kami
                </span>
                <h2 class="font-display font-bold text-3xl text-rustic-900">
                    Kedai Suasana Hangat Menunggumu
                </h2>
                <p class="font-body text-sm text-rustic-800 leading-relaxed">
                    Tersedia area indoor ber-AC yang nyaman untuk bekerja (WFC) dengan Wi-Fi kencang dan colokan di setiap meja, serta area outdoor rimbun untuk menikmati senja.
                </p>
                <div class="pt-2 border-t border-rustic-400 space-y-2 text-sm font-body text-rustic-900">
                    <div>📍 <strong>Alamat:</strong> {{ $settings['address'] ?? 'Jl. Kopi No. 12, Senopati, Kebayoran Baru, Jakarta Selatan' }}</div>
                    <div>🕒 <strong>Jam Buka:</strong> {{ $settings['opening_hours'] ?? 'Setiap hari 08.00 – 22.00 WIB' }}</div>
                    <div>📞 <strong>Kontak Kasir:</strong> {{ $settings['contact_phone'] ?? '0812-3456-7890' }}</div>
                </div>
            </div>

            <!-- Visual Ambience / Map Card -->
            <div class="border-2 border-rustic-700 bg-rustic-900 text-parchment p-6 text-center space-y-3">
                <span class="text-4xl">🗺️</span>
                <h3 class="font-display font-bold text-xl text-copper">Peta Lokasi & Denah</h3>
                <p class="font-body italic text-xs text-rustic-300">
                    Hanya 5 menit berjalan kaki dari Halte Senopati. Parkir mobil dan motor luas tersedia di area depan.
                </p>
                <a href="{{ $settings['maps_url'] ?? 'https://maps.google.com' }}" target="_blank" rel="noopener"
                   class="inline-block bg-copper text-rustic-900 font-heading text-xs uppercase tracking-wider py-2 px-4 font-bold hover:bg-copper-light transition">
                    Buka Google Maps ↗
                </a>
            </div>
        </div>
    </section>

@endsection
