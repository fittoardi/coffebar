@extends('layouts.auth')

@section('title', 'Masuk Akun Pelanggan')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded mb-2">
            Pelanggan (OTP)
        </span>
        <h1 class="text-xl font-bold text-gray-900">Masuk Akun Pelanggan</h1>
        <p class="text-xs text-gray-500 mt-1">
            Masuk untuk memesan kopi favorit, meracik menu, dan melihat pesanan Anda.
        </p>
    </div>

    <!-- Form: Customer OTP Login -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="identifier" class="block text-xs font-semibold text-gray-700 mb-1">
                Email atau Nomor WhatsApp / HP <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="identifier"
                   id="identifier"
                   value="{{ old('identifier') }}"
                   required
                   autofocus
                   placeholder="Contoh: 08123456789 atau nama@email.com"
                   class="w-full bg-white border border-gray-300 px-3.5 py-2 text-sm text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
            <span class="text-xs text-gray-500 mt-1 block">
                Kode verifikasi OTP 6-digit akan dikirimkan langsung ke kontak ini.
            </span>
        </div>

        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md text-xs text-gray-600 space-y-1">
            <div class="font-semibold text-gray-800">
                🔒 Tanpa Kata Sandi
            </div>
            <p class="leading-relaxed">
                Anda tidak perlu mengingat kata sandi. Cukup masukkan email atau WhatsApp terdaftar dan sistem akan mengirimkan kode login.
            </p>
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white hover:bg-blue-700 font-medium text-xs uppercase tracking-wider py-2.5 px-4 rounded-md transition cursor-pointer">
                Kirim Kode OTP Masuk
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs text-gray-500">
        Belum memiliki akun pelanggan?
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold ml-1">
            Daftar Sekarang
        </a>
    </div>

    <div class="mt-3 text-center text-xs">
        <a href="{{ route('staff.login') }}" class="text-gray-400 hover:text-gray-600">
            Masuk sebagai Staf Internal / Kasir →
        </a>
    </div>
@endsection
