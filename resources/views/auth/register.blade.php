@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="font-display font-bold text-2xl text-rustic-900">Pendaftaran Pelanggan</h1>
        <p class="font-body italic text-sm text-rustic-600 mt-1">
            Daftar untuk mulai memesan dan meracik minuman kustom Anda
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-body italic text-sm text-rustic-700 mb-1">
                Nama Lengkap
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   placeholder="Contoh: Ahmad Budiman"
                   class="w-full bg-parchment border-2 border-rustic-400 font-body px-4 py-2.5 text-rustic-900 rounded-sm focus:border-copper focus:outline-none focus:ring-1 focus:ring-copper">
        </div>

        <div>
            <label for="email" class="block font-body italic text-sm text-rustic-700 mb-1">
                Alamat Email
            </label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="nama@email.com"
                   class="w-full bg-parchment border-2 border-rustic-400 font-body px-4 py-2.5 text-rustic-900 rounded-sm focus:border-copper focus:outline-none focus:ring-1 focus:ring-copper">
            <span class="text-xs text-rustic-500 italic mt-0.5 block">Kode OTP verifikasi akan dikirimkan ke email ini.</span>
        </div>

        <div>
            <label for="phone" class="block font-body italic text-sm text-rustic-700 mb-1">
                Nomor WhatsApp / HP
            </label>
            <input type="text"
                   name="phone"
                   id="phone"
                   value="{{ old('phone') }}"
                   required
                   placeholder="08xxxxxxxxxx"
                   class="w-full bg-parchment border-2 border-rustic-400 font-body px-4 py-2.5 text-rustic-900 rounded-sm focus:border-copper focus:outline-none focus:ring-1 focus:ring-copper">
            <span class="text-xs text-rustic-500 italic mt-0.5 block">Wajib aktif untuk menerima kode verifikasi OTP.</span>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading tracking-widest uppercase text-sm py-3 px-4 shadow-rustic transition duration-150 active:translate-x-0.5 active:translate-y-0.5">
                DAFTAR & KIRIM OTP
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-rustic-200 text-center text-sm font-body">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-heading text-copper hover:underline font-bold ml-1">
            Masuk Sekarang
        </a>
    </div>
@endsection
