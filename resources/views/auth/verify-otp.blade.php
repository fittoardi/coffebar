@extends('layouts.auth')

@section('title', 'Verifikasi Kode OTP')

@section('content')
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-gray-900">Verifikasi Kode</h1>
        <p class="text-xs text-gray-500 mt-1">
            Kami telah mengirimkan 6 digit kode verifikasi ke:
        </p>
        <div class="font-semibold text-blue-600 text-sm mt-1 select-all">
            {{ $identifier ?? 'Kontak Anda' }}
        </div>
    </div>

    <form method="POST" action="{{ route('otp.verify') }}" id="otp-form" class="space-y-6">
        @csrf
        <input type="hidden" name="identifier" value="{{ $identifier }}">

        <!-- Combined Hidden Input that holds the 6 digits -->
        <input type="hidden" name="code" id="final-otp-code">

        <!-- 6 OTP Input Boxes -->
        <div class="flex justify-center items-center gap-2 sm:gap-3" id="otp-inputs-container">
            @for ($i = 0; $i < 6; $i++)
                <input type="text"
                       maxlength="1"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       autocomplete="one-time-code"
                       data-index="{{ $i }}"
                       class="otp-digit w-10 sm:w-12 h-14 text-center text-2xl font-bold bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none transition text-gray-900"
                       {{ $i === 0 ? 'autofocus' : '' }}>
                @if ($i === 2)
                    <span class="text-gray-400 font-bold text-lg select-none">—</span>
                @endif
            @endfor
        </div>

        <div>
            <button type="submit"
                    id="btn-verify"
                    class="w-full bg-blue-600 text-white hover:bg-blue-700 font-medium text-xs uppercase tracking-wider py-2.5 px-4 rounded-md transition cursor-pointer">
                Verifikasi & Masuk
            </button>
        </div>
    </form>

    <!-- Resend OTP Section -->
    <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs text-gray-500">
        <p class="mb-2">
            Tidak menerima kode verifikasi?
        </p>

        <form method="POST" action="{{ route('otp.resend') }}" class="inline">
            @csrf
            <input type="hidden" name="identifier" value="{{ $identifier }}">
            <button type="submit"
                    id="btn-resend"
                    class="text-blue-600 hover:underline font-semibold">
                Kirim Ulang Kode (<span id="countdown">60</span>s)
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-gray-600">
                ← Kembali ke Halaman Masuk
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.otp-digit');
            const hiddenCode = document.getElementById('final-otp-code');
            const form = document.getElementById('otp-form');

            // Handle Input & Navigation
            inputs.forEach((input, index) => {
                input.addEventListener('input', function (e) {
                    const value = e.target.value;

                    // Allow only digits
                    if (!/^\d*$/.test(value)) {
                        input.value = '';
                        return;
                    }

                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    updateHiddenCode();
                });

                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Support Paste
                input.addEventListener('paste', function (e) {
                    e.preventDefault();
                    const pastedData = (e.clipboardData || window.clipboardData).getData('text').trim();
                    if (/^\d{6}$/.test(pastedData)) {
                        pastedData.split('').forEach((char, i) => {
                            if (inputs[i]) {
                                inputs[i].value = char;
                            }
                        });
                        inputs[5].focus();
                        updateHiddenCode();
                    }
                });
            });

            function updateHiddenCode() {
                let code = '';
                inputs.forEach(input => code += input.value);
                hiddenCode.value = code;
            }

            form.addEventListener('submit', function (e) {
                updateHiddenCode();
                if (hiddenCode.value.length < 6) {
                    e.preventDefault();
                    alert('Harap masukkan 6 digit kode OTP secara lengkap.');
                }
            });

            // Countdown for Resend Button
            let timeLeft = 60;
            const resendBtn = document.getElementById('btn-resend');
            const countdownSpan = document.getElementById('countdown');

            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');

            const timer = setInterval(() => {
                timeLeft--;
                countdownSpan.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    resendBtn.innerHTML = 'KIRIM ULANG SEKARANG';
                }
            }, 1000);
        });
    </script>
@endsection
