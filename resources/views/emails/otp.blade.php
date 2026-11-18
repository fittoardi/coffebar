<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi CoffeBar</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #F4ECDD;
            color: #2B1D14;
            margin: 0;
            padding: 28px;
        }
        .container {
            max-width: 480px;
            margin: 0 auto;
            background-color: #FFFDF8;
            border: 1px dashed #CDB48F;
            border-radius: 16px;
            padding: 36px 28px;
            box-shadow: 0 8px 24px rgba(43, 29, 20, 0.08);
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #CDB48F;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .logo {
            font-size: 26px;
            font-weight: bold;
            color: #2B1D14;
            letter-spacing: -0.5px;
        }
        .divider {
            color: #B5753A;
            font-size: 14px;
            margin: 8px 0;
        }
        .content {
            text-align: center;
            line-height: 1.6;
        }
        .otp-box {
            display: inline-block;
            background-color: #2B1D14;
            color: #F4ECDD;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 16px 28px;
            border: 2px solid #B5753A;
            margin: 24px 0;
            border-radius: 12px;
        }
        .warning {
            font-size: 13px;
            color: #6B5646;
            margin-top: 16px;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: #6B5646;
            border-top: 1px dashed #CDB48F;
            padding-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">☕ COFFEBAR</div>
            <div class="divider">✦ ─────── ☕ ─────── ✦</div>
            <p style="margin: 4px 0 0; color: #B5753A; font-style: italic;">Seduhan Hangat & Cerita Autentik</p>
        </div>

        <div class="content">
            <h2 style="color: #2B1D14; margin-bottom: 8px; font-size: 20px;">Kode Verifikasi Anda</h2>
            <p style="color: #4A3224; font-size: 14px;">Gunakan kode OTP 6-digit di bawah ini untuk menyelesaikan proses pendaftaran atau masuk ke akun Anda:</p>

            <div class="otp-box">
                {{ $otpCode }}
            </div>

            <p class="warning">
                * Kode ini berlaku selama <strong>{{ $expiryMinutes }} menit</strong>.<br>
                Demi keamanan, jangan berikan kode ini kepada siapapun termasuk barista CoffeBar.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CoffeBar · Rasa Nusantara Diracik Untukmu
        </div>
    </div>
</body>
</html>
