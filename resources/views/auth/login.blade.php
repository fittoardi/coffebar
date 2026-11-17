@extends('layouts.auth')

@section('title', 'Masuk Akun Pelanggan')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-rustic-800 text-copper font-heading uppercase text-[10px] tracking-widest px-2.5 py-0.5 border border-copper mb-2">
            ☕ Pelanggan (OTP)
        </span>
        <h1 class="font-display font-bold text-2xl text-rustic-900">Masuk Akun Pelanggan</h1>
        <p class="font-body italic text-xs text-rustic-600 mt-1">
            Masuk untuk memesan kopi favorit, meracik kreasi menu, dan melihat tiket pesanan Anda.
        </p>
    </div>

    <!-- Form: Customer OTP Login -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="identifier" class="block font-heading uppercase tracking-wider text-xs text-rustic-900 font-black mb-1">
                Email atau Nomor WhatsApp / HP <span class="text-brick">*</span>
            </label>
            <input type="text"
                   name="identifier"
                   id="identifier"
                   value="{{ old('identifier') }}"
                   required
                   autofocus
                   placeholder="Contoh: 08123456789 atau nama@email.com"
                   class="w-full bg-parchment border-2 border-rustic-900 font-body px-4 py-2.5 text-rustic-900 rounded-sm shadow-rustic-sm focus:border-retro-orange focus:outline-none">
            <span class="text-[11px] text-rustic-600 italic mt-1 block">
                Kode verifikasi OTP 6-digit akan dikirimkan langsung ke kontak ini.
            </span>
        </div>

        <div class="p-3 bg-parchment border-2 border-dashed border-rustic-400 rounded-xs text-xs text-rustic-800 space-y-1">
            <div class="font-heading uppercase font-black text-retro-orange text-[10px] tracking-wider">
                🔒 Tanpa Kata Sandi
            </div>
            <p class="font-body text-[11px] leading-relaxed">
                Anda tidak perlu mengingat kata sandi rumit. Cukup masukkan email atau WhatsApp terdaftar dan sistem akan mengirimkan kode login instan.
            </p>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-black tracking-widest uppercase text-xs py-3.5 px-4 shadow-rustic transition duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none">
                KIRIM KODE OTP MASUK →
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-rustic-200 text-center text-xs font-body">
        Belum memiliki akun pelanggan?
        <a href="{{ route('register') }}" class="font-heading text-copper hover:underline font-bold ml-1">
            Daftar Sekarang
        </a>
    </div>

    <!-- Portal Switch for Staff & Admin -->
    <div class="mt-6 pt-4 border-t-2 border-dashed border-rustic-300 text-center">
        <span class="text-[11px] text-rustic-500 font-body block mb-1">Petugas Kasir, Barista, atau Admin Kedai?</span>
        <a href="{{ route('staff.login') }}"
           class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-rustic-100 border border-copper text-copper font-heading uppercase text-[10px] tracking-wider font-bold hover:bg-copper hover:text-rustic-900 transition">
            <span>🔐</span>
            <span>Masuk ke Portal Internal Staf →</span>
        </a>
    </div>
@endsection
