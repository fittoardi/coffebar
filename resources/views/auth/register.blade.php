@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-retro-orange text-parchment font-heading text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 border border-rustic-900 shadow-sm mb-2 -rotate-1">
            ★ REGISTRASI PELANGGAN BARU ★
        </span>
        <h1 class="font-display font-black text-2xl text-rustic-900">Pendaftaran Pelanggan</h1>
        <p class="font-body italic text-xs text-rustic-600 mt-1">
            Daftar untuk mulai memesan dan meracik minuman kustom Anda
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block font-heading uppercase tracking-wider text-xs text-rustic-900 font-black mb-1">
                Nama Lengkap <span class="text-brick">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   placeholder="Contoh: Ahmad Budiman"
                   class="w-full bg-parchment border-2 border-rustic-900 font-body px-4 py-2.5 text-rustic-900 rounded-sm shadow-rustic-sm focus:border-retro-orange focus:outline-none">
        </div>

        <div>
            <label for="email" class="block font-heading uppercase tracking-wider text-xs text-rustic-900 font-black mb-1">
                Alamat Email <span class="text-brick">*</span>
            </label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="nama@email.com"
                   class="w-full bg-parchment border-2 border-rustic-900 font-body px-4 py-2.5 text-rustic-900 rounded-sm shadow-rustic-sm focus:border-retro-orange focus:outline-none">
            <span class="text-[11px] text-rustic-600 italic mt-0.5 block">Kode OTP verifikasi akan dikirimkan ke email ini.</span>
        </div>

        <div>
            <label for="phone" class="block font-heading uppercase tracking-wider text-xs text-rustic-900 font-black mb-1">
                Nomor WhatsApp / HP <span class="text-brick">*</span>
            </label>
            <input type="text"
                   name="phone"
                   id="phone"
                   value="{{ old('phone') }}"
                   required
                   placeholder="08xxxxxxxxxx"
                   class="w-full bg-parchment border-2 border-rustic-900 font-body px-4 py-2.5 text-rustic-900 rounded-sm shadow-rustic-sm focus:border-retro-orange focus:outline-none">
            <span class="text-[11px] text-rustic-600 italic mt-0.5 block">Wajib aktif untuk menerima kode verifikasi OTP.</span>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-black tracking-widest uppercase text-xs py-3.5 px-4 shadow-rustic transition duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none">
                DAFTAR & KIRIM OTP →
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
