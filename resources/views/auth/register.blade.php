@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="font-display text-2xl font-semibold text-[#2B1D14]">Pendaftaran Pelanggan</h1>
        <p class="text-xs text-[#6B5646] mt-1.5 leading-relaxed">
            Daftar untuk mulai memesan dan meracik minuman kustom Anda.
        </p>
    </div>

    <!-- Registration Form (03-DESIGN.md Section 16.1) -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                Nama Lengkap <span class="text-[#B4552F]">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   placeholder="Contoh: Ahmad Budiman"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                Alamat Email <span class="text-[#B4552F]">*</span>
            </label>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="nama@email.com"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
            <span class="text-[11px] text-[#6B5646] mt-1 block">Kode OTP verifikasi akan dikirimkan ke email ini.</span>
        </div>

        <div>
            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-[#4A3224] mb-1.5">
                Nomor WhatsApp / HP <span class="text-[#B4552F]">*</span>
            </label>
            <input type="text"
                   name="phone"
                   id="phone"
                   value="{{ old('phone') }}"
                   required
                   placeholder="08xxxxxxxxxx"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
            <span class="text-[11px] text-[#6B5646] mt-1 block">Wajib aktif untuk menerima kode verifikasi OTP.</span>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-3.5 px-4 rounded-xl transition shadow-warm cursor-pointer">
                Daftar & Kirim OTP
            </button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-dashed border-[#CDB48F] text-center text-xs text-[#6B5646]">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="text-[#B5753A] hover:text-[#B4552F] font-bold ml-1">
            Masuk Sekarang
        </a>
    </div>
@endsection
