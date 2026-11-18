@extends('layouts.auth')

@section('title', 'Masuk Akun Pelanggan')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-[#E8DCC4] border border-[#CDB48F] text-[#4A3224] text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-3">
            Pelanggan (OTP)
        </span>
        <h1 class="font-display text-2xl font-semibold text-[#2B1D14]">Masuk Akun Pelanggan</h1>
        <p class="text-xs text-[#6B5646] mt-1.5 leading-relaxed">
            Masuk untuk memesan kopi favorit, meracik menu, dan melihat pesanan Anda.
        </p>
    </div>

    <!-- Form: Customer OTP Login (03-DESIGN.md Section 16) -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="identifier" class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                Email atau Nomor WhatsApp / HP <span class="text-[#B4552F]">*</span>
            </label>
            <input type="text"
                   name="identifier"
                   id="identifier"
                   value="{{ old('identifier') }}"
                   required
                   autofocus
                   placeholder="Contoh: 08123456789 atau nama@email.com"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
            <span class="text-[11px] text-[#6B5646] mt-1.5 block">
                Kode verifikasi OTP 6-digit akan dikirimkan langsung ke kontak ini.
            </span>
        </div>

        <div class="p-3.5 bg-[#E8DCC4]/60 border border-[#CDB48F] rounded-xl text-xs text-[#4A3224] space-y-1">
            <div class="font-bold flex items-center space-x-1.5 text-[#2B1D14]">
                <span>🔒</span>
                <span>Tanpa Kata Sandi</span>
            </div>
            <p class="leading-relaxed text-[11px] text-[#6B5646]">
                Anda tidak perlu mengingat kata sandi. Cukup masukkan email atau WhatsApp terdaftar dan sistem akan mengirimkan kode login.
            </p>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-3.5 px-4 rounded-xl transition shadow-warm cursor-pointer">
                Kirim Kode OTP Masuk
            </button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-dashed border-[#CDB48F] text-center text-xs text-[#6B5646]">
        Belum memiliki akun pelanggan?
        <a href="{{ route('register') }}" class="text-[#B5753A] hover:text-[#B4552F] font-bold ml-1">
            Daftar Sekarang
        </a>
    </div>

    <div class="mt-4 text-center text-xs">
        <a href="{{ route('staff.login') }}" class="text-[#6B5646] hover:text-[#2B1D14] underline underline-offset-2">
            Masuk sebagai Staf Internal / Kasir →
        </a>
    </div>
@endsection
