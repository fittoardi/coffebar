<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kedai Kopi & Cerita Autentik') — CoffeBar</title>

    <!-- Google Fonts: Rustic Modern (Fraunces, DM Sans, Caveat) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,400..700,50&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/bean-single.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F4ECDD] text-[#2B1D14] min-h-screen flex flex-col font-sans antialiased selection:bg-[#B5753A] selection:text-white overflow-x-hidden"
      x-data="{ mobileMenuOpen: false }">

    @php
        $cartCount = app(\App\Services\Cart\CartService::class)->getCount();
    @endphp

    <!-- Rustic Modern Navigation Bar (03-DESIGN.md Section 6.1) -->
    <nav class="sticky top-0 z-40 bg-[#F4ECDD]/95 backdrop-blur-sm border-b border-dashed border-[#CDB48F] transition-all">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24">
            <div class="flex items-center justify-between h-20">

                <!-- Logo & Brand (Wordmark Serif) -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 text-[#2B1D14] hover:text-[#B5753A] transition group">
                        <img src="{{ asset('images/bean-single.png') }}" class="w-7 h-7 object-contain inline-block -mt-0.5 group-hover:rotate-12 transition transform duration-300 drop-shadow-sm" alt="Biji Kopi" />
                        <span class="font-display font-bold text-2xl tracking-tight text-[#2B1D14] group-hover:text-[#B5753A] transition">
                            COFFEBAR
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-[#4A3224]">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-[#B5753A] font-bold border-b-2 border-[#B5753A] pb-1' : 'hover:text-[#B5753A]' }} transition">
                        Beranda
                    </a>
                    <a href="{{ route('menu.index') }}"
                       class="{{ request()->routeIs('menu.*') ? 'text-[#B5753A] font-bold border-b-2 border-[#B5753A] pb-1' : 'hover:text-[#B5753A]' }} transition">
                        Menu Kami
                    </a>
                    <a href="{{ route('custom.index') }}"
                       class="{{ request()->routeIs('custom.*') ? 'text-[#B5753A] font-bold border-b-2 border-[#B5753A] pb-1' : 'hover:text-[#B5753A]' }} transition">
                        Berkreasi ✦
                    </a>
                    <a href="{{ route('home') }}#tentang" class="hover:text-[#B5753A] transition">
                        Tentang
                    </a>
                    <a href="{{ route('home') }}#kontak" class="hover:text-[#B5753A] transition">
                        Lokasi & Kontak
                    </a>
                </div>

                <!-- Right Action Icons: Cart & Auth -->
                <div class="flex items-center space-x-4">
                    <!-- Shopping Cart Icon -->
                    <a href="{{ route('cart.index') }}"
                       class="relative p-2 text-[#4A3224] hover:text-[#B5753A] transition"
                       title="Keranjang Pesanan">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-[#B5753A] text-white font-bold text-xs w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login Button -->
                    @auth
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen"
                                    type="button"
                                    class="flex items-center space-x-2 text-sm text-[#2B1D14] border border-[#CDB48F] bg-[#FFFDF8] px-3.5 py-2 rounded-xl hover:bg-[#E8DCC4] transition">
                                <span>{{ Str::limit(Auth::user()->name, 14) }}</span>
                                <svg class="w-4 h-4 text-[#B5753A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div x-show="userMenuOpen"
                                 @click.outside="userMenuOpen = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-[#FFFDF8] border border-[#CDB48F] shadow-lg rounded-xl py-1.5 z-50 text-sm">
                                <div class="px-4 py-2 border-b border-[#E8DCC4] text-xs text-[#6B5646]">
                                    Masuk sebagai:<br>
                                    <strong class="text-[#2B1D14]">{{ Auth::user()->name }}</strong>
                                </div>

                                <a href="{{ route('orders.index') }}"
                                   class="block px-4 py-2 text-[#4A3224] hover:bg-[#F4ECDD] transition">
                                    📋 Pesanan Saya
                                </a>

                                @if (Auth::user()->isCashier())
                                    <a href="{{ route('cashier.dashboard') }}"
                                       class="block px-4 py-2 text-[#4A3224] hover:bg-[#F4ECDD] transition">
                                        🛒 Panel Kasir
                                    </a>
                                @endif

                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="block px-4 py-2 text-[#4A3224] hover:bg-[#F4ECDD] transition">
                                        ⚙️ Panel Admin
                                    </a>
                                @endif

                                <div class="border-t border-[#E8DCC4] my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-[#B4552F] hover:bg-[#F4ECDD] transition">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-block bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-xl shadow-sm hover:-translate-y-0.5 transition duration-200">
                            Masuk
                        </a>
                    @endauth

                    <!-- Mobile Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="md:hidden text-[#2B1D14] hover:text-[#B5753A] p-1 focus:outline-none"
                            aria-label="Menu Navigasi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation (Section 6.1: Charcoal Background with Large Fraunces Links) -->
        <div x-show="mobileMenuOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-[#1E1A17] text-[#F4ECDD] px-6 pt-6 pb-8 space-y-4 font-display border-b border-[#4A3224]">
            <a href="{{ route('home') }}" @click="mobileMenuOpen = false"
               class="block text-2xl text-[#F4ECDD] hover:text-[#B5753A] border-b border-[#4A3224] pb-2">
                Beranda
            </a>
            <a href="{{ route('menu.index') }}" @click="mobileMenuOpen = false"
               class="block text-2xl text-[#F4ECDD] hover:text-[#B5753A] border-b border-[#4A3224] pb-2">
                Menu Kami
            </a>
            <a href="{{ route('custom.index') }}" @click="mobileMenuOpen = false"
               class="block text-2xl text-[#F4ECDD] hover:text-[#B5753A] border-b border-[#4A3224] pb-2">
                Berkreasi ✦
            </a>
            <a href="{{ route('home') }}#tentang" @click="mobileMenuOpen = false"
               class="block text-xl text-[#E8DCC4] hover:text-[#B5753A] border-b border-[#4A3224] pb-2">
                Tentang
            </a>
            <a href="{{ route('home') }}#kontak" @click="mobileMenuOpen = false"
               class="block text-xl text-[#E8DCC4] hover:text-[#B5753A] border-b border-[#4A3224] pb-2">
                Lokasi & Kontak
            </a>

            @auth
                <div class="pt-3 font-sans space-y-2 text-sm">
                    <div class="text-xs text-[#CDB48F]">Masuk sebagai: <strong>{{ Auth::user()->name }}</strong></div>
                    <a href="{{ route('orders.index') }}" class="block py-2 text-[#E8DCC4] hover:text-[#B5753A]">📋 Pesanan Saya</a>
                    @if (Auth::user()->isCashier())
                        <a href="{{ route('cashier.dashboard') }}" class="block py-2 text-[#E8DCC4] hover:text-[#B5753A]">🛒 Panel Kasir</a>
                    @endif
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-[#E8DCC4] hover:text-[#B5753A]">⚙️ Panel Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-[#B4552F]">Keluar</button>
                    </form>
                </div>
            @else
                <div class="pt-4 font-sans">
                    <a href="{{ route('login') }}"
                       class="block text-center w-full bg-[#B5753A] text-white font-bold py-3 rounded-xl hover:bg-[#B4552F] transition">
                        Masuk ke Akun
                    </a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Flash Alerts Container -->
    @if (session('status') || session('success') || session('error'))
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24 mt-4">
            @if (session('status'))
                <div class="p-4 bg-[#FFFDF8] border-l-4 border-[#B5753A] text-[#2B1D14] text-sm rounded-r-xl shadow-warm-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 bg-[#7A8467]/15 border-l-4 border-[#7A8467] text-[#4F5A3E] text-sm font-semibold rounded-r-xl shadow-warm-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-[#B4552F]/15 border-l-4 border-[#B4552F] text-[#B4552F] text-sm rounded-r-xl shadow-warm-sm">
                    ⚠ {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content Slot -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Rustic Modern Footer (03-DESIGN.md Section 6.7: Charcoal, Cream, Kraft) -->
    <footer class="bg-[#1E1A17] text-[#F4ECDD] border-t-2 border-[#CDB48F]/30 mt-20 pt-16 pb-12">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-24">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 xl:gap-14 mb-12">

                <!-- Col 1: Brand & Tagline -->
                <div class="md:col-span-1 space-y-3">
                    <span class="font-display font-bold text-2xl tracking-tight text-[#F4ECDD] flex items-center gap-2">
                        <img src="{{ asset('images/bean-single.png') }}" class="w-6 h-6 object-contain inline-block -mt-0.5" alt="Kopi" />
                        <span>COFFEBAR</span>
                    </span>
                    <p class="text-[#CDB48F] text-xs font-hand text-lg">
                        "Disangrai pelan, diseduh dengan hati."
                    </p>
                    <p class="text-[#E8DCC4]/80 text-xs leading-relaxed">
                        Kehangatan kedai kopi tradisional dengan biji kopi pilihan nusantara dan roti hangat setiap hari.
                    </p>
                    <img src="{{ asset('images/beans-cluster.png') }}" class="w-14 h-14 object-contain opacity-75 pt-1 drop-shadow-sm" alt="Biji Kopi Nusantara" />
                </div>

                <!-- Col 2: Jam Buka -->
                <div class="space-y-3">
                    <h3 class="font-display font-semibold text-[#B5753A] text-base">Jam Buka</h3>
                    <ul class="text-[#E8DCC4]/80 text-xs space-y-1.5">
                        <li>Senin – Jumat: 08.00 – 22.00 WIB</li>
                        <li>Sabtu – Minggu: 07.00 – 23.00 WIB</li>
                        <li class="text-[#7A8467] pt-1 font-medium">● Buka setiap hari termasuk hari libur</li>
                    </ul>
                </div>

                <!-- Col 3: Lokasi & Kontak -->
                <div class="space-y-3">
                    <h3 class="font-display font-semibold text-[#B5753A] text-base">Kedai Kami</h3>
                    <p class="text-[#E8DCC4]/80 text-xs leading-relaxed">
                        Jl. Senopati No. 88, Jakarta Selatan
                    </p>
                    <div class="text-xs space-y-1 text-[#E8DCC4]/80">
                        <div>WhatsApp: <a href="https://wa.me/6281234567890" class="text-[#B5753A] hover:underline">0812-3456-7890</a></div>
                        <div>Instagram: <span class="text-[#B5753A]">@coffebar.id</span></div>
                    </div>
                </div>

                <!-- Col 4: Aksi Cepat & Navigasi -->
                <div class="space-y-3">
                    <h3 class="font-display font-semibold text-[#B5753A] text-base">Navigasi</h3>
                    <ul class="text-xs space-y-2 text-[#E8DCC4]/80">
                        <li><a href="{{ route('menu.index') }}" class="hover:text-[#B5753A] transition">Daftar Menu</a></li>
                        <li><a href="{{ route('custom.index') }}" class="hover:text-[#B5753A] transition">Racik Minuman Sendiri</a></li>
                        <li><a href="{{ route('staff.login') }}" class="hover:text-[#B5753A] transition">Portal Khusus Staf & Kasir →</a></li>
                    </ul>
                </div>

            </div>

            <!-- Footer Bottom Line -->
            <div class="border-t border-dashed border-[#CDB48F]/25 pt-8 text-center text-xs text-[#CDB48F] flex flex-col sm:flex-row items-center justify-between gap-3">
                <div>&copy; {{ date('Y') }} CoffeBar. Semua hak cipta dilindungi.</div>
                <div class="text-xs">
                    Kopi lokal pilihan · Handcrafted with warmth
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
