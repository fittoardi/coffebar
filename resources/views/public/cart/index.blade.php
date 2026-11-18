@extends('layouts.public')

@section('title', 'Keranjang Pesanan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
            Keranjang Pesanan
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Periksa kembali pesanan Anda sebelum melanjutkan ke pembayaran.
        </p>
    </div>

    @if ($items->isEmpty())
    <!-- Empty Cart State -->
    <div class="bg-white border border-gray-200 rounded-lg p-12 text-center space-y-4">
        <span class="text-4xl block">🛒</span>
        <h2 class="font-bold text-xl text-gray-900">
            Keranjang Anda Masih Kosong
        </h2>
        <p class="text-sm text-gray-500 max-w-sm mx-auto">
            Belum ada menu yang Anda masukkan ke keranjang.
        </p>
        <div class="pt-2 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('menu.index') }}"
                class="bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium px-5 py-2.5 rounded-md transition">
                Lihat Menu
            </a>
            <a href="{{ route('custom.index') }}"
                class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 text-sm font-medium px-5 py-2.5 rounded-md transition">
                Racik Minuman Sendiri
            </a>
        </div>
    </div>
    @else
    <!-- Cart Items List -->
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
            @foreach ($items as $rowId => $item)
            <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <!-- Left: Item Info & Options -->
                <div class="flex-1 space-y-1">
                    <h3 class="font-bold text-base text-gray-900">
                        {{ $item['name'] }}
                    </h3>

                    @if (! empty($item['options']))
                    <div class="text-xs text-gray-500 space-y-0.5">
                        @if (! empty($item['options']['size']))
                        <div>Ukuran: {{ $item['options']['size'] }}</div>
                        @endif
                        @if (! empty($item['options']['sugar']))
                        <div>Gula: {{ $item['options']['sugar'] }}</div>
                        @endif
                        @if (! empty($item['options']['ice']))
                        <div>Suhu: {{ $item['options']['ice'] }}</div>
                        @endif
                        @if (! empty($item['options']['toppings']) && is_array($item['options']['toppings']))
                        <div>Topping: {{ implode(', ', $item['options']['toppings']) }}</div>
                        @endif
                    </div>
                    @endif

                    @if (! empty($item['notes']))
                    <div class="text-xs text-blue-600">
                        Catatan: "{{ $item['notes'] }}"
                    </div>
                    @endif

                    <div class="text-xs text-gray-500 pt-1">
                        Satuan: Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </div>
                </div>

                <!-- Right: Quantity Form & Subtotal & Delete -->
                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6">
                    <!-- Qty Stepper Form -->
                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center border border-gray-300 rounded-md bg-white">
                        @csrf
                        <input type="hidden" name="row_id" value="{{ $rowId }}">
                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                            class="px-2.5 py-1 text-sm font-bold text-gray-600 hover:bg-gray-100">
                            -
                        </button>
                        <span class="px-3 py-1 text-xs font-semibold text-gray-900">
                            {{ $item['quantity'] }}
                        </span>
                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                            class="px-2.5 py-1 text-sm font-bold text-gray-600 hover:bg-gray-100">
                            +
                        </button>
                    </form>

                    <!-- Subtotal -->
                    <div class="text-right min-w-[100px]">
                        <span class="block text-[10px] text-gray-400 uppercase">Subtotal</span>
                        <span class="font-bold text-sm text-gray-900">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Remove Button -->
                    <form method="POST" action="{{ route('cart.remove', $rowId) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            title="Hapus Item"
                            class="text-red-500 hover:text-red-700 p-1 text-sm font-bold">
                            ✕
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Summary & Actions -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <!-- Total Amount -->
            <div>
                <span class="block text-xs uppercase text-gray-500 font-medium">
                    Total Pembayaran
                </span>
                <span class="font-bold text-2xl sm:text-3xl text-gray-900">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')"
                        class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-medium rounded-md transition">
                        Kosongkan
                    </button>
                </form>

                <a href="{{ route('checkout.show') }}"
                    class="flex-1 sm:flex-none text-center bg-blue-600 text-white hover:bg-blue-700 font-medium text-xs uppercase tracking-wider py-2.5 px-6 rounded-md transition">
                    Lanjut ke Pembayaran →
                </a>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
