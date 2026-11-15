@extends('layouts.admin')

@section('title', 'Katalog Produk & Menu')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-copper/30 pb-4 gap-4">
        <div>
            <span class="text-xs uppercase font-heading tracking-widest text-copper block">Katalog Menu & Racikan</span>
            <h1 class="text-3xl font-display font-black text-rustic-900 tracking-tight">Produk & Menu</h1>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}"
               class="px-4 py-2.5 bg-copper text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-copper-dark transition inline-flex items-center space-x-2">
                <span>+</span>
                <span>Tambah Produk Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.products.index') }}"
          class="bg-[#FFFDF9] border-2 border-rustic-300 p-4 shadow-rustic-sm flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between text-xs font-heading">
        <div class="flex-1 flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama produk atau deskripsi..."
                       class="w-full bg-parchment-light border-2 border-rustic-300 px-3 py-2 text-xs font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>

            <!-- Category -->
            <div class="sm:w-48">
                <select name="category_id"
                        class="w-full bg-parchment-light border-2 border-rustic-300 px-3 py-2 text-xs font-body text-rustic-900 focus:border-copper focus:outline-none">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Availability Status -->
            <div class="sm:w-40">
                <select name="status"
                        class="w-full bg-parchment-light border-2 border-rustic-300 px-3 py-2 text-xs font-body text-rustic-900 focus:border-copper focus:outline-none">
                    <option value="">-- Status Stok --</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Habis (Sold Out)</option>
                </select>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <button type="submit"
                    class="px-4 py-2 bg-rustic-800 text-parchment font-heading text-xs uppercase tracking-wider font-bold hover:bg-rustic-900 transition">
                Filter
            </button>
            @if (request()->hasAny(['search', 'category_id', 'status']))
                <a href="{{ route('admin.products.index') }}"
                   class="px-3 py-2 border border-rustic-300 text-rustic-600 hover:text-rustic-900 text-xs">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Table of Products -->
    <div class="bg-[#FFFDF9] border-2 border-rustic-300 shadow-rustic-sm overflow-hidden">
        @if ($products->isEmpty())
            <div class="p-8 text-center text-rustic-500 font-heading text-sm">
                Tidak ada produk ditemukan. Coba sesuaikan kata kunci filter atau tambah produk baru.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b-2 border-rustic-800 bg-rustic-100 font-heading uppercase tracking-wider text-rustic-800">
                            <th class="p-3">Produk</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3 text-right">Harga Dasar</th>
                            <th class="p-3 text-center">Varian / Opsi</th>
                            <th class="p-3 text-center">Ketersediaan</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rustic-200">
                        @foreach ($products as $prod)
                            <tr class="hover:bg-rustic-50/70 transition">
                                <td class="p-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-rustic-100 border border-rustic-300 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if ($prod->image)
                                                <img src="{{ $prod->image }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xl">☕</span>
                                            @endif
                                        </div>
                                        <div>
                                            <strong class="font-display text-sm text-rustic-900 block">{{ $prod->name }}</strong>
                                            <span class="text-[11px] font-mono text-rustic-500">{{ $prod->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 font-heading text-rustic-700">
                                    <span class="px-2 py-0.5 bg-rustic-100 border border-rustic-300 rounded text-[11px]">
                                        {{ $prod->category ? $prod->category->name : 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-display font-bold text-copper text-sm">
                                    Rp {{ number_format($prod->price, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-center text-[11px] font-heading text-rustic-600">
                                    @if ($prod->options->count() > 0)
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-900 border border-amber-300 font-bold">
                                            {{ $prod->options->count() }} opsi
                                        </span>
                                    @else
                                        <span class="text-rustic-400">—</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    <form method="POST" action="{{ route('admin.products.toggle', $prod) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                title="Klik untuk mengubah ketersediaan produk"
                                                class="px-2.5 py-1 border font-heading uppercase text-[10px] font-bold transition {{ $prod->is_available ? 'bg-emerald-100 text-emerald-900 border-emerald-400 hover:bg-emerald-200' : 'bg-rose-100 text-rose-900 border-rose-400 hover:bg-rose-200' }}">
                                            {{ $prod->is_available ? '✓ Tersedia' : '✕ Habis' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3 text-right space-x-2">
                                    <a href="{{ route('admin.products.edit', $prod) }}"
                                       class="px-2.5 py-1 bg-rustic-100 text-rustic-800 border border-rustic-300 font-heading text-[11px] font-bold hover:bg-copper hover:text-parchment hover:border-copper transition">
                                        Ubah
                                    </a>

                                    <form method="POST" action="{{ route('admin.products.destroy', $prod) }}" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 bg-brick/10 text-brick border border-brick/40 font-heading text-[11px] font-bold hover:bg-brick hover:text-parchment transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="p-4 border-t border-rustic-200 bg-rustic-50">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
