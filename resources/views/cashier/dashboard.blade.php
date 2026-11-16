@extends('layouts.cashier')

@section('title', 'Dashboard Pesanan & Barista')

@section('content')
<div x-data="{
        payModalOpen: false,
        activeOrder: null,
        cashGiven: 0,
        availabilityModalOpen: false,
        soundEnabled: true,
        newOrderAlert: null,

        // Orders list managed in reactive state
        orders: {{ Js::from($orders->map(function ($o) {
            return [
                'id' => $o->id,
                'order_code' => $o->order_code,
                'customer_name' => $o->customer_name ?: ($o->user?->name ?? 'Pelanggan'),
                'customer_phone' => $o->customer_phone ?: ($o->user?->phone ?? '-'),
                'source' => $o->source,
                'status' => $o->status,
                'payment_method' => $o->payment_method,
                'payment_status' => $o->payment_status,
                'total_amount' => (float) $o->total_amount,
                'formatted_total' => 'Rp ' . number_format((float) $o->total_amount, 0, ',', '.'),
                'notes' => $o->notes,
                'created_at_human' => $o->created_at->diffForHumans(),
                'created_at_time' => $o->created_at->format('H:i'),
                'receipt_url' => route('orders.receipt', $o->order_code),
                'items' => $o->items->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                        'formatted_subtotal' => 'Rp ' . number_format((float) $item->subtotal, 0, ',', '.'),
                        'options' => $item->options,
                    ];
                }),
            ];
        })) }},

        knownOrderIds: new Set({{ Js::from($orders->pluck('id')) }}),

        counts: {
            waiting: {{ $countWaitingPayment }},
            paid: {{ $countPaidOnline }},
            processing: {{ $countInProcess }},
            ready: {{ $countReady }},
            completed: {{ $countCompletedToday }}
        },

        init() {
            // Start real-time polling every 3.5 seconds
            setInterval(() => {
                this.pollOrders();
            }, 3500);
        },

        async pollOrders() {
            try {
                const response = await fetch('{{ route('cashier.feed') }}', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;

                const data = await response.json();
                let newlyArrived = null;

                data.orders.forEach(order => {
                    if (!this.knownOrderIds.has(order.id)) {
                        this.knownOrderIds.add(order.id);
                        newlyArrived = order;
                    }
                });

                // Update orders and counts
                this.orders = data.orders;
                if (data.counts) {
                    this.counts.waiting = data.counts.waiting;
                    this.counts.paid = data.counts.paid;
                    this.counts.processing = data.counts.processing;
                    this.counts.ready = data.counts.ready;
                }

                // If a new order arrived from web/pos, trigger alert and sound
                if (newlyArrived) {
                    this.newOrderAlert = newlyArrived;
                    this.playChime();
                }
            } catch (err) {
                console.error('Gagal memperbarui feed pesanan:', err);
            }
        },

        playChime() {
            if (!this.soundEnabled) return;
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const now = audioCtx.currentTime;

                // Tone 1: D5 (587 Hz)
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now);
                gain1.gain.setValueAtTime(0.35, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.start(now);
                osc1.stop(now + 0.6);

                // Tone 2: A5 (880 Hz) harmonic chime
                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.type = 'triangle';
                osc2.frequency.setValueAtTime(880, now + 0.18);
                gain2.gain.setValueAtTime(0.4, now + 0.18);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 1.0);
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                osc2.start(now + 0.18);
                osc2.stop(now + 1.0);
            } catch (e) {
                console.log('Audio chime unsupported or blocked by browser gesture:', e);
            }
        },

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

    <!-- FLOATING POPUP NOTIFIKASI: "ADA PESANAN BARU!" -->
    <div x-show="newOrderAlert"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="mb-6 p-4 md:p-5 bg-copper text-rustic-900 border-2 border-rustic-900 shadow-rustic-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative overflow-hidden animate-pulse">
        <div class="flex items-center space-x-3.5">
            <span class="text-3xl sm:text-4xl">🔔</span>
            <div>
                <div class="flex items-center space-x-2">
                    <span class="bg-rustic-900 text-copper font-heading uppercase text-[10px] tracking-widest px-2 py-0.5 font-bold">
                        PESANAN MASUK BARU
                    </span>
                    <span class="text-xs font-mono font-bold" x-text="'#' + newOrderAlert?.order_code"></span>
                </div>
                <h2 class="font-display font-black text-xl sm:text-2xl text-rustic-900 tracking-tight leading-snug mt-0.5">
                    Ada Pesanan Baru Masuk!
                </h2>
                <p class="font-body text-xs sm:text-sm text-rustic-800 font-semibold"
                   x-text="'Pelanggan: ' + (newOrderAlert?.customer_name || 'Pelanggan Web') + ' • Total: ' + (newOrderAlert?.formatted_total || '') + ' (' + (newOrderAlert?.payment_method === 'cash' ? 'Bayar di Kasir' : 'Dibayar Online') + ')'">
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-2 self-end sm:self-center shrink-0">
            <button type="button"
                    @click="newOrderAlert = null"
                    class="px-4 py-2 bg-rustic-900 text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-rustic-800 transition">
                Tutup Notifikasi ✕
            </button>
        </div>
    </div>

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b-2 border-rustic-300">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="font-display font-bold text-2xl sm:text-3xl text-rustic-900">
                    Dashboard Pesanan Kasir & Barista
                </h1>
                <span class="inline-flex items-center px-2 py-0.5 bg-emerald-100 text-emerald-800 border border-emerald-400 text-[10px] font-heading uppercase tracking-wider font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 mr-1.5 animate-ping"></span>
                    Live Sync Otomatis
                </span>
            </div>
            <p class="font-body italic text-xs text-rustic-600 mt-1">
                {{ now()->translatedFormat('l, d F Y') }} • Pesanan web langsung masuk ke antrean tanpa perlu scan QR.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Audio Sound Toggle Button -->
            <button type="button"
                    @click="soundEnabled = !soundEnabled; if (soundEnabled) playChime();"
                    class="px-3 py-2 border-2 font-heading text-xs uppercase font-bold transition flex items-center space-x-1.5"
                    :class="soundEnabled ? 'bg-amber-100 border-amber-600 text-amber-950' : 'bg-rustic-100 border-rustic-300 text-rustic-600'">
                <span x-text="soundEnabled ? '🔔' : '🔕'"></span>
                <span x-text="soundEnabled ? 'Suara Notif: Aktif' : 'Suara Notif: Mute'"></span>
            </button>

            <!-- Quick POS Walk-in button -->
            <a href="{{ route('cashier.pos.index') }}"
               class="bg-copper text-rustic-900 border border-rustic-700 hover:bg-copper-light font-heading text-xs uppercase px-3.5 py-2 shadow-rustic-sm transition font-bold">
                ⚡ + Walk-In
            </a>

            <!-- Toggle Availability Button -->
            <button type="button"
                    @click="availabilityModalOpen = true"
                    class="bg-parchment text-rustic-800 border-2 border-rustic-500 hover:bg-rustic-200 font-heading text-xs uppercase px-3 py-2 transition">
                ☕ Atur Stok
            </button>
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
            <div class="font-display font-bold text-2xl mt-1" x-text="counts.waiting">{{ $countWaitingPayment }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'paid']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'paid' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }}">
            <div class="text-[10px] font-heading uppercase tracking-wider text-copper">Dibayar Online</div>
            <div class="font-display font-bold text-2xl mt-1" x-text="counts.paid">{{ $countPaidOnline }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'processing']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'processing' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }}">
            <div class="text-[10px] font-heading uppercase tracking-wider text-copper">Sedang Diproses</div>
            <div class="font-display font-bold text-2xl mt-1" x-text="counts.processing">{{ $countInProcess }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'ready']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'ready' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }}">
            <div class="text-[10px] font-heading uppercase tracking-wider text-copper">Siap Disajikan</div>
            <div class="font-display font-bold text-2xl mt-1 text-[#2D6A4F]" x-text="counts.ready">{{ $countReady }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'completed']) }}"
           class="p-4 rounded-sm border-2 transition {{ $tab === 'completed' ? 'bg-rustic-800 text-parchment border-copper shadow-rustic-sm' : 'bg-[#FFF8F0] border-rustic-300 hover:border-copper' }} col-span-2 sm:col-span-1">
            <div class="text-[10px] font-heading uppercase tracking-wider text-rustic-500">Selesai Hari Ini</div>
            <div class="font-display font-bold text-2xl mt-1 text-rustic-700" x-text="counts.completed">{{ $countCompletedToday }}</div>
        </a>
    </div>

    <!-- Active Tab Filter Pills -->
    <div class="flex items-center space-x-2 border-b border-rustic-300 pb-3 mb-6 overflow-x-auto text-xs font-heading uppercase tracking-wider">
        <a href="{{ route('cashier.dashboard') }}"
           class="px-3.5 py-1.5 rounded-xs transition {{ !request()->has('tab') || request('tab') === 'active' ? 'bg-copper text-rustic-900 font-bold border border-copper' : 'bg-parchment text-rustic-700 border border-rustic-300 hover:border-copper' }}">
            Semua Pesanan Aktif
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'waiting']) }}"
           class="px-3.5 py-1.5 rounded-xs transition {{ request('tab') === 'waiting' ? 'bg-copper text-rustic-900 font-bold border border-copper' : 'bg-parchment text-rustic-700 border border-rustic-300 hover:border-copper' }}">
            Menunggu Bayar
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'paid']) }}"
           class="px-3.5 py-1.5 rounded-xs transition {{ request('tab') === 'paid' ? 'bg-copper text-rustic-900 font-bold border border-copper' : 'bg-parchment text-rustic-700 border border-rustic-300 hover:border-copper' }}">
            Sudah Dibayar Online
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'processing']) }}"
           class="px-3.5 py-1.5 rounded-xs transition {{ request('tab') === 'processing' ? 'bg-copper text-rustic-900 font-bold border border-copper' : 'bg-parchment text-rustic-700 border border-rustic-300 hover:border-copper' }}">
            Sedang Diracing
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'ready']) }}"
           class="px-3.5 py-1.5 rounded-xs transition {{ request('tab') === 'ready' ? 'bg-copper text-rustic-900 font-bold border border-copper' : 'bg-parchment text-rustic-700 border border-rustic-300 hover:border-copper' }}">
            Siap Diambil
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'completed']) }}"
           class="px-3.5 py-1.5 rounded-xs transition {{ request('tab') === 'completed' ? 'bg-copper text-rustic-900 font-bold border border-copper' : 'bg-parchment text-rustic-700 border border-rustic-300 hover:border-copper' }}">
            Riwayat Selesai
        </a>
    </div>

    <!-- MAIN ORDERS CARDS: NO SCAN NEEDED -->
    <template x-if="orders.length === 0">
        <div class="bg-[#FFF8F0] border-2 border-dashed border-rustic-400 p-12 text-center rounded-sm">
            <span class="text-4xl block mb-2">☕</span>
            <h3 class="font-display font-bold text-lg text-rustic-900">
                Belum Ada Pesanan Antrean Saat Ini
            </h3>
            <p class="font-body italic text-xs text-rustic-600 mt-1 max-w-md mx-auto">
                Pesanan yang dibuat oleh pelanggan melalui web atau kasir akan langsung otomatis muncul di sini tanpa perlu scan QR.
            </p>
        </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" x-show="orders.length > 0">
        <template x-for="order in orders" :key="order.id">
            <div class="bg-[#FFF8F0] border-2 border-rustic-400 rounded-sm p-4 shadow-rustic hover:shadow-rustic-lg transition flex flex-col justify-between relative overflow-hidden">
                <!-- Status Bar Top Accent -->
                <div class="absolute top-0 left-0 right-0 h-1.5"
                     :class="{
                         'bg-amber-500': order.status === 'menunggu',
                         'bg-blue-600': order.status === 'dibayar',
                         'bg-copper': order.status === 'diterima',
                         'bg-purple-600': order.status === 'diproses',
                         'bg-emerald-600': order.status === 'siap',
                         'bg-slate-600': order.status === 'selesai'
                     }"></div>

                <div>
                    <!-- Header: Code, Source & Time -->
                    <div class="flex items-start justify-between border-b border-rustic-200 pb-2 mb-3 pt-1">
                        <div>
                            <span class="font-display font-black text-xl text-rustic-900 block leading-tight"
                                  x-text="'#' + order.order_code">
                            </span>
                            <span class="text-[11px] text-rustic-500 font-body">
                                <span x-text="order.created_at_human"></span> •
                                <strong class="uppercase text-copper"
                                        x-text="order.source === 'cashier' ? 'Kasir (Walk-In)' : '🌐 Pesanan Web'"></strong>
                            </span>
                        </div>

                        <span class="border font-heading text-[10px] uppercase tracking-wider px-2 py-0.5"
                              :class="{
                                  'border-amber-600 text-amber-800 bg-amber-50': order.status === 'menunggu',
                                  'border-blue-600 text-blue-800 bg-blue-50': order.status === 'dibayar',
                                  'border-copper text-copper bg-rustic-200 font-bold': order.status === 'diterima',
                                  'border-purple-600 text-purple-800 bg-purple-50 font-bold': order.status === 'diproses',
                                  'border-emerald-600 text-emerald-800 bg-emerald-50 font-bold': order.status === 'siap',
                                  'border-slate-500 text-slate-800 bg-slate-100': order.status === 'selesai'
                              }">
                            ● <span x-text="order.status"></span>
                        </span>
                    </div>

                    <!-- Customer Info -->
                    <div class="text-xs font-body mb-3 pb-2 border-b border-rustic-200">
                        <strong x-text="order.customer_name"></strong>
                        <span class="text-rustic-500" x-show="order.customer_phone && order.customer_phone !== '-'" x-text="'(' + order.customer_phone + ')'"></span>
                        <div class="text-[11px] text-rustic-600 mt-0.5">
                            Metode Bayar:
                            <strong class="uppercase" x-text="order.payment_method === 'cash' ? 'Bayar di Kasir' : order.payment_method"></strong>
                            (<span class="font-bold" :class="order.payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-800'"
                                   x-text="order.payment_status === 'paid' ? 'LUNAS' : 'MENUNGGU BAYAR'"></span>)
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-1.5 text-xs font-body mb-3">
                        <template x-for="item in order.items" :key="item.name">
                            <div class="flex justify-between items-start">
                                <div class="pr-2">
                                    <strong x-text="item.quantity + '×'"></strong>
                                    <span x-text="item.name"></span>
                                    <template x-if="item.options">
                                        <div class="text-[10px] text-rustic-600 italic">
                                            <span x-text="[item.options.size, item.options.sugar, item.options.ice, Array.isArray(item.options.toppings) ? item.options.toppings.join(', ') : item.options.toppings].filter(Boolean).join(' • ')"></span>
                                        </div>
                                    </template>
                                </div>
                                <span class="font-display font-bold text-rustic-900 shrink-0" x-text="item.formatted_subtotal"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Notes -->
                    <template x-if="order.notes">
                        <div class="p-2 bg-parchment border border-rustic-300 rounded-xs text-[11px] italic text-rustic-700 mb-3">
                            Catatan: "<span x-text="order.notes"></span>"
                        </div>
                    </template>
                </div>

                <!-- Bottom Action Controls: Direct 1-Click Operations (NO SCAN) -->
                <div class="pt-3 border-t-2 border-rustic-300 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-heading uppercase text-rustic-600 font-bold">Total Tagihan:</span>
                        <span class="font-display font-bold text-lg text-copper" x-text="order.formatted_total"></span>
                    </div>

                    <!-- Action Buttons Depending on Status -->
                    <div class="space-y-1.5 pt-1">
                        <!-- Menunggu Bayar Tunai di Kasir -->
                        <template x-if="order.status === 'menunggu' && order.payment_method === 'cash'">
                            <button type="button"
                                    @click="openPayModal(order)"
                                    class="w-full bg-copper text-rustic-900 border border-rustic-700 hover:bg-copper-light font-heading text-xs uppercase py-2.5 shadow-rustic-sm font-bold transition">
                                💵 TERIMA PEMBAYARAN KASIR
                            </button>
                        </template>

                        <!-- Sudah Dibayar Online -> Barista Terima Pesanan Langsung -->
                        <template x-if="order.status === 'dibayar'">
                            <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + order.id + '/status'">
                                @csrf
                                <input type="hidden" name="status" value="diterima">
                                <button type="submit"
                                        class="w-full bg-rustic-800 text-parchment border border-copper hover:bg-rustic-700 font-heading text-xs uppercase py-2.5 shadow-rustic-sm font-bold transition">
                                    ✓ TERIMA PESANAN (KE DAPUR)
                                </button>
                            </form>
                        </template>

                        <!-- Diterima -> Mulai Racik -->
                        <template x-if="order.status === 'diterima'">
                            <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + order.id + '/status'">
                                @csrf
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit"
                                        class="w-full bg-rustic-800 text-parchment border border-copper hover:bg-rustic-700 font-heading text-xs uppercase py-2.5 shadow-rustic-sm font-bold transition">
                                    ⚙️ MULAI RACIK MINUMAN
                                </button>
                            </form>
                        </template>

                        <!-- Diproses -> Tandai Siap Diambil -->
                        <template x-if="order.status === 'diproses'">
                            <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + order.id + '/status'">
                                @csrf
                                <input type="hidden" name="status" value="siap">
                                <button type="submit"
                                        class="w-full bg-[#2D6A4F] text-parchment hover:opacity-90 font-heading text-xs uppercase py-2.5 font-bold shadow-sm transition">
                                    ☕ TANDAI SIAP DIAMBIL
                                </button>
                            </form>
                        </template>

                        <!-- Siap Diambil -> Selesaikan -->
                        <template x-if="order.status === 'siap'">
                            <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + order.id + '/status'">
                                @csrf
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit"
                                        class="w-full bg-[#2D6A4F] text-parchment hover:opacity-90 font-heading text-xs uppercase py-2.5 font-bold shadow-sm transition">
                                    ✓ SERAHKAN KE PELANGGAN (SELESAI)
                                </button>
                            </form>
                        </template>

                        <!-- Quick Utility Links: Print Receipt & Cancel -->
                        <div class="flex items-center space-x-2 pt-1 text-xs">
                            <a :href="order.receipt_url"
                               target="_blank"
                               class="flex-1 text-center bg-parchment border border-rustic-400 text-rustic-700 hover:bg-rustic-200 py-1 font-heading text-[10px] uppercase">
                                🖨 Cetak Struk
                            </a>

                            <template x-if="order.status !== 'selesai' && order.status !== 'batal'">
                                <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + order.id + '/status'" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="status" value="batal">
                                    <button type="submit"
                                            :onclick="'return confirm(\'Batalkan pesanan #' + order.order_code + '?\')'"
                                            class="w-full bg-transparent border border-brick text-brick hover:bg-brick/10 py-1 font-heading text-[10px] uppercase">
                                        ✕ Batal
                                    </button>
                                </form>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

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
                <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + activeOrder.id + '/bayar'" class="space-y-4">
                    @csrf

                    <div class="bg-parchment p-3 border border-rustic-300 rounded-sm text-xs space-y-1">
                        <div class="flex justify-between">
                            <span class="text-rustic-600 font-heading uppercase">Kode Pesanan:</span>
                            <strong class="font-mono" x-text="activeOrder.order_code"></strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-rustic-600 font-heading uppercase">Pelanggan:</span>
                            <strong x-text="activeOrder.customer_name"></strong>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-rustic-300 text-sm">
                            <span class="font-heading uppercase font-bold text-rustic-900">Total Tagihan:</span>
                            <strong class="font-display font-bold text-copper text-base"
                                    x-text="activeOrder.formatted_total"></strong>
                        </div>
                    </div>

                    <div>
                        <label class="block font-heading uppercase text-xs text-rustic-800 mb-1">
                            Metode Pembayaran
                        </label>
                        <select name="payment_method"
                                class="w-full bg-parchment border-2 border-rustic-400 p-2 text-xs font-heading font-bold text-rustic-900 rounded-sm">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="qris">QRIS Kasir</option>
                            <option value="debit">Kartu Debit</option>
                            <option value="transfer">Transfer Bank</option>
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
