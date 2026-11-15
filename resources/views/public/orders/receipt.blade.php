<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran {{ $order->order_code }} — CoffeBar</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f7f7f7;
            color: #222;
            padding: 20px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .receipt-card {
            background: #fff;
            padding: 24px;
            width: 320px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            font-size: 13px;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider {
            border-top: 1px dashed #555;
            margin: 10px 0;
        }
        .double-divider {
            border-top: 2px solid #333;
            margin: 10px 0;
        }
        .flex {
            display: flex;
            justify-content: space-between;
        }
        .bold { font-weight: bold; }
        .btn-print {
            margin-top: 20px;
            padding: 10px 20px;
            background: #3E1F0D;
            color: #F5E6C8;
            border: 2px solid #B87333;
            cursor: pointer;
            font-family: inherit;
            font-weight: bold;
            text-transform: uppercase;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-card { box-shadow: none; border: none; width: 100%; padding: 0; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="text-center">
            <div style="font-size: 18px; font-weight: bold;">☕ COFFEBAR</div>
            <div>Jl. Kopi No. 12, Senopati</div>
            <div>Telp: 0812-3456-7890</div>
            <div>Jakarta Selatan</div>
        </div>

        <div class="divider"></div>

        <div>
            <div class="flex">
                <span>No. Pesanan:</span>
                <span class="bold">{{ $order->order_code }}</span>
            </div>
            <div class="flex">
                <span>Tanggal:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex">
                <span>Kasir / Petugas:</span>
                <span>{{ $order->cashier ? $order->cashier->name : 'Sistem Online' }}</span>
            </div>
            <div class="flex">
                <span>Pelanggan:</span>
                <span>{{ $order->customer_name }}</span>
            </div>
        </div>

        <div class="double-divider"></div>

        <div>
            @foreach ($order->items as $item)
                <div style="margin-bottom: 6px;">
                    <div class="bold">{{ $item->name }}</div>
                    <div class="flex">
                        <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="bold">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if (! empty($item->options))
                        <div style="font-size: 11px; color: #555;">
                            {{ implode(', ', array_filter([
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

        <div class="double-divider"></div>

        <div>
            <div class="flex" style="font-size: 15px; font-weight: bold;">
                <span>TOTAL:</span>
                <span>{{ $order->formatted_total }}</span>
            </div>
            <div class="flex">
                <span>Metode Bayar:</span>
                <span>{{ strtoupper($order->payment_method) }}</span>
            </div>
            <div class="flex">
                <span>Status Bayar:</span>
                <span class="bold">{{ strtoupper($order->payment_status) }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 12px; font-size: 12px;">
            <div>Terima kasih atas kunjungan Anda!</div>
            <div>Seduhan Cerita & Kopi Autentik</div>
            <div>www.coffebar.id</div>
        </div>
    </div>

    <button onclick="window.print()" class="btn-print">
        🖨 Cetak Struk (Print)
    </button>

</body>
</html>
