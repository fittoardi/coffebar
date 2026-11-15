<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir') — CoffeBar Internal</title>

    <!-- Google Fonts: Rustic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-rustic-50 min-h-screen font-body text-rustic-900 flex flex-col md:flex-row antialiased"
      x-data="{ mobileNavOpen: false }">

    <!-- DESKTOP SIDEBAR -->
    <aside class="hidden md:flex md:w-64 bg-rustic-900 text-parchment flex-col justify-between border-r-2 border-copper p-5 shadow-lg shrink-0">
        <div class="space-y-6">
            <!-- Brand -->
            <div class="border-b border-rustic-700 pb-4">
                <a href="{{ route('cashier.dashboard') }}" class="block">
                    <span class="text-2xl font-display font-black text-copper tracking-wide">
                        ☕ COFFEBAR
                    </span>
                    <span class="block text-[11px] font-heading uppercase tracking-widest text-parchment-dark mt-0.5">
                        Sistem Kasir & Barista
                    </span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-2 font-heading text-xs uppercase tracking-wider">
                <a href="{{ route('cashier.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('cashier.dashboard') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>📋</span>
                    <span>Dashboard Pesanan</span>
                </a>

                <a href="{{ route('cashier.pos.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('cashier.pos.*') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>⚡</span>
                    <span>POS Pesanan Walk-In</span>
                </a>

                <a href="{{ route('cashier.verify') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('cashier.verify') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>🔍</span>
                    <span>Verifikasi QR / Kode</span>
                </a>

                <a href="{{ route('cashier.shifts.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('cashier.shifts.*') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>💰</span>
                    <span>Shift & Kas Harian</span>
                </a>

                <div class="border-t border-rustic-800 pt-3 mt-4">
                    <a href="{{ route('home') }}"
                       class="flex items-center space-x-3 px-3 py-2 text-rustic-400 hover:text-parchment transition">
                        <span>←</span>
                        <span>Lihat Web Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Cashier User Info & Logout -->
        <div class="border-t border-rustic-700 pt-4 space-y-3">
            <div class="text-xs">
                <span class="text-rustic-400 block text-[10px]">Petugas Aktif:</span>
                <strong class="text-parchment block truncate">{{ Auth::user()->name }}</strong>
                <span class="text-[10px] text-copper uppercase font-heading">{{ Auth::user()->role }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left font-heading text-xs uppercase tracking-wider text-brick hover:text-red-400 py-1 transition">
                    🚪 Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE TOPBAR -->
    <header class="md:hidden bg-rustic-900 text-parchment border-b-2 border-copper p-4 flex items-center justify-between z-30 sticky top-0">
        <div class="flex items-center space-x-2">
            <span class="font-display font-black text-xl text-copper">☕ COFFEBAR KASIR</span>
        </div>
        <button @click="mobileNavOpen = !mobileNavOpen" class="text-parchment focus:outline-none p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- MOBILE DRAWER -->
    <div x-show="mobileNavOpen"
         x-transition
         class="md:hidden bg-rustic-900 border-b-2 border-copper p-4 text-parchment space-y-3 font-heading text-xs uppercase tracking-wider">
        <a href="{{ route('cashier.dashboard') }}" class="block py-1.5 hover:text-copper">📋 Dashboard Pesanan</a>
        <a href="{{ route('cashier.pos.index') }}" class="block py-1.5 hover:text-copper">⚡ POS Walk-In</a>
        <a href="{{ route('cashier.verify') }}" class="block py-1.5 hover:text-copper">🔍 Verifikasi QR / Kode</a>
        <a href="{{ route('cashier.shifts.index') }}" class="block py-1.5 hover:text-copper">💰 Shift & Kas</a>
        <a href="{{ route('home') }}" class="block py-1.5 text-rustic-400">← Web Publik</a>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

        <!-- Flash Notifications -->
        @if (session('status') || session('success') || session('error'))
            <div class="mb-6 space-y-2">
                @if (session('success'))
                    <div class="p-4 bg-[#2D6A4F]/15 border-l-4 border-[#2D6A4F] text-[#2D6A4F] text-sm font-body font-bold rounded-xs shadow-sm">
                        ✓ {{ session('success') }}
                    </div>
                @endif
                @if (session('status'))
                    <div class="p-4 bg-rustic-100 border-l-4 border-copper text-rustic-800 text-sm font-body rounded-xs shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 bg-brick/15 border-l-4 border-brick text-brick text-sm font-body font-bold rounded-xs shadow-sm">
                        ⚠ {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
