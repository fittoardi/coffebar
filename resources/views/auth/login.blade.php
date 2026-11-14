@extends('layouts.auth')

@section('title', 'Masuk Akun')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="font-display font-bold text-2xl text-rustic-900">Selamat Datang</h1>
        <p class="font-body italic text-sm text-rustic-600 mt-1">
            Masuk untuk mengakses pesanan dan fitur kedai
        </p>
    </div>

    <!-- Tab Selection between OTP (Customer) and Password (Internal Staff) -->
    <div class="flex border-2 border-rustic-300 rounded-sm mb-5 text-xs font-heading uppercase tracking-wider overflow-hidden" id="login-tabs">
        <button type="button"
                id="tab-otp-btn"
                onclick="switchTab('otp')"
                class="flex-1 py-2.5 px-3 bg-rustic-800 text-parchment text-center transition font-bold">
            Pelanggan (OTP)
        </button>
        <button type="button"
                id="tab-password-btn"
                onclick="switchTab('password')"
                class="flex-1 py-2.5 px-3 bg-parchment text-rustic-800 text-center transition hover:bg-rustic-200">
            Kasir / Admin
        </button>
    </div>

    <!-- Form 1: Customer OTP Login -->
    <form method="POST" action="{{ route('login') }}" id="form-otp" class="space-y-4">
        @csrf

        <div>
            <label for="identifier_otp" class="block font-body italic text-sm text-rustic-700 mb-1">
                Email atau Nomor WhatsApp / HP
            </label>
            <input type="text"
                   name="identifier"
                   id="identifier_otp"
                   value="{{ old('identifier') }}"
                   required
                   autofocus
                   placeholder="Contoh: 0812xxxx atau user@email.com"
                   class="w-full bg-parchment border-2 border-rustic-400 font-body px-4 py-2.5 text-rustic-900 rounded-sm focus:border-copper focus:outline-none focus:ring-1 focus:ring-copper">
            <span class="text-xs text-rustic-500 italic mt-0.5 block">Kode verifikasi OTP akan dikirimkan ke nomor / email ini.</span>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading tracking-widest uppercase text-sm py-3 px-4 shadow-rustic transition duration-150 active:translate-x-0.5 active:translate-y-0.5">
                KIRIM KODE OTP
            </button>
        </div>
    </form>

    <!-- Form 2: Password Login (Staff / Admin) -->
    <form method="POST" action="{{ route('login') }}" id="form-password" class="space-y-4 hidden">
        @csrf

        <div>
            <label for="identifier_pwd" class="block font-body italic text-sm text-rustic-700 mb-1">
                Email Karyawan / Admin
            </label>
            <input type="text"
                   name="identifier"
                   id="identifier_pwd"
                   placeholder="admin@coffebar.id"
                   class="w-full bg-parchment border-2 border-rustic-400 font-body px-4 py-2.5 text-rustic-900 rounded-sm focus:border-copper focus:outline-none focus:ring-1 focus:ring-copper">
        </div>

        <div>
            <label for="password" class="block font-body italic text-sm text-rustic-700 mb-1">
                Kata Sandi
            </label>
            <input type="password"
                   name="password"
                   id="password"
                   placeholder="••••••••"
                   class="w-full bg-parchment border-2 border-rustic-400 font-body px-4 py-2.5 text-rustic-900 rounded-sm focus:border-copper focus:outline-none focus:ring-1 focus:ring-copper">
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-copper text-rustic-900 border-2 border-rustic-700 hover:bg-copper-light font-heading tracking-widest uppercase text-sm py-3 px-4 shadow-rustic transition duration-150 active:translate-x-0.5 active:translate-y-0.5 font-bold">
                MASUK DENGAN SANDI
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-rustic-200 text-center text-sm font-body">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="font-heading text-copper hover:underline font-bold ml-1">
            Daftar Sekarang
        </a>
    </div>

    <script>
        function switchTab(mode) {
            const formOtp = document.getElementById('form-otp');
            const formPwd = document.getElementById('form-password');
            const btnOtp = document.getElementById('tab-otp-btn');
            const btnPwd = document.getElementById('tab-password-btn');

            if (mode === 'password') {
                formOtp.classList.add('hidden');
                formPwd.classList.remove('hidden');
                btnPwd.classList.remove('bg-parchment', 'text-rustic-800');
                btnPwd.classList.add('bg-rustic-800', 'text-parchment', 'font-bold');
                btnOtp.classList.remove('bg-rustic-800', 'text-parchment', 'font-bold');
                btnOtp.classList.add('bg-parchment', 'text-rustic-800');
                document.getElementById('identifier_pwd').focus();
            } else {
                formPwd.classList.add('hidden');
                formOtp.classList.remove('hidden');
                btnOtp.classList.remove('bg-parchment', 'text-rustic-800');
                btnOtp.classList.add('bg-rustic-800', 'text-parchment', 'font-bold');
                btnPwd.classList.remove('bg-rustic-800', 'text-parchment', 'font-bold');
                btnPwd.classList.add('bg-parchment', 'text-rustic-800');
                document.getElementById('identifier_otp').focus();
            }
        }
    </script>
@endsection
