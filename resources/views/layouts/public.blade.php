<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kedai Kopi') — CoffeBar</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col font-sans antialiased"
      x-data="{ mobileMenuOpen: false }">

    @php
        $cartCount = app(\App\Services\Cart\CartService::class)->getCount();
    @endphp

    <!-- Simple Functional Navigation Bar -->
    <nav class="sticky top-0 z-40 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 text-gray-900 font-bold text-xl tracking-tight hover:text-blue-600 transition">
                        <span>☕</span>
                        <span>COFFEBAR</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-gray-700">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : 'hover:text-gray-900' }} transition">
                        Beranda
                    </a>
                    <a href="{{ route('menu.index') }}"
                       class="{{ request()->routeIs('menu.*') ? 'text-blue-600 font-semibold' : 'hover:text-gray-900' }} transition">
                        Menu Kami
                    </a>
                    <a href="{{ route('custom.index') }}"
                       class="{{ request()->routeIs('custom.*') ? 'text-blue-600 font-semibold' : 'hover:text-gray-900' }} transition">
                        Berkreasi
                    </a>
                </div>

                <!-- Right Action Icons: Cart & Auth -->
                <div class="flex items-center space-x-4">
                    <!-- Shopping Cart Icon -->
                    <a href="{{ route('cart.index') }}"
                       class="relative p-2 text-gray-700 hover:text-blue-600 transition"
                       title="Keranjang Pesanan">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-blue-600 text-white font-bold text-xs w-5 h-5 rounded-full flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login Button -->
                    @auth
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen"
                                    type="button"
                                    class="flex items-center space-x-2 text-sm text-gray-700 border border-gray-300 bg-white px-3 py-1.5 rounded-md hover:bg-gray-50 transition">
                                <span>{{ Str::limit(Auth::user()->name, 16) }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div x-show="userMenuOpen"
                                 @click.outside="userMenuOpen = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-md rounded-md py-1 z-50 text-sm">
                                <div class="px-4 py-2 border-b border-gray-100 text-xs text-gray-500">
                                    Masuk sebagai:<br>
                                    <strong class="text-gray-900 font-medium">{{ Auth::user()->name }}</strong>
                                </div>

                                <a href="{{ route('orders.index') }}"
                                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                    Pesanan Saya
                                </a>

                                @if (Auth::user()->isCashier())
                                    <a href="{{ route('cashier.dashboard') }}"
                                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                        Panel Kasir
                                    </a>
                                @endif

                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                        Panel Admin
                                    </a>
                                @endif

                                <div class="border-t border-gray-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 transition">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-block bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium px-4 py-2 rounded-md transition">
                            Masuk
                        </a>
                    @endauth

                    <!-- Mobile Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="md:hidden text-gray-700 hover:text-gray-900 p-1 focus:outline-none"
                            aria-label="Menu Navigasi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen"
             x-cloak
             class="md:hidden bg-white border-t border-gray-200 px-4 pt-2 pb-4 space-y-1 text-sm">
            <a href="{{ route('home') }}"
               class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">
                Beranda
            </a>
            <a href="{{ route('menu.index') }}"
               class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">
                Menu Kami
            </a>
            <a href="{{ route('custom.index') }}"
               class="block py-2 text-gray-700 hover:text-blue-600 border-b border-gray-100">
                Berkreasi
            </a>

            @guest
                <div class="pt-2">
                    <a href="{{ route('login') }}"
                       class="block text-center w-full bg-blue-600 text-white font-medium py-2 rounded-md hover:bg-blue-700 transition">
                        Masuk ke Akun
                    </a>
                </div>
            @endguest
        </div>
    </nav>

    <!-- Flash Alerts Container -->
    @if (session('status') || session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            @if (session('status'))
                <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-sm font-medium rounded-md">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-md">
                    ⚠ {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content Slot -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Simple Functional Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16 py-10 text-gray-600 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

                <!-- Col 1: Brand -->
                <div class="space-y-2">
                    <span class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                        <span>☕</span>
                        <span>COFFEBAR</span>
                    </span>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Menikmati cita rasa kopi pilihan nusantara dengan racikan sederhana dan berkualitas.
                    </p>
                </div>

                <!-- Col 2: Jam Operasional -->
                <div class="space-y-2">
                    <h3 class="font-semibold text-gray-900 text-sm">Jam Operasional</h3>
                    <ul class="text-gray-500 text-sm space-y-1">
                        <li>Senin – Jumat: 08.00 – 22.00 WIB</li>
                        <li>Sabtu – Minggu: 07.00 – 23.00 WIB</li>
                    </ul>
                </div>

                <!-- Col 3: Lokasi & Kontak -->
                <div class="space-y-2">
                    <h3 class="font-semibold text-gray-900 text-sm">Lokasi & Kontak</h3>
                    <p class="text-gray-500 text-sm">
                        Jl. Senopati No. 88, Jakarta Selatan
                    </p>
                    <div class="text-sm space-y-1 text-gray-500">
                        <div>WhatsApp: 0812-3456-7890</div>
                    </div>
                </div>

            </div>

            <!-- Footer Bottom Line -->
            <div class="border-t border-gray-200 pt-6 text-center text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>&copy; {{ date('Y') }} CoffeBar. Semua hak cipta dilindungi.</div>
                <div>
                    <a href="{{ route('staff.login') }}" class="text-gray-500 hover:text-gray-900 underline transition">
                        Portal Staf & Kasir
                    </a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
