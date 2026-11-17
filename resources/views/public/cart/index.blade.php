@extends('layouts.public')

@section('title', 'Keranjang Pesanan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">

    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="font-display font-black text-3xl sm:text-4xl text-rustic-900 tracking-tight">
            Keranjang Pesanan Anda
        </h1>
        <div class="text-copper text-sm tracking-widest my-2 select-none">
            ❧ ─────── ✦ ─────── ❧
        </div>
        <p class="font-body italic text-rustic-600 text-sm">
            Periksa kembali pesanan Anda sebelum melanjutkan ke pembayaran
        </p>
    </div>

    @if ($items->isEmpty())
        <!-- Empty Cart State -->
        <div class="bg-[#FFF8F0] border-2 border-dashed border-rustic-400 rounded-sm p-12 text-center shadow-rustic space-y-4">
            <span class="text-5xl select-none">🛒</span>
            <h2 class="font-display font-bold text-2xl text-rustic-900">
                Keranjang Anda Masih Kosong
            </h2>
            <p class="font-body text-sm text-rustic-600 max-w-sm mx-auto">
                Belum ada kopi hangat atau camilan lezat yang Anda masukkan ke keranjang.
            </p>
            <div class="pt-4 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('menu.index') }}"
                   class="bg-rustic-800 text-parchment border-2 border-copper hover:bg-rustic-700 font-heading text-xs uppercase tracking-widest px-6 py-3 shadow-rustic transition">
                    LIHAT MENU KAMI
                </a>
                <a href="{{ route('custom.index') }}"
                   class="bg-transparent text-rustic-800 border-2 border-rustic-700 hover:bg-rustic-100 font-heading text-xs uppercase tracking-widest px-6 py-3 transition">
                    BERKREASI RACIKAN SENDIRI ✦
                </a>
            </div>
        </div>
    @else
        <!-- Cart Items List -->
        <div class="space-y-6">
            <div class="bg-[#FFF8F0] border-2 border-copper rounded-sm shadow-rustic divide-y divide-rustic-200">
                @foreach ($items as $rowId => $item)
                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <!-- Left: Item Info & Options -->
                        <div class="flex-1 space-y-1">
                            <h3 class="font-display font-bold text-lg text-rustic-900">
                                {{ $item['name'] }}
                            </h3>

                            @if (! empty($item['options']))
                                <div class="text-xs font-body text-rustic-600 space-y-0.5">
                                    @if (! empty($item['options']['size']))
                                        <div><strong class="font-heading uppercase text-[10px]">Ukuran:</strong> {{ $item['options']['size'] }}</div>
                                    @endif
                                    @if (! empty($item['options']['sugar']))
                                        <div><strong class="font-heading uppercase text-[10px]">Gula:</strong> {{ $item['options']['sugar'] }}</div>
                                    @endif
                                    @if (! empty($item['options']['ice']))
                                        <div><strong class="font-heading uppercase text-[10px]">Suhu:</strong> {{ $item['options']['ice'] }}</div>
                                    @endif
                                    @if (! empty($item['options']['toppings']) && is_array($item['options']['toppings']))
                                        <div><strong class="font-heading uppercase text-[10px]">Topping:</strong> {{ implode(', ', $item['options']['toppings']) }}</div>
                                    @endif
                                </div>
                            @endif

                            @if (! empty($item['notes']))
                                <div class="text-xs font-body italic text-copper">
                                    Catatan: "{{ $item['notes'] }}"
                                </div>
                            @endif

                            <div class="text-xs font-heading text-rustic-500 pt-1">
                                Satuan: Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Right: Quantity Form & Subtotal & Delete -->
                        <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6">
                            <!-- Qty Stepper Form -->
                            <form method="POST" action="{{ route('cart.update') }}" class="flex items-center border-2 border-rustic-400 rounded-sm bg-parchment">
                                @csrf
                                <input type="hidden" name="row_id" value="{{ $rowId }}">
                                <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                        class="px-2.5 py-1 text-sm font-bold text-rustic-800 hover:bg-rustic-200">
                                    -
                                </button>
                                <span class="px-3 py-1 font-heading text-xs font-bold text-rustic-900">
                                    {{ $item['quantity'] }}
                                </span>
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                        class="px-2.5 py-1 text-sm font-bold text-rustic-800 hover:bg-rustic-200">
                                    +
                                </button>
                            </form>

                            <!-- Subtotal -->
                            <div class="text-right min-w-[100px]">
                                <span class="block text-[10px] font-heading uppercase text-rustic-500">Subtotal</span>
                                <span class="font-display font-bold text-base text-rustic-900">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Remove Button -->
                            <form method="POST" action="{{ route('cart.remove', $rowId) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        title="Hapus Item"
                                        class="text-brick hover:text-red-800 p-1 font-bold text-sm">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary & Actions (Retro Diner Bill) -->
            <div class="bg-parchment-dark border-2 border-rustic-900 rounded-sm p-6 shadow-rustic flex flex-col sm:flex-row items-center justify-between gap-6">
                <!-- Total Amount -->
                <div>
                    <span class="block font-heading text-xs uppercase tracking-widest text-rustic-700 font-bold">
                        ★ TOTAL PEMBAYARAN ★
                    </span>
                    <span class="font-display font-black text-3xl sm:text-4xl text-retro-orange">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')"
                                class="px-4 py-3 border-2 border-rustic-900 text-rustic-900 hover:bg-parchment font-heading font-bold text-xs uppercase tracking-wider transition shadow-rustic-sm">
                            Kosongkan
                        </button>
                    </form>

                    <a href="{{ route('checkout.show') }}"
                       class="flex-1 sm:flex-none text-center bg-retro-orange text-parchment border-2 border-rustic-900 hover:bg-[#d85f30] font-heading font-black tracking-widest uppercase text-xs py-3.5 px-8 shadow-rustic transition duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none">
                        LANJUT KE PEMBAYARAN →
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
