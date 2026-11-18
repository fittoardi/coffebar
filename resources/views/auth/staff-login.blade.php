@extends('layouts.auth')

@section('title', 'Login Portal Staf & Kasir')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-[#E8DCC4] border border-[#CDB48F] text-[#4A3224] text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-3">
            Portal Internal Staf
        </span>
        <h1 class="font-display text-2xl font-semibold text-[#2B1D14]">Masuk Portal Staf</h1>
        <p class="text-xs text-[#6B5646] mt-1.5 leading-relaxed">
            Akses khusus petugas kasir dan administrator CoffeBar.
        </p>
    </div>

    <!-- Form: Staff Password Login -->
    <form method="POST" action="{{ route('staff.login.post') }}" class="space-y-4">
        @csrf

        <!-- Email Akun Staf -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                Email atau No. HP Staf <span class="text-[#B4552F]">*</span>
            </label>
            <input type="text"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   placeholder="Contoh: kasir@coffebar.id atau admin@coffebar.id"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
        </div>

        <!-- Kata Sandi -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#4A3224]">
                    Kata Sandi <span class="text-[#B4552F]">*</span>
                </label>
            </div>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   placeholder="••••••••"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" value="1"
                       class="w-4 h-4 text-[#B5753A] border-[#CDB48F] rounded focus:ring-[#B5753A]">
                <span class="text-xs text-[#4A3224] font-medium">
                    Ingat Sesi Saya
                </span>
            </label>
        </div>

        <!-- Demo Accounts Hint Box -->
        <div class="p-3.5 bg-[#E8DCC4]/60 border border-[#CDB48F] rounded-xl text-xs text-[#4A3224] space-y-1">
            <div class="font-bold text-[#2B1D14] flex items-center space-x-1">
                <span>🔑</span>
                <span>Akun Demo Petugas (Sandi: password123)</span>
            </div>
            <div>• Kasir: <strong class="text-[#2B1D14]">kasir@coffebar.id</strong></div>
            <div>• Admin: <strong class="text-[#2B1D14]">admin@coffebar.id</strong></div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-[#4A3224] text-[#F4ECDD] hover:bg-[#2B1D14] font-bold text-xs uppercase tracking-wider py-3.5 px-4 rounded-xl transition shadow-warm cursor-pointer">
                Masuk ke Portal Internal
            </button>
        </div>
    </form>

    <!-- Switch back to Customer Login -->
    <div class="mt-6 pt-5 border-t border-dashed border-[#CDB48F] text-center text-xs">
        <a href="{{ route('login') }}" class="text-[#B5753A] hover:text-[#B4552F] font-bold">
            ← Kembali ke Halaman Login Pelanggan
        </a>
    </div>
@endsection
