<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi CoffeBar</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #F5E6C8;
            color: #3E200E;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #FFF8F0;
            border: 2px solid #B87333;
            border-radius: 4px;
            padding: 32px;
            box-shadow: 4px 4px 0px #B87333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #EDD9A3;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .logo {
            font-size: 26px;
            font-weight: bold;
            color: #3E1F0D;
            letter-spacing: 1px;
        }
        .divider {
            color: #B87333;
            font-size: 14px;
            margin: 8px 0;
        }
        .content {
            text-align: center;
            line-height: 1.6;
        }
        .otp-box {
            display: inline-block;
            background-color: #3E1F0D;
            color: #F5E6C8;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 16px 28px;
            border: 2px solid #B87333;
            margin: 24px 0;
            border-radius: 4px;
        }
        .warning {
            font-size: 13px;
            color: #7A5C45;
            font-style: italic;
            margin-top: 16px;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: #8B7D75;
            border-top: 1px dashed #D4A574;
            padding-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">☕ COFFEBAR</div>
            <div class="divider">❧ ─────── ✦ ─────── ❧</div>
            <p style="margin: 4px 0 0; color: #B87333; font-style: italic;">Seduhan Cerita & Kopi Pilihan</p>
        </div>

        <div class="content">
            <h2 style="color: #3E1F0D; margin-bottom: 8px;">Kode Verifikasi Anda</h2>
            <p>Gunakan kode OTP 6-digit di bawah ini untuk menyelesaikan proses pendaftaran atau masuk ke akun Anda:</p>

            <div class="otp-box">
                {{ $otpCode }}
            </div>

            <p class="warning">
                * Kode ini berlaku selama <strong>{{ $expiryMinutes }} menit</strong>.<br>
                Demi keamanan, jangan berikan kode ini kepada siapapun termasuk pihak CoffeBar.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CoffeBar. Semua hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
