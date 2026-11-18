@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-gray-900">Pendaftaran Pelanggan</h1>
        <p class="text-xs text-gray-500 mt-1">
            Daftar untuk mulai memesan dan meracik minuman kustom Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   placeholder="Contoh: Ahmad Budiman"
                   class="w-full bg-white border border-gray-300 px-3.5 py-2 text-sm text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">
                Alamat Email <span class="text-red-500">*</span>
            </label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="nama@email.com"
                   class="w-full bg-white border border-gray-300 px-3.5 py-2 text-sm text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
            <span class="text-xs text-gray-500 mt-0.5 block">Kode OTP verifikasi akan dikirimkan ke email ini.</span>
        </div>

        <div>
            <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1">
                Nomor WhatsApp / HP <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="phone"
                   id="phone"
                   value="{{ old('phone') }}"
                   required
                   placeholder="08xxxxxxxxxx"
                   class="w-full bg-white border border-gray-300 px-3.5 py-2 text-sm text-gray-900 rounded-md focus:border-blue-500 focus:outline-none">
            <span class="text-xs text-gray-500 mt-0.5 block">Wajib aktif untuk menerima kode verifikasi OTP.</span>
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white hover:bg-blue-700 font-medium text-xs uppercase tracking-wider py-2.5 px-4 rounded-md transition cursor-pointer">
                Daftar & Kirim OTP
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs text-gray-500">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold ml-1">
            Masuk Sekarang
        </a>
    </div>
@endsection
