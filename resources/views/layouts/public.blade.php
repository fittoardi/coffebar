<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kedai Kopi & Cerita Autentik') — CoffeBar</title>

    <!-- Google Fonts: Rustic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="texture-parchment min-h-screen font-body text-rustic-800 flex flex-col antialiased selection:bg-copper selection:text-rustic-900"
      x-data="{ mobileMenuOpen: false }">

    @php
        $cartCount = app(\App\Services\Cart\CartService::class)->getCount();
    @endphp

    <!-- Top Retro Navigation Bar with 70s Burnt Orange Accent -->
    <nav class="sticky top-0 z-40 bg-rustic-900 text-parchment border-t-4 border-retro-orange border-b-2 border-rustic-950 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 sm:h-20">

                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 text-copper hover:text-copper-light transition group">
                        <span class="text-2xl sm:text-3xl font-display font-black tracking-wide text-parchment group-hover:text-retro-orange transition flex items-center gap-2">
                            <span>☕</span>
                            <span class="tracking-tight">COFFEBAR</span>
                        </span>
                        <span class="hidden sm:inline-block text-[9px] font-heading tracking-widest text-retro-mustard uppercase font-bold border-l-2 border-rustic-700 pl-3">
                            ★ ESTD. 2026 ★
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-8 font-heading text-sm tracking-wider uppercase">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-copper border-b-2 border-copper pb-1' : 'text-parchment hover:text-copper' }} transition">
                        Beranda
                    </a>
                    <a href="{{ route('menu.index') }}"
                       class="{{ request()->routeIs('menu.*') ? 'text-copper border-b-2 border-copper pb-1' : 'text-parchment hover:text-copper' }} transition">
                        Menu Kami
                    </a>
                    <a href="{{ route('custom.index') }}"
                       class="{{ request()->routeIs('custom.*') ? 'text-copper border-b-2 border-copper pb-1' : 'text-parchment hover:text-copper' }} transition">
                        Berkreasi ✦
                    </a>
                </div>

                <!-- Right Action Icons: Cart & Auth -->
                <div class="flex items-center space-x-4 sm:space-x-6">
                    <!-- Shopping Cart Icon -->
                    <a href="{{ route('cart.index') }}"
                       class="relative p-2 text-parchment hover:text-copper transition"
                       title="Keranjang Pesanan">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-copper text-rustic-900 font-heading font-bold text-xs w-5 h-5 rounded-full flex items-center justify-center border border-rustic-900 shadow">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login Button -->
                    @auth
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen"
                                    type="button"
                                    class="flex items-center space-x-2 text-xs font-heading uppercase tracking-wider text-parchment hover:text-copper border border-rustic-700 bg-rustic-800 px-3 py-1.5 rounded-sm transition">
                                <span>{{ Str::limit(Auth::user()->name, 12) }}</span>
                                <svg class="w-4 h-4 text-copper" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div x-show="userMenuOpen"
                                 @click.outside="userMenuOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-48 bg-rustic-900 border-2 border-copper shadow-rustic rounded-sm py-2 z-50 text-xs font-heading uppercase tracking-wider">
                                <div class="px-4 py-2 border-b border-rustic-700 text-rustic-400 normal-case font-body">
                                    Masuk sebagai:<br>
                                    <strong class="text-parchment">{{ Auth::user()->name }}</strong>
                                </div>

                                <a href="{{ route('orders.index') }}"
                                   class="block px-4 py-2.5 text-parchment hover:bg-rustic-800 hover:text-copper transition">
                                    📋 Pesanan Saya
                                </a>

                                @if (Auth::user()->isCashier())
                                    <a href="{{ route('cashier.dashboard') }}"
                                       class="block px-4 py-2.5 text-parchment hover:bg-rustic-800 hover:text-copper transition">
                                        🛒 Panel Kasir
                                    </a>
                                @endif

                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="block px-4 py-2.5 text-parchment hover:bg-rustic-800 hover:text-copper transition">
                                        ⚙️ Panel Admin
                                    </a>
                                @endif

                                <div class="border-t border-rustic-700 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2.5 text-brick hover:bg-rustic-800 transition">
                                        🚪 Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-block bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading text-xs uppercase tracking-widest px-4 py-2 shadow-rustic transition active:translate-x-0.5 active:translate-y-0.5">
                            MASUK
                        </a>
                    @endauth

                    <!-- Mobile Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="md:hidden text-parchment hover:text-copper p-1 focus:outline-none"
                            aria-label="Menu Navigasi">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden bg-rustic-900 border-t border-rustic-700 px-4 pt-3 pb-5 space-y-3 font-heading uppercase text-sm tracking-wider">
            <a href="{{ route('home') }}"
               class="block py-2 text-parchment hover:text-copper border-b border-rustic-800">
                Beranda
            </a>
            <a href="{{ route('menu.index') }}"
               class="block py-2 text-parchment hover:text-copper border-b border-rustic-800">
                Menu Kami
            </a>
            <a href="{{ route('custom.index') }}"
               class="block py-2 text-parchment hover:text-copper border-b border-rustic-800">
                Berkreasi Minuman ✦
            </a>

            @guest
                <div class="pt-2">
                    <a href="{{ route('login') }}"
                       class="block text-center w-full bg-rustic-800 text-parchment border-2 border-copper font-heading py-2.5 shadow-rustic">
                        MASUK KE AKUN
                    </a>
                </div>
            @endguest
        </div>
    </nav>

    <!-- Flash Alerts Container -->
    @if (session('status') || session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if (session('status'))
                <div class="p-4 bg-rustic-100 border-l-4 border-copper text-rustic-800 text-sm font-body shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 bg-[#2D6A4F]/15 border-l-4 border-[#2D6A4F] text-[#2D6A4F] text-sm font-body shadow-sm font-semibold">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-brick/15 border-l-4 border-brick text-brick text-sm font-body shadow-sm">
                    ⚠ {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content Slot -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Rustic Footer -->
    <footer class="bg-rustic-900 text-parchment border-t-4 border-copper mt-20 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">

                <!-- Col 1: Brand & Philosophy -->
                <div class="md:col-span-2 space-y-3">
                    <span class="text-3xl font-display font-black tracking-wide text-copper">
                        ☕ COFFEBAR
                    </span>
                    <div class="text-copper text-xs tracking-widest select-none">
                        ❧ ─────── ✦ ─────── ❧
                    </div>
                    <p class="font-body text-sm text-rustic-300 max-w-md leading-relaxed">
                        Menyeduh cerita dan cita rasa kopi autentik dari tanah nusantara. Hadir dengan suasana hangat meja kayu tua, aroma biji kopi segar, dan racikan penuh dedikasi.
                    </p>
                </div>

                <!-- Col 2: Jam Operasional -->
                <div class="space-y-2">
                    <h3 class="font-heading font-bold text-copper uppercase tracking-wider text-sm border-b border-rustic-700 pb-1">
                        Jam Operasional
                    </h3>
                    <ul class="text-sm font-body text-rustic-300 space-y-1">
                        <li>Senin – Jumat: 08.00 – 22.00</li>
                        <li>Sabtu – Minggu: 07.00 – 23.00</li>
                        <li class="italic text-xs text-copper pt-1">Buka setiap hari termasuk hari libur nasional.</li>
                    </ul>
                </div>

                <!-- Col 3: Lokasi & Kontak -->
                <div class="space-y-2">
                    <h3 class="font-heading font-bold text-copper uppercase tracking-wider text-sm border-b border-rustic-700 pb-1">
                        Kedai Kami
                    </h3>
                    <p class="text-sm font-body text-rustic-300">
                        Jl. Kopi No. 12, Senopati, Kebayoran Baru, Jakarta Selatan.
                    </p>
                    <div class="pt-2 text-xs font-heading space-y-1">
                        <div>WhatsApp: <a href="https://wa.me/6281234567890" class="text-copper hover:underline">0812-3456-7890</a></div>
                        <div>Instagram: <span class="text-copper">@coffebar.id</span></div>
                    </div>
                </div>

            </div>

            <!-- Footer Bottom Line -->
            <div class="border-t border-rustic-800 pt-6 text-center text-xs text-rustic-400 font-body">
                <div>&copy; {{ date('Y') }} CoffeBar Indonesia. Disajikan dengan rasa bangga dan secangkir cerita.</div>
                <div class="mt-2 text-[11px]">
                    <a href="{{ route('staff.login') }}" class="text-rustic-500 hover:text-copper transition font-heading uppercase tracking-wider">
                        🔐 Portal Khusus Petugas Kasir & Admin →
                    </a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
