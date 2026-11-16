<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — CoffeBar Artisan</title>

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
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <span class="text-2xl font-display font-black text-copper tracking-wide">
                        ☕ COFFEBAR
                    </span>
                    <span class="block text-[11px] font-heading uppercase tracking-widest text-parchment-dark mt-0.5">
                        Panel Owner & Admin
                    </span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5 font-heading text-xs uppercase tracking-wider">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('admin.categories.*') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>📂</span>
                    <span>Kategori Menu</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('admin.products.*') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>☕</span>
                    <span>Produk & Menu</span>
                </a>

                <a href="{{ route('admin.staff.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('admin.staff.*') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>👥</span>
                    <span>Akun Staf / Kasir</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('admin.reports.*') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>📈</span>
                    <span>Laporan Penjualan</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-sm transition {{ request()->routeIs('admin.settings.*') ? 'bg-rustic-800 text-copper border-l-4 border-copper font-bold' : 'text-parchment hover:bg-rustic-800 hover:text-copper' }}">
                    <span>⚙️</span>
                    <span>Profil & Pengaturan</span>
                </a>

                <div class="border-t border-rustic-800 pt-3 mt-4 space-y-1">
                    <a href="{{ route('cashier.dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2 text-rustic-400 hover:text-copper transition">
                        <span>⚡</span>
                        <span>Buka Sistem Kasir</span>
                    </a>

                    <a href="{{ route('home') }}"
                       class="flex items-center space-x-3 px-3 py-2 text-rustic-400 hover:text-parchment transition">
                        <span>←</span>
                        <span>Lihat Web Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Admin User Info & Logout -->
        <div class="border-t border-rustic-700 pt-4 space-y-3">
            <div class="text-xs">
                <span class="text-rustic-400 block text-[10px]">Administrator:</span>
                <strong class="text-parchment block truncate">{{ Auth::user()->name }}</strong>
                <span class="text-[10px] text-copper uppercase font-heading">{{ Auth::user()->email }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-center py-2 px-3 border border-copper/50 text-xs font-heading uppercase tracking-wider text-parchment hover:bg-rustic-800 hover:text-copper transition">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE HEADER -->
    <div class="md:hidden bg-rustic-900 text-parchment p-4 flex items-center justify-between border-b-2 border-copper">
        <a href="{{ route('admin.dashboard') }}" class="font-display font-black text-copper text-lg tracking-wide">
            ☕ COFFEBAR ADMIN
        </a>
        <button @click="mobileNavOpen = !mobileNavOpen"
                class="px-2 py-1 border border-copper text-copper rounded text-sm">
            <span x-text="mobileNavOpen ? '✕ Tutup' : '☰ Menu'"></span>
        </button>
    </div>

    <!-- MOBILE NAV DRAWER -->
    <div x-show="mobileNavOpen"
         x-cloak
         class="md:hidden bg-rustic-900 text-parchment border-b border-copper p-4 space-y-2 font-heading text-xs uppercase">
        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-copper font-bold">📊 Dashboard</a>
        <a href="{{ route('admin.categories.index') }}" class="block py-2">📂 Kategori Menu</a>
        <a href="{{ route('admin.products.index') }}" class="block py-2">☕ Produk & Menu</a>
        <a href="{{ route('admin.staff.index') }}" class="block py-2">👥 Akun Staf / Kasir</a>
        <a href="{{ route('admin.reports.index') }}" class="block py-2">📈 Laporan Penjualan</a>
        <a href="{{ route('admin.settings.index') }}" class="block py-2">⚙️ Profil & Pengaturan</a>
        <div class="border-t border-rustic-800 pt-2 space-y-2">
            <a href="{{ route('cashier.dashboard') }}" class="block py-1 text-copper">⚡ Sistem Kasir</a>
            <a href="{{ route('home') }}" class="block py-1 text-parchment-dark">← Web Publik</a>
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-left py-1 text-brick">Keluar</button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-2 border-emerald-600 text-emerald-950 font-heading text-sm shadow-rustic-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs text-emerald-700 font-bold">Tutup</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-brick/10 border-2 border-brick text-brick font-heading text-sm shadow-rustic-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span>⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs text-brick font-bold">Tutup</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-brick/10 border-2 border-brick text-brick font-heading text-sm shadow-rustic-sm">
                <div class="font-bold mb-1">Periksa kembali data formulir Anda:</div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
