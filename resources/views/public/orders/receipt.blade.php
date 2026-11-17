<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket & Struk Pesanan {{ $order->order_code }} — CoffeBar</title>

    <!-- Google Fonts: Retro Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Lora', Georgia, serif;
            background-color: #EFE4D3;
            color: #1B0F07;
            padding: 30px 15px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .ticket-wrapper {
            position: relative;
            background: #FBF4E6;
            width: 360px;
            max-width: 100%;
            border: 2px solid #1B0F07;
            box-shadow: 6px 6px 0px #1B0F07;
            padding: 28px 24px;
            border-radius: 4px;
        }
        /* Top Ticket Perforation */
        .ticket-perforation-top {
            border-bottom: 2px dashed #CF5328;
            padding-bottom: 16px;
            margin-bottom: 18px;
            text-align: center;
        }
        /* Middle Ticket Cutout Notches */
        .ticket-notch-left, .ticket-notch-right {
            position: absolute;
            top: 55%;
            width: 20px;
            height: 20px;
            background: #EFE4D3;
            border-radius: 50%;
            border: 2px solid #1B0F07;
            z-index: 10;
        }
        .ticket-notch-left {
            left: -11px;
            clip-path: polygon(50% 0%, 100% 0%, 100% 100%, 50% 100%);
        }
        .ticket-notch-right {
            right: -11px;
            clip-path: polygon(0% 0%, 50% 0%, 50% 100%, 0% 100%);
        }
        .brand-header {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #1B0F07;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .badge-estd {
            display: inline-block;
            font-family: 'Libre Baskerville', Georgia, serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: #E2A63B;
            color: #1B0F07;
            padding: 2px 8px;
            border: 1px solid #1B0F07;
            margin-top: 4px;
        }
        .meta-text {
            font-size: 11px;
            color: #5C2B14;
            margin-top: 4px;
        }
        .order-code-display {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #CF5328;
            margin: 8px 0;
            text-align: center;
        }
        .qr-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 14px 0;
        }
        .qr-box {
            background: #FFF;
            padding: 10px;
            border: 2px solid #1B0F07;
            box-shadow: 3px 3px 0px #1B0F07;
            display: inline-block;
        }
        .qr-box svg {
            width: 140px;
            height: 140px;
            display: block;
        }
        .dashed-line {
            border-top: 2px dashed #CF5328;
            margin: 16px 0;
        }
        .double-line {
            border-top: 3px double #1B0F07;
            margin: 14px 0;
        }
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .item-row {
            margin-bottom: 10px;
        }
        .item-name {
            font-weight: 700;
            font-size: 13px;
            color: #1B0F07;
        }
        .item-options {
            font-size: 11px;
            color: #7A5C45;
            font-style: italic;
            margin-top: 2px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 18px;
            font-weight: 900;
            color: #1B0F07;
            padding-top: 6px;
        }
        .total-amount {
            color: #CF5328;
            font-size: 22px;
        }
        .barista-stamp {
            border: 2px solid #CF5328;
            color: #CF5328;
            font-family: 'Libre Baskerville', Georgia, serif;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 1px;
            padding: 6px 12px;
            display: inline-block;
            transform: rotate(-3deg);
            margin-top: 14px;
        }
        .btn-print {
            margin-top: 24px;
            padding: 12px 28px;
            background: #CF5328;
            color: #FBF4E6;
            border: 2px solid #1B0F07;
            box-shadow: 4px 4px 0px #1B0F07;
            cursor: pointer;
            font-family: 'Libre Baskerville', Georgia, serif;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.15s ease;
        }
        .btn-print:hover {
            background: #E2A63B;
            color: #1B0F07;
        }
        .btn-print:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px #1B0F07;
        }
        @media print {
            body {
                background: #FFF;
                padding: 0;
            }
            .ticket-wrapper {
                box-shadow: none;
                border: 1px dashed #000;
                width: 100%;
                padding: 15px;
            }
            .ticket-notch-left, .ticket-notch-right, .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    @php
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->color(27, 15, 7)->generate($order->order_code);
    @endphp

    <div class="ticket-wrapper">
        <div class="ticket-notch-left"></div>
        <div class="ticket-notch-right"></div>

        <!-- Header -->
        <div class="ticket-perforation-top">
            <div class="brand-header">
                <span>☕</span>
                <span>COFFEBAR</span>
            </div>
            <div class="badge-estd">★ ESTD 2026 ★ ARTISAN ROASTERY</div>
            <div class="meta-text">Jl. Kopi No. 12, Senopati, Kebayoran Baru, Jakarta Selatan</div>
            <div class="meta-text">Telp/WA: 0812-3456-7890 • www.coffebar.id</div>
        </div>

        <!-- Order Code & QR Code -->
        <div style="text-align: center;">
            <div style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #5C2B14;">
                TIKET & KODE PESANAN RESMI
            </div>
            <div class="order-code-display">
                {{ $order->order_code }}
            </div>
            
            <div class="qr-container">
                <div class="qr-box">
                    {!! $qrCodeSvg !!}
                </div>
            </div>
            <div style="font-size: 10px; color: #7A5C45; font-style: italic;">
                Tunjukkan QR code ini ke kasir atau barista saat pengambilan
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Order Metadata -->
        <div style="margin-bottom: 12px;">
            <div class="flex-between">
                <span style="color: #5C2B14;">Tanggal & Waktu:</span>
                <span style="font-weight: 700;">{{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
            </div>
            <div class="flex-between">
                <span style="color: #5C2B14;">Nama Pelanggan:</span>
                <span style="font-weight: 700;">{{ $order->customer_name }}</span>
            </div>
            <div class="flex-between">
                <span style="color: #5C2B14;">Petugas Kasir:</span>
                <span>{{ $order->cashier ? $order->cashier->name : 'Sistem Mandiri Web' }}</span>
            </div>
            <div class="flex-between">
                <span style="color: #5C2B14;">Metode & Status:</span>
                <span style="font-weight: 700; text-transform: uppercase;">
                    {{ $order->payment_method === 'cash' ? 'Bayar di Kasir' : 'Online QRIS' }}
                    ({{ $order->payment_status === 'paid' ? 'LUNAS' : 'MENUNGGU' }})
                </span>
            </div>
        </div>

        <div class="double-line"></div>

        <!-- Items Detail -->
        <div>
            @foreach ($order->items as $item)
                <div class="item-row">
                    <div class="flex-between">
                        <span class="item-name">{{ $item->name }}</span>
                        <span style="font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex-between">
                        <span style="font-size: 11px; color: #5C2B14;">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                    @if (! empty($item->options))
                        <div class="item-options">
                            {{ implode(' • ', array_filter([
                                $item->options['size'] ?? null,
                                $item->options['sugar'] ?? null,
                                $item->options['ice'] ?? null,
                                !empty($item->options['toppings']) ? implode(', ', (array)$item->options['toppings']) : null,
                            ])) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="double-line"></div>

        <!-- Grand Total -->
        <div class="total-row">
            <span>TOTAL TAGIHAN:</span>
            <span class="total-amount">{{ $order->formatted_total }}</span>
        </div>

        @if (! empty($order->notes))
            <div style="margin-top: 10px; font-size: 11px; color: #CF5328; font-style: italic; background: #F3E5CF; padding: 6px 10px; border-left: 3px solid #CF5328;">
                Catatan: "{{ $order->notes }}"
            </div>
        @endif

        <div class="dashed-line"></div>

        <!-- Footer Seal Stamp & Signature -->
        <div style="text-align: center; margin-top: 14px;">
            <div class="barista-stamp">
                ★ DISEDUR DENGAN CINTA & TRADISI ★
            </div>
            <div style="margin-top: 14px; font-size: 11px; color: #5C2B14; font-style: italic;">
                "Secangkir kopi hangat, sejuta cerita bermakna."
            </div>
            <div style="font-size: 10px; color: #7A5C45; margin-top: 6px;">
                Terima kasih atas kunjungan Anda ke CoffeBar!
            </div>
        </div>
    </div>

    <!-- Print Action -->
    <div style="text-align: center;">
        <button onclick="window.print()" class="btn-print">
            🖨 Cetak Struk (Print)
        </button>
    </div>

</body>
</html>
