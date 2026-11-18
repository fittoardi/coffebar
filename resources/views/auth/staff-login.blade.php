@extends('layouts.auth')

@section('title', 'Login Portal Staf & Kasir')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded mb-2">
            Portal Internal Staf
        </span>
        <h1 class="text-xl font-bold text-gray-900">Masuk Portal Staf</h1>
        <p class="text-xs text-gray-500 mt-1">
            Akses khusus petugas kasir dan administrator CoffeBar.
        </p>
    </div>

    <!-- Form: Staff Password Login -->
    <form method="POST" action="{{ route('staff.login.post') }}" class="space-y-4">
        @csrf

        <!-- Email Akun Staf -->
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">
                Email atau No. HP Staf <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   placeholder="Contoh: kasir@coffebar.id atau admin@coffebar.id"
                   class="w-full bg-white border border-gray-300 px-3.5 py-2 text-sm text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
        </div>

        <!-- Kata Sandi -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-semibold text-gray-700">
                    Kata Sandi <span class="text-red-500">*</span>
                </label>
            </div>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   placeholder="••••••••"
                   class="w-full bg-white border border-gray-300 px-3.5 py-2 text-sm text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" value="1"
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-xs text-gray-700">
                    Ingat Sesi Saya
                </span>
            </label>
        </div>

        <!-- Demo Accounts Hint Box -->
        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md text-xs text-gray-600 space-y-1">
            <div class="font-semibold text-gray-800">
                Akun Demo Petugas (Sandi: password123)
            </div>
            <div>• Kasir: <strong class="text-gray-900">kasir@coffebar.id</strong></div>
            <div>• Admin: <strong class="text-gray-900">admin@coffebar.id</strong></div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white hover:bg-blue-700 font-medium text-xs uppercase tracking-wider py-2.5 px-4 rounded-md transition cursor-pointer">
                Masuk ke Portal Internal
            </button>
        </div>
    </form>

    <!-- Switch back to Customer Login -->
    <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
            ← Kembali ke Halaman Login Pelanggan
        </a>
    </div>
@endsection
