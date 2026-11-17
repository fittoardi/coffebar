@extends('layouts.auth')

@section('title', 'Login Portal Staf & Kasir')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-brick text-parchment font-heading uppercase text-[10px] tracking-widest px-2.5 py-0.5 border border-copper mb-2 font-bold">
            🔐 Portal Internal Staf & Admin
        </span>
        <h1 class="font-display font-bold text-2xl text-rustic-900">Masuk Portal Staf</h1>
        <p class="font-body italic text-xs text-rustic-600 mt-1">
            Akses khusus petugas kasir, barista, dan administrator CoffeBar.
        </p>
    </div>

    <!-- Form: Staff Password Login -->
    <form method="POST" action="{{ route('staff.login.post') }}" class="space-y-4">
        @csrf

        <!-- Email Akun Staf -->
        <div>
            <label for="email" class="block font-heading uppercase tracking-wider text-xs text-rustic-900 font-black mb-1">
                Email atau No. HP Staf <span class="text-brick">*</span>
            </label>
            <input type="text"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   placeholder="Contoh: kasir@coffebar.id atau admin@coffebar.id"
                   class="w-full bg-parchment border-2 border-rustic-900 font-body px-4 py-2.5 text-rustic-900 rounded-sm shadow-rustic-sm focus:border-retro-orange focus:outline-none">
        </div>

        <!-- Kata Sandi -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block font-heading uppercase tracking-wider text-xs text-rustic-900 font-black">
                    Kata Sandi <span class="text-brick">*</span>
                </label>
            </div>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   placeholder="••••••••"
                   class="w-full bg-parchment border-2 border-rustic-900 font-body px-4 py-2.5 text-rustic-900 rounded-sm shadow-rustic-sm focus:border-retro-orange focus:outline-none">
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1">
            <label class="inline-flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" value="1"
                       class="w-4 h-4 text-retro-orange border-rustic-900 rounded focus:ring-retro-orange">
                <span class="text-xs font-heading uppercase tracking-wider text-rustic-800 font-bold">
                    Ingat Sesi Saya
                </span>
            </label>
        </div>

        <!-- Demo Accounts Hint Box -->
        <div class="p-3 bg-parchment border-2 border-dashed border-rustic-400 rounded-xs text-[11px] font-mono text-rustic-800 space-y-1">
            <div class="font-heading font-black text-retro-orange text-[10px] uppercase tracking-wider">
                💡 Akun Demo Petugas (Sandi: password123)
            </div>
            <div>• Kasir: <strong class="text-rustic-900">kasir@coffebar.id</strong></div>
            <div>• Admin: <strong class="text-rustic-900">admin@coffebar.id</strong></div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-black tracking-widest uppercase text-xs py-3.5 px-4 shadow-rustic transition duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none">
                MASUK KE PORTAL INTERNAL →
            </button>
        </div>
    </form>

    <!-- Switch back to Customer Login -->
    <div class="mt-6 pt-4 border-t-2 border-dashed border-rustic-300 text-center">
        <span class="text-[11px] text-rustic-500 font-body block mb-1">Bukan petugas kasir atau admin?</span>
        <a href="{{ route('login') }}"
           class="inline-flex items-center space-x-1 font-heading text-copper hover:underline text-xs font-bold">
            <span>←</span>
            <span>Kembali ke Halaman Login Pelanggan</span>
        </a>
    </div>
@endsection
