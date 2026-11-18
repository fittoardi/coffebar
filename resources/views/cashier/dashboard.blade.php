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

    <!-- FLOATING POPUP NOTIFIKASI: "ADA PESANAN BARU!" (03-DESIGN.md Section 18) -->
    <div x-show="newOrderAlert"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="mb-6 p-4 md:p-5 bg-[#B5753A] text-[#FFFDF8] border border-[#2B1D14] shadow-warm rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative overflow-hidden animate-pulse">
        <div class="flex items-center space-x-3.5">
            <span class="text-3xl sm:text-4xl">🔔</span>
            <div>
                <div class="flex items-center space-x-2">
                    <span class="bg-[#2B1D14] text-[#F4ECDD] uppercase text-[10px] tracking-widest px-2.5 py-0.5 rounded-full font-bold">
                        PESANAN MASUK BARU
                    </span>
                    <span class="text-xs font-mono font-bold" x-text="'#' + newOrderAlert?.order_code"></span>
                </div>
                <h2 class="font-display font-semibold text-xl sm:text-2xl text-white tracking-tight leading-snug mt-1">
                    Ada Pesanan Baru Masuk!
                </h2>
                <p class="text-xs sm:text-sm text-[#F4ECDD] font-medium"
                   x-text="'Pelanggan: ' + (newOrderAlert?.customer_name || 'Pelanggan Web') + ' • Total: ' + (newOrderAlert?.formatted_total || '') + ' (' + (newOrderAlert?.payment_method === 'cash' ? 'Bayar di Kasir' : 'Dibayar Online') + ')'">
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-2 self-end sm:self-center shrink-0">
            <button type="button"
                    @click="newOrderAlert = null"
                    class="px-4 py-2 bg-[#2B1D14] text-[#F4ECDD] uppercase tracking-wider text-xs font-bold rounded-xl hover:bg-[#4A3224] transition shadow-sm">
                Tutup Notifikasi ✕
            </button>
        </div>
    </div>

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-dashed border-[#CDB48F]">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="font-display font-semibold text-2xl sm:text-3xl text-[#2B1D14]">
                    Dashboard Pesanan Kasir & Barista
                </h1>
                <span class="inline-flex items-center px-2.5 py-1 bg-[#7A8467]/20 text-[#4F5A3E] border border-[#7A8467] text-[10px] uppercase tracking-wider font-bold rounded-full">
                    <span class="w-2 h-2 rounded-full bg-[#7A8467] mr-1.5 animate-ping"></span>
                    Live Sync Otomatis
                </span>
            </div>
            <p class="text-xs text-[#6B5646] mt-1">
                {{ now()->translatedFormat('l, d F Y') }} • Pesanan web langsung masuk ke antrean tanpa perlu scan QR.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Audio Sound Toggle Button -->
            <button type="button"
                    @click="soundEnabled = !soundEnabled; if (soundEnabled) playChime();"
                    class="px-3.5 py-2.5 border rounded-xl text-xs uppercase font-bold transition flex items-center space-x-1.5 shadow-sm"
                    :class="soundEnabled ? 'bg-[#E8DCC4] border-[#B5753A] text-[#2B1D14]' : 'bg-[#FFFDF8] border-[#CDB48F] text-[#6B5646]'">
                <span x-text="soundEnabled ? '🔔' : '🔕'"></span>
                <span x-text="soundEnabled ? 'Suara: Aktif' : 'Suara: Mute'"></span>
            </button>

            <!-- Quick POS Walk-in button -->
            <a href="{{ route('cashier.pos.index') }}"
               class="bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase font-bold px-4 py-2.5 rounded-xl shadow-warm transition">
                ⚡ + Walk-In
            </a>

            <!-- Toggle Availability Button -->
            <button type="button"
                    @click="availabilityModalOpen = true"
                    class="bg-[#FFFDF8] text-[#2B1D14] border border-[#CDB48F] hover:bg-[#E8DCC4] text-xs uppercase font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                ☕ Atur Stok
            </button>
        </div>
    </div>

    <!-- Shift Warning Notice if no shift open -->
    @if (! $currentShift)
        <div class="mb-6 p-4 bg-[#B4552F]/15 border-l-4 border-[#B4552F] text-[#2B1D14] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 rounded-xl">
            <div>
                <strong class="uppercase text-xs text-[#B4552F] block font-bold">⚠ Perhatian: Shift Kasir Belum Dibuka!</strong>
                <p class="text-xs text-[#6B5646] mt-0.5">
                    Silakan buka shift kasir terlebih dahulu untuk mencatat modal awal dan rekapitulasi kas harian secara akurat.
                </p>
            </div>
            <a href="{{ route('cashier.shifts.index') }}"
               class="bg-[#B4552F] text-white text-xs uppercase font-bold px-4 py-2 rounded-xl hover:opacity-90 shrink-0 shadow-sm">
                Buka Shift Sekarang →
            </a>
        </div>
    @else
        <div class="mb-6 p-3.5 bg-[#FFFDF8] border border-[#CDB48F] text-xs text-[#4A3224] flex items-center justify-between rounded-xl shadow-sm">
            <div>
                <span class="text-[#7A8467] uppercase text-[10px] font-bold">● Shift Sedang Aktif:</span>
                Dimulai {{ $currentShift->started_at->format('H:i') }} WIB • Modal Awal: <strong>Rp {{ number_format($currentShift->starting_cash, 0, ',', '.') }}</strong> • Penjualan Shift: <strong>Rp {{ number_format($currentShift->total_sales, 0, ',', '.') }}</strong>
            </div>
            <a href="{{ route('cashier.shifts.index') }}" class="text-[10px] uppercase text-[#B5753A] underline font-bold hover:text-[#2B1D14]">
                Kelola Shift →
            </a>
        </div>
    @endif

    <!-- Metric Cards Grid (03-DESIGN.md Section 18) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-8">
        <a href="{{ route('cashier.dashboard', ['tab' => 'waiting']) }}"
           class="p-4 rounded-xl border transition {{ $tab === 'waiting' ? 'bg-[#2B1D14] text-[#F4ECDD] border-[#B5753A] shadow-warm' : 'bg-[#FFFDF8] border-[#CDB48F] hover:border-[#B5753A]' }}">
            <div class="text-[10px] uppercase tracking-wider font-bold text-[#CDB48F]">Menunggu Bayar Kasir</div>
            <div class="font-display font-bold text-2xl mt-1" x-text="counts.waiting">{{ $countWaitingPayment }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'paid']) }}"
           class="p-4 rounded-xl border transition {{ $tab === 'paid' ? 'bg-[#2B1D14] text-[#F4ECDD] border-[#B5753A] shadow-warm' : 'bg-[#FFFDF8] border-[#CDB48F] hover:border-[#B5753A]' }}">
            <div class="text-[10px] uppercase tracking-wider font-bold text-[#CDB48F]">Dibayar Online</div>
            <div class="font-display font-bold text-2xl mt-1" x-text="counts.paid">{{ $countPaidOnline }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'processing']) }}"
           class="p-4 rounded-xl border transition {{ $tab === 'processing' ? 'bg-[#2B1D14] text-[#F4ECDD] border-[#B5753A] shadow-warm' : 'bg-[#FFFDF8] border-[#CDB48F] hover:border-[#B5753A]' }}">
            <div class="text-[10px] uppercase tracking-wider font-bold text-[#CDB48F]">Sedang Diproses</div>
            <div class="font-display font-bold text-2xl mt-1" x-text="counts.processing">{{ $countInProcess }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'ready']) }}"
           class="p-4 rounded-xl border transition {{ $tab === 'ready' ? 'bg-[#2B1D14] text-[#F4ECDD] border-[#B5753A] shadow-warm' : 'bg-[#FFFDF8] border-[#CDB48F] hover:border-[#B5753A]' }}">
            <div class="text-[10px] uppercase tracking-wider font-bold text-[#CDB48F]">Siap Disajikan</div>
            <div class="font-display font-bold text-2xl mt-1 text-[#7A8467]" x-text="counts.ready">{{ $countReady }}</div>
        </a>

        <a href="{{ route('cashier.dashboard', ['tab' => 'completed']) }}"
           class="p-4 rounded-xl border transition {{ $tab === 'completed' ? 'bg-[#2B1D14] text-[#F4ECDD] border-[#B5753A] shadow-warm' : 'bg-[#FFFDF8] border-[#CDB48F] hover:border-[#B5753A]' }} col-span-2 sm:col-span-1">
            <div class="text-[10px] uppercase tracking-wider font-bold text-[#6B5646]">Selesai Hari Ini</div>
            <div class="font-display font-bold text-2xl mt-1 text-[#4A3224]" x-text="counts.completed">{{ $countCompletedToday }}</div>
        </a>
    </div>

    <!-- Active Tab Filter Pills -->
    <div class="flex items-center space-x-2 border-b border-dashed border-[#CDB48F] pb-3 mb-6 overflow-x-auto text-xs uppercase tracking-wider font-semibold">
        <a href="{{ route('cashier.dashboard') }}"
           class="px-4 py-2 rounded-xl transition {{ !request()->has('tab') || request('tab') === 'active' ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4]' }}">
            Semua Pesanan Aktif
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'waiting']) }}"
           class="px-4 py-2 rounded-xl transition {{ request('tab') === 'waiting' ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4]' }}">
            Menunggu Bayar
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'paid']) }}"
           class="px-4 py-2 rounded-xl transition {{ request('tab') === 'paid' ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4]' }}">
            Sudah Dibayar Online
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'processing']) }}"
           class="px-4 py-2 rounded-xl transition {{ request('tab') === 'processing' ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4]' }}">
            Sedang Diracing
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'ready']) }}"
           class="px-4 py-2 rounded-xl transition {{ request('tab') === 'ready' ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4]' }}">
            Siap Diambil
        </a>
        <a href="{{ route('cashier.dashboard', ['tab' => 'completed']) }}"
           class="px-4 py-2 rounded-xl transition {{ request('tab') === 'completed' ? 'bg-[#B5753A] text-white shadow-sm' : 'bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] hover:bg-[#E8DCC4]' }}">
            Riwayat Selesai
        </a>
    </div>

    <!-- MAIN ORDERS CARDS: NO SCAN NEEDED -->
    <template x-if="orders.length === 0">
        <div class="bg-[#FFFDF8] border border-dashed border-[#CDB48F] p-12 text-center rounded-2xl shadow-sm">
            <span class="text-4xl block mb-2">☕</span>
            <h3 class="font-display font-semibold text-xl text-[#2B1D14]">
                Belum Ada Pesanan Antrean Saat Ini
            </h3>
            <p class="text-xs text-[#6B5646] mt-1 max-w-md mx-auto">
                Pesanan yang dibuat oleh pelanggan melalui web atau kasir akan langsung otomatis muncul di sini tanpa perlu scan QR.
            </p>
        </div>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" x-show="orders.length > 0">
        <template x-for="order in orders" :key="order.id">
            <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl p-5 shadow-sm hover:shadow-warm transition flex flex-col justify-between relative overflow-hidden">
                <!-- Status Bar Top Accent -->
                <div class="absolute top-0 left-0 right-0 h-1.5"
                     :class="{
                         'bg-[#CDB48F]': order.status === 'menunggu',
                         'bg-[#B5753A]': order.status === 'dibayar',
                         'bg-[#B5753A]': order.status === 'diterima',
                         'bg-[#B4552F]': order.status === 'diproses',
                         'bg-[#7A8467]': order.status === 'siap',
                         'bg-[#4A3224]': order.status === 'selesai'
                     }"></div>

                <div>
                    <!-- Header: Code, Source & Time -->
                    <div class="flex items-start justify-between border-b border-dashed border-[#CDB48F]/70 pb-3 mb-3 pt-1">
                        <div>
                            <span class="font-display font-bold text-2xl text-[#2B1D14] block leading-tight"
                                  x-text="'#' + order.order_code">
                            </span>
                            <span class="text-[11px] text-[#6B5646]">
                                <span x-text="order.created_at_human"></span> •
                                <strong class="uppercase text-[#B5753A]"
                                        x-text="order.source === 'cashier' ? 'Kasir (Walk-In)' : '🌐 Pesanan Web'"></strong>
                            </span>
                        </div>

                        <span class="font-bold text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-full border"
                              :class="{
                                  'border-[#CDB48F] text-[#4A3224] bg-[#E8DCC4]': order.status === 'menunggu',
                                  'border-[#B5753A] text-[#B5753A] bg-[#B5753A]/10': order.status === 'dibayar' || order.status === 'diterima',
                                  'border-[#B4552F] text-[#B4552F] bg-[#B4552F]/10': order.status === 'diproses',
                                  'border-[#7A8467] text-[#4F5A3E] bg-[#7A8467]/20': order.status === 'siap',
                                  'border-[#4A3224] text-white bg-[#4A3224]': order.status === 'selesai'
                              }">
                            ● <span x-text="order.status"></span>
                        </span>
                    </div>

                    <!-- Customer Info -->
                    <div class="text-xs mb-3 pb-2 border-b border-dashed border-[#CDB48F]/70">
                        <strong class="text-sm text-[#2B1D14]" x-text="order.customer_name"></strong>
                        <span class="text-[#6B5646]" x-show="order.customer_phone && order.customer_phone !== '-'" x-text="'(' + order.customer_phone + ')'"></span>
                        <div class="text-[11px] text-[#6B5646] mt-0.5">
                            Metode Bayar:
                            <strong class="uppercase text-[#4A3224]" x-text="order.payment_method === 'cash' ? 'Bayar di Kasir' : order.payment_method"></strong>
                            (<span class="font-bold" :class="order.payment_status === 'paid' ? 'text-[#7A8467]' : 'text-[#B4552F]'"
                                   x-text="order.payment_status === 'paid' ? 'LUNAS' : 'MENUNGGU BAYAR'"></span>)
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-1.5 text-xs mb-3">
                        <template x-for="item in order.items" :key="item.name">
                            <div class="flex justify-between items-start">
                                <div class="pr-2">
                                    <strong class="text-[#2B1D14]" x-text="item.quantity + '×'"></strong>
                                    <span class="text-[#4A3224]" x-text="item.name"></span>
                                    <template x-if="item.options">
                                        <div class="text-[10px] text-[#6B5646] italic">
                                            <span x-text="[item.options.size, item.options.sugar, item.options.ice, Array.isArray(item.options.toppings) ? item.options.toppings.join(', ') : item.options.toppings].filter(Boolean).join(' • ')"></span>
                                        </div>
                                    </template>
                                </div>
                                <span class="font-display font-bold text-[#2B1D14] shrink-0" x-text="item.formatted_subtotal"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Notes -->
                    <template x-if="order.notes">
                        <div class="p-2.5 bg-[#E8DCC4]/50 border border-[#CDB48F] rounded-xl text-[11px] text-[#4A3224] mb-3">
                            Catatan: "<span x-text="order.notes"></span>"
                        </div>
                    </template>
                </div>

                <!-- Bottom Action Controls: Direct 1-Click Operations (NO SCAN) -->
                <div class="pt-3 border-t border-dashed border-[#CDB48F] space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="uppercase text-[#6B5646] font-bold">Total Tagihan:</span>
                        <span class="font-display font-bold text-xl text-[#2B1D14]" x-text="order.formatted_total"></span>
                    </div>

                    <!-- Action Buttons Depending on Status -->
                    <div class="space-y-2 pt-1">
                        <!-- Menunggu Bayar Tunai di Kasir -->
                        <template x-if="order.status === 'menunggu' && order.payment_method === 'cash'">
                            <button type="button"
                                    @click="openPayModal(order)"
                                    class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] text-xs uppercase py-3 rounded-xl shadow-sm font-bold transition cursor-pointer">
                                💵 TERIMA PEMBAYARAN KASIR
                            </button>
                        </template>

                        <!-- Sudah Dibayar Online -> Barista Terima Pesanan Langsung -->
                        <template x-if="order.status === 'dibayar'">
                            <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + order.id + '/status'">
                                @csrf
                                <input type="hidden" name="status" value="diterima">
                                <button type="submit"
                                        class="w-full bg-[#4A3224] text-[#F4ECDD] hover:bg-[#2B1D14] text-xs uppercase py-3 rounded-xl shadow-sm font-bold transition cursor-pointer">
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
                                        class="w-full bg-[#4A3224] text-[#F4ECDD] hover:bg-[#2B1D14] text-xs uppercase py-3 rounded-xl shadow-sm font-bold transition cursor-pointer">
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
                                        class="w-full bg-[#7A8467] text-white hover:opacity-90 text-xs uppercase py-3 rounded-xl font-bold shadow-sm transition cursor-pointer">
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
                                        class="w-full bg-[#7A8467] text-white hover:opacity-90 text-xs uppercase py-3 rounded-xl font-bold shadow-sm transition cursor-pointer">
                                    ✓ SERAHKAN KE PELANGGAN (SELESAI)
                                </button>
                            </form>
                        </template>

                        <!-- Quick Utility Links: Print Receipt & Cancel -->
                        <div class="flex items-center space-x-2 pt-1 text-xs">
                            <a :href="order.receipt_url"
                               target="_blank"
                               class="flex-1 text-center bg-[#FFFDF8] border border-[#CDB48F] text-[#4A3224] hover:bg-[#E8DCC4] py-2 rounded-lg text-[11px] uppercase font-bold transition">
                                🖨 Cetak Struk
                            </a>

                            <template x-if="order.status !== 'selesai' && order.status !== 'batal'">
                                <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + order.id + '/status'" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="status" value="batal">
                                    <button type="submit"
                                            :onclick="'return confirm(\'Batalkan pesanan #' + order.order_code + '?\')'"
                                            class="w-full bg-transparent border border-[#B4552F] text-[#B4552F] hover:bg-[#B4552F]/10 py-2 rounded-lg text-[11px] uppercase font-bold transition">
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

    <!-- MODAL PROCESS PAYMENT AT CASHIER (03-DESIGN.md Section 18) -->
    <div x-show="payModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-[#1E1A17]/70 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-[#FFFDF8] border border-[#CDB48F] shadow-warm rounded-2xl w-full max-w-md p-6 sm:p-7"
             @click.outside="payModalOpen = false">

            <div class="flex justify-between items-center border-b border-dashed border-[#CDB48F] pb-3 mb-4">
                <h3 class="font-display font-semibold text-xl text-[#2B1D14]">
                    Proses Pembayaran Kasir
                </h3>
                <button type="button" @click="payModalOpen = false" class="text-[#6B5646] hover:text-[#2B1D14] font-bold text-lg p-1">✕</button>
            </div>

            <template x-if="activeOrder">
                <form method="POST" :action="'{{ url('/kasir/pesanan') }}/' + activeOrder.id + '/bayar'" class="space-y-4">
                    @csrf

                    <div class="bg-[#F4ECDD] p-4 border border-[#CDB48F] rounded-xl text-xs space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-[#6B5646] uppercase font-semibold">Kode Pesanan:</span>
                            <strong class="font-mono text-sm text-[#2B1D14]" x-text="activeOrder.order_code"></strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B5646] uppercase font-semibold">Pelanggan:</span>
                            <strong class="text-[#2B1D14]" x-text="activeOrder.customer_name"></strong>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-dashed border-[#CDB48F] text-sm">
                            <span class="uppercase font-bold text-[#4A3224]">Total Tagihan:</span>
                            <strong class="font-display font-bold text-[#2B1D14] text-lg"
                                    x-text="activeOrder.formatted_total"></strong>
                        </div>
                    </div>

                    <div>
                        <label class="block uppercase text-xs font-bold text-[#4A3224] mb-1.5">
                            Metode Pembayaran
                        </label>
                        <select name="payment_method"
                                class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-xs font-bold text-[#2B1D14] rounded-xl focus:border-[#B5753A]">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="qris">QRIS Kasir</option>
                            <option value="debit">Kartu Debit</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <div>
                        <label class="block uppercase text-xs font-bold text-[#4A3224] mb-1.5">
                            Uang Tunai Diterima (Rp)
                        </label>
                        <input type="number"
                               name="cash_given"
                               x-model.number="cashGiven"
                               :min="activeOrder.total_amount"
                               class="w-full bg-[#FFFDF8] border border-[#CDB48F] font-display font-bold text-xl p-3 text-[#2B1D14] rounded-xl focus:border-[#B5753A]">
                    </div>

                    <div class="p-3.5 bg-[#E8DCC4] border border-[#CDB48F] rounded-xl flex justify-between items-center">
                        <span class="uppercase text-xs font-bold text-[#4A3224]">Uang Kembalian:</span>
                        <span class="font-display font-bold text-2xl text-[#7A8467]"
                              x-text="formatRupiah(calculateChange())"></span>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full bg-[#B5753A] text-white hover:bg-[#B4552F] tracking-wider uppercase text-xs font-bold py-4 rounded-xl shadow-warm transition cursor-pointer">
                            SELESAIKAN & CETAK STRUK
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- MODAL ATUR STOK / TOGGLE KETERSEDIAAN MENU (03-DESIGN.md Section 18) -->
    <div x-show="availabilityModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-[#1E1A17]/70 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-[#FFFDF8] border border-[#CDB48F] shadow-warm rounded-2xl w-full max-w-2xl p-6 sm:p-7 max-h-[85vh] overflow-y-auto"
             @click.outside="availabilityModalOpen = false">

            <div class="flex justify-between items-center border-b border-dashed border-[#CDB48F] pb-3 mb-4">
                <div>
                    <h3 class="font-display font-semibold text-xl text-[#2B1D14]">
                        Atur Ketersediaan Stok Menu
                    </h3>
                    <p class="text-xs text-[#6B5646]">
                        Klik tombol untuk menandai produk Habis atau Tersedia secara instan
                    </p>
                </div>
                <button type="button" @click="availabilityModalOpen = false" class="text-[#6B5646] hover:text-[#2B1D14] font-bold text-lg p-1">✕</button>
            </div>

            <div class="divide-y divide-dashed divide-[#CDB48F]">
                @foreach ($products as $p)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#B5753A] block">{{ $p->category->name }}</span>
                            <strong class="font-display text-sm text-[#2B1D14]">{{ $p->name }}</strong>
                            <div class="text-xs text-[#6B5646]">{{ $p->formatted_price }}</div>
                        </div>

                        <form method="POST" action="{{ route('cashier.product.toggle', $p->id) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3.5 py-1.5 text-xs uppercase rounded-xl transition font-bold shadow-sm {{ $p->is_available ? 'bg-[#7A8467] text-white hover:opacity-90' : 'bg-[#B4552F] text-white hover:opacity-90' }}">
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
