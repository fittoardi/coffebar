@extends('layouts.auth')

@section('title', 'Verifikasi Kode OTP')

@section('content')
    <div class="mb-6 text-center">
        <span class="font-hand text-2xl text-[#B5753A]">Keamanan Akun</span>
        <h1 class="font-display text-2xl font-semibold text-[#2B1D14] mt-1">Verifikasi Kode</h1>
        <p class="text-xs text-[#6B5646] mt-1.5 leading-relaxed">
            Kode sudah meluncur. Cek email dan WhatsApp-mu ya:
        </p>
        <div class="font-bold text-[#B5753A] text-sm mt-1.5 select-all font-mono">
            {{ $identifier ?? 'Kontak Anda' }}
        </div>
    </div>

    <!-- OTP Form (03-DESIGN.md Section 16.2) -->
    <form method="POST" action="{{ route('otp.verify') }}" id="otp-form" class="space-y-6">
        @csrf
        <input type="hidden" name="identifier" value="{{ $identifier }}">

        <!-- Combined Hidden Input that holds the 6 digits -->
        <input type="hidden" name="code" id="final-otp-code">

        <!-- 6 OTP Input Boxes (52x60px, Fraunces 28px, Kraft border, Caramel focus) -->
        <div class="flex justify-center items-center gap-2 sm:gap-2.5" id="otp-inputs-container">
            @for ($i = 0; $i < 6; $i++)
                <input type="text"
                       maxlength="1"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       autocomplete="one-time-code"
                       data-index="{{ $i }}"
                       class="otp-digit w-12 sm:w-[52px] h-[60px] text-center font-display text-2xl sm:text-[28px] font-bold bg-[#FFFDF8] border border-[#CDB48F] rounded-xl focus:border-[#B5753A] focus:ring-2 focus:ring-[#B5753A]/30 focus:outline-none transition text-[#2B1D14]"
                       {{ $i === 0 ? 'autofocus' : '' }}>
                @if ($i === 2)
                    <span class="text-[#CDB48F] font-bold text-xl select-none">·</span>
                @endif
            @endfor
        </div>

        <div>
            <button type="submit"
                    id="btn-verify"
                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] font-bold text-xs uppercase tracking-wider py-3.5 px-4 rounded-xl transition shadow-warm cursor-pointer">
                Verifikasi & Masuk
            </button>
        </div>
    </form>

    <!-- Resend OTP Section (03-DESIGN.md Section 16.2) -->
    <div class="mt-6 pt-5 border-t border-dashed border-[#CDB48F] text-center text-xs text-[#6B5646]">
        <p class="mb-2">
            Tidak menerima kode verifikasi?
        </p>

        <form method="POST" action="{{ route('otp.resend') }}" class="inline">
            @csrf
            <input type="hidden" name="identifier" value="{{ $identifier }}">
            <button type="submit"
                    id="btn-resend"
                    class="text-[#B5753A] hover:text-[#B4552F] font-bold">
                Kirim Ulang Kode (<span id="countdown" class="font-mono">60</span>s)
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="text-xs text-[#6B5646] hover:text-[#2B1D14] underline underline-offset-2">
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
