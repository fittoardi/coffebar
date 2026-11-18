<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan {{ $order->order_code }} — CoffeBar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,400..700,50&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background-color: #F4ECDD;
            color: #2B1D14;
            padding: 40px 15px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .receipt-card {
            background: #FFFDF8;
            width: 380px;
            max-width: 100%;
            border: 1px dashed #CDB48F;
            box-shadow: 0 8px 24px rgba(43, 29, 20, 0.12);
            padding: 32px 24px;
            border-radius: 16px;
            position: relative;
        }
        .brand-header {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            color: #2B1D14;
            letter-spacing: -0.5px;
        }
        .meta-text {
            font-size: 11px;
            color: #6B5646;
            text-align: center;
            margin-top: 4px;
            line-height: 1.5;
        }
        .order-code-display {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #B5753A;
            text-align: center;
            margin: 12px 0 4px 0;
        }
        .divider {
            border-top: 1px dashed #CDB48F;
            margin: 16px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .info-label {
            color: #6B5646;
        }
        .info-value {
            font-weight: 600;
            color: #2B1D14;
        }
        .item-row {
            margin-bottom: 12px;
            font-size: 13px;
        }
        .item-main {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            color: #2B1D14;
        }
        .item-sub {
            font-size: 11px;
            color: #6B5646;
            margin-top: 2px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-weight: 700;
            margin-top: 10px;
        }
        .total-amount {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 22px;
            color: #2B1D14;
        }
        .btn-print {
            margin-top: 24px;
            background: #B5753A;
            color: #FFFDF8;
            border: none;
            padding: 12px 28px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(181, 117, 58, 0.3);
            transition: all 0.2s ease;
        }
        .btn-print:hover {
            background: #B4552F;
            transform: translateY(-1px);
        }
        .handwritten-footer {
            font-family: 'Caveat', cursive;
            font-size: 20px;
            color: #4A3224;
            text-align: center;
            margin-top: 16px;
        }
        @media print {
            body {
                background: #FFFDF8;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                border: 1px dashed #000;
                width: 100%;
                border-radius: 0;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <!-- Brand Header (03-DESIGN.md Section 17.4) -->
        <div class="brand-header">
            ☕ COFFEBAR
        </div>
        <div class="meta-text">
            Jl. Senopati No. 88, Jakarta Selatan<br>
            Kedai Kopi Autentik Nusantara · Telp: 0812-3456-7890
        </div>

        <div class="divider"></div>

        <!-- Order Code -->
        <div style="text-align: center;">
            <div style="font-size: 10px; color: #6B5646; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Kode Pesanan</div>
            <div class="order-code-display">{{ $order->order_code }}</div>
            <div style="font-size: 11px; color: #6B5646;">
                {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>

        <div class="divider"></div>

        <!-- Order Meta -->
        <div class="info-row">
            <span class="info-label">Pelanggan:</span>
            <span class="info-value">{{ $order->customer_name ?: ($order->user?->name ?? 'Pelanggan Walk-In') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tipe Pesanan:</span>
            <span class="info-value">{{ $order->source === 'online' ? 'Online Order' : 'Kasir / Dine-In' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status Bayar:</span>
            <span class="info-value">
                @if ($order->payment_status === 'paid')
                    <span style="color: #7A8467;">LUNAS ({{ ucfirst($order->payment?->payment_method ?? 'Kasir') }})</span>
                @else
                    <span style="color: #B4552F;">MENUNGGU PEMBAYARAN</span>
                @endif
            </span>
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <div style="margin: 14px 0;">
            @foreach ($order->items as $item)
                <div class="item-row">
                    <div class="item-main">
                        <span>{{ $item->name }} × {{ $item->quantity }}</span>
                        <span>{{ $item->formatted_subtotal }}</span>
                    </div>
                    @if (! empty($item->options))
                        <div class="item-sub">
                            {{ implode(' · ', array_filter([
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

        <div class="divider"></div>

        <!-- Grand Total -->
        <div class="total-row">
            <span style="color: #4A3224; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">TOTAL:</span>
            <span class="total-amount">{{ $order->formatted_total }}</span>
        </div>

        @if (! empty($order->notes))
            <div style="margin-top: 12px; font-size: 11px; color: #4A3224; background: #E8DCC4; padding: 8px 12px; border-radius: 8px; border: 1px solid #CDB48F;">
                Catatan: "{{ $order->notes }}"
            </div>
        @endif

        <div class="divider"></div>

        <div class="handwritten-footer">
            "Terima kasih atas kehangatan obrolanmu di CoffeBar!"
        </div>
    </div>

    <!-- Print Action -->
    <div style="text-align: center;">
        <button onclick="window.print()" class="btn-print">
            Cetak Struk
        </button>
    </div>

</body>
</html>
