@extends('layouts.cashier')

@section('title', 'Dashboard Pesanan')

@section('content')
<div x-data="{
        payModalOpen: false,
        activeOrder: null,
        cashGiven: 0,
        availabilityModalOpen: false,

        openPayModal(order) {
            this.activeOrder = order;
            this.cashGiven = parseFloat(order.total_amount);
            this.payModalOpen = true;
        },
        calculateChange() {
            if (!this.activeOrder) return 0;
            return Math.max(0, this.cashGiven - parseFloat(this.activeOrder.total_amount));
        },
        formatRupiah(num) {
            return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
        }
     }">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b-2 border-rustic-300">
        <div>
            <h1 class="font-display font-bold text-2xl sm:text-3xl text-rustic-900">
                Dashboard Pesanan Kasir
            </h1>
            <p class="font-body italic text-xs text-rustic-600 mt-0.5">
                {{ now()->translatedFormat('l, d F Y') }} • Antrean Pesanan Masuk & Proses Barista
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Quick POS Walk-in button -->
            <a href="{{ route('cashier.pos.index') }}"
               class="bg-copper text-rustic-900 border border-rustic-700 hover:bg-copper-light font-heading text-xs uppercase px-4 py-2.5 shadow-rustic-sm transition font-bold">
                ⚡ + Pesanan Walk-In
            </a>

            <!-- Toggle Availability Button -->
            <button type="button"
                    @click="availabilityModalOpen = true"
                    class="bg-parchment text-rustic-800 border-2 border-rustic-500 hover:bg-rustic-200 font-heading text-xs uppercase px-3.5 py-2 transition">
                ☕ Atur Stok Menu
            </button>

            <!-- Verification Shortcut -->
            <a href="{{ route('cashier.verify') }}"
               class="bg-rustic-800 text-parchment border border-copper hover:bg-rustic-700 font-heading text-xs uppercase px-3.5 py-2 shadow-rustic-sm transition">
                🔍 Scan QR / Cari
            </a>
        </div>
    </div>

    <!-- Shift Warning Notice if no shift open -->
    @if (! $currentShift)
        <div class="mb-6 p-4 bg-brick/15 border-l-4 border-brick text-rustic-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 rounded-xs">
            <div>
                <strong class="font-heading uppercase text-xs text-brick block">⚠ Perhatian: Shift Kasir Belum Dibuka!</strong>
                <p class="font-body text-xs text-rustic-700 mt-0.5">
                    Silakan buka shift kasir terlebih dahulu untuk mencatat modal awal dan rekapitulasi kas harian secara akurat.
                </p>
            </div>
            <a href="{{ route('cashier.shifts.index') }}"
               class="bg-brick text-parchment font-heading text-xs uppercase px-4 py-2 hover:opacity-90 font-bold shrink-0">
                Buka Shift Sekarang →
            </a>
        </div>
    @else
        <div class="mb-6 p-3 bg-parchment border border-copper text-xs font-body text-rustic-800 flex items-center justify-between rounded-xs">
            <div>
                <span class="text-copper font-heading uppercase text-[10px] font-bold">● Shift Sedang Aktif:</span>
                Dimulai {{ $currentShift->started_at->format('H:i') }} WIB • Modal Awal: <strong>Rp {{ number_format($currentShift->starting_cash, 0, ',', '.') }}</strong> • Penjualan Shift: <strong>Rp {{ number_format($currentShift->total_sales, 0, ',', '.') }}</strong>
            </div>
            <a href="{{ route('cashier.shifts.index') }}" class="font-heading text-[10px] uppercase text-copper underline hover:text-rustic-900 font-bold">
                Kelola Shift →
            </a>
        </div>
    @endif

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-8">
        <a href="{{ route('cashier.dashboard', ['tab' => 'waiting']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'waiting' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }}">
            <div class="text-[10px] font-heading uppercase tracking-wider text-copper">Menunggu Bayar Kasir</div>
            <div class="font-display font-bold text-2xl mt-1">{{ $countWaitingPayment }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'paid']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'paid' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }}">
            <div class="text-[10px] font-heading uppercase tracking-wider text-copper">Dibayar Online</div>
            <div class="font-display font-bold text-2xl mt-1">{{ $countPaidOnline }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'processing']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'processing' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }}">
            <div class="text-[10px] font-heading uppercase tracking-wider text-copper">Sedang Diproses</div>
            <div class="font-display font-bold text-2xl mt-1">{{ $countInProcess }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'ready']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'ready' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }}">
            <div class="text-[10px] font-heading uppercase tracking-wider text-copper">Siap Disajikan</div>
            <div class="font-display font-bold text-2xl mt-1 text-[#2D6A4F]">{{ $countReady }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'completed']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'completed' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }} col-span-2 sm:col-span-1">
            <div class="text-[10px] font-heading uppercase tracking-wider text-rustic-500">Selesai Hari Ini</div>
            <div class="font-display font-bold text-2xl mt-1 text-rustic-700">{{ $countCompletedToday }}</div>
        </a>
    </div>

    <!-- Active Tab Filter Pills -->
    <div class="flex items-center space-x-2 border-b border-rustic-300 pb-3 mb-6 overflow-x-auto text-xs font-heading uppercase tracking-wider">
        <a href="{{ route('cashier.dashboard') }}"
           class="px-3 py-1.5 rounded-sm transition shrink-0 {{ $tab === 'active' ? 'bg-rustic-800 text-parchment font-bold' : 'bg-parchment text-rustic-700 hover:bg-rustic-200' }}">
            Semua Antrean Aktif ({{ $countWaitingPayment + $countPaidOnline + $countInProcess + $countReady }})
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'waiting']) }}"
           class="px-3 py-1.5 rounded-sm transition shrink-0 {{ $tab === 'waiting' ? 'bg-rustic-800 text-parchment font-bold' : 'bg-parchment text-rustic-700 hover:bg-rustic-200' }}">
            Menunggu Bayar Kasir ({{ $countWaitingPayment }})
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'paid']) }}"
           class="px-3 py-1.5 rounded-sm transition shrink-0 {{ $tab === 'paid' ? 'bg-rustic-800 text-parchment font-bold' : 'bg-parchment text-rustic-700 hover:bg-rustic-200' }}">
            Sudah Bayar Online ({{ $countPaidOnline }})
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'processing']) }}"
           class="px-3 py-1.5 rounded-sm transition shrink-0 {{ $tab === 'processing' ? 'bg-rustic-800 text-parchment font-bold' : 'bg-parchment text-rustic-700 hover:bg-rustic-200' }}">
            Diproses Barista ({{ $countInProcess }})
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'ready']) }}"
           class="px-3 py-1.5 rounded-sm transition shrink-0 {{ $tab === 'ready' ? 'bg-rustic-800 text-parchment font-bold' : 'bg-parchment text-rustic-700 hover:bg-rustic-200' }}">
            Siap Diambil ({{ $countReady }})
        </a>
    </div>

    <!-- Order List -->
    @if ($orders->isEmpty())
        <div class="bg-[#FFF8F0] border-2 border-dashed border-rustic-300 rounded-sm p-12 text-center shadow-sm">
            <span class="text-4xl">☕</span>
            <h3 class="font-display font-bold text-xl text-rustic-900 mt-2">Tidak Ada Pesanan di Kategori Ini</h3>
            <p class="font-body text-xs text-rustic-600 mt-1">
                Semua pesanan pada tab ini telah diproses atau belum ada pesanan baru yang masuk.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($orders as $order)
                @php
                    $statusStyles = [
                        'menunggu' => 'border-rustic-500 text-rustic-700 bg-rustic-100',
                        'dibayar' => 'border-info text-info bg-info/10',
                        'diterima' => 'border-copper text-copper bg-rustic-200 font-bold',
                        'diproses' => 'border-warning text-warning bg-warning/10 font-bold',
                        'siap' => 'border-[#2D6A4F] text-[#2D6A4F] bg-[#2D6A4F]/10 font-bold',
                        'selesai' => 'border-[#2D6A4F] text-[#2D6A4F] bg-[#2D6A4F]/20 font-bold',
                        'batal' => 'border-brick text-brick bg-brick/10',
                    ];
                    $badge = $statusStyles[$order->status] ?? 'border-rustic-400 text-rustic-600';
                @endphp

                <div class="bg-[#FFF8F0] border-2 border-rustic-400 rounded-sm p-4 shadow-rustic hover:shadow-rustic-lg transition flex flex-col justify-between">
                    <div>
                        <!-- Header: Code, Status & Time -->
                        <div class="flex items-start justify-between border-b border-rustic-200 pb-2 mb-3">
                            <div>
                                <span class="font-display font-black text-xl text-rustic-900 block leading-tight">
                                    {{ $order->order_code }}
                                </span>
                                <span class="text-[11px] text-rustic-500 font-body">
                                    {{ $order->created_at->diffForHumans() }} •
                                    <strong class="uppercase text-copper">{{ $order->source === 'cashier' ? 'Walk-In' : 'Online' }}</strong>
                                </span>
                            </div>

                            <span class="border {{ $badge }} font-heading text-[10px] uppercase tracking-wider px-2 py-0.5">
                                ● {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <!-- Customer Info -->
                        <div class="text-xs font-body mb-3 pb-2 border-b border-rustic-200">
                            <strong>{{ $order->customer_name ?? 'Pelanggan' }}</strong>
                            @if ($order->customer_phone)
                                <span class="text-rustic-500">({{ $order->customer_phone }})</span>
                            @endif
                            <div class="text-[11px] text-rustic-600 mt-0.5">
                                Metode: <strong class="uppercase">{{ $order->payment_method === 'cash' ? 'Bayar di Kasir' : $order->payment_method }}</strong>
                                ({{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM DIBAYAR' }})
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="space-y-1.5 text-xs font-body mb-3">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between items-start">
                                    <div class="pr-2">
                                        <strong>{{ $item->quantity }}×</strong> {{ $item->name }}
                                        @if (! empty($item->options))
                                            <div class="text-[10px] text-rustic-600 italic">
                                                {{ implode(', ', array_filter([
                                                    $item->options['size'] ?? null,
                                                    $item->options['sugar'] ?? null,
                                                    $item->options['ice'] ?? null,
                                                    !empty($item->options['toppings']) ? implode(', ', (array)$item->options['toppings']) : null,
                                                ])) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="font-display font-bold text-rustic-900 shrink-0">
                                        {{ $item->formatted_subtotal }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if ($order->notes)
                            <div class="p-2 bg-parchment border border-rustic-300 rounded-xs text-[11px] italic text-rustic-700 mb-3">
                                Catatan: "{{ $order->notes }}"
                            </div>
                        @endif
                    </div>

                    <!-- Bottom Action Controls -->
                    <div class="pt-3 border-t-2 border-rustic-300 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-heading uppercase text-rustic-600 font-bold">Total:</span>
                            <span class="font-display font-bold text-lg text-copper">
                                {{ $order->formatted_total }}
                            </span>
                        </div>

                        <!-- Action Buttons Depending on Status -->
                        <div class="space-y-1.5 pt-1">
                            @if ($order->status === 'menunggu' && $order->payment_method === 'cash')
                                <button type="button"
                                        @click="openPayModal({{ json_encode($order) }})"
                                        class="w-full bg-copper text-rustic-900 border border-rustic-700 hover:bg-copper-light font-heading text-xs uppercase py-2 shadow-rustic-sm font-bold transition">
                                    💵 TERIMA PEMBAYARAN KASIR
                                </button>
                            @elseif ($order->status === 'dibayar')
                                <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit"
                                            class="w-full bg-rustic-800 text-parchment border border-copper hover:bg-rustic-700 font-heading text-xs uppercase py-2 shadow-rustic-sm font-bold transition">
                                        ✓ TERIMA PESANAN (KE DAPUR)
                                    </button>
                                </form>
                            @elseif ($order->status === 'diterima')
                                <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="diproses">
                                    <button type="submit"
                                            class="w-full bg-rustic-800 text-parchment border border-copper hover:bg-rustic-700 font-heading text-xs uppercase py-2 shadow-rustic-sm font-bold transition">
                                        ⚙️ MULAI RACIK (DIPROSES)
                                    </button>
                                </form>
                            @elseif ($order->status === 'diproses')
                                <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="siap">
                                    <button type="submit"
                                            class="w-full bg-[#2D6A4F] text-parchment hover:opacity-90 font-heading text-xs uppercase py-2 font-bold shadow-sm transition">
                                        ☕ TANDAI SIAP DIAMBIL
                                    </button>
                                </form>
                            @elseif ($order->status === 'siap')
                                <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit"
                                            class="w-full bg-[#2D6A4F] text-parchment hover:opacity-90 font-heading text-xs uppercase py-2 font-bold shadow-sm transition">
                                        ✓ SERAHKAN KE PELANGGAN (SELESAI)
                                    </button>
                                </form>
                            @endif

                            <div class="flex items-center space-x-2 pt-1 text-xs">
                                <a href="{{ route('orders.receipt', $order->order_code) }}"
                                   target="_blank"
                                   class="flex-1 text-center bg-parchment border border-rustic-400 text-rustic-700 hover:bg-rustic-200 py-1 font-heading text-[10px] uppercase">
                                    🖨 Cetak Struk
                                </a>

                                @if (! in_array($order->status, ['selesai', 'batal']))
                                    <form method="POST" action="{{ route('cashier.order.update-status', $order->id) }}" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="batal">
                                        <button type="submit"
                                                onclick="return confirm('Batalkan pesanan {{ $order->order_code }}?')"
                                                class="w-full bg-transparent border border-brick text-brick hover:bg-brick/10 py-1 font-heading text-[10px] uppercase">
                                            ✕ Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- MODAL PROCESS PAYMENT AT CASHIER -->
    <div x-show="payModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-rustic-900/70 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-[#FFF8F0] border-2 border-copper shadow-rustic-lg rounded-sm w-full max-w-md p-6"
             @click.outside="payModalOpen = false">

            <div class="flex justify-between items-center border-b border-rustic-300 pb-3 mb-4">
                <h3 class="font-display font-bold text-xl text-rustic-900">
                    Proses Pembayaran Kasir
                </h3>
                <button type="button" @click="payModalOpen = false" class="text-rustic-600 hover:text-rustic-900 font-bold text-lg">✕</button>
            </div>

            <template x-if="activeOrder">
                <form method="POST" :action="'/kasir/pesanan/' + activeOrder.id + '/bayar'" class="space-y-4">
                    @csrf

                    <div>
                        <span class="text-xs font-heading uppercase text-rustic-500 block">Kode Pesanan</span>
                        <strong class="text-xl font-display text-rustic-900" x-text="activeOrder.order_code"></strong>
                        <div class="text-xs text-rustic-600" x-text="activeOrder.customer_name"></div>
                    </div>

                    <div class="p-3 bg-parchment border border-rustic-400 rounded-sm">
                        <div class="text-xs font-heading uppercase text-rustic-600">Total Tagihan:</div>
                        <div class="font-display font-bold text-2xl text-copper"
                             x-text="formatRupiah(activeOrder.total_amount)"></div>
                    </div>

                    <div>
                        <label class="block font-heading uppercase text-xs text-rustic-800 mb-1">
                            Metode Pembayaran
                        </label>
                        <select name="payment_method" class="w-full bg-parchment border-2 border-rustic-400 font-body text-xs p-2.5 rounded-sm focus:border-copper">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="qris">QRIS Kasir</option>
                            <option value="debit">Kartu Debit / EDC</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-heading uppercase text-xs text-rustic-800 mb-1">
                            Uang Tunai Diterima (Rp)
                        </label>
                        <input type="number"
                               name="cash_given"
                               x-model.number="cashGiven"
                               :min="activeOrder.total_amount"
                               class="w-full bg-parchment border-2 border-rustic-400 font-display font-bold text-lg p-2.5 text-rustic-900 rounded-sm focus:border-copper">
                    </div>

                    <div class="p-3 bg-rustic-100 border border-copper rounded-sm flex justify-between items-center">
                        <span class="font-heading uppercase text-xs text-rustic-700">Uang Kembalian:</span>
                        <span class="font-display font-bold text-xl text-[#2D6A4F]"
                              x-text="formatRupiah(calculateChange())"></span>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading tracking-widest uppercase text-xs py-3.5 shadow-rustic font-bold transition">
                            SELESAIKAN & CETAK STRUK
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- MODAL ATUR STOK / TOGGLE KETERSEDIAAN MENU -->
    <div x-show="availabilityModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-rustic-900/70 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-[#FFF8F0] border-2 border-copper shadow-rustic-lg rounded-sm w-full max-w-2xl p-6 max-h-[85vh] overflow-y-auto"
             @click.outside="availabilityModalOpen = false">

            <div class="flex justify-between items-center border-b border-rustic-300 pb-3 mb-4">
                <div>
                    <h3 class="font-display font-bold text-xl text-rustic-900">
                        Atur Ketersediaan Stok Menu
                    </h3>
                    <p class="font-body italic text-xs text-rustic-600">
                        Klik tombol untuk menandai produk Habis atau Tersedia secara instan
                    </p>
                </div>
                <button type="button" @click="availabilityModalOpen = false" class="text-rustic-600 hover:text-rustic-900 font-bold text-lg">✕</button>
            </div>

            <div class="divide-y divide-rustic-200">
                @foreach ($products as $p)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-heading uppercase text-copper block text-[10px]">{{ $p->category->name }}</span>
                            <strong class="font-display text-sm text-rustic-900">{{ $p->name }}</strong>
                            <div class="text-xs text-rustic-500">{{ $p->formatted_price }}</div>
                        </div>

                        <form method="POST" action="{{ route('cashier.product.toggle', $p->id) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 font-heading text-xs uppercase border rounded-xs transition font-bold {{ $p->is_available ? 'bg-[#2D6A4F] text-parchment hover:opacity-90' : 'bg-brick text-parchment hover:opacity-90' }}">
                                {{ $p->is_available ? '✓ Tersedia' : '✕ Habis' }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
