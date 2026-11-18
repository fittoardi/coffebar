<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir') — CoffeBar Internal</title>

    <!-- Google Fonts: Fraunces & DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,400..700,50&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/bean-single.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F4ECDD] min-h-screen text-[#2B1D14] flex flex-col md:flex-row font-sans antialiased"
      x-data="{ mobileNavOpen: false }">

    <!-- DESKTOP SIDEBAR (03-DESIGN.md Section 18: Espresso Dark Sidebar) -->
    <aside class="hidden md:flex md:w-64 bg-[#2B1D14] text-[#F4ECDD] border-r border-[#4A3224] flex-col justify-between p-5 shrink-0">
        <div class="space-y-6">
            <!-- Brand & Status -->
            <div class="border-b border-[#4A3224] pb-4">
                <a href="{{ route('cashier.dashboard') }}" class="block group">
                    <div class="flex items-center space-x-2.5">
                        <img src="{{ asset('images/bean-single.png') }}" class="w-6 h-6 object-contain inline-block -mt-0.5 group-hover:rotate-12 transition duration-300" alt="Kopi" />
                        <span class="font-display font-bold text-xl text-[#F4ECDD] tracking-tight group-hover:text-[#B5753A] transition">
                            COFFEBAR
                        </span>
                    </div>
                    <div class="flex items-center space-x-2 mt-2 text-xs text-[#CDB48F]">
                        <span class="w-2 h-2 rounded-full bg-[#7A8467] animate-pulse"></span>
                        <span class="font-medium">Kasir & Barista Aktif</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5 text-sm font-medium">
                <a href="{{ route('cashier.dashboard') }}"
                   class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('cashier.dashboard') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-lg">📋</span>
                    <span>Dashboard Pesanan</span>
                </a>

                <a href="{{ route('cashier.pos.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('cashier.pos.*') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-lg">⚡</span>
                    <span>POS Walk-In</span>
                </a>

                <a href="{{ route('cashier.verify') }}"
                   class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('cashier.verify') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-lg">🔍</span>
                    <span>Verifikasi QR / Kode</span>
                </a>

                <a href="{{ route('cashier.shifts.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('cashier.shifts.*') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-lg">💰</span>
                    <span>Shift & Kas</span>
                </a>

                <div class="border-t border-[#4A3224] pt-4 mt-4">
                    <a href="{{ route('home') }}"
                       class="flex items-center space-x-2 px-3 py-2 text-xs text-[#CDB48F] hover:text-[#F4ECDD] transition">
                        <span>←</span>
                        <span>Lihat Web Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Cashier User Info & Logout -->
        <div class="border-t border-[#4A3224] pt-4 space-y-3">
            <div class="text-xs">
                <span class="text-[#CDB48F] block text-[10px] uppercase tracking-wider font-semibold">Petugas Kasir:</span>
                <strong class="text-[#F4ECDD] block truncate text-sm font-semibold">{{ Auth::user()->name }}</strong>
                <span class="text-[10px] text-[#B5753A] uppercase font-bold tracking-wider">{{ Auth::user()->role }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-xs text-[#B4552F] hover:text-[#F4ECDD] py-1.5 transition cursor-pointer font-bold">
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE TOPBAR -->
    <header class="md:hidden bg-[#2B1D14] text-[#F4ECDD] border-b border-[#4A3224] p-4 flex items-center justify-between z-30 sticky top-0">
        <div class="flex items-center space-x-2">
            <span class="font-display font-bold text-lg text-[#F4ECDD]">☕ COFFEBAR KASIR</span>
        </div>
        <button @click="mobileNavOpen = !mobileNavOpen" class="text-[#F4ECDD] focus:outline-none p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- MOBILE DRAWER -->
    <div x-show="mobileNavOpen"
         x-cloak
         class="md:hidden bg-[#2B1D14] text-[#F4ECDD] border-b border-[#4A3224] p-4 space-y-2 text-sm">
        <a href="{{ route('cashier.dashboard') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Dashboard Pesanan</a>
        <a href="{{ route('cashier.pos.index') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">POS Walk-In</a>
        <a href="{{ route('cashier.verify') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Verifikasi QR / Kode</a>
        <a href="{{ route('cashier.shifts.index') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Shift & Kas</a>
        <a href="{{ route('home') }}" class="block py-2 text-[#CDB48F]">← Web Publik</a>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

        <!-- Flash Notifications -->
        @if (session('status') || session('success') || session('error'))
            <div class="mb-6 space-y-2">
                @if (session('success'))
                    <div class="p-4 bg-[#7A8467]/15 border border-[#7A8467] text-[#4F5A3E] text-sm font-semibold rounded-xl">
                        ✓ {{ session('success') }}
                    </div>
                @endif
                @if (session('status'))
                    <div class="p-4 bg-[#E8DCC4] border border-[#CDB48F] text-[#4A3224] text-sm rounded-xl">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 bg-[#B4552F]/15 border border-[#B4552F] text-[#B4552F] text-sm rounded-xl">
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
