@extends('layouts.auth')

@section('title', 'Masuk Akun')

@section('content')
    <div class="mb-6 text-center">
        <span class="inline-block bg-[#E8DCC4] border border-[#CDB48F] text-[#4A3224] text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-3">
            Pelanggan & Anggota
        </span>
        <h1 class="font-display text-2xl font-semibold text-[#2B1D14]">Masuk ke Akun</h1>
        <p class="text-xs text-[#6B5646] mt-1.5 leading-relaxed">
            Masuk dengan email atau nomor HP dan kata sandi Anda.
        </p>
    </div>

    <!-- Form: Password-based Login -->
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
                   placeholder="nama@email.com atau 08xxxxxxxxxx"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
            @error('identifier')
                <span class="text-xs text-[#B4552F] mt-1 block">{{ $message }}</span>
            @enderror
        </div>

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
                   placeholder="Masukkan kata sandi akun Anda"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-4 py-2.5 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none focus:ring-1 focus:ring-[#B5753A]">
            @error('password')
                <span class="text-xs text-[#B4552F] mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center space-x-2 cursor-pointer text-[#4A3224]">
                <input type="checkbox"
                       name="remember"
                       value="1"
                       class="rounded border-[#CDB48F] text-[#B5753A] focus:ring-[#B5753A]">
                <span>Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-3.5 px-4 rounded-xl transition shadow-warm cursor-pointer">
                Masuk ke Akun
            </button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-dashed border-[#CDB48F] text-center text-xs text-[#6B5646]">
        Belum memiliki akun pelanggan?
        <a href="{{ route('register') }}" class="text-[#B5753A] hover:text-[#B4552F] font-bold ml-1">
            Daftar Sekarang (Verifikasi OTP)
        </a>
    </div>

    <div class="mt-4 text-center text-xs">
        <a href="{{ route('staff.login') }}" class="text-[#6B5646] hover:text-[#2B1D14] underline underline-offset-2">
            Masuk sebagai Staf Internal / Kasir →
        </a>
    </div>
@endsection
