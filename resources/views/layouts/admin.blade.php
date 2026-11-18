<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — CoffeBar</title>

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
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <span class="text-xl font-bold text-gray-900 tracking-tight">
                        ☕ COFFEBAR
                    </span>
                    <span class="block text-xs text-gray-500 mt-0.5">
                        Panel Owner & Admin
                    </span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>📂</span>
                    <span>Kategori Menu</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>☕</span>
                    <span>Produk & Menu</span>
                </a>

                <a href="{{ route('admin.staff.index') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.staff.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>👥</span>
                    <span>Akun Staf / Kasir</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>📈</span>
                    <span>Laporan Penjualan</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center space-x-3 px-3 py-2 rounded-md transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <span>⚙️</span>
                    <span>Profil & Pengaturan</span>
                </a>

                <div class="border-t border-gray-100 pt-3 mt-4 space-y-1">
                    <a href="{{ route('cashier.dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2 text-xs text-gray-600 hover:text-blue-600 transition">
                        <span>⚡</span>
                        <span>Buka Sistem Kasir</span>
                    </a>

                    <a href="{{ route('home') }}"
                       class="flex items-center space-x-3 px-3 py-2 text-xs text-gray-500 hover:text-gray-900 transition">
                        <span>←</span>
                        <span>Lihat Web Publik</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Admin User Info & Logout -->
        <div class="border-t border-gray-200 pt-4 space-y-2">
            <div class="text-xs">
                <span class="text-gray-500 block text-[10px]">Administrator:</span>
                <strong class="text-gray-900 block truncate">{{ Auth::user()->name }}</strong>
                <span class="text-[10px] text-gray-500">{{ Auth::user()->email }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-xs text-red-600 hover:text-red-800 py-1 transition cursor-pointer">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE HEADER -->
    <div class="md:hidden bg-white text-gray-900 p-4 flex items-center justify-between border-b border-gray-200">
        <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg text-gray-900">
            ☕ COFFEBAR ADMIN
        </a>
        <button @click="mobileNavOpen = !mobileNavOpen"
                class="px-2 py-1 border border-gray-300 text-gray-700 rounded text-sm">
            <span x-text="mobileNavOpen ? '✕ Tutup' : '☰ Menu'"></span>
        </button>
    </div>

    <!-- MOBILE NAV DRAWER -->
    <div x-show="mobileNavOpen"
         x-cloak
         class="md:hidden bg-white border-b border-gray-200 p-4 space-y-2 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="block py-1.5 text-blue-600 font-semibold">Dashboard</a>
        <a href="{{ route('admin.categories.index') }}" class="block py-1.5 text-gray-700">Kategori Menu</a>
        <a href="{{ route('admin.products.index') }}" class="block py-1.5 text-gray-700">Produk & Menu</a>
        <a href="{{ route('admin.staff.index') }}" class="block py-1.5 text-gray-700">Akun Staf / Kasir</a>
        <a href="{{ route('admin.reports.index') }}" class="block py-1.5 text-gray-700">Laporan Penjualan</a>
        <a href="{{ route('admin.settings.index') }}" class="block py-1.5 text-gray-700">Profil & Pengaturan</a>
        <div class="border-t border-gray-100 pt-2 space-y-2">
            <a href="{{ route('cashier.dashboard') }}" class="block py-1 text-gray-600">Sistem Kasir</a>
            <a href="{{ route('home') }}" class="block py-1 text-gray-500">← Web Publik</a>
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-left py-1 text-red-600 text-xs">Keluar</button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-sm font-medium rounded-md flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs text-green-700 font-bold">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-md flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span>⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs text-red-700 font-bold">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-md">
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
