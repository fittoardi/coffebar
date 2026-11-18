@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    <!-- HERO SECTION -->
    <section class="bg-white border-b border-gray-200 py-16 sm:py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center space-y-6">
            <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full">
                {{ $settings['shop_tagline'] ?? 'Kedai Kopi Autentik Nusantara' }}
            </span>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight">
                COFFEBAR — {{ $settings['hero_title'] ?? 'Kopi Terbaik, Diracik Untukmu' }}
            </h1>

            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                {{ $settings['hero_subtitle'] ?? 'Cita Rasa Juara Dari Bumi Nusantara' }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="{{ route('menu.index') }}"
                   class="w-full sm:w-auto bg-blue-600 text-white font-medium px-6 py-3 rounded-md hover:bg-blue-700 transition">
                    Lihat Daftar Menu
                </a>
                <a href="{{ route('custom.index') }}"
                   class="w-full sm:w-auto bg-white text-gray-700 border border-gray-300 font-medium px-6 py-3 rounded-md hover:bg-gray-50 transition">
                    Racik Minuman Sendiri
                </a>
            </div>
        </div>
    </section>

    <!-- HIGHLIGHT MENU & CATEGORIES -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h2 class="text-2xl font-bold text-gray-900">Pilihan Menu Favorit</h2>
            <p class="text-gray-500 text-sm mt-1">Jelajahi berbagai pilihan kopi dan sajian pelengkap kami.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($popularProducts ?? [] as $product)
                <div class="bg-white border border-gray-200 rounded-lg p-5 flex flex-col justify-between hover:shadow-sm transition">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span>{{ $product->category->name ?? 'Minuman' }}</span>
                            @if (!$product->is_available)
                                <span class="text-red-600 font-medium">Habis</span>
                            @else
                                <span class="text-green-600 font-medium">Tersedia</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $product->name }}</h3>
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $product->description }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <a href="{{ route('menu.index') }}"
                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Pesan →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500 text-sm bg-white border border-gray-200 rounded-lg">
                    Menu belum tersedia saat ini.
                </div>
            @endforelse
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="bg-gray-100 border-y border-gray-200 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-4">
            <h2 class="text-2xl font-bold text-gray-900">Tentang CoffeBar</h2>
            <p class="text-gray-600 text-sm leading-relaxed max-w-2xl mx-auto">
                {{ $settings['about_text'] ?? 'CoffeBar didirikan dengan semangat menyajikan kopi berkualitas dari berbagai pelosok Nusantara. Kami percaya setiap cangkir memiliki cerita dan kehangatan yang layak dinikmati bersama.' }}
            </p>
        </div>
    </section>

    <!-- LOCATION & CONTACT SECTION -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Lokasi & Kontak</h2>
            <p class="text-gray-500 text-sm mt-1">Kunjungi kedai kami atau hubungi kami untuk informasi lebih lanjut.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-bold text-gray-900 mb-2">Alamat Kedai</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ $settings['shop_address'] ?? 'Jl. Senopati No. 88, Jakarta Selatan' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-bold text-gray-900 mb-2">Jam Buka</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ $settings['shop_hours'] ?? 'Setiap Hari: 08:00 - 22:00 WIB' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-bold text-gray-900 mb-2">Kontak Kami</h3>
                <p class="text-gray-600 text-sm mb-4">
                    WhatsApp: {{ $settings['contact_phone'] ?? '0812-3456-7890' }}
                </p>
                <a href="{{ $settings['maps_url'] ?? 'https://maps.google.com' }}" target="_blank" rel="noopener"
                   class="inline-block bg-blue-600 text-white text-xs font-medium py-2 px-4 rounded hover:bg-blue-700 transition">
                    Buka Google Maps
                </a>
            </div>
        </div>
    </section>

@endsection
