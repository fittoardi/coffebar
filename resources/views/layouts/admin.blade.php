<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — CoffeBar</title>

    <!-- Google Fonts: Fraunces & DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,400..700,50&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F4ECDD] min-h-screen text-[#2B1D14] flex flex-col md:flex-row font-sans antialiased"
      x-data="{ mobileNavOpen: false }">

    <!-- DESKTOP SIDEBAR (03-DESIGN.md Section 19: Espresso Dark Sidebar 240px) -->
    <aside class="hidden md:flex md:w-64 bg-[#2B1D14] text-[#F4ECDD] border-r border-[#4A3224] flex-col justify-between p-5 shrink-0">
        <div class="space-y-6">
            <!-- Brand -->
            <div class="border-b border-[#4A3224] pb-4">
                <a href="{{ route('admin.dashboard') }}" class="block group">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">☕</span>
                        <span class="font-display font-bold text-xl text-[#F4ECDD] tracking-tight group-hover:text-[#B5753A] transition">
                            COFFEBAR
                        </span>
                    </div>
                    <span class="block text-xs text-[#CDB48F] mt-1 font-medium">
                        Panel Administrasi Kedai
                    </span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-base">📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-base">📂</span>
                    <span>Kategori Menu</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-base">☕</span>
                    <span>Produk & Menu</span>
                </a>

                <a href="{{ route('admin.staff.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.staff.*') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-base">👥</span>
                    <span>Akun Staf / Kasir</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-base">📈</span>
                    <span>Laporan Penjualan</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#4A3224] text-[#F4ECDD] font-bold border-l-4 border-[#B5753A]' : 'text-[#CDB48F] hover:bg-[#1E1A17] hover:text-[#F4ECDD]' }}">
                    <span class="text-base">⚙️</span>
                    <span>Profil & Pengaturan</span>
                </a>

                <div class="border-t border-[#4A3224] pt-4 mt-4 space-y-1">
                    <a href="{{ route('cashier.dashboard') }}"
                       class="flex items-center space-x-2 px-3 py-2 text-xs text-[#CDB48F] hover:text-[#F4ECDD] transition">
                        <span>⚡</span>
                        <span>Buka Sistem Kasir</span>
                    </a>

                    <a href="{{ route('home') }}"
                       class="flex items-center space-x-2 px-3 py-2 text-xs text-[#CDB48F] hover:text-[#F4ECDD] transition">
                        <span>←</span>
                        <span>Lihat Web Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Admin User Info & Logout -->
        <div class="border-t border-[#4A3224] pt-4 space-y-2">
            <div class="text-xs">
                <span class="text-[#CDB48F] block text-[10px] uppercase tracking-wider font-semibold">Administrator:</span>
                <strong class="text-[#F4ECDD] block truncate text-sm font-semibold">{{ Auth::user()->name }}</strong>
                <span class="text-[10px] text-[#CDB48F]">{{ Auth::user()->email }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-xs text-[#B4552F] hover:text-[#F4ECDD] py-1 transition cursor-pointer font-bold">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE HEADER -->
    <div class="md:hidden bg-[#2B1D14] text-[#F4ECDD] p-4 flex items-center justify-between border-b border-[#4A3224]">
        <a href="{{ route('admin.dashboard') }}" class="font-display font-bold text-lg text-[#F4ECDD]">
            ☕ COFFEBAR ADMIN
        </a>
        <button @click="mobileNavOpen = !mobileNavOpen"
                class="px-3 py-1.5 border border-[#CDB48F] text-[#F4ECDD] rounded-xl text-xs font-semibold">
            <span x-text="mobileNavOpen ? '✕ Tutup' : '☰ Menu'"></span>
        </button>
    </div>

    <!-- MOBILE NAV DRAWER -->
    <div x-show="mobileNavOpen"
         x-cloak
         class="md:hidden bg-[#2B1D14] text-[#F4ECDD] border-b border-[#4A3224] p-4 space-y-2 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-[#F4ECDD] font-semibold">Dashboard</a>
        <a href="{{ route('admin.categories.index') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Kategori Menu</a>
        <a href="{{ route('admin.products.index') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Produk & Menu</a>
        <a href="{{ route('admin.staff.index') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Akun Staf / Kasir</a>
        <a href="{{ route('admin.reports.index') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Laporan Penjualan</a>
        <a href="{{ route('admin.settings.index') }}" class="block py-2 text-[#CDB48F] hover:text-[#F4ECDD]">Profil & Pengaturan</a>
        <div class="border-t border-[#4A3224] pt-2 space-y-2">
            <a href="{{ route('cashier.dashboard') }}" class="block py-1 text-[#CDB48F]">⚡ Sistem Kasir</a>
            <a href="{{ route('home') }}" class="block py-1 text-[#CDB48F]">← Web Publik</a>
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-left py-1 text-[#B4552F] text-xs font-bold">Keluar</button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-[#7A8467]/15 border border-[#7A8467] text-[#4F5A3E] text-sm font-semibold rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs text-[#4F5A3E] font-bold">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-[#B4552F]/15 border border-[#B4552F] text-[#B4552F] text-sm font-semibold rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <span>⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs text-[#B4552F] font-bold">✕</button>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
