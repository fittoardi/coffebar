<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir') — CoffeBar Internal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen text-gray-900 flex flex-col md:flex-row font-sans antialiased"
      x-data="{ mobileNavOpen: false }">

    <!-- DESKTOP SIDEBAR -->
    <aside class="hidden md:flex md:w-64 bg-white border-r border-gray-200 flex-col justify-between p-5 shrink-0">
        <div class="space-y-6">
            <!-- Brand -->
            <div class="border-b border-gray-200 pb-4">
                <a href="{{ route('cashier.dashboard') }}" class="block">
                    <span class="text-xl font-bold text-gray-900 tracking-tight">
                        ☕ COFFEBAR
                    </span>
                    <span class="block text-xs text-gray-500 mt-0.5">
                        Sistem Kasir & Barista
                    </span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1 text-sm font-medium">
                <a href="{{ route('cashier.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('cashier.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>📋</span>
                    <span>Dashboard Pesanan</span>
                </a>

                <a href="{{ route('cashier.pos.index') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('cashier.pos.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>⚡</span>
                    <span>POS Walk-In</span>
                </a>

                <a href="{{ route('cashier.verify') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('cashier.verify') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>🔍</span>
                    <span>Verifikasi QR / Kode</span>
                </a>

                <a href="{{ route('cashier.shifts.index') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('cashier.shifts.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>💰</span>
                    <span>Shift & Kas</span>
                </a>

                <div class="border-t border-gray-100 pt-3 mt-4">
                    <a href="{{ route('home') }}"
                       class="flex items-center space-x-3 px-3 py-2 text-xs text-gray-500 hover:text-gray-900 transition">
                        <span>←</span>
                        <span>Lihat Web Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Cashier User Info & Logout -->
        <div class="border-t border-gray-200 pt-4 space-y-2">
            <div class="text-xs">
                <span class="text-gray-500 block text-[10px]">Petugas:</span>
                <strong class="text-gray-900 block truncate">{{ Auth::user()->name }}</strong>
                <span class="text-[10px] text-gray-500 uppercase">{{ Auth::user()->role }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-xs text-red-600 hover:text-red-800 py-1 transition cursor-pointer">
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE TOPBAR -->
    <header class="md:hidden bg-white border-b border-gray-200 p-4 flex items-center justify-between z-30 sticky top-0">
        <div class="flex items-center space-x-2">
            <span class="font-bold text-lg text-gray-900">☕ COFFEBAR KASIR</span>
        </div>
        <button @click="mobileNavOpen = !mobileNavOpen" class="text-gray-700 focus:outline-none p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- MOBILE DRAWER -->
    <div x-show="mobileNavOpen"
         x-cloak
         class="md:hidden bg-white border-b border-gray-200 p-4 space-y-2 text-sm">
        <a href="{{ route('cashier.dashboard') }}" class="block py-1.5 text-gray-700 hover:text-blue-600">Dashboard Pesanan</a>
        <a href="{{ route('cashier.pos.index') }}" class="block py-1.5 text-gray-700 hover:text-blue-600">POS Walk-In</a>
        <a href="{{ route('cashier.verify') }}" class="block py-1.5 text-gray-700 hover:text-blue-600">Verifikasi QR / Kode</a>
        <a href="{{ route('cashier.shifts.index') }}" class="block py-1.5 text-gray-700 hover:text-blue-600">Shift & Kas</a>
        <a href="{{ route('home') }}" class="block py-1.5 text-gray-500">← Web Publik</a>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

        <!-- Flash Notifications -->
        @if (session('status') || session('success') || session('error'))
            <div class="mb-6 space-y-2">
                @if (session('success'))
                    <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-sm font-medium rounded-md">
                        ✓ {{ session('success') }}
                    </div>
                @endif
                @if (session('status'))
                    <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-md">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-md">
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
