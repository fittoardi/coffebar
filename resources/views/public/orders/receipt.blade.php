<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan {{ $order->order_code }} — CoffeBar</title>

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f3f4f6;
            color: #111827;
            padding: 30px 15px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .receipt-card {
            background: #ffffff;
            width: 360px;
            max-width: 100%;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            padding: 24px;
            border-radius: 8px;
        }
        .brand-header {
            font-size: 20px;
            font-weight: 700;
            text-align: center;
            color: #111827;
        }
        .meta-text {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            margin-top: 4px;
        }
        .order-code-display {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #2563eb;
            text-align: center;
            margin: 12px 0 4px 0;
        }
        .divider {
            border-top: 1px dashed #d1d5db;
            margin: 14px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .info-label {
            color: #6b7280;
        }
        .info-value {
            font-weight: 600;
            color: #111827;
        }
        .item-row {
            margin-bottom: 10px;
            font-size: 13px;
        }
        .item-main {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
        }
        .item-sub {
            font-size: 11px;
            color: #6b7280;
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
            font-size: 18px;
            color: #111827;
        }
        .btn-print {
            margin-top: 20px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                border: none;
                width: 100%;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <!-- Brand Header -->
        <div class="brand-header">
            ☕ COFFEBAR
        </div>
        <div class="meta-text">
            Jl. Senopati No. 88, Jakarta Selatan<br>
            Telp / WA: 0812-3456-7890
        </div>

        <div class="divider"></div>

        <!-- Order Code -->
        <div style="text-align: center;">
            <div style="font-size: 11px; color: #6b7280; text-transform: uppercase;">Kode Pesanan</div>
            <div class="order-code-display">{{ $order->order_code }}</div>
            <div style="font-size: 11px; color: #6b7280;">
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
                    LUNAS ({{ ucfirst($order->payment?->payment_method ?? 'Kasir') }})
                @else
                    MENUNGGU PEMBAYARAN
                @endif
            </span>
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <div style="margin: 12px 0;">
            @foreach ($order->items as $item)
                <div class="item-row">
                    <div class="item-main">
                        <span>{{ $item->name }} × {{ $item->quantity }}</span>
                        <span>{{ $item->formatted_subtotal }}</span>
                    </div>
                    @if (! empty($item->options))
                        <div class="item-sub">
                            {{ implode(' | ', array_filter([
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
            <span>TOTAL:</span>
            <span class="total-amount">{{ $order->formatted_total }}</span>
        </div>

        @if (! empty($order->notes))
            <div style="margin-top: 10px; font-size: 11px; color: #2563eb; background: #eff6ff; padding: 6px 10px; border-radius: 4px;">
                Catatan: "{{ $order->notes }}"
            </div>
        @endif

        <div class="divider"></div>

        <div style="text-align: center; font-size: 11px; color: #6b7280; margin-top: 12px;">
            Terima kasih atas kunjungan Anda ke CoffeBar!
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
